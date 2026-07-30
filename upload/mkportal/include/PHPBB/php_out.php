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

function mkportal_board_out() {
		global $db, $userdata, $Checkmkout, $mkportals, $DB, $Skin, $MK_PATH, $MK_TEMPLATE, $mklib, $ForumOut, $mklib_board, $board_config;
		$MK_PATH = "../";
		require $MK_PATH."mkportal/conf_mk.php";
		$mkportals->base_url = $MK_PATH.$FORUM_PATH."/index.php";
		$mkportals->forum_url = $MK_PATH.$FORUM_PATH;

		require_once $MK_PATH."mkportal/include/mk_mySQL.php";
		$DB = new db_driver;
		$DB->db_connect_id = $db->db_connect_id;
		// assign member information
		$mkportals->member['id'] = intval($userdata['user_id']);
		$mkportals->member['name'] = $userdata['username'];

		if($userdata['user_id'] == -1) {
			$mkportals->member['id'] = "";
		}
		$mkportals->member['last_visit'] = $userdata['user_lastvisit'];
		$mkportals->member['session_id'] = $userdata['session_id'];

		$mkportals->member['user_new_privmsg'] = $userdata['user_unread_privmsg']."/".$userdata['user_new_privmsg'];
		if ($userdata['user_last_privmsg'] > $userdata['user_lastvisit'] && $userdata['user_new_privmsg'] > 0) {
			$mkportals->member['show_popup'] = 1;
		}
		$mkportals->member['email'] = $userdata['user_email'];
		$mkportals->member['timezone'] = $userdata['user_timezone'];
		//$mkportals->member['dateformat'] = $userdata['user_dateformat'];

		//assign member group -> attention don't change this !!
		$mkportals->member['mgroup'] = 3;
		
		// assign to forum admin access to MKportal CPA
		if($userdata['user_level'] == 1) {
			$mkportals->member['g_access_cp'] = 1;
			$mkportals->member['mgroup'] = 1;
		}
		if($userdata['user_id'] == -1) {
			$mkportals->member['mgroup'] = 9;
		}
		if($userdata['user_level'] == 2) {
			$mkportals->member['mgroup'] = 2;
		}
		$mkportals->member['theme'] = $userdata['user_style'];
		if (empty($userdata['user_style'])) {
			$mkportals->member['theme'] = $board_config['default_style'];
		}
		$mkportals->member['mk_lang'] = $userdata['user_lang'];
		if (empty($mkportals->member['mk_lang'])) {
			$mkportals->member['mk_lang'] = $board_config['default_lang'];
		}
		require_once $MK_PATH."mkportal/include/functions.php";
		require_once $MK_PATH."mkportal/include/PHPBB/php_board_functions.php";
		require_once "$mklib->template/tpl_main.php";
		if($MK_OFFLINE && !$mkportals->member['g_access_cp'] && !$mklib->member['g_access_cpa']) {
				$message = $mklib->lang['offline'];
				$mklib->off_line_page($message);
				exit;
		}

		ob_start();
    		eval($ForumOut);
    		$contentspage = ob_get_contents();
     		ob_end_clean();

		$ForumOut = $mklib->printpage_forum("$mklib->forumcs", "$mklib->forumcd", "Forum", $ForumOut);
		print $ForumOut; 

}


?>
