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
Function that extends array_unique to
support multi-dimensional arrays.
*/
function ws_plugin__qcache_array_unique ($array = FALSE)
	{
		if (!is_array ($array))
			{
				return array ($array);
			}
		else /* Serialized array_unique. */
			{
				foreach ($array as &$value)
					{
						$value = serialize ($value);
					}
				/**/
				$array = array_unique ($array);
				/**/
				foreach ($array as &$value)
					{
						$value = unserialize ($value);
					}
				/**/
				return $array;
			}
	}
/*
Function that buffers ( gets ) function output.
*/
function ws_plugin__qcache_get ($function = FALSE)
	{
		$args = func_get_args ();
		$function = array_shift ($args);
		/**/
		if (is_string ($function) && $function)
			{
				ob_start ();
				/**/
				if (is_array ($args) && !empty ($args))
					{
						$return = call_user_func_array ($function, $args);
					}
				else /* There are no additional arguments to pass. */
					{
						$return = call_user_func ($function);
					}
				/**/
				$echo = ob_get_contents ();
				/**/
				ob_end_clean ();
				/**/
				return (!strlen ($echo) && strlen ($return)) ? $return : $echo;
			}
		else /* Else return null. */
			return;
	}
?>