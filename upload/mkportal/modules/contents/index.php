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

$idx = new mk_content;
class mk_content {

	function mk_content() {
		global $mkportals, $DB, $mklib, $Skin, $mklib_board;
		$testo = "";	
		
		if (isset($mkportals->input['skinid']) &&  $mkportals->member['id']) {
			$mklib_board->update_skin($mkportals->input['skinid']);
		}
		if (isset($mkportals->input['langid']) &&  $mkportals->member['id']) {
			$mklib_board->update_lang($mkportals->input['langid']);
		}

		//location
		$mklib_board->store_location("portale");

		$content = "";
		$pid = intval($mkportals->input['pid']);
		if ($pid) {
			$myquery = $DB->query("SELECT id, title, content, file, perms, active FROM mkp_pages WHERE id='$pid'");
			$row = $DB->fetch_row($myquery);
			$title_page = stripslashes($row['title']);
			$testo = stripslashes($row['content']);
			if ($mklib->mkeditor == "BBCODE") {
				$testo = $mklib->decode_bb($testo);
				$testo = $mklib_board->decode_smilies($testo);
			}
			//Is page active? - Admins can view inactive pages
			if(!$row['id'] || (!$row['active'] && (!$mkportals->member['g_access_cp'] && !$mklib->member['g_access_cpa']))) {
				$message = $mklib->lang['error_404'];
				$mklib->error_page($message);
				exit;
			}
		}
		//Check view perms
		$perms = array();
		if ($row['perms']) {
			$perms =  unserialize($row['perms']);
		}

		if (is_array($mkportals->member['mgroup'])) { //C1.2 rc2 - phpBB3 only
				
			//Get all viewer's usergroups
			foreach ($mkportals->member['mgroup'] AS $key => $value) {

				//Check each of viewer's member groups to see if it has block view perms
				if (!in_array($value, $perms)) {
					$viewblock = true;
				}					
				//If any group the viewer belongs to has view perms allow viewing 
				if ($viewblock === true) {
					break;
				}
			}

		} else { //C1.2 rc2 - All other boards
			$viewblock = (!in_array($mkportals->member['mgroup'], $perms)) ? true : false ;
		}

		if ($viewblock !== true) {
		//if (in_array($mkportals->member['mgroup'], $perms)) {
		$message = $mklib->lang['unauth_page'];
			$mklib->error_page($message);
			exit;
		}
		//php page
		if ($row['file']) {
		$file = $mklib->sitepath."mkportal/".$row['file'];
			if (is_file($file)) {
				@require $file;
				$testo ="<tr><td class=\"blocks\">".$content."</td></tr>";
			}
		}
		//html page
		if ($testo) {
			$content = "
				<tr>
				  <td class=\"contents\">
				  ";
			$content .= $testo;
			$content .= "
				  </td>
				</tr>
				    ";
			$blocks .= $Skin->view_block($title_page, $content);
			$mklib->printpage("1", "1", $mklib->sitename.$mklib->lang['tt_sep'].$title_page, $blocks);
		} else {
			$mklib->main_page();
		}
	}
}
?>
