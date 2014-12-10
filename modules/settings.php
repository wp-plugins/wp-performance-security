<?php 
	
	ob_start();

	// Process settings update
	add_action('plugins_loaded', 'wpps_update_settings');
	function wpps_update_settings(){
		if( wp_verify_nonce($_POST['_wpnonce']) ){
			$config = get_option('wpps_options'); 
			foreach( $_POST as $key=>$value ){
				$wpps_options[$key] = sanitize_text_field( $value );
			}
			update_option('wpps_options', $wpps_options );
		}
	}

	// Add settings page
	add_action('admin_menu', 'wpps_item_menu');
	function wpps_item_menu() {
		add_options_page(  __('WP Performance & Security', 'wpps'), __('Performance & Security', 'wpps'), 'edit_published_posts', 'wpps_config', 'wpps_config');
	}

	// Add settings link on plugin page
	function wpps_plugin_settings_link($links) { 
		$settings_link = '<a href="options-general.php?page=wpps_config">Settings</a>'; 
		array_unshift($links, $settings_link); 
		return $links;
	}

	$plugin = 'wp-performance-security/wp-performance-security.php';
	add_filter("plugin_action_links_$plugin", 'wpps_plugin_settings_link' );

	// Settings output function
	function wpps_config(){
?>
	<div class="wrap">
		
		<h2><?php _e('WP Performance &amp; Security Settings', 'wpps'); ?></h2>

	<?php if(  wp_verify_nonce($_POST['_wpnonce']) ): ?>
		<div class="updated fade" >
			<p><?php _e('Settings saved successfully', 'wpps'); ?></p>
		</div>
	<?php endif; ?>

		<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">

		<?php
			wp_nonce_field();  
			$config = get_option('wpps_options'); 
		?> 
			
			<h2 class="nav-tab-wrapper wpps-nav-tab-wrapper">
				<a href="#tabs-1" class="nav-tab nav-tab-active"><?php _e('General', 'wpps'); ?></a>
				<a href="#tabs-2" class="nav-tab"><?php _e('Performance', 'wpps'); ?></a>
				<a href="#tabs-3" class="nav-tab"><?php _e('Security', 'wpps'); ?></a>
				<a href="#tabs-4" class="nav-tab"><?php _e('Administration', 'wpps'); ?></a>
				<a href="#tabs-5" class="nav-tab"><?php _e('Login', 'wpps'); ?></a>
				<a href="#tabs-6" class="nav-tab"><?php _e('Google Analytics', 'wpps'); ?></a>
			</h2>

			<div class="wpps-settings">

				<div id="tabs-1" class="tab-content">

					<table class="form-table">
						<tr>
							<th scope="row"><?php _e('Excerpts', 'wpps'); ?></th>
							<td>
								<fieldset>
									<label>
										<span><?php _e('Number of words in excerpts: ', 'wpps'); ?></span>
										<input type="text" class="small-text" name="wpps_excerpt_length" value="<?php echo $config['wpps_excerpt_length']; ?>">
									</label>
								</fieldset>
								<fieldset>
									<label>
										<span><?php _e('“More” text string in excerpts: ', 'wpps'); ?></span>
										<input type="text" class="small-text" name="wpps_excerpt_more" value="<?php echo $config['wpps_excerpt_more']; ?>">
									</label>
								</fieldset>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_page_excerpts" value="1" <?php checked( $config['wpps_page_excerpts'], 1 ); ?>>
										<span><?php _e('Allow excerpts on Pages', 'wpps'); ?></span>
									</label>
								</fieldset>
							</td>
						</tr>
					</table>

					<hr>

					<table class="form-table">
						<tr>
							<th scope="row"><?php _e('“Read More”', 'wpps'); ?></th>
							<td>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_read_more" value="1" <?php checked( $config['wpps_read_more'], 1 ); ?>>
										<span><?php _e('Disable “Read more” links from jumping to anchor', 'wpps'); ?></span>
									</label>
									<p class="description"><?php _e('When creating a <code>&lt;!--more--&gt;</code> link in WordPress the default action is to jump to the ‘next’ section.', 'wpps'); ?></p>
								</fieldset>
							</td>
					</table>

					<hr>

					<table class="form-table">
						<tr>
							<th scope="row"><?php _e('Custom Post Types', 'wpps'); ?></th>
							<td>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_searchAll" value="1" <?php checked( $config['wpps_searchAll'], 1 ); ?>>
										<span><?php _e('Show Custom Post Types in the search results', 'wpps'); ?></span>
									</label>
								</fieldset>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_custom_feed_request" value="1" <?php checked( $config['wpps_custom_feed_request'], 1 ); ?>>
										<span><?php _e('Show Custom Post Types in the RSS feed', 'wpps'); ?></span>
									</label>
								</fieldset>
							</td>
						</tr>
					</table>

					<hr>

					<table class="form-table">
						<tr>
							<th scope="row"><?php _e('Tags', 'wpps'); ?></th>
							<td>
								<fieldset>
									<label>
										<input type="checkbox" name="tags_support_all" value="1" <?php checked( $config['tags_support_all'], 1 ); ?>>
										<span><?php _e('Allow tags on pages', 'wpps'); ?></span>
									</label>
								</fieldset>
								<fieldset>
									<label>
										<input type="checkbox" name="tags_support_query" value="1" <?php checked( $config['tags_support_query'], 1 ); ?>>
										<span><?php _e('Ensure all tags are included in queries', 'wpps'); ?></span>
									</label>
								</fieldset>
							</td>
						</tr>
					</table>

					<hr>

					<table class="form-table">
						<tr>
							<th scope="row"><?php _e('Header', 'wpps'); ?></th>
							<td>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_rel_links" value="1" <?php checked( $config['wpps_rel_links'], 1 ); ?>>
										<span><?php _e('Remove relational links for the posts adjacent to the current post', 'wpps'); ?></span>
									</label>
								</fieldset>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_wlw_manifest" value="1" <?php checked( $config['wpps_wlw_manifest'], 1 ); ?>>
										<span><?php _e('Remove <em>Windows Live Writer</em> manifest link (wlwmanifest)', 'wpps'); ?></span>
									</label>
								</fieldset>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_rsd_link" value="1" <?php checked( $config['wpps_rsd_link'], 1 ); ?>>
										<span><?php _e('Remove <abbr title="Really Simple Discovery">RSD</abbr> link', 'wpps'); ?></span>
									</label>
								</fieldset>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_short_link" value="1" <?php checked( $config['wpps_short_link'], 1 ); ?>>
										<span><?php _e('Remove Shortlink', 'wpps'); ?></span>
									</label>
								</fieldset>
							</td>
						</tr>
					</table>

					<hr>

					<table class="form-table">
						<tr>
							<th scope="row"><?php _e('Uploads', 'wpps'); ?></th>
							<td>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_custom_upload_mimes" value="1" <?php checked( $config['wpps_custom_upload_mimes'], 1 ); ?>>
										<span><?php _e('Allow SVG image uploads', 'wpps'); ?></span>
									</label>
								</fieldset>
							</td>
						</tr>
					</table>

					<hr>


					<table class="form-table">
						<tr>
							<th scope="row"><?php _e('HTML5 Support', 'wpps'); ?></th>
							<td>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_html5_support" value="1" <?php checked( $config['wpps_html5_support'], 1 ); ?>>
										<span><?php _e('Use HTML5 markup for the comment forms, search forms, comment lists, images and captions.', 'wpps'); ?></span>
									</label>
								</fieldset>
							</td>
						</tr>
					</table>

					<hr>

					<table class="form-table">
						<tr>
							<th scope="row"><?php _e('Auto-Formatting', 'wpps'); ?></th>
							<td>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_auto_content" value="1" <?php checked( $config['wpps_auto_content'], 1 ); ?>>
										<span><?php _e('Disable auto-formatting content', 'wpps'); ?></span>
									</label>
								</fieldset>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_auto_excerpt" value="1" <?php checked( $config['wpps_auto_excerpt'], 1 ); ?>>
										<span><?php _e('Disable auto-formatting excerpts', 'wpps'); ?></span>
									</label>
								</fieldset>
							</td>
						</tr>
					</table>

					<hr>

				</div>

				<div id="tabs-2" class="tab-content">
					<table class="form-table">
						<tr>
							<th scope="row"><?php _e('Compression', 'wpps'); ?></th>
							<td>
								<fieldset>
									<label>
										<input type="checkbox" name="output_compression" value="1" <?php checked( $config['output_compression'], 1 ); ?>>
										<span><?php _e('Enable GZIP compression', 'wpps'); ?></span>
									</label>
									<p class="description"><strong><?php _e('Warning:', 'wpps'); ?></strong> <?php _e('this can sometimes interfere with other plugins. You can often enable GZIP compression from cPanel or Plesk, or request activation from your website hosting company.', 'wpps'); ?></p>
								</fieldset>
							</td>  
						</tr>
					</table>

					<hr>

					<table class="form-table">
						<tr>
							<th scope="row"><?php _e('Pings', 'wpps'); ?></th>
							<td>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_self_ping" value="1" <?php checked( $config['wpps_self_ping'], 1 ); ?>>
										<span><?php _e('Disable self-ping', 'wpps'); ?></span>
									</label>
									<p class="description"><?php _e('Stops WordPress from registering internal links as ‘pings’.', 'wpps'); ?></p>
								</fieldset>
							</td>  
						</tr>
					</table>

					<hr>

					<table class="form-table">
						<tr>
							<th scope="row"><?php _e('Version strings', 'wpps'); ?></th>
							<td>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_remove_script_version" value="1" <?php checked( $config['wpps_remove_script_version'], 1 ); ?>>
										<span><?php _e('Remove the version query strings from scripts and styles', 'wpps'); ?></span>
									</label>
									<p class="description"><?php _e('Query strings can cause problems for browser caching. Some browsers don’t cache files with query strings.', 'wpps'); ?></p>
								</fieldset>
							</td>
						</tr>
					</table>

					<hr>

					<table class="form-table">
						<tr>
							<th scope="row"><?php _e('Jetpack', 'wpps'); ?></th>
							<td>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_jetpack_devicepx" value="1" <?php checked( $config['wpps_jetpack_devicepx'], 1 ); ?>>
										<span><?php _e('Remove <code>devicepx</code> script', 'wpps'); ?></span>
									</label>
									<p class="description"><?php _e('The Jetpack plugin includes a script called <code>devicepx</code> that handles support for retina/HiDPI versions of files  such as Gravatars. Remove if unnecessary.', 'wpps'); ?></p>
								</fieldset>
							</td>
						</tr>
					</table>

					<hr>

				</div>
				
				<div id="tabs-3" class="tab-content">

					<table class="form-table">
						<tr>
							<th scope="row"><?php _e('WordPress Version', 'wpps'); ?></th>
							<td>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_remove_wp_version" value="1" <?php checked( $config['wpps_remove_wp_version'], 1 ); ?>>
										<span><?php _e('Remove the WordPress version number', 'wpps'); ?></span>
									</label>
									<p class="description"><?php _e('This stops potential hackers from being able to identify which version of WordPress you are using and what vulnerabilities you might be exposed to.', 'wpps'); ?></p>
								</fieldset>
							</td>
						</tr>
					</table>

					<hr>

					<table class="form-table">
						<tr>
							<th scope="row"><?php _e('XMLRPC', 'wpps'); ?></th>
							<td>
								<fieldset>
									<label>
										<input type="checkbox" name="xmlrpc_enabled" value="1" <?php checked( $config['xmlrpc_enabled'], 1 ); ?>>
										<span><?php _e('Disable XMLRPC', 'wpps'); ?></span>
									</label>
									<p class="description"><?php _e('This will disable external editors that rely on XMLRPC to connect with your WordPress installion.', 'wpps'); ?></p>
								</fieldset>
								<br>
								<fieldset>
									<label>
										<input type="checkbox" name="atom_service_url_filter" value="1" <?php checked( $config['atom_service_url_filter'], 1 ); ?>>
										<span><?php _e('Disable XMLRPC SSL Testing', 'wpps'); ?></span>
									</label>
									<p class="description"><?php _e('Prevents WordPress from testing XMLRPC SSL capability when XMLRPC not in use', 'wpps'); ?></p>
								</fieldset>
							</td>
						</tr>
					</table>

					<hr>

					<table class="form-table">
						<tr>
							<th scope="row"><?php _e('Comments', 'wpps'); ?></th>
							<td>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_closeCommentsGlobaly" value="1" <?php checked( $config['wpps_closeCommentsGlobaly'], 1 ); ?>>
										<span><?php _e('Disable comments', 'wpps'); ?></span>
									</label>
								</fieldset>
								<div class="wpps_menu_comments_sub">
									<fieldset>
										<label>
											<input type="checkbox" name="wpps_media_comment_status" value="1" <?php checked( $config['wpps_media_comment_status'], 1 ); ?>>
											<span><?php _e('Disable comments on media files', 'wpps'); ?></span>
										</label>
									</fieldset>
									<fieldset>
										<label>
											<input type="checkbox" name="wpps_clickable_comments" value="1" <?php checked( $config['wpps_clickable_comments'], 1 ); ?>>
											<span><?php _e('Disable active links in comments', 'wpps'); ?></span>
										</label>
									</fieldset>
									<fieldset>
										<label>
											<input type="checkbox" name="wpps_comment_url" value="1" <?php checked( $config['wpps_comment_url'], 1 ); ?>>
											<span><?php _e('Remove the ‘URL’ field from the comments form', 'wpps'); ?></span>
										</label>
									</fieldset>
									<fieldset>
										<label>
											<input type="number" class="small-text" min="0" name="wpps_minimum_comment_length" value="<?php echo $config['wpps_minimum_comment_length']; ?>">
											<span><?php _e('Minimum number of characters required in a comment', 'wpps'); ?></span>
										</label>
									</fieldset>
								</div>							
							</td>
						</tr>
					</table>

					<hr>

				</div>

				<div id="tabs-4" class="tab-content">

					<table class="form-table">
						<tr>
							<th scope="row"><?php _e('Admin Bar', 'wpps'); ?></th>
							<td>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_admin_bar" value="1" <?php checked( $config['wpps_admin_bar'], 1 ); ?>>
										<span><?php _e('Hide the Admin bar from front-facing pages', 'wpps'); ?></span>
									</label>
								</fieldset>
							</td>
						</tr>
					</table>

					<hr>					

					<table class="form-table">
						<tr>
							<th scope="row"><?php _e('Statistics', 'wpps'); ?></th>
							<td>
								<fieldset>
									<label>
										<input type="checkbox" name="stats_admin_footer" value="1" <?php checked( $config['stats_admin_footer'], 1 ); ?>>
										<span><?php _e('Show database statistics', 'wpps'); ?></span>
									</label>
								</fieldset>
								<p class="description"><?php _e('Display database queries, time spent and memory consumption in the footer of Admin pages.', 'wpps'); ?></p>
							</td>
						</tr>
					</table>

					<hr>
					
					<table class="form-table">
						<tr>
							<th scope="row">
								<label for="wpps_replace_howdy"><?php _e('WordPress greeting', 'wpps'); ?></label>
							</th>
							<td>
								<input type="text" class="regular-text" name="wpps_replace_howdy" value="<?php echo $config['wpps_replace_howdy']; ?>">
								<p class="description"><?php _e('Change the default WordPress greeting on Admin pages.', 'wpps'); ?></p>
							</td>
						</tr>
					</table>

					<hr>

					<table class="form-table">
						<tr>
							<th scope="row"><?php _e('Open Sans font', 'wpps'); ?></th>
							<td>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_remove_wp_open_sans" value="1" <?php checked( $config['wpps_remove_wp_open_sans'], 1 ); ?>>
										<span><?php _e('Remove ‘Open Sans’ font from Admin pages', 'wpps'); ?></span>
									</label>
									<p class="description"><?php _e('WordPress uses the Open Sans font from Google webfonts on Admin pages. Remove this if it is causing errors or you don’t want the additional overhead.', 'wpps'); ?></p>
								</fieldset>
							</td>
						</tr>
					</table>

					<hr>

					<table class="form-table">
						<tr>
							<th scope="row"><?php _e('Dashboard Widgets', 'wpps'); ?></th>
							<td>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_dash_primary" value="1" <?php checked( $config['wpps_dash_primary'], 1 ); ?>>
										<span><?php _e('Remove ‘WordPress Blog’ widget', 'wpps'); ?></span>
									</label>
								</fieldset>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_dash_secondary" value="1" <?php checked( $config['wpps_dash_secondary'], 1 ); ?>>
										<span><?php _e('Remove ‘Other WordPress News’ widget', 'wpps'); ?></span>
									</label>
								</fieldset>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_dash_right_now" value="1" <?php checked( $config['wpps_dash_right_now'], 1 ); ?>>
										<span><?php _e('Remove ‘Right Now’ widget', 'wpps'); ?></span>
									</label>
								</fieldset>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_dash_incoming_links" value="1" <?php checked( $config['wpps_dash_incoming_links'], 1 ); ?>>
										<span><?php _e('Remove ‘Incoming Links’ widget', 'wpps'); ?></span>
									</label>
								</fieldset>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_dash_quick_press" value="1" <?php checked( $config['wpps_dash_quick_press'], 1 ); ?>>
										<span><?php _e('Remove ‘Quick Press’ widget', 'wpps'); ?></span>
									</label>
								</fieldset>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_dash_recent_drafts" value="1" <?php checked( $config['wpps_dash_recent_drafts'], 1 ); ?>>
										<span><?php _e('Remove ‘Recent Drafts’ widget', 'wpps'); ?></span>
									</label>
								</fieldset>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_dash_recent_comments" value="1" <?php checked( $config['wpps_dash_recent_comments'], 1 ); ?>>
										<span><?php _e('Remove ‘Recent Comments’ widget', 'wpps'); ?></span>
									</label>
								</fieldset>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_dash_plugins" value="1" <?php checked( $config['wpps_dash_plugins'], 1 ); ?>>
										<span><?php _e('Remove ‘Plugins’ widget', 'wpps'); ?></span>
									</label>
								</fieldset>
							</td>
						</tr>
					</table>

					<hr>

					<table class="form-table">
						<tr>
							<th scope="row"><?php _e('Menu items', 'wpps'); ?></th>
							<td>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_menu_wp" value="1" <?php checked( $config['wpps_menu_wp'], 1 ); ?>>
										<span><?php _e('Remove WordPress menu', 'wpps'); ?></span>
									</label>
								</fieldset>
								<p class="description"><?php _e('Remove the ‘WordPress’ menu from the top left of the Admin section or select individual options to remove.', 'wpps'); ?></p>
								<br>

								<div class="wpps_menu_wp_sub">
									<fieldset>
										<label>
											<input type="checkbox" name="wpps_menu_about" value="1" <?php checked( $config['wpps_menu_about'], 1 ); ?>>
											<span><?php _e('About', 'wpps'); ?></span>
										</label>
									</fieldset>
									<fieldset>
										<label>
											<input type="checkbox" name="wpps_menu_wporg" value="1" <?php checked( $config['wpps_menu_wporg'], 1 ); ?>>
											<span><?php _e('WordPress.org', 'wpps'); ?></span>
										</label>
									</fieldset>
									<fieldset>
										<label>
											<input type="checkbox" name="wpps_menu_documentation" value="1" <?php checked( $config['wpps_menu_documentation'], 1 ); ?>>
											<span><?php _e('Documentation', 'wpps'); ?></span>
										</label>
									</fieldset>
									<fieldset>
										<label>
											<input type="checkbox" name="wpps_menu_forums" value="1" <?php checked( $config['wpps_menu_forums'], 1 ); ?>>
											<span><?php _e('Support Forums', 'wpps'); ?></span>
										</label>
									</fieldset>
									<fieldset>
										<label>
											<input type="checkbox" name="wpps_menu_feedback" value="1" <?php checked( $config['wpps_menu_feedback'], 1 ); ?>>
											<span><?php _e('Feedback', 'wpps'); ?></span>
										</label>
									</fieldset>
									<fieldset>
										<label>
											<input type="checkbox" name="wpps_menu_site" value="1" <?php checked( $config['wpps_menu_site'], 1 ); ?>>
											<span><?php _e('View Site', 'wpps'); ?></span>
										</label>
									</fieldset>
								</div>
							</td>
						</tr>
					</table>

					<hr>

					<table class="form-table">
						<tr>
							<th scope="row"><?php _e('“All Settings” menu', 'wpps'); ?></th>
							<td>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_all_settings_link" value="1" <?php checked( $config['wpps_all_settings_link'], 1 ); ?>>
										<span><?php _e('Add new Admin menu item “All Settings”', 'wpps'); ?></span>
									</label>
								</fieldset>
							</td>
						</tr>
					</table>

					<hr>

				</div>

				<div id="tabs-5" class="tab-content">

					<table class="form-table">
						<tr>
							<th scope="row">
								<label for="wpss_custom_login_logo"><?php _e('Login logo', 'wpps'); ?></label>
							</th>
							<td>
								<input type="text" class="regular-text code" name="wpss_custom_login_logo" value="<?php echo $config['wpss_custom_login_logo']; ?>">
								<span class="description"><?php _e('URL of custom image, 300px x 200px', 'wpps'); ?></span>
								<p class="description"><?php _e('Use a custom logo on the login page. Leave blank for default.', 'wpps'); ?></p>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="wpps_custom_login_url"><?php _e('Login URL', 'wpps'); ?></label>
							</th>
							<td>
								<input type="text" class="regular-text code" name="wpps_custom_login_url" value="<?php echo $config['wpps_custom_login_url']; ?>">
								<p class="description"><?php _e('Use a custom URL on the login page logo. Leave blank for default.', 'wpps'); ?></p>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="wpps_custom_login_title"><?php _e('URL title attribute', 'wpps'); ?></label>
							</th>
							<td>
								<input type="text" name="wpps_custom_login_title" class="regular-text" value="<?php echo $config['wpps_custom_login_title']; ?>">
								<p class="description"><?php _e('Custom login URL Title Attribute. Leave blank for default.', 'wpps'); ?></p>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php _e('Errors', 'wpps'); ?></th>
							<td>
								<fieldset>
									<label>
										<input type="checkbox" name="login_errors" value="1" <?php checked( $config['login_errors'], 1 ); ?>>
										<span><?php _e('Hide detailed login form error messages', 'wpps'); ?></span>
									</label>
									<p class="description"><?php _e('By default WordPress shows detailed errors for failed login attempts. This can be a security risk.', 'wpps'); ?></p>
								</fieldset>
							</td>
						</tr>
					</table>

					<hr>

				</div>

				<div id="tabs-6" class="tab-content">

					<table class="form-table">
						<tr>
							<th scope="row"><?php _e('Google Analytics', 'wpps'); ?></th>
							<td>
								<p><a href="http://www.google.com/analytics/" rel="nofollow">Google Analytics</a> <?php _e('is powerful tracking and reporting feature for websites.', 'wpps'); ?></p>
								<p><?php _e('The following setttings will allow you to embed your Google tracking code on your WordPress site. Most users will only need to know their tracking code and whether they are using the new Universal Analytics tracking code or the old classic tracking code.', 'wpps'); ?></p>
								<p><?php _e('Note, you must be using the Universal Analytics tracking code (on your site and set in your property within Google Analytics) before you can use the advanced features.', 'wpps'); ?></p>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php _e('Enable', 'wpps'); ?></th>
							<td>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_ga_insert" value="1" <?php checked( $config['wpps_ga_insert'], 1 ); ?>>
										<span><?php _e('Add Google Analytics tracking code', 'wpps'); ?></span>
									</label>
								</fieldset>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php _e('Tracking ID', 'wpps'); ?></th>
							<td>
								<fieldset class="wpps_ga_sub">
									<label><?php _e('Google Analytics Tracking ID', 'wpps'); ?>: 
										<input type="text" class="regular-text" name="wpps_ga_id" value="<?php echo $config['wpps_ga_id']; ?>" required placeholder="UA-123456-78">
									</label>
									<p class="description"><strong><?php _e('Note:', 'wpps'); ?></strong> <?php _e('You <em>must</em> include the correct tracking ID for your site.', 'wpps'); ?></p>
								</fieldset>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php _e('Universal Analytics', 'wpps'); ?></th>
							<td>
								<fieldset class="wpps_ga_sub">
									<label>
										<input type="checkbox" name="wpps_ga_universal" value="1" <?php checked( $config['wpps_ga_universal'], 1 ); ?>>
										<span><?php _e('Use ‘Universal Analytics’ code', 'wpps'); ?></span>
									</label>
									<p class="description"><strong><?php _e('Warning:', 'wpps'); ?></strong> <?php _e('Do not use this code until your Google Analytics property has been transferred to Universal Analytics.', 'wpps'); ?> <a href="https://developers.google.com/analytics/devguides/collection/upgrade/guide" rel="nofollow"><?php _e('Learn more.', 'wpps'); ?></a></p>
								</fieldset>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php _e('Advanced Features', 'wpps'); ?></th>
							<td>
								<fieldset class="wpps_ga_sub wpps_ga_uni_sub">
									<label>
										<input type="checkbox" name="wpps_ga_ssl" value="1" <?php checked( $config['wpps_ga_ssl'], 1 ); ?>>
										<span><?php _e('Force SSL', 'wpps'); ?></span>
									</label>
									<p class="description"><?php _e('Send all data using SSL, even from insecure (HTTP) pages.', 'wpps'); ?></p>
								</fieldset>
								<br>
								<fieldset class="wpps_ga_sub wpps_ga_uni_sub">
									<label>
										<input type="checkbox" name="wpps_ga_display" value="1" <?php checked( $config['wpps_ga_display'], 1 ); ?>>
										<span><?php _e('Enable ‘Display Features’ plugin', 'wpps'); ?></span>
									</label>
									<p class="description"><?php _e('The display features plugin can be used to enable Advertising Features in Google Analytics, such as Remarketing, Demographics and Interest Reporting, and more.', 'wpps'); ?> <a href="https://support.google.com/analytics/answer/3450482" rel="nofollow"><?php _e('Learn more.', 'wpps'); ?></a></p>
								</fieldset>
							</td>
						</tr>
					</table>

				</div>

			</div>

			<p class="submit">
				<input type="submit" name="submit" class="button button-primary" value="<?php _e('Save All Changes', 'wpps'); ?>">
			</p>

		</form>

	</div>

	<?php 
	}
?>