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
/**/
if (!class_exists ("c_ws_plugin__qcache_utils_urls"))
	{
		class c_ws_plugin__qcache_utils_urls
			{
				/*
				Responsible for remote communications processed by this plugin.
					`wp_remote_request()` through the `WP_Http` class.
				*/
				public static function remote ($url = FALSE, $post_vars = FALSE, $args = array ())
					{
						static $http_response_filtered = false; /* Apply GZ filters only once. */
						/**/
						$args = (!is_array ($args)) ? array (): $args; /* Disable SSL verifications. */
						$args["sslverify"] = (!isset ($args["sslverify"])) ? false : $args["sslverify"];
						/**/
						if (!$http_response_filtered && ($http_response_filtered = true))
							add_filter ("http_response", "c_ws_plugin__qcache_utils_urls::_remote_gz_variations");
						/**/
						if ($url) /* Obviously, we must have a valid URL before we do anything at all here. */
							{
								if (preg_match ("/^https/i", $url) && strtolower (substr (PHP_OS, 0, 3)) === "win")
									add_filter ("use_curl_transport", "__return_false", ($curl_disabled = 1352));
								/**/
								if ((is_array ($post_vars) || is_string ($post_vars)) && !empty ($post_vars))
									$args = array_merge ($args, array ("method" => "POST", "body" => $post_vars));
								/**/
								$body = wp_remote_retrieve_body (wp_remote_request ($url, $args));
								/**/
								if ($curl_was_disabled_by_this_routine_with_1352_priority = $curl_disabled)
									remove_filter ("use_curl_transport", "__return_false", 1352);
								/**/
								return $body; /* The body content received. */
							}
						/**/
						return false; /* Else return false. */
					}
				/*
				Filters the WP_Http response for additional gzinflate variations.
					Attach to: add_filter("http_response");
				*/
				public static function _remote_gz_variations ($response = array ())
					{
						if (!isset ($response["ws__gz_variations"]) && ($response["ws__gz_variations"] = 1))
							{
								if ($response["headers"]["content-encoding"])
									if (substr ($response["body"], 0, 2) === "\x78\x9c")
										if (($gz = @gzinflate (substr ($response["body"], 2))))
											$response["body"] = $gz;
							}
						/**/
						return $response;
					}
			}
	}
?>