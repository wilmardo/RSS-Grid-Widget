<?php
/*
Plugin Name: RSS Grid Widget
Description: Creates a grid of a RSS feed
Version: 1.0
Author: Wilmar den Ouden
Author URI: https://wilmardenouden.nl
License: MIT
*/

class rss_grid_widget extends WP_Widget {

    /**
     * Sets up the widgets name etc
     */
    public function __construct() {
        $widget_ops = array(
            'classname' => 'rss_grid_widget',
            'description' => 'Makes a RSS grid widget with clickable images',
        );
        parent::__construct( 'rss_grid_widget', 'RSS Grid Widget', $widget_ops );
    }

    // widget form creation
    function form($instance) {
        // Check values
        if( $instance) {
            $title = esc_attr($instance['title']);
            $url = esc_attr($instance['url']);
            $items = esc_attr($instance['items']);
            $thumbsize = esc_attr($instance['thumbsize']);
        } else {
            $title = '';
            $url = '';
            $items = 0;
        }
        ?>

        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Widget Title', 'rss_grid_widget'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('url'); ?>"><?php _e('RSS Feed URL:', 'rss_grid_widget'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('url'); ?>" name="<?php echo $this->get_field_name('url'); ?>" type="text" value="<?php echo $url; ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('items'); ?>"><?php _e('Amount of items:', 'rss_grid_widget'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('items'); ?>" name="<?php echo $this->get_field_name('items'); ?>" type="text" value="<?php echo $items; ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('thumbsize'); ?>"><?php _e('Thumbnail size (default is 150x150):', 'rss_grid_widget'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('thumbsize'); ?>" name="<?php echo $this->get_field_name('thumbsize'); ?>" type="text" value="<?php echo $thumbsize; ?>" />
        </p>

        <?php
    }

    // widget update
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        // Fields
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['url'] = strip_tags($new_instance['url']);
        $instance['items'] = strip_tags($new_instance['items']);
        $instance['thumbsize'] = strip_tags($new_instance['thumbsize']);
        return $instance;
    }

    // widget display
    function widget($args, $instance) {
        extract( $args );
        // these are the widget options
        $title = apply_filters('widget_title', $instance['title']);
        $url = $instance['url'];
        $items = $instance['items'];
        $thumbsize = $instance['thumbsize'];
        echo $before_widget;

        // Display the widget
        echo '<div class="rss_grid_widget">';

        // Check if title is set
        if ( $title ) {
            echo $before_title . $title . $after_title;
        }

        // Default url
        if( !$url ) {
            $url = 'https://wilmardenouden.nl/?cat=2&feed=rss2';
        }
        // Default items
        if( !$items ) {
            $items = 6;
        }

        // Default thumbsize
        if( !$thumbsize ) {
            $thumbsize = '150x150';
        }

        $rawFeed = file_get_contents($url);
        $xml = new SimpleXmlElement($rawFeed);
        echo '<script type="text/javascript" charset="utf-8">jQuery(document).ready(function($) {$(".rss_grid_widget img").hover_caption();});</script>';
        $count = 0;
        foreach ($xml->channel->item as $item) {
            if ($count < $items) {
                echo '<a href="' . $item->link . '" target="_blank">';
                echo '<div class="rss_grid_widget_image">';
                $imageurl = '';
                $image = '';
                $explode = explode( "/", $item->children( 'http://search.yahoo.com/mrss/' )->content->attributes() );
                if(count($explode) > 0){
                    $image = array_pop($explode); // removes the last element, and returns it

                    if(count($explode) > 0){
                        $imageurl = implode('/', $explode); // glue the remaining pieces back together
                    }
                }
                $filename = explode( ".", $image); // split image on dot for filename and filetype
                $imagename = explode( "-", $filename[0]); // split filename on - to check for thumbparameters
                $thumbparams = array_pop($imagename); // get last element of array
                if (preg_match('/[x]+[0-9]/', $thumbparams)) {
                  // thumbparams exist but array_pop already removed -150x150
                } else {
                  array_push($imagename, $thumbparams); //no thumbparams so back at the end of the array
                }
                echo '<img src="'.$imageurl.'/'.implode('-', $imagename).'-'.$thumbsize.'.'.$filename[1].'" alt="'.$item->title.'" title="'.$item->title.'"/></div></a>';
                $count++;
            } else {
                break;
            }
        }
        echo $after_widget;
    }
}

// register widget
add_action( 'widgets_init', function(){
    register_widget( 'rss_grid_widget' );
	wp_register_style( 'rss_grid_widget_style', plugins_url('/style.css', __FILE__));
});
add_action('wp_enqueue_scripts', function() {
	wp_enqueue_style( 'rss_grid_widget_style' );
	wp_enqueue_script('hover_caption', plugins_url('/jquery.hover_caption.min.js', __FILE__), array('jquery'));
});
?>
