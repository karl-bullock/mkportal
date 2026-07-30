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
$idx = new mk_ad_gallery;
class mk_ad_gallery {


	function mk_ad_gallery() {
		global $mkportals;
		switch($mkportals->input['op']) {
			case 'add_event':
    			$this->add_event();
    		break;
			case 'edit_event':
    			$this->edit_event();
    		break;
			case 'update_event':
    			$this->update_event();
    		break;
			case 'add_foto':
    			$this->add_foto();
    		break;
			case 'edit_foto':
    			$this->edit_foto();
    		break;
			case 'update_foto':
    			$this->update_foto();
    		break;
			case 'delete_foto':
    			$this->delete_foto();
    		break;
			case 'delete_event':
    			$this->delete_event();
    		break;
			case 'add_fotolink':
    			$this->add_fotolink();
    		break;
			case 'save_main':
    			$this->save_main();
    		break;
			default:
    			$this->gallery_show();
    		break;
    		}
	}

	function gallery_show() {
	global $mkportals, $mklib, $Skin, $DB;

		// Admin Approval combo
		$approval = $mklib->config['approval_gallery'];
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
		//Watermark position
		$waterpos = $mklib->config['watermark_pos'];
		switch($waterpos) {
			case '1':
    			$selwaterp1="selected=\"selected\"";
    		break;
			case '2':
    			$selwaterp2="selected=\"selected\"";
    		break;
    		default:
    			$selwaterp="selected=\"selected\"";
    		break;
		}
		$cselecta = "<option value=\"0\" $selap>{$mklib->lang['ad_approp_0']}</option>\n";
		$cselecta .= "<option value=\"1\" $selap1>{$mklib->lang['ad_approp_1']}</option>\n";
		$cselecta .= "<option value=\"2\" $selap2>{$mklib->lang['ad_approp_2']}</option>\n";
		$cselecta .= "<option value=\"3\" $selap3>{$mklib->lang['ad_approp_3']}</option>\n";

		$cselect = $this->row_select_event();
		$cselects = "<option value='0'>{$mklib->lang['ad_dowpcat']}</option>\n";
		$cselects .= $cselect;
		
		$cselectw = "<option value=\"0\" $selwaterp>{$mklib->lang['ad_topr']}</option>\n";
		$cselectw .= "<option value=\"1\" $selwaterp1>{$mklib->lang['ad_centerpos']}</option>\n";
		$cselectw .= "<option value=\"2\" $selwaterp2>{$mklib->lang['ad_bottomr']}</option>\n";
		
		// se deve visualizzare una sezione....
		$output_section = "";
		$idev = intval($mkportals->input['evento_show']);
		if( $idev) {
			$output_section = $this->return_section_preview($idev);
		}
		if ($mklib->config['mod_gallery']) {
		$checkactive =  "checked=\"checked\"";
   		}
		if ($mklib->config['watermark_enable']) {
		$checkwater =  "checked=\"checked\"";
   		}
		$gallery_sec_page = $mklib->config['gallery_sec_page'];
		$gallery_file_page= $mklib->config['gallery_file_page'];
		$thumb_max_dimen= $mklib->config['thumb_max_dimen'];
		$thumb_jpg_quality= $mklib->config['thumb_jpg_quality'];
		$waterlevel =  $mklib->config['watermark_level'];		
		$upload_image_max= $mklib->config['upload_image_max'];
		$classic_thumbs2 = "checked=\"checked\"";
		if ($mklib->config['classic_thumbs'] == 0) {
			$classic_thumbs = "checked=\"checked\"";
			$classic_thumbs2 = "";
		}
		$square_thumbs2 = "checked=\"checked\"";
		if ($mklib->config['square_thumbs'] == 0) {
			$square_thumbs = "checked=\"checked\"";
			$square_thumbs2 = "";
		}

		if ($mkportals->input['mode'] == "saved") {
		$checksave = "{$mklib->lang['ad_saved']}";
   		}
   		// Select images FTP
   		$mass_file = $this->mass_file();
	 	$content  = "
		
	<tr>
	  <td>

	    <script type=\"text/javascript\">
			function makesure() {
			if (confirm('{$mklib->lang[ad_delgenconfirm]}')) {
			return true;
			} else {
			return false;
			}
			}
	    </script>

	    <form action=\"index.php?ind=ad_gallery&amp;op=save_main\" name=\"save_main\" method=\"post\">
	    <table width=\"100%\" border=\"0\">
	      <tr>
		<td>$checksave</td>
	      </tr>
	      <tr>
		<td class=\"titadmin\">{$mklib->lang['ad_preferences']}</td>
	      </tr>
	      <tr>
		<td><span class=\"mktxtcontr\">{$mklib->lang['ad_galdisactive']}</span> <input type=\"checkbox\" name=\"stato\" value=\"1\" $checkactive /></td>
	      </tr>
	      <tr>
		<td>{$mklib->lang['ad_galspages']}</td>
	      </tr>
	      <tr>
		<td><input type=\"text\" name=\"gallery_sec_page\" value=\"$gallery_sec_page\" size=\"20\" class=\"bgselect\" /></td>
	      </tr>
	      <tr>
		<td>{$mklib->lang['ad_galipages']}</td>
	      </tr>
	      <tr>
		<td><input type=\"text\" name=\"gallery_file_page\" value=\"$gallery_file_page\" size=\"20\" class=\"bgselect\" /></td>
	      </tr>
	      <tr>
		<td>{$mklib->lang['ad_galmaxup']}</td>
	      </tr>
	      <tr>
		<td><input type=\"text\" name=\"upload_image_max\" value=\"$upload_image_max\" size=\"20\" class=\"bgselect\" /></td>
	      </tr>

	      <tr> 
		<td>{$mklib->lang['ad_clthumbs']}</td>
	      </tr>
	      <tr>
	      <td>
	      {$mklib->lang['ad_ajaxth']}&nbsp;<input type=\"radio\" value=\"0\" name=\"classic_thumbs\" $classic_thumbs />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$mklib->lang['ad_classicth']}&nbsp;<input type=\"radio\" value=\"1\" name=\"classic_thumbs\" $classic_thumbs2 />
	      </td>
	      </tr>

	      <tr> 
		<td>{$mklib->lang['ad_sqthumbs']}</td>
	      </tr>
	      <tr>
	      <td>
	      {$mklib->lang['ad_yes']}&nbsp;<input type=\"radio\" value=\"1\" name=\"square_thumbs\" $square_thumbs2 />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$mklib->lang['ad_no']}&nbsp;<input type=\"radio\" value=\"0\" name=\"square_thumbs\" $square_thumbs />
	      </td>
	      </tr>

	      <tr>
		<td>{$mklib->lang['ad_thumbmax']}</td>
	      </tr>
	      <tr>
		<td><input type=\"text\" name=\"thumb_max_dimen\" value=\"$thumb_max_dimen\" size=\"20\" class=\"bgselect\" /></td>
	      </tr>
	      <tr>
		<td>{$mklib->lang['ad_thumbqual']}</td>
	      </tr>
	      <tr>
		<td><input type=\"text\" name=\"thumb_jpg_quality\" value=\"$thumb_jpg_quality\" size=\"20\" class=\"bgselect\" /></td>
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
		<td><br /><span class=\"mktxtcontr\">{$mklib->lang['ad_waterm']}</span> <input type=\"checkbox\" name=\"water_enable\" value=\"1\" $checkwater /></td>
	      </tr>
	      <tr>
		<td>{$mklib->lang['ad_waterwarn']}</td>
	      </tr>
	      <tr>
		<td>{$mklib->lang['ad_waterpos']}</td>
	      </tr>
	      <tr>
		<td>
		  <select class=\"bgselect\" size=\"1\" name=\"waterpos\">
		  {$cselectw}
		  </select>
		</td>
	      </tr>
	      <tr>
		<td>{$mklib->lang['ad_waterlevel']}</td>
	      </tr>
	      <tr>
		<td><input type=\"text\" name=\"waterlevel\" value=\"$waterlevel\" size=\"20\" class=\"bgselect\" /></td>
	      </tr>
	      <tr>
		<td>{$mklib->lang['ad_watimg']}</td>
	      </tr>
	      <tr>
		<td><img src=\"../modules/gallery/wt.png\" border=\"0\" alt=\"\" /></td>
	      </tr>
	      <tr>
		<td>{$mklib->lang['ad_wattest']}</td>
	      </tr>
	      <tr>
		<td><img src=\"../cache/watermark_test.jpg\" border=\"2\" alt=\"\" /></td>
	      </tr>
	      <tr>
		<td><input type=\"submit\" name=\"Salve\" value=\"{$mklib->lang['ad_save']}\"  class=\"mkbutton\" /></td>
	      </tr>
	    </table>
	    </form>
	    
	    <form action=\"index.php?ind=ad_gallery&amp;op=add_event\" name=\"a_ev\" method=\"post\">
	    <table width=\"100%\" border=\"0\">
	      <tr>
		<td class=\"titadmin\">{$mklib->lang['ad_addsection']}</td>
	      </tr>
	      <tr>
		<td>{$mklib->lang['ad_dowcat_scat']}</td>
	      </tr>
	      <tr>
		<td width=\"10%\">
		  <select name=\"father\" size=\"1\" class=\"bgselect\">
		  {$cselects}
		  </select>
		</td>
	      </tr>
	      <tr>
		<td>{$mklib->lang['ad_title']}</td>
	      </tr>
	      <tr>
		<td>
		  <input type=\"text\" name=\"eventoa\" size=\"50\" class=\"bgselect\" />
		  <input type=\"submit\" name=\"Inserisci\" value=\"{$mklib->lang['ad_insert']}\"  class=\"mkbutton\" />
		</td>
	      </tr>
	    </table>
	    </form>

	    <form action=\"index.php?ind=ad_gallery&amp;op=edit_event\" name=\"m_ev\" method=\"post\">
	    <table width=\"100%\" border=\"0\">
	      <tr>
		<td class=\"titadmin\">{$mklib->lang['ad_editsection']}</td>
	      </tr>
	      <tr>
		<td width=\"10%\">
		  <select name=\"eventoc\" size=\"1\" class=\"bgselect\">
		  {$cselect}
		  </select>   
		  <input type=\"submit\" name=\"Modifica\" value=\"{$mklib->lang['ad_edit']}\" class=\"mkbutton\" />
		</td>
	      </tr>
	    </table>
	    </form>

	    <form action=\"index.php?ind=ad_gallery&amp;op=delete_event\" name=\"e_ev\" method=\"post\">
	    <table width=\"100%\" border=\"0\">
	      <tr>
		<td class=\"titadmin\">{$mklib->lang['ad_delsection']}
		</td>
	      </tr>
	      <tr>
		<td width=\"10%\">
		  <select name=\"eventoc\" size=\"1\" class=\"bgselect\">
		  {$cselect}
		  </select>   
		  <input type=\"submit\" name=\"Elimina\" value=\"{$mklib->lang['ad_delete']}\" onclick=\"return makesure()\" class=\"mkbutton\" />
		</td>
	      </tr>
	      <tr>
		<td>{$mklib->lang['ad_galwarning']}</td>
	      </tr>
	    </table>
	    </form>

	    <form action=\"index.php?ind=ad_gallery&amp;op=add_foto\" name=\"FOTO_UPLOAD\" method=\"post\" enctype=\"multipart/form-data\">
	    <table width=\"100%\" border=\"0\">
	      <tr>
		<td class=\"titadmin\" colspan=\"2\">{$mklib->lang['ad_galupim']}</td>
	      </tr>
	      <tr>
		<td>{$mklib->lang['ad_section']}</td>
		<td>
		  <select class=\"bgselect\" name=\"evento\" size=\"1\">
		  {$cselect}
		  </select>
		</td>
	      </tr>
	      <tr>
		<td width=\"10%\">{$mklib->lang['ad_title']}</td>
		<td width=\"90%\"><input type=\"text\" name=\"titolo\" size=\"52\" class=\"bgselect\" /></td>
	      </tr>
	      <tr>
		<td width=\"10%\" valign=\"top\">{$mklib->lang['ad_description']}</td>
		<td width=\"90%\"><textarea cols=\"50\" rows=\"4\" name=\"descrizione\" class=\"bgselect\"></textarea></td>
	      </tr>
	      <tr>
		<td width=\"10%\">{$mklib->lang['ad_image']}</td>
		<td width=\"90%\"><input type=\"file\" name=\"FILE_UPLOAD\" size=\"50\" class=\"bgselect\" /></td>
	      </tr>
	      <tr>
		<td colspan=\"2\"><input type=\"submit\" value=\"{$mklib->lang['ad_insert']}\" class=\"mkbutton\" /></td>
	      </tr>
	    </table>
	  </form>
<!-- BEGIN ADD FOTO MASS -->
	  <form action=\"index.php?ind=ad_gallery&amp;op=add_fotolink\" name=\"FOTO_LINK\" method=\"post\">
	  <table width=\"100%\" border=\"0\">	
	    <tr>
	      <td class=\"titadmin\" colspan=\"2\">{$mklib->lang['ad_galaddim']}</td>
	    </tr>
	    <tr>
	      <td>{$mklib->lang['ad_section']}</td>
	      <td>
		<select class=\"bgselect\" name=\"evento\" size=\"1\">
		{$cselect}
		</select>
	      </td>
	    </tr>
	    <tr>
	      <td width=\"10%\" valign=\"top\">{$mklib->lang['ad_description']}</td>
	      <td width=\"90%\"><textarea cols=\"50\" rows=\"4\" name=\"descrizione\" class=\"bgselect\"></textarea></td>
	    </tr>
	    <tr>
	      <td width=\"10%\">{$mklib->lang['ad_galaddname']}</td>
	      <td width=\"90%\">
			<select class=\"bgselect\" name=\"selimages[]\" multiple=\"multiple\" size=\"7\">
			{$mass_file}
			</select>
		  </td>
	    </tr>
	    <tr>
	      <td colspan=\"2\"><input type=\"submit\" value=\"{$mklib->lang['ad_insert']}\" class=\"mkbutton\" /></td>
	    </tr>	  
	  </table>
	  </form>
<!-- END ADD FOTO MASS -->
	  <form action=\"index.php?ind=ad_gallery&amp;op=show_section\" name=\"s_sectm\" method=\"post\">
	  <table width=\"100%\" border=\"0\">
	    <tr>
	      <td class=\"titadmin\">{$mklib->lang['ad_galv_e_d']}</td>
	    </tr>
	    <tr>
	      <td width=\"10%\">
		{$mklib->lang['ad_image']}: <select name=\"evento_show\" size=\"1\" class=\"bgselect\">
		{$cselect}
		</select>   
		<input type=\"submit\" name=\"Modifica\" value=\"{$mklib->lang['ad_show']}\" class=\"mkbutton\" />
	      </td>
	    </tr>
	  </table>
	  </form>
	  
	  <table width=\"100%\" border=\"0\">
	    <tr>
	      <td>
	   	{$output_section}
	      </td>
	    </tr>
	  </table>
	</td>
      </tr>
		";
		$output = $Skin->view_block("{$mklib->lang['ad_galtitle']}", "$content");
		$mklib->printpage_admin($mklib->lang['ad_titlepage'].$mklib->lang['tt_sep'].$mklib->lang['ad_galtitle'], $output);

	}
	function add_event() {

		global $mkportals, $DB, $mklib;
		$eventoa = $mkportals->input['eventoa'];
		$father = intval($mkportals->input['father']);
		$evento = $eventoa;
		if (!$evento) {
			$message = "{$mklib->lang['ad_req_ncat']}";
			$mklib->error_page($message);
			exit;
		}
		$DB->query("INSERT INTO  mkp_gallery_events (evento, father) VALUES ('$evento', '$father')");
		$DB->close_db();
	 	Header("Location: index.php?ind=ad_gallery&mode=saved");
		exit;
  }
	function edit_event() {
   		global $mkportals, $mklib, $Skin, $DB;

		$idf = intval($mkportals->input['eventoc']);
		if (!$idf) {
			$message = "{$mklib->lang['ad_req_cat']}";
			$mklib->error_page($message);
			exit;
		}
		$query = $DB->query( "SELECT evento, position, father FROM mkp_gallery_events  WHERE id = $idf");
		$row = $DB->fetch_row($query);
		$evento = $row['evento'];
		$position =  $row['position'];
		$father =  $row['father'];
		$cselect = "<option value='0'>{$mklib->lang['ad_dowpcat']}</option>\n";
		$query = $DB->query( "SELECT id, evento FROM mkp_gallery_events ORDER BY `id` DESC");
		while( $row = $DB->fetch_row($query) ) {
			$ideventot = $row['id'];
			$eventot = $row['evento'];
			$selected = "";
			if($ideventot == $father) {
				$selected = "selected";
			}
			$cselect.= "<option value='$ideventot' $selected>$eventot</option>\n";
		}

		$content = "
	<tr>
	  <td>
	  
	    <form action=\"index.php?ind=ad_gallery&amp;op=update_event\" name=\"EV_UPGRADE\" method=\"post\">
	    <table width=\"100%\" border=\"0\">
	      <tr>
		<td>
		  <input type=\"hidden\" name=\"idf\"  value = \"{$idf}\" />
		</td>
	      </tr>
	      <tr>
		<td class=\"titadmin\">{$mklib->lang['ad_dowfcat']}</td>
	      </tr>
	      <tr>
		<td width=\"10%\">
		  <select name=\"father\" size=\"1\" class=\"bgselect\">
		  {$cselect}
		  </select>
		</td>
	      </tr>
	      <tr>
		<td class=\"titadmin\">{$mklib->lang['ad_title']}</td>
	      </tr>
	      <tr>
		<td><input type=\"text\" name=\"evento\"  value = \"{$evento}\" size=\"50\" class=\"bgselect\" /></td>
	      </tr>
	      <tr>
		<td class=\"titadmin\">{$mklib->lang['ad_position']}</td>
	      </tr>
	      <tr>
		<td><input type=\"text\" name=\"position\"  value = \"{$position}\" size=\"50\" class=\"bgselect\" /></td>
	      </tr>
	      <tr>
		<td><input type=\"submit\" value=\"{$mklib->lang['ad_edit']}\" class=\"mkbutton\" /></td>
	      </tr>
	    </table>
	    </form>
	    
	  </td>
	</tr>
		";
		$output = $Skin->view_block("{$mklib->lang['ad_galedisec']}", "$content");
		$mklib->printpage_admin($mklib->lang['ad_titlepage'].$mklib->lang['tt_sep'].$mklib->lang['ad_galtitle'], $output);

	}
	function update_event() {
    	global $mkportals, $DB, $mklib;
		$idf = intval($mkportals->input['idf']);
		$evento = $mkportals->input['evento'];
		$position = intval($mkportals->input['position']);
		$father = intval($mkportals->input['father']);
		if (!$evento) {
			$message = "{$mklib->lang['ad_req_ncat']}";
			$mklib->error_page($message);
			exit;
		}
		if ($father == $idf) {
			$father = 0;
		}
		$DB->query("UPDATE mkp_gallery_events set evento = '$evento', position = '$position', father = '$father' WHERE id = '$idf'");
		$DB->close_db();
		Header("Location: index.php?ind=ad_gallery&evento_show=$idf");
		exit;
  	}
	function add_foto() {

    	global $mkportals, $DB,  $_FILES, $mklib;
		$evento = $mkportals->input['evento'];
		$titolo = $mkportals->input['titolo'];
		$descrizione = $mkportals->input['descrizione'];
		$FILE_UPLOAD = $mkportals->input['FILE_UPLOAD'];
		$file =  $_FILES['FILE_UPLOAD']['tmp_name'];
		$file_name =  $_FILES['FILE_UPLOAD']['name'];
		$file_type =  $_FILES['FILE_UPLOAD']['type'];
		$peso =  $_FILES['FILE_UPLOAD']['size'];
		$autore = $mkportals->member['name'];

		if (!$evento || !$titolo || !$descrizione || !$file) {
			$message = "{$mklib->lang['ad_all_rows']}";
			$mklib->error_page($message);
			exit;
		}

		$file_ext = substr ($file_name, (strlen($file_name)-3), 3);
		$file_ext = strtolower($file_ext);

		switch($file_ext)
		{
			case 'gif':
				$ext = 'gif';
				break;
			case 'jpg':
				$ext = 'jpg';
				break;
			case 'png':
				$ext = 'png';
				break;
			case 'tif':
				$ext = 'tif';
				break;
			case 'bmp':
				$ext = 'bmp';
				break;
			default:
				$ext = 'not_supported';
				break;
		}

		if ($ext == "not_supported")  {
			$message = "{$mklib->lang['ad_gfnosupport']}";
			$mklib->error_page($message);
			exit;
		}

		if ($mklib->config['upload_image_max'] > 0 && $peso > ($mklib->config['upload_image_max']*1024)) {
			$message = "{$mklib->lang['ad_guptoobig']}";
			$mklib->error_page($message);
			exit;
		}



		$query = $DB->query("SELECT id FROM mkp_gallery ORDER BY id DESC LIMIT 1");
		$row = $DB->fetch_row($query);
		$totr = $row['id'];
		++$totr;

		$image = "a_"."$totr".".$ext";
		@copy("$file", "../modules/gallery/album/$image");

		if (!is_file ("../modules/gallery/album/$image")) {
			$message = "{$mklib->lang['ad_gdirnoperm']}";
			$mklib->error_page($message);
			exit;
		}
		$thumb = "t_$image";
		$cdata = time();
// Meo Changed in C 0.1.b to extend thumb types 
		//if ($ext == "jpg") {
			$mklib->CreateImage(120,"../modules/gallery/album/$image", "../modules/gallery/album/$thumb");
		//}
// End
		//try to watermark image.
		if ($mklib->config['watermark_enable']) {
			$mklib->watermark("../modules/gallery/album/$image");
		}
		$query="INSERT INTO mkp_gallery(evento, titolo, descrizione, file, autore, peso, data)VALUES('$evento', '$titolo', '$descrizione', '$image', '$autore', '$peso', $cdata)";
		$DB->query($query);
		$this->update_total();
		$DB->close_db();
	 	Header("Location: index.php?ind=ad_gallery&evento_show=$evento");
		exit;
  }

	// add photo mass
  function add_fotolink() {

    	global $mkportals, $DB,  $mklib;
		$evento = $mkportals->input['evento'];
		$descrizione = $mkportals->input['descrizione'];
		$autore = $mkportals->member['name'];
		$selimages = $mkportals->input['selimages'];
		$query = $DB->query("SELECT id FROM mkp_gallery ORDER BY id DESC LIMIT 1");
		$row = $DB->fetch_row($query);
		$totr = $row['id'];

		if (!$evento || !$descrizione || !$selimages) {
			$message = "{$mklib->lang['ad_all_rows']}";
			$mklib->error_page($message);
			exit;
		}

 			for ($i = 0; $i < @sizeof($selimages); $i++) {
 				++$totr;
				$file = "../modules/gallery/album/$selimages[$i]";
				$peso = filesize($file);
				$cdata = time();
 				$file_name = preg_replace("/(.*?)\.(.*?)$/", "a_$totr.\\2", $selimages[$i]);
 				$titolo = preg_replace("/(.*?)\.(.*?)$/", "\\1", $selimages[$i]);
				@rename($file, "../modules/gallery/album/$file_name");
				if (!is_file ("../modules/gallery/album/$file_name")) {
           				$message = "{$mklib->lang['ad_massnowrite']}";
					$mklib->error_page($message);
					exit;
       				}
				$thumb = "t_$file_name";
				$mklib->CreateImage(120,"../modules/gallery/album/$file_name", "../modules/gallery/album/$thumb");
				//try to watermark image.
				if ($mklib->config['watermark_enable']) {
					$mklib->watermark("../modules/gallery/album/$file_name");
				}
				$DB->query("INSERT INTO mkp_gallery(evento, titolo, descrizione, file, autore, peso, data)VALUES('$evento', '$titolo', '$descrizione', '$file_name', '$autore', '$peso', '$cdata')");
 			}

		$this->update_total();
		$DB->close_db();
	 	Header("Location: index.php?ind=ad_gallery&evento_show=$evento");
		exit;
  }


	function edit_foto() {
    	global $mkportals, $mklib, $Skin, $DB;

		$idf = intval($mkportals->input['ida']);

		$query = $DB->query( "SELECT evento, titolo, descrizione FROM mkp_gallery WHERE id = $idf");
		$row = $DB->fetch_row($query);
		$evento1 = $row['evento'];
		$evento2 = $this->retrieve_event($evento1);
		$titolo = $row['titolo'];
		$descrizione = $row['descrizione'];
		$descrizione = str_replace("<br />", "\n", $descrizione);
		$cselect.= "<option value='$evento1'>$evento2</option>\n";
		$query = $DB->query( "SELECT id, evento FROM mkp_gallery_events ORDER BY `id` DESC");
		while( $row = $DB->fetch_row($query) ) {
			$idevento = $row['id'];
			$evento = $row['evento'];
			$cselect.= "<option value='$idevento'>$evento</option>\n";
		}
		$content = "
	<tr>
	  <td class=\"tdblock\" valign=\"top\">
	  
	    <form action=\"index.php?ind=ad_gallery&amp;op=update_foto\" name=\"FOTO_UPGRADE\" method=\"post\">
	    <table width=\"100%\" border=\"0\">
	      <tr>
		<td class=\"titadmin\" colspan=\"2\"></td>
	      </tr>
	      <tr>
		<td>{$mklib->lang['ad_section']}</td>
		<td>
		  <select name=\"evento\" size=\"1\" class=\"bgselect\">
		  {$cselect}
		  </select>
		</td>
	      </tr>
	      <tr>
		<td>{$mklib->lang['ad_title']}</td>
		<td><input type=\"hidden\" name=\"idf\"  value = \"{$idf}\" /><input type=\"text\" name=\"titolo\"  value = \"{$titolo}\" size=\"52\" class=\"bgselect\" /></td>
	      </tr>
	      <tr>
		<td>{$mklib->lang['ad_description']}</td>
		<td><textarea cols=\"50\" rows=\"4\" name=\"descrizione\" class=\"bgselect\">{$descrizione}</textarea></td>
	      </tr>
	      <tr>
		<td colspan=\"2\" class=\"titadmin\"><input type=\"submit\" value=\"{$mklib->lang['ad_edit']}\" class=\"mkbutton\" /></td>
	      </tr>
	    </table>
	  </form>
	  
	</td>
      </tr>
		";
		$output = $Skin->view_block("{$mklib->lang['ad_editimage']}", "$content");
		$mklib->printpage_admin($mklib->lang['ad_titlepage'].$mklib->lang['tt_sep'].$mklib->lang['ad_galtitle'], $output);
	 }

	function update_foto() {

    	global $mkportals, $DB, $mklib;

		$idf = intval($mkportals->input['idf']);
		$evento = $mkportals->input['evento'];
		$titolo = $mkportals->input['titolo'];
		$descrizione = $mkportals->input['descrizione'];

		if (!$evento || !$titolo || !$descrizione) {
			$message = "{$mklib->lang['ad_all_rows']}";
			$mklib->error_page($message);
			exit;
		}

		$query="UPDATE mkp_gallery SET evento = '$evento', titolo = '$titolo', descrizione = '$descrizione' WHERE id = '$idf'";
		$DB->query($query);
		$DB->close_db();
	 	Header("Location: index.php?ind=ad_gallery&evento_show=$evento");
		exit;
  	}

	function delete_foto() {
    	global $mkportals, $DB;

		$id = intval($mkportals->input['ida']);
		$query = $DB->query( "SELECT evento, descrizione, file FROM mkp_gallery WHERE id = $id");
		$row = $DB->fetch_row($query);
		$evento = $row['evento'];
		$file = $row['file'];
		$thumb = "t_$file";
		@unlink("../modules/gallery/album/$file");
		@unlink("../modules/gallery/album/$thumb");
		$DB->query("DELETE FROM mkp_gallery WHERE id = $id");
		$DB->query("DELETE FROM mkp_gallery_comments WHERE identry = $id");
		$this->update_total();
		$DB->close_db();
		Header("Location: index.php?ind=ad_gallery&evento_show=$evento");
		exit;

	}
	function delete_event() {
    	global $mkportals, $DB, $mklib;

		$idevento = intval($mkportals->input['eventoc']);
		if (!$idevento) {
			$message = "{$mklib->lang['ad_nodelcat']}";
			$mklib->error_page($message);
			exit;
		}
		$query = $DB->query( "SELECT evento FROM mkp_gallery_events WHERE father = '$idevento'");
		while( $row = $DB->fetch_row($query) ) {
			$eventerror = $row['evento'];
		}
		if ($eventerror) {
			$message = "{$mklib->lang['ad_havesubcat']}";
			$mklib->error_page($message);
			exit;
		}
		$DB->query("DELETE FROM mkp_gallery_events WHERE id = $idevento");
		$query = $DB->query( "SELECT id, file FROM mkp_gallery WHERE evento = $idevento");
		while( $row = $DB->fetch_row($query) ) {
			$idf = $row['id'];
			$file = $row['file'];
			$thumb = "t_$file";
			@unlink("../modules/gallery/album/$file");
			@unlink("../modules/gallery/album/$thumb");
			$DB->query("DELETE FROM mkp_gallery WHERE id = $idf");
			$DB->query("DELETE FROM mkp_gallery_comments WHERE identry = $idf");
		}
		$this->update_total();
		$DB->close_db();
	 	Header("Location: index.php?ind=ad_gallery&mode=saved");
		exit;

	}


	//Thumbnail Gallery Preview
	function return_section_preview($idev) {
		global $mkportals, $DB, $mklib;
		$query = $DB->query( "SELECT id, evento, titolo, descrizione, file FROM mkp_gallery WHERE evento = '$idev' ORDER BY `id`");
		$content.= "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\"><tr>";
		$index = 0;
		while( $row = $DB->fetch_row($query) ) {
			$idfoto = $row['id'];
			$evento = $row['evento'];
			$titolo = $row['titolo'];
			$descrizione = $row['descrizione'];
			$file = $row['file'];
			$thumb = "t_$file";
			$content.= "<td align=\"center\" valign=\"bottom\">";
			$content.= "<table border=\"0\" width=\"100\" cellspacing=\"0\" cellpadding=\"0\">";
  			$content.= "<tr>";
    			$content.= "<td width=\"1%\" style=\"font-size: 4px;\"><img src=\"$mklib->images/a_sx_a.gif\" style=\"vertical-align: bottom\" alt=\"\" /></td>";
    			$content.= "<td width=\"98%\" background=\"$mklib->images/a_sf_s.gif\" style=\"font-size: 4px;\"><img src=\"$mklib->images/a_sf_s.gif\" height=\"8\" style=\"vertical-align: bottom\" alt=\"\" /></td>";
    			$content.= "<td width=\"1%\" style=\"font-size: 4px;\"><img src=\"$mklib->images/a_dx_a.gif\" style=\"vertical-align: bottom\" alt=\"\" /></td>";
  			$content.= "</tr><tr>";
    			$content.= "<td width=\"1%\" background=\"$mklib->images/a_sx_s.gif\"><img src=\"$mklib->images/a_sx_s.gif\" width=\"11\" height=\"15\" alt=\"\" /></td>";
    			$content.= "<td width=\"98%\" style=\"background-color:#ffffff;\" align=\"center\">";
		//Thumbnail & title
			if (!file_exists("../modules/gallery/album/$thumb")) {
  				$thumb_mes = $mklib->ResizeImage(120,"../modules/gallery/album/$file");
				$content.= "<img src=\"../modules/gallery/album/$file\" width='$thumb_mes[0]' height='$thumb_mes[1]' border=\"0\" alt=\"\" /><br />$titolo<br /> ";
			} else {
				$content.= "<img src=\"../modules/gallery/album/$thumb\" border=\"0\" alt=\"\" /><br />$titolo<br /> ";
			}
			$content.= "</td>";
   			$content.= "<td width=\"1%\" background=\"$mklib->images/a_dx_s.gif\"><img src=\"$mklib->images/a_dx_s.gif\" width=\"11\" height=\"14\" alt=\"\" /></td>";
  			$content.= "</tr><tr>";
    		$content.= "<td width=\"1%\"><img src=\"$mklib->images/a_sx_g.gif\" height=\"22\" style=\"vertical-align: top\" alt=\"\" /></td>";
    		$content.= "<td width=\"98%\" background=\"$mklib->images/a_sf_g.gif\"></td>";
    		$content.= "<td width=\"1%\" valign=\"top\"><img src=\"$mklib->images/a_dx_g.gif\" height=\"22\" style=\"vertical-align: top\" alt=\"\" /></td>";
  			$content.= "</tr></table>";
				$content.= "<a href=\"index.php?ind=ad_gallery&amp;op=edit_foto&amp;ida=$idfoto\">{$mklib->lang['ad_edit']}</a>";
				$content.= " <a href=\"index.php?ind=ad_gallery&amp;op=delete_foto&amp;ida=$idfoto\" onclick=\"return makesure()\">{$mklib->lang['ad_delete']}</a> <br /><br />";
			$content.= "</td>";
			++ $index;
			if ($index == 4) {
				$index = 0;
				$content.= "</tr>";
				$content.= "<tr>";
			}

		}
		$content.= "</tr></table>";
		if (!$idfoto) {
			$content = "<tr><td align=\"center\"><b>{$mklib->lang['gs_noimages']}</b><br /><br /></td></tr>";
		}
		return $content;
		}

		function save_main() {
    		global $mkportals, $DB, $mklib;
		
		$gallery_sec_page = $mkportals->input['gallery_sec_page'];
  		$gallery_file_page = $mkportals->input['gallery_file_page'];
		$upload_image_max = $mkportals->input['upload_image_max'];
		$mod_gallery = intval($mkportals->input['stato']);
		$approval = $mkportals->input['approvalc'];
		$water_enable = $mkportals->input['water_enable'];
		$waterpos = $mkportals->input['waterpos'];
		$waterlevel = $mkportals->input['waterlevel'];
		if (!$gallery_sec_page || !$gallery_file_page) {
			$message = "{$mklib->lang['ad_all_rows']}";
			$mklib->error_page($message);
			exit;
		}
		$thumb_max_dimen = intval($mkportals->input['thumb_max_dimen']);
		$thumb_jpg_quality = intval($mkportals->input['thumb_jpg_quality']);
		if ($thumb_jpg_quality < 0 || $thumb_jpg_quality > 100) {
			$message = $mklib->lang['ad_badqual'];
			$mklib->error_page($message);
			exit;
		}
		$square_thumbs = intval($mkportals->input['square_thumbs']);
		$classic_thumbs = intval($mkportals->input['classic_thumbs']);

		if ($waterlevel < 0) {$waterlevel = 0;}
		if ($waterlevel > 100) {$waterlevel = 100;}
		//Adjust values and try to watermark image.
		$mklib->config['watermark_level'] = $waterlevel;
		$mklib->config['watermark_pos'] = $waterpos;
			
		if ($water_enable) {
			$mklib->watermark("../include/mkbox.jpg", "../cache/watermark_test.jpg");
		} else  {
			@copy("../include/mkbox.jpg", "../cache/watermark_test.jpg");
		}
		$DB->query("UPDATE mkp_config SET valore ='$gallery_sec_page' WHERE chiave = 'gallery_sec_page'");
		$DB->query("UPDATE mkp_config SET valore ='$gallery_file_page' WHERE chiave = 'gallery_file_page'");
		$DB->query("UPDATE mkp_config SET valore ='$upload_image_max' WHERE chiave = 'upload_image_max'");
		$DB->query("UPDATE mkp_config SET valore ='$mod_gallery' WHERE chiave = 'mod_gallery'");
		$DB->query("UPDATE mkp_config SET valore ='$approval' WHERE chiave = 'approval_gallery'");
		$DB->query("UPDATE mkp_config SET valore ='$water_enable' WHERE chiave = 'watermark_enable'");
		$DB->query("UPDATE mkp_config SET valore ='$waterpos' WHERE chiave = 'watermark_pos'");
		$DB->query("UPDATE mkp_config SET valore ='$waterlevel' WHERE chiave = 'watermark_level'");
		$DB->query("UPDATE mkp_config SET valore ='$thumb_max_dimen' WHERE chiave = 'thumb_max_dimen'");
		$DB->query("UPDATE mkp_config SET valore ='$thumb_jpg_quality' WHERE chiave = 'thumb_jpg_quality'");
		$DB->query("UPDATE mkp_config SET valore ='$square_thumbs' WHERE chiave = 'square_thumbs'");
		$DB->query("UPDATE mkp_config SET valore ='$classic_thumbs' WHERE chiave = 'classic_thumbs'");
		$DB->close_db();
		Header("Location: index.php?ind=ad_gallery&mode=saved");
		exit;
  	}

		//ricava la descrizione di un  evento dal suo id
		function retrieve_event($idevento) {
			global $mkportals, $DB;
			$query = $DB->query( "SELECT evento FROM mkp_gallery_events WHERE id = '$idevento'");
			$evento = $DB->fetch_row($query);
			return $evento['evento'];
		}

		function row_select_event() {
			global $mkportals, $DB, $mklib;

			$chache_sections = array();
			//load cache
			$DB->query( "SELECT id, evento, father FROM mkp_gallery_events ORDER BY `id`");
			while( $row = $DB->fetch_row() ) {
            			$chache_sections[] = $row;
       	 		}
			
			$listall = array();
			$children = array();
			foreach ($chache_sections as $row) {
				if ($row['father']) {
					$children[ $row['father'] ][] = $row;
				}
			}
			
			foreach ($chache_sections as $row) {
				$idevento = $row['id'];
				$evento = $row['evento'];
				$father = $row['father'];
				if (!in_array($idevento, $listall) && !$row['father']) {
					$cselect.= "<option value=\"$idevento\">$evento</option>\n";
					$listall[] = $idevento;
				}
				$pref = "|";
				if (count($children[$idevento]) > 0) {
					foreach($children[$idevento] as $row3) {
						$pref = "|--";
						if (!in_array($row3['id'], $listall)) {
							$cselect.= "<option value=\"$row3[id]\">$pref $row3[evento]</option>\n";
							$listall[] = $row3['id'];
						}
						$idevento = $row3['id'];
						while ($idevento) {
				   			$pref .= "--";
				   			$newfather = "";
				   			reset ($chache_sections);
				   			foreach ($chache_sections as $row2) {
								if ($idevento == $row2['father']) {
									$newfather = $row2['id'];
									if (!in_array($row2['id'], $listall)) {
										$cselect.= "<option value=\"{$row2['id']}\">$pref {$row2['evento']}</option>\n";
										$listall[] = $row2['id'];
									}
								}
				   			}
				   		$idevento = $newfather;
				 		}
				
					}
				}
			
			}
			return $cselect;
			
		}
		/// Select photo for dir "modules/gallery/album/"
		function mass_file() {
			if( $dir = opendir("../modules/gallery/album/") ){
				while( $sub_dir = @readdir($dir) ){
				$sub_dir = preg_replace("/a_(\d+).(.*?)$/", ".", $sub_dir);
  				$sub_dir = preg_replace("/a_no_image.gif$/", ".", $sub_dir);
				switch($type = strtolower(substr($sub_dir, (strlen($sub_dir)-3), 3))){
							case 'jpg':
							$ok = 'jpg';
							break;
							case 'png':
							$ok = 'png';
							break;
							case 'tif':
							$ok = 'tif';
							break;
							case 'bmp':
							$ok = 'bmp';
							break;
							default :
							$ok = 'gif';
							break;
						}
					if( $sub_dir != "." && $sub_dir != ".." && $type == $ok ){							
							for($i = 0; $i < count($sub_dir); $i++){								
							$image[] = $sub_dir;
							}
						}
					}
 					for ($i = 0; $i < @sizeof($image); $i++) {
  					$output .= "\n<OPTION VALUE=\"$image[$i]\"";
  						if (@in_array($image[$i], $image)) {
   						$output .= " SELECTED=\"SELECTED\"";
  					}
  						$output .= ">".$image[$i]."</OPTION>";
 				}
 				 return($output);
			}	
		}
		
	function update_total() {
		global $DB;
		$query = $DB->query( "SELECT id FROM mkp_gallery WHERE validate = '1'");
		$count = $DB->get_num_rows($query);
		$DB->query("UPDATE mkp_stat SET valore ='$count' WHERE chiave = 'tot_gallery'");
	
	}

}

?>
