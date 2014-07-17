<?php
if( isset($_GET['resid']) ) { $resid = $_GET['resid']; } else { $resid = ''; }
?>

<form id="resres_cancel_form" action="" method="POST">

<label for="resres_cancel"><?php echo __('Enter your reservation ID to cancel.'); ?></label>
<input type="text" id="resres_cancel" name="resres_cancel" value="<?php echo $resid; ?>" />
<?php wp_nonce_field( 'resres_cancel' ); ?>
<input type="submit" id="resres_cancel_submit" name="resres_cancel_submit" value="<?php echo __('Cancel Reservation'); ?>"  />


</form>