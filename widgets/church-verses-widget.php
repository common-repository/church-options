<?php

class church_Options_Verses_Widget extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		$widget_ops = array( 
			'classname' => 'church_verses_widget',
			'description' => 'Widget for displaying random Bible verses',
		);
		parent::__construct( 'church_verses_widget', 'Church Options Random Verse Widget', $widget_ops );
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		// outputs the content of the widget
        extract( $args );
        $title = apply_filters( 'widget_title', $instance[ 'title' ] );
        // The Query
        $loop = new WP_Query( array(
            'post_type'		   => 'church_verses',
            'orderby'		   => 'rand',
            'posts_per_page'   => 1,
        ) );
        echo '<section class="widget bordered verses-widget">';
        if ( $title ) {
            echo $before_title . $title . $after_title;
        }
        ?>
        
        <!--  The Loop -->
        <?php if ( $loop->have_posts() ) : while ( $loop->have_posts() ) : $loop->the_post(); ?>

                <article class="post">
                    <?php the_content(); ?>
                    <div class="entry-title">
                        <p class="italicized"><?php the_title(); ?></p>
                    </div>
                </article>

        <?php
            endwhile;
            endif;
            wp_reset_query();
        
        echo $after_widget;
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {
		// outputs the options form on admin
        
        // Set widget defaults
        $defaults = array(
            'title'     => '',
        );
        // Parse current settings with defaults
        extract( wp_parse_args( ( array ) $instance, $defaults ) );
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>">Title</label>
            <input class="widefat" type="text" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" value="<?php echo esc_attr( $title ); ?>">
        </p>
        
        <?php
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		// processes widget options to be saved
        $instance = $old_instance;
        $instance[ 'title' ] = strip_tags( $new_instance[ 'title' ] );
        return $instance;
	}
}

add_action( 'widgets_init', function(){
	register_widget( 'church_Options_Verses_Widget' );
});