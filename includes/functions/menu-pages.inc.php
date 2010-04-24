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
	exit;
/*
Function for saving all options from any page.
*/
function ws_plugin__qcache_update_all_options ()
	{
		if (($nonce = $_POST["ws_plugin__qcache_options_save"]) && wp_verify_nonce ($nonce, "ws-plugin--qcache-options-save"))
			{
				if (!$GLOBALS["WS_PLUGIN__"]["qcache"]["c"]["is_wpmu"] || ($GLOBALS["WS_PLUGIN__"]["qcache"]["c"]["is_wpmu"] && (int)$GLOBALS["blog_id"] === 1 && is_site_admin ()))
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
						update_option ("ws_plugin__qcache_options", $options); /* Update options. */
						/**/
						if ($GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["enabled"]) /* Enabled? */
							{
								if (ws_plugin__qcache_add_wp_cache ()) /* Add WP_CACHE to the config file. */
									if (ws_plugin__qcache_add_mu_plugin ()) /* Add the WPMU plugin as needed. */
										if (ws_plugin__qcache_add_advanced ()) /* Add the advanced-cache.php file. */
											if (ws_plugin__qcache_add_garbage_collector ()) /* Add the garbage collector. */
												if (ws_plugin__qcache_schedule_cache_dir_removal ()) /* Remove/reset the cache. */
													{
														if ($GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["auto_cache_enabled"])
															if ($GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["auto_cache_agent"])
																if ($GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["auto_cache_sitemap_url"]/**/
																|| $GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["auto_cache_additional_urls"])
																	ws_plugin__qcache_add_auto_cache_engine ();
														/**/
														$notice = '<strong>Options updated, and the cache was reset to avoid conflicts.</strong>';
														ws_plugin__qcache_display_admin_notice ($notice);
													}
							}
						/**/
						else if (!$GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["enabled"]) /* Disabled? */
							{
								if (ws_plugin__qcache_remove_wp_cache ()) /* Remove WP_CACHE from the config file. */
									if (ws_plugin__qcache_remove_mu_plugin ()) /* Remove the WPMU plugin if it exists. */
										if (ws_plugin__qcache_remove_advanced ()) /* Remove the advanced-cache.php file. */
											if (ws_plugin__qcache_remove_garbage_collector ()) /* Remove the garbage collector. */
												if (ws_plugin__qcache_schedule_cache_dir_removal ()) /* Remove the cache dir. */
													if (ws_plugin__qcache_remove_auto_cache_engine ()) /* Remove Auto-Cache Engine. */
														{
															$notice = '<strong>Options updated. Quick Cache disabled.</strong>';
															ws_plugin__qcache_display_admin_notice ($notice);
														}
							}
					}
				else if ($GLOBALS["WS_PLUGIN__"]["qcache"]["c"]["is_wpmu"] && (int)$GLOBALS["blog_id"] > 1)
					{
						$notice = '<strong>Quick Cache can ONLY be modified by the MU Site Administrator, while operating on the main site.</strong>';
						ws_plugin__qcache_display_admin_notice ($notice, true);
					}
			}
		else if (($nonce = $_POST["ws_plugin__qcache_clear_cache"]) && wp_verify_nonce ($nonce, "ws-plugin--qcache-clear-cache"))
			{
				if (ws_plugin__qcache_remove_cache_dir ()) /* Remove/reset the cache in real-time, immediately. */
					{
						$notice = '<strong>Cache cleared ( purged in real-time ).</strong>';
						ws_plugin__qcache_display_admin_notice ($notice);
					}
				else /* Else there was a problem during the removal of the cache directory. */
					{
						$notice = '<strong>Unable to clear the cache.</strong>';
						ws_plugin__qcache_display_admin_notice ($notice, true);
					}
			}
		/**/
		return;
	}
/*
Add the options menus & sub-menus.
Attach to: add_action("admin_menu");
*/
function ws_plugin__qcache_add_admin_options ()
	{
		if (!$GLOBALS["WS_PLUGIN__"]["qcache"]["c"]["is_wpmu"] || ($GLOBALS["WS_PLUGIN__"]["qcache"]["c"]["is_wpmu"] && (int)$GLOBALS["blog_id"] === 1 && is_site_admin ()))
			{
				add_filter ("plugin_action_links", "_ws_plugin__qcache_add_settings_link", 10, 2);
				/**/
				add_menu_page ("Quick Cache", "Quick Cache", "edit_plugins", "ws-plugin--qcache-options", "ws_plugin__qcache_options_page");
				add_submenu_page ("ws-plugin--qcache-options", "Quick Cache Config Options", "Config Options", "edit_plugins", "ws-plugin--qcache-options", "ws_plugin__qcache_options_page");
				add_submenu_page ("ws-plugin--qcache-options", "Quick Cache Info", "Quick Cache Info", "edit_plugins", "ws-plugin--qcache-info", "ws_plugin__qcache_info_page");
			}
		else if ($GLOBALS["WS_PLUGIN__"]["qcache"]["c"]["is_wpmu"] && (int)$GLOBALS["blog_id"] > 1)
			{
				add_filter ("all_plugins", "_ws_plugin__qcache_hide_from_plugins_menu");
			}
		/**/
		return;
	}
/*
A sort of callback function to add the settings link.
Attach to: add_filter("plugin_action_links");
*/
function _ws_plugin__qcache_add_settings_link ($links = array (), $file = "")
	{
		if (preg_match ("/" . preg_quote ($file, "/") . "$/", $GLOBALS["WS_PLUGIN__"]["qcache"]["l"]) && is_array ($links))
			{
				$settings = '<a href="admin.php?page=ws-plugin--qcache-options">Settings</a>';
				array_unshift ($links, $settings);
			}
		/**/
		return $links;
	}
/*
A sort of callback function to hide Quick Cache from plugins menu.
Attach to: add_filter("all_plugins");
*/
function _ws_plugin__qcache_hide_from_plugins_menu ($plugins = FALSE)
	{
		foreach ($plugins as $file => $plugin)
			if (preg_match ("/" . preg_quote ($file, "/") . "$/", $GLOBALS["WS_PLUGIN__"]["qcache"]["l"]))
				unset ($plugins[$file]);
		/**/
		return $plugins;
	}
/*
Add scripts to admin panels.
Attach to: add_action("admin_print_scripts");
*/
function ws_plugin__qcache_add_admin_scripts ()
	{
		if ($_GET["page"] && preg_match ("/ws-plugin--qcache-/", $_GET["page"]))
			{
				wp_enqueue_script ("jquery");
				wp_enqueue_script ("thickbox");
				wp_enqueue_script ("media-upload");
				wp_enqueue_script ("ws-plugin--qcache-menu-pages", get_bloginfo ("url") . "/?ws_plugin__qcache_menu_pages_js=1", array ("jquery", "thickbox", "media-upload"), $GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["options_version"] . $GLOBALS["WS_PLUGIN__"]["qcache"]["c"]["filemtime"]);
			}
		/**/
		return;
	}
/*
Add styles to admin panels.
Attach to: add_action("admin_print_styles");
*/
function ws_plugin__qcache_add_admin_styles ()
	{
		if ($_GET["page"] && preg_match ("/ws-plugin--qcache-/", $_GET["page"]))
			{
				wp_enqueue_style ("thickbox");
				wp_enqueue_style ("ws-plugin--qcache-menu-pages", get_bloginfo ("url") . "/?ws_plugin__qcache_menu_pages_css=1", array ("thickbox"), $GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["options_version"] . $GLOBALS["WS_PLUGIN__"]["qcache"]["c"]["filemtime"], "all");
			}
		/**/
		return;
	}
/*
Function that outputs the js for menu pages.
Attach to: add_action("init");
*/
function ws_plugin__qcache_menu_pages_js ()
	{
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
				/**/
				exit;
			}
	}
/*
Function that outputs the css for menu pages.
Attach to: add_action("init");
*/
function ws_plugin__qcache_menu_pages_css ()
	{
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
				/**/
				exit;
			}
	}
/*
Function for building and handling the options page.
*/
function ws_plugin__qcache_options_page ()
	{
		ws_plugin__qcache_update_all_options ();
		/**/
		include_once dirname (dirname (__FILE__)) . "/menu-pages/options.inc.php";
		/**/
		return;
	}
/*
Function for building and handling the info page.
*/
function ws_plugin__qcache_info_page ()
	{
		include_once dirname (dirname (__FILE__)) . "/menu-pages/info.inc.php";
		/**/
		return;
	}
?>