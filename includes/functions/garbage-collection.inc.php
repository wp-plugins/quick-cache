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
Add a scheduled task for garbage collection.
*/
function ws_plugin__qcache_add_garbage_collector ()
	{
		if (!ws_plugin__qcache_remove_garbage_collector())
			{
				return false; /* Do not proceed if unable to remove first. */
			}
		else /* Otherwise, we can safely schedule the event and return true. */
			{
				wp_schedule_event(strtotime("+1 hour"), "hourly", "qcache_cron_garbage_collector");
				/**/
				return true;
			}
	}
/*
Remove scheduled tasks for garbage collection.
*/
function ws_plugin__qcache_remove_garbage_collector ()
	{
		wp_clear_scheduled_hook("qcache_cron_garbage_collector");
		/**/
		return true;
	}
/*
Schedule a clearing (forced removal) of the cache directory.
*/
function ws_plugin__qcache_schedule_cache_dir_removal ()
	{
		static $once = false; /* Only schedule once. */
		/**/
		if (!$once && ($once = true)) /* No need to duplicate this. */
			{
				wp_schedule_single_event(time(), "qcache_cron_remove_cache_dir");
			}
		/**/
		return true;
	}
/*
Clear/remove all cache files & directory if empty.
*/
function ws_plugin__qcache_remove_cache_dir ()
	{
		clearstatcache();
		set_time_limit(900);
		ignore_user_abort(true);
		ini_set("memory_limit", "512M");
		/**/
		define("QUICK_CACHE_ALLOWED", false);
		/**/
		if (is_dir(WP_CONTENT_DIR . "/cache") && is_writable(WP_CONTENT_DIR . "/cache"))
			{
				foreach (scandir(WP_CONTENT_DIR . "/cache") as $file)
					if ($file !== "." && $file !== "..")
						if (is_writable(WP_CONTENT_DIR . "/cache/" . $file))
							unlink(WP_CONTENT_DIR . "/cache/" . $file);
				/**/
				rmdir(WP_CONTENT_DIR . "/cache");
				/**/
				clearstatcache(); /* Clear & re-check. */
				/**/
				if (is_dir(WP_CONTENT_DIR . "/cache"))
					{
						register_shutdown_function(create_function('', 'echo \'<script type="text/javascript">alert(\\\'[ Quick Cache Error: ] Unable to completely purge the wp-content/cache directory. Please remove the /cache directory manually.\\\');</script>\';'));
						/**/
						return false;
					}
				else /* Removed successfully. */
					{
						return true;
					}
			}
		else if (is_dir(WP_CONTENT_DIR . "/cache") && !is_writable(WP_CONTENT_DIR . "/cache"))
			{
				register_shutdown_function(create_function('', 'echo \'<script type="text/javascript">alert(\\\'[ Quick Cache Error: ] Your wp-content/cache directory is not writable at the moment. Please remove the /cache directory manually.\\\');</script>\';'));
				/**/
				return false;
			}
		else /* Defaults to true for removal. */
			{
				return true;
			}
	}
/*
Clean/remove expired cache files only.
*/
function ws_plugin__qcache_garbage_collector ()
	{
		clearstatcache();
		set_time_limit(900);
		ignore_user_abort(true);
		ini_set("memory_limit", "512M");
		/**/
		define("QUICK_CACHE_ALLOWED", false);
		/**/
		if ($GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["use_flock_or_sem"] === "sem" && function_exists("sem_get") && ($mutex = @sem_get(1976, 1, 0644 | IPC_CREAT, 1)) && @sem_acquire($mutex))
			{
				if (is_dir(WP_CONTENT_DIR . "/cache") && is_writable(WP_CONTENT_DIR . "/cache"))
					{
						foreach (scandir(WP_CONTENT_DIR . "/cache") as $file)
							if ($file !== "." && $file !== ".." && $file !== ".htaccess" && $file !== "mutex.lock" && !preg_match("/^index\.(htm|html|php)$/", $file))
								if (filemtime(WP_CONTENT_DIR . "/cache/" . $file) < strtotime("-" . $GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["expiration"] . " seconds"))
									if (is_writable(WP_CONTENT_DIR . "/cache/" . $file))
										unlink(WP_CONTENT_DIR . "/cache/" . $file);
					}
				sem_release($mutex);
			}
		/**/
		else if ($GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["use_flock_or_sem"] === "flock" && ($mutex = @fopen(WP_CONTENT_DIR . "/cache/mutex.lock", "w")) && @flock($mutex, LOCK_EX))
			{
				if (is_dir(WP_CONTENT_DIR . "/cache") && is_writable(WP_CONTENT_DIR . "/cache"))
					{
						foreach (scandir(WP_CONTENT_DIR . "/cache") as $file)
							if ($file !== "." && $file !== ".." && $file !== ".htaccess" && $file !== "mutex.lock" && !preg_match("/^index\.(htm|html|php)$/", $file))
								if (filemtime(WP_CONTENT_DIR . "/cache/" . $file) < strtotime("-" . $GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["expiration"] . " seconds"))
									if (is_writable(WP_CONTENT_DIR . "/cache/" . $file))
										unlink(WP_CONTENT_DIR . "/cache/" . $file);
					}
				flock($mutex, LOCK_UN);
			}
		/**/
		return;
	}
?>