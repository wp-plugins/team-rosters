=== Team Rosters ===
Contributors: Mark O'Donnell
Donate link: http://shoalsummitsolutions.com
Tags: sports,games,roster,sports teams,team roster,sports roster,sports team roster  
Requires at least: 3.4.2
Tested up to: 3.5
Stable tag: 2.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html


Manages multiple sports team rosters. Displays tabular rosters, a single player bios, and a player gallery.

== Description ==

The Team Rosters plugin creates a custom post type (player), installs an editor for this post type, and provides a shortcode to display rosters as simple html tables. Page templates are provided to offer a single player view and a team 'player gallery' view of the roster. Players are assigned to team rosters using a custom taxonomy for teams. The plugin supports as many players and team rosters as needed.

**NEW IN VERSION 2.0** is the ability to upload team rosters from CSV files. See the *Usage Notes* section for complete details.

A shortcode adds team rosters to your site in six tabular formats. (screenshot-3). A custom template (single-player.php) displays an individual player in a 'player bio' format (screenshot-4). A custom template (taxomony-teams.php) displays a team as a 'player gallery' (screenshot-6). [Read the Installation Instructions, FAQs, and Usage Notes for how to get these pages working properly.]

The look of roster tables and player bios can be customized using the plugin's settings (screenshot-5) or by editting the plugin's stylesheet (/css/mstw-tr-style.css). The admin settings override the stylesheet rules. So if you choose to hack the stylesheet, you may want to clear all the stylesheet related settings on the admin settings screen.

The plugin is internationalized and ready for translation. I am happy to help translators.

Use the Edit Player screen (screenshot-2) to enter player information. The available fields are:

* Title: The post title is displayed only on the admin pages. I suggest using the title to simplify player/team organization and sorting in the Players Edit table.
* First Name: Player's first name - 32 chars
* Last Name: Player's last name - 32 chars
* Number: Player's number. Could use "88/99" if a player has different home and away numbers. - 8 chars	
* Position: Player's position. Free form could be "OL/DL" or "Offensive Tackle". - 32 chars
* Height: Player's height in any format. Nominally 5'10" in US - 8 chars
* Weight: Player's weight in any format. Units are not specified - 8 chars
* Year: Player's year in school; e.g., "Senior" or "Soph" or "FR". Not used in the pro format rosters. - 8 chars
* Experience: Playing experience. For high-school or college formats, it would be something like "2V" (two years varsity). For pro format, it would be simply "10" or "10 years" - 8 chars
* Age: Player's age. Used only in pro format. - 8 chars
* Home Town: Player's home town. Used only in college format. - 32 chars
* Last School: Player's last previous school. Used in college and pro formats. - 32 chars
* Country: Player's native country. - 32 chars
* Bats: R|L|B [For baseball only.]
* Throws: R|L [For baseball only.]
* Other Info: Not currently displayed anywhere other than the Admin Player Edit page. [But feel free to add it if you have a need.]

* TEAMS (category): Select the team(s) the player is on.

* FEATURED IMAGE: If you are using the player bios, add the player's photo as the Featured Image. The default size for player photos is 150px by 150px; that can be changed in the admin settings.

**Team Roster Shortcode**

Game rosters are displayed via the shortcode [mstw-tr-roster] (screenshot-2), which accepts the following arguments:
 
1. team. REQUIRED. Use team=the-team-permalink to specific which roster to show. 

2. roster_type. Use roster_type=pro|college|high-school|baseball-high-school|baseball-college|baseball-pro to specify the roster format. Defaults to high-school, which shows the minimum amount of player information.

3. show_title. Use show_title=false if you want to provide your own title for the roster. Defaults to the team name.

4. sort_order. Use sort_order=alpha|numeric to specify the sort order. Alphabetically by last name is the default. Numerically is the option. Note that this argument will override the corresponding setting on the admin settings page.

5. show_weight. Use show_weight=show|hide to show or hide the player weight. Defaults to show. This argument will override the corresponding setting on the admin settings page.

**Singular Post Template (Individual Player Bios)**

The single post template - the single-player.php and content-single-player.php files - provides the "bio" view for a single player (screenshot-3).  These files are located in the plugin's theme-templates directory. Copy these files into your theme's directory if you want to use the single player view. Note that if you don't want to use this feature, you can disable the links from the shortcode's table to the individual player views from the admin settings screen. These files were originally built and tested on the WordPress twentyeleven theme, so I believe they are fairly generic, but you certainly may need to edit them for your specific theme. (If you have problems, let me know and I'll certainly try to help you if I can.)

**Team Taxonomy Template (Player Gallery View)**

*New in release 1.1!* The team taxonomy template - taxonomy-team.php - provides the "player gallery" view for a team (screenshot-6).  This file is located in the plugin's theme-templates directory. Copy these files into your theme's directory if you want to use the player gallery view. Note that there are no links from the shortcode output to this view. You have to link to this page however you choose. This file was originally built and tested on the WordPress twentyeleven theme (and WordPress 3.5), so I believe it is fairly generic, but you certainly may need to edit it for your specific theme. (If you have problems, let me know and I'll certainly try to help you if I can.)

**Roster Views**
As described above the roster table, single player, and taxonomy views may be set to display six formats: high-school, college, pro, baseball-high-school, baseball-college, and baseball-pro. Here are the fields that are shown in each view type:

* High School: Number, Name, Position, Height, Weight, Year 
* High School Baseball: Number, Name, Position, Bats/Throws, Height, Weight, Year
* College: Number, Name, Position, Height, Weight, Year, Experience, Hometown, Last School
* College Baseball: Number, Name, Position, Bats/Throws, Height, Weight, Year, Experience, Hometown, Last School
* Pro: Number, Name, Position, Height, Weight, Age, Experience, Last School, Country
* Pro Baseball: Number, Name, Position, Bats/Throws, Height, Weight, Age, Experience, Last School, Country

**Settings**

The Admin Settings Page provides the following settings to customize the [shortcode] and single page displays.

*Single Player View Settings*

* Show Player Weight (defaults to "show") Read more about this setting in the FAQ section.  
* Single Player Content Title (defaults to "Player Bio")
* Single Player Page Main Box Background Color
* Single Player Page Main Box Text Color

*Roster Table [shortcode]*

* Sort Roster Alphabetically (by Last Name) or Numerically
* Roster Table Title Text Color
* Roster Table Player Name Format ("First Last" or "Last, First")
* Roster Table Format (high-school, pro, or college)
* Add Links from Roster Table to Player Bios 
* Link Text Color (links on roster table to player bios)
* Roster Table Header Background Color
* Roster Table Header Text Color
* Roster Table Even Row Text Color
* Roster Table Even Row Background Color
* Roster Table Odd Row Text Color
* Roster Table Odd Row Background Color

*Player Gallery*

* Sort Player Gallery Alphabetically (by Last Name) or Numerically
* Add Links from Player Gallery to Player Bios
* Note that the player name format "First Last" or "Last, First" - is inherited from the Roster Table Settings. (Don't ask me why; I guess it just seemed weird to have names displayed in different ways for the same team.)

More complete control of the graphic design of the roster tables, the single player page, and the player gallery page may be obtained via the plugin's style sheet /css/mstw-tr-style.css. **Note** that the admin page settings will override the corresponding styles in the stylesheet. You must clear any admin settings to enable the corresponding stylesheet rules to take effect. (Out of the box, the admin settings should all be blank so the stylesheet rules control the default display.)

**Player Photos & Defaults**

See the *Usage Notes* section. 

**Notes:**

The Team Rosters plugin is the third in a set of plugins supporting a framework for sports team websites. Others will include Game Locations and Game Schedules(both available on wordpress.org/extend/plugins/  or at shoalsummitsolutions.com), Coaching Staffs, Sponsors, Frequently Asked Questions, Users Guide, and more. If you are a developer and there is one you would really like to have, or if you would like to participate in the development of one, please let me know (mark@shoalsummitsolutions.com).

== Installation ==

*NOTE:* When upgrading the existing player data will not be deleted, however any changes to the mstw_tr_styles.css stylesheet *will* be overwritten, so you may want to save that file before upgrading.

Basic installation the **AUTOMATED** way:

1. Go to the Plugins->Installed plugins page in Wordpress Admin.
2. Click on Add New.
3. Search for Team Rosters.
4. Click Install Now.
5. Activate the plugin.
6. Use the new Players menu to create and manage your team rosters.
7. Use the Settings page to configure the plugin, shortcode, and single player page.

Basic installation the **MANUAL** way:

1. Download the plugin from the wordpress site.
2. Copy the entire /mstw-team-roster/ directory into your /wp-content/plugins/ directory.
3. Go to the Wordpress Admin Plugins page and activate the plugin.
4. Use the new Players menu to create and manage your team rosters.
5. Use the Settings page to configure the plugin, shortcode, and single player page.

= If you plan to use the player bio pages linked from the roster table, then you must copy the following files from the team-rosters/theme-templates directory to your theme's directory: =

1.	single-player.php
2.	content-single-player.php

See *Usage Notes* for more information.

= If you plan to use the Player Gallery page, then you must copy the following file from the team-rosters/theme-templates directory to your theme's directory: =

1.	taxomony-teams.php

== Frequently Asked Questions ==

= Can I set up separate rosters for different teams? =
Yes. You enter the players and control what roster(s) they are on through Teams category.

= Can a player be on more than one team? =
Yes. You can assign two Team categories to one player, in which case both teams will be listed in the title on his or her bio page. OR, you can duplicate a player and assign the copies to different Team categories, in which case only one team will be listed on the bio page. 

= I live in Split, Croatia (or wherever). Does the plugin support other languages? =
The plugin supports localization as of version 2.0. If you happen to live in Split, you're in luck. The Croatian translation is contained in the /lang directory. (Thanks Juraj!)

= How do I change the look (text colors, background colors, etc.) of the team roster table and/or the player bio page? =
See the information under *Settings* above. 

= Can I display more than one roster on a single page by using multiple shortcodes? =
Yes.

= The link from the players' names to their bio pages appears to be broken or at least the bio page does not display properly. What did I do wrong? =
Maybe nothing. First, please review the installation instructions. You must copy the plugin's template files into your theme's main directory. These templates, and the assocatiated stylesheets and settings, were tested with the WordPress Twentyeleven theme. Your theme may be overriding some of the styles, defining display areas that are too small for various elements of the plugin, or any number of other things. This can all be fixed, but it has to be done on a theme-by-theme basis.

= I'm positive I installed everything correctly, but the links to the single player pages (player bios) still don't work. What's wrong? =
You probably need to reset your permalinks. Go to your admin dashboard -> Settings -> Permalinks. Change the permalink setting and save. Then change it back. (FWIW, you probably want to use the Post-name setting, but you must change it to reset the permalinks.)

= I don't want to display weights for a women's team? How can I do that? =
As of version 2.0, there are several ways to accomplish this. First, you can turn off the weights in the admin settings screen. This will remove the weight column from ALL roster tables on your site. Then you can override the admin setting with the 'show_weight' argument in the [shortcode]. `show_weight=show|hide` will show or hide the weight column regardless of the admin setting. Finally, to remove the weight from the single player and player gallery views, just don't enter weights for the players. If a player has a blank for his or her weight then nothing will be display (or "Weight" title or data).

== Usage Notes ==
*I recommend that you use the test pages on http://shoalsummitsolutions.com/dev as guides to compare what works with what doesn't.*

= The single-player.php template and the single player view =
The single player pages are linked from the names in the roster table. You can deactivate those links in the plugin settings. If you wish to use the single player view, you must copy both the single-player.php and the content-single-player.php templates from the plugins /theme-templates directory to your theme's main directory. The links in the roster table are of the form:
`your-base-url/players/player-slug/?format=your-format`
where your-format is whatever has been set in the plugin settings -  high-school|college|pro.

The first problem many users encounter is a 404 Page not found message, that indicates that their permalinks need to be reset. On the WP dashboard go to Settings -> Permalinks. Change the setting to anything at all. Save it. Then change to setting back to "Post name". 

The next problem is that the single player page doesn't look right in a given theme. It was built and tested on the WP Twenty Eleven theme, but there's not reason it will "fit" your theme. Many settings are available in the plugin settings page, but you will likely need to edit the styles in mstw-tr-styles.css and the two templates to get the single player content to fit nicely within your theme's 'wrapper'. 

= The taxonomy-teams template and the player gallery view =
The player gallery page(s) must be linked from somewhere on your site, for example, a menu item. If you wish to use the player gallery view, you must copy the taxonomy-teams.php template from the plugins /theme-templates directory to your theme's main directory. The URL for a team gallery page looks something like:
`your-base-url/teams/team-slug`

The first problem many users encounter is a 404 message, which indicates that their permalinks need to be reset. See the instructions above. The next problem is that the player gallery page doesn't look right in a given theme. See the discussion above. 

= Default images for player photos =
Individual player photos are entered as the Featured Image on the Player Edit screen. See instructions above.

No featured image is found, the single player page and the player gallery page will look for the file default-photo-team-slug.jpg in the plugin's /images directory. So you can set a different default images (usually the team's logo) for each team. If that file is not found, the single player page and the player gallery page will use the default-photo.jpg image from the plugin's /images directory. Out of the box, the default is a "mystery man". You use an FTP program to replace it with another image of your choosing, your team logo, say. Your replacement default-photo.jpg file **MUST** use that name and should be 150px by 150px by default.  

= Loading rosters from CSV files =
New in version 2.0 is the ability to load team rosters from files in CSV format. This allows teams to load their roster from an Excel spreadsheet, which is often used to create the printed roster. However, some rules apply. Sample CSV files are avaliable in the plugins /csv-examples directory. I recommend you get these examples to work, then copy them for your own needs. 

**Rule 1:** The Team must exist in the Teams taxonomy before the roster can be uploaded. Note that hierarchical entries are not supported. Create the team using the Players->Teams screen. [All teams are at the top level of the taxonomy, for example, teams->baseball->giants does not work.]

**Rule 2:** The following field names may appear in the top row of the CSV table and will be mapped as follows:
CVS Column Header -> Database Field

* "First Name" or First" -> first_name
* "Last Name", "Last" -> last_name
* "Position", "Pos" -> position
* "Number", "Nbr", "#" -> number
* "Weight", "Wt" -> weight
* "Height", "Ht" -> height
* "Age" -> age
* "Year" -> year
* "Experience", "Exp" -> experience
* "Home Town" -> home_town
* "Country" -> country
* "Last School" -> last_school
* "Bats", "Bat" -> bats
* "Throws", "Thw", "Throw" -> throws

**Other Rules**

1. The player slug will be first-last, so it is important that at least one of Frist Name and Last Name fields be present.
2. Capitalization is not important. So "Ht" and "HT" both are mapped to height.
3. Missing fields are ignored.

== Screenshots ==

1. Edit All Players page
2. Edit Single Player page
3. Sample of shortcode display
4. Sample of single player (bio) page
5. Settings page
6. Sample Player Gallery page

== Changelog ==

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

The current version of Team Rosters has been developed and tested on WordPress 3.5. If you use older version of WordPress, good luck! (FYI, version 1.0 was developed and tested on WP 3.4.2.) If you are using a newer version, please let me know how the plugin works, especially if you encounter problems.

Upgrades of the Team Rosters plugin are designed to *NOT* impact any existing players, rosters, or settings. (But backup your DB before you upgrade, just in case. :)