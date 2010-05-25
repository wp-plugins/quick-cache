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
		if (!ws_plugin__qcache_delete_advanced ())
			{
				return false; /* Do not proceed if unable to delete. */
			}
		/**/
		else if (is_writable (WP_CONTENT_DIR) && (!file_exists (WP_CONTENT_DIR . "/advanced-cache.php") || is_writable (WP_CONTENT_DIR . "/advanced-cache.php")) && ($handler = file_get_contents (dirname (dirname (__FILE__)) . "/templates/handler.txt")))
			{
				$handler = preg_replace ("/%%QUICK_CACHE__ENABLED%%/", $GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["enabled"], $handler);
				$handler = preg_replace ("/%%QUICK_CACHE__ENABLE_DEBUGGING%%/", $GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["enable_debugging"], $handler);
				$handler = preg_replace ("/%%QUICK_CACHE__DONT_CACHE_WHEN_LOGGED_IN%%/", $GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["dont_cache_when_logged_in"], $handler);
				$handler = preg_replace ("/%%QUICK_CACHE__DONT_CACHE_QUERY_STRING_REQUESTS%%/", $GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["dont_cache_query_string_requests"], $handler);
				$handler = preg_replace ("/%%QUICK_CACHE__EXPIRATION%%/", $GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["expiration"], $handler);
				$handler = preg_replace ("/%%QUICK_CACHE__ALLOW_BROWSER_CACHE%%/", $GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["allow_browser_cache"], $handler);
				$handler = preg_replace ("/%%QUICK_CACHE__USE_FLOCK_OR_SEM%%/", $GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["use_flock_or_sem"], $handler);
				/**/
				foreach (preg_split ("/[\r\n\t]+/", $GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["dont_cache_these_uris"]) as $uri)
					$uris .= "|" . preg_quote (trim ($uri), "/");
				if ($uris = trim (trim ($uris, " \|")))
					$uris = "/" . preg_replace ('/"/', '\"', $uris) . "/";
				$handler = preg_replace ("/%%QUICK_CACHE__DONT_CACHE_THESE_URIS%%/", $uris, $handler);
				/**/
				foreach (preg_split ("/[\r\n\t]+/", $GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["dont_cache_these_refs"]) as $ref)
					$refs .= "|" . preg_quote (trim ($ref), "/");
				if ($refs = trim (trim ($refs, " \|")))
					$refs = "/" . preg_replace ('/"/', '\"', $refs) . "/i";
				$handler = preg_replace ("/%%QUICK_CACHE__DONT_CACHE_THESE_REFS%%/", $refs, $handler);
				/**/
				foreach (preg_split ("/[\r\n\t]+/", $GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["dont_cache_these_agents"]) as $agent)
					$agents .= "|" . preg_quote (trim ($agent), "/");
				if ($agents = trim (trim ($agents, " \|")))
					$agents = "/" . preg_replace ('/"/', '\"', $agents) . "/i";
				$handler = preg_replace ("/%%QUICK_CACHE__DONT_CACHE_THESE_AGENTS%%/", $agents, $handler);
				/**/
				if (strlen ($GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["version_salt"]))
					if (file_put_contents (dirname (dirname (dirname (__FILE__))) . "/syntax-check.php", '<?php error_reporting(0); $v = ' . $GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["version_salt"] . '; echo "ok"; ?>')):
						$salt_ok = (($syntax_check = ws_plugin__qcache_remote ($GLOBALS["WS_PLUGIN__"]["qcache"]["c"]["dir_url"] . "/syntax-check.php")) === "ok") ? true : false;
						if (!$salt_ok) /* If we could not validate the syntax of their salt, we need to notify them that it will not be used. */
							ws_plugin__qcache_enqueue_admin_notice ("<strong>Quick Cache:</strong> Your MD5 Version Salt may contain syntax errors. Please check it and try again. Otherwise, if you are unable to correct the problem, your Salt will simply be ignored. Quick Cache will continue to function properly using its default setting.", "ws-plugin--qcache-options", true);
						unlink (dirname (dirname (dirname (__FILE__))) . "/syntax-check.php");
					endif;
				$handler = preg_replace ("/%%QUICK_CACHE__VERSION_SALT%%/", (($salt_ok) ? $GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["version_salt"] : '""'), $handler);
				/**/
				file_put_contents (WP_CONTENT_DIR . "/advanced-cache.php", trim ($handler));
				/**/
				return true;
			}
		else /* Defaults to false, unable to create the advanced-cache.php file. */
			{
				return false;
			}
	}
/*
Delete the advanced-cache.php file.
*/
function ws_plugin__qcache_delete_advanced ()
	{
		if (file_exists (WP_CONTENT_DIR . "/advanced-cache.php") && is_writable (WP_CONTENT_DIR . "/advanced-cache.php"))
			{
				unlink (WP_CONTENT_DIR . "/advanced-cache.php");
				/**/
				return true;
			}
		else if (file_exists (WP_CONTENT_DIR . "/advanced-cache.php") && !is_writable (WP_CONTENT_DIR . "/advanced-cache.php"))
			{
				return false;
			}
		else /* Defaults to true for deletion. */
			{
				return true;
			}
	}
?>