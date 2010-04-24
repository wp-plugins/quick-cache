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
		if (!ws_plugin__qcache_remove_auto_cache_engine ())
			{
				return false; /* Do not proceed if unable to remove first. */
			}
		else /* Otherwise, we can safely schedule the event and return true. */
			{
				wp_schedule_event (time (), "every5m", "qcache_cron_auto_cache_engine");
				/**/
				return true;
			}
	}
/*
Remove scheduled tasks for the auto-cache engine.
*/
function ws_plugin__qcache_remove_auto_cache_engine ()
	{
		wp_clear_scheduled_hook ("qcache_cron_auto_cache_engine");
		/**/
		return true;
	}
/*
Runs the Auto-Cache Engine. ( this must be enabled first )
The Auto-Cache Engine keeps an entire site cached automatically.
Attach to: add_action("qcache_cron_auto_cache_engine");
*/
function ws_plugin__qcache_auto_cache_engine ()
	{
		if ($GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["enabled"] && function_exists ("QUICK_CACHE__handler")/**/
		&& function_exists ("curl_init") /* Must have the cURL extension for PHP installed. Otherwise this will fail anyway. */
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
										if ($sitemap = ws_plugin__qcache_auto_cache_curl_get ($GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["auto_cache_sitemap_url"]))
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
																ws_plugin__qcache_auto_cache_curl_get ($url, "break");
																/**/
																$log .= date ("M j, Y, g:i a T") . " / Auto-Cached: " . $salt_host_uri . "\n";
																/**/
																if (($processed = (int)$processed + 1) /* Check max processes. */
																>= $GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["auto_cache_max_processes"])
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
											if (is_writable ($auto_cache_log)) /* This is a 2MB log removal ^. */
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
/*
Use cURL to send an Auto-Cache request, with a fake User-Agent.
The Quick Cache handler is designed to set: ignore_user_abort(true) for these requests.
*/
function ws_plugin__qcache_auto_cache_curl_get ($url = FALSE, $get__break = "get")
	{
		if ($agent = $GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["auto_cache_agent"])
			{
				if ($agent = $agent . " + Quick Cache ( Auto-Cache Engine )")
					{
						if (is_string ($url) && $url && $c = curl_init ())
							{
								curl_setopt ($c, CURLOPT_URL, $url);
								curl_setopt ($c, CURLOPT_USERAGENT, $agent);
								curl_setopt ($c, CURLOPT_TIMEOUT, (($get__break === "break") ? 1 : 30));
								curl_setopt ($c, CURLOPT_CONNECTTIMEOUT, (($get__break === "break") ? 5 : 30));
								curl_setopt ($c, CURLOPT_FOLLOWLOCATION, true) . curl_setopt ($c, CURLOPT_MAXREDIRS, 4);
								curl_setopt ($c, CURLOPT_SSL_VERIFYPEER, false);
								curl_setopt ($c, CURLOPT_FAILONERROR, true);
								curl_setopt ($c, CURLOPT_ENCODING, "");
								curl_setopt ($c, CURLOPT_HEADER, false);
								curl_setopt ($c, CURLOPT_FORBID_REUSE, true);
								curl_setopt ($c, CURLOPT_RETURNTRANSFER, true);
								/**/
								$o = @curl_exec ($c);
								@curl_close ($c);
								/**/
								return $o;
							}
					}
			}
		/**/
		return;
	}
?>