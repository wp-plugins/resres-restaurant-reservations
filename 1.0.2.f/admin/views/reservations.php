<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   ResRes
 * @author    Dean Robinson <team@deftdev.com>
 * @license   GPL-2.0+
 * @link      http://deftdev.com
 * @copyright 2014 deftDEV
 */

$reg_response = $this->resres_get_registration_list();

//echo "<pre>" . print_r($reg_response, 1) . "</pre>";
		$ajax_nonce = wp_create_nonce( "resres_admin_reservation" );

?>



<div class="wrap">

	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
	<noscript><div class="error"><p class="resresnojs">WARNING: JavaScript is DISABLED. ResRes requires JavaScript to be enabled in the admin to function correctly.</p></div></noscript>

<form id="resres_registrations" method="POST" action="" nonce="<?php echo $ajax_nonce; ?>">

<div id="resres_reservations_top_left">
	<input type="submit" id="resres_reg_today" name="resres_reg_today" value="<?php echo __('Today'); ?>" class="button-primary" disabled />
	<input type="submit" id="resres_reg_tomorrow" name="resres_reg_tomorrow" value="<?php echo __('Tomorrow'); ?>" class="" disabled />
	<input type="submit" id="resres_reg_week" name="resres_reg_week" value="<?php echo __('This week'); ?>" class="" disabled />
	<input type="submit" id="resres_reg_month" name="resres_reg_month" value="<?php echo __('This month'); ?>" class="" disabled />
	<input type="text" id="resres_reg_selectdate_start" name="resres_reg_selectdate_start" value="" class="datepicker_filter" placeholder="<?php echo __('Select first date'); ?>" disabled />
	<input type="text" id="resres_reg_selectdate_end" name="resres_reg_selectdate_end" value="" class="datepicker_filter" placeholder="<?php echo __('Select second date'); ?>" disabled />

	<input type="hidden" id="resres_hidden_date" />

	<input type="submit" id="resres_reg_loaddate" name="resres_reg_loaddate" value="<?php echo __('Load'); ?>" class="" disabled />

	<img src="<?php echo plugin_dir_url(__FILE__); ?>ajax-loader.gif" id="resres_res_loader">
</div>

<div id="resres_reservations_top_right">
	<input type="button" id="resres_reg_print" name="resres_reg_print" value="<?php echo __('Print'); ?>" class="resres_res_right" onClick="window.print()" disabled />
	<label class="resres_res_right resres_res_checkboxes"><input type="checkbox" id="resres_hide_arrived" name="resres_hide_arrived" value="" /> <?php echo __('Hide arrived customers'); ?></label>
	<label class="resres_res_right resres_res_checkboxes"><input type="checkbox" id="resres_hide_cancelled" name="resres_hide_cancelled" value="" checked="checked" /> <?php echo __('Hide cancelled customers'); ?></label>

	<input type="button" id="resres_show_res_screen" name="resres_show_res_screen" class="button-primary" value="<?php echo __('Add Reservation'); ?>" class="resres_res_right" disabled />
</div>




</form>


	<div id="resres_modal">
		<form id="resres_modal_form">
		<h3>Add Reservation</h3>
		

		<table>

<tr>
	<td>
		<p><label class="resres_label"><?php echo __('Date'); ?><br><input type="text" class="resres_formfield datepicker" id="resres_date" name="resres_date" value="" /></label></p>
	</td>
	<td>
		<p><label class="resres_label"><?php echo __('Time'); ?><br><input type="text" class="resres_formfield resres_time" id="resres_time" name="resres_time" /></label></p>
	</td>
</tr>

<tr>
	<td>
		<p><label class="resres_label"><?php echo __('Name'); ?><br><input type="text" class="resres_formfield" id="resres_name" name="resres_name" /></label></p>
	</td>
	<td>
		<label class="resres_label"><?php echo __('Party Size'); ?><br>
			<div id="resres_partysize_wrapper">
				<div class="slider"></div>
				<input type="text" class="resres_formfield smalltext" id="resres_partysize" name="resres_partysize" />
			</div>
		</label>
	</td>
</tr>

<tr>
	<td>
		<p><label class="resres_label"><?php echo __('Phone number'); ?><br><input type="text" class="resres_formfield" id="resres_phone" name="resres_phone" /></label></p>
	</td>
	<td>
		<p><label class="resres_label"><?php echo __('Email'); ?><br><input type="text" class="resres_formfield" id="resres_email" name="resres_email" /></label></p>
	</td>
</tr>

<tr>
	<td colspan=2>
		<p><label class="resres_label"><?php echo __('Notes'); ?><br><textarea class="resres_formfield" id="resres_notes" name="resres_notes" /></textarea></label></p>

	</td>
</tr>

<tr>
	<td>
		<input type="button" value="Cancel" name="resres_modal_cancel" id="resres_modal_cancel" class="simplemodal-close" />
	</td>
	<td>
		<input type="button" value="RESERVE" name="resres_modal_reserve" id="resres_modal_reserve" class="button-primary" />
	</td>
	<td>
		<input type="checkbox" id="resres_admin_send_admin_email" name="resres_admin_send_admin_email"class="" value="1" checked /><label for="resres_admin_send_admin_email"><?php echo __('Admin email'); ?></label>
			<br>
		<input type="checkbox" id="resres_admin_send_customer_email" name="resres_admin_send_customer_email" class="" value="1" checked /><label for="resres_admin_send_customer_email"><?php echo __('Customer email'); ?></label>
	</td>
</tr>

</table>

	</form>
	</div>




<p class="resres_cur_"><?php echo __('Currently displaying:'); ?> <?php echo $reg_response['date']; ?></p>
	<table id="resres_res_table" class="wp-list-table widefat" cellspacing="0">

<colgroup>
<col span="1" class="res_15">
<col span="1" class="res_15">
<col span="1" class="res_15">
<col span="1" class="res_25">
<col span="1" class="res_10">
<col span="1" class="res_10">
<col span="1" class="res_5" >
<col span="1" class="res_5" >
</colgroup>

		<thead>
			<tr>
				<th class="sortable manage-column"><?php echo __('Name'); ?></th>
				<th class="sortable manage-column"><?php echo __('Phone'); ?></th>
				<th class="sortable manage-column"><?php echo __('Email'); ?></th>
				<th class="sortable manage-column"><?php echo __('Notes'); ?></th>
				<th class="sortable manage-column"><?php echo __('Party Size'); ?></th>
				<?php if( isset($_POST['resres_reg_week']) || isset($_POST['resres_reg_month']) || isset($_POST['resres_reg_loaddate']) ) { ?>
				<th class="sortable manage-column"><?php echo __('Date'); ?></th>
				<?php } ?>
				<th class="sortable manage-column"><?php echo __('Time'); ?></th>
				<th class="sortable manage-column"><?php echo __('Arrived'); ?></th>
				<th class="sortable manage-column"><?php echo __('Cancelled'); ?></th>
				<!--<th class="sortable manage-column"><?php //echo __('Table'); ?></th>-->
				<!--<th class="sortable manage-column"><?php //echo __('Section'); ?></th>-->
				<!--<th class="sortable manage-column"><?php //echo __('Menu'); ?></th>-->
				<!--<th class="sortable manage-column"><?php //echo __('Wine'); ?></th>-->
				<!--<th class="sortable manage-column"><?php //echo __('Deposit Amount'); ?></th>-->
				<!--<th class="sortable manage-column"><?php //echo __('Pre-paid?'); ?></th>-->
				<!--<th class="sortable manage-column"><?php //echo __('Reservation ID'); ?></th>-->
			</tr>
		</thead>

		<tbody>
				<?php echo $reg_response['return']; ?>
		</tbody>

	</table>
</div>


<div style="padding-top:80px; width:600px; margin: 0 auto;">

<h2 style="text-align:center; font-size: 30px; line-height:40px;">Upgrade to Pro and get access to dashboard reservations!</h2>

<ul class="resresfreelist" style="width:50%; margin: 0 auto;">
<li><span class="resresfreelisticon"></span>Check your reservations each day.</li>
<li><span class="resresfreelisticon"></span>See which customers have cancelled.</li>
<li><span class="resresfreelisticon"></span>Mark customers as arrived or cancelled with a tick.</li>
<li><span class="resresfreelisticon"></span>Add a phone or email reservation into the system.</li>
<li><span class="resresfreelisticon"></span>Print off a copy of the reservation list.</li>
</ul>
<br />

<h2 style="text-align:center" ><a class="resresupgrade" href="http://www.deftdev.com/resres" target="_blank">Upgrade Now!</a></h2>
</div>
