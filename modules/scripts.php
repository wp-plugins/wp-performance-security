<?php 

	// Add scripts and styles
	function wpps_add_script_fn($hook){
		if ( $hook == 'settings_page_wpps_config' ) {
			wp_enqueue_script('wpps_admin_js', plugins_url('/js/admin.js', __FILE__ ), array('jquery'), '1.0', true ) ;
		}
	}
	add_action( 'admin_enqueue_scripts', 'wpps_add_script_fn' );

?>