<?php

/**
 * Plugin Name: Advanced News Ticker
 * Plugin URI: https://github.com/habibjh88/advanced-news-ticker
 * Description: Advanced News Ticker is a powerful WordPress plugin designed to effortlessly create dynamic breaking news tickers for Elementor, with fully customizable layouts and settings
 * Author: habibjh88
 * Version: 1.0.2
 * Text Domain: advanced-news-ticker
 * Domain Path: /languages
 * Author URI: https://habibportfolio.com/
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package AdvancedNewsTicker
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'ADVANCED_NEWS_TICKER' ) ) {
	define( 'ADVANCED_NEWS_TICKER', '1.0.2' );
	define( 'ADVANCED_NEWS_TICKER_PREFIX', 'advanced_news_ticker' );
	define( 'ADVANCED_NEWS_TICKER_BASE_URL', plugin_dir_url( __FILE__ ) );
	define( 'ADVANCED_NEWS_TICKER_BASE_DIR', plugin_dir_path( __FILE__ ) );
	define( 'ADVANCED_NEWS_TICKER_BASE_FILE_NAME', plugin_basename( __FILE__ ) );
}

if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) :
	require_once dirname( __FILE__ ) . '/vendor/autoload.php';
endif;

if ( class_exists( 'habibjh88\\AdvancedNewsTicker\\Init' ) ) :
	function advancedNewsTicker() {
		return habibjh88\AdvancedNewsTicker\Init::instance();
	}
	advancedNewsTicker();
endif;
