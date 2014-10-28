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
				<a href="#tabs-1" class="nav-tab nav-tab-active">General</a>
				<a href="#tabs-2" class="nav-tab">Performance</a>
				<a href="#tabs-3" class="nav-tab">Security</a>
				<a href="#tabs-4" class="nav-tab">Administration</a>
				<a href="#tabs-5" class="nav-tab">Login</a>
				<a href="#tabs-6" class="nav-tab">Google Analytics</a>
			</h2>

			<div class="wpps-settings">

				<div id="tabs-1" class="tab-content">

					<table class="form-table">
						<tr>
							<th scope="row">Excerpts</th>
							<td>
								<fieldset>
									<label>
										<span>Number of words in excerpts: </span>
										<input type="text" class="small-text" name="wpps_excerpt_length" value="<?php echo $config['wpps_excerpt_length']; ?>">
									</label>
								</fieldset>
								<fieldset>
									<label>
										<span>"More" text string in excerpts: </span>
										<input type="text" class="small-text" name="wpps_excerpt_more" value="<?php echo $config['wpps_excerpt_more']; ?>">
									</label>
								</fieldset>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_page_excerpts" value="1" <?php checked( $config['wpps_page_excerpts'], 1 ); ?>>
										<span>Allow excerpts on Pages</span>
									</label>
								</fieldset>
							</td>
						</tr>
					</table>

					<hr>

					<table class="form-table">
						<tr>
							<th scope="row">“Read More”</th>
							<td>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_read_more" value="1" <?php checked( $config['wpps_read_more'], 1 ); ?>>
										<span>Disable “Read more” links from jumping to anchor</span>
									</label>
									<p class="description">When creating a <code>&lt;!--more--&gt;</code> link in WordPress the default action is to jump to the ‘next’ section.</p>
								</fieldset>
							</td>
					</table>

					<hr>

					<table class="form-table">
						<tr>
							<th scope="row">Custom Post Types</th>
							<td>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_searchAll" value="1" <?php checked( $config['wpps_searchAll'], 1 ); ?>>
										<span>Show Custom Post Types in the search results</span>
									</label>
								</fieldset>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_custom_feed_request" value="1" <?php checked( $config['wpps_custom_feed_request'], 1 ); ?>>
										<span>Show Custom Post Types in the RSS feed</span>
									</label>
								</fieldset>
							</td>
						</tr>
					</table>

					<hr>

					<table class="form-table">
						<tr>
							<th scope="row">Tags</th>
							<td>
								<fieldset>
									<label>
										<input type="checkbox" name="tags_support_all" value="1" <?php checked( $config['tags_support_all'], 1 ); ?>>
										<span>Allow tags on pages</span>
									</label>
								</fieldset>
								<fieldset>
									<label>
										<input type="checkbox" name="tags_support_query" value="1" <?php checked( $config['tags_support_query'], 1 ); ?>>
										<span>Ensure all tags are included in queries</span>
									</label>
								</fieldset>
							</td>
						</tr>
					</table>

					<hr>

					<table class="form-table">
						<tr>
							<th scope="row">Header</th>
							<td>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_rel_links" value="1" <?php checked( $config['wpps_rel_links'], 1 ); ?>>
										<span>Remove relational links for the posts adjacent to the current post</span>
									</label>
								</fieldset>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_wlw_manifest" value="1" <?php checked( $config['wpps_wlw_manifest'], 1 ); ?>>
										<span>Remove <em>Windows Live Writer</em> manifest link (wlwmanifest)</span>
									</label>
								</fieldset>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_rsd_link" value="1" <?php checked( $config['wpps_rsd_link'], 1 ); ?>>
										<span>Remove <abbr title="Really Simple Discovery">RSD</abbr> link</span>
									</label>
								</fieldset>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_short_link" value="1" <?php checked( $config['wpps_short_link'], 1 ); ?>>
										<span>Remove Shortlink</span>
									</label>
								</fieldset>
							</td>
						</tr>
					</table>

					<hr>

					<table class="form-table">
						<tr>
							<th scope="row">Uploads</th>
							<td>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_custom_upload_mimes" value="1" <?php checked( $config['wpps_custom_upload_mimes'], 1 ); ?>>
										<span>Allow SVG image uploads</span>
									</label>
								</fieldset>
							</td>
						</tr>
					</table>

					<hr>


					<table class="form-table">
						<tr>
							<th scope="row">HTML5 Support</th>
							<td>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_html5_support" value="1" <?php checked( $config['wpps_html5_support'], 1 ); ?>>
										<span>Use HTML5 markup for the comment forms, search forms, comment lists, images and captions.</span>
									</label>
								</fieldset>
							</td>
						</tr>
					</table>

					<hr>

					<table class="form-table">
						<tr>
							<th scope="row">Auto-Formatting</th>
							<td>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_auto_content" value="1" <?php checked( $config['wpps_auto_content'], 1 ); ?>>
										<span>Disable auto-formatting content</span>
									</label>
								</fieldset>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_auto_excerpt" value="1" <?php checked( $config['wpps_auto_excerpt'], 1 ); ?>>
										<span>Disable auto-formatting excerpts</span>
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
							<th scope="row">Compression</th>
							<td>
								<fieldset>
									<label>
										<input type="checkbox" name="output_compression" value="1" <?php checked( $config['output_compression'], 1 ); ?>>
										<span>Enable GZIP compression</span>
									</label>
									<p class="description"><strong>Warning:</strong> this can sometimes interfere with other plugins. You can often enable GZIP compression from cPanel or Plesk, or request activation from your website hosting company.</p>
								</fieldset>
							</td>  
						</tr>
					</table>

					<hr>

					<table class="form-table">
						<tr>
							<th scope="row">Pings</th>
							<td>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_self_ping" value="1" <?php checked( $config['wpps_self_ping'], 1 ); ?>>
										<span>Disable self-ping</span>
									</label>
									<p class="description">Stops WordPress from registering internal links as ‘pings’.</p>
								</fieldset>
							</td>  
						</tr>
					</table>

					<hr>

					<table class="form-table">
						<tr>
							<th scope="row">Version strings</th>
							<td>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_remove_script_version" value="1" <?php checked( $config['wpps_remove_script_version'], 1 ); ?>>
										<span>Remove the version query strings from scripts and styles</span>
									</label>
									<p class="description">Query strings can cause problems for browser caching. Some browsers don’t cache files with query strings.</p>
								</fieldset>
							</td>
						</tr>
					</table>

					<hr>

					<table class="form-table">
						<tr>
							<th scope="row">Jetpack</th>
							<td>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_jetpack_devicepx" value="1" <?php checked( $config['wpps_jetpack_devicepx'], 1 ); ?>>
										<span>Remove <code>devicepx</code> script</span>
									</label>
									<p class="description">The Jetpack plugin includes a script called <code>devicepx</code> that handles support for retina/HiDPI versions of files  such as Gravatars. Remove if unnecessary.</p>
								</fieldset>
							</td>
						</tr>
					</table>

					<hr>

				</div>
				
				<div id="tabs-3" class="tab-content">

					<table class="form-table">
						<tr>
							<th scope="row">WordPress Version</th>
							<td>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_remove_wp_version" value="1" <?php checked( $config['wpps_remove_wp_version'], 1 ); ?>>
										<span>Remove the WordPress version number</span>
									</label>
									<p class="description">This stops potential hackers from being able to identify which version of WordPress you are using and what vulnerabilities you might be exposed to.</p>
								</fieldset>
							</td>
						</tr>
					</table>

					<hr>

					<table class="form-table">
						<tr>
							<th scope="row">XMLRPC</th>
							<td>
								<fieldset>
									<label>
										<input type="checkbox" name="xmlrpc_enabled" value="1" <?php checked( $config['xmlrpc_enabled'], 1 ); ?>>
										<span>Disable XMLRPC</span>
									</label>
									<p class="description">This will disable external editors that rely on XMLRPC to connect with your WordPress installion.</p>
								</fieldset>
								<br>
								<fieldset>
									<label>
										<input type="checkbox" name="atom_service_url_filter" value="1" <?php checked( $config['atom_service_url_filter'], 1 ); ?>>
										<span>Disable XMLRPC SSL Testing</span>
									</label>
									<p class="description">Prevents WordPress from testing XMLRPC SSL capability when XMLRPC not in use</p>
								</fieldset>
							</td>
						</tr>
					</table>

					<hr>

					<table class="form-table">
						<tr>
							<th scope="row">Comments</th>
							<td>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_closeCommentsGlobaly" value="1" <?php checked( $config['wpps_closeCommentsGlobaly'], 1 ); ?>>
										<span>Disable comments</span>
									</label>
								</fieldset>
								<div class="wpps_menu_comments_sub">
									<fieldset>
										<label>
											<input type="checkbox" name="wpps_media_comment_status" value="1" <?php checked( $config['wpps_media_comment_status'], 1 ); ?>>
											<span>Disable comments on media files</span>
										</label>
									</fieldset>
									<fieldset>
										<label>
											<input type="checkbox" name="wpps_clickable_comments" value="1" <?php checked( $config['wpps_clickable_comments'], 1 ); ?>>
											<span>Disable active links in comments</span>
										</label>
									</fieldset>
									<fieldset>
										<label>
											<input type="checkbox" name="wpps_comment_url" value="1" <?php checked( $config['wpps_comment_url'], 1 ); ?>>
											<span>Remove the ‘URL’ field from the comments form</span>
										</label>
									</fieldset>
									<fieldset>
										<label>
											<input type="number" class="small-text" min="0" name="wpps_minimum_comment_length" value="<?php echo $config['wpps_minimum_comment_length']; ?>">
											<span>Minimum number of characters required in a comment</span>
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
							<th scope="row">Admin Bar</th>
							<td>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_admin_bar" value="1" <?php checked( $config['wpps_admin_bar'], 1 ); ?>>
										<span>Hide the Admin bar from front-facing pages</span>
									</label>
								</fieldset>
							</td>
						</tr>
					</table>

					<hr>					

					<table class="form-table">
						<tr>
							<th scope="row">Statistics</th>
							<td>
								<fieldset>
									<label>
										<input type="checkbox" name="stats_admin_footer" value="1" <?php checked( $config['stats_admin_footer'], 1 ); ?>>
										<span>Show database statistics</span>
									</label>
								</fieldset>
								<p class="description">Display database queries, time spent and memory consumption in the footer of Admin pages.</p>
							</td>
						</tr>
					</table>

					<hr>
					
					<table class="form-table">
						<tr>
							<th scope="row">
								<label for="wpps_replace_howdy">WordPress greeting</label>
							</th>
							<td>
								<input type="text" class="regular-text" name="wpps_replace_howdy" value="<?php echo $config['wpps_replace_howdy']; ?>">
								<p class="description">Change the default WordPress greeting on Admin pages</p>
							</td>
						</tr>
					</table>

					<hr>

					<table class="form-table">
						<tr>
							<th scope="row">Open Sans font</th>
							<td>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_remove_wp_open_sans" value="1" <?php checked( $config['wpps_remove_wp_open_sans'], 1 ); ?>>
										<span>Remove ‘Open Sans’ font from Admin pages</span>
									</label>
									<p class="description">WordPress uses the Open Sans font from Google webfonts on Admin pages. Remove this if it is causing errors or you don’t want the additional overhead.</p>
								</fieldset>
							</td>
						</tr>
					</table>

					<hr>

					<table class="form-table">
						<tr>
							<th scope="row">Dashboard Widgets</th>
							<td>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_dash_primary" value="1" <?php checked( $config['wpps_dash_primary'], 1 ); ?>>
										<span>Remove ‘WordPress Blog’ widget</span>
									</label>
								</fieldset>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_dash_secondary" value="1" <?php checked( $config['wpps_dash_secondary'], 1 ); ?>>
										<span>Remove ‘Other WordPress News’ widget</span>
									</label>
								</fieldset>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_dash_right_now" value="1" <?php checked( $config['wpps_dash_right_now'], 1 ); ?>>
										<span>Remove ‘Right Now’ widget</span>
									</label>
								</fieldset>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_dash_incoming_links" value="1" <?php checked( $config['wpps_dash_incoming_links'], 1 ); ?>>
										<span>Remove ‘Incoming Links’ widget</span>
									</label>
								</fieldset>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_dash_quick_press" value="1" <?php checked( $config['wpps_dash_quick_press'], 1 ); ?>>
										<span>Remove ‘Quick Press’ widget</span>
									</label>
								</fieldset>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_dash_recent_drafts" value="1" <?php checked( $config['wpps_dash_recent_drafts'], 1 ); ?>>
										<span>Remove ‘Recent Drafts’ widget</span>
									</label>
								</fieldset>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_dash_recent_comments" value="1" <?php checked( $config['wpps_dash_recent_comments'], 1 ); ?>>
										<span>Remove ‘Recent Comments’ widget</span>
									</label>
								</fieldset>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_dash_plugins" value="1" <?php checked( $config['wpps_dash_plugins'], 1 ); ?>>
										<span>Remove ‘Plugins’ widget</span>
									</label>
								</fieldset>
							</td>
						</tr>
					</table>

					<hr>

					<table class="form-table">
						<tr>
							<th scope="row">Menu items</th>
							<td>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_menu_wp" value="1" <?php checked( $config['wpps_menu_wp'], 1 ); ?>>
										<span>Remove WordPress menu</span>
									</label>
								</fieldset>
								<p class="description">Remove the ‘WordPress’ menu from the top left of the Admin section or select individual options to remove.</p>
								<br>

								<div class="wpps_menu_wp_sub">
									<fieldset>
										<label>
											<input type="checkbox" name="wpps_menu_about" value="1" <?php checked( $config['wpps_menu_about'], 1 ); ?>>
											<span>About</span>
										</label>
									</fieldset>
									<fieldset>
										<label>
											<input type="checkbox" name="wpps_menu_wporg" value="1" <?php checked( $config['wpps_menu_wporg'], 1 ); ?>>
											<span>WordPress.org</span>
										</label>
									</fieldset>
									<fieldset>
										<label>
											<input type="checkbox" name="wpps_menu_documentation" value="1" <?php checked( $config['wpps_menu_documentation'], 1 ); ?>>
											<span>Documentation</span>
										</label>
									</fieldset>
									<fieldset>
										<label>
											<input type="checkbox" name="wpps_menu_forums" value="1" <?php checked( $config['wpps_menu_forums'], 1 ); ?>>
											<span>Support Forums</span>
										</label>
									</fieldset>
									<fieldset>
										<label>
											<input type="checkbox" name="wpps_menu_feedback" value="1" <?php checked( $config['wpps_menu_feedback'], 1 ); ?>>
											<span>Feedback</span>
										</label>
									</fieldset>
									<fieldset>
										<label>
											<input type="checkbox" name="wpps_menu_site" value="1" <?php checked( $config['wpps_menu_site'], 1 ); ?>>
											<span>View Site</span>
										</label>
									</fieldset>
								</div>
							</td>
						</tr>
					</table>

					<hr>

					<table class="form-table">
						<tr>
							<th scope="row">“All Settings” menu</th>
							<td>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_all_settings_link" value="1" <?php checked( $config['wpps_all_settings_link'], 1 ); ?>>
										<span>Add new Admin menu item “All Settings”</span>
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
								<label for="wpss_custom_login_logo">Login logo</label>
							</th>
							<td>
								<input type="text" class="regular-text code" name="wpss_custom_login_logo" value="<?php echo $config['wpss_custom_login_logo']; ?>">
								<span class="description">URL of custom image, 300px x 200px</span>
								<p class="description">Use a custom logo on the login page. Leave blank for default.</p>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="wpps_custom_login_url">Login URL</label>
							</th>
							<td>
								<input type="text" class="regular-text code" name="wpps_custom_login_url" value="<?php echo $config['wpps_custom_login_url']; ?>">
								<p class="description">Use a custom URL on the login page logo. Leave blank for default.</p>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="wpps_custom_login_title">URL title attribute</label>
							</th>
							<td>
								<input type="text" name="wpps_custom_login_title" class="regular-text" value="<?php echo $config['wpps_custom_login_title']; ?>">
								<p class="description">Custom login URL Title Attribute. Leave blank for default.</p>
							</td>
						</tr>
						<tr>
							<th scope="row">Errors</th>
							<td>
								<fieldset>
									<label>
										<input type="checkbox" name="login_errors" value="1" <?php checked( $config['login_errors'], 1 ); ?>>
										<span>Hide detailed login form error messages</span>
									</label>
									<p class="description">By default WordPress shows detailed errors for failed login attempts. This can be a security risk.</p>
								</fieldset>
							</td>
						</tr>
					</table>

					<hr>

				</div>

				<div id="tabs-6" class="tab-content">

					<table class="form-table">
						<tr>
							<th scope="row">Google Analytics</th>
							<td>
								<p><a href="http://www.google.com/analytics/" rel="nofollow">Google Analytics</a> is powerful tracking and reporting feature for websites</p>
								<p>The following setttings will allow you to embed your Google tracking code on your WordPress site. Most users will only need to know their tracking code and whether they are using the new Universal Analytics tracking code or the old classic tracking code.</p>
								<p>Note, you must be using the Universal Analytics tracking code (on your site and set in your property within Google Analytics) before you can use the advanced features.</p>
							</td>
						</tr>
						<tr>
							<th scope="row">Enable</th>
							<td>
								<fieldset>
									<label>
										<input type="checkbox" name="wpps_ga_insert" value="1" <?php checked( $config['wpps_ga_insert'], 1 ); ?>>
										<span>Add Google Analytics tracking code</span>
									</label>
								</fieldset>
							</td>
						</tr>
						<tr>
							<th scope="row">Tracking ID</th>
							<td>
								<fieldset class="wpps_ga_sub">
									<label>Google Analytics Tracking ID: 
										<input type="text" class="regular-text" name="wpps_ga_id" value="<?php echo $config['wpps_ga_id']; ?>" required placeholder="UA-123456-78">
									</label>
									<p class="description"><strong>Note:</strong> You <em>must</em> include the correct tracking ID for your site.</p>
								</fieldset>
							</td>
						</tr>
						<tr>
							<th scope="row">Universal Analytics</th>
							<td>
								<fieldset class="wpps_ga_sub">
									<label>
										<input type="checkbox" name="wpps_ga_universal" value="1" <?php checked( $config['wpps_ga_universal'], 1 ); ?>>
										<span>Use ‘Universal Analytics’ code</span>
									</label>
									<p class="description"><strong>Warning:</strong> Do not use this code until your Google Analytics property has been transferred to Universal Analytics. <a href="https://developers.google.com/analytics/devguides/collection/upgrade/guide" rel="nofollow">Learn more.</a></p>
								</fieldset>
							</td>
						</tr>
						<tr>
							<th scope="row">Advanced Features</th>
							<td>
								<fieldset class="wpps_ga_sub wpps_ga_uni_sub">
									<label>
										<input type="checkbox" name="wpps_ga_ssl" value="1" <?php checked( $config['wpps_ga_ssl'], 1 ); ?>>
										<span>Force SSL</span>
									</label>
									<p class="description">Send all data using SSL, even from insecure (HTTP) pages.</p>
								</fieldset>
								<br>
								<fieldset class="wpps_ga_sub wpps_ga_uni_sub">
									<label>
										<input type="checkbox" name="wpps_ga_display" value="1" <?php checked( $config['wpps_ga_display'], 1 ); ?>>
										<span>Enable ‘Display Features’ plugin</span>
									</label>
									<p class="description">The display features plugin can be used to enable Advertising Features in Google Analytics, such as Remarketing, Demographics and Interest Reporting, and more. <a href="https://support.google.com/analytics/answer/3450482" rel="nofollow">Learn more.</a></p>
								</fieldset>
							</td>
						</tr>
					</table>

				</div>

			</div>

			<p class="submit">
				<input type="submit" name="submit" class="button button-primary" value="<?php _e( 'Save All Changes' ); ?>">
			</p>

		</form>

	</div>

	<?php 
	}
?>