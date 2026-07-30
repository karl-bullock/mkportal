<?php
/*
+--------------------------------------------------------------------------
|   MkPortal
|   ========================================
|   by Meo aka Luponero <Amedeo de longis>
|      Don K. Colburn <visiblesoul.net>
|
|   Copyright (c) 2003-2008 mkportal.it
|   http://www.mkportal.it
|   Email: luponero@mclink.it
|
+---------------------------------------------------------------------------
|
|   > MKPortal
|   > Written By Amedeo de longis
|   > Date started: 9.2.2004
|
+--------------------------------------------------------------------------
*/
global $mklib, $DB, $MK_BOARD;

//General Settings
//Anything need to be approved?

/*
//MySQL >= 4.0
if ($MK_BOARD == "IPB") {
	//Needed for IP.Board 2.x only - IPS Driver errors without this line
	//See ips_kernal/class_db_mysql_client.php (function query) for info 
	global $ipsclass;
	//$DB->allow_sub_select = 1; //IP.Board < 2.1
	$ipsclass->DB->allow_sub_select = 1; //IP.Board >= 2.1
}

$query = $DB->query("	
	(SELECT id FROM mkp_news WHERE validate = '0')
		UNION
	(SELECT id FROM mkp_blog WHERE validate = '0')
		UNION
	(SELECT id FROM mkp_gallery WHERE validate = '0')
		UNION
	(SELECT id FROM mkp_download WHERE validate = '0')
		UNION
	(SELECT id FROM mkp_topsite WHERE validate = '0')
		UNION
	(SELECT id FROM mkp_reviews WHERE validate = '0')
		UNION
	(SELECT id FROM mkp_quotes WHERE validate = '0')");
$result = $DB->get_num_rows($query);
*/

//MySQL < 4.0
$query = $DB->query("SELECT id FROM mkp_news WHERE validate = '0'");
$cnews = $DB->get_num_rows($query);
$query = $DB->query("SELECT id FROM mkp_blog WHERE validate = '0'");
$cblog = $DB->get_num_rows($query);
$query = $DB->query("SELECT id FROM mkp_gallery WHERE validate = '0'");
$cgal = $DB->get_num_rows($query);
$query = $DB->query("SELECT id FROM mkp_download WHERE validate = '0'");
$cdown = $DB->get_num_rows($query);
$query = $DB->query("SELECT id FROM mkp_topsite WHERE validate = '0'");
$ctop = $DB->get_num_rows($query);
$query = $DB->query("SELECT id FROM mkp_reviews WHERE validate = '0'");
$crew = $DB->get_num_rows($query);
$query = $DB->query("SELECT id FROM mkp_quotes WHERE validate = '0'");
$cquot = $DB->get_num_rows($query);

$result = $cnews + $cblog + $cgal + $cdown + $ctop + $crew + $cquot;

//New Submissions notification
$approvebg = (!$result) ? "tdblock" : "tdblock bghighlight2"; //background highlighting
$approveno =  (!$result) ? "" : '&nbsp;('.intval($result).')'; //Number of waiting submissions
$approve = (!$result) ? "<img class=\"mkicon\" src=\"images/icons/famfamfam/silk/nosubmissions.png\" align=\"left\" title=\"(0) {$this->lang['ad_apprmenu']}\" alt=\"(0) {$this->lang['ad_apprmenu']}\" />" : "<img class=\"mkicon\" src=\"images/icons/famfamfam/silk/newsubmissions.png\" align=\"left\" title=\"$approveno {$this->lang['ad_apprmenu']}\" alt=\"$approveno {$this->lang['ad_apprmenu']}\" />"; //Notification icon

$menu = $Skin->view_block("{$this->lang['ad_mgeneral']}", "<tr><td width=\"100%\" class=\"tdblock\"><img class=\"mkicon\" src=\"images/icons/foood/atb_home.gif\" align=\"left\" title=\"{$this->lang['portal_home']}\" alt=\"{$this->lang['portal_home']}\" />&nbsp;<a class=\"mktxtcontr\" href=\"$this->siteurl/index.php\">{$this->lang['portal_home']}</a></td></tr>
		<tr><td width=\"100%\" class=\"tdblock\"><img class=\"mkicon\" src=\"images/icons/famfamfam/silk/preferences.png\" align=\"left\" title=\"{$this->lang['ad_preferences']}\" alt=\"{$this->lang['ad_preferences']}\" />&nbsp;<a class=\"uno\" href=\"index.php\">{$this->lang['ad_preferences']}</a></td></tr>
		<tr><td width=\"100%\" class=\"tdblock\"><img class=\"mkicon\" src=\"images/icons/famfamfam/silk/perms.png\" align=\"left\" title=\"{$this->lang['ad_mperm']}\" alt=\"{$this->lang['ad_mperm']}\" />&nbsp;<a class=\"uno\" href=\"index.php?ind=ad_perms\">{$this->lang['ad_mperm']}</a></td></tr>
		<tr><td width=\"100%\" class=\"$approvebg\">$approve&nbsp;<a class=\"uno\" href=\"index.php?ind=ad_approvals\">{$this->lang['ad_apprmenu']}</a>$approveno</td></tr>
       	<tr><td width=\"100%\" class=\"tdblock\"><img class=\"mkicon\" src=\"images/icons/famfamfam/silk/page_edit.png\" align=\"left\" title=\"{$this->lang['ad_categories']}\" alt=\"{$this->lang['ad_categories']}\" />&nbsp;<a class=\"uno\" href=\"index.php?ind=ad_categories\">{$this->lang['ad_categories']}</a></td></tr>
");

//Blocks
$menu .= $Skin->view_block("{$this->lang['ad_mblocks']}", "
		<tr><td width=\"100%\" class=\"tdblock\"><img class=\"mkicon\" src=\"images/icons/famfamfam/silk/blocks.png\" align=\"left\" title=\"{$this->lang['ad_mposition']}\" alt=\"{$this->lang['ad_mposition']}\" />&nbsp;<a class=\"uno\" href=\"index.php?ind=ad_blocks\">{$this->lang['ad_mposition']}</a></td></tr>
		<tr><td width=\"100%\" class=\"tdblock\"><img class=\"mkicon\" src=\"images/icons/famfamfam/silk/block_titles.png\" align=\"left\" title=\"{$this->lang['ad_mbtitles']}\" alt=\"{$this->lang['ad_mbtitles']}\" />&nbsp;<a class=\"uno\" href=\"index.php?ind=ad_blocks&amp;op=blocks_titles\">{$this->lang['ad_mbtitles']}</a></td></tr>
		<tr><td width=\"100%\" class=\"tdblock\"><img class=\"mkicon\" src=\"images/icons/famfamfam/silk/block_edit.png\" align=\"left\" title=\"{$this->lang['ad_mmanage']}\" alt=\"{$this->lang['ad_mmanage']}\" />&nbsp;<a class=\"uno\" href=\"index.php?ind=ad_blocks&amp;op=blocks_list\">{$this->lang['ad_mmanage']}</a></td></tr>
		<tr><td width=\"100%\" class=\"tdblock\"><img class=\"mkicon\" src=\"images/icons/famfamfam/silk/block_add.png\" align=\"left\" title=\"{$this->lang['ad_mcreation']}\" alt=\"{$this->lang['ad_mcreation']}\" />&nbsp;<a class=\"uno\" href=\"index.php?ind=ad_blocks&amp;op=blocks_main_new\">{$this->lang['ad_mcreation']}</a></td></tr>");

//Pages
$menu .= $Skin->view_block("{$this->lang['ad_mcont']}", "<tr><td width=\"100%\" class=\"tdblock\"><img class=\"mkicon\" src=\"images/icons/famfamfam/silk/page_edit.png\" align=\"left\" title=\"{$this->lang['ad_mmanage']}\" alt=\"{$this->lang['ad_mmanage']}\" />&nbsp;<a class=\"uno\" href=\"index.php?ind=ad_contents\">{$this->lang['ad_mmanage']}</a></td></tr>
		<tr><td width=\"100%\" class=\"tdblock\"><img class=\"mkicon\" src=\"images/icons/famfamfam/silk/page_add.png\" align=\"left\" title=\"{$this->lang['ad_mcreation']}\" alt=\"{$this->lang['ad_mcreation']}\" />&nbsp;<a class=\"uno\" href=\"index.php?ind=ad_contents&amp;op=contents_main_new\">{$this->lang['ad_mcreation']}</a></td></tr>");

//Modules
$modconfig = array("mod_blog", "mod_gallery", "mod_reviews", "mod_urlobox", "mod_news", "mod_downloads", "mod_topsite", "mod_chat", "mod_quote", "mod_poll", "mod_contact", "mod_recommend");
foreach($modconfig AS $active) {
	$$active = ($mklib->config[$active]) ? "<img class=\"mkicon\" src=\"images/icons/famfamfam/silk/cross.png\" align=\"left\" title=\"{$this->lang['ad_disabled']}\" alt=\"{$this->lang['ad_disabled']}\" />" : "<img class=\"mkicon\" src=\"images/icons/famfamfam/silk/tick.png\" align=\"left\" title=\"{$this->lang['ad_active']}\" alt=\"{$this->lang['ad_active']}\" />";
}

//Are Boardnews.php, rss_simplepie.php, rss.php, or poll.php blocks active?
$query = $DB->query("SELECT file FROM mkp_blocks WHERE file = 'Boardnews.php' AND active = 'checked'");
$bnfile = $DB->get_num_rows($query);	
$query = $DB->query("SELECT file FROM mkp_blocks WHERE (file = 'rss.php' OR file = 'rss_simplepie.php') AND active = 'checked'");
$rssfile = $DB->get_num_rows($query);	
$query = $DB->query("SELECT file FROM mkp_blocks WHERE file = 'poll.php' AND active = 'checked'");
$pollfile = $DB->get_num_rows($query);

$mod_boardnews = (!$bnfile) ? "<img class=\"mkicon\" src=\"images/icons/famfamfam/silk/cross.png\" align=\"left\" title=\"{$this->lang['ad_disabled']}\" alt=\"{$this->lang['ad_disabled']}\" />" : "<img class=\"mkicon\" src=\"images/icons/famfamfam/silk/tick.png\" align=\"left\" title=\"{$this->lang['ad_active']}\" alt=\"{$this->lang['ad_active']}\" />";
$mod_rss = (!$rssfile) ? "<img class=\"mkicon\" src=\"images/icons/famfamfam/silk/cross.png\" align=\"left\" title=\"{$this->lang['ad_disabled']}\" alt=\"{$this->lang['ad_disabled']}\" />" : "<img class=\"mkicon\" src=\"images/icons/famfamfam/silk/tick.png\" align=\"left\" title=\"{$this->lang['ad_active']}\" alt=\"{$this->lang['ad_active']}\" />";
$mod_poll2 = (!$pollfile) ? "<img class=\"mkicon\" src=\"images/icons/famfamfam/silk/cross.png\" align=\"left\" title=\"{$this->lang['ad_disabled']}\" alt=\"{$this->lang['ad_disabled']}\" />" : "<img class=\"mkicon\" src=\"images/icons/famfamfam/silk/tick.png\" align=\"left\" title=\"{$this->lang['ad_active']}\" alt=\"{$this->lang['ad_active']}\" />";

$menu .= $Skin->view_block("{$this->lang['ad_mmod']}", "
		<tr><td width=\"100%\" class=\"tdblock\">$mod_news&nbsp;<a class=\"uno\" href=\"index.php?ind=ad_news\">{$this->lang['news']}</a></td></tr>	
		<tr><td width=\"100%\" class=\"tdblock\">$mod_boardnews&nbsp;<a class=\"uno\" href=\"index.php?ind=ad_boardnews\">{$this->lang['ad_bnews']}</a></td></tr>
		<tr><td width=\"100%\" class=\"tdblock\">$mod_rss&nbsp;<a class=\"uno\" href=\"index.php?ind=ad_rss\">{$this->lang['ad_rssnews']}</a></td></tr>
		<tr><td width=\"100%\" class=\"tdblock\">$mod_blog&nbsp;<a class=\"uno\" href=\"index.php?ind=ad_blog\">{$this->lang['blog']}</a></td></tr>
		<tr><td width=\"100%\" class=\"tdblock\">$mod_chat&nbsp;<a class=\"uno\" href=\"index.php?ind=ad_chat\">{$this->lang['chat']}</a></td></tr>
		<tr><td width=\"100%\" class=\"tdblock\">$mod_urlobox&nbsp;<a class=\"uno\" href=\"index.php?ind=ad_urlo\">{$this->lang['urlobox']}</a></td></tr>
		<tr><td width=\"100%\" class=\"tdblock\">$mod_gallery&nbsp;<a class=\"uno\" href=\"index.php?ind=ad_gallery\">{$this->lang['gallery']}</a></td></tr>
		<tr><td width=\"100%\" class=\"tdblock\">$mod_downloads&nbsp;<a class=\"uno\" href=\"index.php?ind=ad_download\">{$this->lang['download']}</a></td></tr>
		<tr><td width=\"100%\" class=\"tdblock\">$mod_topsite&nbsp;<a class=\"uno\" href=\"index.php?ind=ad_topsite\">{$this->lang['topsite']}</a></td></tr>
		<tr><td width=\"100%\" class=\"tdblock\">$mod_poll2&nbsp;<a class=\"uno\" href=\"index.php?ind=ad_poll\">{$this->lang['ad_mpoll']}</a></td></tr>
		<tr><td width=\"100%\" class=\"tdblock\">$mod_reviews&nbsp;<a class=\"uno\" href=\"index.php?ind=ad_review\">{$this->lang['ad_review']}</a></td></tr>
		<tr><td width=\"100%\" class=\"tdblock\">$mod_quote&nbsp;<a class=\"uno\" href=\"index.php?ind=ad_quote\">{$this->lang['ad_quote']}</a></td></tr>
       	<tr><td width=\"100%\" class=\"tdblock\">$mod_poll&nbsp;<a class=\"uno\" href=\"index.php?ind=ad_voting\">{$this->lang['ad_menu_voting']}</a></td></tr>
        <tr><td width=\"100%\" class=\"tdblock\">$mod_contact&nbsp;<a class=\"uno\" href=\"index.php?ind=ad_contact\">{$this->lang['ad_menu_contact']}</a></td></tr>
        <tr><td width=\"100%\" class=\"tdblock\">$mod_recommend&nbsp;<a class=\"uno\" href=\"index.php?ind=ad_recommend\">{$this->lang['ad_recommend']}</a></td></tr>
		");

//Skin
$menu .= $Skin->view_block("{$this->lang['ad_skin']}", "<tr><td width=\"100%\" class=\"tdblock\"><img class=\"mkicon\" src=\"images/icons/famfamfam/silk/skins.png\" align=\"left\" title=\"{$this->lang['ad_skinm']}\" alt=\"{$this->lang['ad_skinm']}\" />&nbsp;<a class=\"uno\" href=\"index.php?ind=ad_skin\">{$this->lang['ad_skinm']}</a></td></tr>");

//Language
$menu .= $Skin->view_block($this->lang['ad_language'], "<tr><td width=\"100%\" class=\"tdblock\"><img class=\"mkicon\" src=\"images/icons/famfamfam/silk/internationalization.png\" align=\"left\" title=\"{$this->lang['ad_navlbar']}\" alt=\"{$this->lang['ad_navlbar']}\" />&nbsp;<a class=\"uno\" href=\"index.php?ind=ad_langs\">{$this->lang['ad_langm']}</a></td></tr>");

//Menus
$menu .= $Skin->view_block("{$this->lang['ad_navl']}", "<tr><td width=\"100%\" class=\"tdblock\"><img class=\"mkicon\" src=\"images/icons/famfamfam/silk/links.png\" align=\"left\" title=\"{$this->lang['ad_navlbar']}\" alt=\"{$this->lang['ad_navlbar']}\" />&nbsp;<a class=\"uno\" href=\"index.php?ind=ad_nav\">{$this->lang['ad_navlbar']}</a></td></tr>
		<tr><td width=\"100%\" class=\"tdblock\"><img class=\"mkicon\" src=\"images/icons/famfamfam/silk/links.png\" align=\"left\" title=\"{$this->lang['ad_navlmenu']}\" alt=\"{$this->lang['ad_navlmenu']}\" />&nbsp;<a class=\"uno\" href=\"index.php?ind=ad_nav&amp;op=menu\">{$this->lang['ad_navlmenu']}</a></td></tr>");

//Tools
$menu .= $Skin->view_block("{$this->lang['ad_tools']}", "<tr><td width=\"100%\" class=\"tdblock\"><img class=\"mkicon\" src=\"images/icons/famfamfam/silk/phpinfo.png\" align=\"left\" title=\"{$this->lang['ad_phpinfo']}\" alt=\"{$this->lang['ad_phpinfo']}\" />&nbsp;<a class=\"uno\" href=\"index.php?ind=ad_phpinfo\">{$this->lang['ad_phpinfo']}</a></td></tr>");

?>
