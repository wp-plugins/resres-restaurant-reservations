//Timepickewr is http://jonthornton.github.io/jquery-timepicker/

var item_count = 1;
var section_count = 0;


(function ( $ ) {
	"use strict";


	$(function () {

var dateToday = new Date();


$('.datepicker_filter').datepicker({
	dateFormat: resres_time_vars.date_format,
	altFormat: "yy-mm-dd",
  	altField: "#resres_hidden_date"
})


//moved this to a function below, leavign it here for now just in case.
/*

$('.datepicker').datepicker({
	minDate: dateToday,
	dateFormat: resres_time_vars.date_format,
	altFormat: "yy-mm-dd",
  	altField: "#resres_hidden_date",
	beforeShowDay: function(day) {
		//this function will disable days when restuarant isnt open.
        var day = day.getDay();

		var days = [];

		if(resres_time_vars.open_sunday != 1 ) { days.push( parseInt(0) ); }
		if(resres_time_vars.open_monday != 1 ) { days.push( 1 ); }
		if(resres_time_vars.open_tuesday != 1 ) { days.push( 2 ); }
		if(resres_time_vars.open_wednesday != 1 ) { days.push( 3 ); }
		if(resres_time_vars.open_thursday != 1 ) { days.push( 4); }
		if(resres_time_vars.open_friday != 1 ) { days.push( 5); }
		if(resres_time_vars.open_saturday != 1 ) { days.push( 6); }


        if( $.inArray( day, days ) == -1) {
        	return [true, ''];
        }
        else {
        return [false, ''];
        }

    },
	onSelect: function(){

		//stops it from jumping
		$(".ui-datepicker a").removeAttr("href");

		//$('.resres_time').timepicker('remove').val('');

		//$('.resres_time').removeProp('disabled', this).prop('required', this);

		var selectedDay = new Date($('#resres_hidden_date').val());

		var weekday = selectedDay.getDay();
		var dstarts = '8';
		var dends = '24';


		if(weekday == 0 ) {
			//dstarts = resres_time_vars.sunday_from_one; dends = resres_time_vars.sunday_to_one;
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
			//dstarts = resres_time_vars.monday_from_one; dends = resres_time_vars.monday_to_one;
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
			dstarts = resres_time_vars.tuesday_from_one; dends = resres_time_vars.tuesday_to_one;
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
			//dstarts = resres_time_vars.wednesday_from_one; dends = resres_time_vars.wednesday_to_one;
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
			//dstarts = resres_time_vars.thursday_from_one; dends = resres_time_vars.thursday_to_one;
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
			//dstarts = resres_time_vars.friday_from_one; dends = resres_time_vars.friday_to_one;
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
			//dstarts = resres_time_vars.saturday_from_one; dends = resres_time_vars.saturday_to_one;
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
					'timeFormat' : resres_time_vars.time_format
					});
				}
				else {

					var a = "00:00";
					var b = parseInt(dstarts_xxx[0]) + ":00";
					var c = dends_xxx[0] + ":00";
					var d = "23:59";

					$('.resres_time').timepicker({
					'step' : 15,
					'disableTimeRanges': [
					[a, b],
					[c, d]
					],
					//'minTime' : b,
					//'maxTime' : e,
					'timeFormat' : resres_time_vars.time_format
					});
				}

		}
		else {
					if(parseInt( dends2_xxx[0] ) < parseInt( dstarts_xxx[0] ) ) {
						var a = dends2_xxx[0] + ":00";
						var b = dstarts_xxx[0] + ":00";
						var c = dends_xxx[0] + ":00";
						var d = dstarts2_xxx[0] + ":00";

						$('.resres_time').timepicker({
						'step' : 15,
						'disableTimeRanges': [
						[a, b],
						[c, d]
						],
						//'minTime' : b,
						//'maxTime' : e,
						'timeFormat' : resres_time_vars.time_format
						});

					}
					else {
						var a = "00:00";
						var b = dstarts_xxx[0] + ":00";
						var c = dends_xxx[0] + ":00";
						var d = dstarts2_xxx[0] + ":00";
						var e = dends2_xxx[0] + ":00";
						var f = "23:59";

						$('.resres_time').timepicker({
						'step' : 15,
						'disableTimeRanges': [
						[a, b],
						[c, d],
						[e, f],
						],
						//'minTime' : b,
						//'maxTime' : e,
						'timeFormat' : resres_time_vars.time_format
						});
					}
		}

	} //end onselect function
});

*/



/*
*
* reservations
*
*****************************/

		$('#resres_show_res_screen').show();



		$('#resres_show_res_screen').click(function (e) {

			$( ".datepicker" ).datepicker( "destroy" );
			$( ".datepicker" ).datepicker( wibble() );

			$('#resres_modal').modal({
				maxWidth: 450,
				minHeight: 500,
				close: true,
				overlayClose:true
			});


			$('#resres_name').prop('required', 1);

			$("#resres_modal_form").validate();
			$("#resres_modal_form").removeAttr("novalidate");
			$("#resres_modal_form").validate();



			return false;
		});

		$('#resres_modal_reserve').click(function(e) {

			$('.resres_wp_spinner').show();

			var resres_date                      = $('#resres_date').val();
			var resres_time                      = $('#resres_time').val();
			var resres_partysize                 = $('#resres_partysize').val();
			var resres_name                      = $('#resres_name').val();
			var resres_phone                     = $('#resres_phone').val();
			var resres_email                     = $('#resres_email').val();
			var resres_notes                     = $('#resres_notes').val();
			var resres_admin_send_admin_email    = $('#resres_admin_send_admin_email:checked').length > 0;
			var resres_admin_send_customer_email = $('#resres_admin_send_customer_email:checked').length > 0;

			var data = {
						action: 'resres_add_reservation_from_admin',
						type: 'POST',
						dataType: 'text',
						"resres_date" 						: resres_date,
						"resres_time" 						: resres_time,
						"resres_partysize" 					: resres_partysize,
						"resres_name" 						: resres_name,
						"resres_phone" 						: resres_phone,
						"resres_email" 						: resres_email,
						"resres_notes" 						: resres_notes,
						"resres_admin_send_admin_email" 	: resres_admin_send_admin_email,
						"resres_admin_send_customer_email" 	: resres_admin_send_customer_email
					};

					jQuery.post(ajaxurl, data, function(response) {
						//console.log(response);
						var response = $.parseJSON(response);
						if(response.success == true) {
							$('.resres_wp_spinner').hide();

							$('#resres_modal_form h3').append(response.sucmsg);
							setTimeout(function() {
							$.modal.close();
							}, 2000);

						}
					});

		});

		if($('#resres_hide_cancelled').attr('checked')) {
			$('.res_bad').hide();
		}


		$('.resres_days_open').change(function() {
			var attrcheck = $(this).attr('checked');
			var whichday = $(this).attr('name');
			if(typeof attrcheck !== 'undefined' && attrcheck !== false) {
				$('.open_'+whichday).show();
			} else {
				$('.open_'+whichday).hide();
			}

		});


		$('.is_checked_in').change(function() {
			var attrcheck = $(this).attr('checked');
			if(typeof attrcheck !== 'undefined' && attrcheck !== false) {
				$(this).parent().parent().addClass('res_good')
					if($('#resres_hide_arrived').attr('checked')) {
						$(this).parent().parent().fadeOut('slow');
					}
				
				var nonce = jQuery("#resres_registrations").attr("nonce");
				var resid = jQuery(this).parent().parent().attr("resid");

				var data = {
							action: 'resres_update_checkin',
							type: 'POST',
							dataType: 'text',
							"nonce": nonce,
							"resid": resid,
							"is_checked_in" : 1,
						};
						jQuery.post(ajaxurl, data, function(response) {
							var response = $.parseJSON(response);
						});
			} else {
				$(this).parent().parent().removeClass('res_good');

				var nonce = jQuery("#resres_registrations").attr("nonce");
				var resid = jQuery(this).parent().parent().attr("resid");

				var data = {
							action: 'resres_update_checkin',
							type: 'POST',
							dataType: 'text',
							"nonce": nonce,
							"resid": resid,
							"is_checked_in" : 0,
						};
						jQuery.post(ajaxurl, data, function(response) {
							var response = $.parseJSON(response);
						});
			}

		});

		$('.is_active').change(function() {
			var attrcheck = $(this).attr('checked');
			if(typeof attrcheck !== 'undefined' && attrcheck !== false) {
				$(this).parent().parent().addClass('res_bad')
					if($('#resres_hide_cancelled').attr('checked')) {
						$(this).parent().parent().fadeOut('slow');
					}

				var nonce = jQuery("#resres_registrations").attr("nonce");
				var resid = jQuery(this).parent().parent().attr("resid");

				var data = {
							action: 'resres_update_cancellation',
							type: 'POST',
							dataType: 'text',
							"nonce": nonce,
							"resid": resid,
							"is_active" : 0,
						};
						jQuery.post(ajaxurl, data, function(response) {
							var response = $.parseJSON(response);
						});

			} else {
				$(this).parent().parent().removeClass('res_bad')

				var nonce = jQuery("#resres_registrations").attr("nonce");
				var resid = jQuery(this).parent().parent().attr("resid");

				var data = {
							action: 'resres_update_cancellation',
							type: 'POST',
							dataType: 'text',
							"nonce": nonce,
							"resid": resid,
							"is_active" : 1,
						};
						jQuery.post(ajaxurl, data, function(response) {
							var response = $.parseJSON(response);
						});
			}

		});

		$('#resres_hide_cancelled').change(function() {
			var attrcheck = $(this).attr('checked');
			if(typeof attrcheck !== 'undefined' && attrcheck !== false) {
				$('.res_bad').fadeOut('slow');
			} else {
				$('.res_bad').show();
			}

		});

		$('#resres_hide_arrived').change(function() {
			var attrcheck = $(this).attr('checked');
			if(typeof attrcheck !== 'undefined' && attrcheck !== false) {
				$('.res_good').fadeOut('slow');
			} else {
				$('.res_good').show();
			}

		});







/*
*
- Admin - general options
*
**********************************************/


	//http://trentrichardson.com/examples/timepicker/
	$('.resres_time').timepicker({
			'step' : 15,
	    	'timeFormat' : 'H:i' //resres_time_vars.time_format

	});

	var resres_colour = $('#resres_template_colour').val();
	var resres_font = $('#resres_template_font_colour').val();
	$('.resres_colour').css( 'background-color', resres_colour );
	$('.resres_colour').css( 'color', resres_font );

	$('#resres_template_colour').iris({
		change: function(event, ui) {
	        $(this).css( 'color', $('#resres_template_font_colour').val());
	        $(this).css( 'background-color', ui.color.toString());
	   }
	});
	$('#resres_template_font_colour').iris({
		change: function(event, ui) {
	        $(this).css( 'background-color', $('#resres_template_colour').val());
	                $(this).css( 'color', ui.color.toString());
	    }
	});

	//for reservation form options section.
	$('#disable_themeroller').change(function(event) {
		if($('#themeroller_select').attr('disabled')) {
			$('#themeroller_select').prop('disabled', false);
		}
		else {
			$('#themeroller_select').prop('disabled', true);
		}
	});




	$('#resres_menu_section_list').sortable({
		placeholder: "resres_sortable_placeholder"
	});

	$('.resres_menu_ordering').sortable({
		placeholder: "resres_sortable_placeholder"
	});


/**
*Licence tab
*/


 	if( $('#resres_license_key').length ) { //stops JS from breaking when not on licence tab
 		if( $('#resres_license_key').val().length !== 32 ) {
			$('#resres_license_activate').prop("disabled", true);
 		}
 	}
 	
	$('#resres_license_key').on("keyup", action);

	function action() {
	    if( $('#resres_license_key').val().length == 32 ) {
	        $('#resres_license_activate').prop("disabled", false);
	    } else {
	        $('#resres_license_activate').prop("disabled", true);
	    }   
	}

	$("#resres_license_key").bind({
        paste : function(){
        	if( $('#resres_license_key').val().length == 32 ) {
        		$('#resres_license_activate').prop("disabled", false);
        	}
        }
    });






$('#resres_date_help').on('click', function() {
        $('#dialog').modal();
        return false;
});









function wibble() {

var dateToday = new Date();

$('.datepicker').datepicker({
	minDate: dateToday,
	dateFormat: resres_time_vars.date_format,
	altFormat: "yy-mm-dd",
  	altField: "#resres_hidden_date",
	beforeShowDay: function(day) {
		//this function will disable days when restuarant isnt open.
        var day = day.getDay();

		var days = [];

		if(resres_time_vars.open_sunday != 1 ) { days.push( parseInt(0) ); }
		if(resres_time_vars.open_monday != 1 ) { days.push( 1 ); }
		if(resres_time_vars.open_tuesday != 1 ) { days.push( 2 ); }
		if(resres_time_vars.open_wednesday != 1 ) { days.push( 3 ); }
		if(resres_time_vars.open_thursday != 1 ) { days.push( 4); }
		if(resres_time_vars.open_friday != 1 ) { days.push( 5); }
		if(resres_time_vars.open_saturday != 1 ) { days.push( 6); }


        if( $.inArray( day, days ) == -1) {
        	return [true, ''];
        }
        else {
        return [false, ''];
        }

    },
	onSelect: function(){

		//stops it from jumping
		$(".ui-datepicker a").removeAttr("href");

		$('.resres_time').timepicker('remove').val('');

		$('.resres_time').removeProp('disabled', this).prop('required', this);

		var selectedDay = new Date($('#resres_hidden_date').val());

		var weekday = selectedDay.getDay();
		var dstarts = '8';
		var dends = '24';


		if(weekday == 0 ) {
			//dstarts = resres_time_vars.sunday_from_one; dends = resres_time_vars.sunday_to_one;
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
			//dstarts = resres_time_vars.monday_from_one; dends = resres_time_vars.monday_to_one;
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
			dstarts = resres_time_vars.tuesday_from_one; dends = resres_time_vars.tuesday_to_one;
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
			//dstarts = resres_time_vars.wednesday_from_one; dends = resres_time_vars.wednesday_to_one;
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
			//dstarts = resres_time_vars.thursday_from_one; dends = resres_time_vars.thursday_to_one;
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
			//dstarts = resres_time_vars.friday_from_one; dends = resres_time_vars.friday_to_one;
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
			//dstarts = resres_time_vars.saturday_from_one; dends = resres_time_vars.saturday_to_one;
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
					'timeFormat' : resres_time_vars.time_format
					});
				}
				else {

					var a = "00:00";
					var b = parseInt(dstarts_xxx[0]) + ":00";
					var c = dends_xxx[0] + ":00";
					var d = "23:59";

					$('.resres_time').timepicker({
					'step' : 15,
					'disableTimeRanges': [
					[a, b],
					[c, d]
					],
					//'minTime' : b,
					//'maxTime' : e,
					'timeFormat' : resres_time_vars.time_format
					});
				}

		}
		else {
					if(parseInt( dends2_xxx[0] ) < parseInt( dstarts_xxx[0] ) ) {
						var a = dends2_xxx[0] + ":00";
						var b = dstarts_xxx[0] + ":00";
						var c = dends_xxx[0] + ":00";
						var d = dstarts2_xxx[0] + ":00";

						$('.resres_time').timepicker({
						'step' : 15,
						'disableTimeRanges': [
						[a, b],
						[c, d]
						],
						//'minTime' : b,
						//'maxTime' : e,
						'timeFormat' : resres_time_vars.time_format
						});

					}
					else {
						var a = "00:00";
						var b = dstarts_xxx[0] + ":00";
						var c = dends_xxx[0] + ":00";
						var d = dstarts2_xxx[0] + ":00";
						var e = dends2_xxx[0] + ":00";
						var f = "23:59";

						$('.resres_time').timepicker({
						'step' : 15,
						'disableTimeRanges': [
						[a, b],
						[c, d],
						[e, f],
						],
						//'minTime' : b,
						//'maxTime' : e,
						'timeFormat' : resres_time_vars.time_format
						});
					}
		}

	} //end onselect function
});


}
/*******************************************/

});


}(jQuery));











