<?php

namespace devofwp\AdvancedNewsTicker\Helper;
/**
 * Installer Class
 */
class Install {

	/**
	 * Plugin Activator
	 * @return void
	 */
	public static function activate() {
		if ( get_option( 'advanced_news_ticker_active' ) ) {
			add_option( 'advanced_news_ticker_active', time() );
		}
	}

	/**
	 * Plugin Deactivate
	 * @return void
	 */
	public static function deactivate() {

	}


}
