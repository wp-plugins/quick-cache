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
Add a scheduled task for the auto-cache engine.
*/
function ws_plugin__qcache_add_auto_cache_engine ()
	{
		if (!ws_plugin__qcache_delete_auto_cache_engine ())
			{
				return false; /* Do not proceed if unable to delete. */
			}
		else if (function_exists ("wp_cron")) /* Otherwise, we can schedule. */
			{
				wp_schedule_event (time (), "every5m", "qcache_cron_auto_cache_engine");
				/**/
				return true;
			}
		else /* WP-Cron is not available. */
			{
				return false;
			}
	}
/*
Delete scheduled tasks for the auto-cache engine.
*/
function ws_plugin__qcache_delete_auto_cache_engine ()
	{
		if (function_exists ("wp_cron"))
			{
				wp_clear_scheduled_hook ("qcache_cron_auto_cache_engine");
				/**/
				return true;
			}
		else /* WP-Cron is not available. */
			{
				return false;
			}
	}
/*
Runs the Auto-Cache Engine. ( this must be enabled first )
The Auto-Cache Engine keeps an entire site cached automatically.
Attach to: add_action("qcache_cron_auto_cache_engine");
*/
function ws_plugin__qcache_auto_cache_engine ()
	{
		if ($GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["enabled"] && defined ("QUICK_CACHE__VERSION_SALT")/**/
		&& $GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["auto_cache_enabled"] && $GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["auto_cache_agent"]/**/
		&& ($GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["auto_cache_sitemap_url"] || $GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["auto_cache_additional_urls"])/**/
		&& $GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["expiration"] >= 3600) /* Must have a Cache Expiration set to at least 3600; for security. */
			{
				$log = "";
				clearstatcache ();
				set_time_limit (900);
				ignore_user_abort (true);
				ini_set ("memory_limit", "512M");
				/**/
				define ("QUICK_CACHE_ALLOWED", false);
				/**/
				if ($GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["use_flock_or_sem"] === "sem" /* 1977 is the Auto-Cache Engine. */
				&& function_exists ("sem_get") && ($mutex = @sem_get (1977, 1, 0644 | IPC_CREAT, 1)) && @sem_acquire ($mutex))
					$mutex_method = "sem";
				/**/
				else if ($GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["use_flock_or_sem"] === "flock"/**/
				&& ($mutex = @fopen (WP_CONTENT_DIR . "/cache/ac.mutex.lock", "w")) && @flock ($mutex, LOCK_EX))
					$mutex_method = "flock";
				/**/
				if (($mutex && $mutex_method) && is_array ($urls = array ())) /* URLs must reside in the same top-level domain. */
					{
						if ($GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["auto_cache_sitemap_url"]) /* Sitemap must reside in the same top-level domain. */
							{
								if (preg_match ("/^http(s?)\:\/\/(.*?)" . preg_quote ($_SERVER["HTTP_HOST"], "/") . "\//i", $GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["auto_cache_sitemap_url"]))
									{
										if ($sitemap = ws_plugin__qcache_remote ($GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["auto_cache_sitemap_url"]))
											{
												preg_match_all ("/\<loc\>(.+?)\<\/loc\>/i", $sitemap, $sitemap_matches);
												if (is_array ($sitemap_matches[1]) && !empty ($sitemap_matches[1]))
													foreach ($sitemap_matches[1] as $sitemap_match)
														if ($url = trim ($sitemap_match))
															$urls[] = $url;
											}
									}
							}
						/**/
						if ($GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["auto_cache_additional_urls"])
							{
								if (is_array ($additionals = preg_split ("/[\r\n\t]+/", $GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["auto_cache_additional_urls"])))
									foreach ($additionals as $additional)
										if ($additional = trim ($additional))
											$urls[] = $additional;
							}
						/**/
						if (($urls = array_unique ($urls)) && !empty ($urls) && shuffle ($urls))
							{
								foreach ($urls as $url) /* These URLs must be in the same top-level domain; for security. */
									{
										if (preg_match ("/^http(s?)\:\/\/(.*?)" . preg_quote ($_SERVER["HTTP_HOST"], "/") . "\//i", $url))
											{
												if ($host_uri = preg_replace ("/^http(s?)\:\/\//i", "", $url))
													{
														$md5_cache = WP_CONTENT_DIR . "/cache/" . md5 ($salt_host_uri = QUICK_CACHE__VERSION_SALT . $host_uri);
														/**/
														if (!file_exists ($md5_cache) || filemtime ($md5_cache) < strtotime ("-" . $GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["expiration"] . " seconds"))
															{
																ws_plugin__qcache_remote ($url, false, array ("timeout" => 0.01, "blocking" => false, "user-agent" => $GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["auto_cache_agent"] . " + Quick Cache ( Auto-Cache Engine )"));
																/**/
																$log .= date ("M j, Y, g:i a T") . " / Auto-Cached: " . $salt_host_uri . "\n";
																/**/
																if (($processed = (int)$processed + 1) >= $GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["auto_cache_max_processes"])
																	break;
																/**/
																else if ($processed >= 25) /* Hard-coded maximum; for security. */
																	break; /* Never allow more than 25 to be processed at once. */
															}
													}
											}
									}
							}
						/**/
						if ($log && (is_dir (WP_CONTENT_DIR . "/cache") || is_writable (WP_CONTENT_DIR)))
							{
								if (!is_dir (WP_CONTENT_DIR . "/cache"))
									@mkdir (WP_CONTENT_DIR . "/cache", 0777, true);
								/**/
								if (is_dir (WP_CONTENT_DIR . "/cache") && is_writable (WP_CONTENT_DIR . "/cache"))
									{
										$auto_cache_log = WP_CONTENT_DIR . "/cache/auto-cache.log";
										/**/
										if (file_exists ($auto_cache_log) && filesize ($auto_cache_log) > 2097152)
											if (is_writable ($auto_cache_log)) /* This is a 2MB log rotation ^. */
												unlink ($auto_cache_log); /* Resets the log. */
										/**/
										if (!file_exists ($auto_cache_log) || is_writable ($auto_cache_log))
											file_put_contents ($auto_cache_log, $log, FILE_APPEND);
									}
							}
						/**/
						if ($mutex_method === "sem")
							sem_release ($mutex);
						/**/
						else if ($mutex_method === "flock")
							flock ($mutex, LOCK_UN);
					}
			}
		/**/
		return;
	}
?>