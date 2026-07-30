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
$idx = new mk_poll;
class mk_poll {
	var $tpl       = "";	
 function mk_poll() {
 global $mkportals, $mklib, $Skin, $mklib_board;
 $mklib->load_lang("lang_poll.php");
 if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_mod_poll']) {
			$message = "{$mklib->lang['poll_chek1']}";
			$mklib->error_page($message);
			exit;
		}
		if ($mklib->config['mod_poll'] == 1) {
		$message = "{$mklib->lang['poll_chek']}";
			$mklib->error_page($message);
			exit;
		}
switch($mkportals->input['op']) {
    case"poll_result":
    $this->poll_result();
    break;
    case"poll_save":
    $this->poll_save();
    break;
    case"poll_result":
    $this->poll_result();
    break;
    case "poll_show":
    $this->poll_show();
    break;
    default:
    $this->all_poll();
    break;
    case "add_comment":
    $this->add_comment();
    break;
    case "add_comment":
    $this->add_comment();
    break;
    case "del_comment":
    $this->del_comment();
    break;
    case "ajax_voting":
    $this->ajax_voting();
    break;
}
}
function del_comment() {
    	global $mkportals, $DB, $mklib;
		$idpoll= intval($mkportals->input['ide']);
		$idcomm= intval($mkportals->input['idcomm']);
		$DB->query("DELETE FROM mkp_comments WHERE id = $idcomm AND module = 'poll'");
		$query = $DB->query( "SELECT pool_comments FROM mkp_poll WHERE poll_id ='$idpoll'");
		$row = $DB->fetch_row($query);
		$totalcomm = $row['pool_comments'];
		--$totalcomm;
		$DB->query("UPDATE mkp_poll SET pool_comments ='$totalcomm' WHERE poll_id ='$idpoll'");
		$DB->close_db();
	 	Header("Location: /index.php?ind=poll&op=poll_show&poll_id=$idpoll");
		exit;
  	}

function add_comment() {
   global $mkportals, $DB, $mklib, $mklib_board;
	$ide = intval($mkportals->input['ide']);
	$testo = $mkportals->input['ta'];
	$autore = $mkportals->member['name'];
	$autid = $mkportals->member['id'];
	$cdata = time();
	if (!$testo) {
		$message = "{$mklib->lang['poll_chek4']}";
		$mklib->error_page($message);
		exit;
	}
	$testo = $mklib->convert_savedb($testo);
    $query="INSERT INTO `mkp_comments` (`cid`, `module`, `data`, `memid`, `name`, `comment` )VALUES ('$ide', 'poll', '$cdata', '$autid', '$autore', '$testo')";
	$DB->query($query);
	$query2 = $DB->query( "SELECT pool_comments FROM mkp_poll WHERE poll_id = '$ide'");
	$row = $DB->fetch_row($query2);
	$totalcomm = $row['pool_comments'];
	++$totalcomm;
	$DB->query("UPDATE mkp_poll SET pool_comments ='$totalcomm' WHERE poll_id = '$ide'");
	$DB->close_db();
	Header("Location: /index.php?ind=poll&op=poll_show&poll_id=$ide");
	exit;
}
function all_poll() {
    global $mkportals, $DB, $std, $print, $mklib, $Skin, $mklib_board;
    $result1 = $DB->query("SELECT poll_id FROM mkp_poll");
    $num = $DB->get_num_rows($result1);
        $q_page = intval($mkportals->input['st']);
		if ($q_page=="" or $q_page <= 0) {
			$q_page	=	0;
		}
		$per_page = $mklib->config['poll_page'];
		if ($per_page=="" or $per_page <= 0) {
			$per_page	=	10;
		}
	    $start = $q_page;
		$show_pages = $mklib->build_pages( array( TOTAL_POSS  => $num,
							PER_PAGE    => $per_page,
							CUR_ST_VAL  => $q_page,
							L_SINGLE    => '',
							L_MULTI     => 'pagine',
						    BASE_URL    => '/index.php?ind=poll',
										  )
								   );
	$result = $DB->query("SELECT poll_id, poll_date, poll_title, pool_vote, pool_comments, acomm FROM mkp_poll ORDER BY poll_id DESC LIMIT $start, $per_page");
    $num = $DB->get_num_rows($result);
    if ($num > 0) {
		$cn = 1;
		$output .= "<tr><td>";
		$output .= "<table align=\"center\" width=\"100%\">";
		while ($row = $DB->fetch_row($result)) {
			$p_data = $mklib->create_date($row['poll_date'], "short");
			$output .= "<tr><td align=\"center\">$p_data</td>
			<td align=\"center\"><a href=\"/index.php?ind=poll&amp;op=poll_show&amp;poll_id=$row[poll_id]\" title=\"$row[poll_title]\"><img src=\"mkportal/modules/poll/images/Voting.gif\" border=\"0\" alt=\"$row[poll_title]\"></a></td>
			<td align=\"center\"><a href=\"/index.php?ind=poll&amp;op=poll_show&amp;poll_id=$row[poll_id]\" title=\"$row[poll_title]\">$row[poll_title]</a></td>
			<td align=\"center\">{$mklib->lang['poll_pgoloss']} ($row[pool_vote])";
			if ($row[acomm] == 1) {$output .= "&nbsp;&nbsp;{$mklib->lang['poll_pcomen']} ($row[pool_comments])"; }
			
			$output .= "</td></tr>";	
		}
		$output .= "</table>";
    } else {
    	$output .= "<tr><td>";
		$output .= "<table align=\"center\" width=\"100%\">";
		$output .= "
		<td align=\"center\">{$mklib->lang['poll_nopoll']}</td>";
    }
    $output .= "</br><table align=\"center\">
      <tr>
 	<td align=\"center\">
	<div style=\"margin: 4px\">{$show_pages}</div>
	</td>
      </tr>
    </table>";
    $output .= "</td></tr>";
    $output .= "<tr>
	  <td align=\"center\"><br /><br />
	    <div align=\"center\"><a href=\"http://www.rusmkportal.ru\" target=\"_blank\">MKPPoll</a> &copy;2007-2009 <a href=\"http://www.rusmkportal.ru\" target=\"_blank\">rusmkportal.ru</a></div>
	  </td>
	</tr>	
	";
    $blocks .= $Skin->view_block("{$mklib->lang['poll_poll']}", $output);
	$mklib->printpage("1", "1", "{$mklib->lang['poll_poll']}", $blocks);

}
function poll_show() {
    global $mkportals, $DB, $std, $print, $mklib, $Skin, $mklib_board;
    $poll_id = intval($mkportals->input['poll_id']);
    $idaut = $mkportals->member['id'];
    $ip = $_SERVER['REMOTE_ADDR'];
    if (!$idaut || $idaut == 0) { //Guests: check IP address
			$query = $DB->query( "SELECT * FROM mkp_poll_check WHERE poll_id='$poll_id' AND ip = '$ip'");
			$check = $DB->get_num_rows($query);

		} else { //Registered Members: check userid
			$query = $DB->query( "SELECT * FROM mkp_poll_check WHERE poll_id='$poll_id' AND mem_id = '$idaut'");
			$check = $DB->get_num_rows($query);
		}
		if($check) {
		$result = $DB->query("SELECT * FROM mkp_poll WHERE poll_id='$poll_id'");
	   if ($numrows = $DB->get_num_rows($result) != 1) {
		Header("Location: /index.php?ind=poll");
		exit;
	}
    $row = $DB->fetch_row($result);
    $poll_title = $row[poll_title];
    $questions = explode("|", $row[poll_questions]);
    $p_summ = $row[pool_vote];
    $nm = 1;
    for($i = 0; $i<count($questions)-1; $i++) {
		$a_ques[$i] = $questions[$i];
		$a_poll[$i] = $row[poll_answer_.$nm];
		$nm++;
    }
	$m_size = getimagesize("mkportal/modules/poll/images/mainbar.gif");


    $output .= "<tr>
		  <td>";
    $output .= "<center><b>$poll_title</b><br><br>";
    $output .= "<table width=\"80%\">";
    for ($i = 0; $i <count($questions)-1; $i++) {
		if ($p_summ > 0) {
			$proc = 100 * $a_poll[$i] / $p_summ;
			$im_w = (int)$proc - 5;
			$procent = number_format($proc, 2);
		} else {
			$im_w = 1;
			$procent = "0.00";
		}
		$output .= "<tr><td align=\"left\" width=\"100%\">&nbsp;&nbsp;$a_ques[$i] ($a_poll[$i])</td></tr>";
		$output .= "<tr><td align=\"left\">
		&nbsp;&nbsp;<img src=\"mkportal/modules/poll/images/leftbar.gif\" alt=\"".$a_ques[$i]." - ".$procent."%\" title=\"".$a_ques[$i]." - ".$procent."%\"><img src=\"mkportal/modules/poll/images/mainbar.gif\" height=\"$m_size[1]\" width=\"".$im_w."%\" alt=\"".$a_ques[$i]." - ".$procent."%\" title=\"".$a_ques[$i]." - ".$procent."%\"><img src=\"mkportal/modules/poll/images/rightbar.gif\" alt=\"".$a_ques[$i]." - ".$procent."%\" title=\"".$a_ques[$i]." - ".$procent."%\"></td>
		<td align=\"left\">".$procent."%</td>
		</tr>
		<tr><td height=\"5\">
		</td></tr>";
    }
    $output .= "</table><br><br>{$mklib->lang['poll_pgoloss']} $p_summ";
    $output .= "<br><br>[ <a href=\"/index.php?ind=poll\" title=\"{$mklib->lang['poll_poll']}\">{$mklib->lang['poll_poll']}</a> ]";
    
    $output .= "</center>";
    $acomm = $row[acomm];
    $poll_id = $row[poll_id];
    if ($acomm == 1) {
	$result3 = $DB->query( "SELECT id, cid, module, data, memid, name, memip, comment, status FROM mkp_comments WHERE cid = '$poll_id' AND module = 'poll'  ORDER BY `id` DESC");
    while( $row = $DB->fetch_row($result3) ) {
    	$idc = $row['id'];
		$autid = $row['memid'];
		$autore = $row['name'];
		$testo = stripslashes($row['comment']);
		$data = $mklib->create_date($row['data']);
				$testo = $mklib->decode_bb($testo);
		
		$delete = "
			<a href=\"/index.php?ind=poll&amp;op=del_comment&amp;idcomm=$idc&amp;ide=$poll_id\">[ {$mklib->lang['poll_ad_delpoll_menu']} ]</a>
			";
			if(!$mkportals->member['g_access_cp']) {
				$delete = "";
			}
		$content2 .= "
			<tr>
                            <td class=\"modulecell\" width=\"20%\" valign=\"top\">{$autore}<br />{$data}<br />{$delete}</td>
                            <td class=\"modulecell\" width=\"80%\" valign=\"middle\">{$testo}</td>
			</tr>
			";
		
    }
    $textarepar = "mce_editable=\"true\"";
		$textarew = "100%";
			$editorscript = "";
			$textarepar = "";
			$textarew = "75%";
			$bbeditor= $mklib->get_commentbbeditor();
		$bbcomnt ="<form action=\"/index.php?ind=poll&amp;op=add_comment\" name=\"editor\" method=\"post\" >
				<table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"8\">		
				  <tr>
        	
				    <td rowspan=\"3\" align=\"center\" height=\"100%\">
				      <input type=\"hidden\" name=\"ide\" value=\"$poll_id\" />
		
				      <td width=\"70%\" align=\"left\">
		                      $bbeditor
		             <textarea cols=\"10\" style=\"width:75%\" rows=\"5\" name=\"ta\" id=\"ta\"></textarea>
				    <td>{$mklib->lang['ne_writecomm']}</td>
				  </tr>
				  <tr>
				    <td width=\"70%\" align=\"left\">

				    </td>
				  </tr>
				  <tr>
				    <td>
				      <input type=\"submit\" name=\"submit\" value=\"{$mklib->lang['poll_ad_saves']}\" class=\"button2\" accesskey=\"s\" /><br />
				    </td>
				  </tr>		
				</table>
				</form>";
			
			$output .= "<tr>
		      <td>
			<table class=\"moduleborder\" width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"4\">
			{$content2}
	
	         </table>
	$bbcomnt
		      </td>
		    </tr>
		  ";
		}
	$output .= "</td></tr>";
	$output .= "<tr>
	  <td align=\"center\"><br /><br />
	    <div align=\"center\"><a href=\"http://www.rusmkportal.ru\" target=\"_blank\">MKPPoll</a> &copy;2007-2009 <a href=\"http://www.rusmkportal.ru\" target=\"_blank\">rusmkportal.ru</a></div>
	  </td>
	</tr>	
	";
	$blocks .= $Skin->view_block("{$mklib->lang['poll_poll']} $poll_title", $output);
	$mklib->printpage("1", "1", "{$mklib->lang['poll_poll']} $poll_title", $blocks);
			exit;
		}
    $result = $DB->query("SELECT poll_id, poll_title, poll_questions, pool_vote, pool_comments, acomm FROM mkp_poll WHERE poll_id='$poll_id'");
    while( $row = $DB->fetch_row($result) ) {
		$poll_id = $row['poll_id'];
		$poll_title = $row['poll_title'];
		$poll_questions = $row['poll_questions'];
		$pool_vote = $row['pool_vote'];
		$pool_comments = $row['pool_comments'];
		$acomm = $row['acomm'];
    }
   $output .= "<tr><td>";    
    if ($poll_id == 0 || $poll_id == "") {
		Header("Location: /index.php?ind=poll");
    } else {
		$output .= "<center><form action=\"/index.php?ind=poll&amp;op=poll_save\" method=\"post\">";
		$output .= "<input type=\"hidden\" name=\"poll_id\" value=\"$poll_id\">";
		$output .= "<input type=\"hidden\" name=\"op\" value=\"poll_save\">";
		$output .= "<font class=\"content\"><br><b>$poll_title</b></font><br><br>\n";
		$output .= "<table border=\"0\">";
		$questions = explode("|", $poll_questions);
		for ($i = 0; $i <count($questions)-1; $i++) {
			$n=$i+1;
			$output .= "<tr><td valign=\"top\">
			<input type=\"radio\" name=\"questions\" value=\"".$n."\"></td>
			<td><font class=\"content\">$questions[$i]</font></td></tr>\n";	
		}
		$output .= "</table>
		<br><center>
		<font class=\"content\"><input type=\"submit\" value=\"{$mklib->lang['poll_submit']}\"></font><br>";
		$output .= "<br>
		[ <a href=\"/index.php?ind=poll&amp;op=poll_result&amp;poll_id=$poll_id\" title=\"{$mklib->lang['poll_rezult']}\">{$mklib->lang['poll_rezult']}</a> | <a href=\"/index.php?ind=poll\" title=\"{$mklib->lang['poll_poll']}\">{$mklib->lang['poll_poll']}</a> ]<br>";

		if ($acomm == 1) {
			$output .= "<br>{$mklib->lang['poll_pgoloss']} $pool_vote<br>{$mklib->lang['poll_pcomen']} $pool_comments";
		} else {
			$output .= "<br>{$mklib->lang['poll_pgoloss']} $pool_vote";
		}
		$output .= "</font></center></form>";
	}
	 if ($acomm == 1) {
	$result3 = $DB->query( "SELECT id, cid, module, data, memid, name, memip, comment, status FROM mkp_comments WHERE cid = '$poll_id' AND module = 'poll'  ORDER BY `id` DESC");
    while( $row = $DB->fetch_row($result3) ) {
    $idc = $row['id'];
		$autid = $row['memid'];
		$autore = $row['name'];
		$testo = stripslashes($row['comment']);
		$data = $mklib->create_date($row['data']);
				$testo = $mklib->decode_bb($testo);
		$delete = "
			<a href=\"/index.php?ind=poll&amp;op=del_comment&amp;idcomm=$idc&amp;ide=$poll_id\">[ {$mklib->lang['poll_ad_delpoll_menu']} ]</a>
			";
			if(!$mkportals->member['g_access_cp']) {
				$delete = "";
			}
		$content2 .= "
			<tr>
                            <td class=\"modulecell\" width=\"20%\" valign=\"top\">{$autore}<br />{$data}<br />{$delete}</td>
                            <td class=\"modulecell\" width=\"80%\" valign=\"middle\">{$testo}</td>
			</tr>
			";
		
    }
    $textarepar = "mce_editable=\"true\"";
		$textarew = "100%";
		
			$editorscript = "";
			$textarepar = "";
			$textarew = "75%";
			$bbeditor= $mklib->get_commentbbeditor();
		
		$bbcomnt ="<form action=\"/index.php?ind=poll&amp;op=add_comment&amp;poll_id=$poll_id\" name=\"editor\" method=\"post\" >
				<table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"8\">		
				  <tr>
        	
				    <td rowspan=\"3\" align=\"center\" height=\"100%\">
				      <input type=\"hidden\" name=\"ide\" value=\"$poll_id\" />
		
				      <td width=\"70%\" align=\"left\">
		                      $bbeditor
		             <textarea cols=\"10\" style=\"width:75%\" rows=\"5\" name=\"ta\"></textarea>
				    <td>{$mklib->lang['ne_writecomm']}</td>
				  </tr>
				  <tr>
				    <td width=\"70%\" align=\"left\">

				    </td>
				  </tr>
				  <tr>
				    <td>
				      <input type=\"submit\" name=\"submit\" value=\"{$mklib->lang['poll_ad_saves']}\" class=\"button2\" accesskey=\"s\" /><br />
				    </td>
				  </tr>		
				</table>
				</form>";
			
			$output .= "<tr>
		      <td>
			<table class=\"moduleborder\" width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"4\">
			{$content2}
	
	         </table>
	$bbcomnt
		      </td>
		    </tr>
		  ";
		}

$output .= "</td></tr>";
$output .= "<tr>
	  <td align=\"center\"><br /><br />
	    <div align=\"center\"><a href=\"http://www.rusmkportal.ru\" target=\"_blank\">MKPPoll</a> &copy;2007-2009 <a href=\"http://www.rusmkportal.ru\" target=\"_blank\">rusmkportal.ru</a></div>
	  </td>
	</tr>	
	";
     $blocks .= $Skin->view_block("{$mklib->lang['poll_poll']} $poll_title", $output);
	$mklib->printpage("1", "1", "{$mklib->lang['poll_poll']} $poll_title", $blocks);

  
}

function poll_save() {
   global $mkportals, $DB, $std, $print, $mklib, $Skin, $mklib_board;
    if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_send_poll']) {
			$message = "{$mklib->lang['poll_chek2']}";
			$mklib->error_page($message);
			exit;
		}
    $poll_id = intval($mkportals->input['poll_id']);
    $questions = intval($mkportals->input['questions']);
    $ip = $_SERVER['REMOTE_ADDR'];
    $idaut = $mkportals->member['id'];
    if (!$idaut || $idaut == 0) { //Guests: check IP address
			$query = $DB->query( "SELECT * FROM mkp_poll_check WHERE poll_id='$poll_id' AND ip = '$ip'");
			$check = $DB->get_num_rows($query);

		} else { //Registered Members: check userid
			$query = $DB->query( "SELECT * FROM mkp_poll_check WHERE poll_id='$poll_id' AND mem_id = '$idaut'");
			$check = $DB->get_num_rows($query);
		}
		if($check) {
			$message = "{$mklib->lang['poll_chek3']}";
			$mklib->error_page($message);
			exit;
		}
	$ctime = time();
    $DB->query("INSERT INTO mkp_poll_check (ip, time, poll_id, mem_id) VALUES ('$ip', '$ctime', '$poll_id', '$idaut')");
   $DB->query("UPDATE mkp_poll SET poll_answer_".$questions."=poll_answer_".$questions."+1, pool_vote=pool_vote+1 WHERE poll_id='$poll_id'");
    Header("Location: /index.php?ind=poll&op=poll_result&poll_id=$poll_id");
}

function poll_result() {
   global $mkportals, $DB, $std, $print, $mklib, $Skin, $mklib_board;
    $poll_id = intval($mkportals->input['poll_id']);
    $result = $DB->query("SELECT * FROM mkp_poll WHERE poll_id='$poll_id'");
	if ($numrows = $DB->get_num_rows($result) != 1) {
		Header("Location: /index.php?ind=poll");
		exit;
	}
    $row = $DB->fetch_row($result);
    $poll_title = $row[poll_title];
    $questions = explode("|", $row[poll_questions]);
    $p_summ = $row[pool_vote];
    $nm = 1;
    for($i = 0; $i<count($questions)-1; $i++) {
		$a_ques[$i] = $questions[$i];
		$a_poll[$i] = $row[poll_answer_.$nm];
		$nm++;
    }
	$m_size = getimagesize("mkportal/modules/poll/images/mainbar.gif");
    $output .= "<tr>
		  <td>";
    $output .= "<center><b>$poll_title</b><br><br>";
    $output .= "<table width=\"80%\">";
    for ($i = 0; $i <count($questions)-1; $i++) {
		if ($p_summ > 0) {
			$proc = 100 * $a_poll[$i] / $p_summ;
			$im_w = (int)$proc - 5;
			$procent = number_format($proc, 2);
		} else {
			$im_w = 1;
			$procent = "0.00";
		}
		$output .= "<tr><td align=\"left\" width=\"100%\">&nbsp;&nbsp;$a_ques[$i] ($a_poll[$i])</td></tr>";
		$output .= "<tr><td align=\"left\">
		&nbsp;&nbsp;<img src=\"mkportal/modules/poll/images/leftbar.gif\" alt=\"".$a_ques[$i]." - ".$procent."%\" title=\"".$a_ques[$i]." - ".$procent."%\"><img src=\"mkportal/modules/poll/images/mainbar.gif\" height=\"$m_size[1]\" width=\"".$im_w."%\" alt=\"".$a_ques[$i]." - ".$procent."%\" title=\"".$a_ques[$i]." - ".$procent."%\"><img src=\"mkportal/modules/poll/images/rightbar.gif\" alt=\"".$a_ques[$i]." - ".$procent."%\" title=\"".$a_ques[$i]." - ".$procent."%\"></td>
		<td align=\"left\">".$procent."%</td>
		</tr>
		<tr><td height=\"5\">
		</td></tr>";
    }
    $output .= "</table><br><br>{$mklib->lang['poll_pgoloss']} $p_summ";
    $output .= "<br><br>[ <a href=\"/index.php?ind=poll\" title=\"{$mklib->lang['poll_poll']}\">{$mklib->lang['poll_poll']}</a> ]";
    
    $output .= "</center>";
	$output .= "</td></tr>";
	$output .= "<tr>
	  <td align=\"center\"><br /><br />
	    <div align=\"center\"><a href=\"http://www.rusmkportal.ru\" target=\"_blank\">MKPPoll</a> &copy;2007-2009 <a href=\"http://www.rusmkportal.ru\" target=\"_blank\">rusmkportal.ru</a></div>
	  </td>
	</tr>	
	";
  $blocks .= $Skin->view_block("{$mklib->lang['poll_tezult_title']} $poll_title", $output);
  $mklib->printpage("1", "1", "{$mklib->lang['poll_tezult_title']} $poll_title", $blocks);
}
function ajax_voting() {
	global $mkportals, $DB, $std, $print, $mklib, $Skin, $mklib_board;
	//$mklib->load_lang("lang_poll.php");
@header("Content-type: text/html; charset={$mklib->charset}");
@header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
@header('Last-Modified: '.@gmdate('D, d M Y H:i:s').' GMT');
@header('Cache-Control: no-store, no-cache, must-revalidate');
@header('Cache-Control: post-check=0, pre-check=0', false);
@header('Pragma: no-cache');
	$poll_id = intval($mkportals->input['poll_id']);
    $questions = intval($mkportals->input['var']);
    $ip = $_SERVER['REMOTE_ADDR'];
    $idaut = $mkportals->member['id'];
	$ctime = time();
   $DB->query("INSERT INTO mkp_poll_check (ip, time, poll_id, mem_id) VALUES ('$ip', '$ctime', '$poll_id', '$idaut')");
   $DB->query("UPDATE mkp_poll SET poll_answer_".$questions."=poll_answer_".$questions."+1, pool_vote=pool_vote+1 WHERE poll_id='$poll_id'");
   $result = $DB->query("SELECT * FROM mkp_poll ORDER BY poll_id DESC LIMIT 1");
$row = $DB->fetch_row($result); 
		$poll_id = $row['poll_id'];
		$poll_title = $row['poll_title'];
		$poll_questions = $row['poll_questions'];
		$pool_vote = $row['pool_vote'];
		$pool_comments = $row['pool_comments'];
		$acomm = $row['acomm'];
		$questions = explode("|", $poll_questions);
    $p_summ = $pool_vote;
    $nm = 1;
    for($i = 0; $i<count($questions)-1; $i++) {
		$a_ques[$i] = $questions[$i];
		$a_poll[$i] = $row[poll_answer_.$nm];
		$nm++;
    }
	$m_size = getimagesize("$mklib->sitepath/mkportal/modules/poll/images/mainbar.gif");


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
		$content .= "<tr><td align=\"left\" width=\"100%\"><img src=\"$mklib->sitepath/mkportal/modules/poll/images/leftbar.gif\" alt=\"".$a_ques[$i]." - ".$procent."%\" title=\"".$a_ques[$i]." - ".$procent."%\"><img src=\"$mklib->sitepath/mkportal/modules/poll/images/mainbar.gif\" height=\"$m_size[1]\" width=\"".$im_w."px\" alt=\"".$a_ques[$i]." - ".$procent."%\" title=\"".$a_ques[$i]." - ".$procent."%\"><img src=\"$mklib->sitepath/mkportal/modules/poll/images/rightbar.gif\" alt=\"".$a_ques[$i]." - ".$procent."%\" title=\"".$a_ques[$i]." - ".$procent."%\">($a_poll[$i])</td></tr>";
    }

$content .= "</td></tr></table>";
		if ($acomm == 1) {
			$content .= "<br>{$mklib->lang['poll_pgoloss']} $pool_vote<br>{$mklib->lang['poll_pcomen']} $pool_comments";
		} else {
			$content .= "<br>{$mklib->lang['poll_pgoloss']} $pool_vote";
		}
		$content .= "<br>
		[ <a href=\"{$mklib->siteurl}//index.php?ind=poll&amp;op=poll_result&amp;poll_id=$poll_id\" title=\"{$mklib->lang['poll_rezult']}\">{$mklib->lang['poll_rezult']}</a> | <a href=\"{$mklib->siteurl}//index.php?ind=poll\" title=\"{$mklib->lang['poll_poll']}\">{$mklib->lang['poll_poll']}</a> ]<br />";

		echo "$content";
  	
	}
	
}
?>