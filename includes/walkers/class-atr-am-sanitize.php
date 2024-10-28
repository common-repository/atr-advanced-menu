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
class Atr_Advanced_Menu_Sanitize {
    /**
     * sanitize_html_class works for a single class
     * We want to have more than one calss in <b class="fa fa-info"></b> 
     * to validate both fa fa-info,
     * Because sanitize_html_class doesn't allow spaces.
     *
     * @uses   sanitize_html_class
     * @param  (mixed: string/array) $class   "blue hedgehog goes shopping" or array("blue", "hedgehog", "goes", "shopping")
     * @param  (mixed) $fallback Anything you want returned in case of a failure
     * @return (mixed: string / $fallback )
	 * Credit to https://gist.github.com/justnorris/5387539
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
}