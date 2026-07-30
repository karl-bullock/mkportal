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
$idx = new mk_ad_quote;
class mk_ad_quote {


	function mk_ad_quote() {
		global $mkportals;
		switch($mkportals->input['op']) {
			case 'save_main':
    			$this->save_main();
    		break;
			case 'activate':
    			$this->activate();
    		break;
			case 'delete':
    			$this->delete();
    		break;
			default:
    			$this->quote_show();
    		break;
    		}
	}

	function quote_show() {
	global $mkportals, $mklib, $Skin, $DB;

		$quote_page = $mklib->config['quote_page'];

		// Admin Approval combo
		$approval = $mklib->config['approval_quote'];
		switch($approval) {
			case '1':
    			$selap1="selected=\"selected\"";
    		break;
			case '2':
    			$selap2="selected=\"selected\"";
    		break;
			case '3':
    			$selap3="selected=\"selected\"";
    		break;
    		default:
    			$selap="selected=\"selected\"";
    		break;
		}
		$cselecta = "<option value=\"0\" $selap>{$mklib->lang['ad_approp_0']}</option>\n";
		$cselecta .= "<option value=\"1\" $selap1>{$mklib->lang['ad_approp_1']}</option>\n";
		$cselecta .= "<option value=\"2\" $selap2>{$mklib->lang['ad_approp_2']}</option>\n";
		$cselecta .= "<option value=\"3\" $selap3>{$mklib->lang['ad_approp_3']}</option>\n";

		if ($mkportals->input['mode'] == "saved") {
		$checksave = "{$mklib->lang['ad_saved']}";
   		}
		if ($mkportals->input['mode'] == "activated") {
		$checksave = "{$mklib->lang['ad_quotactived']}";
   		}
		if ($mkportals->input['mode'] == "deleted") {
		$checksave = "{$mklib->lang['ad_quotdeleted']}";
   		}
		if ($mklib->config['mod_quote']) {
		$checkactive =  "checked=\"checked\"";
   		}
	 	$content  = "
  
      <tr>
	<td>

	  <script type=\"text/javascript\">

			function makesuretop() {
			if (confirm('{$mklib->lang[ad_delquotconfirm]}')) {
			return true;
			} else {
			return false;
			}
			}

	  </script>

	  <form action=\"index.php?ind=ad_quote&amp;op=save_main\" name=\"save_main\" method=\"post\">
	  <table width=\"100%\" border=\"0\">	   
	    <tr>
	      <td>$checksave</td>
	    </tr>
	    <tr>
	      <td class=\"titadmin\">{$mklib->lang['ad_preferences']}</td>
	    </tr>
	    <tr>
	      <td><span class=\"mktxtcontr\">{$mklib->lang['ad_quotdisactive']}</span> <input type=\"checkbox\" name=\"stato\" value=\"1\" $checkactive /></td>
	    </tr>
	    <tr>
	      <td>{$mklib->lang['ad_quotpages']}</td>
	    </tr>
	    <tr>
	      <td><input type=\"text\" name=\"quote_page\" value=\"$quote_page\" size=\"10\" class=\"bgselect\" /></td>
	    </tr>
		<tr>
		<td>{$mklib->lang['ad_apprtit']}</td>
	      </tr>
	      <tr>
		<td>
		  <select class=\"bgselect\" size=\"1\" name=\"approvalc\">
		  {$cselecta}
		  </select>
		</td>
	      </tr>
	    <tr>
	      <td><br /><input type=\"submit\" name=\"Salve\" value=\"{$mklib->lang['ad_save']}\" class=\"mkbutton\" /></td>
	    </tr>       
	  </table>
	  </form>
	  
	</td>
      </tr>
      <tr>
	<td class=\"titadmin\"><br /></td>
      </tr>
      <tr>
	<td>
	  
	  <table class=\"moduleborder\" cellspacing=\"1\" cellpadding=\"2\" width=\"98%\" align=\"center\" border=\"0\">
	    <tr>
	      <td colspan=\"4\" class=\"titadmin\">{$mklib->lang['ad_quotsubmitted']}</td>
	    </tr>
	    <tr>
	      <th class=\"modulex mkalign1\" width=\"5%\">{$mklib->lang['ad_activeon']}</th>
	      <th class=\"modulex mkalign1\" width=\"5%\">{$mklib->lang['ad_delete']}</th>
	      <th class=\"modulex mkalign1\" width=\"20%\">{$mklib->lang['ad_author']}</th>
	      <th class=\"modulex mkalign1\" width=\"70%\">{$mklib->lang['ad_quottext']}</th>
	    </tr>	   
	   ";
		$query = $DB->query( "SELECT id, member, quote, validate FROM mkp_quotes WHERE validate = '0' ORDER BY `id`");
		while( $row = $DB->fetch_row($query) ) {
			$idb = $row['id'];
			$member = $row['member'];
			$quote = $row['quote'];

			$content .= "
	    <tr>
    	      <td class=\"modulecell mkalign1\"><a class=\"mktxtcontr2\" href=\"index.php?ind=ad_quote&amp;op=activate&amp;ide=$idb\">{$mklib->lang['ad_activeon']}</a></td>
	      <td class=\"modulecell mkalign1\"><a class=\"mktxtcontr\" href=\"index.php?ind=ad_quote&amp;op=delete&amp;ide=$idb\" onclick=\"return makesuretop()\">{$mklib->lang['ad_delete']}</a></td>
	      <td class=\"modulecell mkalign1\">$member</td>
	      <td class=\"modulecell mkalign1\">$quote</td>
    	    </tr>
			";
		}
	 $content  .= "	   
	  </table><br />
	  
	</td>
      </tr>
      <tr>
	<td class=\"titadmin\"><br /></td>
      </tr>
      <tr>
	<td>
	   
	  <table class=\"moduleborder\" cellspacing=\"1\" cellpadding=\"2\" width=\"98%\" align=\"center\" border=\"0\">
	    <tr>
	      <th colspan=\"3\" class=\"titadmin\">{$mklib->lang['ad_quotactivated']}</th>
	    </tr>
	    <tr>
	      <th class=\"modulex mkalign1\" width=\"10%\">{$mklib->lang['ad_delete']}</th>
	      <th class=\"modulex mkalign1\" width=\"20%\">{$mklib->lang['ad_author']}</th>
	      <th class=\"modulex mkalign1\" width=\"70%\">{$mklib->lang['ad_quottext']}</th>
	    </tr>	   
	   ";
		$query = $DB->query( "SELECT id, member, quote, validate FROM mkp_quotes WHERE validate = '1' ORDER BY `id`");
		while( $row = $DB->fetch_row($query) ) {
			$idb = $row['id'];
			$member = $row['member'];
			$quote = $row['quote'];
			$idb = $row['id'];
			$banner = $row['banner'];
			$link = $row['link'];
			$titolo = $row['title'];
			$descrizione = $row['description'];
			$email = $row['email'];

			$content .= "
	    <tr>
	      <td class=\"modulecell mkalign1\"><a class=\"mktxtcontr\" href=\"index.php?ind=ad_quote&amp;op=delete&amp;ide=$idb\" onclick=\"return makesuretop()\">{$mklib->lang['ad_delete']}</a></td>
	      <td class=\"modulecell mkalign1\">$member</td>
	      <td class=\"modulecell mkalign1\">$quote</td>
    	    </tr>
			";
		}

	 $content  .= "	   
	  </table>
	  <br /><br /><br />
	  
	</td>
      </tr>
		";
		$output = $Skin->view_block("{$mklib->lang['ad_quottitle']}", "$content");
		$mklib->printpage_admin($mklib->lang['ad_titlepage'].$mklib->lang['tt_sep'].$mklib->lang['ad_quottitle'], $output);

	}

	function save_main() {
    	global $mkportals, $DB, $mklib;
		$quote_page = $mkportals->input['quote_page'];
		$mod_quote = intval($mkportals->input['stato']);
		$approval = $mkportals->input['approvalc'];
		if (!$quote_page) {
			$message = "{$mklib->lang['ad_all_rows']}";
			$mklib->error_page($message);
			exit;
		}
		$DB->query("UPDATE mkp_config SET valore ='$quote_page' WHERE chiave = 'quote_page'");
		$DB->query("UPDATE mkp_config SET valore ='$mod_quote' WHERE chiave = 'mod_quote'");
		$DB->query("UPDATE mkp_config SET valore ='$approval' WHERE chiave = 'approval_quote'");
		$DB->close_db();
		Header("Location: index.php?ind=ad_quote&mode=saved");
		exit;
  	}
	function activate() {
    	global $mkportals, $DB;
		$ide = intval($mkportals->input['ide']);

		$DB->query("UPDATE mkp_quotes SET validate ='1' WHERE id = '$ide'");
		$query = $DB->query( "SELECT id FROM mkp_quotes WHERE validate = '1'");
		$count = $DB->get_num_rows($query);
		$DB->query("UPDATE mkp_stat SET valore ='$count' WHERE chiave = 'tot_quotes'");
		$DB->close_db();
		Header("Location: index.php?ind=ad_quote&mode=activated");
		exit;
  	}

	function delete() {
    	global $mkportals, $DB;
		$ide = intval($mkportals->input['ide']);

		$DB->query("DELETE FROM mkp_quotes WHERE id = '$ide'");
		$query = $DB->query( "SELECT id FROM mkp_quotes WHERE validate = '1'");
		$count = $DB->get_num_rows($query);
		$DB->query("UPDATE mkp_stat SET valore ='$count' WHERE chiave = 'tot_quotes'");
		$DB->close_db();
		Header("Location: index.php?ind=ad_quote&mode=deleted");
		exit;
  	}


}

?>
