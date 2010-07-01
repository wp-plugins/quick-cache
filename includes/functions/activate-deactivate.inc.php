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
Check existing installations that have not been re-activated.
Attach to: add_action("admin_init");
*/
if (!function_exists ("ws_plugin__qcache_check_activation"))
	{
		function ws_plugin__qcache_check_activation () /* Re-activated? */
			{
				$v = get_option ("ws_plugin__qcache_activated_version");
				if (!$v || !version_compare ($v, WS_PLUGIN__QCACHE_VERSION, ">="))
					ws_plugin__qcache_activate ();
				/**/
				return;
			}
	}
/*
Function for handling activation routines.
This function should match the array key for this plugin:
ws_plugin__$plugin_key_activate() is called by our themes.

We also initialize some option values here.
Initializing these options will force them to be
autoloaded into WordPress® instead of generating
extra queries before they are set.
*/
if (!function_exists ("ws_plugin__qcache_activate"))
	{
		function ws_plugin__qcache_activate ()
			{
				do_action ("ws_plugin__qcache_before_activation", get_defined_vars ());
				/**/
				(!is_numeric (get_option ("ws_plugin__qcache_configured"))) ? update_option ("ws_plugin__qcache_configured", "0") : null;
				(!is_array (get_option ("ws_plugin__qcache_notices"))) ? update_option ("ws_plugin__qcache_notices", array ()) : null;
				(!is_array (get_option ("ws_plugin__qcache_options"))) ? update_option ("ws_plugin__qcache_options", array ()) : null;
				/**/
				if ($GLOBALS["WS_PLUGIN__"]["qcache"]["c"]["configured"] && $GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["enabled"])
					{
						if (ws_plugin__qcache_add_wp_cache ()) /* Add WP_CACHE to the config file. */
							if (ws_plugin__qcache_add_advanced ()) /* Add the advanced-cache.php file. */
								if (ws_plugin__qcache_add_garbage_collector ()) /* Add the garbage collector. */
									if (ws_plugin__qcache_schedule_cache_dir_purge (false, false, false)) /* Purge. */
										{
											if ($GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["auto_cache_enabled"])
												ws_plugin__qcache_add_auto_cache_engine (); /* Auto-Cache. */
											else /* Otherwise, we need to delete the Auto-Cache engine. */
												ws_plugin__qcache_delete_auto_cache_engine ();
											/**/
											$notice = '<strong>Quick Cache</strong> has been <strong>re-activated</strong> with the latest version. The cache has been reset automatically to avoid conflicts :-)';
											ws_plugin__qcache_enqueue_admin_notice ($notice, array ("plugins.php", "ws-plugin--qcache-options"));
											$re_activated = true; /* Re-activated! */
										}
						/**/
						if (!$re_activated) /* Otherwise, we need to throw a warning up. The site owner needs to disable, and re-enable. */
							{
								$notice = '<strong>Quick Cache</strong> Please go to <code>Quick Cache -> Config Options</code>. You\'ll need to disable, and then re-enable Quick Cache, to complete the upgrade process.';
								ws_plugin__qcache_enqueue_admin_notice ($notice, array ("plugins.php", "ws-plugin--qcache-options"), true);
							}
					}
				/**/
				if ($GLOBALS["WS_PLUGIN__"]["qcache"]["c"]["configured"]) /* Cleanup /cache before v2.2. */
					{
						$v = get_option ("ws_plugin__qcache_activated_version");
						if (!$v || version_compare ($v, "2.2", "<")) /* File cleanup. */
							if (is_dir (WP_CONTENT_DIR . "/cache")) /* If directory exists. */
								foreach ((array)glob (WP_CONTENT_DIR . "/cache/*") as $file)
									if ($file && $file !== "." && $file !== ".." && is_file ($file))
										(is_writable ($file)) ? unlink ($file) : null;
					}
				/**/
				update_option ("ws_plugin__qcache_activated_version", WS_PLUGIN__QCACHE_VERSION);
				/**/
				do_action ("ws_plugin__qcache_after_activation", get_defined_vars ());
				/**/
				return;
			}
	}
/*
Function for handling de-activation cleanup routines.
This function should match the array key for this plugin:
ws_plugin__$plugin_key_deactivate() is called by our themes.
*/
if (!function_exists ("ws_plugin__qcache_deactivate"))
	{
		function ws_plugin__qcache_deactivate ()
			{
				do_action ("ws_plugin__qcache_before_deactivation", get_defined_vars ());
				/**/
				ws_plugin__qcache_delete_wp_cache ();
				ws_plugin__qcache_delete_advanced ();
				ws_plugin__qcache_delete_garbage_collector ();
				ws_plugin__qcache_delete_auto_cache_engine ();
				/**/
				if ($GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["run_deactivation_routines"])
					{
						delete_option("ws_plugin__qcache_activated_version");
						delete_option("ws_plugin__qcache_configured");
						delete_option("ws_plugin__qcache_notices");
						delete_option("ws_plugin__qcache_options");
					}
				/**/
				ws_plugin__qcache_purge_cache_dir (false, false, false);
				/**/
				do_action ("ws_plugin__qcache_after_deactivation", get_defined_vars ());
				/**/
				return;
			}
	}
?>