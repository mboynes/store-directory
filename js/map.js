(function( $ ) {

	var map;
	var markers = [];
	var infoWindow;

	function geocode_address( address, callback ) {
		var geocoder = new google.maps.Geocoder();
		geocoder.geocode({address: address}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				callback( results[0].geometry.location );
			} else {
				alert(address + ' not found');
			}
		});
	}

	function clear_locations() {
		infoWindow.close();
		for (var i = 0; i < markers.length; i++) {
			markers[i].setMap(null);
		}
		markers.length = 0;
	}

	function map_locations() {
		clear_locations();

		if ( !wpsd_map_data || !wpsd_map_data.length)
			return;

		var bounds = new google.maps.LatLngBounds();
		for (var i = 0; i < wpsd_map_data.length; i++) {
			var name = wpsd_map_data[i].name;
			var address = wpsd_map_data[i].address;
			var distance = parseFloat(wpsd_map_data[i].distance);
			var latlng = new google.maps.LatLng(
				parseFloat(wpsd_map_data[i].latitude),
				parseFloat(wpsd_map_data[i].longitude)
			);

			create_marker(latlng, name, address);
			bounds.extend(latlng);
		}
		map.fitBounds(bounds);
	}

	function create_marker(latlng, name, address) {
		var html = "<b>" + name + "</b> <br/>" + address;
		var marker = new google.maps.Marker({
			map: map,
			position: latlng
		});
		google.maps.event.addListener(marker, 'click', function() {
			infoWindow.setContent(html);
			infoWindow.open(map, marker);
		});
		markers.push(marker);
	}

	function set_geo_data( location ) {
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
			map = new google.maps.Map(document.getElementById("wpsd_map"), {
				center: new google.maps.LatLng(wpsd_center.lat, wpsd_center.lng),
				zoom: 4,
				mapTypeId: 'roadmap',
				mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU}
			});
			infoWindow = new google.maps.InfoWindow();

			map_locations();
		}

		if ( $('#sd_geolocate').length ) {
			$('#sd_geolocate').click(function(event) {
				event.preventDefault();
				geocode_address( $('#sd_addr').val(), wpsd_map_admin );
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
				geocode_address( $(this).val(), set_geo_data );
			});
		}
	});

})(jQuery);