<?php 
/*
Plugin Name: PAC Meta Widget
Plugin URI: www.pacfl.org
Description: Customized widget for pacfl.org
Author: Joe Daigle
Version: 1
Min WP Version: 2.6
Max WP Version: 4.0
Author URI: 

*/
	class JDCustomMetaWidget extends WP_Widget {
	
	function __construct() {
		// Instantiate the parent object
		parent::__construct( false, 'Custom Meta Widget' );
		
		$args = array(
			'name'          => __( 'Custom Meta Widget', 'theme_text_domain' ),
			'id'            => '',
			'description'   => 'This widget allows customization of the default meta widget',
			'class'         => '',
			'before_widget' => '<li id="%1$s" class="widget %2$s">',
			'after_widget'  => '</li>',
			'before_title'  => '<h3 class="widgettitle">',
			'after_title'   => '</h3>' );
			
		
	}

	function widget( $args, $instance ) {
		extract($args);
		$title = 'Account';
		echo($before_widget);
		echo($before_title . $title . $after_title);
		echo('<ul>');
		wp_register();
		echo('<li>');
		wp_loginout();
		wp_meta();
		echo('</ul>');
		echo($after_widget); 
	}

	function update( $new_instance, $old_instance ) {
		// Save widget options
	}
	
	function form( $instance ) {
		// Output admin widget options form
	}

}

function myplugin_register_widgets() {
	register_widget( 'JDCustomMetaWidget' );
}

add_action( 'widgets_init', 'myplugin_register_widgets' );

?>