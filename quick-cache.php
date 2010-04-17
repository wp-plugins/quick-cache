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
Version: 2.1.3
Framework: P-2.1
Stable tag: 2.1.3

WordPress Compatible: yes
WordPress MU Compatible: yes
MU Blog Farm Compatible: yes

Tested up to: 2.9
Requires at least: 2.8.4
Requires: WordPress® 2.8.4+, PHP 5.2+

Copyright: © 2009 WebSharks, Inc.
License: GNU General Public License
Contributors: WebSharks, PriMoThemes
Author URI: http://www.primothemes.com/
Author: PriMoThemes.com / WebSharks, Inc.
Donate link: http://www.primothemes.com/donate/

ZipId: quick-cache
FolderId: quick-cache
Plugin Name: Quick Cache
Plugin URI: http://www.primothemes.com/post/quick-cache-plugin-for-wordpress/
Description: Dramatically improves the performance & speed of your site! Also compatible with WordPress® MU.
Tags: cache, quick cache, quick-cache, quickcache, speed, performance, loading, generation, execution, benchmark, benchmarking, debug, debugging, caching, cash, caching, cacheing, super cache, advanced cache, advanced-cache, wp-cache, wp cache, options panel included, websharks framework, w3c validated code, includes extensive documentation, highly extensible
*/
/*
Direct access denial.
*/
if (realpath (__FILE__) === realpath ($_SERVER["SCRIPT_FILENAME"]))
	exit;
/*
Compatibility checks.
*/
if (version_compare (PHP_VERSION, "5.2", ">=") && version_compare (get_bloginfo ("version"), "2.8.4", ">=") && basename (dirname (__FILE__)) !== "mu-plugins" && !isset ($GLOBALS["WS_PLUGIN__"]["qcache"]))
	{
		/*
		Record the location of this file.
		*/
		$GLOBALS["WS_PLUGIN__"]["qcache"]["l"] = __FILE__;
		/*
		Function includes.
		*/
		include_once dirname (__FILE__) . "/includes/funcs.inc.php";
		/*
		Syscon includes.
		*/
		include_once dirname (__FILE__) . "/includes/syscon.inc.php";
		/*
		Hook includes.
		*/
		include_once dirname (__FILE__) . "/includes/hooks.inc.php";
	}
/*
Else handle incompatibilities.
*/
else if (is_admin () || ($_GET["preview"] && $_GET["template"]))
	{
		if (!version_compare (PHP_VERSION, "5.2", ">="))
			{
				register_shutdown_function (create_function ('', 'echo \'<script type="text/javascript">alert(\\\'You need PHP version 5.2 or higher to use the Quick Cache plugin.\\\');</script>\';'));
			}
		else if (!version_compare (get_bloginfo ("version"), "2.8.4", ">="))
			{
				register_shutdown_function (create_function ('', 'echo \'<script type="text/javascript">alert(\\\'You need WordPress® 2.8.4 or higher to use the Quick Cache plugin.\\\');</script>\';'));
			}
		else if (basename (dirname (__FILE__)) === "mu-plugins")
			{
				register_shutdown_function (create_function ('', 'echo \'<script type="text/javascript">alert(\\\'The Quick Cache plugin is compatible with WordPress® MU. However, the Quick Cache plugin should NOT be in the /mu-plugins directory. Please move it into the standard /plugins directory, then re-activate.\\\');</script>\';'));
			}
	}
?>