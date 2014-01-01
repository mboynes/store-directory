<?php

/**
 *
 */

if ( !class_exists( 'WPSD_Post_Type' ) ) :

class WPSD_Post_Type {

	private static $instance;

	public $post_type = 'store';

	private function __construct() {
		/* Don't do anything, needs to be initialized via instance() method */
	}

	public function __clone() { wp_die( "Please don't __clone WPSD_Post_Type" ); }

	public function __wakeup() { wp_die( "Please don't __wakeup WPSD_Post_Type" ); }

	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new WPSD_Post_Type;
			self::$instance->setup();
		}
		return self::$instance;
	}

	public function setup() {
		# You can disable this if you need to.
		if ( apply_filters( 'wpsd_register_post_type', true ) ) {
			add_action( 'init', array( $this, 'register_post_type' ) );

			# Ensure that the rewrite rules get set when the plugin activates
			register_activation_hook( __FILE__, array( $this, 'flush_rewrite_rules' ) );
			register_deactivation_hook( __FILE__, array( $this, 'flush_rewrite_rules' ) );
		}
	}

	public function register_post_type() {
		register_post_type( $this->post_type, apply_filters( 'wpsd_post_type_args', array(
			'public'      => true,
			'has_archive' => true,
			'supports'    => array( 'title' ),
			'labels'      => array(
				'name'                => __( 'Stores', 'store-directory' ),
				'singular_name'       => __( 'Store', 'store-directory' ),
				'all_items'           => __( 'Stores', 'store-directory' ),
				'new_item'            => __( 'New store', 'store-directory' ),
				'add_new'             => __( 'Add New', 'store-directory' ),
				'add_new_item'        => __( 'Add New store', 'store-directory' ),
				'edit_item'           => __( 'Edit store', 'store-directory' ),
				'view_item'           => __( 'View store', 'store-directory' ),
				'search_items'        => __( 'Search stores', 'store-directory' ),
				'not_found'           => __( 'No stores found', 'store-directory' ),
				'not_found_in_trash'  => __( 'No stores found in trash', 'store-directory' ),
				'parent_item_colon'   => __( 'Parent store', 'store-directory' ),
				'menu_name'           => __( 'Stores', 'store-directory' ),
			)
		) ) );
	}

	public function flush_rewrite_rules() {
		delete_option( 'rewrite_rules' );
	}
}

function WPSD_Post_Type() {
	return WPSD_Post_Type::instance();
}
add_action( 'after_setup_theme', 'WPSD_Post_Type' );

endif;