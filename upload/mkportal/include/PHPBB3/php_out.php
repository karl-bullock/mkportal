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
	global $db, $user, $mkportals, $DB, $Skin, $MK_PATH, $MK_TEMPLATE, $mklib, $mklib_board, $config, $mode;

	$MK_PATH = "../";
	require $MK_PATH."mkportal/conf_mk.php";

	if($FORUM_VIEW == 1 && $mode != 'popup') {
		$mkportals->base_url = $MK_PATH.$FORUM_PATH."/index.php";
		$mkportals->forum_url = $MK_PATH.$FORUM_PATH;
		require($mkportals->forum_url . "/config.php");

		$output = ob_get_contents();
		ob_end_clean();

		require_once $MK_PATH."mkportal/include/mk_mySQL.php";
		$DB = new db_driver;
		$DB->obj['dbname'] = $dbname;
		$DB->obj['dbuser'] = $dbuser;
		$DB->obj['dbpasswd'] = $dbpasswd;
		$DB->obj['dbhost'] = $dbhost;
		$DB->connect();
		$DB->query("SET NAMES 'utf8'");
		$mkportals->member['id'] = intval($user->data['user_id']);
		$mkportals->member['name'] = $user->data['username'];
		$mkportals->member['name'] = str_replace( "'", "&#39;", $mkportals->member['name'] );
		$mkportals->member['ip'] = $user->ip;
		$mkportals->member['email'] = $user->data['user_email'];
		if($user->data['user_id'] == ANONYMOUS || $user->data['is_bot']) {
			$mkportals->member['id'] = '';
		}
		$mkportals->member['last_visit'] = $user->data['session_last_visit'];
		$mkportals->member['session_id'] = $user->session_id;
		$mkportals->member['user_new_privmsg'] = $user->data['user_new_privmsg'].'/'.$user->data['user_unread_privmsg'];
		/* This code creates 2 PM popups in forum view
		To use the MKPortal popup globally the phpBB3 includes/functions.php file will have to be edited		
		if (isset($user->data['is_registered']) && $user->data['is_registered'] && $user->optionget('popuppm') && $user->data['user_last_privmsg'] > $user->data['session_last_visit'] && $user->data['user_new_privmsg'] > 0) {
			$mkportals->member['show_popup'] = 1;
		}
		*/
		$mkportals->member['timezone'] = ($user->data['user_id'] != ANONYMOUS) ? $user->data['user_timezone']:$config['board_timezone'];
		$mkportals->member['mgroup'] = intval($user->data['group_id']);
		if($mkportals->member['mgroup'] == 5) {
			$mkportals->member['g_access_cp'] = 1;
		}
		$mkportals->member['theme'] = $user->theme['theme_id'];
		if (empty($user->theme['theme_id'])) {
			$mkportals->member['theme'] = $config['default_style'];
		}
		$mkportals->member['theme_path'] = $user->theme['template_path'];
		$mkportals->member['mk_lang'] = $user->lang_name;
		if (empty($mkportals->member['mk_lang'])) {
			$mkportals->member['mk_lang'] = $config['default_lang'];
		}

		require_once $MK_PATH."mkportal/include/functions.php";
		require_once $MK_PATH."mkportal/include/PHPBB3/php_board_functions.php";
		require_once "$mklib->template/tpl_main.php";
		if($MK_OFFLINE && !$mkportals->member['g_access_cp'] && !$mklib->member['g_access_cpa']) {
				$message = $mklib->lang['offline'];
				$mklib->off_line_page($message);
				exit;
		}
		$output = $mklib->printpage_forum("$mklib->forumcs", "$mklib->forumcd", "Forum", $output);
		$output = str_replace ("<body onload=\"javascript:GetPos()\">", "<body id=\"phpbb\" class=\"section-index ltr\" style=\"padding: 0px;\" onload=\"javascript:GetPos();\">", $output);
		$output = str_replace ("<div id=\"wrap\">", "<div id=\"wrap\" style=\"padding: 0 5px;\" >", $output);

		$output = str_replace ("<div id=\"menubar\">", "<div id=\"menubar\" style=\"margin: 5px 5px 0 5px;\" >", $output);
		$output = str_replace ("<div id=\"wrapcentre\">", "<div id=\"wrapcentre\" style=\"margin: 0 5px 0 5px;\" >", $output);
		$output = str_replace ("<div id=\"datebar\">", "<div id=\"datebar\" style=\"margin: 10px 5px 0 5px;\" >", $output);
//datebar
		$output = str_replace ("</head>", "<style type=\"text/css\">td { padding: 0; }</style>\n\n</head>", $output);
		
		$output = preg_replace( "`(\<div id=\"logodesc\">(.*?\</div>))`is", "",$output);
		
		print $output; 
	}
}


?>
