<?php
/**
 * @author  DevofWP
 * @since   1.0
 * @version 1.0
 */

namespace AdvancedNewsTicker\Hooks;

use AdvancedNewsTicker\Traits\SingletonTraits;

class ActionHooks {
	use SingletonTraits;

	public function __construct() {
		add_action( 'raw_addons_daily_scheduled_events', [ __CLASS__, 'daily_scheduled_events' ] );
		add_filter( 'wp_head', [ __CLASS__, 'set_post_view_count' ], 9999 );
		add_action( 'raw_addon_before_post_grid', [ __CLASS__, 'raw_addon_before_post_grid' ] );
		add_action( 'raw_addon_after_post_grid', [ __CLASS__, 'raw_addon_after_post_grid' ] );
	}

	public static function raw_addon_before_post_grid( $data ) {
		$layout = $data['layout'] ?? '';
		if ( str_contains( $layout, 'hover' ) ) {
			add_filter( 'raw_addon_can_show_post_thumbnail', '__return_true' );
		}
	}

	public static function raw_addon_after_post_grid( $data ) {
		$layout = $data['layout'] ?? '';
		if ( str_contains( $layout, 'hover' ) ) {
			remove_filter( 'raw_addon_can_show_post_thumbnail', '__return_false' );
		}
	}

	public static function daily_scheduled_events() {
		try {
			global $wpdb;
			//phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
			$expired = $wpdb->get_col( "SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE '_transient_timeout%' AND option_value < UNIX_TIMESTAMP()" );

			foreach ( $expired as $transient ) {
				$key = str_replace( '_transient_timeout_raw_addons_cache_', 'raw_addons_cache_', $transient );
				delete_transient( $key );
			}
		} catch ( \Exception $e ) {
		}
	}

	/**
	 * Set view count
	 *
	 * @param string $content Content.
	 *
	 * @return string
	 */
	public static function set_post_view_count( $content ) {
		if ( is_single() ) {
			$post_id = get_the_ID();


			if ( ! $post_id && is_admin() ) {
				return $content;
			}

			$user_ip = sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ); // retrieve the current IP address of the visitor.
			$key     = 'raw_addons_cache_' . $user_ip . '_' . $post_id;
			$value   = [ $user_ip, $post_id ];
			$visited = get_transient( $key );

			if ( false === $visited ) {
				// set_transient( $key, $value, HOUR_IN_SECONDS * 12 ); // store the unique key, Post ID & IP address for 12 hours if it does not exist.
				set_transient( $key, $value, HOUR_IN_SECONDS * 12 ); // store the unique key, Post ID & IP address for 12 hours if it does not exist.

				// now run post views function.
				$count_key = 'ant_post_views';
				$count     = get_post_meta( $post_id, $count_key, true );

				if ( '' == $count ) {
					update_post_meta( $post_id, $count_key, 1 );
				} else {
					$count = absint( $count );
					$count ++;

					update_post_meta( $post_id, $count_key, $count );
				}
			}
		}

		return $content;
	}

}