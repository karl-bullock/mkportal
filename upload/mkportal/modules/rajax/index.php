<?php
/*
+--------------------------------------------------------------------------
|   RusMKPortal.ru
|   ========================================
|   by Support
|   Copyright (c) 2007-2009 rusmkportal.ru
|   http://www.rusmkportal.ru
|   Email: rusmkportal@mail.ru
|   Запрещается распростронять данный код, без согласия его автора
+---------------------------------------------------------------------------
*/
if (!defined("IN_MKP")) {
    die ("Sorry !! You cannot access this file directly.");
}

	global $mkportals, $DB, $mklib, $Skin, $mklib_board;


    		switch($mkportals->input['op']) {
    			case 'refresh_online':
    				refresh_online();
    			break;
				case 'rating_process':
    				rating_process();
    			break;
    		/*	case 'click_site':
    				$this->click_site();
    			break;
				case 'submit_rate':
    				$this->submit_rate();
    			break;
				case 'add_rate':
    				$this->add_rate();
    			break;*/
    			
    		}
	

	function refresh_online() {
		global $mkportals, $DB, $std, $print, $mklib, $Skin, $mklib_board;

@header("Content-type: text/html; charset={$mklib->charset}");
@header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
@header('Last-Modified: '.@gmdate('D, d M Y H:i:s').' GMT');
@header('Cache-Control: no-store, no-cache, must-revalidate');
@header('Cache-Control: post-check=0, pre-check=0', false);
@header('Pragma: no-cache');
$users= $mklib_board->get_onlineblock();

	$logged_visible_online = $users[0];
	$logged_hidden_online = $users[1];
	$guests_online = $users[2];
	$online_userlist .= "<br>";
	$online_userlist .= $users[3];
	unset ($users);
	$total_online_users = $logged_visible_online + $logged_hidden_online + $guests_online;
	
	echo "<tr><td width=\"100%\">
				      <tr>
					<td class=\"tdglobal\">
	<p align=\"left\">
	<img src=\"$mklib->images/noactiv.png\" border=\"0\" align=\"middle\" alt=\"\" /></a>&nbsp;{$mklib->lang['guests']}:&nbsp;<b>$guests_online</b><br />
	<img src=\"$mklib->images/guestactiv.png\" border=\"0\" align=\"middle\" alt=\"\" /></a>&nbsp;{$mklib->lang['anons']}:&nbsp;<b>$logged_hidden_online</b><br />
	<img src=\"$mklib->images/activ.png\" border=\"0\" align=\"middle\" alt=\"\" /></a>&nbsp;{$mklib->lang['noanons']}:&nbsp;<b>$logged_visible_online</b><br />
					</td>
				      </tr>
				  </td>
				</tr>				
<tr><td width=\"100%\">
	<a id=\"cont\" OnClick=\"SwitchMenu('user')\"><img title=\"{$mklib->lang['block_online_pod']}\" src=\"$mklib->images/plus.gif\"></a> 
		<a href=\"#\" onclick=\"ajax_showPop('{$mklib->sitepath}index.php?ind=ajax&amp;act=sitemon', 1);return false\"><img src=\"$mklib->images/load.png\" border=\"0\" align=\"middle\" alt=\"{$mklib->lang['block_online_ho']}\" /></a>
	<a href=\"#\" onclick=\"refresh_online();return false\"><img title=\"{$mklib->lang['block_online_ref']}\" src=\"$mklib->images/refresh.png\"></a> 
	&nbsp;<img title=\"{$mklib->lang['block_online_all']}\" src=\"$mklib->images/group.png\"></a>{$mklib->lang['block_online_all']}:<span class=\"mktxtcontr\">$total_online_users</span>
	<div id=\"user\">
$online_userlist
</div></td></tr>";


	}
	
	function rating_process() {
global $mkportals, $DB, $std, $print, $mklib, $Skin, $mklib_board;
@header("Content-type: text/html; charset={$mklib->charset}");
@header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
@header('Last-Modified: '.@gmdate('D, d M Y H:i:s').' GMT');
@header('Cache-Control: no-store, no-cache, must-revalidate');
@header('Cache-Control: post-check=0, pre-check=0', false);
@header('Pragma: no-cache');

	$id = $_POST['id'];
	$rating = $_POST['rating'];
	$modname = $_POST['modname'];
	$idauth = $mkportals->member['id'];
	
	if (!$idauth || $idauth == 0) {
	$sel = $DB->query("SELECT id FROM mkp_votes WHERE ip = '".$_SERVER['REMOTE_ADDR']."' AND id_entry = '$id' AND module = '$modname'");
	} else {
		$sel = $DB->query("SELECT id FROM mkp_votes WHERE ip = '".$_SERVER['REMOTE_ADDR']."' AND id_entry = '$id' AND module = '$modname' AND id_member = '$idauth'");
	}
		$count = $DB->get_num_rows ($sel);
	if ($count){
		echo "<span class=\"mktxtcontr\">{$mklib->lang['error_rating']}</span>";
		exit;
	}
	$DB->query("UPDATE mkp_$modname SET rate=rate+$rating, trate=trate+1 WHERE id = '$id'");
	$DB->query("INSERT INTO mkp_votes (id_entry, module, id_member, ip) VALUES ('$id', '$modname', '$idauth', '".$_SERVER['REMOTE_ADDR']."')");
	

	
	$sel = $DB->query("SELECT rate, trate FROM mkp_$modname WHERE id = '$id'");
			while($row = $DB->fetch_row($sel)){
			$rate = $row['rate'];
			$trate = $row['trate'];
			}
			$trate = (intval($trate)) ? $trate : 1;
			$width = number_format($rate / $trate, 2) * 17;
	$text = '
			<ul class="mbratings" id="rater_'.$id.'">
				<li class="cur-rating" style="width:'.$width.'px;" id="ul_'.$id.'"></li>
			</ul>
			';
	echo "$text";
	
	}
?>
