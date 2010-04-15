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
Add the MU plugin file.
*/
function ws_plugin__qcache_add_mu_plugin ()
	{
		if ($GLOBALS["WS_PLUGIN__"]["qcache"]["c"]["is_wpmu"])
			{
				if (!ws_plugin__qcache_remove_mu_plugin())
					{
						return false; /* Do not proceed if unable to remove first. */
					}
				/**/
				else if (is_writable(WPMU_PLUGIN_DIR) && (!file_exists(WPMU_PLUGIN_DIR . "/quick-cache-mu.php") || is_writable(WPMU_PLUGIN_DIR . "/quick-cache-mu.php")) && ($mu_plugin = file_get_contents(dirname(dirname(__FILE__)) . "/templates/mu-plugin.txt")))
					{
						file_put_contents(WPMU_PLUGIN_DIR . "/quick-cache-mu.php", trim($mu_plugin));
						/**/
						return true;
					}
				else if (!is_writable(WPMU_PLUGIN_DIR) || (file_exists(WPMU_PLUGIN_DIR . "/quick-cache-mu.php") && !is_writable(WPMU_PLUGIN_DIR . "/quick-cache-mu.php")))
					{
						register_shutdown_function(create_function('', 'echo \'<script type="text/javascript">alert(\\\'[ Quick Cache Error: ] Your mu-plugins directory is not writable at the moment. Please chmod this directory to 0777 temporarily and update your settings again. Afterward you can reset its permissions if you like. The location of this directory is: ' . WPMU_PLUGIN_DIR . '. If you have a file in that directory named quick-cache-mu.php, please remove it so it can be re-built.\\\');</script>\';'));
						/**/
						return false;
					}
				else /* Defaults to false, unable to create the quick-cache-mu.php file. */
					{
						register_shutdown_function(create_function('', 'echo \'<script type="text/javascript">alert(\\\'[ Quick Cache Error: ] Unable to create: quick-cache-mu.php. Please check the location, and also the permissions of your /mu-plugins directory, then try again.\\\');</script>\';'));
						/**/
						return false;
					}
			}
		else /* Default to true if not running under WPMU. */
			{
				return true;
			}
	}
/*
Remove the quick-cache-mu.php file.
*/
function ws_plugin__qcache_remove_mu_plugin ()
	{
		if ($GLOBALS["WS_PLUGIN__"]["qcache"]["c"]["is_wpmu"])
			{
				if (file_exists(WPMU_PLUGIN_DIR . "/quick-cache-mu.php") && is_writable(WPMU_PLUGIN_DIR . "/quick-cache-mu.php"))
					{
						unlink(WPMU_PLUGIN_DIR . "/quick-cache-mu.php");
						/**/
						return true;
					}
				/**/
				else if (file_exists(WPMU_PLUGIN_DIR . "/quick-cache-mu.php") && !is_writable(WPMU_PLUGIN_DIR . "/quick-cache-mu.php"))
					{
						register_shutdown_function(create_function('', 'echo \'<script type="text/javascript">alert(\\\'[ Quick Cache Error: ] Your mu-plugins/quick-cache-mu.php file is not writable at the moment. Please remove the quick-cache-mu.php file manually, then try again.\\\');</script>\';'));
						/**/
						return false;
					}
				else /* Defaults to true for removal. */
					{
						return true;
					}
			}
		else /* Default to true if not running under WPMU. */
			{
				return true;
			}
	}
?>