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

$idx = new mk_ad_topsite;
class mk_ad_topsite {


	function mk_ad_topsite() {
		global $mkportals;
		switch($mkportals->input['op']) {
			case 'save_main':
    			$this->save_main();
    		break;
			case 'activate':
    			$this->activate();
    		break;
			case 'delete_site':
    			$this->delete_site();
    		break;
			case 'edit_site':
    			$this->edit_site();
    		break;
			case 'update_site':
    			$this->update_site();
    		break;
			default:
    			$this->topsite_show();
    		break;
    		}
	}

	function topsite_show() {
	global $mkportals, $mklib, $Skin, $DB, $mklib_board;

		$topsite_page = $mklib->config['topsite_page'];
          $mklib->load_lang("lang_topsite.php");
          $link_user = $mklib_board->forum_link("profile");
		// Admin Approval combo
		$approval = $mklib->config['approval_topsite'];
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
		$checksave = $mklib->lang['ad_saved'];
   		}
		if ($mkportals->input['mode'] == "activated") {
		$checksave = $mklib->lang['ad_topactived'];
   		}
		if ($mkportals->input['mode'] == "deleted") {
		$checksave = $mklib->lang['ad_topdeleted'];
   		}
		if ($mklib->config['mod_topsite']) {
		$checkactive =  "checked=\"checked\"";
   		}
	 	$content  = "
  
	<tr>
	  <td>

	    <script type=\"text/javascript\">

			function makesuretop() {
			if (confirm('{$mklib->lang[ad_deltopconfirm]}')) {
			return true;
			} else {
			return false;
			}
			}

	    </script>

	    <form action=\"index.php?ind=ad_topsite&amp;op=save_main\" name=\"save_main\" method=\"post\"> 
	    <table width=\"100%\" border=\"0\">	    
	      <tr>
		<td>$checksave</td>
	      </tr>
	      <tr>
		<td class=\"titadmin\">{$mklib->lang['ad_preferences']}</td>
	      </tr>
	      <tr>
		<td><span class=\"mktxtcontr\">{$mklib->lang['ad_topdisactive']}</span> <input type=\"checkbox\" name=\"stato\" value=\"1\" $checkactive /></td>
	      </tr>
	      <tr>
		<td>{$mklib->lang['ad_toppages']}</td>
	      </tr>
	      <tr>
		<td><input type=\"text\" name=\"topsite_page\" value=\"$topsite_page\" size=\"10\" class=\"bgselect\" /></td>
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
	      <tr>
		<td class=\"titadmin\"></td>
	      </tr>       
	    </table>
	    </form>	  
	   
	    <table class=\"moduleborder\" cellspacing=\"1\" cellpadding=\"2\" width=\"98%\" align=\"center\" border=\"0\">
	      <tr>
		<td colspan=\"7\" class=\"titadmin\">{$mklib->lang['ad_topsubmitted']}</td>
	      </tr>
	      <tr>
		<th class=\"modulex mkalign1\" width=\"5%\">{$mklib->lang['ad_activeon']}</th>
		<th class=\"modulex mkalign1\" width=\"5%\">{$mklib->lang['ad_delete']}</th>
		<th class=\"modulex mkalign1\" width=\"5%\">{$mklib->lang['ad_address']}</th>
		<th class=\"modulex mkalign1\" width=\"10%\">{$mklib->lang['ad_email']}</th>
		<th class=\"modulex mkalign1\" width=\"10%\">{$mklib->lang['ad_title']}</th>
		<th class=\"modulex mkalign1\" width=\"30%\">{$mklib->lang['ad_description']}</th>
		<th class=\"modulex mkalign1\" width=\"35%\">{$mklib->lang['to_poster']}</th>
	      </tr>	      
	   ";
		$query = $DB->query( "SELECT id, id_member, autor, data, title, description, link, email, banner, click, rate, trate FROM mkp_topsite WHERE validate = '0' ORDER BY `id`");
		while( $row = $DB->fetch_row($query) ) {
			$idb = $row['id'];
			$banner = $row['banner'];
			$link = $row['link'];
			$titolo = $row['title'];
			$descrizione = $row['description'];
			$email = $row['email'];
			$id_member = $row['id_member'];
			$autor = $row['autor'];

			$content .= "
	      <tr>
		<td class=\"modulecell mkalign1\"><a class=\"mktxtcontr2\" href=\"index.php?ind=ad_topsite&amp;op=activate&amp;ide=$idb\">{$mklib->lang['ad_activeon']}</a></td>
		<td class=\"modulecell mkalign1\"><a class=\"mktxtcontr\" href=\"index.php?ind=ad_topsite&amp;op=delete_site&amp;ide=$idb\" onclick=\"return makesuretop()\">{$mklib->lang['ad_delete']}</a></td>
		<td class=\"modulecell mkalign1\"><a href=\"$link\" target=\"_blank\">$link</a></td>
		<td class=\"modulecell mkalign1\"><a href=\"mailto:$email\">$email</a></td>
		<td class=\"modulecell mkalign1\">$titolo</td>
		<td class=\"modulecell mkalign1\">$descrizione</td>
		<td class=\"modulecell mkalign1\" ><b><a href=\"$link_user=$id_member\">$autor</a></b></td>
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
		<td colspan=\"7\" class=\"titadmin\">{$mklib->lang['ad_topactivated']}</td>
	      </tr>
	      <tr>
		<th class=\"modulex mkalign1\" width=\"5%\">{$mklib->lang['ad_edit']}</th>
		<th class=\"modulex mkalign1\" width=\"5%\">{$mklib->lang['ad_delete']}</th>
		<th class=\"modulex mkalign1\" width=\"5%\">{$mklib->lang['ad_address']}</th>
		<th class=\"modulex mkalign1\" width=\"10%\">{$mklib->lang['ad_email']}</th>
		<th class=\"modulex mkalign1\" width=\"10%\">{$mklib->lang['ad_title']}</th>
		<th class=\"modulex mkalign1\" width=\"30%\">{$mklib->lang['ad_description']}</th>
		<th class=\"modulex mkalign1\" width=\"35%\">{$mklib->lang['to_poster']}</th>
	      </tr>	      
	   ";
		$query = $DB->query( "SELECT id, id_member, autor, data, title, description, link, email, banner, click, rate, trate FROM mkp_topsite WHERE validate = '1' ORDER BY `id` DESC");
		while( $row = $DB->fetch_row($query) ) {
			$idb = $row['id'];
			$banner = $row['banner'];
			$link = $row['link'];
			$titolo = $row['title'];
			$descrizione = $row['description'];
			$email = $row['email'];
			$id_member = $row['id_member'];
			$autor = $row['autor'];

			$content .= "
	      <tr>
		<td class=\"modulecell mkalign1\"><a class=\"mktxtcontr2\" href=\"index.php?ind=ad_topsite&amp;op=edit_site&amp;ide=$idb\">{$mklib->lang['ad_edit']}</a></td>
		<td class=\"modulecell mkalign1\"><a class=\"mktxtcontr\" href=\"index.php?ind=ad_topsite&amp;op=delete_site&amp;ide=$idb\" onclick=\"return makesuretop()\">{$mklib->lang['ad_delete']}</a></td>
		<td class=\"modulecell mkalign1\"><a href=\"$link\" target=\"_blank\">$link</a></td>
		<td class=\"modulecell mkalign1\"><a href=\"mailto:$email\">$email</a></td>
		<td class=\"modulecell mkalign1\">$titolo</td>
		<td class=\"modulecell mkalign1\">$descrizione</td>
		<td class=\"modulecell mkalign1\" ><b><a href=\"$link_user=$id_member\">$autor</a></b></td>
	      </tr>
			";
		}

	 $content  .= "
	   
	    </table>
	    <br /><br /><br />

	  </td>
	</tr>
		";
		$output = $Skin->view_block("{$mklib->lang['ad_toptitle']}", "$content");
		$mklib->printpage_admin($mklib->lang['ad_titlepage'].$mklib->lang['tt_sep'].$mklib->lang['ad_toptitle'], $output);

	}

	function save_main() {
    	global $mkportals, $DB, $mklib;
		$topsite_page = $mkportals->input['topsite_page'];
		$mod_topsite = intval($mkportals->input['stato']);
		$approval = $mkportals->input['approvalc'];
		if (!$topsite_page) {
			$message = "{$mklib->lang['ad_all_rows']}";
			$mklib->error_page($message);
			exit;
		}
		$DB->query("UPDATE mkp_config SET valore ='$topsite_page' WHERE chiave = 'topsite_page'");
		$DB->query("UPDATE mkp_config SET valore ='$mod_topsite' WHERE chiave = 'mod_topsite'");
		$DB->query("UPDATE mkp_config SET valore ='$approval' WHERE chiave = 'approval_topsite'");
		
		$DB->close_db();
		Header("Location: index.php?ind=ad_topsite&mode=saved");
		exit;
  	}
	function activate() {
    	global $mkportals, $DB;
		$ide = intval($mkportals->input['ide']);

		$DB->query("UPDATE mkp_topsite SET validate ='1' WHERE id = '$ide'");
		$query = $DB->query( "SELECT id FROM mkp_topsite WHERE validate = '1'");
		$count = $DB->get_num_rows($query);
		$DB->query("UPDATE mkp_stat SET valore ='$count' WHERE chiave = 'tot_topsite'");
		$DB->close_db();
		Header("Location: index.php?ind=ad_topsite&mode=activated");
		exit;
  	}

	function delete_site() {
    	global $mkportals, $DB;
		$ide = intval($mkportals->input['ide']);

		$DB->query("DELETE FROM mkp_topsite WHERE id = '$ide'");
		$query = $DB->query( "SELECT id FROM mkp_topsite WHERE validate = '1'");
		$count = $DB->get_num_rows($query);
		$DB->query("UPDATE mkp_stat SET valore ='$count' WHERE chiave = 'tot_topsite'");
		$DB->close_db();
		Header("Location: index.php?ind=ad_topsite&mode=deleted");
		exit;
  	}
	function update_site() {
    	global $mkportals, $DB, $mklib;
		$ide = intval($mkportals->input['ide']);
		$title = $mkportals->input['title'];
		$description = $mkportals->input['description'];
		$link = $mkportals->input['link'];
		$banner = $mkportals->input['banner'];
		$banner2 = $mkportals->input['banner2'];
		$email = $mkportals->input['email'];

		if (!$title || !$description || !$link || !$email) {
			$message = "{$mklib->lang['ad_all_rows']}";
			$mklib->error_page($message);
			exit;
		}

		$DB->query("UPDATE mkp_topsite SET title ='$title', description ='$description', link ='$link', banner ='$banner', banner2 ='$banner2', email = '$email' WHERE id = '$ide'");
		$DB->close_db();
		Header("Location: index.php?ind=ad_topsite&mode=saved");
		exit;
  	}

	function edit_site() {
		global $mkportals, $DB, $mklib, $Skin;

		$ide = intval($mkportals->input['ide']);

		$query = $DB->query( "SELECT title, description, link, banner, banner2, email FROM mkp_topsite WHERE id = '$ide'");
		$row = $DB->fetch_row($query);
		$link = $row['link'];
		$title = $row['title'];
		$description = $row['description'];
		$banner = $row['banner'];
		$banner2 = $row['banner2'];
		$email = $row['email'];

		$output = "
	<tr>
	  <td><br />
	  
	    <table class=\"moduleborder\" cellspacing=\"1\" cellpadding=\"0\" width=\"100%\" align=\"center\" border=\"0\">
	      <tr>
		<td>
		  <table class=\"modulebg\" cellspacing=\"1\" cellpadding=\"0\" width=\"100%\" border=\"0\">
		    <tr>
		      <td class=\"tdblock\" width=\"100%\" height=\"25\"><img class=\"mkicon\" src=\"$mklib->images/arrow.gif\" alt=\"\" />{$mklib->lang['ad_topeditsite']}</td>
		    </tr>
		    <tr>
		      <td>
			<table class=\"moduleborder\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" border=\"0\">
			  <tr>
			    <td>
			      <table class=\"moduleborder\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" border=\"0\">
				<tr>				 
				  <td class=\"modulex\">
				  
				    <form action=\"index.php?ind=ad_topsite&amp;op=update_site&amp;ide=$ide\" name=\"e_b\" method=\"post\">
				    <table width=\"100%\" border=\"0\">
				      <tr>
					<td class=\"titadmin\"><br />{$mklib->lang['ad_topeditdata']}<br /><br /> </td>
				      </tr>
				      <tr>
					<td>{$mklib->lang['ad_topname']}</td>
				      </tr>
				      <tr>
					<td><input type=\"text\" name=\"title\" value=\"$title\" size=\"70\" class=\"bgselect\" /></td>
				      </tr>
				      <tr>
					<td>{$mklib->lang['ad_topurl']}</td>
				      </tr>
				      <tr>
					<td><input type=\"text\" name=\"link\" value=\"$link\" size=\"70\" class=\"bgselect\" /></td>
				      </tr>
				      <tr>
					<td>{$mklib->lang['ad_topemailw']}</td>
				      </tr>
				      <tr>
					<td><input type=\"text\" name=\"email\" value=\"$email\" size=\"70\" class=\"bgselect\" /></td>
				      </tr>
				      <tr>
					<td>{$mklib->lang['ad_topdesc']}</td>
				      </tr>
				      <tr>
					<td><textarea rows=\"15\" name=\"description\" cols=\"100\">$description</textarea></td>
				      </tr>
				      
				      
					<td><input type=\"submit\" value=\"{$mklib->lang['ad_save']}\" class=\"mkbutton\" /></td>
				      </tr>
				    </table>
				    </form>
	
				  </td>
				</tr>
			      </table>
			    </td>
			  </tr>
			</table>
		      </td>
		    </tr>
		  </table>
		</td>
	      </tr>
	    </table>
	  </td>
	</tr>
	";
		$output = $Skin->view_block("{$mklib->lang['ad_topedittitle']}", "$output");
		$mklib->printpage_admin($mklib->lang['ad_titlepage'].$mklib->lang['tt_sep'].$mklib->lang['ad_toptitle'], $output);

	}

}

?>
