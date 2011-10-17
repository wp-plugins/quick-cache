<?php
/*
Copyright: © 2009 WebSharks, Inc. ( coded in the USA )
<mailto:support@websharks-inc.com> <http://www.websharks-inc.com/>

Released under the terms of the GNU General Public License.
You should have received a copy of the GNU General Public License,
along with this software. In the main directory, see: /licensing/
If not, see: <http://www.gnu.org/licenses/>.
*/
if (realpath (__FILE__) === realpath ($_SERVER["SCRIPT_FILENAME"]))
	exit("Do not access this file directly.");
/**/
if (!class_exists ("c_ws_plugin__qcache_utils_urls"))
	{
		class c_ws_plugin__qcache_utils_urls
			{
				/*
				Responsible for remote communications processed by this plugin.
					`wp_remote_request()` through the `WP_Http` class.
				*/
				public static function remote ($url = FALSE, $post_vars = FALSE, $args = FALSE, $return = FALSE)
					{
						if ($url && is_string ($url)) /* We MUST have a valid full URL (string) before we do anything in this routine. */
							{
								$args = (!is_array ($args)) ? array (): $args; /* Force array & disable SSL verification. */
								$args["sslverify"] = (!isset ($args["sslverify"])) ? /* Off. */ false : $args["sslverify"];
								/**/
								if ((is_array ($post_vars) || is_string ($post_vars)) && !empty ($post_vars))
									$args = array_merge ($args, array ("method" => "POST", "body" => $post_vars));
								/**/
								if (preg_match ("/^https/i", $url) && stripos (PHP_OS, "win") === 0)
									add_filter ("use_curl_transport", "__return_false", ($curl_disabled = 1352));
								/**/
								if (!has_filter ("http_response", "c_ws_plugin__qcache_utils_urls::_remote_gz_variations"))
									add_filter ("http_response", "c_ws_plugin__qcache_utils_urls::_remote_gz_variations");
								/**/
								$response = wp_remote_request ($url, $args); /* Try to process the remote request now. */
								/**/
								if ($return === "array" /* Return array? */ && !is_wp_error ($response) && is_array ($response))
									{
										$r = array ("code" => (int)wp_remote_retrieve_response_code ($response), "message" => wp_remote_retrieve_response_message ($response));
										/**/
										$r = array_merge ($r, array ("o_headers" => wp_remote_retrieve_headers ($response), "headers" => array ()));
										foreach (array_keys ($r["o_headers"]) as $header) /* Array of lowercase headers makes things easier. */
											$r["headers"][strtolower ($header)] = $r["o_headers"][$header];
										/**/
										$r = array_merge ($r, array ("body" => wp_remote_retrieve_body ($response), "response" => $response));
									}
								/**/
								else if (!is_wp_error ($response) && is_array ($response)) /* Else returning ``$response`` body only. */
									$r = wp_remote_retrieve_body ($response);
								/**/
								else /* Else this remote request has failed completely. We must return a `false` value. */
									$r = false; /* Remote request failed, return false. */
								/**/
								if (isset ($curl_disabled) && $curl_disabled === 1352) /* Remove this Filter now? */
									remove_filter ("use_curl_transport", "__return_false", 1352);
								/**/
								return $r; /* The ``$r`` return value. */
							}
						/**/
						else /* Else, return false. */
							return false;
					}
				/*
				Filters the WP_Http response for additional gzinflate variations.
					Attach to: add_filter("http_response");
				*/
				public static function _remote_gz_variations ($response = array ())
					{
						if (!isset ($response["ws__gz_variations"]) && ($response["ws__gz_variations"] = 1))
							{
								if (!empty ($response["headers"]["content-encoding"]))
									if (!empty ($response["body"]) && substr ($response["body"], 0, 2) === "\x78\x9c")
										if (($gz = @gzinflate (substr ($response["body"], 2))))
											$response["body"] = $gz;
							}
						/**/
						return $response; /* Return response. */
					}
				/*
				Parses out a full valid URI, from either a full URL, or a partial URI.
				*/
				public static function parse_uri ($url_or_uri = FALSE)
					{
						if (is_string ($url_or_uri) && is_array ($parse = c_ws_plugin__qcache_utils_urls::parse_url ($url_or_uri)))
							{
								$parse["path"] = (!empty ($parse["path"])) ? ((strpos ($parse["path"], "/") === 0) ? $parse["path"] : "/" . $parse["path"]) : "/";
								/**/
								return (!empty ($parse["query"])) ? $parse["path"] . "?" . $parse["query"] : $parse["path"];
							}
						else /* Force a string return value here. */
							return ""; /* Empty string. */
					}
				/*
				Parses a URL with mostly the same args as PHP's ``parse_url()`` function.
				*/
				public static function parse_url ($url_or_uri = FALSE, $component = FALSE, $clean_path = TRUE)
					{
						$component = ($component === false || $component === -1) ? -1 : $component;
						/**/
						if (is_string ($url_or_uri) && strpos ($url_or_uri, "?") !== "false") /* A query string? */
							{
								list ($_, $query) = preg_split ("/\?/", $url_or_uri, 2); /* Split at the query string. */
								/* Works around bug in many versions of PHP. See: <https://bugs.php.net/bug.php?id=38143>. */
								$query = str_replace ("://", urlencode ("://"), $query);
								$url_or_uri = $_ . "?" . $query;
							}
						/**/
						$parse = @parse_url ($url_or_uri, $component); /* Let PHP work its magic now. */
						/**/
						if ($clean_path && isset ($parse["path"]) && is_string ($parse["path"]) && !empty ($parse["path"]))
							$parse["path"] = preg_replace ("/\/+/", "/", $parse["path"]);
						/**/
						return ($component !== -1) ? /* Force a string return value here? */ (string)$parse : $parse;
					}
			}
	}
?>