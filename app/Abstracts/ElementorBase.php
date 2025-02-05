<?php
/**
 * @author  habibjh88
 * @since   1.0
 * @version 1.0
 */

namespace AdvancedNewsTicker\Abstracts;

use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

abstract class ElementorBase extends Widget_Base {

	public $prefix;
	public $ant_name;
	public $ant_base;
	public $ant_category;
	public $ant_icon;
	public $ant_translate;

	public function __construct( $data = [], $args = null ) {
		$this->ant_category = ADVANCED_NEWS_TICKER_PREFIX . '-widgets'; // Category /@dev
		$this->ant_icon     = 'ant-el-custom';
		parent::__construct( $data, $args );
	}

	public function get_name() {
		return $this->ant_base;
	}

	public function get_title() {
		return $this->ant_name;
	}

	public function get_icon() {
		return $this->ant_icon;
	}

	public function get_categories() {
		return [ $this->ant_category ];
	}
}