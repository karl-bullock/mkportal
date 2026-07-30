<?php
/*
+---------------------------------------------------------------------------
|   > made: Support
|   > http://www.rusmkportal.ru
+--------------------------------------------------------------------------
*/
if (!defined("IN_MKP")) {
    die ("Sorry !! You cannot access this file directly.");
}

$idx = new mk_ad_recommend;
class mk_ad_recommend {

	function mk_ad_recommend() {
		global $mkportals, $mklib;
		switch($mkportals->input['op']) {
			case 'main_save':
    				$this->main_save();
    			break;
    			default:
    				$this->recommend_show();
    			break;
    		}
	}


function recommend_show() {
global $mkportals, $mklib, $DB, $Skin;
   		$ofmods = $mklib->config['mod_recommend'];
	$ofmod1 = "checked=\"checked\"";
	if ($ofmods == "1") {
		$ofmod2 = "checked=\"checked\"";
		$ofmod1 = "";
   	}
	
	
$content = "
	<tr>
	  <td>
	    <form name=\"main1\" method=\"post\" action=\"index.php?ind=ad_recommend&op=main_save\">
	    <table width=\"100%\">";
$content .= "
<tr>
<td>
<table width=\"100%\" cellspacing=\"2\" cellpadding=\"5\" class=\"tabmain\">	      
	      <tr>
		<td width=\"50%\" height=\"60\" class=\"modulex\">{$mklib->lang['ad_recommend_offmod']}</td>
		<td width=\"50%\" class=\"modulex\" align=\"center\">{$mklib->lang['ad_yes']}&nbsp;<input type=\"radio\" value=\"1\" name=\"ofmods\" $ofmod2 />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$mklib->lang['ad_no']}&nbsp;<input type=\"radio\" value=\"0\" name=\"ofmods\" $ofmod1 /></td>
	      </tr>	      
</table>
</td>
</tr>
	";

	$content .= "
		  <tr>
		<td colspan=\"2\" class=\"titadmin\" align = \"center\"><br />
		  <input type=\"submit\" class=\"mkbutton\" value=\" {$mklib->lang['ad_save']} \" name=\"B1\" />
		</td>
	      </tr>
	    </table>
	    </form>
	  </td>
	</tr>
	";
$output = $Skin->view_block("{$mklib->lang['ad_recommend']}", $content);
$mklib->printpage_admin("{$mklib->lang['ad_recommend']}", $output);
}

function main_save() {
global $mkportals, $mklib, $Skin, $DB; 
	$ofmods = $mkportals->input['ofmods'];

	$DB->query("UPDATE mkp_config SET valore ='$ofmods' WHERE chiave = 'mod_recommend'");
		$DB->close_db();
		
		Header("Location: index.php?ind=ad_recommend");
}

}
        
?>