<?php
/*
+--------------------------------------------------------------------------
|   RusMKPortal.ru
|   ========================================
|   by Support
|   Copyright (c) 2007-2009 rusmkportal.ru
|   http://www.rusmkportal.ru
|   Email: rusmkportal@mail.ru
|
+---------------------------------------------------------------------------
*/
if (!defined("IN_MKP")) {
    die ("Sorry !! You cannot access this file directly.");
}

$this->load_lang("lang_poll.php");
$content = "";
$result = $DB->query("SELECT * FROM mkp_poll ORDER BY poll_id DESC LIMIT 1");
$row = $DB->fetch_row($result); 
		$poll_id = $row['poll_id'];
		$poll_title = $row['poll_title'];
		$poll_questions = $row['poll_questions'];
		$pool_vote = $row['pool_vote'];
		$pool_comments = $row['pool_comments'];
		$acomm = $row['acomm'];
    
$idaut = $mkportals->member['id'];
    $ip = $_SERVER['REMOTE_ADDR'];
    if (!$idaut || $idaut == 0) {
			$query = $DB->query( "SELECT * FROM mkp_poll_check WHERE poll_id='$poll_id' AND ip = '$ip'");
			$check = $DB->get_num_rows($query);

		} else {
			$query = $DB->query( "SELECT * FROM mkp_poll_check WHERE poll_id='$poll_id' AND mem_id = '$idaut'");
			$check = $DB->get_num_rows($query);
		}
if($check) {
    $questions = explode("|", $poll_questions);
    $p_summ = $pool_vote;
    $nm = 1;
    for($i = 0; $i<count($questions)-1; $i++) {
		$a_ques[$i] = $questions[$i];
		$a_poll[$i] = $row[poll_answer_.$nm];
		$nm++;
    }
	$m_size = getimagesize("$this->sitepath/mkportal/modules/poll/images/mainbar.gif");


    $content .= "<tr>
		  <td>";
    $content .= "<center><b>$poll_title</b><br><br>";
    $content .= "<table width=\"100%\">";
    for ($i = 0; $i <count($questions)-1; $i++) {
		if ($p_summ > 0) {
			$proc = 100 * $a_poll[$i] / $p_summ;
			$im_w = (int)$proc - 5;
			$procent = number_format($proc, 2);
		} else {
			$im_w = 1;
			$procent = "0.00";
		}
		$content .= "<tr><td align=\"left\" width=\"80%\">&nbsp;&nbsp;$a_ques[$i] </td></tr>";
		$content .= "<tr><td align=\"left\" width=\"100%\"><img src=\"$this->sitepath/mkportal/modules/poll/images/leftbar.gif\" alt=\"".$a_ques[$i]." - ".$procent."%\" title=\"".$a_ques[$i]." - ".$procent."%\"><img src=\"$this->sitepath/mkportal/modules/poll/images/mainbar.gif\" height=\"$m_size[1]\" width=\"".$im_w."px\" alt=\"".$a_ques[$i]." - ".$procent."%\" title=\"".$a_ques[$i]." - ".$procent."%\"><img src=\"$this->sitepath/mkportal/modules/poll/images/rightbar.gif\" alt=\"".$a_ques[$i]." - ".$procent."%\" title=\"".$a_ques[$i]." - ".$procent."%\">($a_poll[$i])</td></tr>";
    }

$content .= "</td></tr></table>";
		if ($acomm == 1) {
			$content .= "<br>{$this->lang['poll_pgoloss']} $pool_vote<br>{$this->lang['poll_pcomen']} $pool_comments";
		} else {
			$content .= "<br>{$this->lang['poll_pgoloss']} $pool_vote";
		}
		$content .= "<br>
		[ <a href=\"{$this->siteurl}/index.php?ind=poll&amp;op=poll_result&amp;poll_id=$poll_id\" title=\"{$this->lang['poll_rezult']}\">{$this->lang['poll_rezult']}</a> | <a href=\"{$this->siteurl}/index.php?ind=poll\" title=\"{$this->lang['poll_poll']}\">{$this->lang['poll_poll']}</a> ]<br />";

}
else {
	$content .= "<tr><td id=\"pollresult\">";  
	$content .= "<form id =\"voting\" name=\"voting\" action=\"javascript:sendvoting();\" method=\"post\">";
		$content .= "<input type=\"hidden\" name=\"poll_id\" value=\"$poll_id\">";
		$content .= "<font class=\"content\"><b>$poll_title</b></font><br><br>\n";
		$content .= "<table border=\"0\">";
		$questions = explode("|", $poll_questions);
		for ($i = 0; $i <count($questions)-1; $i++) {
			$nss=$i+1;
			$content .= "<tr><td valign=\"top\"><input type=\"radio\" name=\"var\" value=\"$nss\"></td>
			<td><font class=\"content\">$questions[$i]</font></td></tr>\n";	
		}
		$content .= "</table>
		<br><center>
		<font class=\"content\"><input type=\"submit\" value=\"{$this->lang['poll_submit']}\"></font><br>";
		$content .= "<br>
		[ <a href=\"{$this->siteurl}/index.php?ind=poll&amp;op=poll_result&amp;poll_id=$poll_id\" title=\"{$this->lang['poll_rezult']}\">{$this->lang['poll_rezult']}</a> | <a href=\"{$this->siteurl}/index.php?ind=poll\" title=\"{$this->lang['poll_poll']}\">{$this->lang['poll_poll']}</a> ]<br>";

		if ($acomm == 1) {
			$content .= "<br>{$this->lang['poll_pgoloss']} $pool_vote<br>{$this->lang['poll_pcomen']} $pool_comments";
		} else {
			$content .= "<br>{$this->lang['poll_pgoloss']} $pool_vote";
		}
		$content .= "</font></form>";
		
}
?>