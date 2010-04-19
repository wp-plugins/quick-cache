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
Add the advanced-cache.php file.
*/
function ws_plugin__qcache_add_advanced ()
	{
		if (!ws_plugin__qcache_remove_advanced ())
			{
				return false; /* Do not proceed if unable to remove first. */
			}
		/**/
		else if (is_writable (WP_CONTENT_DIR) && (!file_exists (WP_CONTENT_DIR . "/advanced-cache.php") || is_writable (WP_CONTENT_DIR . "/advanced-cache.php")) && ($handler = file_get_contents (dirname (dirname (__FILE__)) . "/templates/handler.txt")))
			{
				$handler = preg_replace ("/%%QUICK_CACHE__ENABLED%%/", $GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["enabled"], $handler);
				$handler = preg_replace ("/%%QUICK_CACHE__DONT_CACHE_WHEN_LOGGED_IN%%/", $GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["dont_cache_when_logged_in"], $handler);
				$handler = preg_replace ("/%%QUICK_CACHE__DONT_CACHE_QUERY_STRING_REQUESTS%%/", $GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["dont_cache_query_string_requests"], $handler);
				$handler = preg_replace ("/%%QUICK_CACHE__USE_FLOCK_OR_SEM%%/", $GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["use_flock_or_sem"], $handler);
				$handler = preg_replace ("/%%QUICK_CACHE__EXPIRATION%%/", $GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["expiration"], $handler);
				$handler = preg_replace ("/%%QUICK_CACHE__ALLOW_BROWSER_CACHE%%/", $GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["allow_browser_cache"], $handler);
				$handler = preg_replace ("/%%QUICK_CACHE__ENABLE_DEBUGGING%%/", $GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["enable_debugging"], $handler);
				/**/
				foreach (preg_split ("/[\r\n\t]+/", $GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["dont_cache_these_agents"]) as $agent)
					$agents .= "|" . preg_quote (trim ($agent), "/");
				if ($agents = trim (trim ($agents, " \|")))
					$agents = "/" . preg_replace ('/"/', '\"', $agents) . "/i";
				$handler = preg_replace ("/%%QUICK_CACHE__DONT_CACHE_THESE_AGENTS%%/", $agents, $handler);
				/**/
				foreach (preg_split ("/[\r\n\t]+/", $GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["dont_cache_these_refs"]) as $ref)
					$refs .= "|" . preg_quote (trim ($ref), "/");
				if ($refs = trim (trim ($refs, " \|")))
					$refs = "/" . preg_replace ('/"/', '\"', $refs) . "/i";
				$handler = preg_replace ("/%%QUICK_CACHE__DONT_CACHE_THESE_REFS%%/", $refs, $handler);
				/**/
				foreach (preg_split ("/[\r\n\t]+/", $GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["dont_cache_these_uris"]) as $uri)
					$uris .= "|" . preg_quote (trim ($uri), "/");
				if ($uris = trim (trim ($uris, " \|")))
					$uris = "/" . preg_replace ('/"/', '\"', $uris) . "/";
				$handler = preg_replace ("/%%QUICK_CACHE__DONT_CACHE_THESE_URIS%%/", $uris, $handler);
				/**/
				if (!($ok = false) && strlen ($GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["version_salt"]))
					if (file_put_contents (dirname (dirname (dirname (__FILE__))) . "/syntax-check.php", '<?php error_reporting(0); $v = ' . $GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["version_salt"] . '; echo 1; ?>')):
						$ok = (!$ok && trim (@file_get_contents ($GLOBALS["WS_PLUGIN__"]["qcache"]["c"]["dir_url"] . "/syntax-check.php")) == 1) ? 1 : $ok; /* Supress in case of authentication. */
						$ok = (!$ok && trim (shell_exec ("/usr/local/bin/php " . dirname (dirname (dirname (__FILE__))) . "/syntax-check.php")) == 1) ? 1 : $ok;
						$ok = (!$ok && trim (shell_exec ("/usr/bin/php " . dirname (dirname (dirname (__FILE__))) . "/syntax-check.php")) == 1) ? 1 : $ok;
						if (!$ok) /* If we could not validate the syntax of their salt, we need to notify them that it will not be used. */
							register_shutdown_function (create_function ('', 'echo \'<script type="text/javascript">alert(\\\'[ Quick Cache: ] Your MD5 Version Salt contains syntax errors. Please check it and try again. Otherwise, if you are unable to correct the problem, your Salt will simply be ignored. Quick Cache will continue to function properly using its default setting.\\\');</script>\';'));
						unlink (dirname (dirname (dirname (__FILE__))) . "/syntax-check.php");
					endif;
				$handler = preg_replace ("/%%QUICK_CACHE__VERSION_SALT%%/", (($ok) ? $GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["version_salt"] : '""'), $handler);
				/**/
				file_put_contents (WP_CONTENT_DIR . "/advanced-cache.php", trim ($handler));
				/**/
				return true;
			}
		else if (!is_writable (WP_CONTENT_DIR) || (file_exists (WP_CONTENT_DIR . "/advanced-cache.php") && !is_writable (WP_CONTENT_DIR . "/advanced-cache.php")))
			{
				register_shutdown_function (create_function ('', 'echo \'<script type="text/javascript">alert(\\\'[ Quick Cache Error: ] Your wp-content directory is not writable at the moment. Please chmod this directory to 0777 temporarily and update your settings again. Afterward you can reset its permissions if you like. The location of this directory is: ' . WP_CONTENT_DIR . '. If you have a file in that directory named advanced-cache.php, please remove it so it can be re-built.\\\');</script>\';'));
				/**/
				return false;
			}
		else /* Defaults to false, unable to create the advanced-cache.php file. */
			{
				register_shutdown_function (create_function ('', 'echo \'<script type="text/javascript">alert(\\\'[ Quick Cache Error: ] Unable to create: advanced-cache.php. Please check the location, and also the permissions of your /wp-content directory, then try again.\\\');</script>\';'));
				/**/
				return false;
			}
	}
/*
Remove the advanced-cache.php file.
*/
function ws_plugin__qcache_remove_advanced ()
	{
		if (file_exists (WP_CONTENT_DIR . "/advanced-cache.php") && is_writable (WP_CONTENT_DIR . "/advanced-cache.php"))
			{
				unlink (WP_CONTENT_DIR . "/advanced-cache.php");
				/**/
				return true;
			}
		/**/
		else if (file_exists (WP_CONTENT_DIR . "/advanced-cache.php") && !is_writable (WP_CONTENT_DIR . "/advanced-cache.php"))
			{
				register_shutdown_function (create_function ('', 'echo \'<script type="text/javascript">alert(\\\'[ Quick Cache Error: ] Your wp-content/advanced-cache.php file is not writable at the moment. Please remove the advanced-cache.php file manually, then try again.\\\');</script>\';'));
				/**/
				return false;
			}
		else /* Defaults to true for removal. */
			{
				return true;
			}
	}
?>