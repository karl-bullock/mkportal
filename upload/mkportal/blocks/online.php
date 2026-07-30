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
	$users= $mklib_board->get_onlineblock();

	$logged_visible_online = $users[0];
	$logged_hidden_online = $users[1];
	$guests_online = $users[2];
	$online_userlist .= "<br>";
	$online_userlist .= $users[3];
	unset ($users);


			$total_online_users = $logged_visible_online + $logged_hidden_online + $guests_online;

	

	
	$content = "
				<tr>
				  <td width=\"100%\" id=\"onlines\">
				    <table border=\"0\" width=\"100%\" cellpadding=\"1\" cellspacing=\"1\">
		<p align=\"left\">		
	<img src=\"$this->images/noactiv.png\" border=\"0\" align=\"middle\" alt=\"\" /></a>&nbsp;{$this->lang['guests']}:&nbsp;<b>$guests_online</b><br />
	<img src=\"$this->images/guestactiv.png\" border=\"0\" align=\"middle\" alt=\"\" /></a>&nbsp;{$this->lang['anons']}:&nbsp;<b>$logged_hidden_online</b><br />
	<img src=\"$this->images/activ.png\" border=\"0\" align=\"middle\" alt=\"\" /></a>&nbsp;{$this->lang['noanons']}:&nbsp;<b>$logged_visible_online</b>		
<tr>
<td width=\"100%\">
	<a id=\"cont\" OnClick=\"SwitchMenu('user')\"><img title=\"{$this->lang['block_online_pod']}\" src=\"$this->images/plus.gif\"></a> 
		<a href=\"#\" onclick=\"ajax_showPop('/index.php?ind=ajax&amp;act=sitemon', 1);return false\"><img src=\"$this->images/load.png\" border=\"0\" align=\"middle\" alt=\"{$this->lang['block_online_ho']}\" /></a>
	<a href=\"#\" onclick=\"refresh_online();return false\"><img title=\"{$this->lang['block_online_ref']}\" src=\"$this->images/refresh.png\"></a> 
	&nbsp;<img title=\"{$this->lang['block_online_all']}\" src=\"$this->images/group.png\"></a>{$this->lang['block_online_all']}:<span class=\"mktxtcontr\">$total_online_users</span>
	
	<div id=\"user\">
&nbsp;&nbsp;
$online_userlist
	
</div>

		             
</td>
		</tr>  </table>
				  </td>
				</tr>	";

?>
