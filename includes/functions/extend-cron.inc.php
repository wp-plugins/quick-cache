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
Extends the WP_Cron schedules to support 5 minute intervals.
Attach to: add_filter("cron_schedules");
*/
function ws_plugin__qcache_extend_cron_schedules ($schedules = array ())
	{
		$array = array ("every5m" => array ("interval" => 300, "display" => "Every 5 Minutes"));
		/**/
		return array_merge ($array, $schedules);
	}
?>