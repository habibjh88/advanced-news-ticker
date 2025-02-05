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
		$min_suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? null : '.min';
		$css_path = is_rtl() ? "styles-rtl{$min_suffix}.css" : "style{$min_suffix}.css";
		//Register JS File
		wp_register_script( 'ant-scripts', ADVANCED_NEWS_TICKER_BASE_URL . "assets/js/scripts{$min_suffix}.js", [ 'swiper' ], self::get_version(), true );

		//Register CSS File
		wp_register_style( 'ant-styles', ADVANCED_NEWS_TICKER_BASE_URL . "assets/css/{$css_path}", [ 'swiper' ], self::get_version() );

		//Localize Script for 'ant-scripts' js
		wp_localize_script(
			'ant-scripts',
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
