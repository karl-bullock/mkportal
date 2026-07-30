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
@define('MK_SCRIPT', 'forum');

function mkportal_board_out($output) {
	global $mkportals, $MK_BOARD, $DB, $Skin, $MK_PATH, $mklib, $mklib_board, $MK_TIMEDIFF, $FORUM_PATH, $conn, $user, $globals;
	
	$MK_PATH = "../";
	require $MK_PATH."mkportal/conf_mk.php";

	if($FORUM_VIEW == 1) {
		$boarddir = $MK_PATH.$FORUM_PATH."/";
		$mkportals->base_url = $boarddir."index.php";
		$mkportals->forum_url = $MK_PATH.$FORUM_PATH;
		$mkportals->member['id'] = intval($user['id']);
		$mkportals->member['name'] = $user['username'];
		$mkportals->member['name'] = str_replace( "'", "&#39;", $mkportals->member['name'] );
		$mkportals->member['session_id'] = $user['sid']; 
		$mkportals->member['last_visit'] = $user['lastlogin_1'];
		$mkportals->member['user_new_privmsg'] = $user['pm']."/".$user['unread_pm'];
		$mkportals->member['timezone'] = $user['timezone'];
		$mkportals->member['avatar'] = $user['avatar'];
		$mkportals->member['avatartype'] = $user['avatar_type'];
		$mkportals->member['avatar_width'] = $user['avatar_width'];
		$mkportals->member['avatar_height'] = $user['avatar_height'];
		$mkportals->member['email'] = $user['email'];
		$mkportals->member['view_anonymous'] = $user['view_anonymous'];
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

		global $config;
		require ($MK_PATH."mkportal/include/mk_mySQL.php");
		$DB = new db_driver;
		$DB->obj['dbname'] = $globals['database'];
		$DB->obj['dbuser'] = $globals['user'];
		$DB->obj['dbpasswd'] = $globals['password'];
		$DB->obj['dbhost'] = $globals['server'];

		$DB->db_connect_id = $conn;

		require_once $MK_PATH."mkportal/include/functions.php";
		require_once $MK_PATH."mkportal/include/AEF/aef_board_functions.php";
		require_once "$mklib->template/tpl_main.php";
		if($MK_OFFLINE && !$mkportals->member['g_access_cp'] && !$mklib->member['g_access_cpa']) {
				$message = $mklib->lang['offline'];
				$mklib->off_line_page($message);
				exit;
		}
		
		//$output = preg_replace( "`(\<div class=\"logo\">(.*?\</div>))`is", "",$output);
		$output = str_replace ("<body onload=\"bodyonload();\">", "", $output);
		$output = $mklib->printpage_forum("$mklib->forumcs", "$mklib->forumcd", "Forum", $output);
		$output = str_replace ("<body onload=\"javascript:GetPos()\">", "<body onload=\"javascript:GetPos(); bodyonload();\">", $output);
	}

	return $output;

}


?>
