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
Function that handles readme.txt parsing.
*/
function ws_plugin__qcache_parse_readme ()
	{
		if (file_exists(dirname(dirname(dirname(__FILE__))) . "/readme.txt"))
			{
				if (!function_exists("NC_Markdown"))
					include_once dirname(dirname(__FILE__)) . "/markdown/nc-markdown.inc.php";
				/**/
				$rm = file_get_contents(dirname(dirname(dirname(__FILE__))) . "/readme.txt"); /* Get readme.txt file contents. */
				$mb = function_exists("mb_convert_encoding") ? @mb_convert_encoding($rm, "UTF-8",@mb_detect_encoding($rm, "WINDOWS-1252, UTF-8")) : $rm;
				$rm = ($mb) ? $mb : $rm; /* Double check this, just in case conversion fails on an unpredicted charset. */
				/**/
				$rm = preg_replace("/(\=\=\=)( )(.+?)( )(\=\=\=)/", "<h2>Specifications</h2>", $rm);
				$rm = preg_replace("/(\=\=)( )(.+?)( )(\=\=)/", "<h2>$3</h2>", $rm);
				$rm = preg_replace("/(\=)( )(.+?)( )(\=)/", "<h3>$3</h3>", $rm);
				$rm = NC_Markdown($rm); /* Parse out the Markdown syntax now. */
				/**/
				$r1 = "/(\<h2\>)(.+?)(\<\/h2\>)(.+?)(\<h2\>|$)/si";
				$r2 = "/(\<\/div\>)(\<h2\>)(.+?)(\<\/h2\>)(.+?)(\<div class\=\"section\"\>\<h2\>|$)/si";
				$r3 = "/(\<div class\=\"section\"\>)(\<h2\>)(Specifications)(\<\/h2\>)(\<div class\=\"content\"\>)(.+?)(\<\/div\>\<\/div\>)/sei";
				$r4 = "/(\<div class\=\"section\"\>)(\<h2\>)(Screenshots)(\<\/h2\>)(\<div class\=\"content\"\>)(.+?)(\<\/div\>\<\/div\>)/si";
				/**/
				$rm = preg_replace($r1, '<div class="section">' . "$1$2$3" . '<div class="content">' . "$4" . '</div></div>' . "$5", $rm);
				$rm = preg_replace($r2, "$1" . '<div class="section">' . "$2$3$4" . '<div class="content">' . "$5" . '</div></div>' . "$6", $rm);
				$rm = stripslashes(preg_replace($r3, "'\\1\\2\\3\\4\\5'._ws_plugin__qcache_parse_readme_specs('\\6').'\\7'", $rm, 1));
				$rm = stripslashes(preg_replace($r4, "", $rm, 1)); /* Here we just remove the screenshots completely. */
				/**/
				$readme = '<div class="readme">' . "\n";
				$readme .= $rm . "\n"; /* Content. */
				$readme .= '</div>' . "\n";
				/**/
				return $readme;
			}
		else /* In case readme.txt is deleted. */
			{
				return "Unable to parse /readme.txt.";
			}
	}
/*
Callback function that helps readme file parsing with specs.
*/
function _ws_plugin__qcache_parse_readme_specs ($str = FALSE)
	{
		$str = preg_replace("/(\<p\>|^)(.+?)(\:)( )(.+?)($|\<\/p\>)/mi", "$1" . '<li><strong>' . "$2" . '</strong>' . "$3" . '&nbsp;&nbsp;&nbsp;&nbsp;<code>' . "$5" . '</code></li>' . "$6", $str);
		$str = preg_replace("/\<p\>\<li\>/i", '<ul><li>', $str); /* Open the list items. */
		$str = preg_replace("/\<\/li\>\<\/p\>/i", '</li></ul><br />', $str);
		/**/
		return $str;
	}
/*
Function for parsing readme.txt files and returning a key value.
*/
function ws_plugin__qcache_parse_readme_value ($key = FALSE)
	{
		static $readme; /* For repeated lookups. */
		/**/
		$path = dirname(dirname(dirname(__FILE__))) . "/readme.txt";
		/**/
		if (isset($readme) || file_exists($path))
			{
				if (!isset($readme)) /* If not already opened, we need open it up now. */
					{
						$readme = file_get_contents($path); /* Get readme.txt file contents. */
						$mb = function_exists("mb_convert_encoding") ? @mb_convert_encoding($readme, "UTF-8",@mb_detect_encoding($readme, "WINDOWS-1252, UTF-8")) : $readme;
						$readme = ($mb) ? $mb : $readme; /* Double check this, just in case conversion fails on an unpredicted charset. */
					}
				/**/
				preg_match("/(^)(" . preg_quote($key, "/") . ")(\:)( )(.+?)($)/m", $readme, $m);
				/**/
				return strlen($m[5] = trim($m[5])) ? $m[5] : false;
			}
		else /* Nope. */
			return false;
	}
?>