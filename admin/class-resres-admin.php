<?php
/**
 * Plugin Name.
 *
 * @package   ResRes_Admin
 * @author    Dean Robinson <team@deftdev.com>
 * @license   GPL-2.0+
 * @link      http://deftdev.com
 * @copyright 2014 deftDEV
 */

/**
 * Plugin class. This class should ideally be used to work with the
 * administrative side of the WordPress site.
 *
 * If you're interested in introducing public-facing
 * functionality, then refer to `class-resres.php`
 *
 *
 * @package ResRes_Admin
 * @author  Dean Robinson <team@deftdev.com>
 */
class ResRes_Admin {

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		/*
		 * - Uncomment following lines if the admin class should only be available for super admins
		 */
		/* if( ! is_super_admin() ) {
			return;
		} */

		/*
		 * Call $plugin_slug from public plugin class.
		 *
		 */
		$plugin = ResRes::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();

		// Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );

		// Add an action link pointing to the options page.
		$plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_slug . '.php' );
		add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );

		/*
		 * Define custom functionality.
		 *
		 * Read more about actions and filters:
		 * http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
		 */
		add_action( '@TODO', array( $this, 'action_method_name' ) );
		add_filter( '@TODO', array( $this, 'filter_method_name' ) );

		add_action('admin_init', array($this, 'resres_initialise_general_options' ) );
		add_action( 'admin_init', array($this, 'resres_initialise_form_options' ) );
		add_action( 'admin_init', array($this, 'resres_initialise_email_options' ) );
		add_action( 'admin_init', array($this, 'resres_initialise_menu_order_options' ) );

		add_action( 'wp_ajax_resres_save_update_sections', array($this, 'resres_save_update_sections') );

		add_action( 'admin_enqueue_scripts', array($this, 'enqueue_date_picker' ) );

		//add_action( 'admin_head', array($this, 'resres_theme_link' ) );

		add_action( 'wp_ajax_resres_add_reservation_from_admin', array($this, 'resres_add_reservation_from_admin') );

		//for reservation (admin) cancellation/arrived
		add_action( 'wp_ajax_resres_update_cancellation', array($this, 'resres_update_cancellation') );
		add_action( 'wp_ajax_resres_update_checkin', array($this, 'resres_update_checkin') );


		add_action( 'wp_ajax_resres_datepicker_highlight', array($this, 'resres_datepicker_highlight') );

		include_once('includes/meta_boxes.php');


		add_action('created_menu_sections', array($this, 'resres_new_menu_section_term' ) );
		add_action('delete_menu_sections', array($this, 'resres_deleted_menu_section_term' ) );

		//summer sales July 2014
		add_action('admin_notices', array( $this, 'resres_summer_2014') );
		add_action('wp_ajax_resres_summer_2014_dismiss', array( $this, 'resres_summer_2014_dismiss') );

	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		/*
		 * - Uncomment following lines if the admin class should only be available for super admins
		 */
		/* if( ! is_super_admin() ) {
			return;
		} */

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_styles() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			//return;
		}

		$screen = get_current_screen();

		$isitresrespage = substr($screen->id, 0, 11);

		wp_enqueue_style( $this->plugin_slug .'-font', plugins_url( 'assets/css/resres_font.css', __FILE__ ), array(), ResRes::VERSION );

		if ( $this->plugin_screen_hook_suffix == $screen->id || $isitresrespage == 'resres_page') {
			wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url( 'assets/css/admin.css', __FILE__ ), array(), ResRes::VERSION );
			wp_enqueue_style('jquery-ui-css', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');		}

	}

	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		$isitresrespage = substr($screen->id, 0, 11);

		$salecheck = get_option('resres_summer_sale');
		if( $salecheck != 'false') {			
			wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'assets/js/sale.js', __FILE__ ), array( 'jquery' ), ResRes::VERSION );
		}


		if ( $this->plugin_screen_hook_suffix == $screen->id || $isitresrespage == 'resres_page') {

			wp_enqueue_script('jquery-ui-core', 'jQuery');
			wp_enqueue_script('jquery-ui-slider', 'jQuery');
			wp_enqueue_script('jquery-ui-datepicker', 'jQuery');
			wp_enqueue_script('wp-color-picker', 'jQuery');

			wp_enqueue_script('jquery-ui-sortable', 'jQuery');

			wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'assets/js/admin.js', __FILE__ ), array( 'jquery' ), ResRes::VERSION );
			wp_enqueue_script( $this->plugin_slug . '-simplemodal', plugins_url( 'assets/js/jquery.simplemodal.js', __FILE__ ), array( 'jquery' ), ResRes::VERSION );
			wp_enqueue_script( $this->plugin_slug . '-validate', plugins_url( '../public/assets/js/jquery.validate.min.js', __FILE__ ), array( 'jquery', 'resres-admin-script' ), ResRes::VERSION );
			wp_enqueue_script( $this->plugin_slug . '-anytime', plugins_url( '../public/assets/js/jquery-ui-timepicker-min.js', __FILE__ ), array( 'jquery', 'jquery-ui-datepicker' ), ResRes::VERSION );

		$options = get_option('resres_options');
		$timef = get_option('time_format');
		$datef = $this->wpdatetojs(  get_option('date_format') );

//all this shit to stop notices.... yey.


			if( !empty( $options['resres_day_of_week_mon_from_one']) ) { $m1 = $this->resres_time_convert($options['resres_day_of_week_mon_from_one']); } else { $m1 = ':'; }
			if( !empty( $options['resres_day_of_week_mon_to_one']) ) { $m2 = $this->resres_time_convert($options['resres_day_of_week_mon_to_one']); } else { $m2 = ':'; }
			if( !empty( $options['resres_day_of_week_mon_from_two']) ) { $m3 = $this->resres_time_convert($options['resres_day_of_week_mon_from_two']); } else { $m3 = ':'; }
			if( !empty( $options['resres_day_of_week_mon_to_two']) ) { $m4 = $this->resres_time_convert($options['resres_day_of_week_mon_to_two']); } else { $m4 = ':'; }
			if( !empty( $options['resres_day_of_week_tue_from_one']) ) { $t1 = $this->resres_time_convert($options['resres_day_of_week_tue_from_one']); } else { $t1 = ':'; }
			if( !empty( $options['resres_day_of_week_tue_to_one']) ) { $t2 = $this->resres_time_convert($options['resres_day_of_week_tue_to_one']); } else { $t2 = ':'; }
			if( !empty( $options['resres_day_of_week_tue_from_two']) ) { $t3 = $this->resres_time_convert($options['resres_day_of_week_tue_from_two']); } else { $t3 = ':'; }
			if( !empty( $options['resres_day_of_week_tue_to_two']) ) { $t4 = $this->resres_time_convert($options['resres_day_of_week_tue_to_two']); } else { $t4 = ':'; }
			if( !empty( $options['resres_day_of_week_wed_from_one']) ) { $w1 = $this->resres_time_convert($options['resres_day_of_week_wed_from_one']); } else { $w1 = ':'; }
			if( !empty( $options['resres_day_of_week_wed_to_one']) ) { $w2 = $this->resres_time_convert($options['resres_day_of_week_wed_to_one']); } else { $w2 = ':'; }
			if( !empty( $options['resres_day_of_week_wed_from_two']) ) { $w3 = $this->resres_time_convert($options['resres_day_of_week_wed_from_two']); } else { $w3 = ':'; }
			if( !empty( $options['resres_day_of_week_wed_to_two']) ) { $w4 = $this->resres_time_convert($options['resres_day_of_week_wed_to_two']); } else { $w4 = ':'; }
			if( !empty( $options['resres_day_of_week_thu_from_one']) ) { $th1 = $this->resres_time_convert($options['resres_day_of_week_thu_from_one']); } else { $th1 = ':'; }
			if( !empty( $options['resres_day_of_week_thu_to_one']) ) { $th2 = $this->resres_time_convert($options['resres_day_of_week_thu_to_one']); } else { $th2 = ':'; }
			if( !empty( $options['resres_day_of_week_thu_from_two']) ) { $th3 = $this->resres_time_convert($options['resres_day_of_week_thu_from_two']); } else { $th3 = ':'; }
			if( !empty( $options['resres_day_of_week_thu_to_two']) ) { $th4 = $this->resres_time_convert($options['resres_day_of_week_thu_to_two']); } else { $th4 = ':'; }
			if( !empty( $options['resres_day_of_week_fri_from_one']) ) { $f1 = $this->resres_time_convert($options['resres_day_of_week_fri_from_one']); } else { $f1 = ':'; }
			if( !empty( $options['resres_day_of_week_fri_to_one']) ) { $f2 = $this->resres_time_convert($options['resres_day_of_week_fri_to_one']); } else { $f2 = ':'; }
			if( !empty( $options['resres_day_of_week_fri_from_two']) ) { $f3 = $this->resres_time_convert($options['resres_day_of_week_fri_from_two']); } else { $f3 = ':'; }
			if( !empty( $options['resres_day_of_week_fri_to_two']) ) { $f4 = $this->resres_time_convert($options['resres_day_of_week_fri_to_two']); } else { $f4 = ':'; }
			if( !empty( $options['resres_day_of_week_sat_from_one']) ) { $s1 = $this->resres_time_convert($options['resres_day_of_week_sat_from_one']); } else { $s1 = ':'; }
			if( !empty( $options['resres_day_of_week_sat_to_one']) ) { $s2 = $this->resres_time_convert($options['resres_day_of_week_sat_to_one']); } else { $s2 = ':'; }
			if( !empty( $options['resres_day_of_week_sat_from_two']) ) { $s3 = $this->resres_time_convert($options['resres_day_of_week_sat_from_two']); } else { $s3 = ':'; }
			if( !empty( $options['resres_day_of_week_sat_to_two']) ) { $s4 = $this->resres_time_convert($options['resres_day_of_week_sat_to_two']); } else { $s4 = ':'; }
			if( !empty( $options['resres_day_of_week_sun_from_one']) ) { $su1 = $this->resres_time_convert($options['resres_day_of_week_sun_from_one']); } else { $su1 = ':'; }
			if( !empty( $options['resres_day_of_week_sun_to_one']) ) { $su2 = $this->resres_time_convert($options['resres_day_of_week_sun_to_one']); } else { $su2 = ':'; }
			if( !empty( $options['resres_day_of_week_sun_from_two']) ) { $su3 = $this->resres_time_convert($options['resres_day_of_week_sun_from_two']); } else { $su3 = ':'; }
			if( !empty( $options['resres_day_of_week_sun_to_two']) ) { $su4 = $this->resres_time_convert($options['resres_day_of_week_sun_to_two']); } else { $su4 = ':'; }

		wp_localize_script('resres-anytime', 'resres_time_vars', array(
			'open_monday'			=>	isset( $options['resres_day_of_week_mon'] ),
			'open_tuesday'			=>	isset( $options['resres_day_of_week_tue'] ),
			'open_wednesday'		=>	isset( $options['resres_day_of_week_wed'] ),
			'open_thursday'			=>	isset( $options['resres_day_of_week_thu'] ),
			'open_friday'			=>	isset( $options['resres_day_of_week_fri'] ),
			'open_saturday'			=>	isset( $options['resres_day_of_week_sat'] ),
			'open_sunday'			=>	isset( $options['resres_day_of_week_sun'] ),
			'monday_from_one' 		=> 	$m1, //$this->resres_time_convert( isset( $options['resres_day_of_week_mon_from_one']) ),
			'monday_to_one' 		=> 	$m2, //$this->resres_time_convert($options['resres_day_of_week_mon_to_one']),
			'monday_from_two' 		=> 	$m3, //$this->resres_time_convert($options['resres_day_of_week_mon_from_two']),
			'monday_to_two' 		=> 	$m4, //$this->resres_time_convert($options['resres_day_of_week_mon_to_two']),
			'tuesday_from_one' 		=> 	$t1, //$this->resres_time_convert($options['resres_day_of_week_tue_from_one']),
			'tuesday_to_one' 		=> 	$t2, //$this->resres_time_convert($options['resres_day_of_week_tue_to_one']),
			'tuesday_from_two' 		=> 	$t3, //$this->resres_time_convert($options['resres_day_of_week_tue_from_two']),
			'tuesday_to_two' 		=> 	$t4, //$this->resres_time_convert($options['resres_day_of_week_tue_to_two']),
			'wednesday_from_one' 	=> 	$w1, //$this->resres_time_convert($options['resres_day_of_week_wed_from_one']),
			'wednesday_to_one' 		=> 	$w2, //$this->resres_time_convert($options['resres_day_of_week_wed_to_one']),
			'wednesday_from_two' 	=> 	$w3, //$this->resres_time_convert($options['resres_day_of_week_wed_from_two']),
			'wednesday_to_two' 		=> 	$w4, //$this->resres_time_convert($options['resres_day_of_week_wed_to_two']),
			'thursday_from_one' 	=> 	$th1, //$this->resres_time_convert($options['resres_day_of_week_thu_from_one']),
			'thursday_to_one' 		=> 	$th2, //$this->resres_time_convert($options['resres_day_of_week_thu_to_one']),
			'thursday_from_two' 	=> 	$th3, //$this->resres_time_convert($options['resres_day_of_week_thu_from_two']),
			'thursday_to_two' 		=> 	$th4, //$this->resres_time_convert($options['resres_day_of_week_thu_to_two']),
			'friday_from_one' 		=> 	$f1, //$this->resres_time_convert($options['resres_day_of_week_fri_from_one']),
			'friday_to_one' 		=> 	$f2, //$this->resres_time_convert($options['resres_day_of_week_fri_to_one']),
			'friday_from_two' 		=> 	$f3, //$this->resres_time_convert($options['resres_day_of_week_fri_from_two']),
			'friday_to_two' 		=> 	$f4, //$this->resres_time_convert($options['resres_day_of_week_fri_to_two']),
			'saturday_from_one' 	=> 	$s1, //$this->resres_time_convert($options['resres_day_of_week_sat_from_one']),
			'saturday_to_one' 		=> 	$s2, //$this->resres_time_convert($options['resres_day_of_week_sat_to_one']),
			'saturday_from_two' 	=> 	$s3, //$this->resres_time_convert($options['resres_day_of_week_sat_from_two']),
			'saturday_to_two' 		=> 	$s4, //$this->resres_time_convert($options['resres_day_of_week_sat_to_two']),
			'sunday_from_one' 		=> 	$su1, //$this->resres_time_convert($options['resres_day_of_week_sun_from_one']),
			'sunday_to_one' 		=> 	$su2, //$this->resres_time_convert($options['resres_day_of_week_sun_to_one']),
			'sunday_from_two' 		=> 	$su3, //$this->resres_time_convert($options['resres_day_of_week_sun_from_two']),
			'sunday_to_two' 		=> 	$su4, //$this->resres_time_convert($options['resres_day_of_week_sun_to_two']),
			'time_format'			=>	$timef,
			'date_format'			=>	$datef,
			'max_capacity' 			=> 	isset( $options['resres_max_hourly_capacity']),
			'datatables_search'		=> __('Search'),
			'datatables_emptyTable'	=> __('No data available.'),
			'datatables_info'		=> __('Showing _START_ to _END_ of _TOTAL_ entries.'),
			'datatables_infoEmpty'	=> __('Showing 0 to 0 of 0 entries.'),
			'datatables_infoFiltered'	=> __('Filtered from _MAX_ total entries.')
			)
		);

		}

	}

	/*
	 * Matches each symbol of PHP date format standard
	 * with jQuery equivalent codeword
	 * @author Tristan Jahier
	 */
	public function wpdatetojs($php_format)
	{
	    $SYMBOLS_MATCHING = array(
	        // Day
	        'd' => 'dd',
	        'D' => 'D',
	        'j' => 'd',
	        'l' => 'DD',
	        'N' => '',
	        'S' => '',
	        'w' => '',
	        'z' => 'o',
	        // Week
	        'W' => '',
	        // Month
	        'F' => 'MM',
	        'm' => 'mm',
	        'M' => 'M',
	        'n' => 'm',
	        't' => '',
	        // Year
	        'L' => '',
	        'o' => '',
	        'Y' => 'yy',
	        'y' => 'y',
	        // Time
	        'a' => '',
	        'A' => '',
	        'B' => '',
	        'g' => '',
	        'G' => '',
	        'h' => '',
	        'H' => '',
	        'i' => '',
	        's' => '',
	        'u' => ''
	    );
	    $jqueryui_format = "";
	    $escaping = false;
	    for($i = 0; $i < strlen($php_format); $i++)
	    {
	        $char = $php_format[$i];
	        if($char === '\\') // PHP date format escaping character
	        {
	            $i++;
	            if($escaping) $jqueryui_format .= $php_format[$i];
	            else $jqueryui_format .= '\'' . $php_format[$i];
	            $escaping = true;
	        }
	        else
	        {
	            if($escaping) { $jqueryui_format .= "'"; $escaping = false; }
	            if(isset($SYMBOLS_MATCHING[$char]))
	                $jqueryui_format .= $SYMBOLS_MATCHING[$char];
	            else
	                $jqueryui_format .= $char;
	        }
	    }
	    return $jqueryui_format;
	}



	public function resres_time_convert($time) {

	$x = explode(':', $time);
		$y = $x[1];

	if ( substr( $x[0], 0, 1 ) == 0 ) { // if the first character is a 0 then:
	   $x = substr( $x[0], 1 ); // take the string starting at the 2nd character (starts with 0,1,2,3,4)
	}
	else {
		$x = $x[0];
	}

	return $x . ':' . $y;

	}


	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {

		/*
		 * Add a settings page for this plugin to the Settings menu.
		 *
		 * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
		 *
		 *        Administration Menus: http://codex.wordpress.org/Administration_Menus
		 *
		 */


		$this->plugin_screen_hook_suffix = add_menu_page(
			__( 'ResRes', $this->plugin_slug ),
			__( 'ResRes', $this->plugin_slug ),
			'manage_options',
			$this->plugin_slug,
			array( $this, 'display_resres_registrations_page' ),
			//'dashicons-star-filled'
			//plugin_dir_url( __FILE__ ) . 'assets/resres_icon.png'
			'none'
		);

		add_submenu_page(
			'resres',
			__( 'Reservations', $this->plugin_slug ),
			__( 'Reservations', $this->plugin_slug ),
			'manage_options',
			'resres',
			array( $this, 'display_resres_registrations_page' )
		);

		add_submenu_page(
			'resres',
			__( 'Settings', $this->plugin_slug ),
			__( 'Settings', $this->plugin_slug ),
			'manage_options',
			'resres-settings',
			array( $this, 'display_resres_settings_page' )
		);


		

		add_submenu_page(
			'resres',
			__( 'Add New Dish', $this->plugin_slug ),
			__( 'Add New Dish', $this->plugin_slug ),
			'manage_options',
			'post-new.php?post_type=dish',
			''
		);

		add_submenu_page(
			'resres',
			__( 'Sections', $this->plugin_slug ),
			__( 'Sections', $this->plugin_slug ),
			'manage_options',
			'edit-tags.php?taxonomy=menu_sections',
			''
		);

	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */

	public function display_resres_settings_page() {
		include_once( 'views/admin.php' );
	}

	public function display_resres_registrations_page() {
		include_once( 'views/reservations.php' );
	}



	public function resres_get_registration_list() {

		return false;
	}


	public function resres_update_cancellation() {

		//var_dump($_POST);

		if ( !wp_verify_nonce( $_POST['nonce'], "resres_admin_reservation")) {
     		exit("No naughty business please");
   		} 
   		global $wpdb;

	   	$table_name_reservations = $wpdb->prefix . "resres_reservations";

		$wpdb->update( $table_name_reservations, array( 'is_active' => (int)$_POST['is_active'] ), array( 'reservation_id' => $_POST['resid'] ), array("%d"), array("%s") );

   		die();
	}

	public function resres_update_checkin() {

		//var_dump($_POST);

		if ( !wp_verify_nonce( $_POST['nonce'], "resres_admin_reservation")) {
     		exit("No naughty business please");
   		} 
   		global $wpdb;

	   	$table_name_reservations = $wpdb->prefix . "resres_reservations";

		$wpdb->update( $table_name_reservations, array( 'is_checked_in' => (int)$_POST['is_checked_in'] ), array( 'reservation_id' => $_POST['resid'] ), array("%d"), array("%s") );

   		die();
	}


	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.0
	 */
	public function add_action_links( $links ) {

		return array_merge(
			array(
				'settings' => '<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_slug ) . '">' . __( 'Settings', $this->plugin_slug ) . '</a>'
			),
			$links
		);

	}

	/**
	 * NOTE:     Actions are points in the execution of a page or process
	 *           lifecycle that WordPress fires.
	 *
	 *           Actions:    http://codex.wordpress.org/Plugin_API#Actions
	 *           Reference:  http://codex.wordpress.org/Plugin_API/Action_Reference
	 *
	 * @since    1.0.0
	 */
	public function action_method_name() {
		// @TODO: Define your action hook callback here

	}

	/**
	 * NOTE:     Filters are points of execution in which WordPress modifies data
	 *           before saving it or sending it to the browser.
	 *
	 *           Filters: http://codex.wordpress.org/Plugin_API#Filters
	 *           Reference:  http://codex.wordpress.org/Plugin_API/Filter_Reference
	 *
	 * @since    1.0.0
	 */
	public function filter_method_name() {
		// @TODO: Define your filter hook callback here
	}




	public function resres_save_update_sections($data) {

		//var_dump($_POST);




		$section_id = $_POST['section_id'];
		$section_name = $_POST['section_name'];

		$item_array = $_POST['resresItemArray'];

		global $wpdb;
		//$table_name_menu = $wpdb->prefix . "resres_menus";
		$table_name_section_items = $wpdb->prefix . "resres_section_items";
	   	$table_name_reservations = $wpdb->prefix . "resres_reservations";
	   	$idprefix = "res-";

		foreach ($item_array as $item) {

				echo $item['iprice'];
				$wpdb->insert($table_name_section_items,
				array(
					"item_name" 	=> $item['iname'],
					"item_desc" 	=> $item['idesc'],
					"item_price" 	=> (int)$item['iprice'],
					"item_meta" 	=> $allergens,
					"section_id" 	=> 1,
					"section_name" 	=> $section_name
					),
				array(
					"%s", // iitem name
					"%s", // item desc
					"%f", // item price
					"%s", // item meta
					"%d", // section id
					"%s"  // section name
					)
				);



		}


		die();
	}



	public function resres_add_reservation_from_admin($data) {

		$the_form_data = $_POST;

	   	global $wpdb;

		$table_name_menu = $wpdb->prefix . "resres_menus";
		$table_name_menu_items = $wpdb->prefix . "resres_menu_items";
	   	$table_name_reservations = $wpdb->prefix . "resres_reservations";

	   	$idprefix = "res-";

		$uid = uniqid($idprefix);

		$wpdb->resres_reservations = "{$wpdb->prefix}resres_reservations";

		$wpdb->insert($table_name_reservations,
			array(
				"reservation_id" => $uid,
				"is_active" => 1,
				"date_created" => date('Y-m-d'),
				"time_created" => date('H:i:s'),
				"res_date" => date('Y-m-d', strtotime($_POST['resres_date'])),
				"res_time" => $_POST['resres_time'],
				"partysize" => $_POST['resres_partysize'],
				"name" => $_POST['resres_name'],
				"phone" => $_POST['resres_phone'],
				"email" => $_POST['resres_email'],
				"menu" => $_POST['xxx'],
				"notes" => $_POST['resres_notes'],
				"table_id" => $_POST['xxx'],
				"table_section_id" => $_POST['xxx'],
				"wine" => $_POST['xxx'],
				"deposit" => $_POST['xxx'],
				"deposit_amount" => $_POST['xxx'],
				"paid" => $_POST['xxx'],
				"paid_amount" => $_POST['xxx'],
				"payment_type" => $_POST['xxx']

				),
			array(
				"%s", // res id
				"%d", // is_active
				"%s", // date cre
				"%s", // time cre
				"%s", // date res
				"%s", // time res
				"%d", // party
				"%s", // name
				"%s", // phone
				"%s", // email
				"%s", // menu
				"%s", // notes
				"%d", // table id
				"%d", // table section id
				"%s", // wine
				"%d", // dep
				"%d", // dep amt
				"%d", // paid
				"%d", // paid amt
				"%s" // pay type
				)
			);

		$response['sucmsg'] = __('<span id="resres_sucmsg"> - Reservation added successfully</span>');
		$response['success'] = true;
		$response = json_encode($response);
		echo $response;

		die();
	}














/*
*
*
* General options tab
*
*
***********************************************************/

public function resres_initialise_general_options() {

if( false == get_option( 'resres_options' ) ) {
    add_option( 'resres_options' );
} // end if

	// First, we register a section. This is necessary since all future options must belong to one.
	add_settings_section(
	    'general_settings_section',         // ID used to identify this section and with which to register options
	    __('General Options'),                  // Title to be displayed on the administration page
	    array($this, 'resres_general_options_callback'), // Callback used to render the description of the section
	    'resres_options'                           // Page on which to add this section of options
	);

	add_settings_field(
		'resres_org_settings',                      // ID used to identify the field throughout the theme
		__('Restaurant information'),                           // The label to the left of the option interface element
		array($this, 'resres_org_settings_callback'),   // The name of the function responsible for rendering the option interface
		'resres_options',                          // The page on which this option will be displayed
		'general_settings_section',         // The name of the section to which this field belongs
		array(                              // The array of arguments to pass to the callback. In this case, just a description.
		    ''
		)
	);

	add_settings_field(
		'resres_day_of_week',                      // ID used to identify the field throughout the theme
		__('Days restaurant is open'),                           // The label to the left of the option interface element
		array($this, 'resres_dayofweek_callback'),   // The name of the function responsible for rendering the option interface
		'resres_options',                          // The page on which this option will be displayed
		'general_settings_section',         // The name of the section to which this field belongs
		array(                              // The array of arguments to pass to the callback. In this case, just a description.
		    ''
		)
	);

	add_settings_field(
		'resres_overrides',                      // ID used to identify the field throughout the theme
		__('Override opening times: <br> Enter one date per line. Please use the Month-Date format, e.g. for 25th December use 12-25'),                           // The label to the left of the option interface element
		array($this, 'resres_overrides_callback'),   // The name of the function responsible for rendering the option interface
		'resres_options',                          // The page on which this option will be displayed
		'general_settings_section',         // The name of the section to which this field belongs
		array(                              // The array of arguments to pass to the callback. In this case, just a description.
		    __('Please do not use leading zeros. E.g. if the date you want to exclude is January the first, write it as 1-1, not 01-01')
		)
	);
	
	add_settings_field(
		'resres_currency',                      // ID used to identify the field throughout the theme
		__('Currency symbol'),                           // The label to the left of the option interface element
		array($this, 'resres_currency_callback'),   // The name of the function responsible for rendering the option interface
		'resres_options',                          // The page on which this option will be displayed
		'general_settings_section',         // The name of the section to which this field belongs
		array(                              // The array of arguments to pass to the callback. In this case, just a description.
		    __('Default is $')
		)
	);

	add_settings_field(
		'resres_disable_featured_images',                      // ID used to identify the field throughout the theme
		__('Disable menu images'),                           // The label to the left of the option interface element
		array($this, 'resres_disable_featured_images_callback'),   // The name of the function responsible for rendering the option interface
		'resres_options',                          // The page on which this option will be displayed
		'general_settings_section',         // The name of the section to which this field belongs
		array(                              // The array of arguments to pass to the callback. In this case, just a description.
		    ''
		)
	);





	add_settings_field(
		'resres_time',                      // ID used to identify the field throughout the theme
		'',                           // The label to the left of the option interface element
		array($this, 'resres_free_callback'),   // The name of the function responsible for rendering the option interface
		'resres_options',                          // The page on which this option will be displayed
		'general_settings_section',         // The name of the section to which this field belongs
		array(                              // The array of arguments to pass to the callback. In this case, just a description.
		    ''
		)
	);


	register_setting(
	    'resres_options',
	    'resres_options'
	);
	

	register_setting(
	    'resres_options',
	    'resres_options'
	);

	register_setting(
	    'resres_options',
	    'resres_options'
	);

	register_setting(
	    'resres_options',
	    'resres_options'
	);

	register_setting(
	    'resres_options',
	    'resres_options'
	);

	register_setting(
	    'resres_options',
	    'resres_options'
	);

	register_setting(
	    'resres_options',
	    'resres_options'
	);

}

public function resres_general_options_callback() {
    echo __('<p>The following shortcodes can be used on pages or posts</p>');
    echo __('<p>[resres] - displays the reservations form</p>');
    echo __('<p>[resresmenu] - displays the menu</p>');
    //echo __('<p>[resrescancel] - displays the cancel reservation form</p>');
    //echo __('<p>[resrestimes] - displays the opening times and location details</p>');
    echo __('<p><strong>Please see the <a href="http://www.deftdev.com/document/resres-documentation/">documentation</a> for further information regarding the shortcodes.</strong></p>');
} // end sandbox_general_options_callback



public function resres_org_settings_callback($args) {

    $options = get_option('resres_options');

	if(isset( $options['address_name'] )) { $address_name             = $options['address_name'];} else { $address_name = '';}
	if(isset( $options['address_line1'] )) { $address_line1           = $options['address_line1'];} else { $address_line1 = '';}
	if(isset( $options['address_line2'] )) { $address_line2           = $options['address_line2'];} else { $address_line2 = '';}
	if(isset( $options['address_city'] )) { $address_city             = $options['address_city'];} else { $address_city = '';}
	if(isset( $options['address_region'] )) { $address_region         = $options['address_region'];} else { $address_region = '';}
	if(isset( $options['address_country'] )) { $address_country       = $options['address_country'];} else { $address_country = '';}
	if(isset( $options['address_postalcode'] )) { $address_postalcode = $options['address_postalcode'];} else { $address_postalcode = '';}
	if(isset( $options['address_phone'] )) { $address_phone           = $options['address_phone'];} else { $address_phone = '';}
	if(isset( $options['address_fax'] )) { $address_fax               = $options['address_fax'];} else { $address_fax = '';}
	if(isset( $options['address_email'] )) { $address_email           = $options['address_email'];} else { $address_email = '';}
	if(isset( $options['address_facebook'] )) { $address_facebook     = $options['address_facebook'];} else { $address_facebook = '';}
	if(isset( $options['address_twitter'] )) { $address_twitter       = $options['address_twitter'];} else { $address_twitter = '';}
	if(isset( $options['address_googleplus'] )) { $address_googleplus = $options['address_googleplus'];} else { $address_googleplus = '';}

    $html = '';

    $html .= '<input class="" type="text" id="address_name" name="resres_options[address_name]" value="' . $address_name . '" />';
    $html .= '<label for="address_name"> '  . __('Restaurant name') . '</label>';

    $html .='<br>';

    $html .= '<input class="" type="text" id="address_line1" name="resres_options[address_line1]" value="' . $address_line1 . '" />';
    $html .= '<label for="address_line1"> '  . __('Address') . '</label>';

    $html .='<br>';

    $html .= '<input class="" type="text" id="address_line2" name="resres_options[address_line2]" value="' . $address_line2 . '" />';
    $html .= '<label for="address_line2"> '  . __('Address line 2') . '</label>';

    $html .='<br>';

    $html .= '<input class="" type="text" id="address_city" name="resres_options[address_city]" value="' . $address_city . '" />';
    $html .= '<label for="address_city"> '  . __('City') . '</label>';

    $html .='<br>';

    $html .= '<input class="" type="text" id="address_region" name="resres_options[address_region]" value="' . $address_region . '" />';
    $html .= '<label for="address_region"> '  . __('State/Region') . '</label>';

    $html .='<br>';

    $html .= '<input class="" type="text" id="address_country" name="resres_options[address_country]" value="' . $address_country . '" />';
    $html .= '<label for="address_country"> '  . __('Country') . '</label>';

    $html .='<br>';

    $html .= '<input class="" type="text" id="address_postalcode" name="resres_options[address_postalcode]" value="' . $address_postalcode . '" />';
    $html .= '<label for="address_postalcode"> '  . __('Zip/Postal Code') . '</label>';

    $html .='<br>';

	$html .= '<input class="" type="text" id="address_phone" name="resres_options[address_phone]" value="' . $address_phone . '" />';
	$html .= '<label for="address_phone"> '  . __('Telephone') . '</label>';

    $html .='<br>';

	$html .= '<input class="" type="text" id="address_fax" name="resres_options[address_fax]" value="' . $address_fax . '" />';
	$html .= '<label for="address_fax"> '  . __('Fax') . '</label>';

    $html .='<br>';

	$html .= '<input class="" type="text" id="address_email" name="resres_options[address_email]" value="' . $address_email . '" />';
	$html .= '<label for="address_email"> '  . __('E-mail') . '</label>';

    $html .='<br>';

	$html .= '<input class="" type="text" id="address_facebook" name="resres_options[address_facebook]" value="' . $address_facebook . '" />';
	$html .= '<label for="address_facebook"> '  . __('Facebook (url)') . '</label>';

    $html .='<br>';

	$html .= '<input class="" type="text" id="address_twitter" name="resres_options[address_twitter]" value="' . $address_twitter . '" />';
	$html .= '<label for="address_twitter"> '  . __('Twitter (handle)') . '</label>';

    $html .='<br>';

	$html .= '<input class="" type="text" id="address_googleplus" name="resres_options[address_googleplus]" value="' . $address_googleplus . '" />';
	$html .= '<label for="address_googleplus"> '  . __('Google Plus (user code)') . '</label>';

    echo $html;

}


public function resres_dayofweek_callback($args) {

	// First, we read the options collection
    $options = get_option('resres_options');

    $html = '';

	ob_start(); ?>

		<table id="resres_opening_times_admin">
		<thead>
			<tr>
				<th></th>
				<th><?php echo __('Open?'); ?></th>
				<th><?php echo __('First sitting from'); ?></th>
				<th><?php echo __('To'); ?></th>
				<!--<th><?php //echo __('Max. Capacity'); ?></th>-->
				<th><?php echo __('Second sitting from'); ?></th>
				<th><?php echo __('To'); ?></th>
				<!--<th><?php //echo __('Max. Capacity'); ?></th>-->
			</tr>
		</thead>
		<tbody>
			<tr>
				<td><?php echo __('Mon'); ?></td>
				<td><input type="checkbox" id="resres_day_of_week_mon" name="resres_options[resres_day_of_week_mon]" value="1" <?php echo checked(1, isset ($options['resres_day_of_week_mon'] ), false); ?> /></td>
				<td><input type="text" class="resres_formfield resres_time" id="resres_day_of_week_mon_from_one" name="resres_options[resres_day_of_week_mon_from_one]" value="<?php if(isset ( $options['resres_day_of_week_mon_from_one'] ) ) { echo $options['resres_day_of_week_mon_from_one']; } ?>" /></td>
				<td><input type="text" class="resres_formfield resres_time" id="resres_day_of_week_mon_to_one" name="resres_options[resres_day_of_week_mon_to_one]" value="<?php if( isset( $options['resres_day_of_week_mon_to_one'] ) ) { echo $options['resres_day_of_week_mon_to_one']; } ?>" /></td>

				<!--<td><input class="small-text" type="text" id="resres_day_of_week_mon_capacity_one" name="resres_options[resres_day_of_week_mon_capacity_one]" value="<?php //if( isset( $options['resres_day_of_week_mon_capacity_one'] ) ) { echo $options['resres_day_of_week_mon_capacity_one']; } ?>" /></td>-->

				<td><input type="text" class="resres_formfield resres_time" id="resres_day_of_week_mon_from_two" name="resres_options[resres_day_of_week_mon_from_two]" value="<?php if( isset( $options['resres_day_of_week_mon_from_two'] ) ) { echo $options['resres_day_of_week_mon_from_two']; } ?>" /></td>
				<td><input type="text" class="resres_formfield resres_time" id="resres_day_of_week_mon_to_two" name="resres_options[resres_day_of_week_mon_to_two]" value="<?php if( isset( $options['resres_day_of_week_mon_to_two'] ) ) { echo $options['resres_day_of_week_mon_to_two']; } ?>" /></td>

				<!--<td><input class="small-text" type="text" id="resres_day_of_week_mon_capacity_two" name="resres_options[resres_day_of_week_mon_capacity_two]" value="<?php //if( isset( $options['resres_day_of_week_mon_capacity_two']; ?>" /></td>-->
			</tr>

			<tr>
				<td><?php echo __('Tue'); ?></td>
				<td><input type="checkbox" id="resres_day_of_week_tue" name="resres_options[resres_day_of_week_tue]" value="1" <?php echo checked(1, isset( $options['resres_day_of_week_tue'] ), false); ?> /></td>
				<td><input type="text" class="resres_formfield resres_time" id="resres_day_of_week_tue_from_one" name="resres_options[resres_day_of_week_tue_from_one]" value="<?php if( isset ( $options['resres_day_of_week_tue_from_one'] ) ) { echo $options['resres_day_of_week_tue_from_one']; } ?>" /></td>
				<td><input type="text" class="resres_formfield resres_time" id="resres_day_of_week_tue_to_one" name="resres_options[resres_day_of_week_tue_to_one]" value="<?php if( isset ( $options['resres_day_of_week_tue_to_one'] ) ) { echo $options['resres_day_of_week_tue_to_one']; } ?>" /></td>
				<!--<td><input class="small-text" type="text" id="currency_symbol" name="resres_options[currency_symbol]" value="<?php //if( isset ( $options['currency_symbol'] ) ) { echo $options['currency_symbol']; } ?>" /></td>-->
				<td><input type="text" class="resres_formfield resres_time" id="resres_day_of_week_tue_from_two" name="resres_options[resres_day_of_week_tue_from_two]" value="<?php if( isset ( $options['resres_day_of_week_tue_from_two'] ) ) { echo $options['resres_day_of_week_tue_from_two']; } ?>" /></td>
				<td><input type="text" class="resres_formfield resres_time" id="resres_day_of_week_tue_to_two" name="resres_options[resres_day_of_week_tue_to_two]" value="<?php if( isset ( $options['resres_day_of_week_tue_to_two'] ) ) { echo $options['resres_day_of_week_tue_to_two']; } ?>" /></td>
				<!--<td><input class="small-text" type="text" id="currency_symbol" name="resres_options[currency_symbol]" value="<?php //if( isset ( $options['currency_symbol'] ) ) { echo $options['currency_symbol']; } ?>" /></td>-->
			</tr>

			<tr>
				<td><?php echo __('Wed'); ?></td>
				<td><input type="checkbox" id="resres_day_of_week_wed" name="resres_options[resres_day_of_week_wed]" value="1" <?php echo checked(1, isset( $options['resres_day_of_week_wed'] ), false); ?> /></td>
				<td><input type="text" class="resres_formfield resres_time" id="resres_day_of_week_wed_from_one" name="resres_options[resres_day_of_week_wed_from_one]" value="<?php if( isset( $options['resres_day_of_week_wed_from_one'] ) ) { echo $options['resres_day_of_week_wed_from_one']; } ?>" /></td>
				<td><input type="text" class="resres_formfield resres_time" id="resres_day_of_week_wed_to_one" name="resres_options[resres_day_of_week_wed_to_one]" value="<?php if( isset( $options['resres_day_of_week_wed_to_one'] ) ) { echo $options['resres_day_of_week_wed_to_one']; } ?>" /></td>
				<!--<td><input class="small-text" type="text" id="currency_symbol" name="resres_options[currency_symbol]" value="<?php //if( isset( $options['currency_symbol'] ) ) { echo $options['currency_symbol']; } ?>" /></td>-->
				<td><input type="text" class="resres_formfield resres_time" id="resres_day_of_week_wed_from_two" name="resres_options[resres_day_of_week_wed_from_two]" value="<?php if( isset( $options['resres_day_of_week_wed_from_two'] ) ) { echo $options['resres_day_of_week_wed_from_two']; } ?>" /></td>
				<td><input type="text" class="resres_formfield resres_time" id="resres_day_of_week_wed_to_two" name="resres_options[resres_day_of_week_wed_to_two]" value="<?php if( isset( $options['resres_day_of_week_wed_to_two'] ) ) { echo $options['resres_day_of_week_wed_to_two']; } ?>" /></td>
				<!--<td><input class="small-text" type="text" id="currency_symbol" name="resres_options[currency_symbol]" value="<?php //if( isset( $options['currency_symbol'] ) ) { echo $options['currency_symbol']; } ?>" /></td>-->
			</tr>

			<tr>
				<td><?php echo __('Thu'); ?></td>
				<td><input type="checkbox" id="resres_day_of_week_thu" name="resres_options[resres_day_of_week_thu]" value="1" <?php echo checked(1, isset( $options['resres_day_of_week_thu']) , false); ?> /></td>
				<td><input type="text" class="resres_formfield resres_time" id="resres_day_of_week_thu_from_one" name="resres_options[resres_day_of_week_thu_from_one]" value="<?php if( isset( $options['resres_day_of_week_thu_from_one'] ) ) { echo $options['resres_day_of_week_thu_from_one']; } ?>" /></td>
				<td><input type="text" class="resres_formfield resres_time" id="resres_day_of_week_thu_to_one" name="resres_options[resres_day_of_week_thu_to_one]" value="<?php if( isset( $options['resres_day_of_week_thu_to_one'] ) ) { echo $options['resres_day_of_week_thu_to_one']; } ?>" /></td>
				<!--<td><input class="small-text" type="text" id="currency_symbol" name="resres_options[currency_symbol]" value="<?php //if( isset( $options['currency_symbol'] ) ) { echo $options['currency_symbol']; } ?>" /></td>-->
				<td><input type="text" class="resres_formfield resres_time" id="resres_day_of_week_thu_from_two" name="resres_options[resres_day_of_week_thu_from_two]" value="<?php if( isset( $options['resres_day_of_week_thu_from_two'] ) ) { echo $options['resres_day_of_week_thu_from_two']; } ?>" /></td>
				<td><input type="text" class="resres_formfield resres_time" id="resres_day_of_week_thu_to_two" name="resres_options[resres_day_of_week_thu_to_two]" value="<?php if( isset( $options['resres_day_of_week_thu_to_two'] ) ) { echo $options['resres_day_of_week_thu_to_two']; } ?>" /></td>
				<!--<td><input class="small-text" type="text" id="currency_symbol" name="resres_options[currency_symbol]" value="<?php //if( isset( $options['currency_symbol'] ) ) { echo $options['currency_symbol']; } ?>" /></td>-->
			</tr>

			<tr>
				<td><?php echo __('Fri'); ?></td>
				<td><input type="checkbox" id="resres_day_of_week_fri" name="resres_options[resres_day_of_week_fri]" value="1" <?php echo checked(1, isset( $options['resres_day_of_week_fri'] ), false); ?> /></td>
				<td><input type="text" class="resres_formfield resres_time" id="resres_day_of_week_fri_from_one" name="resres_options[resres_day_of_week_fri_from_one]" value="<?php if( isset( $options['resres_day_of_week_fri_from_one'] ) ) { echo $options['resres_day_of_week_fri_from_one']; } ?>" /></td>
				<td><input type="text" class="resres_formfield resres_time" id="resres_day_of_week_fri_to_one" name="resres_options[resres_day_of_week_fri_to_one]" value="<?php if( isset( $options['resres_day_of_week_fri_to_one'] ) ) { echo $options['resres_day_of_week_fri_to_one']; } ?>" /></td>
				<!--<td><input class="small-text" type="text" id="currency_symbol" name="resres_options[currency_symbol]" value="<?php //if( isset( $options['currency_symbol'] ) ) { echo $options['currency_symbol']; } ?>" /></td>-->
				<td><input type="text" class="resres_formfield resres_time" id="resres_day_of_week_fri_from_two" name="resres_options[resres_day_of_week_fri_from_two]" value="<?php if( isset( $options['resres_day_of_week_fri_from_two'] ) ) { echo $options['resres_day_of_week_fri_from_two']; } ?>" /></td>
				<td><input type="text" class="resres_formfield resres_time" id="resres_day_of_week_fri_to_two" name="resres_options[resres_day_of_week_fri_to_two]" value="<?php if( isset( $options['resres_day_of_week_fri_to_two'] ) ) { echo $options['resres_day_of_week_fri_to_two']; } ?>" /></td>
				<!--<td><input class="small-text" type="text" id="currency_symbol" name="resres_options[currency_symbol]" value="<?php //if( isset( $options['currency_symbol'] ) ) { echo $options['currency_symbol']; } ?>" /></td>-->
			</tr>

			<tr>
				<td><?php echo __('Sat'); ?></td>
				<td><input type="checkbox" id="resres_day_of_week_sat" name="resres_options[resres_day_of_week_sat]" value="1" <?php echo checked(1, isset( $options['resres_day_of_week_sat'] ), false); ?> /></td>
				<td><input type="text" class="resres_formfield resres_time" id="resres_day_of_week_sat_from_one" name="resres_options[resres_day_of_week_sat_from_one]" value="<?php if( isset( $options['resres_day_of_week_sat_from_one'] ) ) { echo $options['resres_day_of_week_sat_from_one']; } ?>" /></td>
				<td><input type="text" class="resres_formfield resres_time" id="resres_day_of_week_sat_to_one" name="resres_options[resres_day_of_week_sat_to_one]" value="<?php if( isset( $options['resres_day_of_week_sat_to_one'] ) ) { echo $options['resres_day_of_week_sat_to_one']; } ?>" /></td>
				<!--<td><input class="small-text" type="text" id="currency_symbol" name="resres_options[currency_symbol]" value="<?php //if( isset( $options['currency_symbol'] ) ) { echo $options['currency_symbol']; } ?>" /></td>-->
				<td><input type="text" class="resres_formfield resres_time" id="resres_day_of_week_sat_from_two" name="resres_options[resres_day_of_week_sat_from_two]" value="<?php if( isset( $options['resres_day_of_week_sat_from_two'] ) ) { echo $options['resres_day_of_week_sat_from_two']; } ?>" /></td>
				<td><input type="text" class="resres_formfield resres_time" id="resres_day_of_week_sat_to_two" name="resres_options[resres_day_of_week_sat_to_two]" value="<?php if( isset( $options['resres_day_of_week_sat_to_two'] ) ) { echo $options['resres_day_of_week_sat_to_two']; } ?>" /></td>
				<!--<td><input class="small-text" type="text" id="currency_symbol" name="resres_options[currency_symbol]" value="<?php //if( isset( $options['currency_symbol'] ) ) { echo $options['currency_symbol']; } ?>" /></td>-->
			</tr>

			<tr>
				<td><?php echo __('Sun'); ?></td>
				<td><input type="checkbox" id="resres_day_of_week_sun" name="resres_options[resres_day_of_week_sun]" value="1" <?php echo checked(1, isset( $options['resres_day_of_week_sun']) , false); ?> /></td>
				<td><input type="text" class="resres_formfield resres_time" id="resres_day_of_week_sun_from_one" name="resres_options[resres_day_of_week_sun_from_one]" value="<?php if( isset( $options['resres_day_of_week_sun_from_one'] ) ) { echo $options['resres_day_of_week_sun_from_one']; } ?>" /></td>
				<td><input type="text" class="resres_formfield resres_time" id="resres_day_of_week_sun_to_one" name="resres_options[resres_day_of_week_sun_to_one]" value="<?php if( isset( $options['resres_day_of_week_sun_to_one'] ) ) { echo $options['resres_day_of_week_sun_to_one']; } ?>" /></td>
				<!--<td><input class="small-text" type="text" id="currency_symbol" name="resres_options[currency_symbol]" value="<?php //if( isset( $options['currency_symbol'] ) ) { echo $options['currency_symbol']; } ?>" /></td>-->
				<td><input type="text" class="resres_formfield resres_time" id="resres_day_of_week_sun_from_two" name="resres_options[resres_day_of_week_sun_from_two]" value="<?php if( isset( $options['resres_day_of_week_sun_from_two'] ) ) { echo $options['resres_day_of_week_sun_from_two']; } ?>" /></td>
				<td><input type="text" class="resres_formfield resres_time" id="resres_day_of_week_sun_to_two" name="resres_options[resres_day_of_week_sun_to_two]" value="<?php if( isset( $options['resres_day_of_week_sun_to_two'] ) ) { echo $options['resres_day_of_week_sun_to_two']; } ?>" /></td>
				<!--<td><input class="small-text" type="text" id="currency_symbol" name="resres_options[currency_symbol]" value="<?php //if( isset( $optionsresres_day_of_week_sun_to_two['currency_symbol'] ) ) { echo $optionsresres_day_of_week_sun_to_two['currency_symbol']; } ?>" /></td>-->
			</tr>
		</tbody>
		</table>

	<?php

	$html .= ob_get_clean();

    echo $html;

}

public function resres_overrides_callback($args) {

    $options = get_option('resres_options');

    $html = '<textarea cols="25" rows="10" id="resres_overrides" name="resres_options[resres_overrides]">' . $options['resres_overrides'] . '</textarea>';
    $html .= '<label for="show_content" style="vertical-align:top"> '  . $args[0] . '</label>';

    echo $html;

} // end resres_overrides_callback

public function resres_currency_callback($args) {

    $options = get_option('resres_options');

    $html = '<input class="small-text" type="text" id="currency_symbol" name="resres_options[currency_symbol]" value="' . $options['currency_symbol'] . '" />';
    $html .= '<label for="currency_symbol"> '  . $args[0] . '</label>';

    echo $html;

}



public function resres_disable_featured_images_callback($args) {

    $options = get_option('resres_options');

    $html = '<label for="resres_disable_featured_images"></label>';
    $html .= '<input type="checkbox" id="resres_disable_featured_images" name="resres_options[resres_disable_featured_images]" value="1" ' . checked(1, isset($options['resres_disable_featured_images']), false) . '/>';

    echo $html;

}






public function resres_free_callback($args) {


    $html = '<div>';

    $html .= '<h2>Want more great features? Upgrade to ResRes Pro!</h2>';

    $html .= '<ul class="resresfreelist">';

    $html .= '<li><span class="resresfreelisticon"></span>More templates - accordion, grid, double columns & chalkboard</li>';
    $html .= '<li><span class="resresfreelisticon"></span>Allergen information (text or icons)</li>';
    $html .= '<li><span class="resresfreelisticon"></span>Wine information</li>';
    $html .= '<li><span class="resresfreelisticon"></span>Chilli pepper symbols to denote spicy heat</li>';
    $html .= '<li><span class="resresfreelisticon"></span>Customise the colour scheme</li>';
    $html .= '<li><span class="resresfreelisticon"></span>Built in numeric captcha system</li>';
    $html .= "<li><span class='resresfreelisticon'></span>or use Google's reCAPTCHA</li>";
    $html .= '<li><span class="resresfreelisticon"></span>Themeroller styles for the form elements</li>';
    $html .= '<li><span class="resresfreelisticon"></span>Cancellation system</li>';

    $html .= '</ul>';

    $html .= '<h2><a class="resresupgrade" href="http://www.deftdev.com/downloads/resres/" target="_blank">Upgrade Now!</a></h2>';

    $html .= '</div>';

    $html .= '<input type="hidden" name="resres_form_options[themeroller_select]" id="" value="Smoothness" />';
    
    echo $html;

}












/*
*
*
* Reservation form options tab
*
*
***********************************************************/

public function resres_initialise_form_options() {

    // If the social options don't exist, create them.
    if( false == get_option( 'resres_form_options' ) ) {
        add_option( 'resres_form_options' );
    } // end if


	add_settings_section(
	    'resres_form_options_section',          // ID used to identify this section and with which to register options
	    __('Reservation Form Options'),                   // Title to be displayed on the administration page
	    array($this, 'resres_form_options_callback'),  // Callback used to render the description of the section
	    'resres_form_options'      // Page on which to add this section of options
	);


	add_settings_field(
	    'max_party_size',
	    __('Max. Party Size'),
	    array($this, 'resres_regform_partysize_callback'),
	    'resres_form_options',
	    'resres_form_options_section',
	    array(
	        ''
	    )
	);



	add_settings_field(
		'resres_free',                      // ID used to identify the field throughout the theme
		'',                           // The label to the left of the option interface element
		array($this, 'resres_free_callback'),   // The name of the function responsible for rendering the option interface
		'resres_form_options',                          // The page on which this option will be displayed
		'resres_form_options_section',         // The name of the section to which this field belongs
		array(                              // The array of arguments to pass to the callback. In this case, just a description.
		    ''
		)
	);


	register_setting(
	    'resres_form_options',
	    'resres_form_options'
	);

	register_setting(
	    'resres_form_options',
	    'resres_form_options'
	);
/*
	register_setting(
	    'resres_form_options',
	    'resres_form_options'
	);

	register_setting(
	    'resres_form_options',
	    'resres_form_options'
	);

	register_setting(
	    'resres_form_options',
	    'resres_form_options'
	);

	register_setting(
	    'resres_form_options',
	    'resres_form_options'
	);
*/

}




public function resres_form_options_callback() {
    echo '<p></p>';
}

public function resres_regform_partysize_callback($args) {

    $options = get_option('resres_form_options');

    $html = '<input type="text" id="party_size" name="resres_form_options[party_size]" value="' . $options['party_size'] . '" />';
    $html .= '<label for="show_content"> '  . $args[0] . '</label>';

    echo $html;

}

/*
public function resres_regform_disable_themeroller_callback($args) {

    $options = get_option('resres_form_options');

    $html = '<input type="checkbox" id="disable_themeroller" name="resres_form_options[disable_themeroller]" value="1" ' . checked(1, isset($options['disable_themeroller']), false) . '/>';

    $html .= '<label for="show_content"> '  . $args[0] . '</label>';

    echo $html;

}

public function resres_regform_themeroller_styles_callback($args) {

    $options = get_option('resres_form_options');

    if( isset( $options['disable_themeroller']) && $options['disable_themeroller'] == 1) { $options['themeroller_select'] = "Please Select"; $stop_this_option="disabled"; };

    if( isset($options['themeroller_select']) ) { $current_selected = $options['themeroller_select']; } else { $current_selected = ''; }

    $html = '<select id="themeroller_select" name="resres_form_options[themeroller_select]"' . isset ( $stop_this_option ) . '>';

					$html .= '<option value="">Please Select</option>';
					$html .= '<option ' . selected('Black Tie', $current_selected, false) . 'value="Black Tie">Black Tie</option>';
					$html .= '<option ' . selected('Blitzer', $current_selected, false) . 'value="Blitzer">Blitzer</option>';
					$html .= '<option ' . selected('Cupertino', $current_selected, false) . 'value="Cupertino">Cupertino</option>';
					$html .= '<option ' . selected('Dark Hive', $current_selected, false) . 'value="Dark Hive">Dark Hive</option>';
					$html .= '<option ' . selected('Dot Luv', $current_selected, false) . 'value="Dot Luv">Dot Luv</option>';
					$html .= '<option ' . selected('Eggplant', $current_selected, false) . 'value="Eggplant">Eggplant</option>';
					$html .= '<option ' . selected('Excite Bike', $current_selected, false) . 'value="Excite Bike">Excite Bike</option>';
					$html .= '<option ' . selected('Flick', $current_selected, false) . 'value="Flick">Flick</option>';
					$html .= '<option ' . selected('Hot Sneaks', $current_selected, false) . 'value="Hot Sneaks">Hot Sneaks</option>';
					$html .= '<option ' . selected('Le Frog', $current_selected, false) . 'value="Le Frog">Le Frog</option>';
					$html .= '<option ' . selected('Humanity', $current_selected, false) . 'value="Humanity">Humanity</option>';
					$html .= '<option ' . selected('Mint Choc', $current_selected, false) . 'value="Mint choc">Mint Choc</option>';
					$html .= '<option ' . selected('Overcast', $current_selected, false) . 'value="Overcast">Overcast</option>';
					$html .= '<option ' . selected('Pepper Grinder', $current_selected, false) . 'value="Pepper Grinder">Pepper Grinder</option>';
					$html .= '<option ' . selected('Redmond', $current_selected, false) . 'value="Redmond">Redmond</option>';
					$html .= '<option ' . selected('Smoothness', $current_selected, false) . 'value="Smoothness">Smoothness</option>';
					$html .= '<option ' . selected('South Street', $current_selected, false) . 'value="South Street">South Street</option>';
					$html .= '<option ' . selected('Start', $current_selected, false) . 'value="Start">Start</option>';
					$html .= '<option ' . selected('Sunny', $current_selected, false) . 'value="Sunny">Sunny</option>';
					$html .= '<option ' . selected('Swanky Purse', $current_selected, false) . 'value="Swanky Purse">Swanky Purse</option>';
					$html .= '<option ' . selected('Trontastic', $current_selected, false) . 'value="Trontastic">Trontastic</option>';
					$html .= '<option ' . selected('UI Darkness', $current_selected, false) . 'value="UI Darkness">UI Darkness</option>';
					$html .= '<option ' . selected('UI Lightness', $current_selected, false) . 'value="UI Lightness">UI Lightness</option>';
					$html .= '<option ' . selected('Vader', $current_selected, false) . 'value="Vader">Vader</option>';

    $html .= '</select>';




    $html .= '<label for="show_content"> '  . $args[0] . '</label>';



    echo $html;

}


public function resres_regform_disable_resres_captcha_callback($args) {

    $options = get_option('resres_form_options');

    $html = '<input type="checkbox" id="disable_resres_captcha" name="resres_form_options[disable_resres_captcha]" value="1" ' . checked(1, isset( $options['disable_resres_captcha'] ), false) . '/>';

    $html .= '<label for="show_content"> '  . $args[0] . '</label>';

    echo $html;

}


public function resres_regform_enable_recaptcha_callback($args) {

    $options = get_option('resres_form_options');

    $html = '<input type="checkbox" id="enable_recaptcha" name="resres_form_options[enable_recaptcha]" value="1" ' . checked(1, isset($options['enable_recaptcha']), false) . '/>';
    $html .= '<label for="show_content"> '  . $args[0] . '</label>';

    echo $html;

}


public function resres_regform_recaptcha_key_callback($args) {

    $options = get_option('resres_form_options');

    $html = '<input type="text" id="recaptcha_public" name="resres_form_options[recaptcha_public]" value="' . $options['recaptcha_public'] . '" />';
    $html .= '<label for="show_content"> '  . __('Public Key') . '</label>';

    $html .= "<br>";

    $html .= '<input type="text" id="recaptcha_private" name="resres_form_options[recaptcha_private]" value="' . $options['recaptcha_private'] . '" />';
    $html .= '<label for="show_content"> '  . __('Private Key') . '</label>';

    echo $html;

}

*/




















/*
*
*
* Email options tab
*
*
***********************************************************/

public function resres_initialise_email_options() {

if( false == get_option( 'resres_email_options' ) ) {
    add_option( 'resres_email_options' );
} // end if

	// First, we register a section. This is necessary since all future options must belong to one.
	add_settings_section(
	    'resres_email_options_section',         // ID used to identify this section and with which to register options
	    __('Email Options'),                  // Title to be displayed on the administration page
	    array($this, 'resres_email_options_callback'), // Callback used to render the description of the section
	    'resres_email_options'                           // Page on which to add this section of options
	);

/*
	add_settings_field(
		'resres_disable_html_email',                      // ID used to identify the field throughout the theme
		__('Disable HTML emails'),                           // The label to the left of the option interface element
		array($this, 'resres_disable_html_email_callback'),   // The name of the function responsible for rendering the option interface
		'resres_email_options',                          // The page on which this option will be displayed
		'resres_email_options_section',         // The name of the section to which this field belongs
		array(                              // The array of arguments to pass to the callback. In this case, just a description.
		    __('Emails will still go out, but in plain text form.')
		)
	);
*/

	add_settings_field(
	    'admin_emails',
	    __('Disable emails to site admin'),
	    array($this, 'resres_regform_admin_emails_callback'),
	    'resres_email_options',
	    'resres_email_options_section',
	    array(
	        ''
	    )
	);

	add_settings_field(
	    'registration_emails',
	    __('Disable reservation emails to customer'),
	    array($this, 'resres_regform_registration_emails_callback'),
	    'resres_email_options',
	    'resres_email_options_section',
	    array(
	        ''
	    )
	);

	add_settings_field(
	    'resres_create_registrant_email',
	    __('The customer email. <br>') . '
	    <span class="resres_email_tags">
			<ul class="dean">
			<li>' . __('These tags can be used in your emails.') . '</li>
			<li>{customer_email}</li>
			<li>{customer_name}</li>
			<li>{customer_phone}</li>
			<li>{reservation_date}</li>
			<li>{reservation_time}</li>
			<li>{reservation_notes}</li>
			<li>{party_size}</li>
			<li>{admin_email}</li>
			<li>{restaurant_add}</li>
			<li>{restaurant_add2}</li>
			<li>{restaurant_city}</li>
			<li>{restaurant_region}</li>
			<li>{restaurant_postalcode}</li>
			<li>{restaurant_country}</li>
			<li>{restaurant_phone}</li>
			<li>{restaurant_email}</li>
			<li>{restaurant_name}</li>
			</ul>
			</span>
			',
	    array($this, 'resres_email_create_registrant_email_callback'),
	    'resres_email_options',
	    'resres_email_options_section',
	    array(
	        ''
	    )
	);

	add_settings_field(
	    'resres_create_admin_email',
	    __('The admin email.'),
	    array($this, 'resres_email_create_admin_email_callback'),
	    'resres_email_options',
	    'resres_email_options_section',
	    array(
	        ''
	    )
	);




	add_settings_field(
		'resres_time',                      // ID used to identify the field throughout the theme
		'',                           // The label to the left of the option interface element
		array($this, 'resres_free_callback'),   // The name of the function responsible for rendering the option interface
		'resres_email_options',                          // The page on which this option will be displayed
		'resres_email_options_section',         // The name of the section to which this field belongs
		array(                              // The array of arguments to pass to the callback. In this case, just a description.
		    ''
		)
	);

	register_setting(
	    'resres_email_options',
	    'resres_email_options'
	);

	register_setting(
	    'resres_email_options',
	    'resres_email_options'
	);

	register_setting(
	    'resres_email_options',
	    'resres_email_options'
	);

	register_setting(
	    'resres_email_options',
	    'resres_email_options'
	);

	register_setting(
	    'resres_email_options',
	    'resres_email_options'
	);

	register_setting(
	    'resres_email_options',
	    'resres_email_options'
	);

	register_setting(
	    'resres_email_options',
	    'resres_email_options'
	);

	register_setting(
	    'resres_email_options',
	    'resres_email_options'
	);
}

public function resres_email_options_callback() {
}


public function resres_disable_html_email_callback($args) {

	// First, we read the options collection
    $options = get_option('resres_email_options');

    $html = '<input type="checkbox" id="resres_disable_html_email" name="resres_email_options[resres_disable_html_email]" value="1" ' . checked(1, $options['resres_disable_html_email'], false) . '/>';
    $html .= '<label for="show_content"> '  . $args[0] . '</label>';



    echo $html;

}

public function resres_regform_admin_emails_callback($args) {

    $options = get_option('resres_email_options');

    $html = '<input type="checkbox" id="admin_emails" name="resres_email_options[admin_emails]" value="1" ' . checked(1, isset($options['admin_emails']), false) . '/>';

    $html .= '<label for="show_content"> '  . $args[0] . '</label>';

    echo $html;

}


public function resres_regform_registration_emails_callback($args) {

    $options = get_option('resres_email_options');

    $html = '<input type="checkbox" id="registration_emails" name="resres_email_options[registration_emails]" value="1" ' . checked(1, isset($options['registration_emails']), false) . '/>';

    $html .= '<label for="show_content"> '  . $args[0] . '</label>';

    echo $html;

}


public function resres_email_create_registrant_email_callback($args) {

    $options = get_option('resres_email_options');

    $html = '';

    $html .= '<label for="show_content"> '  . __('To') . '</label><br>';

    $html .= '<input type="text" id="registration_emails_registrant_to" name="resres_email_options[registration_emails_registrant_to]" value="' . $options['registration_emails_registrant_to'] . '" /><br>';

    $html .= '<label for="show_content"> '  . __('Cc') . '</label><br>';

	$html .= '<input type="text" id="registration_emails_registrant_cc" name="resres_email_options[registration_emails_registrant_cc]" value="' . $options['registration_emails_registrant_cc'] . '" /><br>';

    $html .= '<label for="show_content"> '  . __('Subject') . '</label><br>';

    $html .= '<input type="text" id="registration_emails_registrant_subject" name="resres_email_options[registration_emails_registrant_subject]" value="' . $options['registration_emails_registrant_subject'] . '" /><br>';

    $html .= '<label for="show_content"> '  . __('Message') . '</label><br>';

    $html .= '<textarea id="registration_emails_registrant_message" name="resres_email_options[registration_emails_registrant_message]">' . $options['registration_emails_registrant_message'] . '</textarea>';



    echo $html;

}


public function resres_email_create_admin_email_callback($args) {

    $options = get_option('resres_email_options');

    $html = '';

    $html .= '<label for="show_content"> '  . __('To') . '</label><br>';

    $html .= '<input type="text" id="registration_emails_admin_to" name="resres_email_options[registration_emails_admin_to]" value="' . $options['registration_emails_admin_to'] . '" /><br>';

    $html .= '<label for="show_content"> '  . __('Cc') . '</label><br>';

	$html .= '<input type="text" id="registration_emails_admin_cc" name="resres_email_options[registration_emails_admin_cc]" value="' . $options['registration_emails_admin_cc'] . '" /><br>';

    $html .= '<label for="show_content"> '  . __('Subject') . '</label><br>';

    $html .= '<input type="text" id="registration_emails_admin_subject" name="resres_email_options[registration_emails_admin_subject]" value="' . $options['registration_emails_admin_subject'] . '" /><br>';

    $html .= '<label for="show_content"> '  . __('Message') . '</label><br>';

    $html .= '<textarea id="registration_emails_admin_message" name="resres_email_options[registration_emails_admin_message]">' . $options['registration_emails_admin_message'] . '</textarea>';


$html .= "<p>" . __('Available email tags') . "</p>";

$html .= "{reservation_id} {admin_email} {customer_name} {customer_phone} {customer_email} {reservation_date} {reservation_time} {party_size} {reservation_notes} {restaurant_name} {restaurant_add} {restaurant_add2} {restaurant_city} {restaurant_region} {restaurant_country} {restaurant_postalcode} {restaurant_phone} {restaurant_fax} {restaurant_email} {restaurant_facebook} {restaurant_twitter} {restaurant_googleplus}";


    echo $html;

}




/*
*
*
* menu ordering tab
*
*
***********************************************************/

public function resres_initialise_menu_order_options() {

if( false == get_option( 'resres_menu_ordering' ) ) {
    add_option( 'resres_menu_ordering' );
} // end if

	// First, we register a section. This is necessary since all future options must belong to one.
	add_settings_section(
		'menu_order_section',         // ID used to identify this section and with which to register options
		__('Menu Ordering'),                  // Title to be displayed on the administration page
		array($this, 'resres_menu_ordering_page_callback'), // Callback used to render the description of the section
		'resres_menu_ordering'                           // Page on which to add this section of options
	);



	add_settings_field(
		'resres_order_sections',                      // ID used to identify the field throughout the theme
		__('Order the menu sections'),                           // The label to the left of the option interface element
		array($this, 'resres_order_sections_callback'),   // The name of the function responsible for rendering the option interface
		'resres_menu_ordering',                          // The page on which this option will be displayed
		'menu_order_section',         // The name of the section to which this field belongs
		array(                              // The array of arguments to pass to the callback. In this case, just a description.
		    ''
		)
	);

	register_setting(
	    'resres_menu_ordering',
	    'resres_menu_ordering'
	);

	add_settings_field(
		'resres_menu_order_settings',                      // ID used to identify the field throughout the theme
		__('Order the dishes'),                            // The label to the left of the option interface element
		array($this, 'resres_menu_ordering_callback'),   // The name of the function responsible for rendering the option interface
		'resres_menu_ordering',                          // The page on which this option will be displayed
		'menu_order_section',         // The name of the section to which this field belongs
		array(                              // The array of arguments to pass to the callback. In this case, just a description.
		    ''
		)
	);

	register_setting(
	    'resres_menu_ordering',
	    'resres_menu_ordering'
	);


}


public function resres_menu_ordering_page_callback() {
}

public function resres_menu_ordering_callback($args) {

    $options = get_option('resres_menu_ordering');

    $html = '';

	?>
	<div class="wrap">
		<form method="post" action="options.php">
		
			<?php settings_fields('resres_menu_ordering'); ?>
		</form>
	</div>
<?php

	echo $this->resres_get_dishes_by_section();

    echo $html;

}


public function resres_order_sections_callback($args) {

	if(isset($_POST['resres_update_menu_order'])) {

		$x = $_POST['resres_sections'];
		update_option( 'resres_sections', $x );
	}

    $options = get_option('resres_sections');

    $html = '<small><em>' . __('Drag & drop to re-order') . '</em></small>';
    $html .= '<ul id="resres_menu_section_list">';
    $html .= $this->resres_get_the_menu_sections();
    $html .= '</ul>';

    $html .= '<label for="show_footer"> '  . $args[0] . '</label>';

    echo $html;

}

public function resres_get_dishes_by_section() {
	//jquery sortable should be loaded already.

	echo '<small><em>' . __('Drag & drop to re-order') . '</em></small>';
	echo "<br>";
	echo '<small><em>' . __('Dishes can only be re-ordered within their section.') . '</em></small>';


	if(isset($_POST['resres_update_menu_order'])) {

		global $wpdb;

		$x = $_POST['rr'];

		foreach( $x as $key => $value ) {
			$wpdb->update( $wpdb->posts, array("menu_order" => $key), array("ID" => $value), array("%d"), array("%d") );
		}

	}

	$sections_options = get_option('resres_sections');
	$options = get_option('resres_menu_ordering');

	//if no sections, no dishes will show so let's add a message
	
	if( $sections_options == false ) { echo "<br><br>"; echo __('Please allocate a dish to a section.'); return false;}

	$t = array();
    // from fucntion resres_get_the_menu_sections
    foreach ($sections_options as $term) {
			$t[] = get_term($term, 'menu_sections');
	}

//var_dump($t);


	foreach ($t as $key) {
		$args = array(
			'posts_per_page'   => -1,
			'orderby'          => 'menu_order',
			'order'            => 'ASC',
			'post_type'        => 'dish',
			'menu_sections'    => $key->slug,
			'post_status'      => 'publish',
			'suppress_filters' => true 
		);

		$posts_array = get_posts( $args );

//var_dump($posts_array);


		echo '<h2>' . $key->name . '</h2>';

		echo '<ul class="ui-sortable resres_menu_ordering">';

		foreach ($posts_array as $post) {
			//if(!isset($post)) { echo "Nothing to see here, move along!"; continue; }
			//var_dump($post);
			//echo '<li name="rr[]" value="1" menu-order="'. $post->menu_order .'">' . $post->post_title . '</li>';

			echo '<li><input menu-order="'. $post->menu_order .'" type="checkbox" id="" name="rr[]" value="'. $post->ID .'" checked >' . $post->post_title . '</li>';

		}

		echo '</ul>';
	}

}







    public function resres_summer_2014(){
    	$x = get_option( 'resres_summer_sale' );
    	if( $x == "false" ) { return false; }

	    echo '
		<style>
			#resres_summer_2014_dismiss_wrap a {
				text-decoration: underline;
			}
			a#resres_buy_now_button {
				display: block;
				width: 80px;
				text-align:center;
				text-decoration:none;
			}
			a#resres_buy_now_button:hover {
				cursor: pointer;
			}
			#resres_buy_now_button {
				border: none;
				background-color:green;
				padding:10px;
				font-size: 18px;
				color: #fff;
				float:right;
			}
		</style>
	    <div class="updated" id="resres_summer_2014_dismiss_wrap">
	    <span style="float:right"><a href="#" id="resres_summer_2014_dismiss" target="_blank">Dismiss</a></span>
	       <h3>ResRes Summer Sale</h3>
	       	       <p><a href="http://www.deftdev.com/go/res-res-summer-special" id="resres_buy_now_button" target="_blank">Buy Now</a></p>

	       <h4><strong><a href="http://www.deftdev.com/go/res-res-summer-special" target="_blank">Buy Now</a></strong> and get <strong>20%</strong> off ResRes Premium! Use the coupon code HAPPYSUMMER2014</h4>
	       <p><strong>Buy before 1st of August</strong> and get ResRes Premium for 20% less and get more templates, an admin reservation tracker and <a href="http://www.deftdev.com/go/res-res-summer-special" target="_blank">many more features</a></p>
	    </div>';
	}
    public function resres_summer_2014_dismiss(){
	    add_option('resres_summer_sale', 'false');
	    die();
	}



	//* hooks into the add new term function and dumps the term id into the resres_sections options
	public function resres_new_menu_section_term($term_id) {

		$the_new_term = get_term($term_id, 'menu_sections');

		$the_terms_options = get_option('resres_sections');


		if($the_terms_options == false) {
			$term = array();
			$the_terms_options = array();
			$term[] = (string)$the_new_term->term_id;
			//$term = serialize($term);
		}
		else {
			//$the_terms_options = unserialize($the_terms_options);
			$the_terms_options[] = (string)$the_new_term->term_id;
			$term = serialize($the_terms_options);
			$term = $the_terms_options;
		}

		update_option('resres_sections', $term);
	}



	//* hooks into the delete term function and removes the term id from the resres_sections options
	public function resres_deleted_menu_section_term($deleted_term) {

		$the_deleted_term = $deleted_term;

		$the_terms_options = get_option('resres_sections');

		//$the_terms_options = unserialize($the_terms_options);

		$pos = array_search($the_deleted_term, $the_terms_options);

		unset($the_terms_options[$pos]);

		//$term = serialize($the_terms_options);
		$term = $the_terms_options;

		update_option('resres_sections', $term);
	}


	public function resres_get_the_menu_sections() {

		$options = get_option('resres_sections');
		//$options = unserialize($options);

		if( empty($options) ) { return __('No sections found.'); }

		$html = '';
		foreach ($options as $term) {
			$t = get_term($term, 'menu_sections');
			//$html .= '<li id="" value="' . $term. '" name="resres_options[sections]">' . $t->name. '</li>';
			$html .= '<li><input type="checkbox" id="" name="resres_sections[]" value="' . $term. '" checked />' . $t->name. '</li>';
		}

		return $html;
	}



	public function resres_theme_link() {
		echo '<link id="ui-theme" rel="stylesheet" type="text/css" href="' . plugins_url() . '/resres/public/assets/js/themes/smoothness/jquery-ui.css">';
	}


	public function enqueue_date_picker(){
		wp_enqueue_script('jquery-ui-datepicker');
	}

	public function resres_datepicker_highlight() {

	}




} // END OF CLASS!











//http://en.bainternet.info/2013/add-taxonomy-filter-to-custom-post-type

if (!class_exists('ResResTaxonomyFilter')){
  /**
    * Tax CTP Filter Class
    * Simple class to add custom taxonomy dropdown to a custom post type admin edit list
    * @author Ohad Raz <admin@bainternet.info>
    * @version 0.1
    */
    class ResResTaxonomyFilter
    {
        /**
         * __construct
         * @author Ohad Raz <admin@bainternet.info>
         * @since 0.1
         * @param array $cpt [description]
         */
        function __construct($cpt = array()){
            $this->cpt = $cpt;
            // Adding a Taxonomy Filter to Admin List for a Custom Post Type
            add_action( 'restrict_manage_posts', array($this,'resres_menu_sections_filter' ));
        }

        /**
         * resres_menu_sections_filter  add the slelect dropdown per taxonomy
         * @author Ohad Raz <admin@bainternet.info>
         * @since 0.1
         * @return void
         */
        public function resres_menu_sections_filter() {
            // only display these taxonomy filters on desired custom post_type listings
            global $typenow;
            $types = array_keys($this->cpt);
            if (in_array($typenow, $types)) {
                // create an array of taxonomy slugs you want to filter by - if you want to retrieve all taxonomies, could use get_taxonomies() to build the list
                $filters = $this->cpt[$typenow];
                foreach ($filters as $tax_slug) {
                    // retrieve the taxonomy object
                    $tax_obj = get_taxonomy($tax_slug);
                    $tax_name = $tax_obj->labels->name;

                    // output html for taxonomy dropdown filter
                    echo "<select name='".strtolower($tax_slug)."' id='".strtolower($tax_slug)."' class='postform'>";
                    echo "<option value=''>Show All $tax_name</option>";
                    $this->generate_taxonomy_options($tax_slug,0,0,(isset($_GET[strtolower($tax_slug)])? $_GET[strtolower($tax_slug)] : null));
                    echo "</select>";
                }
            }
        }

        /**
         * generate_taxonomy_options generate dropdown
         * @author Ohad Raz <admin@bainternet.info>
         * @since 0.1
         * @param  string  $tax_slug
         * @param  string  $parent
         * @param  integer $level
         * @param  string  $selected
         * @return void
         */
        public function generate_taxonomy_options($tax_slug, $parent = '', $level = 0,$selected = null) {
            $args = array('show_empty' => 1);
            if(!is_null($parent)) {
                $args = array('parent' => $parent);
            }
            $terms = get_terms($tax_slug,$args);
            $tab='';
            for($i=0;$i<$level;$i++){
                $tab.='--';
            }

            foreach ($terms as $term) {
                // output each select option line, check against the last $_GET to show the current option selected
                echo '<option value='. $term->slug, $selected == $term->slug ? ' selected="selected"' : '','>' .$tab. $term->name .' (' . $term->count .')</option>';
                $this->generate_taxonomy_options($tax_slug, $term->term_id, $level+1,$selected);
            }



        }


    }//end class
}//end if
new ResResTaxonomyFilter(array('dish' => array('menu_sections')));
