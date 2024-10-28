<?php

/**
 * The fields for the admin edit menu.
 *
 * Defines the fields for the menu edit page
 *
 * @package    Atr_Advanced_Menu
 * @subpackage Atr_Advanced_Menu/admin/walkers-edit
 * @author     Yehuda Tiram <yehuda@atarimtr.co.il>
 */
class Atr_Advanced_Menu_Walker_Edit_Fields {
	
        // Load API for media loader
        function atr_am_load_wp_media_files() {
			// Check if wp_enqueue_media is already loaded. (Otherwise, media will not worl on posts/pages edit)
			if ( did_action( 'wp_enqueue_media' ) ) {
				return;
			}
			else {
				wp_enqueue_media();
			}
        }	
		//$this->loader->add_action('load_wp_media_files', $plugin_edit_menu_fields, 'atr_am_load_wp_media_files', 11, 4);
		//add_action('load_wp_media_files', array($this->atr_am_load_wp_media_files()) 'atr_am_load_wp_media_files', 11, 4);
    /**
     * Add fields to hook added in Walker
     * This will allow us to play nicely with any other plugin that is adding the same hook
     * @params obj $item - the menu item 
     * @params array $args 
     * @since 1.0.0
     */
    public function custom_fields($item_id, $item, $depth, $args) {
        /* New fields insertion starts here */
        ?> 
        <div class="fields-wrapper-atr-mm">     
            <h2 class="field-atr-mm description description-wide"><img class="atr-mm-show-chart" src="<?php echo str_replace('includes/', '', plugin_dir_url(__FILE__)) . 'img/189-tree_1.png' ?>" />&nbsp;<?php _e('ATR Advanced Menu custom fields', 'atr-advanced-menu'); ?></h2>			
            <?php
            /* Sub title field insertion starts here */
            ?> 				
            <p class="field-atr-mm description description-wide">
                <label for="edit-menu-item-subtitle-<?php echo $item_id; ?>">
                    <?php _e('<strong>Subtitle</strong>', 'atr-advanced-menu'); ?><br />
                    <input type="text" id="edit-menu-item-subtitle-<?php echo $item_id; ?>" class="widefat code edit-menu-item-subtitle" name="menu-item-subtitle[<?php echo $item_id; ?>]" value="<?php echo sanitize_text_field($item->subtitle); ?>" />
                </label>
            </p>
            <?php
            /* select the mega menu panel for this top level item. Options: cols-1, cols-3, cols-4 */
            ?>
            <p class="field-atr-mm panel-class description-wide">
                <label for="edit-menu-item-panelclass-<?php echo $item_id; ?>">
                    <?php _e('<strong>Panel class</strong> - used only in top level items (optional)', 'atr-advanced-menu'); ?><br />
                    <input type="text" id="edit-menu-item-panelclass-<?php echo $item_id; ?>" class="widefat code edit-menu-item-panelclass" name="menu-item-panelclass[<?php echo $item_id; ?>]" value="<?php echo $this->sanitize_html_classes($item->panelclass); ?>" />
                </label>
            </p>         
            <?php
            /* Select between post thumbnail and media library image field insertion starts here */
            ?> 	
            <h3 class="field-atr-mm description description-wide"><?php _e('Select item image', 'atr-advanced-menu'); ?></h3><br /><hr />
            <p id="menu-item-chooseimage-wrapper-<?php echo $item_id; ?>" class="field-atr-mm chooseimage description-thin">				
                <input type="radio" value="0" name="menu-item-chooseimage<?php echo $item_id; ?>[]"<?php echo (($item->chooseimage == '0') || ($item->chooseimage == '') ? 'checked' : ''); ?> /><?php _e('None', 'atr-advanced-menu'); ?> <br />
                <input type="radio" value="2" name="menu-item-chooseimage<?php echo $item_id; ?>[]"<?php echo ($item->chooseimage == '2' ? 'checked' : ''); ?> /><?php _e('Use featured image', 'atr-advanced-menu'); ?><br /> 
                <input type="radio" value="1" name="menu-item-chooseimage<?php echo $item_id; ?>[]"<?php echo ($item->chooseimage == '1' ? 'checked' : ''); ?> /><?php _e('Item image from library', 'atr-advanced-menu'); ?><br /> 
                <input type="radio" value="3" name="menu-item-chooseimage<?php echo $item_id; ?>[]"<?php echo ($item->chooseimage == '3' ? 'checked' : ''); ?> /><?php _e('Item image from CSS', 'atr-advanced-menu'); ?>  
            </p>					
            <?php
            /* Featured image slection field insertion starts here */
            ?> 	
            <div class="field-atr-mm featuredimage description-thin">
                <label for="menu-item-featuredimage-<?php echo $item_id; ?>">	
                    <?php _e('Use the post featured image for this menu item', 'atr-advanced-menu'); ?><br />	
                    <?php
                    // featured image display
                    $image_attributes = '';

                    global $wp_version;
                    if ( version_compare( $wp_version, '4.4', '>=' ) ) {
                        // WordPress version is greater than 4.4

                    }                    
                    switch ($item->type) {
                        case 'post_type':
                            if (has_post_thumbnail($item->object_id)) {
                                $image_attributes = get_the_post_thumbnail($item->object_id, 'thumbnail');
                            } else
                                $image_attributes = '';
                            break;
                        case 'taxonomy':
                            if ($item->object == "product_cat") {
                                $woo_thumbnail_id = '';
                                global $wp_version;
                                if ( version_compare( $wp_version, '4.4', '>=' ) ) {
                                    // WordPress version is greater than 4.4
                                    // Avoid get_woocommerce_term_meta throws a deprecated notice. Since WooCommerce no longer supports WP versions lower than 4.4, the wrappers are no longer necessary and native Wordpress functions can be used instead. https://github.com/woocommerce/woocommerce/wiki/3.6.x-notes-FAQ
                                    $woo_thumbnail_id = wp_get_attachment_url($item->object_id);
                                }
                                else{
                                    $woo_thumbnail_id = get_woocommerce_term_meta($item->object_id, 'thumbnail_id', true);
                                }
                                
                                if ($woo_thumbnail_id) {
                                    $woo_thumbnail = wp_get_attachment_url($woo_thumbnail_id);
                                    $image_attributes = '<img class="atr-mm-wc-cat-img" src="' . $woo_thumbnail . '" alt=""/>';
                                } else {
                                    $image_attributes = '';
                                }
                            } else {
                                if (has_post_thumbnail($item->object_id)) {
                                    $image_attributes = get_the_post_thumbnail($item->object_id, 'thumbnail');
                                } else
                                    $image_attributes = '';
                            }
                            break;
                        case 'custom':
                            $image_attributes = '';
                    }
                    //$image_attributes = get_the_post_thumbnail(get_post_meta( $item->ID, '_menu_item_object_id', true ), array( 50, 50));
                    //$image_attributes = get_the_post_thumbnail($item->object_id, array( 50, 50));
                    if ($image_attributes != '') {
                        echo $image_attributes;
                    } else {
                        //$image_attributes = '<p class="atr-inline-warning featuredimage-error"><span class="dashicons dashicons-warning"></span>No featured image set. Please set it in post edit page!<p>';
						$image_attributes = _e('<p class="atr-inline-warning featuredimage-error"><span class="dashicons dashicons-warning"></span>No featured image set. Please set it in post edit page!</p>', 'atr-advanced-menu');
                        echo $image_attributes;  //
                    }
                    ?>
                </label>				
            </div>
            <?php
            /* Media library slection field insertion starts here */
            ?>         
            <div class="field-atr-mm customimage description-thin">
                <label for="menu-item-customimage-<?php echo $item_id; ?>">	
                    <?php //custom image     ?>
                    <img src="<?php echo esc_url($item->customimage); ?>" id="menu-item-customimage-<?php echo $item_id; ?>" class="atr-mm-wc-cat-img menu-item-customimage" />
                    <input id="customimage-url-<?php echo $item_id; ?>" type="text" name="menu-item-customimage[<?php echo $item_id; ?>]" value="<?php echo esc_url($item->customimage); ?>" class="edit-menu-item-customimage" />
                    <form method="post" class="form-atr-mm">					  
                        <input id="upload-button-<?php echo $item_id; ?>" type="button" class="button" value="<?php _e('Upload/Select Image', 'atr-advanced-menu') ?>" />  					 
                    </form>	
                </label>
            </div>	
            <?php
            /* select the item icon class for this item. */
            ?>
            <div class="field-atr-mm icon-class description-thin">            
                <label for="edit-menu-item-iconclass-<?php echo $item_id; ?>">
                    <?php //CSS Icon class  ?>
                    <?php 
						_e('<strong>Icon class</strong> - Write the class for font icon.', 'atr-advanced-menu'); 
						$atr_am_options = get_option( 'atr_advanced_menu_display_options' );
						 if ( ! ($atr_am_options[ 'load_icon_font' ]  )) {
							_e('<span class="atr-inline-warning"><strong>Note: You cannot use font icon. Please define the font icons file in the plugin settings -> "Load icon font"! </strong></span>', 'atr-advanced-menu'); 
						 }						
					
					?><br />
                    <input type="text" id="edit-menu-item-iconclass-<?php echo $item_id; ?>" class="widefat code edit-menu-item-iconclass" name="menu-item-iconclass[<?php echo $item_id; ?>]" value="<?php echo $this->sanitize_html_classes($item->iconclass); ?>" /><br />

                    <a id="iconclass-preview-button-<?php echo $item_id; ?>" class="button" href="#"><i class="<?php echo $this->sanitize_html_classes($item->iconclass); ?>"></i> <?php _e('Preview', 'atr-advanced-menu') ?></a>
                </label>
            </div>   
            <?php
            /* Add content from post # field insertion starts here */
            ?>
            <div class="atr-content-from-post-wrpper">
				<h3 class="field-atr-mm description description-wide atr-content-from-post-ttl"><?php _e('Content from post', 'atr-advanced-menu'); ?></h3><br /><hr />				
                <p class="field-atr-mm remove-title description-wide">
                    <label for="edit-menu-item-remttl-<?php echo $item_id; ?>">
                        <input type="checkbox" id="edit-menu-item-remttl-<?php echo $item_id; ?>" class="widefat code edit-menu-item-remttl" value="remove-title" name="menu-item-remttl[<?php echo $item_id; ?>]"<?php checked($item->remttl, 'remove-title'); ?> />
                        <?php _e('Remove title (and link) from this menu item.', 'atr-advanced-menu'); ?>
                    </label>
                </p>               
                <div class="field-atr-mm content-from-post description-thin">
                    <label for="menu-item-content-from-post-<?php echo $item_id; ?>">
                        <?php _e('<strong>Content from post</strong> - Write the the post id to insert its content into this item.', 'atr-advanced-menu'); ?><br />
                        <input type="text" id="edit-menu-item-content-from-post-<?php echo $item_id; ?>" class="widefat code edit-menu-item-content-from-post" name="menu-item-content-from-post[<?php echo $item_id; ?>]" value="<?php echo (!empty($item->content_from_post)) ? intval($item->content_from_post) : ''; ?>" />                    
                    </label>
                </div>  
                <div class="field-atr-mm atr-mm-postexcerpt description-thin">
                    <label for="edit-menu-item-postexcerpt-<?php echo $item_id; ?>">
                        <input type="checkbox" id="edit-menu-item-postexcerpt-<?php echo $item_id; ?>" class="widefat code edit-menu-item-postexcerpt" value="atr-mm-postexcerpt" name="menu-item-postexcerpt[<?php echo $item_id; ?>]"<?php checked($item->postexcerpt, 'use-excerpt'); ?> />
                        <?php _e('Use this post excerpt with link to target post.', 'atr-advanced-menu'); ?>
                    </label>
                </div> 	                
                <div class="field-atr-mm atr-mm-postfeatimg description-thin">
                    <label for="edit-menu-item-postfeatimg-<?php echo $item_id; ?>">
                        <input type="checkbox" id="edit-menu-item-postfeatimg-<?php echo $item_id; ?>" class="widefat code edit-menu-item-postfeatimg" value="atr-mm-postfeatimg" name="menu-item-postfeatimg[<?php echo $item_id; ?>]"<?php checked($item->postfeatimg, 'use-post-feat'); ?> />
                        <?php _e('Display this post featured image.', 'atr-advanced-menu'); ?>
                    </label>
                </div>                 
            </div>

        </div>
        <?php
        /* New fields insertion ends here */
    }

    /** TBD put this finc in util file
     * sanitize_html_class works for a single class
     * We want to have more than one calss in <b class="fa fa-info"></b> 
     * to validate both fa fa-info,
     * Because sanitize_html_class doesn't allow spaces.
     *
     * @uses   sanitize_html_class
     * @param  (mixed: string/array) $class   "blue hedgehog goes shopping" or array("blue", "hedgehog", "goes", "shopping")
     * @param  (mixed) $fallback Anything you want returned in case of a failure
     * @return (mixed: string / $fallback )
     */
    public function sanitize_html_classes($class, $fallback = null) {
        // Explode it, if it's a string
        if (is_string($class)) {
            $class = explode(" ", $class);
        }
        if (is_array($class) && count($class) > 0) {
            $class = array_map("sanitize_html_class", $class);
            return implode(" ", $class);
        } else {
            return sanitize_html_class($class, $fallback);
        }
    }

    /**
     * Add custom fields to $item nav object
     * in order to be used in custom Walker
     *
     * @access      public
     * @since       1.0 
     * @return      void
     */
    function atr_am_add_custom_nav_fields($menu_item) {
        $menu_item->subtitle = sanitize_text_field(get_post_meta($menu_item->ID, '_menu_item_subtitle', true));
        $menu_item->customimage = esc_url(get_post_meta($menu_item->ID, '_menu_item_customimage', true));
        $menu_item->chooseimage = esc_attr(get_post_meta($menu_item->ID, '_menu_item_chooseimage', true));
        $menu_item->panelclass = sanitize_html_classes(get_post_meta($menu_item->ID, '_menu_item_panelclass', true));
        $menu_item->iconclass = sanitize_html_classes(get_post_meta($menu_item->ID, '_menu_item_iconclass', true));
        $menu_item->content_from_post = intval(get_post_meta($menu_item->ID, '_menu_item_content_from_post', true));
        $menu_item->remttl = get_post_meta($menu_item->ID, '_menu_item_remttl', true);
        $menu_item->postexcerpt = get_post_meta($menu_item->ID, '_menu_item_postexcerpt', true);
        $menu_item->postfeatimg = get_post_meta($menu_item->ID, '_menu_item_postfeatimg', true);
        return $menu_item;
    }

    /**
     * Save menu custom fields
     *
     * @access      public
     * @since       1.0 
     * @return      void
     */
    function atr_am_update_custom_nav_fields($menu_id, $menu_item_db_id, $args) {

        // Check if we are in wp customizer mod.
        // When in customizer mod, we do not want to update any custom field because it will set wrong values (the fields are not present in this mode).
        // See http://wordpress.stackexchange.com/questions/58731/way-to-check-if-we-are-in-theme-customizer-mode
        // And http://wordpress.stackexchange.com/questions/55227/how-to-execute-conditional-script-when-on-new-customize-php-theme-customize-sc
        global $wp_customize;
        if (!isset($wp_customize)) {
            // Update menu custom fields
            if (isset($_POST['menu-item-subtitle' . $menu_item_db_id])) {
                if (is_array($_POST['menu-item-subtitle'])) {
                    $subtitle_value = sanitize_text_field($_POST['menu-item-subtitle'][$menu_item_db_id]);
                    update_post_meta($menu_item_db_id, '_menu_item_subtitle', $subtitle_value);
                }
            }
            if (isset($_POST['menu-item-customimage' . $menu_item_db_id])) {
                if (is_array($_POST[''])) {
                    $customimage_value = esc_url_raw($_POST['menu-item-customimage'][$menu_item_db_id]);
                    update_post_meta($menu_item_db_id, '_menu_item_customimage', $customimage_value);
                }
            }
			//$chooseimage_value = ''; // v1.0.1 changed all chooseimagehere because it got empty when saved all the menu ("save menu button")
            if (isset($_POST['menu-item-chooseimage' . $menu_item_db_id])) {
                if (is_array($_POST['menu-item-chooseimage' . $menu_item_db_id])) {
					//$chooseimage_value = implode(",", $_POST['menu-item-chooseimage' . $menu_item_db_id]);
					$chooseimage_value = $_POST['menu-item-chooseimage'][$menu_item_db_id];
					update_post_meta($menu_item_db_id, '_menu_item_chooseimage', intval($chooseimage_value));
                }
				} else {
					$chooseimage_value = "0";
					update_post_meta($menu_item_db_id, '_menu_item_chooseimage', intval($chooseimage_value));
				}
            
			//update_post_meta($menu_item_db_id, '_menu_item_chooseimage', 888);
            if (isset($_POST['menu-item-panelclass' . $menu_item_db_id])) {
                if (is_array($_POST['menu-item-panelclass'])) {
                    $panelclass_value = sanitize_html_classes($_POST['menu-item-panelclass'][$menu_item_db_id]);
                    update_post_meta($menu_item_db_id, '_menu_item_panelclass', $panelclass_value);
                }
            }
            if (isset($_POST['menu-item-iconclass' . $menu_item_db_id])) {
                if (is_array($_POST['menu-item-iconclass'])) {
                    $iconclass_value = sanitize_html_classes($_POST['menu-item-iconclass'][$menu_item_db_id]);
                    update_post_meta($menu_item_db_id, '_menu_item_iconclass', $iconclass_value);
                }
            }
            if (isset($_POST['menu-item-content-from-post' . $menu_item_db_id])) {
                if (is_array($_POST['menu-item-content-from-post'])) {
                    //$content_from_post_value = $_POST['menu-item-content-from-post'][$menu_item_db_id];
                    $content_from_post_value = (!empty($_POST['menu-item-content-from-post'][$menu_item_db_id])) ? intval($_POST['menu-item-content-from-post'][$menu_item_db_id]) : '';
                    update_post_meta($menu_item_db_id, '_menu_item_content_from_post', $content_from_post_value);
                }
            }
            if (isset($_POST['menu-item-remttl' . $menu_item_db_id])) {
                if (is_array($_POST['menu-item-remttl'])) {
                    $remttl_value = $_POST['menu-item-remttl'][$menu_item_db_id]; // $_POST['menu-item-remttl'] = 'remove-title'
                    update_post_meta($menu_item_db_id, '_menu_item_remttl', sanitize_text_field($remttl_value));
                }
            }
            if (isset($_POST['menu-item-postexcerpt' . $menu_item_db_id])) {
                if (is_array($_POST['menu-item-postexcerpt'])) {
                    $postexcerpt_value = $_POST['menu-item-postexcerpt'][$menu_item_db_id]; // $_POST['menu-item-postexcerpt'] = 'use-excerpt'
                    update_post_meta($menu_item_db_id, '_menu_item_postexcerpt', $postexcerpt_value);
                }
            }
            if (isset($_POST['menu-item-postfeatimg' . $menu_item_db_id])) {
                if (is_array($_POST['menu-item-postfeatimg'])) {
                    $postfeatimg_value = $_POST['menu-item-postfeatimg'][$menu_item_db_id];// $_POST['menu-item-postfeatimg'] = 'use-post-feat'
                    update_post_meta($menu_item_db_id, '_menu_item_postfeatimg', $postfeatimg_value);
                }
            }
        }
    }

    /**
     * Define new Walker edit
     *
     * @access      public
     * @since       1.0 
     * @return      void
     */
    function atr_am_edit_walker($walker, $menu_id) {

        return 'Walker_Nav_Menu_Edit_Custom';
    }	
}