<?php
/*
+--------------------------------------------------------------------------
|
|   Adapted for IPB3 by Agron Nikaj
|
+--------------------------------------------------------------------------
|
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
@define('MK_SCRIPT', 'forum');

function destroy(&$var) {
    if (is_object($var)) {
        $vars = get_object_vars($var);
        if (is_array($vars)) {
            foreach ($vars as $key => $value) {
                unset($var->{$key});
            }
            $var = "";
        }
        else {
            unset ($var);
        }
    }
    else {
        $var = "";
    }
}

function mkportal_board_out($output) {

        global  $DB, $mkportals, $Skin, $MK_PATH, $MK_TEMPLATE, $mklib, $mklib_board, $MK_TIMEDIFF;

        define ( 'IN_MKP', 1 );
        $MK_PATH = "../";
        require $MK_PATH."mkportal/conf_mk.php";
        $boarddir = $MK_PATH.$FORUM_PATH."/";
//get property of member
$mkportals->member['mgroup'] = IPSRegistry::member()->getProperty('member_group_id');
$mkportals->member['last_visit'] = IPSRegistry::member()->getProperty('last_visit');
$mkportals->member['email'] = IPSRegistry::member()->getProperty('email');
$mkportals->member['theme'] = IPSRegistry::member()->getProperty('skin');
$mkportals->member['skin'] = IPSRegistry::member()->getProperty('skin');
$mkportals->member['id'] = IPSRegistry::member()->getProperty('member_id');
$mkportals->member['language'] = IPSRegistry::member()->getProperty('language');
$mkportals->member['name'] = IPSRegistry::member()->getProperty('members_display_name');
$mkportals->member['avatar'] = IPSMember::buildAvatar( $mkportals->member['id'] );
$mkportals->member['show_popup'] = IPSRegistry::member()->getProperty('msg_show_notification');
//messenger        
$mkportals->messenger = IPSRegistry::member()->fetchMemberData();
$mkportals->member['msg_total'] = $mkportals->messenger['msg_count_total'];
$mkportals->member['new_msg'] = $mkportals->messenger['msg_count_new'];

        $mkportals->base_url = $boarddir."index.php";
        $mkportals->forum_url = $MK_PATH.$FORUM_PATH;
        $mkportals->member['user_new_privmsg'] = $mkportals->member['msg_total']."/".$mkportals->member['new_msg'];
        if($mkportals->member['mgroup'] == 4) {
            $mkportals->member['g_access_cp'] = 1;
        }
//other requests as session, caches ect..
$mkportals->member['session_id'] = IPSRegistry::member()->session_id;
$mkportals->md5_check = IPSRegistry::member()->form_hash;
$mkportals->lang_cache = IPSRegistry::cache()->getCache('lang_data');
$mkportals->cache['stats'] = IPSRegistry::cache()->getCache('stats');

//requests timezone ect...
$mkportals->member['timezone'] = (IPSRegistry::request()->request['time_offset'] /3600);
$mkportals->ipbprefix = ipsRegistry::$settings['sql_tbl_prefix'];
$mkportals->theme_name = IPSRegistry::getClass('output')->allSkins[$mkportals->member['theme']]['set_name'];

if (substr($mkportals->theme_name, 0, 9) == "mkportal2") {
$MK_TEMPLATE = "default";
}
foreach (IPSRegistry::cache()->getCache('lang_data') as $value) {
   if ($value['lang_id'] == $mkportals->member['language']) {
   	$mkportals->member['mk_lang'] = strtolower($value['lang_title']);
   }
}

//database

require $MK_PATH."mkportal/include/mk_mySQL.php";
define( 'ROOT_PATH', $MK_PATH.$FORUM_PATH."/" );
require ROOT_PATH   . "conf_global.php";
$DB = new db_driver;

$DB->obj['dbname'] = $INFO['sql_database'];
$DB->obj['dbuser'] = $INFO['sql_user'];
$DB->obj['dbpasswd'] = $INFO['sql_pass'];
$DB->obj['dbhost'] = $INFO['sql_host'];

$DB->connect();
$DB->query("SET NAMES 'utf8'");
//end database

        $board_name = IPSRegistry::fetchSettings()->settings['board_name'];

	//start mkportal query count and load mkportal	
	

        require $MK_PATH."mkportal/include/functions.php";
        require_once $MK_PATH."mkportal/include/IPB3/ipb3_board_functions.php";
        require_once "$mklib->template/tpl_main.php";
        if($MK_OFFLINE && !$mkportals->member['g_access_cp'] && !$mklib->member['g_access_cpa']) {
            $message = $mklib->lang['offline'];
            $mklib->off_line_page($message);
            exit;
        }

	$mkpsubs = "'ipbwrapper' style='font-size: 12px'";
	if (preg_match("/'ipbwrapper'/is", $output))
		$output = preg_replace( "/'ipbwrapper'/is", $mkpsubs, $output);

	$mkpsubs = "img{ 
            border: 0;
        }";
	$output = preg_replace( "`(\img{(.*?\}))`is", $mkpsubs,$output);

	$mkpsubs = ".divpad{
            padding: 0px;
        }";
	//$output = preg_replace( "`(\.divpad(.*?\}))`is", $mkpsubs,$output);
	$output = str_replace("background: transparent;", " ", $output);

	$output = preg_replace( "`(\<div id=\"logostrip\">(.*?\</div>))`is", "",$output);
	$output = $mklib->printpage_forum("$mklib->forumcs", "$mklib->forumcd", $board_name, $output);
	return $output;

}


?>
