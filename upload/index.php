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

define ( 'IN_MKP', 1 );
define('MK_SCRIPT', 'index');

$_SERVER['QUERY_STRING'] = str_replace(array('%3C', '%3E', '<', '>'), array('', '', '', ''), $_SERVER['QUERY_STRING'] );
$_SERVER['PHP_SELF'] = str_replace(array('%3C', '%3E', '<', '>'), array('', '', '', ''), $_SERVER['PHP_SELF'] );

$MK_PATH = "./";
require $MK_PATH."mkportal/conf_mk.php";

switch($MK_BOARD) {
	case 'IPB':
		$driverf = "IPB/ipb_driverf.php";
		$board_functions = "IPB/ipb_board_functions.php";
    break;
    case 'IPB3':
		$driverf = "IPB3/ipb3_driverf.php";
		$board_functions = "IPB3/ipb3_board_functions.php";
    break;
	case 'PHPBB':
    	$driverf = "PHPBB/php_driverf.php";
		$board_functions = "PHPBB/php_board_functions.php";
    break;
//Meo: Added in C 0.1.c for Phpbb3 integration
	case 'PHPBB3':
    	$driverf = "PHPBB3/php_driverf.php";
		$board_functions = "PHPBB3/php_board_functions.php";
    break;
//End
	case 'VB':
    	$driverf = "VB/vb_driverf.php";
		$board_functions = "VB/vb_board_functions.php";
    break;
//Meo: Added in C 0.1 for AEF integration
	case 'AEF':
    	$driverf = "AEF/aef_driverf.php";
		$board_functions = "AEF/aef_board_functions.php";
    break;
//End
    case 'IPB13':
    		$driverf = "IPB13/ipb13_driverf.php";
		$board_functions = "IPB13/ipb13_board_functions.php";
    break;
	case 'MYBB':
    		$driverf = "MYBB/mybb_driverf.php";
		$board_functions = "MYBB/mybb_board_functions.php";
    break;
    case 'SMF2':
    	$driverf = "SMF2/smf_driverf.php";
		$board_functions = "SMF2/smf_board_functions.php";
    break;
	default:
    	$driverf = "SMF/smf_driverf.php";
		$board_functions = "SMF/smf_board_functions.php";
    break;
}

require $MK_PATH."mkportal/include/$driverf";
require $MK_PATH."mkportal/include/functions.php";
require $MK_PATH."mkportal/include/$board_functions";
require "$mklib->template/tpl_main.php";
if ($mklib->config['antibot_chek'] && !$mkportals->member['id']){
session_start();
}

$mkportals->input = $mklib->mkp_input();

//Offline?
if($MK_OFFLINE && !$mkportals->member['g_access_cp'] && !$mklib->member['g_access_cpa']) {
		$mklib->off_line_page();
		exit;
}

$switch = array('blog'         =>   "blog",
                'chat'         =>   "chat",
                'contents'     =>   "contents",
                'downloads'    =>   "downloads",
                'gallery'      =>   "gallery",
                'news'         =>   "news",
                'quote'        =>   "quote",
                'reviews'      =>   "reviews",
                'search'       =>   "search",
                'topsite'      =>   "topsite",
                'urlobox'      =>   "urlobox",
                'poll'      =>   "poll",
                'contact'      =>   "contact",
                'recommend'      =>   "recommend",
		'staff'        =>   "staff",
		'docs'         =>   "docs",
//Meo: Changed in C 0.1 for Ajax integration
		'ajax'         =>   "ajaxout",
// rusmkportal integration ajax
		'rajax'         =>   "rajax",
		'info'         =>   "info"  
                );

//Added by Kimi in C1.2.2 (this is by Mark)
if (is_array($mkportals->input['ind'])) {
$message = $mklib->lang['error_noallow'];
$mklib->error_page($message);
exit;
}

if ($mklib->config['modules']) {
    $mkportals->input['ind'] = $mklib->config['modules'];
}
if (!isset($switch[$mkportals->input['ind']])) {
    $mkportals->input['ind'] = "contents";
}
$modname = "{$switch[$mkportals->input['ind']]}";
if (!$mklib->disablegzip && $mkportals->input['ind'] != "downloads") {
	ob_end_clean();
	@ob_start('ob_gzhandler');
}
require "./mkportal/modules/{$switch[$mkportals->input['ind']]}/index.php";

?>
