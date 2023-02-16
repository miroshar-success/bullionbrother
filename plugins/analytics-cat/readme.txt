=== Analytics Cat - Google Analytics Made Easy ===
Contributors: fatcatapps, davidhme, ryannovotny
Donate link: https://fatcatapps.com/
Tags: google analytics, ga, universal analytics, google analytics plugin, google analytics wordpress, google analytics script, universal analytics plugin, google analytics wordpress plugin
Author URI: https://fatcatapps.com/
Plugin URI: https://fatcatapps.com/
Requires at least: 4.0
Tested up to: 6.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Stable tag: 1.1.1

Analytics Cat - Google Analytics Lets You Add Your Google Analytics / Universal Analytics Tracking Code To Your Site With Ease.

== Description ==

Analytics Cat - Google Analytics is a lean, fast, simple, no-frills way to add your Google Analytics / Universal Google Analytics code to your WordPress site.

This bloat-free, simple Google Analytics WordPress plugin doesn't add tons of features. Instead, Analytics Cat - Google Analytics simply focuses on letting you add your Google Analytics (Universal Analytics) Code to your site in less than 2 minutes, without slowing your site down.


= Which features does Analytics Cat - Google Analytics have? =
* Add the Google Analytics (Universal Analytics) tracking code to your WordPress site with ease.
* Hide your Google Analytics tracking code from logged-in users so you don't pollute your data.

= How to use Analytics Cat - Google Analytics? =

There are multiple ways to add the Google Analytics tracking code to your WordPress site.

**1. Pasting your Google Analytics script into your theme**
This approach isn't great for two reasons:
a) If you edit your live site and make a mistake when pasting your Google Analytics script into your theme, you could take down your website.
b) If your theme is updated with new features or security fixes, your Google Analytics code will be overwritten.

**2. Pasting your Google Analytics script into a header/footer script plugin**
This is a valid approach, but has some disadvantages. General purposes "header script plugins" aren't built from the ground-up to support Google Analytics. 

What this means is that :
a) These plugins lack Google Analytics-specific functionality.
b) These plugins will not adapt if Google Analytics changes again. Since Analytics Cat is a dedicated Google Analytics WordPress plugin, we'll make sure make sure to stay compatible with future changes to Google Analytics.
c) These plugins usually don't support hiding your Google Analytics code from logged-in users.

**3. Using Some Other Google Analytics WordPress Plugin**
There are some good Google Analytics plugins out there, but many of them are bloated, have too many settings and are slow. 

You'll love Analytics Cat - Google Analytics Cat iff what you want is a simple, reliable Google Analytics plugin, 

= Does Analytics Cat - Google Analytics Plugin work with Universal Analytics? =
Yes. Analytics Cat is built from the ground up to support Universal Analytics.

Analytics Cat - Google Analytics does not work with the old Google Analytics script that Google deprecated.

= Can I hide my Google Analytics tracking code from logged in users? =
Yes. You can hide your Google Analytics code from logged in users by going to Settings -> Google Analytics Manager.

= Is Analytics Cat - Google Analytics easy to translate? =
Yes. Analytics Cat - Google Analytics Cat is fully translateable. Please let us know if you're interested in contributing.

= Analytics Cat - Google Analytics Feature Roadmap =
This is just the first version of Analytics Cat - we have tons of new features & improvements lined up. Do you have any suggestions? Please leave a comment in the support forums.

--[The Fatcat Apps Team](https://fatcatapps.com/)


== Installation ==

1. Upload the Analytics Cat - Google Analytics plugin file (`fca-ga.zip`) to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. In your sidebar, select 'Settings -> Google Analytics Manager' to add your tracking code.
 

== Frequently Asked Questions ==
= How do I set up Google Analytics using Analytics Cat? =
After installing this plugin, simply go to Settings -> Google Analytics Manager and add your Google Analytics (Universal Analytics) ID.

= What is my Google Analytics tracking ID? =

Follow these steps to find your Google Analytics tracking id:

1. Sign in to your Google Analytics account

2. On the bottom left side of the screen, click on “Admin” (Cog icon).

3. Select your preferred Account and Property from the columns.

4. Under Property, Click ‘Tracking Info’ > ‘Tracking Code’

5. You will find your Tracking ID here (example: UA-xxxxxx-01)

6. Paste this Tracking ID into Analytics Cat

Important: Do not paste your tracking code (starting with <script>).  Instead, paste your tracking id (example: UA-xxxxxx-01) into Analytics Cat.

= Can I anonimize ip addresses? =

Yes you can! Append the following code to your theme's functions.php page:
`
function my_custom_ga_attributes ( $value ) {
	return '&aip=1';
}

add_filter( 'fca_ga_attributes', 'my_custom_ga_attributes' );`


For more information, read this: [How to find your Google Analytics tracking code, Google Analytics tracking ID, and Google Analytics property number](https://support.google.com/analytics/answer/1032385?hl=en)

== Privacy Disclosure ==

This plugin can be configured to connect to 3rd party service providers such as Google.

If you use this plugin to connect to a 3rd party, personal data may also be shared with that party.

Additional privacy policy information for 3rd party services can be found here:

[Google](https://policies.google.com/privacy)

Our full privacy policy is available here: [https://fatcatapps.com/legal/privacy-policy/](https://fatcatapps.com/legal/privacy-policy/)


== Screenshots ==
1. Analytics Cat - Google Analytics for WordPress settings screen

== Changelog ==

= Analytics Cat - Google Analytics 1.1.1 =
* Improved notices
* Changed default role exclusion to none
* Fix potential empty value error reported
* Remove unused API calls

= Analytics Cat - Google Analytics 1.1.0 =
* Improved security for Editor page
* Tested up to WordPress 5.9.1

= Analytics Cat - Google Analytics 1.0.9 =
* Updated feedback form
* Tested up to WordPress 5.6.2

= Analytics Cat - Google Analytics 1.0.8 =
* Fixed double tracking issue

= Analytics Cat - Google Analytics 1.0.7 =
* Tested up to WordPress 5.5

= Analytics Cat - Google Analytics 1.0.6 =
* Added filter fca_ga_attributes to add ability to customize enqueued script attributes (see readme)
* Admin UI text update
* Removed quotes from Google Analytics ID ( fixed issue with 3rd party add-on )
* Tested up to WordPress 5.4.2

= Analytics Cat - Google Analytics 1.0.5 =
* Upgraded from analytics.js to gtag.js
* Tested up to WordPress 5.4.1

= Analytics Cat - Google Analytics 1.0.4 =
* Load Select2 library locally instead of via CDN
* Add privacy disclosure

= Analytics Cat - Google Analytics 1.0.3 =
* Removed installation splash screen

= Analytics Cat - Google Analytics 1.0.2 =
* Add settings saved message
* Update help text & links
* Update permissions text

= Analytics Cat - Google Analytics 1.0.1 =
* Fix link to quick start guide in admin UI

= Analytics Cat - Google Analytics 1.0.0 =
* Release candidate 1 of Analytics Cat - Google Analytics