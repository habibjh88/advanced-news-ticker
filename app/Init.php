<?php
/**
 *
 * This theme uses PSR-4 and OOP logic instead of procedural coding
 * Every function, hook and action is properly divided and organized inside related folders and files
 * Use the file `config/custom/custom.php` to write your custom functions
 *
 * @package AdvancedNewsTicker
 */

namespace habibjh88\AdvancedNewsTicker;

use habibjh88\AdvancedNewsTicker\Helper\Install;
use habibjh88\AdvancedNewsTicker\Traits\SingletonTraits;

final class Init {

	use SingletonTraits;

	/**
	 * @var string
	 */
	public $nonceId = 'advanced_news_ticker_wpnonce';

	/**
	 * @var string
	 */
	public $nonceText = 'advanced_news_ticker_nonce_secret';

	/**
	 * Class constructor
	 */
	public function __construct() {
		add_action( 'setup_theme', [ $this, 'after_theme_loaded' ] );

		register_activation_hook( ADVANCED_NEWS_TICKER_BASE_FILE_NAME, [ Install::class, 'activate' ] );
		register_deactivation_hook( ADVANCED_NEWS_TICKER_BASE_FILE_NAME, [ Install::class, 'deactivate' ] );
	}

	/**
	 * Instantiate all class
	 * @return void
	 */
	public function after_theme_loaded() {
		Controllers\ScriptController::instance();

		if ( did_action( 'elementor/loaded' ) ) {
			Controllers\ElementorController::instance();
		}

	}

}
