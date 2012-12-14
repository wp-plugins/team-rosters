=== Team Rosters ===
Contributors: Mark O'Donnell
Donate link: http://shoalsummitsolutions.com
Tags: sports,games,roster,sports teams,team roster,sports roster,sports team roster  
Requires at least: 3.4.2
Tested up to: 3.5
Stable tag: 1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html


Manages multiple sports team rosters. Displays tabular rosters, a single player bios, and a player gallery.

== Description ==

The Team Rosters plugin creates a custom post type (player), installs an editor for this post type, and provides a shortcode to display rosters as simple html tables. Page templates are provided to offer a single player view and a team 'player gallery' view of the roster. Players are assigned to team rosters using a custom taxonomy for teams. The plugin supports as many players and team rosters as you need.

A shortcode adds team rosters to your site in three tabular formats. (screenshot-3). A custom template (single-player.php) displays an individual player in a 'player bio' format (screenshot-4). A custom template (taxomony-teams.php) displays a team as a 'player gallery' (screenshot-6). [Read the installation instructions and the FAQs for how to get these pages working properly.]

The look of roster tables and player bios can be customized using the plugin's settings (screenshot-5) or by editting the plugin's stylesheet (/css/mstw-tr-style.css). The admin settings override the stylesheet rules. So if you choose to hack the stylesheet, I would recommend you clear all the stylesheet related settings on the admin settings screen.

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
* Other Info: Not currently displayed anywhere other than the Admin Player Edit page.

* TEAMS (category): Select the team(s) the player is on.

* FEATURED IMAGE: If you are using the player bios, add the player's photo as the Featured Image. Player photos should be 150px by 150px, unless you want to muck around with the single-player/content-single-player and taxonmy-teams page templates and the related styles to get everything to line up and fit. (The image size is currently hard-wired at 150x150px.)

**Team Roster Shortcode**

Game rosters are displayed via the shortcode [mstw-tr-roster] (screenshot-2), which accepts the following arguments:
 
1. team. REQUIRED. Use team=the-team-permalink to specific which roster to show. 

2. roster_type. Use roster_type=pro|college|high-school to specify the roster format. Defaults to high-school, which shows the minimum amount of player information.

3. show_title. Use show_title=false if you want to provide your own title for the roster. Defaults to the team name.

4. sort_order. Use sort_order=alpha|numeric to specify the sort order. Alphabetically by last name is the default. Numerically is the option. Not that this argument will override the corresponding setting on the admin page.

**Singular Post Template (Individual Player Bios)**

The single post template - the single-player.php and content-single-player.php files - provides the "bio" view for a single player (screenshot-3).  These files are located in the plugin's theme-templates directory. Copy these files into your theme's directory if you want to use the single player view. Note that if you don't want to use this feature, you can disable the links from the shortcode's table to the individual player views from the admin settings screen. These files were originally built on and tested with the WordPress twentyeleven theme, so I believe they are fairly generic, but you certainly may need to edit them for your specific theme. (If you have problems, let me know and I'll certainly try to help you if I can.)

**Team Taxonomy Template (Player Gallery View)**

*New in release 1.1!* The team taxonomy template - taxonomy-team.php - provides the "player gallery" view for a team (screenshot-6).  This file is located in the plugin's theme-templates directory. Copy these files into your theme's directory if you want to use the player gallery view. Note that there are no links from the shortcode output to this view. You have to link to this page however you choose. This file was originally built on and tested with the WordPress twentyeleven theme (and WordPress 3.5), so I believe it is fairly generic, but you certainly may need to edit it for your specific theme. (If you have problems, let me know and I'll certainly try to help you if I can.)

**Roster Views**
As described above the roster table, single player, and taxonomy views may be set for three view types: high-school, college, or pro. Here are the files that are shown in each view type:

* High School: Number, Name, Position, Height, Weight, Year 
* College: Number, Name, Position, Height, Weight, Year, Experience, Hometown, Last School
* Pro: Number, Name, Position, Height, Weight, Age, Experience, Last School, Country

**Settings**

The Admin Settings Page provides the following settings to customize the [shortcode] and single page displays.

*Single Player View Settings*

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

More complete control of the graphic design of the roster tables, the single player page, and the player gallery page may be obtained via the plugin's style sheet /css/mstw-tr-style.css. **Note** that the admin page settings will override the corresponding styles in the stylesheet. You **must** clear any admin settings to enable the corresponding stylesheet rules to take effect. (Out of the box, the admin settings should all be blank so the stylesheet rules control the default display.)

**Player Photos & Defaults**

Individual player photos are entered as the Featured Image on the Player Edit screen. See instructions above.

No featured image is found, the single player page and the player gallery page will look for the file default-photo-team-slug.jpg in the plugin's /images directory. So you can set a different default images (usually the team's logo) for each team. If that file is not found, the single player page and the player gallery page will use the default-photo.jpg image from the plugin's /images directory. Out of the box, the default is a "mystery man" icon. You use an FTP program to replace it with another image of your choosing, your team logo, say. Your replacement default-photo.jpg file MUST use that name and should be 150px by 150px.   

**Notes:**

The Team Rosters plugin is the third in a set of plugins supporting a framework for sports team websites. Others will include Game Locations and Game Schedules(both available on wordpress.org/extend/plugins/  or at shoalsummitsolutions.com), Coaching Staffs, Sponsors, Frequently Asked Questions, Users Guide, and more. If you are a developer and there is one you would really like to have, or if you would like to participate in the development of one, please let me know (mark@shoalsummitsolutions.com).

== Installation ==

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
You probably need to reset your permalinks. Go to your admin dashboard -> Settings -> Permalinks. Change the permalink setting and save. Then change it back. (FWIW, I test the plugin with the Post name setting; not that it should matter.)

== Screenshots ==

1. Edit All Players page
2. Edit Single Player page
3. Sample of shortcode display
4. Sample of single player (bio) page
5. Settings page
6. Sample Player Gallery page

== Changelog ==

= 1.0 =
* Initial release.

= 1.1 =
* Added the "Player Gallery" view of a roster
* Added admin settings for the sort order to allow numerical rosters in both the table [shortcode] and the player gallery.
* Added admin settings to enable or disable links from both the table view [shortcode] and the player gallery to the single player pages.
* Added an admin setting to control the title of the "Player Bio" content box on the single player view. By default, it is "Player Bio".
* Added fields to the player post type so that no field serves different purposes in different views [high-school|college|pro]. Note that not every field is used in every views and many fields are used in multiple views. However, every field now has one and only one meaning.

== Upgrade Notice ==

The current version of Team Rosters has been developed and tested on WordPress 3.5. If you use older version of WordPress, good luck! (FYI, version 1.0 was developed and tested on WP 3.4.2.) If you are using a newer version, please let me know how the plugin works, especially if you encounter problems.

Upgrades of the Team Rosters plugin are designed to *NOT* impact any existing players, rosters, or settings. (But backup your DB before you upgrade, just in case. :)