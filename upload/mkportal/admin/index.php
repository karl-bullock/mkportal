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
if (defined ('IN_MKPADMIN') ) die ('Hacking attempt!!!');
else define ( 'IN_MKPADMIN', 1 );
define('MK_SCRIPT', 'admin');

$MK_PATH = "../../";
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
//Meo: Changed in C 0.1 for AEF integration
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

// Portal CP template
if ($mklib->config['cp_tpl']) {
	$mklib->template = $MK_PATH."mkportal/templates/default";
	$mklib->images = $MK_PATH."mkportal/templates/default/images";
}       
require "$mklib->template/tpl_main.php";

$mklib->load_lang("lang_admin.php");

//Offline?
	if($MK_OFFLINE && !$mkportals->member['g_access_cp'] && !$mklib->member['g_access_cpa']) {
		$message = $mklib->lang['offline'].$mklib->lang['offline2'];
		$mklib->off_line_page($message);
		exit;
	}

//Check perms
	if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_access_cpa']) {
		$message = "{$mklib->lang['ad_noperms']}";
		$mklib->error_page($message);
		exit;
	}

	if($mkportals->member['name']=="Guest" OR $mkportals->member['name']=="") {
		$message = "{$mklib->lang['ad_noperms']}";
		$mklib->error_page($message);
		exit;
}

$mkportals->input = $mklib->mkp_input();
$ind = $mkportals->input['ind'];

$switch = array(
                'ad_blocks'       =>   "ad_blocks",
                'ad_blog'         =>   "ad_blog",
                'ad_chat'         =>   "ad_chat",
                'ad_contents'     =>   "ad_contents",
                'ad_download'     =>   "ad_download",
                'ad_gallery'      =>   "ad_gallery",
                'ad_main'         =>   "ad_main",
                'ad_news'         =>   "ad_news",
		        'ad_boardnews'    =>   "ad_boardnews",
                'ad_perms'        =>   "ad_perms",
                'ad_poll'         =>   "ad_poll",
                'ad_quote'        =>   "ad_quote",
                'ad_review'       =>   "ad_review",
                'ad_topsite'      =>   "ad_topsite",
                'ad_urlo'         =>   "ad_urlo",
                'ad_voting'         =>   "ad_voting",
                'ad_categories'         =>   "ad_categories",
                'ad_contact'         =>   "ad_contact",
                'ad_recommend'         =>   "ad_recommend",
		'ad_nav'          =>   "ad_nav",
		'ad_skin'         =>   "ad_skin",
//-- language_management begin
                'ad_langs'        =>   "ad_langs",
//-- language_management end

//-- rss_reader begin
                'ad_rss'          =>   "ad_rss",
//-- rss_reader end
		'ad_approvals'    =>   "ad_approvals",
		'ad_phpinfo'      =>   "ad_phpinfo"
                );

if (!isset($switch[$ind])) {
    $ind = "ad_main";
}

// HTTP_REFERER check
if (! $mklib->referer) {
	if (!strstr($_SERVER['HTTP_REFERER'], "$mklib->mkurl/$mklib->adminpath/index.php") && $ind != "ad_main" && !($ind =='ad_blocks' && $mkportals->input['op'] == 'blocks_titles')) {
		$message = "{$mklib->lang['error_noallow']}";
		$mklib->error_page($message);
		exit;
	}
}

require "{$switch[$ind]}.php";



?>
