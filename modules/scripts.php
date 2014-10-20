<?php 

	// Add scripts and styles
	function wpps_add_script_fn(){
		wp_enqueue_script('wwf_admin_js', plugins_url('/js/admin.js', __FILE__ ), array('jquery'), '1.0' ) ;
	}
	add_action( 'admin_enqueue_scripts', 'wpps_add_script_fn' );

?>