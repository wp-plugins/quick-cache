<?php
/*
Copyright: © 2009 WebSharks, Inc. ( coded in the USA )
<mailto:support@websharks-inc.com> <http://www.websharks-inc.com/>

Released under the terms of the GNU General Public License.
You should have received a copy of the GNU General Public License,
along with this software. In the main directory, see: /licensing/
If not, see: <http://www.gnu.org/licenses/>.
*/
/*
Direct access denial.
*/
if (realpath (__FILE__) === realpath ($_SERVER["SCRIPT_FILENAME"]))
	exit ("Do not access this file directly.");
/*
Function for saving all options from any page.
*/
if (!function_exists ("ws_plugin__qcache_update_all_options"))
	{
		function ws_plugin__qcache_update_all_options ()
			{
				do_action ("ws_plugin__qcache_before_update_all_options", get_defined_vars ());
				/**/
				if (($nonce = $_POST["ws_plugin__qcache_options_save"]) && wp_verify_nonce ($nonce, "ws-plugin--qcache-options-save"))
					{
						if (!$GLOBALS["WS_PLUGIN__"]["qcache"]["c"]["is_multisite"] || /* When we are NOT in multisite mode. Or when we are, but it's the Super Admin in the main site. */
						($GLOBALS["WS_PLUGIN__"]["qcache"]["c"]["is_multisite"] && (int)$GLOBALS["blog_id"] === 1 && ((function_exists ("is_super_admin") && is_super_admin ()) || is_site_admin ())))
							{
								$options = $GLOBALS["WS_PLUGIN__"]["qcache"]["o"]; /* Get current options. */
								/**/
								foreach ($_POST as $key => $value) /* Go through each post variable and look for qcache. */
									{
										if (preg_match ("/^" . preg_quote ("ws_plugin__qcache", "/") . "/", $key)) /* Look for keys. */
											{
												if ($key === "ws_plugin__qcache_configured") /* This is a special configuration option. */
													{
														update_option ("ws_plugin__qcache_configured", trim (stripslashes ($value))); /* Update this option separately. */
														/**/
														$GLOBALS["WS_PLUGIN__"]["qcache"]["c"]["configured"] = trim (stripslashes ($value)); /* Update configuration on-the-fly. */
													}
												else /* We need to place this option into the array. Here we remove the ws_plugin__qcache_ portion on the beginning. */
													{
														(is_array ($value)) ? array_shift ($value) : null; /* Arrays should be padded, 1st key is removed. */
														$options[preg_replace ("/^" . preg_quote ("ws_plugin__qcache_", "/") . "/", "", $key)] = $value;
													}
											}
									}
								/**/
								$options["options_version"] = $GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["options_version"] + 0.001; /* Increment options version. */
								/**/
								$options = ws_plugin__qcache_configure_options_and_their_defaults ($options); /* Also updates the global options array. */
								/**/
								eval ('foreach(array_keys(get_defined_vars())as$__v)$__refs[$__v]=&$$__v;');
								do_action ("ws_plugin__qcache_during_update_all_options", get_defined_vars ());
								unset ($__refs, $__v); /* Unset defined __refs, __v. */
								/**/
								update_option ("ws_plugin__qcache_options", $options); /* Update options. */
								/**/
								if ($GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["enabled"]) /* Enabled? */
									{
										if (ws_plugin__qcache_add_wp_cache ()) /* Add WP_CACHE to the config file. */
											if (ws_plugin__qcache_add_advanced ()) /* Add the advanced-cache.php file. */
												if (ws_plugin__qcache_add_garbage_collector ()) /* Add the garbage collector. */
													if (ws_plugin__qcache_schedule_cache_dir_delete ()) /* Delete/reset the cache. */
														{
															if ($GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["auto_cache_enabled"] && $GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["auto_cache_agent"]/**/
															&& ($GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["auto_cache_sitemap_url"] || $GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["auto_cache_additional_urls"]))
																ws_plugin__qcache_add_auto_cache_engine (); /* Add Auto-Cache. */
															else /* Otherwise, we need to delete Auto-Cache. */
																ws_plugin__qcache_delete_auto_cache_engine ();
															/**/
															$notice = '<strong>Options updated, and the cache was reset to avoid conflicts.</strong>';
															ws_plugin__qcache_admin_notices (); /* Flush all notices. */
															ws_plugin__qcache_display_admin_notice ($notice);
															$enabled = true; /* Enabled successfully. */
														}
										/**/
										if (!$enabled) /* Otherwise, we need to throw a warning up. The site owner needs to try again. */
											{
												$notice = '<strong>Error:</strong> Could not enable Quick Cache. Please check permissions on <code>/wp-config.php</code>, <code>/wp-content/</code>';
												$notice .= ' and <code>/wp-content/cache/</code>. Permissions need to be <code>755</code> or higher.';
												ws_plugin__qcache_admin_notices (); /* Flush all notices. */
												ws_plugin__qcache_display_admin_notice ($notice, true);
											}
									}
								/**/
								else if (!$GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["enabled"]) /* Disabled? */
									{
										if (ws_plugin__qcache_delete_wp_cache ()) /* Delete WP_CACHE from the config file. */
											if (ws_plugin__qcache_delete_advanced ()) /* Delete the advanced-cache.php file. */
												if (ws_plugin__qcache_delete_garbage_collector ()) /* Delete the garbage collector. */
													if (ws_plugin__qcache_schedule_cache_dir_delete ()) /* Delete the cache dir. */
														{
															ws_plugin__qcache_delete_auto_cache_engine (); /* Delete Auto-Cache. */
															/**/
															$notice = '<strong>Options updated. Quick Cache disabled.</strong>';
															ws_plugin__qcache_admin_notices (); /* Flush all notices. */
															ws_plugin__qcache_display_admin_notice ($notice);
															$disabled = true; /* Disabled successfully. */
														}
										/**/
										if (!$disabled) /* Otherwise, we need to throw a warning up. The site owner needs to try again. */
											{
												$notice = '<strong>Error:</strong> Could not disable Quick Cache. Please check permissions on <code>/wp-config.php</code>, <code>/wp-content/</code>';
												$notice .= ' and <code>/wp-content/cache/</code>. Permissions need to be <code>755</code> or higher.';
												ws_plugin__qcache_admin_notices (); /* Flush all notices. */
												ws_plugin__qcache_display_admin_notice ($notice, true);
											}
									}
							}
						else /* Else, a security warning needs to be issued. Only Super Administrators are allowed to modify Quick Cache. */
							{
								$notice = '<strong>Quick Cache can ONLY be modified by a Super Administrator, while operating on the main site ( w/ blog ID# 1 ).</strong>';
								ws_plugin__qcache_display_admin_notice ($notice, true);
							}
					}
				else if (($nonce = $_POST["ws_plugin__qcache_clear_cache"]) && wp_verify_nonce ($nonce, "ws-plugin--qcache-clear-cache"))
					{
						if (ws_plugin__qcache_delete_cache_dir ()) /* Delete/reset the cache in real-time, immediately. */
							{
								$notice = '<strong>Cache cleared ( purged in real-time ).</strong>';
								ws_plugin__qcache_display_admin_notice ($notice);
							}
						else /* Else there was a problem during deletion of the cache directory. */
							{
								$notice = '<strong>Unable to clear the cache.</strong>';
								ws_plugin__qcache_display_admin_notice ($notice, true);
							}
					}
				/**/
				do_action ("ws_plugin__qcache_after_update_all_options", get_defined_vars ());
				/**/
				return;
			}
	}
/*
Add the options menus & sub-menus.
Attach to: add_action("admin_menu");
*/
if (!function_exists ("ws_plugin__qcache_add_admin_options"))
	{
		function ws_plugin__qcache_add_admin_options ()
			{
				do_action ("ws_plugin__qcache_before_add_admin_options", get_defined_vars ());
				/**/
				if (!$GLOBALS["WS_PLUGIN__"]["qcache"]["c"]["is_multisite"] || /* When we are NOT in multisite mode. Or when we are, but it's the Super Admin in the main site. */
				($GLOBALS["WS_PLUGIN__"]["qcache"]["c"]["is_multisite"] && (int)$GLOBALS["blog_id"] === 1 && ((function_exists ("is_super_admin") && is_super_admin ()) || is_site_admin ())))
					{
						add_filter ("plugin_action_links", "_ws_plugin__qcache_add_settings_link", 10, 2);
						/**/
						if (apply_filters ("ws_plugin__qcache_during_add_admin_options_create_menu_items", true, get_defined_vars ()))
							{
								add_menu_page ("Quick Cache", "Quick Cache", "edit_plugins", "ws-plugin--qcache-options", "ws_plugin__qcache_options_page");
								/**/
								if (apply_filters ("ws_plugin__qcache_during_add_admin_options_add_options_page", true, get_defined_vars ()))
									add_submenu_page ("ws-plugin--qcache-options", "Quick Cache Config Options", "Config Options", "edit_plugins", "ws-plugin--qcache-options", "ws_plugin__qcache_options_page");
								/**/
								if (apply_filters ("ws_plugin__qcache_during_add_admin_options_add_info_page", true, get_defined_vars ()))
									add_submenu_page ("ws-plugin--qcache-options", "Quick Cache Info", "Quick Cache Info", "edit_plugins", "ws-plugin--qcache-info", "ws_plugin__qcache_info_page");
							}
					}
				else /* Else we need to hide Quick Cache from the plugins menu. It is not accessible. */
					{
						add_filter ("all_plugins", "_ws_plugin__qcache_hide_from_plugins_menu");
					}
				/**/
				do_action ("ws_plugin__qcache_after_add_admin_options", get_defined_vars ());
				/**/
				return;
			}
	}
/*
A sort of callback function to add the settings link.
Attach to: add_filter("plugin_action_links");
*/
if (!function_exists ("_ws_plugin__qcache_add_settings_link"))
	{
		function _ws_plugin__qcache_add_settings_link ($links = array (), $file = "")
			{
				eval ('foreach(array_keys(get_defined_vars())as$__v)$__refs[$__v]=&$$__v;');
				do_action ("_ws_plugin__qcache_before_add_settings_link", get_defined_vars ());
				unset ($__refs, $__v); /* Unset defined __refs, __v. */
				/**/
				if (preg_match ("/" . preg_quote ($file, "/") . "$/", $GLOBALS["WS_PLUGIN__"]["qcache"]["l"]) && is_array ($links))
					{
						$settings = '<a href="admin.php?page=ws-plugin--qcache-options">Settings</a>';
						array_unshift ($links, $settings);
						/**/
						eval ('foreach(array_keys(get_defined_vars())as$__v)$__refs[$__v]=&$$__v;');
						do_action ("_ws_plugin__qcache_during_add_settings_link", get_defined_vars ());
						unset ($__refs, $__v); /* Unset defined __refs, __v. */
					}
				/**/
				return apply_filters ("_ws_plugin__qcache_add_settings_link", $links, get_defined_vars ());
			}
	}
/*
A sort of callback function to hide Quick Cache from plugins menu.
Attach to: add_filter("all_plugins");
*/
if (!function_exists ("_ws_plugin__qcache_hide_from_plugins_menu"))
	{
		function _ws_plugin__qcache_hide_from_plugins_menu ($plugins = FALSE)
			{
				eval ('foreach(array_keys(get_defined_vars())as$__v)$__refs[$__v]=&$$__v;');
				do_action ("_ws_plugin__qcache_before_hide_from_plugins_menu", get_defined_vars ());
				unset ($__refs, $__v); /* Unset defined __refs, __v. */
				/**/
				foreach ($plugins as $file => $plugin)
					if (preg_match ("/" . preg_quote ($file, "/") . "$/", $GLOBALS["WS_PLUGIN__"]["qcache"]["l"]))
						unset ($plugins[$file]);
				/**/
				return apply_filters ("_ws_plugin__qcache_hide_from_plugins_menu", $plugins, get_defined_vars ());
			}
	}
/*
Add scripts to admin panels.
Attach to: add_action("admin_print_scripts");
*/
if (!function_exists ("ws_plugin__qcache_add_admin_scripts"))
	{
		function ws_plugin__qcache_add_admin_scripts ()
			{
				do_action ("ws_plugin__qcache_before_add_admin_scripts", get_defined_vars ());
				/**/
				if ($_GET["page"] && preg_match ("/ws-plugin--qcache-/", $_GET["page"]))
					{
						wp_enqueue_script ("jquery");
						wp_enqueue_script ("thickbox");
						wp_enqueue_script ("media-upload");
						wp_enqueue_script ("ws-plugin--qcache-menu-pages", get_bloginfo ("url") . "/?ws_plugin__qcache_menu_pages_js=1", array ("jquery", "thickbox", "media-upload"), $GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["options_version"] . $GLOBALS["WS_PLUGIN__"]["qcache"]["c"]["filemtime"]);
						/**/
						do_action ("ws_plugin__qcache_during_add_admin_scripts", get_defined_vars ());
					}
				/**/
				do_action ("ws_plugin__qcache_after_add_admin_scripts", get_defined_vars ());
				/**/
				return;
			}
	}
/*
Add styles to admin panels.
Attach to: add_action("admin_print_styles");
*/
if (!function_exists ("ws_plugin__qcache_add_admin_styles"))
	{
		function ws_plugin__qcache_add_admin_styles ()
			{
				do_action ("ws_plugin__qcache_before_add_admin_styles", get_defined_vars ());
				/**/
				if ($_GET["page"] && preg_match ("/ws-plugin--qcache-/", $_GET["page"]))
					{
						wp_enqueue_style ("thickbox");
						wp_enqueue_style ("ws-plugin--qcache-menu-pages", get_bloginfo ("url") . "/?ws_plugin__qcache_menu_pages_css=1", array ("thickbox"), $GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["options_version"] . $GLOBALS["WS_PLUGIN__"]["qcache"]["c"]["filemtime"], "all");
						/**/
						do_action ("ws_plugin__qcache_during_add_admin_styles", get_defined_vars ());
					}
				/**/
				do_action ("ws_plugin__qcache_after_add_admin_styles", get_defined_vars ());
				/**/
				return;
			}
	}
/*
Function that outputs the js for menu pages.
Attach to: add_action("init");
*/
if (!function_exists ("ws_plugin__qcache_menu_pages_js"))
	{
		function ws_plugin__qcache_menu_pages_js ()
			{
				do_action ("ws_plugin__qcache_before_menu_pages_js", get_defined_vars ());
				/**/
				if ($_GET["ws_plugin__qcache_menu_pages_js"] && is_user_logged_in () && current_user_can ("edit_plugins"))
					{
						header ("Content-Type: text/javascript; charset=utf-8");
						header ("Expires: " . gmdate ("D, d M Y H:i:s", strtotime ("-1 week")) . " GMT");
						header ("Last-Modified: " . gmdate ("D, d M Y H:i:s") . " GMT");
						header ("Cache-Control: no-cache, must-revalidate, max-age=0");
						header ("Pragma: no-cache");
						/**/
						$u = $GLOBALS["WS_PLUGIN__"]["qcache"]["c"]["dir_url"];
						$i = $GLOBALS["WS_PLUGIN__"]["qcache"]["c"]["dir_url"] . "/images";
						/**/
						include_once dirname (dirname (__FILE__)) . "/menu-pages/menu-pages.js";
						@include_once dirname (dirname (__FILE__)) . "/menu-pages/menu-pages-s.js";
						/**/
						do_action ("ws_plugin__qcache_during_menu_pages_js", get_defined_vars ());
						/**/
						exit ();
					}
				/**/
				do_action ("ws_plugin__qcache_after_menu_pages_js", get_defined_vars ());
			}
	}
/*
Function that outputs the css for menu pages.
Attach to: add_action("init");
*/
if (!function_exists ("ws_plugin__qcache_menu_pages_css"))
	{
		function ws_plugin__qcache_menu_pages_css ()
			{
				do_action ("ws_plugin__qcache_before_menu_pages_css", get_defined_vars ());
				/**/
				if ($_GET["ws_plugin__qcache_menu_pages_css"] && is_user_logged_in () && current_user_can ("edit_plugins"))
					{
						header ("Content-Type: text/css; charset=utf-8");
						header ("Expires: " . gmdate ("D, d M Y H:i:s", strtotime ("-1 week")) . " GMT");
						header ("Last-Modified: " . gmdate ("D, d M Y H:i:s") . " GMT");
						header ("Cache-Control: no-cache, must-revalidate, max-age=0");
						header ("Pragma: no-cache");
						/**/
						$u = $GLOBALS["WS_PLUGIN__"]["qcache"]["c"]["dir_url"];
						$i = $GLOBALS["WS_PLUGIN__"]["qcache"]["c"]["dir_url"] . "/images";
						/**/
						include_once dirname (dirname (__FILE__)) . "/menu-pages/menu-pages.css";
						@include_once dirname (dirname (__FILE__)) . "/menu-pages/menu-pages-s.css";
						/**/
						do_action ("ws_plugin__qcache_during_menu_pages_css", get_defined_vars ());
						/**/
						exit ();
					}
				/**/
				do_action ("ws_plugin__qcache_after_menu_pages_css", get_defined_vars ());
			}
	}
/*
Function for building and handling the options page.
*/
if (!function_exists ("ws_plugin__qcache_options_page"))
	{
		function ws_plugin__qcache_options_page ()
			{
				do_action ("ws_plugin__qcache_before_options_page", get_defined_vars ());
				/**/
				ws_plugin__qcache_update_all_options ();
				/**/
				if (file_exists (ABSPATH . "wp-config.php") && !is_writable (ABSPATH . "wp-config.php"))
					{
						$notice = '<strong>Permissions:</strong> Please check permissions on <code>' . esc_html (preg_replace ("/^" . preg_quote ($_SERVER["DOCUMENT_ROOT"], "/") . "/", "", ABSPATH . "wp-config.php")) . '</code>. Quick Cache needs write-access to this file. Permissions need to be <code>755</code> or higher.';
						ws_plugin__qcache_display_admin_notice ($notice, true);
					}
				else if (file_exists (dirname (ABSPATH) . "/wp-config.php") && !is_writable (dirname (ABSPATH) . "/wp-config.php"))
					{
						$notice = '<strong>Permissions:</strong> Please check permissions on <code>' . esc_html (preg_replace ("/^" . preg_quote ($_SERVER["DOCUMENT_ROOT"], "/") . "/", "", dirname (ABSPATH) . "/wp-config.php")) . '</code>. Quick Cache needs write-access to this file. Permissions need to be <code>755</code> or higher.';
						ws_plugin__qcache_display_admin_notice ($notice, true);
					}
				/**/
				if (!is_writable (WP_CONTENT_DIR))
					{
						$notice = '<strong>Permissions:</strong> Please check permissions on <code>' . esc_html (preg_replace ("/^" . preg_quote ($_SERVER["DOCUMENT_ROOT"], "/") . "/", "", WP_CONTENT_DIR)) . '</code>. Quick Cache needs write-access to this directory. Permissions need to be <code>755</code> or higher.';
						ws_plugin__qcache_display_admin_notice ($notice, true);
					}
				/**/
				if (is_dir (WP_CONTENT_DIR . "/cache") && !is_writable (WP_CONTENT_DIR . "/cache"))
					{
						$notice = '<strong>Permissions:</strong> Please check permissions on <code>' . esc_html (preg_replace ("/^" . preg_quote ($_SERVER["DOCUMENT_ROOT"], "/") . "/", "", WP_CONTENT_DIR . "/cache")) . '</code>. Quick Cache needs write-access to this directory. Permissions need to be <code>755</code> or higher.';
						ws_plugin__qcache_display_admin_notice ($notice, true);
					}
				/**/
				if (!is_writable (dirname (dirname (dirname (__FILE__)))))
					{
						$notice = '<strong>Permissions:</strong> Please check permissions on <code>' . esc_html (preg_replace ("/^" . preg_quote ($_SERVER["DOCUMENT_ROOT"], "/") . "/", "", dirname (dirname (dirname (__FILE__))))) . '</code>. Quick Cache needs write-access to this directory. Permissions need to be <code>755</code> or higher.';
						ws_plugin__qcache_display_admin_notice ($notice, true);
					}
				/**/
				include_once dirname (dirname (__FILE__)) . "/menu-pages/options.inc.php";
				/**/
				do_action ("ws_plugin__qcache_after_options_page", get_defined_vars ());
				/**/
				return;
			}
	}
/*
Function for building and handling the info page.
*/
if (!function_exists ("ws_plugin__qcache_info_page"))
	{
		function ws_plugin__qcache_info_page ()
			{
				do_action ("ws_plugin__qcache_before_info_page", get_defined_vars ());
				/**/
				include_once dirname (dirname (__FILE__)) . "/menu-pages/info.inc.php";
				/**/
				do_action ("ws_plugin__qcache_after_info_page", get_defined_vars ());
				/**/
				return;
			}
	}
?>