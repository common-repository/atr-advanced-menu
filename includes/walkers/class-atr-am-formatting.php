<?php

/*
 * Taken from csst-nav/inc/class.csst-nav-formatting.php
 * https://github.com/scofennell/csst-nav/blob/master/inc/class.csst-nav-formatting.php#L13
 */


/**
 * Description of class-mega-menu-formatting
 *
 * @author yehuda
 */
class atr_advanced_menu_formatting  {

	/**
	 * Take an array of classes, prepend a prefix to each,
	 * apply some naming preferences to each, sanitize each,
	 * and return the new array.
	 * 
	 * @param  $prefix string A prefix to append to each class.
	 * @param  $classes array An array of css classes (strings).
	 * @return array    An array of css classes (strings), renamed.
	 */
	public static function rename_css_classes( $prefix = '', $classes = array() ) {

		// This will hold the renamed classes.
		$out = array();

		// For each css class...
		foreach( $classes as $class ) {
			
			// If it's empty, bail.
			if( empty( $class ) ) { continue; }

			/**
			 * Let's use underscores for everything in the suffix,
			 * just to be consistent.
			 */  
			//$class = str_replace( '-', '_', $class );
			
			// If there is a prefix, prepend it.
			if( ! empty( $prefix ) ) {
				$class = $prefix . "-$class";
			}

			// If the class is non-empty, append it.
			if( ! empty( $class ) ) {
				$out[] = $class;
			}

		}

		// Sanitize each class name.
		$out = array_map( 'sanitize_html_class', $out );

		// Lowercase each class name.
		$out = array_map( 'strtolower', $out );

		// No duplicates.
		array_unique( $out );

		// Mind if I sort them alphabetically?
		//asort( $out );

		return $out;

	}
	
}