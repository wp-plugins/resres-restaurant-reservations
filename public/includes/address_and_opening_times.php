<?php

$mon_open   = '';
$mon_times  = '';
$mon_times2 = '';

$tue_open   = '';
$tue_times  = '';
$tue_times2 = '';

$wed_open   = '';
$wed_times  = '';
$wed_times2 = '';

$thu_open   = '';
$thu_times  = '';
$thu_times2 = '';

$fri_open   = '';
$fri_times  = '';
$fri_times2 = '';

$sat_open   = '';
$sat_times  = '';
$sat_times2 = '';

$sun_open   = '';
$sun_times  = '';
$sun_times2 = '';

		$mon_open = $options['resres_day_of_week_mon'];
		if($mon_open == 0) { $mon_times = __('Closed'); } else { $mon_times = date( $time_format, strtotime($options['resres_day_of_week_mon_from_one']) ) . " - " . date( $time_format, strtotime( $options['resres_day_of_week_mon_to_one'] ) ); }
		if( $options['resres_day_of_week_mon_from_two'] != '' &&  $mon_open == 1) { $mon_times .= " " . date( $time_format, strtotime( $options['resres_day_of_week_mon_from_two'] ) ) . " - " . date( $time_format, strtotime( $options['resres_day_of_week_mon_to_two'] ) ); } 



		$mon_open = $options['resres_day_of_week_mon'];

		if($mon_open == 0) { $mon_times = __('Closed'); } else { $mon_times = date( $time_format, strtotime($options['resres_day_of_week_mon_from_one']) ) . " - " . date( $time_format, strtotime( $options['resres_day_of_week_mon_to_one'] ) ); }

		if( $options['resres_day_of_week_mon_from_two'] != '' &&  $mon_open == 1) { $mon_times2 .= " " . date( $time_format, strtotime( $options['resres_day_of_week_mon_from_two'] ) ) . " - " . date( $time_format, strtotime( $options['resres_day_of_week_mon_to_two'] ) ); } 





		$tue_open = $options['resres_day_of_week_tue'];
		if($tue_open == 0) { $tue_times = __('Closed'); } else { $tue_times = date( $time_format, strtotime( $options['resres_day_of_week_tue_from_one'] ) ) . " - " . date( $time_format, strtotime( $options['resres_day_of_week_tue_to_one'] ) ); }
		if( $options['resres_day_of_week_tue_from_two'] != '' &&  $tue_open == 1 ) { $tue_times2 .= " " . date( $time_format, strtotime( $options['resres_day_of_week_tue_from_two'] ) ) . " - " . date( $time_format, strtotime( $options['resres_day_of_week_tue_to_two'] ) ); }

		$wed_open = $options['resres_day_of_week_wed'];
		if($wed_open == 0) { $wed_times = __('Closed'); } else { $wed_times = date( $time_format, strtotime( $options['resres_day_of_week_wed_from_one'] ) ) . " - " . date( $time_format, strtotime( $options['resres_day_of_week_wed_to_one'] ) ); }
		if( $options['resres_day_of_week_wed_from_two'] != '' &&  $wed_open == 1 ) { $wed_times2 .= " " . date( $time_format, strtotime( $options['resres_day_of_week_wed_from_two'] ) ) . " - " . date( $time_format, strtotime( $options['resres_day_of_week_wed_to_two'] ) ); }

		$thu_open = $options['resres_day_of_week_thu'];
		if($thu_open == 0) { $thu_times = __('Closed'); } else { $thu_times = date( $time_format, strtotime(  $options['resres_day_of_week_thu_from_one'] ) ) . " - " . date( $time_format, strtotime( $options['resres_day_of_week_thu_to_one'] ) ); }
		if( $options['resres_day_of_week_thu_from_two'] != '' &&  $thu_open == 1 ) { $thu_times2 .= " " . date( $time_format, strtotime( $options['resres_day_of_week_thu_from_two'] ) ) . " - " . date( $time_format, strtotime( $options['resres_day_of_week_thu_to_two'] ) ); }

		$fri_open = $options['resres_day_of_week_fri'];
		if($fri_open == 0) { $fri_times = __('Closed'); } else { $fri_times = date( $time_format, strtotime( $options['resres_day_of_week_fri_from_one'] ) ) . " - " . date( $time_format, strtotime( $options['resres_day_of_week_fri_to_one'] ) ); }
		if( $options['resres_day_of_week_fri_from_two'] != '' &&  $fri_open == 1 ) { $fri_times2 .= " " . date( $time_format, strtotime( $options['resres_day_of_week_fri_from_two'] ) ) . " - " . date( $time_format, strtotime( $options['resres_day_of_week_fri_to_two'] ) ); }

		$sat_open = $options['resres_day_of_week_sat'];
		if($sat_open == 0) { $sat_times = __('Closed'); } else { $sat_times = date( $time_format, strtotime( $options['resres_day_of_week_sat_from_one'] ) ) . " - " . date( $time_format, strtotime( $options['resres_day_of_week_sat_to_one'] ) ); }
		if( $options['resres_day_of_week_sat_from_two'] != '' &&  $sat_open == 1 ) { $sat_times2 .= " " . date( $time_format, strtotime( $options['resres_day_of_week_sat_from_two'] ) ) . " - " . date( $time_format, strtotime( $options['resres_day_of_week_sat_to_two'] ) ); }

		$sun_open = $options['resres_day_of_week_sun'];
		if($sun_open == 0) { $sun_times = __('Closed'); } else { $sun_times = date( $time_format, strtotime( $options['resres_day_of_week_sun_from_one'] ) ) . " - " . date( $time_format, strtotime( $options['resres_day_of_week_sun_to_one'] ) ); }
		if( $options['resres_day_of_week_sun_from_two'] != '' &&  $sun_open == 1 ) { $sun_times2 .= " " . date( $time_format, strtotime( $options['resres_day_of_week_sun_from_two'] ) ) . " - " . date( $time_format, strtotime( $options['resres_day_of_week_sun_to_two'] ) ); }



/*meta*/
$meta_mon = $options['resres_day_of_week_mon_from_one'] . "-" . $options['resres_day_of_week_mon_to_one'];
if( $options['resres_day_of_week_mon_from_two'] != '' &&  $mon_open == 1) { $meta_mon .= " " . $options['resres_day_of_week_mon_from_two'] . "-" . $options['resres_day_of_week_mon_to_two']; } 
if($mon_open == 0) { $meta_mon = __('Closed'); }

$meta_tue = $options['resres_day_of_week_tue_from_one'] . "-" . $options['resres_day_of_week_tue_to_one'];
if( $options['resres_day_of_week_tue_from_two'] != '' &&  $tue_open == 1) { $meta_tue .= " " . $options['resres_day_of_week_tue_from_two'] . "-" . $options['resres_day_of_week_tue_to_two']; } 
if($tue_open == 0) { $meta_tue = __('Closed'); }

$meta_wed = $options['resres_day_of_week_wed_from_one'] . "-" . $options['resres_day_of_week_wed_to_one'];
if( $options['resres_day_of_week_wed_from_two'] != '' &&  $wed_open == 1) { $meta_wed .= " " . $options['resres_day_of_week_wed_from_two'] . "-" . $options['resres_day_of_week_wed_to_two']; } 
if($wed_open == 0) { $meta_wed = __('Closed'); }

$meta_thu = $options['resres_day_of_week_thu_from_one'] . "-" . $options['resres_day_of_week_thu_to_one'];
if( $options['resres_day_of_week_thu_from_two'] != '' &&  $thu_open == 1) { $meta_thu .= " " . $options['resres_day_of_week_thu_from_two'] . "-" . $options['resres_day_of_week_thu_to_two']; } 
if($thu_open == 0) { $meta_thu = __('Closed'); }

$meta_fri = $options['resres_day_of_week_fri_from_one'] . "-" . $options['resres_day_of_week_fri_to_one'];
if( $options['resres_day_of_week_fri_from_two'] != '' &&  $fri_open == 1) { $meta_fri .= " " . $options['resres_day_of_week_fri_from_two'] . "-" . $options['resres_day_of_week_fri_to_two']; } 
if($fri_open == 0) { $meta_fri = __('Closed'); }

$meta_sat = $options['resres_day_of_week_sat_from_one'] . "-" . $options['resres_day_of_week_sat_to_one'];
if( $options['resres_day_of_week_sat_from_two'] != '' &&  $sat_open == 1) { $meta_sat .= " " . $options['resres_day_of_week_sat_from_two'] . "-" . $options['resres_day_of_week_sat_to_two']; } 
if($sat_open == 0) { $meta_sat = __('Closed'); }

$meta_sun = $options['resres_day_of_week_sun_from_one'] . "-" . $options['resres_day_of_week_sun_to_one'];
if( $options['resres_day_of_week_sun_from_two'] != '' &&  $sun_open == 1) { $meta_sun .= " " . $options['resres_day_of_week_sun_from_two'] . "-" . $options['resres_day_of_week_sun_to_two']; } 
if($sun_open == 0) { $meta_sun = __('Closed'); }



		ob_start(); 

?>
<div id="resres_address_wrapper">

<div class="resres_address_time_block" itemscope itemtype="http://schema.org/Restaurant">
<?php 

		if( $atts['address'] == 'false') { } else { 

			if( $atts['show_titles'] == 'false' ) {} else {
				echo "<h2>" . __('Contact Us') . "</h2>";
			}
		?>


<table class="resres_address">
 <colgroup>
       <col span="1" style="width: 5%;">
       <col span="1" style="width: 95%;">
    </colgroup>

<?php if( $options['address_name'] != '' ) { ?>
<tr>
	<td><span aria-hidden="true" data-icon="&#xe608;" class="resres_address_icons resres_phone_icon" title="<?php echo __('Address'); ?>"></span></td>
	<td><span itemprop="name"><?php echo $options['address_name']; ?></span></td>
</tr>
<?php } ?>
<div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
<?php if( $options['address_line1'] != '' ) { ?>
<tr>
	<td></td>
	<td><span data-icon=" "></span><span itemprop="streetAddress"><?php echo $options['address_line1']; ?></span></td>
</tr>
<?php } ?>
<?php if( $options['address_line2'] != '' ) { ?>
<tr>
	<td></td>
	<td><span data-icon=" "></span><span itemprop="streetAddress"><?php echo $options['address_line2']; ?></span></td>
</tr>
<?php } ?>
<?php if( $options['address_city'] != '' ) { ?>
<tr>
	<td></td>
	<td><span data-icon=" "></span><span itemprop="addressLocality"><?php echo $options['address_city']; ?></span></td>
</tr>
<?php } ?>
<?php if( $options['address_region'] != '' ) { ?>
<tr>
	<td></td>
	<td><span data-icon=" "></span><span itemprop="addressRegion"><?php echo $options['address_region']; ?></span> <span itemprop="postalCode"><?php echo $options['address_postalcode']; ?></span></td>
</tr>
</div>
<?php } ?>
<?php if( $options['address_phone'] != '' ) { ?>
<tr>
	<td><span aria-hidden="true" data-icon="&#xe603;" class="resres_address_icons resres_phone_icon" title="<?php echo __('Phone'); ?>"></span></td>
	<td><span itemprop="telephone"><?php echo $options['address_phone']; ?></span></td>
</tr>
<?php } ?>
<?php if( $options['address_fax'] != '' ) { ?>
<tr>
	<td><span aria-hidden="true" data-icon="&#xe624;" class="resres_address_icons resres_fax_icon" title="<?php echo __('Fax'); ?>"></span></td>
	<td><span itemprop="faxNumber"><?php echo $options['address_fax']; ?></span></td>
</tr>
<?php } ?>
<?php if( $options['address_email'] != '' ) { ?>
<tr>
	<td><span aria-hidden="true" data-icon="&#xe628;" class="resres_address_icons resres_email_icon" title="<?php echo __('Email'); ?>"></span></td>
	<td><span itemprop="email"><?php echo $options['address_email']; ?></span></td>
</tr>
<?php } ?>

</table>
</div>


<div class="resres_map_canvas_container">
	<div id="dummy"></div>
	<div id="resres_map_canvas"></div>
</div>
		<?php 
		}

		if( $atts['times'] == 'false') { } else { 

			if( $atts['show_titles'] == 'false' ) {} else {
				echo "<h2>" . __('Opening Times') . "</h2>";
			}

?>
<div class="resres_time_wrapper">
	<table class="resres_opening_times">

	 <colgroup>
       <col span="1" style="width: 20%;">
       <col span="1" style="width: 40%;">
       <col span="1" style="width: 40%;">
    </colgroup>

		<tr>
			<td><meta itemprop="openingHours" content="Mo <?php echo $meta_mon; ?>"><?php echo __('Monday: '); ?></td>
			<td><?php echo $mon_times; ?></td>
			<td><?php echo $mon_times2; ?></td>
		</tr>
		<tr>
			<td><meta itemprop="openingHours" content="Mo <?php echo $meta_tue; ?>"><?php echo __('Tuesday: '); ?></td>
			<td><?php echo $tue_times; ?></td>
			<td><?php echo $tue_times2; ?></td>
		</tr>
		<tr>
			<td><meta itemprop="openingHours" content="Mo <?php echo $meta_wed; ?>"><?php echo __('Wednesday: '); ?></td>
			<td><?php echo $wed_times; ?></td>
			<td><?php echo $wed_times2; ?></td>
		</tr>
		<tr>
			<td><meta itemprop="openingHours" content="Mo <?php echo $meta_thu; ?>"><?php echo __('Thursday: '); ?></td>
			<td><?php echo $thu_times; ?></td>
			<td><?php echo $thu_times2; ?></td>
		</tr>
		<tr>
			<td><meta itemprop="openingHours" content="Mo <?php echo $meta_fri; ?>"><?php echo __('Friday: '); ?></td>
			<td><?php echo $fri_times; ?></td>
			<td><?php echo $fri_times2; ?></td>
		</tr>
		<tr>
			<td><meta itemprop="openingHours" content="Mo <?php echo $meta_sat; ?>"><?php echo __('Saturday: '); ?></td>
			<td><?php echo $sat_times; ?></td>
			<td><?php echo $sat_times2; ?></td>
		</tr>
		<tr>
			<td><meta itemprop="openingHours" content="Mo <?php echo $meta_sun; ?>"><?php echo __('Sunday: '); ?></td>
			<td><?php echo $sun_times; ?></td>
			<td><?php echo $sun_times2; ?></td>
		</tr>
	</table>
</div>

		<?php } ?>





</div>
