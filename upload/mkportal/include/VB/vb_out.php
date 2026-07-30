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
	global $DB_site, $vboptions, $_REQUEST, $vbulletin;
	global $mkportals, $DB, $Skin, $MK_PATH, $mklib, $mklib_board, $MK_TIMEDIFF, $FORUM_PATH;
	
	$MK_PATH = "../";
	require $MK_PATH."mkportal/conf_mk.php";
	$CINQ = strstr($_SERVER['REQUEST_URI'], 'articles.php');
	if($FORUM_VIEW == 1 && !$CINQ && $_REQUEST['do'] != "im" && $_REQUEST['do'] != "getsmilies" && THIS_SCRIPT != "newattachment" && THIS_SCRIPT != "arcade" && THIS_SCRIPT != "vbchat" && THIS_SCRIPT != "printthread") { 
		$boarddir = $MK_PATH.$FORUM_PATH."/";
		$mkportals->base_url = $boarddir."index.php";
		$mkportals->forum_url = $MK_PATH.$FORUM_PATH;

		//get some basic user information first in the case we need it it DB driver later - SQL Debug feature
		$mkportals->member['id'] = intval($vbulletin->userinfo['userid']);
		$mkportals->member['name'] = $vbulletin->userinfo['username'];
		$mkportals->member['name'] = str_replace( "'", "&#39;", $mkportals->member['name'] );
//		$mkportals->member['session_id'] = $vbulletin->session->vars['idhash'];
		$mkportals->member['session_id'] = $vbulletin->session->vars['dbsessionhash'];

		// assign to forum admin access to MKportal CPA
		if($vbulletin->userinfo['usergroupid'] == 6) {
			$mkportals->member['g_access_cp'] = 1;
		} else {
			$mkportals->member['g_access_cp'] = 0;
		}

		if(!$mkportals->member['id']) {
			$mkportals->member['mgroup'] = 1;
		} else {
			$mkportals->member['mgroup'] = intval($vbulletin->userinfo['usergroupid']);
		}

		//load DB driver
		require ($MK_PATH."mkportal/include/mk_mySQL.php");

		$DB = new db_driver;
		
		$DB->obj['dbname'] = $vbulletin->config['Database']['dbname'];
		$DB->obj['dbuser'] = $vbulletin->config['MasterServer']['username'];
		$DB->obj['dbpasswd'] = $vbulletin->config['MasterServer']['password'];
		$DB->obj['dbhost'] = $vbulletin->config['MasterServer']['servername'];

		if (strtolower($vbulletin->config['Database']['dbtype']) == 'mysql') {
			//$DB->db_connect_id =& $vbulletin->db->connection_write; // < vB version 3.6
			$DB->db_connect_id =& $vbulletin->db->connection_master; // >= vB version 3.6
		} else {
			$DB->connect();
		}

		// assign remaining member information
		$mkportals->member['last_visit'] = $vbulletin->userinfo['lastvisit'];
		$mkportals->member['user_new_privmsg'] = $vbulletin->userinfo['pmtotal']."/".$vbulletin->userinfo['pmunread'];
		//$mkportals->member['show_popup'] = $vbulletin->userinfo['pmpopup'];
		$mkportals->member['timezone'] = $vbulletin->userinfo['timezoneoffset'];
//		$mkportals->member['avatar'] = $user_info['avatar'];
		$mkportals->member['avatar'] = $vbulletin->userinfo['avatar'];
		$mkportals->member['email'] = $vbulletin->userinfo['email'];

		/*
		$mkportals->member['theme'] = $vbulletin->userinfo['styleid'];
			if ($vbulletin->userinfo['styleid'] == 0) {
				$mkportals->member['theme'] = $vboptions['styleid'];
			}
		*/
		if ($vbulletin->userinfo['styleid']) {
			$mkportals->member['theme'] = $vbulletin->userinfo['styleid'];
		} else {
			$mkportals->member['theme'] = $vbulletin->options['styleid'];
		}
		/*
		$boardlang = $vbulletin->userinfo['languageid'];
		if ($vbulletin->userinfo['languageid'] == 0) {
				$boardlang = $vboptions['languageid'];
			}
		*/
		if ($vbulletin->userinfo['languageid']) {
			$boardlang = $vbulletin->userinfo['languageid'];
		} else {
			$boardlang = $vbulletin->options['languageid'];
		}

		$query = $DB->query("SELECT title FROM " . TABLE_PREFIX . "language WHERE languageid = '$boardlang'");
		$row = $DB->fetch_row($query);
		$mkportals->member['mk_lang'] = $row['title'];

		require_once $MK_PATH."mkportal/include/functions.php";
		require_once $MK_PATH."mkportal/include/VB/vb_board_functions.php";
		require_once "{$mklib->template}/tpl_main.php";
		if($MK_OFFLINE && !$mkportals->member['g_access_cp'] && !$mklib->member['g_access_cpa']) {
				$message = $mklib->lang['offline'];
				$mklib->off_line_page($message);
				exit;
		}
		$output = preg_replace( "`(\<!-- logo -->(.*?\</table>))`is", "", $output);
		$output = str_replace ("<div style=\"padding:0px 25px 0px 25px\" align=\"left\">", "<div style=\"width:100%\">", $output);
		$output = str_replace ("margin: 5px 10px 10px 10px;", "", $output);

		//vBulletin_init() javascript must be outside all table tags. It must be the last thing before the closing body tag.
		$output = preg_replace( "`<script type=\"text/javascript\">(\s*?)<!--(\s*?)//(\s*?)Main vBulletin Javascript Initialization(\s*?)vBulletin_init\(\);(\s*?)//-->(\s*?)</script>`i", "", $output);
		
		$output = $mklib->printpage_forum("$mklib->forumcs", "$mklib->forumcd", "Forum", $output);
		$pos = strpos($output, "vbulletin_editor.js");
		if ($pos) {
			$output = str_replace ("onload=\"javascript:GetPos()\"", "", $output);
		}
		//print vBulletin_init() before MKPortal closing body tag
		$output = str_replace ("</body>", "<script type=\"text/javascript\">\n<!--\n\t// Main vBulletin Javascript Initialization\n\tvBulletin_init();\n//-->\n</script>\n\n</body>\n", $output);
	}
	return $output;

}


?>
