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
$idx = new mk_ad_voting;
class mk_ad_voting {
	
	function mk_ad_voting() {
		global $mkportals, $mklib, $Skin, $DB, $mklib_board;
		 $mklib->load_lang("lang_poll.php");
			switch($mkportals->input['op']) {
	default:
    $this->create_voting();
    break;

    case "save_voting":
    $this->save_voting();
    break;
    
    case "select_remove_voting":
    $this->select_remove_voting();
    break;
    
    case "remove_voting":
    $this->remove_voting();
    break;
    
    case "select_edit_voting":
    $this->select_edit_voting();
    break;
    
    case "edit_voting":
    $this->edit_voting();
    break;
    
    case "save_edit_voting":
    $this->save_edit_voting();
    break;
     case "config":
    $this->config();
    break;
    case "save_config":
    $this->save_config();
    break;
}
		}

function NaviCom() {
	global $mkportals, $mklib, $Skin, $DB, $mklib_board;
    $output .= "<table align=\"center\" width=\"100%\" border=\"0\"><tr><td align=\"center\">
    <b>Опросы</b><br><br>"
	."[ <a href=\"index.php?ind=ad_voting&amp;op=create_voting\">{$mklib->lang['poll_ad_home_menu']}</a>"
	." | <a href=\"index.php?ind=ad_voting&amp;op=select_remove_voting\">{$mklib->lang['poll_ad_delpoll_menu']}</a>"
	." | <a href=\"index.php?ind=ad_voting&amp;op=select_edit_voting\">{$mklib->lang['poll_ad_aditpoll_menu']}</a>"
	." | <a href=\"index.php?ind=ad_voting&amp;op=config\">{$mklib->lang['poll_ad_conf']}</a> ]</td>
	</tr>
</table>";
	return $output;
}

function acomm($acomm) {
	global $mkportals, $mklib, $Skin, $DB, $mklib_board;
    $output .= "<tr><td>{$mklib->lang['poll_ad_adcoments']}</td><td>";
    if (($acomm == 0) OR ($acomm == "")) {
		$sel1 = "";
		$sel2 = "checked";
    }
    if ($acomm == 1) {
		$sel1 = "checked";
		$sel2 = "";
    }
    $output .= "<input type=\"radio\" name=\"acomm\" value=\"1\" $sel1>{$mklib->lang['poll_ad_yes']} &nbsp;&nbsp; <input type=\"radio\" name=\"acomm\" value=\"0\" $sel2>{$mklib->lang['poll_ad_no']}</font></td></tr>";
return $output;
}

function create_voting() {
  global $mkportals, $mklib, $Skin, $DB, $mklib_board;

   $output .= $this->NaviCom();
   $output .= "
<tr>
<td>
<table width=\"100%\" cellspacing=\"2\" cellpadding=\"5\" class=\"tabmain\">
	      <tr>
		<td width=\"50%\" class=\"sottotitolo\" align=\"center\">{$mklib->lang['poll_ad_polltitle']}</td>
		<td width=\"50%\" class=\"sottotitolo\" align=\"center\">{$mklib->lang['poll_ad_aditpoll_menu']}</td>
		</tr>";
    $result = $DB->query("SELECT poll_id, poll_title FROM mkp_poll ORDER BY poll_id");
while( $row = $DB->fetch_row($result) ) {
		$poll_id = $row['poll_id'];
		$poll_title = $row['poll_title'];
		 $output .= "		
	      <tr>
		<td width=\"50%\" class=\"modulex\" align=\"center\">$poll_title</td>
		<td width=\"50%\" class=\"modulex\" align=\"center\"><a class=\"uno\" href=\"index.php?ind=ad_voting&amp;op=edit_voting&amp;poll_id=$poll_id\"><img class=\"mkicon\" src=\"images/icons/famfamfam/silk/page_edit.png\" align=\"center\" title=\"{$mklib->lang['poll_ad_aditpoll_menu']}\" alt=\"{$mklib->lang['poll_ad_aditpoll_menu']}\" /></a>
		 <a class=\"uno\" href=\"index.php?ind=ad_voting&amp;op=remove_voting&amp;del=$poll_id\"><img class=\"mkicon\" src=\"images/icons/famfamfam/silk/cross.png\" align=\"center\" title=\"{$mklib->lang['poll_ad_delpoll_menu']}\" alt=\"{$mklib->lang['poll_ad_delpoll_menu']}\" /></a></td>
	      </tr>
	";
}
	$output .= "	    
</table>
</td>
</tr>
	";
	$output .= "<center><font class=\"option\"><b>{$mklib->lang['poll_ad_adpoll']}</b></font><br>"
	."<form action=\"index.php?ind=ad_voting&amp;op=save_voting\" method=\"post\">"
	."<table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" align=\"center\">"
	."<tr><td>{$mklib->lang['poll_ad_polltitle']}:</td>
	<td><input type=\"text\" name=\"polltitle\" maxlength=\"100\" size=\"65\" style=\"width:400px\"></td></tr>";
	 $output .= $this->acomm($acomm);
    
		$output .= "<input type=\"hidden\" name=\"planguage\" value=\"$language\"><br><br>";
    
    for($i = 1; $i <= 12; $i++) {
		$output .= "<tr><td>{$mklib->lang['poll_ad_varpoll']} $i:</td>
		<td><input type=\"text\" name=\"optiontext[]\" maxlength=\"50\" size=\"65\" style=\"width:400px\"></td></tr>";
    }
    $output .= "</table><br>"
	."<center><input type=\"hidden\" name=\"op\" value=\"save_voting\">"
	."<input type=\"submit\" value=\"{$mklib->lang['poll_ad_adpoll']}\"></form><center>";
$output = $Skin->view_block("{$mklib->lang['poll_ad_adpoll']}", "$output");
$mklib->printpage_admin("{$mklib->lang['poll_ad_adpoll']}", $output);
}

function save_voting() {
    global $mkportals, $mklib, $Skin, $DB, $mklib_board;
    $polltitle = $mkportals->input['polltitle'];
	$acomm = $mkportals->input['acomm'];
	$optiontext = $_POST['optiontext'];
	$cdata = time();
    $number_optiontext = count($optiontext);
    $op_text = "";
    for ($p=0;$p<$number_optiontext;$p++) {
		if ($optiontext[$p] != "") {
			$op_text .= "$optiontext[$p]|";
		}
		
    }
    $DB->query("INSERT INTO mkp_poll (poll_date, poll_title, poll_questions, acomm) VALUES ('$cdata', '$polltitle', '$op_text', '$acomm')");
    Header("Location: index.php?ind=ad_voting");
}

function select_remove_voting() {
      global $mkportals, $mklib, $Skin, $DB, $mklib_board;

	$output .= $this->NaviCom();
	
    $result = $DB->query("SELECT poll_id, poll_title FROM mkp_poll");
    $num = $DB->get_num_rows($result);
    if ($num > 0) {
		
		$output .= "<center><font class=\"option\"><b>{$mklib->lang['poll_ad_alldelet']}</b></font><br><br>"
		."<table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" align=\"center\">"
		."<form action=\"index.php?ind=ad_voting&amp;op=remove_voting\" method=\"post\">";
		while ($row = $DB->fetch_row($result)) {
			$output .= "<tr><td><input type=\"checkbox\" name=\"del[]\" value=\"$row[poll_id]\"></td><td>$row[poll_title]</td></tr>";
		}
		$output .= "</table><br><center><input type=\"hidden\" name=\"op\" value=\"remove_voting\">"
		."<input type=\"submit\" value=\"{$mklib->lang['poll_ad_delpoll_menu']}\"></form></center>";
		
    }
   $output = $Skin->view_block("{$mklib->lang['poll_ad_adpoll']}", "$output");
     $mklib->printpage_admin("{$mklib->lang['poll_ad_adpoll']}", $output);
}

function remove_voting() {
    global $mkportals, $mklib, $Skin, $DB, $mklib_board;
	//$del = $_POST['del'];
	$del = $mkportals->input['del'];
    $number_del = count($del);
    for($p=0;$p<$number_del;$p++) {
		$DB->query("DELETE FROM mkp_poll WHERE poll_id='$del[$p]'");
		$DB->query("DELETE FROM mkp_poll_check WHERE poll_id='$del[$p]'");
		$DB->query("DELETE FROM mkp_poll_comments WHERE idpoll='$del[$p]'");
    }
    Header("Location: index.php?ind=ad_voting&op=select_remove_voting");
}

function select_edit_voting() {
    global $mkportals, $mklib, $Skin, $DB, $mklib_board;
    
   
    $output .= $this->NaviCom();
    $result = $DB->query("SELECT poll_id, poll_title FROM mkp_poll");
    $num = $DB->get_num_rows($result);
    if ($num > 0) {
		
		$output .= "<center><font class=\"option\"><b>{$mklib->lang['poll_ad_adit']}</b></font><br><br>"
		."<table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" align=\"center\">"
		."<form action=\"index.php?ind=ad_voting&amp;op=edit_voting\" method=\"post\">";
		while ($row = $DB->fetch_row($result)) {
			$output .= "<tr><td><input type=\"radio\" value=\"$row[poll_id]\" name=\"poll_id\"></td><td>$row[poll_title]</td></tr>";
		}
		$output .= "</table><br><center><input type=\"hidden\" name=\"op\" value=\"edit_voting\">"
		."<input type=\"submit\" value=\"{$mklib->lang['poll_ad_aditpoll_menu']}\"></form></center>";
		
    }
    $output = $Skin->view_block("{$mklib->lang['poll_ad_adit']}", "$output");
     $mklib->printpage_admin("{$mklib->lang['poll_ad_adit']}", $output);
}

function edit_voting() {
    global $mkportals, $mklib, $Skin, $DB, $mklib_board;
    $poll_id = $mkportals->input['poll_id'];
    $output .= $this->NaviCom();
    $sql = "SELECT * FROM mkp_poll WHERE poll_id='$poll_id'";
    $result = $DB->query($sql);
    $num = $DB->get_num_rows($result);
    if ($num == 1) {
		
		$row = $DB->fetch_row($result);
		$acomm = $row[acomm];
		
		$output .= "<center><font class=\"option\"><b>{$mklib->lang['poll_ad_adit_poll']}</b></font><br><br>"
		."<table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" align=\"center\">"
		."<form action=\"index.php?ind=ad_voting&amp;op=save_edit_voting\" method=\"post\">"
		."<tr><td>{$mklib->lang['poll_ad_polltitle']}:</td><td><input type=\"text\" name=\"polltitle\" value=\"$row[poll_title]\" size=\"65\" style=\"width:400px\"></td></tr>";
		$output .= $this->acomm($acomm);
	}
		$questions = explode("|", $row[poll_questions]);
		for($i = 1; $i <= 12; $i++) {
			$p = $i-1;
			$output .= "<tr><td>{$mklib->lang['poll_ad_varpoll']} $i:</td><td><input type=\"text\" name=\"optiontext[]\" value=\"$questions[$p]\" size=\"65\" style=\"width:400px\"></td></tr>";
		}
		$output .= "</table><br>"
        ."<center><input type=\"hidden\" name=\"poll_id\" value=\"$poll_id\">"
		."<input type=\"hidden\" name=\"op\" value=\"save_edit_voting\">"
		."<input type=\"submit\" value=\"{$mklib->lang['poll_ad_saves']}\"></form></center>";
		
    
    $output = $Skin->view_block("{$mklib->lang['poll_ad_adit_poll']}", "$output");
     $mklib->printpage_admin("{$mklib->lang['poll_ad_adit_poll']}", $output);
}

function save_edit_voting() {
    global $mkportals, $mklib, $Skin, $DB, $mklib_board;
    $poll_id = $mkportals->input['poll_id'];
    $polltitle = $mkportals->input['polltitle'];
	$acomm = $mkportals->input['acomm'];
	$optiontext = $_POST['optiontext'];
    $number_optiontext = count($optiontext);
    $op_text = "";
    for ($p=0;$p<$number_optiontext;$p++) {
		if ($optiontext[$p] != "") {
			$op_text .= "$optiontext[$p]|";
		}
    }
    $DB->query("UPDATE mkp_poll SET poll_title='$polltitle', poll_questions='$op_text', acomm='$acomm' WHERE poll_id='$poll_id'");
     Header("Location: index.php?ind=ad_voting&op=edit_voting&poll_id=$poll_id");
}
function config() {
    global $mkportals, $mklib, $Skin, $DB, $mklib_board;
    $off_mod = $mklib->config['mod_poll'];
    $listpoll = $mklib->config['poll_page'];
    $off1 = "checked=\"checked\"";
	if ($off_mod == "1") {
		$off1 = "";
		$off2 = "checked=\"checked\"";
   	}
    $output .= "
	<tr>
	  <td>

	    <form name=\"config\" method=\"post\" action=\"index.php?ind=ad_voting&amp;op=save_config\">
	    <table width=\"100%\">
	    ";
    $output .= "
<tr>
<td>
<table width=\"100%\" cellspacing=\"2\" cellpadding=\"5\" class=\"tabmain\">
    <tr align=\"right\">
    <td>"
    ."[ <a href=\"index.php?ind=ad_voting&amp;op=create_voting\">{$mklib->lang['poll_ad_home_menu']}</a>"
	." | <a href=\"index.php?ind=ad_voting&amp;op=select_remove_voting\">{$mklib->lang['poll_ad_delpoll_menu']}</a>"
	." | <a href=\"index.php?ind=ad_voting&amp;op=select_edit_voting\">{$mklib->lang['poll_ad_aditpoll_menu']}</a>"
	." | <a href=\"index.php?ind=ad_voting&amp;op=config\">{$mklib->lang['poll_ad_conf']}</a> ]
   
    </td></tr>
      <tr>
		<td width=\"50%\" height=\"60\" class=\"modulex\">{$mklib->lang['poll_ad_conf_ofmod']}</td>
		<td width=\"50%\" class=\"modulex\" align=\"center\">{$mklib->lang['ad_yes']}&nbsp;<input type=\"radio\" value=\"1\" name=\"off_mod\"{$off2}/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$mklib->lang['ad_no']}&nbsp;<input type=\"radio\" value=\"0\" name=\"off_mod\"{$off1}/></td>
	      </tr> 
		<tr>
		<td width=\"50%\" height=\"60\" class=\"modulex\">{$mklib->lang['poll_ad_conf_list']}</td>
		<td width=\"50%\" class=\"modulex\" align=\"center\"><input type=\"text\" name=\"conf_list\" value=\"{$listpoll}\" size=\"40\"/></td>
	      </tr>
	      
</table>
</td>
</tr>
	";
    $output .= "
		  <tr>
		<td colspan=\"2\" class=\"titadmin\" align = \"center\"><br />
		  <input type=\"submit\" class=\"mkbutton\" value=\" {$mklib->lang['poll_ad_saves']} \" name=\"B1\" />
		</td>
	      </tr>
	    </table>
	    </form>
	  </td>
	</tr>
	";
    
    $output = $Skin->view_block("{$mklib->lang['poll_ad_conf']}", "$output");
    $mklib->printpage_admin("{$mklib->lang['poll_ad_conf']}", $output);
    
}
function save_config() {
    global $mkportals, $mklib, $Skin, $DB, $mklib_board;
    $off_mod = $mkportals->input['off_mod'];
    $conf_list = $mkportals->input['conf_list'];
    
        $DB->query("UPDATE mkp_config SET valore ='$conf_list' WHERE chiave = 'poll_page'");
		$DB->query("UPDATE mkp_config SET valore ='$off_mod' WHERE chiave = 'mod_poll'");
        Header("Location: index.php?ind=ad_voting&op=config");
}
		}

?>