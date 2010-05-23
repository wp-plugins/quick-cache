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
Function displays an admin notice immediately.
*/
function ws_plugin__qcache_display_admin_notice ($notice = FALSE, $error = FALSE)
	{
		if ($notice && $error) /* Special format for errors. */
			{
				echo '<div class="error fade"><p>' . $notice . '</p></div>';
			}
		else if ($notice) /* Otherwise, we just send it as an update notice. */
			{
				echo '<div class="updated fade"><p>' . $notice . '</p></div>';
			}
		/**/
		return;
	}
/*
Function that enqueues admin notices.
*/
function ws_plugin__qcache_enqueue_admin_notice ($notice = FALSE, $on_pages = FALSE, $error = FALSE, $time = FALSE)
	{
		if ($notice && is_string ($notice)) /* If we have a valid string. */
			{
				$notices = (array)get_option ("ws_plugin__qcache_notices");
				/**/
				array_push ($notices, array ("notice" => $notice, "on_pages" => $on_pages, "error" => $error, "time" => $time));
				/**/
				update_option ("ws_plugin__qcache_notices", ws_plugin__qcache_array_unique ($notices));
			}
		/**/
		return;
	}
/*
Function that displays admin notices.
Attach to: add_action("admin_notices");
*/
function ws_plugin__qcache_admin_notices ()
	{
		global $pagenow; /* This holds the current page filename. */
		/**/
		if (is_array ($notices = get_option ("ws_plugin__qcache_notices")) && !empty ($notices))
			{
				foreach ($notices as $key => $notice) /* Check time on each notice. */
					{
						if (empty ($notice["on_pages"]) || $pagenow === $notice["on_pages"] || in_array ($pagenow, (array)$notice["on_pages"]) || $_GET["page"] === $notice["on_pages"] || in_array ($_GET["page"], (array)$notice["on_pages"]))
							{
								if (strtotime ("now") >= $notice["time"]) /* Time to show it? */
									{
										unset ($notices[$key]); /* Clear this notice & display it. */
										/**/
										ws_plugin__qcache_display_admin_notice ($notice["notice"], $notice["error"]);
									}
							}
					}
				/**/
				$notices = array_merge ($notices); /* Re-index array keys. */
				/**/
				update_option ("ws_plugin__qcache_notices", $notices);
			}
		/**/
		return;
	}
?>