<?php

function wpsd_admin_scripts() {
	global $hook_suffix, $post_type;
	if ( ( 'post.php' == $hook_suffix || 'post-new.php' == $hook_suffix ) && WPSD_Post_Type()->post_type == $post_type ) {
		wp_enqueue_script( 'wpsd_gmaps', 'http://maps.google.com/maps/api/js?sensor=false', array(), '1.0', true );
		wp_enqueue_script( 'wpsd_map', WPSD_URL . 'js/map.js', array( 'jquery', 'wpsd_gmaps' ), '1.0', true );
	}
}
add_action( 'admin_enqueue_scripts', 'wpsd_admin_scripts' );