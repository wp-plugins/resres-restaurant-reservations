<?php

add_action( 'load-post.php', 'resres_post_meta_boxes_setup' );
add_action( 'load-post-new.php', 'resres_post_meta_boxes_setup' );

/* Meta box setup function. */
function resres_post_meta_boxes_setup() {

	/* Add meta boxes on the 'add_meta_boxes' hook. */
	add_action( 'add_meta_boxes', 'resres_add_info_box' );
	add_action( 'add_meta_boxes', 'resres_add_post_meta_boxes' );

	/* Save post meta on the 'save_post' hook. */
	add_action( 'save_post', 'resres_save_dish_settings_meta', 10, 2 );
}

/* Create one or more meta boxes to be displayed on the post editor screen. */
function resres_add_post_meta_boxes() {

	add_meta_box(
		'resres-dish-settings',						// Unique ID
		esc_html__( 'Dish Settings', 'resres' ),	// Title
		'resres_dish_settings_meta_box',				// Callback function
		'dish',										// Admin page (or post type)
		'normal',									// Context
		'default'										// Priority
	);
}

function resres_add_info_box() {

	add_meta_box(
		'resres-dish-menu-section-info',						// Unique ID
		esc_html__( 'Did you know...', 'resres' ),	// Title
		'resres_dish_info_meta_box',				// Callback function
		'dish',										// Admin page (or post type)
		'side',									// Context
		'default'										// Priority
	);
}



function resres_dish_info_meta_box() {

echo __('That you can re-order how the sections are displayed on the menu from the <a target="_blank" href="' . admin_url('admin.php?page=resres-settings&tab=menu_ordering') . '">settings page?</a>');

}


/* Display the post meta box. */
function resres_dish_settings_meta_box( $object, $box ) { ?>

	<?php wp_nonce_field( basename( __FILE__ ), 'resres_dish_settings_nonce' ); ?>

	<p>
		<label for="resres-dish-price">
		<?php _e( "Price", 'resres' );?> <?php $options = get_option( 'resres_options' ); echo " " . $options['currency_symbol']; ?>
		<input class="small-text" type="text" name="resres-dish-price" id="resres-dish-price" value="<?php echo esc_attr( get_post_meta( $object->ID, 'resres_dish_price', true ) ); ?>" size="30" />
		</label>
	</p>


<?php }


/* Save the meta box's post metadata. */
function resres_save_dish_settings_meta( $post_id, $post ) {

	/* Verify the nonce before proceeding. */
	if ( !isset( $_POST['resres_dish_settings_nonce'] ) || !wp_verify_nonce( $_POST['resres_dish_settings_nonce'], basename( __FILE__ ) ) )
		return $post_id;
	/* Get the post type object. */
	$post_type = get_post_type_object( $post->post_type );
	/* Check if the current user has permission to edit the post. */
	if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
		return $post_id;


	/* Get the posted data and sanitize it for use as an HTML class. */
	//$new_meta_value = ( isset( $_POST['resres-dish-price'] ) ? sanitize_html_class( $_POST['resres-dish-price'] ) : '' );
	$new_meta_value = ( isset( $_POST['resres-dish-price'] ) ? $_POST['resres-dish-price'] : '' );
	/* Get the meta key. */
	$meta_key = 'resres_dish_price';
	/* Get the meta value of the custom field key. */
	$meta_value = get_post_meta( $post_id, $meta_key, true );
	/* If a new meta value was added and there was no previous value, add it. */
	if ( $new_meta_value && '' == $meta_value )
		add_post_meta( $post_id, $meta_key, $new_meta_value, true );
	/* If the new meta value does not match the old value, update it. */
	elseif ( $new_meta_value && $new_meta_value != $meta_value )
		update_post_meta( $post_id, $meta_key, $new_meta_value );
	/* If there is no new meta value but an old value exists, delete it. */
	elseif ( '' == $new_meta_value && $meta_value )
		delete_post_meta( $post_id, $meta_key, $meta_value );



}



