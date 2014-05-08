<?php
/**
 * Represents the view for the public-facing component of the plugin.
 *
 * This typically includes any information, if any, that is rendered to the
 * frontend of the theme when the plugin is activated.
 *
 * @package   ResRes
 * @author    Dean Robinson <team@deftdev.com>
 * @license   GPL-2.0+
 * @link      http://deftdev.com
 * @copyright 2014 deftDEV
 */
?>

<?php $res_form_options = $this->resres_get_form_options(); ?>

<?php
if( isset( $_POST['resres_date'] ) ) { $resres_date           = $_POST['resres_date']; } else { $resres_date = ''; }
if( isset( $_POST['resres_time'] ) ) { $resres_time           = $_POST['resres_time']; } else { $resres_time = ''; }
if( isset( $_POST['resres_partysize'] ) ) { $resres_partysize = $_POST['resres_partysize']; } else { $resres_partysize = ''; }
if( isset( $_POST['resres_name'] ) ) { $resres_name           = $_POST['resres_name']; } else { $resres_name = ''; }
if( isset( $_POST['resres_phone'] ) ) { $resres_phone         = $_POST['resres_phone']; } else { $resres_phone = ''; }
if( isset( $_POST['resres_email'] ) ) { $resres_email         = $_POST['resres_email']; } else { $resres_email = ''; }
if( isset( $_POST['resres_notes'] ) ) { $resres_notes         = $_POST['resres_notes']; } else { $resres_notes = ''; }
?>

<?php //var_dump($_POST); ?>

<!-- This file is used to markup the public facing aspect of the plugin. -->

<form method="POST" action="" id="resres_form">

<label class="resres_label"><?php echo __('Date *'); ?><br><input type="text" class="resres_formfield hasPicker" id="resres_date" name="resres_date" value="<?php echo $resres_date; ?>" required /> </label>
	<input type="hidden" id="resres_hidden_date" />

<label class="resres_label"><?php echo __('Time *'); ?><br><input type="text" class="resres_formfield resres_time" id="resres_time" name="resres_time" value="<?php echo $resres_time; ?>" required /></label>

<label class="resres_label"><?php echo __('Party Size - slide to set, or type a number in the box.'); ?><br>
	<div id="resres_partysize_wrapper">
		<div class="slider"></div>

		
		<input type="text" class="resres_formfield smalltext" id="resres_partysize" name="resres_partysize" value="<?php echo $resres_partysize; ?>" required />
	</div>
</label>

<div class="resres_clear"></div>

<label class="resres_label"><?php echo __('Name *'); ?><br><input type="text" class="resres_formfield" id="resres_name" name="resres_name" value="<?php echo $resres_name; ?>" required /></label>

<label class="resres_label"><?php echo __('Phone number *'); ?><br><input type="text" class="resres_formfield" id="resres_phone" name="resres_phone" value="<?php echo $resres_phone; ?>" required /></label>

<label class="resres_label"><?php echo __('Email *'); ?><br><input type="text" class="resres_formfield" id="resres_email" name="resres_email" value="<?php echo $resres_email; ?>" required /></label>

<label class="resres_label"><?php echo __('Notes - please let us know about any special needs, birthdays, etc.'); ?><br><textarea class="resres_formfield" id="resres_notes" name="resres_notes" /><?php echo $resres_notes; ?></textarea></label>

<?php do_action( 'resres_form_before_recaptcha' ); ?>

<?php if( isset($res_form_options['disable_resres_captcha']) && $res_form_options['disable_resres_captcha'] == 1) {} else { ?>
	<label class="resres_label"><?php echo __('Please confirm you are human, what is '); ?><span id="cap1"><?php echo $cap['cap1'] . '</span> + <span id="cap2">' . $cap['cap2']; ?></span><br>
	<input type="text" class="resres_formfield" id="resres_captcha" name="resres_captcha" required />
	</label>
	<input type="hidden" value="<?php echo $cap['capt']; ?>" name="wibble" />
<?php } ?>


<?php
	if( isset($res_form_options['enable_recaptcha']) && isset( $res_form_options['enable_recaptcha'] ) == 1) {
		require_once( WP_PLUGIN_DIR . '/resres/public/includes/recaptchalib.php' );
		$publickey = $res_form_options['recaptcha_public']; // you got this from the signup page
		echo recaptcha_get_html($publickey);		
	} else {
	}
?>

<!-- Alternate CAPTCHA would go here -->

<?php do_action( 'resres_form_before_submit' ); ?>

<br>
<div class="res_clearmargin">
<input type="submit" id="resres_submit" name="resres_submit" value="<?php echo __('Reserve Table'); ?>" />
</div>
<input type="hidden" id="maxpartysetting" value="<?php echo $res_form_options['party_size']; ?>" />


</form>