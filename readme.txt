=== Team Rosters ===
Contributors: MarkODonnell
Donate link: http://shoalsummitsolutions.com
Tags: sports,games,roster,sports teams,team roster,sports roster,sports team roster  
Requires at least: 3.4.2
Tested up to: 4.2.2
Stable tag: 3.1.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html


Manages multiple sports team rosters. Displays tabular rosters, a single player bios, and player galleries.

== Description ==

VERSION 4.0 IS A MAJOR UPGRADE THAT CHANGES THE PLUGIN'S UNDERLYING DATA STRUCTURES. IF YOU ARE UPGRADING, YOU MUST MIGRATE YOUR EXISTING DATA VIA CSV FILES IF YOU WISH TO KEEP IT. READ THE INSTALLATION DOCUMENTATION AND UPGRADE NOTICE CAREFULLY BEFORE UPGRADING. 

The MSTW Team Rosters plugin manages rosters for multiple sports teams. It provides roster tables with built-in formats for high school, college, and professional teams as well as custom roster formats for baseball. Admins can repurpose data fields by re-labeling them, so rosters can be used for Office Directories, for example. See the [MSTW Plugin Development Site](http://dev.shoalsummitsolutions.com) for examples.

Players are assigned to team rosters using a Teams custom taxonomy. These taxonomies may now be linked to the MSTW Schedules & Scoreboards teams database, and the Team Rosters plugin can pull information on teams, such as their logos and colors, from that plugin. 

The plugin supports as many players and teams as needed. It provides several views of rosters including: a table (via a shortcode), a player gallery (via both a shortcode and a custom taxonomy template), and single player bio (via a custom post type template). Samples of all of the above displays are available in the screenshots on WordPress.org and on the [Shoal Summit Solutions Plugin Development Site](http://shoalsummitsolutions.com/dev).

The major enhancements in version 4.0 are:
 
* The filter by team feature on the "All Players" admin screen should now work on all sites (assuming no ill-behaved plugins).
* Integration with MSTW Schedules & Scoreboards
* Use Team Colors
* Configure table columns and data fields to meet your requirements. You can show/hide all columns (except Player Name) and change the header/label of all columns and data fields 
* Additional color settings have been provided on the admin settings screen, and the code to apply these settings has been re-factored to improve performance 
* The new WordPress Color Selector has been added to the admin settings screen.
* Additional CSS tags have been added to the display code to allow any team's rosters to be uniquely styled via the plugin's stylesheet. This functionality supports websites with multiple teams (leagues or clubs) with multiple colors, as shown on the [Shoal Summit Solutions Plugin Development Site](http://shoalsummitsolutions.com/dev/test-roster-plugin/). 
* The player name format can now be controlled on the admin setting screen. Several formats are available, perhaps most importantly a first name only format is now available to address privacy concerns with young players (screenshot-5).
* While the six built-in roster formats remain (high-school, college, pro, baseball-high-school, baseball-college, and baseball-pro), roster and player displays are now highly configurable. Between the admin display settings and the plugin's stylesheet, you can take (almost) complete control of your roster displays.
* The plugin is internationalized and ready for translation. The current translations in the /lang directory now require updating, especially for the extensive additions to the admin screens. I am happy to help translators.

Documentation on the following topics is available at the links below:

* [Add Edit Players](http://shoalsummitsolutions.com/tr-data-entry/)

* [Team Roster Shortcode](http://shoalsummitsolutions.com/tr-shortcode/)

* [Single Player Template (Individual Player Bios)](http://shoalsummitsolutions.com/tr-templates/)

* [Team Taxonomy Template (Player Gallery View)](http://shoalsummitsolutions.com/tr-templates/)

* [Display Settings](http://shoalsummitsolutions.com/tr-display-settings/)

* [Styling the Displays (with CSS stylesheet)](http://shoalsummitsolutions.com/tr-styling/)

* [Player Photos & Defaults](http://shoalsummitsolutions.com/tr-usage-notes/)

* [Uploading Rosters from CSV Files](http://shoalsummitsolutions.com/tr-loading-csv-files/)

* [Other Usage Notes](http://shoalsummitsolutions.com/tr-usage-notes/)

== Installation ==

Complete installation instructions are available on [shoalsummitsolutions.com](http://shoalsummitsolutions.com/tr-installation/). If you are upgrading from a previous version and wish to preserve your existing data, it is important that you READ THE ABOVE MAN PAGE CAREFULLY.

**NOTES:**
 
* Any changes to the plugin stylesheet (css/mstw-tr-style.css)*WILL* be overwritten, so if you have customized that file you should save it before upgrading. This issue has been fixed in version 4.0.
* To support the (significant) refactoring of the code in version 4.0, some shortcode names, arguments, and default Display Settings had to be changed. Therefore, it is possible that some customizations of existing Roster Tables and Player Galleries via [shortcode arguments], default colors, display settings, and so forth may have to be modified. Existing arguments and parameters were preserved to the greatest extent possible, so mileage will vary depending on exactly what arguments and/or settings you were using. 

== Frequently Asked Questions ==

[Frequently Asked Questions are available here](http://shoalsummitsolutions.com/tr-faq/).

== Usage Notes ==
*I suggest that you use the test pages on [the MSTW Plugin Development Site](http://shoalsummitsolutions.com/dev) as guides to compare what works and what doesn't.*

The [Other Usage Notes](http://shoalsummitsolutions.com/tr-usage-notes/) are available on shoalsummitsolutions.com.

== Screenshots ==
// NEEDS TO BE REDONE

1. Edit All Players admin screen
2. Edit Single Player admin screen
3. Sample of a Roster Table [shortcode] display
4. Sample of Single Player (bio) page
5. Display Settings admin screen
6. Sample Player Gallery page
7. Teams (taxonomy) admin screen
8. CSV File Import Screen

== Changelog ==

= 4.0 =
* Access controls for MSTW Admin, MSTW Team Rosters Admin, and Team Admins.
* New data fields for the team taxonomy to integrate with MSTW Schedules & Scoreboards Teams database
* Completely re-wrote the settings screen - organized with tabs and added help screens
* Re-orgainized Edit Player screen
* Added field to link Team taxonomy to MSTW Schedules & Scoreboards Teams DB
* Corrected the display of height/weight in the single-player.php template
* Cleaned up WP internationalization/translation. Domain was changed from mstw-loc-domain to mstw-team-rosters.
* Changed Custom Post Type & Taxonomy names to reduce the possibility of name collisions with themes and other plugins. THIS HAS A MAJOR IMPACT ON UPGRADES FROM PREVIOUS VERSIONS. READ HOW TO DO IT RIGHT HERE.
* Uses the single-player.php and taxonomy-team.php templates from the plugin's /theme-templates directory so the template no longer needs to be copied to the theme's (or child theme's) directory. But they can be moved to the main theme (or child theme) directory if desired. The plugin looks for them there first.
* The plugin's stylesheet (/css/mstw-tr-styles.css) no longer needs to be modified. One can create custom styles in the mstw-tr-custom-styles.css sytlesheet in the theme's (or child theme's) main directory. It will be loaded AFTER the plugin's stylesheet in the plugin's /css directory, so mstw-tr-custom-styles.css will have the highest priory in the plugin's style cascade.
* Added a setting to control the addition of links to single player profile pages from the player names in roster tables 
* Integrated mstw_utility_functions - removed old mstw-admin-utils.php 
* Added if ( !function_exists( 'function_name' ) ) wrappers to all include files
* Cleaned up many details in admin UI

= 3.1.2 =
* Fixed a bug (a typo) that prevented the team gallery shortcode from behaving correctly.
* Fixed bug with the show/hide table title setting - titles could not be hidden with the display setting. Corrected and tested.

= 3.1.1 =
* Fixed bug that prevented links to single player profiles from working with CHILD THEMES. If you aren't using a CHILD THEME, you don't need this patch.

= 3.1 =
* Fixed bug with sort order. Roster table and player gallery views both sort properly by number, first name, and last name.
* Fixed bug with show_height settings.
* Fixed minor bug: gallery sometimes linked to players/player-slug/?format='' instead of players/player-slug/?format=custom. This bug may or may not have an affect on a site, depending on formats and usage.
* Fixed the "Filter by Team" dropdown on the Show All Players admin screen. 
* Re-enabled the bulk delete menu on the All Players screen.
* Enabled the "Other" field. It may now be used on all 'custom' displays but it is disabled by default.
* Improved responsiveness of single player profile page (single-player.php). Looks better on small screens.
* Combined `single-player.php` and `content-single-player.php` templates (into the `single-player.php` template. Why? ...
* The use of links from the players/roster gallery or players/roster table to the single player profile is now determined by the existence of the `single-player.php` template in the active theme's main directory. Removed the 'use_xxx-links' settings, which are now superfluous. If you want links, just put the `single-player.php` template in the right directory. If not, omit it.
* Re-factored the admin menu code. Added MSTW icon to admin menu and screens.
* The WordPress Color Selector has been added to all color settings in the admin settings screen.
* Added a control to show player photos in the roster tables (shortcode).
* Added a gallery shortcode. [mstw-tr-gallery team=team-slug]

= 3.0.1 =
* Tweaked two calls (one in mstw-team-rosters.php and one in includes/mstw-team-rosters-admin.php) to prevent WARNINGS. (Easily fixed by setting WP_DEBUG to false in wp-config.php.) 
* Restructured the include files (filenames and function calls) to prevent conflicts with other MSTW plugins.

= 3.0 =
* Added a filter by team to the "All Players" table on the admin screen (screenshot-1).
* Added ability to configure table columns and data fields to meet specific application requirements. Show/hide all columns (except Player Name) and change the header/label of all columns and data fields. 
* Provided additional color settings on the Display Settings admin screen, and refactored the code to improve performance.
* Added the new WordPress Color Selector to the Display Settings admin screen.
* Added more CSS tags the display code to allow any team's rosters to be uniquely styled via the plugin's stylesheet. 
* Added player name format control to the Display Settings admin screen. Several formats are available, perhaps most importantly a first name only format is now available to address privacy concerns with young players.

= 2.1 =
* Re-factored the featured image (thumbnail) activation code to avoid conflicts with another plugin. (Thanks, Razz.)
* In the process, modified the theme settings so that the player photo width and height settings would always be honored. The default remains 150x150px regardless of how the thumbnail sizes are set in the theme.
* Corrected another conflict with some themes due to my horrible choice of the function name - my_get_posts(). Shame on me ... it's now mstw_tr_get_posts(). Doh!

= 2.0.1 =
* One include file was omitted from the build. That file is only needed for the CSV import function, which won't run without it.

= 2.0 =
* Added the ability to import rosters from CSV files
* Actived the Featured Image metabox on the add/edit page for players (player custom post type). Standard WordPress "Featured Images" are used for the player photos in the single player and player gallery pages.
* Added admin setting to hide player weights
* Added the ability to set the player photo size on the plugin settings page.
* Added three new formats for baseball: baseball-high-school, baseball-college, baseball-pro
* Cleaned up misc error checking and file/function includes to prevent conflicts with other plugins.

= 1.1 =
* Added the "Player Gallery" view of a roster
* Added admin settings for the sort order to allow numerical rosters in both the table [shortcode] and the player gallery.
* Added admin settings to enable or disable links from both the table view [shortcode] and the player gallery to the single player pages.
* Added an admin setting to control the title of the "Player Bio" content box on the single player view. By default, it is "Player Bio".
* Added fields to the player post type so that no field serves different purposes in different views [high-school|college|pro]. Note that not every field is used in every views and many fields are used in multiple views. However, every field now has one and only one meaning.

= 1.0 =
* Initial release.

== Upgrade Notice ==

3.1.1 Fixed bug that prevented links to single player profiles from working with CHILD THEMES. If you aren't using a CHILD THEME, you don't need this patch.

Significant new functionality has been added to version 3.0/3.1. Admins now have the ability to customize the visibility of and headings for data fields in roster tables, player bios, and player galleries. Admins can also control the display of roster tables in terms of colors and layout on a team-by-team basis in support of leagues and clubs. See the documentation [here](http://shoalsummitsolutions.com/tr-styling/] for more details.

* Upgrades of the Team Rosters plugin are designed to *NOT* impact any existing players, rosters, or settings. (But backup your DB before you upgrade, just in case. :) )
* Any changes to the plugin stylesheet (css/mstw-tr-style.css)*will* be overwritten, so if you have customized that file you will want to save it before upgrading.
* To support the (significant) refactoring of the code in version 3.0, some shortcode arguments and default Display Settings had to be changed. Therefore, it is possible that some customizations of existing Roster Tables via [shortcode arguments], default colors and other display settings, and so forth may have to be replaced. Existing arguments and parameters were preserved to the greatest extent possible, so mileage will vary depending on exactly what arguments and/or settings you were using. 

Version 3.1 of Team Rosters has been developed and tested on WordPress 3.6. If you use older version of WordPress, good luck! If you are using a newer WP version, please let me know how the plugin works, especially if you encounter problems.

