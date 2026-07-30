<?php
//Note: All entries must be compatible with javascript syntax. HTML tags will not be parsed.

//Preferences
$langmk['had_boardname'] = "The MKPortal designation for the forum software you are using. This value can only be changed by editing mkportal/conf_mk.php.";
$langmk['had_siteurl'] = "The full URL to your MKPortal website root directory. This value can only be changed by editing mkportal/conf_mk.php.";
$langmk['had_adminpath'] = "Changing the \"admin\" directory name is recommended for security. To change the admin directory name: (1) Rename the \"admin\" directory something that is hard to guess. Make the directory name strong like a good password. Example: \"ride2live\". (2) Edit mkportal/conf_mk.php to set the new admin directory name. Example: \$ADMIN_PATH = \"ride2live\";  This value can only be changed by editing mkportal/conf_mk.php.";
$langmk['had_fpath'] = "The name of your forum directory. This is not the full path to the forum, only the directory name. This value can only be changed by editing mkportal/conf_mk.php.";
$langmk['had_sitename'] = "The name of your website. This will be used globally in the browser title bar and in other places in MKPortal. Titles are very important for SEO. Use it wisely.";
$langmk['had_putoff'] = "Set this to \"Yes\" to put your website in \"maintenance mode\". Both the portal and forum will be offline. Admins who are logged in will still be able to view the website. If you accidently logout while your site is offline simply ftp into your server and edit the conf_mk.php file. Set \$MK_OFFLINE = \"0\"; Zero \"0\" means the portal is NOT offline. One \"1\" means it IS offline.";
$langmk['had_lang'] = "The default language for your website. Language can be changed by users if you allow it in your forum permissions and if you have corresponding language packs installed for portal and forum. For example, if you want to allow users to choose the Spanish language you must have an MKPortal Spanish lang pack installed and a Spanish lang pack installed for your forum.";
$langmk['had_editor'] = "The HTML editor available in MKportal is the TinyMCE WYSIWYG editor. The BBcode editor is proprietary to MKPortal. Note that frontend submissions using either editor are filtered for security. Content submitted in the MKPortal CP is not filtered which allows Admins to use full html and javascript. Because TinyMCE does filter some content it is suggested that Admins use the BBcode editor to add content in the Portal CP.";
$langmk['had_disablezip'] = "Using gzip compressions will save bandwidth and decrease page load times. However, if your server does not support this feature you can disabled it with this setting.";
$langmk['had_sytime'] = "Use this setting to synchronize MKPortal time with your forum's time";
$langmk['had_metadesc'] = "Used in the meta \"description\" tag. Meta descriptions are important for SEO. Describe your website using a couple of good sentences with keywords. But not too long.";
$langmk['had_metakey'] = "Used in the meta \"keywords\" tag. Meta keywords are generally not very important for SEO. Don't use too many.";
$langmk['had_skin'] = "Sets the MKPortal template. Use the MKPortal \"Forum\" template to automatically import the forum css into the portal. New MKPortal templates can be installed simply by uploading the new template's folder to mkportal/templates. Be sure the new template is compatible with your MKPortal version.";
$langmk['had_powidth'] = "Set width in pixels to make your template a fixed width. Set the template to a percentage to make the template resize with the viewer's browser. This feature may not be supported with all 3rd party templates. Keep in mind that a very narrow width can break the template in some modules such as TopSite, Blog Chart and in forum view.";
$langmk['had_disablenav'] = "Set to \"Yes\" to disable the top navigation bar.";
$langmk['had_noicons'] = "Turns icons on or off globally in navigation links. This setting affects links in the top navigation bar and in all MKPortal blocks.";
$langmk['had_cowidth'] = "Sets the width for the MKPortal side columns in pixels. Enter a numerical value only. Do not add \"px\" since it is implied.";
$langmk['had_load_leftc'] = "Disabling or \"Unloading\" a column means that it is not initialized in the MKPortal php script. This setting is global in the portal only. This is different than \"collapsing\" a column which means the column is loaded in the php script but is hidden client-side using javascript.";
$langmk['had_load_rightc'] = "Disabling or \"Unloading\" a column means that it is not initialized in the MKPortal php script. This setting is global in the portal only. This is different than \"collapsing\" a column which means the column is loaded in the php script but is hidden client-side using javascript.";
$langmk['had_foot_logo'] = "Although the MKPortal license does not allow modification of the global copyright notice, it is perfectly acceptable to hide the MKPortal logo if you like.";
$langmk['had_foot_version'] = "Some Admins prefer to hide the version number of scripts because it can make it a little harder for \"crackers\" and other internet miscreants to locate vulnerable programs online. This is not normally an issue if you keep your scripts updated. Other Admins prefer to show the version number so their users know they are running the latest releases.";
$langmk['had_foot_debug'] = "Displays page generation time and total query count in the footer. Used mainly when debugging custom MKPortal blocks, modules, or hacks or when troubleshooting other website issues.";
$langmk['had_forumin'] = "Set to \"Yes\" to display your forum inside the MKPortal wrapper. IMPORTANT! You must also edit one or more forum files before this setting will have any effect. See the instructions in the readme.htm included in the MKPortal download package.";
$langmk['had_rightcolumn'] = "\"Closing\" or Collapsing a column means the column is loaded in the php script but is hidden client-side using javascript. This allows users to open or close the column according to their preference. This is different than disabling or \"not loading\" a column which means that the column is not initialized in the MKPortal php script.";
$langmk['had_leftcolumn'] = "\"Closing\" or Collapsing a column means the column is loaded in the php script but is hidden client-side using javascript. This allows users to open or close the column according to their preference. This is different than disabling or \"not loading\" a column which means that the column is not initialized in the MKPortal php script.";
$langmk['had_uleftcolumn'] = "Disabling or \"Unloading\" a column means that it is not initialized in the MKPortal php script. This setting is global in the forum only. This is different than \"collapsing\" a column which means the column is loaded in the php script but is hidden client-side using javascript.";
$langmk['had_urightcolumn'] = "Disabling or \"Unloading\" a column means that it is not initialized in the MKPortal php script. This setting is global in the forum only. This is different than \"collapsing\" a column which means the column is loaded in the php script but is hidden client-side using javascript.";




$langmk['had_cp_tpl'] = "This setting allows you to use the MKportal \"default\" template in Portal CP. This setting will also set the template width in the Portal CP to \"100%\" which is the optimum width for the CP. If you are using a narrow MKPortal template this is recommended for correct display of settings.";



//RSS
$langmk['had_rss_parser'] = "Select the RSS Parser to use for displaying RSS feeds. Each RSS Parser has it\'s own unique features and limitations. Remember to activate and position the corresponding block in MKPortal CP &gt; Blocks &gt; Position. The MKPortal RSS block is named \"rss.php\" and the SimplePie RSS block is named \"rss_simplepie.php\".";
$langmk['had_rss_desc'] = "Sets the text cutoff for RSS details. Setting a cutoff value will also strip all html tags. To parse html in the RSS details leave this field blank or enter \"0\"";
$langmk['had_max_items'] = "The number of feed items to display from each feed channel.";
$langmk['had_desc'] = "Check this to display the feed description details.";
$langmk['had_cache_time'] = "Cache refresh time in seconds. Examples: 60 = 1minute; 300 = 5minutes; 600 = 10minutes; 3600 = 1hour";
$langmk['had_marquee'] = "Scrolls RSS news items upward in RSS block. Note: This feature uses the html &lt;marquee&gt; tag which is not valid xhtml. However, it will work with most modern browsers.";
$langmk['had_marquee_height'] = "Sets the height of the RSS block when using the marquee scrolling feature. This value must not be blank or \"0\" if you are using the scrolling feature. This field has no effect when not using scrolling.";
$langmk['had_rss_media'] = "RSS enclosures include embedded media such as Media RSS, iTunes RSS etc. This feature is available with the SimplePie parser only.";
$langmk['had_rss_css'] = "Formats the RSS block using a separate linked stylesheet. The stylesheet is located at mkportal/modules/rss/files/simplepie.css. By disabling this feature the RSS block will use default MKPortal text formatting.";
$langmk['had_sp_compat'] = "Click this link to run the SimplePie Server Compatibility test. This test will show you if your server meets the minimum requirements to use the SimplePie RSS parser. If your server does not meet the requirements the test results will tell you exactly why. Link opens in new window.";
$langmk['had_source_pos'] = "Display order for RSS feed channels.";
$langmk['had_source_name'] = "Give a name to the feed.";
$langmk['had_source_url'] = "Enter the full URL of a valid feed.";
$langmk['had_test'] = "After saving your settings you can use the \"Test\" link to preview the feed in your RSS reader to determine if it is a valid feed. Your RSS reader is determined by browser settings. Link opens in new window.";
$langmk['had_save_clean'] = "Click \"Save\" to update your RSS configuration settings or feeds. Remember to activate your feeds above or they will not display in the RSS block. The \"Clean Cache\" button will refresh the cached feeds stored in the mkportal/cache directory. If your RSS block does not display your latest settings use this feature to update the RSS cache.";
$langmk['had_referer'] = "The HTTP_REFERER check for the Portal CP is designed only to help Admins recognize certain kinds of \"social engineering\" attacks caused by clicking malicious links. If you receive \"Action Not Allowed\" error messages when trying to access Portal CP pages be sure that you allow your browser and firewall to pass referer info to your domain. Alternatively, you can disable this setting by editing conf_mk.php to set \$MK_REFERER = \"1\";";
$langmk['had_postwhitelist'] = "List hostnames allowed to \$_POST to your site. For example, if you have eCommerce payment processing on your site the processor may return some data back to your site for display on a \"Thank You\" page, etc. The HTTP_REFERER check for \POST\" is called when submitting web forms and is designed to stop automated and other attacks from remote servers. If users receive \"not in the white-list allowed to request POST\" error messages when submitting forms then they must allow their browser and firewall to pass referer info to your domain. Alternatively you can disable this check for all users by entering the string \"disable\" in the \"POST\" Whitelist input box.";
$langmk['had_rss_merge'] = "Merges items from multiple feeds and sorts them by date.";

//Blocks
$langmk['had_sbllist'] = "\"System\" blocks are blocks that have been uploaded as PHP files to the mkportal/blocks directory. These include the default MKPortal blocks as well as blocks uploaded by site Admins via ftp.";
$langmk['had_bllist'] = "\"Personal\" blocks are blocks that have been created in the MKPortal CP Blocks manager. HTML block and Internal Page Links block content is stored in the mkp_blocks database table. PHP block content is stored as a cached php file in the mkportal/cache directory. PHP block cached files are named \"pblock_x.php\" where \"x\" is the block ID number.";

//Pages
$langmk['had_mcont'] = "HTML Internal Page content is stored in the mkp_pages database table. PHP Internal Page content is stored as a cached php file in the mkportal/cache directory. PHP page cached files are named \"ppage_x.php\" where \"x\" is the page ID number.";


?>
