<?php
/**
 * @author  devofwp
 * @since   1.0
 * @version 1.0
 */

namespace devofwp\AdvancedNewsTicker\Elementor\Addons;

use Elementor\Controls_Manager;
use devofwp\AdvancedNewsTicker\Helper\Fns;
use devofwp\AdvancedNewsTicker\Abstracts\ElementorBase;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Background;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Counter Class
 */
class NewsTicker extends ElementorBase {

	/**
	 * Class Constructor
	 *
	 * @param $data
	 * @param $args
	 *
	 * @throws \Exception
	 */
	public function __construct( $data = [], $args = null ) {
		$this->ticker_name = esc_html__( 'News Ticker', 'advanced-news-ticker' );
		$this->ticker_base = 'advanced-news-ticker';
		parent::__construct( $data, $args );
	}

	public function get_script_depends() {
		return [ 'advanced-news-ticker-scripts' ];
	}

	public function get_style_depends() {
		return [ 'advanced-news-ticker-styles' ];
	}

	/**
	 * Register Controls
	 *
	 * @return void
	 */
	protected function register_controls() {
		$this->general_settings();
		$this->query();
		$this->ticker_style();
		$this->ticker_title();
		$this->ticker_post_title();
		$this->ticker_control();
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
					'1'   => [
						'title' => esc_html__( 'Layout 1', 'advanced-news-ticker' ),
						'url'   => esc_url( Fns::get_assets_url( 'images/layout/newsticker-1.svg' ) ),
					],
					'2'   => [
						'title' => esc_html__( 'Layout 2', 'advanced-news-ticker' ),
						'url'   => esc_url( Fns::get_assets_url( 'images/layout/newsticker-2.svg' ) ),
					],
					'3'   => [
						'title' => esc_html__( 'Layout 3', 'advanced-news-ticker' ),
						'url'   => esc_url( Fns::get_assets_url( 'images/layout/newsticker-3.svg' ) ),
					],
					'4'   => [
						'title' => esc_html__( 'Layout 4', 'advanced-news-ticker' ),
						'url'   => esc_url( Fns::get_assets_url( 'images/layout/newsticker-4.svg' ) ),
					],
					'5'   => [
						'title' => esc_html__( 'Layout 5', 'advanced-news-ticker' ),
						'url'   => esc_url( Fns::get_assets_url( 'images/layout/newsticker-5.svg' ) ),
					],
					'6'   => [
						'title' => esc_html__( 'Layout 6', 'advanced-news-ticker' ),
						'url'   => esc_url( Fns::get_assets_url( 'images/layout/newsticker-6.svg' ) ),
					],
					'7'   => [
						'title' => esc_html__( 'Layout 7', 'advanced-news-ticker' ),
						'url'   => esc_url( Fns::get_assets_url( 'images/layout/newsticker-7.svg' ) ),
					],
					'8'   => [
						'title' => esc_html__( 'Layout 8', 'advanced-news-ticker' ),
						'url'   => esc_url( Fns::get_assets_url( 'images/layout/newsticker-8.svg' ) ),
					],
				],
				'classes'      => 'columns-2',
				'default'      => '1',
				'prefix_class' => 'ticker-style-',
				'render_type'  => 'template',
			]
		);

		$this->add_control(
			'title',
			[
				'label'   => esc_html__( 'Breaking Title', 'advanced-news-ticker' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Breaking News', 'advanced-news-ticker' ),
			]
		);

		$this->add_control(
			'control_visibility',
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
					'control_visibility' => 'yes',
				]
			]
		);

		$this->add_control(
			'ticker_settings',
			[
				'label' => esc_html__( 'Ticker Settings', 'advanced-news-ticker' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'ticker_animation',
			[
				'label'   => esc_html__( 'Animation', 'advanced-news-ticker' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'horizontal',
				'options' => [
					'horizontal' => esc_html__( 'Slide Horizontal', 'advanced-news-ticker' ),
					'vertical'   => esc_html__( 'Slide Vertical', 'advanced-news-ticker' ),
					'type'       => esc_html__( 'Typing', 'advanced-news-ticker' ),
					'marquee'    => esc_html__( 'Marquee Scroll', 'advanced-news-ticker' ),
				],
			]
		);

		$this->add_control(
			'marquee_direction',
			[
				'label'     => esc_html__( 'Direction', 'advanced-news-ticker' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'right',
				'options'   => [
					'right' => esc_html__( 'Right', 'advanced-news-ticker' ),
					'left'  => esc_html__( 'Left', 'advanced-news-ticker' ),
				],
				'condition' => [
					'ticker_animation' => 'marquee',
				]
			]
		);
		$this->add_control(
			'marquee_behavior',
			[
				'label'     => esc_html__( 'Behavior', 'advanced-news-ticker' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'alternate',
				'options'   => [
					'alternate' => esc_html__( 'Alternatel', 'advanced-news-ticker' ),
					'scroll'    => esc_html__( 'Scroll', 'advanced-news-ticker' ),
				],
				'condition' => [
					'ticker_animation' => 'marquee',
				]
			]
		);

		$this->add_control(
			'scrollamount',
			[
				'label'     => esc_html__( 'Speed', 'advanced-news-ticker' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 5,
				'condition' => [
					'ticker_animation' => 'marquee',
				]
			]
		);
		$this->add_control(
			'speed',
			[
				'label'     => esc_html__( 'Speed', 'advanced-news-ticker' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 300,
				'condition' => [
					'ticker_animation!' => 'marquee',
				]
			]
		);

		$this->add_control(
			'delay',
			[
				'label'     => esc_html__( 'Delay', 'advanced-news-ticker' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 1500,
				'condition' => [
					'ticker_animation!' => 'marquee',
				]
			]
		);

		$this->add_control(
			'pause_on_hover',
			[
				'label'        => esc_html__( 'Pause On Hover', 'advanced-news-ticker' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'On', 'advanced-news-ticker' ),
				'label_off'    => esc_html__( 'Off', 'advanced-news-ticker' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'ticker_animation!' => 'marquee',
				]
			]
		);

		$this->end_controls_section();
	}

	protected function query() {
		$this->start_controls_section(
			'query_section',
			[
				'label' => esc_html__( 'Query', 'advanced-news-ticker' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$post_types = Fns::get_post_types();
		$this->add_control(
			'post_type',
			[
				'label'   => esc_html__( 'Post Type', 'advanced-news-ticker' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $post_types,
				'default' => 'post',
			]
		);
		$this->add_control(
			'post_limit',
			[
				'label'       => esc_html__( 'Post Per Page', 'advanced-news-ticker' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Enter Post Limit', 'advanced-news-ticker' ),
				'description' => esc_html__( 'Enter number of post to show.', 'advanced-news-ticker' ),
				'default'     => '12',
			]
		);

		foreach ( $post_types as $post_type => $post_label ) {
			$this->add_control(
				$post_type . '_ids',
				[
					'type'                 => 'advanced-news-ticker-select2',
					'label'                => esc_html__( 'Choose ', 'advanced-news-ticker' ) . $post_label,
					'source_name'          => 'post_type',
					'source_type'          => $post_type,
					'multiple'             => true,
					'label_block'          => true,
					'minimum_input_length' => 3,
					'condition'            => [
						'post_type' => $post_type,
					],
				]
			);
		}
		$taxonomies = get_taxonomies( [], 'objects' );

		foreach ( $taxonomies as $taxonomy => $object ) {
			if ( ! isset( $object->object_type[0] )
			     || ! in_array( $object->object_type[0], array_keys( $post_types ) )
			     || in_array( $taxonomy, Fns::get_excluded_taxonomy() )
			) {
				continue;
			}
			$this->add_control(
				$taxonomy . '_ids',
				[
					'label'       => esc_html__( 'By ', 'advanced-news-ticker' ) . $object->label,
					'type'        => Controls_Manager::SELECT2,
					'label_block' => true,
					'multiple'    => true,
					'options'     => Fns::get_categories_by_id( $taxonomy ),
					'condition'   => [
						'post_type' => $object->object_type,
					],
					'description' => "Post by " . $object->label,
				]
			);
		}


		$this->add_control(
			'tax_relation',
			[
				'label'   => esc_html__( 'Taxonomy Relation', 'advanced-news-ticker' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'OR'  => esc_html__( 'OR', 'advanced-news-ticker' ),
					'AND' => esc_html__( 'AND', 'advanced-news-ticker' ),
				],
				'default' => 'OR',
			]
		);

		$this->add_control(
			'offset',
			[
				'label'       => esc_html__( 'Post offset', 'advanced-news-ticker' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Enter Post offset', 'advanced-news-ticker' ),
				'description' => esc_html__( 'Number of post to displace or pass over. The offset parameter is ignored when post limit => -1 (show all posts) is used.', 'advanced-news-ticker' ),
			]
		);

		$this->add_control(
			'exclude',
			[
				'type'                 => 'advanced-news-ticker-select2',
				'label'                => esc_html__( 'Exclude posts', 'advanced-news-ticker' ),
				'description'          => esc_html__( 'Choose posts for exclude', 'advanced-news-ticker' ),
				'source_name'          => 'post',
				'source_type'          => 'post',
				'multiple'             => true,
				'label_block'          => true,
				'minimum_input_length' => 3,
			]
		);

		$this->add_control(
			'orderby',
			[
				'label'   => esc_html__( 'Order by', 'advanced-news-ticker' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'date',
				'options' => [
					'date'           => esc_html__( 'Date', 'advanced-news-ticker' ),
					'ID'             => esc_html__( 'Order by post ID', 'advanced-news-ticker' ),
					'author'         => esc_html__( 'Author', 'advanced-news-ticker' ),
					'title'          => esc_html__( 'Title', 'advanced-news-ticker' ),
					'modified'       => esc_html__( 'Last modified date', 'advanced-news-ticker' ),
					'parent'         => esc_html__( 'Post parent ID', 'advanced-news-ticker' ),
					'comment_count'  => esc_html__( 'Number of comments', 'advanced-news-ticker' ),
					'menu_order'     => esc_html__( 'Menu order', 'advanced-news-ticker' ),
					'meta_value'     => esc_html__( 'Meta value', 'advanced-news-ticker' ),
					'meta_value_num' => esc_html__( 'Meta value number', 'advanced-news-ticker' ),
					'rand'           => esc_html__( 'Random order', 'advanced-news-ticker' ),
				],
			]
		);

		$this->add_control(
			'meta_key',
			[
				'label'     => esc_html__( 'Meta Key', 'advanced-news-ticker' ),
				'type'      => Controls_Manager::TEXT,
				'condition' => [
					'orderby' => [ 'meta_value', 'meta_value_num' ],
				],
			]
		);

		$this->add_control(
			'order',
			[
				'label'   => esc_html__( 'Sort order', 'advanced-news-ticker' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'DESC',
				'options' => [
					'ASC'  => esc_html__( 'ASC', 'advanced-news-ticker' ),
					'DESC' => esc_html__( 'DESC', 'advanced-news-ticker' ),
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Newsticker Style
	 *
	 * @return void
	 */
	public function ticker_style() {
		$this->start_controls_section(
			'ticker_style',
			[
				'label' => esc_html__( 'News Ticker', 'advanced-news-ticker' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'advanced-news-ticker' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} :is(.advanced-news-ticker-inner, .ticker-title, .navigation .news-ticker-nav)' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'padding',
			[
				'label'      => esc_html__( 'Padding', 'advanced-news-ticker' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .advanced-news-ticker-inner' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => 'ticker_border',
				'label'    => esc_html__( 'Border', 'advanced-news-ticker' ),
				'selector' => '{{WRAPPER}} .advanced-news-ticker-inner',
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => 'ticker_background_color',
				'label'          => esc_html__( 'Title Background', 'advanced-news-ticker' ),
				'types'          => [ 'classic', 'gradient' ],
				'selector'       => '{{WRAPPER}} .advanced-news-ticker-inner',
				'exclude'        => [ 'image' ],
				'fields_options' => [
					'background' => [
						'label' => esc_html__( 'Title Background', 'advanced-news-ticker' ),
					],
					'color'      => [
						'label' => 'Background Color',
					],
					'color_b'    => [
						'label' => 'Background Color 2',
					],
				],
			]
		);
		$this->add_control(
			'height',
			[
				'label'     => esc_html__( 'Height', 'advanced-news-ticker' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 30,
				'selectors' => [
					'{{WRAPPER}} .advanced-news-ticker-inner' => 'height:{{VALUE}}px;',
				],
			]
		);


		$this->end_controls_section();
	}

	/**
	 * Newsticker Title settings
	 *
	 * @return void
	 */
	public function ticker_title() {
		$this->start_controls_section(
			'ticker_title',
			[
				'label' => esc_html__( 'Breaking Title', 'advanced-news-ticker' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'breaking_title_typography',
				'label'    => esc_html__( 'Typography', 'advanced-news-ticker' ),
				'selector' => '{{WRAPPER}} .advanced-news-ticker-inner .ticker-title',
			]
		);

		$this->add_control(
			'breaking_title_color',
			[
				'label'     => esc_html__( 'Title Color', 'advanced-news-ticker' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .advanced-news-ticker-inner .ticker-title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => 'title_background_color',
				'label'          => esc_html__( 'Title Background', 'advanced-news-ticker' ),
				'types'          => [ 'classic', 'gradient' ],
				'selector'       => '{{WRAPPER}} .advanced-news-ticker-inner .ticker-title',
				'exclude'        => [ 'image' ],
				'fields_options' => [
					'background' => [
						'label' => esc_html__( 'Title Background', 'advanced-news-ticker' ),
					],
					'color'      => [
						'label' => 'Background Color',
					],
					'color_b'    => [
						'label' => 'Background Color 2',
					],
				],
			]
		);

		$this->add_control(
			'breaking_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'advanced-news-ticker' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .advanced-news-ticker-inner .ticker-title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => 'breaking_border',
				'label'    => esc_html__( 'Border', 'advanced-news-ticker' ),
				'selector' => '{{WRAPPER}} .advanced-news-ticker-inner .ticker-title',
			]
		);

		/**
		 * Icon Settings
		 */
		$this->add_control(
			'title_icon_heading',
			[
				'label' => esc_html__( 'Icon Setting', 'advanced-news-ticker' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'show_icon',
			[
				'label'        => esc_html__( 'Show Icon', 'advanced-news-ticker' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'breaking_icon',
			[
				'label'     => esc_html__( 'Choose Icon', 'advanced-news-ticker' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'default',
				'options'   => [
					'default'      => esc_html__( 'Default', 'advanced-news-ticker' ),
					'bolt-round'   => esc_html__( 'Bolt Circle', 'advanced-news-ticker' ),
					'bolt-round-2' => esc_html__( 'Bolt Circle 2', 'advanced-news-ticker' ),
					'bolt-round-3' => esc_html__( 'Bolt Circle 3', 'advanced-news-ticker' ),
					'bolt'         => esc_html__( 'Bolt Light', 'advanced-news-ticker' ),
					'bolt-2'       => esc_html__( 'Bolt Solid', 'advanced-news-ticker' ),
					'live'         => esc_html__( 'Live Circle', 'advanced-news-ticker' ),
					'custom'       => esc_html__( 'Custom Icon', 'advanced-news-ticker' ),
				],
				'condition' => [
					'show_icon' => 'yes',
				]
			]
		);


		$this->add_control(
			'title_icon',
			[
				'label'     => esc_html__( 'Choose Icon', 'advanced-news-ticker' ),
				'type'      => Controls_Manager::ICONS,
				'condition' => [
					'show_icon'     => 'yes',
					'breaking_icon' => 'custom',
				]
			]
		);

		$this->add_control(
			'live_animation',
			[
				'label'        => esc_html__( 'Live Animation', 'advanced-news-ticker' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'default'      => false,
				'return_value' => 'yes',
				'prefix_class' => 'live-animation-',
				'condition'    => [
					'show_icon' => 'yes',
				]
			]
		);

		$this->add_responsive_control(
			'breaking_icon_size',
			[
				'label'      => esc_html__( 'Icon Size', 'advanced-news-ticker' ),
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
					'{{WRAPPER}} .ticker-title :is(i, svg)' => 'font-size: {{SIZE}}px;',
				],
				'condition'  => [
					'show_icon' => 'yes',
				]
			]
		);

		$this->add_control(
			'breaking_icon_color',
			[
				'label'     => esc_html__( 'Icon Color', 'advanced-news-ticker' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .advanced-news-ticker-inner .ticker-title :is(i, svg)' => 'color: {{VALUE}}',
				],
				'condition' => [
					'show_icon' => 'yes',
				]
			]
		);

		$this->add_control(
			'title_position',
			[
				'label' => esc_html__( 'Position Tune (optional)', 'advanced-news-ticker' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_responsive_control(
			'title_icon_y_post',
			[
				'label'      => esc_html__( 'Icon Vertical Position', 'advanced-news-ticker' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => - 30,
						'max'  => 30,
						'step' => 0.1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .advanced-news-ticker-inner .ticker-live-icon' => 'transform: translateY({{SIZE}}px)',
				],
				'condition'  => [
					'show_icon' => 'yes',
				]
			]
		);

		$this->add_responsive_control(
			'title_y_post',
			[
				'label'      => esc_html__( 'Title Vertical Position', 'advanced-news-ticker' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => - 40,
						'max'  => 40,
						'step' => 0.1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .advanced-news-ticker-inner .breaking-title' => 'transform: translateY({{SIZE}}px)',
				],
			]
		);
		$this->end_controls_section();
	}

	/**
	 * Newsticker Post Title
	 *
	 * @return void
	 */
	public function ticker_post_title() {
		$this->start_controls_section(
			'ticker_post_title',
			[
				'label' => esc_html__( 'Post Title', 'advanced-news-ticker' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'post_typography',
				'label'    => esc_html__( 'Typography', 'advanced-news-ticker' ),
				'selector' => '{{WRAPPER}} .advanced-news-ticker-inner .post-link',
			]
		);

		$this->add_control(
			'post_title_link',
			[
				'label'        => esc_html__( 'Enable Link?', 'advanced-news-ticker' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'post_icon',
			[
				'label'   => esc_html__( 'Icon', 'advanced-news-ticker' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'star'         => esc_html__( 'Star Icon', 'advanced-news-ticker' ),
					'star-outline' => esc_html__( 'Star-Outline Icon', 'advanced-news-ticker' ),
					'dot'          => esc_html__( 'Dot Icon', 'advanced-news-ticker' ),
					'bolt-round'   => esc_html__( 'Bolt Circle', 'advanced-news-ticker' ),
					'bolt-round-2' => esc_html__( 'Bolt Circle 2', 'advanced-news-ticker' ),
					'bolt-round-3' => esc_html__( 'Bolt Circle 3', 'advanced-news-ticker' ),
					'bolt'         => esc_html__( 'Bolt Light', 'advanced-news-ticker' ),
					'bolt-2'       => esc_html__( 'Bolt Solid', 'advanced-news-ticker' ),
					'live'         => esc_html__( 'Live Circle', 'advanced-news-ticker' ),
					'custom'       => esc_html__( 'Custom Icon', 'advanced-news-ticker' ),
				],
				'default' => 'live',
			]
		);

		$this->add_control(
			'custom_post_icon',
			[
				'label'     => esc_html__( 'Custom Icon', 'advanced-news-ticker' ),
				'type'      => Controls_Manager::ICONS,
				'default'   => [
					'value'   => 'far fa-circle',
					'library' => 'fa-regular',
				],
				'condition' => [
					'post_icon' => 'custom',
				],
			]
		);

		$this->add_responsive_control(
			'post_icon_size',
			[
				'label'      => esc_html__( 'Icon Size', 'advanced-news-ticker' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 4,
						'max'  => 50,
						'step' => 1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .advanced-news-ticker-inner .ticker-content :is(svg, i)' => 'font-size: {{SIZE}}px;',
				],
				'condition'  => [
					'post_icon!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'post_icon_gap',
			[
				'label'      => esc_html__( 'Icon Gap', 'advanced-news-ticker' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .advanced-news-ticker-inner .ticker-content :is(svg, i)' => 'margin-right: {{SIZE}}px;',
					'{{WRAPPER}} .advanced-news-ticker-inner .post-link'                  => 'margin-right: {{SIZE}}px;',
				],
				'condition'  => [
					'post_icon!' => 'none',
				],
			]
		);

		$this->add_control(
			'post_title_color',
			[
				'label'     => esc_html__( 'Title Color', 'advanced-news-ticker' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .advanced-news-ticker-inner .post-link' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'post_title_color_hover',
			[
				'label'     => esc_html__( 'Title Color:hover', 'advanced-news-ticker' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .advanced-news-ticker-inner .post-link:hover' => 'color: {{VALUE}}',
				],
				'condition' => [
					'post_title_link' => 'yes',
				],
			]
		);

		$this->add_control(
			'post_icon_color',
			[
				'label'     => esc_html__( 'Icon Color', 'advanced-news-ticker' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .advanced-news-ticker-inner .ticker-content :is(i, svg)' => 'color: {{VALUE}}',
				],
				'condition' => [
					'post_icon!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'post_title_icon_pos',
			[
				'label'      => esc_html__( 'Icon Vertical Position', 'advanced-news-ticker' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => - 20,
						'max'  => 20,
						'step' => .5,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .advanced-news-ticker-inner .ticker-content .post-icon' => 'transform:translateY({{SIZE}}{{UNIT}});',
				],
			]
		);

		$this->add_responsive_control(
			'post_title_pos',
			[
				'label'      => esc_html__( 'Title Vertical Position', 'advanced-news-ticker' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => - 20,
						'max'  => 20,
						'step' => .5,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .advanced-news-ticker-inner .ticker-content .post-link' => 'transform:translateY({{SIZE}}{{UNIT}});',
				],
			]
		);
		$this->end_controls_section();
	}

	/**
	 * Newsticker Control
	 *
	 * @return void
	 */
	public function ticker_control() {
		$this->start_controls_section(
			'ticker_control',
			[
				'label'     => esc_html__( 'Control Button', 'advanced-news-ticker' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'ticker_animation!'  => 'marquee',
					'control_visibility' => 'yes'
				],
			]
		);

		$this->add_control(
			'control_prev_icon',
			[
				'label'            => esc_html__( 'Prev Icon', 'advanced-news-ticker' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'recommended'      => [
					'fa-solid'   => [
						'chevron-left',
						'angle-left',
						'angle-double-left',
						'caret-left',
						'arrow-left',
						'caret-square-left',
						'long-arrow-alt-left'
					],
					'fa-regular' => [
						'caret-square-left',
						'arrow-right',
					],
				],
				'skin'             => 'inline',
				'label_block'      => false,
			]
		);

		$this->add_control(
			'control_next_icon',
			[
				'label'            => esc_html__( 'Next Icon', 'advanced-news-ticker' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'recommended'      => [
					'fa-solid'   => [
						'chevron-right',
						'angle-right',
						'angle-double-right',
						'caret-right',
						'arrow-right',
						'caret-square-right',
						'long-arrow-alt-right'
					],
					'fa-regular' => [
						'caret-square-right',
						'arrow-right',
					],
				],
				'skin'             => 'inline',
				'label_block'      => false,
			]
		);

		$this->add_control(
			'control_pause_icon',
			[
				'label'            => esc_html__( 'Pause Icon', 'advanced-news-ticker' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'recommended'      => [
					'fa-solid'   => [
						'pause',
						'pause-circle'
					],
					'fa-regular' => [
						'pause-circle',
					],
				],
				'skin'             => 'inline',
				'label_block'      => false,
			]
		);

		$this->add_responsive_control(
			'control_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'advanced-news-ticker' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .advanced-news-ticker-inner .navigation .news-ticker-nav' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'icon_size',
			[
				'label'      => esc_html__( 'Icon Size', 'advanced-news-ticker' ),
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
					'{{WRAPPER}} .advanced-news-ticker-inner .news-ticker-nav :is(svg, i)' => 'font-size:{{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_width',
			[
				'label'      => esc_html__( 'Icon Width', 'advanced-news-ticker' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 8,
						'max'  => 60,
						'step' => 1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .advanced-news-ticker-inner .navigation .news-ticker-nav' => 'width:{{SIZE}}{{UNIT}};',
				],
			]
		);


		$this->add_responsive_control(
			'icon_height',
			[
				'label'      => esc_html__( 'Icon Height', 'advanced-news-ticker' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 8,
						'max'  => 60,
						'step' => 1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .advanced-news-ticker-inner .navigation .news-ticker-nav' => 'height:{{SIZE}}{{UNIT}};',
				],
			]
		);


		$this->add_control(
			'control_color',
			[
				'label'     => esc_html__( 'Icon Color', 'advanced-news-ticker' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .advanced-news-ticker-inner .navigation .news-ticker-nav' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'control_color_h',
			[
				'label'     => esc_html__( 'Icon color:hover', 'advanced-news-ticker' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .advanced-news-ticker-inner .navigation .news-ticker-nav:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'control_bg',
			[
				'label'     => esc_html__( 'Control Background', 'advanced-news-ticker' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .advanced-news-ticker-inner .navigation .news-ticker-nav' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'control_bg_h',
			[
				'label'     => esc_html__( 'Control Background:hover', 'advanced-news-ticker' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .advanced-news-ticker-inner .navigation .news-ticker-nav:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();
	}

	public function swiper_slider( $query, $data ) {

		$speed = $data['speed'] ?? 300;

		if ( $data['ticker_animation'] == 'type' ) {
			$speed = 0;
		}

		$swiperConfig = [
			'slidesPerView'     => 1,
			'speed'             => $speed,
			'effect'            => 'fade',
			'loop'              => true,
			'allowTouchMove'    => false,
			'pauseOnMouseEnter' => boolval( $data['pause_on_hover'] ),
			'autoplay'          => [
				'delay'                => $data['delay'] ?? 2000,
				'disableOnInteraction' => true,
			],
			'navigation'        => [
				'nextEl' => '.newsticker-button-next',
				'prevEl' => '.newsticker-button-prev',
			],
		];

		if ( in_array( $data['ticker_animation'], [ 'vertical', 'horizontal' ] ) ) {
			$swiperConfig['parallax'] = true;
		}
		$animation = '';
		if ( 'vertical' === $data['ticker_animation'] ) {
			$animation = 'data-swiper-parallax-y=-40';
		}

		if ( 'horizontal' === $data['ticker_animation'] ) {
			$animation = 'data-swiper-parallax=-120';
		}

		?>
        <div class="swiper advanced-news-ticker" data-swiper='<?php echo wp_json_encode( $swiperConfig ); ?>'>

            <div class="swiper-wrapper">
				<?php
				while ( $query->have_posts() ) :
					$query->the_post();
					?>
                    <div class="swiper-slide">
                        <div style="--transitionDuration:<?php echo esc_attr( $data['delay'] ); ?>ms" class="ticker-content" <?php echo esc_attr( $animation ); ?>>
							<?php $this->get_post_icon( $data ); ?>
							<?php if ( 'yes' == $data['post_title_link'] ) { ?>
                                <a class="post-link" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
							<?php } else { ?>
                                <span class="post-link"><?php the_title(); ?></span>
							<?php } ?>
                        </div>
                    </div>
				<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>
            </div>
			<?php if ( 'yes' == $data['control_visibility'] ) : ?>
                <div class="navigation">
                    <div class="newsticker-button-prev news-ticker-nav">
						<?php
						if ( ! empty( $data['control_prev_icon']['value'] ) ) {
							Icons_Manager::render_icon( $data['control_prev_icon'], [ 'aria-hidden' => 'true' ] );
						} else {
							$this->get_breaking_icon( 'prev' );
						}
						?>
                    </div>
					<?php if ( 'yes' == $data['pause_visibility'] ) : ?>
                        <div class="swiper-pause news-ticker-nav">
							<span class="pause-icon">
                                <?php
                                if ( ! empty( $data['control_pause_icon']['value'] ) ) {
	                                Icons_Manager::render_icon( $data['control_pause_icon'], [ 'aria-hidden' => 'true' ] );
                                } else {
	                                $this->get_breaking_icon( 'pause' );
                                }
                                ?>
                            </span>
                            <span class="play-icon"><?php $this->get_breaking_icon( 'play' ); ?></span>
                        </div>
					<?php endif; ?>
                    <div class="newsticker-button-next news-ticker-nav">
						<?php
						if ( ! empty( $data['control_next_icon']['value'] ) ) {
							Icons_Manager::render_icon( $data['control_next_icon'], [ 'aria-hidden' => 'true' ] );
						} else {
							$this->get_breaking_icon( 'next' );
						}
						?>
                    </div>
                </div>
			<?php endif; ?>
        </div>
		<?php
	}


	public function marquee_slider( $query, $data ) {
		$hoverEffect = '';
		if ( 'yes' === $data['pause_on_hover'] ) {
			$hoverEffect = 'onmouseover=this.stop() onmouseout=this.start()';
		}
		?>
        <div class="advanced-news-ticker advanced-news-ticker-marquee">
            <marquee class="news-scroll ticker-content"
                     behavior="<?php echo esc_attr( $data['marquee_behavior'] ) ?>"
                     direction="<?php echo esc_attr( $data['marquee_direction'] ) ?>"
                     scrollamount="<?php echo esc_attr( $data['scrollamount'] ) ?>"
				<?php echo esc_attr( $hoverEffect ); ?>
            >
                <div class="marquee-inner">
					<?php
					while ( $query->have_posts() ) :
						$query->the_post();
						$this->get_post_icon( $data );
						if ( 'yes' == $data['post_title_link'] ) {
							?>
                            <a class="post-link" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						<?php } else { ?>
                            <span class="post-link"><?php the_title(); ?></span>
							<?php
						}
					endwhile;
					?>
					<?php wp_reset_postdata(); ?>
                </div>
            </marquee>
        </div>
		<?php
	}


	public function get_post_icon( $data ) {
		if ( 'none' !== $data['post_icon'] ) {
			?>
            <span class="post-icon">
                <?php
                if ( 'custom' == $data['post_icon'] && ! empty( $data['custom_post_icon']['value'] ) ) {
	                Icons_Manager::render_icon( $data['custom_post_icon'], [ 'aria-hidden' => 'true' ] );
                } else {
	                $this->get_breaking_icon( $data['post_icon'] );
                }
                ?>
            </span>
			<?php
		}
	}

	/**
	 * Get breaking icon
	 *
	 * @param $icon
	 *
	 * @return void
	 */
	public function get_breaking_icon( $icon = '' ): void {
		switch ( $icon ) {
			case 'bolt-round':
				echo '<svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">				<path fill-rule="evenodd" clip-rule="evenodd" d="M12.7365 0.963608C6.1091 0.963608 0.736511 6.33619 0.736511 12.9636C0.736511 19.591 6.1091 24.9636 12.7365 24.9636C19.3639 24.9636 24.7365 19.591 24.7365 12.9636C24.7365 6.33619 19.3639 0.963608 12.7365 0.963608ZM13.8782 6.47904C13.9052 6.2628 13.7864 6.05462 13.5865 5.96784C13.3866 5.88106 13.1534 5.93646 13.0139 6.10388L6.46846 13.9584C6.34653 14.1048 6.32024 14.3084 6.40103 14.4809C6.48183 14.6534 6.65511 14.7636 6.8456 14.7636H12.1804L11.5948 19.4482C11.5678 19.6644 11.6866 19.8726 11.8865 19.9594C12.0864 20.0462 12.3196 19.9908 12.4591 19.8233L19.0045 11.9688C19.1265 11.8225 19.1528 11.6188 19.072 11.4463C18.9912 11.2738 18.8179 11.1636 18.6274 11.1636H13.2926L13.8782 6.47904ZM12.7365 13.7818H7.89371L12.6962 8.01874L12.2494 11.5936C12.2319 11.7333 12.2753 11.8738 12.3685 11.9794C12.4617 12.085 12.5957 12.1454 12.7365 12.1454H17.5793L12.7768 17.9085L13.2236 14.3336C13.2411 14.1939 13.1977 14.0534 13.1045 13.9478C13.0113 13.8423 12.8773 13.7818 12.7365 13.7818Z" fill="currentColor"/>				</svg>';
				break;
			case 'bolt-round-2':
				echo '<svg width="512" height="512" viewBox="0 0 512 512" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_684_107)"><path fill-rule="evenodd" clip-rule="evenodd" d="M256 0C114.615 0 0 114.615 0 256C0 397.385 114.615 512 256 512C397.385 512 512 397.385 512 256C512 114.615 397.385 0 256 0ZM221.581 401.095C222.678 401.568 223.827 401.795 224.96 401.795C226.392 401.796 227.801 401.436 229.058 400.75C230.315 400.064 231.379 399.073 232.152 397.868L351.463 212.098C352.291 210.807 352.757 209.317 352.812 207.785C352.866 206.252 352.507 204.733 351.772 203.387C351.036 202.041 349.952 200.917 348.633 200.135C347.314 199.352 345.809 198.939 344.275 198.939H277.404L311.215 122.192C311.788 120.892 312.027 119.469 311.912 118.053C311.796 116.637 311.329 115.271 310.553 114.081C308.976 111.663 306.284 110.205 303.397 110.205H224.827C223.103 110.205 221.419 110.727 219.997 111.701C218.575 112.676 217.481 114.058 216.859 115.666L159.759 263.285C159.258 264.58 159.08 265.976 159.241 267.355C159.401 268.733 159.895 270.052 160.68 271.197C161.464 272.342 162.516 273.278 163.744 273.925C164.972 274.572 166.339 274.91 167.726 274.91H245.561L216.673 391.191C216.185 393.156 216.411 395.23 217.31 397.044C218.208 398.858 219.722 400.293 221.581 401.095Z" fill="currentColor"/></g><defs><clipPath id="clip0_684_107"><rect width="512" height="512" fill="white"/></clipPath></defs></svg>';
				break;
			case 'bolt-round-3':
				echo '<svg width="1110" height="1510" viewBox="0 0 1110 1510" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M414.731 791.571L414.249 792.164L414.256 792.162L155.948 1109.51L155.945 1109.51L155.942 1109.51L155.939 1109.5C72.0437 1015.21 21.0762 890.974 21.0762 754.834C21.0762 460.038 260.055 221.06 554.85 221.06C589.391 221.06 623.165 224.34 655.878 230.607C657.458 230.211 659.012 229.822 660.538 229.44L658.627 231.141L658.782 231.172L320.809 532.148L1109.84 0.760254L796.511 278.77L796.632 278.831L135.953 865.039L414.731 791.571Z" fill="currentColor"/><path d="M693.852 718.67L954.62 401.5L954.624 401.51L954.632 401.501C1038.01 495.673 1088.62 619.521 1088.62 755.19C1088.62 1049.99 849.646 1288.96 554.85 1288.96C523.049 1288.96 491.896 1286.18 461.623 1280.85H447.757L450.167 1278.7C449.988 1278.67 449.808 1278.63 449.628 1278.6L787.954 977.117L0.00390625 1509.64L975.596 645.224L693.852 718.67Z" fill="currentColor"/></svg>';
				break;
			case 'bolt':
				echo ' <svg width="14" height="16" viewBox="0 0 14 16" fill="none" xmlns="http://www.w3.org/2000/svg">                    <path d="M7.87273 1.45456L1.32727 9.30911H7.21818L6.56363 14.5455L13.1091 6.69092H7.21818L7.87273 1.45456Z" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>                </svg>';
				break;
			case 'bolt-2':
				echo '<svg xmlns="http://www.w3.org/2000/svg" width="512" height="512" x="0" y="0" viewBox="0 0 512.002 512.002" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><path d="M201.498 512.002c-1.991 0-4.008-.398-5.934-1.229a15 15 0 0 1-8.617-17.39l50.724-204.178h-136.67a15 15 0 0 1-13.99-20.412L187.273 9.589A15 15 0 0 1 201.263 0h137.962c5.069 0 9.795 2.56 12.565 6.806a15.002 15.002 0 0 1 1.162 14.242l-59.369 134.76h117.419a15 15 0 0 1 12.621 23.105L214.126 505.106a15 15 0 0 1-12.628 6.896z" fill="currentColor" opacity="1" data-original="currentColor" class=""></path></g></svg>';
				break;
			case 'prev':
				echo '<svg width="7" height="12" viewBox="0 0 7 12" fill="none" xmlns="http://www.w3.org/2000/svg">                    <path d="M6 11L1 5.99998L6 0.999985" stroke="currentColor" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>                </svg>';
				break;
			case 'next':
				echo ' <svg width="7" height="12" viewBox="0 0 7 12" fill="none" xmlns="http://www.w3.org/2000/svg">                    <path d="M1 11L6 5.99998L1 0.999985" stroke="currentColor" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>                </svg>';
				break;
			case 'pause':
				echo '<svg width="512" height="512" viewBox="0 0 512 512" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_692_209)"><path fill-rule="evenodd" clip-rule="evenodd" d="M77.0762 452V60H145.414V452H77.0762ZM17.0762 45C17.0762 20.1472 37.2234 0 62.0762 0H160.414C185.267 0 205.414 20.1472 205.414 45V467C205.414 491.853 185.267 512 160.414 512H62.0762C37.2234 512 17.0762 491.853 17.0762 467V45ZM366.586 452V60H434.924V452H366.586ZM306.586 45C306.586 20.1472 326.733 0 351.586 0H449.924C474.777 0 494.924 20.1472 494.924 45V467C494.924 491.853 474.777 512 449.924 512H351.586C326.733 512 306.586 491.853 306.586 467V45Z" fill="currentColor"/></g><defs><clipPath id="clip0_692_209"><rect width="512" height="512" fill="white"/></clipPath></defs></svg>';
				break;
			case 'play':
				echo '<svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="512" height="512" x="0" y="0" viewBox="0 0 163.861 163.861" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><path d="M34.857 3.613C20.084-4.861 8.107 2.081 8.107 19.106v125.637c0 17.042 11.977 23.975 26.75 15.509L144.67 97.275c14.778-8.477 14.778-22.211 0-30.686L34.857 3.613z" fill="currentColor" opacity="1" data-original="currentColor" class=""></path></g></svg>';
				break;
			case 'star':
				echo '<svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">				<path d="M4.02818 0.879112C4.36444 0.197874 5.33587 0.197874 5.67214 0.879112L6.42415 2.4026C6.55756 2.67288 6.81531 2.8603 7.11355 2.90389L8.79674 3.14991C9.54833 3.25977 9.84788 4.18362 9.30375 4.7136L8.08724 5.89849C7.87102 6.10908 7.77233 6.41262 7.82335 6.71011L8.11027 8.38295C8.23871 9.13183 7.45262 9.70288 6.78013 9.34922L5.27682 8.55864C5.00972 8.41818 4.6906 8.41818 4.42349 8.55864L2.92019 9.34922C2.2477 9.70288 1.46161 9.13183 1.59005 8.38294L1.87696 6.71011C1.92798 6.41262 1.82929 6.10908 1.61308 5.89849L0.396561 4.7136C-0.147566 4.18362 0.151985 3.25977 0.903574 3.14991L2.58676 2.90389C2.885 2.8603 3.14276 2.67288 3.27617 2.4026L4.02818 0.879112Z" fill="currentColor"/>				</svg>';
				break;
			case 'star-outline':
				echo '<svg width="12" height="11" viewBox="0 0 12 11" fill="none" xmlns="http://www.w3.org/2000/svg">                <path d="M5.02818 1.71462C5.36444 1.03338 6.33587 1.03338 6.67214 1.71462L7.42415 3.23811C7.55756 3.50839 7.81531 3.69581 8.11355 3.7394L9.79674 3.98542C10.5483 4.09528 10.8479 5.01913 10.3038 5.54911L9.08724 6.734C8.87102 6.94459 8.77233 7.24813 8.82335 7.54562L9.11027 9.21846C9.23871 9.96734 8.45262 10.5384 7.78013 10.1847L6.27682 9.39416C6.00972 9.25369 5.6906 9.25369 5.42349 9.39415L3.92019 10.1847C3.2477 10.5384 2.46161 9.96734 2.59005 9.21845L2.87696 7.54562C2.92798 7.24813 2.82929 6.94459 2.61308 6.734L1.39656 5.54911C0.852434 5.01913 1.15199 4.09528 1.90357 3.98542L3.58676 3.7394C3.885 3.69581 4.14276 3.50839 4.27617 3.23811L5.02818 1.71462Z" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/>                </svg>';
				break;
			case 'dot':
				echo '<svg width="8" height="8" viewBox="0 0 8 8" fill="none" xmlns="http://www.w3.org/2000/svg">				<rect width="8" height="8" rx="4" fill="currentColor"/>				</svg>';
				break;
			case 'live':
				echo '<svg width="512" height="512" viewBox="0 0 512 512" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M256.001 159.3C202.595 159.3 159.301 202.594 159.301 256C159.301 309.406 202.595 352.7 256.001 352.7C309.407 352.7 352.701 309.406 352.701 256C352.701 202.594 309.407 159.3 256.001 159.3Z" fill="currentColor"/><path fill-rule="evenodd" clip-rule="evenodd" d="M0 256C0 114.615 114.615 0 256 0C397.385 0 512 114.615 512 256C512 397.385 397.385 512 256 512C114.615 512 0 397.385 0 256ZM256 462C142.229 462 50 369.771 50 256C50 142.229 142.229 50 256 50C369.771 50 462 142.229 462 256C462 369.771 369.771 462 256 462Z" fill="currentColor"/></svg>';
				break;
		}
	}

	/**
	 * Content Render
	 *
	 * @return void
	 */
	protected function render() {
		$data   = $this->get_settings();
		$args   = Fns::query_args( $data );
		$query  = new \WP_Query( $args );
		$layout = $data['layout'] ?? '1';

		$breadking_icon = $data['breaking_icon'];
		if ( 'default' == $breadking_icon ) {
			$icon_map       = [
				'1'   => 'bolt',
				'1-2' => 'bolt',
				'2'   => 'bolt-round',
				'3'   => 'bolt-round-2',
				'4'   => 'bolt-round-3',
				'5'   => 'bolt-2',
				'6'   => 'live',
			];
			$breadking_icon = $icon_map[ $layout ] ?? 'bolt';
		}

		?>
        <div class="advanced-news-ticker-main clearfix">
            <div class="advanced-news-ticker-inner animation-<?php echo esc_attr( $data['ticker_animation'] ); ?>">
				<?php if ( $data['title'] ) : ?>
                    <div class="ticker-title">
						<?php
						if ( 'yes' === $data['show_icon'] ) {
							?>
                            <span class="ticker-live-icon">
                                <?php
                                if ( 'custom' == $data['breaking_icon'] && ! empty( $data['title_icon']['value'] ) ) {
	                                Icons_Manager::render_icon( $data['title_icon'], [ 'aria-hidden' => 'true' ] );
                                } else {
	                                $this->get_breaking_icon( $breadking_icon );
                                }
                                ?>
							</span>
							<?php
						}
						?>
                        <span class="breaking-title"><?php echo esc_html( $data['title'] ); ?></span>
                    </div>
				<?php endif; ?>

				<?php if ( $query->have_posts() ) : ?>
					<?php
					if ( 'marquee' === $data['ticker_animation'] ) {
						$this->marquee_slider( $query, $data );
					} else {
						$this->swiper_slider( $query, $data );
					}
					?>
				<?php endif; ?>
            </div>
        </div>
		<?php
	}

}