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

define('SMF', 1);
error_reporting(E_ALL ^ E_NOTICE);
$boarddir = $MK_PATH.$FORUM_PATH."/";
$mkportals->base_url = $boarddir."index.php";
$mkportals->forum_url = $MK_PATH.$FORUM_PATH;
// This makes it so headers can be sent!
//define('DBPREFIX', $db_prefix, $smcFunc);
//ob_start();

// Do some cleaning, just in case.
foreach (array('db_character_set', 'cachedir') as $variable)
	if (isset($GLOBALS[$variable]))
		unset($GLOBALS[$variable]);

// Load the settings...
include($boarddir . 'Settings.php');

define('DBPREFIX', $db_prefix);

// Make absolutely sure the cache directory is defined.
if ((empty($cachedir) || !file_exists($cachedir)) && file_exists($boarddir . '/cache'))
	$cachedir = $boarddir . '/cache';

// And important includes.
require_once($sourcedir . '/QueryString.php');
require_once($sourcedir . '/Subs.php');
require_once($sourcedir . '/Errors.php');
require_once($sourcedir . '/Load.php');
require_once($sourcedir . '/Security.php');

// Using an pre-PHP5 version?
if (@version_compare(PHP_VERSION, '5') == -1)
	require_once($sourcedir . '/Subs-Compat.php');

// If $maintenance is set specifically to 2, then we're upgrading or something.
if (!empty($maintenance) && $maintenance == 2)
	db_fatal_error();

// Create a variable to store some SMF specific functions in.
$smcFunc = array();

// Initate the database connection and define some database functions to use.
loadDatabase();

// Load the settings from the settings table, and perform operations like optimizing.
reloadSettings();
// Clean the request variables, add slashes, etc.
cleanRequest();
$context = array();

// Start the session. (assuming it hasn't already been.)
loadSession();

// There's a strange bug in PHP 4.1.2 which makes $_SESSION not work unless you do this...
if (@version_compare(PHP_VERSION, '4.2.0') == -1)
	$HTTP_SESSION_VARS['php_412_bugfix'] = true;

loadUserSettings();

// assign member information
//$mkportals->member['id'] = $id_member;
$mkportals->member['id'] = intval($user_info['id']);
$mkportals->member['name'] = $user_info['name'];
$mkportals->member['email'] = $user_info['email'];

$mkportals->member['last_visit'] = $user_info['last_login'];
$mkportals->member['session_id'] = $sc;
$mkportals->member['user_new_privmsg'] = $user_info['messages']."/".$user_info['unread_messages'];
if ($user_info['unread_messages'] > (isset($_SESSION['unread_messages']) ? $_SESSION['unread_messages'] : 0))  {
			$mkportals->member['show_popup'] = 1;
} else {
		$mkportals->member['show_popup'] = 0;
}
$_SESSION['unread_messages'] = $user_info['unread_messages'];
$mkportals->member['timezone'] = $user_info['time_offset'];

$mkportals->member['avatar'] = $user_info['avatar'];

// assign to forum admin access to MKportal CPA
if($user_info['is_admin']) {
	$mkportals->member['g_access_cp'] = 1;
}
$mkportals->member['mgroup'] = intval($user_settings['ID_GROUP']);
if(!$ID_MEMBER) {
	$mkportals->member['mgroup'] = 99;
}
if($mkportals->member['mgroup'] == 0) {
	$mkportals->member['mgroup'] = intval($user_settings['ID_POST_GROUP']);
}
if($mkportals->member['mgroup'] == 0) {
	$mkportals->member['mgroup'] = 4;
}

$mkportals->member['theme'] = $user_info['theme'];
	if (empty($user_info['theme'])) {
		$mkportals->member['theme'] = $modSettings['theme_guests'];
	}

//mysql_close($db_connection);

$to_require = $MK_PATH."mkportal/include/mk_mySQL.php";
	require ($to_require);


	$DB = new db_driver;

	$DB->obj['dbname'] = $db_name;
	$DB->obj['dbuser'] = $db_user;
	$DB->obj['dbpasswd'] = $db_passwd;
	$DB->obj['dbhost'] = $db_server;

	$DB->connect();

// Remember this URL incase someone doesn't like sending HTTP_REFERER.
	$_SESSION['old_url'] = $_SERVER['REQUEST_URI'];

	// For session check verfication.... don't switch browsers...
	$_SESSION['USER_AGENT'] = $_SERVER['HTTP_USER_AGENT'];

	$mkportals->member['mk_lang'] = $user_info['language'];
	writeLog();

	//echo $modSettings['theme_guests'];
	//echo $language;
	//echo $_SESSION['language'];
	//echo $user_info['language'];
	
	//exit;

?>
