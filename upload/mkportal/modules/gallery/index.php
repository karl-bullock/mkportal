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

$idx = new mk_gallery;
class mk_gallery {

	var $tpl    = "";
	var $cache_sections = array();
	var $cache_files = array();
	
	function mk_gallery() {

		global $mkportals, $mklib, $Skin, $DB, $mklib_board;		
		$mklib->load_lang("lang_gallery.php");

		if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_access_gallery'] && $mkportals->input['op'] != "postcard_view") {
			$message = "{$mklib->lang['ga_noenterg']}.";
			$mklib->error_page($message);
			exit;
		}
		if ($mklib->config['mod_gallery']) {
		$message = "{$mklib->lang['ga_mnoactive']}";
			$mklib->error_page($message);
			exit;
		}
		
		//load cache
		$DB->query( "SELECT * FROM mkp_gallery_events ORDER BY `id`");
		while( $row = $DB->fetch_row() ) {
            		$this->cache_sections[] = $row;
       	 	}
		$DB->query( "SELECT * FROM mkp_gallery WHERE validate = '1' ORDER BY `id`");
		while( $row = $DB->fetch_row() ) {
            		$this->cache_files[] = $row;
       	 	}
		
		//location
		$mklib_board->store_location("gallery");
		require "mkportal/modules/gallery/tpl_gallery.php";
		$this->tpl = new tpl_gallery();

    		switch($mkportals->input['op']) {
    			case 'section_view':
    				$this->section_view();
    			break;
			case 'foto_show':
    				$this->foto_show();
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
			case 'submit_rate':
    				$this->submit_rate();
    			break;
			case 'add_rate':
    				$this->add_rate();
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
			case 'show_emoticons':
    				$this->show_emoticons();
    			break;
			case 'submit_postcard':
    				$this->submit_postcard();
    			break;
			case 'preview_postcard':
    				$this->preview_postcard();
    			break;
			case 'send_postcard':
    				$this->send_postcard();
    			break;
			case 'postcard_view':
    				$this->postcard_view();
    			break;
			case 'slide_start':
    				$this->slide_start();
    			break;
			case 'slide_update':
    				$this->slide_update();
    			break;
			case 'search':
    				$this->search();
    			break;
			case 'result_search':
    				$this->result_search();
    			break;
    			default:
    				$this->album_show();
    			break;
    		}
	}

	function album_show() {
    global $mkportals, $DB, $mklib, $Skin, $mklib_board;

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
	$query = $DB->query( "SELECT id FROM mkp_gallery_events WHERE father = '0'");
	$countpage = $DB->get_num_rows($query);
	$per_page = $mklib->config['gallery_sec_page'];
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
						    BASE_URL    => '/index.php?ind=gallery&amp;order='.intval($mkportals->input['order'])
										  )
	);
	
	$checkempty = $this->row_categories(0, $order, $start, $per_page);
	if(!$checkempty) {
		$content .= "{$mklib->lang['ga_emptyga']}";
	} else {
		$content .= $checkempty;
	}


// Link Navigation bar
	$navbar = "<a href=\"/index.php?ind=gallery\">{$mklib->lang['ga_mkpgal']}</a>";
// Link Navigation bar
	$maintit = "{$mklib->lang['ga_sections']}";
	$submit = "<a href=\"/index.php?ind=gallery&amp;op=search\">[ {$mklib->lang['ga_search']} ]</a> <a href=\"/index.php?ind=gallery&amp;op=slide_start\">[ {$mklib->lang['ga_slide']} ]</a> <a href=\"/index.php?ind=gallery&amp;op=submit_file\">[ {$mklib->lang['ga_insertimg']} ]</a> ";
	if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_send_gallery']) {
			$submit ="<a href=\"/index.php?ind=gallery&amp;op=search\">[ {$mklib->lang['ga_search']} ]</a> <a href=\"/index.php?ind=gallery&amp;op=slide_start\">[ {$mklib->lang['ga_slide']} ]</a>";
	}
	$stat = $this->retrieve_stat();
	$utonline = $mklib_board->get_active_users("gallery");
// Option evento
	$jump1 = "<option value='0'>{$mklib->lang['ga_jumpcat']}</option>\n";
	$jump1 .= $this->row_select_event();
// Option evento
	$jump = "
	<select name=\"jumpsection\" size=\"1\" onchange=\"selChd(this)\" class=\"bgselect\">
       		$jump1
	</select>
	 ";
	 $sort = "
	<select name=\"order\" size=\"1\" onchange=\"selChoc(this)\"  class=\"bgselect\">
	  <option value=\"0\">{$mklib->lang['ga_orderby']}</option>\n
	  <option value=\"1\">{$mklib->lang['ga_position']}</option>\n
	  <option value=\"2\">{$mklib->lang['ga_namecat']}</option>\n
	  <option value=\"3\">{$mklib->lang['ga_datecre']}</option>\n
      	</select>
	 ";
	$toolbar = $this->tpl->row_toolbar($jump, $sort);
	$output  = $this->tpl->gallery_show($navbar, $maintit, $content, $submit, $stat, $toolbar, $show_pages, $utonline);
	$blocks = $Skin->view_block("{$mklib->lang['ga_imggal']}", $output);
	$mklib->printpage("1", "1", $mklib->sitename.$mklib->lang['tt_sep'].$mklib->lang['ga_imggal'], $blocks);
	}

	function section_view() {
		global $mkportals, $DB, $mklib, $Skin, $mklib_board;
		$idev = intval($mkportals->input['idev']);
// Link Navigation bar
		$navbar = $this->navbar($idev);
// Link Navigation bar
		
		$content = $this->row_categories($idev, $order, $start, $per_page);

		switch(intval($mkportals->input['order'])) {
		case '1':
			$order = "ORDER BY `titolo`";
    	break;
		default:
    		$order = "ORDER BY `id` DESC";
    	break;
		}
		//pagination
		$query = $DB->query( "SELECT id FROM mkp_gallery WHERE evento = '$idev' and validate = '1'");
		$countpage = $DB->get_num_rows($query);
		$per_page = $mklib->config['gallery_file_page'];
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
						    BASE_URL    => '/index.php?ind=gallery&amp;op=section_view&amp;idev='.$idev.'&amp;order='.intval($mkportals->input['order'])
										  )
		);
		$query = $DB->query( "SELECT id, evento, titolo, descrizione, file, peso FROM mkp_gallery WHERE evento = '$idev' and validate = '1' $order LIMIT $start, $per_page");
		$content.= "<tr>
		<td colspan=\"6\" bgcolor=\"#ffffff\">
		<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">
		<tr>";
		$index = 0;
		while( $row = $DB->fetch_row($query) ) {
			$idfoto = $row['id'];
			//$evento = $row['evento']; //deprectaed
			$titolo = $row['titolo'];
			$descrizione = $row['descrizione'];
			$file = $row['file'];
			$thumb = "t_$file";
			//$peso = "(".round(($row['peso']/1024),2)." K)";
			$content.= "<td align=\"center\" valign=\"bottom\">";
			$content.= "<table border=\"0\" width=\"100\" cellspacing=\"0\" cellpadding=\"0\">";
  			$content.= "<tr>";
    			$content.= "<td width=\"1%\" style=\"font-size: 4px;\"><img src=\"$mklib->images/a_sx_a.gif\" style=\"vertical-align: bottom\" alt=\"\" /></td>";
    			$content.= "<td width=\"98%\" style=\"background-image: url('$mklib->images/a_sf_s.gif'); font-size: 4px;\"><img src=\"$mklib->images/a_sf_s.gif\" height=\"8\" style=\"vertical-align: bottom\" alt=\"\" /></td>";
    			$content.= "<td width=\"1%\" style=\"font-size: 4px;\"><img src=\"$mklib->images/a_dx_a.gif\" style=\"vertical-align: bottom\" alt=\"\" /></td>";
  			$content.= "</tr><tr>";
    			$content.= "<td width=\"1%\" style=\"background-image: url('$mklib->images/a_sx_s.gif');\"><img src=\"$mklib->images/a_sx_s.gif\" width=\"11\" height=\"15\" alt=\"\" /></td>";
    			$content.= "<td width=\"98%\" style=\"background-color: #ffffff;\" align=\"center\">";

			//Thumbnail & title
			if (!file_exists("/mkportal/modules/gallery/album/$thumb")) { //No thumbnail image

				//Classic thumbnails
				if ($mklib->config['classic_thumbs'] == 1) {
  					$thumb_mes = $mklib->ResizeImage($mklib->config['thumb_max_dimen'],"mkportal/modules/gallery/album/$file");
					$content.= "<a href=\"/index.php?ind=gallery&amp;op=foto_show&amp;ida=$idfoto\"><img src=\"/mkportal/modules/gallery/album/$file\" border=\"0\" width=\"$thumb_mes[0]\" height=\"$thumb_mes[1]\" alt=\"{$mklib->lang['ga_czoom']}\" /></a><br /><a href=\"/index.php?ind=gallery&amp;op=foto_show&amp;ida=$idfoto\" title=\"{$mklib->lang['ga_details']}\">$titolo</a><br /> ";
				//Ajax thumbnails					
				} else { 
					$thumb_mes = $mklib->ResizeImage($mklib->config['thumb_max_dimen'],"mkportal/modules/gallery/album/$file");
					$content.= "<img style=\"cursor: url($mklib->template/images/mkajax/zoomin.cur), pointer;\" src=\"/mkportal/modules/gallery/album/$file\" border=\"0\" width=\"$thumb_mes[0]\" height=\"$thumb_mes[1]\" alt=\"{$mklib->lang['ga_czoom']}\" onclick=\"ajax_showPop('/index.php?ind=ajax&act=showgal&idp=$idfoto', 1);return false\" /><div style=\"margin-top: 5px\"><a href=\"/index.php?ind=gallery&amp;op=foto_show&amp;ida=$idfoto\" title=\"{$mklib->lang['ga_details']}\">$titolo</a></div>";
				}				
			} else { //Thumbnail available
				if ($mklib->config['classic_thumbs'] == 1) {
					//Classic thumbnails
					$content.= "<a href=\"/index.php?ind=gallery&amp;op=foto_show&amp;ida=$idfoto\"><img src=\"/mkportal/modules/gallery/album/$thumb\" border=\"0\" alt=\"{$mklib->lang['ga_czoom']}\" /></a><br /><a href=\"/index.php?ind=gallery&amp;op=foto_show&amp;ida=$idfoto\" title=\"{$mklib->lang['ga_details']}\">$titolo</a><br /> ";
				} else {
					//Ajax thumbnails
					$content.= "<img style=\"cursor: url($mklib->template/images/mkajax/zoomin.cur), pointer;\" src=\"/mkportal/modules/gallery/album/$thumb\" border=\"0\" alt=\"{$mklib->lang['ga_czoom']}\" onclick=\"ajax_showPop('{$mklib->sitepath}/index.php?ind=ajax&act=showgal&idp=$idfoto', 1);return false\" /><div style=\"margin-top: 5px\"><a href=\"/index.php?ind=gallery&amp;op=foto_show&amp;ida=$idfoto\" title=\"{$mklib->lang['ga_details']}\">$titolo</a></div>";	
				}
			}
			// End

			$content.= "</td>";
   			$content.= "<td width=\"1%\" style=\"background-image: url('$mklib->images/a_dx_s.gif');\"><img src=\"$mklib->images/a_dx_s.gif\" width=\"11\" height=\"14\" alt=\"\" /></td>";
  			$content.= "</tr><tr>";
    		$content.= "<td width=\"1%\"><img src=\"$mklib->images/a_sx_g.gif\" height=\"22\" style=\"vertical-align: top\" alt=\"\" /></td>";
    		$content.= "<td width=\"98%\" style=\"background-image: url('$mklib->images/a_sf_g.gif');\"></td>";
    		$content.= "<td width=\"1%\" valign=\"top\"><img src=\"$mklib->images/a_dx_g.gif\" height=\"22\" style=\"vertical-align: top\" alt=\"\" /></td>";
  			$content.= "</tr></table>";
			$content.= "</td>";
			++ $index;
			if ($index == 3) {
				$index = 0;
				$content.= "</tr><tr><td>&nbsp;</td></tr>";
				$content.= "<tr>";
			}

		}
		$content.= "</tr></table>";

		$content.= "</td></tr>";

		$maintit = $row['evento'];
		$submit = "<a href=\"/index.php?ind=gallery&amp;op=search\">[ {$mklib->lang['ga_search']} ]</a>  <a href=\"/index.php?ind=gallery&amp;op=submit_file\">[ {$mklib->lang['ga_insertimg']} ]</a> ";
		if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_send_gallery']) {
			$submit ="<a href=\"/index.php?ind=gallery&amp;op=search\">[ {$mklib->lang['ga_search']} ]</a> &nbsp;";
		}
		$stat = $this->retrieve_stat();
// Option evento
		$jump1 = "<option value='0'>{$mklib->lang['ga_jumpcat']}</option>\n";
		$jump1 .= $this->row_select_event();
// Option evento
		$jump = "
		<select name=\"jumpsection\" size=\"1\" onchange=\"selChd(this)\"  class=\"bgselect\">
       		$jump1
      	</select>
	 	";
	 	$sort = "
		<select name=\"order\" size=\"1\" onchange=\"selChoe(this, '$idev')\"  class=\"bgselect\">
		 <option value=\"0\">{$mklib->lang['ga_orderby']}</option>\n
		 <option value=\"1\">{$mklib->lang['ga_nameimg']}</option>\n
		 <option value=\"2\">{$mklib->lang['ga_dateins']}</option>\n
      	</select>
		 ";
		$toolbar = $this->tpl->row_toolbar($jump, $sort);
		$utonline = $mklib_board->get_active_users("gallery");
		$output  = $this->tpl->gallery_show($navbar, $maintit, $content, $submit, $stat, $toolbar, $show_pages, $utonline);
		$blocks = $Skin->view_block("{$mklib->lang['ga_imggal']}", $output);
		$mklib->printpage("1", "1", $mklib->sitename.$mklib->lang['tt_sep'].$mklib->lang['ga_imggal'], $blocks);
		}

		function foto_show() {
		global $mkportals, $DB, $mklib, $Skin, $mklib_board;
		$id = intval($mkportals->input['ida']);

		$query = $DB->query( "SELECT evento, titolo, descrizione, file, click, rate, trate, autore, idauth, peso, data FROM mkp_gallery WHERE id = '$id' AND validate = '1'");
		$content.= "
			<tr>
			  <td bgcolor=\"#ffffff\"><br />
			    <table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">
			      <tr>
			      ";
		$row = $DB->fetch_row($query);
		if(!$row) {
            		$message = "{$mklib->lang['error_404']}";
            		$mklib->error_page($message);
            		exit;
		}

		$titolo = $row['titolo'];
		$descrizione = $row['descrizione'];
		$evento1 = $row['evento'];
		$file = $row['file'];
		$click = $row['click'];
		$rate = $row['rate'];
		$trate = $row['trate'];
		$autore = $row['autore'];
		$idauth = $row['idauth'];
		$peso = round(($row['peso']/1024),2)." Kb";
		if( $row['data']) {
			$cdata = $mklib->create_date($row['data'], "short");
		}
		$image_details = @getimagesize("/mkportal/modules/gallery/album/$file");
		$dimensioni = "$image_details[0] x $image_details[1]";
		++$click;
		$DB->query("UPDATE mkp_gallery SET click ='$click' WHERE id = '$id'");
		$width = round(($rate*100)/4) - 6;
	 	$width2 = $width - 4;
        //$id = $iden; 
   		$modname ='gallery';
        $rating = $mklib->pullRating($id, $modname, $rate, $trate);
		$imagesize_x = $image_details[0];
		if($imagesize_x > 600) {
			$size = $mklib->ResizeImage(600,"$mklib->sitepath/mkportal/modules/gallery/album/$file");
			$dims = "width=\"600\" height=\"$size[1]\"";
		}


		$content.= "<td align=\"center\" valign=\"bottom\">";
			$content.= "<table border=\"0\" width=\"100\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">";
  			$content.= "<tr>";
    			$content.= "<td width=\"1%\" style=\"font-size: 4px;\"><img src=\"$mklib->images/a_sx_a.gif\" style=\"vertical-align: bottom\" alt=\"\" /></td>";
    			$content.= "<td width=\"98%\" style=\"background-image: url('$mklib->images/a_sf_s.gif'); font-size: 4px;\"><img src=\"$mklib->images/a_sf_s.gif\" height=\"8\" style=\"vertical-align: bottom\" alt=\"\" /></td>";
    			$content.= "<td width=\"1%\" style=\"font-size: 4px;\"><img src=\"$mklib->images/a_dx_a.gif\" style=\"vertical-align: bottom\" alt=\"\" /></td>";
  			$content.= "</tr><tr>";
    			$content.= "<td width=\"1%\" style=\"background-image: url('$mklib->images/a_sx_s.gif');\"><img src=\"$mklib->images/a_sx_s.gif\" width=\"11\" height=\"15\" alt=\"\" /></td>";
    			$content.= "<td width=\"98%\" bgcolor=\"#ffffff\" align=\"center\">";
		//Thumbnail & title
		$content.= "<a href=\"/mkportal/modules/gallery/album/$file\" target=\"_blank\"><img src=\"/mkportal/modules/gallery/album/$file\" border=\"0\" $dims alt=\"zoom\" /></a>";

		$content.= "</td>";
   		$content.= "<td width=\"1%\" style=\"background-image: url('$mklib->images/a_dx_s.gif');\"><img src=\"$mklib->images/a_dx_s.gif\" width=\"11\" height=\"14\" alt=\"\" /></td>";
  		$content.= "</tr><tr>";
    		$content.= "<td width=\"1%\"><img src=\"$mklib->images/a_sx_g.gif\" height=\"22\" style=\"vertical-align: top\" alt=\"\" /></td>";
    		$content.= "<td width=\"98%\" style=\"background-image: url('$mklib->images/a_sf_g.gif');\"><img src=\"$mklib->images/a_sf_g.gif\" height=\"22\" style=\"vertical-align: top\" alt=\"\" /></td>";
    		$content.= "<td width=\"1%\" valign=\"top\"><img src=\"$mklib->images/a_dx_g.gif\" height=\"22\" style=\"vertical-align: top\" alt=\"\" /></td>";
  			$content.= "</tr></table>";
		$content.= "</td>";
		$content.= "</tr></table><p align=\"center\">";

		//Next image & previous image links
		$query = $DB->query( "SELECT id FROM mkp_gallery WHERE id < $id AND evento = $evento1 AND validate = '1' ORDER BY id DESC LIMIT 1");
		$row = $DB->fetch_row($query);
		$id_next = $row['id'];
		$query = $DB->query( "SELECT id FROM mkp_gallery WHERE id > $id AND evento = $evento1 AND validate = '1' ORDER BY id LIMIT 1");
		$row = $DB->fetch_row($query);
		$id_prew = $row['id'];
		if($id_prew) {
			$content.= "<a href=\"/index.php?ind=gallery&amp;op=foto_show&amp;ida=$id_prew\"><img src=\"$mklib->images/a_indietro.gif\" border=\"0\" alt=\"\" /></a>&nbsp;&nbsp;&nbsp;";
		}
		if($id_next) {
			$content.= "&nbsp;&nbsp;&nbsp;<a href=\"/index.php?ind=gallery&amp;op=foto_show&amp;ida=$id_next\"><img src=\"$mklib->images/a_avanti.gif\" border=\"0\" alt=\"\" /></a><br /><br />";
		}
		$content.= "";
		$content.= "</p><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"moduleborder\"><tr><td>";		
		$content.= "<table border=\"0\" width=\"100%\" cellspacing=\"1\" cellpadding=\"5\">";
		$content .= $this->tpl->row_entry($id, $titolo, $descrizione, $click, $trate, $rate, $width2, $width, $autore, $peso, $dimensioni, $cdata, $rating);
		$content.= "
				  </table>
				</td>
			      </tr>
			    </table>
		";
		$content .= "
			    <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"moduleborder\">
			      <tr>
				<td>
				  <table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"5\">
				    <tr>
				      <td class=\"tdblock\" colspan=\"2\">
				      {$mklib->lang['ga_comments']}
				      </td>
				    </tr>
		";
		if($mklib->member['g_send_comments'] || $mkportals->member['g_access_cp']) {
		$editorscript = "";
			$textarepar = "";
			$textarew = "75%";
			$bbeditor= $mklib->get_commentbbeditor();
			$captcha = $mklib->antibot_start();
			$bbcomnt ="<form action=\"/index.php?ind=gallery&amp;op=add_comment&amp;ide={$id}\" name=\"editor\" method=\"post\" >
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
				      <input type=\"submit\" name=\"submit\" value=\"{$mklib->lang['ga_send']}\" class=\"button2\" accesskey=\"s\" /><br />
				    </td>
				  </tr>		
				</table>
				</form>";
		}
		$query = $DB->query( "SELECT id, autore, testo, data FROM mkp_gallery_comments WHERE identry = '$id' ORDER BY `id` DESC");
		while( $row = $DB->fetch_row($query) ) {
			$idcomm = $row['id'];
			$autorec = $row['autore'];
			$testo = $row['testo'];
			$data = $mklib->create_date($row['data'], "short");
			$delete = "
				    <script type=\"text/javascript\">
				    function makesure() {
				    if (confirm('{$mklib->lang[ga_delcoconfirm]}')) {
				    return true;
				    } else {
				    return false;
				    }
				    }
				    </script><a href=\"/index.php?ind=gallery&amp;op=del_comment&amp;idcomm=$idcomm&amp;iden=$id\" onclick=\"return makesure()\">[ {$mklib->lang['ga_delete']} ]</a>
			";
			if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_mod_gallery']) {
				$delete ="";
			}
			$content .= "
				    <tr>
				      <td class=\"modulecell mkalign1\" width=\"20%\" valign=\"top\">{$autorec}<br />{$data}<br />{$delete}</td>
				      <td class=\"modulecell mkalign1\" width=\"80%\" valign=\"middle\">{$testo}</td>
				    </tr>
			";
		}
		$content .="<table width=\"100%\"><tr><td width=\"100%\">$bbcomnt</td>
			</tr>";
		$content .= "
				  </table>
				</td>
			      </tr>
			    </table>
			  </td>
			</tr>
			";
// Link Navigation bar		
		$navbar = $this->navbar($evento1).$mklib->lang['bc_sep']."<a href=\"#\">$titolo</a>";
// Link Navigation bar
		$maintit = $titolo;
		$submit = "<script type=\"text/javascript\">
			function makesure2() {
			if (confirm('{$mklib->lang[ga_delimgconfirm]}')) {
			return true;
			} else {
			return false;
			}
			}
			</script><a href=\"/index.php?ind=gallery&amp;op=search\">[ {$mklib->lang['ga_search']} ]</a> <a href=\"/index.php?ind=gallery&amp;op=edit_file&amp;iden=$id\">[ {$mklib->lang['ga_modify']} ]</a>  <a href=\"/index.php?ind=gallery&amp;op=del_file&amp;iden=$id\" onclick=\"return makesure2()\">[ {$mklib->lang['ga_delete']} ]</a> ";
			if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_mod_gallery'] && (!$mklib->member['g_send_gallery'] || $mkportals->member['id'] != $idauth || $idauth == 0 )) {
				$submit ="<a href=\"/index.php?ind=gallery&amp;op=search\">[ {$mklib->lang['ga_search']} ]</a>&nbsp;";
		}
		$stat = $this->retrieve_stat();
		$toolbar = "";
		$utonline = $mklib_board->get_active_users("gallery");
		$output  = $this->tpl->gallery_show($navbar, $maintit, $content, $submit, $stat, $toolbar, $show_pages, $utonline);
		$blocks = $Skin->view_block("{$mklib->lang['ga_imggal']}", $output);
		$mklib->printpage("1", "1", $mklib->sitename.$mklib->lang['tt_sep'].$mklib->lang['ga_imggal'].$mklib->lang['tt_sep'].$titolo, $blocks);
		}

		function submit_comment() {
		global $mkportals, $mklib, $Skin, $DB, $mklib_board;
		$ide= intval($mkportals->input['ide']);
		$query = $DB->query( "SELECT id, evento, titolo FROM mkp_gallery WHERE id = '$ide' AND validate = '1'");
		$row = $DB->fetch_row($query);
		if(!$row) {
            		$message = "{$mklib->lang['error_404']}";
            		$mklib->error_page($message);
            		exit;
		}
		$t_id = $row['id'];
		$t_t = $row['titolo'];
		$t_ev1 = $row['evento'];
		
// Link Navigation bar
		$navbar = $this->navbar($t_ev1).$mklib->lang['bc_sep']."<a href=\"/index.php?ind=gallery&amp;op=foto_show&amp;ida=$t_id\">$t_t</a>{$mklib->lang['bc_sep']}<a href=\"#\">{$mklib->lang['ga_comimg']}</a>";
// Link Navigation bar

	   	$content .= "
	   
<tr>
  <td>
    <script type=\"text/javascript\">
    function emo_pop()
    {
 	window.open('{$mkportals->base_url}act=legends&amp;CODE=emoticons&amp;s={$mkportals->session_id}','Legends','width=250,height=500,resizable=yes,scrollbars=yes');
    }
    </script>

    <form action=\"/index.php?ind=gallery&amp;op=add_comment&amp;ide={$ide}\" name=\"editor\" method=\"post\" >
    <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"8\">        
      <tr>
	<td rowspan=\"3\" align=\"center\" height=\"100%\">
	  <iframe src=\"/index.php?ind=gallery&amp;op=show_emoticons\" frameborder=\"0\"  width=\"200\" align=\"middle\" height=\"200\" scrolling=\"auto\"></iframe>
	</td>
	<td>{$mklib->lang['ga_typecom']}</td>
      </tr>
      <tr>
	<td width=\"70%\" align=\"left\"><textarea cols=\"10\" style=\"width:95%\" rows=\"4\" name=\"ta\"></textarea></td>
      </tr>
      <tr>
	<td><input type=\"submit\" name=\"submit\" value=\"{$mklib->lang['ga_send']}\" class=\"mkbutton\" accesskey=\"s\" /><br /></td>
      </tr>		
    </table>
  </form>
  
  </td>
</tr>
	";

	$maintit = "{$mklib->lang['ga_comment']}: $t_t";
	$stat = $this->retrieve_stat();
	$toolbar = "";
	$utonline = $mklib_board->get_active_users("gallery");
	$output  = $this->tpl->gallery_show($navbar, $maintit, $content, $submit, $stat, $toolbar, $show_pages, $utonline);
	$blocks = $Skin->view_block("{$mklib->lang['ga_insertcom']}", $output);
	$mklib->printpage("1", "1", $mklib->sitename.$mklib->lang['tt_sep'].$mklib->lang['ga_imggal'].$mklib->lang['tt_sep'].$t_t.$mklib->lang['tt_sep'].$mklib->lang['ga_insertcom'], $blocks);
	}

	function add_comment() {
    	global $mkportals, $DB, $mklib, $mklib_board;
		$ide= intval($mkportals->input['ide']);
		$testo = $mkportals->input['ta'];
		$autore = $mkportals->member['name'];

		if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_send_comments']) {
			$message = "{$mklib->lang['ga_nosendcom']}";
			$mklib->error_page($message);
			exit;
		}
		       	if ($mklib->config['antibot_chek'] && !$mkportals->member['id']){
$captcha_code = $mkportals->input['check'];
$captcha_check = $mklib->antibot_check($captcha_code);
}

        $cdata = time();

		if (!$testo) {
			$message = "{$mklib->lang['ga_inserttx']}";
			$mklib->error_page($message);
			exit;
		}

		$testo = $mklib_board->decode_smilies($testo);

		//$testo = addslashes($testo);
		$query="INSERT INTO mkp_gallery_comments(identry, autore, testo, data)VALUES('$ide', '$autore', '$testo', '$cdata')";
		$DB->query($query);
		$DB->close_db();
	 	Header("Location: /index.php?ind=gallery&op=foto_show&ida=$ide");
		exit;
  	}

	function del_comment() {
    	global $mkportals, $DB, $mklib, $mklib_board;

		if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_mod_gallery']) {
			$message = "{$mklib->lang['ga_nodelcom']}";
			$mklib->error_page($message);
			exit;
		}
		$ide= intval($mkportals->input['iden']);
		$idcomm= intval($mkportals->input['idcomm']);
		$DB->query("DELETE FROM mkp_gallery_comments WHERE id = $idcomm");
		$DB->close_db();
	 	Header("Location: /index.php?ind=gallery&op=foto_show&ida=$ide");
		exit;
  	}

	function submit_rate() {
    	global $mkportals, $mklib, $Skin, $DB, $mklib_board;
		$ide= intval($mkportals->input['ide']);
		$iduser = $mkportals->member['id'];
		$ipuser = $_SERVER['REMOTE_ADDR'];
		$module = "gallery";

		if (!$iduser || $iduser == 0) { //Guests: check IP address
			$query = $DB->query( "SELECT id FROM mkp_votes WHERE module = '$module' AND id_entry = '$ide' AND ip = '$ipuser'");
			$check = $DB->get_num_rows($query);

		} else { //Registered Members: check userid
			$query = $DB->query( "SELECT id FROM mkp_votes WHERE module = '$module' AND id_entry = '$ide' AND id_member = '$iduser'");
			$check = $DB->get_num_rows($query);
		}
		if($check) {
			$message = "{$mklib->lang['ga_novote']}";
			$mklib->error_page($message);
			exit;
		}


		$query = $DB->query( "SELECT id, evento, titolo FROM mkp_gallery WHERE id = '$ide' AND validate = '1'");
		$row = $DB->fetch_row($query);
		if(!$row) {
            		$message = "{$mklib->lang['error_404']}";
            		$mklib->error_page($message);
            		exit;
		}
		$t_id = $row['id'];
		$t_t = $row['titolo'];
		$t_ev1 = $row['evento'];
		
// Link Navigation bar
		$navbar = $this->navbar($t_ev1).$mklib->lang['bc_sep']."<a href=\"/index.php?ind=gallery&amp;op=foto_show&amp;ida=$t_id\">$t_t</a>{$mklib->lang['bc_sep']}<a href=\"#\">{$mklib->lang['ga_voteimg']}</a>";
// Link Navigation bar

		$content .= "
<tr>
  <td bgcolor=\"#ffffff\">
  
    <form action=\"/index.php?ind=gallery&amp;op=add_rate&amp;ide={$ide}\" method=\"post\" id=\"ratea\" name=\"ratea\">
    <table width=\"100%\">
      <tr>
	<td class=\"modulex\" width=\"60%\" valign=\"top\">{$mklib->lang['ga_voteimgmax']}
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
	  <input type=\"submit\" name=\"ok\" value=\"{$mklib->lang['ga_vote']}\" class=\"mkbutton\" />
	</td>
      </tr>		
    </table>
    </form>
    
  </td>
</tr>

	";
	$maintit = "{$mklib->lang['ga_vote']} $t_t";
	$stat = $this->retrieve_stat();
	$toolbar = "";
	$utonline = $mklib_board->get_active_users("gallery");
	$output  = $this->tpl->gallery_show($navbar, $maintit, $content, $submit, $stat, $toolbar, $show_pages, $utonline);
	$blocks = $Skin->view_block("{$mklib->lang['ga_vote']}", $output);
	$mklib->printpage("1", "1", $mklib->sitename.$mklib->lang['tt_sep'].$mklib->lang['ga_imggal'].$mklib->lang['tt_sep'].$t_t.$mklib->lang['tt_sep'].$mklib->lang['ga_voteimg'], $blocks);
	}
	function add_rate() {
    	global $mkportals, $DB, $mklib, $mklib_board;
		$ide= intval($mkportals->input['ide']);
		$rating = intval($mkportals->input['rating']);
		$iduser = $mkportals->member['id'];
		$ipuser = $_SERVER['REMOTE_ADDR'];
		$module = "gallery";

		if (!$iduser || $iduser == 0) { //Guests: check IP address
			$query = $DB->query( "SELECT id FROM mkp_votes WHERE module = '$module' AND id_entry = '$ide' AND ip = '$ipuser'");
			$check = $DB->get_num_rows($query);

		} else { //Registered Members: check userid
			$query = $DB->query( "SELECT id FROM mkp_votes WHERE module = '$module' AND id_entry = '$ide' AND id_member = '$iduser'");
			$check = $DB->get_num_rows($query);
		}
		if($check) {
			$message = "{$mklib->lang['ga_novote']}";
			$mklib->error_page($message);
			exit;
		}

		//Validate rating value
		if ($rating < 1 || $rating > 5) {
    			$message = $mklib->lang['ga_badvote'];
    			$mklib->error_page($message);
    			exit;
		}

		$query="INSERT INTO mkp_votes(id_entry, module, id_member, ip)VALUES('$ide', '$module', '$iduser', '$ipuser')";
		$DB->query($query);

		$query = $DB->query( "SELECT rate, trate FROM mkp_gallery WHERE id = '$ide'");
		$row = $DB->fetch_row($query);
		$rate = $row['rate'];
		$trate = $row['trate'];
		$votes = ($trate +1);
		$rate = round ((($trate*$rate)+$rating)/($votes), 2);

		$DB->query("UPDATE mkp_gallery SET rate ='$rate', trate ='$votes' WHERE id = '$ide'");
		$DB->close_db();
	 	Header("Location: /index.php?ind=gallery&op=foto_show&ida=$ide");
		exit;
  	}
	function submit_file() {
		global $mkportals, $DB, $mklib, $Skin, $mklib_board;

		if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_send_gallery']) {
			$message = "{$mklib->lang['ga_nouplimg']}";
			$mklib->error_page($message);
			exit;
		}
// Link Navigation bar
		$navbar = "<a href=\"/index.php?ind=gallery\">{$mklib->lang['ga_mkpgal']}</a>{$mklib->lang['bc_sep']}<a href=\"#\">{$mklib->lang['ga_insertimg']}</a>";
// Link Navigation bar		
		$maintit = "{$mklib->lang['ga_mkpgal']}";
// Option evento		
		$cselect = $this->row_select_event();
		if ($cselect == FALSE) {
			$message = "{$mklib->lang['ga_nocat']}";
			$mklib->error_page($message);
			exit;
        	}
	// Option evento
		$content .= "
		<tr>
		  <td>
		  
		    <form action=\"/index.php?ind=gallery&amp;op=add_file\" name=\"UPLOAD\" method=\"post\" enctype=\"multipart/form-data\">
		    <table width=\"100%\" border=\"0\">
		      <tr>
			<td>{$mklib->lang['ga_section']}</td>
			  <td>
			    <select class=\"bgselect\" name=\"evento\" size=\"1\">
			    {$cselect}
			    </select>
			  </td>
			</tr>
			<tr>
			  <td width=\"10%\">{$mklib->lang['ga_title']}</td>
			  <td width=\"90%\"><input type=\"text\" name=\"titolo\" size=\"52\" class=\"bgselect\" /></td>
			</tr>
			<tr>
			  <td width=\"10%\" valign=\"top\">{$mklib->lang['ga_des']}</td>
			  <td width=\"90%\"><textarea cols=\"50\" rows=\"10\" name=\"descrizione\" class=\"bgselect\"></textarea></td>
			</tr>
			<tr>
			  <td width=\"10%\">{$mklib->lang['ga_file']}</td>
			  <td width=\"90%\"><input type=\"file\" name=\"FILE_UPLOAD\" size=\"50\" class=\"bgselect\" /></td>
			</tr>
<!-- Submit FILE URL -->
			<tr>
			  <td width=\"10%\">{$mklib->lang['ga_file_URL']}</td>
			  <td width=\"90%\"><input type=\"text\" name=\"FILE_URL\" size=\"50\" class=\"bgselect\" /><br> {$mklib->lang['ga_URL']} </td>
			</tr>	
<!-- Submit FILE URL -->
			<tr>
			  <td colspan=\"2\"><input type=\"submit\" value=\"{$mklib->lang['ga_insert']}\" class=\"mkbutton\" /></td>
			</tr>
		      </table>
		      </form>
		      
		    </td>
		  </tr>
		";

		$submit = " <a href=\"/index.php?ind=gallery&amp;op=submit_file\">[ {$mklib->lang['ga_insertimg']} ]</a> ";
		$stat = $this->retrieve_stat();
		$toolbar = "";
		$utonline = $mklib_board->get_active_users("gallery");
		$output  = $this->tpl->gallery_show($navbar, $maintit, $content, $submit, $stat, $toolbar, $show_pages, $utonline);
		$blocks = $Skin->view_block("{$mklib->lang['ga_insertaimg']}", $output);
		$mklib->printpage("1", "1", $mklib->sitename.$mklib->lang['tt_sep'].$mklib->lang['ga_imggal'].$mklib->lang['tt_sep'].$mklib->lang['ga_insertimg'], $blocks);
	}

	function add_file() {

		global $mkportals, $DB, $mklib, $_FILES, $mklib_board;

		if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_send_gallery']) {
			$message = "{$mklib->lang['ga_nouplimg']}";
			$mklib->error_page($message);
			exit;
		}
		$FILE_UPLOAD = $mkportals->input['FILE_UPLOAD'];
		$FILE_URL = $mkportals->input['FILE_URL'];
		
		if (!$FILE_UPLOAD && $FILE_URL){
            $file = $FILE_URL;
            $file_name = preg_replace("`.*\/(([^\/]+)\.([^\/]+))`", "\\1", $FILE_URL);
            $file_name = preg_replace("/[^a-zA-Z0-9\_\-\.]/", '' , $file_name);
            $file_name = preg_replace('#\.{1,}#s', '.', $file_name);
            $file_name = preg_replace('#\_{2,}#s', '_', $file_name);

            if (!preg_match('#^http://#', $FILE_URL) || !$file_name) {
                $message = "{$mklib->lang['ga_noURL']}<br />{$mklib->lang['ga_URL']}";
                $mklib->error_page($message);
                exit;
            } 

        $fp = fopen($FILE_URL,"rb");
		
		if (!$fp) {
			$message = "{$mklib->lang['ga_fopen']}";
			$mklib->error_page($message);
			exit;
		}

        $header = stream_get_meta_data($fp);
        	for ($i=1; isset($header['wrapper_data'][$i]); $i++) {
            	if (strstr(strtolower($header['wrapper_data'][$i]), 'content-type')) {
                	if((eregi('^content-type: ([[:graph:]]+)', $header['wrapper_data'][$i], $MIME_extraction_array))) {
					$file_type = $MIME_extraction_array[1];
					}
				}
				if (strstr(strtolower($header['wrapper_data'][$i]), 'content-length')) {
                	if(eregi('^content-length: ([[:digit:]]+)', $header['wrapper_data'][$i], $length_extraction_array)) {
                	$peso = $length_extraction_array[1];
                	}
            	}
			}
		} else {
		$file =  $_FILES['FILE_UPLOAD']['tmp_name'];
		$file_name =  $_FILES['FILE_UPLOAD']['name'];
		//$file_type =  $_FILES['FILE_UPLOAD']['type'];
		$peso =  $_FILES['FILE_UPLOAD']['size'];		
		}
		
		$evento = intval($mkportals->input['evento']);
		$titolo = $mkportals->input['titolo'];
		$descrizione = $mkportals->input['descrizione'];
		$autore = $mkportals->member['name'];
		$idauth = $mkportals->member['id'];

		if (!$evento || !$titolo || !$descrizione || !$file) {
			$message = "{$mklib->lang['ga_mustcompile']}";
			$mklib->error_page($message);
			exit;
		}

		if ($file_name) {
        //Validate file extension
		//Edited by Kimi in C1.2.2 (code by visiblesoul)
		$file_ext = preg_replace("`.*(\..*)`", "\\1", $file_name);
	//	$file_ext = substr ($file_name, (strlen($file_name)-4), 4); //Removed by Kimi in C1.2.2 (thanks to BMG)
		$file_ext = strtolower($file_ext);

		switch($file_ext)
		{
			case '.gif':
				$ext = '.gif';
				break;
			case '.jpg':
				$ext = '.jpg';
				break;
			case '.png':
				$ext = '.png';
				break;
			case '.tif':
				$ext = '.tif';
				break;
			case '.bmp':
				$ext = '.bmp';
				break;
			default:
				$ext = 'not_supported';
				break;
		}

			if ($ext == "not_supported")  {
				$message = "{$mklib->lang['ga_notsup']}";
				$mklib->error_page($message);
				exit;
			}

			//Validate file size
			if ($mklib->config['upload_image_max'] > 0 && $peso > ($mklib->config['upload_image_max']*1024)) {
				$message = "{$mklib->lang['ga_maxupl']}";
				$mklib->error_page($message);
				exit;
			}
						
			if (!$FILE_UPLOAD && $FILE_URL) {
				//Copy file from remote server to gallery "tmp" directory
				if (!copy("$file", "/mkportal/modules/gallery/album/tmp/$file_name")) {
					$message = "{$mklib->lang['ga_errorupl']}";
					$mklib->error_page($message);
					exit;
				}
			} else {
				//Move file from local server tmp directory to gallery "tmp" directory
				if (!move_uploaded_file("$file", "/mkportal/modules/gallery/album/tmp/$file_name")) {
					$message = "{$mklib->lang['ga_errorupl']}";
					$mklib->error_page($message);
					exit;
				}
			}
			@chmod("/mkportal/modules/gallery/album/tmp/$file_name", 0644);

			//Validate by mime type
			$tmpfilename = "mkportal/modules/gallery/album/tmp/$file_name";
			$size = @getimagesize($tmpfilename);
			//If getimagesize does not recognize file as an image delete file
			if (!$size)  {
				@unlink($tmpfilename);
				$message .= "{$mklib->lang['ga_notsup']}";
				$mklib->error_page($message);
				exit;
			}
			$file_type = $size['mime'];
		
			if (!$mklib->check_attach($file_type, $file_ext))  {
				//Delete invalid file and display error
				@unlink($tmpfilename);
				$message .= "{$mklib->lang['ga_notsup']}";
				$mklib->error_page($message);
				exit;
			}	

			//Validate by file contents
			$fcontents = file_get_contents ($tmpfilename);
			$carray = array("html", "javascript", "vbscript", "alert", "onmouseover", "onclick", "onload", "onsubmit");		
			foreach ($carray as $fch) {
            			if (strstr($fcontents, $fch)) {
                			@unlink($tmpfilename);
					$message .= "{$mklib->lang['ga_notsup']}";
					$mklib->error_page($message);
                			exit;
            			}
        		}
        		if (preg_match("#script(.+?)/script#ies", $fcontents)) {
           			@unlink($tmpfilename);
				$message .= "{$mklib->lang['ga_notsup']}";
				$mklib->error_page($message);
            			exit;
        		}

			$query = $DB->query("SELECT id FROM mkp_gallery ORDER BY id DESC LIMIT 1");
			$row = $DB->fetch_row($query);
			$totr = $row['id'];
			++$totr;

			//Edited by Kimi in C1.2.2
			$image = "a_"."$totr"."$ext";

			//move file from "tmp" directory to "album" directory
			@rename($tmpfilename, "/mkportal/modules/gallery/album/$image");
		}

		if (!is_file ("/mkportal/modules/gallery/album/$image")) {
			$message = "{$mklib->lang['ga_errorupl']}";
			$mklib->error_page($message);
			exit;
		}
		
		$cdata = time();
		$thumb = "t_$image";

		$validat = "1";
		$approval = $mklib->config['approval_gallery'];
		if ($approval == "2" || $approval == "3") {
			$validat = 0;
		}
		if($mkportals->member['g_access_cp']) {
			$validat = "1";
		}
// Meo Changed in C 0.1.b to extend thumb types 
		//if ($ext == "jpg") {
			$mklib->CreateImage($mklib->config['thumb_max_dimen'],"/mkportal/modules/gallery/album/$image", "/mkportal/modules/gallery/album/$thumb");
		//}
// End
		//try to watermark image.
		if ($mklib->config['watermark_enable']) {
			$mklib->watermark("/mkportal/modules/gallery/album/$image");
		}
		$query="INSERT INTO mkp_gallery(evento, titolo, descrizione, file, autore, idauth, peso, data, validate)VALUES('$evento', '$titolo', '$descrizione', '$image', '$autore', '$idauth', '$peso', '$cdata', '$validat')";
		$DB->query($query);
		
		if ($approval == "1") {
			$mailsubj = $mklib->lang['01mail'].$mklib->lang['gallery'];
			$mailmess = $mklib->lang['02mail'].$mklib->lang['module'].$mklib->lang['gallery']."\n".$mklib->lang['sender'].$autore."\n\n\n".$mklib->lang['from']." ".$mklib->sitename;
			$mklib_board->admin_mail($mailsubj, $mailmess);
		}
		if ($approval == "2" && !$mkportals->member['g_access_cp']) {
			$mailsubj = $mklib->lang['01mail'].$mklib->lang['gallery'];
			$mailmess = $mklib->lang['03mail'].$mklib->lang['module'].$mklib->lang['gallery']."\n".$mklib->lang['sender'].$autore."\n\n\n".$mklib->lang['from']." ".$mklib->sitename;
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

	 	Header("Location: /index.php?ind=gallery");
		exit;
		
  	}
	function edit_file() {
		global $mkportals, $DB, $mklib, $Skin, $mklib_board;

		$iden = intval($mkportals->input['iden']);
		$query = $DB->query( "SELECT evento, titolo, descrizione, autore, idauth FROM mkp_gallery WHERE id = $iden");
		$row = $DB->fetch_row($query);
		if(!$row) {
            		$message = "{$mklib->lang['error_404']}";
            		$mklib->error_page($message);
            		exit;
		}
		$evento1 = $row['evento'];
		$titolo = $row['titolo'];
		$autore = $row['autore'];
		$idauth = $row['idauth'];
		$descrizione = $row['descrizione'];
		$descrizione = str_replace("<br />", "\n", $descrizione);

		if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_mod_gallery'] && (!$mklib->member['g_send_gallery'] || $mkportals->member['id'] != $idauth || $idauth == 0 )) {
			$message = "{$mklib->lang['ga_noautmodg']}";
			$mklib->error_page($message);
			exit;
		}
// Option evento
		
		$cselect = $this->row_select_event();
// Option evento
		$content .= "
		  <tr>
		    <td class=\"modulex\">
		    
		      <form action=\"/index.php?ind=gallery&amp;op=update_file&amp;iden=$iden\" name=\"UPDATE\" method=\"post\">
		      <table width=\"100%\" border=\"0\">
			<tr>
			  <td>{$mklib->lang['ga_section']}</td>
			  <td>
			  <select class=\"bgselect\" name=\"evento\" size=\"1\">
			  {$cselect}
			  </select>
			  </td>
			</tr>
			<tr>
			  <td width=\"10%\">{$mklib->lang['ga_title']}</td>
			  <td width=\"90%\"><input type=\"text\" name=\"titolo\" value=\"$titolo\" size=\"52\" class=\"bgselect\" /></td>
			</tr>
			<tr>
			  <td width=\"10%\" valign=\"top\">{$mklib->lang['ga_des']}</td>
			  <td width=\"90%\"><textarea cols=\"50\" rows=\"10\" name=\"descrizione\" class=\"bgselect\">{$descrizione}</textarea></td>
			</tr>
			<tr>
			  <td colspan=\"2\"><input type=\"submit\" value=\"{$mklib->lang['ga_save']}\" class=\"mkbutton\" /></td>
			</tr>
		      </table>
		      </form>
		    </td>
		  </tr>
		";

		$submit = "<script type=\"text/javascript\">
			function makesure2() {
			if (confirm('{$mklib->lang[ga_delimgconfirm]}')) {
			return true;
			} else {
			return false;
			}
			}
			</script> <a href=\"/index.php?ind=gallery&amp;op=del_file&amp;iden=$iden\" onclick=\"return makesure2()\">[ {$mklib->lang[ga_delete]} ]</a> ";
// Link Navigation bar		
		$navbar = $this->navbar($evento1).$mklib->lang['bc_sep']."<a href=\"/index.php?ind=gallery&amp;op=foto_show&amp;ida=$iden\">$titolo</a>{$mklib->lang['bc_sep']}<a href=\"#\">{$mklib->lang['ga_modimg']}</a>";
// Link Navigation bar		
		$maintit = $titolo;
		$stat = $this->retrieve_stat();
		$toolbar = "";
		$utonline = $mklib_board->get_active_users("gallery");
		$output  = $this->tpl->gallery_show($navbar, $maintit, $content, $submit, $stat, $toolbar, $show_pages, $utonline);
		$blocks = $Skin->view_block("{$mklib->lang['ga_modimg']}", $output);
		$mklib->printpage("1", "1", $mklib->sitename.$mklib->lang['tt_sep'].$mklib->lang['ga_imggal'].$mklib->lang['tt_sep'].$titolo.$mklib->lang['tt_sep'].$mklib->lang['ga_modimg'], $blocks);
	}

	function update_file() {
    	global $mkportals, $DB, $mklib, $mklib_board;
		$iden= intval($mkportals->input['iden']);
		$idcategoria = intval($mkportals->input['evento']);
		$titolo = $mkportals->input['titolo'];
		$descrizione = $mkportals->input['descrizione'];

		$query = $DB->query("SELECT idauth FROM mkp_gallery WHERE id = $iden");
		$row = $DB->fetch_row($query);
		$idauth = $row['idauth']; 

		if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_mod_gallery'] && (!$mklib->member['g_send_gallery'] || $mkportals->member['id'] != $idauth || $idauth == 0 )) {
			$message = "{$mklib->lang['ga_noautmodg']}";
			$mklib->error_page($message);
			exit;
		}

		$DB->query("UPDATE mkp_gallery SET evento ='$idcategoria', titolo ='$titolo', descrizione ='$descrizione' where id = '$iden'");
		$DB->close_db();
		Header("Location: /index.php?ind=gallery&op=foto_show&ida=$iden");
		exit;
  		}

	function del_file() {
    	global $mkportals, $DB, $mklib, $mklib_board;

		$iden= intval($mkportals->input['iden']);
		$query = $DB->query( "SELECT file, autore, idauth FROM mkp_gallery WHERE id = $iden");
		$row = $DB->fetch_row($query);
		$file = $row['file'];
		$thumb = "t_$file";
		$autore = $row['autore'];
		$idauth = $row['idauth'];
		if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_mod_gallery'] && (!$mklib->member['g_send_gallery'] || $mkportals->member['id'] != $idauth || $idauth == 0 )) {
			$message = "{$mklib->lang['ga_nodelimg']}";
			$mklib->error_page($message);
			exit;
		}

		@unlink("mkportal/modules/gallery/album/$file");
		@unlink("mkportal/modules/gallery/album/$thumb");
		$DB->query("DELETE FROM mkp_gallery WHERE id = $iden");
		$DB->query("DELETE FROM mkp_gallery_comments WHERE identry = $iden");
		$this->update_total();
		$DB->close_db();
	 	Header("Location: /index.php?ind=gallery");
		exit;
  		}



	function retrieve_stat() {
			global $mkportals, $DB, $mklib, $mklib_board;
			$query = $DB->query( "SELECT id, titolo FROM mkp_gallery WHERE validate = '1' ORDER BY `click` DESC");
			$row = $DB->fetch_row($query);
			$id = $row['id'];
			$name = $row['titolo'];
			$visitato = "<a href=\"/index.php?ind=gallery&amp;op=foto_show&amp;ida=$id\">$name</a>";
			$count = $DB->get_num_rows($query);
			$query = $DB->query( "SELECT id, titolo FROM mkp_gallery WHERE validate = '1' ORDER BY `trate` DESC LIMIT 1");
			$row = $DB->fetch_row($query);
			$id = $row['id'];
			$name = $row['titolo'];
			$votato = "<a href=\"/index.php?ind=gallery&amp;op=foto_show&amp;ida=$id\">$name</a>";
			$query = $DB->query( "SELECT id, titolo FROM mkp_gallery WHERE validate = '1' ORDER BY `id` DESC LIMIT 1");
			$row = $DB->fetch_row($query);
			$id = $row['id'];
			$name = $row['titolo'];
			$ultima = "<a href=\"/index.php?ind=gallery&amp;op=foto_show&amp;ida=$id\">$name</a>";
			$output = "{$mklib->lang['ga_have']} $count {$mklib->lang['ga_totfile']}<br />{$mklib->lang['ga_mosts']} $visitato<br />{$mklib->lang['ga_mostv']} $votato<br />{$mklib->lang['ga_lastinsim']} $ultima";
			return $output;
		}

	function submit_postcard() {
		global $mkportals, $mklib, $Skin, $DB, $mklib_board;
		$ide= intval($mkportals->input['ide']);


		if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_send_ecard']) {
			$message = "{$mklib->lang['ga_nosendecard']}";
			$mklib->error_page($message);
			exit;
		}

		$query = $DB->query( "SELECT id, evento, titolo, file FROM mkp_gallery WHERE id = '$ide' AND validate = '1'");
		$row = $DB->fetch_row($query);
		if(!$row) {
            		$message = "{$mklib->lang['error_404']}";
            		$mklib->error_page($message);
            		exit;
		}
		$t_id = $row['id'];
		$t_t = $row['titolo'];
		$t_ev1 = $row['evento'];
		$file = $row['file'];
		
// Link Navigation bar
		$navbar = $this->navbar($t_ev1).$mklib->lang['bc_sep']."<a href=\"/index.php?ind=gallery&amp;op=foto_show&amp;ida=$t_id\">$t_t</a>{$mklib->lang['bc_sep']}<a href=\"#\">{$mklib->lang['gat_pcard']}</a>";
// Link Navigation bar

	    $content .= "
		  <tr>
		    <td bgcolor=\"#ffffff\" align=\"center\">
		      <table border=\"0\" width=\"100\" cellspacing=\"0\" cellpadding=\"0\">
			<tr>
			  <td width=\"1%\" style=\"font-size: 4px;\"><img src=\"$mklib->images/a_sx_a.gif\" style=\"vertical-align: bottom\" alt=\"\" /></td>
			  <td width=\"98%\" style=\"background-image: url('$mklib->images/a_sf_s.gif'); font-size: 4px;\"><img src=\"$mklib->images/a_sf_s.gif\" height=\"8\" style=\"vertical-align: bottom\" alt=\"\" /></td>
			  <td width=\"1%\" style=\"font-size: 4px;\"><img src=\"$mklib->images/a_dx_a.gif\" style=\"vertical-align: bottom\" alt=\"\" /></td>
			</tr>
			<tr>
			  <td width=\"1%\" style=\"background-image: url('$mklib->images/a_sx_s.gif');\"><img src=\"$mklib->images/a_sx_s.gif\" width=\"11\" height=\"15\" alt=\"\" /></td>
			  <td width=\"98%\" bgcolor=\"#ffffff\" align=\"center\">
			    <p class=\"ecardtitle\">[{$mklib->lang['ga_title']}]</p>
			    <p align=\"right\">{$mklib->lang['ga_to']} [{$mklib->lang['ga_dest']}]</p>
			    <img src=\"/mkportal/modules/gallery/album/$file\" border=\"0\" alt=\"\" /><br /><br />
			    <p class=\"ecardtitle\">[{$mklib->lang['ga_youmess']}]</p>
			    <p align=\"right\">[{$mklib->lang['ga_you']}]</p>
			  </td>
			  <td width=\"1%\" style=\"background-image: url('$mklib->images/a_dx_s.gif');\"><img src=\"$mklib->images/a_dx_s.gif\" width=\"11\" height=\"14\" alt=\"\" /></td>
			</tr>
			<tr>
			  <td width=\"1%\"><img src=\"$mklib->images/a_sx_g.gif\" height=\"22\" style=\"vertical-align: top\" alt=\"\" /></td>
			  <td width=\"98%\" style=\"background-image: url('$mklib->images/a_sf_g.gif');\"></td>
			  <td width=\"1%\" valign=\"top\"><img src=\"$mklib->images/a_dx_g.gif\" height=\"22\" style=\"vertical-align: top\" alt=\"\" /></td>
			</tr>
		      </table>  		
		
		      <form action=\"/index.php?ind=gallery&amp;op=preview_postcard\" name=\"editor\" method=\"post\" >
		      <table align=\"center\" border=\"0\" cellspacing=\"2\" cellpadding=\"0\" class=\"modulebg\">
			<tr>		
			  <td width=\"30%\" align=\"right\">{$mklib->lang['ga_title']}:</td>
			  <td align=\"left\">
			    <input type=\"hidden\" name=\"ide\" value=\"$ide\" />
			    <input type=\"text\" name=\"titlecard\" size=\"30\" class=\"bgselect\" />
			  </td>
			</tr>
			<tr>
			  <td width=\"30%\" align=\"right\">{$mklib->lang['ga_dest']}:</td>
			  <td align=\"left\"><input type=\"text\" name=\"dest\" size=\"30\" class=\"bgselect\" /></td>
			</tr>
			<tr>
			  <td width=\"30%\" align=\"right\">{$mklib->lang['ga_emaildest']}:</td>
			  <td align=\"left\"><input type=\"text\" name=\"emaildest\" size=\"30\" class=\"bgselect\" /></td>
			</tr>
			<tr>
			  <td width=\"30%\" align=\"right\">{$mklib->lang['ga_you']}:</td>
			  <td align=\"left\"><input type=\"text\" name=\"you\" size=\"30\" class=\"bgselect\" /><br /></td>
			</tr>
			<tr>
			  <td colspan=\"2\" align=\"center\">{$mklib->lang['ga_youmess']}</td>
			</tr>
			<tr>
			  <td width=\"70%\" align=\"left\" colspan=\"2\"><textarea cols=\"10\" style=\"width:95%\" rows=\"4\" name=\"ta\"></textarea></td>
			</tr>
			<tr>
			  <td colspan=\"2\" align=\"center\"><input type=\"submit\" name=\"submit\" value=\"{$mklib->lang['ga_ecardprev']}\" class=\"mkbutton\" accesskey=\"s\" /><br /></td>
			</tr>
		      </table>
		      </form>		
		
		    </td>
		  </tr>
	";
	$maintit = "$t_t";
	$stat = $this->retrieve_stat();
	$toolbar = "";
	$utonline = $mklib_board->get_active_users("gallery");
	$output  = $this->tpl->gallery_show($navbar, $maintit, $content, $submit, $stat, $toolbar, $show_pages, $utonline);
	$blocks = $Skin->view_block("{$mklib->lang['gat_pcard']}", $output);
	$mklib->printpage("1", "1", $mklib->sitename.$mklib->lang['tt_sep'].$mklib->lang['ga_imggal'].$mklib->lang['tt_sep'].$t_t .$mklib->lang['tt_sep'].$mklib->lang['gat_pcard'], $blocks);
	}

	function preview_postcard() {
		global $mkportals, $mklib, $Skin, $DB, $mklib_board;

		if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_send_ecard']) {
			$message = "{$mklib->lang['ga_nosendecard']}";
			$mklib->error_page($message);
			exit;
		}

		$ide= intval($mkportals->input['ide']);
		$titlecard= stripslashes($mkportals->input['titlecard']);
		$dest= $mkportals->input['dest'];
		$emaildest= $mkportals->input['emaildest'];
		$you= $mkportals->input['you'];
		$Post= stripslashes($mkportals->input['ta']);

  		if (!$titlecard || !$dest || !$emaildest || !$you || !$Post) {
			$message = "{$mklib->lang['ga_mustcompile']}";
			$mklib->error_page($message);
			exit;
		}

		$query = $DB->query( "SELECT id, evento, titolo, file FROM mkp_gallery WHERE id = '$ide' AND validate = '1'");
		$row = $DB->fetch_row($query);
		$t_id = $row['id'];
  		$t_t = $row['titolo'];
		$t_ev1 = $row['evento'];
		$file = $row['file'];

// Link Navigation bar
		$navbar = $this->navbar($t_ev1).$mklib->lang['bc_sep']."<a href=\"/index.php?ind=gallery&amp;op=foto_show&amp;ida=$t_id\">$t_t</a>{$mklib->lang['bc_sep']}<a href=\"#\">{$mklib->lang['gat_pcard']}</a>";
// Link Navigation bar

	   	$content .= "
		  <tr>
		    <td bgcolor=\"#ffffff\" align=\"center\">				
		      <table border=\"0\" width=\"100\" cellspacing=\"0\" cellpadding=\"0\">
			<tr>
			  <td width=\"1%\" style=\"font-size: 4px;\"><img src=\"$mklib->images/a_sx_a.gif\" style=\"vertical-align: bottom\" alt=\"\" /></td>
			  <td width=\"98%\" style=\"background-image: url('$mklib->images/a_sf_s.gif'); font-size: 4px;\"><img src=\"$mklib->images/a_sf_s.gif\" height=\"8\" style=\"vertical-align: bottom\" alt=\"\" /></td>
			  <td width=\"1%\" style=\"font-size: 4px;\"><img src=\"$mklib->images/a_dx_a.gif\" style=\"vertical-align: bottom\" alt=\"\" /></td>
			</tr>
			<tr>
			  <td width=\"1%\" style=\"background-image: url('$mklib->images/a_sx_s.gif');\"><img src=\"$mklib->images/a_sx_s.gif\" width=\"11\" height=\"15\" alt=\"\" /></td>
			  <td width=\"98%\" bgcolor=\"#ffffff\" align=\"center\">
			    <p align=\"center\"><big><b>$titlecard</big></b></p>
			    <p align=\"right\">{$mklib->lang['ga_to']} $dest </p>
			    <img src=\"/mkportal/modules/gallery/album/$file\" border=\"0\" alt=\"\" /></a><br /><br />
			    <p align=\"center\"><big><b>$Post</big></b></p>
			    <p align=\"right\">$you</p>
			  </td>
			  <td width=\"1%\" style=\"background-image: url('$mklib->images/a_dx_s.gif');\"><img src=\"$mklib->images/a_dx_s.gif\" width=\"11\" height=\"14\" alt=\"\" /></td>
			</tr>
			<tr>
			  <td width=\"1%\"><img src=\"$mklib->images/a_sx_g.gif\" height=\"22\" style=\"vertical-align: top\" alt=\"\" /></td>
			  <td width=\"98%\" style=\"background-image: url('$mklib->images/a_sf_g.gif');\"></td>
			  <td width=\"1%\" valign=\"top\"><img src=\"$mklib->images/a_dx_g.gif\" height=\"22\" style=\"vertical-align: top\" alt=\"\" /></td>
			</tr>
		      </table>
	   	   
		      <form action=\"/index.php?ind=gallery&amp;op=send_postcard\" name=\"editor\" method=\"post\" >
		      <table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"0\" class=\"modulebg\">
			<tr>
			  <td align=\"center\">
			    <input type=\"hidden\" name=\"file\" value=\"$file\" />
			      <input type=\"hidden\" name=\"titlecard\" value=\"$titlecard\" />
			      <input type=\"hidden\" name=\"dest\" value=\"$dest\" />
			      <input type=\"hidden\" name=\"emaildest\" value=\"$emaildest\" />
			      <input type=\"hidden\" name=\"you\" value=\"$you\" />
			      <input type=\"hidden\" name=\"ta\" value=\"$Post\" />
			      <input type=\"submit\" name=\"submit\" value=\"{$mklib->lang['gat_pcard']}\" class=\"mkbutton\" accesskey=\"s\" /><br />
			    </td>
			  </tr>
			</table>
			</form>
			
		      </td>
		    </tr>
	";
	$maintit = "$t_t";
	$stat = $this->retrieve_stat();
	$toolbar = "";
	$utonline = $mklib_board->get_active_users("gallery");
	$output  = $this->tpl->gallery_show($navbar, $maintit, $content, $submit, $stat, $toolbar, $show_pages, $utonline);
	$blocks = $Skin->view_block("{$mklib->lang['gat_pcard']}", $output);
	$mklib->printpage("1", "1", $mklib->sitename.$mklib->lang['tt_sep'].$mklib->lang['ga_imggal'].$mklib->lang['tt_sep'].$t_t.$mklib->lang['tt_sep'].$mklib->lang['gat_pcard'], $blocks);
	}

	function send_postcard() {
		global $mkportals, $mklib, $Skin, $DB, $mklib_board;

		if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_send_ecard']) {
			$message = "{$mklib->lang['ga_nosendecard']}";
			$mklib->error_page($message);
			exit;
		}
		$emaildest= $mkportals->input['emaildest'];

		$titolo= $mkportals->input['titlecard'];
		$file= $mkportals->input['file'];
		$destinatario= $mkportals->input['dest'];
		$mittente= $mkportals->input['you'];
		$emailmit = $mkportals->member['email'];
		$testo= $mkportals->input['ta'];
		$member = $mkportals->member['name'];
		$date = time();
		$code = rand(1, 9999);

		$query="INSERT INTO mkp_ecards (titolo, file, destinatario, mittente, emailmit, testo, member, date, code)VALUES('$titolo', '$file', '$destinatario', '$mittente', '$emailmit', '$testo', '$member', '$date', '$code')";
		$DB->query($query);

		$query = $DB->query("SELECT id FROM mkp_ecards WHERE code = '$code' ORDER BY `id` DESC LIMIT 1");
		$row = $DB->fetch_row($query);
		$ide = $row['id'];
		$url = "$mklib->siteurl/index.php?ind=gallery&op=postcard_view&ide=$ide&code=$code";

		$oggetto = $mklib->lang['ga_ecard'];
		$intestazione = "From:mkportal_ecard_service \nReply-To:$emailmit";
		$messaggio = "{$mklib->lang['ga_mailmsg1']}";
		$messaggio .= "$mittente ($emailmit) \n\n";
		$messaggio .= "{$mklib->lang['ga_mailmsg2']} \n";
		$messaggio .= "$url \n\n{$mklib->lang['ga_team']}";

		mail($emaildest, $oggetto, $messaggio, $intestazione);

		$output = "
		    <tr>
		      <td>
			<table class=\"moduleborder\" cellspacing=\"1\" cellpadding=\"0\" width=\"98%\" align=\"center\" border=\"0\">
			  <tr>
			    <td class=\"titadmin\" width=\"100%\" align=\"center\">
			    <br /><br />{$mklib->lang['ga_pcardsendok']}<br /><br /><br />
			    </td>
			  </tr>
			  <tr>
			    <td align=\"center\" class=\"modulecell\"><br /><br />
			    <div align=\"center\"><a href=\"http://www.mkportal.it\" target=\"_blank\">MKPGallery</a> &copy;2003-2008 <a href=\"http://www.mkportal.it\" target=\"_blank\">mkportal.it</a></div>
			    </td>
			  </tr>
			</table>
		      </td>
		    </tr>
		";

		$blocks = $Skin->view_block("{$mklib->lang['gat_pcard']}", $output);
		$mklib->printpage("1", "1", $mklib->sitename.$mklib->lang['tt_sep'].$mklib->lang['ga_imggal'].$mklib->lang['tt_sep'].$titolo.$mklib->lang['tt_sep'].$mklib->lang['gat_pcard'], $blocks);
	}


	function postcard_view() {
		global $mkportals, $mklib, $Skin, $DB, $mklib_board;
		$ide= intval($mkportals->input['ide']);
		$code= $mkportals->input['code'];

		if(!$code) {
			$message = "{$mklib->lang['ga_nopcard']}.";
			$mklib->error_page($message);
			exit;
		}

		$query = $DB->query( "SELECT titolo, file, destinatario, mittente, emailmit, testo, member, date FROM mkp_ecards WHERE id = '$ide' and code = '$code'");
		$row = $DB->fetch_row($query);

		$file = $row['file'];
		if(!$file) {
			$message = "{$mklib->lang['ga_nopcard']}.";
			$mklib->error_page($message);
			exit;
		}
		$row['testo'] = stripslashes($row['testo']);
		$row['testo'] = str_replace("&lt;br /&gt;", "<br />", $row['testo']);
		$row['titolo'] = stripslashes($row['titolo']);
// Link Navigation bar
	   	$navbar = "{$mklib->lang['ga_ecard']}";
// Link Navigation bar
	   	$content .= "
		    <tr>
		      <td bgcolor=\"#ffffff\" align=\"center\">
		      
			<table border=\"0\" width=\"100\" cellspacing=\"0\" cellpadding=\"0\">
			  <tr>
			    <td width=\"1%\" style=\"font-size: 4px;\"><img src=\"$mklib->images/a_sx_a.gif\" style=\"vertical-align: bottom\" alt=\"\" /></td>
			    <td width=\"98%\" style=\"background-image: url('$mklib->images/a_sf_s.gif'); font-size: 4px;\"><img src=\"$mklib->images/a_sf_s.gif\" height=\"8\" style=\"vertical-align: bottom\" alt=\"\" /></td>
			    <td width=\"1%\" style=\"font-size: 4px;\"><img src=\"$mklib->images/a_dx_a.gif\" style=\"vertical-align: bottom\" alt=\"\" /></td>
			  </tr>
			  <tr>
			    <td width=\"1%\" style=\"background-image: url('$mklib->images/a_sx_s.gif');\"><img src=\"$mklib->images/a_sx_s.gif\" width=\"11\" height=\"15\" alt=\"\" /></td>
			    <td width=\"98%\" bgcolor=\"#ffffff\" align=\"center\">
			      <p class=\"ecardtitle\">{$row['titolo']}</p>
			      <p align=\"right\">{$mklib->lang['ga_to']} {$row['destinatario']}</p>
			      <img src=\"/mkportal/modules/gallery/album/$file\" border=\"0\" alt=\"\" /><br /><br />
			      <p class=\"ecardtitle\">{$row['testo']}</p>
			      <p align=\"right\">
			      {$row['mittente']} ({$row['emailmit']})<br />{$row['member']}</p>
			    </td>
			    <td width=\"1%\" style=\"background-image: url('$mklib->images/a_dx_s.gif');\"><img src=\"$mklib->images/a_dx_s.gif\" width=\"11\" height=\"14\" alt=\"\" /></td>
			  </tr>
			  <tr>
			    <td width=\"1%\"><img src=\"$mklib->images/a_sx_g.gif\" height=\"22\" style=\"vertical-align: top\" alt=\"\" /></td>
			    <td width=\"98%\" style=\"background-image: url('$mklib->images/a_sf_g.gif');\"></td>
			    <td width=\"1%\" valign=\"top\"><img src=\"$mklib->images/a_dx_g.gif\" height=\"22\" style=\"vertical-align: top\" alt=\"\" /></td>
			  </tr>
			</table>
			
		      </td>
		    </tr>
	";
	// delete ecard older than 1 month
	$old = (time() - (86400*30));


	$DB->query("DELETE FROM mkp_ecards WHERE date < $old");
	$maintit = "{$row['titolo']}";
	$stat = $this->retrieve_stat();
	$toolbar = "";
	$utonline = $mklib_board->get_active_users("gallery");
	$output  = $this->tpl->gallery_show($navbar, $maintit, $content, $submit, $stat, $toolbar, $show_pages, $utonline);
	$blocks = $Skin->view_block("{$row['destinatario']}", $output);
	$mklib->printpage("1", "1", $mklib->sitename.$mklib->lang['tt_sep'].$mklib->lang['ga_imggal'].$mklib->lang['tt_sep'].$row['titolo'].$mklib->lang['tt_sep'].$mklib->lang['ga_ecard'], $blocks);
	}



	function slide_start() {
		global $mkportals, $mklib, $Skin, $DB, $mklib_board;
		$ide= intval($mkportals->input['ide']);
		$cat = intval($mkportals->input['cat']);
		
		$cselect = "<option value=\"$idevento\">{$mklib->lang['ga_schannel']}</option>\n";
		$cselect .= $this->row_select_event();

// Link Navigation bar	   	
		$navbar = "<a href=\"/index.php?ind=gallery\">{$mklib->lang['ga_mkpgal']}</a>{$mklib->lang['bc_sep']}<a href=\"#\">{$mklib->lang['ga_slide']}</a>";
// Link Navigation bar	
 	
		$content .= "
		    <tr>
		      <td bgcolor=\"#ffffff\" align=\"center\">

			<table border=\"0\" width=\"600\" cellspacing=\"0\" cellpadding=\"0\">
			  <tr>
			    <td width=\"100%\"><img src=\"$mklib->images/sl_top.gif\" style=\"vertical-align: bottom\" alt=\"\" /></td>
			  </tr>
			  <tr>
			    <td width=\"100%\">
			      <table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">
				<tr>
				  <td width=\"1%\" style=\"background-image: url('$mklib->images/sl_sx.gif');\"><img src=\"$mklib->images/sl_sx.gif\" width=\"48\" height=\"24\" alt=\"\" /></td>
				  <td width=\"98%\" bgcolor=\"#595e61\" align=\"center\">
				    <iframe src=\"/index.php?ind=gallery&amp;op=slide_update&amp;ide=0&amp;cat=$cat\" frameborder=\"0\" width=\"490\" align=\"middle\" height=\"450\" scrolling=\"auto\"></iframe>
				  </td>
				  <td width=\"1%\" style=\"background-image: url('$mklib->images/sl_dx.gif');\"><img src=\"$mklib->images/sl_dx.gif\" width=\"60\" height=\"20\" alt=\"\" /></td>
				</tr>
			      </table>
			    </td>
			  </tr>
			  <tr>
			    <td width=\"100%\">
			
			      <form action=\"/index.php?ind=gallery&amp;op=slide_start\" name=\"START\" method=\"post\" >
			      <table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">
				<tr>
				  <td width=\"1%\"><img src=\"$mklib->images/sl_bot_sx.gif\" width=\"88\" height=\"78\" style=\"vertical-align: top\" alt=\"\" /></td>
				  <td width=\"98%\" style=\"background-image: url('$mklib->images/sl_bot.gif');\" valign=\"middle\" align=\"left\">&nbsp;&nbsp;&nbsp;
				    <select name=\"cat\" size=\"1\" class=\"bgselect\">
				    $cselect
				    </select>
				    &nbsp;&nbsp;&nbsp;
				    <input type=\"image\" src=\"$mklib->images/sl_play.gif\" align=\"middle\" style=\"border: 0px;\" />
				    &nbsp;&nbsp;
				    <a href=\"/index.php?ind=gallery&amp;op=slide_start\"><img src=\"$mklib->images/sl_stop.gif\" align=\"middle\" border=\"0\" alt=\"\" /></a>
				  </td>
				  <td width=\"1%\" valign=\"top\"><img src=\"$mklib->images/sl_bot_dx.gif\" width=\"98\" height=\"78\" style=\"vertical-align: top\" alt=\"\" /></td>
				</tr>
			      </table>
			      </form>
			    
			    </td>
			  </tr>
			</table>		      
		      </td>
		    </tr>
	";
	$maintit = "{$mklib->lang['ga_slide']}";
	$stat = $this->retrieve_stat();
	$toolbar = "";
	$utonline = $mklib_board->get_active_users("gallery");
	$output  = $this->tpl->gallery_show($navbar, $maintit, $content, $submit, $stat, $toolbar, $show_pages, $utonline);
	$blocks = $Skin->view_block("{$mklib->lang['ga_slide']}", $output);
	$mklib->printpage("1", "1", $mklib->sitename.$mklib->lang['tt_sep'].$mklib->lang['ga_imggal'].$mklib->lang['tt_sep'].$mklib->lang['ga_slide'], $blocks);
	}
	function slide_update() {
 		global $mkportals, $DB, $std, $Skin, $mklib, $mklib_board;

		$ide= intval($mkportals->input['ide']);
		$cat = intval($mkportals->input['cat']);

		if(!$cat) {
			exit;
		}

		$query = $DB->query( "SELECT id, titolo, file FROM mkp_gallery WHERE id > $ide AND evento = $cat AND validate = '1' ORDER BY id LIMIT 1");
		$row = $DB->fetch_row($query);
		$file = $row['file'];
		$id = $row['id'];
		if(!$id) {
			exit;
		}
		$image_details = @getimagesize("$mklib->sitepath/mkportal/modules/gallery/album/$file");
		$imagesize_x = $image_details[0];
		if($imagesize_x > 480) {
			$size = $mklib->ResizeImage(480,"$mklib->sitepath/mkportal/modules/gallery/album/$file");
			$dims = "width=\"488\" height=\"$size[1]\"";
		}
		$output = "
<head>
<meta http-equiv=\"refresh\" content=\"5;url=$mklib->siteurl/index.php?ind=gallery&op=slide_update&ide=$id&cat=$cat\">
</head>
<body bgcolor=\"#595E61\" style=\"margin: 0px\">
  <div align=\"center\">
  <table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" height=\"100%\">
    <tr>
      <td align=\"center\" width=\"100%\" height=\"100%\" valign=\"middle\">
      <a href=\"/index.php?ind=gallery&amp;op=foto_show&amp;ida=$id\" target=\"_top\"><img src=\"/mkportal/modules/gallery/album/$file\" border=\"0\" align=\"center\" $dims alt\"\" /></a>
      <br><span style=\"font-family: verdana; font-size: 12pt; font-weight: bold; color: #ffffff;\">{$row['titolo']}</span>
      </td>
    </tr>
  </table>
  </div>
</body>";
		print $output;
		$DB->close_db();
		exit;

 	}

		function search() {
		global $mkportals, $DB, $mklib, $Skin, $mklib_board;
		$maintit = "{$mklib->lang['ga_searchf']}";
		$cselect.= "<option value='1'>{$mklib->lang['ga_title']}</option>\n";
		$cselect.= "<option value='2'>{$mklib->lang['ga_des']}</option>\n";
		$content .= "
		    <tr>
		      <td>
		      
			<form action=\"/index.php?ind=gallery&amp;op=result_search\" name=\"search\" method=\"post\">
			<table width=\"100%\" border=\"0\">
			  <tr>
			    <td>{$mklib->lang['ga_searchin']}</td>
			    <td>
			      <select class=\"bgselect\" name=\"campo\" size=\"1\">
			      {$cselect}
			      </select>
			    </td>
			  </tr>
			  <tr>
			    <td width=\"20%\">{$mklib->lang['ga_searchtext']}</td>
			    <td width=\"80%\"><input type=\"text\" name=\"testo\" size=\"52\" class=\"bgselect\" /></td>
			  </tr>
			  <tr>
			    <td colspan=\"2\"><input type=\"submit\" value=\"{$mklib->lang['ga_searchstart']}\" class=\"mkbutton\" /></td>
			  </tr>
			</table>
			</form>
			
		      </td>
		    </tr>
		";
// Link Navigation bar
		$navbar = "<a href=\"/index.php?ind=gallery\">{$mklib->lang['ga_mkpgal']}</a>{$mklib->lang['bc_sep']}<a href=\"#\">{$mklib->lang['ga_searchf']}</a>";
// Link Navigation bar		
		$stat = $this->retrieve_stat();
		$toolbar = "";
		$utonline = $mklib_board->get_active_users("gallery");
		$output  = $this->tpl->gallery_show($navbar, $maintit, $content, $submit, $stat, $toolbar, $show_pages, $utonline);
		$blocks = $Skin->view_block("{$mklib->lang['ga_imggal']}", $output);
		$mklib->printpage("1", "1", $mklib->sitename.$mklib->lang['tt_sep'].$mklib->lang['ga_imggal'].$mklib->lang['tt_sep'].$mklib->lang['ga_searchstart'], $blocks);

	}

	function result_search() {
		global $mkportals, $DB, $mklib, $Skin, $mklib_board;
		//$campo = $mkportals->input['campo']; //deprecated
		$testo = $mkportals->input['testo'];
		$campo = "titolo"; //This is a database table row name - Do not translate!
		if (intval($mkportals->input['campo']) == 2) {
			$campo = "descrizione"; //This is a database table row name - Do not translate!
		}
		if (!$testo) {
			$message = "{$mklib->lang['ga_reqstring']}";
			$mklib->error_page($message);
			exit;
		}
// Link Navigation bar
		$navbar = "<a href=\"/index.php?ind=gallery\">{$mklib->lang['ga_mkpgal']}</a>{$mklib->lang['bc_sep']}<a href=\"/index.php?ind=gallery&amp;op=search\">{$mklib->lang['ga_search']}</a>{$mklib->lang['bc_sep']}<a href=\"#\">{$mklib->lang['ga_searchresult']}</a>";
// Link Navigation bar
		$maintit = "{$mklib->lang['ga_searchresult']}";
		//$content = $this->tpl->row_main_category();

		$query = $DB->query( "SELECT id, evento, titolo, descrizione, file, peso FROM mkp_gallery WHERE $campo LIKE '%$testo%' AND validate = '1'");
		$content.= "
		    <tr>
		      <td colspan=\"6\" bgcolor=\"#ffffff\">
			<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">
			  <tr>
			  ";
		$index = 0;
		while( $row = $DB->fetch_row($query) ) {
			$idfoto = $row['id'];
			$evento = $row['evento'];
			$titolo = $row['titolo'];
			$descrizione = $row['descrizione'];
			$file = $row['file'];
			$thumb = "t_$file";
			//$peso = "(".round(($row['peso']/1024),2)." K)";
			$content.= "<td align=\"center\" valign=\"bottom\">";
			$content.= "<table border=\"0\" width=\"100\" cellspacing=\"0\" cellpadding=\"0\">";
  			$content.= "<tr>";
    			$content.= "<td width=\"1%\" style=\"font-size: 4px;\"><img src=\"$mklib->images/a_sx_a.gif\" style=\"vertical-align: bottom\" alt=\"\" /></td>";
    			$content.= "<td width=\"98%\" style=\"background-image: url('$mklib->images/a_sf_s.gif'); font-size: 4px;\"><img src=\"$mklib->images/a_sf_s.gif\" height=\"8\" style=\"vertical-align: bottom\" alt=\"\" /></td>";
    			$content.= "<td width=\"1%\" style=\"font-size: 4px;\"><img src=\"$mklib->images/a_dx_a.gif\" style=\"vertical-align: bottom\" alt=\"\" /></td>";
  			$content.= "</tr><tr>";
    			$content.= "<td width=\"1%\" style=\"background-image: url('$mklib->images/a_sx_s.gif');\"><img src=\"$mklib->images/a_sx_s.gif\" width=\"11\" height=\"15\" alt=\"\" /></td>";
    			$content.= "<td width=\"98%\" bgcolor=\"#ffffff\" align=\"center\">";
			//Thumbnail & title
			if (!file_exists("mkportal/modules/gallery/album/$thumb")) {
  				$thumb_mes = $mklib->ResizeImage($mklib->config['thumb_max_dimen'],"/mkportal/modules/gallery/album/$file");
				$content.= "<a href=\"/index.php?ind=gallery&amp;op=foto_show&amp;ida=$idfoto\"><img src=\"/mkportal/modules/gallery/album/$file\" border=\"0\" width=\"$thumb_mes[0]\" height=\"$thumb_mes[1]\" alt=\"{$mklib->lang['ga_czoom']}\" /></a><br />$titolo<br /> ";
			} else {
				$content.= "<a href=\"/index.php?ind=gallery&amp;op=foto_show&amp;ida=$idfoto\"><img src=\"/mkportal/modules/gallery/album/$thumb\" border=\"0\" alt=\"{$mklib->lang['ga_czoom']}\" /></a><br />$titolo<br /> ";
			}
			$content.= "</td>";
   			$content.= "<td width=\"1%\" style=\"background-image: url('$mklib->images/a_dx_s.gif');\"><img src=\"$mklib->images/a_dx_s.gif\" width=\"11\" height=\"14\" alt=\"\" /></td>";
  			$content.= "</tr><tr>";
    		$content.= "<td width=\"1%\"><img src=\"$mklib->images/a_sx_g.gif\" height=\"22\" style=\"vertical-align: top\" alt=\"\" /></td>";
    		$content.= "<td width=\"98%\" style=\"background-image: url('$mklib->images/a_sf_g.gif');\"></td>";
    		$content.= "<td width=\"1%\" valign=\"top\"><img src=\"$mklib->images/a_dx_g.gif\" height=\"22\" style=\"vertical-align: top\" alt=\"\" /></td>";
  			$content.= "</tr></table>";
			$content.= "</td>";
			++ $index;
			if ($index == 3) {
				$index = 0;
				$content.= "</tr>";
				$content.= "<tr>";
			}

		}
		$content.= "</tr></table>";
		if (!$idfoto) {
			$content = "<td align=\"center\" width=\"100%\" class=\"modulecell\"><br />{$mklib->lang['ga_searchnot']}<br /><br /><br /></td>";
		}
		$content.= "</td></tr>";
		$submit = "";
		$stat = $this->retrieve_stat();
		$toolbar = "";
		$utonline = $mklib_board->get_active_users("gallery");
		$output  = $this->tpl->gallery_show($navbar, $maintit, $content, $submit, $stat, $toolbar, $show_pages, $utonline);
		$blocks = $Skin->view_block("{$mklib->lang['ga_imggal']}", $output);
		$mklib->printpage("1", "1", $mklib->sitename.$mklib->lang['tt_sep'].$mklib->lang['ga_imggal'].$mklib->lang['tt_sep'].$mklib->lang['ga_searchresult'], $blocks);
	}

	function show_emoticons()
 	{
		global $mklib_board;
		$mklib_board->show_emoticons();
 	}
 	
// Link Navigation bar
	function navbar($idevento) {
	global $DB, $mklib;
		while ( $idevento > 0 ) {
			//$query = $DB->query( "SELECT id, father, evento FROM mkp_gallery_events where id = '$idevento' ORDER BY `id`");
        		//$row = $DB->fetch_row($query);
			foreach ($this->cache_sections as $row) {
				if ($row['id'] == $idevento) {
        			$dev = $row['id'];
        			$evento = $row['evento'];
        			$space = "{$mklib->lang['bc_sep']}<a href=\"/index.php?ind=gallery&amp;op=section_view&amp;idev=$dev\">$evento</a>{$space}";
				$idevento = $row['father'];
				}
			}
		}
		if (!$idevento){
		return "<a href=\"/index.php?ind=gallery\">{$mklib->lang['ga_mkpgal']}</a>{$space}";
		}
	}

	function row_select_event() {
			global $mklib;
			
			//$query = $DB->query( "SELECT id, evento, father FROM mkp_download_sections ORDER BY `id`");
			//while( $row = $DB->fetch_row($query) ) {
			$listall = array();
			$children = array();
			if (!$this->cache_sections) {
				return FALSE;
			}
			foreach ($this->cache_sections as $row) {
				if ($row['father']) {
					$children[ $row['father'] ][] = $row;
				}
			}
			
			foreach ($this->cache_sections as $row) {
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
				   			reset ($this->cache_sections);
				   			foreach ($this->cache_sections as $row2) {
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


	
	function row_categories($father, $order, $start, $per_page) {
	global $DB, $mklib;
	
		if($father > 0 ){
		$query = $DB->query( "SELECT id, evento FROM mkp_gallery_events WHERE father = '$father' ORDER BY position");
			if($DB->get_num_rows($query)) {
				$content = $this->tpl->row_main_category();
			}
		} else {
		$query = $DB->query( "SELECT id, evento FROM mkp_gallery_events WHERE father = '$father' $order LIMIT $start, $per_page");
    		}
		while( $row = $DB->fetch_row($query) ) {
			$idevento = $row['id'];
			$evento = $row['evento'];
		
			//$countsub = $DB->get_num_rows($DB->query("SELECT id FROM mkp_gallery_events  WHERE father = '$idevento' ORDER BY `id`"));
			$countsub = 0;
			foreach ($this->cache_sections as $cat) {
				if ($cat['father'] == $idevento) {
					++$countsub;
				}	
			}
			$count = 0;
			foreach ($this->cache_files as $cat) {
				if ($cat['evento'] == $idevento) {
					++$count;
				}	
			}
			
			$foto = array();
			foreach ($this->cache_files as $cat) {
			    if ($cat['evento'] == $idevento) {
			    	$foto['id'] = $cat['id'];
				$foto['titolo'] = $cat['titolo'];
				$foto['file'] = $cat['file'];
			    }
			}
				
				$titolo = "<a href=\"/index.php?ind=gallery&amp;op=foto_show&amp;ida=".$foto['id']."\">".$foto['titolo']."</a>";
				$file = $foto['file'];
				$thumb = "t_$file";
				if($count == 0) {
					$thumb = "a_no_image.gif";
				}
				if (!file_exists("/mkportal/modules/gallery/album/$thumb")) {
  					$thumb_mes = $mklib->ResizeImage($mklib->config['thumb_max_dimen'],"/mkportal/modules/gallery/album/$file");
					$image = "<a href=\"/index.php?ind=gallery&amp;op=section_view&amp;idev=$idevento\"><img src=\"/mkportal/modules/gallery/album/$file\" border=\"0\" width=\"$thumb_mes[0]\" height=\"$thumb_mes[1]\" alt=\"\" /></a>";
				} else {
					$image = "<a href=\"/index.php?ind=gallery&amp;op=section_view&amp;idev=$idevento\"><img src=\"/mkportal/modules/gallery/album/$thumb\" border=\"0\" alt=\"\" /></a>";
				}
				$name = "<a href=\"/index.php?ind=gallery&amp;op=section_view&amp;idev=$idevento\">$evento</a>";
				$content .= $this->tpl->row_main_category_content($name, $image, $descrizione, $count, $titolo, $countsub);
		}
	 	if(!$idevento) {
			return false;
		}
		return $content;
	}
	

	function update_total() {
		global $DB;
		$query = $DB->query( "SELECT id FROM mkp_gallery WHERE validate = '1'");
		$count = $DB->get_num_rows($query);
		$DB->query("UPDATE mkp_stat SET valore ='$count' WHERE chiave = 'tot_gallery'");
	
	}
	
}
?>
