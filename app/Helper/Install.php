<?php

namespace AdvancedNewsTicker\Helper;
class Install {

	public static function activate() {
		if ( get_option( 'advanced_news_ticker_active' ) ) {
			add_option( 'advanced_news_ticker_active', time() );
		}
	}

	public static function deactivate() {

	}


}
