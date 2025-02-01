<?php
/**
 * @author  habibjh88
 * @since   1.0
 * @version 1.0
 */

namespace AdvancedNewsTicker\Hooks;

use AdvancedNewsTicker\Modules\Svg;
use AdvancedNewsTicker\Traits\SingletonTraits;

class FilterHooks {
	use SingletonTraits;


	public function __construct() {
		//Add user contact info
		add_filter( 'user_contactmethods', [ __CLASS__, 'ant_user_extra_contact_info' ] );
	}

	/* User Contact Info */
	public static function ant_user_extra_contact_info( $contactmethods ) {
		$contactmethods['ant_designation'] = __( 'Designation', 'advanced-news-ticker' );
		$contactmethods['ant_phone']     = __( 'Phone Number', 'advanced-news-ticker' );
		$contactmethods['ant_facebook']  = __( 'Facebook', 'advanced-news-ticker' );
		$contactmethods['ant_twitter']   = __( 'Twitter', 'advanced-news-ticker' );
		$contactmethods['ant_linkedin']  = __( 'LinkedIn', 'advanced-news-ticker' );
		$contactmethods['ant_vimeo']     = __( 'Vimeo', 'advanced-news-ticker' );
		$contactmethods['ant_youtube']   = __( 'Youtube', 'advanced-news-ticker' );
		$contactmethods['ant_instagram'] = __( 'Instagram', 'advanced-news-ticker' );
		$contactmethods['ant_pinterest'] = __( 'Pinterest', 'advanced-news-ticker' );
		$contactmethods['ant_whatsapp']  = __( 'Whatsapp', 'advanced-news-ticker' );

		return $contactmethods;
	}

}