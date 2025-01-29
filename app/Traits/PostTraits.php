<?php
/**
 * PostTraits
 */

namespace AdvancedNewsTicker\Traits;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

use AdvancedNewsTicker\Helper\Fns;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;

trait PostTraits {

	use QueryTraits;

	/**
	 * Content Tab
	 *
	 * @param $object
	 *
	 * @return void
	 */
	protected function layout( $object ) {
		// widget title
		$object->start_controls_section(
			'ant_post_grid',
			[
				'label' => esc_html__( 'Post Grid', 'advanced-news-ticker' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);


		if ( 'flex' == $object->prefix ) {
			$layouts         = $this->flex_layout();
			$layouts_default = 'flex-1';
		} else {
			$layouts         = $this->grid_layout();
			$layouts_default = 'grid-1';
		}

		$object->add_control(
			'layout',
			[
				'type'         => 'advanced-news-ticker-image-select',
				'label'        => esc_html__( 'Choose Layout', 'advanced-news-ticker' ),
				'description'  => esc_html__( 'Choose layout', 'advanced-news-ticker' ),
				'options'      => $layouts,
				'default'      => $layouts_default,
				'prefix_class' => 'blog-',
				'render_type'  => 'template',
			]
		);

		if ( 'flex' == $object->prefix ) {
			$conditional_presets = [ 'flex-8', 'flex-9', 'flex-12' ];
			$object->add_control(
				'presets',
				[
					'label'        => __( 'Presets', 'advanced-news-ticker' ),
					'type'         => Controls_Manager::SELECT,
					'options'      => [
						'preset-1'                 => __( 'Preset 1', 'advanced-news-ticker' ),
						'preset-2'                 => __( 'Preset 2', 'advanced-news-ticker' ),
						'preset-2 ant-preset-3' => __( 'Preset 3', 'advanced-news-ticker' ),
						'preset-4'                 => __( 'Preset 4', 'advanced-news-ticker' ),
					],
					'default'      => 'preset-1',
					'prefix_class' => 'ant-',
					'render_type'  => 'template',
					'condition'    => [
						'layout!' => $conditional_presets
					]
				]
			);

			$object->add_control(
				'presets2',
				[
					'label'        => __( 'Presets', 'advanced-news-ticker' ),
					'type'         => Controls_Manager::SELECT,
					'options'      => [
						'preset-1' => __( 'Preset 1', 'advanced-news-ticker' ),
						'preset-2' => __( 'Preset 2', 'advanced-news-ticker' ),
					],
					'prefix_class' => 'ant-',
					'default'      => 'preset-1',
					'render_type'  => 'template',
					'condition'    => [
						'layout' => $conditional_presets
					]
				]
			);

			$object->add_control(
				'flex_border',
				[
					'label'        => __( 'Flex Border', 'advanced-news-ticker' ),
					'type'         => Controls_Manager::SELECT,
					'options'      => [
						'no-border'     => __( '--Select--', 'advanced-news-ticker' ),
						'border-solid'  => __( 'Solid Border', 'advanced-news-ticker' ),
						'border-dashed' => __( 'Solid dashed', 'advanced-news-ticker' ),
					],
					'default'      => 'no-border',
					'prefix_class' => 'flex-',
					'render_type'  => 'template',
					'condition'    => [
						'layout!' => [ 'flex-11' ]
					]
				]
			);

			$object->add_control(
				'grid_gap',
				[
					'label'     => __( 'Grid Gap', 'advanced-news-ticker' ),
					'type'      => Controls_Manager::NUMBER,
					'selectors' => [
						'{{WRAPPER}} .row-grid' => 'gap: {{VALUE}}px;--flex-gap: {{VALUE}}px;',
					],
				]
			);
		}

		if ( 'grid' == $object->prefix ) {
			$object->add_responsive_control(
				'grid_column',
				[
					'label'          => esc_html__( 'Grid Column', 'advanced-news-ticker' ),
					'type'           => Controls_Manager::SELECT,
					'default'        => '',
					'tablet_default' => '',
					'mobile_default' => '',
					'options'        => Fns::column_list(),
				]
			);
		}
		if ( 'slider' == $object->prefix ) {
			$object->add_responsive_control(
				'slider_column',
				[
					'label'          => esc_html__( 'Slider Column', 'advanced-news-ticker' ),
					'type'           => Controls_Manager::SELECT,
					'default'        => '',
					'tablet_default' => '',
					'mobile_default' => '',
					'options'        => Fns::slider_list(),
				]
			);
		}
		$object->add_responsive_control(
			'alignment',
			[
				'label'     => __( 'Alignment', 'advanced-news-ticker' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => __( 'Left', 'advanced-news-ticker' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'advanced-news-ticker' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'advanced-news-ticker' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'toggle'    => true,
				'selectors' => [
					'{{WRAPPER}} .article-inner-wrapper' => 'text-align: {{VALUE}};',
				],
			]
		);

		$object->add_control(
			'field_visibility',
			[
				'label'     => __( 'Visibility', 'advanced-news-ticker' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$object->add_control(
			'thumbnail_visibility',
			[
				'label'        => esc_html__( 'Thumbnail Visibility', 'advanced-news-ticker' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$object->add_control(
			'excerpt_visibility',
			[
				'label'        => esc_html__( 'Excerpt Visibility', 'advanced-news-ticker' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$object->add_control(
			'meta_visibility',
			[
				'label'        => esc_html__( 'Meta Visibility', 'advanced-news-ticker' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$object->add_control(
			'separate_cat_visibility',
			[
				'label'        => esc_html__( 'Separate Category Visibility', 'advanced-news-ticker' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'render_type'  => 'template',
				'prefix_class' => 'separate-cat-'
			]
		);

		$object->add_control(
			'readmore_visibility',
			[
				'label'        => esc_html__( 'Read More Visibility', 'advanced-news-ticker' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => false,
			]
		);


		$object->add_control(
			'features_heading',
			[
				'label'     => __( 'Features', 'advanced-news-ticker' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$object->add_control(
			'ajax_filter',
			[
				'label'        => esc_html__( 'Ajax Filter', 'advanced-news-ticker' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => false,
			]
		);

		$object->add_control(
			'section_title_visibility',
			[
				'label'        => esc_html__( 'Section Title Visibility', 'advanced-news-ticker' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => false,
				'condition'    => [
					'ajax_filter' => 'yes',
				]
			]
		);

		$object->add_control(
			'load_more',
			[
				'label'        => esc_html__( 'Load More', 'advanced-news-ticker' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => false,
			]
		);

		$object->add_control(
			'masonry_layout',
			[
				'label'        => esc_html__( 'Masonry Layout', 'advanced-news-ticker' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => false,
				'separator'    => 'after',
				'description'  => esc_html__( 'Masonry works only for front-end', 'advanced-news-ticker' ),
				'condition'    => [
					'layout' => [ 'grid-1', 'grid-2', 'grid-3' ]
				]
			]
		);


		$object->add_control(
			'query_settings',
			[
				'label'     => __( 'Query Settings', 'advanced-news-ticker' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->query( $object );

		$object->end_controls_section();
	}


	/**
	 * Section Title
	 *
	 * @param $object
	 *
	 * @return void
	 */
	protected function section_title( $object ) {

		$object->start_controls_section(
			'post_global_section_heading',
			[
				'label'   => __( 'Global Settings', 'advanced-news-ticker' ),
				'tab'     => Controls_Manager::TAB_STYLE,
				'classes' => 'section-main-heading'
			]
		);
		$object->end_controls_section();

		$object->start_controls_section(
			'section_title_settings',
			[
				'label'     => __( 'Section Title', 'advanced-news-ticker' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'section_title_visibility' => 'yes'
				]
			]
		);

		$object->add_control(
			'section_title',
			[
				'label'   => __( 'Enter Title', 'advanced-news-ticker' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Latest Post', 'advanced-news-ticker' ),
			]
		);

		$object->add_control(
			'section_title_tag',
			[
				'label'   => esc_html__( 'Title Tag', 'advanced-news-ticker' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'h3',
				'options' => [
					'h1' => esc_html__( 'H1', 'advanced-news-ticker' ),
					'h2' => esc_html__( 'H2', 'advanced-news-ticker' ),
					'h3' => esc_html__( 'H3', 'advanced-news-ticker' ),
					'h4' => esc_html__( 'H4', 'advanced-news-ticker' ),
					'h5' => esc_html__( 'H5', 'advanced-news-ticker' ),
					'h6' => esc_html__( 'H6', 'advanced-news-ticker' ),
				],
			]
		);

		$object->add_control(
			'section_title_link',
			[
				'label'         => __( 'Link', 'advanced-news-ticker' ),
				'type'          => Controls_Manager::URL,
				'placeholder'   => __( 'https://your-link.com', 'advanced-news-ticker' ),
				'show_external' => true,
				'dynamic'       => [
					'active' => true,
				],
				'default'       => [
					'url'         => '',
					'is_external' => true,
					'nofollow'    => true,
				],
			]
		);


		$object->end_controls_section();
	}


	/**
	 * Ajax Filter
	 *
	 * @param $object
	 *
	 * @return void
	 */
	protected function ajax_filter( $object ) {
		// Thumbnail style
		//========================================================
		$object->start_controls_section(
			'ajax_filter_settings',
			[
				'label'     => __( 'Ajax Filter', 'advanced-news-ticker' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'ajax_filter' => 'yes'
				]
			]
		);

		$object->add_control(
			'filter_style',
			[
				'label'        => __( 'Filter Style', 'advanced-news-ticker' ),
				'type'         => Controls_Manager::SELECT,
				'options'      => [
					'filter-default'    => __( 'Default Text', 'advanced-news-ticker' ),
					'filter-border'     => __( 'Border Style', 'advanced-news-ticker' ),
					'filter-background' => __( 'Backgrounded Style', 'advanced-news-ticker' ),
				],
				'default'      => 'default',
				'prefix_class' => 'ant-',
			]
		);

		$object->add_control(
			'filter_source',
			[
				'label'   => __( 'Taxonomy Source', 'advanced-news-ticker' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'category' => __( 'Categories', 'advanced-news-ticker' ),
					'post_tag' => __( 'Post Tags', 'advanced-news-ticker' ),
				],
				'default' => 'category',
			]
		);

		$object->add_responsive_control(
			'filter_v_align',
			[
				'label'     => __( 'Vertical Align', 'advanced-news-ticker' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'row'                            => [
						'title' => esc_html__( 'Start', 'elementor' ),
						'icon'  => 'eicon-align-start-h',
					],
					'column;justify-content:center;' => [
						'title' => esc_html__( 'Center', 'elementor' ),
						'icon'  => 'eicon-align-center-h',
					],
					'row-reverse'                    => [
						'title' => esc_html__( 'End', 'elementor' ),
						'icon'  => 'eicon-align-end-h',
					],
				],
				'toggle'    => true,
				'condition' => [
					'section_title_visibility' => 'yes'
				],
				'selectors' => [
					'{{WRAPPER}} .ajax-filter-wrapper' => 'flex-direction: {{VALUE}}',
				],
			]
		);

		$object->add_responsive_control(
			'filter_alignment',
			[
				'label'     => __( 'Alignment', 'advanced-news-ticker' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => __( 'Left', 'advanced-news-ticker' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'advanced-news-ticker' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'advanced-news-ticker' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'condition' => [
					'section_title_visibility!' => 'yes'
				],
				'selectors' => [
					'{{WRAPPER}} .ajax-filter-wrapper' => 'text-align: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$object->add_control(
			'total_item',
			[
				'label'   => __( 'Total Items', 'advanced-news-ticker' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => '6',
			]
		);

		$object->add_control(
			'show_post_count',
			[
				'label'        => __( 'Show Post Count', 'advanced-news-ticker' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'advanced-news-ticker' ),
				'label_off'    => __( 'Hide', 'advanced-news-ticker' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$object->add_control(
			'filter_nav_icon',
			[
				'label'            => __( 'Nav Icon', 'advanced-news-ticker' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'default'          => [
					'value'   => 'fas fa-angle-right',
					'library' => 'fa-solid',
				],
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

		$object->end_controls_section();
	}

	/**
	 * Ajax Filter
	 *
	 * @param $object
	 *
	 * @return void
	 */
	protected function load_more( $object ) {
		// Thumbnail style
		//========================================================
		$object->start_controls_section(
			'load_more_settings',
			[
				'label'     => __( 'Load More', 'advanced-news-ticker' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'load_more' => 'yes'
				]
			]
		);


		$object->add_control(
			'load_btn_style',
			[
				'label'   => esc_html__( 'Button Style', 'advanced-news-ticker' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'load-more' => esc_html__( 'Load More Button', 'advanced-news-ticker' ),
					'next-prev' => esc_html__( 'Next Prev Button', 'advanced-news-ticker' ),
					'link'      => esc_html__( 'External Link', 'advanced-news-ticker' ),
				],
				'default' => 'load-more',
			]
		);

		$object->add_control(
			'btn_next_label',
			[
				'label'     => esc_html__( 'Next Label', 'advanced-news-ticker' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => 'Next',
				'condition' => [
					'load_btn_style' => 'next-prev'
				]
			]
		);

		$object->add_control(
			'btn_prev_label',
			[
				'label'     => esc_html__( 'Prev Label', 'advanced-news-ticker' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => 'Prev',
				'condition' => [
					'load_btn_style' => 'next-prev'
				]
			]
		);

		$object->add_control(
			'btn_loadmore_label',
			[
				'label'     => esc_html__( 'Load More Label', 'advanced-news-ticker' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => 'Load More',
				'condition' => [
					'load_btn_style' => [ 'load-more', 'link' ]
				]
			]
		);

		$object->add_control(
			'readmore_link',
			[
				'label'         => __( 'Button Link', 'advanced-news-ticker' ),
				'type'          => Controls_Manager::URL,
				'placeholder'   => __( 'https://your-link.com', 'advanced-news-ticker' ),
				'show_external' => true,
				'dynamic'       => [
					'active' => true,
				],
				'default'       => [
					'url'         => '',
					'is_external' => true,
					'nofollow'    => true,
				],
			]
		);

		$object->add_control(
			'btn_loadmore_style',
			[
				'label'     => esc_html__( 'Load More Style', 'advanced-news-ticker' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$object->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'load_btn_typography',
				'selector' => '{{WRAPPER}} .ant-post-ajax-filter .load-post-button .ant-ajax-nav-icon',
			]
		);

		$object->add_responsive_control(
			'load_btn_padding',
			[
				'label'      => __( 'Padding', 'advanced-news-ticker' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .ant-post-ajax-filter .load-post-button .ant-ajax-nav-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);
		$object->add_responsive_control(
			'load_btn_radius',
			[
				'label'      => __( 'Radius', 'advanced-news-ticker' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .ant-post-ajax-filter .load-post-button .ant-ajax-nav-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		// Start Icon Style Tab.
		$object->start_controls_tabs(
			'load_post_style_tabs'
		);

		// Normal Style.
		$object->start_controls_tab(
			'load_post_style_normal_tab',
			[
				'label' => __( 'Normal', 'ant-core' ),
			]
		);

		$object->add_control(
			'load_btn_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Load Button Color', 'ant-core' ),
				'selectors' => [
					'{{WRAPPER}} .ant-post-ajax-filter .load-post-button .ant-ajax-nav-icon' => 'color: {{VALUE}}',
				],
			]
		);

		$object->add_control(
			'load_btn_bg',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Load More Background', 'ant-core' ),
				'selectors' => [
					'{{WRAPPER}} .ant-post-ajax-filter .load-post-button .ant-ajax-nav-icon' => 'background-color: {{VALUE}}',
				],
			]
		);

		$object->add_control(
			'load_btn_border',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Load Button Color', 'ant-core' ),
				'selectors' => [
					'{{WRAPPER}} .ant-post-ajax-filter .load-post-button .ant-ajax-nav-icon' => 'border-color: {{VALUE}}',
				],
			]
		);

		$object->end_controls_tab();

		// Hover Style
		$object->start_controls_tab(
			'load_post_style_hover_tab',
			[
				'label' => __( 'Hover', 'ant-core' ),
			]
		);

		$object->add_control(
			'load_btn_color_hover',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Load Button Color:hover', 'ant-core' ),
				'selectors' => [
					'{{WRAPPER}} .ant-post-ajax-filter .load-post-button .ant-ajax-nav-icon:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$object->add_control(
			'load_btn_bg_hover',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Load More Background:hover', 'ant-core' ),
				'selectors' => [
					'{{WRAPPER}} .ant-post-ajax-filter .load-post-button .ant-ajax-nav-icon:hover' => 'background-color: {{VALUE}}; border-color: {{VALUE}}',
				],
			]
		);

		$object->end_controls_tab();

		$object->end_controls_tabs();
		// End Icon Style Tab.

		$object->end_controls_section();
	}

	/**
	 * Thumbnail Settings
	 *
	 * @param $object
	 *
	 * @return void
	 */
	protected function thumbnail_settings( $object ) {

		$object->start_controls_section(
			'thumbnail_style',
			[
				'label'     => __( 'Thumbnail Style', 'advanced-news-ticker' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'thumbnail_visibility' => 'yes'
				]
			]
		);

		$object->add_control(
			'thumbnail_size',
			[
				'label'   => esc_html__( 'Image Size', 'advanced-news-ticker' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'large',
				'options' => Fns::image_size_lists(),
			]
		);

		if ( 'slider' !== $object->prefix ) {
			$object->add_control(
				'hide_images',
				[
					'label'   => __( 'Hide Images', 'advanced-news-ticker' ),
					'type'    => Controls_Manager::SELECT,
					'options' => [
						'0'  => __( 'Select', 'advanced-news-ticker' ),
						'1'  => __( 'All except first one', 'advanced-news-ticker' ),
						'2'  => __( 'All except first two', 'advanced-news-ticker' ),
						'3'  => __( 'All except first three', 'advanced-news-ticker' ),
						'4'  => __( 'All except first four', 'advanced-news-ticker' ),
						'5'  => __( 'All except first five', 'advanced-news-ticker' ),
						'6'  => __( 'All except first six', 'advanced-news-ticker' ),
						'7'  => __( 'All except first seven', 'advanced-news-ticker' ),
						'8'  => __( 'All except first eight', 'advanced-news-ticker' ),
						'9'  => __( 'All except first nine', 'advanced-news-ticker' ),
						'10' => __( 'All except first ten', 'advanced-news-ticker' ),

					],
					'default' => '0',
				]
			);
		}

		$object->add_responsive_control(
			'image_gap_list',
			[
				'label'      => __( 'Image Gap', 'advanced-news-ticker' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 1,
						'max'  => 200,
						'step' => 1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .blog-list .article-inner-wrapper' => 'gap: {{SIZE}}px;',
				],
				'condition'  => [
					'layout' => [ 'list-1', 'list-2', 'list-3' ]
				]
			]
		);

		$object->add_responsive_control(
			'image_height',
			[
				'label'      => __( 'Image Height Ratio', 'advanced-news-ticker' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 1,
						'max'  => 20,
						'step' => .5,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .post-thumbnail-wrap.post-grid .post-thumbnail' => 'aspect-ratio: 10 / {{SIZE}};',
				],
				'condition'  => [
					'layout' => [ 'flex-1', 'flex-3', 'flex-12' ]
				]
			]
		);

		if ( 'flex' == $object->prefix ) {
			$object->add_responsive_control(
				'big_image_height',
				[
					'label'      => __( 'Big Image Height Ratio', 'advanced-news-ticker' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'range'      => [
						'px' => [
							'min'  => 1,
							'max'  => 20,
							'step' => .01,
						],
					],
					'selectors'  => [
						'{{WRAPPER}} .has-big-image .post-thumbnail-wrap.post-grid .post-thumbnail' => 'aspect-ratio: 10 / {{SIZE}};',
					],
					'condition'  => [
						'layout' => [ 'flex-1', 'flex-3', 'flex-12' ]
					]
				]
			);
		}

		$object->add_responsive_control(
			'image_width',
			[
				'label'      => __( 'Image Width', 'advanced-news-ticker' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 50,
						'max'  => 1000,
						'step' => .5,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .article-inner-wrapper .post-thumbnail-wrap' => 'flex: 0 0 {{SIZE}}{{UNIT}};max-width:{{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'layout' => [ 'list-1', 'list-2', 'list-3' ]
				]
			]
		);

		$object->add_control(
			'thumb_box_radius',
			[
				'label'      => __( 'Thumbnail Radius', 'advanced-news-ticker' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .post-thumbnail-wrap .post-thumbnail, {{WRAPPER}} .post-thumbnail-wrap .post-thumbnail img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Start Icon Style Tab.
		$object->start_controls_tabs(
			'icon_style_tabs'
		);

		// Normal Style.
		$object->start_controls_tab(
			'icon_style_normal_tab',
			[
				'label' => __( 'Normal', 'ant-core' ),
			]
		);
		$object->add_control(
			'video_icon_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Video/Gallery Icon Color', 'ant-core' ),
				'selectors' => [
					'{{WRAPPER}} .post-thumbnail-wrap .post-thumbnail :is(.ant-popup-video, .slick-arrow)' => 'color: {{VALUE}}',
				],
			]
		);
		$object->add_control(
			'video_icon_bg',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Video/Gallery Icon Background', 'ant-core' ),
				'selectors' => [
					'{{WRAPPER}} .post-thumbnail-wrap .post-thumbnail :is(.ant-popup-video, .slick-arrow, .slick-dots button)' => 'background-color: {{VALUE}}',
				],
			]
		);

		$object->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => 'overlay_bg',
				'fields_options' => [
					'background' => [
						'label' => esc_html__( 'Overlay Background', 'advanced-news-ticker' ),
					],
				],
				'exclude'        => [ 'image' ],
				'types'          => [ 'classic', 'gradient' ],
				'selector'       => '{{WRAPPER}} .post-thumbnail-wrap .post-thumbnail::before',
			]
		);

		$object->end_controls_tab();

		// Hover Style
		$object->start_controls_tab(
			'icon_style_hover_tab',
			[
				'label' => __( 'Hover', 'ant-core' ),
			]
		);

		$object->add_control(
			'video_icon_color_hover',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Video/Gallery Icon Color:hover', 'ant-core' ),
				'selectors' => [
					'{{WRAPPER}} .post-thumbnail-wrap .post-thumbnail :is(.ant-popup-video:hover, .slick-arrow:hover)' => 'color: {{VALUE}}',
				],
			]
		);
		$object->add_control(
			'video_icon_bg_hover',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Video/Gallery Icon BG:hover', 'ant-core' ),
				'selectors' => [
					'{{WRAPPER}} .post-thumbnail-wrap .post-thumbnail :is(.ant-popup-video:hover, .slick-arrow:hover, .slick-dots button:hover, .slick-active button)' => 'background-color: {{VALUE}}',
				],
			]
		);

		$object->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => 'overlay_bg_hover',
				'fields_options' => [
					'background' => [
						'label' => esc_html__( 'Overlay Background:hover', 'advanced-news-ticker' ),
					],
				],
				'exclude'        => [ 'image' ],
				'types'          => [ 'classic', 'gradient' ],
				'selector'       => '{{WRAPPER}} .post-thumbnail-wrap .post-thumbnail::after',
			]
		);
		$object->end_controls_tab();

		$object->end_controls_tabs();
		// End Icon Style Tab.

		$object->end_controls_section();
	}

	/**
	 * Title Settings
	 *
	 * @param $object
	 *
	 * @return void
	 */
	protected function title_settings( $object ) {

		// Title Settings
		//=====================================================================
		$object->start_controls_section(
			'title_style',
			[
				'label' => __( 'Title Style', 'advanced-news-ticker' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$object->add_control(
			'title_tag',
			[
				'label'   => esc_html__( 'Title Tag', 'advanced-news-ticker' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'h3',
				'options' => [
					'h1' => esc_html__( 'H1', 'advanced-news-ticker' ),
					'h2' => esc_html__( 'H2', 'advanced-news-ticker' ),
					'h3' => esc_html__( 'H3', 'advanced-news-ticker' ),
					'h4' => esc_html__( 'H4', 'advanced-news-ticker' ),
					'h5' => esc_html__( 'H5', 'advanced-news-ticker' ),
					'h6' => esc_html__( 'H6', 'advanced-news-ticker' ),
				],
			]
		);
		$object->add_control(
			'title_line_number',
			[
				'label'        => esc_html__( 'Title line number', 'advanced-news-ticker' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'full',
				'options'      => [
					'full'       => esc_html__( '-Select-', 'advanced-news-ticker' ),
					'one-line'   => esc_html__( 'One line', 'advanced-news-ticker' ),
					'tow-line'   => esc_html__( 'Tow line', 'advanced-news-ticker' ),
					'three-line' => esc_html__( 'Three line', 'advanced-news-ticker' ),
				],
				'prefix_class' => 'title-',
				'render_type'  => 'content'
			]
		);

		$object->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .advanced-news-ticker-post-card .entry-title',
			]
		);

		$object->add_control(
			'title_spacing',
			[
				'label'              => __( 'Title Spacing', 'advanced-news-ticker' ),
				'type'               => Controls_Manager::DIMENSIONS,
				'size_units'         => [ 'px' ],
				'selectors'          => [
					'{{WRAPPER}} .advanced-news-ticker-post-card .entry-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'allowed_dimensions' => 'vertical',
				'default'            => [
					'top'      => '',
					'right'    => '',
					'bottom'   => '14',
					'left'     => '',
					'isLinked' => false,
				],
			]
		);

		$object->start_controls_tabs(
			'title_style_tabs'
		);

		$object->start_controls_tab(
			'title_normal_tab',
			[
				'label' => __( 'Normal', 'advanced-news-ticker' ),
			]
		);

		$object->add_control(
			'title_color',
			[
				'label'     => __( 'Title Color', 'advanced-news-ticker' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .advanced-news-ticker-post-card .entry-title a' => 'color: {{VALUE}}',
				],
			]
		);

		$object->end_controls_tab();

		$object->start_controls_tab(
			'title_hover_tab',
			[
				'label' => __( 'Hover', 'advanced-news-ticker' ),
			]
		);

		$object->add_control(
			'title_hover_color',
			[
				'label'     => __( 'Title Hover Color', 'advanced-news-ticker' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .advanced-news-ticker-post-card .entry-title a:hover' => 'color: {{VALUE}} !important',
				],
			]
		);

		$object->end_controls_tab();

		$object->end_controls_tabs();

		$object->end_controls_section();
	}

	/**
	 * Excerpt Settings
	 *
	 * @param $object
	 *
	 * @return void
	 */
	protected function excerpt_settings( $object ) {
		// Content Settings
		//=====================================================================

		$object->start_controls_section(
			'content_style',
			[
				'label'     => __( 'Excerpt Style', 'advanced-news-ticker' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'excerpt_visibility' => 'yes'
				]
			]
		);

		$object->add_control(
			'content_limit',
			[
				'label'   => __( 'Excerpt Limit', 'advanced-news-ticker' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '15',
			]
		);

		$object->add_control(
			'excerpt_line',
			[
				'label'        => __( 'Excerpt Line number', 'advanced-news-ticker' ),
				'type'         => Controls_Manager::SELECT,
				'options'      => [
					'full'       => __( '-Select-', 'advanced-news-ticker' ),
					'one-line'   => esc_html__( 'One line', 'advanced-news-ticker' ),
					'tow-line'   => esc_html__( 'Tow line', 'advanced-news-ticker' ),
					'three-line' => esc_html__( 'Three line', 'advanced-news-ticker' ),
				],
				'default'      => '',
				'prefix_class' => 'excerpt-'
			]
		);

		$object->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'content_typography',
				'selector' => '{{WRAPPER}} .article-inner-wrapper .entry-content',
			]
		);

		$object->add_control(
			'content_spacing',
			[
				'label'              => __( 'Excerpt Spacing', 'advanced-news-ticker' ),
				'type'               => Controls_Manager::DIMENSIONS,
				'size_units'         => [ 'px' ],
				'selectors'          => [
					'{{WRAPPER}} .article-inner-wrapper .entry-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'allowed_dimensions' => 'vertical',
				'default'            => [
					'top'      => '',
					'right'    => '',
					'bottom'   => '',
					'left'     => '',
					'isLinked' => false,
				],
			]
		);

		$object->add_control(
			'content_color',
			[
				'label'     => __( 'Content Color', 'advanced-news-ticker' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .article-inner-wrapper .entry-content' => 'color: {{VALUE}}',
				],
			]
		);

		$object->end_controls_section();
	}

	/**
	 * Meta Settings
	 *
	 * @param $object
	 *
	 * @return void
	 */
	protected function meta_settings( $object ) {


		$object->start_controls_section(
			'meta_info_style',
			[
				'label'     => __( 'Meta Settings', 'advanced-news-ticker' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'meta_visibility' => 'yes'
				]
			]
		);

		$object->add_control(
			'meta_list',
			[
				'label'       => __( 'Choose Meta', 'advanced-news-ticker' ),
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'options'     => Fns::blog_meta_list(),
				'label_block' => true,
				'default'     => [ 'author', 'date', 'category' ],
				'description' => __( 'Select post meta.', 'advanced-news-ticker' ),
			]
		);

		$object->add_control(
			'meta_author',
			[
				'label'   => esc_html__( 'Author Avatar', 'advanced-news-ticker' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => false,
			]
		);

		$object->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'post_meta_typography',
				'selector' => '{{WRAPPER}} .blog-box .post-content .post-meta a',
			]
		);

		$object->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'meta_border',
				'label'    => __( 'Border', 'advanced-news-ticker' ),
				'selector' => '{{WRAPPER}} .article-inner-wrapper',
			]
		);

		$object->start_controls_tabs(
			'post_meta_style_tabs'
		);

		$object->start_controls_tab(
			'post_meta_normal_tab',
			[
				'label' => __( 'Normal', 'advanced-news-ticker' ),
			]
		);


		$object->add_control(
			'meta_color',
			[
				'label'     => __( 'Meta Color', 'advanced-news-ticker' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .raw_addons-post-meta' => 'color: {{VALUE}}',
				],
			]
		);

		$object->add_control(
			'author_color',
			[
				'label'     => __( 'Author Color', 'advanced-news-ticker' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .raw_addons-post-meta .author a' => 'color: {{VALUE}}',
				],
			]
		);

		$object->end_controls_tab();

		$object->start_controls_tab(
			'post_meta_hover_tab',
			[
				'label' => __( 'Hover', 'advanced-news-ticker' ),
			]
		);

		$object->add_control(
			'link_hover_color',
			[
				'label'     => __( 'Meta Color:hover', 'advanced-news-ticker' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .raw_addons-post-meta a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$object->end_controls_tab();

		$object->end_controls_tabs();

		$object->end_controls_section();
	}


	/**
	 * Separate Category
	 *
	 * @param $object
	 *
	 * @return void
	 */
	protected function separate_category( $object ) {


		$object->start_controls_section(
			'separate_cat_style',
			[
				'label'     => __( 'Separate Category', 'advanced-news-ticker' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'separate_cat_visibility' => 'yes'
				]
			]
		);

		$object->add_control(
			'separate_cat',
			[
				'label'       => __( 'Choose Meta', 'advanced-news-ticker' ),
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'options'     => Fns::blog_meta_list(),
				'default'     => [ 'category' ],
				'label_block' => true,
				'description' => __( 'Select any one separate meta item for the best view', 'advanced-news-ticker' ),
			]
		);

		$object->add_control(
			'separate_meta_position',
			[
				'label'   => __( 'Separate Meta Position', 'advanced-news-ticker' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'above-title' => __( 'Above Title', 'advanced-news-ticker' ),
					'in-thumb'    => __( 'In Thumbnail', 'advanced-news-ticker' ),
				],
				'default' => 'above-title',
			]
		);

		$object->add_control(
			'different_cat_color',
			[
				'label'   => esc_html__( 'Different Category Color', 'advanced-news-ticker' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$object->add_control(
			'thumb_meta_position',
			[
				'label'        => __( 'Thumb meta position', 'advanced-news-ticker' ),
				'type'         => Controls_Manager::SELECT,
				'options'      => [
					'top'    => __( 'top', 'advanced-news-ticker' ),
					'center' => __( 'Center', 'advanced-news-ticker' ),
					'bottom' => __( 'bottom', 'advanced-news-ticker' ),
				],
				'condition'    => [
					'separate_meta_position' => 'in-thumb'
				],
				'prefix_class' => 'thumb-meta-',
				'default'      => 'top',
			]
		);

		$object->add_responsive_control(
			'separate_meta_alignment',
			[
				'label'     => __( 'Alignment', 'advanced-news-ticker' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => __( 'Left', 'advanced-news-ticker' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'advanced-news-ticker' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'advanced-news-ticker' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'   => 'left',
				'selectors' => [
					'{{WRAPPER}} .advanced-news-ticker-post-card .separate-meta' => 'text-align: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$object->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'separate_cat_typography',
				'selector' => '{{WRAPPER}} .advanced-news-ticker-post-card .separate-meta .meta-inner',
			]
		);

		$object->add_control(
			'above_cat_margin',
			[
				'label'      => __( 'Margin', 'advanced-news-ticker' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .advanced-news-ticker-post-card .separate-meta' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$object->add_control(
			'above_cat_radius',
			[
				'label'      => __( 'Border Radius', 'advanced-news-ticker' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .advanced-news-ticker-post-card .separate-meta .meta-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$object->add_control(
			'above_cat_padding',
			[
				'label'      => __( 'Padding', 'advanced-news-ticker' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .advanced-news-ticker-post-card .separate-meta .meta-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$object->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'separate_meta_border',
				'label'    => __( 'Border', 'advanced-news-ticker' ),
				'selector' => '{{WRAPPER}} .advanced-news-ticker-post-card .separate-meta .meta-inner',
			]
		);

		$object->start_controls_tabs(
			'separate_cat_style_tabs'
		);

		$object->start_controls_tab(
			'separate_cat_normal_tab',
			[
				'label' => __( 'Normal', 'advanced-news-ticker' ),
			]
		);


		$object->add_control(
			'separate_cat_color',
			[
				'label'     => __( 'Color', 'advanced-news-ticker' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .advanced-news-ticker-post-card .separate-meta .meta-inner' => 'color: {{VALUE}}',
				],
			]
		);
		$object->add_control(
			'separate_cat_link_color',
			[
				'label'     => __( 'Link Color', 'advanced-news-ticker' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .advanced-news-ticker-post-card .separate-meta .meta-inner a' => 'color: {{VALUE}}',
				],
			]
		);
		$object->add_control(
			'separate_cat_bg',
			[
				'label'     => __( 'Background', 'advanced-news-ticker' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .separate-meta .category-links a, {{WRAPPER}} .separate-meta .meta-inner:not(.category)' => 'background-color: {{VALUE}};border-color:{{VALUE}}',
				],
			]
		);

		$object->end_controls_tab();

		$object->start_controls_tab(
			'separate_cat_hover_tab',
			[
				'label' => __( 'Hover', 'advanced-news-ticker' ),
			]
		);


		$object->add_control(
			'separate_cat_color_h',
			[
				'label'     => __( 'Link Color:hover', 'advanced-news-ticker' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .advanced-news-ticker-post-card .separate-meta .meta-inner a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$object->end_controls_tab();

		$object->end_controls_tabs();

		$object->end_controls_section();
	}

	/**
	 * Read More Settings
	 *
	 * @param $object
	 *
	 * @return void
	 */
	protected function readmore_settings( $object ) {

		//Read More Style
		//====================

		$object->start_controls_section(
			'readmore_style',
			[
				'label'     => __( 'Read More Style', 'advanced-news-ticker' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'readmore_visibility' => 'yes'
				]
			]
		);

		$object->add_control(
			'readmore_btn_style',
			[
				'label'        => esc_html__( 'Style', 'neeon-core' ),
				'type'         => Controls_Manager::SELECT,
				'options'      => [
					'btn-default' => esc_html__( 'Default', 'neeon-core' ),
					'btn-primary' => esc_html__( 'Primary', 'neeon-core' ),
					'btn-text'    => esc_html__( 'Text Button', 'neeon-core' ),
				],
				'default'      => 'btn-default',
				'prefix_class' => 'more-'
			]
		);

		$object->add_control(
			'readmore_text',
			[
				'label'       => __( 'Button Text', 'advanced-news-ticker' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Read More', 'advanced-news-ticker' ),
				'placeholder' => __( 'Type your title here', 'advanced-news-ticker' ),
			]
		);

		$object->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'readmore_typography',
				'selector' => '{{WRAPPER}} .advanced-news-ticker-post-card .entry-footer .btn',
			]
		);

		$object->add_control(
			'readmore_spacing',
			[
				'label'              => __( 'Button Spacing', 'advanced-news-ticker' ),
				'type'               => Controls_Manager::DIMENSIONS,
				'size_units'         => [ 'px' ],
				'allowed_dimensions' => 'vertical',
				'default'            => [
					'top'      => '',
					'right'    => '',
					'bottom'   => '',
					'left'     => '',
					'isLinked' => false,
				],
				'selectors'          => [
					'{{WRAPPER}} .advanced-news-ticker-post-card .entry-footer' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$object->add_control(
			'readmore_padding',
			[
				'label'      => __( 'Button Padding', 'advanced-news-ticker' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .advanced-news-ticker-post-card .entry-footer .btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);


		$object->add_control(
			'btn_border_radius',
			[
				'label'      => __( 'Border Radius', 'advanced-news-ticker' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .advanced-news-ticker-post-card .entry-footer .btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$object->add_control(
			'show_btn_icon',
			[
				'label'        => __( 'Show Button Icon', 'advanced-news-ticker' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'advanced-news-ticker' ),
				'label_off'    => __( 'Hide', 'advanced-news-ticker' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'btn_icon',
			[
				'label' => esc_html__( 'Button Icon', 'advanced-news-ticker' ),
				'type'  => \Elementor\Controls_Manager::ICONS,
			]
		);

		//Button style Tabs
		$object->start_controls_tabs(
			'readmore_style_tabs'
		);

		$object->start_controls_tab(
			'readmore_style_normal_tab',
			[
				'label' => __( 'Normal', 'advanced-news-ticker' ),
			]
		);

		$object->add_control(
			'readmore_color',
			[
				'label'     => __( 'Text Color', 'advanced-news-ticker' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .advanced-news-ticker-post-card .entry-footer .btn' => 'color: {{VALUE}}',
				],
			]
		);

		$object->add_control(
			'icon_color',
			[
				'label'     => __( 'Icon Color', 'advanced-news-ticker' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .advanced-news-ticker-post-card .entry-footer .btn :is(i, svg)' => 'color: {{VALUE}}',
				],
			]
		);

		$object->add_control(
			'readmore_bg',
			[
				'label'     => __( 'Background Color', 'advanced-news-ticker' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .advanced-news-ticker-post-card .entry-footer .btn' => 'background-color: {{VALUE}}',
				],
			]
		);

		$object->end_controls_tab();

		$object->start_controls_tab(
			'readmore_style_hover_tab',
			[
				'label' => __( 'Hover', 'advanced-news-ticker' ),
			]
		);

		$object->add_control(
			'readmore_color_hover',
			[
				'label'     => __( 'Text Color:hover', 'advanced-news-ticker' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .advanced-news-ticker-post-card .entry-footer .btn:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$object->add_control(
			'icon_color_hover',
			[
				'label'     => __( 'Icon Color:hover', 'advanced-news-ticker' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .advanced-news-ticker-post-card .entry-footer .btn:hover :is(i, svg)' => 'color: {{VALUE}}',
				],
			]
		);

		$object->add_control(
			'readmore_bg_hover',
			[
				'label'     => __( 'Background Color:hover', 'advanced-news-ticker' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .advanced-news-ticker-post-card .entry-footer .btn:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$object->end_controls_tab();

		$object->end_controls_tabs();
		$object->end_controls_section();

	}

	/**
	 * Post Card Styles
	 *
	 * @param $object
	 *
	 * @return void
	 */
	protected function post_card_settings( $object ) {
		$object->start_controls_section(
			'post_card_style',
			[
				'label' => __( 'Post Card Style', 'advanced-news-ticker' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$object->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => 'card_bg',
				'selector'       => '{{WRAPPER}} .article-inner-wrapper',
				'types'          => [ 'classic', 'gradient' ],
				'fields_options' => [
					'background' => [
						'label' => esc_html__( 'Card Background', 'the-post-grid' ),
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

		$object->add_control(
			'flex_item_bg',
			[
				'type'        => Controls_Manager::COLOR,
				'label'       => esc_html__( 'Flex Item Background', 'ant-core' ),
				'description' => esc_html__( 'If the top flex-item has a background, you can modify its color for emphasis.', 'ant-core' ),
				'selectors'   => [
					'{{WRAPPER}} .article-inner-wrapper' => '--flex-item-bg: {{VALUE}}',
				],
			]
		);

		$object->add_control(
			'flex_item_border',
			[
				'type'        => Controls_Manager::COLOR,
				'label'       => esc_html__( 'Flex Item Border', 'ant-core' ),
				'description' => esc_html__( 'If the top flex-item has a border, you can modify its border color.', 'ant-core' ),
				'selectors'   => [
					'{{WRAPPER}} .article-inner-wrapper' => '--flex-item-border: {{VALUE}}',
				],
			]
		);

		$object->add_responsive_control(
			'card_padding',
			[
				'label'      => __( 'Padding', 'advanced-news-ticker' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .article-inner-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$object->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'box_shadow',
				'label'    => __( 'Box Shadow', 'advanced-news-ticker' ),
				'selector' => '{{WRAPPER}} .article-inner-wrapper',
			]
		);

		$object->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'button_border',
				'label'    => __( 'Border', 'advanced-news-ticker' ),
				'selector' => '{{WRAPPER}} .article-inner-wrapper',
			]
		);

		$object->add_control(
			'main_box_radius',
			[
				'label'      => __( 'Border Radius', 'advanced-news-ticker' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .article-inner-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$object->end_controls_section();
	}

	/**
	 * Flex layouts
	 * @return array[]
	 */
	protected function flex_layout() {
		return [
			'flex-1'  => [
				'title' => esc_html__( 'Layout 1', 'advanced-news-ticker' ),
				'url'   => esc_url( Fns::get_assets_url( 'images/layout/flex-1.svg' ) ),
			],
			'flex-2'  => [
				'title' => esc_html__( 'Layout 2', 'advanced-news-ticker' ),
				'url'   => esc_url( Fns::get_assets_url( 'images/layout/flex-2.svg' ) ),
			],
			'flex-3'  => [
				'title' => esc_html__( 'Layout 3', 'advanced-news-ticker' ),
				'url'   => esc_url( Fns::get_assets_url( 'images/layout/flex-3.svg' ) ),
			],
			'flex-4'  => [
				'title'  => esc_html__( 'Layout 4', 'advanced-news-ticker' ),
				'url'    => esc_url( Fns::get_assets_url( 'images/layout/flex-4.svg' ) ),
				'is_pro' => ! advancedNewsTicker()->has_pro()
			],
			'flex-5'  => [
				'title'  => esc_html__( 'Layout 5', 'advanced-news-ticker' ),
				'url'    => esc_url( Fns::get_assets_url( 'images/layout/flex-5.svg' ) ),
				'is_pro' => ! advancedNewsTicker()->has_pro()
			],
			'flex-6'  => [
				'title'  => esc_html__( 'Layout 6', 'advanced-news-ticker' ),
				'url'    => esc_url( Fns::get_assets_url( 'images/layout/flex-6.svg' ) ),
				'is_pro' => ! advancedNewsTicker()->has_pro()
			],
			'flex-7'  => [
				'title'  => esc_html__( 'Layout 7', 'advanced-news-ticker' ),
				'url'    => esc_url( Fns::get_assets_url( 'images/layout/flex-7.svg' ) ),
				'is_pro' => ! advancedNewsTicker()->has_pro()
			],
			'flex-8'  => [
				'title'  => esc_html__( 'Layout 8', 'advanced-news-ticker' ),
				'url'    => esc_url( Fns::get_assets_url( 'images/layout/flex-8.svg' ) ),
				'is_pro' => ! advancedNewsTicker()->has_pro()
			],
			'flex-9'  => [
				'title'  => esc_html__( 'Layout 9', 'advanced-news-ticker' ),
				'url'    => esc_url( Fns::get_assets_url( 'images/layout/flex-9.svg' ) ),
				'is_pro' => ! advancedNewsTicker()->has_pro()
			],
			'flex-10' => [
				'title'  => esc_html__( 'Layout 10', 'advanced-news-ticker' ),
				'url'    => esc_url( Fns::get_assets_url( 'images/layout/flex-10.svg' ) ),
				'is_pro' => ! advancedNewsTicker()->has_pro()
			],
			'flex-11' => [
				'title'  => esc_html__( 'Layout 11', 'advanced-news-ticker' ),
				'url'    => esc_url( Fns::get_assets_url( 'images/layout/flex-11.svg' ) ),
				'is_pro' => ! advancedNewsTicker()->has_pro()
			],
			'flex-12' => [
				'title'  => esc_html__( 'Layout 12', 'advanced-news-ticker' ),
				'url'    => esc_url( Fns::get_assets_url( 'images/layout/flex-12.svg' ) ),
				'is_pro' => ! advancedNewsTicker()->has_pro()
			],
		];
	}

	/**
	 * Grid Layout
	 *
	 * @return array[]
	 */
	protected function grid_layout() {
		return [
			'grid-1'  => [
				'title' => esc_html__( 'Layout 1', 'advanced-news-ticker' ),
				'url'   => esc_url( Fns::get_assets_url( 'images/layout/grid-1.svg' ) ),
			],
			'grid-2'  => [
				'title'  => esc_html__( 'Layout 2', 'advanced-news-ticker' ),
				'url'    => esc_url( Fns::get_assets_url( 'images/layout/grid-2.svg' ) ),
				'is_pro' => ! advancedNewsTicker()->has_pro()
			],
			'grid-3'  => [
				'title'  => esc_html__( 'Layout 3', 'advanced-news-ticker' ),
				'url'    => esc_url( Fns::get_assets_url( 'images/layout/grid-3.svg' ) ),
				'is_pro' => ! advancedNewsTicker()->has_pro()
			],
			'hover-1' => [
				'title' => esc_html__( 'Layout 4', 'advanced-news-ticker' ),
				'url'   => esc_url( Fns::get_assets_url( 'images/layout/hover-1.svg' ) ),
			],
			'hover-2' => [
				'title'  => esc_html__( 'Layout 5', 'advanced-news-ticker' ),
				'url'    => esc_url( Fns::get_assets_url( 'images/layout/hover-2.svg' ) ),
				'is_pro' => ! advancedNewsTicker()->has_pro()
			],
			'hover-3' => [
				'title'  => esc_html__( 'Layout 6', 'advanced-news-ticker' ),
				'url'    => esc_url( Fns::get_assets_url( 'images/layout/hover-3.svg' ) ),
				'is_pro' => ! advancedNewsTicker()->has_pro()
			],
			'list-1'  => [
				'title' => esc_html__( 'Layout 3', 'advanced-news-ticker' ),
				'url'   => esc_url( Fns::get_assets_url( 'images/layout/list-1.svg' ) ),
			],
			'list-2'  => [
				'title'  => esc_html__( 'Layout 3', 'advanced-news-ticker' ),
				'url'    => esc_url( Fns::get_assets_url( 'images/layout/list-2.svg' ) ),
				'is_pro' => ! advancedNewsTicker()->has_pro()
			],
			'list-3'  => [
				'title'  => esc_html__( 'Layout 3', 'advanced-news-ticker' ),
				'url'    => esc_url( Fns::get_assets_url( 'images/layout/list-3.svg' ) ),
				'is_pro' => ! advancedNewsTicker()->has_pro()
			],
		];
	}
}