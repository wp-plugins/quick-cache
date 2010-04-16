=== Quick Cache ( A WP Super Cache Alternative ) ===

Version: 2.1.3
Framework: P-2.1
Stable tag: trunk

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
Tags: cache, quick cache, quick-cache, quickcache, speed, performance, loading, generation, execution, benchmark, benchmarking, debug, debugging, caching, cash, caching, cacheing, super cache, advanced cache, advanced-cache, wp-cache, wp cache, wpmu, options panel included, websharks framework, w3c validated code, includes extensive documentation, highly extensible

Speed up your site ~ BIG Time! - If you care about the speed of your site, Quick Cache is a plugin that you absolutely MUST have installed.

== Installation ==

**Quick Tip:** WordPress® can only deal with one cache plugin being activated at a time. So, you'll need to un-install any existing cache plugins that you've tried in the past. In other words, if you've installed WP-Super-Cache, DB-Cache-Reloaded, or any other caching plugin, un-install them all before installing Quick Cache. One way to check, is to make sure this file: `/wp-content/advanced-cache.php` is NOT present; and if it is present, delete it before installing Quick Cache. That file will ONLY be present if you have a cache plugin already installed. If you don't see it, you're good.

**Quick Cache is very easy to install ( follow these instructions ):**

1. Upload the `/quick-cache` folder to your `/wp-content/plugins/` directory.
2. Activate the plugin through the `Plugins` menu in WordPress®.
3. Navigate to the `Quick Cache` panel & enable it.

**How do I know that Quick Cache is working?**

First of all, make sure that you've enabled Quick Cache. After you activate the plugin, go to the Quick Cache Options panel and enable it, then scroll to the bottom and click Save. All of the other options on that page are already pre-configured for typical usage. Skip them all for now. You can go back through all of them later and fine-tune things the way you like them. Once Quick Cache has been enabled, **you'll need to log out**. Cache files are NOT served to visitors who are logged in, and that includes YOU! In order to verify that Quick Cache is working, navigate your site like a normal visitor would. Right-click on any page ( choose View Source ), then scroll to the very bottom of the document. At the bottom, you'll find comments that show Quick Cache stats and information. You should also notice that page-to-page navigation is lightning fast compared to what you experienced prior to installing Quick Cache.

**Running Quick Cache On A WordPress® MU Installation**

WordPress® MU is a special ( multi-user ) version of WordPress®. If Quick Cache is installed under WordPress® MU, it will be enabled for ALL blogs the same way. The centralized config options for Quick Cache, can only be modified by the MU Site Administrator operating on the main site with Blog ID# 1. Under WordPress® MU, a special file: `quick-cache-mu.php` will be added to your `mu-plugins` directory automatically. That special file prevents the Quick Cache plugin from being visible to other blog owners, even when the Plugins Menu is turned on in your MU options panel. This is recommeded for security purposes, because Quick Cache is a unique plugin, that should only be modified, updated, or de-activated by the Site Administrator, not by individual blog owners.

Even without the `quick-cache-mu.php` file, Quick Cache still has internal processing routines that prevent configuration changes by anyone other than the Site Administrator. The `quick-cache-mu.php` file just removes any confusion that may occur as a result of the plugin being listed as a possible option to other blog owners; which only occurs when you have the Plugins Menu enabled in your MU options. If you're running the standard version of WordPress®, you can safely ignore this notation, because you won't even have an `mu-plugins` directory. WordPress® MU is a special ( multi-user ) version of WordPress® that is normally installed by web developers.

== Description ==

If you care about the speed of your site, Quick Cache is one of those plugins that you absolutely MUST have installed. Quick Cache takes a real-time snapshot ( building a cache ) of every Page, Post, Category, Link, etc. These snapshots are then stored ( cached ) intuitively, so they can be referenced later, in order to save all of that processing time that has been dragging your site down and costing you money.

The Quick Cache plugin uses configuration options, that you select from the options panel. See: `Config Options` under `Quick Cache`. Once a file has been cached, Quick Cache uses advanced techniques that allow it to recognize when it should and should not serve a cached version of the file. The decision engine that drives these techniques is under your complete control through options on the back-end. By default, Quick Cache does not serve cached pages to users who are logged in, or to users who have left comments recently. Quick Cache also excludes administrational pages, login pages, POST/PUT/GET requests, CLI processes, and any additional User-Agents or special pattern matches that you want to add.

== Screenshots ==

1. Options Panel screenshot.
2. MD5 Version Salt configuration.
3. Configuring GZIP compression.
4. Speeding thing up.
5. Quick Cache vs. WP Super Cache.

== So Why Does WordPress® Need To Be Cached? ==

To understand how Quick Cache works, first you have to understand what a cached file is, and why it is absolutely necessary for your site and every visitor that comes to it. WordPress® ( by its very definition ) is a database-driven publishing platform. That means you have all these great tools on the back-end of your site to work with, but it also means that every time a Page/Post/Category is accessed on your site, dozens of connections to the database have to be made, and literally thousands of PHP routines run in harmony behind-the-scenes to make everything jive. The problem is, for every request that a browser sends to your site, all of these routines and connections have to be made ( every: yes, every single time ). Geesh, what a waste of processing power, memory, and other system resources. After all, most of the content on your site remains the same for at least a few minutes at a time. If you've been using WordPress® for very long, you've probably noticed that ( on average ) your site does not load up as fast as other sites on the web. Now you know why!

== The Definition Of A Cached File ( From The Wikipedia ) ==

In computer science, a cache (pronounced /kash/) is a collection of data duplicating original values stored elsewhere or computed earlier, where the original data is expensive to fetch (owing to longer access time) or to compute, compared to the cost of reading the cache. In other words, a cache is a temporary storage area where frequently accessed data can be stored for rapid access. Once the data is stored in the cache, it can be used in the future by accessing the cached copy rather than re-fetching or recomputing the original data.

== Prepare To Be Amazed / It's Time To Speed Things Up ==

Quick Cache is extremely reliable, because it runs completely in PHP code, and does not hand important decisions off to the `mod_rewrite` engine or browser cache. Quick Cache actually sends a no-cache header ( yes, a no-cache header ) that allows it to remain in control at all times. It may seem weird that a caching plugin would send a no-cache header :-). Please understand that the no-cache headers are the key to the whole concept behind this plugin, and they will NOT affect performance negatively. On the contrary, this is how the system can accurately serve cache files for public users vs. users who are logged in, commenters, etc. That is why this plugin works so reliably.

If you care about the speed of your site, Quick Cache is one of those plugins that you absolutely MUST have installed. Quick Cache takes a real-time snapshot ( building a cache ) of every Page, Post, Category, Link, etc. These snapshots are then stored ( cached ) intuitively, so they can be referenced later, in order to save all of that processing time that has been dragging your site down and costing you money. The Quick Cache plugin uses configuration options, that you select from the options panel. See: `Config Options` under `Quick Cache`. Once a file has been cached, Quick Cache uses advanced techniques that allow it to recognize when it should and should not serve a cached version of the file. The decision engine that drives these techniques is under your complete control through options on the back-end. By default, Quick Cache does not serve cached pages to users who are logged in, or to users who have left comments recently. Quick Cache also excludes administrational pages, login pages, POST/PUT/GET requests, CLI processes, and any additional User-Agents or special pattern matches that you want to add.

== Running Quick Cache On A WordPress® MU Installation ==

WordPress® MU is a special ( multi-user ) version of WordPress®. If Quick Cache is installed under WordPress® MU, it will be enabled for ALL blogs the same way. The centralized config options for Quick Cache, can only be modified by the MU Site Administrator operating on the main site with Blog ID# 1. Under WordPress® MU, a special file: `quick-cache-mu.php` will be added to your `mu-plugins` directory automatically. That special file prevents the Quick Cache plugin from being visible to other blog owners, even when the Plugins Menu is turned on in your MU options panel. This is recommeded for security purposes, because Quick Cache is a unique plugin, that should only be modified, updated, or de-activated by the Site Administrator, not by individual blog owners.

Even without the `quick-cache-mu.php` file, Quick Cache still has internal processing routines that prevent configuration changes by anyone other than the Site Administrator. The `quick-cache-mu.php` file just removes any confusion that may occur as a result of the plugin being listed as a possible option to other blog owners; which only occurs when you have the Plugins Menu enabled in your MU options. If you're running the standard version of WordPress®, you can safely ignore this notation, because you won't even have an `mu-plugins` directory. WordPress® MU is a special ( multi-user ) version of WordPress® that is normally installed by web developers.

== How To Enable GZIP Compression For Even Greater Speeds ==

You don't have to use an .htaccess file to enjoy the performance enhancements provided by this plugin; caching is handled by WordPress®/PHP alone. That being said, if you want to take advantage of GZIP compression ( and we do recommend this ), then you WILL need an .htaccess file to accomplish that part. This plugin fully supports GZIP compression on its output. However, it does not handle GZIP compression directly. We purposely left GZIP compression out of this plugin, because GZIP compression is something that should really be enabled at the Apache level or inside your php.ini file. GZIP compression can be used for things like JavaScript and CSS files as well, so why bother turning it on for only WordPress® generated pages when you can enable GZIP at the server level and cover all the bases.

If you want to enable GZIP, create an .htaccess file in your WordPress® installation directory and put the following few lines in it. Alternatively, if you already have an .htaccess file, just add these lines to it, and that is all there is to it. GZIP is now enabled!

	<IfModule mod_deflate.c>
	 AddOutputFilterByType DEFLATE text/html text/xml text/css text/plain
	 AddOutputFilterByType DEFLATE image/svg+xml application/xhtml+xml application/xml
	 AddOutputFilterByType DEFLATE application/rdf+xml application/rss+xml application/atom+xml
	 AddOutputFilterByType DEFLATE text/javascript application/javascript application/x-javascript
	 AddOutputFilterByType DEFLATE application/x-font-ttf application/x-font-otf
	 AddOutputFilterByType DEFLATE font/truetype font/opentype
	</IfModule>

If your installation of Apache does not have `mod_deflate` installed. You can also enable GZIP compression using PHP configuration alone. In your php.ini file, you can simply add the following line anywhere: `zlib.output_compression = on`

== 'Quick Cache' vs WP Super Cache ( Main Differences ) ==

* Quick Cache uses a combination phase ( with less code ) and NO `mod_rewrite` rules ( no .htaccess file is required ). Super Cache requires an .htaccess file with `mod_rewrite` rules that serve GZ files. Quick Cache works right out of the box, so it is MUCH easier to install. All you have to do is activate the plugin and enable caching. All of its other options are already pre-configured for typical usage. Using an .htaccess file is 100% optional, and it's for GZIP compression only.

* Quick Cache provides a complete set of decision engine options for its entire methodology. Super Cache offers `On`, `Half On`, and `Off`. It has fewer options on the back-end panel. Even though Quick Cache is pre-configured for typical usage, it is important for a site owner to have full control at all times. Even those advanced settings that tend to scare novice users away; those have all been included with Quick Cache. Quick Cache teaches you advanced techniques with its examples and built-in documentation for each option.

* Quick Cache maintains absolute control over who sees cached pages. Super Cache allows browsers to `cache - the - cache`. In other words, some control is lost, and people who ARE logged in may see (not-logged-in) versions of pages. This technique gives Super Cache its name though. It makes things Super Fast. However, that may not be practical for some database-driven sites that are updated all the time and have lots of different plugins installed. If you offer membership or provide special content for members, you may want to try Quick Cache.

* Quick Cache provides you with the ability to customize the Salt used in cache storage. Super Cache does not provide this capability. It could be done with instruction, but you would need to dig into the code for that. The ability to easily customize the Salt used in cache storage is very important. Many sites offer unique services and serve special versions of certain files. The ability to control how different versions of pages are cached, is critical to advanced webmasters that need to tweak everything and customize the caching engine to their specific needs.

== Frequently Asked Questions ==

= How do I know that Quick Cache is working the way it should be? =
First of all, make sure that you've enabled Quick Cache. After you activate the plugin, go to the Quick Cache Options panel and enable it, then scroll to the bottom and click Save. All of the other options on that page are already pre-configured for typical usage. Skip them all for now. You can go back through all of them later and fine-tune things the way you like them. Once Quick Cache has been enabled, **you'll need to log out**. Cache files are NOT served to visitors who are logged in, and that includes YOU! In order to verify that Quick Cache is working, navigate your site like a normal visitor would. Right-click on any page ( choose View Source ), then scroll to the very bottom of the document. At the bottom, you'll find comments that show Quick Cache stats and information. You should also notice that page-to-page navigation is lightning fast compared to what you experienced prior to installing Quick Cache.

= What is the down side to running Quick Cache? =
Ummm, how can we say this... There is NOT one! Quick Cache is a MUST HAVE for every WordPress® powered site. In fact, we really can't think of any site running WordPress® that would want to be without it. To put it another way, the WordPress® software itself comes with a built in ( hard-coded ) action reference for an `advanced-cache.php` file, because WordPress® developers realize the importance of such as plugin. The `/wp-content/advanced-cache.php` file is named as such, because the WordPress® developers expect it to be there when caching is enabled by a plugin. If you don't have the `/wp-content/advanced-cache.php` file yet, it is because you have not enabled Quick Cache from the options panel yet.

= So why does WordPress® need to be cached? =
To understand how Quick Cache works, first you have to understand what a cached file is, and why it is absolutely necessary for your site and every visitor that comes to it. WordPress® ( by its very definition ) is a database-driven publishing platform. That means you have all these great tools on the back-end of your site to work with, but it also means that every time a Page/Post/Category is accessed on your site, dozens of connections to the database have to be made, and literally thousands of PHP routines run in harmony behind-the-scenes to make everything jive. The problem is, for every request that a browser sends to your site, all of these routines and connections have to be made ( every: yes, every single time ). Geesh, what a waste of processing power, memory, and other system resources. After all, most of the content on your site remains the same for at least a few minutes at a time. If you've been using WordPress® for very long, you've probably noticed that ( on average ) your site does not load up as fast as other sites on the web. Now you know why!

In computer science, a cache (pronounced /kash/) is a collection of data duplicating original values stored elsewhere or computed earlier, where the original data is expensive to fetch (owing to longer access time) or to compute, compared to the cost of reading the cache. In other words, a cache is a temporary storage area where frequently accessed data can be stored for rapid access. Once the data is stored in the cache, it can be used in the future by accessing the cached copy rather than re-fetching or recomputing the original data.

= Where & why are the cache files stored on my server? =
The cache files are stored in a special directory: `/wp-content/cache/`. This directory needs to remain writable, just like the `/wp-content/uploads` directory on many WordPress® installations. The `/cache` directory is where MD5 hash files reside. These files are named ( with an MD5 hash ) according to your `MD5 Version Salt` and the `HTTP_HOST/REQUEST_URI`. ( See: `Quick Cache -> Config Options -> MD5 Version Salt` ).

Whenever a request comes in from someone on the web, Quick Cache checks to see if it can serve a cached file, it looks at your `Salt`, it looks at the `HTTP_HOST/REQUEST_URI`, then it checks the `/cache` directory. If a cache file has been built already, and it matches your `Salt.HTTP_HOST.REQUEST_URI` combination, and it is not too old ( See: `Quick Cache -> Config Options -> Expiration` ), then it will serve that file instead of asking WordPress® to re-generate it. This adds tremendous speed to your site and reduces server load.

If you have GZIP compression enabled, then the cache file is also sent to the browser with compression ( recommended ). Modern web browsers that support this technique will definitely take advantage of it. After all, if it is easier to email a zip file, it's also easier to download a web page that way. That is why on-the-fly GZIP compression for web pages is recommended. This is supported by all modern browsers.

If you want to enable GZIP, create an .htaccess file in your WordPress® installation directory and put the following few lines in it. Alternatively, if you already have an .htaccess file, just add these lines to it, and that is all there is to it. GZIP is now enabled!

	<IfModule mod_deflate.c>
	 AddOutputFilterByType DEFLATE text/html text/xml text/css text/plain
	 AddOutputFilterByType DEFLATE image/svg+xml application/xhtml+xml application/xml
	 AddOutputFilterByType DEFLATE application/rdf+xml application/rss+xml application/atom+xml
	 AddOutputFilterByType DEFLATE text/javascript application/javascript application/x-javascript
	 AddOutputFilterByType DEFLATE application/x-font-ttf application/x-font-otf
	 AddOutputFilterByType DEFLATE font/truetype font/opentype
	</IfModule>

If your installation of Apache does not have `mod_deflate` installed. You can also enable GZIP compression using PHP configuration alone. In your php.ini file, you can simply add the following line anywhere: `zlib.output_compression = on`

= What happens if a user logs in? Are cache files used then? =
The decision engine that drives these techniques is under your complete control through options on the back-end. By default, Quick Cache does not serve cached pages to users who are logged in, or users who have left comments recently. Quick Cache also excludes administrational pages, login pages, POST/PUT/GET requests, CLI processes, and any additional User-Agents or special pattern matches that you want to add. POST requests should never be cached. A CLI request is one that comes from the command line; commonly used by cron jobs and other automated routines.

= Will comments and other dynamic parts of my blog update immediately? =
There is an automatic expiration system ( the garbage collector ), which runs through WordPress® behind-the-scenes, according to your Expiration setting ( See: `Quick Cache -> Config Options -> Expiration` ). Then there is also a built-in expiration time on existing files that is checked before any cache file is served up, which also uses your Expiration setting. In addition; whenever you update a Post or a Page, Quick Cache can automatically prune that particular file from the cache so it instantly becomes fresh again. Otherwise your visitors would need to wait for the previous cached version to expire. ( See: `Quick Cache -> Config Options -> Dynamic Cache Pruning` ).

By default, Quick Cache does not serve cached pages to users who are logged in, or users who have left comments recently. Quick Cache also excludes administrational pages, login pages, POST/PUT/GET requests, CLI processes, and any additional User-Agents or special pattern matches that you want to add. POST requests should never be cached. A CLI request is one that comes from the command line; commonly used by cron jobs and other automated routines.

= Can I customize the way cache files are stored & served up? =
Quick Cache provides you with the ability to customize the Salt used in MD5 hash generation for cache storage, and that directly affects the way they are served also. The ability to customize the Salt used in cache storage is important to advanced webmasters. Some sites offer unique services and serve special versions of certain files across different devices. The ability to control how different versions of pages are cached, is critical to advanced webmasters that need to tweak everything and customize the caching engine to their specific needs. ( See: `Quick Cache -> Config Options -> MD5 Version Salt` ). If you don't understand what a Salt is, or what an MD5 hash is, that is 100% ok :-) If you don't understand what it is, you probably don't need it. That simple :-) Using a custom Salt is a very advanced technique and it is not required to benefit from speed enhancements provided by Quick Cache.

= How do I enable GZIP compression? Is GZIP supported? =
There is no need to use an .htaccess file with this plugin; caching is handled by WordPress®/PHP alone. That being said, if you also want to take advantage of GZIP compression ( and we do recommend this ), then you WILL need an .htaccess file to accomplish that part. This plugin fully supports GZIP compression on its output. However, it does not handle GZIP compression directly. We purposely left GZIP compression out of this plugin, because GZIP compression is something that should really be enabled at the Apache level or inside your php.ini file. GZIP compression can be used for things like JavaScript and CSS files as well, so why bother turning it on for only WordPress® generated pages when you can enable GZIP at the server level and cover all the bases.

If you want to enable GZIP, create an .htaccess file in your WordPress® installation directory and put the following few lines in it. Alternatively, if you already have an .htaccess file, just add these lines to it, and that is all there is to it. GZIP is now enabled!

	<IfModule mod_deflate.c>
	 AddOutputFilterByType DEFLATE text/html text/xml text/css text/plain
	 AddOutputFilterByType DEFLATE image/svg+xml application/xhtml+xml application/xml
	 AddOutputFilterByType DEFLATE application/rdf+xml application/rss+xml application/atom+xml
	 AddOutputFilterByType DEFLATE text/javascript application/javascript application/x-javascript
	 AddOutputFilterByType DEFLATE application/x-font-ttf application/x-font-otf
	 AddOutputFilterByType DEFLATE font/truetype font/opentype
	</IfModule>

If your installation of Apache does not have `mod_deflate` installed. You can also enable gzip compression using PHP configuration alone. In your php.ini file, you can simply add the following line anywhere: `zlib.output_compression = on`

= How can I serve a different set of cache files to iPhone users? =
Set your MD5 Version Salt to the following:

	((preg_match("/IPHONE/i", $_SERVER["HTTP_USER_AGENT"])) ? "IPHONES" : "")

This effectively creates two versions of the cache. When iPhone browsers are detected, Quick Cache will prepend `IPHONES` to the `HTTP_HOST.REQUEST_URI`, before it generates the MD5 hash for storage.

= How can I serve a different set of cache files based on a cookie? =
Set your MD5 Version Salt to the following:

	((preg_match("/BlueLizards/i", $_COOKIE["BlueLizardsCookie"])) ? "BlueLizards" : "")

This effectively creates two versions of the cache. When `BlueLizardsCookie` contains `BlueLizards`, Quick Cache will prepend `BlueLizards` to the `HTTP_HOST.REQUEST_URI`, before it generates the MD5 hash for storage. Another, even simpler way to handle this, would be to use the value of a specific cookie to generate multiple variations of the cache. So instead of the Ternary expression shown above, you would simply set your Version Salt to:

`$_COOKIE["someCookie"]`

The value of `$_COOKIE["someCookie"]` is what would be used as your Version Salt. It would even be OK if `$_COOKIE["someCookie"]` was equal to an empty string. In that case the default version of the cache would be used.

= I'm a plugin developer. How can I prevent certain files from being cached? =
	define("QUICK_CACHE_ALLOWED", false);
When your script finishes execution, Quick Cache will know that it should NOT cache that particular page. It does not matter where or when you define this Constant. Quick Cache is the last thing to run during execution. So as long as you define this Constant at some point in your routines, everything will be fine. Quick Cache also provides backward support for `define("DONOTCACHEPAGE", true)`, which is used by the WP Super Cache plugin as well. Another option is: `$_SERVER["QUICK_CACHE_ALLOWED"] = false`. The `$_SERVER` array method is useful if you need to disable caching at the Apache level using `mod_rewrite`. The `$_SERVER` array is filled with all Environment variables, so if you use `mod_rewrite` to set the `QUICK_CACHE_ALLOWED` Environment variable, that will end up in `$_SERVER["QUICK_CACHE_ALLOWED"]`. All of these methods have the same end result, so it's up to you which one you'd like to use.

= What should my expiration setting be? =
If you don't update your site much, you could set this to 1 week ( 604800 seconds ) and optimize everything even further. The longer the cache expiration time is, the greater your performance gain. Alternatively, the shorter the expiration time, the fresher everything will remain on your site. 3600 ( which is 1 hour ) is the recommended expiration time, it's a good middle ground. That being said, you could set this to just 60 seconds and you would still see huge differences in speed and performance.

== Changelog ==

= 2.1.3 =
* Added `De-Activation Safeguards` to the Quick Cache options panel.
* Updated the Quick Cache options panel. It's been given a make-over.

= 2.1.2 =
* WebSharks Framework for Plugins has been updated to P-2.1.
* Updated caching routines in support of hosting providers running with CGI/FastCGI. Quick Cache has been tested with VPS.net, HostGator, BlueHost, (mt) Media Temple (gs) and (dv), The Rackspace Cloud, and several dedicated servers ( including some Amazon EC2 instances ) running with Apache; including support for both `mod_php` and also `CGI/FastCGI` implementations. Quick Cache should work fine with any Apache/PHP combination. Please report all bugs to <primothemes@websharks-inc.com>.
* An issue was discovered with WordPress® MU `/files/` being accessed through `htaccess/mod_rewrite`. Quick Cache has been updated to exclude all `/files/` served under WordPress® MU, which is the way it should be. Requests that contain `/files/` are a reference to WordPress® Media, and there is no reason, to cache, or send no-cache headers, for Media. Quick Cache now ignores all references to `/files/` under WordPress® MU. This problem was not affecting all installations of WPMU, because there already are/were scans in place for Content-Type headers. However, under some CGI/FastCGI implementations, this was not getting picked on WMPU with `mod_rewrite` rules. This has been resolved in v2.1.2.

= 2.1.1 =
* A WPMU bug was corrected in Quick Cache v2.1.1. This bug was related to `HTTP_HOST` detection under WordPress® MU installations that were using sub-domains. Please thank `QuickSander` for reporting this important issue.

= 2.1 =
* Quick Cache has added further support for themes and plugins that dynamically set `Content-Type` headers through PHP routines. Quick Cache is now smart enough to automatically disable itself whenever a theme or plugin sends a `Content-Type` header that would be incompatible with Quick Cache. In other words, any `Content-Type` header that is not a variation of `HTML, XHTML or XML`.
* Quick Cache has also been upgraded to support the preservation of scripted headers sent by PHP routines. If a plugin or theme sends scripted headers ( using the `header()` function in PHP ), those headers will be preserved. They'll be stored along with the cache. This allows them to be sent back to the browser whenever a cached version is served on subsequent visits to the original file.
* Compatability checked against WordPress.org 2.9.1, 2.9.2 &amp; WordPress MU 2.9.1, 2.9.2. Everything looks good. No changes required.

= 2.0 =
* A few tweaks to the options panel.
* Documentation updated, several small improvements in error reporting.
* Additional error checking to support an even wider range of hosting providers.
* Added automation routines for safe re-activation after an upgrade is performed.

= 1.9 =
* Additional support added for WordPress® MU 2.8.6+.
* Security file `quick-cache-mu.php` added specifically for MU installations. WordPress® MU is a special ( multi-user ) version of WordPress®. If you're running WordPress® MU, check the [readme.txt] file for WordPress® MU notations.

= 1.8 =
* Re-organized core framework. Updated to: P-2.0.
* Updated to support WP 2.9+.

= 1.7 =
* Updated documentation. Added some additional code samples.
* Tested with WP 2.8.5. Everything ok.

= 1.6 =
* We've added the ability to enable Double-Caching ( client-side caching ). Full documentation is provided in the Quick Cache options panel. This feature is for those of you who just want blazing fast speed and are not concerned as much about reliability and control. We don't recommend turning this on unless you realize what you're doing.

= 1.5 =
* Support for Dynamic Cache Pruning has been improved. Full documentation is provided in the Quick Cache options panel.
* Additional feature-specific documentation has been added to assist novice webmasters during configuration.

= 1.4 =
* Garbage collection has been further optimized for speed and performance on extremely high traffic sites.
* PHP Ternary expressions are now supported in your Version Salt. This takes your Version Salt to a whole new level.
* Additional code samples have been provided for Version Salts; showing you how to deal with mobile devices and other tricky situations.

= 1.3 =
* We've implemented both Semaphore ( `sem_get` ) and `flock()` mutex. If you're on a Cloud Computing Model ( such as the Rackspace® Cloud ), then you'll want to go with flock() unless they tell you otherwise. In all other cases we recommend the use of Semaphores over Flock because it is generally more reliable. The folks over at Rackspace® have suggested the use of flock() because of the way their Cloud handles multi-threading. In either case, flock() will be fully functional in any hosting environment, so it makes a great fallback in case you experience any problems.

= 1.2 =
* We've implemented a way for plugin developers to disallow caching during certain routines or on specific pages. You can set the following PHP Constant at runtime to disable caching. `define("QUICK_CACHE_ALLOWED", false)`. We have also added backward compatability for WP Super Cache, so that `define("DONOTCACHEPAGE", true)` will also be supported by plugins that have previously been written for compatability with Super Cache. In other words, Quick Cache looks for either of these two Constants.

= 1.1 =
* Added the ability to create a Version Salt. This is a feature offered ONLY by Quick Cache. Full documentation is provided in the Quick Cache options panel. This can become very useful for sites that provide membership services or have lots and lots of plugins installed that makes their site incompatible with WP Super Cache. With Quick Cache, you'll now have more control over the entire caching process using a custom Version Salt tailored to your specific needs.

= 1.0 =
* Initial release.