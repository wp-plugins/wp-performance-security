=== Plugin Name ===
Contributors: imaginarymedia
Tags: performance, security, settings
Requires at least: 3.0.1
Tested up to: 4.0
Stable tag: 0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin provides settings to modify WordPress and improve performance and security.

== Description ==

This plugin provides settings to modify WordPress and improve performance and security.

## General settings
* Modify excerpt length, the "More" text, and allow excerpts on Pages
* Change the "Read more" settings, so that the anchors to articles don't jump
* Modify custom post types so that they appear in search results and RSS feeds
* Allow tags on pages and ensure all tags appear in search queries
* Remove relational links
* Remove the Windows Live Writer manifest link (`wlwmanifest`)
* Remove the RSD link
* Remove the shortlink
* Allow SVG image uploads
* Enable HTML5 support for forms, comment lists, images and captions.
* Disable auto-formatting of content and/or excerpts

## Performance
* Enable GZIP on Apache
* Disable WordPress pings from internal links
* Remove the version query string on styles and scripts
* Remove the JetPack plugin `devicepx` script

## Security
* Remove the WordPress version string
* Modify XMLRPC features - disable entirely and/or disable XMLRPC SSL testing
* Comment modifications:
	- Disable comments
	- Disable comments on media files
	- Disable links in comments
	- Remove the 'URL' field from the comments form

## Administration
* Show statistics in the Admin section
* Change the WordPress greeting, even for non US English installs
* Remove the 'Open Sans' Google webfont from Admin
* Remove dashboard widgets
* Remove menu items
* Include the "All Settings" menu item

## Login
* Change the login page logo
* Change the login page logo URL
* Change the login page logo URL title
* Disable detailed login errors

## In development...
We're working to add the following features to the plugin:

* Google Analytics tracking
* Scheduled trash cleanup
* .htaccess modification for security and compression

If you have further suggestions, please contact us via the [plugin support page](https://wordpress.org/support/plugin/wp-performance-security).

If you find this plugin it useful to manage your WordPress settings, please [review the plugin](https://wordpress.org/support/view/plugin-reviews/wp-performance-security).

== Installation ==

1. Unzip the plugin and copy the `wp-performance-security` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress


== Changelog ==

= 0.1 =
* Initial launch