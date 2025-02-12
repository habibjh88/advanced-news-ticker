<?php
/**
 * @author  habibjh88
 * @since   1.0
 * @version 1.0
 */

namespace habibjh88\AdvancedNewsTicker\Abstracts;

use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

abstract class ElementorBase extends Widget_Base {

	public $prefix;
	public $ticker_name;
	public $ticker_base;
	public $ticker_category;
	public $ticker_icon;
	public $ticker_translate;

	public function __construct( $data = [], $args = null ) {
		$this->ticker_category = ADVANCED_NEWS_TICKER_PREFIX . '-widgets'; // Category /@dev
		$this->ticker_icon     = 'advanced-news-ticker-el-custom';
		parent::__construct( $data, $args );
	}

	public function get_name() {
		return $this->ticker_base;
	}

	public function get_title() {
		return $this->ticker_name;
	}

	public function get_icon() {
		return $this->ticker_icon;
	}

	public function get_categories() {
		return [ $this->ticker_category ];
	}
}