=== ATR advanced menu ===
Contributors: yehudaT
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=T6VTA75GTS3YA
Tags: plugin, menu, mega menu, custom Walker_Nav_Menu
Requires at least: 3.9
Tested up to: 6.4.2
Stable tag: 1.0.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html 

Adds an easy to manage accessible highly customized menu to your site. No special editor is used to manage it. Requires editing header.php.

== Description ==

Easily create accessible mega/dropdown menu with images/icons/title/posts using standard Wordpress menu editing page (supports keyboard interaction ).
Add menu item icons and images from media library, icon-font or featured image of the post(automatically).
Create menu items with none standart content, like videos, post content or anything that can be put in a post.

Note: This plugin requires a change of the header.php file of your theme (or another file, if the menu is defined somewhere else).
It also requires css editing in order to style it. It will technically work out of the box but you nust style it.

See a demo here:

https://www.youtube.com/watch?v=Ud0txlQ-ErQ

See the menu editing guide here:

https://www.youtube.com/watch?v=6efuT-zL_DM

= Features =

1. Save each item without reloading the page.
2. Each menu item is conrolled separately
3. Show images in menu items. Select between font icon, image from media library, Featured image of the post or none.
4. In addition to the image you can show in the menu: Title Attribute, Description and Subtitle.
5. Menu items can load full post (thus, enables you to inject html, video, image etc. or even shortcode) or post excerpt directly into it. 
6. Remove title (and link) from menu item (if you want the menu item with post not to show menu title).
7. Load web-font directly from the menu settings.
8. Let you define a unique class to each drop down panel. This way you can control each panel layout separately.
9. Uses class prefix to avoid css conflicts.

all the editing of the menu is done by the familiar menu editor of wordpress.

== Installation ==

Note: You must define a valid menu in your WP before activating the plugin in order for it to work correctly. 
If you see an error regarding this plugin, deactivate it and set a menu first.

Installation can be done either by searching for "ATR advanced menu" via the "Plugins > Add New" screen in your WordPress dashboard, or by using the following steps:

1. Download the plugin via WordPress.org
2. Upload the zip file through the 'Plugins > Add New > Upload' screen in your WordPress dashboard
3. Activate the plugin through the 'Plugins' menu in WordPress

= After installation: =
Important! You must edit the header.php file of your theme in order to use the menu.
Edit your theme header.php file and add 'walker' => new atr_advanced_menu_walker() to wp_nav_menu. :

	add to this:

    <pre><?php wp_nav_menu( array( 
		'theme_location' => 'primary', 
		'menu_class' => 'nav-menu', 
		'menu_id' => 'primary-menu' 
		) ); ?></pre>
	
See the last property: 

     <pre><?php  
			wp_nav_menu( array(
			'theme_location'	=> 'primary',
			'menu_class' => 'nav-menu',
			'menu_id' => 'primary-menu',
			'walker' => new atr_advanced_menu_walker()
			) );  
			?></pre>	

Note 1: If you use a theme that uses indirect call to the menu definition (mostly in bought themes or themes that use a framework) you'll have to find the correct way to apply the plugin in that theme.

Note 2: Examine the class of the menu wrapper div in your theme (like 'menu_class' => 'nav-menu' in above example ). Usually this theme class has properties that override the plugin's css definitions and can mess up your menu. 

If that happens, simply specify the css  rule like so:
Find the rule that breaks the menu, for example:
.main-navigation ul ul {}
Duplicate it and add ".megamenu" (the wrapper for the plugin's generated menu ) as an explicit selector.
.main-navigation .megamenu ul ul {}

You'll have also to set the other classes for the menu to display well.


= Settings =

Right after activating the plugin, go to Appearance - > ATR advanced menu Settings
Set your settings and save.


== Screenshots ==

1. Description of first screenshot named screenshot-1
2. Description of second screenshot named screenshot-2
3. Description of third screenshot named screenshot-3

== Frequently Asked Questions ==

= What is the ATR advanced menu for? =
It is a replacement for the default menu provided by Wordpress. It uses a feature of Wordpress, called Walker which let us customize the way the menu behaves.

= Why do I have to go to header.php I am not a programmer =
The only way to replace the default menu is from the header file.
If you do not know how to do it please ask some one who knows.

= Is it accessible? =
Yes. You can navigate between the items by your keyboard.

== Changelog ==

= 1.0.4 =
* 2020-8-12
* Tested for WP 5.5
* Tested for Woocommerce  4.3.2
* changed $('.atr-menu-save').live('click', function to $('.menu-item-settings').on('click', '.atr-menu-save', function 
  in admin\js\atr-am-admin.js

= 1.0.3 =
* 2019-6-15
* Tested for WP 5.2.1
* Tested for Woocommerce  3.6.4
* Added condition to avoid get_woocommerce_term_meta throws a deprecated notice. Since WooCommerce no longer supports WP versions lower than 4.4, the wrappers are no longer necessary and native Wordpress functions can be used instead. https://github.com/woocommerce/woocommerce/wiki/3.6.x-notes-FAQ

= 1.0.2 =
* 2016-9-09
* Checked for WP 4.6.1
* Made the settings more clear
* Change settings behavior. 
* megamenu.css now loads only the mandatory classes

= 1.0.1 =
* 2016-9-08
* Fixed undefined chooseimage var

= 1.0 =
* 2016-9-05
* Initial release
