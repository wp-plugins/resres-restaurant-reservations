/*!
 * jQuery UI Touch Punch 0.2.3
 *
 * Copyright 2011–2014, Dave Furfero
 * Dual licensed under the MIT or GPL Version 2 licenses.
 *
 * Depends:
 *  jquery.ui.widget.js
 *  jquery.ui.mouse.js
 */
!function(a){function f(a,b){if(!(a.originalEvent.touches.length>1)){a.preventDefault();var c=a.originalEvent.changedTouches[0],d=document.createEvent("MouseEvents");d.initMouseEvent(b,!0,!0,window,1,c.screenX,c.screenY,c.clientX,c.clientY,!1,!1,!1,!1,0,null),a.target.dispatchEvent(d)}}if(a.support.touch="ontouchend"in document,a.support.touch){var e,b=a.ui.mouse.prototype,c=b._mouseInit,d=b._mouseDestroy;b._touchStart=function(a){var b=this;!e&&b._mouseCapture(a.originalEvent.changedTouches[0])&&(e=!0,b._touchMoved=!1,f(a,"mouseover"),f(a,"mousemove"),f(a,"mousedown"))},b._touchMove=function(a){e&&(this._touchMoved=!0,f(a,"mousemove"))},b._touchEnd=function(a){e&&(f(a,"mouseup"),f(a,"mouseout"),this._touchMoved||f(a,"click"),e=!1)},b._mouseInit=function(){var b=this;b.element.bind({touchstart:a.proxy(b,"_touchStart"),touchmove:a.proxy(b,"_touchMove"),touchend:a.proxy(b,"_touchEnd")}),c.call(b)},b._mouseDestroy=function(){var b=this;b.element.unbind({touchstart:a.proxy(b,"_touchStart"),touchmove:a.proxy(b,"_touchMove"),touchend:a.proxy(b,"_touchEnd")}),d.call(b)}}}(jQuery);



(function ( $ ) {
	"use strict";
$(function () {



if($('#resres_date').length > 0 ) {

if($('#resres_date').val().length == 0 ) {
	$('.resres_time').removeProp('required').prop('disabled', this);
}
else {
	//need to check time, in case it's after form submission - otherwise JS goes nuts.
	if( $('#resres_time').val().length > 0  ) {

	} else {
	le_time_picker();
	}
}

}




//http://stackoverflow.com/questions/19395558/highlight-disable-specific-days-in-jquery-ui-datepicker
function disableAllTheseDays(date) {

	var disabledDays = resres_time_vars.overrides;

    var m = date.getMonth(), d = date.getDate(), y = date.getFullYear();

    for (var i = 0; i < disabledDays.length; i++) {

        // this works //if($.inArray(y + '-' + (m+1) + '-' + d,disabledDays) != -1) {
        if($.inArray((m+1) + '-' + d,disabledDays) != -1) {
            return [false];
        }
    }
    return [true];
}


function disable_these_weekdays(date) {
	var day = date.getDay();

	var days = [];

	if(resres_time_vars.open_sunday != 1 ) { days.push( parseInt(0) ); }
	if(resres_time_vars.open_monday != 1 ) { days.push( 1 ); }
	if(resres_time_vars.open_tuesday != 1 ) { days.push( 2 ); }
	if(resres_time_vars.open_wednesday != 1 ) { days.push( 3 ); }
	if(resres_time_vars.open_thursday != 1 ) { days.push( 4); }
	if(resres_time_vars.open_friday != 1 ) { days.push( 5); }
	if(resres_time_vars.open_saturday != 1 ) { days.push( 6); }

    if( $.inArray( day, days ) == -1 ) {
    	return [true, ''];
    }
    else {
    return [false, ''];
    }
}


function setCustomDate(date) {
	var clazz = '';
	var arr2 = disableAllTheseDays(date);
	var arr3 = disable_these_weekdays(date);

	return [(!arr2[0] || !arr3[0]) ? false : true, clazz];  	
}




var dateToday = new Date();


$('.usst_widget_datepicker').datepicker({
	minDate: dateToday,
	dateFormat: resres_time_vars.date_format,
	altFormat: "yy-mm-dd",
  	altField: "#resres_hidden_date",
  	firstDay: 1,
	beforeShowDay: setCustomDate
    });

$('.hasPicker').datepicker({
	minDate: dateToday,
	dateFormat: resres_time_vars.date_format,
	altFormat: "yy-mm-dd",
  	altField: "#resres_hidden_date",
  	firstDay: 1,
	beforeShowDay: setCustomDate,
	onSelect: function(){
	le_time_picker();

	} //end onselect function
}).attr('readonly', true);




	var custom_max_partysize = jQuery('#maxpartysetting').val();

	var resres_post_q = 1;

	if(resres_post.post_party_size != 'null') { resres_post_q = resres_post.post_party_size; }
	//console.log(resres_post_q);

	$('#resres_partysize').val(resres_post_q);


	var slider = $('.slider'),
			tooltip = $('.tooltip');

	var slider_widget = $('.slider_widget');



	//tooltip.hide();

	slider.slider({
		range: "min",
		min: 1,
		max:custom_max_partysize,
		value: resres_post_q,

		start: function(event,ui) {
		  //tooltip.fadeIn('fast');
		},

		slide: function(event, ui) {

			var value = slider.slider('value');

			tooltip.css('left', value).text(ui.value);

			$('#resres_partysize').val(ui.value);

		},

		stop: function(event,ui) {
		  //tooltip.fadeOut('fast');
		},
	});



	slider_widget.slider({
		range: "min",
		min: 1,
		max:custom_max_partysize,
		value: resres_post_q,

		start: function(event,ui) {
		  //tooltip.fadeIn('fast');
		},

		slide: function(event, ui) {

			var value = slider_widget.slider('value');

			tooltip.css('left', value).text(ui.value);

			$('#resres_partysize_widget').val(ui.value);

		},

		stop: function(event,ui) {
		  //tooltip.fadeOut('fast');
		},
	});



	$('.resres_success_message').delay(5000).fadeOut('slow');




var max_party_size_validation = jQuery('#maxpartysetting').val();


$( "#resres_form" ).validate({
  rules: {
    resres_email: {
      required: true,
      email: true
    },
    resres_partysize: {
    	required: true,
    	digits: true,
    	min: 1,
    	max: parseInt(max_party_size_validation)
    }
  }
});




function le_time_picker() {

			//important need to reset the timepicker, otherwise it may not work right after form submission
		$('.resres_time').timepicker('remove');

			//stops it from jumping
		$(".ui-datepicker a").removeAttr("href");

		if( jQuery('.resres_success_message').length > 0 ) {
		}
		else {
			$('.resres_time').timepicker('remove').val('');			
		}

		$('.resres_time').removeProp('disabled', this).prop('required', this);


		//var selectedDay = new Date($('#resres_hidden_date').val());
		//var weekday = selectedDay.getUTCDay();

		//This block is here becuase Safari spits its dummy out unless you pass it an expicitly formatted date
		var selectedDay = $('#resres_hidden_date').val(),
		    values = selectedDay.split(/[^0-9]/),
		    year = parseInt(values[0], 10),
		    month = parseInt(values[1], 10) - 1, // Month is zero based, so subtract 1
		    day = parseInt(values[2], 10),
		    hours = '0',
		    minutes = '0',
		    seconds = '0',
		    formattedDate;
		formattedDate = new Date(year, month, day, hours, minutes, seconds);
		//end Safaris hissy fit
		

		//var selectedDay = new Date($('#resres_hidden_date').val());
		var weekday = formattedDate.getDay();


		var dstarts = '8';
		var dends = '24';




		if(weekday == 0 ) {

			var dstarts = resres_time_vars.sunday_from_one;

			var dstarts_xxx = dstarts.split(':');

			var dends = resres_time_vars.sunday_to_one;

			var dends_xxx = dends.split(':');

			var dstarts2 = resres_time_vars.sunday_from_two;
			if(resres_time_vars.sunday_from_two == ':') {
				dstarts2_xxx = "false";
			}
			else {
				var dstarts2_xxx = dstarts2.split(':');
			}

			var dends2 = resres_time_vars.sunday_to_two;
			if(resres_time_vars.sunday_to_two == ':') {
				dends2_xxx = "false";
			}
			else {
				var dends2_xxx = dends2.split(':');
			}
		}
		if(weekday == 1 ) {

			var dstarts = resres_time_vars.monday_from_one;

			var dstarts_xxx = dstarts.split(':');

			var dends = resres_time_vars.monday_to_one;

			var dends_xxx = dends.split(':');

			var dstarts2 = resres_time_vars.monday_from_two;
			if(resres_time_vars.monday_from_two == ':') {
				dstarts2_xxx = "false";
			}
			else {
				var dstarts2_xxx = dstarts2.split(':');
			}

			var dends2 = resres_time_vars.monday_to_two;
			if(resres_time_vars.monday_to_two == ':') {
				dends2_xxx = "false";
			}
			else {
				var dends2_xxx = dends2.split(':');
			}


		}
		if(weekday == 2 ) {

			var dstarts = resres_time_vars.tuesday_from_one;

			var dstarts_xxx = dstarts.split(':');

			var dends = resres_time_vars.tuesday_to_one;

			var dends_xxx = dends.split(':');

			var dstarts2 = resres_time_vars.tuesday_from_two;
			if(resres_time_vars.tuesday_from_two == ':') {
				dstarts2_xxx = "false";
			}
			else {
				var dstarts2_xxx = dstarts2.split(':');
			}

			var dends2 = resres_time_vars.tuesday_to_two;
			if(resres_time_vars.tuesday_to_two == ':') {
				dends2_xxx = "false";
			}
			else {
				var dends2_xxx = dends2.split(':');
			}
		}
		if(weekday == 3 ) {

			var dstarts = resres_time_vars.wednesday_from_one;

			var dstarts_xxx = dstarts.split(':');

			var dends = resres_time_vars.wednesday_to_one;

			var dends_xxx = dends.split(':');

			var dstarts2 = resres_time_vars.wednesday_from_two;
			if(resres_time_vars.wednesday_from_two == ':') {
				dstarts2_xxx = "false";
			}
			else {
				var dstarts2_xxx = dstarts2.split(':');
			}

			var dends2 = resres_time_vars.wednesday_to_two;
			if(resres_time_vars.wednesday_to_two == ':') {
				dends2_xxx = "false";
			}
			else {
				var dends2_xxx = dends2.split(':');
			}
		}
		if(weekday == 4 ) {

			var dstarts = resres_time_vars.thursday_from_one;

			var dstarts_xxx = dstarts.split(':');

			var dends = resres_time_vars.thursday_to_one;

			var dends_xxx = dends.split(':');

			var dstarts2 = resres_time_vars.thursday_from_two;
			if(resres_time_vars.thursday_from_two == ':') {
				dstarts2_xxx = "false";
			}
			else {
				var dstarts2_xxx = dstarts2.split(':');
			}

			var dends2 = resres_time_vars.thursday_to_two;
			if(resres_time_vars.thursday_to_two == ':') {
				dends2_xxx = "false";
			}
			else {
				var dends2_xxx = dends2.split(':');
			}
		}
		if(weekday == 5 ) {

			
			var dstarts = resres_time_vars.friday_from_one;

			var dstarts_xxx = dstarts.split(':');

			var dends = resres_time_vars.friday_to_one;

			var dends_xxx = dends.split(':');

			var dstarts2 = resres_time_vars.friday_from_two;
			if(resres_time_vars.friday_from_two == ':') {
				dstarts2_xxx = "false";
			}
			else {
				var dstarts2_xxx = dstarts2.split(':');
			}

			var dends2 = resres_time_vars.friday_to_two;
			if(resres_time_vars.friday_to_two == ':') {
				dends2_xxx = "false";
			}
			else {
				var dends2_xxx = dends2.split(':');
			}
		}
		if(weekday == 6 ) {

			var dstarts = resres_time_vars.saturday_from_one;

			var dstarts_xxx = dstarts.split(':');

			var dends = resres_time_vars.saturday_to_one;

			var dends_xxx = dends.split(':');

			var dstarts2 = resres_time_vars.saturday_from_two;
			if(resres_time_vars.saturday_from_two == ':') {
				dstarts2_xxx = "false";
			}
			else {
				var dstarts2_xxx = dstarts2.split(':');
			}

			var dends2 = resres_time_vars.saturday_to_two;
			if(resres_time_vars.saturday_to_two == ':') {
				dends2_xxx = "false";
			}
			else {
				var dends2_xxx = dends2.split(':');
			}
		}

		if(dstarts2_xxx == 'false' || dends2_xxx == 'false') {

				if(parseInt(dends_xxx[0]) < parseInt(dstarts_xxx[0]) ) {

					var a = dends_xxx[0];
					var b = dstarts_xxx[0];

					$('.resres_time').timepicker({
					'step' : 15,
					'disableTimeRanges': [
					[a, b],
					[c, d]
					],
					//'minTime' : b,
					//'maxTime' : e,
					'disableTouchKeyboard' : true,
					'timeFormat' : resres_time_vars.time_format
					});

					$('.ui-timepicker-select option:disabled').wrap(function() { return "<span class='wibble'></span>"; });

				}
				else {

					var a = "00:00";
					var b = parseInt(dstarts_xxx[0]) + dstarts_xxx[1];
					var c = dends_xxx[0] + dends_xxx[1];
					var d = "23:59";


					$('.resres_time').timepicker({
					'step' : 15,
					'disableTimeRanges': [
					[a, b],
					[c, d]
					],
					'disableTouchKeyboard' : true,
					//'minTime' : b,
					//'maxTime' : e,
					'timeFormat' : resres_time_vars.time_format
					});


				}

		}
		else {


					if(parseInt( dends2_xxx[0] ) < parseInt( dstarts_xxx[0] ) ) {
						var a = dends2_xxx[0] + dends2_xxx[1];
						var b = dstarts_xxx[0] + dstarts_xxx[1];
						var c = dends_xxx[0] + dends_xxx[1];
						var d = dstarts2_xxx[0] + dstarts2_xxx[1];

						$('.resres_time').timepicker({
						'step' : 15,
						'disableTimeRanges': [
						[a, b],
						[c, d]
						],
						'disableTouchKeyboard' : true,
						//'minTime' : b,
						//'maxTime' : e,
						'timeFormat' : resres_time_vars.time_format
						});
					$('.ui-timepicker-select option:disabled').wrap(function() { return "<span class='wibble'></span>"; });

					}
					else {

						var a = "00:00";
						var b = dstarts_xxx[0] + dstarts_xxx[1];
						var c = dends_xxx[0] + dends_xxx[1];
						var d = dstarts2_xxx[0] + dstarts2_xxx[1];
						var e = dends2_xxx[0] + dends2_xxx[1];
						var f = "23:59";

						$('.resres_time').timepicker({
						'step' : 15,
						'disableTimeRanges': [
						[a, b],
						[c, d],
						[e, f],
						],
						'disableTouchKeyboard' : true,
						//'minTime' : b,
						//'maxTime' : e,
						'timeFormat' : resres_time_vars.time_format
						});

					}
		}
}

/*
      function initialize() {
        var map_canvas = document.getElementById('resres_map_canvas');
        var map_options = {
          center: new google.maps.LatLng(44.5403, -78.5463),
          zoom: 8,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        }
        var map = new google.maps.Map(map_canvas, map_options)
      }
      google.maps.event.addDomListener(window, 'load', initialize);
*/






});
}(jQuery));


