<?php

namespace AdvancedNewsTicker\Helper;

class Fns {

	public static function doing_it_wrong( $function, $message, $version ) {
		// @codingStandardsIgnoreStart
		$message .= ' Backtrace: ' . wp_debug_backtrace_summary();
		_doing_it_wrong( $function, $message, $version );
	}

	/**
	 * @param        $template_name
	 * @param string $template_path
	 * @param string $default_path
	 *
	 * @return mixed|void
	 */
	public static function locate_template( $template_name, $template_path = '', $default_path = '' ) {
		$template_name = $template_name . ".php";

		if ( ! $template_path ) {
			$template_path = 'advanced-news-ticker/';
		}

		if ( ! $default_path ) {
			$default_path = untrailingslashit( ADVANCED_NEWS_TICKER_BASE_DIR ) . '/templates/';
		}

		$template_files = trailingslashit( $template_path ) . $template_name;

		$template = locate_template( apply_filters( 'ant_locate_template_files', $template_files, $template_name, $template_path, $default_path ) );

		// Get default template/.
		if ( ! $template ) {
			$template = trailingslashit( $default_path ) . $template_name;
		}

		return apply_filters( 'ant_locate_template', $template, $template_name );
	}

	/**
	 * Template Content
	 *
	 * @param string $template_name Template name.
	 * @param array $args Arguments. (default: array).
	 * @param string $template_path Template path. (default: '').
	 * @param string $default_path Default path. (default: '').
	 */
	public static function get_template( $template_name, $args = null, $template_path = '', $default_path = '' ) {

		if ( ! empty( $args ) && is_array( $args ) ) {
			extract( $args ); // @codingStandardsIgnoreLine
		}

		$located = self::locate_template( $template_name, $template_path, $default_path );


		if ( ! file_exists( $located ) ) {
			// translators: %s template
			self::doing_it_wrong( __FUNCTION__, sprintf( __( '%s does not exist.', 'classified-listing' ), '<code>' . $located . '</code>' ), '1.0' );

			return;
		}

		// Allow 3rd party plugin filter template file from their plugin.
		$located = apply_filters( 'ant_get_template', $located, $template_name, $args );

		do_action( 'ant_before_template_part', $template_name, $located, $args );

		include $located;

		do_action( 'ant_after_template_part', $template_name, $located, $args );
	}

	/**
	 * Get Asset URL
	 * @return string
	 */
	public static function get_assets_url( $path = null ) {
		return ADVANCED_NEWS_TICKER_BASE_URL . 'assets/' . $path;
	}

	/**
	 * Get all Post Type
	 * @return array
	 */
	public static function get_post_types( $exc = '' ) {
		$post_types = get_post_types(
			[
				'public' => true,
			],
			'objects'
		);
		$post_types = wp_list_pluck( $post_types, 'label', 'name' );

		$exclude = [
			'attachment',
			'revision',
			'nav_menu_item',
			'elementor_library',
			'tpg_builder',
			'e-landing-page',
			'elementor-raw_addons',
			'e-floating-buttons'
		];
		if ( $exc ) {
			$exclude = array_merge( $exclude, $exc );
		}

		foreach ( $exclude as $ex ) {
			unset( $post_types[ $ex ] );
		}

		return $post_types;
	}


	/**
	 * Link Attribute
	 *
	 * @param $link
	 *
	 * @return string
	 */

	public static function link_attr( $link, $echo = true ) {
		if ( empty( $link['url'] ) ) {
			return '';
		}

		$attr = "href={$link['url']}";
		$attr .= ! empty( $link['is_external'] ) ? " target=_blank" : '';
		$attr .= ! empty( $link['nofollow'] ) ? " rel=nofollow" : '';
		if ( ! $echo ) {
			return esc_attr( $attr );
		}
		echo esc_attr( $attr );
	}


	/**
	 * Post Query Arguments
	 *
	 * @param $data
	 *
	 * @return array
	 */
	public static function query_args( $data ) {
		$_post_type = ! empty( $data['post_type'] ) ? esc_html( $data['post_type'] ) : 'post';
		$post_type  = self::available_post_type( $_post_type );
		$args       = [
			'post_type'           => $post_type,
			'ignore_sticky_posts' => 1,
			'post_status'         => 'publish',
		];

		$args['posts_per_page'] = $data['post_limit'] ?? 5;

		$_taxonomies = get_object_taxonomies( $post_type, 'objects' );

		foreach ( $_taxonomies as $index => $object ) {
			if ( in_array( $object->name, Fns::get_excluded_taxonomy() ) ) {
				continue;
			}

			$setting_key = $object->name . '_ids';
			if ( ! empty( $data[ $setting_key ] ) ) {
				//phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
				$args['tax_query'][] = [
					'taxonomy' => $object->name,
					'field'    => 'term_id',
					'terms'    => $data[ $setting_key ],
				];
			}
		}

		/*if ( ! empty( $data['category'] ) || ! empty( $data['post_tag'] ) ) {
			$args['tax_query']['relation'] = $data['tax_relation'];

			if ( ! empty( $data['category'] ) ) {
				$args['tax_query'][] = [
					'taxonomy' => 'category',
					'field'    => 'id',
					'terms'    => $data['category'],
				];
			}

			if ( ! empty( $data['post_tag'] ) ) {
				$args['tax_query'][] = [
					'taxonomy' => 'post_tag',
					'field'    => 'id',
					'terms'    => $data['post_tag'],
				];
			}

		}*/

		if ( ! empty( $data['tags'] ) ) {
			$args['tag_slug__in'] = $data['tags'];
		}

		$orderBy = ! empty ( $data['orderby'] ) ? $data['orderby'] : 'date';

		$args['orderby'] = $orderBy;

		if ( in_array( $orderBy, [ 'meta_value', 'meta_value_num' ] ) && ! empty( $data['meta_key'] ) ) {
			$args['meta_key'] = esc_html( $data['meta_key'] );
		}

		if ( ! empty( $data['order'] ) ) {
			$args['order'] = esc_html( $data['order'] );
		}

		$post_types = Fns::get_post_types();
		foreach ( $post_types as $post_type => $post_label ) {
			$postIds = $data[$post_type . '_ids'];
			if ( ! empty( $postIds ) ) {
				$args['post__in'] = $postIds;
			}
		}

		if ( ! empty( $data['exclude'] ) ) {
			$args['post__not_in'] = $data['exclude'];
		}

		if ( $data['offset'] ) {
			$args['offset'] = $data['offset'];
		}

		return $args;
	}


	/**
	 * Print HTML with wp_kses
	 *
	 * @param $html
	 * @param $context
	 * @param $echo
	 *
	 * @return string|void
	 */
	public static function html( $html, $context = 'plain', $echo = true ) {
		// Define reusable tag configurations
		$base_tags = [
			'a'      => [ 'href' => [], 'class' => [], 'rel' => [], 'title' => [], 'target' => [] ],
			'b'      => [],
			'img'    => [ 'src' => [], 'alt' => [], 'class' => [], 'width' => [], 'height' => [], 'srcset' => [] ],
			'p'      => [ 'class' => [] ],
			'span'   => [ 'class' => [], 'style' => [], 'title' => [] ],
			'strong' => [],
			'br'     => [],
		];

		// Context-specific tag adjustments
		$tags_by_context = [
			'plain'   => $base_tags,
			'link'    => [ 'a' => $base_tags['a'] ],
			'image'   => [ 'img' => $base_tags['img'] ],
			'title'   => $base_tags,
			'default' => array_merge( $base_tags, [
				'abbr'       => [ 'title' => [] ],
				'blockquote' => [ 'cite' => [] ],
				'code'       => [],
				'del'        => [ 'datetime' => [], 'title' => [] ],
				'div'        => [ 'class' => [], 'style' => [], 'title' => [], 'id' => [] ],
				'h1'         => [ 'class' => [], 'style' => [], 'title' => [], 'id' => [] ],
				'h2'         => [ 'class' => [], 'style' => [], 'title' => [], 'id' => [] ],
				'h3'         => [ 'class' => [], 'style' => [], 'title' => [], 'id' => [] ],
				'h4'         => [ 'class' => [], 'style' => [], 'title' => [], 'id' => [] ],
				'h5'         => [ 'class' => [], 'style' => [], 'title' => [], 'id' => [] ],
				'h6'         => [ 'class' => [], 'style' => [], 'title' => [], 'id' => [] ],
				'i'          => [ 'class' => [] ],
				'li'         => [ 'class' => [] ],
				'ol'         => [ 'class' => [] ],
				'ul'         => [ 'class' => [] ],
				'iframe'     => [
					'class'                 => [],
					'id'                    => [],
					'name'                  => [],
					'src'                   => [],
					'title'                 => [],
					'frameBorder'           => [],
					'width'                 => [],
					'height'                => [],
					'scrolling'             => [],
					'allow'                 => [],
					'allowvr'               => [],
					'allowFullScreen'       => [],
					'webkitallowfullscreen' => [],
					'mozallowfullscreen'    => [],
					'loading'               => []
				],
			] ),
		];

		// Determine tags for the given context
		$tags = $tags_by_context[ $context ] ?? $tags_by_context['default'];

		// If echo is false, return the sanitized HTML
		if ( ! $echo ) {
			return wp_kses( $html, $tags );
		}

		// Echo the sanitized HTML
		echo wp_kses( $html, $tags );
	}

	/**
	 * Get Available Post Type
	 *
	 * @param $post_type
	 *
	 * @return mixed|string
	 */
	public static function available_post_type( $post_type ) {

		$post_type_object = get_post_type_object( $post_type );

		if ( ! $post_type_object ) {
			return 'post';
		}

		if ( $post_type_object->public ) {
			return $post_type;
		}

		if ( is_user_logged_in() ) {
			$user          = wp_get_current_user();
			$roles         = (array) $user->roles;
			$allowed_roles = [ 'administrator', 'editor', 'author', 'contributor' ];

			if ( array_intersect( $roles, $allowed_roles ) ) {
				return $post_type;
			}
		}

		return 'post';
	}

	/**
	 * Get Excluded Taxonomy
	 *
	 * @return string[]
	 */
	public static function get_excluded_taxonomy() {
		return [
			'post_format',
			'nav_menu',
			'link_category',
			'wp_theme',
			'elementor_library_type',
			'elementor_library_type',
			'elementor_library_category',
			'product_visibility',
			'product_shipping_class',
		];
	}

	public static function get_categories_by_id( $cat = 'category' ) {
		$terms = get_terms(
			[
				'taxonomy'   => $cat,
				'hide_empty' => true,
			]
		);

		$options = [];
		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
			foreach ( $terms as $term ) {
				$options[ $term->term_id ] = $term->name;
			}
		}

		return $options;
	}

}


