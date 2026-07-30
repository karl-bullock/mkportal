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
		global $db_connection, $context, $modSettings, $user_settings, $ID_MEMBER, $user_info, $sc, $db_prefix, $mkportals, $DB, $Skin, $MK_PATH, $MK_TEMPLATE, $mklib, $mklib_board, $MK_TIMEDIFF;
  		
		define('DBPREFIX', $db_prefix);
		restore_error_handler();
		error_reporting(E_ALL ^ E_NOTICE);	
		
		$MK_PATH = "../";
		//$_SESSION['USER_AGENT'] = $_SERVER['HTTP_USER_AGENT'];
		require $MK_PATH."mkportal/conf_mk.php";
		$checkaction = 0;
		if (array_key_exists('action', $_REQUEST)) {
			if (in_array($_REQUEST['action'], array('dlattach', 'dumpdb', 'findmember', 'helpadmin', 'quotefast', 'spellcheck', '.xml',/* 'help',*/ 'printpage', 'jsoption', 'keepalive'))) {
				$checkaction = 1;
			}
		}
		if($FORUM_VIEW == 1 && !$checkaction) {
  			
			$contentspage = ob_get_contents();
     			ob_end_clean();

			$boarddir = $MK_PATH.$FORUM_PATH."/";
			$mkportals->base_url = $boarddir."index.php";
			$mkportals->forum_url = $MK_PATH.$FORUM_PATH;

			// assign member information
			$mkportals->member['mgroup'] = 0;
			$mkportals->member['id'] = intval($ID_MEMBER);
			$mkportals->member['name'] = $user_info['name'];
			$mkportals->member['email'] = $user_info['email'];

			$mkportals->member['last_visit'] = $user_info['last_login'];
			$mkportals->member['session_id'] = $sc;
			$mkportals->member['user_new_privmsg'] = $user_info['messages']."/".$user_info['unread_messages'];
			$mkportals->member['show_popup'] = 0;
			if (array_key_exists('popup_messages',$context['user'])) {
				if ($context['user']['popup_messages'] == true)  {
					$mkportals->member['show_popup'] = 1;
				}
			}
			$mkportals->member['timezone'] = $user_info['time_offset'];
			$mkportals->member['avatar'] = $user_info['avatar'];

			$mkportals->member['theme'] = $user_info['theme'];
			if (empty($user_info['theme'])) {
				$mkportals->member['theme'] = $modSettings['theme_guests'];
			}
			$mkportals->member['mk_lang'] = $user_info['language'];
			// assign to forum admin access to MKportal CPA
			$mkportals->member['g_access_cp'] = "";
			if($user_info['is_admin']) {
				$mkportals->member['g_access_cp'] = 1;
			}
			if (array_key_exists('ID_GROUP',$user_settings)) {
				$mkportals->member['mgroup'] = intval($user_settings['ID_GROUP']);
				$mkportals->member['mgroup'] = intval($user_settings['ID_GROUP']);
			}
			if(!$ID_MEMBER) {
				$mkportals->member['mgroup'] = 99;
			}
			if($mkportals->member['mgroup'] == 0 && array_key_exists('ID_POST_GROUP',$user_settings)) {
				$mkportals->member['mgroup'] = intval($user_settings['ID_POST_GROUP']);
			}
			if($mkportals->member['mgroup'] == 0) {
				$mkportals->member['mgroup'] = 4;
			}
		
			$to_require = $MK_PATH."mkportal/include/mk_mySQL.php";
			require ($to_require);

			$DB = new db_driver;
			$DB->db_connect_id = $db_connection;

			require_once $MK_PATH."mkportal/include/functions.php";
			require_once $MK_PATH."mkportal/include/SMF2/smf_board_functions.php";
			@require_once "$mklib->template/tpl_main.php";
			if($MK_OFFLINE && !$mkportals->member['g_access_cp'] && !$mklib->member['g_access_cpa']) {
				$message = $mklib->lang['offline'];
				$mklib->off_line_page($message);
				exit;
			}
			$titlepage = "forum";
			if (array_key_exists('page_title', $context)) {
				$titlepage = $context['page_title'];
			}
			
			$contentspage = $mklib->printpage_forum("$mklib->forumcs", "$mklib->forumcd", $titlepage, $contentspage);
			print $contentspage;
		}
}


?>
