<?php
/**
 * Plugin Name: WP Performance & Security
 * Plugin URI: https://imaginarymedia.com.au/resources/plugins/wp-performance-security/
 * Description: A plugin with a range of performance and security enhancements
 * Version: 0.1
 * Author: Imaginary Media
 * Author URI: https://imaginarymedia.com.au/
 * License: GPL2
 */

/*  Copyright 2014 Imaginary Media (email : support@imaginarymedia.com.au)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as 
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if ( !defined( 'ABSPATH' ) ) exit;

#Add settings and styles files
include('modules/settings.php');
include('modules/scripts.php');

// Init settings values on activation
function wpps_activate(){
	$config = get_option('wpps_options'); 
	
	$wpps_options['stats_admin_footer'] = 1;
	$wpps_options['wpps_custom_upload_mimes'] = 1;
	$wpps_options['wpps_remove_wp_open_sans'] = 1;
	$wpps_options['wpps_excerpt_length'] = '55';
	$wpps_options['wpps_page_excerpts'] = 1;
	$wpps_options['output_compression'] = 1;
	$wpps_options['xmlrpc_enabled'] = 1;
	$wpps_options['atom_service_url_filter'] = 1;
	$wpps_options['wpps_searchAll'] = 1;
	$wpps_options['wpps_custom_feed_request'] = 1;
	$wpps_options['tags_support_all'] = 1;
	$wpps_options['tags_support_query'] = 1;
	$wpps_options['wpps_self_ping'] = 1;
	$wpps_options['wpps_html5_support'] = 1;
	$wpps_options['wpps_remove_script_version'] = 1;
	$wpps_options['wpps_remove_wp_version'] = 1;
	$wpps_options['wpps_all_settings_link'] = 1;
	$wpps_options['wpps_replace_howdy'] = 'Welcome, ';
	$wpps_options['wpps_auto_content'] = 1;
	$wpps_options['wpps_auto_excerpt'] = 1;
	$wpps_options['wpps_clickable_comments'] = 1;
	$wpps_options['filter_media_comment_status'] = 1;
	$wpps_options['wpps_closeCommentsGlobaly'] = 1;
	$wpps_options['wpps_comment_url'] = 1;
	$wpps_options['wpps_jetpack_devicepx'] = 1;

	// Login Options
	$wpps_options['wpss_custom_login_logo'] = '';
	$wpps_options['wpps_custom_login_url'] = '';
	$wpps_options['wpps_custom_login_title'] = '';
	$wpps_options['login_errors'] = 1;

	$wpps_options['wpps_excerpt_more'] = '[...]';
	$wpps_options['wpps_read_more'] = 1;
	
	// Header links
	$wpps_options['wpps_rel_links'] = 1;
	$wpps_options['wpps_wlw_manifest'] = 1;
	$wpps_options['wpps_rsd_link'] = 1;
	$wpps_options['wpps_short_link'] = 1;

	// Dashboard Widgets
	$wpps_options['wpps_dash_primary'] = 0;
	$wpps_options['wpps_dash_secondary'] = 0;
	$wpps_options['wpps_dash_right_now'] = 0;
	$wpps_options['wpps_dash_incoming_links'] = 0;
	$wpps_options['wpps_dash_quick_press'] = 0;
	$wpps_options['wpps_dash_recent_drafts'] = 0;
	$wpps_options['wpps_dash_recent_comments'] = 0;
	$wpps_options['wpps_dash_plugins'] = 0;

	// Admin Menu Items
	$wpps_options['wpps_menu_wp'] = 0;
	$wpps_options['wpps_menu_about'] = 0;
	$wpps_options['wpps_menu_wporg'] = 0;
	$wpps_options['wpps_menu_documentation'] = 0;
	$wpps_options['wpps_menu_forums'] = 0;
	$wpps_options['wpps_menu_feedback'] = 0;
	$wpps_options['wpps_menu_site'] = 0;

	update_option('wpps_options', $wpps_options );
}
register_activation_hook( __FILE__, 'wpps_activate' );


function wpps_init() {

	// Get settings
	$config = get_option('wpps_options');

	// Remove Jetpack devicepx script
	function wpps_dequeue_devicepx() {
		wp_dequeue_script( 'devicepx' );
	}
	if( $config['wpps_jetpack_devicepx'] == 1 ){
		add_action( 'wp_enqueue_scripts', 'wpps_dequeue_devicepx', 20 );
	}

	// Display DB Queries, Time Spent and Memory Consumption in Admin footer
	function stats_admin_footer() {
		$stat = sprintf(  '%d queries in %.3f seconds, using %.2fMB memory', get_num_queries(), timer_stop( 0, 3 ), memory_get_peak_usage() / 1024 / 1024 );
		echo $stat;
	} 
	if( $config['stats_admin_footer'] == 1 ){
		add_filter('admin_footer_text', 'stats_admin_footer');
	}

	// Allow SVG MIME types to be uploaded
	function wpps_custom_upload_mimes ( $existing_mimes=array() ) {
		$existing_mimes['svg'] = 'image/svg+xml';
		return $existing_mimes;
	}
	if( $config['wpps_custom_upload_mimes'] == 1 ){
		add_filter('upload_mimes', 'wpps_custom_upload_mimes');
	}

	// Remove Google Open Sans font
	function wpps_remove_wp_open_sans() {
		wp_deregister_style( 'open-sans' );
		wp_register_style( 'open-sans', false );
		wp_dequeue_style( 'twentytwelve-fonts' );
		remove_filter( 'mce_css', 'twentytwelve_mce_css' );
		remove_action( 'wp_enqueue_scripts', 'twentytwelve_scripts_styles' );
	}
	if( $config['wpps_remove_wp_open_sans'] == 1 ){
		add_action('wp_enqueue_scripts', 'wpps_remove_wp_open_sans', 11);
		add_action('admin_enqueue_scripts', 'wpps_remove_wp_open_sans', 11);
	}

	// Change the length of the default excerpt (number of words, default is 55)
	// **** Allow setting the specific length ***//
	function wpps_excerpt_length($length) { 
		$config = get_option('wpps_options'); 
		return $config['wpps_excerpt_length'];
	}
	add_filter('excerpt_length', 'wpps_excerpt_length');

	// Allow excerpts on Pages
	function wpps_page_excerpts() {
		add_post_type_support( 'page', 'excerpt' );
	}
	if( $config['wpps_page_excerpts'] == 1 ){
		add_action('init', 'wpps_page_excerpts');
	}

	// Custom excerpt ellipses
	function custom_excerpt_more( $more ) {
		$config = get_option('wpps_options'); 
		return $config['wpps_excerpt_more'];
	}
	add_filter( 'excerpt_more', 'custom_excerpt_more' );


	// Header Stuff
	if( $config['wpps_rel_links'] == 1 ){
		remove_action( 'wp_head', 'index_rel_link' );
		remove_action( 'wp_head', 'parent_post_rel_link' );
		remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
		remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
	}

	if( $config['wpps_wlw_manifest'] == 1 ){
		remove_action( 'wp_head', 'wlwmanifest_link' );
	}

	if( $config['wpps_rsd_link'] == 1 ){
		remove_action( 'wp_head', 'rsd_link' );
	}

	if( $config['wpps_short_link'] == 1 ){
		remove_action( 'wp_head', 'wp_shortlink_wp_head');
	}


	// Enable GZIP compression
	if( $config['output_compression'] == 1 ){
		if(extension_loaded("zlib") && (ini_get("output_handler") != "ob_gzhandler")) {
			add_action('wp', create_function('', '@ob_end_clean();@ini_set("zlib.output_compression", 1);'));
		}
	}

	// Disable XMLRPC
	if( $config['xmlrpc_enabled'] == 1 ){
		add_filter('xmlrpc_enabled', '__return_false');
	}

	// Prevents WordPress from testing SSL capability on domain.com/xmlrpc.php?rsd when XMLRPC not in use
	if( $config['atom_service_url_filter'] == 'on' ){
		remove_filter('atom_service_url','atom_service_url_filter');
	}

	// Hide login form error messages
	if( $config['login_errors'] == 1 ){
		add_filter('login_errors',create_function('$a', "return 'Error';"));
	}

	// Show Custom Post Types in search results
	function wpps_searchAll( $query ) {
		if ( $query->is_search ) { $query->set( 'post_type', array( 'site', 'plugin', 'theme', 'person' )); } 
		return $query;
	}
	if( $config['wpps_searchAll'] == 1 ){
		add_filter( 'the_search_query', 'wpps_searchAll' );
	}

	// Add Custom Post Types to the default RSS feed
	function wpps_custom_feed_request( $vars ) {
		if (isset($vars['feed']) && !isset($vars['post_type']))
			$vars['post_type'] = array( 'post', 'site', 'plugin', 'theme', 'person' );
		return $vars;
	}
	if( $config['wpps_custom_feed_request'] == 1 ){
		add_filter( 'request', 'wpps_custom_feed_request' );
	}

	// Allow tags on pages
	function tags_support_all() {
		register_taxonomy_for_object_type('post_tag', 'page');
	}
	if( $config['tags_support_all'] == 1 ){
		add_action('init', 'tags_support_all');
	}

	// Ensure all tags are included in queries
	function tags_support_query($wp_query) {
		if ($wp_query->get('tag')) $wp_query->set('post_type', 'any');
	}
	if( $config['tags_support_query'] == 1 ){
		add_action('pre_get_posts', 'tags_support_query');
	}

	// Disable self-ping
	function wpps_self_ping( &$links ) {
		$home = get_option( 'home' );
		foreach ( $links as $l => $link )
			if ( 0 === strpos( $link, $home ) )
				unset($links[$l]);
	}
	if( $config['wpps_self_ping'] == 1 ){
		add_action( 'pre_ping', 'wpps_self_ping' );
	}

	// Use HTML5
	if( ( $config['wpps_html5_support'] == 1 ) && function_exists( 'add_theme_support' ) ) {
			add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ) );
	}

	// Remove the version query string from scripts and styles - allows for better caching
	function wpps_remove_script_version( $src ){
		$parts = explode( '?', $src );
		return $parts[0];
	}
	if( $config['wpps_remove_script_version'] == 1 ){
		add_filter( 'script_loader_src', 'wpps_remove_script_version', 15, 1 );
		add_filter( 'style_loader_src', 'wpps_remove_script_version', 15, 1 );
	}

	// Remove WordPress version number
	function wpps_remove_wp_version() {
		return '';
	}
	if( $config['wpps_remove_wp_version'] == 1 ){
		remove_action('wp_head', 'wp_generator');
		add_filter('the_generator', 'wpps_remove_wp_version');
	}

	// Add new Admin menu item "All Settings"
	function wpps_all_settings_link() {
		add_options_page(__('All Settings'), __('All Settings'), 'administrator', 'options.php');
	}

	if( $config['wpps_all_settings_link'] == 1 ){
		add_action('admin_menu', 'wpps_all_settings_link');
	}

	// Change the default WordPress greeting in Admin

	function wpps_replace_howdy( $wp_admin_bar ) {
		$config = get_option('wpps_options'); 
	
		$my_account=$wp_admin_bar->get_node('my-account');
		$newtitle = preg_replace( "/^(.*?),/", $config['wpps_replace_howdy'], $my_account->title );
		$wp_admin_bar->add_node( array(
			'id' => 'my-account',
			'title' => $newtitle,
		) );
	}

	add_filter( 'admin_bar_menu', 'wpps_replace_howdy', 25 );

	// Disable Auto-Formatting in Excerpt
	if( $config['wpps_auto_excerpt'] == 1 ){
		remove_filter( 'the_excerpt', 'wpautop' );
	}

	if( $config['wpps_auto_content'] == 1 ){
		remove_filter( 'the_content', 'wpautop' );
	}

	// Disable Auto Linking of URLs in comments
	if( $config['wpps_clickable_comments'] == 1 ){
		remove_filter('comment_text', 'make_clickable', 9);
	}

	// Removes URL field from comments
	function wpps_remove_comment_url($fields) {
		unset($fields['url']);
		return $fields;
	}
	if( $config['wpps_comment_url'] == 1 ) {
		add_filter('comment_form_default_fields','wpps_remove_comment_url');
	}

	// Disable comments on media files
	function filter_media_comment_status( $open, $post_id ) {
		$post = get_post( $post_id );
		if( $post->post_type == 'attachment' ) {
			return false;
		}
		return $open;
	}
	if( $config['filter_media_comment_status'] == 1 ){
		add_filter( 'comments_open', 'filter_media_comment_status', 10 , 2 );
	}

	// Close comments globally
	function wpps_closeCommentsGlobaly($data) { return false; }
	if( $config['wpps_closeCommentsGlobaly'] == 1 ){
		add_filter('comments_number', 'wpps_closeCommentsGlobaly');
		add_filter('comments_open', 'wpps_closeCommentsGlobaly');
	}

	// no more jumping for read more link
	function wpps_no_jump($post) {
		return '<a href="'.get_permalink($post->ID).'" class="more-link">'.'Continue Reading'.'</a>';
	}
	if( $config['wpps_read_more'] == 1 ){
		add_filter('excerpt_more', 'wpps_no_jump');
		add_filter('the_content_more_link', 'wpps_no_jump');
	}

	// Custom login logo
	function wpss_custom_login_logo() {
		$config = get_option('wpps_options');
		echo '<style type="text/css">
		.login #login { padding-top: 0;}
		.login h1 { width: 320px; height: 200px; }
		.login h1 a { background:url('. $config['wpss_custom_login_logo'].') 50% 50% no-repeat; background-size:contain; height: 200px; width: 320px; }
		</style>';
	}
	if( $config['wpss_custom_login_logo'] !== '' ){
		add_action('login_head', 'wpss_custom_login_logo');
	}


	// Custom login URL
	function wpps_custom_login_url(){
		$config = get_option('wpps_options');
		return  $config['wpps_custom_login_url'];
	}
	if( $config['wpps_custom_login_url'] !== '' ){
		add_filter('login_headerurl', 'wpps_custom_login_url');
	}


	// Custom login URL Title Attribute
	function wpps_custom_login_title(){
		$config = get_option('wpps_options');
		return  $config['wpps_custom_login_title'] ;
	}
	if( $config['wpps_custom_login_title'] !== '' ){
		add_filter('login_headertitle', 'wpps_custom_login_title');
	}

	// Admin Menu Items
	// Admin Menu Items - WP
	function wpps_menu_wp() {
		global $wp_admin_bar;
		$wp_admin_bar->remove_menu('wp-logo');
	}

	if( $config['wpps_menu_wp'] == 1 ){
		add_action( 'wp_before_admin_bar_render', 'wpps_menu_wp' );
	}

	// Admin Menu Items - About
	function wpps_menu_about() {
		global $wp_admin_bar;
		$wp_admin_bar->remove_menu('about');
	}

	if( $config['wpps_menu_about'] == 1 ){
		add_action( 'wp_before_admin_bar_render', 'wpps_menu_about' );
	}

	// Admin Menu Items - WP.org
	function wpps_menu_wporg() {
		global $wp_admin_bar;
		$wp_admin_bar->remove_menu('wporg');
	}

	if( $config['wpps_menu_wporg'] == 1 ){
		add_action( 'wp_before_admin_bar_render', 'wpps_menu_wporg' );
	}

	// Admin Menu Items - Documentation
	function wpps_menu_documentation() {
		global $wp_admin_bar;
		$wp_admin_bar->remove_menu('documentation');
	}

	if( $config['wpps_menu_documentation'] == 1 ){
		add_action( 'wp_before_admin_bar_render', 'wpps_menu_documentation' );
	}

	// Admin Menu Items - Support Forums
	function wpps_menu_forums() {
		global $wp_admin_bar;
		$wp_admin_bar->remove_menu('support-forums');
	}

	if( $config['wpps_menu_forums'] == 1 ){
		add_action( 'wp_before_admin_bar_render', 'wpps_menu_forums' );
	}

	// Admin Menu Items - Feedback
	function wpps_menu_feedback() {
		global $wp_admin_bar;
		$wp_admin_bar->remove_menu('feedback');
	}

	if( $config['wpps_menu_feedback'] == 1 ){
		add_action( 'wp_before_admin_bar_render', 'wpps_menu_feedback' );
	}

	// Admin Menu Items - View Site
	function wpps_menu_site() {
		global $wp_admin_bar;
		$wp_admin_bar->remove_menu('view-site');
	}

	if( $config['wpps_menu_site'] == 1 ){
		add_action( 'wp_before_admin_bar_render', 'wpps_menu_site' );
	}


	// Remove Dashboard Items in Admin - Functions
	function remove_dashboard_primary() {
		remove_meta_box( 'dashboard_primary', 'dashboard', 'normal' );
	}

	function remove_dashboard_secondary() {
		remove_meta_box( 'dashboard_secondary', 'dashboard', 'normal' );
	}

	function remove_dashboard_right_now() {
		remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
	}

	function remove_dashboard_incoming_links() {
		remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
	}

	function remove_dashboard_plugins() {
		remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' );
	}

	function remove_dashboard_quick_press() {
		remove_meta_box( 'dashboard_quick_press', 'dashboard', 'normal' );
	}

	function remove_dashboard_recent_drafts() {
		remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'normal' );
	}

	function remove_dashboard_recent_comments() {
		remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
	}

	if( $config['wpps_dash_primary'] == 1 ){
		add_action( 'admin_init', 'remove_dashboard_primary' );
	}

	if( $config['wpps_dash_secondary'] == 1 ){
		add_action( 'admin_init', 'remove_dashboard_secondary' );
	}

	if( $config['wpps_dash_right_now'] == 1 ){
		add_action( 'admin_init', 'remove_dashboard_right_now' );
	}

	if( $config['wpps_dash_incoming_links'] == 1 ){
		add_action( 'admin_init', 'remove_dashboard_incoming_links' );
	}

	if( $config['wpps_dash_plugins'] == 1 ){
		add_action( 'admin_init', 'remove_dashboard_plugins' );
	}

	if( $config['wpps_dash_quick_press'] == 1 ){
		add_action( 'admin_init', 'remove_dashboard_quick_press' );
	}

	if( $config['wpps_dash_recent_drafts'] == 1 ){
		add_action( 'admin_init', 'remove_dashboard_recent_drafts' );
	}

	if( $config['wpps_dash_recent_comments'] == 1 ){
		add_action( 'admin_init', 'remove_dashboard_recent_comments' );
	}

}

add_action( 'plugins_loaded', 'wpps_init' );

?>