<?php
/**
 * @author  DevofWP
 * @since   1.0
 * @version 1.0
 */

namespace AdvancedNewsTicker\Elementor\Addons;

use Elementor\Controls_Manager;
use AdvancedNewsTicker\Helper\Fns;
use AdvancedNewsTicker\Abstracts\ElementorBase;
use AdvancedNewsTicker\Traits\QueryTraits;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Counter Class
 */
class NewsTicker extends ElementorBase {

	use QueryTraits;

	/**
	 * Class Constructor
	 *
	 * @param $data
	 * @param $args
	 *
	 * @throws \Exception
	 */
	public function __construct( $data = [], $args = NULL ) {
		$this->ant_name = __( 'News Ticker', 'advanced-news-ticker' );
		$this->ant_base = 'ant-news-ticker';
		parent::__construct( $data, $args );
	}

	public function get_script_depends() {
		return [ 'ant-newsticker' ];
	}

	/**
	 * Register Controls
	 *
	 * @return void
	 */
	protected function register_controls() {
		$this->general_settings();
		$this->query();
		$this->breaking_title();
		$this->post_title();
		$this->newsticker_controls();
		$this->wrapper_controls();
	}

	protected function query() {
		$this->start_controls_section(
			'query_section',
			[
				'label' => esc_html__( 'Query', 'raw-addons' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'post_limit',
			[
				'label'       => __( 'Post Per Page', 'advanced-news-ticker' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'Enter Post Limit', 'advanced-news-ticker' ),
				'description' => __( 'Enter number of post to show.', 'advanced-news-ticker' ),
				'default'     => '12',
			]
		);

		$this->add_control(
			'post_id',
			[
				'type'                 => 'ant-select2',
				'label'                => __( 'By Posts', 'advanced-news-ticker' ),
				'source_name'          => 'post',
				'source_type'          => 'post',
				'multiple'             => TRUE,
				'label_block'          => TRUE,
				'minimum_input_length' => 3,
			]
		);

		$this->add_control(
			'category',
			[
				'type'                 => 'ant-select2',
				'label'                => esc_html__( 'By Categories', 'advanced-news-ticker' ),
				'source_name'          => 'taxonomy',
				'source_type'          => 'category',
				'multiple'             => TRUE,
				'label_block'          => TRUE,
				'minimum_input_length' => 1,
			]
		);

		$this->add_control(
			'post_tag',
			[
				'type'                 => 'ant-select2',
				'label'                => esc_html__( 'By Tags', 'advanced-news-ticker' ),
				'source_name'          => 'taxonomy',
				'source_type'          => 'post_tag',
				'multiple'             => TRUE,
				'label_block'          => TRUE,
				'minimum_input_length' => 1,
			]
		);

		$this->add_control(
			'tax_relation',
			[
				'label'   => __( 'Taxonomy Relation', 'advanced-news-ticker' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'OR'  => __( 'OR', 'advanced-news-ticker' ),
					'AND' => __( 'AND', 'advanced-news-ticker' ),
				],
				'default' => 'OR',
			]
		);

		$this->add_control(
			'offset',
			[
				'label'       => __( 'Post offset', 'advanced-news-ticker' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'Enter Post offset', 'advanced-news-ticker' ),
				'description' => __( 'Number of post to displace or pass over. The offset parameter is ignored when post limit => -1 (show all posts) is used.', 'advanced-news-ticker' ),
			]
		);

		$this->add_control(
			'exclude',
			[
				'type'                 => 'ant-select2',
				'label'                => __( 'Exclude posts', 'advanced-news-ticker' ),
				'description'          => __( 'Choose posts for exclude', 'advanced-news-ticker' ),
				'source_name'          => 'post',
				'source_type'          => 'post',
				'multiple'             => TRUE,
				'label_block'          => TRUE,
				'minimum_input_length' => 3,
			]
		);

		$this->add_control(
			'orderby',
			[
				'label'   => __( 'Order by', 'advanced-news-ticker' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'date',
				'options' => [
					'date'           => __( 'Date', 'advanced-news-ticker' ),
					'ID'             => __( 'Order by post ID', 'advanced-news-ticker' ),
					'author'         => __( 'Author', 'advanced-news-ticker' ),
					'title'          => __( 'Title', 'advanced-news-ticker' ),
					'modified'       => __( 'Last modified date', 'advanced-news-ticker' ),
					'parent'         => __( 'Post parent ID', 'advanced-news-ticker' ),
					'comment_count'  => __( 'Number of comments', 'advanced-news-ticker' ),
					'menu_order'     => __( 'Menu order', 'advanced-news-ticker' ),
					'meta_value'     => __( 'Meta value', 'advanced-news-ticker' ),
					'meta_value_num' => __( 'Meta value number', 'advanced-news-ticker' ),
					'rand'           => __( 'Random order', 'advanced-news-ticker' ),
				],
			]
		);

		$this->add_control(
			'order',
			[
				'label'   => __( 'Sort order', 'advanced-news-ticker' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'DESC',
				'options' => [
					'ASC'  => __( 'ASC', 'advanced-news-ticker' ),
					'DESC' => __( 'DESC', 'advanced-news-ticker' ),
				],
			]
		);

		$this->end_controls_section();
	}

	protected function general_settings() {
		$this->start_controls_section(
			'sec_general',
			[
				'label' => esc_html__( 'General', 'advanced-news-ticker' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'layout',
			[
				'type'         => 'advanced-news-ticker-image-select',
				'label'        => esc_html__( 'Choose Layout', 'advanced-news-ticker' ),
				'description'  => esc_html__( 'Choose layout', 'advanced-news-ticker' ),
				'options'      => [
					'1' => [
						'title' => esc_html__( 'Layout 1', 'advanced-news-ticker' ),
						'url'   => esc_url( Fns::get_assets_url( 'images/layout/newsticker-1.svg' ) ),
					],
					'2' => [
						'title'  => esc_html__( 'Layout 2', 'advanced-news-ticker' ),
						'url'    => esc_url( Fns::get_assets_url( 'images/layout/newsticker-2.svg' ) ),
						'is_pro' => ! advancedNewsTicker()->has_pro(),
					],
					'3' => [
						'title'  => esc_html__( 'Layout 3', 'advanced-news-ticker' ),
						'url'    => esc_url( Fns::get_assets_url( 'images/layout/newsticker-3.svg' ) ),
						'is_pro' => ! advancedNewsTicker()->has_pro(),
					],
					'4' => [
						'title'  => esc_html__( 'Layout 4', 'advanced-news-ticker' ),
						'url'    => esc_url( Fns::get_assets_url( 'images/layout/newsticker-4.svg' ) ),
						'is_pro' => ! advancedNewsTicker()->has_pro(),
					],
					'5' => [
						'title'  => esc_html__( 'Layout 5', 'advanced-news-ticker' ),
						'url'    => esc_url( Fns::get_assets_url( 'images/layout/newsticker-5.svg' ) ),
						'is_pro' => ! advancedNewsTicker()->has_pro(),
					],
					'6' => [
						'title'  => esc_html__( 'Layout 6', 'advanced-news-ticker' ),
						'url'    => esc_url( Fns::get_assets_url( 'images/layout/newsticker-6.svg' ) ),
						'is_pro' => ! advancedNewsTicker()->has_pro(),
					],
					'7' => [
						'title'  => esc_html__( 'Layout 7', 'advanced-news-ticker' ),
						'url'    => esc_url( Fns::get_assets_url( 'images/layout/newsticker-7.svg' ) ),
						'is_pro' => ! advancedNewsTicker()->has_pro(),
					],
					'8' => [
						'title'  => esc_html__( 'Layout 8', 'advanced-news-ticker' ),
						'url'    => esc_url( Fns::get_assets_url( 'images/layout/newsticker-7.svg' ) ),
						'is_pro' => ! advancedNewsTicker()->has_pro(),
					],
				],
				'classes'      => 'advanced-news-ticker-col-2',
				'default'      => '1',
				'prefix_class' => 'ticker-style-',
				'render_type'  => 'template',
			]
		);

		$this->add_control(
			'breaking_title',
			[
				'label'   => esc_html__( 'Breaking Title', 'neeon-core' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Breaking News', 'neeon-core' ),
			]
		);

		$this->add_control(
			'ticker_settings',
			[
				'label' => esc_html__( 'Ticker Settings', 'neeon-core' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'effect',
			[
				'label'   => esc_html__( 'Effect', 'neeon-core' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'slide-left'  => esc_html__( 'Slide Left', 'neeon-core' ),
					'slide-down'  => esc_html__( 'Slide Down', 'neeon-core' ),
					'slide-up'    => esc_html__( 'Slide Up', 'neeon-core' ),
					'slide-right' => esc_html__( 'Slide Right', 'neeon-core' ),
					'typography'  => esc_html__( 'Typography', 'neeon-core' ),
					'scroll'      => esc_html__( 'Scroll', 'neeon-core' ),
					'fade'        => esc_html__( 'Fade', 'neeon-core' ),
				],
				'default' => 'slide-left',
			]
		);

		$this->add_control(
			'position',
			[
				'label'   => esc_html__( 'Position', 'neeon-core' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					''             => esc_html__( 'Default', 'neeon-core' ),
					'fixed-top'    => esc_html__( 'Fixed Top', 'neeon-core' ),
					'fixed-bottom' => esc_html__( 'Fixed Bottom', 'neeon-core' ),
				],
				'default' => '',
			]
		);

		$this->add_control(
			'custom_pos_popover',
			[
				'label'        => esc_html__( 'Custom Position', 'textdomain' ),
				'type'         => \Elementor\Controls_Manager::POPOVER_TOGGLE,
				'label_off'    => esc_html__( 'Default', 'textdomain' ),
				'label_on'     => esc_html__( 'Custom', 'textdomain' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'position' => [ 'fixed-top', 'fixed-bottom' ],
				],
			]
		);

		$this->start_popover();

		$this->add_responsive_control(
			'left_pos',
			[
				'label'      => __( 'Left', 'advanced-news-ticker' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .ant-breaking-news-ticker' => 'left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'top_pos',
			[
				'label'      => __( 'Top', 'advanced-news-ticker' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .ant-breaking-news-ticker' => 'top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'right_pos',
			[
				'label'      => __( 'Right', 'advanced-news-ticker' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .ant-breaking-news-ticker' => 'right: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'bottom_pos',
			[
				'label'      => __( 'Bottom', 'advanced-news-ticker' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .ant-breaking-news-ticker' => 'bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_popover();

		$this->add_control(
			'height',
			[
				'type'        => Controls_Manager::NUMBER,
				'label'       => esc_html__( 'Height', 'advanced-news-ticker' ),
				'placeholder' => 20,
				'min'         => 16,
				'render_type' => 'template',
				'selectors'   => [
					'{{WRAPPER}} .ant-breaking-news-ticker' => 'height: {{VALUE}}px;',
				],
			]
		);

		$this->add_control(
			'controls_visibility',
			[
				'type'      => \Elementor\Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Controls Button Visibility', 'advanced-news-ticker' ),
				'label_on'  => esc_html__( 'On', 'advanced-news-ticker' ),
				'label_off' => esc_html__( 'Off', 'advanced-news-ticker' ),
				'default'   => 'yes',
			]
		);

		$this->add_control(
			'pause_visibility',
			[
				'type'      => \Elementor\Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Pause Button Visibility', 'advanced-news-ticker' ),
				'label_on'  => esc_html__( 'On', 'advanced-news-ticker' ),
				'label_off' => esc_html__( 'Off', 'advanced-news-ticker' ),
				'default'   => 'yes',
				'condition' => [
					'controls_visibility' => 'yes',
				],
			]
		);

		$this->add_control(
			'ticker_primary_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Primary Color', 'neuzin-core' ),
				'selectors' => [
					'body {{WRAPPER}} .ant-breaking-news-ticker' => '--ticker-primary-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'ticker_secondary_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Secondary Color', 'neuzin-core' ),
				'selectors' => [
					'body {{WRAPPER}} .ant-breaking-news-ticker' => '--ticker-secondary-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function breaking_title() {
		$this->start_controls_section(
			'breaking_settings',
			[
				'label' => __( 'Breaking Title', 'advanced-news-ticker' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typo',
				'label'    => esc_html__( 'Typography', 'advanced-news-ticker' ),
				'selector' => '{{WRAPPER}} .ant-breaking-news-ticker .ticker-label',
			]
		);

		$this->add_responsive_control(
			'breaking_title_radius',
			[
				'label'      => __( 'Radius', 'advanced-news-ticker' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .ant-breaking-news-ticker .ticker-label' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);
		$this->add_responsive_control(
			'breaking_title_padding',
			[
				'label'              => __( 'padding', 'advanced-news-ticker' ),
				'type'               => Controls_Manager::DIMENSIONS,
				'allowed_dimensions' => 'horizontal',
				'size_units'         => [ 'px' ],
				'selectors'          => [
					'{{WRAPPER}} .ant-breaking-news-ticker .ticker-label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'breaking_title_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Color', 'advanced-news-ticker' ),
				'selectors' => [
					'{{WRAPPER}} .ant-breaking-news-ticker .ticker-label' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'breaking_title_bg',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Background', 'advanced-news-ticker' ),
				'selectors' => [
					'{{WRAPPER}} .ant-breaking-news-ticker .ticker-label' => 'background-color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_responsive_control(
			'breaking_title_tune',
			[
				'label'      => __( 'Title Offset Y', 'advanced-news-ticker' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => - 10,
						'max'  => 10,
						'step' => 0.1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .ant-breaking-news-ticker .ticker-label' => 'transform: translateY({{SIZE}}px);',
				],
			]
		);

		$this->add_control(
			'breaking_title_icon_heading',
			[
				'type'  => Controls_Manager::HEADING,
				'label' => esc_html__( 'Icon Settings', 'advanced-news-ticker' ),
			]
		);

		$this->add_control(
			'breaking_icon',
			[
				'label'            => __( 'Breaking Icon', 'advanced-news-ticker' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'skin'             => 'inline',
				'label_block'      => FALSE,
				'default'          => [
					'value'   => 'fas fa-bolt',
					'library' => 'fa-solid',
				],
			]
		);

		$this->add_control(
			'breaking_icon_post',
			[
				'label'     => __( 'Icon Position', 'advanced-news-ticker' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'default'   => 'left',
				'options'   => [
					'left'  => esc_html__( 'Left', 'advanced-news-ticker' ),
					'right' => esc_html__( 'Right', 'advanced-news-ticker' ),
					'both'  => esc_html__( 'Both', 'advanced-news-ticker' ),
				],
				'condition' => [
					'breaking_icon[value]!' => '',
				],
			]
		);

		$this->add_control(
			'breaking_icon_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Color', 'advanced-news-ticker' ),
				'selectors' => [
					'{{WRAPPER}} .ant-breaking-news-ticker .ticker-label .elementor-icon' => 'color: {{VALUE}} !important;',
				],
				'condition' => [
					'breaking_icon[value]!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'breaking_icon_size',
			[
				'label'      => __( 'Icon Size', 'advanced-news-ticker' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 5,
						'max'  => 100,
						'step' => 1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .ant-breaking-news-ticker .ticker-label .elementor-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'breaking_icon[value]!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'breaking_icon_tune',
			[
				'label'      => __( 'Icon Offset Y', 'advanced-news-ticker' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => - 10,
						'max'  => 10,
						'step' => 0.1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .ant-breaking-news-ticker .ticker-label .elementor-icon' => 'transform: translateY({{SIZE}}px);',
				],
				'condition'  => [
					'breaking_icon[value]!' => '',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function post_title() {
		$this->start_controls_section(
			'post_title_settings',
			[
				'label' => __( 'Post Title', 'advanced-news-ticker' ),
				'tab'   => Controls_Manager::TAB_STYLE,

			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'post_title_typo',
				'label'    => esc_html__( 'Typography', 'advanced-news-ticker' ),
				'selector' => '{{WRAPPER}} .ant-breaking-news-ticker .ticker-news a',
			]
		);

		$this->add_control(
			'post_title_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Color', 'advanced-news-ticker' ),
				'selectors' => [
					'{{WRAPPER}} .ant-breaking-news-ticker .ticker-news a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'post_title_color_hover',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Color:hover', 'advanced-news-ticker' ),
				'selectors' => [
					'{{WRAPPER}} .ant-breaking-news-ticker .ticker-news a:hover' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'post_title_tune',
			[
				'label'      => __( 'Title Offset Y', 'advanced-news-ticker' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => - 10,
						'max'  => 10,
						'step' => 0.1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .ant-breaking-news-ticker .ticker-news a' => 'transform: translateY({{SIZE}}px);',
				],
			]
		);
		$this->add_control(
			'post_title_icon_heading',
			[
				'type'  => Controls_Manager::HEADING,
				'label' => esc_html__( 'Icon Settings', 'advanced-news-ticker' ),
			]
		);

		$this->add_control(
			'post_title_icon',
			[
				'label'            => __( 'Post Title Icon', 'advanced-news-ticker' ),
				'type'             => Controls_Manager::ICONS,
				'skin'             => 'inline',
				'label_block'      => FALSE,
				'fa4compatibility' => 'icon',
				'default'          => [
					'value'   => 'far fa-dot-circle',
					'library' => 'fa-regular',
				],
			]
		);

		$this->add_control(
			'post_title_icon_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Icon Color', 'advanced-news-ticker' ),
				'selectors' => [
					'{{WRAPPER}} .ant-breaking-news-ticker .ticker-news .elementor-icon' => 'color: {{VALUE}} !important;',
				],
				'condition' => [
					'post_title_icon[value]!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'post_title_size',
			[
				'label'      => __( 'Icon Size', 'advanced-news-ticker' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 5,
						'max'  => 100,
						'step' => 1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .ant-breaking-news-ticker .ticker-news .elementor-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'post_title_icon[value]!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'title_icon_tune',
			[
				'label'      => __( 'Icon Offset Y', 'advanced-news-ticker' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => - 10,
						'max'  => 10,
						'step' => 0.1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .ant-breaking-news-ticker .ticker-news .elementor-icon' => 'transform: translateY({{SIZE}}px);',
				],
				'condition'  => [
					'post_title_icon[value]!' => '',
				],
			]
		);

		$this->add_control(
			'title_spacing',
			[
				'label'              => __( 'Icon Spacing', 'neuzin-core' ),
				'type'               => Controls_Manager::DIMENSIONS,
				'size_units'         => [ 'px' ],
				'selectors'          => [
					'{{WRAPPER}} .ant-breaking-news-ticker .ticker-news .elementor-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'allowed_dimensions' => 'horizontal',
				'default'            => [
					'top'      => '',
					'right'    => '',
					'bottom'   => '',
					'left'     => '',
					'isLinked' => FALSE,
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Controls
	 *
	 * @return void
	 */
	protected function newsticker_controls() {
		$this->start_controls_section(
			'controls_settings',
			[
				'label' => __( 'Controls', 'advanced-news-ticker' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'Controls_wrap_radius',
			[
				'label'      => __( 'Controls Wrapper Radius', 'advanced-news-ticker' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .ant-breaking-news-ticker .ticker-controls' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'button_radius',
			[
				'label'      => __( 'Controls Button Radius', 'advanced-news-ticker' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .ant-breaking-news-ticker .ticker-controls button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'button_width',
			[
				'label'      => __( 'Button Width', 'neuzin-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 10,
						'max'  => 80,
						'step' => 1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .ant-breaking-news-ticker .ticker-controls button' => 'width:{{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'button_height',
			[
				'label'      => __( 'Button Height', 'neuzin-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 1,
						'max'  => 60,
						'step' => 1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .ant-breaking-news-ticker .ticker-controls button' => 'height:{{SIZE}}{{UNIT}};max-height:100%;',
				],
			]
		);

		$this->add_responsive_control(
			'btn_wrapper_position',
			[
				'label'      => __( 'Button Horizontal Position', 'neuzin-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 200,
						'step' => 1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .ant-breaking-news-ticker .ticker-controls' => '--raadd-ticker-pos:{{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'button_gap',
			[
				'label'      => __( 'Button Gap', 'neuzin-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 30,
						'step' => 0.5,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .ant-breaking-news-ticker .ticker-controls' => 'gap:{{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs(
			'controls_tabs'
		);

		$this->start_controls_tab(
			'controls_normal_tab',
			[
				'label' => __( 'Normal', 'advanced-news-ticker' ),
			]
		);

		$this->add_control(
			'controls_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Color', 'advanced-news-ticker' ),
				'selectors' => [
					'{{WRAPPER}} .ant-breaking-news-ticker .ticker-controls button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'controls_bg',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Background Color', 'advanced-news-ticker' ),
				'selectors' => [
					'{{WRAPPER}} .ant-breaking-news-ticker .ticker-controls button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'controls_hover_tab',
			[
				'label' => __( 'Hover', 'advanced-news-ticker' ),
			]
		);

		$this->add_control(
			'controls_color_hover',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Color:hover', 'advanced-news-ticker' ),
				'selectors' => [
					'{{WRAPPER}} .ant-breaking-news-ticker .ticker-controls button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'controls_bg_hover',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Background Color:hover', 'advanced-news-ticker' ),
				'selectors' => [
					'{{WRAPPER}} .ant-breaking-news-ticker .ticker-controls button:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function wrapper_controls() {
		$this->start_controls_section(
			'wrapper_settings',
			[
				'label' => __( 'Wrapper', 'advanced-news-ticker' ),
				'tab'   => Controls_Manager::TAB_STYLE,

			]
		);

		$this->add_control(
			'wrapper_bg',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Background', 'advanced-news-ticker' ),
				'selectors' => [
					'{{WRAPPER}} .ant-breaking-news-ticker' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => 'wrap_border',
				'selector' => '{{WRAPPER}} .ant-breaking-news-ticker',
			]
		);

		$this->add_responsive_control(
			'box_radius',
			[
				'label'      => __( 'Radius', 'advanced-news-ticker' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'body {{WRAPPER}} .ant-breaking-news-ticker' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Content Render
	 *
	 * @return void
	 */
	protected function render() {
		$data     = $this->get_settings();
		$args     = Fns::query_args( $data );
		$query    = new \WP_Query( $args );
		$layout   = $data['layout'] ?? '1';
		$sendData = [
			'breaking_title'      => $data['breaking_title'],
			'breaking_icon'       => $data['breaking_icon'],
			'post_title_icon'     => $data['post_title_icon'],
			'controls_visibility' => $data['controls_visibility'],
			'pause_visibility'    => $data['pause_visibility'],
			'breaking_icon_post'  => $data['breaking_icon_post'],
		];

		$height_calculation = 40;
		if ( ! empty( $data['height'] ) && $data['height'] > 20 ) {
			$height_calculation = $data['height'];
		} elseif ( '1' == $layout ) {
			$height_calculation = 30;
		} elseif ( '8' == $layout ) {
			$height_calculation = 60;
		}
		$ticker_obj = [
			'position'  => $data['position'] ?? '',
			'direction' => is_rtl() ? 'rtl' : 'ltr',
			'effect'    => $data['effect'] ?? '',
			'height'    => intval( $height_calculation ),
		];

		$sendData['ticker_obj'] = $ticker_obj;
		$sendData['query']      = $query;

		Fns::get_template( "elementor/news-ticker/style-1", $sendData );
	}

}