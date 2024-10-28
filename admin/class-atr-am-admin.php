<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.atarimtr.com/
 * @since      1.0.0
 *
 * @package    Atr_Advanced_Menu
 * @subpackage Atr_Advanced_Menu/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Atr_Advanced_Menu
 * @subpackage Atr_Advanced_Menu/admin
 * @author     Yehuda Tiram <yehuda@atarimtr.co.il>
 */
class Atr_Advanced_Menu_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->load_dependencies();

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Atr_Advanced_Menu_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Atr_Advanced_Menu_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( 'atr-am-admin.css', plugin_dir_url( __FILE__ ) . 'css/atr-am-admin.css', array(), $this->version, 'all' );

		$atr_am_options = get_option( 'atr_advanced_menu_display_options' );
		 if ( ! ( $atr_am_options  === FALSE )) {
			if ( ($atr_am_options[ 'load_icon_font' ]  )){
				$icon_font_file = esc_url($atr_am_options[ 'load_icon_font' ]);
				wp_enqueue_style( 'load_icon_font', $icon_font_file );				
			}			 			 
		 }	

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * An instance of this class should be passed to the run() function
		 * defined in Atr_Advanced_Menu_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Atr_Advanced_Menu_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/atr-am-admin.js', array( 'jquery' ), $this->version, false );

	}
	
	/**
     * Load the required dependencies for the Admin facing functionality.
     *
     * Include the following files that make up the plugin:
     *
     * - Atr_Advanced_Menu_Admin_Settings. Registers the admin settings and page.
     *
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies() {

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) .  'admin/class-atr-am-settings.php';

    }	

}
