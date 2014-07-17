(function ( $ ) {
	"use strict";

$(function () {

/*summer sale 2014*/

$('#resres_summer_2014_dismiss').on('click', function() {
    event.preventDefault();
	var data = {
		action: 'resres_summer_2014_dismiss',
		type: 'POST',
		dataType: 'text',
		"resres_summer_sale": "dismissed"
	};
	jQuery.post(ajaxurl, data, function(response) {
		$('#resres_summer_2014_dismiss_wrap').remove();
	});
})

/*******************************************/

});


}(jQuery));











