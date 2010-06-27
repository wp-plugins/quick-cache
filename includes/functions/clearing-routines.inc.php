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
	exit("Do not access this file directly.");
/*
Function clears cache on creations/deletions.
Attach to: add_action("delete_post");
Attach to: add_action("save_post");
Attach to: add_action("edit_post");
*/
if (!function_exists ("ws_plugin__qcache_clear_on_post_page_creations_deletions"))
	{
		function ws_plugin__qcache_clear_on_post_page_creations_deletions ($id = FALSE)
			{
				static $once = false; /* Only clear "all" once. */
				global $pagenow; /* This variable holds the current page filename. */
				/**/
				eval('foreach(array_keys(get_defined_vars())as$__v)$__refs[$__v]=&$$__v;');
				do_action ("ws_plugin__qcache_before_clear_on_post_page_creations_deletions", get_defined_vars ());
				unset ($__refs, $__v); /* Unset defined __refs, __v. */
				/**/
				if ($GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["enabled"] && defined ("QUICK_CACHE_VERSION_SALT"))
					{
						if (in_array ($pagenow, ($pages = array ("edit.php", "edit-pages.php", "page.php", "post.php"))))
							{
								if ($id && (preg_match ("/^single/", $GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["clear_on_update"])/**/
								|| ($GLOBALS["WS_PLUGIN__"]["qcache"]["c"]["is_multisite"] && preg_match ("/^all$/", $GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["clear_on_update"]))))
									{
										if (($permalink = get_permalink ($id)) && ($parsed = parse_url ($permalink)))
											{
												$uri = ltrim (preg_replace ("/^" . preg_quote ($parsed["scheme"], "/") . "/i", "", $permalink), ":/");
												$uri = preg_replace ("/^" . preg_quote ($parsed["host"], "/") . "/i", "", $uri);
												/**/
												if ($uri && (file_exists (WP_CONTENT_DIR . "/cache/" . ($cache = md5 (QUICK_CACHE_VERSION_SALT . $_SERVER["HTTP_HOST"] . $uri)))/**/
												|| file_exists (WP_CONTENT_DIR . "/cache/" . ($cache = md5 (QUICK_CACHE_VERSION_SALT . $_SERVER["HTTP_HOST"] . $uri . "/")))))
													{
														if (is_writable (WP_CONTENT_DIR . "/cache/" . $cache))
															{
																unlink(WP_CONTENT_DIR . "/cache/" . $cache);
																/**/
																$notice = 'Quick Cache updated: <code>' . esc_html (QUICK_CACHE_VERSION_SALT . " " . $_SERVER["HTTP_HOST"] . $uri) . '</code> automatically :-)';
																if (!$GLOBALS["WS_PLUGIN__"]["qcache"]["c"]["is_multisite"]) /* Avoid confusion. */
																	ws_plugin__qcache_enqueue_admin_notice ($notice, $pages);
															}
														else /* Else we can notify site owners here whenever their cache directory permissions are invalid. */
															{
																$notice = 'Quick Cache was unable to update: <code>' . esc_html (QUICK_CACHE_VERSION_SALT . " " . $_SERVER["HTTP_HOST"] . $uri) . '</code>. File not writable.';
																if (!$GLOBALS["WS_PLUGIN__"]["qcache"]["c"]["is_multisite"]) /* Avoid confusion. */
																	ws_plugin__qcache_enqueue_admin_notice ($notice, $pages, true);
															}
													}
											}
										/**/
										if (preg_match ("/^single-fp$/", $GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["clear_on_update"]))
											{
												if (($url = get_bloginfo ("url") . "/") && ($parsed = parse_url ($url)))
													{
														$uri = ltrim (preg_replace ("/^" . preg_quote ($parsed["scheme"], "/") . "/i", "", $url), ":/");
														$uri = preg_replace ("/^" . preg_quote ($parsed["host"], "/") . "/i", "", $uri);
														/**/
														if ($uri && (file_exists (WP_CONTENT_DIR . "/cache/" . ($cache = md5 (QUICK_CACHE_VERSION_SALT . $_SERVER["HTTP_HOST"] . $uri)))/**/
														|| file_exists (WP_CONTENT_DIR . "/cache/" . ($cache = md5 (QUICK_CACHE_VERSION_SALT . $_SERVER["HTTP_HOST"] . $uri . "/")))))
															{
																if (is_writable (WP_CONTENT_DIR . "/cache/" . $cache))
																	{
																		unlink(WP_CONTENT_DIR . "/cache/" . $cache);
																		/**/
																		$notice = 'Quick Cache updated: <code>' . esc_html (QUICK_CACHE_VERSION_SALT . " " . $_SERVER["HTTP_HOST"] . $uri) . '</code> automatically :-)';
																		if (!$GLOBALS["WS_PLUGIN__"]["qcache"]["c"]["is_multisite"]) /* Avoid confusion. */
																			ws_plugin__qcache_enqueue_admin_notice ($notice, $pages);
																	}
																else /* Else we can notify site owners here whenever their cache directory permissions are invalid. */
																	{
																		$notice = 'Quick Cache was unable to update: <code>' . esc_html (QUICK_CACHE_VERSION_SALT . " " . $_SERVER["HTTP_HOST"] . $uri) . '</code>. File not writable.';
																		if (!$GLOBALS["WS_PLUGIN__"]["qcache"]["c"]["is_multisite"]) /* Avoid confusion. */
																			ws_plugin__qcache_enqueue_admin_notice ($notice, $pages, true);
																	}
															}
													}
											}
									}
								else if (!$once && preg_match ("/^all$/", $GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["clear_on_update"]) && ($once = true))
									{
										if (!$GLOBALS["WS_PLUGIN__"]["qcache"]["c"]["is_multisite"]) /* But NOT in multisite mode. */
											{
												ws_plugin__qcache_schedule_cache_dir_delete (); /* Delete cache. */
												/**/
												$notice = 'Quick Cache reset automatically to avoid conflicts :-)';
												ws_plugin__qcache_enqueue_admin_notice ($notice, $pages);
											}
									}
							}
						/**/
						do_action ("ws_plugin__qcache_during_clear_on_post_page_creations_deletions", get_defined_vars ());
					}
				/**/
				do_action ("ws_plugin__qcache_after_clear_on_post_page_creations_deletions", get_defined_vars ());
				/**/
				return;
			}
	}
/*
Function clears cache on various creations/deletions.
Attach to: add_action("create_category");
Attach to: add_action("edit_category");
Attach to: add_action("delete_category");
Attach to: add_action("add_link");
Attach to: add_action("edit_link");
Attach to: add_action("delete_link");
*/
if (!function_exists ("ws_plugin__qcache_clear_on_creations_deletions"))
	{
		function ws_plugin__qcache_clear_on_creations_deletions ($category_or_link_id = FALSE)
			{
				static $once = false; /* Only clear once. */
				global $pagenow; /* This variable holds the current page filename. */
				/**/
				eval('foreach(array_keys(get_defined_vars())as$__v)$__refs[$__v]=&$$__v;');
				do_action ("ws_plugin__qcache_before_clear_on_creations_deletions", get_defined_vars ());
				unset ($__refs, $__v); /* Unset defined __refs, __v. */
				/**/
				if (in_array ($pagenow, ($pages = array ("categories.php", "link-manager.php", "link.php", "link-add.php", "edit-link-categories.php", "link-category.php"))))
					{
						if (!$once && preg_match ("/^all$/", $GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["clear_on_update"]) && ($once = true))
							{
								if (!$GLOBALS["WS_PLUGIN__"]["qcache"]["c"]["is_multisite"]) /* But NOT in multisite mode. */
									{
										ws_plugin__qcache_schedule_cache_dir_delete (); /* Cache deletion. */
										/**/
										$notice = 'Quick Cache reset automatically to avoid conflicts :-)';
										ws_plugin__qcache_enqueue_admin_notice ($notice, $pages);
									}
								/**/
								do_action ("ws_plugin__qcache_during_clear_on_creations_deletions", get_defined_vars ());
							}
					}
				/**/
				do_action ("ws_plugin__qcache_after_clear_on_creations_deletions", get_defined_vars ());
				/**/
				return;
			}
	}
/*
Function for clearing cache on theme changes.
Attach to: add_action("switch_theme");

The cache is always reset after a theme change,
no matter what setting has been configured. A theme
being changed will always require a cache reset!
*/
if (!function_exists ("ws_plugin__qcache_clear_on_theme_change"))
	{
		function ws_plugin__qcache_clear_on_theme_change ()
			{
				static $once = false; /* Only clear once. */
				global $pagenow; /* Holds the current page filename. */
				/**/
				do_action ("ws_plugin__qcache_before_clear_on_theme_change", get_defined_vars ());
				/**/
				if (in_array ($pagenow, ($pages = array ("themes.php"))))
					{
						if (!$once && ($once = true)) /* But NOT in multisite mode. */
							{
								if (!$GLOBALS["WS_PLUGIN__"]["qcache"]["c"]["is_multisite"])
									{
										ws_plugin__qcache_schedule_cache_dir_delete ();
										/**/
										$notice = 'Quick Cache has been reset automatically to avoid conflicts :-)';
										ws_plugin__qcache_enqueue_admin_notice ($notice, array_merge ($pages, array ("ws-plugin--qcache-options")));
									}
								/**/
								do_action ("ws_plugin__qcache_during_clear_on_theme_change", get_defined_vars ());
							}
					}
				/**/
				do_action ("ws_plugin__qcache_after_clear_on_theme_change", get_defined_vars ());
				/**/
				return;
			}
	}
?>