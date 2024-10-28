<?php

/**
 * Custom Walker
 *
 * @access      public
 * @since       1.0 
 * @return      void
 */
class atr_advanced_menu_walker extends Walker_Nav_Menu {

    // Add variable to walker load (Has to be a parameter in header.php cal to walker)
    // http://wordpress.stackexchange.com/questions/31236/how-do-i-pass-an-argument-to-my-custom-walker
    var $refine;
    static $i_items = 0;
    static $parent_panel_class = '';
    public $css_class = '';

    function __construct() {
        
    }

//    function __construct($refine) {
//
//        $this->refine = $refine;
//        // Set up the namespace for our class.
//        //$this->css_class = strtolower(__CLASS__);
//        //add_filter('wp_nav_menu_items', 'new_nav_menu_items');        
//    }

    /**
     * Provide the opening markup for a new menu within our menu (AKA a submenu).
     * 
     * @param string $output Passed by reference. @see start_el().
     * @param int    $depth  Depth of menu item. @see start_el().
     * @param array  $args   An array of arguments. @see start_el().
     */
    function start_lvl(&$output, $depth = 0, $args = array()) {
        $indent = str_repeat("\t", $depth);
        //$panel_class = $item->panelclass;
        // Select a CSS class for this `<ul>` based on $depth
        // $do_not_use_wrapper = get_option('atr_all_menu_do_not_use_wrapper');
        // ************** TODO Check this conditon again. I removed the option from settings ***************
        // if (!$do_not_use_wrapper == 'on') {

        // }
		// Set the Mega Menu panel class (only level 0) 
		$the_panel_class = self::$parent_panel_class; // This child element is wrapped with a div + class = $the_panel_class
		
		
		$atr_am_options = get_option( 'atr_advanced_menu_display_options' );
		if ( ! ( $atr_am_options  === FALSE )){
			$default_panel_class = $atr_am_options['panel_default_class'];
		}
		else {
			$default_panel_class = 'accessible-megamenu-panel';
		}
		
		
		
		//$default_panel_class = get_option('atr_all_menu_panel_default_class', 'accessible-megamenu-panel');
		if ($depth === 0) {
			//$output .= "<div class=\"$default_panel_class $the_panel_class \">\n";
		} else {
			//$output .= "<div class=\"$the_panel_class\">\n";
		}		
        $class = 'sub-menu sub-list-' . $depth . ' ';
        //$output .= "<ul class=$class>\n";
		if ($depth === 0) {
			$output .= "<ul class=\"$class \">\n";
		}
		else{
			$output .= "<ul class=\"$class $the_panel_class \">\n";
		}
		
        self::$parent_panel_class = ''; // reset the panel class to prevent adding the class to next (if $item->panelclass != '')
    }

    /**
     * Append the opening html for a nav menu item, and the menu item itself.
     *
     * @param string $output Passed by reference. The output for all of the preceding menu items.
     * @param object $item   The Post object for this menu item.
     * @param int    $depth  The number of levels deep we are in submenu-land.
     * @param array  $args   An array of arguments for wp_nav_menu().
     * @param int    $id     Allegedly the current item ID -- seems to always just be 0.
     */
    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        // Set the class for the pop up panel (used in start_lvl function above )
        // This will be added to the next child element as a div $the_panel_class
        ($item->panelclass != '') ? self::$parent_panel_class = $item->panelclass : $parent_panel_class = ''; //Prevent adding panel class to next li div panel if its def is empty
        $indent = str_repeat("\t", $depth);
        $attributes = '';
        !empty($item->attr_title)
                and $attributes .= ' title="' . esc_attr($item->attr_title) . '"';
        !empty($item->target)
                and $attributes .= ' target="' . esc_attr($item->target) . '"';
        !empty($item->xfn)
                and $attributes .= ' rel="' . esc_attr($item->xfn) . '"';
        !empty($item->url)
                and $attributes .= ' href="' . esc_attr($item->url) . '"';
//        !empty($item->class)
//                and $attributes .= ' class="' . esc_attr($item->class) . '"';        
        // insert thumbnail
        $thumbnail = '';
        $thumbnail_id = '';

        /*
         * The next if is experimental to check wp_nav_menu Walker Parameter pased in header
         * TO DO: in the future will be used to pass user selection of the menu in the Nav Walker call in the template/widget 
         */
        //if ($this->refine == "accessible_megamenu_walker") {
        //if ($depth != 0){
        if ($item->chooseimage == '1') {
            // Item image from library 
            $thumbnail = '<img src="' . $item->customimage . '" />';
        } elseif ($item->chooseimage == '2') {
            // Use featured image or category image in case of Woocommerce product category page            
            switch ($item->type) {
                case 'post_type':
                    $thumbnail = get_the_post_thumbnail($item->object_id); // . 'item->type:' . $item->type;
                    break;
                case 'taxonomy':
                    if ($item->object == "product_cat") {
                        global $wp_version;
                        if ( version_compare( $wp_version, '4.4', '>=' ) ) {
                            // WordPress version is greater than 4.4
                            // Avoid get_woocommerce_term_meta throws a deprecated notice. Since WooCommerce no longer supports WP versions lower than 4.4, the wrappers are no longer necessary and native Wordpress functions can be used instead. https://github.com/woocommerce/woocommerce/wiki/3.6.x-notes-FAQ
                            $thumbnail = wp_get_attachment_url($item->object_id);
                        }
                        else{
                            $thumbnail_id = get_woocommerce_term_meta($item->object_id, 'thumbnail_id', true);
                            $thumbnail = wp_get_attachment_url($thumbnail_id);                            
                        }
                        if ($thumbnail)
                            $thumbnail = '<img class="atr-mm-wc-cat-img" src="' . $thumbnail . '" alt=""/>';
                    }

                    break;
                case (is_product_category()):

                    break;
            }
        }
        //}

        // CSS class for the menu.
        $class = $this->css_class;

        $item_output = '';
        //Add classes to item classes array

        if ($depth == 0) {
            $item->classes[] = 'top-level';
            switch ($item->chooseimage) {
                case 0:
                    $item->classes[] = 'top-level-no-icon';
                    break;
                case 1:
                    $item->classes[] = 'top-level-lib-icon';
                    break;
                case 2:
                    $item->classes[] = 'top-level-feat-icon';
                    break;
                case 3:
                    $item->classes[] = 'top-level-css-icon';
                    break;
            }
        } else {
            switch ($item->chooseimage) {
                case 0:
                    $item->classes[] = 'no-icon';
                    break;
                case 1:
                    $item->classes[] = 'lib-icon';
                    break;
                case 2:
                    $item->classes[] = 'feat-icon';
                    break;
                case 3:
                    $item->classes[] = 'css-icon';
                    break;
            }
        }

        if ($item->chooseimage != '0') {
            $item->classes[] = 'item-bg-icon';
        }
        $item->classes[] = 'menu-item-' . $item->ID;
        // Grab the class names for the menu item.
        $classes = $item->classes;
		//var_dump( $classes[1] );
        // Rename 
        $css_class_prefix = '';
		$atr_am_options = get_option( 'atr_advanced_menu_display_options' );
		if ( ! ( $atr_am_options  === FALSE )){
			$css_class_prefix = $atr_am_options['css_class_prefix'];
		}
		else {
			$css_class_prefix = 'accessible-megamenu-panel';
		}		
        if ($classes){
            $classes = atr_advanced_menu_formatting::rename_css_classes($css_class_prefix, $classes);
			// Expose the classes to filtering.
			apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth);

			// Convert the classes into a string for output.
			$class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item));			
		}


        // ***************************
        // **** print the items ******
        // ***************************
        // In all items
        $item_output = $args->before;
        if (!$item->remttl) $item_output .= "<a $attributes>";
        switch ($item->chooseimage) {
            // 0 = No image
            // 1,2 = image from featured or media lib
            // 3 = image from web-font
            case 1:
            case 2:
                ($depth == 0) ? $item_output .= $thumbnail : $item_output .= '<div class="atr-mm-menu-thumb">' . $thumbnail . '</div>';
                break;
            case 3:
                $item_output .= "<b class=\"";
                $item_output .= $item->iconclass;
                $item_output .= "\"></b>";
                break;
        }
        //$top_levet_subtitle = ($depth == 0) ? '<div class="menu-item-subtitle">' . $item->subtitle . '</div>' : ''; // Only depth 0 gets the subtitle under the link
        $top_levet_subtitle = $item->attr_title ? '<div class="menu-item-title-att">' . $item->attr_title . '</div>' : '';
        $item_output .= $args->link_before;
        if (!$item->remttl) $item_output .= $title = apply_filters('the_title', $item->title, $item->ID);
        $item_output .= $top_levet_subtitle . $args->link_after;
        if (!$item->remttl) $item_output .= '</a> ';
        //$item_output .= $post_excerpt_wrap;
        $item_output .= $args->after;
        switch ($depth) {
            case 0:
                break; // depth 0 does not get other elements
            default:
                // All other `<li>`s 
                if (strlen($item->subtitle) > 2) {
                    $item_output .= '<div class="menu-item-subtitle">' . $item->subtitle . '</div>';
                }
                if (strlen($item->description) > 2) {
                    $item->description = apply_filters('nav_menu_description', $item->post_content);
                    $item_output .= '<div class="menu-item-description">' . $item->description . '</div>';
                }
                if ($item->content_from_post) {
                    $content = '';
                    $postexcerpt = '';
                    $my_postid = $item->content_from_post; //This is page id or post id
                    $content_post = get_post($my_postid);
                    $postfeatimg = ($item->postfeatimg) ?  '<div class="atr-mm-postfeat">' . get_the_post_thumbnail($my_postid) . '</div>' : '';                  
                    if ($item->postexcerpt) {
                        //$postexcerpt = '<div class="atr-mm-postexcerpt">' . wp_trim_words($content_post->post_excerpt, $num_words = 12, $more = '… ') . '</div>'; //TODO change the length to chars. see function atr_mm_trim_excerpt at bottom
                        $postexcerpt = '<div class="atr-mm-postexcerpt">' . $this->atr_mm_trim_excerpt($content_post->post_excerpt, 15) . '</div>';
                        $item_output .= '<div class="menu-item-content-from-post">' . $postfeatimg . $postexcerpt . ' <a class="atr-mm-bottom-link" href="' . get_permalink($my_postid) . '">' . esc_html__('Read more...', 'atr-all-menu') . '</a></div>';
                    } else {
                        $content = $content_post->post_content;
                        $content = apply_filters('the_content', $content);
                        $content = str_replace(']]>', ']]&gt;', $content);
                        $item_output .= '<div class="menu-item-content-from-post">' . $postfeatimg . $content . '</div>';
                    } 
                }
                break;
        }
        // ***************************
        // **** End print the items ******
        // ***************************
        // Only print out the 'class' attribute if a class has been assigned
        if (isset($class)) {
            $output .= $indent . '<li class="' . $class_names . '">';
        } else {
            $output .= $indent . '<li>';
        }

        // Since $output is called by reference we don't need to return anything.
        $output .= apply_filters(
                'walker_nav_menu_start_el'
                , $item_output
                , $item
                , $depth
                , $args
        );
    }

    /**
     * Allows for excerpt generation outside the loop.
     * 
     * @param string $text  The text to be trimmed
     * @param string $excerpt_length_passed  The text to be trimmed
     * @return string       The trimmed text
     */
    function atr_mm_trim_excerpt($text = '', $excerpt_length_passed) {
        $text = strip_shortcodes($text);
        $text = apply_filters('the_content', $text);
        $text = str_replace(']]>', ']]&gt;', $text);
        $excerpt_length = apply_filters('excerpt_length', $excerpt_length_passed);
        $excerpt_more = apply_filters('excerpt_more', ' ' . '[...]');
        //return wp_trim_words($text, $excerpt_length, $excerpt_more);
        return wp_trim_words($text, $excerpt_length);
    }

}
