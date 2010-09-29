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
This function monitors status header codes.
Attach to: add_filter("status_header");
*/
if (!function_exists ("ws_plugin__qcache_status"))
	{
		function ws_plugin__qcache_status ($header = FALSE, $status = FALSE)
			{
				$GLOBALS["QUICK_CACHE_STATUS"] = $status;
				/**/
				return $header;
			}
	}
?>