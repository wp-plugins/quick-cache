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
Function clears cache on creations/deletions.
Attach to: add_action("delete_post");
Attach to: add_action("save_post");
Attach to: add_action("edit_post");
*/
function ws_plugin__qcache_clear_on_post_page_creations_deletions ($id = FALSE)
	{
		static $once = false; /* Only clear "all" once. */
		global $pagenow; /* This variable holds the current page filename. */
		/**/
		if ($GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["enabled"] && defined ("QUICK_CACHE__VERSION_SALT"))
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
										if ($uri && (file_exists (WP_CONTENT_DIR . "/cache/" . ($cache = md5 (QUICK_CACHE__VERSION_SALT . $_SERVER["HTTP_HOST"] . $uri)))/**/
										|| file_exists (WP_CONTENT_DIR . "/cache/" . ($cache = md5 (QUICK_CACHE__VERSION_SALT . $_SERVER["HTTP_HOST"] . $uri . "/")))))
											{
												if (is_writable (WP_CONTENT_DIR . "/cache/" . $cache))
													{
														unlink (WP_CONTENT_DIR . "/cache/" . $cache);
														/**/
														$notice = 'Quick Cache updated: <code>' . esc_html (QUICK_CACHE__VERSION_SALT . " " . $_SERVER["HTTP_HOST"] . $uri) . '</code> automatically :-)';
														if (!$GLOBALS["WS_PLUGIN__"]["qcache"]["c"]["is_multisite"]) /* Avoid confusion. */
															ws_plugin__qcache_enqueue_admin_notice ($notice, $pages);
													}
												else /* Else we can notify site owners here whenever their cache directory permissions are invalid. */
													{
														$notice = 'Quick Cache was unable to update: <code>' . esc_html (QUICK_CACHE__VERSION_SALT . " " . $_SERVER["HTTP_HOST"] . $uri) . '</code>. File not writable.';
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
												if ($uri && (file_exists (WP_CONTENT_DIR . "/cache/" . ($cache = md5 (QUICK_CACHE__VERSION_SALT . $_SERVER["HTTP_HOST"] . $uri)))/**/
												|| file_exists (WP_CONTENT_DIR . "/cache/" . ($cache = md5 (QUICK_CACHE__VERSION_SALT . $_SERVER["HTTP_HOST"] . $uri . "/")))))
													{
														if (is_writable (WP_CONTENT_DIR . "/cache/" . $cache))
															{
																unlink (WP_CONTENT_DIR . "/cache/" . $cache);
																/**/
																$notice = 'Quick Cache updated: <code>' . esc_html (QUICK_CACHE__VERSION_SALT . " " . $_SERVER["HTTP_HOST"] . $uri) . '</code> automatically :-)';
																if (!$GLOBALS["WS_PLUGIN__"]["qcache"]["c"]["is_multisite"]) /* Avoid confusion. */
																	ws_plugin__qcache_enqueue_admin_notice ($notice, $pages);
															}
														else /* Else we can notify site owners here whenever their cache directory permissions are invalid. */
															{
																$notice = 'Quick Cache was unable to update: <code>' . esc_html (QUICK_CACHE__VERSION_SALT . " " . $_SERVER["HTTP_HOST"] . $uri) . '</code>. File not writable.';
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
			}
		/**/
		return;
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
function ws_plugin__qcache_clear_on_creations_deletions ($category_or_link_id = FALSE)
	{
		static $once = false; /* Only clear once. */
		global $pagenow; /* This variable holds the current page filename. */
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
					}
			}
		/**/
		return;
	}
/*
Function for clearing cache on option updates.
For now, we only clear the cache if options are updated in response
to a POST request from an Administrator in the admin area. Other option
updates may occur for lots of reasons that do not justify a reset.

Also, until we find a better way of dealing with option updates,
no notices are displayed for this type of reset, due to inconsistencies with
various theme/plugin/widget panels. Sometimes options are updated too late to display
the admin notice on the same screen, and not all plugins use wp_redirect() after
options are updated. Eventually we'll find a better way of dealing with this.

Attach to: add_action("updated_option");
*/
function ws_plugin__qcache_clear_on_options_updated ($option = FALSE, $old = FALSE, $new = FALSE)
	{
		static $once = false; /* Only clear once. */
		global $pagenow; /* Holds the current page filename. */
		/**/
		if (!$once && !empty ($_POST) && !preg_match ("/^ws_plugin__qcache/", $option) && ($once = true)) /* Only process this once. */
			{
				remove_action ("updated_option", "ws_plugin__qcache_clear_on_options_updated"); /* Remove this now to prevent extra processing. */
				/**/
				if (preg_match ("/^all$/", $GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["clear_on_update"])) /* Only if pruning all. */
					{
						if (!$GLOBALS["WS_PLUGIN__"]["qcache"]["c"]["is_multisite"]) /* But NOT in multisite mode. */
							{
								if (is_admin () && current_user_can ("manage_options")) /* Administrator? */
									{
										ws_plugin__qcache_schedule_cache_dir_delete (); /* Delete cache. */
									}
							}
					}
			}
		/**/
		return;
	}
/*
Function for clearing cache on theme changes.
Attach to: add_action("switch_theme");

The cache is always reset after a theme change,
no matter what setting has been configured. A theme
being changed will always require a cache reset!
*/
function ws_plugin__qcache_clear_on_theme_change ()
	{
		static $once = false; /* Only clear once. */
		global $pagenow; /* Holds the current page filename. */
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
					}
			}
		/**/
		return;
	}
?>