<?php
/*
Plugin Name: General Bootstrap
plugin URI: http://wpdevadvice.com/wordpress-general-plugin/
Description: The main reason I have created this WordPress general plugin repository is to give you, the developer, a base to work off of.
version: 1.0
Author: Kyle Benk
Author URI: http://kylebenkapps.com
License: GPL2
*/

/**
 * Global Definitions
 */

/* Plugin Name */

if (!defined('GENERAL_BOOTSTRAP_PLUGIN_NAME'))
    define('GENERAL_BOOTSTRAP_PLUGIN_NAME', trim(dirname(plugin_basename(__FILE__)), '/'));

/* Plugin directory */

if (!defined('GENERAL_BOOTSTRAP_PLUGIN_DIR'))
    define('GENERAL_BOOTSTRAP_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . GENERAL_BOOTSTRAP_PLUGIN_NAME);

/* Plugin url */

if (!defined('GENERAL_BOOTSTRAP_PLUGIN_URL'))
    define('GENERAL_BOOTSTRAP_PLUGIN_URL', WP_PLUGIN_URL . '/' . GENERAL_BOOTSTRAP_PLUGIN_NAME);

/* Plugin verison */

if (!defined('GENERAL_BOOTSTRAP_VERSION_NUM'))
    define('GENERAL_BOOTSTRAP_VERSION_NUM', '1.0.0');


/**
 * Activatation / Deactivation
 */

register_activation_hook( __FILE__, array('General_Bootstrap', 'register_activation'));

/**
 * Hooks / Filter
 */

add_action('init', array('General_Bootstrap', 'load_textdomain'));
add_action('admin_menu', array('General_Bootstrap', 'menu_page'));

$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", array('General_Bootstrap', 'plugin_links'));

add_action('the_content', array('General_Bootstrap', 'display_data'), 10, 1);

// Include all the helper libraries

require_once('includes/data-manager/autoload.php');
require_once('includes/display-conditions/autoload.php');
require_once('includes/stats-tracker/autoload.php');
require_once('includes/newsletter-integrations/autoload.php');

/**
 *  General_Bootstrap main class
 *
 * @since 1.0.0
 * @using Wordpress 3.8
 */

class General_Bootstrap {

	/**
	 * text_domain
	 *
	 * (default value: 'general-bootstrap')
	 *
	 * @var string
	 * @access private
	 * @static
	 */
	public static $text_domain = 'general-bootstrap';

	/**
	 * prefix
	 *
	 * (default value: 'general_bootstrap_')
	 *
	 * @var string
	 * @access private
	 * @static
	 */
	public static $prefix = 'general_bootstrap_';

	/**
	 * prefix_dash
	 *
	 * (default value: 'gen-bts-')
	 *
	 * @var string
	 * @access public
	 * @static
	 */
	public static $prefix_dash = 'gen-bts-';

	/**
	 * settings_page
	 *
	 * (default value: 'general-admin-menu-settings')
	 *
	 * @var string
	 * @access private
	 * @static
	 */
	public static $settings_page = 'general-bootstrap-admin-menu-settings';

	/**
	 * dashboard_page
	 *
	 * (default value: 'general-bootstrap-data-manager')
	 *
	 * @var string
	 * @access public
	 * @static
	 */
	public static $dashboard_page = 'general-bootstrap-data-manager';

	/**
	 * add_edit_page
	 *
	 * (default value: 'general-bootstrap-admin-menu-settings')
	 *
	 * @var string
	 * @access public
	 * @static
	 */
	public static $add_edit_page = 'general-bootstrap-admin-menu-settings';

	/**
	 * stats_page
	 *
	 * (default value: 'general-bootstrap-stats')
	 *
	 * @var string
	 * @access public
	 * @static
	 */
	public static $stats_page = 'general-bootstrap-stats';

	/**
	 * data_manager_page
	 *
	 * (default value: 'general-bootstrap-data-manager')
	 *
	 * @var string
	 * @access public
	 * @static
	 */
	public static $data_manager_page = 'general-bootstrap-data-manager';

	/**
	 * tabs_settings_page
	 *
	 * (default value: 'general-admin-menu-tab-settings')
	 *
	 * @var string
	 * @access private
	 * @static
	 */
	public static $tabs_settings_page = 'general-bootstrap-admin-menu-tab-settings';

	/**
	 * email_list_page
	 *
	 * (default value: 'general-bootstrap-email-list')
	 *
	 * @var string
	 * @access public
	 * @static
	 */
	public static $email_list_page = 'general-bootstrap-email-list';

	/**
	 * usage_page
	 *
	 * (default value: 'general-admin-menu-usage')
	 *
	 * @var string
	 * @access private
	 * @static
	 */
	public static $usage_page = 'general-bootstrap-admin-menu-usage';

	/**
	 * data_manager_table_name
	 *
	 * (default value: 'data_manager_v1')
	 *
	 * @var string
	 * @access public
	 * @static
	 */
	public static $data_manager_table_name = 'data_manager_v1';

	/**
	 * stats_table_name
	 *
	 * (default value: 'data_manager_v1')
	 *
	 * @var string
	 * @access public
	 * @static
	 */
	public static $stats_table_name = 'stats_tracker_v1';

	/**
	 * newsletter_table_name
	 *
	 * (default value: 'newsletter_submissions_v1')
	 *
	 * @var string
	 * @access public
	 * @static
	 */
	public static $newsletter_table_name = 'newsletter_submissions_v1';

	/**
	 * default
	 *
	 * @var mixed
	 * @access private
	 * @static
	 */
	private static $default = array(
		'text'		=> 'text',
		'textarea'	=> 'textarea',
		'checkbox'	=> true,
		'select'	=> 'medium',
		'radio'		=> 'start',
		'url'		=> 'kylebenkapps.com'
	);

	/**
	 * Load the text domain
	 *
	 * @since 1.0.0
	 */
	static function load_textdomain() {
		load_plugin_textdomain(self::$text_domain, false, GENERAL_BOOTSTRAP_PLUGIN_DIR . '/languages');
	}

	/**
	 * Hooks to 'register_activation_hook'
	 *
	 * @since 1.0.0
	 */
	static function register_activation() {

		// Check if multisite, if so then save as site option

		if (function_exists('is_multisite') && is_multisite()) {
			update_site_option(self::$prefix . 'version', GENERAL_BOOTSTRAP_VERSION_NUM);
		} else {
			update_option(self::$prefix . 'version', GENERAL_BOOTSTRAP_VERSION_NUM);
		}

		// Create Table upon activation

		$data_manager = new NNR_Data_Manager_v1( self::$table_name );
		$data_manager->create_table();

		$stats_tracker = new NNR_Stats_Tracker_v1( 'stats_tracker_v1' );
		$stats_tracker->create_table();

		$newsletter_submissions = new NNR_Newsletter_Integrations_Submission_v1( 'newsletter_submissions_v1' );
		$newsletter_submissions->create_table();
	}

	/**
	 * Hooks to 'plugin_action_links_' filter
	 *
	 * @since 1.0.0
	 */
	static function plugin_links($links) {
		$settings_link = '<a href="admin.php?page=' . self::$settings_page . '">Settings</a>';
		array_unshift($links, $settings_link);
		return $links;
	}

	/**
	 * Hooks to 'admin_menu'
	 *
	 * @since 1.0.0
	 */
	static function menu_page() {

		// Add the menu Page

		add_menu_page(
			__('General Bootstrap', self::$text_domain),				// Page Title
			__('General Bootstrap', self::$text_domain), 				// Menu Name
	    	'manage_options', 											// Capabilities
	    	self::$settings_page, 										// slug
	    	array('General_Bootstrap', 'admin_settings')				// Callback function
	    );

	    // Cast the first sub menu to the top menu

	    $settings_page_load = add_submenu_page(
	    	self::$settings_page, 										// parent slug
	    	__('Settings', self::$text_domain), 						// Page title
	    	__('Settings', self::$text_domain), 						// Menu name
	    	'manage_options', 											// Capabilities
	    	self::$settings_page, 										// slug
	    	array('General_Bootstrap', 'admin_settings')				// Callback function
	    );
	    add_action("admin_print_scripts-$settings_page_load", array('General_Bootstrap', 'include_admin_scripts'));

	    // Cast the first sub menu to the top menu

	    $data_manager_page_load = add_submenu_page(
	    	self::$settings_page, 										// parent slug
	    	__('Data Manager', self::$text_domain), 					// Page title
	    	__('Data Manager', self::$text_domain), 					// Menu name
	    	'manage_options', 											// Capabilities
	    	self::$data_manager_page, 									// slug
	    	array('General_Bootstrap', 'data_manager')					// Callback function
	    );
	    add_action("admin_print_scripts-$data_manager_page_load", array('General_Bootstrap', 'include_data_manager_scripts'));

	    // Cast the first sub menu to the top menu

	    $email_list_page_load = add_submenu_page(
	    	self::$settings_page, 										// parent slug
	    	__('Email List', self::$text_domain), 						// Page title
	    	__('Email List', self::$text_domain), 						// Menu name
	    	'manage_options', 											// Capabilities
	    	self::$email_list_page, 									// slug
	    	array('General_Bootstrap', 'email_list')					// Callback function
	    );
	    add_action("admin_print_scripts-$email_list_page_load", array('General_Bootstrap', 'include_data_manager_scripts'));

	    // Another sub menu

	    $usage_page_load = add_submenu_page(
	    	self::$settings_page, 										// parent slug
	    	__('Usage', self::$text_domain),  							// Page title
	    	__('Usage', self::$text_domain),  							// Menu name
	    	'manage_options', 											// Capabilities
	    	self::$usage_page, 											// slug
	    	array('General_Bootstrap', 'admin_usage')					// Callback function
	    );
	    add_action("admin_print_scripts-$usage_page_load", array('General_Bootstrap', 'include_admin_scripts'));

	    // Another sub menu

	    $tabs_page_load = add_submenu_page(
	    	self::$settings_page, 										// parent slug
	    	__('Tabs', self::$text_domain),  							// Page title
	    	__('Tabs', self::$text_domain),  							// Menu name
	    	'manage_options', 											// Capabilities
	    	self::$tabs_settings_page, 									// slug
	    	array('General_Bootstrap', 'admin_tabs')					// Callback function
	    );
	    add_action("admin_print_scripts-$tabs_page_load", array('General_Bootstrap', 'include_admin_scripts'));
	}

	/**
	 * Hooks to 'admin_print_scripts-$page'
	 *
	 * @since 1.0.0
	 */
	static function include_admin_scripts() {

		// CSS

		wp_register_style(self::$prefix . 'settings_css', GENERAL_BOOTSTRAP_PLUGIN_URL . '/css/settings.css');
		wp_enqueue_style(self::$prefix . 'settings_css');
		wp_register_style(self::$prefix . 'fontawesome_css', GENERAL_BOOTSTRAP_PLUGIN_URL . '/css/font-awesome.min.css');
		wp_enqueue_style(self::$prefix . 'fontawesome_css');

		// Javascript

		wp_register_script(self::$prefix . 'settings_js', GENERAL_BOOTSTRAP_PLUGIN_URL . '/js/settings.js');
		wp_enqueue_script(self::$prefix . 'settings_js');

		// BootStrap

		wp_enqueue_style(self::$prefix . 'bootstrap_css', GENERAL_BOOTSTRAP_PLUGIN_URL . '/includes/bootstrap/css/bootstrap.css');
		wp_enqueue_script(self::$prefix . 'bootstrap_js', GENERAL_BOOTSTRAP_PLUGIN_URL . '/includes/bootstrap/js/bootstrap.js', array('jquery'));
	}

	/**
	 * Hooks to 'admin_print_scripts-$page'
	 *
	 * @since 1.0.0
	 */
	static function include_data_manager_scripts() {

		// CSS

		wp_register_style(self::$prefix . 'settings_css', GENERAL_BOOTSTRAP_PLUGIN_URL . '/css/settings.css');
		wp_enqueue_style(self::$prefix . 'settings_css');
		wp_register_style(self::$prefix . 'fontawesome_css', GENERAL_BOOTSTRAP_PLUGIN_URL . '/css/font-awesome.min.css');
		wp_enqueue_style(self::$prefix . 'fontawesome_css');

		// Javascript

		wp_register_script(self::$prefix . 'settings_js', GENERAL_BOOTSTRAP_PLUGIN_URL . '/js/settings.js');
		wp_enqueue_script(self::$prefix . 'settings_js');

		// BootStrap

		wp_enqueue_style(self::$prefix . 'bootstrap_css', GENERAL_BOOTSTRAP_PLUGIN_URL . '/includes/bootstrap/css/bootstrap.css');
		wp_enqueue_script(self::$prefix . 'bootstrap_js', GENERAL_BOOTSTRAP_PLUGIN_URL . '/includes/bootstrap/js/bootstrap.js', array('jquery'));
	}

	/**
	 * Displays the HTML for the 'general-admin-menu-settings' admin page
	 *
	 * @since 1.0.0
	 */
	static function admin_settings() {

		if (function_exists('is_multisite') && is_multisite()) {
			$settings = get_site_option(self::$prefix . 'settings');
		} else {
			$settings = get_option(self::$prefix . 'settings');
		}

		// Default values

		if ( $settings === false ) {
			$settings = self::$default;
		}

		$data_manager_settings = new NNR_Data_Manager_Settings_v1(self::$prefix_dash, self::$text_domain);
		$display_conditions_settings = new NNR_Display_Conditions_Settings_v1(self::$prefix_dash, self::$text_domain);
		$newsletter_integration_settings = new NNR_Newsletter_Integrations_Settings_v1(self::$prefix_dash, self::$text_domain);
		$data_manager = new NNR_Data_Manager_v1( self::$data_manager_table_name );

		// Save data and check nonce

		if (isset($_POST['submit']) && check_admin_referer(self::$prefix . 'admin_settings')) {

			$settings = array(
				'text'		=> stripcslashes(sanitize_text_field($_POST[self::$prefix_dash . 'text'])),
				'textarea'	=> stripcslashes(sanitize_text_field($_POST[self::$prefix_dash . 'textarea'])),
				'checkbox'	=> isset($_POST[self::$prefix . 'checkbox']) && $_POST[self::$prefix_dash . 'checkbox'] ? true : false,
				'select'	=> $_POST[self::$prefix_dash . 'select'],
				'radio'		=> $_POST[self::$prefix_dash . 'radio'],
				'url'		=> stripcslashes(sanitize_text_field($_POST[self::$prefix_dash . 'url']))
			);

			// Get Data

			$data = $data_manager_settings->get_data();
			$display_data = $display_conditions_settings->get_data();
			$newsletter_data = $newsletter_integration_settings->get_data();

			// Add Data

			if ( !isset($settings['args']) || !is_array($settings['args']) ) {
				$settings['args'] = array();
			}

			$settings = array_merge($settings, $data);
			$settings['display_conditions'] = $display_data;
			$settings['args']['newsletter'] = $newsletter_data;

			//$data_manager->add_data($settings);

			if (function_exists('is_multisite') && is_multisite()) {
				update_site_option(self::$prefix . 'settings', $settings);
			} else {
				update_option(self::$prefix . 'settings', $settings);
			}
		}

		require('admin/settings.php');
	}

	/**
	 * Test the Display Conditions library
	 *
	 * @access public
	 * @static
	 * @return void
	 */
	static function display_data( $content ) {

		if (function_exists('is_multisite') && is_multisite()) {
			$settings = get_site_option(self::$prefix . 'settings');
		} else {
			$settings = get_option(self::$prefix . 'settings');
		}

		wp_register_style(self::$prefix . 'fontawesome_css', GENERAL_BOOTSTRAP_PLUGIN_URL . '/css/font-awesome.min.css');
		wp_enqueue_style(self::$prefix . 'fontawesome_css');

		// Display Data

		$data_manager = new NNR_Data_Manager_v1( self::$data_manager_table_name );
		$display_conditions = new NNR_Display_Conditions_Display_v1(self::$prefix_dash, self::$text_domain);
		$newsletter_form = new NNR_Newsletter_Integrations_Form_v1(self::$prefix_dash, self::$text_domain, self::$data_manager_table_name, self::$newsletter_table_name);

		$data = $data_manager->get_data();

		foreach ( $data as $item ) {
			//error_log($display_conditions->check_conditions($item['display_conditions']));
			return $newsletter_form->display_form($item['id'], $settings['args']['newsletter']) . $content;
		}

		// Record Stats

		$stats_tracker = new NNR_Stats_Tracker_v1( 'stats_tracker_v1' );
		//$stats_tracker->record_impresssion(false, 3);

		return $content;
	}

	/**
	 * Outputs the data on the Data Manager Page
	 *
	 * @access public
	 * @static
	 * @return void
	 */
	static function data_manager() {

		?>
		<div id="<?php echo self::$prefix_dash; ?>content">
			<h1><?php _e('Data Manager', self::$text_domain); ?></h1>
		<?php

		include_once('includes/data-manager/views/table.php');

		?> </div> <?php

	}

	/**
	 * Display the Email List
	 *
	 * @access public
	 * @static
	 * @return void
	 */
	static function email_list() {
		?>
		<div id="<?php echo self::$prefix_dash; ?>content">
			<h1><?php _e('Email List', self::$text_domain); ?></h1>
		<?php

		include_once('includes/newsletter-integrations/views/table.php');

		?> </div> <?php
	}

	/**
	 * Displays the HTML for the 'general-admin-menu-usage' admin page
	 *
	 * @since 1.0.0
	 */
	static function admin_usage() {
		?>
		<div id="<?php echo self::$prefix_dash; ?>content">
			<h1><?php _e('Usage Page', self::$text_domain); ?> <small><?php _e('Information about how to use this plugin.', self::$text_domain); ?></small></h1>
		</div>
		<?php
	}

	/**
	 * Displays the HTML for the 'general-admin-menu-tab-settings' admin page
	 *
	 * @since 1.0.0
	 */
	static function admin_tabs() {
		?>

		<script type="text/javascript">
			jQuery(document).ready(function($){
				$('#myTab a').click(function (e) {
					e.preventDefault();
					$(this).tab('show');
				})
			});
		</script>

		<div id="<?php echo self::$prefix_dash; ?>content">

			<h1><?php _e('Tabs Page', self::$text_domain); ?></h1>

			<!-- Nav tabs -->
			<ul id="myTab" class="nav nav-tabs" role="tablist">
				<li role="presentation" class="active"><a href="#<?php echo self::$prefix_dash;?>tab-1" role="tab" data-toggle="tab"><?php _e('Tab 1', self::$text_domain); ?></a></li>
				<li role="presentation"><a href="#<?php echo self::$prefix_dash;?>tab-2" role="tab" data-toggle="tab"><?php _e('Tab 2', self::$text_domain); ?></a></li>
			</ul>
			<br/>

			<!-- Tab panes -->
			<div class="tab-content">
				<div role="tabpanel" class="tab-pane active" id="<?php echo self::$prefix_dash;?>tab-1">
					<p><?php _e('Content of Tab 1', self::$text_domain); ?></p>
				</div>
				<div role="tabpanel" class="tab-pane" id="<?php echo self::$prefix_dash;?>tab-2">
					<p><?php _e('Content of Tab 2', self::$text_domain); ?></p>
				</div>
			</div>

		</div>
		<?php
	}
}

?>