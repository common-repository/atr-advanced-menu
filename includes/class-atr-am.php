<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://www.atarimtr.com/
 * @since      1.0.0
 *
 * @package    Atr_Advanced_Menu
 * @subpackage Atr_Advanced_Menu/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Atr_Advanced_Menu
 * @subpackage Atr_Advanced_Menu/includes
 * @author     Yehuda Tiram <yehuda@atarimtr.co.il>
 */
class Atr_Advanced_Menu {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Atr_Advanced_Menu_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'atr-advanced-menu';
		$this->version = '1.0.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Atr_Advanced_Menu_Loader. Orchestrates the hooks of the plugin.
	 * - Atr_Advanced_Menu_i18n. Defines internationalization functionality.
	 * - Atr_Advanced_Menu_Admin. Defines all hooks for the admin area.
	 * - Atr_Advanced_Menu_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-atr-am-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-atr-am-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-atr-am-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-atr-am-public.php';
		
		// Load custom walker
		// Check if we are in wp customizer mod.
		// When in customizer mod, we do not want to update any custom field because it will set wrong values (the fields are not present in this mode).
		// See http://wordpress.stackexchange.com/questions/58731/way-to-check-if-we-are-in-theme-customizer-mode
		// And http://wordpress.stackexchange.com/questions/55227/how-to-execute-conditional-script-when-on-new-customize-php-theme-customize-sc
		//global $wp_customize;
		if (!isset($wp_customize)) {
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/walkers/class-atr-am-walker.php';
		}
		/**
		 * The class for the menu edit page
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/walkers-edit/class-atr-am-walker-edit.php';

		/**
		 * The class for the menu fields edit page
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/walkers-edit/class-atr-am-walker-edit-fields.php';
		

		$this->loader = new Atr_Advanced_Menu_Loader();

	}
	

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Atr_Advanced_Menu_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Atr_Advanced_Menu_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Atr_Advanced_Menu_Admin( $this->get_plugin_name(), $this->get_version() );
		$plugin_settings = new Atr_Advanced_Menu_Admin_Settings( $this->get_plugin_name(), $this->get_version() );
		/* Load the edit menu functionality */
		$plugin_edit_menu_fields = new Atr_Advanced_Menu_Walker_Edit_Fields( $this->get_plugin_name(), $this->get_version() );	

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$plugin_basename = $this->plugin_name . '/' . 'atr-am.php';
        $this->loader->add_filter('plugin_action_links_' . $plugin_basename, $plugin_settings, 'add_action_links');
        $this->loader->add_action( 'admin_menu', $plugin_settings, 'setup_plugin_options_menu' );
        $this->loader->add_action( 'admin_init', $plugin_settings, 'initialize_display_options' );	
        // add media browsing ability to menu fields to menu
        $this->loader->add_action('admin_enqueue_scripts', $plugin_edit_menu_fields, 'atr_am_load_wp_media_files');


        // add custom menu fields to menu
        $this->loader->add_filter('wp_setup_nav_menu_item', $plugin_edit_menu_fields, 'atr_am_add_custom_nav_fields');

        // save menu custom fields
        $this->loader->add_action('wp_update_nav_menu_item', $plugin_edit_menu_fields, 'atr_am_update_custom_nav_fields', 10, 3);

        // edit menu walker
        $this->loader->add_filter('wp_edit_nav_menu_walker', $plugin_edit_menu_fields, 'atr_am_edit_walker', 10, 2);

        // add new fields via hook
        $this->loader->add_action('wp_nav_menu_item_custom_fields', $plugin_edit_menu_fields, 'custom_fields', 10, 4);		
	}


	
	
	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Atr_Advanced_Menu_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Atr_Advanced_Menu_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
