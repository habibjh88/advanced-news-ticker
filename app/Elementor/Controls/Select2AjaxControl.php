<?php
/**
 * @author  habibjh88
 * @since   1.0
 * @version 1.0
 */

namespace AdvancedNewsTicker\Elementor\Controls;

use AdvancedNewsTicker\Helper\Fns;
use Elementor\Base_Data_Control;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

class Select2AjaxControl extends Base_Data_Control {

	/**
	 * Set control name.
	 *
	 * @var string
	 */
	public static $controlName = 'ant-select2';

	public function get_type() {
		return self::$controlName;
	}

	public function enqueue() {
		wp_enqueue_style( 'ant-select2', Fns::get_assets_url( 'css/admin/el-select.css' ), [], '1.0' );
		wp_enqueue_script( 'ant-editor-script' );
		wp_localize_script(
			'ant-editor-script',
			'antSelect2Obj',
			[
				'ajaxurl'     => esc_url( admin_url( 'admin-ajax.php' ) ),
				'search_text' => esc_html__( 'Please Select', 'advanced-news-ticker' ),
				'nonce'       => wp_create_nonce( 'ant-select2-nonce' ),
			]
		);
		?>
		<?php
	}

	protected function get_default_settings() {
		return [
			'multiple'                 => false,
			'label_block'              => true,
			'source_name'              => 'post_type',
			'source_type'              => 'post',
			'minimum_input_length'     => 3,
			'maximum_selection_length' => - 1,
		];
	}

	public function content_template() {
		$control_uid = $this->get_control_uid();
		?>

        <# var controlUID = '<?php echo esc_html( $control_uid ); ?>'; #>
        <# var currentID = elementor.panel.currentView.currentPageView.model.attributes.settings.attributes[data.name]; #>
        <# var maxSelection = (data.maximum_selection_length > 0) ? 'max-select'+data.maximum_selection_length : 'unlimited-select' #>
        <div class="elementor-control-field ant-select2-main-wrapper {{maxSelection}}">
            <# if ( data.label ) { #>
            <label for="<?php echo esc_attr( $control_uid ); ?>" class="elementor-control-title">{{{data.label }}}</label>
            <# } #>
            <div class="elementor-control-input-wrapper elementor-control-unit-5">
                <# var multiple = ( data.multiple ) ? 'multiple' : ''; #>
                <select id="<?php echo esc_attr( $control_uid ); ?>" {{ multiple }} class="ant-select2" data-setting="{{ data.name }}"></select>
            </div>
            <# if ( data.description ) { #>
            <div class="elementor-control-field-description ant-description">{{{ data.description }}}</div>
            <# } #>
        </div>
        <#
        ( function( $ ) {
        $( document.body ).trigger( 'ant_select2_event',{currentID:data.controlValue,data:data,controlUID:controlUID,multiple:data.multiple} );
        }( jQuery ) );
        #>
		<?php
	}
}
