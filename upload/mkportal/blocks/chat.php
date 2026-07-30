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

$content = "";

		$link_user = $mklib_board->forum_link("profile");
		$dead_users = time() - (60*3);

		//$DB->query("DELETE FROM mkp_chat WHERE run_time < ".$dead_users." ");
		$DB->query("SELECT id, nick, run_time FROM mkp_chat WHERE run_time > $dead_users ");

		$sep = ",";
		$totuser = $DB->get_num_rows();
		if ( $totuser ==  1){
			$ut = $this->lang['user'];
		}

		while ($chat_user = $DB->fetch_row()) {
			$link ="<a class=\"uno\" href=\"$link_user={$chat_user['id']}\">{$chat_user['nick']}</a>$sep";
				if ($chat_user['id'] ==0){
					$link = "{$this->lang['guest']}$sep";
				}
   			$activeu .= $link." ";
		}
		if ( $totuser ==  0){
			//$activeu = "<tr><td class=\"tdglobal\">".$this->lang['chat_nobody']."</td></tr>";
			$activeu = "{$this->lang['chat_nobody']}";
		}
		$ut = $this->lang['users'];
		if ( $totuser ==  1){
			$ut = $this->lang['user'];
		}
		$activeu = substr($activeu, 0, (strlen($activeu) -2));
			$content = "
				<tr>
				  <td width=\"100%\">
				    <table border=\"0\" width=\"100%\" cellpadding=\"1\" cellspacing=\"1\">
				      <tr>
					<td class=\"tdblock\">
					<span class=\"mktxtcontr\">$totuser</span> <b>$ut {$this->lang['chat_in']}</b>
					</td>
				      </tr>
				      <tr>
					<td class=\"tdglobal\">
					$activeu
					</td>
				      </tr>
				    </table>
				  </td>
				</tr>
				<tr>
				  <td class=\"tdblock\">";		
			$content .= $Skin->row_link_block(!$this->config['noicons'] ? "<img class=\"mkicon\" src=\"$this->images/atb_chat.gif\" style=\"vertical-align: middle\" alt=\"{$this->lang['chat_enter']}\" />" : "", "href=\"$this->siteurl/index.php?ind=chat\" target=\"_blank\"", $this->lang['chat_enter']);
			$content .= "
				  </td>
				</tr>            
     	 	";

			if(!$mkportals->member['g_access_cp'] && !$this->member['g_access_chat']) {
			$content = "
				<tr>
				  <td class=\"tdblock\" align=\"center\">
				  {$this->lang['chat_noallow']}
				  </td>
				</tr>
			";
	}
		unset($link_user);
		unset($dead_users);
		unset($sep);
		unset($totuser);
		unset($chat_user);
		unset($link);
		unset($ut);



?>
