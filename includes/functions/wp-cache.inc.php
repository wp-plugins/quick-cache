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
Add WP_CACHE to the config file(s).
*/
function ws_plugin__qcache_add_wp_cache ()
	{
		if (!ws_plugin__qcache_remove_wp_cache())
			{
				return false; /* Do not proceed if unable to remove first. */
			}
		/**/
		else if (file_exists(ABSPATH . "wp-config.php") && is_writable(ABSPATH . "wp-config.php"))
			{
				$config = file_get_contents(ABSPATH . "wp-config.php");
				$config = preg_replace("/^([\r\n\t ]*)(\<\?)(php)?/i", "<?php define('WP_CACHE', true);", $config);
				file_put_contents(ABSPATH . "wp-config.php", $config);
				/**/
				return true;
			}
		else if (file_exists(dirname(ABSPATH) . "/wp-config.php") && is_writable(dirname(ABSPATH) . "/wp-config.php"))
			{
				$config = file_get_contents(dirname(ABSPATH) . "/wp-config.php");
				$config = preg_replace("/^([\r\n\t ]*)(\<\?)(php)?/i", "<?php define('WP_CACHE', true);", $config);
				file_put_contents(dirname(ABSPATH) . "/wp-config.php", $config);
				/**/
				return true;
			}
		else if (file_exists(ABSPATH . "wp-config.php") && !is_writable(ABSPATH . "wp-config.php"))
			{
				register_shutdown_function(create_function('', 'echo \'<script type="text/javascript">alert(\\\'[ Quick Cache Error: ] Your wp-config.php file is not writable at the moment. Please chmod this file to 0777 temporarily and update your settings again. Afterward you can reset its permissions if you like. The location of this file is: ' . ABSPATH . 'wp-config.php\\\');</script>\';'));
				/**/
				return false;
			}
		else if (file_exists(dirname(ABSPATH) . "/wp-config.php") && !is_writable(dirname(ABSPATH) . "/wp-config.php"))
			{
				register_shutdown_function(create_function('', 'echo \'<script type="text/javascript">alert(\\\'[ Quick Cache Error: ] Your wp-config.php file is not writable at the moment. Please chmod this file to 0777 temporarily and update your settings again. Afterward you can reset its permissions if you like. The location of this file is: ' . dirname(ABSPATH) . 'wp-config.php\\\');</script>\';'));
				/**/
				return false;
			}
		else if (!file_exists(ABSPATH . "wp-config.php") && !file_exists(dirname(ABSPATH) . "/wp-config.php"))
			{
				register_shutdown_function(create_function('', 'echo \'<script type="text/javascript">alert(\\\'[ Quick Cache Error: ] Your wp-config.php file could not be found. Caching has NOT been enabled. Could not find: ' . ABSPATH . 'wp-config.php. Also tried: ' . dirname(ABSPATH) . 'wp-config.php. Please check your WordPress® installation.\\\');</script>\';'));
				/**/
				return false;
			}
		else /* Defaults to false. */
			{
				register_shutdown_function(create_function('', 'echo \'<script type="text/javascript">alert(\\\'[ Quick Cache Error: ] Your wp-config.php file could not be found. Caching has NOT been enabled.\\\');</script>\';'));
				/**/
				return false;
			}
	}
/*
Remove WP_CACHE from the config file(s).
*/
function ws_plugin__qcache_remove_wp_cache ()
	{
		if (file_exists(ABSPATH . "wp-config.php") && is_writable(ABSPATH . "wp-config.php"))
			{
				$config = file_get_contents(ABSPATH . "wp-config.php");
				$config = preg_replace("/( ?)(define)( ?)(\()( ?)(['\"])WP_CACHE(['\"])( ?)(,)( ?)(true|false)( ?)\)( ?);/i", "", $config);
				file_put_contents(ABSPATH . "wp-config.php", $config);
				/**/
				return true;
			}
		else if (file_exists(dirname(ABSPATH) . "/wp-config.php") && is_writable(dirname(ABSPATH) . "/wp-config.php"))
			{
				$config = file_get_contents(dirname(ABSPATH) . "/wp-config.php");
				$config = preg_replace("/( ?)(define)( ?)(\()( ?)(['\"])WP_CACHE(['\"])( ?)(,)( ?)(true|false)( ?)\)( ?);/i", "", $config);
				file_put_contents(dirname(ABSPATH) . "/wp-config.php", $config);
				/**/
				return true;
			}
		else if (file_exists(ABSPATH . "wp-config.php") && !is_writable(ABSPATH . "wp-config.php"))
			{
				register_shutdown_function(create_function('', 'echo \'<script type="text/javascript">alert(\\\'[ Quick Cache Error: ] Your wp-config.php file is not writable at the moment. You will need to remove the following line from that file manually: define("WP_CACHE", true); The location of this file is: ' . ABSPATH . 'wp-config.php\\\');</script>\';'));
				/**/
				return false;
			}
		else if (file_exists(dirname(ABSPATH) . "/wp-config.php") && !is_writable(dirname(ABSPATH) . "/wp-config.php"))
			{
				register_shutdown_function(create_function('', 'echo \'<script type="text/javascript">alert(\\\'[ Quick Cache Error: ] Your wp-config.php file is not writable at the moment. You will need to remove the following line from that file manually: define("WP_CACHE", true); The location of this file is: ' . dirname(ABSPATH) . 'wp-config.php\\\');</script>\';'));
				/**/
				return false;
			}
		else /* Defaults to true for removal. */
			{
				return true;
			}
	}
?>