<?php
/**
 * @var $title_tag
 * @var $ticker_speed
 * @var $ticker_title
 * @var $display_type
 * @var $ticker_delay
 * @var $ticker_item
 * @var $catid
 * @var $query_type
 * @author  devofwp
 * @since   1.0
 * @version 1.0
 *
 */

use Elementor\Icons_Manager;

?>
<?php if ( $query->have_posts() ) : ?>
    <div class="ant-breaking-news-ticker ant-newsticker" data-newsticker="<?php echo esc_js( json_encode( $ticker_obj ) ) ?>" style="opacity: 0">
		<?php if ( $breaking_title ) : ?>
            <div class="ticker-label">
				<?php
				if ( ! empty( $breaking_icon['value'] ) && in_array( $breaking_icon_post, [ 'left', 'both' ] ) ) {
					echo "<span class='elementor-icon'>";
					Icons_Manager::render_icon( $breaking_icon );
					echo "</span>";
				}
				?>
                <span class="breaking-title"><?php echo esc_html( $breaking_title ); ?></span>
				<?php
				if ( ! empty( $breaking_icon['value'] ) && in_array( $breaking_icon_post, [ 'right', 'both' ] ) ) {
					echo "<span class='elementor-icon'>";
					Icons_Manager::render_icon( $breaking_icon );
					echo "</span>";
				}
				?>
            </div>
		<?php endif; ?>
        <div class="ticker-news">
            <ul>
				<?php while ( $query->have_posts() ) : $query->the_post();
					if ( ! get_the_title() ) {
						continue;
					}
					?>
                    <li>
                        <a href="<?php the_permalink(); ?>">
							<?php
							if ( ! empty( $post_title_icon['value'] ) ) {
								echo "<span class='elementor-icon'>";
								Icons_Manager::render_icon( $post_title_icon );
								echo "</span>";
							}
							?>
                            <span class="title"><?php the_title() ?></span>
                        </a>
                    </li>
				<?php endwhile; ?>
            </ul>
        </div>
		<?php if ( $controls_visibility ) :?>
            <div class="ticker-controls">
                <button class="ticker-arrow">
                    <span class="bn-prev">
                        <span class='elementor-icon'><?php Icons_Manager::render_icon( $arrow_icon ); ?></span>
                    </span>
                </button>
				<?php if ( $pause_visibility ) : ?>
                    <button class="ticker-pause"><span class="ticker-action"></span></button>
				<?php endif; ?>
                <button class="ticker-arrow">
                    <span class="bn-next">
                        <span class='elementor-icon'><?php Icons_Manager::render_icon( $arrow_icon ); ?></span>
                    </span>
                </button>
            </div>
		<?php endif; ?>
    </div>
<?php else: ?>
    <p><?php echo esc_html__( "No posts found", "advanced-news-ticker" ) ?></p>
<?php endif; ?>
<?php wp_reset_postdata(); ?>