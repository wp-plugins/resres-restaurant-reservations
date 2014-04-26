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

/*
*
* SETTINGS PAGE
*
*/

?>


<div class="wrap">

	<img id="resres_settings_logo" src="<?php echo plugins_url( 'assets/resres_small.png' , dirname(__FILE__) ); ?>" />

		<div id="resres_top_links">
		<a href="http://www.deftdev.com/document/resres-documentation/" target="_blank"><?php echo __('Documentation'); ?></a>
		<a href="http://www.deftdev.com/support/" target="_blank"><?php echo __('Support'); ?></a>
		<a class="resres_upgrade" href="http://www.deftdev.com/downloads/resres/" target="_blank"><?php echo __('Upgrade to Pro'); ?></a>
		</div>

	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>




	<?php settings_errors(); ?>
	<noscript><div class="error"><p class="resresnojs"><?php echo __('WARNING: JavaScript is DISABLED. ResRes requires JavaScript to be enabled in the admin to function correctly.'); ?></p></div></noscript>



	<?php
	$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'general_options';
	$tabactive1 =''; $tabactive2 =''; $tabactive3 = '';

	if( $active_tab == 'general_options' ) {
	$tabactive1= "activetab";
	}
	if( $active_tab == 'form_options' ) {
	$tabactive2= "activetab";
	}
	if( $active_tab == 'email_options' ) {
	$tabactive3= "activetab";
	}	
	if( $active_tab == 'menu_ordering' ) {
	$tabactive4= "activetab";
	}


	?>

	<h2 class="nav-tab-wrapper">
	    <a href="?page=resres-settings&tab=general_options" class="nav-tab <?php echo $tabactive1; ?>"><?php echo __('General Options'); ?></a>
	    <a href="?page=resres-settings&tab=form_options" class="nav-tab <?php echo $tabactive2; ?>"><?php echo __('Reservation Form Options'); ?></a>
	    <a href="?page=resres-settings&tab=email_options" class="nav-tab <?php echo $tabactive3; ?>"><?php echo __('Email Options'); ?></a>
	    <a href="?page=resres-settings&tab=menu_ordering" class="nav-tab <?php echo $tabactive4; ?>"><?php echo __('Menu Ordering'); ?></a>
	</h2>






<?php 
	if( $active_tab == 'menu_ordering' ) {
		echo '<form method="post" action="">';
	}
	else {
		echo '<form method="post" action="options.php">';
	}
?>

		<?php
if( $active_tab == 'email_options' ) {
	?>

	<?php
}
?>
		<?php

	        if( $active_tab == 'general_options' ) {
	        	settings_fields( 'resres_options' );
		    	do_settings_sections( 'resres_options' );
	        }
	        elseif( $active_tab == 'form_options' ) {
	            settings_fields( 'resres_form_options' );
	            do_settings_sections( 'resres_form_options' );
	        }
	        elseif( $active_tab == 'email_options' ) {
	            settings_fields( 'resres_email_options' );
	            do_settings_sections( 'resres_email_options' );
	        }
	        elseif( $active_tab == 'menu_ordering' ) { 
	            settings_fields( 'resres_menu_ordering' );
	            do_settings_sections( 'resres_menu_ordering' );
	        }
	        else {
	        	do_action('resres_insert_settings_tab');
	        }

		//submits and buttons
	        if( $active_tab == 'licence' ) {

	        }
	        elseif( $active_tab == 'menu_ordering' ) {
	        	echo '<input type="submit" value="Save Changes" class="button-primary" id="resres_update_menu_order" name="resres_update_menu_order" />';
	        }
	        else {
	        	submit_button();
	        }

	    ?>
	</form>
