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
$idx = new mk_ad_contents;
class mk_ad_contents {


	function mk_ad_contents() {
		global $mkportals;
		switch($mkportals->input['op']) {
			case 'contents_edit':
    			$this->contents_edit(intval($mkportals->input['idc']));
    		break;
			case 'contents_main_new':
    			$this->contents_main_new();
    		break;
			case 'contents_new':
    			$this->contents_new();
    		break;
			case 'contents_new_php':
    			$this->contents_new_php();
    		break;
			case 'contents_save':
    			$this->contents_save(intval($mkportals->input['idc']));
    		break;
			case 'contents_savenew':
    			$this->contents_savenew();
    		break;
			case 'contents_save_php':
    			$this->contents_save_php();
		break;
			case 'contents_save_list':
    			$this->contents_save_list();
    		break;
			case 'contents_delete':
    			$this->contents_delete(intval($mkportals->input['idc']));
    		break;
			case 'contents_edit_php':
    			$this->contents_edit_php(intval($mkportals->input['idblock']));
    		break;
			case 'contents_update_php':
    			$this->contents_update_php();
    		break;		
			case 'contents_perms':
    			$this->contents_perms();
    		break;
			case 'psave_perms':
    			$this->psave_perms();
		break;
			default:
    			$this->contents_show();
    		break;
    		}
	}

	function contents_show() {
		global $mkportals, $mklib, $Skin, $DB;

	$mode = $mkportals->input['mode'];
	if ($mode == "saved") {
		$checksave = "<div class=\"bghighlight1 success\">{$mklib->lang['ad_saved']}</div>";
   	}
	if ($mode == "deleted") {
		$checksave = "<div class=\"bghighlight1 success\">{$mklib->lang['ad_deleted']}</div>";
	}

	$myquery = $DB->query("SELECT id, title, content IS NOT NULL, active FROM mkp_pages ORDER BY active DESC, id");
	  $content = "
	  <tr>
	  <td>
	    $checksave
	    <form name=\"config_pages\" method=\"post\" action=\"index.php?ind=ad_contents&amp;op=contents_save_list\">
	    <table width=\"100%\" class=\"tabmain\">
		<tr>
		  <td colspan=\"8\" class=\"tdblock\">" . $mklib->helplink('had_mcont','left') . "&nbsp;{$mklib->lang['ad_mcont']}</td>
		</tr>    
	    
		<tr>
		  <td width=\"4%\" class=\"tdblock\">{$mklib->lang['ad_id']}</td>
		  <td width=\"3%\" class=\"tdblock\">{$mklib->lang['ad_type']}</td>
		  <td width=\"26%\" class=\"tdblock\">{$mklib->lang['ad_title']}</td>
		  <td width=\"42%\" class=\"tdblock\">{$mklib->lang['ad_addresspage']}</td>
		  <td width=\"20%\" colspan=\"3\" class=\"tdblock\">{$mklib->lang['ad_actions']}</td>
		  <td width=\"5%\" class=\"tdblock\">{$mklib->lang['ad_active']}</td>
		</tr>
		";

		$content .= "
		<tr>
		  <td>
			<script type=\"text/javascript\">

			function makesure() {
			if (confirm('{$mklib->lang[ad_delpageconfirm]}')) {
			return true;
			} else {
			return false;
			}
			}

			</script>
		  </td>
		</tr>
		";
	  $clastr = "tdglobal";
	  while( $row = $DB->fetch_row($myquery) ) {
		$id = $row['id'];
		
		$titlep = stripslashes($row['title']);
		
		//Active page highlighting  
		$active = $row['active'];
		if ($clastr == "tdglobal" || $clastr == "tdglobal bghighlight1") {
			$clastr = $active ? "modulex bghighlight1" : "modulex"; //Active block highlighting
		} else {
			$clastr = $active ? "tdglobal bghighlight1" : "tdglobal"; //Active block highlighting
		}
		$active = $active ? "checked=\"checked\"" : "";
		$active_form = $id."_active";

		//Page type
		if ($row['content IS NOT NULL'] == 1) { // HTML Block
				$typeimg = 'page_white_html.png';
				$typealt = 'HTML';
		}else { // PHP Block
				$typeimg = 'page_white_php.png';
				$typealt = 'PHP';
		}


		$content .= "
		<tr class=\"$clastr\">
		  <td width=\"4%\">
		    &nbsp;<a href=\"index.php?ind=ad_contents&amp;op=contents_edit&amp;idc={$row['id']}\" title=\"{$mklib->lang['ad_edit']}\">{$row['id']}</a>
		  </td>
		  <td width=\"3%\" style=\"text-align: center\"><img src=\"images/icons/famfamfam/silk/$typeimg\" title=\"$typealt\" alt=\"$typealt\" /></td>
		  <td width=\"26%\">
		    &nbsp;<a href=\"index.php?ind=ad_contents&amp;op=contents_edit&amp;idc={$row['id']}\" title=\"{$mklib->lang['ad_edit']}\">$titlep</a>
		  </td>
		  <td width=\"43%\">
		    <a href=\"$mklib->siteurl/index.php?pid={$row['id']}\" title=\"{$mklib->lang['ad_viewpage']}\">$mklib->siteurl/index.php?pid={$row['id']}</a>
		  </td>
		  <td width=\"8%\" align=\"center\" nowrap=\"nowrap\">
		    [<a href=\"index.php?ind=ad_contents&amp;op=contents_perms&amp;idc={$row['id']}\">{$mklib->lang['ad_mperm']}</a>]
		  </td>
		  <td width=\"8%\" align=\"center\" nowrap=\"nowrap\">
		    [<a href=\"index.php?ind=ad_contents&amp;op=contents_edit&amp;idc={$row['id']}\">{$mklib->lang['ad_edit']}</a>]
		  </td>
		  <td width=\"8%\" align=\"center\" nowrap=\"nowrap\">
		    [<a href=\"index.php?ind=ad_contents&amp;op=contents_delete&amp;idc={$row['id']}\" onclick=\"return makesure()\">{$mklib->lang['ad_delete']}</a>]
		  </td>  
		  <td width=\"5%\" align=\"center\" nowrap=\"nowrap\">
		    <input type=\"checkbox\" name=\"$active_form\" value=\"1\" $active />
		  </td>
		</tr>
		";
	  }

	  $content .= "
	      </table>";
	  if ($id) {
	  	$content .= "
		<p align=\"center\"><input type=\"submit\" value=\"{$mklib->lang['ad_save']}\" name=\"B1\" class=\"mkbutton\" /></p>
		";
	  }	    	
	  $content .= "
	      </form>
	    </td>
	  </tr>
	  ";
	$output = $Skin->view_block("{$mklib->lang['ad_contentslist']}", "$content");
	$mklib->printpage_admin($mklib->lang['ad_titlepage'].$mklib->lang['tt_sep'].$mklib->lang['ad_mcont'].$mklib->lang['tt_sep'].$mklib->lang['ad_mmanage'], $output);

	}

	//Save page active status
	function contents_save_list() {
	global $mkportals, $mklib, $Skin,  $DB;
		$myquery = $DB->query("SELECT id FROM mkp_pages");
 		while( $row = $DB->fetch_row($myquery) ) {
			$id = $row['id'];
			$active_form = $id."_active";
			$mkportals->input[$active_form] = ($mkportals->input[$active_form]) ? '1' : '0'; //default value = "0"
		$DB->query("UPDATE mkp_pages SET active ='{$mkportals->input[$active_form]}' WHERE id='$id'");
		}

		$DB->close_db();
		Header("Location: index.php?ind=ad_contents&mode=saved");
		exit;

	}

	function contents_main_new() {
	global $mkportals, $mklib, $Skin, $DB;
	   $content .= "		
		  <tr align=\"center\">
		    <td><br /><a href=\"index.php?ind=ad_contents&amp;op=contents_new\"><img src=\"images/icons/foood/block.gif\" border=\"0\" alt=\"\" /></a><br />
		    </td>
		  </tr>
		  <tr align=\"center\">
		    <td>{$mklib->lang['ad_pagenewh']}<br /><br /><br /><br /></td>
		  </tr>
		  <tr align=\"center\">
		    <td><a href=\"index.php?ind=ad_contents&amp;op=contents_new_php\"><img src=\"images/icons/foood/block3.gif\" border=\"0\" alt=\"\" /></a><br /></td>
		  </tr>
		  <tr align=\"center\">
		    <td>{$mklib->lang['ad_pagenewp']}<br /><br /><br /><br /></td>
		  </tr>
	";
	$output = $Skin->view_block("{$mklib->lang['ad_contentsnew']}", "$content");
	$mklib->printpage_admin($mklib->lang['ad_titlepage'].$mklib->lang['tt_sep'].$mklib->lang['ad_mcont'].$mklib->lang['tt_sep'].$mklib->lang['ad_mcreation'], $output);
	}
	
	function contents_edit($id) {
	global $mkportals, $mklib, $Skin, $DB, $editorscript;
		$editorscript = 1;
		$textarepar = "mce_editable=\"true\"";
		$textarew = "100%";
		$bbeditor= "";
		if ($mklib->mkeditor == "BBCODE") {
			$editorscript = "";
			$textarepar = "";
			$textarew = "75%";
			$bbeditor= $mklib->get_bbeditor();
		}
		$myquery = $DB->query("SELECT title, content, file, active FROM mkp_pages WHERE id='$id'");
		$row = $DB->fetch_row($myquery);
		// if is php page
		if ($row['file']) {
			$DB->close_db();
			Header("Location: index.php?ind=ad_contents&op=contents_edit_php&idblock=$id");
			exit;
		}
		$titlep = stripslashes($row['title']);
		$testo = stripslashes($row['content']);
		$testo = str_replace("</textarea>", "[/textarea]", $testo);
		//Active status
		$checkactive = ""; //default value is unchecked
		//value from database
		if ($row['active'] == 1) {
			$checkactive = "checked=\"checked\"";
		}
		//overwrite database value with user input value
		if ($mkportals->input['active'] == 1) {
			$checkactive ="checked=\"checked\"";
		}
	   $content = "
		<tr>
		  <td>
		  
		    <form action=\"index.php?ind=ad_contents&amp;op=contents_save&amp;idc=$id\" method=\"post\" id=\"editor\" name=\"editor\">
		    <table width=\"100%\">
		      <tr>
			<td class=\"tdblock\">
			  {$mklib->lang['ad_title']}:	<input type=\"text\" name=\"titlepage\" value=\"$titlep\" size=\"40\" />&nbsp;&nbsp;{$mklib->lang['ad_active']}	<input type=\"checkbox\" name=\"active\" value=\"1\" $checkactive /> 
			</td>
		      </tr>
		      <tr>
			<td class=\"tdblock\">
			$bbeditor
 			<textarea id=\"ta\" name=\"ta\" $textarepar style=\"width: $textarew\" rows=\"14\" cols=\"40\">$testo</textarea>
			</td>
		      </tr>
		      <tr>
			<td>
			  <div align=\"center\"><input type=\"submit\" name=\"ok\" value=\"{$mklib->lang['ad_save']}\" class=\"mkbutton\" /></div>		
			</td>
		      </tr>
		    </table>
		    </form>
		    
		  </td>
		</tr>

	";
	$output = $Skin->view_block("{$mklib->lang['ad_contentsedit']}", "$content");
	$mklib->printpage_admin($mklib->lang['ad_titlepage'].$mklib->lang['tt_sep'].$mklib->lang['ad_mcont'].$mklib->lang['tt_sep'].$mklib->lang['ad_mmanage'], $output);
	}
	function contents_new() {
	global $mkportals, $mklib, $Skin, $DB, $editorscript;
	   $editorscript = 1;
		$textarepar = "mce_editable=\"true\"";
		$textarew = "100%";
		$bbeditor= "";
		if ($mklib->mkeditor == "BBCODE") {
			$editorscript = "";
			$textarepar = "";
			$textarew = "75%";
			$bbeditor= $mklib->get_bbeditor();
		}
	   $content = "
		<tr>
		  <td>
		    <form action=\"index.php?ind=ad_contents&amp;op=contents_savenew\" method=\"post\" id=\"editor\" name=\"editor\">
		    <table width=\"100%\">
		      <tr>
			<td class=\"tdblock\" valign=\"top\">
			{$mklib->lang['ad_title']}:	<input type=\"text\" name=\"titlepage\" size=\"40\" />&nbsp;&nbsp;{$mklib->lang['ad_active']}	<input type=\"checkbox\" name=\"active\" value=\"1\" $checkactive />
			</td>
		      </tr>
		      <tr>
			<td class=\"tdblock\">
			$bbeditor
 			<textarea id=\"ta\" name=\"ta\" $textarepar style=\"width: $textarew\" rows=\"14\" cols=\"40\"></textarea>
			</td>
		      </tr>
		      <tr>
			<td>
			  <div align=\"center\"><input type=\"submit\" name=\"ok\" value=\"{$mklib->lang['ad_save']}\" class=\"mkbutton\" /></div>		
			</td>
		      </tr>
		    </table>
		    </form>
		  </td>
		</tr>
	";
	$output = $Skin->view_block("{$mklib->lang['ad_contentsnew']}", "$content");
	$mklib->printpage_admin($mklib->lang['ad_titlepage'].$mklib->lang['tt_sep'].$mklib->lang['ad_mcont'].$mklib->lang['tt_sep'].$mklib->lang['ad_mcreation'], $output);
	}
	function contents_save($id) {
	global $mkportals, $mklib, $Skin,  $DB;
	
		//Error if title or content field is empty
		$message = '';		
		$message .= !$mkportals->input['titlepage'] ? '<p>'.$mklib->lang['ad_notitle'].'</p>' : '';
		$message .= !$_POST['ta'] ? '<p>'.$mklib->lang['ad_nocontent'].'</p>' : '';
		if (!$mkportals->input['titlepage'] || !$_POST['ta']) {
		      $mklib->error_page($message);
		      exit;
		}

		//normally done by $mkportals->input
		$content = str_replace( "\r", "", $_POST['ta']);
		//If newline is not preceeded by ">" and is not followed by "</" tag
		//$content = preg_replace( "/(?<!\>)\n(?!\<\/(.+?)\>)/"  , "<br />"   , $content ); //working		

		$content = $mklib->convert_savedbadmin($content);

		$titlepage = $mklib->convert_savedbadmin($mkportals->input['titlepage']);
		$active = intval($mkportals->input['active']);

		$content = str_replace ("[/textarea]", "</textarea>", $content);
		$DB->query("UPDATE mkp_pages SET content ='$content', title='$titlepage', active='$active' WHERE id='$id'");
		$DB->close_db();
		Header("Location: index.php?ind=ad_contents&mode=saved");
		exit;
	}
	function contents_savenew() {
	global $mkportals, $mklib, $Skin, $DB, $_POST;
	
		//Error if title or content field is empty
		$message = '';		
		$message .= !$mkportals->input['titlepage'] ? '<p>'.$mklib->lang['ad_notitle'].'</p>' : '';
		$message .= !$_POST['ta'] ? '<p>'.$mklib->lang['ad_nocontent'].'</p>' : '';
		if (!$mkportals->input['titlepage'] || !$_POST['ta']) {
		      $mklib->error_page($message);
		      exit;
		}
		
		
		//normally done by $mkportals->input
		$content = str_replace( "\r", "", $_POST['ta']);
		//If newline is not preceeded by ">" and is not followed by "</" tag
		//$content = preg_replace( "/(?<!\>)\n(?!\<\/(.+?)\>)/"  , "<br />"   , $content ); //working

		$content = $mklib->convert_savedbadmin($content);

		$titlepage = $mklib->convert_savedbadmin($mkportals->input['titlepage']);
		$active = intval($mkportals->input['active']);

		$content = str_replace ("[/textarea]", "</textarea>", $content);
		$DB->query("INSERT INTO mkp_pages (content, title, active) VALUES ('$content', '$titlepage', '$active')");
		$DB->close_db();
		Header("Location: index.php?ind=ad_contents&mode=saved");
		exit;
	}
	function contents_delete($id) {
	global $mkportals, $mklib, $Skin,  $DB;
		$DB->query("SELECT file FROM mkp_pages WHERE id =  '$id'");
		$row = $DB->fetch_row();
		if ($row['file']) {
			$filename = $row['file'];
			@unlink('../'.$filename);
		}
		$DB->query("DELETE FROM mkp_pages WHERE id='$id'");
		$DB->close_db();
		Header("Location: index.php?ind=ad_contents&mode=deleted");
		exit;

	}

	//PHP pages: create & preview
	function contents_new_php() {
	global $mkportals, $mklib, $Skin, $DB;

		//Save or Preview?
		if (isset($mkportals->input['oksave'])) {
			$this->contents_save_php();
		}

		//User-defined title & text values
		$titleblock = stripslashes($mkportals->input['titleblock']);

		//Important! Cannot use stripslashes here or it will strip escape characters in the php code
		$testo = $mklib->convert_viewphpadmin($_POST['ta']);
				
		//Default title & text values
		if (!$titleblock) {
			$titleblock = $mklib->lang['ad_title'];
   		}		

		//Active status
		$checkactive = ""; //default value is unchecked
		//value from database
		if ($row['active'] == 1) {
			$checkactive = "checked=\"checked\"";
		}
		//overwrite database value with user input value
		if ($mkportals->input['active'] == 1) {
			$checkactive ="checked=\"checked\"";
		}
		
		if (!$testo) {
			$testo = $mklib->lang['ad_blphpcode'];
		}
		$testo = trim($testo);
		
		//Add css for preview
		$css = "$mklib->template/style.css";
 		$filetext = "<head>\n<link href=\"{$css}\" rel=\"stylesheet\" type=\"text/css\">\n</head>\n";
		$filetext .= $testo;
		
		//Open tmp_block.php
		$filename = "../cache/tmp_block.php";
		if (!$handle = fopen($filename, 'wb')) {
         		$message = $mklib->lang['ad_fopen_error'];
			$mklib->error_page($message);
			exit;
		}
		//Write content to tmp_block.php
   		if (!fwrite($handle, $filetext)) {
       			$message = $mklib->lang['ad_blnofile'];
			$mklib->error_page($message);
			exit;
   		}
		fclose($handle);

	   $content .= "
		<tr>
		  <td>
		  
		    <form action=\"index.php?ind=ad_contents&amp;op=contents_new_php\" method=\"post\" id=\"editor\" name=\"editor\">
		    <table width=\"300\">
		      <tr>
			<td class=\"tdblock\">
			  {$mklib->lang['ad_title']}: <input type=\"text\" value=\"$titleblock\" name=\"titleblock\" size=\"40\" />&nbsp;&nbsp;{$mklib->lang['ad_active']} <input type=\"checkbox\" name=\"active\" value=\"1\" $checkactive />
			</td>
		      </tr>
		      <tr>
			<td class=\"tdblock\">
			  <textarea id=\"ta\" name=\"ta\" rows=\"20\" cols=\"75\">$testo</textarea>
			  <input type=\"submit\" name=\"okpreview\" value=\"{$mklib->lang['ad_pgpreview']}\" class=\"mkbutton\" />
			  <input type=\"submit\" name=\"oksave\" value=\"{$mklib->lang['ad_save']}\" class=\"mkbutton\" />
			</td>
		      </tr>
		    </table>
		    </form>

		  </td>
		</tr>		    		    
		<tr>
		  <td align=\"left\" height=\"100%\">
		    <table width=\"100%\">
		      <tr>
			<td>
			  <iframe src=\"index.php?ind=ad_blocks&amp;op=show_code&amp;titleblock=$titleblock\" frameborder=\"0\" width=\"100%\" align=\"middle\" height=\"200\" scrolling=\"auto\"></iframe>
			</td>
		      </tr>
		    </table>
		  </td>
		</tr>
	";

	$output = $Skin->view_block("{$mklib->lang['ad_pagenewp']}", "$content");
	$mklib->printpage_admin($mklib->lang['ad_titlepage'].$mklib->lang['tt_sep'].$mklib->lang['ad_mcont'].$mklib->lang['tt_sep'].$mklib->lang['ad_mcreation'], $output);
	}
	
	//PHP pages: save
	function contents_save_php() {
	global $mkportals, $mklib, $Skin,  $DB;
	
		//User input cleanup
		$titlepage = stripslashes($mkportals->input['titleblock']); //strip slashes added by $_POST
		
		$titlepage = $mklib->convert_savedbadmin($titlepage);  
		//$titlepage = $mklib->post_htmlspecialchars($titlepage);
		if (!$titlepage) {
			$titlepage = $mklib->lang['ad_title'];
			//$titlepage = $mklib->post_htmlspecialchars($titlepage);
		}
		$active = intval($mkportals->input['active']);

		//Important! Cannot use stripslashes here or it will strip escape characters in the php code
		$testo = $mklib->convert_viewphpadmin($_POST['ta']);
		$testo = trim ($testo);
		
		//Create new page ID
		$query = $DB->query("SELECT id FROM mkp_pages ORDER BY id DESC LIMIT 1");
		$row = $DB->fetch_row($query);
		$filename2 = "cache/ppage_";
		$filename2 .= ++$row['id'];
		$filename2 .= ".php";

		//Create cached php file
		if (!$handle = fopen('../'.$filename2, 'wb')) {
         		$message = $mklib->lang['ad_blnofile'];
			$mklib->error_page($message);
			exit;
   		}
		//Write $testo to cached php file
		if (!fwrite($handle,$testo)) {
       			$message = $mklib->lang['ad_blnofile'];
			$mklib->error_page($message);
			exit;
   		}
		fclose($handle);
		//Close cached php file

		//Insert filename & title into database
		$DB->query("INSERT INTO mkp_pages (file, title, active) VALUES ('$filename2', '$titlepage', '$active')");
		$DB->close_db();
		Header("Location: index.php?ind=ad_contents&mode=saved");
		exit;
	}
	
	//PHP pages: edit save
	function contents_update_php() {
	global $mkportals, $mklib, $Skin, $DB;

		//User input cleanup
		$idblock = intval($mkportals->input['idblock']);
		$titleblock = stripslashes($mkportals->input['titleblock']); //strip slashes added by $_POST
		$titleblock = $mklib->convert_savedbadmin($titleblock);
		//$titleblock = $mklib->post_htmlspecialchars($titleblock);
		
		if (!$titleblock) {
			$titleblock = $mklib->lang['ad_title'];
			//$titleblock = $mklib->post_htmlspecialchars($titleblock);
		}
		$active = intval($mkportals->input['active']);
		
		//Important! Cannot use stripslashes here or it will strip escape characters in the php code
		$testo = $mklib->convert_viewphpadmin($_POST['ta']);
		$testo = trim($testo);
		
		//Get cached filename from database
		$query = $DB->query("SELECT file FROM mkp_pages WHERE id = '$idblock'");
		$row = $DB->fetch_row($query);
		if(!$row) {
            		$message = $mklib->lang['error_404'];
            		$mklib->error_page($message);
            		exit;
		}		

		//Open cached php file
		$filename2 = $row['file'];
		if (!$handle = fopen('../'.$filename2, 'wb')) {
         		$message = $mklib->lang['ad_blnofile'];
			$mklib->error_page($message);
			exit;
   		}
		//Write $testo to cached php file
		if (!fwrite($handle,$testo)) {
       		$message = $mklib->lang['ad_blnofile'];
			$mklib->error_page($message);
			exit;
   		}
		fclose($handle);
		//Close cached php file   		

		//Update page title in database
		$DB->query("UPDATE mkp_pages SET title='$titleblock', active='$active' WHERE id='$idblock'");
		$DB->close_db();
		Header("Location: index.php?ind=ad_contents&mode=saved");
		exit;
	}

	//PHP pages: edit and preview
	function contents_edit_php($idblock, $check="") {
	global $mkportals, $mklib, $Skin, $DB;

		//Save or Preview?
		if (isset($mkportals->input['oksave'])) {
			$this->contents_update_php();
		}

		//User-defined title and text values
		$titleblock = stripslashes($mkportals->input['titleblock']);

		//Important! Cannot use stripslashes here or it will strip escape characters in the php code
		$testo = $mklib->convert_viewphpadmin($_POST['ta']);

		//Get cached page filename & title
		$query = $DB->query("SELECT file, title, active FROM mkp_pages WHERE id = '$idblock'");
		$row = $DB->fetch_row($query);
		if (!$titleblock) {
			$titleblock = $row['title'];
		}

		//Active status
		$checkactive = ""; //default value is unchecked
		//value from database
		if ($row['active'] == 1) {
			$checkactive = "checked=\"checked\"";
		}
		//overwrite database value with user input value
		if ($mkportals->input['active'] == 1) {
			$checkactive ="checked=\"checked\"";
		}

		//Cached title and text values
		if (!$testo) {
			$filename = $row['file'];
			if (!$handle = fopen('../'.$filename, "rb")) {
         			$message = $mklib->lang['ad_fopen_error'];
				$mklib->error_page($message);
				exit;
			}
			$testo = fread($handle, filesize('../'.$filename));
			fclose($handle);
		}		
		$testo =  trim($testo);

		//Add css for preview
		$css = "$mklib->template/style.css";
 		$filetext = "<head>\n<link href=\"{$css}\" rel=\"stylesheet\" type=\"text/css\">\n</head>\n";
		$filetext .= $testo;

		$filename = "../cache/tmp_block.php";

		//Open tmp_block.php or display error
		if (!$handle = fopen($filename, 'wb')) {
         		$message = $mklib->lang['ad_fopen_error'];
			$mklib->error_page($message);
			exit;
		}
		//Write text to tmp_block.php or display error
   		if (!fwrite($handle, $filetext)) {
       			$message = $mklib->lang['ad_blnofile'];
			$mklib->error_page($message);
			exit;
   		}
		fclose($handle);   		

	   $content .= "
		<tr>
		  <td>
		    <form action=\"index.php?ind=ad_contents&amp;op=contents_edit_php&amp;idblock=$idblock\" method=\"post\" id=\"editor\" name=\"editor\">
		    <table width=\"300\">
		      <tr>
			<td class=\"tdblock\">
			  {$mklib->lang['ad_title']}:
			  <input type=\"text\" value=\"$titleblock\" name=\"titleblock\" size=\"40\" />&nbsp;&nbsp;{$mklib->lang['ad_active']}	<input type=\"checkbox\" name=\"active\" value=\"1\" $checkactive /> 
			</td>
		      </tr>
		      <tr>
			<td class=\"tdblock\">
			  <textarea id=\"ta\" name=\"ta\"  rows=\"20\" cols=\"75\">$testo</textarea>
			  <input type=\"submit\" name=\"okpreview\" value=\"{$mklib->lang['ad_pgpreview']}\" class=\"mkbutton\" />
			  <input type=\"submit\" name=\"oksave\" value=\"{$mklib->lang['ad_save']}\" class=\"mkbutton\" />
			</td>
		      </tr>
		    </table>
		    </form>
		  </td>
		</tr>		
		<tr>
		  <td align=\"left\" height=\"100%\">
		    <table width=\"100%\">
		      <tr>
			<td>
			  <iframe src=\"index.php?ind=ad_blocks&amp;op=show_code&amp;titleblock=$titleblock\" frameborder=\"0\"  width=\"100%\" align=\"middle\" height=\"200\" scrolling=\"auto\"></iframe>
			</td>
		      </tr>
		    </table>
		  </td>
		</tr>
	";

	$output = $Skin->view_block("{$mklib->lang['ad_contentsedit']}", "$content");
	$mklib->printpage_admin($mklib->lang['ad_titlepage'].$mklib->lang['tt_sep'].$mklib->lang['ad_mcont'].$mklib->lang['tt_sep'].$mklib->lang['ad_mmanage'], $output);
	}
	
	
	function contents_perms() {
	global $mkportals, $mklib, $Skin, $DB, $mklib_board;
	$idc = intval($mkportals->input['idc']);
	$DB->query("SELECT id, title, perms FROM mkp_pages WHERE id =  '$idc'");
	$row = $DB->fetch_row();
	$groups = $mklib_board->build_grouplist2();
	$perms = array();
	if ($row['perms']) {
		$perms =  unserialize($row['perms']);
	}
	$content = "
	<tr>
	  <td>
	    <form name=\"main1\" method=\"post\" action=\"index.php?ind=ad_contents&amp;op=psave_perms&amp;idc=$idc\">
		<tr>
		<td class=\"titadmin\" style=\"border: 0\">{$mklib->lang['ad_groupsallow']} {$row['title']}</td>
	      </tr>
	      
	";
	$clastr = "tdglobal";
	foreach ($groups as $value) {
   		//echo "id: $value[id] title: $value[title]<br />\n";
		$checkactive = "checked=\"checked\"";
		if (in_array($value[id], $perms)) {
			$checkactive ="";
   		}
		$name = "group".$value[id];
		$content .= "
		<tr class=\"$clastr\">
		<td><br />&nbsp;&nbsp;<input type=\"checkbox\" name=\"$name\" value=\"1\" $checkactive />&nbsp;&nbsp; $value[title]</td>
	      </tr>
		";
		if ($clastr == "tdglobal") {
			$clastr = "modulex";
		 } else {
			$clastr = "tdglobal";
		 }
	}
	$content .= "
		
		<tr>
		<td><br />
		&nbsp;&nbsp;<input type=\"submit\" value=\"{$mklib->lang['ad_save']}\" name=\"B1\" class=\"mkbutton\" /><br />
		<br />
		</td>
	      </tr>
	    </form>
	  </td>
	</tr>
	";
	$output = $Skin->view_block("{$mklib->lang['ad_mperm']}", "$content");
	$mklib->printpage_admin($mklib->lang['ad_titlepage'].$mklib->lang['tt_sep'].$mklib->lang['ad_mcont'].$mklib->lang['tt_sep'].$mklib->lang['ad_mperm'], $output);

	}
	function psave_perms() {
		global $mkportals, $mklib, $Skin, $DB, $mklib_board;
		$permissions = array();
		$idpage = intval($mkportals->input['idc']);
		$groups = $mklib_board->build_grouplist2();
		foreach ($groups as $value) {
			$idgroup = $value[id];
			$groupperm = "group".$idgroup;
			$groupperm = $mkportals->input[$groupperm];
			//echo "idblock: $idblock idgroup: $idgroup title: $value[title] permission: $groupperm<br />\n";
			if (empty($groupperm)) {
				$permissions[] = $idgroup;
			}
		}
		$permissions = serialize($permissions);
		$DB->query("UPDATE mkp_pages SET perms ='$permissions' WHERE id='$idpage'");
		$DB->close_db();
		Header("Location: index.php?ind=ad_contents&mode=saved");
		exit;
	}
	
}

?>
