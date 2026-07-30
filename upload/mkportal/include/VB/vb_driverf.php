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

define('NO_REGISTER_GLOBALS', 1);
define('THIS_SCRIPT', 'index');
define('CSRF_PROTECTION', false);
//define('MK_PATH', $MK_PATH);

error_reporting(E_ALL ^ E_NOTICE);

//Change directory to initialize vB files
$saved_path = getcwd();
@chdir($MK_PATH.$FORUM_PATH);

$phrasegroups = array();
$specialtemplates = array();
$globaltemplates = array();
$actiontemplates = array();

require_once('./global.php');
require_once('./includes/functions_bigthree.php');
require_once('./includes/functions_forumlist.php');

//Change back to the working directory after initializing vB files
@chdir($saved_path);
unset($saved_path);

//OsCommerce module support
if (defined('IN_OSCOMMERCE')) {
	@chdir('mkportal/modules/oscommerce');
}
if (defined('IN_OSCOMMERCEADMIN')) {
	@chdir('mkportal/modules/oscommerce/admin/');
}

//get some basic user information first in the case we need it it DB driver later - SQL Debug feature
$mkportals->member['id'] = intval($vbulletin->userinfo['userid']);
$mkportals->member['name'] = $vbulletin->userinfo['username'];
$mkportals->member['name'] = str_replace( "'", "&#39;", $mkportals->member['name'] );
//$mkportals->member['session_id'] = $vbulletin->session->vars['idhash'];
$mkportals->member['session_id'] = $vbulletin->session->vars['dbsessionhash'];

// assign to forum admin access to MKportal CPA
if($vbulletin->userinfo['usergroupid'] == 6) {
	$mkportals->member['g_access_cp'] = 1;
} else {
	$mkportals->member['g_access_cp'] = 0;
}

if(!$mkportals->member['id']) {
	$mkportals->member['mgroup'] = 1;
} else {
	$mkportals->member['mgroup'] = intval($vbulletin->userinfo['usergroupid']);
}

//load DB driver
require $MK_PATH."mkportal/conf_mk.php";
$boarddir = $MK_PATH.$FORUM_PATH."/";
$mkportals->base_url = $boarddir."index.php";
$mkportals->forum_url = $MK_PATH.$FORUM_PATH;

require ("$boarddir"."includes/config.php");

$to_require = $MK_PATH."mkportal/include/mk_mySQL.php";
require ($to_require);

$DB = new db_driver;

$DB->obj['dbname'] = $config['Database']['dbname'];
$DB->obj['dbuser'] = $config['MasterServer']['username'];
$DB->obj['dbpasswd'] = $config['MasterServer']['password'];
$DB->obj['dbhost'] = $config['MasterServer']['servername'];

if (strtolower($config['Database']['dbtype']) == 'mysql') {
	$DB->db_connect_id =& $vbulletin->db->connection_master;
} else {
	$DB->connect();
}
// needed for vb
$board_functions = "VB/vb_board_functions.php";
//mysql_close($db_connection);

// assign remaining member information
$mkportals->member['last_visit'] = $vbulletin->userinfo['lastvisit'];
$mkportals->member['user_new_privmsg'] = $vbulletin->userinfo['pmtotal']."/".$vbulletin->userinfo['pmunread'];

if($vbulletin->userinfo['pmpopup'] > 1) {
	$mkportals->member['show_popup'] = 1;
}
$mkportals->member['timezone'] = $vbulletin->userinfo['timezoneoffset'];
$mkportals->member['avatar'] = $vbulletin->userinfo['avatar'];
$mkportals->member['email'] = $vbulletin->userinfo['email'];
/*
$mkportals->member['theme'] = $vbulletin->userinfo['styleid'];
	if ($vbulletin->userinfo['styleid'] == 0) {
		$mkportals->member['theme'] = $vboptions['styleid'];
	}
*/
if ($vbulletin->userinfo['styleid']) {
	$mkportals->member['theme'] = $vbulletin->userinfo['styleid'];
} else {
	$mkportals->member['theme'] = $vbulletin->options['styleid'];
}
/*
$boardlang = $vbulletin->userinfo['languageid'];
if ($vbulletin->userinfo['languageid'] == 0) {
		$boardlang = $vboptions['languageid'];
	}
*/
if ($vbulletin->userinfo['languageid']) {
	$boardlang = $vbulletin->userinfo['languageid'];
} else {
	$boardlang = $vbulletin->options['languageid'];
}

$query = $DB->query("SELECT title FROM " . TABLE_PREFIX . "language WHERE languageid = '$boardlang'");
$row = $DB->fetch_row($query);
$mkportals->member['mk_lang'] = $row['title'];


?>
