(function ( $ ) {
	"use strict";
$(function () {

	var geocoder;
	var map;

	function initialize() {
	geocoder = new google.maps.Geocoder();
	var latlng = new google.maps.LatLng(-34.397, 150.644);
	codeAddress();

	var mapOptions = {
	  zoom: 8,
	  center: latlng
	}
	map = new google.maps.Map(document.getElementById("resres_map_canvas"), mapOptions);
	}

	function codeAddress() {
	var address = org_add.org_add;
	geocoder.geocode( { 'address': address}, function(results, status) {
	  if (status == google.maps.GeocoderStatus.OK) {
	    map.setCenter(results[0].geometry.location);
	    var marker = new google.maps.Marker({
	        map: map,
	        position: results[0].geometry.location
	    });
	  } else {
	    console.log("Geocode was not successful for the following reason: " + status);
	    $('#resres_map_canvas').empty();
	  }
	});
	}

      google.maps.event.addDomListener(window, 'load', initialize);



});
}(jQuery));


