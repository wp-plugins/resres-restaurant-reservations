<?php

function register_cpt_dish() {

    $labels = array(
        'name' => _x( 'Dishes', 'dish' ),
        'singular_name' => _x( 'Dish', 'dish' ),
        'add_new' => _x( 'Add New', 'dish' ),
        'add_new_item' => _x( 'Add New Dish', 'dish' ),
        'edit_item' => _x( 'Edit Dish', 'dish' ),
        'new_item' => _x( 'New Dish', 'dish' ),
        'view_item' => _x( 'View Dish', 'dish' ),
        'search_items' => _x( 'Search Dishes', 'dish' ),
        'not_found' => _x( 'No dishes found', 'dish' ),
        'not_found_in_trash' => _x( 'No dishes found in Trash', 'dish' ),
        'parent_item_colon' => _x( 'Parent Dish:', 'dish' ),
        'menu_name' => _x( 'Dishes', 'dish' ),
    );

    $args = array(
        'labels' => $labels,
        'hierarchical' => false,

        'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt' ), //removed , 'custom-fields'
        'taxonomies' => array( 'menu-sections' ),
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => 'resres',

        'menu_position'	=> 100,
        'show_in_nav_menus' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'has_archive' => false,
        'query_var' => true,
        'can_export' => true,
        'rewrite' => true,
        'capability_type' => 'post'
    );

    register_post_type( 'dish', $args );

    if(get_option('resres_flush_permalinks') ) {
    }
    else {
        flush_rewrite_rules();
        add_option( 'resres_flush_permalinks', true );
    }
}
add_action( 'init', 'register_cpt_dish' );



function register_taxonomy_menu_sections() {

    $labels = array(
        'name' => _x( 'Menu Sections', 'menu_sections' ),
        'singular_name' => _x( 'Menu Section', 'menu_sections' ),
        'search_items' => _x( 'Search Menu Sections', 'menu_sections' ),
        //'popular_items' => _x( 'Popular Menu Sections', 'menu_sections' ),
        'all_items' => _x( 'All Menu Sections', 'menu_sections' ),
        //'parent_item' => _x( 'Parent Menu Section', 'menu_sections' ),
        //'parent_item_colon' => _x( 'Parent Menu Section:', 'menu_sections' ),
        'edit_item' => _x( 'Edit Menu Section', 'menu_sections' ),
        'update_item' => _x( 'Update Menu Section', 'menu_sections' ),
        'add_new_item' => _x( 'Add New Menu Section', 'menu_sections' ),
        'new_item_name' => _x( 'New Menu Section', 'menu_sections' ),
        'separate_items_with_commas' => _x( 'Separate menu sections with commas', 'menu_sections' ),
        'add_or_remove_items' => _x( 'Add or remove menu sections', 'menu_sections' ),
        'choose_from_most_used' => _x( 'Choose from the most used menu sections', 'menu_sections' ),
        'menu_name' => _x( 'Menu Sections', 'menu_sections' ),
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'show_in_nav_menus' => true,
        'show_ui' => true,
        'show_tagcloud' => false,
        'show_admin_column' => true,
        'hierarchical' => true,

        'rewrite' => true,
        'query_var' => true
    );

    register_taxonomy( 'menu_sections', array('dish'), $args );
}
add_action( 'init', 'register_taxonomy_menu_sections', 0 );


add_filter("manage_edit-menu_sections_columns", 'dd_menu_selection_column'); 
 
function dd_menu_selection_column($theme_columns) {
    $new_columns = array(
        'cb' => '<input type="checkbox" />',
        'name' => __('Name'),
        'description' => __('Description'),
        'slug' => __('Slug'),
        //'posts' => __('Posts'),
        'posts2' => __('Posts'),

        );
    return $new_columns;
}
add_filter("manage_menu_sections_custom_column", 'dd_menu_selection_new_column', 10, 3);
 
function dd_menu_selection_new_column($out, $column_name, $theme_id) {
    $theme = get_term($theme_id, 'menu_sections');
    switch ($column_name) {
        case 'posts2': 

            //var_dump($theme);
            $query = new WP_Query( array( 'menu_sections' => $theme->slug ) );
            $count = $query->post_count;

           
            $out .= '<a href="' . admin_url('edit.php?post_type=dish&menu_sections=') . $theme->slug . '">' . $count . '</a>'; 
            break;
 
        default:
            break;
    }
    return $out;    
}



function resres_change_menu_order( $menu_ord )
{
    global $submenu;

    // Enable the next line to see all menu orders
    //echo '<pre>'.print_r($submenu,true).'</pre>';

    $arr = array();
    $arr[] = $submenu['resres'][2];     //my original order was 0 dishes, 1 reservatiosn, 2 settings, 3 add new dish, 4 sections
    $arr[] = $submenu['resres'][0];
    $arr[] = $submenu['resres'][3];
    $arr[] = $submenu['resres'][4];
    $arr[] = $submenu['resres'][1];

unset($submenu['resres'][0]);
unset($submenu['resres'][1]);
unset($submenu['resres'][2]);
unset($submenu['resres'][3]);
unset($submenu['resres'][4]);

    $submenu['resres'] = $arr + $submenu['resres'];

    return $menu_ord;
}
if(current_user_can('manage_options')) {
    add_filter( 'custom_menu_order', 'resres_change_menu_order' );
}


// http://wordpress.org/support/topic/moving-taxonomy-ui-to-another-main-menu
function resres_move_taxonomy($parent_file) {
	global $current_screen;
	$taxonomy = $current_screen->taxonomy;
	if ($taxonomy == 'menu_sections')
		$parent_file = 'resres';
	return $parent_file;
}
add_action('parent_file', 'resres_move_taxonomy');



//source: http://flashingcursor.com/wordpress/change-the-enter-title-here-text-in-wordpress-963
function resres_change_cpt_placeholder( $title ){

    $screen = get_current_screen();

    if ( 'dish' == $screen->post_type ){
        $title = __('Dish name', 'resres');
    }

    return $title;
}

add_filter( 'enter_title_here', 'resres_change_cpt_placeholder' );




function resres_modify_default_meta_boxes()
{
    remove_meta_box( 'postimagediv', 'dish', 'side' );
    add_meta_box('postimagediv', __('Photo of Dish'), 'post_thumbnail_meta_box', 'dish', 'side', 'default');

    //might not keep this
    remove_meta_box( 'postexcerpt', 'dish', 'normal' );
    add_meta_box('postexcerpt', __('Short Description'), 'post_excerpt_meta_box', 'dish', 'normal', 'high');
}
add_action('do_meta_boxes', 'resres_modify_default_meta_boxes');



//remove text from excerpts
function remove_admin_stuff( $translated_text, $untranslated_text, $domain ) {

        $custom_field_text = 'Excerpts are optional hand-crafted summaries of your content that can be used in your theme. <a href="http://codex.wordpress.org/Excerpt" target="_blank">Learn more about manual excerpts.</a>';

        if ( is_admin() && $untranslated_text === $custom_field_text && get_post_type( get_the_ID() ) == 'dish' ) {
            return '';
        }

        return $translated_text;

}
add_filter('gettext', 'remove_admin_stuff', 20, 3);




function resres_taxonomy_message() {
    echo '<h3><strong>' . __('IMPORTANT: You can change the order of the Menu Sections from the') . ' <a href="' . admin_url('admin.php?page=resres-settings&tab=menu_ordering') . '">' . __('Settings page') . '</a></strong></h3><br><br>';
}
add_action('menu_sections_add_form_fields','resres_taxonomy_message');
