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
Add WP_CACHE to the config file(s).
*/
function ws_plugin__qcache_add_wp_cache ()
	{
		if (!ws_plugin__qcache_delete_wp_cache ())
			{
				return false; /* Do not proceed if unable to delete. */
			}
		else if (file_exists (ABSPATH . "wp-config.php") && is_writable (ABSPATH . "wp-config.php"))
			{
				$config = file_get_contents (ABSPATH . "wp-config.php");
				$config = preg_replace ("/^([\r\n\t ]*)(\<\?)(php)?/i", "<?php define('WP_CACHE', true);", $config);
				file_put_contents (ABSPATH . "wp-config.php", $config);
				/**/
				return true;
			}
		else if (file_exists (dirname (ABSPATH) . "/wp-config.php") && is_writable (dirname (ABSPATH) . "/wp-config.php"))
			{
				$config = file_get_contents (dirname (ABSPATH) . "/wp-config.php");
				$config = preg_replace ("/^([\r\n\t ]*)(\<\?)(php)?/i", "<?php define('WP_CACHE', true);", $config);
				file_put_contents (dirname (ABSPATH) . "/wp-config.php", $config);
				/**/
				return true;
			}
		else /* Defaults to false. */
			{
				return false;
			}
	}
/*
Delete WP_CACHE from the config file(s).
*/
function ws_plugin__qcache_delete_wp_cache ()
	{
		if (file_exists (ABSPATH . "wp-config.php") && is_writable (ABSPATH . "wp-config.php"))
			{
				$config = file_get_contents (ABSPATH . "wp-config.php");
				$config = preg_replace ("/( ?)(define)( ?)(\()( ?)(['\"])WP_CACHE(['\"])( ?)(,)( ?)(true|false)( ?)(\))( ?);/i", "", $config);
				file_put_contents (ABSPATH . "wp-config.php", $config);
				/**/
				return true;
			}
		else if (file_exists (dirname (ABSPATH) . "/wp-config.php") && is_writable (dirname (ABSPATH) . "/wp-config.php"))
			{
				$config = file_get_contents (dirname (ABSPATH) . "/wp-config.php");
				$config = preg_replace ("/( ?)(define)( ?)(\()( ?)(['\"])WP_CACHE(['\"])( ?)(,)( ?)(true|false)( ?)(\))( ?);/i", "", $config);
				file_put_contents (dirname (ABSPATH) . "/wp-config.php", $config);
				/**/
				return true;
			}
		else if (file_exists (ABSPATH . "wp-config.php") && !is_writable (ABSPATH . "wp-config.php"))
			{
				return false;
			}
		else if (file_exists (dirname (ABSPATH) . "/wp-config.php") && !is_writable (dirname (ABSPATH) . "/wp-config.php"))
			{
				return false;
			}
		else /* Defaults to true for deletion. */
			{
				return true;
			}
	}
?>