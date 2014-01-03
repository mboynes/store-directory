(function( $ ) {

	/**
	 * Some of the following code was influenced by the Google Maps API sample article,
	 * https://developers.google.com/maps/articles/phpsqlsearch_v3
	 */
	var wpsd_map;
	var wpsd_markers = [];
	var wpsd_wpsd_info_window;

	function wpsd_geocode_address( address, callback ) {
		var geocoder = new google.maps.Geocoder();
		geocoder.geocode({address: address}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				callback( results[0].geometry.location );
			} else {
				alert(address + ' not found');
			}
		});
	}

	function wpsd_clear_locations() {
		wpsd_info_window.close();
		for (var i = 0; i < wpsd_markers.length; i++) {
			wpsd_markers[i].setMap(null);
		}
		wpsd_markers.length = 0;
	}

	function wpsd_map_locations() {
		wpsd_clear_locations();

		if ( !wpsd_map_data || !wpsd_map_data.length)
			return;

		var bounds = new google.maps.LatLngBounds();
		for (var i = 0; i < wpsd_map_data.length; i++) {
			var latlng = new google.maps.LatLng(
				parseFloat(wpsd_map_data[i].latitude),
				parseFloat(wpsd_map_data[i].longitude)
			);
			bounds.extend(latlng);

			if ( typeof wpsd_custom_create_marker == 'undefined' ) {
				wpsd_create_marker(latlng, wpsd_map_data[i].name, wpsd_map_data[i].address);
			} else {
				// If you define a function, wpsd_custom_create_marker, you can customize the markers
				wpsd_custom_create_marker(latlng, wpsd_map_data[i]);
			}
		}
		wpsd_map.fitBounds(bounds);
	}

	function wpsd_create_marker(latlng, name, address) {
		var html = "<b>" + name + "</b> <br/>" + address;
		var marker = new google.maps.Marker({
			map: wpsd_map,
			position: latlng
		});
		google.maps.event.addListener(marker, 'click', function() {
			wpsd_info_window.setContent(html);
			wpsd_info_window.open(wpsd_map, marker);
		});
		wpsd_markers.push(marker);
	}

	function wpsd_set_geo_data( location ) {
		$('#sd_lat').val( location.lat() );
		$('#sd_lng').val( location.lng() );
		$('#sd_search')
			.prop( 'disabled', false )
			.val( $('#sd_search').data('idle') );
	}

	function wpsd_map_admin( location ) {
		$('#wpsd_map').show();
		$('#sd_lat').val( location.lat() );
		$('#sd_lng').val( location.lng() );
		var marker = new google.maps.Marker({
		    position: location,
		    map: new google.maps.Map( document.getElementById('wpsd_map'), { zoom: 17, center: location } )
		});
	}

	$(function(){
		if ( $('#wpsd_map').length && 'undefined' != typeof wpsd_center ) {
			$('#wpsd_map').show();
			wpsd_map = new google.maps.Map(document.getElementById("wpsd_map"), {
				center: new google.maps.LatLng(wpsd_center.lat, wpsd_center.lng),
				zoom: 4,
				mapTypeId: 'roadmap',
				mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU}
			});
			wpsd_info_window = new google.maps.InfoWindow();

			wpsd_map_locations();
		}

		if ( $('#sd_geolocate').length ) {
			$('#sd_geolocate').click(function(event) {
				event.preventDefault();
				wpsd_geocode_address( $('#sd_addr').val(), wpsd_map_admin );
			});
			if ( $('#sd_lat').val() && $('#sd_lng').val() ) {
				var location = new google.maps.LatLng(
					parseFloat( $('#sd_lat').val() ),
					parseFloat( $('#sd_lng').val() )
				);
				wpsd_map_admin( location );
			}
		} else if ( $('#sd_addr').length ) {
			$('#sd_addr').blur(function() {
				$('#sd_search')
					.prop( 'disabled', true )
					.data( 'idle', $('#sd_search').val() )
					.val( $('#sd_search').data('loading') );
				wpsd_geocode_address( $(this).val(), wpsd_set_geo_data );
			});
		}
	});

})(jQuery);