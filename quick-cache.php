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
Version: 2.1.7
Stable tag: 2.1.7
Framework: WS-P-2.3

WordPress Compatible: yes
WP Multisite Compatible: yes
Multisite Blog Farm Compatible: yes

Tested up to: 3.0
Requires at least: 2.9.2
Requires: WordPress® 2.9.2+, PHP 5.2+

Copyright: © 2009 WebSharks, Inc.
License: GNU General Public License
Contributors: WebSharks, PriMoThemes
Author URI: http://www.primothemes.com/
Author: PriMoThemes.com / WebSharks, Inc.
Donate link: http://www.primothemes.com/donate/

Plugin Name: Quick Cache
Forum URI: http://www.primothemes.com/forums/viewforum.php?f=5
Plugin URI: http://www.primothemes.com/post/quick-cache-plugin-for-wordpress/
Description: Dramatically improves the performance & speed of your site! Also compatible with WordPress® Multisite/Networking.
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
if (version_compare (PHP_VERSION, "5.2", ">=") && version_compare (get_bloginfo ("version"), "2.9.2", ">=") && basename (dirname (__FILE__)) !== basename (WPMU_PLUGIN_DIR) && !isset ($GLOBALS["WS_PLUGIN__"]["qcache"]))
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
else if (is_admin ()) /* Admin compatibility errors. */
	{
		if (!version_compare (PHP_VERSION, "5.2", ">="))
			{
				add_action ("admin_notices", create_function ('', 'echo \'<div class="error fade"><p>You need PHP version 5.2 or higher to use the Quick Cache plugin.</p></div>\';'));
			}
		else if (!version_compare (get_bloginfo ("version"), "2.9.2", ">="))
			{
				add_action ("admin_notices", create_function ('', 'echo \'<div class="error fade"><p>You need WordPress® 2.9.2 or higher to use the Quick Cache plugin.</p></div>\';'));
			}
		else if (basename (dirname (__FILE__)) === basename (WPMU_PLUGIN_DIR))
			{
				add_action ("admin_notices", create_function ('', 'echo \'<div class="error fade"><p>The Quick Cache plugin is compatible with WordPress® Multisite. However, the Quick Cache plugin should NOT be in the <code>/' . basename (WPMU_PLUGIN_DIR) . '</code> directory. Please move it into the standard <code>/plugins</code> directory, then re-activate.</p></div>\';'));
			}
	}
?>