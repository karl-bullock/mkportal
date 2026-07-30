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
	global $mkportals, $DB, $Skin, $MK_PATH, $mklib, $mklib_board, $MK_TIMEDIFF, $FORUM_PATH, $theme, $mybb, $db;
	
	$MK_PATH = "../";
	require $MK_PATH."mkportal/conf_mk.php";
	$NOOUT = strstr($_SERVER['REQUEST_URI'], 'report.php');
	if (strstr($_SERVER['REQUEST_URI'], 'reputation.php') && ($mybb->input['action'] == "add" || $mybb->input['action'] == "do_add")) {
		$NOOUT = 1;
	}

	if($FORUM_VIEW == 1 && $mybb->input['action'] != "buddypopup" && !$mybb->input['popup'] && !$NOOUT) {
		$boarddir = $MK_PATH.$FORUM_PATH."/";
		$mkportals->base_url = $boarddir."index.php";
		$mkportals->forum_url = $MK_PATH.$FORUM_PATH;
		$mkportals->member['id'] = intval($mybb->user['uid']);
		$mkportals->member['name'] = $mybb->user['username'];
		global $session;
		$mkportals->member['session_id'] = ($session->sid) ? htmlspecialchars($session->sid) : 0; 
		$mkportals->member['last_visit'] = $mybb->user['lastvisit'];
		$mkportals->member['user_new_privmsg'] = $mybb->user['totalpms']."/".$mybb->user['unreadpms'];
		if(($mybb->user['receivepms'] != "no" && $mybb->user['pmpopup'] != "no") && ($mybb->user['pmpopup'] == "new" && $mybb->user['unreadpms'] > 0)) {
			$mkportals->member['show_popup'] = 1;
		}
		$mkportals->member['timezone'] = $mybb->user['timezone'];
		if(!$mkportals->member['timezone']) {
			$mkportals->member['timezone'] = $mybb->settings['timezoneoffset'];
		}
		$mkportals->member['avatar'] = $mybb->user['avatar'];
		$mkportals->member['avatartype'] = $mybb->user['avatartype'];
		$mkportals->member['avatardimensions'] = $mybb->user['avatardimensions'];
		$mkportals->member['email'] = $mybb->user['email'];
		if($mybb->user['usergroup'] == 4) {
			$mkportals->member['g_access_cp'] = 1;
		}
		$mkportals->member['mgroup'] = intval($mybb->user['usergroup']);
		if(!$mkportals->member['id']) {
			$mkportals->member['mgroup'] = 1;
		}
		$mkportals->member['theme'] = $mybb->user['style'];
		if ($mybb->user['style'] == 0) {
			$mkportals->member['theme'] = $theme['tid'];
		}
		$mkportals->member['mk_lang'] = $mybb->user['language'];
		if (!$mybb->user['language']) {
				$mkportals->member['mk_lang'] = $mybb->settings['bblanguage'];
		}
		global $config;
		require ($MK_PATH."mkportal/include/mk_mySQL.php");
		$DB = new db_driver;
		$DB->obj['dbname'] = $config['database'];
		$DB->obj['dbuser'] = $config['username'];
		$DB->obj['dbpasswd'] = $config['password'];
		$DB->obj['dbhost'] = $config['hostname'];

		$DB->connect();
		//$DB->db_connect_id = $db->link;

		require_once $MK_PATH."mkportal/include/functions.php";
		require_once $MK_PATH."mkportal/include/MYBB/mybb_board_functions.php";
		require_once "$mklib->template/tpl_main.php";
		if($MK_OFFLINE && !$mkportals->member['g_access_cp'] && !$mklib->member['g_access_cpa']) {
				$message = $mklib->lang['offline'];
				$mklib->off_line_page($message);
				exit;
		}
		$output = preg_replace( "`(\<div class=\"logo\">(.*?\</div>))`is", "",$output);
		$output = str_replace ("<div id=\"container\">", "<div class=\"mkalign1\" style=\"padding: 5px;\">", $output);
		
		$output = $mklib->printpage_forum("$mklib->forumcs", "$mklib->forumcd", "Forum", $output);

		//for MSIE javascript bug in Compose Private Message screen
		if (strstr($_SERVER['REQUEST_URI'], 'private.php') && $mybb->input['action'] == "send") {
			$add_to = "				
<script type=\"text/javascript\" src=\"jscripts/autocomplete.js?ver=121\"></script>
<script type=\"text/javascript\">
	new autoComplete(\"to\", \"xmlhttp.php?action=get_users\", {valueSpan: \"username\"});
</script>
</body>
</html>";
        		$output = preg_replace( "`(\<!-- start: private_send_autocomplete -->(.*?\-->))`is", "", $output);
        		$output = str_replace("</body>\n</html>", $add_to, $output);
		}

	}

	return $output;

}


?>
