<?php

namespace AdvancedNewsTicker\Helper;

use Elementor\Icons_Manager;
use AdvancedNewsTicker\Modules\PostMeta;
use AdvancedNewsTicker\Modules\Svg;

class Fns {

	public static function doing_it_wrong( $function, $message, $version ) {
		// @codingStandardsIgnoreStart
		$message .= ' Backtrace: ' . wp_debug_backtrace_summary();
		_doing_it_wrong( $function, $message, $version );
	}

	/**
	 * @param        $template_name
	 * @param string $template_path
	 * @param string $default_path
	 *
	 * @return mixed|void
	 */
	public static function locate_template( $template_name, $template_path = '', $default_path = '' ) {
		$template_name = $template_name . ".php";

        error_log(print_r($template_path, true)."\n\n", 3, __DIR__.'/log.txt');
		if ( ! $template_path ) {
			$template_path = 'advanced-news-ticker/';
		}

		if ( ! $default_path ) {
			$default_path = untrailingslashit( ADVANCED_NEWS_TICKER_BASE_DIR ) . '/templates/';
		}

		$template_files = trailingslashit( $template_path ) . $template_name;

		$template = locate_template( apply_filters( 'ant_locate_template_files', $template_files, $template_name, $template_path, $default_path ) );

		// Get default template/.
		if ( ! $template ) {
			$template = trailingslashit( $default_path ) . $template_name;
		}

		return apply_filters( 'ant_locate_template', $template, $template_name );
	}

	/**
	 * Template Content
	 *
	 * @param string $template_name Template name.
	 * @param array $args Arguments. (default: array).
	 * @param string $template_path Template path. (default: '').
	 * @param string $default_path Default path. (default: '').
	 */
	public static function get_template( $template_name, $args = null, $template_path = '', $default_path = '' ) {

		if ( ! empty( $args ) && is_array( $args ) ) {
			extract( $args ); // @codingStandardsIgnoreLine
		}

		$located = self::locate_template( $template_name, $template_path, $default_path );


		if ( ! file_exists( $located ) ) {
			// translators: %s template
			self::doing_it_wrong( __FUNCTION__, sprintf( __( '%s does not exist.', 'classified-listing' ), '<code>' . $located . '</code>' ), '1.0' );

			return;
		}

		// Allow 3rd party plugin filter template file from their plugin.
		$located = apply_filters( 'ant_get_template', $located, $template_name, $args );

		do_action( 'ant_before_template_part', $template_name, $located, $args );

		include $located;

		do_action( 'ant_after_template_part', $template_name, $located, $args );
	}

	/**
	 * Get Asset URL
	 * @return string
	 */
	public static function get_assets_url( $path = null ) {
		return ADVANCED_NEWS_TICKER_BASE_URL . 'assets/' . $path;
	}

	/**
	 * Class list
	 *
	 * @param $clsses
	 *
	 * @return string
	 */
	public static function class_list( $clsses ): string {
		return trim( implode( ' ', $clsses ) );
	}

	/**
	 * Get all Post Type
	 * @return array
	 */
	public static function get_post_types( $exc = '' ) {
		$post_types = get_post_types(
			[
				'public' => true,
			],
			'objects'
		);
		$post_types = wp_list_pluck( $post_types, 'label', 'name' );

		$exclude = [
			'attachment',
			'revision',
			'nav_menu_item',
			'elementor_library',
			'tpg_builder',
			'e-landing-page',
			'elementor-raw_addons'
		];
		if ( $exc ) {
			$exclude = array_merge( $exclude, $exc );
		}

		foreach ( $exclude as $ex ) {
			unset( $post_types[ $ex ] );
		}

		return $post_types;
	}


	/**
	 * Get Nav menu list
	 * @return array
	 */
	public static function nav_menu_list() {
		$nav_menus     = wp_get_nav_menus();
		$nav_list      = [];
		$nav_list['0'] = __( 'Select A Menu', 'advanced-news-ticker' );
		foreach ( (array) $nav_menus as $_nav_menu ) {
			$nav_list[ $_nav_menu->term_id ] = $_nav_menu->name;
		}

		return $nav_list;
	}

	/**
	 * Render Elementor Link
	 *
	 * @param $link
	 * @param $context
	 *
	 * @return void
	 */
	public static function el_link( $link, $context = 'start', $class = 'ant-link', $empty_check = false ) {

		if ( $empty_check && empty( $link['url'] ) ) {
			return;
		}

		$target   = $link['is_external'] ? '_blank' : '_self';
		$nofollow = $link['nofollow'] ? "nofollow" : '';

		if ( 'start' === $context ) {
			printf(
				'<a href="%s" class="%s" target="%s" rel="%s">',
				! empty( $link['url'] ) ? esc_url( $link['url'] ) : '#',
				esc_attr( $class ),
				esc_attr( $target ),
				esc_attr( $nofollow )
			);
		}

		if ( 'end' === $context ) {
			echo '</a>';
		}

	}

	/**
	 * Link Attribute
	 *
	 * @param $link
	 *
	 * @return void
	 */
	public static function link_button( $link, $title, $class = '' ) {
		if ( ! empty( $link['url'] ) ) {
			$attr = "href={$link['url']}";
			$attr .= ! empty( $link['is_external'] ) ? " target=_blank" : '';
			$attr .= ! empty( $link['nofollow'] ) ? " rel=nofollow" : '';
			$attr .= $class ? " class={$class}" : '';

			printf( "<a %s>%s</a>", $attr, $title );
		} else {
			self::html( $title );
		}
	}

	/**
	 * Link Attribute
	 *
	 * @param $link
	 *
	 * @return string
	 */

	public static function link_attr( $link, $echo = true ) {
		if ( empty( $link['url'] ) ) {
			return '';
		}

		$attr = "href={$link['url']}";
		$attr .= ! empty( $link['is_external'] ) ? " target=_blank" : '';
		$attr .= ! empty( $link['nofollow'] ) ? " rel=nofollow" : '';
		if ( ! $echo ) {
			return esc_attr( $attr );
		}
		echo esc_attr( $attr );
	}

	/**
	 * Allow HTML Tag
	 *
	 * @param $content
	 * @param $tag
	 * @param $class
	 *
	 * @return void
	 */
	public static function allow_html_tag( $content, $tag = 'h2', $class = '', $link = null ) {
		if ( ! $content ) {
			return;
		}

		$allowed_tags = apply_filters( 'raw_addons_allowed_html_headings_tag', [
			'h1',
			'h2',
			'h3',
			'h4',
			'h5',
			'h6',
			'p',
			'div',
		] );

		$title_tag = in_array( strtolower( $tag ), $allowed_tags, true ) ? $tag : 'div';

		if ( null !== $link ) {
			printf(
				'<%1$s class="%2$s"><a %3$s>%4$s</a></%5$s>',
				esc_attr( $title_tag ),
				esc_attr( $class ),
				self::link_attr( $link, false ),
				self::html( $content, 'plain', false ),
				esc_attr( $title_tag )
			);

		} else {
			printf(
				'<%s class="%s">%s</%s>',
				esc_attr( $title_tag ),
				esc_attr( $class ),
				self::html( $content, 'plain', false ),
				esc_attr( $title_tag )
			);
		}

	}

	/**
	 * Post title with allowed tag
	 *
	 * @param $title_tag
	 * @param $class
	 *
	 * @return void
	 */
	public static function post_title_with_tag( $title_tag, $class = 'entry-title default-max-width' ) {
		$title = sprintf( "<a href='%s'>%s</a>", get_permalink(), get_the_title() );
		Fns::allow_html_tag( $title, $title_tag, 'entry-title default-max-width' );
	}

	/**
	 * Label change with language dir
	 *
	 * @param $rtl_label
	 * @param $rtr_label
	 *
	 * @return mixed
	 */
	public static function label_rtl( $rtl_label, $rtr_label ) {
		return is_rtl() ? $rtl_label : $rtr_label;
	}

	/**
	 * Elementor Animation List
	 *
	 * @return array
	 */
	public static function el_animation() {
		return [
			""                       => __( "None", "ant-core" ),
			'toptobottom'            => __( 'Top to Bottom', 'ant-core' ),
			'bottomtotop'            => __( 'Bottom to Top', 'ant-core' ),
			'lefttoright'            => __( 'Left to Right', 'ant-core' ),
			'righttoleft'            => __( 'Right to Left', 'ant-core' ),
			"grow"                   => __( "Grow", "ant-core" ),
			"shrink"                 => __( "Shrink", "ant-core" ),
			"pulse"                  => __( "Pulse", "ant-core" ),
			"pulse-grow"             => __( "Pulse Grow", "ant-core" ),
			"pulse-shrink"           => __( "Pulse Shrink", "ant-core" ),
			"push"                   => __( "Push", "ant-core" ),
			"pop"                    => __( "Pop", "ant-core" ),
			"bounce-in"              => __( "Bounce In", "ant-core" ),
			"bounce-out"             => __( "Bounce Out", "ant-core" ),
			"rotate"                 => __( "Rotate", "ant-core" ),
			"grow-rotate"            => __( "Grow Rotate", "ant-core" ),
			"float"                  => __( "Float", "ant-core" ),
			"sink"                   => __( "Sink", "ant-core" ),
			"bob"                    => __( "Bob", "ant-core" ),
			"hang"                   => __( "Hang", "ant-core" ),
			"skew"                   => __( "Skew", "ant-core" ),
			"skew-forward"           => __( "Skew Forward", "ant-core" ),
			"skew-backward"          => __( "Skew Backward", "ant-core" ),
			"wobble-vertical"        => __( "Wobble Vertical", "ant-core" ),
			"wobble-horizontal"      => __( "Wobble Horizontal", "ant-core" ),
			"wobble-to-bottom-right" => __( "Wobble To Bottom Right", "ant-core" ),
			"wobble-to-top-right"    => __( "Wobble To Top Right", "ant-core" ),
			"wobble-top"             => __( "Wobble Top", "ant-core" ),
			"wobble-bottom"          => __( "Wobble Bottom", "ant-core" ),
			"wobble-skew"            => __( "Wobble Skew", "ant-core" ),
			"buzz"                   => __( "Buzz", "ant-core" ),
			"buzz-out"               => __( "Buzz Out", "ant-core" ),
		];
	}

	/**
	 * Grid Columns
	 *
	 * @return mixed|null
	 */
	public static function column_list() {
		$columns = [
			''   => __( '-Select-', 'advanced-news-ticker' ),
			'2'  => __( '6 Columns', 'advanced-news-ticker' ),
			'20' => __( '5 Columns', 'advanced-news-ticker' ),
			'3'  => __( '4 Columns', 'advanced-news-ticker' ),
			'4'  => __( '3 Columns', 'advanced-news-ticker' ),
			'6'  => __( '2 Columns', 'advanced-news-ticker' ),
			'12' => __( '1 Columns', 'advanced-news-ticker' ),
		];

		return apply_filters( 'raw_addons_gird_columns', $columns );
	}


	/**
	 * Grid Columns
	 *
	 * @return mixed|null
	 */
	public static function slider_list() {
		$columns = [
			''   => __( '--Select--', 'advanced-news-ticker' ),
			'1'  => __( '1 Columns', 'advanced-news-ticker' ),
			'2'  => __( '2 Columns', 'advanced-news-ticker' ),
			'3'  => __( '3 Columns', 'advanced-news-ticker' ),
			'4'  => __( '4 Columns', 'advanced-news-ticker' ),
			'5'  => __( '5 Columns', 'advanced-news-ticker' ),
			'6'  => __( '6 Columns', 'advanced-news-ticker' ),
			'7'  => __( '7 Columns', 'advanced-news-ticker' ),
			'8'  => __( '8 Columns', 'advanced-news-ticker' ),
			'9'  => __( '9 Columns', 'advanced-news-ticker' ),
			'10' => __( '10 Columns', 'advanced-news-ticker' ),
		];

		return apply_filters( 'raw_addons_slider_columns', $columns );
	}

	/**
	 * Grid Column
	 *
	 * @param $data
	 *
	 * @return string
	 */
	public static function grid_column( $data ) {
		$default = [ '4', '6', '12' ];

		if ( in_array( $data['layout'], [ 'list-1', 'list-2', 'list-3' ] ) ) {
			$default = [ '12', '12', '12' ];
		}

		$grid_column_lg = ! empty( $data['grid_column'] ) ? $data['grid_column'] : $default[0];
		$grid_column_md = ! empty( $data['grid_column_tablet'] ) ? $data['grid_column_tablet'] : $default[1];
		$grid_column_sm = ! empty( $data['grid_column_mobile'] ) ? $data['grid_column_mobile'] : $default[2];

		return "col-lg-$grid_column_lg col-md-$grid_column_md col-sm-$grid_column_sm col-$grid_column_sm";
	}

	/**
	 * Make layout depended on class for css
	 *
	 * @param $layout
	 * @param $prefix
	 *
	 * @return string
	 */
	public static function layout_prefix( $layout, $prefix = 'blog' ) {
		$parts = explode( '-', $layout );

		return $prefix . '-' . $parts[0];
	}

	/**
	 * Get Slack Arguments
	 *
	 * @param $args
	 *
	 * @return array
	 */
	public static function swiper_args( $data, $uid ) {

		$default = [ '4', '2', '1' ];

		$slider_column_lg = absint( ! empty( $data['slider_column'] ) ? $data['slider_column'] : $default[0] );
		$slider_column_md = absint( ! empty( $data['slider_column_tablet'] ) ? $data['slider_column_tablet'] : $default[1] );
		$slider_column_sm = absint( ! empty( $data['slider_column_mobile'] ) ? $data['slider_column_mobile'] : $default[2] );

		if ( $data['v_direction'] == 'yes' ) {
			$slider_column_lg = $slider_column_md = $slider_column_sm = 1;
		}
		$swiper_options = [
			'slidesPerView'  => $slider_column_lg,
			'spaceBetween'   => $data['spaceBetween'] ?? 30,
			'speed'          => $data['speed'] ?? 300,
			'grabCursor'     => $data['grabCursor'] === 'yes',
			'loop'           => $data['loop'] === 'yes',
			'allowTouchMove' => $data['allowTouchMove'] === 'yes',
			'autoHeight'     => $data['autoHeight'] === 'yes',
			'lazy'           => $data['lazy'] === 'yes',
			'observer'       => true,
			'observeParents' => true,

			// Breakpoints
			'breakpoints'    => [
				0    => [
					'slidesPerView'  => $slider_column_sm,
					'slidesPerGroup' => $data['slidesPerGroup'] === 'yes' ? $slider_column_sm : 1,
				],
				768  => [
					'slidesPerView'  => $slider_column_md,
					'slidesPerGroup' => $data['slidesPerGroup'] === 'yes' ? $slider_column_md : 1,
				],
				1200 => [
					'slidesPerView'  => $slider_column_lg,
					'slidesPerGroup' => $data['slidesPerGroup'] === 'yes' ? $slider_column_lg : 1,
				],
			],
		];


		if ( 'yes' === $data['v_direction'] ) {
			$swiper_options['direction'] = 'vertical';
		}

		if ( 'yes' === $data['pagination'] ) {
			$swiper_options['pagination'] = [
				'el'             => '.swiper-pagination',
				'clickable'      => true,
				'dynamicBullets' => $data['dynamic_dots'] === 'yes',
			];
		}

		if ( 'yes' === $data['navigation'] ) {
			$swiper_options['navigation'] = [
				'nextEl' => '.swiper-button-next-' . $uid,
				'prevEl' => '.swiper-button-prev-' . $uid,
			];
		}
		if ( 'yes' === $data['scrollbar'] ) {
			$swiper_options['scrollbar'] = [
				'el'        => '.swiper-scrollbar',
				'draggable' => true,
			];
		}

		return apply_filters( 'raw_addons_swiper_args', $swiper_options );


	}

	/**
	 * Get Slick Arguments
	 *
	 * @param $args
	 *
	 * @return array
	 */
	public static function slick_args( $data, $default = '' ) {

		if ( ! $default ) {
			$default = [ '4', '2', '1' ];

			if ( in_array( $data['layout'], [ 'list', 'list-2', 'list-3' ] ) ) {
				$default = [ '2', '2', '1' ];
			}
		}
		$slider_column_lg = absint( ! empty( $data['slider_column'] ) ? $data['slider_column'] : $default[0] );
		$slider_column_md = absint( ! empty( $data['slider_column_tablet'] ) ? $data['slider_column_tablet'] : $default[1] );
		$slider_column_sm = absint( ! empty( $data['slider_column_mobile'] ) ? $data['slider_column_mobile'] : $default[2] );

		$is_fade = (bool) $data['fade'];

		if ( $is_fade ) {
			$slider_column_lg = $slider_column_md = $slider_column_sm = 1;
		}

		return [
			'dots'           => (bool) $data['dots'],
			'arrows'         => false, //We have custom arrows
			'fade'           => $is_fade,
			'autoplay'       => (bool) $data['autoplay'],
			'adaptiveHeight' => (bool) $data['adaptiveHeight'],
			'infinite'       => (bool) $data['infinite'],
			'draggable'      => (bool) $data['draggable'],
			'rtl'            => is_rtl(),
			'speed'          => absint( $data['speed'] ),
			'autoplaySpeed'  => absint( $data['autoplaySpeed'] ),
			'slidesToShow'   => $slider_column_lg,
			'slidesToScroll' => $slider_column_lg,
			'cssEase'        => 'cubic-bezier(0.7, 0, 0.3, 1)',
			'touchThreshold' => 100,
			'responsive'     => [
				[
					'breakpoint' => 1240,
					'settings'   => [
						'slidesToShow'   => $slider_column_md,
						'slidesToScroll' => $slider_column_md,
						'infinite'       => true
					]
				],
				[
					'breakpoint' => 768,
					'settings'   => [
						'slidesToShow'   => $slider_column_sm,
						'slidesToScroll' => $slider_column_sm
					]
				],
				[
					'breakpoint' => 480,
					'settings'   => [
						'slidesToShow'   => $slider_column_sm,
						'slidesToScroll' => $slider_column_sm
					]
				]
			]
		];


	}


	/**
	 * Post Query Arguments
	 *
	 * @param $data
	 *
	 * @return array
	 */
	public static function query_args( $data ) {
		$args = [
			'post_type'           => 'post',
			'ignore_sticky_posts' => 1,

			'post_status' => 'publish',
		];

		if ( $data['post_limit'] ) {
			$args['posts_per_page'] = $data['post_limit'];
		} else {
			if ( $data['layout'] == 'flex-1' ) {
				$args['posts_per_page'] = 5;
			}
		}


		if ( ! empty( $data['category'] ) || ! empty( $data['post_tag'] ) ) {
			$args['tax_query']['relation'] = $data['tax_relation'];

			if ( ! empty( $data['category'] ) ) {
				$args['tax_query'][] = [
					'taxonomy' => 'category',
					'field'    => 'id',
					'terms'    => $data['category'],
				];
			}

			if ( ! empty( $data['post_tag'] ) ) {
				$args['tax_query'][] = [
					'taxonomy' => 'post_tag',
					'field'    => 'id',
					'terms'    => $data['post_tag'],
				];
			}

		}

		if ( ! empty( $data['tags'] ) ) {
			$args['tag_slug__in'] = $data['tags'];
		}

		if ( ! empty ( $data['orderby'] ) ) {
			$args['orderby'] = $data['orderby'];
		}

		if ( ! empty( $data['order'] ) ) {
			$args['order'] = $data['order'];
		}


		if ( ! empty( $data['post_id'] ) ) {
			$args['post__in'] = $data['post_id'];
		}

		if ( ! empty( $data['exclude'] ) ) {
			$args['post__not_in'] = $data['exclude'];
		}

		if ( $data['offset'] ) {
			$args['offset'] = $data['offset'];
		}

		return $args;
	}

	/**
	 * Get posts list by post type
	 *
	 * @param $post_type
	 *
	 * @return array
	 */
	public static function posts_list( $post_type = 'post' ) {
		$posts = get_posts( [
			'numberposts' => - 1,
			'post_type'   => $post_type
		] );

		$categories = [];
		foreach ( $posts as $pn_cat ) {
			$categories[ $pn_cat->ID ] = get_the_title( $pn_cat->ID );
		}

		return $categories;
	}

	/**
	 * Get Taxonomies Lists
	 *
	 * @param $taxonomy
	 *
	 * @return array
	 */
	public static function taxonomies_list( $taxonomy = 'category' ) {
		$categories = get_categories( [
			'taxonomy' => $taxonomy
		] );

		$category_dropdown = [];
		if ( ! empty( $categories ) ) {
			foreach ( $categories as $category ) {
				$category_dropdown[ $category->term_id ] = $category->name;
			}
		}

		return $category_dropdown;
	}

	/**
	 * Get All Image Sizes
	 *
	 * @return array
	 */
	public static function image_size_lists() {
		global $_wp_additional_image_sizes;
		$image_sizes = [
			''          => __( '--Select--', 'advanced-news-ticker' ),
			'full'      => __( 'Full', 'advanced-news-ticker' ),
			'large'     => __( 'Large', 'advanced-news-ticker' ),
			'medium'    => __( 'Medium', 'advanced-news-ticker' ),
			'thumbnail' => __( 'Thumbnail', 'advanced-news-ticker' ),
		];
		if ( ! empty( $_wp_additional_image_sizes ) ) {
			foreach ( $_wp_additional_image_sizes as $index => $item ) {
				$image_sizes[ $index ] = __( ucwords( $index . ' - ' . $item['width'] . 'x' . $item['height'] ), 'advanced-news-ticker' );
			}
		}

		return $image_sizes;
	}

	/**
	 * Flex post dynamic classes
	 *
	 * @param $layout
	 * @param $count
	 *
	 * @return string
	 */
	public static function dynamic_classes( $layout, $count ) {
		switch ( $layout ) {
			case 'flex-1':
			case 'flex-12':
				$classes = $count === 1 ? 'has-big-image' : '';
				break;
			case 'flex-2':
				$classes = $count === 1 ? 'has-big-image is-overlay-layout' : '';
				$classes .= in_array( $count, [ 2, 3, 4 ] ) ? 'flex-list-layout' : '';
				break;
			case 'flex-3':
				$classes = $count === 1 ? 'has-big-image' : '';
				$classes .= in_array( $count, [ 2, 3, 4, 5, 6, 7 ] ) ? 'flex-list-layout' : '';
				break;
			case 'flex-4':
			case 'flex-5':
			case 'flex-6':
				$classes = $count === 1 ? 'has-big-image is-overlay-layout' : 'flex-list-layout';
				break;
			case 'flex-7':
				$classes = $count === 1 ? 'has-big-image' : '';
				$classes .= in_array( $count, [ 1, 2, 3, 4, 5 ] ) ? ' is-overlay-layout' : '';
				break;
			case 'flex-8':
				$classes = $count === 1 ? 'has-big-image' : '';
				$classes .= in_array( $count, [ 1, 2, 3 ] ) ? ' is-overlay-layout' : '';
				break;
			case 'flex-9':
			case 'flex-10':
				$classes = $count === 1 ? 'has-big-image' : '';
				$classes .= in_array( $count, [ 1, 2, 3, 4 ] ) ? ' is-overlay-layout' : '';
				break;
			case 'flex-11':
				$classes = $count === 1 ? 'has-big-image' : '';
				$classes .= ' flex-list-layout';
				break;
			default:
				$classes = '';
				break;
		}


		return ' ' . $classes;
	}

	public static function loader_spin() {
		echo '<div class="ant-loader"><div></div><div></div></div>';
	}

	public static function html_all( $html, $all_html = false, $echo = true ) {
		if ( ! $html ) {
			return;
		}

		$html   = stripslashes_deep( $html );
		$output = $all_html ? $html : wp_kses_post( $html );

		if ( $echo ) {
			echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		} else {
			return $output;
		}
	}

	public static function html_old( $html, $context = '', $echo = true ) {

		if ( 'plain' === $context ) {
			$tags = [
				'a'    => [ 'href' => [] ],
				'b'    => [],
				'span' => [ 'class' => [] ],
				'p'    => [ 'class' => [] ]
			];
		} elseif ( 'social' === $context ) {
			$tags = [
				'a' => [ 'href' => [] ],
				'b' => []
			];
		} elseif ( 'allow_link' === $context ) {
			$tags = [
				'a'   => [
					'class'  => [],
					'href'   => [],
					'rel'    => [],
					'title'  => [],
					'target' => [],
				],
				'img' => [
					'alt'    => [],
					'class'  => [],
					'height' => [],
					'src'    => [],
					'srcset' => [],
					'width'  => [],
				],
				'b'   => []
			];
		} elseif ( 'allow_title' === $context ) {
			$tags = [
				'a'      => [
					'class'  => [],
					'href'   => [],
					'rel'    => [],
					'title'  => [],
					'target' => [],
				],
				'br'     => [],
				'p'      => [],
				'span'   => [
					'class' => [],
					'style' => [],
				],
				'img'    => [
					'alt'    => [],
					'class'  => [],
					'height' => [],
					'src'    => [],
					'srcset' => [],
					'width'  => [],
				],
				'b'      => [],
				'strong' => [],
			];
		} else {
			$tags = [
				'a'          => [
					'class'  => [],
					'href'   => [],
					'rel'    => [],
					'title'  => [],
					'target' => [],
				],
				'abbr'       => [
					'title' => [],
				],
				'b'          => [],
				'br'         => [],
				'sub'        => [],
				'blockquote' => [
					'cite' => [],
				],
				'cite'       => [
					'title' => [],
				],
				'code'       => [],
				'del'        => [
					'datetime' => [],
					'title'    => [],
				],
				'dd'         => [],
				'div'        => [
					'class' => [],
					'title' => [],
					'style' => [],
					'id'    => [],
				],
				'dl'         => [],
				'dt'         => [],
				'em'         => [],
				'h1'         => [
					'class' => [],
					'title' => [],
					'style' => [],
					'id'    => [],
				],
				'h2'         => [
					'class' => [],
					'title' => [],
					'style' => [],
					'id'    => [],
				],
				'h3'         => [
					'class' => [],
					'title' => [],
					'style' => [],
					'id'    => [],
				],
				'h4'         => [
					'class' => [],
					'title' => [],
					'style' => [],
					'id'    => [],
				],
				'h5'         => [
					'class' => [],
					'title' => [],
					'style' => [],
					'id'    => [],
				],
				'h6'         => [
					'class' => [],
					'title' => [],
					'style' => [],
					'id'    => [],
				],
				'i'          => [
					'class' => [],
				],
				'img'        => [
					'alt'    => [],
					'class'  => [],
					'height' => [],
					'src'    => [],
					'srcset' => [],
					'width'  => [],
				],
				'li'         => [
					'class' => [],
				],
				'ol'         => [
					'class' => [],
				],
				'p'          => [
					'class' => [],
				],
				'q'          => [
					'cite'  => [],
					'title' => [],
				],
				'span'       => [
					'class' => [],
					'title' => [],
					'style' => [],
				],
				'strike'     => [],
				'strong'     => [],
				'ul'         => [
					'class' => [],
				],
				'iframe'     => [
					'class'                 => [],
					'id'                    => [],
					'name'                  => [],
					'src'                   => [],
					'title'                 => [],
					'frameBorder'           => [],
					'width'                 => [],
					'height'                => [],
					'scrolling'             => [],
					'allowvr'               => [],
					'allow'                 => [],
					'allowFullScreen'       => [],
					'webkitallowfullscreen' => [],
					'mozallowfullscreen'    => [],
					'loading'               => [],
				],
			];
		}
		if ( $echo ) {
			echo wp_kses( $html, $tags );
		} else {
			return wp_kses( $html, $tags );
		}

	}

	/**
	 * Print HTML with wp_kses
	 *
	 * @param $html
	 * @param $context
	 * @param $echo
	 *
	 * @return string|void
	 */
	public static function html( $html, $context = 'plain', $echo = true ) {
		// Define reusable tag configurations
		$base_tags = [
			'a'      => [ 'href' => [], 'class' => [], 'rel' => [], 'title' => [], 'target' => [] ],
			'b'      => [],
			'img'    => [ 'src' => [], 'alt' => [], 'class' => [], 'width' => [], 'height' => [], 'srcset' => [] ],
			'p'      => [ 'class' => [] ],
			'span'   => [ 'class' => [], 'style' => [], 'title' => [] ],
			'strong' => [],
			'br'     => [],
		];

		// Context-specific tag adjustments
		$tags_by_context = [
			'plain'   => $base_tags,
			'link'    => [ 'a' => $base_tags['a'] ],
			'image'   => [ 'img' => $base_tags['img'] ],
			'title'   => $base_tags,
			'default' => array_merge( $base_tags, [
				'abbr'       => [ 'title' => [] ],
				'blockquote' => [ 'cite' => [] ],
				'code'       => [],
				'del'        => [ 'datetime' => [], 'title' => [] ],
				'div'        => [ 'class' => [], 'style' => [], 'title' => [], 'id' => [] ],
				'h1'         => [ 'class' => [], 'style' => [], 'title' => [], 'id' => [] ],
				'h2'         => [ 'class' => [], 'style' => [], 'title' => [], 'id' => [] ],
				'h3'         => [ 'class' => [], 'style' => [], 'title' => [], 'id' => [] ],
				'h4'         => [ 'class' => [], 'style' => [], 'title' => [], 'id' => [] ],
				'h5'         => [ 'class' => [], 'style' => [], 'title' => [], 'id' => [] ],
				'h6'         => [ 'class' => [], 'style' => [], 'title' => [], 'id' => [] ],
				'i'          => [ 'class' => [] ],
				'li'         => [ 'class' => [] ],
				'ol'         => [ 'class' => [] ],
				'ul'         => [ 'class' => [] ],
				'iframe'     => [
					'class'                 => [],
					'id'                    => [],
					'name'                  => [],
					'src'                   => [],
					'title'                 => [],
					'frameBorder'           => [],
					'width'                 => [],
					'height'                => [],
					'scrolling'             => [],
					'allow'                 => [],
					'allowvr'               => [],
					'allowFullScreen'       => [],
					'webkitallowfullscreen' => [],
					'mozallowfullscreen'    => [],
					'loading'               => []
				],
			] ),
		];

		// Determine tags for the given context
		$tags = $tags_by_context[ $context ] ?? $tags_by_context['default'];

		// If echo is false, return the sanitized HTML
		if ( ! $echo ) {
			return wp_kses( $html, $tags );
		}

		// Echo the sanitized HTML
		echo wp_kses( $html, $tags );
	}

	/**
	 * Blog Meta Style
	 * @return array
	 */
	public static function blog_meta_list() {
		return [
			'author'   => __( 'Author', 'neuzin' ),
			'date'     => __( 'Date', 'neuzin' ),
			'category' => __( 'Category', 'neuzin' ),
			'tag'      => __( 'Tag', 'neuzin' ),
			'comment'  => __( 'Comment', 'neuzin' ),
			'reading'  => __( 'Reading', 'neuzin' ),
			'view'     => __( 'Views', 'neuzin' ),
		];
	}

	public static function separate_meta( $post_type = 'post', $includes = [ 'category' ] ) {
		?>
        <div class="separate-meta">
			<?php
			PostMeta::get_meta( $post_type, [
				'with_list' => false,
				'with_icon' => false,
				'include'   => $includes,
			] );
			?>
        </div>
		<?php

	}

	public static function entry_content( $limit = '' ) {
		$length = $limit ?: neuzin_option( 'ant_excerpt_limit' );
		echo wp_trim_words( get_the_excerpt(), $length );
	}

	public static function read_more( $readmore_text, $show_btn_icon, $btn_icon ) {
		?>
        <a class="btn post-read-more" href="<?php the_permalink(); ?>">
			<?php
			echo esc_html( $readmore_text );

			if ( $show_btn_icon ) {
				if ( ! empty( $btn_icon['value'] ) ) {
					Icons_Manager::render_icon( $btn_icon );
				} else {
					echo "<i class='raw-icon-arrow-right'></i>";
				}
			}
			?>
        </a>
		<?php
	}


	/**
	 * Post reading time calculate
	 *
	 * @param $content
	 * @param $is_zero
	 * @param $reading_suffix
	 *
	 * @return string
	 */
	public static function reading_time_count( $content = '', $is_zero = false, $reading_suffix = '' ) {
		global $post;
		$post_content = $content ?? $post->post_content;
		$word         = str_word_count( wp_strip_all_tags( strip_shortcodes( $post_content ) ) );
		$m            = floor( $word / 200 );
		$s            = floor( $word % 200 / ( 200 / 60 ) );
		if ( $is_zero && $m < 10 ) {
			$m = '0' . $m;
		}
		if ( $is_zero && $s < 10 ) {
			$s = '0' . $s;
		}
		$suffix = $reading_suffix ? ' ' . $reading_suffix : null;

		/* translators: used time as singular and plular */
		$text = sprintf( _n( '%s Min', '%s Mins', $m, 'ant' ), $m );

		if ( $m < 1 ) {
			/* translators: used time as singular and plular */
			$text = sprintf( _n( '%s Second', '%s Seconds', $s, 'ant' ), $s );
		}

		return $text . $suffix;
	}


	public static function post_views_count( $text = '', $post_id = 0 ) {

		if ( empty( $post_id ) ) {
			$post_id = get_the_ID();
		}
		$views_class = '';
		$count_key   = 'ant_post_views';
		$view_count  = get_post_meta( $post_id, $count_key, true );

		if ( ! empty( $view_count ) ) {
			if ( $view_count > 1000 ) {
				$views_class = 'very-high';
			} elseif ( $view_count > 100 ) {
				$views_class = 'high';
			} elseif ( $view_count > 5 ) {
				$views_class = 'rising';
			}
		} else if ( $view_count == '' ) {
			$view_count = 0;
		} else {
			$view_count = 0;
		}

		if ( $view_count == 1 ) {
			$neuzin_view_html = esc_html__( 'View', 'neuzin' );
		} else {
			$neuzin_view_html = esc_html__( 'Views', 'neuzin' );
		}
		$view_count        = number_format_i18n( (int) $view_count );
		$neuzin_views_html = '<span class="view-number" >' . $view_count . '</span> ' . $neuzin_view_html;

		return '<span class="meta-views meta-item ' . $views_class . '">' . $neuzin_views_html . '</span> ';
	}

	public static function isColorDark( $hex = '' ) {
		if ( '' == $hex ) {
			return;
		}
		// Remove the hash at the start if it's there
		$hex = str_replace( '#', '', $hex );

		// Convert hex to RGB
		$r = hexdec( substr( $hex, 0, 2 ) );
		$g = hexdec( substr( $hex, 2, 2 ) );
		$b = hexdec( substr( $hex, 4, 2 ) );

		// Calculate the brightness (luminance)
		// Using the formula for luminance: (0.299 * R + 0.587 * G + 0.114 * B)
		$luminance = ( 0.299 * $r + 0.587 * $g + 0.114 * $b ) / 255;

		// Return true if the color is dark, otherwise false
		return $luminance < 0.8;
	}

	/**
	 * Hex To RGBA
	 *
	 * @param $hex
	 * @param $alpha
	 *
	 * @return string
	 */
	public static function hex_to_rgba( $color, $alpha = 1 ) {
		// If the color is already rgba, return it directly
		if ( stripos( $color, 'rgba' ) === 0 ) {
			return $color;
		}

		// If the color is rgb, add the alpha value and return as rgba
		if ( stripos( $color, 'rgb' ) === 0 ) {
			return str_replace( 'rgb', 'rgba', $color ) . ", $alpha)";
		}

		// Handle hexadecimal colors
		$color = str_replace( '#', '', $color );

		// Handle shorthand hex
		if ( strlen( $color ) === 3 ) {
			$color = $color[0] . $color[0] . $color[1] . $color[1] . $color[2] . $color[2];
		}

		// Convert hex to RGB
		$r = hexdec( substr( $color, 0, 2 ) );
		$g = hexdec( substr( $color, 2, 2 ) );
		$b = hexdec( substr( $color, 4, 2 ) );

		// Handle alpha if present in hex
		if ( strlen( $color ) === 8 ) {
			$alpha = round( hexdec( substr( $color, 6, 2 ) ) / 255, 2 );
		}

		return "rgba($r, $g, $b, $alpha)";
	}


	/**
	 * Post Layout Data
	 *
	 * @param $data
	 * @param $post_classes
	 *
	 * @return array
	 */
	public static function post_layout_data( $data, $post_classes = '' ) {
		return [
			'template'                => 'post-grid',
			'layout'                  => $data['layout'],
			'title_tag'               => $data['title_tag'],
			'filter_source'           => $data['filter_source'] ?? '',
			'post_limit'              => $data['post_limit'],
			'meta_list'               => $data['meta_list'],
			'thumbnail_visibility'    => $data['thumbnail_visibility'],
			'thumbnail_size'          => $data['thumbnail_size'],
			'content_limit'           => $data['content_limit'],
			'separate_cat_visibility' => $data['separate_cat_visibility'],
			'meta_visibility'         => $data['meta_visibility'],
			'meta_author'             => $data['meta_author'],
			'excerpt_visibility'      => $data['excerpt_visibility'],
			'readmore_visibility'     => $data['readmore_visibility'],
			'readmore_text'           => $data['readmore_text'],
			'show_btn_icon'           => $data['show_btn_icon'],
			'btn_icon'                => $data['btn_icon'] ?? '',
			'separate_cat'            => $data['separate_cat'],
			'separate_meta_position'  => $data['separate_meta_position'],
			'hide_images'             => $data['hide_images'] ?? '',
			'post_class'              => $post_classes,
			'thumbnail_cat'           => ( $data['separate_cat_visibility'] && 'in-thumb' === $data['separate_meta_position'] ) ? $data['separate_cat'] : '',
		];
	}

	/**
	 * Thumbnail Visibility condition
	 *
	 * @param $data
	 * @param $count
	 *
	 * @return bool
	 */
	public static function thumbnail_visibility( $data, $count ) {

		if ( ! $data['thumbnail_visibility'] ) {
			return false;
		}

		if ( ! isset( $data['hide_images'] ) ) {
			return true;
		}

		return $data['hide_images'] == 0 || $data['hide_images'] >= $count;
	}

	/**
	 * Slick Navigation
	 *
	 * @return void
	 */
	public static function slick_nav_html() {
		?>
        <div class="navigation">
            <button class="slick-prev slick-arrow" aria-label="Previous" type="button">
				<?php echo esc_html( 'Previous' ) ?>
            </button>
            <button class="slick-next slick-arrow" aria-label="Next" type="button" tabindex="-1">
				<?php echo esc_html( 'Next' ) ?>
            </button>
        </div>
		<?php
	}


	/**
	 * Get user social information
	 *
	 * @param $social_links
	 *
	 * @return void
	 */
	public static function get_user_social_info( $social_links ) {
		if ( count( $social_links ) < 1 && ! is_array( $social_links ) ) {
			return;
		}
		?>
        <ul class="team-social">
            <li class="social-item-wrap">
                <a href="#" class="social-hover-icon social-link">
                    <i class="raw-icon-share"></i>
                </a>
                <ul class="team-social-dropdown">
					<?php foreach ( $social_links as $icon ) : ?>

                        <li class="social-item">
                            <a href="<?php echo esc_html( $icon['social_link'] ) ?>"
                               class="social-link" target="_blank"
                               title="<?php echo esc_html( $icon['social_title'] ) ?>">

                                <span class="elementor-icon">
								    <?php Icons_Manager::render_icon( $icon['social_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                                </span>
                            </a>
                        </li>

					<?php endforeach; ?>
                </ul>
            </li>
        </ul>
		<?php
	}

}


