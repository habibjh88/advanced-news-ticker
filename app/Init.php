<?php
/**
 *
 * This theme uses PSR-4 and OOP logic instead of procedural coding
 * Every function, hook and action is properly divided and organized inside related folders and files
 * Use the file `config/custom/custom.php` to write your custom functions
 *
 * @package raw_addons
 */

namespace AdvancedNewsTicker;

use AdvancedNewsTicker\Helper\Install;
use AdvancedNewsTicker\Traits\SingletonTraits;

final class Init {

	use SingletonTraits;

	/**
	 * @var string
	 */
	public $nonceId = '__raw_wpnonce';

	/**
	 * @var string
	 */
	public $nonceText = 'raw_nonce_secret';

	/**
	 * Option settings key
	 *
	 * @var string
	 */
	public $settingKey = 'raw_addons_options';

	/**
	 * Class constructor
	 */
	public function __construct() {
		add_action( 'setup_theme', [ $this, 'after_theme_loaded' ] );
		add_action( 'init', [ $this, 'load_textdomain' ], 20 );

		register_activation_hook( ADVANCED_NEWS_TICKER_BASE_FILE_NAME, [ Install::class, 'activate' ] );
		register_deactivation_hook( ADVANCED_NEWS_TICKER_BASE_FILE_NAME, [ Install::class, 'deactivate' ] );
	}

	/**
	 * Instantiate all class
	 * @return void
	 */
	public function after_theme_loaded() {
		Hooks\FilterHooks::instance();
		Hooks\ActionHooks::instance();
		Controllers\ScriptController::instance();

		if ( did_action( 'elementor/loaded' ) ) {
			Controllers\ElementorController::instance();
		}

	}

	public function load_textdomain() {
		load_plugin_textdomain( 'advanced-news-ticker', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}

	/**
	 * @return string
	 */
	public function has_pro() {
		return class_exists( 'AdvancedNewsTickerPro\\app' );
	}

}
