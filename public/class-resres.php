<?php
/**
 * Plugin Name.
 *
 * @package   ResRes
 * @author    Dean Robinson <team@deftdev.com>
 * @license   GPL-2.0+
 * @link      http://deftdev.com
 * @copyright 2014 deftDEV
 */

/**
 * Plugin class. This class should ideally be used to work with the
 * public-facing side of the WordPress site.
 *
 * If you're interested in introducing administrative or dashboard
 * functionality, then refer to `class-resres-admin.php`
 * *
 * @package ResRes
 * @author  Dean Robinson <team@deftdev.com>
 */
class ResRes {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	const VERSION = '1.0.6.f';

	/**
	 * Database version
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	protected $resres_db_version = '1.0';

	/**
	 * Unique identifier for your plugin.
	 *
	 *
	 * The variable name is used as the text domain when internationalizing strings
	 * of text. Its value should match the Text Domain file header in the main
	 * plugin file.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'resres';

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Activate plugin when new blog is added
		add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );

		// Load public-facing style sheet and JavaScript.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		/* Define custom functionality.
		 * Refer To http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
		 */
		add_action( '@TODO', array( $this, 'action_method_name' ) );
		add_filter( '@TODO', array( $this, 'filter_method_name' ) );

		add_shortcode('resres', array($this, 'resres_registration_form_shortcode'));

		include_once('includes/item_cpt.php');

		add_shortcode('resresmenu', array($this, 'resres_menu_shortcode') );
		add_shortcode('resrescancel', array($this, 'resres_cancellation') );

		add_shortcode('resrestimes', array($this, 'resres_opening_times') );

		add_filter('the_title', array($this, 'resres_filter_single_dish_title') );
		add_filter( 'the_content', array($this, 'resres_filter_single_dish_content' ) );


		add_action( 'resres_display_meta', array( $this, 'resres_display_meta' ), 10, 3 );
		add_action( 'resres_display_chili', array( $this, 'resres_display_chili' ), 10, 3 );
		add_action( 'resres_display_wine', array( $this, 'resres_display_wine' ), 10, 3 );
		add_action( 'resres_display_misc', array( $this, 'resres_display_misc' ), 10, 3 );


	}

	/**
	 * Return the plugin slug.
	 *
	 * @since    1.0.0
	 *
	 * @return    Plugin slug variable.
	 */
	public function get_plugin_slug() {
		return $this->plugin_slug;
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Activate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       activated on an individual blog.
	 */
	public static function activate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide  ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_activate();
				}

				restore_current_blog();

			} else {
				self::single_activate();
			}

		} else {
			self::single_activate();
		}

	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Deactivate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       deactivated on an individual blog.
	 */
	public static function deactivate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_deactivate();

				}

				restore_current_blog();

			} else {
				self::single_deactivate();
			}

		} else {
			self::single_deactivate();
		}

	}

	/**
	 * Fired when a new site is activated with a WPMU environment.
	 *
	 * @since    1.0.0
	 *
	 * @param    int    $blog_id    ID of the new blog.
	 */
	public function activate_new_site( $blog_id ) {

		if ( 1 !== did_action( 'wpmu_new_blog' ) ) {
			return;
		}

		switch_to_blog( $blog_id );
		self::single_activate();
		restore_current_blog();

	}

	/**
	 * Get all blog ids of blogs in the current network that are:
	 * - not archived
	 * - not spam
	 * - not deleted
	 *
	 * @since    1.0.0
	 *
	 * @return   array|false    The blog ids, false if no matches.
	 */
	private static function get_blog_ids() {

		global $wpdb;

		// get an array of blog ids
		$sql = "SELECT blog_id FROM $wpdb->blogs
			WHERE archived = '0' AND spam = '0'
			AND deleted = '0'";

		return $wpdb->get_col( $sql );

	}


	/**
	 * Fired for each blog when the plugin is activated.
	 *
	 * @since    1.0.0
	 */
	private static function single_activate() {
		//Define activation functionality here

	$resres_default_general = array(
		'currency_symbol'	=>	'$'
		);
	$resres_default_form = array(
			'party_size'								=>	'25',
			'enable_recpatcha'							=> 	'1',
			'themeroller_select'						=>	'Smoothness'

		);
	$resres_default_email = array(
			'resres_disable_html_email'					=>	'0',
			'admin_emails'								=>	'0',
			'registration_emails_registrant_to'			=>	'{customer_email}',
			'registration_emails_registrant_cc'			=>	'',
			'registration_emails_registrant_subject'	=>	'Your reservation at {restaurant_name}',
			'registration_emails_registrant_message'	=>	'<p>Hi {customer_name},</p>

			<p>We have received your reservation for {party_size} on {reservation_date} at {reservation_time}.</p>

			<p>Should there be any problems we will contact you on {customer_phone}. If you need to change anything or cancel, please contact us on {restaurant_phone}.</p>

			<p>Your reservation ID is {reservation_id}</p>

			<p>Regards,</p>

			<p>The team at {restaurant_name}</p>

			<p>{restaurant_phone}</p>
			<p>{restaurant_email}</p>
			<p>{restaurant_add}, {restaurant_add2}, {restaurant_city}, {restaurant_postalcode}</p>
			<p> {restaurant_facebook} {restaurant_twitter} {restaurant_googleplus}</p>',

			'registration_emails_admin_to'				=>	'{admin_email}',
			'registration_emails_admin_cc'				=>	'',
			'registration_emails_admin_subject'			=>	'A new reservation',
			'registration_emails_admin_message'			=>	'<p>You have received a new reservation.</p>

			<p>Name: {customer_name}</p>

			<p>Phone: {customer_phone}</p>

			<p>Email: {customer_email}</p>

			<p>Date: {reservation_date}</p>

			<p>Time: {reservation_time}</p>

			<p>Party Size: {party_size}</p>

			<p>Notes: {reservation_notes}</p>',

			'registration_emails_cancel_admin_to'				=>	'{admin_email}',
			'registration_emails_cancel_admin_cc'				=>	'',
			'registration_emails_cancel_admin_subject'			=>	'Cancellation',
			'registration_emails_cancel_admin_message'			=>	'<p>A customer has cancelled.</p>

			<p>Name: {customer_name}</p>

			<p>Phone: {customer_phone}</p>

			<p>Email: {customer_email}</p>

			<p>Date: {reservation_date}</p>

			<p>Time: {reservation_time}</p>

			<p>Party Size: {party_size}</p>

			<p>Notes: {reservation_notes}</p>',

			'registration_emails_cancel_registrant_to'				=>	'{customer_email}',
			'registration_emails_cancel_registrant_cc'				=>	'',
			'registration_emails_cancel_registrant_subject'			=>	'Reservation Cancellation',
			'registration_emails_cancel_registrant_message'			=>	'<p><strong>Your reservation has been cancelled</strong></p>

			<p>Name: {customer_name}</p>

			<p>Phone: {customer_phone}</p>

			<p>Email: {customer_email}</p>

			<p>Date: {reservation_date}</p>

			<p>Time: {reservation_time}</p>

			<p>Party Size: {party_size}</p>

			<p>Notes: {reservation_notes}</p>'
		);

	add_option( 'resres_options', $resres_default_general );
	add_option( 'resres_form_options', $resres_default_form );
	add_option( 'resres_email_options', $resres_default_email );


	/**
	 * This is cos of a bug where in 1.0.0.f if they had saved the form options the themeroller options would be removed. This checks for it and adds it back.
	 */
	$options = get_option('resres_form_options');
	if(!$options['themeroller_select']) {
		$options['themeroller_select'] = "Smoothness";
		update_option( 'resres_form_options', $options );
	}


	foreach($resres_default_email as $opt) {
		add_option( 'resres_email_options', $opt );
	}




   	global $wpdb;
	global $charset_collate;

	$table_name_menu = $wpdb->prefix . "resres_menus";
	//$table_name_section_items = $wpdb->prefix . "resres_section_items";
   	$table_name_reservations = $wpdb->prefix . "resres_reservations";
   	$table_name_capacity = $wpdb->prefix . "resres_capacity";


/*	$sql_create_section_items_table = "CREATE TABLE $table_name_section_items (
		item_id bigint(20) unsigned NOT NULL auto_increment,
		item_name varchar(100) NOT NULL,
		item_desc text NOT NULL DEFAULT '',
		item_price decimal(15,2) NOT NULL,
		item_meta text NOT NULL DEFAULT '',
		section_id bigint(20),
		section_name varchar(100) NOT NULL,
		PRIMARY KEY  (item_id),
		KEY section_id (section_id)
	) $charset_collate; ";
*/

	$sql_create_reservations_table = "CREATE TABLE $table_name_reservations (
		resres_id bigint(20) unsigned NOT NULL auto_increment,
		reservation_id varchar(20) NOT NULL,
		is_active tinyint(1) NOT NULL,
		is_checked_in tinyint(1) NOT NULL,
		is_eating tinyint(1) NOT NULL,
		date_created date NOT NULL,
		time_created time NOT NULL,
		res_date date NOT NULL,
		res_time time NOT NULL,
		partysize smallint(20) unsigned NOT NULL,
		name varchar(100) NOT NULL,
		phone varchar(30) DEFAULT '' NOT NULL,
		email varchar(100) DEFAULT '' NOT NULL,
		notes text NOT NULL DEFAULT '',
		menu text NOT NULL DEFAULT '',
		table_id smallint(20) unsigned  DEFAULT '0' NOT NULL,
		table_section_id smallint(20) unsigned  DEFAULT '0' NOT NULL,
		wine text DEFAULT '' NOT NULL,
		deposit tinyint(1) DEFAULT '0' NOT NULL,
		deposit_amount smallint(20) DEFAULT '0' NOT NULL,
		paid tinyint(1) DEFAULT '0' NOT NULL,
		paid_amount smallint(20)  DEFAULT '0' NOT NULL,
		payment_type tinytext,
		PRIMARY KEY  (resres_id),
		KEY reservation_id (reservation_id)
	) $charset_collate; ";

	//capacity_number_reserved is text as it adds a serialised array of times -> capacity
	$sql_create_capacity_table = "CREATE TABLE $table_name_capacity (
		capacity_id bigint(20) unsigned NOT NULL auto_increment,
		capacity_date date NOT NULL,
		capacity_number_reserved text,
		capacity_full boolean NOT NULL DEFAULT '0',
		PRIMARY KEY  (capacity_id),
		KEY capacity_id (capacity_id)
	) $charset_collate; ";

   require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

	//dbDelta( $sql_create_menu_table );
	//dbDelta( $sql_create_section_items_table );
	dbDelta( $sql_create_reservations_table );
	dbDelta( $sql_create_capacity_table );

	//todo
   add_option( "resres_db_version", '1.0' );

/*
	if( false == get_option( 'resres_options' ) ) {
	add_option( 'resres_options' );
	}
	if( false == get_option( 'resres_form_options' ) ) {
	add_option( 'resres_form_options' );
	}
	if( false == get_option( 'resres_email_options' ) ) {
	add_option( 'resres_email_options' );
	}
*/



	}

	/**
	 * Fired for each blog when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 */
	private static function single_deactivate() {
		// @TODO: Define deactivation functionality here
		flush_rewrite_rules();
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );

	}

	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		$res_options = get_option('resres_form_options');

		wp_enqueue_style( $this->plugin_slug . '-plugin-styles', plugins_url( 'assets/css/public.css', __FILE__ ), array(), self::VERSION );

		if( !isset($res_options['disable_themeroller']) && $res_options['themeroller_select'] != "") {

			$res_options['themeroller_select'] =str_replace(' ', '-', strtolower($res_options['themeroller_select']) );

			wp_enqueue_style( $this->plugin_slug . '-themeroller', plugins_url( 'assets/js/themes/' . $res_options['themeroller_select'] . '/jquery.ui.theme.css', __FILE__ ), array(), self::VERSION );
			wp_enqueue_style( $this->plugin_slug . '-themeroller2', plugins_url( 'assets/js/themes/' . $res_options['themeroller_select'] . '/jquery-ui.min.css', __FILE__ ), array(), self::VERSION );
		}

		wp_register_style( 'resres_template_list', plugin_dir_url(__FILE__ ) . 'templates/list/list.css' );
		wp_register_style( 'resres_template_accordion', plugin_dir_url(__FILE__ ) . 'templates/accordion/accordion.css' );
		wp_register_style( 'resres_template_accordion-column', plugin_dir_url(__FILE__ ) . 'templates/accordion-column/accordion-column.css' );
		wp_register_style( 'resres_template_grid', plugin_dir_url(__FILE__ ) . 'templates/grid/grid.css' );
		wp_register_style( 'resres_template_column', plugin_dir_url(__FILE__ ) . 'templates/column/column.css' );
		wp_register_style( 'resres_template_chalkboard', plugin_dir_url(__FILE__ ) . 'templates/chalkboard/chalkboard.css' );

	}

	/**
	 * Register and enqueues public-facing JavaScript files.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_register_script( $this->plugin_slug . '-plugin-script', plugins_url( 'assets/js/public.js', __FILE__ ), array( 'jquery' ), self::VERSION );
		wp_enqueue_script( $this->plugin_slug . '-validate', plugins_url( 'assets/js/jquery.validate.min.js', __FILE__ ), array( 'jquery' ), self::VERSION );
		wp_enqueue_script( $this->plugin_slug . '-anytime', plugins_url( 'assets/js/jquery-ui-timepicker-min.js', __FILE__ ), array( 'jquery', 'jquery-ui-datepicker' ), self::VERSION );
		wp_register_script( $this->plugin_slug . '-accordion-template', plugins_url( 'templates/accordion/accordion.js', __FILE__ ), array( 'jquery' ), self::VERSION );
		wp_register_script( $this->plugin_slug . '-accordion-column-template', plugins_url( 'templates/accordion-column/accordion.js', __FILE__ ), array( 'jquery' ), self::VERSION );

		wp_register_script( $this->plugin_slug . '-gmaps', plugins_url( 'assets/js/resresgmaps.js', __FILE__ ), array( 'jquery' ), self::VERSION );

		wp_register_script( $this->plugin_slug . '-google-map', 'https://maps.googleapis.com/maps/api/js?sensor=false', array( 'jquery' ), self::VERSION );

		$options = get_option('resres_options');
		$timef = get_option('time_format');
		$datef = $this->wpdatetojs(  get_option('date_format') );

		//var_dump($options);

		if(!isset($_POST['resres_partysize']) ) { $pps = 1; } else { $pps = $_POST['resres_partysize']; }
		wp_localize_script('resres-plugin-script', 'resres_post', array(
			'post_party_size'		=>	$pps
			)
		);


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

		wp_localize_script('resres-gmaps', 'org_add', array(
			'org_add'		=>	$address_name . ',' . $address_line1 . ',' . $address_line2 . ',' . $address_city . ',' . $address_region . ',' . $address_country . ',' . $address_postalcode
			)
		);



//all this shit to stop notices.... yey.
			if( isset( $options['resres_day_of_week_mon_from_one']) && $options['resres_day_of_week_mon_from_one'] != '' ) 
	{ $m1 = $this->resres_time_convert($options['resres_day_of_week_mon_from_one']); } else { $m1 = ':'; }
if( isset( $options['resres_day_of_week_mon_to_one']) && $options['resres_day_of_week_mon_to_one'] != '' ) 
	{ $m2 = $this->resres_time_convert($options['resres_day_of_week_mon_to_one']); } else { $m2 = ':'; }
if( isset( $options['resres_day_of_week_mon_from_two']) && $options['resres_day_of_week_mon_from_two'] != '' ) 
	{ $m3 = $this->resres_time_convert($options['resres_day_of_week_mon_from_two']); } else { $m3 = ':'; }
if( isset( $options['resres_day_of_week_mon_to_two']) && $options['resres_day_of_week_mon_to_two'] != '' ) 
	{ $m4 = $this->resres_time_convert($options['resres_day_of_week_mon_to_two']); } else { $m4 = ':'; }

if( isset( $options['resres_day_of_week_tue_from_one']) && $options['resres_day_of_week_tue_from_one'] != ''  ) 
	{ $t1 = $this->resres_time_convert($options['resres_day_of_week_tue_from_one']); } else { $t1 = ''; }
if( isset( $options['resres_day_of_week_tue_to_one']) && $options['resres_day_of_week_tue_to_one'] != ''  ) 
	{ $t2 = $this->resres_time_convert($options['resres_day_of_week_tue_to_one']); } else { $t2 = ''; }
if( isset( $options['resres_day_of_week_tue_from_two']) && $options['resres_day_of_week_tue_from_two'] != ''  ) 
	{ $t3 = $this->resres_time_convert($options['resres_day_of_week_tue_from_two']); } else { $t3 = ':'; }
if( isset( $options['resres_day_of_week_tue_to_two']) && $options['resres_day_of_week_tue_to_two'] != ''  ) 
	{ $t4 = $this->resres_time_convert($options['resres_day_of_week_tue_to_two']); } else { $t4 = ''; }

if( isset( $options['resres_day_of_week_wed_from_one']) && $options['resres_day_of_week_wed_from_one'] != '' ) 
	{ $w1 = $this->resres_time_convert($options['resres_day_of_week_wed_from_one']); } else { $w1 = ':'; }
if( isset( $options['resres_day_of_week_wed_to_one']) && $options['resres_day_of_week_wed_to_one'] != '' ) 
	{ $w2 = $this->resres_time_convert($options['resres_day_of_week_wed_to_one']); } else { $w2 = ':'; }
if( isset( $options['resres_day_of_week_wed_from_two']) && $options['resres_day_of_week_wed_from_two'] != '' ) 
	{ $w3 = $this->resres_time_convert($options['resres_day_of_week_wed_from_two']); } else { $w3 = ':'; }
if( isset( $options['resres_day_of_week_wed_to_two']) && $options['resres_day_of_week_wed_to_two'] != '' ) 
	{ $w4 = $this->resres_time_convert($options['resres_day_of_week_wed_to_two']); } else { $w4 = ':'; }

if( isset( $options['resres_day_of_week_thu_from_one']) && $options['resres_day_of_week_thu_from_one'] != '' ) { 
	$th1 = $this->resres_time_convert($options['resres_day_of_week_thu_from_one']); } else { $th1 = ':'; }
if( isset( $options['resres_day_of_week_thu_to_one']) && $options['resres_day_of_week_thu_to_one'] != '' ) { 
	$th2 = $this->resres_time_convert($options['resres_day_of_week_thu_to_one']); } else { $th2 = ':'; }
if( isset( $options['resres_day_of_week_thu_from_two']) && $options['resres_day_of_week_thu_from_two'] != '' ) { 
	$th3 = $this->resres_time_convert($options['resres_day_of_week_thu_from_two']); } else { $th3 = ':'; }
if( isset( $options['resres_day_of_week_thu_to_two']) && $options['resres_day_of_week_thu_to_two'] != '' ) { 
	$th4 = $this->resres_time_convert($options['resres_day_of_week_thu_to_two']); } else { $th4 = ':'; }

if( isset( $options['resres_day_of_week_fri_from_one']) && $options['resres_day_of_week_fri_from_one'] != '' ) { 
	$f1 = $this->resres_time_convert($options['resres_day_of_week_fri_from_one']); } else { $f1 = ':'; }
if( isset( $options['resres_day_of_week_fri_to_one']) && $options['resres_day_of_week_fri_to_one'] != '' ) { 
	$f2 = $this->resres_time_convert($options['resres_day_of_week_fri_to_one']); } else { $f2 = ':'; }
if( isset( $options['resres_day_of_week_fri_from_two']) && $options['resres_day_of_week_fri_from_two'] != '' ) { 
	$f3 = $this->resres_time_convert($options['resres_day_of_week_fri_from_two']); } else { $f3 = ':'; }
if( isset( $options['resres_day_of_week_fri_to_two']) && $options['resres_day_of_week_fri_to_two'] != '' ) { 
	$f4 = $this->resres_time_convert($options['resres_day_of_week_fri_to_two']); } else { $f4 = ':'; }

if( isset( $options['resres_day_of_week_sat_from_one']) && $options['resres_day_of_week_sat_from_one'] != '' ) 
	{ $s1 = $this->resres_time_convert($options['resres_day_of_week_sat_from_one']); } else { $s1 = ':'; }
if( isset( $options['resres_day_of_week_sat_to_one']) && $options['resres_day_of_week_sat_to_one'] != '' ) 
	{ $s2 = $this->resres_time_convert($options['resres_day_of_week_sat_to_one']); } else { $s2 = ':'; }
if( isset( $options['resres_day_of_week_sat_from_two']) && $options['resres_day_of_week_sat_from_two'] != '' ) 
	{ $s3 = $this->resres_time_convert($options['resres_day_of_week_sat_from_two']); } else { $s3 = ':'; }
if( isset( $options['resres_day_of_week_sat_to_two']) && $options['resres_day_of_week_sat_to_two'] != '' ) 
	{ $s4 = $this->resres_time_convert($options['resres_day_of_week_sat_to_two']); } else { $s4 = ':'; }

if( isset( $options['resres_day_of_week_sun_from_one']) && $options['resres_day_of_week_sun_from_one'] != '' ) { 
	$su1 = $this->resres_time_convert($options['resres_day_of_week_sun_from_one']); } else { $su1 = ':'; }
if( isset( $options['resres_day_of_week_sun_to_one']) && $options['resres_day_of_week_sun_to_one'] != '' ) { 
	$su2 = $this->resres_time_convert($options['resres_day_of_week_sun_to_one']); } else { $su2 = ':'; }
if( isset( $options['resres_day_of_week_sun_from_two']) && $options['resres_day_of_week_sun_from_two'] != '' ) { 
	$su3 = $this->resres_time_convert($options['resres_day_of_week_sun_from_two']); } else { $su3 = ':'; }
if( isset( $options['resres_day_of_week_sun_to_two']) && $options['resres_day_of_week_sun_to_two'] != '' ) { 
	$su4 = $this->resres_time_convert($options['resres_day_of_week_sun_to_two']); } else { $su4 = ':'; }


	if( isset($options['resres_overrides']) ) { $override_options = $options['resres_overrides']; } else { $override_options = ''; }
	if( isset($override_options) ) { $dis_dates = str_replace(array("\r\n", "\r"), "\n", $override_options); } else { $dis_dates = ''; }

    $overrides = explode("\n", $dis_dates);
    $overrides = array_filter($overrides);


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
			
			'max_capacity' 			=> 	isset($options['resres_max_hourly_capacity']),
			
			'overrides' 			=> $overrides,
			
			'recaptcha_style'		=>	$recaptcha_style,
			
			'dayNames'				=>	array( __('Sunday'), __('Monday'), __('Tuesday'), __('Wednesday'), __('Thursday'), __('Friday'), __('Saturday') ),
			'dayNamesShort'			=>	array( __('Sun'), __('Mon'), __('Tue'), __('Wed'), __('Thu'), __('Fri'), __('Sat') ),
			'dayNamesMin'			=>	array( __('Su'), __('Mo'), __('Tu'), __('We'), __('Th'), __('Fr'), __('Sa') ),
			'monthNames'			=>	array( __('January'), __('February'), __('March'), __('April'), __('May'), __('June'), __('July'), __('August'), __('September'), __('October'), __('November'), __('December') ),
			'monthNamesShort'		=>	array( __('Jan'), __('Feb'), __('Mar'), __('Apr'), __('May'), __('Jun'), __('Jul'), __('Aug'), __('Sep'), __('Oct'), __('Nov'), __('Dec') ),
			'nextText'				=>	__('Next'),
			'prevText'				=>	__('Prev'),

			)
		);
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

	if( empty($time) ) { return  '00:00'; }

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
	 * NOTE:  Actions are points in the execution of a page or process
	 *        lifecycle that WordPress fires.
	 *
	 *        Actions:    http://codex.wordpress.org/Plugin_API#Actions
	 *        Reference:  http://codex.wordpress.org/Plugin_API/Action_Reference
	 *
	 * @since    1.0.0
	 */
	public function action_method_name() {
		// @TODO: Define your action hook callback here
	}

	/**
	 * NOTE:  Filters are points of execution in which WordPress modifies data
	 *        before saving it or sending it to the browser.
	 *
	 *        Filters: http://codex.wordpress.org/Plugin_API#Filters
	 *        Reference:  http://codex.wordpress.org/Plugin_API/Filter_Reference
	 *
	 * @since    1.0.0
	 */
	public function filter_method_name() {
		// @TODO: Define your filter hook callback here
	}



	public function resres_captcha() {
		$cap['cap1'] = rand(1, 10);
		$cap['cap2'] = rand(1, 10);
		$cap['capt'] = $cap['cap1'] + $cap['cap2'];

		return $cap;
	}

	public function resres_get_form_options() {
		$res_form_options = get_option('resres_form_options');

			if($res_form_options['party_size'] == 0 || $res_form_options['party_size'] == '') {
				$res_form_options['party_size'] = 25;
			}

		return $res_form_options;
	}


	public function resres_registration_form_shortcode() {

		wp_enqueue_script('jquery-ui-core', 'jQuery');
		wp_enqueue_script('jquery-ui-slider', 'jQuery');
		wp_enqueue_script('jquery-ui-datepicker', 'jQuery');

		$res_form_options = get_option('resres_form_options' );

		$cap = $this->resres_captcha();

		wp_enqueue_script( 'resres-plugin-script' );

	   	global $wpdb;

		$table_name_menu = $wpdb->prefix . "resres_menus";
		$table_name_menu_items = $wpdb->prefix . "resres_menu_items";
	   	$table_name_reservations = $wpdb->prefix . "resres_reservations";

	   	$idprefix = "res-";

		if(isset($_POST['resres_submit'])) {
			//echo "<pre>" . print_r($_POST, 1) . "</pre>";

			$the_form_data = $_POST;

			//basic check to confirm form fields were filled out, JS will override this. This also returns the error message but wipes the form fields...
			if(empty($the_form_data['resres_name']) || empty($the_form_data['resres_phone']) || empty($the_form_data['resres_email']) || empty($the_form_data['resres_date']) || empty($the_form_data['resres_time']) || empty($the_form_data['resres_partysize']) ) {
				echo "Not all fields were filled in correctly. Please check the form before re-submitting.";
				return false;
			}

			//let's check the capacity first
			$capacity_full = $this->resres_capacity_check();



			if($capacity_full == 1) {
				ob_start();
				

				echo __('<div class="res_clearmargin"><p class="resres_error_message">Sorry, the restuarant is fully booked for that time. Please choose another time and resubmit the form.</p></div>');
				include_once('views/public.php');
				
				return false;				
			}


			if(!$res_form_options['disable_resres_captcha']) {
				if($the_form_data['wibble'] != $the_form_data['resres_captcha']) {
					ob_start();

					echo __('<div class="res_clearmargin"><p class="resres_error_message">The captcha was not entered correctly. Please try it again.</p></div>');

					include_once('views/public.php');

				return false;
				} else {
					$this->resres_reset_form($the_form_data);
					ob_start();
					echo '<div class="res_clearmargin"><p class="resres_success_message">Your reservation has been submitted.</p></div>';
					//include_once('views/public.php');

				}
			}

			if( isset($res_form_options['enable_recaptcha']) ) {
				require_once( WP_PLUGIN_DIR . '/resres/public/includes/recaptchalib.php' );
				$privatekey = $res_form_options['recaptcha_private'];
				$resp = recaptcha_check_answer ($privatekey,
				                            $_SERVER["REMOTE_ADDR"],
				                            $_POST["recaptcha_challenge_field"],
				                            $_POST["recaptcha_response_field"]);

				if (!$resp->is_valid) {
				// What happens when the CAPTCHA was entered incorrectly
				echo __('<div class="res_clearmargin"><p class="resres_error_message">The reCAPTCHA was not entered correctly. Please try it again.</p></div>');
				return false;
				} else {
					//$this->resres_reset_form();
					echo '<div class="res_clearmargin"><p class="resres_success_message">Your reservation has been submitted.</p></div>';
				}
			}


			$uid = uniqid($idprefix);

			$the_form_data['reservation_id'] = $uid;

			$wpdb->resres_reservations = "{$wpdb->prefix}resres_reservations";

			$wpdb->insert($table_name_reservations,
				array(
					"reservation_id"   => $uid,
					"is_active"        => 1,
					"date_created"     => date('Y-m-d'),
					"time_created"     => date('H:i:s'),
					"res_date"         => date('Y-m-d', strtotime($the_form_data['resres_date'])),
					"res_time"         => date('H:i:s', strtotime($the_form_data['resres_time']) ),
					"partysize"        => $the_form_data['resres_partysize'],
					"name"             => $the_form_data['resres_name'],
					"phone"            => $the_form_data['resres_phone'],
					"email"            => $the_form_data['resres_email'],
					"menu"             => '', //$the_form_data['xxx'],
					"notes"            => $the_form_data['resres_notes'],
					"table_id"         => '', //$the_form_data['xxx'],
					"table_section_id" => '', //$the_form_data['xxx'],
					"wine"             => '', //$the_form_data['xxx'],
					"deposit"          => '', //$the_form_data['xxx'],
					"deposit_amount"   => '', //$the_form_data['xxx'],
					"paid"             => '', //$the_form_data['xxx'],
					"paid_amount"      => '', //$the_form_data['xxx'],
					"payment_type"     => '' //$the_form_data['xxx']

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
					//"%s", // menu
					"%s", // notes
					//"%d", // table id
					//"%d", // table section id
					//"%s", // wine
					//"%d", // dep
					//"%d", // dep amt
					//"%d", // paid
					//"%d", // paid amt
					//"%s" // pay type
					));


		if( !isset( $res_form_options['admin_emails']) ) {
			$this->resres_send_admin_email($the_form_data);
		}

		if( !isset( $res_form_options['registration_emails'] ) ) {
			$this->resres_send_registration_email($the_form_data);
		}

		$this->resres_reset_form();



		} //end if isset

		ob_start();

		if(isset($the_form_data['resres_submit']) && isset( $res_form_options['disable_resres_captcha'] )  && !isset( $res_form_options['enable_recaptcha']) ) {
			echo '<div class="res_clearmargin"><p class="resres_success_message">Your reservation has been submitted.</p></div>';
			include_once('views/public.php');
		}
		include_once('views/public.php');


		return ob_get_clean();
	}


	public function resres_capacity_check() {
		$data = $_POST;
		global $wpdb;

	   	$table_name = $wpdb->prefix . "resres_capacity";

		$options = get_option( 'resres_options' );
		$max_capacity = $options['resres_max_hourly_capacity'];

		if( $max_capacity == '' ) { $max_capacity = 99999; }

		$reservation_party_size = $data['resres_partysize'];
		$reservation_time = $data['resres_time'];


		$reservation_date = date('Y-m-d', strtotime( $data['resres_date'] ) );

		$sql = $wpdb->get_row("SELECT * FROM $table_name WHERE capacity_date = '$reservation_date'");

		if( !isset($sql) ) {

			//check capacity isn't bigger than max allowed
			if($reservation_party_size <= $max_capacity) {

				if($reservation_party_size == $max_capacity) { $capacity_full = 1; } else { $capacity_full = 0; }

				$number_reserved = array(
					$reservation_time => $reservation_party_size
					);

				$number_reserved = serialize($number_reserved);

				$wpdb->insert($table_name, 
					array("capacity_date" => $reservation_date, "capacity_full" => $capacity_full, "capacity_number_reserved" => $number_reserved), 
					array("%s", "%d", "%s")
					);
			}
		}
		else {

			if( $sql->capacity_full == 1 ) { 
				//lets do a quick check to make sure that the max capacity hasn't changed
				$current_capacity = $the_times[$reservation_time];	
				if($max_capacity > $current_capacity) {
				}
				else {
					$capacity_full = 1; return $capacity_full;
				}
			}

			$the_times = unserialize($sql->capacity_number_reserved);

			if( array_key_exists( $reservation_time, $the_times) ) {

				$current_capacity = $the_times[$reservation_time];

				$new_capacity = $current_capacity + $reservation_party_size;

				$capacity_full = '';

				if($new_capacity > $max_capacity) { $capacity_full = 1; return $capacity_full; }
				else { $the_times[$reservation_time] = $new_capacity; }

				$the_times = serialize($the_times);

				if($new_capacity == $max_capacity) { $capacity_full = 1; } else { $capacity_full = 0; }
			
			} else {
				$the_times[$reservation_time] = $reservation_party_size;
				$the_times = serialize($the_times);
			}

			$wpdb->update( $table_name, array("capacity_number_reserved" => $the_times, "capacity_full" => $capacity_full), array("capacity_date" => $reservation_date), array("%s", "%d"), array("%s") );

		}

		return $capacity_full;

	}


	public function resres_reset_form() {
		$the_form_data['resres_date'] = '';
		$the_form_data['resres_time'] = '';
		$the_form_data['resres_partysize'] = '';
		$the_form_data['resres_name'] = '';
		$the_form_data['resres_phone'] = '';
		$the_form_data['resres_email'] = '';
		$the_form_data['resres_notes'] = '';

	return $the_form_data;
	}

	public function resres_send_admin_email($form_data) {

		$base_url = parse_url( get_option( 'home' ) );

		$options = get_option( 'resres_options' );
		$email_options = get_option( 'resres_email_options' );

		$date_format = get_option('date_format');
		$time_format = get_option('time_format');

		if($email_options['admin_emails']) { return false; }

		if($email_options['registration_emails_admin_to'] == "{admin_email}") {
			$to = get_option('admin_email' );
		}
		else {
			$to = $email_options['registration_emails_admin_to'];
		}


		$headers[] = 'From: '. get_option('blogname') . ' <noreply@' . $base_url['host'] . '>';
		$headers[] = "Cc: " . $email_options['registration_emails_admin_cc'];

if($email_options['resres_disable_html_email'] != 1) {
	$headers[] = "Content-type: text/html";
}

		$final_message = $email_options['registration_emails_admin_message'];
		$final_message = str_replace('{reservation_id}', $form_data['reservation_id'], $final_message);

		$final_message = str_replace('{customer_name}', $form_data['resres_name'], $final_message);
		$final_message = str_replace('{customer_phone}', $form_data['resres_phone'], $final_message);
		$final_message = str_replace('{customer_email}', $form_data['resres_email'], $final_message);
		$final_message = str_replace('{reservation_date}', $form_data['resres_date'], $final_message); //removed date fromat here as should be in WP format already
		$final_message = str_replace('{reservation_time}', $form_data['resres_time'], $final_message); //removed date fromat here as should be in WP format already
		$final_message = str_replace('{party_size}', $form_data['resres_partysize'], $final_message);
		$final_message = str_replace('{reservation_notes}', $form_data['resres_notes'], $final_message);

		$final_message = str_replace('{restaurant_name}', $options['address_name'], $final_message);
		$final_message = str_replace('{restaurant_add}', $options['address_line1'], $final_message);
		$final_message = str_replace('{restaurant_add2}', $options['address_line2'], $final_message);
		$final_message = str_replace('{restaurant_city}', $options['address_city'], $final_message);
		$final_message = str_replace('{restaurant_region}', $options['address_region'], $final_message);
		$final_message = str_replace('{restaurant_city}', $options['address_country'], $final_message);
		$final_message = str_replace('{restaurant_postalcode}', $options['address_postalcode'], $final_message);
		$final_message = str_replace('{restaurant_phone}', $options['address_phone'], $final_message);
		$final_message = str_replace('{restaurant_fax}', $options['address_fax'], $final_message);
		$final_message = str_replace('{restaurant_email}', $options['address_email'], $final_message);
		$final_message = str_replace('{restaurant_facebook}', '<a href="' . $options['address_facebook'] . '">Facebook</a>', $final_message);
		$final_message = str_replace('{restaurant_twitter}', '<a href="https://twitter.com/' . $options['address_twitter'] . '">@' . $options['address_twitter'] . '</a>', $final_message);
		$final_message = str_replace('{restaurant_googleplus}', '<a href="https://plus.google.com/u/0/' . $options['address_googleplus'] . '">Google+</a>', $final_message);

		$to 			= $to;
		$subject 		= $email_options['registration_emails_admin_subject'];
		$message 		= $final_message;
		$headers 		= $headers;
		//$attachments	= '';


		wp_mail( $to, $subject, $message, $headers, $attachments );


	}

	// customer email
	public function resres_send_registration_email($form_data) {

		//var_dump($form_data);

		$base_url = parse_url( get_option( 'home' ) );

		$options = get_option( 'resres_options' );
		$email_options = get_option( 'resres_email_options' );

		$date_format = get_option('date_format');
		$time_format = get_option('time_format');

		if($email_options['registration_emails']) { return false; }


		$headers[] = 'From: '. get_option('blogname') . ' <noreply@' . $base_url['host'] . '>';
		$headers[] = "Cc: " . $email_options['registration_emails_registrant_cc'];

if($email_options['resres_disable_html_email'] != 1) {
	$headers[] = "Content-type: text/html";
}

		$final_message = $email_options['registration_emails_registrant_message'];

		$final_message = str_replace('{reservation_id}', $form_data['reservation_id'], $final_message);

		$final_message = str_replace('{customer_name}', $form_data['resres_name'], $final_message);
		$final_message = str_replace('{customer_phone}', $form_data['resres_phone'], $final_message);
		$final_message = str_replace('{customer_email}', $form_data['resres_email'], $final_message);
		$final_message = str_replace('{reservation_date}', $form_data['resres_date'], $final_message); //removed date fromat here as should be in WP format already
		$final_message = str_replace('{reservation_time}', $form_data['resres_time'], $final_message); //removed date fromat here as should be in WP format already
		$final_message = str_replace('{party_size}', $form_data['resres_partysize'], $final_message);
		$final_message = str_replace('{reservation_notes}', $form_data['resres_notes'], $final_message);

		$final_message = str_replace('{restaurant_name}', $options['address_name'], $final_message);
		$final_message = str_replace('{restaurant_add}', $options['address_line1'], $final_message);
		$final_message = str_replace('{restaurant_add2}', $options['address_line2'], $final_message);
		$final_message = str_replace('{restaurant_city}', $options['address_city'], $final_message);
		$final_message = str_replace('{restaurant_region}', $options['address_region'], $final_message);
		$final_message = str_replace('{restaurant_city}', $options['address_country'], $final_message);
		$final_message = str_replace('{restaurant_postalcode}', $options['address_postalcode'], $final_message);
		$final_message = str_replace('{restaurant_phone}', $options['address_phone'], $final_message);
		$final_message = str_replace('{restaurant_fax}', $options['address_fax'], $final_message);
		$final_message = str_replace('{restaurant_email}', $options['address_email'], $final_message);
		$final_message = str_replace('{restaurant_facebook}', '<a href="' . $options['address_facebook'] . '">Facebook</a>', $final_message);
		$final_message = str_replace('{restaurant_twitter}', '<a href="https://twitter.com/' . $options['address_twitter'] . '">@' . $options['address_twitter'] . '</a>', $final_message);
		$final_message = str_replace('{restaurant_googleplus}', '<a href="https://plus.google.com/u/0/' . $options['address_googleplus'] . '">Google+</a>', $final_message);

		$to 			= $form_data['resres_email'];
		$subject 		= $email_options['registration_emails_registrant_subject'];
		$subject 		= str_replace('{restaurant_name}', $options['address_name'], $subject);
		$message 		= $final_message;
		$headers 		= $headers;
		//$attachments	= '';

		wp_mail( $to, $subject, $message, $headers, $attachments );

	}



/*
*
* MENU Shortcode
*
* params:
*			template="accordion" //uses the templates slug
*			sections="1,2,4" //uses the sections ID to display specific sections. Can also use section slugs
*			exclude="1,2,3" //uses section id's to exclude sections froma  menu.
*
*
****************************************************************/
	public function resres_menu_shortcode($atts) {


		//make sure there are sections.
		$check_if_sections_exist = get_terms('menu_sections');
		if(empty($check_if_sections_exist)) { echo __('No sections created yet.'); return; }


		extract( shortcode_atts( array(), $atts ) );

		if( isset($atts['sections']) ) {
			$sections_array = array_map('trim', explode(',', $atts['sections']));
		}

		if( isset($atts['exclude']) ) {
			$exclude_array = array_map('trim', explode(',', $atts['exclude']));
		}


		$options = get_option( 'resres_options' );
		if( isset($options['template_type']) ) { $template_type =  $options['template_type']; } else { $template_type = "column"; }

		if( isset($atts['template']) ) {
			$template_type = (string)$atts['template'];
		}

		do_action('resres_check_menu_shortcode', $template_type );


		if(isset($options['resres_template_colour'])) {
			$resres_colour = "background-color:" . $options['resres_template_colour'] . ";";
		}
		else {
			$resres_colour = '';
		}
		if(isset($options['resres_template_font_colour'])) {
			$resres_font_colour = " color:" . $options['resres_template_font_colour'] . ";";
		}
		else {
			$resres_font_colour = '';
		}


		$csymbol = '';


		if(isset($options['currency_symbol'])) { $csymbol = $options['currency_symbol'];}

		if(file_exists((plugin_dir_path(__FILE__) . 'templates/' . $template_type . '/' . $template_type . '.php'))) {
				wp_enqueue_style( 'resres_template_' . $template_type );
				wp_enqueue_script( 'resres-' . $template_type . '-template' );

				ob_start();
				include( plugin_dir_path(__FILE__) . 'templates/' . $template_type . '/' . $template_type . '.php' );
		}
		else {
				wp_enqueue_style( 'resres_template_column' );
				wp_enqueue_script( 'resres-column-template' );
				ob_start();
				include( plugin_dir_path(__FILE__) . 'templates/column/column.php' );
		}

		return ob_get_clean();

	}


	public function resres_cancellation() {

		ob_start();
		include( plugin_dir_path(__FILE__) . 'views/cancel.php' );

		$resres_cancel_html = ob_get_clean();

		if( isset($_POST['resres_cancel_submit']) ) {

		 	if ( !wp_verify_nonce( $_REQUEST['_wpnonce'], "resres_cancel")) {
		      exit("No naughty business please");
		   	}   

			global $wpdb;

			$res_id = sanitize_text_field( $_POST['resres_cancel'] );
		   	$table_name = $wpdb->prefix . "resres_reservations";
			$sql = $wpdb->get_row("SELECT * FROM $table_name WHERE reservation_id = '$res_id'");

			if( !isset($sql) ) { 		include( plugin_dir_path(__FILE__) . 'views/cancel.php' ); echo '<p class="resres_error_message">' . __('Reservation not found. Please check the ID and try again.') . '</p>'; return; }
			else {
				$wpdb->update( $table_name, array("is_active" => 0 ), array("reservation_id" => $res_id), array("%d"), array("%s") );
				echo '<p class="resres_error_message">' . __('Reservation cancelled.') . '</p>';
			}

			resres_cancel_emails($sql);
		}

		return $resres_cancel_html;
	}


	public function resres_cancel_emails($res_details) {

		$base_url = parse_url( get_option( 'home' ) );

		$options = get_option( 'resres_options' );
		$email_options = get_option( 'resres_email_options' );

		$date_format = get_option('date_format');
		$time_format = get_option('time_format');

		if($email_options['registration_emails']) { return false; }

		$headers[] = 'From: '. get_option('blogname') . ' <noreply@' . $base_url['host'] . '>';
		$headers[] = "Cc: " . $email_options['registration_emails_registrant_cc'];

		if($email_options['resres_disable_html_email'] != 1) {
			$headers[] = "Content-type: text/html";
		}

		if($email_options['registration_emails_admin_to'] == "{admin_email}") {
			$admin_to = get_option('admin_email' );
		}
		else {
			$admin_to = $email_options['registration_emails_admin_to'];
		}

		$admin_message = $email_options['registration_emails_cancel_admin_message'];

		$admin_message = str_replace('{reservation_id}', $form_data['reservation_id'], $admin_message);

		$admin_message = str_replace('{restaurant_name}', $options['address_name'], $admin_message);
		$admin_message = str_replace('{restaurant_add}', $options['address_line1'], $admin_message);
		$admin_message = str_replace('{restaurant_add2}', $options['address_line2'], $admin_message);
		$admin_message = str_replace('{restaurant_city}', $options['address_city'], $admin_message);
		$admin_message = str_replace('{restaurant_region}', $options['address_region'], $admin_message);
		$admin_message = str_replace('{restaurant_city}', $options['address_country'], $admin_message);
		$admin_message = str_replace('{restaurant_postalcode}', $options['address_postalcode'], $admin_message);
		$admin_message = str_replace('{restaurant_phone}', $options['address_phone'], $admin_message);
		$admin_message = str_replace('{restaurant_fax}', $options['address_fax'], $admin_message);
		$admin_message = str_replace('{restaurant_email}', $options['address_email'], $admin_message);
		$admin_message = str_replace('{restaurant_facebook}', '<a href="' . $options['address_facebook'] . '">Facebook</a>', $admin_message);
		$admin_message = str_replace('{restaurant_twitter}', '<a href="https://twitter.com/' . $options['address_twitter'] . '">@' . $options['address_twitter'] . '</a>', $admin_message);
		$admin_message = str_replace('{restaurant_googleplus}', '<a href="https://plus.google.com/u/0/' . $options['address_googleplus'] . '">Google+</a>', $admin_message);


		$customer_message = $email_options['registration_emails_cancel_registrant_message'];

		$customer_message = str_replace('{reservation_id}', $form_data['reservation_id'], $customer_message);

		$customer_message = str_replace('{restaurant_name}', $options['address_name'], $customer_message);
		$customer_message = str_replace('{restaurant_add}', $options['address_line1'], $customer_message);
		$customer_message = str_replace('{restaurant_add2}', $options['address_line2'], $customer_message);
		$customer_message = str_replace('{restaurant_city}', $options['address_city'], $customer_message);
		$customer_message = str_replace('{restaurant_region}', $options['address_region'], $customer_message);
		$customer_message = str_replace('{restaurant_city}', $options['address_country'], $customer_message);
		$customer_message = str_replace('{restaurant_postalcode}', $options['address_postalcode'], $customer_message);
		$customer_message = str_replace('{restaurant_phone}', $options['address_phone'], $customer_message);
		$customer_message = str_replace('{restaurant_fax}', $options['address_fax'], $customer_message);
		$customer_message = str_replace('{restaurant_email}', $options['address_email'], $customer_message);
		$customer_message = str_replace('{restaurant_facebook}', '<a href="' . $options['address_facebook'] . '">Facebook</a>', $customer_message);
		$customer_message = str_replace('{restaurant_twitter}', '<a href="https://twitter.com/' . $options['address_twitter'] . '">@' . $options['address_twitter'] . '</a>', $customer_message);
		$customer_message = str_replace('{restaurant_googleplus}', '<a href="https://plus.google.com/u/0/' . $options['address_googleplus'] . '">Google+</a>', $customer_message);

		$admin_to 			= $admin_to;
		$admin_subject 		= $email_options['registration_emails_cancel_admin_subject'];
		$admin_subject 		= str_replace('{restaurant_name}', $options['address_name'], $subject);

		$customer_to 			= $res_details['email'];
		$customer_subject 		= $email_options['registration_emails_cancel_registrant_subject'];
		$customer_subject 		= str_replace('{restaurant_name}', $options['address_name'], $subject);

		$headers 		= $headers;

		wp_mail( $admin_to, $admin_subject, $admin_message, $headers, $attachments );
		wp_mail( $customer_to, $customer_subject, $customer_message, $headers, $attachments );

	}


	/*
	*
	* Opening Times and Address Shortcode
	*
	*
	****************************************/

	public function resres_opening_times($atts) {

		wp_enqueue_script( 'resres-gmaps' );
		wp_enqueue_script( 'resres-google-map' );

		extract( shortcode_atts( array(), $atts ) );

		$options = get_option( 'resres_options' );

		$time_format = get_option('time_format' );

		include_once('includes/address_and_opening_times.php');

		$resres_times = ob_get_clean();

		return $resres_times;
	}



	public function resres_filter_single_dish_title($title) {

		if(!isset($id)) { $id = ''; }

	    if ( in_the_loop() && 'dish' == get_post_type($id)  && is_single() ) {


	    	$dish_meta = get_post_custom( get_the_ID() );

			$options = get_option( 'resres_options' );
			$csymbol = '';

			if(isset($options['currency_symbol'])) { $csymbol = $options['currency_symbol'];}


	        $title = $title . '<span class="resres_template_price" style="float:right">' . $csymbol . $dish_meta['resres_dish_price'][0] . '</span>';
	    }
	return $title;
	}


	public function resres_filter_single_dish_content($content) {
		if(!isset($id)) { $id = ''; }

		if ( 'dish' == get_post_type($id) && is_single() ) {

			$dish_meta = get_post_custom( get_the_ID() );

		$x = '<div class="resres_dish_meta">';
		foreach ($dish_meta as $key => $value) {

			if(substr( $key, 0, 6 ) !== "resres" || $key == "resres_dish_price") { continue; }

			if($key == "resres_chili") { $i = $value; continue; }

			$x .= '<span class="resres_dish_meta_single">';
			$x .= str_replace("_", ' ', $value[0]) . __(" ");
			$x .= "</span>";
		}

			if(!isset($i[0])) {}
				else {
					$countup = 0;
					while ($countup < $i[0]) {
						$x .= '<img class="resres_small_chili" src="' . plugins_url( 'assets/css/images/chili.png', __FILE__) . '" />';
						$countup++;
					}

				}

			$x .= '</div>';

		    $content = $content . $x;
	    }
	  return $content;
	}


	/*
	*
	* ALLERGENS
	*
	********************/
	public function resres_display_meta($ind_post_meta, $resres_font_colour, $resres_colour) {

		$options = get_option('resres_options');

		echo '<div class="resres_meta_block">';
		foreach ($ind_post_meta as $key => $value) {
			if( $key == "resres_dish_price" ) { continue; } 
			elseif ( substr( $key, 0, 11 ) == "resres_dish" || substr( $key, 0, 11 ) == "resres_misc" ) {  } 
			else { continue; }


			$allergenclass = '';
			if(isset($options['resres_enable_allergen_images'])) { $allergenclass = "allergen_image " . $value[0]; }
			if(isset($options['resres_enable_allergen_images']) && isset($options['resres_enable_allergen_images_white'])) { $allergenclass = "allergen_image_white " . $value[0]; }
			$allergenclass = strtolower($allergenclass);

			
			if(isset($options['resres_enable_allergen_images']) || isset($options['resres_enable_allergen_images_white']) ) { 
				echo '<span class="allergen_image_font_' . strtolower($value[0]) . ' ' . $allergenclass . '" title="' . str_replace("_", ' ', $value[0]) . __(" ") . '"></span>'; 
			} else { 

				echo '<span class="resres_dish_meta_single allergen_image_' . strtolower($value[0]) . ' ' . $allergenclass . '" style="'. $resres_colour . $resres_font_colour . '" title="' . str_replace("_", ' ', $value[0]) . __(" ") . '">' . str_replace("_", ' ', $value[0]) . __(" ") . '</span>'; }


		}
		echo '</div>';

		return;
	}


	/*
	*
	* CHILI
	*
	********************/
	public function resres_display_chili($ind_post_meta, $resres_font_colour, $resres_colour) {
		
		$options = get_option('resres_options');

		$chili_count = get_post_meta( get_the_id(), 'resres_chili', true );

		$i = 0;

		echo '<div class="resres_meta_block">';

		echo '<ul class="resres_chili">';

		while ($i < $chili_count) {
			echo '<li><span class="allergen_image_font_spicy allergen_image " title="' . __('Spicy hot!') . '"></span></li>';
			$i++;
		}

		echo '</ul>';

		echo '</div>';


		return;
	}


	/*
	*
	* WINE
	*
	********************/
	public function resres_display_wine($ind_post_meta, $resres_font_colour, $resres_colour) {
		
		$options = get_option('resres_options');

		echo '<div class="resres_meta_block">';
		foreach ($ind_post_meta as $key => $value) {
			if(substr( $key, 0, 11 ) !== "resres_wine" || $key == "resres_dish_price") { continue; }


			echo '<span class="resres_dish_meta_single resres_dish_wine" style="'. $resres_colour . $resres_font_colour . '" title="' . str_replace("_", ' ', $value[0]) . __(" ") . '">';
			echo str_replace("_", ' ', $value[0]) . __(" ");
			echo "</span>";
		}
		echo '</div>';

		return;
	}


	/*
	*
	* MISC
	*
	********************/
	public function resres_display_misc($ind_post_meta, $resres_font_colour, $resres_colour) {
		
		$options = get_option('resres_options');

		echo '<div class="resres_meta_block">';
		foreach ($ind_post_meta as $key => $value) {
			if(substr( $key, 0, 6 ) !== "resres" || $key == "resres_dish_price") { continue; }

			$allergenclass = '';
			if($options['resres_enable_allergen_images']) { $allergenclass = "allergen_image " . $value[0]; }
			if($options['resres_enable_allergen_images'] && $options['resres_enable_allergen_images_white']) { $allergenclass = "allergen_image_white " . $value[0]; }
			$allergenclass = strtolower($allergenclass);

			echo '<span class="resres_dish_meta_single ' . $allergenclass . '" style="'. $resres_colour . $resres_font_colour . '" title="' . str_replace("_", ' ', $value[0]) . __(" ") . '">';
			echo str_replace("_", ' ', $value[0]) . __(" ");
			echo "</span>";
		}
		echo '</div>';

		return;
	}

}
