<?php
/*
Plugin Name:  MR Widget
Description:  A widget for displaying featured posts from a JSON API
Plugin URI:   https://github.com/dush88gs
Author:       Dushan Maduranga
Version:      1.0
License:      GPL v2 or later
License URI:  https://www.gnu.org/licenses/gpl-2.0.txt
*/



// disable direct file access
if ( ! defined( 'ABSPATH' ) ) {

	exit;

}

// Add style.css
function mr_widget_enqueue_styles() {

 wp_register_style( 'mr_widget_css', plugins_url( 'public/css/style.css', __FILE__ ) );
 wp_enqueue_style( 'mr_widget_css' );

}
add_action( 'wp_enqueue_scripts', 'mr_widget_enqueue_styles' );



class MrWidget extends WP_Widget {

    //widget constructor function
    function __construct() {

        $widget_options = array(
            'name' => 'Messages widget: Messages',
            'description' => 'A widget to display the featured posts'
        );

        // WP_Widget::__construct( string $id_base, string $name, array $widget_options = array(), array $control_options = array() )
        parent::__construct( 'mrwidget', '', $widget_options );

    }

    //function to output the widget form
    public function form( $instance ) {
        // print_r($instance);
        // and check our widget on admin widgets for the result array
        extract($instance);
        ?>

        <p>
            <label for="<?php echo $this->get_field_id( 'title'); ?>">Title:</label>
            <input class="widefat" type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php if( isset($title) ) echo esc_attr( $title ); ?>" />
        </p>

        <?php
    }

    //function to display the widget in the site
    public function widget( $args, $instance ) {
        // print_r($args);
        // and check the sidebar on frontend for the result array
        extract($args);
        extract($instance);

        $title = apply_filters( 'widget_title', $title );

        echo $before_widget;
            echo $before_title . $title . $after_title;
            echo "<p>" . $description . "</p>";
        echo $after_widget;
    }

}

//function to register the widget
function mr_widget_register() {
    register_widget('MrWidget');
}
add_action('widgets_init', 'mr_widget_register');

