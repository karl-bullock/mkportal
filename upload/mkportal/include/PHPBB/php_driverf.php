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


define('IN_PHPBB', true);

error_reporting(E_ALL ^ E_NOTICE);

$phpbb_root_path = $MK_PATH.$FORUM_PATH."/";
$mkportals->base_url = $phpbb_root_path."index.php";
$mkportals->forum_url = $MK_PATH.$FORUM_PATH;

include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);
include($phpbb_root_path . 'config.'.$phpEx);
$userdata = session_pagestart($user_ip, PAGE_INDEX);
init_userprefs($userdata);


$to_require = $MK_PATH."mkportal/include/mk_mySQL.php";
	require ($to_require);


	$DB = new db_driver;

	$DB->obj['dbname'] = $dbname;
	$DB->obj['dbuser'] = $dbuser;
	$DB->obj['dbpasswd'] = $dbpasswd;
	$DB->obj['dbhost'] = $dbhost;

	$DB->connect();

// assign member information
$mkportals->member['id'] = intval($userdata['user_id']);
$mkportals->member['name'] = $userdata['username'];
$mkportals->member['name'] = str_replace( "'", "&#39;", $mkportals->member['name'] );
$mkportals->member['ip'] = $user_ip;
$mkportals->member['email'] = $userdata['user_email'];

if($userdata['user_id'] == -1) {
	$mkportals->member['id'] = "";
}
$mkportals->member['last_visit'] = $userdata['user_lastvisit'];
$mkportals->member['session_id'] = $userdata['session_id'];

$mkportals->member['user_new_privmsg'] = $userdata['user_unread_privmsg']."/".$userdata['user_new_privmsg'];
if ($userdata['user_last_privmsg'] > $userdata['user_lastvisit'] && $userdata['user_new_privmsg'] > 0) {
	$mkportals->member['show_popup'] = 1;
}

$mkportals->member['timezone'] = $userdata['user_timezone'];
//$mkportals->member['dateformat'] = $userdata['user_dateformat'];

//assign member group -> attention don't change this !!
$mkportals->member['mgroup'] = 3;

// assign to forum admin access to MKportal CPA
if($userdata['user_level'] == 1) {
	$mkportals->member['g_access_cp'] = 1;
	$mkportals->member['mgroup'] = 1;
}

if($userdata['user_id'] == -1) {
	$mkportals->member['mgroup'] = 9;
}
if($userdata['user_level'] == 2) {
	$mkportals->member['mgroup'] = 2;
}
$mkportals->member['theme'] = $userdata['user_style'];
if (empty($userdata['user_style'])) {
		$mkportals->member['theme'] = $board_config['default_style'];
}
$mkportals->member['mk_lang'] = $userdata['user_lang'];
if (empty($mkportals->member['mk_lang'])) {
	$mkportals->member['mk_lang'] = $board_config['default_lang'];
}


?>
