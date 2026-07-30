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
if (!defined("IN_MKP")) {
    die ("Sorry !! You cannot access this file directly.");
}
define("IN_MYBB", 1);
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

require $MK_PATH."mkportal/include/class_mkportals.php";
$boarddir = $MK_PATH.$FORUM_PATH."/";

$save_cwd = getcwd();
@chdir($boarddir);
require_once  "./global.php";
require "./inc/config.php";
@chdir($save_cwd);
unset($save_cwd); 

$to_require = $MK_PATH."mkportal/include/mk_mySQL.php";
	require ($to_require);


	$DB = new db_driver;

	$DB->obj['dbname'] = $config['database'];
	$DB->obj['dbuser'] = $config['username'];
	$DB->obj['dbpasswd'] = $config['password'];
	$DB->obj['dbhost'] = $config['hostname'];

	$DB->connect();


$mkportals->base_url = $boarddir."index.php";
$mkportals->forum_url = $MK_PATH.$FORUM_PATH;

// assign member information
$mkportals->member['id'] = intval($mybb->user['uid']);
$mkportals->member['name'] = $mybb->user['username'];
$mkportals->member['name'] = str_replace( "'", "&#39;", $mkportals->member['name'] );

global $session;
$mkportals->member['session_id'] = ($session->sid) ? htmlspecialchars($session->sid) : 0; 

$mkportals->member['last_visit'] = $mybb->user['lastvisit'];
//$mkportals->member['session_id'] = $sc;
$mkportals->member['user_new_privmsg'] = $mybb->user['totalpms']."/".$mybb->user['unreadpms'];

if(($mybb->user['receivepms'] != "no" && $mybb->user['pmpopup'] != "no") && ($mybb->user['pmpopup'] == "new" && $mybb->user['unreadpms'] > 0)) {
	$mkportals->member['show_popup'] = 1;
}
$mkportals->member['timezone'] = $mybb->user['timezone'];
if(!$mkportals->member['timezone']) {
	$mkportals->member['timezone'] = $mybb->settings['timezoneoffset'];
}
$mkportals->member['avatar'] = $mybb->user['avatar'];
$mkportals->member['avatartype'] = $mybb->user['avatartype'];
$mkportals->member['avatardimensions'] = $mybb->user['avatardimensions'];
$mkportals->member['email'] = $mybb->user['email'];

// assign to forum admin access to MKportal CPA
if($mybb->user['usergroup'] == 4) {
	$mkportals->member['g_access_cp'] = 1;
}
$mkportals->member['mgroup'] = intval($mybb->user['usergroup']);

if(!$mkportals->member['id']) {
	$mkportals->member['mgroup'] = 1;
}

$mkportals->member['theme'] = $mybb->user['style'];
	if ($mybb->user['style'] == 0) {
		$mkportals->member['theme'] = $theme['tid'];
	}

$mkportals->member['mk_lang'] = $mybb->user['language'];
if (!$mybb->user['language']) {
		$mkportals->member['mk_lang'] = $mybb->settings['bblanguage'];
	}	

// board interfaced !! Now start mkportal query count
$DB->query_count = 0;


?>
