<?php

/**
 *
 */

if ( !class_exists( 'WPSD_Post_Meta' ) ) :

class WPSD_Post_Meta {

	private static $instance;

	private function __construct() {
		/* Don't do anything, needs to be initialized via instance() method */
	}

	public function __clone() { wp_die( "Please don't __clone WPSD_Post_Meta" ); }

	public function __wakeup() { wp_die( "Please don't __wakeup WPSD_Post_Meta" ); }

	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new WPSD_Post_Meta;
			self::$instance->setup();
		}
		return self::$instance;
	}

	public function setup() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
		add_action( 'save_post', array( $this, 'save' ) );
	}

	/**
	 * Adds the meta box container.
	 */
	public function add_meta_box( $post_type ) {
		if ( $post_type != WPSD_Post_Type()->post_type )
			return;

		add_meta_box(
			'store_location',
			__( 'Store Location', 'store-directory' ),
			array( $this, 'render_meta_box_content' ),
			WPSD_Post_Type()->post_type,
			'advanced',
			'high'
		);
	}

	/**
	 * Save the meta when the post is saved.
	 *
	 * @param int $post_id The ID of the post being saved.
	 */
	public function save( $post_id ) {
		# Ensure our prerequisites are present
		if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || empty( $_POST['wpsd_location_nonce'] ) || empty( $_POST['wpsd'] ) )
			return $post_id;

		# Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $_POST['wpsd_location_nonce'], 'wpsd_location' ) )
			return $post_id;

		# Check the user's permissions.
		global $post_type;
		if ( ! current_user_can( get_post_type_object( $post_type )->cap->edit_post, $post_id ) ) {
			return $post_id;
		}

		# Sanitize the user input.
		$address   = ! empty( $_POST['wpsd']['address'] )   ? sanitize_text_field( $_POST['wpsd']['address'] ) : '';
		$latitude  = ! empty( $_POST['wpsd']['latitude'] )  ? floatval( $_POST['wpsd']['latitude'] )           : '';
		$longitude = ! empty( $_POST['wpsd']['longitude'] ) ? floatval( $_POST['wpsd']['longitude'] )          : '';

		# Update the meta field.
		update_post_meta( $post_id, 'address',   $address );
		update_post_meta( $post_id, 'latitude',  $latitude );
		update_post_meta( $post_id, 'longitude', $longitude );
	}


	/**
	 * Render Meta Box content.
	 *
	 * @param WP_Post $post The post object.
	 */
	public function render_meta_box_content( $post ) {
		wp_nonce_field( 'wpsd_location', 'wpsd_location_nonce' );

		# Current values
		$address   = get_post_meta( $post->ID, 'address', true );
		$latitude  = get_post_meta( $post->ID, 'latitude', true );
		$longitude = get_post_meta( $post->ID, 'longitude', true );

		?>
		<p>
			<label for="sd_addr"><?php _e( 'Address', 'store-directory' ); ?></label><br />
			<input type="text" id="sd_addr" name="wpsd[address]" value="<?php echo esc_attr( $address ); ?>" size="50" />
			<a href="#" class="button-secondary" id="sd_geolocate"><?php _e( 'Geolocate', 'store-directory' ); ?></a>
		</p>
		<p>
			<label><?php _e( 'Coordinates', 'store-directory' ); ?></label><br />
			<span class="latlong">
				<input type="text" id="sd_lat" name="wpsd[latitude]" value="<?php echo esc_attr( $latitude ); ?>" size="10" />,
				<label for="sd_lat"><?php _e( 'Latitude', 'store-directory' ); ?></label>
			</span>
			<span class="latlong">
				<input type="text" id="sd_lng" name="wpsd[longitude]" value="<?php echo esc_attr( $longitude ); ?>" size="10" />
				<label for="sd_lng"><?php _e( 'Longitude', 'store-directory' ); ?></label>
			</span>
		</p>
		<div id="wpsd_map" style="width:100%;height:300px;display:none"></div>
		<?php
	}

}

function WPSD_Post_Meta() {
	return WPSD_Post_Meta::instance();
}
add_action( 'load-post.php',     'WPSD_Post_Meta' );
add_action( 'load-post-new.php', 'WPSD_Post_Meta' );


endif;