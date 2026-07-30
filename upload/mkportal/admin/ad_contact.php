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

$idx = new mk_ad_contact;
class mk_ad_contact {

	function mk_ad_contact() {
		global $mkportals, $mklib;
		$mklib->load_lang("lang_contact.php");
		switch($mkportals->input['op']) {
			case 'main_save':
    				$this->main_save();
    			break;
    			default:
    				$this->contact_show();
    			break;
    		}
	}


function contact_show() {
global $mkportals, $mklib, $DB, $Skin;
   		$ips = $mklib->config['contact_ip'];
   		$ofmods = $mklib->config['mod_contact'];
   		$mailsend = $mklib->config['contact_send'];
	$chec1 = "checked=\"checked\"";
	if ($ips == "1") {
		$chec2 = "checked=\"checked\"";
		$chec1 = "";
   	}
	$ofmod1 = "checked=\"checked\"";
	if ($ofmods == "1") {
		$ofmod2 = "checked=\"checked\"";
		$ofmod1 = "";
   	}
	
	
$content = "
	<tr>
	  <td>
	    <form name=\"main1\" method=\"post\" action=\"index.php?ind=ad_contact&op=main_save\">
	    <table width=\"100%\">";
$content .= "
<tr>
<td>
<table width=\"100%\" cellspacing=\"2\" cellpadding=\"5\" class=\"tabmain\">	      
	      <tr>
		<td width=\"50%\" height=\"60\" class=\"modulex\">{$mklib->lang['mcon_modoffline']}</td>
		<td width=\"50%\" class=\"modulex\" align=\"center\">{$mklib->lang['ad_yes']}&nbsp;<input type=\"radio\" value=\"1\" name=\"ofmods\" $ofmod2 />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$mklib->lang['ad_no']}&nbsp;<input type=\"radio\" value=\"0\" name=\"ofmods\" $ofmod1 /></td>
	      </tr>	      

	      <tr>
		<td width=\"50%\" height=\"60\" class=\"modulex\">{$mklib->lang['mcon_adipsend']}</td>
		<td width=\"50%\" class=\"modulex\" align=\"center\">{$mklib->lang['ad_yes']}&nbsp;<input type=\"radio\" value=\"1\" name=\"ips\" $chec2 />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$mklib->lang['ad_no']}&nbsp;<input type=\"radio\" value=\"0\" name=\"ips\" $chec1 /></td>
		</tr>
</table>
</td>
</tr>
	";
$content .= "
<tr>
<td>
<table width=\"100%\" cellspacing=\"2\" cellpadding=\"5\" class=\"tabmain\">
	      <tr>
		<td width=\"50%\" height=\"60\" class=\"modulex\"><!--" . $mklib->helplink('had_contact') . "--->{$mklib->lang['mcon_emailsend']}</td>
		<td width=\"50%\" class=\"modulex\" align=\"center\"><input class=\"moduleborder\" type=\"text\" name=\"mailsend\" value=\"$mailsend\" size=\"60\" /></td>
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
$output = $Skin->view_block("{$mklib->lang['mcon_mcontact']}", $content);
$mklib->printpage_admin("{$mklib->lang['mcon_mcontact']}", $output);
}

function main_save() {
global $mkportals, $mklib, $Skin, $DB; 

	$mailsend = $mkportals->input['mailsend'];
	$ips = $mkportals->input['ips'];
	$ofmods = $mkportals->input['ofmods'];

	$DB->query("UPDATE mkp_config SET valore ='$ips' WHERE chiave = 'contact_ip'");
	$DB->query("UPDATE mkp_config SET valore ='$ofmods' WHERE chiave = 'mod_contact'");
	$DB->query("UPDATE mkp_config SET valore ='$mailsend' WHERE chiave = 'contact_send'");

		$DB->close_db();
		
		Header("Location: index.php?ind=ad_contact");
}

}
        
?>