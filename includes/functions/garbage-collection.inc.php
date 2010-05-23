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
Add a scheduled task for garbage collection.
*/
function ws_plugin__qcache_add_garbage_collector ()
	{
		if (!ws_plugin__qcache_delete_garbage_collector ())
			{
				return false; /* Do not proceed if unable to delete. */
			}
		else if (function_exists ("wp_cron")) /* Otherwise, we can schedule. */
			{
				wp_schedule_event (strtotime ("+1 hour"), "hourly", "qcache_cron_garbage_collector");
				/**/
				return true;
			}
		else /* WP-Cron is not available. */
			{
				return false;
			}
	}
/*
Delete scheduled tasks for garbage collection.
*/
function ws_plugin__qcache_delete_garbage_collector ()
	{
		if (function_exists ("wp_cron"))
			{
				wp_clear_scheduled_hook ("qcache_cron_garbage_collector");
				/**/
				return true;
			}
		else /* WP-Cron is not available. */
			{
				return false;
			}
	}
/*
Schedule a clearing (forced deletion) of the cache directory.
*/
function ws_plugin__qcache_schedule_cache_dir_delete ()
	{
		static $once; /* Only schedule once. */
		/**/
		if (!isset ($once)) /* No need to duplicate this. */
			{
				if (function_exists ("wp_cron") && ($once = true)) /* If available. */
					{
						wp_schedule_single_event (time (), "qcache_cron_delete_cache_dir");
					}
				else /* WP-Cron is not available. */
					{
						$once = false;
					}
			}
		/**/
		return $once;
	}
/*
Clear/delete all cache files & directory if empty.
Attach to: add_action("qcache_cron_delete_cache_dir");
*/
function ws_plugin__qcache_delete_cache_dir ()
	{
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
				if (is_dir (WP_CONTENT_DIR . "/cache"))
					{
						return false;
					}
				else /* Deleted successfully. */
					{
						return true;
					}
			}
		else if (is_dir (WP_CONTENT_DIR . "/cache") && !is_writable (WP_CONTENT_DIR . "/cache"))
			{
				return false;
			}
		else /* Defaults to true for deletion. */
			{
				return true;
			}
	}
/*
This runs the built-in garbage collector for Quick Cache.
Attach to: add_action("qcache_cron_garbage_collector");
*/
function ws_plugin__qcache_garbage_collector ()
	{
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
			}
		/**/
		return;
	}
?>