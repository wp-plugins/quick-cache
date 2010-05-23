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
Mangles internal translations.
Attach to: add_filter("gettext");
*/
function ws_plugin__qcache_translation_mangler ($translated = FALSE, $original = FALSE, $domain = FALSE)
	{
		static $is_admin_media_upload; /* Optimizes this routine. */
		/**/
		if (!isset ($is_admin_media_upload) || $is_admin_media_upload)
			{
				if ($is_admin_media_upload || (is_admin () && preg_match ("/\/(async-upload|media-upload)\.php/", $_SERVER["REQUEST_URI"])))
					{
						$is_admin_media_upload = true;
						/**/
						if ($translated === "Insert into Post")
							{
								$translated = "Insert";
							}
					}
				else
					{
						$is_admin_media_upload = false;
					}
			}
		/**/
		return $translated;
	}
?>