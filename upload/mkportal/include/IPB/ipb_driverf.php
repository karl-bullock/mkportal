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
define( 'IPB_THIS_SCRIPT', 'public' );
define( 'IPB_LOAD_SQL'   , 'queries' );

define( 'ROOT_PATH', $MK_PATH.$FORUM_PATH."/" );
define ( 'USE_SHUTDOWN', '0');
define( 'KERNEL_PATH'  , ROOT_PATH.'ips_kernel/' );
define( 'LEGACY_MODE', 0 );
define ( 'USE_MODULES', 1 );
define( 'CUSTOM_ERROR', 1 );
define( 'TRIAL_VERSION', 0 );
@set_magic_quotes_runtime(0);
error_reporting  (E_ERROR | E_WARNING | E_PARSE);
define ( 'IN_IPB', 1 );
define ( 'IN_ACP', 1 );
define ( 'IN_DEV', 0 );

define ( 'SAFE_MODE_ON', 0 );
define ( 'IPB_INIT_DONE', 1 );

$INFO = array();
require_once ROOT_PATH   . "sources/ipsclass.php";
require_once ROOT_PATH   . "sources/classes/class_display.php";
require_once ROOT_PATH   . "sources/classes/class_session.php";
require_once ROOT_PATH   . "sources/classes/class_forums.php";
require_once KERNEL_PATH . "class_converge.php";
require_once ROOT_PATH   . "conf_global.php";
$ipsclass       = new ipsclass();
$ipsclass->vars = $INFO;
$ipsclass->init_db_connection();
$ipsclass->print            =  new display();
$ipsclass->print->ipsclass  =& $ipsclass;
$ipsclass->sess             =  new session();
$ipsclass->sess->ipsclass   =& $ipsclass;
$ipsclass->forums           =  new forum_functions();
$ipsclass->forums->ipsclass =& $ipsclass;
$ipsclass->parse_incoming();
$ipsclass->converge = new class_converge( $ipsclass->DB );
$ipsclass->cache_array = array('rss_calendar', 'rss_export','components','banfilters', 'settings', 'group_cache', 'systemvars', 'skin_id_cache', 'forum_cache', 'moderators', 'stats', 'languages');
$ipsclass->init_load_cache( $ipsclass->cache_array );
$ipsclass->DB->set_debug_mode( $ipsclass->vars['sql_debug'] == 1 ? intval($_GET['debug']) : 0 );
$ipsclass->initiate_ipsclass();
$ipsclass->member     = $ipsclass->sess->authorise();
$ipsclass->lastclick  = $ipsclass->sess->last_click;
$ipsclass->location   = $ipsclass->sess->location;
$ipsclass->session_id = $ipsclass->sess->session_id; // Used in URLs
$ipsclass->my_session = $ipsclass->sess->session_id; // Used in code
$ipsclass->md5_check = $ipsclass->return_md5_check();
if ( $ipsclass->session_type == 'cookie' )
{
	$ipsclass->session_id = "";
	$ipsclass->base_url   = $ipsclass->vars['board_url'].'/index.'.$ipsclass->vars['php_ext'].'?';
}
else
{
	$ipsclass->base_url = $ipsclass->vars['board_url'].'/index.'.$ipsclass->vars['php_ext'].'?s='.$ipsclass->session_id.'&amp;';
}

$ipsclass->js_base_url = $ipsclass->vars['board_url'].'/index.'.$ipsclass->vars['php_ext'].'?s='.$ipsclass->session_id.'&';
$ipsclass->skin_id = $ipsclass->skin['_setid'];

$ipsclass->vars['img_url']       = 'style_images/' . $ipsclass->skin['_imagedir'];
$ipsclass->vars['AVATARS_URL']   = 'style_avatars';
$ipsclass->vars['EMOTICONS_URL'] = 'style_emoticons/<#EMO_DIR#>';
$ipsclass->vars['mime_img']      = 'style_images/<#IMG_DIR#>';
if ($ipsclass->vars['default_language'] == "")
{
	$ipsclass->vars['default_language'] = 'en';
}
require $MK_PATH."mkportal/include/mk_mySQL.php";

$DB = new db_driver;

$DB->obj['dbname'] = $INFO['sql_database'];
$DB->obj['dbuser'] = $INFO['sql_user'];
$DB->obj['dbpasswd'] = $INFO['sql_pass'];
$DB->obj['dbhost'] = $INFO['sql_host'];

$DB->connect();
$std      =& $ipsclass;
$ibforums =& $ipsclass;
$forums   =& $ipsclass->forums;
$print    =& $ipsclass->print;
$sess     =& $ipsclass->sess;

require $MK_PATH."mkportal/include/class_mkportals.php";
$mkportals = new mkportals_set();

$mkportals->member =& $ipsclass->member;
$mkportals->base_url = ROOT_PATH."index.php";
$mkportals->forum_url = "/".$FORUM_PATH;

foreach ($ibforums->cache['languages'] as $value) {
   if ($value['ldir'] == $mkportals->member['language']) {
   	$mkportals->member['mk_lang'] = strtolower($value['lname']);
   }
}

$mkportals->member['user_new_privmsg'] = $mkportals->member['msg_total']."/".$mkportals->member['new_msg'];
if($mkportals->member['mgroup'] == 4) {
	$mkportals->member['g_access_cp'] = 1;
}
if(!$mkportals->member['id']) {
	$mkportals->member['mgroup'] = 2;
}
$mkportals->member['theme'] = $mkportals->member['skin'];
if (!$mkportals->member['theme']) {
	foreach( $ibforums->cache['skin_id_cache'] as $sid => $data ) {
		if ( $data['set_default'] ) {
			$mkportals->member['theme'] = $data['set_skin_set_id'];
					
		}
	}
}

$mkportals->theme_name = $ibforums->cache['skin_id_cache'][$mkportals->member['theme']]['set_name'];

if (substr($mkportals->theme_name, 0, 9) == "mkportal2") {
$MK_TEMPLATE = "default";
}

$mkportals->member['timezone'] = ($std->get_time_offset() /3600);
$mkportals->member['name'] = $ipsclass->member['members_display_name'];

// board interfaced !! Now start mkportal query count
$DB->query_count = 0;

?>