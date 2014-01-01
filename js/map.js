(function( $ ) {

	var map;
	var markers = [];
	var infoWindow;

	function geocode_address( address ) {
		var geocoder = new google.maps.Geocoder();
		geocoder.geocode({address: address}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				set_geo_data( results[0].geometry.location );
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

	$(function(){
		if ( 'undefined' != typeof wpsd_center && wpsd_center.lat && wpsd_center.lng ) {
			map = new google.maps.Map(document.getElementById("wpsd_map"), {
				center: new google.maps.LatLng(wpsd_center.lat, wpsd_center.lng),
				zoom: 4,
				mapTypeId: 'roadmap',
				mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU}
			});
			infoWindow = new google.maps.InfoWindow();

			map_locations();
		} else {
			$('#wpsd_map').remove();
		}

		$('#sd_addr').blur(function() {
			$('#sd_search')
				.prop( 'disabled', true )
				.data( 'idle', $('#sd_search').val() )
				.val( $('#sd_search').data('loading') );
			geocode_address( $(this).val() );
		});
	});

})(jQuery);