<?php
/*
+--------------------------------------------------------------------------
|
|   IPB3 Bridge written by
|   Agron Nikaj (veriu.com)
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

define( 'ROOT_PATH', $MK_PATH.$FORUM_PATH."/" );
define( 'IPB_THIS_SCRIPT', 'public' );
define( 'IPB_THIS_SCRIPT', 'admin' );
define( 'CP_DIRECTORY', 'admin' );
if(defined ('IPS_AREA'))define('IPS_AREA', strstr( $_SERVER['PHP_SELF'], '/' . CP_DIRECTORY ) ? 'admin' : 'public' );
else define('IPS_AREA', strstr( $_SERVER['PHP_SELF'], '/' . CP_DIRECTORY ) ? 'mkportal' : 'public' );
define( 'IN_ACP', IPS_AREA == 'public' ? 0 : 1 );
define('CCS_GATEWAY_CALLED', 1);
require_once ROOT_PATH   . "conf_global.php";
require_once( ROOT_PATH.'initdata.php' );
require_once( ROOT_PATH.'admin/sources/base/ipsRegistry.php' );

$registry=ipsRegistry::instance();
$registry->init();
		
//$DB = $registry->DB(); //on IPB3 this not work
//$DB = $registry->DB()->execute();
//".ipsRegistry::dbFunctions()->getPrefix()." //tables prefix, deafult is ibf_.this should use if is different from default
//$mkportals->getprefix['tabs'] = ipsRegistry::dbFunctions()->getPrefix();
require $MK_PATH."mkportal/include/mk_mySQL.php";
$DB = new db_driver;

$DB->obj['dbname'] = $INFO['sql_database'];
$DB->obj['dbuser'] = $INFO['sql_user'];
$DB->obj['dbpasswd'] = $INFO['sql_pass'];
$DB->obj['dbhost'] = $INFO['sql_host'];

$DB->connect();
$DB->query("SET NAMES 'utf8'");
require $MK_PATH."mkportal/include/class_mkportals.php";
$mkportals = new mkportals_set();
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

//requests timezone ect...
$mkportals->member['timezone'] = (IPSRegistry::request()->request['time_offset'] /3600);

//other requests as session, caches ect..
$mkportals->member['session_id'] = IPSRegistry::member()->session_id;
$mkportals->md5_check = IPSRegistry::member()->form_hash;
$mkportals->lang_cache = IPSRegistry::cache()->getCache('lang_data');
$mkportals->cache['stats'] = IPSRegistry::cache()->getCache('stats');

$mkportals->ipbprefix = ipsRegistry::$settings['sql_tbl_prefix'];

//PM messenger
$mkportals->messenger = IPSRegistry::member()->fetchMemberData();
$mkportals->member['msg_total'] = $mkportals->messenger['msg_count_total'];
$mkportals->member['new_msg'] = $mkportals->messenger['msg_count_new'];

$mkportals->base_url = ROOT_PATH."index.php";
$mkportals->forum_url = $MK_PATH.$FORUM_PATH;

foreach (IPSRegistry::cache()->getCache('lang_data') as $value) {
   if ($value['lang_id'] == $mkportals->member['language']) {
   	$mkportals->member['mk_lang'] = strtolower($value['lang_title']);
   }
}

$mkportals->member['user_new_privmsg'] = $mkportals->member['msg_total']."/".$mkportals->member['new_msg'];
if($mkportals->member['mgroup'] == 4) {
	$mkportals->member['g_access_cp'] = 1;
}
if(!$mkportals->member['id']) {
	$mkportals->member['mgroup'] = 2;
}

if (!$mkportals->member['theme']) {
	foreach( IPSRegistry::getClass('output')->allSkins as $sid => $data ) {
		if ( $data['set_is_default'] ) {
			$mkportals->member['theme'] = $data['set_id'];
					
		}
	}
}

$mkportals->theme_name = IPSRegistry::getClass('output')->allSkins[$mkportals->member['theme']]['set_name'];

if (substr($mkportals->theme_name, 0, 9) == "mkportal2") {
$MK_TEMPLATE = "default";
}
//error_reporting (E_ALL);
// Query count not work for IPB3
//$DB->query_count = 0;

?>