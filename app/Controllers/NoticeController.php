<?php
/**
 * @author  devofwp
 * @since   1.0
 * @version 1.0
 */

namespace devofwp\AdvancedNewsTicker\Controllers;

use Elementor\Plugin;
use devofwp\AdvancedNewsTicker\Elementor\Controls\ImageSelectorControl;
use devofwp\AdvancedNewsTicker\Elementor\Controls\Select2AjaxControl;
use devofwp\AdvancedNewsTicker\Helper\Fns;
use devofwp\AdvancedNewsTicker\Traits\SingletonTraits;
use devofwp\AdvancedNewsTicker\Elementor\Addons\NewsTicker;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * ElementorController Class
 */
class NoticeController {

	use SingletonTraits;

	/**
	 * Class Construct
	 */
	public function __construct() {
		add_action( 'admin_init', [ $this, 'check_elementor_dependency' ] );
	}

	/**
	 * Checks if Elementor is installed and active.
	 */
	public function check_elementor_dependency() {
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', [ $this, 'elementor_missing_notice' ] );
			//deactivate_plugins( plugin_basename( __FILE__ ) ); // Optional: Deactivate your plugin
		}
	}

	/**
	 * Displays an admin notice if Elementor is not installed or active.
	 */
	public function elementor_missing_notice() {
		?>
        <div class="notice notice-error is-dismissible">
            <p><?php esc_html_e( 'The "Advanced News Ticker" plugin requires Elementor to be installed and activated.', 'dvanced-news-ticker' ); ?></p>
        </div>
		<?php
	}

}