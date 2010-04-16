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
Add the plugin actions/filters here.
*/
add_action("init", "ws_plugin__qcache_menu_pages_js");
add_action("init", "ws_plugin__qcache_menu_pages_css");
/**/
add_filter("gettext", "ws_plugin__qcache_translation_mangler", 10, 3);
/**/
add_action("admin_notices", "ws_plugin__qcache_admin_notices");
add_action("admin_menu", "ws_plugin__qcache_add_admin_options");
add_action("admin_print_scripts", "ws_plugin__qcache_add_admin_scripts");
add_action("admin_print_styles", "ws_plugin__qcache_add_admin_styles");
/**/
add_action("save_post", "ws_plugin__qcache_clear_on_post_page_creations_deletions");
add_action("edit_post", "ws_plugin__qcache_clear_on_post_page_creations_deletions");
add_action("delete_post", "ws_plugin__qcache_clear_on_post_page_creations_deletions");
/**/
add_action("create_category", "ws_plugin__qcache_clear_on_creations_deletions");
add_action("edit_category", "ws_plugin__qcache_clear_on_creations_deletions");
add_action("delete_category", "ws_plugin__qcache_clear_on_creations_deletions");
/**/
add_action("add_link", "ws_plugin__qcache_clear_on_creations_deletions");
add_action("edit_link", "ws_plugin__qcache_clear_on_creations_deletions");
add_action("delete_link", "ws_plugin__qcache_clear_on_creations_deletions");
/**/
add_action("updated_option", "ws_plugin__qcache_clear_on_options_updated");
add_action("switch_theme", "ws_plugin__qcache_clear_on_theme_change");
/**/
add_action("qcache_cron_remove_cache_dir", "ws_plugin__qcache_remove_cache_dir");
add_action("qcache_cron_garbage_collector", "ws_plugin__qcache_garbage_collector");
/*
Register the activation | de-activation routines.
*/
register_activation_hook($GLOBALS["WS_PLUGIN__"]["qcache"]["l"], "ws_plugin__qcache_activate");
register_deactivation_hook($GLOBALS["WS_PLUGIN__"]["qcache"]["l"], "ws_plugin__qcache_deactivate");
?>