<?php

function wpsd_the_store_search_form() {
	$options = wpsd_radius_options();
	?>
	<form action="<?php echo get_post_type_archive_link( WPSD_Post_Type()->post_type ) ?>" method="get" id="sd_search_form">
		<input type="hidden" name="sd_lat" value="<?php echo esc_attr( get_query_var( 'sd_lat' ) ) ?>" id="sd_lat" />
		<input type="hidden" name="sd_lng" value="<?php echo esc_attr( get_query_var( 'sd_lng' ) ) ?>" id="sd_lng" />
		<p>
			<label for="sd_addr"><?php _e( 'Address', 'store-directory' ); ?>
			<input type="text" name="sd_addr" value="<?php echo esc_attr( get_query_var( 'sd_addr' ) ) ?>" id="sd_addr" />
		</p>
		<p>
			<label for="sd_radius"><?php _e( 'Radius', 'store-directory' ); ?>
			<select name="sd_radius" id="sd_radius">
				<?php foreach ( $options as $radius ) : ?>
					<option value="<?php echo absint( $radius ); ?>"<?php selected( $radius, get_query_var( 'sd_radius' ) ); ?>>
						<?php if ( 'miles' == WPSD_Post_Type()->units )
							printf( _n( '%d mile', '%d miles', $radius, 'store-directory' ), $radius );
						else
							printf( _n( '%d km', '%d km', $radius, 'store-directory' ), $radius ); ?>
					</option>
				<?php endforeach ?>
			</select>
		</p>
		<p>
			<input type="submit" value="<?php esc_attr_e( 'Search', 'store-directory' ); ?>" data-loading="<?php esc_attr_e( 'Locating...', 'store-directory' ); ?>" id="sd_search" />
		</p>
	</form>
	<?php
}

function wpsd_the_map( $posts, $lat, $long ) {
	?>
	<div id="wpsd_map" style="display:none"></div>
	<script type="text/javascript">
	var wpsd_map_data = <?php echo json_encode( $posts ) ?>;
	var wpsd_center = { lat: <?php echo floatval( $lat ) ?>, lng: <?php echo floatval( $long ) ?> };
	</script>
	<style type="text/css">
	#wpsd_map { width: 100%; height: 400px }
	</style>
	<?php
}

function wpsd_radius_options() {
	return apply_filters( 'wpsd_radius_options', array( 5, 10, 25, 50, 100 ) );
}