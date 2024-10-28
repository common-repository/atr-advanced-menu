<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://www.atarimtr.com/
 * @since      1.0.0
 *
 * @package    Atr_Advanced_Menu
 * @subpackage Atr_Advanced_Menu/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Atr_Advanced_Menu
 * @subpackage Atr_Advanced_Menu/public
 * @author     Yehuda Tiram <yehuda@atarimtr.co.il>
 */
class Atr_Advanced_Menu_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
    public $_menu_template_token;

    /**
     * The menu templates URL.
     * @var     string
     * @access  public
     * @since   1.0.0
     */	 
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->_menu_template_token = 'ATR_All_Menu_template';

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		//wp_enqueue_style( 'atr-am-public.css', plugin_dir_url( __FILE__ ) . 'css/atr-am-public.css', array(), $this->version, 'all' );

        // $menu_css_file = 'megamenu.css';	
		$atr_am_options = get_option( 'atr_advanced_menu_display_options' );

		// if ( ( ! ( $atr_am_options  === FALSE )) && ($atr_am_options[ 'do_not_load_css' ])){
		// 	// Do not load the css file at all
		// }
		// elseif ( ( ! ( $atr_am_options  === FALSE )) && ($atr_am_options[ 'css_file_to_use' ])) {
        //     wp_register_style('menu_css_file', plugin_dir_url( __FILE__ ) . 'menu-templates/' . $menu_css_file , array(), $this->version);
		// 	wp_enqueue_style('menu_css_file');			 
		// 	wp_register_style('custom_menu_css_file', esc_url($atr_am_options[ 'css_file_to_use' ]), array(), $this->version);
		// 	wp_enqueue_style('custom_menu_css_file');			 
		//  }		
        // else{
        //     wp_register_style('menu_css_file', plugin_dir_url( __FILE__ ) . 'menu-templates/' . $menu_css_file , array(), $this->version);
		// 	wp_enqueue_style('menu_css_file');
        // }        
        
		 if ( ! ( $atr_am_options === FALSE )) {
			if (( ! ( $atr_am_options['icon_font_from_elsewhere'] == '1')) && ($atr_am_options['load_icon_font'])) {
				wp_register_style( 'load_icon_font', esc_url($atr_am_options['load_icon_font']), $this->version);
				wp_enqueue_style('load_icon_font');				
			}			 
		 }		

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
	
		$atr_am_options = get_option( 'atr_advanced_menu_display_options' );
		$menu_js_file = '';
		 if ( ! ( $atr_am_options === FALSE )) {
			if ( ! ( $atr_am_options['style_edit_mode'] == '1')) {
				$menu_js_file = 'jquery-accessibleMegaMenu';
			}	
			else {
				$menu_js_file = 'jquery-accessibleMegaMenuStyleEdit';
			}
		 }
		 else {
			 $menu_js_file = 'jquery-accessibleMegaMenu';
		 }		
		}

}
