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

define('AEF', 1);

$user = array();

error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

require $MK_PATH."mkportal/include/class_mkportals.php";

$boarddir = $MK_PATH.$FORUM_PATH."/";

require $boarddir."universal.php";
require $boarddir."globals.php";

include_once($globals['server_url'].'/dbtables.php');
include_once($globals['mainfiles'].'/functions.php');
require $MK_PATH."mkportal/include/mk_mySQL.php";
$DB = new db_driver;

$DB->obj['dbname'] = $globals['database'];
$DB->obj['dbuser'] = $globals['user'];
$DB->obj['dbpasswd'] = $globals['password'];
$DB->obj['dbhost'] = $globals['server'];
//$DB->connect(); // We leave to connect the board

$conn = mysql_connect($globals['server'], $globals['user'], $globals['password']);
@mysql_select_db($globals['database'], $conn) or die( "Unable to select database");

//connect mkportal db driver to aef db link
$DB->db_connect_id = $conn;

// Some settings are there in the registry
$qresult = $DB->query("SELECT r.*
		FROM ".$dbtables['registry']." r");


if((mysql_num_rows($qresult) > 0)){

	for($i = 0; $i < mysql_num_rows($qresult); $i++){
	
		$row = mysql_fetch_assoc($qresult);
		
		$globals[$row['name']] = $row['regval'];
	
	}

}
@mysql_free_result($qresult);

//Load Session File
include_once($globals['mainfiles'].'/sessions.php');
//Checks a user is logged in
include_once($globals['mainfiles'].'/checklogin.php');
//Is the user Logged In
$logged_in = checklogin();

$mkportals->base_url = $boarddir."index.php";
$mkportals->forum_url = $MK_PATH.$FORUM_PATH;

$mkportals->base_url = $boarddir."index.php";
$mkportals->forum_url = $MK_PATH.$FORUM_PATH;

// assign member information
$mkportals->member['id'] = intval($user['id']);
$mkportals->member['name'] = $user['username'];
$mkportals->member['name'] = str_replace( "'", "&#39;", $mkportals->member['name'] );
$mkportals->member['session_id'] = $user['sid']; 
$mkportals->member['last_visit'] = $user['lastlogin_1'];
$mkportals->member['user_new_privmsg'] = $user['pm']."/".$user['unread_pm'];

// disable the pmpopup notification
/*
if(($mybb->user['receivepms'] != "no" && $mybb->user['pmpopup'] != "no") && ($mybb->user['pmpopup'] == "new" && $mybb->user['unreadpms'] > 0)) {
	$mkportals->member['show_popup'] = 1;
}
*/

$mkportals->member['timezone'] = $user['timezone'];

/* Timezone not yet working in AEF !!
if(!$mkportals->member['timezone']) {
	$mkportals->member['timezone'] = $mybb->settings['timezoneoffset'];
}
*/

$mkportals->member['avatar'] = $user['avatar'];
$mkportals->member['avatartype'] = $user['avatar_type'];
$mkportals->member['avatar_width'] = $user['avatar_width'];
$mkportals->member['avatar_height'] = $user['avatar_height'];
$mkportals->member['email'] = $user['email'];
$mkportals->member['view_anonymous'] = $user['view_anonymous'];

// assign to forum admin access to MKportal CPA
if($user['u_member_group'] == "1") {
	$mkportals->member['g_access_cp'] = 1;
}
$mkportals->member['mgroup'] = intval($user['u_member_group']);

//Guests
if(!$mkportals->member['id']) {
	$mkportals->member['mgroup'] = "-1";
}

$mkportals->member['theme'] = $user['user_theme'];
	if (!$mkportals->member['theme']) {
		$mkportals->member['theme'] = $globals['theme_id'];
	}

// Meo: Added in C 1.2 
$mkportals->member['mk_lang'] = $user['language'];
if (!$mkportals->member['mk_lang']) {
	$mkportals->member['mk_lang'] = $globals['language'];
}

save_session();

// board interfaced !! Now start mkportal query count
$DB->query_count = 0;



// For Debug
/*
print_r($user);
echo "<br><br><br><br>";
print_r($globals);
exit;
*/

?>
