<?php
/**
 * @author  DevofWP
 * @since   1.0
 * @version 1.0
 */


namespace AdvancedNewsTicker\Abstracts;

use Elementor\Plugin;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Text_Stroke;
use AdvancedNewsTicker\Elementor\Controls\ImageSelectorControl;
use AdvancedNewsTicker\Elementor\Controls\Select2AjaxControl;


// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Abstract ElementorWidgetBase Class
 *
 * Implemented by classes for elementor addons development.
 *
 * @version  1.0.0
 * @package  DevofWP\SB
 */
abstract class ElementorWidgetBase extends Widget_Base {

	/**
	 * Widget Title.
	 *
	 * @var String
	 */
	public $raw_addons_name;

	/**
	 * Widget name.
	 *
	 * @var String
	 */
	public $raw_addons_base;

	/**
	 * Widget categories.
	 *
	 * @var String
	 */
	public $raw_addons_category;

	/**
	 * Widget icon class
	 *
	 * @var String
	 */
	public $raw_addons_icon;

	/**
	 * PRO Label HTML.
	 *
	 * @var String
	 */
	public $pro_label = '';

	/**
	 * PRO content tab.
	 *
	 * @var String
	 */
	public $pro_tab;

	/**
	 * Widget prefix.
	 *
	 * @var String
	 */
	public $el_prefix;

	/**
	 * Widget controls.
	 *
	 * @var array
	 */
	public $selectors = [];

	/**
	 * Class Constructor
	 *
	 * @param array $data default data.
	 * @param array $args default arg.
	 */
	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );
		$this->raw_addons_category = ADVANCED_NEWS_TICKER_PREFIX . '-widgets'; // Category /@dev
		$this->raw_addons_icon     = 'rdtheme-el-custom';
	}


	/**
	 * Elementor controls marge all settings
	 *
	 * @return array
	 */
	abstract public function widget_fields();

	/**
	 * Get widget name.
	 *
	 * Retrieve widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return $this->raw_addons_base;
	}


	/**
	 * Get widget title.
	 *
	 * Retrieve widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return $this->raw_addons_name;
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve widget icon.
	 *
	 * @return string Widget icon.
	 * @since 1.0.0
	 * @access public
	 */
	public function get_icon() {
		return $this->raw_addons_icon;
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories widget belongs to.
	 *
	 * @return array Widget categories.
	 * @since 1.0.0
	 * @access public
	 */
	public function get_categories() {
		return [ $this->raw_addons_category ];
	}


	/**
	 * Register widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {

		$fields = $this->widget_fields();

		if ( ! is_array( $fields ) ) {
			return;
		}

		foreach ( $fields as $id => $field ) {
			$field['classes'] = ! empty( $field['classes'] ) ? $field['classes'] . ' elementor-control-raw_addons_el' : ' elementor-control-raw_addons_el';

			if ( ! empty( $field['type'] ) ) {
				$field['type'] = self::field_type( $field['type'] );
			}

			if ( ! empty( $field['tab'] ) ) {
				$field['tab'] = self::el_tabs( $field['tab'] );
			}

			if ( isset( $field['mode'] ) && 'section_start' === $field['mode'] ) {
				unset( $field['mode'] );
				unset( $field['separator'] );

				$this->start_controls_section( $id, $field );
			} elseif ( isset( $field['mode'] ) && 'section_end' === $field['mode'] ) {
				$this->end_controls_section();
			} elseif ( isset( $field['mode'] ) && 'tabs_start' === $field['mode'] ) {
				unset( $field['mode'] );
				unset( $field['separator'] );

				$this->start_controls_tabs( $id );
			} elseif ( isset( $field['mode'] ) && 'tabs_end' === $field['mode'] ) {
				$this->end_controls_tabs();
			} elseif ( isset( $field['mode'] ) && 'tab_start' === $field['mode'] ) {
				unset( $field['mode'] );
				unset( $field['separator'] );

				$this->start_controls_tab( $id, $field );
			} elseif ( isset( $field['mode'] ) && 'tab_end' === $field['mode'] ) {
				$this->end_controls_tab();
			} elseif ( isset( $field['mode'] ) && 'group' === $field['mode'] ) {
				$type          = $field['type'];
				$field['name'] = $id;
				unset( $field['mode'] );
				unset( $field['type'] );
				$this->add_group_control( $type, $field );
			} elseif ( isset( $field['mode'] ) && 'responsive' === $field['mode'] ) {
				unset( $field['mode'] );

				$this->add_responsive_control( $id, $field );
			} elseif ( isset( $field['mode'] ) && 'popover_start' === $field['mode'] ) {
				unset( $field['mode'] );
				unset( $field['separator'] );

				$this->add_control( $id, $field );
				$this->start_popover();
			} elseif ( isset( $field['mode'] ) && 'popover_end' === $field['mode'] ) {
				$this->end_popover();
			} elseif ( isset( $field['mode'] ) && 'repeater' === $field['mode'] ) {
				$repeater        = new Repeater();
				$repeater_fields = $field['fields'];

				foreach ( $repeater_fields as $rf_id => $value ) {
					if ( ! empty( $value['type'] ) ) {
						$value['type'] = self::field_type( $value['type'] );
					}
					if ( isset( $value['mode'] ) && 'responsive' === $value['mode'] ) {
						unset( $value['mode'] );
						$repeater->add_responsive_control( $rf_id, $value );
					} else {
						$repeater->add_control( $rf_id, $value );
					}
				}

				$field['fields'] = $repeater->get_controls();

				$this->add_control( $id, $field );
			} else {
				$this->add_control( $id, $field );
			}
		}

		do_action( 'raw_addons/after/register/controls' );
	}

	/**
	 * Elementor Edit mode.
	 *
	 * @return mixed
	 */
	public function is_edit_mode() {
		return Plugin::$instance->preview->is_preview_mode() || Plugin::$instance->editor->is_edit_mode();
	}


	/**
	 * Elementor Fields.
	 *
	 * @param string $type Control type.
	 *
	 * @return string
	 */
	private static function field_type( $type ) {

		$controls = Controls_Manager::class;

		switch ( $type ) {
			case 'link':
				$type = $controls::URL;
				break;

			case 'image-dimensions':
				$type = $controls::IMAGE_DIMENSIONS;
				break;

			case 'html':
				$type = $controls::RAW_HTML;
				break;

			case 'switch':
				$type = $controls::SWITCHER;
				break;

			case 'popover':
				$type = $controls::POPOVER_TOGGLE;
				break;

			case 'ant-image-select':
				$type = ImageSelectorControl::$controlName;
				break;

			case 'ant-select2':
				$type = Select2AjaxControl::$controlName;
				break;

			case 'typography':
				$type = Group_Control_Typography::get_type();
				break;

			case 'border':
				$type = Group_Control_Border::get_type();
				break;

			case 'background':
				$type = Group_Control_Background::get_type();
				break;

			case 'box-shadow':
				$type = Group_Control_Box_Shadow::get_type();
				break;

			case 'text-shadow':
				$type = Group_Control_Text_Shadow::get_type();
				break;

			case 'text-stroke':
				$type = Group_Control_Text_Stroke::get_type();
				break;
			default:
				$type = constant( 'Elementor\Controls_Manager::' . strtoupper( $type ) );

		}

		return $type;
	}

	/**
	 * Elementor Fields.
	 *
	 * @param string $tab Tab.
	 *
	 * @return string
	 */
	private static function el_tabs( $tab ) {
		return constant( 'Elementor\Controls_Manager::TAB_' . strtoupper( $tab ) );
	}


	/**
	 * Starts an Elementor Section
	 *
	 * @param string $label Section label.
	 * @param string $tab Tab ID.
	 * @param array $conditions Section Condition.
	 * @param array $condition Section Conditions.
	 *
	 * @return array
	 */
	public function start_section( $label, $tab, $conditions = [], $condition = [] ) {
		$start = [
			'mode'  => 'section_start',
			'tab'   => $tab,
			'label' => $label,
		];
		if ( ! empty( $condition ) ) {
			$start['condition'] = $condition;
		}
		if ( ! empty( $conditions ) ) {
			$start['conditions'] = $conditions;
		}

		return $start;
	}

	/**
	 * Ends an Elementor Section
	 *
	 * @return array
	 */
	public function end_section() {
		return [
			'mode' => 'section_end',
		];
	}

	/**
	 * Starts an Elementor tab group.
	 *
	 * @param array $conditions Tab condition.
	 * @param array $condition Tab condition.
	 *
	 * @return array
	 */
	public function start_tab_group( $conditions = [], $condition = [] ) {
		return [
			'mode'       => 'tabs_start',
			'conditions' => $conditions,
			'condition'  => $condition,
		];
	}

	/**
	 * Ends an Elementor tab group.
	 *
	 * @param array $conditions Tab condition.
	 * @param array $condition Tab condition.
	 *
	 * @return array
	 */
	public function end_tab_group( $conditions = [], $condition = [] ) {
		return [
			'mode'       => 'tabs_end',
			'conditions' => $conditions,
			'condition'  => $condition,
		];
	}


	/**
	 * Starts an Elementor tab
	 *
	 * @param string $label Section label.
	 * @param array $conditions Tab condition.
	 * @param array $condition Tab condition.
	 *
	 * @return array
	 */
	public function start_tab( $label, $conditions = [], $condition = [] ) {
		return [
			'mode'       => 'tab_start',
			'label'      => $label,
			'conditions' => $conditions,
			'condition'  => $condition,
		];
	}

	/**
	 * Ends an Elementor tab.
	 *
	 * @param array $conditions Tab condition.
	 * @param array $condition Tab condition.
	 *
	 * @return array
	 */
	public function end_tab( $conditions = [], $condition = [] ) {
		return [
			'mode'       => 'tab_end',
			'conditions' => $conditions,
			'condition'  => $condition,
		];
	}
}
