<?php

namespace AdvancedNewsTicker\Controllers;

use AdvancedNewsTicker\Traits\SingletonTraits;

/**
 * Enqueue.
 */
class ScriptController {

	use SingletonTraits;

	/**
	 * register default hooks and actions for WordPress
	 *
	 * @return
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ], 1 );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ], 99 );
		add_action( 'elementor/frontend/after_enqueue_scripts', [ $this, 'enqueue_scripts' ], 999 );
		add_action( 'admin_enqueue_scripts', [ __CLASS__, 'load_styles_and_scripts' ] );
		add_action( 'admin_enqueue_scripts', [ __CLASS__, 'load_meta_scripts' ] );
	}

	public static function get_version() {
		return WP_DEBUG ? time() : ADVANCED_NEWS_TICKER;
	}

	/**
	 * Enqueue Scripts
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		$elementor_page = get_post_meta( get_the_ID(), '_elementor_edit_mode', TRUE );

		$script_debug = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG;
		$scripts = [
			[
				'handle'  => 'newsticker',
				'src'     => ADVANCED_NEWS_TICKER_BASE_URL . 'assets/js/lib/newsticker.js',
				'deps'    => '',
				'footer'  => TRUE,
				'enqueue' => TRUE,
			],
			[
				'handle'  => 'advanced-news-ticker-main',
				'src'     => ADVANCED_NEWS_TICKER_BASE_URL . "assets/js/scripts.js",
				'deps'    => [ 'jquery', 'imagesloaded' ],
				'footer'  => TRUE,
				'enqueue' => TRUE,
			],
		];

		if ( $script_debug ) {
			//All JS register and enqueue
			foreach ( $scripts as $script ) {
				wp_register_script( $script['handle'], $script['src'], $script['deps'], isset( $script['version'] ) ? $script['version'] : self::get_version(), $script['footer'] );
				if ( $script['enqueue'] ) {
					wp_enqueue_script( $script['handle'] );
				}
			}
		} else {
			//Frontend js file (all in one)
			wp_enqueue_script( 'advanced-news-ticker-main', ADVANCED_NEWS_TICKER_BASE_URL . 'assets/js/frontend/frontend.min.js', [
				'jquery',
				'imagesloaded',
			], self::get_version(), TRUE );
		}

		//CSS load
		if ( ! advancedNewsTicker()->has_pro() ) {
			wp_enqueue_style( 'advanced-news-ticker', ADVANCED_NEWS_TICKER_BASE_URL . "assets/css/style.css", '', self::get_version() );
		}

		//Localize Script for 'advanced-news-ticker-main' js
		wp_localize_script(
			'advanced-news-ticker-main',
			'AntObj',
			[
				'isRtl'   => is_rtl(),
				'ajaxURL' => admin_url( 'admin-ajax.php' ),
				'hasPro'  => advancedNewsTicker()->has_pro(),
				'nonceId' => advancedNewsTicker()->nonceId,
				'nonce'   => wp_create_nonce( advancedNewsTicker()->nonceText ),
			]
		);
	}


	/**
	 * Load necessary CSS and JS
	 *
	 * @return void
	 */
	public static function load_styles_and_scripts( $screen ) {
		/*wp_localize_script(
			'rtrb-admin-block-settings',
			'rtrbAdminSettings',
			array(
				'all_blocks' => BlockLists::get_all_rtrb_blocks(),
				'ajax_url'   => admin_url( 'admin-ajax.php' ),
				'nonce'      => wp_create_nonce( 'rtrb-save-admin-settings' ),
			)
		);*/

		//Load Settings assets
		if ( 'toplevel_page_advanced-news-ticker' == $screen ) {
			wp_enqueue_style( 'wp-components' );
			wp_enqueue_style( 'wp-editor' );
			wp_enqueue_script(
				'raw-settings',
				ADVANCED_NEWS_TICKER_BASE_URL . 'assets/js/admin/settings.js',
				[
					'wp-block-editor',
					'wp-blocks',
					'wp-components',
					'wp-element',
					'wp-i18n',
					'jquery',
					'wp-util',
				],
				NULL,
				TRUE
			);

			wp_enqueue_script(
				'rtrb-admin-settings',
				ADVANCED_NEWS_TICKER_BASE_URL . 'assets/js/admin/rtrb-admin-settings.js',
				[ 'jquery' ],
				self::get_version(),
				TRUE
			);

			$rawParams = apply_filters(
				'raw_localize_settings_params',
				[
					'isAdmin'    => is_admin(),
					'hasPro'     => advancedNewsTicker()->has_pro(),
					'options'    => [],
					'ajaxurl'    => esc_url( admin_url( 'admin-ajax.php' ) ),
					'nonceId'    => advancedNewsTicker()->nonceId,
					'nonce'      => wp_create_nonce( advancedNewsTicker()->nonceText ),
					'all_blocks' => self::get_all_rtrb_blocks(),
				]
			);

			wp_localize_script( 'raw-settings', 'rtrbAdminSettings', $rawParams );

			wp_enqueue_style(
				'raw-settings',
				ADVANCED_NEWS_TICKER_BASE_URL . 'assets/css/admin/settings.css',
				'',
				NULL
			);
		}
	}

	public static function load_meta_scripts() {
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_style( 'raw-meta-style', ADVANCED_NEWS_TICKER_BASE_URL . 'assets/css/admin/admin.css', [], self::get_version() ); // only datepicker

		wp_enqueue_script( 'wp-color-picker' );
		wp_enqueue_media();

		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_script( 'jquery-timepicker', ADVANCED_NEWS_TICKER_BASE_URL . 'assets/js/lib/jquery.timepicker.js', [ 'jquery' ], self::get_version() );
		wp_enqueue_script( 'select2', ADVANCED_NEWS_TICKER_BASE_URL . 'assets/js/lib/select2.min.js', [ 'jquery' ], self::get_version() );

		wp_enqueue_script(
			'raw-admin-script',
			ADVANCED_NEWS_TICKER_BASE_URL . 'assets/js/admin/admin.js',
			[ 'jquery', 'jquery-ui-core', 'wp-color-picker', 'jquery-ui-datepicker' ],
			self::get_version()
		);
		wp_localize_script(
			'raw-admin-script',
			'rawFramwork',
			[
				'nonce'    => wp_create_nonce( 'wp_rest' ),
				'ajaxurl'  => admin_url( 'admin-ajax.php' ),
				'rest_url' => esc_url_raw( rest_url() ),
			]
		);
	}

	public static function get_all_rtrb_blocks() {
		$all_blocks = get_option( 'rtrb_all_blocks' );

		if ( empty( $all_blocks ) ) {
			return self::get_rtrb_blocks();
		}
		if ( count( self::get_rtrb_blocks() ) > count( $all_blocks ) ) {
			return array_merge( self::get_rtrb_blocks(), $all_blocks );
		}

		return $all_blocks;
	}

	public static function get_rtrb_blocks() {
		$default_blocks = [
			'accordion'        => [
				'label'      => __( 'Accordion', 'radius-blocks' ),
				'value'      => 'accordion',
				'visibility' => 'true',
			],
			'advanced_heading' => [
				'label'      => __( 'Advanced Heading', 'radius-blocks' ),
				'value'      => 'advanced_heading',
				'visibility' => 'true',
			],
			'button'           => [
				'label'      => __( 'Button', 'radius-blocks' ),
				'value'      => 'button',
				'visibility' => 'true',
			],
			'call_to_action'   => [
				'label'      => __( 'Call To Action', 'radius-blocks' ),
				'value'      => 'call_to_action',
				'visibility' => 'true',
			],

			'countdown' => [
				'label'      => __( 'Countdown', 'radius-blocks' ),
				'value'      => 'countdown',
				'visibility' => 'true',
			],

			'counter' => [
				'label'      => __( 'Counter', 'radius-blocks' ),
				'value'      => 'counter',
				'visibility' => 'true',
			],

			'dual_button' => [
				'label'      => __( 'Dual Button', 'radius-blocks' ),
				'value'      => 'dual_button',
				'visibility' => 'true',
			],

			'faq' => [
				'label'      => __( 'FAQ', 'radius-blocks' ),
				'value'      => 'faq',
				'visibility' => 'true',
			],

			'flipbox' => [
				'label'      => __( 'Flipbox', 'radius-blocks' ),
				'value'      => 'flipbox',
				'visibility' => 'true',
			],

			'gradient_heading' => [
				'label'      => __( 'Gradient Heading', 'radius-blocks' ),
				'value'      => 'gradient_heading',
				'visibility' => 'true',
			],

			'iconbox' => [
				'label'      => __( 'Icon Box', 'radius-blocks' ),
				'value'      => 'iconbox',
				'visibility' => 'true',
			],

			'notice' => [
				'label'      => __( 'Notice', 'radius-blocks' ),
				'value'      => 'notice',
				'visibility' => 'true',
			],

			'image_comparison' => [
				'label'      => __( 'Image Comparison', 'radius-blocks' ),
				'value'      => 'image_comparison',
				'visibility' => 'true',
			],

			'image_gallery' => [
				'label'      => __( 'Image Gallery', 'radius-blocks' ),
				'value'      => 'image_gallery',
				'visibility' => 'true',
			],

			'infobox' => [
				'label'      => __( 'Infobox', 'radius-blocks' ),
				'value'      => 'infobox',
				'visibility' => 'true',
			],

			'logo_grid'     => [
				'label'      => __( 'Logo Grid', 'radius-blocks' ),
				'value'      => 'logo_grid',
				'visibility' => 'true',
			],
			'post_grid'     => [
				'label'      => __( 'Post Grid', 'radius-blocks' ),
				'value'      => 'post_grid',
				'visibility' => 'true',
			],
			'post_list'     => [
				'label'      => __( 'Post List', 'radius-blocks' ),
				'value'      => 'post_list',
				'visibility' => 'true',
			],
			'pricing_table' => [
				'label'      => __( 'Pricing Table', 'radius-blocks' ),
				'value'      => 'pricing_table',
				'visibility' => 'true',
			],
			'row'           => [
				'label'      => __( 'Row', 'radius-blocks' ),
				'value'      => 'row',
				'visibility' => 'true',
			],
			'team'          => [
				'label'      => __( 'Team Member', 'radius-blocks' ),
				'value'      => 'team',
				'visibility' => 'true',
			],
			'testimonial'   => [
				'label'      => __( 'Testimonial', 'radius-blocks' ),
				'value'      => 'testimonial',
				'visibility' => 'true',
			],

			'wrapper' => [
				'label'      => __( 'Wrapper', 'radius-blocks' ),
				'value'      => 'wrapper',
				'visibility' => 'true',
			],

			'social_icons' => [
				'label'      => __( 'Social Icons', 'radius-blocks' ),
				'value'      => 'social_icons',
				'visibility' => 'true',
			],

			'advanced_image' => [
				'label'      => __( 'Advanced Image', 'radius-blocks' ),
				'value'      => 'advanced_image',
				'visibility' => 'true',
			],

			'icon_list' => [
				'label'      => __( 'Icon List', 'radius-blocks' ),
				'value'      => 'icon_list',
				'visibility' => 'true',
			],

			'advanced_tab' => [
				'label'      => __( 'Advanced Tab', 'radius-blocks' ),
				'value'      => 'advanced_tab',
				'visibility' => 'true',
			],

			'progress_bar' => [
				'label'      => __( 'Progress Bar', 'radius-blocks' ),
				'value'      => 'progress_bar',
				'visibility' => 'true',
			],

			'advanced_video' => [
				'label'      => __( 'Advanced Video', 'radius-blocks' ),
				'value'      => 'advanced_video',
				'visibility' => 'true',
			],

			'search' => [
				'label'      => __( 'Search', 'radius-blocks' ),
				'value'      => 'search',
				'visibility' => 'true',
			],

			'header_info' => [
				'label'      => __( 'Header Info', 'radius-blocks' ),
				'value'      => 'header_info',
				'visibility' => 'true',
			],

			'advanced_navigation' => [
				'label'      => __( 'Advanced Navigation', 'radius-blocks' ),
				'value'      => 'advanced_navigation',
				'visibility' => 'true',
			],

			'copyright' => [
				'label'      => __( 'Copyright Text', 'radius-blocks' ),
				'value'      => 'copyright',
				'visibility' => 'true',
			],

			'logo_slider'          => [
				'label'      => __( 'Logo Slider', 'radius-blocks' ),
				'value'      => 'logo_slider',
				'visibility' => 'true',
			],
			'post_carousel'        => [
				'label'      => __( 'Post Carousel', 'radius-blocks' ),
				'value'      => 'post_carousel',
				'visibility' => 'true',
			],
			'testimonial_slider'   => [
				'label'      => __( 'Testimonial Slider', 'radius-blocks' ),
				'value'      => 'testimonial_slider',
				'visibility' => 'true',
			],
			'fluent_form'          => [
				'label'      => __( 'Fluent Form', 'radius-blocks' ),
				'value'      => 'fluent_form',
				'visibility' => 'true',
			],
			'contact_form7'        => [
				'label'      => __( 'Contact Form7', 'radius-blocks' ),
				'value'      => 'contact_form7',
				'visibility' => 'true',
			],
			'post_timeline'        => [
				'label'      => __( 'Post Timeline', 'radius-blocks' ),
				'value'      => 'post_timeline',
				'visibility' => 'true',
			],
			'news_ticker'          => [
				'label'      => __( 'News Ticker', 'radius-blocks' ),
				'value'      => 'news_ticker',
				'visibility' => 'true',
			],
			'dropcaps'             => [
				'label'      => __( 'Dropcaps', 'radius-blocks' ),
				'value'      => 'dropcaps',
				'visibility' => 'true',
			],
			'social_share'         => [
				'label'      => __( 'Social Share', 'radius-blocks' ),
				'value'      => 'social_share',
				'visibility' => 'true',
			],
			'image_accordion'      => [
				'label'      => __( 'Image Accordion', 'radius-blocks' ),
				'value'      => 'image_accordion',
				'visibility' => 'true',
			],
			'woo_product_grid'     => [
				'label'      => __( 'Woo Product Grid', 'radius-blocks' ),
				'value'      => 'woo_product_grid',
				'visibility' => 'true',
			],
			'woo_product_list'     => [
				'label'      => __( 'Woo Product List', 'radius-blocks' ),
				'value'      => 'woo_product_list',
				'visibility' => 'true',
			],
			'woo_product_carousel' => [
				'label'      => __( 'Woo Product Carousel', 'radius-blocks' ),
				'value'      => 'woo_product_carousel',
				'visibility' => 'true',
			],
		];

		$pro_blocks    = apply_filters( 'rtrb_blocks_pro', [] );
		$merged_blocks = array_merge( $default_blocks, $pro_blocks );

		return $merged_blocks;
	}

}
