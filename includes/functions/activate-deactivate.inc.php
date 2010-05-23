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
Function for handling activation routines.
This function should match the array key for this plugin:
ws_plugin__$plugin_key_activate() is called by our themes.

We also initialize some option values here.
Initializing these options will force them to be
autoloaded into WordPress® instead of generating
extra queries before they are set.
*/
function ws_plugin__qcache_activate ()
	{
		(!is_numeric (get_option ("ws_plugin__qcache_configured"))) ? update_option ("ws_plugin__qcache_configured", "0") : null;
		(!is_array (get_option ("ws_plugin__qcache_notices"))) ? update_option ("ws_plugin__qcache_notices", array ()) : null;
		(!is_array (get_option ("ws_plugin__qcache_options"))) ? update_option ("ws_plugin__qcache_options", array ()) : null;
		/**/
		if (!$GLOBALS["WS_PLUGIN__"]["qcache"]["c"]["is_multisite"] || /* When we are NOT in multisite mode. Or when we are, but it's the Super Admin in the main site. */
		($GLOBALS["WS_PLUGIN__"]["qcache"]["c"]["is_multisite"] && (int)$GLOBALS["blog_id"] === 1 && ((function_exists ("is_super_admin") && is_super_admin ()) || is_site_admin ())))
			{
				if ($GLOBALS["WS_PLUGIN__"]["qcache"]["c"]["configured"] && $GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["enabled"])
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
											$notice = '<strong>Quick Cache</strong> has been <strong>re-activated</strong> with the latest version.';
											$notice .= ' The cache has been reset automatically to avoid conflicts :-)';
											ws_plugin__qcache_enqueue_admin_notice ($notice, array ("plugins.php"));
											$re_activated = true; /* Mark as having been re-activated. */
										}
						/**/
						if (!$re_activated) /* Otherwise, we need to throw a warning up. The site owner needs to disable, and re-enable. */
							{
								$notice = '<strong>Quick Cache</strong> Please go to <code>Quick Cache -> Config Options</code>.';
								$notice .= ' You\'ll need to disable, and then re-enable Quick Cache, to complete the upgrade process.';
								ws_plugin__qcache_enqueue_admin_notice ($notice, array ("plugins.php"), true);
							}
					}
			}
		/**/
		return;
	}
/*
Function for handling de-activation cleanup routines.
This function should match the array key for this plugin:
ws_plugin__$plugin_key_deactivate() is called by our themes.
*/
function ws_plugin__qcache_deactivate ()
	{
		ws_plugin__qcache_delete_wp_cache ();
		ws_plugin__qcache_delete_advanced ();
		ws_plugin__qcache_delete_garbage_collector ();
		ws_plugin__qcache_delete_auto_cache_engine ();
		/**/
		if ($GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["run_deactivation_routines"])
			{
				delete_option ("ws_plugin__qcache_configured");
				delete_option ("ws_plugin__qcache_notices");
				delete_option ("ws_plugin__qcache_options");
			}
		/**/
		ws_plugin__qcache_delete_cache_dir ();
		/**/
		return;
	}
?>