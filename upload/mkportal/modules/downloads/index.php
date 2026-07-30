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

$idx = new mk_downloads;
class mk_downloads {

	var $tpl       = "";
	var $chache_sections = array();
	var $chache_download = array();

	function mk_downloads() {

		global $mkportals, $mklib,  $Skin, $DB, $mklib_board;
		/*
		$message = "Download Area in maintenance";
		$mklib->error_page($message);
		exit;
		*/

		$mklib->load_lang("lang_download.php");

		if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_access_download']) {
			$message = "{$mklib->lang['dw_unauth']}";
			$mklib->error_page($message);
			exit;
		}

		//load cache
		$DB->query( "SELECT * FROM mkp_download_sections ORDER BY `id`");
		while( $row = $DB->fetch_row() ) {
            		$this->chache_sections[] = $row;
       	 	}
		$DB->query( "SELECT * FROM mkp_download WHERE validate = '1' ORDER BY `id`");
		while( $row = $DB->fetch_row() ) {
            		$this->chache_download[] = $row;
       	 	}
		//location
		$mklib_board->store_location("downloads");

		if ($mklib->config['mod_downloads']) {
		$message = "{$mklib->lang['dw_mnoactive']}";
			$mklib->error_page($message);
			exit;
		}

		require "mkportal/modules/downloads/tpl_downloads.php";
		$this->tpl = new tpl_downloads();

    		switch($mkportals->input['op']) {
    			case 'section_view':
    				$this->section_view();
    			break;
			case 'submit_file':
    				$this->submit_file();
    			break;
			case 'add_file':
    				$this->add_file();
    			break;
			case 'edit_file':
    				$this->edit_file();
    			break;
			case 'update_file':
    				$this->update_file();
    			break;
			case 'del_file':
    				$this->del_file();
    			break;
			case 'entry_view':
    				$this->entry_view();
    			break;
			case 'submit_comment':
    				$this->submit_comment();
    			break;
			case 'add_comment':
    				$this->add_comment();
    			break;
			case 'del_comment':
    				$this->del_comment();
    			break;
			case 'download_file':
    				$this->download_file();
    			break;
			case 'submit_rate':
    				$this->submit_rate();
    			break;
			case 'add_rate':
    				$this->add_rate();
    			break;
			case 'search':
    				$this->search();
    			break;
			case 'result_search':
    				$this->result_search();
    			break;
			case 'show_emoticons':
    				$this->show_emoticons();
    			break;
				default:
    				$this->downloads_show();
    			break;
    		}
	}

	function downloads_show() {
        global $mkportals, $DB, $mklib, $Skin, $mklib_board;

	$navbar = "<a href=\"/index.php?ind=downloads\">{$mklib->lang['dw_ptitle2']}</a>";
	$maintit = $mklib->lang['dw_ptitle'];
	$content = $this->tpl->row_main_category();
	switch(intval($mkportals->input['order'])) {
		case '2':
			$order = "ORDER BY `evento`";
    	break;
		case '3':
			$order = "ORDER BY `id` DESC";
    	break;
		default:
    		$order = "ORDER BY `position`";
    	break;
	}
	//pagination
	//$query = $DB->query( "SELECT id FROM mkp_download_sections WHERE father = '0'");
	//$countpage = $DB->get_num_rows($query);
	$countpage = 0;
		foreach ($this->chache_sections as $r) {
			if(!$r['father']) {
				++$countpage;
			}
		}
	
	$per_page = $mklib->config['download_sec_page'];
	if ($per_page=="" or $per_page <= 0) {
			$per_page	=	10;
	}

	$start = intval($mkportals->input['start']);
	$q_page = intval($mkportals->input['st']);
		if ($q_page=="" or $q_page <= 0) {
			$q_page	=	0;
		}
	$start = $q_page;
	$show_pages = $mklib->build_pages( array( TOTAL_POSS  => $countpage,
							PER_PAGE    => $per_page,
							CUR_ST_VAL  => $q_page,
							L_SINGLE    => '',
							L_MULTI     => 'pagine',
						    BASE_URL    => '/index.php?ind=downloads&amp;order='.intval($mkportals->input['order'])
										  )
	);

	$query = $DB->query( "SELECT id, evento, descrizione, position FROM mkp_download_sections  WHERE father = '0'  $order LIMIT $start, $per_page");
        while( $row = $DB->fetch_row($query) ) {
		$count = 0;
		$idevento = $row['id'];
		$evento = $row['evento'];
		$descrizione = $row['descrizione'];
		$totalson = $this->total_son($idevento);
		$countsub = $totalson[0];
		$count = $totalson[1];
		$lastentry = $totalson[2];
		
		/*
		$query1 = $DB->query("SELECT id FROM mkp_download_sections  WHERE father = '$idevento' ORDER BY `id`");
		$countsub = $DB->get_num_rows($query1);
		while( $row1 = $DB->fetch_row($query1) ) {
			$idce = $row1['id'];
			$query2 = $DB->query("SELECT name FROM mkp_download where idcategoria = '$idce' AND validate = '1' ORDER BY `id` DESC");
			$count = $count + $DB->get_num_rows($query2);
			$entry2 = $DB->fetch_row($query2);
		}
		*/
		/*
		$query1 = $DB->query("SELECT name FROM mkp_download where idcategoria = '$idevento' AND validate = '1' ORDER BY `id` DESC");
		$entry = $DB->fetch_row($query1);
		$lastentry = $entry['name'];
		if(!$lastentry) {
			$lastentry = $entry2['name'];
		}
		$count = $count + $DB->get_num_rows($query1);
		*/
		$name ="<a href=\"/index.php?ind=downloads&amp;op=section_view&amp;idev=$idevento\">$evento</a>";
		$link = "<a href=\"/index.php?ind=downloads&amp;op=section_view&amp;idev=$idevento\"><img src=\"$mklib->images/category.gif\" border=\"0\" alt=\"\" /></a>";
		$content .= $this->tpl->row_main_category_content($name, $descrizione, $count, $lastentry, $link, $countsub);
	}
		
	$submit = " <a href=\"/index.php?ind=downloads&amp;op=submit_file\">[ {$mklib->lang['dw_send']} ]</a> ";
	if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_send_download']) {
			$submit ="";
	}
	$stat = $this->retrieve_stat();	
	$jump1 = $this->row_select_event("1");
	$jump = "
	<select name=\"jumpsection\" size=\"1\" onchange=\"selChd(this)\" class=\"bgselect\">
       	$jump1
	</select>
	 ";
	 $sort = "
	<select name=\"order\" size=\"1\" onchange=\"selChoc(this)\" class=\"bgselect\">
	  <option value=\"0\">{$mklib->lang['dw_order']}</option>\n
	  <option value=\"1\">{$mklib->lang['dw_ordpos']}</option>\n
	  <option value=\"2\">{$mklib->lang['dw_ordnamec']}</option>\n
	  <option value=\"3\">{$mklib->lang['dw_ordcrea']}</option>\n
      	</select>
	 ";
	$toolbar = $this->tpl->row_toolbar($jump, $sort);	
	$utonline = $mklib_board->get_active_users("downloads");
	$output  = $this->tpl->downloads_show($navbar, $maintit, $content, $submit, $stat, $toolbar, $show_pages, $utonline);	
	$blocks = $Skin->view_block("{$mklib->lang['dw_pagetitle']}", $output);
	$mklib->printpage("1", "1", $mklib->sitename.$mklib->lang['tt_sep'].$mklib->lang['dw_pagetitle'], $blocks);
	}

	function section_view() {
		global $mkportals, $DB, $mklib, $Skin, $mklib_board;
		$content = "";
		$idev = intval($mkportals->input['idev']);
		$even = $this->retrieve_event($idev);
		$navbar = "<a href=\"/index.php?ind=downloads\">{$mklib->lang['dw_ptitle2']}</a>";
		$navfather = $this->retrieve_father($idev);
		if($navfather['1']) {
			$navbar .= "{$mklib->lang['bc_sep']}<a href=\"/index.php?ind=downloads&amp;op=section_view&amp;idev={$navfather['0']}\">{$navfather['1']}</a>";
		}
		$navbar .= "{$mklib->lang['bc_sep']}<a href=\"#\">$even</a>";
		$maintit = $even;
		$query = $DB->query( "SELECT id, evento, descrizione, position FROM mkp_download_sections WHERE father = '$idev' ORDER by position");
		$cecksub = $DB->get_num_rows($query);
		if($cecksub) {
			$content = $this->tpl->row_main_category();
			while( $row = $DB->fetch_row($query) ) {
			$idevento = $row['id'];
			$evento = $row['evento'];
			$descrizione = $row['descrizione'];
			$totalson = $this->total_son($idevento);
			$countsub = $totalson[0];
			$count = $totalson[1];
			$lastentry = $totalson[2];
			$name ="<a href=\"/index.php?ind=downloads&amp;op=section_view&amp;idev=$idevento\">$evento</a>";
			$link = "<a href=\"/index.php?ind=downloads&amp;op=section_view&amp;idev=$idevento\"><img src=\"$mklib->images/category.gif\" border=\"0\" alt=\"\" /></a>";
			$content .= $this->tpl->row_main_category_content($name, $descrizione, $count, $lastentry, $link, $countsub);
			}
		}


		switch(intval($mkportals->input['order'])) {
		case '1':
			$order = "ORDER BY `name`";
    	break;
		default:
    		$order = "ORDER BY `id` DESC";
    	break;
		}
		//pagination
		$countpage = 0;
		foreach ($this->chache_download as $r) {
			if($r['idcategoria'] == $idev) {
				++$countpage;
			}
		}

		if($countpage) {
			$content .= $this->tpl->row_main_entries();
		}

		$per_page = $mklib->config['download_file_page'];
		if ($per_page=="" or $per_page <= 0) {
			$per_page	=	10;
		}
		$start = intval($mkportals->input['start']);
		$q_page = intval($mkportals->input['st']);
		if ($q_page=="" or $q_page <= 0) {
			$q_page	=	0;
		}
		$start = $q_page;
		$show_pages = $mklib->build_pages( array( TOTAL_POSS  => $countpage,
							PER_PAGE    => $per_page,
							CUR_ST_VAL  => $q_page,
							L_SINGLE    => '',
							L_MULTI     => 'pagine',
						    BASE_URL    => '/index.php?ind=downloads&amp;op=section_view&amp;idev='.$idev.'&amp;order='.intval($mkportals->input['order'])
										  )
		);
		$query = $DB->query( "SELECT id, name, downloads, click, data, trate FROM mkp_download WHERE idcategoria = '$idev' AND validate = '1' $order LIMIT $start, $per_page");
		while( $row = $DB->fetch_row($query) ) {
			$iden = $row['id'];
			$name = $row['name'];
			$trate = $row['trate'];
			$downloads = $row['downloads'];
			$click = $row['click'];
			$data = $mklib->create_date($row['data'], "short");
			$name ="<a href=\"/index.php?ind=downloads&amp;op=entry_view&amp;iden=$iden\">$name</a>";
			$content .= $this->tpl->row_main_entries_content($name, $trate, $downloads, $click, $data);
		}
		$submit = " <a href=\"/index.php?ind=downloads&amp;op=submit_file\">[ {$mklib->lang['dw_send']} ]</a> ";
		if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_send_download']) {
			$submit ="";
		}
		$stat = $this->retrieve_stat();
		$jump1 = $this->row_select_event("1");
		$jump = "
		<select name=\"jumpsection\" size=\"1\" onchange=\"selChd(this)\"  class=\"bgselect\">
       		$jump1
		</select>
	 	";
	 	$sort = "
		<select name=\"order\" size=\"1\" onchange=\"selChoe(this, '$idev')\"  class=\"bgselect\">
		  <option value=\"0\">{$mklib->lang['dw_order']}</option>\n
		  <option value=\"1\">{$mklib->lang['dw_ordnamef']}</option>\n
		  <option value=\"2\">{$mklib->lang['dw_ordinsert']}</option>\n
		</select>
		 ";
		$toolbar = $this->tpl->row_toolbar($jump, $sort);
		$utonline = $mklib_board->get_active_users("downloads");
		$output  = $this->tpl->downloads_show($navbar, $maintit, $content, $submit, $stat, $toolbar, $show_pages, $utonline);
		$blocks = $Skin->view_block("{$mklib->lang['dw_pagetitle']}", $output);
		$mklib->printpage("1", "1", $mklib->sitename.$mklib->lang['tt_sep'].$mklib->lang['dw_pagetitle'].$mklib->lang['tt_sep'].$maintit, $blocks);
	}

	function entry_view() {
		global $mkportals, $DB, $mklib, $Skin, $mklib_board;
		$iden = intval($mkportals->input['iden']);
		$query = $DB->query( "SELECT id, idcategoria, name, description, file, click, trate, rate, screen1, screen2, demo, autore, idauth, peso FROM mkp_download WHERE id = '$iden' AND validate = '1'");
		$row = $DB->fetch_row($query);
		if(!$row) {
            		$message = "{$mklib->lang['error_404']}";
            		$mklib->error_page($message);
            		exit;
		}
		$id = $row['id'];
		$click = $row['click'];
		$idcategoria = $row['idcategoria'];
		$name = $row['name'];
		$description = $row['description'];
		$file = $row['file'];
		$trate = $row['trate'];
		$rate = $row['rate'];
		$autore = $row['autore'];
		$idauth = $row['idauth'];
		$peso = round(($row['peso']/1024),2)." Kb";
		$even = $this->retrieve_event($idcategoria);
		$width = round(($rate*100)/4) - 6;
	 	$width2 = $width - 4;
	 	$modname ='download';
        $rating = $mklib->pullRating($id, $modname, $rate, $trate);
		$screens = "-";
		$demo = "-";
		if ($row['screen1'])  {
			$screens = "<a target=\"_blank\" href=\"{$row['screen1']}\"><img border=\"0\" src=\"{$row['screen1']}\" width=\"120\" height=\"120\"></a>&nbsp;&nbsp;&nbsp;&nbsp;";
		}
		if ($row['screen2'])  {
			$screens .= " <a target=\"_blank\" href=\"{$row['screen2']}\"><img border=\"0\" src=\"{$row['screen2']}\" width=\"120\" height=\"120\"></a>";
		}
		if ($row['demo'])  {
			$demo = " <a href=\"{$row['demo']}\" target=\"_new\">{$row['demo']}</a>";
		}

		//Check download perms
		$dlicon = (!$mkportals->member['g_access_cp'] && !$mklib->member['g_download_files']) ? "<img src=\"{$mklib->images}/view_no.gif\" border=\"0\" alt=\"{$mklib->lang['dw_dwfileno']}\" title=\"{$mklib->lang['dw_dwfileno']}\" />" : "<a href=\"/index.php?ind=downloads&amp;op=download_file&amp;ide={$id}&amp;file={$file}\" title=\"{$mklib->lang['dw_dwfile']}\"><img src=\"{$mklib->images}/view.gif\" border=\"0\" alt=\"{$mklib->lang['dw_dwfile']}\" /></a>";

		$dllink = (!$mkportals->member['g_access_cp'] && !$mklib->member['g_download_files']) ? "{$mklib->lang['dw_dwfileno']}" : "<a href=\"/index.php?ind=downloads&amp;op=download_file&amp;ide={$id}&amp;file={$file}\" title=\"{$mklib->lang['dw_dwfile']}\">{$mklib->lang['dw_dwfile']}</a>";

	 	$content .= $this->tpl->row_entry($id, $name, $description, $trate, $rate, $width2, $width, $screens, $demo, $dlicon, $dllink, $autore, $peso, $rating);
		$content .= "
		<tr>
		  <td class=\"tdblock\" colspan=\"2\">
		  {$mklib->lang['dw_comments']}
		  </td>
		</tr>
		";
		if($mklib->member['g_send_comments'] || $mkportals->member['g_access_cp']) {
		$editorscript = "";
			$textarepar = "";
			$textarew = "75%";
			$bbeditor= $mklib->get_commentbbeditor();
			$captcha = $mklib->antibot_start();
			$bbcomnt ="<form action=\"/index.php?ind=downloads&amp;op=add_comment&amp;ide={$id}\" name=\"editor\" method=\"post\" >
				<table class=\"modulecell\" width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"8\" >		
				  <tr>
        	
				    <td class=\"modulecell\" rowspan=\"3\" align=\"center\" height=\"100%\">
				      <input type=\"hidden\" name=\"ide\" value=\"$id\" />
		
				      <td class=\"modulecell\" width=\"70%\" align=\"left\">
		                      $bbeditor
		             <textarea cols=\"10\" style=\"width:75%\" rows=\"5\" name=\"ta\" id=\"ta\"></textarea>
				    <td class=\"modulecell\">{$mklib->lang['ne_writecomm']}</td>
				  </tr>
				  <tr>
				    <td class=\"modulecell\" width=\"70%\" align=\"left\">
                    $captcha
				    </td>
				  </tr>
				  <tr>
				    <td class=\"modulecell\">
				      <input type=\"submit\" name=\"submit\" value=\"{$mklib->lang['dw_sendcomm']}\" class=\"button2\" accesskey=\"s\" /><br />
				    </td>
				  </tr>		
				</table>
				</form>";
		}
		$query = $DB->query( "SELECT id, autore, testo, data FROM mkp_download_comments WHERE identry = '$id' ORDER BY `id` DESC");
		while( $row = $DB->fetch_row($query) ) {
			$idcomm = $row['id'];
			$autorec = $row['autore'];
			$testo = $row['testo'];
			$testo = stripslashes($row['testo']);
			$testo = $mklib->decode_bb($testo);
			$testo = $mklib_board->decode_smilies($testo);
			$data = $mklib->create_date($row['data'], "short");
			$delete = "
			<script type=\"text/javascript\">

			function makesure() {
			if (confirm('{$mklib->lang[dw_delcommconf]}')) {
			return true;
			} else {
			return false;
			}
			}

			</script><a href=\"/index.php?ind=downloads&amp;op=del_comment&amp;idcomm=$idcomm&amp;iden=$iden\" onclick=\"return makesure()\">[ {$mklib->lang['dw_delete']} ]</a>
			";

			if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_mod_download']) {
				$delete = "";
			}
			$content .= "
			<tr>
                          <td class=\"modulecell\" width=\"20%\" valign=\"top\">{$autorec}<br />{$data}<br />{$delete}</td>
                          <td class=\"modulecell\" width=\"80%\" valign=\"middle\">{$testo}</td>
			</tr>
			";
		}
		$content .="<table width=\"100%\"><tr><td width=\"100%\">$bbcomnt</td>
			</tr>";
		++$click;
		$DB->query("UPDATE mkp_download SET click ='$click' WHERE id = '$iden'");
		$navbar = "<a href=\"/index.php?ind=downloads\">{$mklib->lang['dw_ptitle2']}</a>{$mklib->lang['bc_sep']}<a href=\"/index.php?ind=downloads&amp;op=section_view&amp;idev=$idcategoria\">$even</a>{$mklib->lang['bc_sep']}<a href=\"#\">$name</a>";
		$maintit = $name;
		$submit = "<script type=\"text/javascript\">
			function makesure2() {
			if (confirm('{$mklib->lang[dw_delfileconf]}')) {
			return true;
			} else {
			return false;
			}
			}
			</script><a href=\"/index.php?ind=downloads&amp;op=edit_file&amp;iden=$id\">[ {$mklib->lang['dw_edit']} ]</a>  <a href=\"/index.php?ind=downloads&amp;op=del_file&amp;iden=$id\" onclick=\"return makesure2()\">[ {$mklib->lang['dw_delete']} ]</a> ";

		if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_mod_download'] && (!$mklib->member['g_send_download'] || $mkportals->member['id'] != $idauth || $idauth == 0 )) {
			$submit = "";
		}
		$stat = $this->retrieve_stat();
		$toolbar = "";
		$utonline = $mklib_board->get_active_users("downloads");
		$output  = $this->tpl->downloads_show($navbar, $maintit, $content, $submit, $stat, $toolbar, $show_pages, $utonline);
		$blocks = $Skin->view_block("{$mklib->lang['dw_pagetitle']}", $output);
		$mklib->printpage("1", "1", $mklib->sitename.$mklib->lang['tt_sep'].$mklib->lang['dw_pagetitle'].$mklib->lang['tt_sep'].$even.$mklib->lang['tt_sep'].$name, $blocks);
	}

	function submit_file() {
		global $mkportals, $DB, $mklib, $Skin, $mklib_board;

		if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_send_download']) {
			$message = "{$mklib->lang['dw_nosend']}";
			$mklib->error_page($message);
			exit;
		}

		$navbar = "<a href=\"/index.php?ind=downloads\">{$mklib->lang['dw_ptitle2']}</a>{$mklib->lang['bc_sep']}<a href=\"#\">{$mklib->lang['dw_send']}</a>";
		$maintit = "Downloads";
		/*
		$query = $DB->query( "SELECT id, evento FROM mkp_download_sections ORDER BY `id` DESC");
		while( $row = $DB->fetch_row($query) ) {
				$idevento = $row['id'];
				$evento = $row['evento'];
				$cselect.= "<option value='$idevento'>$evento</option>\n";
		}
		*/
		$cselect = $this->row_select_event();
		if ($cselect == FALSE) {
			$message = "{$mklib->lang['dw_nocat']}";
			$mklib->error_page($message);
			exit;
        	}
		$content .= "
		<tr>
		  <td>
		  
		    <form action=\"/index.php?ind=downloads&amp;op=add_file\" name=\"UPDOWN\" method=\"post\" enctype=\"multipart/form-data\">
		    <table width=\"100%\" border=\"0\">
		      <tr>
			<td class=\"titadmin\" colspan=\"2\">{$mklib->lang['dw_send']}</td>
		      </tr>
		      <tr>
			<td>{$mklib->lang['dw_section']}</td>
			<td>
			  <select class=\"bgselect\" name=\"evento\" size=\"1\">
			  {$cselect}
			  </select>
			</td>
		      </tr>
		      <tr>
			<td width=\"10%\">{$mklib->lang['dw_title']}</td>
			<td width=\"90%\"><input type=\"text\" name=\"titolo\" size=\"52\" class=\"bgselect\" /></td>
		      </tr>
		      <tr>
			<td width=\"10%\" valign=\"top\">{$mklib->lang['dw_description']}</td>
			<td width=\"90%\"><textarea cols=\"50\" rows=\"10\" name=\"descrizione\" class=\"bgselect\"></textarea></td>
		      </tr>
		      <tr>
			<td width=\"10%\">{$mklib->lang['dw_file']}</td>
			<td width=\"90%\"><input type=\"file\" name=\"FILE_UPLOAD\" size=\"50\" class=\"bgselect\" /></td>
		      </tr>
		      <tr>
			<td width=\"10%\">{$mklib->lang['dw_screen1']}</td>
			<td width=\"90%\"><input type=\"text\" name=\"screen1\" size=\"52\" class=\"bgselect\" /></td>
		      </tr>
		      <tr>
			<td width=\"10%\">{$mklib->lang['dw_screen2']}</td>
			<td width=\"90%\"><input type=\"text\" name=\"screen2\" size=\"52\" class=\"bgselect\" /></td>
		      </tr>
		      <tr>
			<td width=\"10%\">{$mklib->lang['dw_demourl']}</td>
			<td width=\"90%\"><input type=\"text\" name=\"demo\" size=\"52\" class=\"bgselect\" /></td>
		      </tr>
		      <tr>
			<td colspan=\"2\">
			<input type=\"submit\" value=\"{$mklib->lang['dw_insert']}\" class=\"mkbutton\" />
			</td>
		      </tr>
		    </table>
		    </form>
		  </td>
		</tr>
		";

		$submit = " <a href=\"/index.php?ind=downloads&amp;op=submit_file\">[ {$mklib->lang['dw_send']} ]</a> ";
		$stat = $this->retrieve_stat();
		$toolbar = "";
		$utonline = $mklib_board->get_active_users("downloads");
		$output  = $this->tpl->downloads_show($navbar, $maintit, $content, $submit, $stat, $toolbar, $show_pages, $utonline);
		$blocks = $Skin->view_block("{$mklib->lang['dw_pagetitle']}", $output);
		$mklib->printpage("1", "1", $mklib->sitename.$mklib->lang['tt_sep'].$mklib->lang['dw_pagetitle'].$mklib->lang['tt_sep'].$mklib->lang['dw_send'], $blocks);
	}

	function edit_file() {
		global $mkportals, $DB, $mklib, $Skin, $mklib_board;


		$maintit = "{$mklib->lang['dw_ptitle']}";
		$iden = intval($mkportals->input['iden']);
		$query = $DB->query( "SELECT idcategoria, name, description, screen1, screen2, demo, autore, idauth FROM mkp_download WHERE id = '$iden'");
		$row = $DB->fetch_row($query);
		if(!$row) {
            		$message = "{$mklib->lang['error_404']}";
            		$mklib->error_page($message);
            		exit;
		}
		$idcategoria = $row['idcategoria'];
		$nav_ev = $this->retrieve_event($idcategoria);
		$name = $row['name'];
		$screen1 = $row['screen1'];
		$screen2 = $row['screen2'];
		$demo = $row['demo'];
		$autore = $row['autore'];
		$idauth = $row['idauth'];
		$description = $row['description'];
		$description = str_replace("<br />", "\n", $description);

		if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_mod_download'] && (!$mklib->member['g_send_download'] || $mkportals->member['id'] != $idauth || $idauth == 0 )) {
			$message = "{$mklib->lang['dw_noedit']}";
			$mklib->error_page($message);
			exit;
		}
/*
		$query = $DB->query( "SELECT id, evento FROM mkp_download_sections ORDER BY `id` DESC");
  		while( $row = $DB->fetch_row($query) ) {
			$idevento = $row['id'];
			$evento = $row['evento'];
			$selected = "";
			if($idevento == $idcategoria) {
				$selected = "selected=\"selected\"";
			}
			$cselect.= "<option value='$idevento' $selected>$evento</option>\n";
		}
*/


			$query = $DB->query( "SELECT id, evento, father FROM mkp_download_sections ORDER BY `id`");
			while( $row = $DB->fetch_row($query) ) {
				$idevento = $row['id'];
				$selected = "";
				if($idevento == $idcategoria) {
					$selected = "selected=\"selected\"";
				}
				$evento = $row['evento'];
				$father = $row['father'];
				if(!$listall[$idevento]) {
					$cselect.= "<option value='$idevento' $selected>$evento</option>\n";
				}
				$listall[$idevento] = 1;
				$query1 = $DB->query( "SELECT id, evento, father FROM mkp_download_sections WHERE father = '$idevento' ORDER BY `id`");
				while( $row2 = $DB->fetch_row($query1) ) {
					$idevento = $row2['id'];
					$selected = "";
					if($idevento == $idcategoria) {
						$selected = "selected=\"selected\"";
					}
					$evento = $row2['evento'];
					if(!$listall[$idevento]) {
						$cselect.= "<option value='$idevento' $selected>- $evento</option>\n";
					}
					$listall[$idevento] = 1;
				}
			}

		$content .= "
		<tr>
		  <td>
		  
		    <form action=\"/index.php?ind=downloads&amp;op=update_file&amp;iden=$iden\" name=\"UPDATE\" method=\"post\" enctype='multipart/form-data'>
		    <table width=\"100%\" border=\"0\">
		      <tr>
			<td class=\"titadmin\" colspan=\"2\">{$mklib->lang['dw_editf']}</td>
		      </tr>
		      <tr>
			<td>{$mklib->lang['dw_section']}</td>
			<td>
			  <select class=\"bgselect\" name=\"evento\" size=\"1\">
			  {$cselect}
			  </select>
			</td>
		      </tr>
		      <tr>
			<td width=\"10%\">{$mklib->lang['dw_title']}</td>
			<td width=\"90%\"><input type=\"text\" name=\"titolo\" value=\"$name\" size=\"52\" class=\"bgselect\" /></td>
		      </tr>
		      <tr>
			<td width=\"10%\" valign=\"top\">{$mklib->lang['dw_description']}</td>
			<td width=\"90%\"><textarea cols=\"50\" rows=\"10\" name=\"descrizione\" class=\"bgselect\">$description</textarea></td>
		      </tr>
			  <tr>
                 <td width=\"10%\">{$mklib->lang['dw_file_update']}</td>
                 <td width=\"90%\"><input type=\"file\" name=\"FILE_UPLOAD\" size=\"52\" class=\"bgselect\" /></td>
               </tr>
		      <tr>
			<td width=\"10%\">{$mklib->lang['dw_screen1']}</td>
			<td width=\"90%\"><input type=\"text\" name=\"screen1\" value=\"$screen1\" size=\"52\" class=\"bgselect\" /></td>
		      </tr>
		      <tr>
			<td width=\"10%\">{$mklib->lang['dw_screen2']}</td>
			<td width=\"90%\"><input type=\"text\" name=\"screen2\" value=\"$screen2\" size=\"52\" class=\"bgselect\" /></td>
		      </tr>
		      <tr>
			<td width=\"10%\">{$mklib->lang['dw_demourl']}</td>
			<td width=\"90%\"><input type=\"text\" name=\"demo\" value=\"$demo\" size=\"52\" class=\"bgselect\" /></td>
		      </tr>
		      <tr>
			<td colspan=\"2\">
			<input class=\"mkbutton\" type=\"submit\" value=\"{$mklib->lang['dw_save']}\" />
			</td>
		      </tr>
		    </table>
		    </form>
		  </td>
		</tr>
		";

		//$submit = " <a href=\"/index.php?ind=downloads&amp;op=del_file&amp;iden=$iden\">[ Elimina ]</a> ";
		$navbar = "<a href=\"/index.php?ind=downloads\">{$mklib->lang['dw_ptitle2']}</a>{$mklib->lang['bc_sep']}<a href=\"/index.php?ind=downloads&amp;op=section_view&amp;idev=$idcategoria\">$nav_ev</a>{$mklib->lang['bc_sep']}<a href=\"/index.php?ind=downloads&amp;op=entry_view&amp;iden=$iden\">$name</a>{$mklib->lang['bc_sep']}<a href=\"#\">{$mklib->lang['dw_editf']}</a>";
		$stat = $this->retrieve_stat();
		$toolbar = "";
		$utonline = $mklib_board->get_active_users("downloads");
		$output  = $this->tpl->downloads_show($navbar, $maintit, $content, $submit, $stat, $toolbar, $show_pages, $utonline);
		$blocks = $Skin->view_block("{$mklib->lang['dw_pagetitle']}", $output);
		$mklib->printpage("1", "1", $mklib->sitename.$mklib->lang['tt_sep'].$mklib->lang['dw_pagetitle'].$mklib->lang['tt_sep'].$name.$mklib->lang['tt_sep'].$mklib->lang['dw_editf'], $blocks);
	}


	function add_file() {

    	global $mkportals, $DB,  $_FILES, $mklib, $mklib_board;

		if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_send_download']) {
			$message = "{$mklib->lang['dw_nosend']}";
			$mklib->error_page($message);
			exit;
		}

		$evento = intval($mkportals->input['evento']);
		$titolo = $mkportals->input['titolo'];
		$screen1 = $mkportals->input['screen1'];
		$screen2 = $mkportals->input['screen2'];
		$demo = $mkportals->input['demo'];
		$descrizione = $mkportals->input['descrizione'];

		if (!$evento || !$titolo || !$descrizione) {
			$message = "{$mklib->lang['dw_reqtcd']}";
			$mklib->error_page($message);
			exit;
		}

		$FILE_UPLOAD = $mkportals->input['FILE_UPLOAD'];
		$file =  $_FILES['FILE_UPLOAD']['tmp_name'];
		$file_name =  $_FILES['FILE_UPLOAD']['name'];
		$file_type =  $_FILES['FILE_UPLOAD']['type'];
		$peso =  $_FILES['FILE_UPLOAD']['size'];

		if (!$file || !$file_name || $file_name == 'none' || $peso<=0) {
			$message = "{$mklib->lang['dw_nofile']}";
			$mklib->error_page($message);
			exit;
		}

		$file_ext = preg_replace("`.*\.(.*)`", "\\1", $file_name);
		if (!$mklib->check_attach($file_type, $file_ext))  {
			$message = $file_type;
			$message .= " - {$mklib->lang['error_filetype']}";
			$mklib->error_page($message);
			exit;
		}

		//Replace illegal sub-extensions
		/*
		$com_types = array('com', 'exe', 'bat', 'scr', 'pif', 'asp', 'cgi', 'pl', 'php');
		foreach ($com_types AS $bad) {
			$file_name = str_replace(".$bad", "_$bad", $file_name);
		}	
        */
		
		//Added by Kimi in C1.2.2 but this is by Mark
		//Replace illegal sub-extensions
		$file_name = preg_replace("/\.(com|exe|bat|scr|pif|asp|cgi|pl|php)/i", "_$1", $file_name);

		//Replace illegal characters
		$file_name = preg_replace('/[^\w.-]/', '_', $file_name);

		$autore = $mkportals->member['name'];
		$idauth = $mkportals->member['id'];

		if ($mklib->config['upload_file_max'] > 0 && $peso > ($mklib->config['upload_file_max']*1024)) {
			$message = "{$mklib->lang['dw_toobig']}";
			$mklib->error_page($message);
			exit;
		}

		$validat = "1";
		$approval = $mklib->config['approval_download'];
		if ($approval == "2" || $approval == "3") {
			$validat = 0;
		}
		if($mkportals->member['g_access_cp']) {
			$validat = "1";
		}

		$query="INSERT INTO mkp_download(idcategoria, name, description, file, data, screen1, screen2, demo, autore, idauth, peso, validate)VALUES('$evento', '$titolo', '$descrizione', '$file_name', '".(time())."', '$screen1', '$screen2', '$demo', '$autore', '$idauth', '$peso', '$validat')";
		$DB->query($query);
        $insert_id = $DB->get_insert_id();
        $real_file = $MK_PATH."mkportal/modules/downloads/file/mk_".$insert_id."_".$file_name;
        $real_file = preg_replace("`(.*)\..*`", "\\1", $real_file);
        $real_file .= ".mk";
		if (is_file ($real_file)) {
    		$DB->query("DELETE FROM mkp_download WHERE id='$insert_id'");
    		$DB->close_db();
			$message = "{$mklib->lang['dw_fexists']}";
			$mklib->error_page($message);
			exit;
		}
		if (is_file ($MK_PATH."mkportal/modules/downloads/file/$file_name")) {
    		$DB->query("DELETE FROM mkp_download WHERE id='$insert_id'");
    		$DB->close_db();
			$message = "{$mklib->lang['dw_fexists']}";
			$mklib->error_page($message);
			exit;
		}
        @move_uploaded_file("$file", $real_file);

		if (!is_file ($real_file)) {
    		$DB->query("DELETE FROM mkp_download WHERE id='$insert_id'");
    		$DB->close_db();
			$message = "{$mklib->lang['dw_chperms']}";
			$mklib->error_page($message);
			exit;
		}

		if ($approval == "1") {
			$mailsubj = $mklib->lang['01mail'].$mklib->lang['download'];
			$mailmess = $mklib->lang['02mail'].$mklib->lang['module'].$mklib->lang['download']."\n".$mklib->lang['sender'].$autore."\n\n\n".$mklib->lang['from']." ".$mklib->sitename;
			$mklib_board->admin_mail($mailsubj, $mailmess);
		}
		if ($approval == "2" && !$mkportals->member['g_access_cp']) {
			$mailsubj = $mklib->lang['01mail'].$mklib->lang['download'];
			$mailmess = $mklib->lang['03mail'].$mklib->lang['module'].$mklib->lang['download']."\n".$mklib->lang['sender'].$autore."\n\n\n".$mklib->lang['from']." ".$mklib->sitename;
			$mklib_board->admin_mail($mailsubj, $mailmess);
			$mklib->message_page ($mklib->lang['notify_adv']);
			exit;
		}
		if ($approval == "3" && !$mkportals->member['g_access_cp']) {
			$mklib->message_page ($mklib->lang['notify_adv']);
			exit;
		}
		$this->update_total();
  		$DB->close_db();
	 	Header("Location: /index.php?ind=downloads");
		exit;
  }
  function update_file() {
    	global $mkportals, $DB,  $_FILES, $mklib, $mklib_board;
		$iden= intval($mkportals->input['iden']);
		$insert_id = $iden;
		$idcategoria = intval($mkportals->input['evento']);
		$titolo = $mkportals->input['titolo'];
		$descrizione = $mkportals->input['descrizione'];
		$screen1 = $mkportals->input['screen1'];
		$screen2 = $mkportals->input['screen2'];
		$demo = $mkportals->input['demo'];
		$query = $DB->query( "SELECT file, data, idauth, peso FROM mkp_download WHERE id = $iden");
		$row = $DB->fetch_row($query);
		if(!$row) {
       		$message = "{$mklib->lang['error_404']}";
       		$mklib->error_page($message);
       		exit;
		}
		$file = $row['file'];
		$oldfile = $file;
		$data = $row['data'];
		$idauth = $row['idauth'];
		$peso = $row['peso'];

		if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_mod_download'] && (!$mklib->member['g_send_download'] || $mkportals->member['id'] != $idauth || $idauth == 0 )) {
			$message = "{$mklib->lang['dw_nodel']}";
			$mklib->error_page($message);
			exit;
		}

		if (!$idcategoria || !$titolo || !$descrizione) {
            		$message = "{$mklib->lang['dw_reqtcd']}";
            		$mklib->error_page($message);
            		exit;
        	}

        	if ($mklib->config['upload_file_max'] > 0 && $_FILES['FILE_UPLOAD']['size'] > ($mklib->config['upload_file_max']*1024)) {
            		$message = "{$mklib->lang['dw_toobig']}";
            		$mklib->error_page($message);
            		exit;
        	} 

		if (!empty($_FILES['FILE_UPLOAD']['tmp_name']) && $_FILES['FILE_UPLOAD']['name'] && $_FILES['FILE_UPLOAD']['name'] != 'none' && $_FILES['FILE_UPLOAD']['size']>0 ) {
//			$FILE_UPLOAD = $mkportals->input['FILE_UPLOAD'];
			$file =  $_FILES['FILE_UPLOAD']['tmp_name'];
			$file_name =  $_FILES['FILE_UPLOAD']['name'];
			$file_type =  $_FILES['FILE_UPLOAD']['type'];
			$peso =  $_FILES['FILE_UPLOAD']['size'];
			$data = time();
			$file_ext = preg_replace("`.*\.(.*)`", "\\1", $file_name);
			if (!$mklib->check_attach($file_type, $file_ext))  {
				$message = $file_type;
				$message .= " - {$mklib->lang['error_filetype']}";
				$mklib->error_page($message);
				exit;
			}

			//Replace illegal sub-extensions
			/*
			$com_types = array('com', 'exe', 'bat', 'scr', 'pif', 'asp', 'cgi', 'pl', 'php');
			foreach ($com_types AS $bad) {
				$file_name = str_replace(".$bad", "_$bad", $file_name);
			}	
            */
			
			//Added by Kimi in C1.2.2 but this is by Mark
			//Replace illegal sub-extensions
			$file_name = preg_replace("/\.(com|exe|bat|scr|pif|asp|cgi|pl|php)/i", "_$1", $file_name);

			//Replace illegal characters
			$file_name = preg_replace('/[^\w.-]/', '_', $file_name);

			if (is_file ($MK_PATH."mkportal/modules/downloads/file/$oldfile")) {
				@unlink($MK_PATH."mkportal/modules/downloads/file/$oldfile");
			}
			$oldfile = $MK_PATH."mkportal/modules/downloads/file/mk_".$insert_id."_".$oldfile;
        	$oldfile = preg_replace("`(.*)\..*`", "\\1", $oldfile);
        	$oldfile .= ".mk";
			if (is_file ($oldfile)) {
    			@unlink($oldfile);
			}
			$real_file = $MK_PATH."mkportal/modules/downloads/file/mk_".$insert_id."_".$file_name;
        	$real_file = preg_replace("`(.*)\..*`", "\\1", $real_file);
        	$real_file .= ".mk";
        	@move_uploaded_file("$file", $real_file);
			if (!is_file ($real_file)) {
    			$DB->query("DELETE FROM mkp_download WHERE id='$insert_id'");
    			$DB->close_db();
				$message = "{$mklib->lang['dw_chperms']}";
				$mklib->error_page($message);
				exit;
			}
			$file = $file_name;
		}

		$DB->query("UPDATE mkp_download SET idcategoria ='$idcategoria', name ='$titolo', description ='$descrizione', file ='$file', data='$data', screen1='$screen1', screen2='$screen2', demo='$demo', peso='$peso' where id = '$iden'");
		$DB->close_db();
		Header("Location: /index.php?ind=downloads&op=entry_view&iden=$iden");
		exit;
  		}
	function del_file() {
    	global $mkportals, $DB, $mklib, $mklib_board;

		$iden= intval($mkportals->input['iden']);
		$query = $DB->query( "SELECT file, autore, idauth FROM mkp_download WHERE id = $iden");
		$row = $DB->fetch_row($query);
		if(!$row) {
       		$message = "{$mklib->lang['error_404']}";
       		$mklib->error_page($message);
       		exit;
		}
		$file = $row['file'];
		$autore = $row['autore'];
		$idauth = $row['idauth'];
		if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_mod_download'] && (!$mklib->member['g_send_download'] || $mkportals->member['id'] != $idauth || $idauth == 0 )) {
			$message = "{$mklib->lang['dw_nodel']}";
			$mklib->error_page($message);
			exit;
		}
		@unlink("mkportal/modules/downloads/file/$file");
        $real_file = "mkportal/modules/downloads/file/mk_".$iden."_".$file;
        $real_file = preg_replace("`(.*)\..*`", "\\1", $real_file);
        $real_file .= ".mk";
		@unlink($real_file);
		$DB->query("DELETE FROM mkp_download WHERE id = $iden");
		$DB->query("DELETE FROM mkp_download_comments WHERE identry = $iden");
		$this->update_total();
		$DB->close_db();
	 	Header("Location: /index.php?ind=downloads");
		exit;
  		}

  function submit_comment() {
		global $mkportals, $mklib, $Skin, $DB, $mklib_board;

		if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_send_comments']) {
			$message = "{$mklib->lang['dw_nosendcom']}";
			$mklib->error_page($message);
			exit;
		}

		$ide= intval($mkportals->input['ide']);
		$query = $DB->query( "SELECT id, idcategoria, name FROM mkp_download WHERE id = '$ide' AND validate = '1'");
		$row = $DB->fetch_row($query);
		if(!$row) {
            		$message = "{$mklib->lang['error_404']}";
            		$mklib->error_page($message);
            		exit;
		}
		$t_id = $row['id'];
		$t_t = $row['name'];
		$t_ev1 = $row['idcategoria'];
		$t_ev2 = $this->retrieve_event($t_ev1);
		$navbar = "<a href=\"/index.php?ind=downloads\">{$mklib->lang['dw_ptitle2']}</a>{$mklib->lang['bc_sep']}<a href=\"/index.php?ind=downloads&amp;op=section_view&amp;idev=$t_ev1\">$t_ev2</a>{$mklib->lang['bc_sep']}<a href=\"/index.php?ind=downloads&amp;op=entry_view&amp;iden=$t_id\">$t_t</a>{$mklib->lang['bc_sep']}<a href=\"#\">{$mklib->lang['dw_commfile']}</a>";
		$content = "		
		<tr>
		  <td>
		
		    <script type=\"text/javascript\">
		    function emo_pop()
		    {
			  window.open('{$mkportals->base_url}act=legends&amp;CODE=emoticons&amp;s={$mkportals->session_id}','Legends','width=250,height=500,resizable=yes,scrollbars=yes');
		    }
		    </script>
		      
		    <form action=\"/index.php?ind=downloads&amp;op=add_comment&amp;ide={$ide}\" name=\"editor\" method=\"post\" >
		    <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"8\">        
		      <tr>
			<td rowspan=\"3\" align=\"center\" height=\"100%\">
			  <iframe src=\"/index.php?ind=downloads&amp;op=show_emoticons\" frameborder=\"0\"  width=\"200\" align=\"middle\" height=\"200\" scrolling=\"auto\"></iframe>
			</td>
			<td>{$mklib->lang['dw_writecomm']}</td>
		      </tr>
		      <tr>
			<td width=\"70%\" align=\"left\"><textarea cols=\"10\" style=\"width:95%\" rows=\"4\" name=\"ta\"></textarea></td>
		      </tr>
		      <tr>
			<td>
			<input type=\"submit\" name=\"submit\" value=\"{$mklib->lang['dw_sendcomm']}\" class=\"mkbutton\" accesskey=\"s\" /><br />
			</td>
		      </tr>		
		    </table>
		    </form>
		    
		  </td>
		</tr>
	";
	$maintit = "<a href=\"/index.php?ind=downloads&amp;op=entry_view&amp;iden=$t_id\">{$mklib->lang['dw_file']}: $t_t</a>";
	$stat = $this->retrieve_stat();
	$toolbar = "";
	$utonline = $mklib_board->get_active_users("downloads");
	$output  = $this->tpl->downloads_show($navbar, $maintit, $content, $submit, $stat, $toolbar, $show_pages, $utonline);
	$blocks = $Skin->view_block("{$mklib->lang['dw_insertcomm']}", $output);
	$mklib->printpage("1", "1", $mklib->sitename.$mklib->lang['tt_sep'].$mklib->lang['dw_pagetitle'].$mklib->lang['tt_sep'].$t_t.$mklib->lang['tt_sep'].$mklib->lang['dw_commfile'], $blocks);
	}

	function submit_rate() {
    	global $mkportals, $mklib, $Skin, $DB, $mklib_board;
		$ide= intval($mkportals->input['ide']);
		$iduser = $mkportals->member['id'];
		$ipuser = $_SERVER['REMOTE_ADDR'];
		$module = "downloads";

		if (!$iduser || $iduser == 0) { //Guests: check IP address
			$query = $DB->query( "SELECT id FROM mkp_votes WHERE module = '$module' AND id_entry = '$ide' AND ip = '$ipuser'");
			$check = $DB->get_num_rows($query);

		} else { //Registered Members: check userid
			$query = $DB->query( "SELECT id FROM mkp_votes WHERE module = '$module' AND id_entry = '$ide' AND id_member = '$iduser'");
			$check = $DB->get_num_rows($query);
		}
		if($check) {
			$message = "{$mklib->lang['dw_justvote']}";
			$mklib->error_page($message);
			exit;
		}

		$query = $DB->query( "SELECT id, idcategoria, name FROM mkp_download WHERE id = '$ide' AND validate = '1'");
		$row = $DB->fetch_row($query);
		if(!$row) {
            		$message = "{$mklib->lang['error_404']}";
            		$mklib->error_page($message);
            		exit;
		}
		$t_id = $row['id'];
		$t_t = $row['name'];
		$t_ev1 = $row['idcategoria'];
		$t_ev2 = $this->retrieve_event($t_ev1);
		$navbar = "<a href=\"/index.php?ind=downloads\">{$mklib->lang['dw_ptitle2']}</a>{$mklib->lang['bc_sep']}<a href=\"/index.php?ind=downloads&amp;op=section_view&amp;idev=$t_ev1\">$t_ev2</a>{$mklib->lang['bc_sep']}<a href=\"/index.php?ind=downloads&amp;op=entry_view&amp;iden=$t_id\">$t_t</a>{$mklib->lang['bc_sep']}<a href=\"#\">{$mklib->lang['dw_vote']}</a>";
	   $content .= "
		<tr>
		  <td class=\"modulecell\">
  
		    <form action=\"/index.php?ind=downloads&amp;op=add_rate&amp;ide={$ide}\" method=\"post\" id=\"ratea\" name=\"ratea\">
		    <table width=\"100%\">
		      <tr>
			<td class=\"modulex\" width=\"50%\" valign=\"top\">{$mklib->lang['dw_maxvote']}
			</td>
			<td class=\"modulex\" width=\"*\">
			  <input class=\"mkradio\" type=\"radio\" tabindex=\"3\" name=\"rating\" value=\"1\" checked=\"checked\" />1
			  <input class=\"mkradio\" type=\"radio\" tabindex=\"4\" name=\"rating\" value=\"2\" />2
			  <input class=\"mkradio\" type=\"radio\" tabindex=\"5\" name=\"rating\" value=\"3\" />3
			  <input class=\"mkradio\" type=\"radio\" tabindex=\"6\" name=\"rating\" value=\"4\" />4
			  <input class=\"mkradio\" type=\"radio\" tabindex=\"7\" name=\"rating\" value=\"5\" />5
			</td>
		      </tr>
		      <tr>
			<td class=\"modulex\" colspan=\"2\">
			  <input type=\"submit\" name=\"ok\" value=\"{$mklib->lang['dw_sendvote']}\" class=\"mkbutton\" />
			</td>
		      </tr>		
		    </table>
		    </form>
    
		  </td>
		</tr>
	";
	$maintit = "<a href=\"/index.php?ind=downloads&amp;op=entry_view&amp;iden=$t_id\">{$mklib->lang['dw_file']}: $t_t</a>";
	$stat = $this->retrieve_stat();
	$toolbar = "";
	$utonline = $mklib_board->get_active_users("downloads");
	$output  = $this->tpl->downloads_show($navbar, $maintit, $content, $submit, $stat, $toolbar, $show_pages, $utonline);
	$blocks = $Skin->view_block("{$mklib->lang['dw_sendvote']}", $output);
	$mklib->printpage("1", "1", $mklib->sitename.$mklib->lang['tt_sep'].$mklib->lang['dw_pagetitle'].$mklib->lang['tt_sep'].$t_t.$mklib->lang['tt_sep'].$mklib->lang['dw_vote'], $blocks);
		}

	function add_comment() {
    	global $mkportals, $DB, $mklib, $mklib_board;


		if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_send_comments']) {
			$message = "{$mklib->lang['dw_nosendcom']}";
			$mklib->error_page($message);
			exit;
		}

		$ide= intval($mkportals->input['ide']);
		$testo = $mkportals->input['ta'];
		$autore = $mkportals->member['name'];
        	$cdata = time();
        	if ($mklib->config['antibot_chek'] && !$mkportals->member['id']){
$captcha_code = $mkportals->input['check'];
$captcha_check = $mklib->antibot_check($captcha_code);
}
		if (!$testo) {
			$message = "{$mklib->lang['dw_reqtext']}";
			$mklib->error_page($message);
			exit;
		}

		$testo = $mklib_board->decode_smilies($testo);

		//$testo = addslashes($testo);
		$query="INSERT INTO mkp_download_comments(identry, autore, testo, data)VALUES('$ide', '$autore', '$testo', '$cdata')";
		$DB->query($query);
		$DB->close_db();
	 	Header("Location: /index.php?ind=downloads&op=entry_view&iden=$ide");
		exit;
  	}
	function del_comment() {
    	global $mkportals, $DB, $mklib, $mklib_board;

		if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_mod_download']) {
			$message = "{$mklib->lang['dw_nodelcomm']}";
			$mklib->error_page($message);
			exit;
		}

		$ide= intval($mkportals->input['iden']);
		$idcomm= intval($mkportals->input['idcomm']);
		$DB->query("DELETE FROM mkp_download_comments WHERE id = $idcomm");
		$DB->close_db();
	 	Header("Location: /index.php?ind=downloads&op=entry_view&iden=$ide");
		exit;
  	}



  		function add_rate() {
    		global $mkportals, $DB, $mklib, $mklib_board;
		$ide= intval($mkportals->input['ide']);
		$rating = $mkportals->input['rating'];
		$iduser = $mkportals->member['id'];
		$ipuser = $_SERVER['REMOTE_ADDR'];
		$module = "downloads";

		if (!$iduser || $iduser == 0) { //Guests: check IP address
			$query = $DB->query( "SELECT id FROM mkp_votes WHERE module = '$module' AND id_entry = '$ide' AND ip = '$ipuser'");
			$check = $DB->get_num_rows($query);

		} else { //Registered Members: check userid
			$query = $DB->query( "SELECT id FROM mkp_votes WHERE module = '$module' AND id_entry = '$ide' AND id_member = '$iduser'");
			$check = $DB->get_num_rows($query);
		}
		if($check) {
			$message = "{$mklib->lang['dw_justvote']}";
			$mklib->error_page($message);
			exit;
		}

		//Validate rating value
		if ($rating < 1 || $rating > 5) {
    			$message = $mklib->lang['dw_badvote'];
    			$mklib->error_page($message);
    			exit;
		}

		$query="INSERT INTO mkp_votes(id_entry, module, id_member, ip)VALUES('$ide', '$module', '$iduser', '$ipuser')";
		$DB->query($query);

		$query = $DB->query( "SELECT rate, trate FROM mkp_download WHERE id = '$ide' AND validate = '1'");
		$row = $DB->fetch_row($query);
		$rate = $row['rate'];
		$trate = $row['trate'];
		$votes = ($trate +1);
		$rate = round ((($trate*$rate)+$rating)/($votes), 2);

		$DB->query("UPDATE mkp_download SET rate ='$rate', trate ='$votes' WHERE id = '$ide'");
		$DB->close_db();
	 	Header("Location: /index.php?ind=downloads&op=entry_view&iden=$ide");
		exit;
  		}
		function download_file() {
			global $mkportals, $DB, $mklib, $mklib_board;

			if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_download_files']) {
           			$message = $mklib->lang['dw_dwfileno'];
           			$mklib->error_page($message);
           			exit;
			}

			$tempva = ob_get_contents();
       			ob_end_clean();
			ob_start();
			
//			$file= $mkportals->input['file'];
			$ide= intval($mkportals->input['ide']);
			$query = $DB->query( "SELECT file, downloads FROM mkp_download WHERE id = '$ide' AND validate = '1'");
			$row = $DB->fetch_row($query);
			if(!$row) {
           		$message = "{$mklib->lang['error_404']}";
           		$mklib->error_page($message);
           		exit;
			}
			$downloads = $row['downloads'];
			$file = $row['file'];
			++$downloads;
			$DB->query("UPDATE mkp_download SET downloads ='$downloads' WHERE id = '$ide'");
			$DB->close_db();
			$real_file = "mkportal/modules/downloads/file/mk_".$ide."_".$file;
            $real_file = preg_replace("`(.*)\..*`", "\\1", $real_file);
            $real_file .= ".mk";
           
	    //Needed for ftp uploads via Portal CP  
	    if (is_file("mkportal/modules/downloads/file/".$file)) {
                @rename("mkportal/modules/downloads/file/".$file, $real_file);
            }	    
	    
		@session_write_close();
   		@ob_end_clean();
   		if (!is_file($real_file) || connection_status()!=0) {
	   		die("Download failed");
		}		  
		@set_time_limit(0);
   		$name=basename($file);
   		if (strstr($_SERVER['HTTP_USER_AGENT'], "MSIE")) {
			   $name = preg_replace('/\./', '%2e', $name, substr_count($name, '.') - 1);
		}

   		header("Cache-Control: ");
   		header("Pragma: ");
   		header("Content-Type: application/octet-stream");
   		header("Content-Length: " .(string)(filesize($real_file)) );
   		header('Content-Disposition: attachment; filename="'.$name.'"');
   		header("Content-Transfer-Encoding: binary\n");
   		if($h = fopen($real_file, 'rb')){
	   		while( (!feof($h)) && (connection_status()==0) ){
		   		print(fread($h, 1024*8));
		   		flush();
	   		}
	   	fclose($h);
   		}
	   
		exit();
		}
		function search() {
		global $mkportals, $DB, $mklib, $Skin, $mklib_board;
		$maintit = "{$mklib->lang['dw_searchf']}";
		$cselect.= "<option value='1'>{$mklib->lang['dw_title']}</option>\n";
		$cselect.= "<option value='2'>{$mklib->lang['dw_description']}</option>\n";
		$content .= "
		<tr>
		  <td>
		  
		    <form action=\"/index.php?ind=downloads&amp;op=result_search\" name=\"search\" method=\"post\">
		    <table width=\"100%\" border=\"0\">
		      <tr>
			<td>{$mklib->lang['dw_searchin']}:</td>
			<td>
			  <select class=\"bgselect\" name=\"campo\" size=\"1\">
			  {$cselect}
			  </select>
			</td>
		      </tr>
		      <tr>
			<td width=\"20%\">{$mklib->lang['dw_searchtext']}</td>
			<td width=\"80%\"><input type=\"text\" name=\"testo\" size=\"52\" class=\"bgselect\" /></td>
		      </tr>
		      <tr>
			<td colspan=\"2\"><input type=\"submit\" value=\"{$mklib->lang['dw_searchstart']}\" class=\"mkbutton\" /></td>
		      </tr>
		    </table>
		    </form>
		    
		</td>
	      </tr>
		";
		$navbar = "<a href=\"/index.php?ind=downloads\">{$mklib->lang['dw_ptitle2']}</a>{$mklib->lang['bc_sep']}<a href=\"#\">{$mklib->lang['dw_searchf']}</a>";
		$stat = $this->retrieve_stat();
		$toolbar = "";
		$utonline = $mklib_board->get_active_users("downloads");
		$output  = $this->tpl->downloads_show($navbar, $maintit, $content, $submit, $stat, $toolbar, $show_pages, $utonline);
		$blocks = $Skin->view_block("{$mklib->lang['dw_pagetitle']}", $output);
		$mklib->printpage("1", "1", $mklib->sitename.$mklib->lang['tt_sep'].$mklib->lang['dw_pagetitle'].$mklib->lang['tt_sep'].$mklib->lang['dw_searchf'], $blocks);
	}

	function result_search() {
		global $mkportals, $DB, $mklib, $Skin, $mklib_board;
		//$campo = $mkportals->input['campo'];  //deprecated
		$testo = $mkportals->input['testo'];
		$campo = "name";
		if (intval($mkportals->input['campo']) == 2) {
			$campo = "description";
		}
		if (!$testo) {
			$message = "{$mklib->lang['dw_reqstring']}";
			$mklib->error_page($message);
			exit;
		}
		$navbar = "<a href=\"/index.php?ind=downloads\">{$mklib->lang['dw_ptitle2']}</a>{$mklib->lang['bc_sep']}<a href=\"#\">{$mklib->lang['dw_searchresult']}</a>";
		$maintit = "{$mklib->lang['dw_searchresult']}";
		$content = $this->tpl->row_main_entries();
		$query = $DB->query( "SELECT id, name, downloads, click, data, trate FROM mkp_download WHERE $campo LIKE '%$testo%' AND validate = '1'");
		while( $row = $DB->fetch_row($query) ) {
			$iden = $row['id'];
			$name = $row['name'];
			$trate = $row['trate'];
			$downloads = $row['downloads'];
			$click = $row['click'];
			$data = $mklib->create_date($row['data'], "short");
			$name ="<a href=\"/index.php?ind=downloads&amp;op=entry_view&amp;iden=$iden\">$name</a>";
			$content .= $this->tpl->row_main_entries_content($name, $trate, $downloads, $click, $data);
		}
		if (!$name) {
			$content = "<td align=\"center\" width=\"100%\" class=\"modulecell\"><br />{$mklib->lang['dw_searchnot']}<br /><br /><br /></td>";
		}
		$submit = "";
		$stat = $this->retrieve_stat();
		$toolbar = "";
		$utonline = $mklib_board->get_active_users("downloads");
		$output  = $this->tpl->downloads_show($navbar, $maintit, $content, $submit, $stat, $toolbar, $show_pages, $utonline);
		$blocks = $Skin->view_block("{$mklib->lang['dw_pagetitle']}", $output);
		$mklib->printpage("1", "1", $mklib->sitename.$mklib->lang['tt_sep'].$mklib->lang['dw_pagetitle'].$mklib->lang['tt_sep'].$mklib->lang['dw_searchresult'], $blocks);
	}

	
		function row_select_event($jump="") {
			global $mklib;
			
			if($jump) {
				$cselect = "<option value=\"0\">{$mklib->lang['dw_jumpcat']}</option>\n";
			}
			//$query = $DB->query( "SELECT id, evento, father FROM mkp_download_sections ORDER BY `id`");
			//while( $row = $DB->fetch_row($query) ) {
			$listall = array();
			$children = array();
			if (!$this->chache_sections) {
				return FALSE;
			}
			foreach ($this->chache_sections as $row) {
				if ($row['father']) {
					$children[ $row['father'] ][] = $row;
				}
			}
			
			foreach ($this->chache_sections as $row) {
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
				   			reset ($this->chache_sections);
				   			foreach ($this->chache_sections as $row2) {
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
		
		function retrieve_event($idevento) {
			foreach ($this->chache_sections as $r) {
				if($r['id'] == $idevento) {
					break;
				}
			}
			return $r['evento'];
		}
		function retrieve_father($idevento) {
			foreach ($this->chache_sections as $row) {
				if($row['id'] == $idevento) {
					break;
				}
			}
			if($row['father']) {
				$evento = $this->retrieve_event($row['father']);
			}
			return array ($row['father'], $evento);
		}
		function retrieve_stat() {
			global $mkportals, $DB, $mklib, $mklib_board;
			$query = $DB->query( "SELECT id, name FROM mkp_download WHERE validate = '1' ORDER BY `downloads` DESC");
			$row = $DB->fetch_row($query);
			$id = $row['id'];
			$name = $row['name'];
			$scaricato = "<a href=\"/index.php?ind=downloads&amp;op=entry_view&amp;iden=$id\">$name</a>";
			$count = count($this->chache_download);
			$query = $DB->query( "SELECT id, name FROM mkp_download WHERE validate = '1' ORDER BY `click` DESC LIMIT 1");
			$row = $DB->fetch_row($query);
			$id = $row['id'];
			$name = $row['name'];
			$visitato = "<a href=\"/index.php?ind=downloads&amp;op=entry_view&amp;iden=$id\">$name</a>";
			$query = $DB->query( "SELECT id, name FROM mkp_download WHERE validate = '1' ORDER BY `trate` DESC LIMIT 1");
			$row = $DB->fetch_row($query);
			$id = $row['id'];
			$name = $row['name'];
			$votato = "<a href=\"/index.php?ind=downloads&amp;op=entry_view&amp;iden=$id\">$name</a>";
			$output = "{$mklib->lang['dw_have']} $count {$mklib->lang['dw_totfile']}<br />{$mklib->lang['dw_mostd']} $scaricato<br />{$mklib->lang['dw_mosts']} $visitato<br />{$mklib->lang['dw_mostv']} $votato";
			return $output;
		}

	
	
	function show_emoticons() {
		global $mklib_board;
		$mklib_board->show_emoticons();
 	}
	function update_total() {
		global $DB;
		$query = $DB->query( "SELECT id FROM mkp_download WHERE validate = '1'");
		$count = $DB->get_num_rows($query);
		$DB->query("UPDATE mkp_stat SET valore ='$count' WHERE chiave = 'tot_download'");
	
	}
	function total_son($idevento) {
		$count = 0;
		$countfile = 0;
		$lastfile = "";
		$father = array();
		$father[] = $idevento;
		foreach ($this->chache_sections as $r) {
			if (in_array($r['father'], $father)) {
				$father[] = $r['id'];
   				++$count;
   			}
		
		}
		foreach ($this->chache_download as $r) {
			if (in_array($r['idcategoria'], $father)) {
				$lastfile = "<a href=\"/index.php?ind=downloads&amp;op=entry_view&amp;iden={$r['id']}\">".$r['name']."</a>";
   				++$countfile;
   			}
		
		}	
		
		return array($count, $countfile, $lastfile);
 	}
	
}
?>
