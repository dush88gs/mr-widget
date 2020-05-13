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

// enqueue admin style
function mr_widget_enqueue_style_admin() {
	
	/*
		wp_enqueue_style(
			string           $handle,
			string           $src = '',
			array            $deps = array(),
			string|bool|null $ver = false,
			string           $media = 'all'
		)
	*/
	
	$src = plugin_dir_url( __FILE__ ) .'admin/css/admin.css';

	wp_enqueue_style( 'mr_widget_admin_css', $src, array(), null, 'all' );

}
add_action( 'admin_enqueue_scripts', 'mr_widget_enqueue_style_admin' );


// enqueue admin script
function mr_widget_enqueue_script_admin() {
	
	/*
		wp_enqueue_script(
			string           $handle,
			string           $src = '',
			array            $deps = array(),
			string|bool|null $ver = false,
			bool             $in_footer = false
		)
	*/
	
	$src = plugin_dir_url( __FILE__ ) .'admin/js/admin.js';

	wp_enqueue_script( 'mr_widget_admin_js', $src, array(), null, false );

}
add_action( 'admin_enqueue_scripts', 'mr_widget_enqueue_script_admin' );



// enqueue public style
function mr_widget_enqueue_style_public() {

	$src = plugin_dir_url( __FILE__ ) .'public/css/style.css';

	wp_enqueue_style( 'mr_widget_public_css', $src, array(), null, 'all' );

}
add_action( 'wp_enqueue_scripts', 'mr_widget_enqueue_style_public' );


// enqueue public script
function mr_widget_enqueue_script_public() {

	$src = plugin_dir_url( __FILE__ ) .'public/js/script.js';

	wp_enqueue_script( 'mr_widget_public_js', $src, array(), null, true );

}
add_action( 'wp_enqueue_scripts', 'mr_widget_enqueue_script_public' );



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

        <p class="mr-input-number">
            <label for="<?php echo $this->get_field_id( 'post_count'); ?>">No. of messages</label>
            <input class="widefat" type="number" id="<?php echo $this->get_field_id( 'post_count' ); ?>" name="<?php echo $this->get_field_name( 'post_count' ); ?>" min="1" max="10" value="<?php echo !empty($post_count) ? $post_count : 3; ?>" />
        </p>

        <p class="mr-input-number">
            <label for="<?php echo $this->get_field_id( 'word_count'); ?>">No. of word in message body</label>
            <input class="widefat" type="number" id="<?php echo $this->get_field_id( 'word_count' ); ?>" name="<?php echo $this->get_field_name( 'word_count' ); ?>" min="10" max="40" value="<?php echo !empty($word_count) ? $word_count : 15; ?>" />
        </p>

        <?php
    }

    //function to display the widget in the site
    public function widget( $args, $instance ) {
        // print_r($args);
        // print_r($instance); die();
        // and check the sidebar on frontend for the result array
        extract($args);
        extract($instance);

        $title = apply_filters( 'widget_title', $title );

        if ( empty($title) ) {
            $title = "Messages";
        }

        $data = $this->featured_posts($post_count, $word_count);

        echo $before_widget;
            echo $before_title . $title . $after_title;
            echo "<p>" . $description . "</p>";
        echo $after_widget;
    }

    private function featured_posts($post_count, $word_count) {
      $this->fetch_featured_posts($post_count, $word_count);
    }

    private function fetch_featured_posts($post_count, $word_count) {
      $f_posts = wp_remote_get("http://jsonplaceholder.typicode.com/posts");
      echo "<pre>";
        print_r($f_posts['body']);
      echo "</pre>";
    }

}

//function to register the widget
function mr_widget_register() {
    register_widget('MrWidget');
}
add_action('widgets_init', 'mr_widget_register');

