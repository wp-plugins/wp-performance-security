jQuery(document).ready(function(e){
	
	e(".wpps-nav-tab-wrapper a").on("click",function(t){
		t.preventDefault();
		e(".wpps-nav-tab-wrapper a").removeClass("nav-tab-active");
		e(this).addClass("nav-tab-active");
		var n=e(this).attr("href");
		e(".wpps-settings .tab-content").hide();
		e(n).show()
	});
	
	e(".wpps-settings .tab-content").hide().eq(0).show()
	
	e('input[name="wpps_menu_wp"]').on('change', function(){
		if(e(this).prop('checked')) {
			e('.wpps_menu_wp_sub input').attr('disabled','disabled');
		} else {
			e('.wpps_menu_wp_sub input').removeAttr('disabled');
		}
	});
	
	// Initial
	if(e('input[name="wpps_menu_wp"]').prop('checked')) {
		e('.wpps_menu_wp_sub input').attr('disabled','disabled');
	}
});