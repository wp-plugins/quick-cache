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
Options page.
*/
echo '<div class="wrap ws-menu-page">' . "\n";
/**/
echo '<div id="icon-plugins" class="icon32"><br /></div>' . "\n";
echo '<h2><div>Developed by <a href="' . ws_plugin__qcache_parse_readme_value ("Plugin URI") . '"><img src="' . $GLOBALS["WS_PLUGIN__"]["qcache"]["c"]["dir_url"] . '/images/brand-light.png" alt="." /></a></div>Quick Cache Options</h2>' . "\n";
/**/
echo '<div class="ws-menu-page-hr"></div>' . "\n";
/**/
echo '<table class="ws-menu-page-table">' . "\n";
echo '<tbody class="ws-menu-page-table-tbody">' . "\n";
echo '<tr class="ws-menu-page-table-tr">' . "\n";
echo '<td class="ws-menu-page-table-l">' . "\n";
/**/
echo '<form method="post" name="ws_plugin__qcache_clear_form" id="ws-plugin--qcache-clear-form" class="ws-menu-page-right">' . "\n";
echo '<input type="hidden" name="ws_plugin__qcache_clear_cache" id="ws-plugin--qcache-clear-cache" value="' . esc_attr (wp_create_nonce ("ws-plugin--qcache-clear-cache")) . '" />' . "\n";
echo '<input type="submit" value="Clear Cache Manually" />' . "\n";
echo '</form>' . "\n";
/**/
echo '<form method="post" name="ws_plugin__qcache_options_form" id="ws-plugin--qcache-options-form">' . "\n";
echo '<input type="hidden" name="ws_plugin__qcache_options_save" id="ws-plugin--qcache-options-save" value="' . esc_attr (wp_create_nonce ("ws-plugin--qcache-options-save")) . '" />' . "\n";
echo '<input type="hidden" name="ws_plugin__qcache_configured" id="ws-plugin--qcache-configured" value="1" />' . "\n";
/**/
echo '<div class="ws-menu-page-section ws-plugin--qcache-activation-section">' . "\n";
echo '<h3>Quick Caching Enabled?</h3>' . "\n";
echo '<p class="ws-menu-page-hilite">You can turn caching on/off at any time you like. It is recommended that you turn it on. This really is the only option that you need to enable. All of the other options below are for web developers only, and are NOT required, because the defaults will work just fine. In other words, just turn Quick Cache on here, and then skip all the way down to the very bottom and click Save :-)</p>' . "\n";
/**/
echo '<table class="form-table">' . "\n";
echo '<tbody>' . "\n";
echo '<tr>' . "\n";
/**/
echo '<th>' . "\n";
echo '<label for="ws-plugin--qcache-enabled">' . "\n";
echo 'Caching Enabled?' . "\n";
echo '</label>' . "\n";
echo '</th>' . "\n";
/**/
echo '</tr>' . "\n";
echo '<tr>' . "\n";
/**/
echo '<td>' . "\n";
echo '<select name="ws_plugin__qcache_enabled" id="ws-plugin--qcache-enabled"' . ((!$GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["enabled"]) ? ' class="ws-menu-page-error-hilite"' : '') . '>' . "\n";
echo '<option value="0"' . ((!$GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["enabled"]) ? ' selected="selected"' : '') . '>Off ( Disabled )</option>' . "\n";
echo '<option value="1"' . (($GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["enabled"]) ? ' selected="selected"' : '') . '>On ( Enabled )</option>' . "\n";
echo '</select><br />' . "\n";
echo 'Quick Cache improves speed &amp; performance!' . "\n";
echo '</td>' . "\n";
/**/
echo '</tr>' . "\n";
echo '</tbody>' . "\n";
echo '</table>' . "\n";
echo '</div>' . "\n";
/**/
echo '<div class="ws-menu-page-hr ws-plugin--qcache-debugging-section-hr"></div>' . "\n";
/**/
echo '<div class="ws-menu-page-section ws-plugin--qcache-debugging-section">' . "\n";
echo '<h3>Enable Internal Debugging For Quick Cache?</h3>' . "\n";
echo '<p>This option is reserved for future implementation. There is already a built-in debugging system for Quick Cache that stays on at all times. Every file it caches and/or serves up will include a comment line or two at the very bottom of the file. Once Quick Cache is enabled you can simply (right-click -> View Source) on your site and look for these to appear. Quick Cache will also report major problems through this method as well. In the future, additional debugging routines will be added and this option will be used at that time for additional fine-tuning.</p>' . "\n";
/**/
echo '<table class="form-table">' . "\n";
echo '<tbody>' . "\n";
echo '<tr>' . "\n";
/**/
echo '<th>' . "\n";
echo '<label for="ws-plugin--qcache-enable-debugging">' . "\n";
echo 'Cache Debugging:' . "\n";
echo '</label>' . "\n";
echo '</th>' . "\n";
/**/
echo '</tr>' . "\n";
echo '<tr>' . "\n";
/**/
echo '<td>' . "\n";
echo '<select name="ws_plugin__qcache_enable_debugging" id="ws-plugin--qcache-enable-debugging">' . "\n";
echo '<option value="0"' . ((!$GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["enable_debugging"]) ? ' selected="selected"' : '') . '>False ( Disable )</option>' . "\n";
echo '<option value="1"' . (($GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["enable_debugging"]) ? ' selected="selected"' : '') . '>True ( Enable )</option>' . "\n";
echo '</select><br />' . "\n";
echo 'Recommended setting ( false ).' . "\n";
echo '</td>' . "\n";
/**/
echo '</tr>' . "\n";
echo '</tbody>' . "\n";
echo '</table>' . "\n";
echo '</div>' . "\n";
/**/
echo '<div class="ws-menu-page-hr ws-plugin--qcache-logged-in-section-hr"></div>' . "\n";
/**/
echo '<div class="ws-menu-page-section ws-plugin--qcache-logged-in-section">' . "\n";
echo '<h3>Don\'t Cache Pages For Logged In Users?</h3>' . "\n";
echo '<p>It is best to leave this set to true at all times. Most visitors are NOT logged in, so this does not hurt performance at all :-) Also, this setting includes some users who AREN\'T actually logged into the system, but who HAVE authored comments recently. This way comment authors will be able to see updates to the spool immediately. In other words, Quick Cache thinks of a comment author as a logged in user, even though technically they are not.</p>' . "\n";
/**/
echo '<table class="form-table">' . "\n";
echo '<tbody>' . "\n";
echo '<tr>' . "\n";
/**/
echo '<th>' . "\n";
echo '<label for="ws-plugin--qcache-dont-cache-when-logged-in">' . "\n";
echo 'Login Sessions:' . "\n";
echo '</label>' . "\n";
echo '</th>' . "\n";
/**/
echo '</tr>' . "\n";
echo '<tr>' . "\n";
/**/
echo '<td>' . "\n";
echo '<select name="ws_plugin__qcache_dont_cache_when_logged_in" id="ws-plugin--qcache-dont-cache-when-logged-in">' . "\n";
echo '<option value="1"' . (($GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["dont_cache_when_logged_in"]) ? ' selected="selected"' : '') . '>True ( Don\'t Cache )</option>' . "\n";
echo '<option value="0"' . ((!$GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["dont_cache_when_logged_in"]) ? ' selected="selected"' : '') . '>False ( Always Cache )</option>' . "\n";
echo '</select><br />' . "\n";
echo 'Recommended setting ( true ).' . "\n";
echo '</td>' . "\n";
/**/
echo '</tr>' . "\n";
echo '</tbody>' . "\n";
echo '</table>' . "\n";
echo '</div>' . "\n";
/**/
echo '<div class="ws-menu-page-hr ws-plugin--qcache-get-requests-section-hr"></div>' . "\n";
/**/
echo '<div class="ws-menu-page-section ws-plugin--qcache-get-requests-section">' . "\n";
echo '<h3>Don\'t Cache Query String GET Requests?</h3>' . "\n";
echo '<p>This should almost always be set to true, <strong>unless</strong> you are using unfriendly Permalinks on your site. In other words, if all of your URLs contain a query string <code>( /?something=something )</code>, you ARE using unfriendly Permalinks, and you should update your Permalink options in WordPress® immediately, because that also optimizes your site for search engines. That being said, if you really want to use unfriendly Permalinks, and only if you\'re using unfriendly Permalinks, you should set this to false; and don\'t worry too much, the sky won\'t fall on your head :-) It should also be noted that POST requests ( forms with method="POST" ) are always excluded from the cache, which is the way it should be. POST requests should never be cached. CLI requests are also excluded from the cache. A CLI request is one that comes from the command line; commonly used by cron jobs and other automated routines.</p>' . "\n";
echo '<p><em>* <b>Advanced Tip:</b> If you are NOT caching GET requests ( recommended ), but you do want to allow some special URLs that include query string parameters to be cached; you can add this special parameter to your URL <code>&amp;qcAC=1</code> to tell Quick Cache that it is OK to cache that particular URL, even though it contains query string arguments.</em></p>' . "\n";
/**/
echo '<table class="form-table">' . "\n";
echo '<tbody>' . "\n";
echo '<tr>' . "\n";
/**/
echo '<th>' . "\n";
echo '<label for="ws-plugin--qcache-dont-cache-query-string-requests">' . "\n";
echo 'Query Strings:' . "\n";
echo '</label>' . "\n";
echo '</th>' . "\n";
/**/
echo '</tr>' . "\n";
echo '<tr>' . "\n";
/**/
echo '<td>' . "\n";
echo '<select name="ws_plugin__qcache_dont_cache_query_string_requests" id="ws-plugin--qcache-dont-cache-query-string-requests">' . "\n";
echo '<option value="1"' . (($GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["dont_cache_query_string_requests"]) ? ' selected="selected"' : '') . '>True ( Don\'t Cache )</option>' . "\n";
echo '<option value="0"' . ((!$GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["dont_cache_query_string_requests"]) ? ' selected="selected"' : '') . '>False ( Always Cache )</option>' . "\n";
echo '</select><br />' . "\n";
echo 'Recommended setting ( true ).' . "\n";
echo '</td>' . "\n";
/**/
echo '</tr>' . "\n";
echo '</tbody>' . "\n";
echo '</table>' . "\n";
echo '</div>' . "\n";
/**/
echo '<div class="ws-menu-page-hr ws-plugin--qcache-double-cache-section-hr"></div>' . "\n";
/**/
echo '<div class="ws-menu-page-section ws-plugin--qcache-double-cache-section">' . "\n";
echo '<h3>Allow Double-Caching In The Client Side Browser?</h3>' . "\n";
echo '<p>It is best to leave this set to false, particularly if you have users logging in and out a lot. Quick Cache optimizes everything through its ability to communicate with a browser using PHP. If you allow the browser to (cache) the caching system itself, then you are momentarily losing control over whether a cache file will be served or not. We say momentary because the cache eventually will expire on its own anyway. This is one major difference between Quick Cache and the original Super Cache plugin. Super Cache allows sort of a double-cache, which really is not very practical and becomes quite confusing to site owners that spend hours testing &amp; tweaking. All that being said, if all you care about is blazing fast speed and you don\'t update your site that often, then you can safely set this to true and see how you like it.</p>' . "\n";
echo '<p><em>* <b>Advanced Tip:</b> If you have Double-Caching turned OFF ( recommended ), but you do want to allow some special URLs to be cached by the browser; you can add this special parameter to your URL <code>&amp;qcABC=1</code>. That tells Quick Cache that it\'s OK for the browser to cache that particular URL, even though you have it disabled for all others. In other words, the <code>qcABC=1</code> parameter will prevent Quick Cache from sending no-cache headers to the browser.</em></p>' . "\n";
/**/
echo '<table class="form-table">' . "\n";
echo '<tbody>' . "\n";
echo '<tr>' . "\n";
/**/
echo '<th>' . "\n";
echo '<label for="ws-plugin--qcache-allow-browser-cache">' . "\n";
echo 'Browser Cache:' . "\n";
echo '</label>' . "\n";
echo '</th>' . "\n";
/**/
echo '</tr>' . "\n";
echo '<tr>' . "\n";
/**/
echo '<td>' . "\n";
echo '<select name="ws_plugin__qcache_allow_browser_cache" id="ws-plugin--qcache-allow-browser-cache">' . "\n";
echo '<option value="0"' . ((!$GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["allow_browser_cache"]) ? ' selected="selected"' : '') . '>False ( Disallow )</option>' . "\n";
echo '<option value="1"' . (($GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["allow_browser_cache"]) ? ' selected="selected"' : '') . '>True ( Allow )</option>' . "\n";
echo '</select><br />' . "\n";
echo 'Recommended setting ( false ).' . "\n";
echo '</td>' . "\n";
/**/
echo '</tr>' . "\n";
echo '</tbody>' . "\n";
echo '</table>' . "\n";
echo '</div>' . "\n";
/**/
echo '<div class="ws-menu-page-hr ws-plugin--qcache-expiration-section-hr"></div>' . "\n";
/**/
echo '<div class="ws-menu-page-section ws-plugin--qcache-expiration-section">' . "\n";
echo '<h3>Set The Expiration Time On Quick Cache Files?</h3>' . "\n";
echo '<p>If you don\'t update your site much, you could set this to 1 week ( 604800 seconds ) and optimize everything even further. The longer the cache expiration time is, the greater your performance gain. Alternatively, the shorter the expiration time, the fresher everything will remain on your site. 3600 ( which is 1 hour ) is the recommended expiration time, it\'s a good middle ground. That being said, you could set this to just 60 seconds and you would still see huge differences in speed and performance.</p>' . "\n";
/**/
echo '<table class="form-table">' . "\n";
echo '<tbody>' . "\n";
echo '<tr>' . "\n";
/**/
echo '<th>' . "\n";
echo '<label for="ws-plugin--qcache-expiration">' . "\n";
echo 'Cache Expiration:' . "\n";
echo '</label>' . "\n";
echo '</th>' . "\n";
/**/
echo '</tr>' . "\n";
echo '<tr>' . "\n";
/**/
echo '<td>' . "\n";
echo '<input type="text" name="ws_plugin__qcache_expiration" id="ws-plugin--qcache-expiration" value="' . format_to_edit ($GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["expiration"]) . '" /><br />' . "\n";
echo 'Recommended setting ( 3600 ).' . "\n";
echo '</td>' . "\n";
/**/
echo '</tr>' . "\n";
echo '</tbody>' . "\n";
echo '</table>' . "\n";
echo '</div>' . "\n";
/**/
echo '<div class="ws-menu-page-hr ws-plugin--qcache-pruning-section-hr"></div>' . "\n";
/**/
echo '<div class="ws-menu-page-section ws-plugin--qcache-pruning-section">' . "\n";
echo '<h3>Enable Dynamic Cache Pruning Routines?</h3>' . "\n";
echo '<p>So let\'s summarize things here and review your configuration thus far. There is an automatic expiration system ( the garbage collector ), which runs through WordPress® behind-the-scenes according to your Expiration setting. Then there is also a built-in expiration time on existing files that is checked before any cache file is served up, which also uses your Expiration setting. So... what happens if you are working on your site and you update a Post or a Page? Do visitors have to wait an hour before they see these changes, or should they see changes like this automatically? That is where this configuration option comes in. Whenever you update a Post or a Page, Quick Cache can automatically prune that particular file from the cache so it instantly becomes fresh again. Otherwise your visitors would need to wait for the previous cached version to expire. If you\'d like Quick Cache to handle this for you, set this option to <em>Single</em>. If you want Quick Cache to completely reset ( purge all cache files ) when this happens; and be triggered on other actions too — like if you rename a category or add links, set this to <em>All</em>. If you don\'t want any of this and you just want blazing fast speed at all times, set this to <em>None</em>.</p>' . "\n";
/**/
echo '<table class="form-table">' . "\n";
echo '<tbody>' . "\n";
echo '<tr>' . "\n";
/**/
echo '<th>' . "\n";
echo '<label for="ws-plugin--qcache-clear-on-update">' . "\n";
echo 'Cache Dynamics:' . "\n";
echo '</label>' . "\n";
echo '</th>' . "\n";
/**/
echo '</tr>' . "\n";
echo '<tr>' . "\n";
/**/
echo '<td>' . "\n";
echo '<select name="ws_plugin__qcache_clear_on_update" id="ws-plugin--qcache-clear-on-update">' . "\n";
echo '<option value="single"' . (($GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["clear_on_update"] === "single") ? ' selected="selected"' : '') . '>Single ( Purge Only The Specific Page/Post )</option>' . "\n";
echo '<option value="all"' . (($GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["clear_on_update"] === "all") ? ' selected="selected"' : '') . '>All ( Purge All Cached Files In The System  * slower )</option>' . "\n";
echo '<option value="no"' . (($GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["clear_on_update"] === "none") ? ' selected="selected"' : '') . '>None ( Wait For Garbage Collector To Handle It )</option>' . "\n";
echo '</select><br />' . "\n";
echo 'Recommended setting ( Single is a good middle ground ).' . "\n";
echo '</td>' . "\n";
/**/
echo '</tr>' . "\n";
echo '</tbody>' . "\n";
echo '</table>' . "\n";
echo '</div>' . "\n";
/**/
echo '<div class="ws-menu-page-hr ws-plugin--qcache-nocache-uris-section-hr"></div>' . "\n";
/**/
echo '<div class="ws-menu-page-section ws-plugin--qcache-nocache-uris-section">' . "\n";
echo '<h3>Don\'t Cache These Special Patterns?</h3>' . "\n";
echo '<p>Sometimes there are special cases where a particular file, or a particular group of files should never be cached. This is where you will enter those if you need to. Searches are performed against the REQUEST_URI ( case sensitive ). So don\'t put in full URLs here, just word fragments found in the file path is all you need, excluding the http:// and the domain name. Wildcards and other regex patterns are not supported here and therefore you don\'t need to escape special characters or anything. Please see the examples below for more information.</p>' . "\n";
/**/
echo '<table class="form-table">' . "\n";
echo '<tbody>' . "\n";
echo '<tr>' . "\n";
/**/
echo '<th>' . "\n";
echo '<label for="ws-plugin--qcache-dont-cache-these-uris">' . "\n";
echo 'Don\'t Cache These URI Patterns:' . "\n";
echo '</label>' . "\n";
echo '</th>' . "\n";
/**/
echo '</tr>' . "\n";
echo '<tr>' . "\n";
/**/
echo '<td>' . "\n";
echo 'One per line please... ( these ARE case sensitive )<br />' . "\n";
echo '<textarea name="ws_plugin__qcache_dont_cache_these_uris" id="ws-plugin--qcache-dont-cache-these-uris" rows="3" wrap="off">' . format_to_edit ($GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["dont_cache_these_uris"]) . '</textarea><br />' . "\n";
echo 'Do NOT include a leading http:// or your domain name. Let\'s use this example URL: <code>http://www.example.com/post/example-post</code>. To exclude this URL, you would put this line into the field above: <code>/post/example-post</code>. Or you could also just put in a small fragment, like: <code>example-</code> and that would exclude any URI that has that word fragment in it.' . "\n";
echo '</td>' . "\n";
/**/
echo '</tr>' . "\n";
echo '</tbody>' . "\n";
echo '</table>' . "\n";
echo '</div>' . "\n";
/**/
echo '<div class="ws-menu-page-hr ws-plugin--qcache-nocache-uagents-section-hr"></div>' . "\n";
/**/
echo '<div class="ws-menu-page-section ws-plugin--qcache-nocache-uagents-section">' . "\n";
echo '<h3>Don\'t Cache These User-Agents?</h3>' . "\n";
echo '<p>If your site has been designed to support mobile devices through special detection scripting, you might want to disable caching for those devices here. Searches are performed against the HTTP_USER_AGENT string ( case insensitive ). Just put in word fragments that you want to look for in the User-Agent string. If a word fragment is found in the User-Agent string, no caching will occur, and only database-driven content will be served up. Wildcards and other regex patterns are not supported in this field and therefore you don\'t need to escape special characters or anything.</p>' . "\n";
echo '<p>Another way to deal with this problem, is to use a custom Salt ( that option is down below ). You could use a custom Salt that includes $_SERVER["HTTP_USER_AGENT"]. This would create different cached versions for every different browser, thereby eliminating the need for this option all together. If your site is really large, you might want to think this through. Having a different set of cache files for every different browser could take up lots of disk space, and there are lots of different browsers out there.</p>' . "\n";
/**/
echo '<table class="form-table">' . "\n";
echo '<tbody>' . "\n";
echo '<tr>' . "\n";
/**/
echo '<th>' . "\n";
echo '<label for="ws-plugin--qcache-dont-cache-these-agents">' . "\n";
echo 'Don\'t Cache These User-Agent Patterns:' . "\n";
echo '</label>' . "\n";
echo '</th>' . "\n";
/**/
echo '</tr>' . "\n";
echo '<tr>' . "\n";
/**/
echo '<td>' . "\n";
echo 'One per line please... ( these are NOT case sensitive )<br />' . "\n";
echo '<textarea name="ws_plugin__qcache_dont_cache_these_agents" id="ws-plugin--qcache-dont-cache-these-agents" rows="3" wrap="off">' . format_to_edit ($GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["dont_cache_these_agents"]) . '</textarea><br />' . "\n";
echo 'If you wanted to prevent caching on a BlackBerry, iPhones, and Playstation systems:<br />' . "\n";
echo '<code>BlackBerry</code><br /><code>Playstation</code><br /><code>iPhone</code>' . "\n";
echo '</td>' . "\n";
/**/
echo '</tr>' . "\n";
echo '</tbody>' . "\n";
echo '</table>' . "\n";
echo '</div>' . "\n";
/**/
echo '<div class="ws-menu-page-hr ws-plugin--qcache-mutex-section-hr"></div>' . "\n";
/**/
echo '<div class="ws-menu-page-section ws-plugin--qcache-mutex-section">' . "\n";
echo '<h3>Maintain Mutex Using Flock() Or A Semaphore?</h3>' . "\n";
echo '<p>On high traffic sites with dedicated servers, a Semaphore (<em>sem_get</em>) offers better performance. Unless your hosting provider has suggested otherwise, it is best to leave this set to the more reliable <em>sem_get</em> method. If your system does not support <em>sem_get</em>, Quick Cache will detect that automatically &amp; fall back on the <em>flock</em> method for you. The <em>flock</em> method can be used on any system, so if you have any trouble using Quick Cache, set this to <em>flock</em> for maximum compatibility.</p>' . "\n";
/**/
echo '<p><strong>Cloud Computing?</strong> If your site is hosted on a Cloud Computing model, such as the Rackspace® Cloud, or (mt) Media Temple; you should set this to <em>flock</em> unless they tell you otherwise.</p>' . "\n";
/**/
echo '<table class="form-table">' . "\n";
echo '<tbody>' . "\n";
echo '<tr>' . "\n";
/**/
echo '<th>' . "\n";
echo '<label for="ws-plugin--qcache-use-flock-or-sem">' . "\n";
echo 'Mutex Method:' . "\n";
echo '</label>' . "\n";
echo '</th>' . "\n";
/**/
echo '</tr>' . "\n";
echo '<tr>' . "\n";
/**/
echo '<td>' . "\n";
echo '<select name="ws_plugin__qcache_use_flock_or_sem" id="ws-plugin--qcache-use-flock-or-sem">' . "\n";
echo '<option value="sem"' . (($GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["use_flock_or_sem"] === "sem") ? ' selected="selected"' : '') . '>Mutex ( Semaphore )</option>' . "\n";
echo '<option value="flock"' . (($GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["use_flock_or_sem"] === "flock") ? ' selected="selected"' : '') . '>Mutex ( Flock )</option>' . "\n";
echo '</select><br />' . "\n";
echo 'Recommended setting ( Semaphore ).' . "\n";
echo '</td>' . "\n";
/**/
echo '</tr>' . "\n";
echo '</tbody>' . "\n";
echo '</table>' . "\n";
echo '</div>' . "\n";
/**/
echo '<div class="ws-menu-page-hr ws-plugin--qcache-md5-salt-section-hr"></div>' . "\n";
/**/
echo '<div class="ws-menu-page-section ws-plugin--qcache-md5-salt-section">' . "\n";
echo '<h3>Create An MD5 Version Salt For Quick Cache?</h3>' . "\n";
echo '<p>This is for advanced users only. Alright, here goes... Quick Cache stores its cache files using an <code>md5()</code> hash of the HOST/URI that it\'s caching. If you want to build these hash strings out of something other than just the HOST/URI, you can add a Salt to the mix. So instead of just <code>md5($_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"])</code>, you might have <code>md5($_COOKIE["myCookie"].$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"])</code>. This would create multiple versions of each page depending on the value of <code>$_COOKIE["myCookie"]</code>. If <code>$_COOKIE["myCookie"]</code> is empty, then just <code>$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]</code> are used. So you see, this gives you the ability to dynamically create multiple variations of the cache, and those dynamic variations will be served on subsequent visits.</p>' . "\n";
echo '<p>A Salt can be a single variable like <code>$_COOKIE["myCookie"]</code>, or it can be a combination of multiple variables, like <code>$_COOKIE["myCookie"].$_COOKIE["myOtherCookie"]</code>. When using multiple variables, please separate them with a dot, as shown in the example. Experts can use PHP ternary expressions that evaluate into something. For example: <code>((preg_match("/IPHONE/i", $_SERVER["HTTP_USER_AGENT"])) ? "IPHONES" : "")</code>. This would force a separate version of the cache to be created for iPhone browsers. With this method your possibilities are limitless.</p>' . "\n";
echo '<p>Quick Cache can also be disabled temporarily. If you\'re a plugin developer, you can define a special constant within your plugin to disable the cache engine at runtime, on a specific page, or in a specific scenario. In your PHP script, do this: <code>define("QUICK_CACHE_ALLOWED", false)</code>. Quick Cache is also compatible with: <code>$_SERVER["QUICK_CACHE_ALLOWED"] = false</code>, as well as <code>define("DONOTCACHEPAGE", true)</code>, which is backward compatible with the WP Super Cache plugin.</p>' . "\n";
/**/
echo '<table class="form-table">' . "\n";
echo '<tbody>' . "\n";
echo '<tr>' . "\n";
/**/
echo '<th>' . "\n";
echo '<label for="ws-plugin--qcache-version-salt">' . "\n";
echo 'MD5 Version Salt:' . "\n";
echo '</label>' . "\n";
echo '</th>' . "\n";
/**/
echo '</tr>' . "\n";
echo '<tr>' . "\n";
/**/
echo '<td>' . "\n";
echo 'md5(<input type="text" name="ws_plugin__qcache_version_salt" id="ws-plugin--qcache-version-salt" value="' . format_to_edit ($GLOBALS["WS_PLUGIN__"]["qcache"]["o"]["version_salt"]) . '" style="width:300px;" />.$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"])<br />' . "\n";
echo 'You can use Super Globals: $_SERVER, $_GET, $_REQUEST, $_COOKIE, etc. Or Constants defined in wp-config.php. Example: <code>DB_NAME.DB_HOST.$_SERVER["REMOTE_PORT"]</code> ( separate multiple variables with a dot ). Your Salt will be checked for PHP syntax errors. If syntax errors are found, you\'ll receive a JavaScript alert, after you click Save.<br />' . "\n";
echo '</td>' . "\n";
/**/
echo '</tr>' . "\n";
echo '</tbody>' . "\n";
echo '</table>' . "\n";
echo '</div>' . "\n";
/**/
echo '<div class="ws-menu-page-hr"></div>' . "\n";
/**/
echo '<p class="submit"><input type="submit" class="button-primary" value="Save Changes" /></p>' . "\n";
/**/
echo '</form>' . "\n";
/**/
echo '</td>' . "\n";
/**/
echo '<td class="ws-menu-page-table-r">' . "\n";
/**/
echo ($GLOBALS["WS_PLUGIN__"]["qcache"]["c"]["menu-r"]["tools"]) ? '<div class="ws-menu-page-tools"><img src="' . $GLOBALS["WS_PLUGIN__"]["qcache"]["c"]["dir_url"] . '/images/brand-tools.png" alt="." /></div>' . "\n" : '';
/**/
echo ($GLOBALS["WS_PLUGIN__"]["qcache"]["c"]["menu-r"]["tips"]) ? '<div class="ws-menu-page-tips"><a href="' . ws_plugin__qcache_parse_readme_value ("Customization URI") . '"><img src="' . $GLOBALS["WS_PLUGIN__"]["qcache"]["c"]["dir_url"] . '/images/brand-tips.png" alt="." /></a></div>' . "\n" : '';
/**/
echo ($GLOBALS["WS_PLUGIN__"]["qcache"]["c"]["menu-r"]["donations"]) ? '<div class="ws-menu-page-donations"><a href="' . ws_plugin__qcache_parse_readme_value ("Donate link") . '"><img src="' . $GLOBALS["WS_PLUGIN__"]["qcache"]["c"]["dir_url"] . '/images/brand-donations.jpg" alt="." /></a></div>' . "\n" : '';
/**/
echo '</td>' . "\n";
/**/
echo '</tr>' . "\n";
echo '</tbody>' . "\n";
echo '</table>' . "\n";
/**/
echo '</div>' . "\n";
?>