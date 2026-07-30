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

$idx = new mk_ad_news;
class mk_ad_news {


	function mk_ad_news() {
		global $mkportals;
		switch($mkportals->input['op']) {
			case 'add_event':
    			$this->add_event();
    		break;
			case 'edit_event':
    			$this->edit_event();
    		break;
			case 'save_main':
    			$this->save_main();
    		break;
			case 'update_event':
    			$this->update_event();
    		break;
			case 'delete_event':
    			$this->delete_event();
		break;
			case 'submit_news':
			$this->submit_news();
		break;
			case 'reg_data':
    			$this->reg_data();
		break;
			case 'edit_news':
    			$this->edit_news();
    		break;
			case 'update_news':
			$this->update_news();
		break;
			default:
    			$this->news_show();
    		break;
    		}
	}

	function news_show() {
	global $mkportals, $mklib, $Skin, $DB;

		// Admin Approval combo
		$approval = $mklib->config['approval_news'];
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
		$news_page = $mklib->config['news_page'];
		$news_block= $mklib->config['news_block'];
		$news_words= $mklib->config['news_words'];
		
		$cselecta = "<option value=\"0\" $selap>{$mklib->lang['ad_approp_0']}</option>\n";
		$cselecta .= "<option value=\"1\" $selap1>{$mklib->lang['ad_approp_1']}</option>\n";
		$cselecta .= "<option value=\"2\" $selap2>{$mklib->lang['ad_approp_2']}</option>\n";
		$cselecta .= "<option value=\"3\" $selap3>{$mklib->lang['ad_approp_3']}</option>\n";

	/*	$query = $DB->query( "SELECT id, titolo FROM mkp_news_sections ORDER BY `id` DESC");
		while( $row = $DB->fetch_row($query) ) {
			$idevento = $row['id'];
			$evento = $row['titolo'];
			$cselect.= "<option value='$idevento'>$evento</option>\n";
		}
		$news_page = $mklib->config['news_page'];
		$news_block= $mklib->config['news_block'];
		$news_words= $mklib->config['news_words'];
		
		if ($mklib->config['mod_news']) {
		$checkactive =  "checked=\"checked\"";
   		}

		if ($mklib->config['news_html']) {
		$checkactive2 =  "checked=\"checked\"";
   		}

		if ($mkportals->input['mode'] == "saved") {
		$checksave = "{$mklib->lang['ad_saved']}";
		}

		if ($mkportals->input['mode'] == "saved_news") {
		$checksave = "{$mklib->lang['ad_savednews']}";
		}
*/
	 	$content  = "
		
	<tr>
	  <td>

	    <script type=\"text/javascript\">

			function makesurenew() {
			if (confirm('{$mklib->lang[ad_delsecconfirm]}')) {
			return true;
			} else {
			return false;
			}
			}

	    </script>
			
	    <form action=\"index.php?ind=ad_news&amp;op=save_main\" name=\"save_main\" method=\"post\">
	    <table width=\"100%\" border=\"0\">
	      <tr>
		<td>$checksave</td>
	      </tr>
	      <tr>
		<td class=\"titadmin\">{$mklib->lang['ad_preferences']}</td>
	      </tr>
	      <tr>
		<td><span class=\"mktxtcontr\">{$mklib->lang['ad_newdisactive']}</span> <input type=\"checkbox\" name=\"stato\" value=\"1\" $checkactive /></td>
	      </tr>
	      <tr>
		<td>{$mklib->lang['ad_newspages']}</td>
	      </tr>
	      <tr>
		<td><input type=\"text\" name=\"news_page\" value=\"$news_page\" size=\"10\" class=\"bgselect\" /></td>
	      </tr>
	      <tr>
		<td>{$mklib->lang['ad_newsblockp']}</td>
	      </tr>
	      <tr>
		<td><input type=\"text\" name=\"news_block\" value=\"$news_block\" size=\"10\" class=\"bgselect\" /></td>
	      </tr>
	      <tr>
		<td>{$mklib->lang['ad_newsmaxwords']}</td>
	      </tr>
	      <tr>
		<td><input type=\"text\" name=\"news_words\" value=\"$news_words\" size=\"10\" class=\"bgselect\" /></td>
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

	   
	    <table width=\"100%\" border=\"0\">
	      <tr>
		<td class=\"titadmin\"><a href=\"index.php?ind=ad_categories\">{$mklib->lang['ad_addsection']}</a></td>
	      </tr>
	      
	    </table>
	  

	    <table width=\"100%\" border=\"0\">
	      <tr>
		<td colspan=\"2\" class=\"titadmin\">{$mklib->lang['ad_newsnew']}</td>
	      </tr>
	      <tr>
		<td width=\"1%\">
		  <p>&nbsp;&nbsp;<a href=\"index.php?ind=ad_news&amp;op=submit_news\"><img src=\"images/icons/foood/block.gif\" border=\"0\" alt=\"\" /></a><br /><a href=\"index.php?ind=ad_news&amp;op=submit_news\">{$mklib->lang['ad_newsnew']}</a></p>
		</td>
	      </tr>
	      <tr>
		<td colspan=\"2\">{$mklib->lang['ad_newsfilt']}</td>
	      </tr>
	    </table>

	    <form action=\"index.php?ind=ad_news&amp;op=edit_news\" name=\"edit news\" method=\"post\">
	    <table width=\"100%\" border=\"0\">
	      <tr>
		<td colspan=\"2\" class=\"titadmin\">{$mklib->lang['ad_newsedit']}</td>
	      </tr>
	      <tr>
		<td><input type=\"text\" name=\"idnews\" size=\"10\" class=\"bgselect\" />&nbsp;<input type=\"submit\" name=\"editnews\" value=\"{$mklib->lang['ad_edit']}\" class=\"mkbutton\" /></td>
		</tr>
	      <tr>
		<td colspan=\"2\">{$mklib->lang['ad_newsedit2']}</td>
	      </tr>
	    </table>
	    </form>

	  </td>
	</tr>
		";
		$output = $Skin->view_block("{$mklib->lang['ad_newstitle']}", "$content");
		$mklib->printpage_admin($mklib->lang['ad_titlepage'].$mklib->lang['tt_sep'].$mklib->lang['ad_newstitle'], $output);

	}

	function save_main() {
    	global $mkportals, $DB, $mklib;
		$news_page = intval($mkportals->input['news_page']);
		$news_block = intval($mkportals->input['news_block']);
		$news_words = $mkportals->input['news_words'];
		$approval = $mkportals->input['approvalc'];
		if (!$news_page || !$news_block) {
			$message = "{$mklib->lang['ad_all_rows']}";
			$mklib->error_page($message);
			exit;
		}
		$mod_news = intval($mkportals->input['stato']);

		$DB->query("UPDATE mkp_config SET valore ='$news_page' WHERE chiave = 'news_page'");
		$DB->query("UPDATE mkp_config SET valore ='$news_block' WHERE chiave = 'news_block'");
		$DB->query("UPDATE mkp_config SET valore ='$mod_news' WHERE chiave = 'mod_news'");
		$DB->query("UPDATE mkp_config SET valore ='$news_words' WHERE chiave = 'news_words'");
		$DB->query("UPDATE mkp_config SET valore ='$approval' WHERE chiave = 'approval_news'");
		$DB->close_db();
		Header("Location: index.php?ind=ad_news&mode=saved");
		exit;
  	}

 	function submit_news() {
		global $mkportals, $DB, $mklib, $Skin, $editorscript;

		$mklib->load_lang("lang_news.php");

		$editorscript = 1;
		$textarepar = "mce_editable=\"true\"";
		$textarew = "100%";
		$bbeditor= "";
		if ($mklib->mkeditor == "BBCODE") {
			$editorscript = "";
			$textarepar = "";
			$textarew = "75%";
			$bbeditor= $mklib->get_bbeditor();
			$bbeditor2= $mklib->get_bbeditor("short");
		}

		if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_send_news']) {
			$message = "{$mklib->lang['ne_noautsendn']}";
			$mklib->error_page($message);
			exit;
		}
	$sql = "SELECT id, title, parentid FROM mkp_categories WHERE module='news' ORDER BY id";
	$result = $DB->query($sql);
	$modname ='news';
		while ($row = $DB->fetch_row($result)) {
			$cid2 = $row[id];
			$title = $row[title];
			$parentid2 = $row[parentid];
			if ($parentid2!=0) $title=$mklib->getcategor($parentid2,$title,$modname);
			$cselect .="<option value=\"$cid2\">$title</option>";
		}
		$content = "
		<tr>
		  <td>
		    <form action=\"index.php?ind=ad_news&amp;op=reg_data\" method=\"post\" class=\"editor\" name=\"editor\">
		    <table width=\"100%\">
		      <tr>
			<td class=\"tdblock\">
			{$mklib->lang['ne_title']}: <input type=\"text\" name=\"titlepage\"  size=\"40\" />
			</td>
		      </tr>
		      <tr>
			<td class=\"tdblock\">
			{$mklib->lang['ne_category']}: <select name=\"categoria\" size=\"1\" class=\"bgselect\">
			{$cselect}
			</select>
			</td>
		      </tr>
		   </tr>
		    <tr>
   			<td class=\"tdblock\" valign=\"top\">{$mklib->lang['short_testo']} {$mklib->lang['ne_news']}<br>
			$bbeditor2
 			<textarea id=\"short\" name=\"short\" $textarepar style=\"width: $textarew\" rows=\"14\" cols=\"40\">$short_testo</textarea>
			</td>
		      </tr>
		      <tr>
   			<td class=\"tdblock\" valign=\"top\">{$mklib->lang['full_testo']} {$mklib->lang['ne_news']}<br>
			$bbeditor
 			<textarea id=\"ta\" name=\"ta\" $textarepar style=\"width: $textarew\" rows=\"14\" cols=\"40\">$testo</textarea>
			</td>
		      </tr>
		<tr>
        
        <td><input type=\"checkbox\" name=\"approve\" value=\"1\" checked> {$mklib->lang['ne_approve']}<br /><br />

	<input type=\"checkbox\" name=\"allow_main\" value=\"1\" checked> {$mklib->lang['ne_allow_main']}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type=\"checkbox\" name=\"allow_comm\" value=\"1\" checked> {$mklib->lang['ne_allow_comm']}<br />

	<input type=\"checkbox\" name=\"allow_rating\" value=\"1\" checked> {$mklib->lang['ne_allow_rating']}&nbsp;&nbsp;&nbsp;
		<input type=\"checkbox\" name=\"pinned\" value=\"1\"> {$mklib->lang['ne_pinned']}<br />
   </td>
	</tr>
		      <tr>
			<td>
			  <input type=\"submit\" name=\"ok\" value=\"{$mklib->lang['ne_save']}\" class=\"mkbutton\" />
			</td>
		      </tr>
		    </table>
		    </form>
		  </td>
		</tr>

	";
		$output = $Skin->view_block("{$mklib->lang['ne_insertn']}", "$content");
		$mklib->printpage_admin($mklib->lang['ad_titlepage'].$mklib->lang['tt_sep'].$mklib->lang['ad_newstitle'], $output);
	}

	function reg_data() {
    	global $mkportals, $DB, $std, $print, $mklib, $mklib_board;

		//Check perms
		if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_access_cpa']) {
			$message = "{$mklib->lang['ad_noperms']}";
			$mklib->error_page($message);
			exit;
		}

		if (!$_POST['ta']) {
			$message = "{$mklib->lang['ne_inserttx']}";
			$mklib->error_page($message);
			exit;
		}
		if (!$mkportals->input['titlepage']) {
			$message = "{$mklib->lang['ne_insterttit']}";
			$mklib->error_page($message);
			exit;
		}
		if (!$mkportals->input['categoria']) {
			$message = "{$mklib->lang['ne_createcat']}";
			$mklib->error_page($message);
			exit;
		}
		$idaut = $mkportals->member['id'];
		$categoria= $mkportals->input['categoria'];
		$testo = $_POST['ta'];
		
		//normally done by $mkportals->input
		$testo = str_replace( "\r", "", $testo);
		//If newline is not preceeded by ">" and is not followed by "</" tag
		//$testo = preg_replace( "/(?<!\>)\n(?!\<\/(.+?)\>)/"  , "<br />"   , $testo ); //working

		$testo = $mklib->convert_savedbadmin($testo);
		$short = $_POST['short'];
		$short = str_replace( "\r", "", $short);
		$short = $mklib->convert_savedbadmin($short);

		$autore = $mkportals->member['name'];
		$titolo = $mkportals->input['titlepage'];
		$titolo = $mklib->convert_savedbadmin($titolo);
		$pinned = intval($mkportals->input['pinned']);
		$cdata = time();
		$validat = "1";
		$allow_main = intval($mkportals->input['allow_main']);
		$allow_comm = intval($mkportals->input['allow_comm']);
		$allow_rating = intval($mkportals->input['allow_rating']);

		$query="INSERT INTO mkp_news(idcategoria, idautore, titolo, autore, short_testo, testo, data, validate, pinned, allow_main, allow_comm, allow_rating)VALUES('$categoria', '$idaut', '$titolo', '$autore', '$short', '$testo', '$cdata', '$validat', '$pinned', '$allow_main', '$allow_comm', '$allow_rating')";
		$DB->query($query);
		$DB->close_db();
		Header("Location: index.php?ind=ad_news&mode=saved_news");
		exit;
		}


  		function edit_news() {
	  	global $mkportals, $DB, $mklib, $Skin, $editorscript;

	  	$mklib->load_lang("lang_news.php");

		$editorscript = 1;
		$textarepar = "mce_editable=\"true\"";
		$textarew = "100%";
		$bbeditor= "";
		if ($mklib->mkeditor == "BBCODE") {
			$editorscript = "";
			$textarepar = "";
			$textarew = "75%";
			$bbeditor= $mklib->get_bbeditor();
			$bbeditor2= $mklib->get_bbeditor("short");
		}

		if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_access_cpa']) {
			$message = "{$mklib->lang['ne_noautmodn']}";
			$mklib->error_page($message);
			exit;
		}
		$idnews = intval($mkportals->input['idnews']);
		$query = $DB->query( "SELECT idcategoria, titolo, short_testo, testo, pinned FROM mkp_news WHERE id = '$idnews'");
		$row = $DB->fetch_row($query);
		if(!$row) {
            		$message = "{$mklib->lang['error_404']}";
            		$mklib->error_page($message);
            		exit;
		}
		$idcategoria = $row['idcategoria'];
		$titolo = stripslashes($row['titolo']);
		$testo = stripslashes($row['testo']);
		$short_testo = stripslashes($row['short_testo']);
		//if ($mklib->mkeditor == "BBCODE") {
			//$testo = str_replace("<br />", "\n", $testo);
		//}
		$active = $row['pinned'] ? 'checked="checked"' : '';

		 $modname ='news';
		$query = $DB->query( "SELECT id, title, parentid FROM mkp_categories WHERE module='$modname' AND id = '$idcategoria' ");
		while( $row = $DB->fetch_row($query) ) {
			$idevento = $row['id'];
			$evento = $row['title'];
			$parentid = $row['parentid'];
			$cselect .= "<option value=\"$idevento\">$evento</option>\n";
		}
		
		$sql = "SELECT id, title, parentid FROM mkp_categories WHERE module='$modname' ORDER BY parentid,title";
	$result = $DB->query($sql);
		while ($row = $DB->fetch_row($result)) {
			$cid2 = $row[id];
			$title = $row[title];
			$parentid2 = $row[parentid];
			if ($parentid2!=0) $title=$mklib->getcategor($parentid2,$title,$modname);
			$cselect .="<option value=\"$cid2\">$title</option>";
		}
		$content = "
		<tr>
		  <td>
		    <form action=\"index.php?ind=ad_news&amp;op=update_news&amp;idnews=$idnews\" method=\"post\" class=\"editor\" name=\"editor\">
		    <table width=\"100%\">
		      <tr>
			<td class=\"tdblock\">
			{$mklib->lang['ne_title']}:<input type=\"text\" name=\"titlepage\" value=\"$titolo\" size=\"40\" />
			&nbsp;{$mklib->lang['ne_pinned']}&nbsp;<input type=\"checkbox\" name=\"pinned\" value=\"1\"$active />
			</td>
		      </tr>
		      <tr>
			<td class=\"tdblock\">
			  {$mklib->lang['ne_category']}: <select name=\"categoria\" size=\"1\" class=\"bgselect\">
			  {$cselect}
			  </select>
			</td>
		      </tr>
		    <tr>
   			<td class=\"tdblock\" valign=\"top\">{$mklib->lang['short_testo']} {$mklib->lang['ne_news']}<br>
			$bbeditor2
 			<textarea id=\"short\" name=\"short\" $textarepar style=\"width: $textarew\" rows=\"14\" cols=\"40\">$short_testo</textarea>
			</td>
		      </tr>
		      <tr>
   			<td class=\"tdblock\" valign=\"top\">{$mklib->lang['full_testo']} {$mklib->lang['ne_news']}<br>
			$bbeditor
 			<textarea id=\"ta\" name=\"ta\" $textarepar style=\"width: $textarew\" rows=\"14\" cols=\"40\">$testo</textarea>
			</td>
		      </tr>
		      <tr>
			<td>
			  <input type=\"submit\" name=\"ok\" value=\"{$mklib->lang['ne_save']}\" class=\"mkbutton\" />
			</td>
		      </tr>
		    </table>
		    </form>
		  </td>
		</tr>
	";
		$output = $Skin->view_block("{$mklib->lang['ne_editn']}", "$content");
		$mklib->printpage_admin($mklib->lang['ad_titlepage'].$mklib->lang['tt_sep'].$mklib->lang['ad_newstitle'], $output);

  }

	function update_news() {
    		global $mkportals, $DB, $std, $print, $mklib;

		if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_access_cpa']) {
			$message = "{$mklib->lang['ne_noautmodn']}";
			$mklib->error_page($message);
			exit;
		}

		if (!$_POST['ta']) {
			$message = "{$mklib->lang['ne_inserttx']}";
			$mklib->error_page($message);
			exit;
		}
		 if (!$mkportals->input['short']) {
			$message = "{$mklib->lang['ne_inserttx']}";
			$mklib->error_page($message);
			exit;
		}
		if (!$mkportals->input['titlepage']) {
			$message = "{$mklib->lang['ne_inserttit']}";
			$mklib->error_page($message);
			exit;
		}
		if (!$mkportals->input['categoria']) {
			echo "{$mklib->lang['ne_createcat']}";
			exit;
		}
		$categoria= $mkportals->input['categoria'];
		$testo = $_POST['ta'];

		//normally done by $mkportals->input
		$testo = str_replace( "\r", "", $testo);
		//If newline is not preceeded by ">" and is not followed by "</" tag
		//$testo = preg_replace( "/(?<!\>)\n(?!\<\/(.+?)\>)/"  , "<br />"   , $testo ); //working

		$testo = $mklib->convert_savedbadmin($testo);
		$short_testo = $mkportals->input['short'];
		$short_testo = $mklib->convert_savedb($short_testo);

		$titolo = $mkportals->input['titlepage'];
		$titolo = $mklib->convert_savedbadmin($titolo);
		$idnews = intval($mkportals->input['idnews']);
		$pinned = intval($mkportals->input['pinned']);

		$DB->query("UPDATE mkp_news SET idcategoria = '$categoria', titolo ='$titolo', short_testo='$short_testo', testo='$testo', pinned='$pinned' WHERE id='$idnews'");
		$DB->close_db();
	 	Header("Location: {$mklib->siteurl}/index.php?ind=news&op=news_show_single&ide=$idnews");
		exit;
	}


}

?>
