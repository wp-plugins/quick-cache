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
if (realpath(__FILE__) === realpath($_SERVER["SCRIPT_FILENAME"]))
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
		(!is_numeric(get_option("ws_plugin__qcache_configured"))) ? update_option("ws_plugin__qcache_configured", "0") : null;
		(!is_array(get_option("ws_plugin__qcache_notices"))) ? update_option("ws_plugin__qcache_notices", array ()) : null;
		(!is_array(get_option("ws_plugin__qcache_options"))) ? update_option("ws_plugin__qcache_options", array ()) : null;
		/**/
		if (!$GLOBALS["WS_PLUGIN__"]["qcache"]["c"]["is_wpmu"] || ($GLOBALS["WS_PLUGIN__"]["qcache"]["c"]["is_wpmu"] && (int)$GLOBALS["blog_id"] === 1 && is_site_admin()))
			{
				if ($GLOBALS["WS_PLUGIN__"]["qcache"]["c"]["configured"] && $GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["enabled"])
					{
						if (ws_plugin__qcache_add_wp_cache()) /* Add WP_CACHE to the config file. */
							if (ws_plugin__qcache_add_mu_plugin()) /* Add the WPMU plugin as needed. */
								if (ws_plugin__qcache_add_advanced()) /* Add the advanced-cache.php file. */
									if (ws_plugin__qcache_add_garbage_collector()) /* Add the garbage collector. */
										if (ws_plugin__qcache_schedule_cache_dir_removal()) /* Remove/reset the cache. */
											{
												$notice = '<strong>Quick Cache</strong> has been <strong>re-activated</strong> with the latest version.';
												$notice .= ' The cache has been reset automatically to avoid conflicts :-)';
												ws_plugin__qcache_enqueue_admin_notice($notice, array("plugins.php", "ws-plugin--qcache-options"));
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
		ws_plugin__qcache_remove_wp_cache();
		ws_plugin__qcache_remove_mu_plugin();
		ws_plugin__qcache_remove_advanced();
		ws_plugin__qcache_remove_garbage_collector();
		/**/
		delete_option("ws_plugin__qcache_configured");
		delete_option("ws_plugin__qcache_notices");
		delete_option("ws_plugin__qcache_options");
		/**/
		ws_plugin__qcache_remove_cache_dir();
		/**/
		return;
	}
?>