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

	$lonlinelist= $mklib_board->forum_link("onlinelist");
	$users= $mklib_board->get_onlinehome($this->lang['guest']);

	$content = "";
	$logged_visible_online = $users[0];
	$logged_hidden_online = $users[1];
	$guests_online = $users[2];
	$online['portale'] = $users[3];
	$online['blog'] = $users[4];
	$online['gallery'] = $users[5];
	$online['urlobox'] = $users[6];
	$online['downloads'] = $users[7];
	$online['news'] = $users[8];
	$online['chat'] = $users[9];
	$online['topsite'] = $users[10];
	$online['reviews'] = $users[11];
	$online['forum'] = $users[12];
	unset ($users);

	
			$total_online_users = $logged_visible_online + $logged_hidden_online + $guests_online;

			$content = "
				<tr>
				  <td width=\"100%\">
				    <table class=\"moduleborder\" cellspacing=\"1\" cellpadding=\"0\" width=\"100%\" align=\"center\" border=\"0\">
				      <tr>
					<td class=\"tdblock\">
					&nbsp;<span class=\"mktxtcontr\">$total_online_users</span> {$this->lang['onlineusers']}: $guests_online &nbsp;{$this->lang['guests']}, $logged_hidden_online &nbsp;{$this->lang['anons']}, $logged_visible_online &nbsp;{$this->lang['noanons']}
					</td>
				      </tr>

				      <tr>
					<td>
					  <table class=\"moduleborder\" cellspacing=\"1\" cellpadding=\"1\" width=\"100%\" align=\"center\" border=\"0\">
					    <tr>
					      <td class=\"modulecell\" width=\"20%\" ><b>{$this->lang['portal_home']}</b></td>
					      <td class=\"modulecell\" width=\"80%\">{$online['portale']}</td>
					    </tr>
					    <tr>
					      <td class=\"modulecell\" width=\"20%\" >{$this->lang['forum']}</td>
					      <td class=\"modulecell\" width=\"80%\">{$online['forum']}</td>
					    </tr>";

			if (!$this->config['mod_blog']) {
					    $content .= "
					    <tr>
					      <td class=\"modulecell\" width=\"20%\" >{$this->lang['blog']}</td>
					      <td class=\"modulecell\" width=\"80%\">{$online['blog']}</td>
					    </tr>";
			}
			if (!$this->config['mod_gallery']) {
					    $content .= "
					    <tr>
					      <td class=\"modulecell\" width=\"20%\" >{$this->lang['gallery']}</td>
					      <td class=\"modulecell\" width=\"80%\">{$online['gallery']}</td>
					    </tr>";
			}
			if (!$this->config['mod_urlobox']) {
					    $content .= "
					    <tr>
					      <td class=\"modulecell\" width=\"20%\" >{$this->lang['urlobox']}</td>
					      <td class=\"modulecell\" width=\"80%\">{$online['urlobox']}</td>
					    </tr>";
			}
			if (!$this->config['mod_downloads']) {
					    $content .= "
					    <tr>
					      <td class=\"modulecell\" width=\"20%\" >{$this->lang['download']}</td>
					      <td class=\"modulecell\" width=\"80%\">{$online['downloads']}</td>
					    </tr>";
			}
			if (!$this->config['mod_news']) {
					    $content .= "
					    <tr>
					      <td class=\"modulecell\" width=\"20%\" >{$this->lang['news']}</td>
					      <td class=\"modulecell\" width=\"80%\">{$online['news']}</td>
					    </tr>";
			}
			if (!$this->config['mod_chat']) {
					    $content .= "
					    <tr>
					      <td class=\"modulecell\" width=\"20%\" >{$this->lang['chat']}</td>
					      <td class=\"modulecell\" width=\"80%\">{$online['chat']}</td>
					    </tr>";
			}
			if (!$this->config['mod_topsite']) {
					    $content .= "
					    <tr>
					      <td class=\"modulecell\" width=\"20%\" >{$this->lang['topsite']}</td>
					      <td class=\"modulecell\" width=\"80%\">{$online['topsite']}</td>
					    </tr>";
			}
			if (!$this->config['mod_reviews']) {
					    $content .= "
					    <tr>
					      <td class=\"modulecell\" width=\"20%\" >{$this->lang['reviews']}</td>
					      <td class=\"modulecell\" width=\"80%\">{$online['reviews']}</td>
					    </tr>";
			}
					    
			$content .= "
					  </table>		
					</td>
				      </tr>
				      <tr>
					<td class=\"tdblock\">
			";
				  
			//Last click
			$content .= $Skin->row_link_block(!$this->config['noicons'] ? "<img class=\"mkicon\" src=\"{$this->images}/atb_members.gif\" align=\"left\" style=\"vertical-align: middle\" alt=\"{$this->lang['lastclick']}\" />" : "", "href=\"{$lonlinelist}\"", $this->lang['lastclick']);

			$content .= "
					</td>
				      </tr>		  
				    </table>
				  </td>
				</tr>
     	    ";

	unset($lonlinelist);
	unset($logged_visible_online);
	unset($logged_hidden_online);
	unset($guests_online);
	unset($online);
	unset($total_online_users);

?>
