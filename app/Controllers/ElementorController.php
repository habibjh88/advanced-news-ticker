<?php
/**
 * @author  habibjh88
 * @since   1.0
 * @version 1.0
 */

namespace AdvancedNewsTicker\Controllers;

use Elementor\Plugin;
use AdvancedNewsTicker\Elementor\Controls\ImageSelectorControl;
use AdvancedNewsTicker\Elementor\Controls\Select2AjaxControl;
use AdvancedNewsTicker\Helper\Fns;
use AdvancedNewsTicker\Traits\SingletonTraits;
use AdvancedNewsTicker\Modules\IconList;
use AdvancedNewsTicker\Elementor\Addons\NewsTicker;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * ElementorController Class
 */
class ElementorController {
	use SingletonTraits;

	public function __construct() {
		add_action( 'elementor/widgets/register', [ $this, 'register_widget' ] );
		add_action( 'elementor/elements/categories_registered', [ $this, 'widget_category' ] );
		add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'editor_style' ] );
		add_action( 'elementor/controls/register', [ $this, 'register_new_control' ] );
		add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'editor_scripts' ] );
		add_action( 'wp_ajax_ant_select2_object_search', [ $this, 'select2_ajax_posts_filter_autocomplete' ] );
		add_action( 'wp_ajax_nopriv_ant_select2_object_search', [ $this, 'select2_ajax_posts_filter_autocomplete' ] );
		// Select2 ajax save data.
		add_action( 'wp_ajax_ant_select2_get_title', [ $this, 'select2_ajax_get_posts_value_titles' ] );
		add_action( 'wp_ajax_nopriv_ant_select2_get_title', [ $this, 'select2_ajax_get_posts_value_titles' ] );
		add_action( 'elementor/icons_manager/additional_tabs', [ $this, 'fontello_support' ] );
	}


	/**
	 * Editor JS.
	 *
	 * @return void
	 */
	public function editor_scripts() {
		$version = ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ? time() : ADVANCED_NEWS_TICKER;

		wp_enqueue_script(
			'ant-editor-script',
			Fns::get_assets_url( 'js/el-editor.js' ),
			[
				'jquery',
				'elementor-editor',
				'jquery-elementor-select2',
			],
			$version,
			true
		);
	}

	/**
	 * Register Controls
	 *
	 * @param $controls_manager
	 *
	 * @return void
	 */
	public function register_new_control( $controls_manager ) {
		$controls_manager->register( new ImageSelectorControl() );
		$controls_manager->register( new Select2AjaxControl() );
	}

	/**
	 * Register Elementor Widget.
	 * Just put the widget class reference here
	 * @return void
	 */
	public function register_widget() {
		$widgets = apply_filters( 'raw_addons_elemetor_widgets', [
			NewsTicker::class,
		] );
		foreach ( $widgets as $class ) {
			Plugin::instance()->widgets_manager->register( new $class );
		}
	}

	/**
	 * Elementor Editor Style
	 * @return void
	 */
	public function editor_style() {
		$icon         = Fns::get_assets_url( 'images/icon.svg' );
		$editor_style = '.elementor-element .icon .ant-el-custom{content: url(' . $icon . ');width: 34px;}';
		$editor_style = '.elementor-control.elementor-control-type-heading .elementor-control-title{color:#93003f;font-size: 14px;}';

		wp_add_inline_style( 'elementor-editor', $editor_style );
	}

	/**
	 * Register Elementor category
	 *
	 * @param $elements_manager
	 *
	 * @return void
	 */
	public function widget_category( $elements_manager ) {
		$id                = ADVANCED_NEWS_TICKER_PREFIX . '-widgets';
		$categories[ $id ] = [
			'title' => __( 'Raw Addons', 'advanced-news-ticker' ),
			'icon'  => 'fa fa-plug',
		];

		$get_all_categories = $elements_manager->get_categories();
		$categories         = array_merge( $categories, $get_all_categories );
		$set_categories     = function ( $categories ) {
			$this->categories = $categories;
		};

		$set_categories->call( $elements_manager, $categories );
	}

	/**
	 * Adding custom icon to icon control in Elementor
	 */
	public function fontello_support( $tabs = [] ) {
		// Append new icons
		$tabs['ant-icons'] = [
			'name'          => 'raw-icons',
			'label'         => esc_html__( 'Raw Icons', 'advanced-news-ticker' ),
			'labelIcon'     => 'fab fa-elementor',
			'prefix'        => '',
			'displayPrefix' => '',
			'url'           => ADVANCED_NEWS_TICKER_BASE_URL . '/assets/css/raw-icons.css',
			'icons'         => array_keys( IconList::raw_icons() ),
			'ver'           => '1.0',
		];

		return $tabs;
	}

	/**
	 * Ajax callback for ant-select2
	 *
	 * @param $post_type
	 * @param $limit
	 * @param $search
	 * @param $paged
	 *
	 * @return array
	 */
	public function get_query_data( $post_type = 'any', $limit = 10, $search = '', $paged = 1 ) {
		global $wpdb;
		$where = '';
		$data  = [];

		if ( - 1 == $limit ) {
			$limit = '';
		} elseif ( 0 == $limit ) {
			$limit = 'limit 0,1';
		} else {
			$offset = 0;
			if ( $paged ) {
				$offset = ( $paged - 1 ) * $limit;
			}
			$limit = $wpdb->prepare( ' limit %d, %d', esc_sql( $offset ), esc_sql( $limit ) );
		}

		if ( 'any' === $post_type ) {
			$in_search_post_types = get_post_types( [ 'exclude_from_search' => false ] );
			if ( empty( $in_search_post_types ) ) {
				$where .= ' AND 1=0 ';
			} else {
				$where .= " AND {$wpdb->posts}.post_type IN ('" . join(
						"', '",
						array_map( 'esc_sql', $in_search_post_types )
					) . "')";
			}
		} elseif ( ! empty( $post_type ) ) {
			$where .= $wpdb->prepare( " AND {$wpdb->posts}.post_type = %s", esc_sql( $post_type ) );
		}

		if ( ! empty( $search ) ) {
			$where .= $wpdb->prepare( " AND {$wpdb->posts}.post_title LIKE %s", '%' . esc_sql( $search ) . '%' );
		}

		$query   = "select post_title,ID  from $wpdb->posts where post_status = 'publish' {$where} {$limit}";
		$results = $wpdb->get_results( $query ); //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching

		if ( ! empty( $results ) ) {
			foreach ( $results as $row ) {
				$data[ $row->ID ] = $row->post_title . ' [#' . $row->ID . ']';
			}
		}

		return $data;
	}

	/**
	 * Ajax callback for ant-select2
	 *
	 * @return void
	 */
	public function select2_ajax_posts_filter_autocomplete() {

		check_ajax_referer( 'ant-select2-nonce' );
		$query_per_page = 15;
		$post_type      = 'post';
		$source_name    = 'post_type';
		$paged          = ! empty( $_POST['page'] ) ? sanitize_text_field( wp_unslash( $_POST['page'] ) ) : 1;

		if ( ! empty( $_POST['post_type'] ) ) {
			$post_type = sanitize_text_field( sanitize_text_field( wp_unslash( $_POST['post_type'] ) ) );
		}

		if ( ! empty( $_POST['source_name'] ) ) {
			$source_name = sanitize_text_field( wp_unslash( $_POST['source_name'] ) );
		}

		$search  = ! empty( $_POST['search'] ) ? sanitize_text_field( wp_unslash( $_POST['search'] ) ) : '';
		$results = $post_list = [];
		switch ( $source_name ) {
			case 'taxonomy':
				$args = [
					'hide_empty' => false,
					'orderby'    => 'name',
					'order'      => 'ASC',
					'search'     => $search,
					'number'     => '5',
				];

				if ( $post_type !== 'all' ) {
					$args['taxonomy'] = $post_type;
				}

				$post_list = wp_list_pluck( get_terms( $args ), 'name', 'term_id' );
				break;
			case 'user':
				$users = [];

				foreach ( get_users( [ 'search' => "*{$search}*" ] ) as $user ) {
					$user_id           = $user->ID;
					$user_name         = $user->display_name;
					$users[ $user_id ] = $user_name;
				}

				$post_list = $users;
				break;
			default:
				$post_list = $this->get_query_data( $post_type, $query_per_page, $search, $paged );
		}

		$pagination = true;
		if ( count( $post_list ) < $query_per_page ) {
			$pagination = false;
		}
		if ( ! empty( $post_list ) ) {
			foreach ( $post_list as $key => $item ) {
				$results[] = [
					'text' => $item,
					'id'   => $key,
				];
			}
		}
		wp_send_json(
			[
				'results'    => $results,
				'pagination' => [ 'more' => $pagination ],
			]
		);
	}


	/**
	 * Ajax callback for ant-select2
	 *
	 * @return void
	 */
	public function select2_ajax_get_posts_value_titles() {
		check_ajax_referer( 'ant-select2-nonce' );
		if ( empty( $_POST['id'] ) ) {
			wp_send_json_error( [] );
		}

		//phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		if ( empty( array_filter( wp_unslash( $_POST['id'] ) ) ) ) {
			wp_send_json_error( [] );
		}
		$ids         = array_map( 'intval', $_POST['id'] );
		$source_name = ! empty( $_POST['source_name'] ) ? sanitize_text_field( wp_unslash( $_POST['source_name'] ) ) : '';

		switch ( $source_name ) {
			case 'taxonomy':
				$args = [
					'hide_empty' => false,
					'orderby'    => 'name',
					'order'      => 'ASC',
					'include'    => implode( ',', $ids ),
				];

				if ( ! empty($_POST['post_type']) && $_POST['post_type'] !== 'all' ) {
					$args['taxonomy'] = sanitize_text_field( wp_unslash( $_POST['post_type'] ) );
				}

				$response = wp_list_pluck( get_terms( $args ), 'name', 'term_id' );
				break;
			case 'user':
				$users = [];

				foreach ( get_users( [ 'include' => $ids ] ) as $user ) {
					$user_id           = $user->ID;
					$user_name         = $user->display_name . '-' . $user->ID;
					$users[ $user_id ] = $user_name;
				}

				$response = $users;
				break;
			default:
				$post_info = get_posts(
					[
						'post_type' => ! empty($_POST['post_type']) ? sanitize_text_field( wp_unslash( $_POST['post_type'] ) ) : 'post',
						'include'   => implode( ',', $ids ),
					]
				);
				$response  = wp_list_pluck( $post_info, 'post_title', 'ID' );
		}

		if ( ! empty( $response ) ) {
			wp_send_json_success( [ 'results' => $response ] );
		} else {
			wp_send_json_error( [] );
		}
	}
}