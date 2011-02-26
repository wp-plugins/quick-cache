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
Function that enqueues Admin Notices.
*/
if (!function_exists ("ws_plugin__qcache_enqueue_admin_notice"))
	{
		function ws_plugin__qcache_enqueue_admin_notice ($notice = FALSE, $on_pages = FALSE, $error = FALSE, $time = FALSE, $dismiss = FALSE)
			{
				eval ('foreach(array_keys(get_defined_vars())as$__v)$__refs[$__v]=&$$__v;');
				do_action ("ws_plugin__qcache_before_enqueue_admin_notice", get_defined_vars ());
				unset ($__refs, $__v); /* Unset defined __refs, __v. */
				/**/
				if ($notice && is_string ($notice)) /* If we have a valid string. */
					{
						$notices = (array)get_option ("ws_plugin__qcache_notices");
						/**/
						array_push ($notices, array ("notice" => $notice, "on_pages" => $on_pages, "error" => $error, "time" => $time, "dismiss" => $dismiss));
						/**/
						eval ('foreach(array_keys(get_defined_vars())as$__v)$__refs[$__v]=&$$__v;');
						do_action ("ws_plugin__qcache_during_enqueue_admin_notice", get_defined_vars ());
						unset ($__refs, $__v); /* Unset defined __refs, __v. */
						/**/
						update_option ("ws_plugin__qcache_notices", ws_plugin__qcache_array_unique ($notices));
					}
				/**/
				do_action ("ws_plugin__qcache_after_enqueue_admin_notice", get_defined_vars ());
				/**/
				return; /* Return for uniformity. */
			}
	}
/*
Function displays an Admin Notice immediately.
*/
if (!function_exists ("ws_plugin__qcache_display_admin_notice"))
	{
		function ws_plugin__qcache_display_admin_notice ($notice = FALSE, $error = FALSE, $dismiss = FALSE)
			{
				eval ('foreach(array_keys(get_defined_vars())as$__v)$__refs[$__v]=&$$__v;');
				do_action ("ws_plugin__qcache_before_display_admin_notice", get_defined_vars ());
				unset ($__refs, $__v); /* Unset defined __refs, __v. */
				/**/
				if ($notice && $error) /* Special format for errors. */
					{
						$notice .= ($dismiss) ? ' [ <a href="' . add_query_arg ("ws-plugin--qcache-dismiss-admin-notice", urlencode (md5 ($notice)), $_SERVER["REQUEST_URI"]) . '">dismiss message</a> ]' : '';
						/**/
						echo '<div class="error fade"><p>' . $notice . '</p></div>'; /* Displays the error message. */
					}
				else if ($notice) /* Otherwise, we send it as an update notice. */
					{
						$notice .= ($dismiss) ? ' [ <a href="' . add_query_arg ("ws-plugin--qcache-dismiss-admin-notice", urlencode (md5 ($notice)), $_SERVER["REQUEST_URI"]) . '">dismiss message</a> ]' : '';
						/**/
						echo '<div class="updated fade"><p>' . $notice . '</p></div>'; /* Displays info message. */
					}
				/**/
				do_action ("ws_plugin__qcache_after_display_admin_notice", get_defined_vars ());
				/**/
				return; /* Return for uniformity. */
			}
	}
/*
Function that displays Admin Notices.
Attach to: add_action("admin_notices");
Attach to: add_action("user_admin_notices");
Attach to: add_action("network_admin_notices");
*/
if (!function_exists ("ws_plugin__qcache_admin_notices"))
	{
		function ws_plugin__qcache_admin_notices ()
			{
				global $pagenow; /* This holds the current page filename. */
				/**/
				do_action ("ws_plugin__qcache_before_admin_notices", get_defined_vars ());
				/**/
				if (is_admin () && is_array ($notices = get_option ("ws_plugin__qcache_notices")) && !empty ($notices))
					{
						$a = (ws_plugin__qcache_is_blog_admin ()) ? "blog" : $a;
						$a = (ws_plugin__qcache_is_user_admin ()) ? "user" : $a;
						$a = (ws_plugin__qcache_is_network_admin ()) ? "network" : $a;
						$a = (!$a) ? "blog" : $a; /* Default Blog Admin. */
						/**/
						foreach ($notices as $i => $notice) /* Check several things about each Notice. */
							foreach (( (!$notice["on_pages"]) ? array ("*"): (array)$notice["on_pages"]) as $page)
								{
									if (!preg_match ("/^(.+?)\:/", $page)) /* NO prefix? */
										$page = "blog:" . ltrim ($page, ":"); /* `blog:` */
									/**/
									$adms = preg_split ("/\|/", preg_replace ("/\:(.*)$/i", "", $page));
									$page = preg_replace ("/^([^\:]*)\:/i", "", $page);
									/**/
									if (empty ($adms) || in_array ("*", $adms) || in_array ($a, $adms) || !version_compare (get_bloginfo ("version"), "3.1-RC", ">="))
										if (!$page || "*" === $page || $pagenow === $page || $_GET["page"] === $page)
											{
												if (strtotime ("now") >= (int)$notice["time"]) /* Time to show it? */
													{
														eval ('foreach(array_keys(get_defined_vars())as$__v)$__refs[$__v]=&$$__v;');
														do_action ("ws_plugin__qcache_during_admin_notices_before_display", get_defined_vars ());
														unset ($__refs, $__v); /* Unset defined __refs, __v. */
														/**/
														if (!$notice["dismiss"] || $_GET["ws-plugin--qcache-dismiss-admin-notice"] === md5 ($notice["notice"]))
															unset ($notices[$i]); /* Clear this administrative notice now? */
														/**/
														if (!$notice["dismiss"] || $_GET["ws-plugin--qcache-dismiss-admin-notice"] !== md5 ($notice["notice"]))
															ws_plugin__qcache_display_admin_notice ($notice["notice"], $notice["error"], $notice["dismiss"]);
														/**/
														do_action ("ws_plugin__qcache_during_admin_notices_after_display", get_defined_vars ());
													}
												/**/
												continue 2; /* This Notice processed; continue to next. */
											}
								}
						/**/
						$notices = array_merge ($notices); /* Re-index array. */
						/**/
						eval ('foreach(array_keys(get_defined_vars())as$__v)$__refs[$__v]=&$$__v;');
						do_action ("ws_plugin__qcache_during_admin_notices", get_defined_vars ());
						unset ($__refs, $__v); /* Unset defined __refs, __v. */
						/**/
						update_option ("ws_plugin__qcache_notices", $notices);
					}
				/**/
				do_action ("ws_plugin__qcache_after_admin_notices", get_defined_vars ());
				/**/
				return; /* Return for uniformity. */
			}
	}
?>