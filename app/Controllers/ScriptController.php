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
//		add_action( 'elementor/frontend/after_enqueue_scripts', [ $this, 'enqueue_scripts' ], 999 );
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
		$script_debug       = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG;
		$js_file_path       = $script_debug ? "assets/js/scripts.js" : "assets/js/frontend/frontend.min.js";
		$js_file_dependency = $script_debug ? [ 'jquery', 'advanced-newsticker' ] : [ 'jquery' ];

		//Register JS File
		wp_register_script( 'advanced-newsticker', ADVANCED_NEWS_TICKER_BASE_URL . 'assets/js/lib/newsticker.js', [], self::get_version(), true );
		wp_register_script( 'advanced-news-ticker-main', ADVANCED_NEWS_TICKER_BASE_URL . $js_file_path, $js_file_dependency, self::get_version(), true );

		//Register CSS File
		wp_register_style( 'advanced-news-ticker', ADVANCED_NEWS_TICKER_BASE_URL . "assets/css/style.css", '', self::get_version() );

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

}
