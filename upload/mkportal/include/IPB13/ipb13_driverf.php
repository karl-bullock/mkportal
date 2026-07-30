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
define ( 'IN_IPB', 1 );
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

// php5 with register_long_arrays off
if (!isset($HTTP_SERVER_VARS) && isset($_SERVER)) {
	$HTTP_GET_VARS = &$_GET;
	$HTTP_POST_VARS = &$_POST;
	$HTTP_ENV_VARS = &$_ENV;
	$HTTP_SERVER_VARS = &$_SERVER;
	$HTTP_COOKIE_VARS = &$_COOKIE;
	$HTTP_POST_FILES = &$_FILES;
}

require $MK_PATH."mkportal/include/class_mkportals.php";

$boarddir = $MK_PATH.$FORUM_PATH."/";

$INFO = array();
require $boarddir."conf_global.php";
require $MK_PATH.$FORUM_PATH."/sources/Drivers/mySQL.php";


$ibforums->vars['AVATARS_URL']     = $INFO['html_url'] . '/avatars';
$ibforums->vars['EMOTICONS_URL']   = $INFO['html_url'] . '/emoticons';
$ibforums->vars['mime_img']        = $INFO['html_url'] . '/mime_types';

$DB = new db_driver;

$DB->obj['sql_database']     = $INFO['sql_database'];
$DB->obj['sql_user']         = $INFO['sql_user'];
$DB->obj['sql_pass']         = $INFO['sql_pass'];
$DB->obj['sql_host']         = $INFO['sql_host'];
$DB->obj['sql_tbl_prefix']   = $INFO['sql_tbl_prefix'];
$DB->connect();

$mkportals = new mkportals_set();

$mkportals->base_url = $boarddir."index.php";
$mkportals->forum_url = $MK_PATH.$FORUM_PATH;

require $MK_PATH.$FORUM_PATH."/sources/functions.php";

$std    = new FUNC;
$sess   = new session();

$ibforums->input['IP_ADDRESS'] = $_SERVER['REMOTE_ADDR'];
$mkportals->member = $sess->authorise();
$ibforums->lastclick  = $sess->last_click;
$ibforums->location   = $sess->location;
$ibforums->session_id = $sess->session_id;
$mkportals->session_id = $ibforums->session_id;

if(!$mkportals->member['msg_total']) {
	$mkportals->member['msg_total'] = 0;
}
if(!$mkportals->member['new_msg']) {
	$mkportals->member['new_msg'] = 0;
}

$mkportals->member['user_new_privmsg'] = $mkportals->member['msg_total']."/".$mkportals->member['new_msg'];
if($mkportals->member['mgroup'] == 4) {
	$mkportals->member['g_access_cp'] = 1;
}
if(!$mkportals->member['id']) {
	$mkportals->member['mgroup'] = 2;
}
$mkportals->member['theme'] = $mkportals->member['skin'];


if (isset($mkportals->member['theme'])) {
	$DB->query("SELECT  sname from ibf_skins where sid = '{$mkportals->member['theme']}'");
}
else {
	$DB->query("SELECT sname from ibf_skins where default_set = '1'");
}
$r = $DB->fetch_row();
$mkportals->theme_name = $r['sname'];

if (substr($mkportals->theme_name, 0, 8) == "mkportal") {
	$MK_TEMPLATE = "default";
}

if(!$mkportals->member['language']) {
	$mkportals->member['language'] = $INFO['default_language'];
}
if(!$mkportals->member['language']) {
	$mkportals->member['language'] = "en";
}

$DB->query("SELECT ldir, lname from ibf_languages");
while ( $r = $DB->fetch_row() ){
  if ($mkportals->member['language'] == $r['ldir'])  {
  	$mkportals->member['mk_lang'] = $r['lname'];
  }
}
unset ($r);
//altrimenti non funziona get_time_offset.
$ibforums->member['id'] = $mkportals->member['id'];
$ibforums->vars['time_offset'] = $INFO['time_offset'];
$ibforums->member['time_offset'] = $mkportals->member['time_offset'];
$ibforums->vars['time_adjust'] =  $INFO['time_adjust'];
$ibforums->member['dst_in_use'] = $mkportals->member['dst_in_use'];
$mkportals->member['timezone'] = ($std->get_time_offset() /3600);

$ibforums->vars['avatars_on'] = 1;

// board interfaced !! Now start mkportal query count
$DB->query_count = 0;

?>
