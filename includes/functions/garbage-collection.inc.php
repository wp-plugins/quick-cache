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
Add a scheduled task for garbage collection.
*/
if (!function_exists ("ws_plugin__qcache_add_garbage_collector"))
	{
		function ws_plugin__qcache_add_garbage_collector ()
			{
				do_action ("ws_plugin__qcache_before_add_garbage_collector", get_defined_vars ());
				/**/
				if (!ws_plugin__qcache_delete_garbage_collector ())
					{
						return apply_filters ("ws_plugin__qcache_add_garbage_collector", false, get_defined_vars ());
					}
				else if (function_exists ("wp_cron")) /* Otherwise, we can schedule. */
					{
						wp_schedule_event (strtotime ("+1 hour"), "hourly", "ws_plugin__qcache_garbage_collector__schedule");
						/**/
						do_action ("ws_plugin__qcache_during_add_garbage_collector", get_defined_vars ());
						/**/
						return apply_filters ("ws_plugin__qcache_add_garbage_collector", true, get_defined_vars ());
					}
				else /* It appears that WP-Cron is not available. */
					{
						return apply_filters ("ws_plugin__qcache_add_garbage_collector", false, get_defined_vars ());
					}
			}
	}
/*
Delete scheduled tasks for garbage collection.
*/
if (!function_exists ("ws_plugin__qcache_delete_garbage_collector"))
	{
		function ws_plugin__qcache_delete_garbage_collector ()
			{
				do_action ("ws_plugin__qcache_before_delete_garbage_collector", get_defined_vars ());
				/**/
				if (function_exists ("wp_cron")) /* If WP-Cron is available. */
					{
						wp_clear_scheduled_hook ("qcache_cron_garbage_collector"); /* For backward compatibility. */
						wp_clear_scheduled_hook ("ws_plugin__qcache_garbage_collector__schedule"); /* Since 2.1.8. */
						/**/
						do_action ("ws_plugin__qcache_during_delete_garbage_collector", get_defined_vars ());
						/**/
						return apply_filters ("ws_plugin__qcache_delete_garbage_collector", true, get_defined_vars ());
					}
				else /* It appears that WP-Cron is not available. */
					{
						return apply_filters ("ws_plugin__qcache_delete_garbage_collector", false, get_defined_vars ());
					}
			}
	}
/*
Schedule a clearing (forced deletion) of the cache directory.
*/
if (!function_exists ("ws_plugin__qcache_schedule_cache_dir_delete"))
	{
		function ws_plugin__qcache_schedule_cache_dir_delete ()
			{
				static $once; /* Only schedule once. */
				/**/
				do_action ("ws_plugin__qcache_before_schedule_cache_dir_delete", get_defined_vars ());
				/**/
				if (!isset ($once)) /* No need to duplicate this. */
					{
						if (function_exists ("wp_cron") && ($once = true)) /* If available. */
							{
								wp_schedule_single_event (time (), "ws_plugin__qcache_delete_cache_dir__schedule");
								/**/
								do_action ("ws_plugin__qcache_during_schedule_cache_dir_delete", get_defined_vars ());
							}
						else /* WP-Cron is not available. */
							{
								$once = false;
							}
					}
				/**/
				return apply_filters ("ws_plugin__qcache_schedule_cache_dir_delete", $once, get_defined_vars ());
			}
	}
/*
Clear/delete all cache files & directory if empty.
Attach to: add_action("ws_plugin__qcache_delete_cache_dir__schedule");
*/
if (!function_exists ("ws_plugin__qcache_delete_cache_dir"))
	{
		function ws_plugin__qcache_delete_cache_dir ()
			{
				do_action ("ws_plugin__qcache_before_delete_cache_dir", get_defined_vars ());
				/**/
				clearstatcache ();
				set_time_limit (900);
				ignore_user_abort (true);
				ini_set ("memory_limit", "512M");
				/**/
				define ("QUICK_CACHE_ALLOWED", false);
				/**/
				if (is_dir (WP_CONTENT_DIR . "/cache") && is_writable (WP_CONTENT_DIR . "/cache"))
					{
						foreach (scandir (WP_CONTENT_DIR . "/cache") as $file)
							if ($file !== "." && $file !== "..")
								if (is_writable (WP_CONTENT_DIR . "/cache/" . $file))
									unlink (WP_CONTENT_DIR . "/cache/" . $file);
						/**/
						rmdir (WP_CONTENT_DIR . "/cache");
						/**/
						clearstatcache (); /* Clear & re-check. */
						/**/
						do_action ("ws_plugin__qcache_during_delete_cache_dir", get_defined_vars ());
						/**/
						if (is_dir (WP_CONTENT_DIR . "/cache"))
							{
								return apply_filters ("ws_plugin__qcache_delete_cache_dir", false, get_defined_vars ());
							}
						else /* Deleted successfully. */
							{
								return apply_filters ("ws_plugin__qcache_delete_cache_dir", true, get_defined_vars ());
							}
					}
				else if (is_dir (WP_CONTENT_DIR . "/cache") && !is_writable (WP_CONTENT_DIR . "/cache"))
					{
						return apply_filters ("ws_plugin__qcache_delete_cache_dir", false, get_defined_vars ());
					}
				else /* Defaults to true for deletion. */
					{
						return apply_filters ("ws_plugin__qcache_delete_cache_dir", true, get_defined_vars ());
					}
			}
	}
/*
This runs the built-in garbage collector for Quick Cache.
Attach to: add_action("ws_plugin__qcache_garbage_collector__schedule");
*/
if (!function_exists ("ws_plugin__qcache_garbage_collector"))
	{
		function ws_plugin__qcache_garbage_collector ()
			{
				do_action ("ws_plugin__qcache_before_garbage_collector", get_defined_vars ());
				/**/
				clearstatcache ();
				set_time_limit (900);
				ignore_user_abort (true);
				ini_set ("memory_limit", "512M");
				/**/
				define ("QUICK_CACHE_ALLOWED", false);
				/**/
				if ($GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["use_flock_or_sem"] === "sem"/**/
				&& function_exists ("sem_get") && ($mutex = @sem_get (1976, 1, 0644 | IPC_CREAT, 1)) && @sem_acquire ($mutex))
					$mutex_method = "sem";
				/**/
				else if ($GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["use_flock_or_sem"] === "flock"/**/
				&& ($mutex = @fopen (WP_CONTENT_DIR . "/cache/mutex.lock", "w")) && @flock ($mutex, LOCK_EX))
					$mutex_method = "flock";
				/**/
				if (($mutex && $mutex_method) && is_dir (WP_CONTENT_DIR . "/cache") && is_writable (WP_CONTENT_DIR . "/cache"))
					{
						foreach (scandir (WP_CONTENT_DIR . "/cache") as $file)
							if ($file !== "." && $file !== ".." && $file !== ".htaccess" && $file !== "mutex.lock" && $file !== "ac.mutex.lock" && $file !== "auto-cache.log" && !preg_match ("/^index\.(htm|html|php)$/", $file))
								if (filemtime (WP_CONTENT_DIR . "/cache/" . $file) < strtotime ("-" . $GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["expiration"] . " seconds"))
									if (is_writable (WP_CONTENT_DIR . "/cache/" . $file))
										unlink (WP_CONTENT_DIR . "/cache/" . $file);
						/**/
						if ($mutex_method === "sem")
							sem_release ($mutex);
						/**/
						else if ($mutex_method === "flock")
							flock ($mutex, LOCK_UN);
						/**/
						do_action ("ws_plugin__qcache_during_garbage_collector", get_defined_vars ());
					}
				/**/
				do_action ("ws_plugin__qcache_after_garbage_collector", get_defined_vars ());
				/**/
				return;
			}
	}
?>