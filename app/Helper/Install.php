<?php

namespace AdvancedNewsTicker\Helper;
class Install {

	public static function activate() {
		self::create_cron_jobs();
		add_option( 'raw_addons_activation_redirect', true );
	}

	public static function deactivate() {
		update_option( 'tpg_flush_rewrite_rules', 0 );
		self::clean_cron_jobs();
	}


	public static function clean_cron_jobs() {
		// Un-schedules all previously-scheduled cron jobs

		wp_clear_scheduled_hook( 'raw_addons_daily_scheduled_events' );
	}

	/**
	 * Create cron jobs (clear them first)
	 *
	 * @return void
	 */
	private static function create_cron_jobs() {
		self::clean_cron_jobs();

		if ( ! wp_next_scheduled( 'raw_addons_daily_scheduled_events' ) ) {
			$ve          = get_option( 'gmt_offset' ) > 0 ? '-' : '+';
			$expire_time = strtotime( '00:00 tomorrow ' . $ve . absint( get_option( 'gmt_offset' ) ) . ' HOURS' );
			wp_schedule_event( $expire_time, 'daily', 'raw_addons_daily_scheduled_events' );
		}
	}
}
