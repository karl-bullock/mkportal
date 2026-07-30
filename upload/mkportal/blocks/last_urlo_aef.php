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

global $MK_BOARD, $dbtables;
//global $mkportals, $DB, $mklib, $dbtables, $mklib_board;

$limit = $this->config['urlo_block'];
if (!$limit) {
	$limit = 5;
}
$content = "";

if($MK_BOARD == 'AEF') {

	$link_user = $mklib_board->forum_link("profile");

		$DB->query("SELECT sh.shid, sh.shuid, sh.shtext, u.username FROM ".$dbtables['shouts']." sh
					LEFT JOIN ".$dbtables['users']." u ON (sh.shuid = u.id)
					ORDER BY sh.shid DESC LIMIT $limit");
		while( $urlo = $DB->fetch_row() ) {
			
		$idu = $urlo['shid'];
		$umes = "";
		$message = str_replace("<br />", " ", $urlo['shtext']);
		$message = str_replace("<br>", " ", $message);
		$message = $this->decode_bb($message);
		$message = str_replace("\n", " ", $message);
		$message = strip_tags($message);
		$message = explode(" ", $message);
		foreach ($message as $value) {
			if (strlen($value) > 20 && !strpos($value, "\'http") && !strpos($value, "\"http") && !strpos($value, "emo")) {
				 $value = substr($value, 0, 20);
			}
   			$umes .= $value." ";
		}
		$umes = $this->decode_bb($umes);		
		$content.= "
				<tr>
				  <td class=\"tdblock\">{$this->lang['from']}: <a href=\"{$mkportals->forum_url}/index.php?mid={$urlo['shuid']}\" class=\"uno\">{$urlo['username']}</a>
				  </td>
				</tr>
				";
		$content .= "
				<tr>
				  <td class=\"tdglobal\">$umes
				  </td>
				</tr>
				";
	}

	if($idu == NULL) {
			$content = "
				<tr>
				  <td class=\"tdblock\" align=\"center\">
				  {$this->lang['urlo_not']}
				  </td>
				</tr>
				";
	}

	if(!$mkportals->member['g_access_cp'] && !$this->member['g_access_urlobox']) {
			$content = "
				<tr>
				  <td class=\"tdblock\" align=\"center\">
				  {$this->lang['urlo_unauth']}
				  </td>
				</tr>
				";
	}
	unset($link_user);
	unset($urlo);
	unset($umes);
    	unset($message);

} else {
			$content = "
				<tr>
				  <td class=\"tdblock\" align=\"center\">
				  {$this->lang['urlo_aefonly']}
				  </td>
				</tr>
				";
}

?>
