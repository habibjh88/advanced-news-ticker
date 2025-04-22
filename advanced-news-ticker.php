<?php

/**
 * Plugin Name: News Ticker for Elementor
 * Plugin URI: https://github.com/devofwp/advanced-news-ticker
 * Description: Advanced News Ticker is a powerful WordPress plugin designed to effortlessly create dynamic breaking news tickers for Elementor, with fully customizable layouts and settings
 * Author: devofwp
 * Version: 1.0.4
 * Text Domain: advanced-news-ticker
 * Domain Path: /languages
 * Author URI: https://devofwp.com/
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package AdvancedNewsTicker
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'ADVANCED_NEWS_TICKER' ) ) {
	define( 'ADVANCED_NEWS_TICKER', '1.0.4' );
	define( 'ADVANCED_NEWS_TICKER_PREFIX', 'advanced_news_ticker' );
	define( 'ADVANCED_NEWS_TICKER_BASE_URL', plugin_dir_url( __FILE__ ) );
	define( 'ADVANCED_NEWS_TICKER_BASE_DIR', plugin_dir_path( __FILE__ ) );
	define( 'ADVANCED_NEWS_TICKER_BASE_FILE_NAME', plugin_basename( __FILE__ ) );
}

if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) :
	require_once dirname( __FILE__ ) . '/vendor/autoload.php';
endif;

if ( class_exists( 'devofwp\\AdvancedNewsTicker\\Init' ) ) :
	function advancedNewsTicker() {
		return devofwp\AdvancedNewsTicker\Init::instance();
	}
	advancedNewsTicker();
endif;
