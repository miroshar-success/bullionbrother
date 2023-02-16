=== Bottom Admin Toolbar ===
Contributors: devloper00
Donate link: https://ko-fi.com/devloper
Tags: admin, bar, adminbar, bottom bar, toolbar, wordpress, bottom
Requires at least: 4.9 or higher
Tested up to: 6.1.1
Requires PHP: 5.6
Stable tag: 1.5.1
License: GPL v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Natively WordPress doesn't offer the possibility to change your admin bar position. With that simple extension you can stick it at the bottom forever and hide it by pressing shortcut!

== Description ==

= LET'S STICK THAT BAR AT THE BOTTOM FOREVER! =

Natively WordPress doesn't offer the possibility to change your admin bar position. With that simple extension you can stick it at the bottom forever and hide it by pressing shortcut!

= Main features: =

* Stick admin bar to the bottom
* Hide bar by pressing **SHIFT + Down Arrow**

== Frequently Asked Questions ==

= Why would I need the admin bar at the bottom? =

For example when you develop a website which has a sticky header

== Installation ==

1. Upload the **bottom-admin-toolbar** folder to the **/wp-content/plugins/** directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Screenshots ==

1. Bottom Admin Toolbar in action

== Changelog ==

= 1.5.1 =
* Use dynamic bar height if bigger than default size

= 1.5 =
* Show / Hide toolbar in administration
* Update deprecated javascript function
* Refacto code

= 1.4.1 =
* Add custom class on admin bar for compatiblity with Dynamic Plugins

= 1.4 =
* Update code by using Object Oriented Method
* Overall Optimization

= 1.3.3 =
* Reset admin bar to top if tinyMCE Instance is detected on page (Conflicts with pop-up)

= 1.3.2 =
* Add rating link 😉
* Prefix functions to prevent conflict

= 1.3.1 =
* Use is_admin_bar_showing() function instead of is_user_loggedin_in() to enqueues custom files. Thanks to @hwk-fr

= 1.3 =
* Add class to wpadminbar for compatibility with other extensions

= 1.2 =
* Rename functions to prevent crashing when using with other extensions

= 1.1 =
* Add support for hiding bar with keyboard shortcut (Shift+ Arrow Down)

= 1.0 =
* Initial release