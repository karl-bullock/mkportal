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

$idx = new mk_review;
class mk_review {

	var $tpl       = "";

	function mk_review() {

		global $mkportals, $mklib,  $Skin, $DB, $mklib_board;

		$mklib->load_lang("lang_review.php");

		if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_access_reviews']) {
			$message = "{$mklib->lang['re_unauth']}";
			$mklib->error_page($message);
			exit;
		}

		if ($mklib->config['mod_reviews']) {
		$message = "{$mklib->lang['re_mnoactive']}";
			$mklib->error_page($message);
			exit;
		}

		//location
		$mklib_board->store_location("reviews");

		require "mkportal/modules/reviews/tpl_reviews.php";
		$this->tpl = new tpl_reviews();

    		switch($mkportals->input['op']) {
    			case 'section_view':
    				$this->section_view();
    			break;
			case 'submit_file':
    				$this->submit_file();
    			break;
			case 'submit_file1':
    				$this->submit_file1();
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
    				$this->reviews_show();
    			break;
    			case 'cat':
    				$this->cat();
    			break;
    		  case 'ajax_comment':
    			$this->ajax_comment();
    		  break;
    		}
	}

	function reviews_show() {
    global $mkportals, $DB, $mklib, $Skin, $mklib_board;
    $modname ="reviews";
	$navbar = "<a href=\"/index.php?ind=reviews\">{$mklib->lang['re_ptitle2']}</a>";
	$maintit = "{$mklib->lang['re_ptitle']}";
	$content = $this->tpl->row_main_category();

	$query = $DB->query( "SELECT id, module, title, description, img, parentid, topics FROM mkp_categories WHERE module='$modname' AND parentid ='0' ORDER BY `id`");

	while( $row = $DB->fetch_row($query) ) {
		$idevento = $row['id'];
		$count = $row['topics'];
		$evento = $row['title'];
		$descrizione = $row['description'];
		$img = $row['img'];
		
		$name ="<a href=\"/index.php?ind=reviews&amp;op=cat&amp;idc=$idevento\">$evento</a>";
		
		$link = "<a href=\"/index.php?ind=reviews&amp;op=cat&amp;idc=$idevento\"><img src=\"{$mklib->images}/categories/".$img."\" border=\"0\" alt=\"\" /></a>";
		
		$content .= $this->tpl->row_main_category_content($name, $descrizione, $count, $lastentry, $link);
	}
	$submit = " <a href=\"/index.php?ind=reviews&amp;op=submit_file\">[ {$mklib->lang['re_send']} ]</a> ";
	if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_send_reviews']) {
			$submit ="";
	}
	$stat = $this->retrieve_stat();

	$utonline = $mklib_board->get_active_users("reviews");
	$output  = $this->tpl->review($navbar, $maintit, $content, $submit, $stat, $toolbar, $show_pages, $utonline);
	$blocks = $Skin->view_block("{$mklib->lang['re_pagetitle']}", $output);
	$mklib->printpage("1", "1", $mklib->sitename.$mklib->lang['tt_sep'].$mklib->lang['re_pagetitle'], $blocks);
	}
function cat() {
 global $mkportals, $DB, $mklib, $Skin, $mklib_board;
    $idc = intval($mkportals->input['idc']);
    $modname ="reviews";
	$navbar = "<a href=\"/index.php?ind=reviews\">{$mklib->lang['re_ptitle2']}</a>";
	
	$maintit = "{$mklib->lang['re_ptitle']}";
	$content = $this->tpl->row_main_category();
	$query = $DB->query( "SELECT id, module, title, description, img, parentid, topics FROM mkp_categories WHERE module='$modname' AND parentid ='$idc' ORDER BY `id`");

	while( $row = $DB->fetch_row($query) ) {
		$idevento = $row['id'];
		$count = $row['topics'];
		$lastentry = "<a href=\"/index.php?ind=reviews&amp;op=entry_view&amp;iden={$entry['id']}\">".$entry['title']."</a>";
		$evento = $row['title'];
		$descrizione = $row['description'];
		$img = $row['img'];
		
		$name ="<a href=\"/index.php?ind=reviews&amp;op=cat&amp;idc=$idevento\">$evento</a>";
		
		$link = "<a href=\"/index.php?ind=reviews&amp;op=cat&amp;idc=$idevento\"><img src=\"{$mklib->images}/categories/".$img."\" border=\"0\" alt=\"\" /></a>";
		
		$content .= $this->tpl->row_main_category_content($name, $descrizione, $count, $lastentry, $link);
	}
	$submit = " <a href=\"/index.php?ind=reviews&amp;op=submit_file\">[ {$mklib->lang['re_send']} ]</a> ";
	if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_send_reviews']) {
			$submit ="";
	}
//
$query = $DB->query( "SELECT id FROM mkp_reviews WHERE id_cat = '$idc' AND validate = '1'");
		$countpage = $DB->get_num_rows($query);

		if($countpage) {
			//$content .= $this->tpl->row_main_entries();
		}
        
		$per_page = $mklib->config['rev_file_page'];
		if ($per_page=="" or $per_page <= 0) {
			$per_page	=	10;
		}

		$start = intval($mkportals->input['start']);
		$q_page		=	intval($mkportals->input['st']);
		if ($q_page=="" or $q_page <= 0) {
			$q_page	=	0;
		}
		$start = $q_page;
		$show_pages = $mklib->build_pages( array( TOTAL_POSS  => $countpage,
							PER_PAGE    => $per_page,
							CUR_ST_VAL  => $q_page,
							L_SINGLE    => '',
							L_MULTI     => 'pagine',
						    BASE_URL    => '/index.php?ind=reviews&amp;op=cat&amp;idc='.$idc.'&amp;order='.intval($mkportals->input['order']),
										  )
		);
		$query = $DB->query( "SELECT id, id_cat, title, description, click, date, trate FROM mkp_reviews WHERE id_cat = '$idc' AND validate = '1' ORDER BY `id` DESC LIMIT $start, $per_page");
		while( $row = $DB->fetch_row($query) ) {
			$iden = $row['id'];
			$name = $row['title'];
			$trate1 = $row['trate'];
			$description1 = $row['description'];
			$click1 = $row['click'];
			$data1 = $mklib->create_date($row['date'], "short");
			$idcat = $row['id_cat'];
		   $name1 ="<a href='/index.php?ind=reviews&amp;op=entry_view&amp;iden=$iden'>$name</a>";
		   
			$content5 .= "
			<tr>
                            <td class=\"modulecell\" width=\"40\" align=\"center\"><img src=\"$mklib->images/entry.gif\" alt=\"\" /></td>
                            <td class=\"modulecell\" width=\"*\" align=\"left\">{$name1}<br />{$description1}</td>
			                <td class=\"modulecell\" width=\"75\" align=\"center\">{$trate1}</td>
                            <td class=\"modulecell\" width=\"75\" align=\"center\">{$click1}</td>
                            <td class=\"modulecell\" width=\"150\" align=\"center\">{$data1}</td>
			</tr>";
		}
		$submit = " <a href=\"/index.php?ind=reviews&amp;op=submit_file\">[ {$mklib->lang['re_send']} ]</a> ";
		if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_send_reviews']) {
			$submit ="";
		}

	$utonline = $mklib_board->get_active_users("reviews");
	$output  = $this->tpl->cat($navbar, $maintit, $content, $submit, $stat, $toolbar, $show_pages, $utonline, $content5);
	$blocks = $Skin->view_block("{$mklib->lang['re_pagetitle']}", $output);
	$mklib->printpage("1", "1", $mklib->lang['re_pagetitle'].$mklib->lang['tt_sep'].$mklib->sitename, $blocks);	
}

	function entry_view() {
		global $mkportals, $DB, $mklib, $Skin, $mklib_board;

		$iden = intval($mkportals->input['iden']);
		$link_user = $mklib_board->forum_link("profile");

		$query = $DB->query( "SELECT id_cat, title, description, field1, field2, field3, field4, field5, field6, field7, image, review, author, idauth, click, rate, trate, descr, keywords FROM mkp_reviews WHERE id = '$iden' AND validate = '1'");
		$row = $DB->fetch_row($query);
		if(!$row) {
            		$message = "{$mklib->lang['error_404']}";
            		$mklib->error_page($message);
            		exit;
		}
		$click = $row['click'];
		$idcategoria = $row['id_cat'];
		$name = $row['title'];
		$description = $row['description'];
		$trate = $row['trate'];
		$rate = $row['rate'];
		$autore = $row['author'];
		$idauth = $row['idauth'];
		$descr = stripslashes($row['descr']);
		$keywords = stripslashes($row['keywords']);
		$image = $row['image'];
		$review = $row['review'];
		$review = stripslashes($review);
		if ($mklib->mkeditor == "BBCODE") {
			$review = $mklib->decode_bb($review);
			$review = $mklib_board->decode_smilies($review);
		}
		//$even = $this->retrieve_father($idcategoria);
        $id = $iden; 
   		$modname ='reviews';
        $rating = $mklib->pullRating($id, $modname, $rate, $trate);
		$editorscript = "";
			$textarepar = "";
			$textarew = "75%";
			$bbeditor= $mklib->get_commentbbeditor();
			$captcha = $mklib->antibot_start();
			$bbcomnt ="<form id =\"editor\" name=\"editor\" action=\"javascript:sendcomentreviews();\" method=\"post\" >
				<table class=\"modulecell\" width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"8\" >		
				  <tr>
        	
				    <td class=\"modulecell\" rowspan=\"3\" align=\"center\" height=\"100%\">
				      <input type=\"hidden\" name=\"ide\" value=\"$iden\" />
		
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
				      <input type=\"submit\" name=\"submit\" value=\"{$mklib->lang['re_sendcomm']}\" class=\"button2\" accesskey=\"s\" /><br />
				    </td>
				  </tr>		
				</table>
				</form>";

		if ($image) {

			//Resize image
			$image_details = @getimagesize("mkportal/modules/reviews/images/$image");
			$dimensioni = "$image_details[0] x $image_details[1]";
			$width = round(($rate*100)/4) - 6;
	 		$width2 = $width - 4;

			$imagesize_x = $image_details[0];
			if($imagesize_x > 600) {
				$size = $mklib->ResizeImage(600,"$mklib->sitepath/mkportal/modules/reviews/images/$image");
				$dims = "width=\"600\" height=\"$size[1]\"";
			}

			$image = "
			<tr>
			<td class=\"modulecell\" align=\"center\" colspan=\"3\"><a href=\"/mkportal/modules/reviews/images/$image\" target=\"_blank\"><img src=\"/mkportal/modules/reviews/images/$image\" border=\"0\" $dims alt=\"zoom\" /></a></td>
			</tr>
			";
		}
	 	$content .= $this->tpl->row_entry($iden, $name, $description, $trate, $rate, $width2, $width, $autore, $image, $field1, $field2, $field3, $field4, $field5, $field6, $field7, $review, $rating);

	   $query1 = $DB->query( "SELECT id, cid, module, data, memid, name, memip, comment, status FROM mkp_comments WHERE cid = '$iden' AND module = 'reviews'  ORDER BY `id` DESC");
		while( $row = $DB->fetch_row($query1) ) {
			$idcomm = $row['id'];
			$autorec = $row['name'];
			$testo = $row['comment'];
			$testo = $mklib->decode_bb($testo);
			$data = $mklib->create_date($row['data'], "short");
			$id_autore = $row['memid'];
			$delete = "
			<script type=\"text/javascript\">

			function makesure() {
			if (confirm('{$mklib->lang[re_delcommconf]}')) {
			return true;
			} else {
			return false;
			}
			}

			</script><a href=\"/index.php?ind=reviews&amp;op=del_comment&amp;idcomm=$idcomm&amp;iden=$iden\" onclick=\"return makesure()\">[ {$mklib->lang['re_delete']} ]</a>
			";

			if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_mod_reviews']) {
				$delete = "";
			}
			$content1 .= "
			
			<tr>
                          <td class=\"modulecell\" width=\"10%\" valign=\"top\"><a href=\"$link_user={$id_autore}\" class=\"uno\">{$autorec}</a><br />{$data}<br />{$delete}</td>
                          <td class=\"modulecell\" width=\"90%\" valign=\"middle\">{$testo}</td>
			</tr>
		
			";
		}
		
			$content .= "
		<tr>
		  <td class=\"tdblock\" colspan=\"3\">
		  {$mklib->lang['re_comments']}
		  </td>
		</tr>
		";
		$content2 .= "
		 <tr>
		      <td id=\"commentsr\">
			<table class=\"moduleborder\" width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"4\">
	
			{$content1}
	
			</table>
	$bbcomnt
		      </td>
		    </tr>";
		++$click;
		$DB->query("UPDATE mkp_reviews SET click ='$click' WHERE id = '$iden'");
		$navbar = "<a href=\"/index.php?ind=reviews\">{$mklib->lang['re_ptitle2']}</a>{$mklib->lang['bc_sep']}<a href=\"/index.php?ind=reviews&amp;op=cat&amp;idc=$idcategoria\">$even</a>{$mklib->lang['bc_sep']}<a href=\"#\">$name</a>";
		
		$maintit = $name;
		$submit = "<script type=\"text/javascript\">
			function makesure2() {
			if (confirm('{$mklib->lang[re_delfileconf]}')) {
			return true;
			} else {
			return false;
			}
			}
			</script><a href=\"/index.php?ind=reviews&amp;op=edit_file&amp;iden=$iden\">[ {$mklib->lang['re_edit']} ]</a>  <a href=\"/index.php?ind=reviews&amp;op=del_file&amp;iden=$iden\" onclick=\"return makesure2()\">[ {$mklib->lang['re_delete']} ]</a> ";

		if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_mod_reviews'] && (!$mklib->member['g_send_reviews'] || $mkportals->member['id'] != $idauth || $idauth == 0 )) {
			$submit = "";
		}

		$toolbar = "";
		$utonline = $mklib_board->get_active_users("reviews");
		$output  = $this->tpl->review_show($navbar, $maintit, $content, $content2, $submit, $stat, $toolbar, $show_pages, $utonline);
		$blocks = $Skin->view_block("{$mklib->lang['re_pagetitle']}", $output);
		$mklib->printpage("1", "1", $name.$mklib->lang['tt_sep'].$mklib->lang['re_pagetitle'].$mklib->lang['tt_sep'].$mklib->sitename, $blocks, $descr, $keywords);
	}

	function submit_file() {
		global $mkportals, $DB, $mklib, $Skin, $mklib_board;

		if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_send_reviews']) {
			$message = "{$mklib->lang['re_nosend']}";
			$mklib->error_page($message);
			exit;
		}

		$navbar = "<a href=\"/index.php?ind=reviews\">{$mklib->lang['re_ptitle2']}</a>{$mklib->lang['bc_sep']}<a href=\"#\">{$mklib->lang['re_send']}</a>";
		$maintit = "{$mklib->lang['re_ptitle']}";

		$cselect = $this->row_select_event();
		if ($cselect == FALSE) {
			$message = "{$mklib->lang['re_nocat']}";
			$mklib->error_page($message);
			exit;
		}
		$content .= "
		<tr>
		  <td class=\"modulex\">
		  
		    <form action=\"/index.php?ind=reviews&amp;op=submit_file1\"  method=\"post\">
		      <table width=\"100%\" border=\"0\">
			<tr>
			  <td class=\"titadmin\">{$mklib->lang['re_send']}</td>
			</tr>
			<tr>
			  <td width=\"15\">{$mklib->lang['re_select']}</td>
			</tr>
			<tr>
			<td>
			  <select class=\"bgselect\" name=\"evento\" size=\"1\">
			  {$cselect}
			  </select>
			</td>
		      </tr>
		      <tr>
			<td><br /></td>
		      </tr>
		      <tr>
			<td>
			  <input type=\"submit\" value=\"{$mklib->lang['re_go']}\" class=\"mkbutton\" />
			</td>
		      </tr>
		    </table>
		    </form>
		    
		  </td>
		</tr>
		";

		$submit = " <a href=\"/index.php?ind=reviews&amp;op=submit_file\">[ {$mklib->lang['re_send']} ]</a> ";
		$stat = $this->retrieve_stat();
		$toolbar = "";
		$utonline = $mklib_board->get_active_users("reviews");
		$output  = $this->tpl->review_show($navbar, $maintit, $content, $content2, $submit, $stat, $toolbar, $show_pages, $utonline);
		$blocks = $Skin->view_block("{$mklib->lang['re_ptitle']}", $output);
		$mklib->printpage("1", "1", $mklib->sitename.$mklib->lang['tt_sep'].$mklib->lang['re_pagetitle'].$mklib->lang['tt_sep'].$mklib->lang['re_send'], $blocks);
	}

	function submit_file1() {
		global $mkportals, $DB, $mklib, $Skin, $mklib_board, $editorscript;

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
		if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_send_reviews']) {
			$message = "{$mklib->lang['re_nosend']}";
			$mklib->error_page($message);
			exit;
		}
		$idev = intval($mkportals->input['evento']);
		if (!$idev) {
			$message = "{$mklib->lang['re_nocat']}";
			$mklib->error_page($message);
			exit;
		}
		$navbar = "<a href=\"/index.php?ind=reviews\">{$mklib->lang['re_ptitle2']}</a>{$mklib->lang['bc_sep']}<a href=\"#\">{$mklib->lang['re_send']}</a>";
		$maintit = "{$mklib->lang['re_ptitle']}";

		/*$query = $DB->query( "SELECT field1, field2, field3, field4, field5, field6, field7 FROM mkp_reviews_sections WHERE id = '$idev'");
		$row = $DB->fetch_row($query);*/
		$content = "
		<tr>
		  <td class=\"modulex\">
		    <form action=\"/index.php?ind=reviews&amp;op=add_file\" name=\"editor\" method=\"post\" enctype=\"multipart/form-data\">
		    <table width=\"100%\" border=\"0\">
		      <tr>
			<td class=\"titadmin\" colspan=\"2\">{$mklib->lang['re_send']}<br />&nbsp;
			  <input type=\"hidden\" value=\"$idev\" name=\"evento\" />
			</td>	   
		      </tr>
		      <tr>
			<td width=\"5%\">{$mklib->lang['re_title']}</td>
			<td width=\"95%\">
			  <input type=\"text\" name=\"titolo\" size=\"52\" class=\"bgselect\" />
			</td>
		      </tr>
		      <tr>
			<td width=\"5%\" valign=\"top\">{$mklib->lang['re_description']}</td>
			<td width=\"95%\">
			  <input type=\"text\" name=\"descrizione\" size=\"52\" class=\"bgselect\" />
			</td>
		      </tr>
		";
		if ($row['field1']) {
			$content .= "
		      <tr>
			<td width=\"5%\">{$row['field1']}</td>
			<td width=\"95%\">
			  <input type=\"text\" name=\"field1\" size=\"52\" class=\"bgselect\" />
			</td>
		      </tr>
		      ";
		}
		if ($row['field2']) {
			$content .= "
		      <tr>
			<td width=\"5%\">{$row['field2']}</td>
			<td width=\"95%\">
			  <input type=\"text\" name=\"field2\" size=\"52\" class=\"bgselect\" />
			</td>
		      </tr>
		      ";
		}
		if ($row['field3']) {
			$content .= "
		      <tr>
			<td width=\"5%\">{$row['field3']}</td>
			<td width=\"95%\">
			  <input type=\"text\" name=\"field3\" size=\"52\" class=\"bgselect\" />
			</td>
		      </tr>
		      ";
		}
		if ($row['field4']) {
			$content .= "
		      <tr>
			<td width=\"5%\">{$row['field4']}</td>
			<td width=\"95%\">
			  <input type=\"text\" name=\"field4\" size=\"52\" class=\"bgselect\" />
			</td>
		      </tr>
		      ";
		}
		if ($row['field5']) {
			$content .= "
		      <tr>
			<td width=\"5%\">{$row['field5']}</td>
			<td width=\"95%\">
			  <input type=\"text\" name=\"field5\" size=\"52\" class=\"bgselect\" />
			</td>
		      </tr>
		      ";
		}
		if ($row['field6']) {
			$content .= "
		      <tr>
			<td width=\"5%\">{$row['field6']}</td>
			<td width=\"95%\">
			  <input type=\"text\" name=\"field6\" size=\"52\" class=\"bgselect\" />
			</td>
		      </tr>
		      ";
		}
		if ($row['field7']) {
			$content .= "
		      <tr>
			<td width=\"5%\">{$row['field7']}</td>
			<td width=\"95%\">
			  <textarea cols=\"50\" rows=\"3\" name=\"field7\" class=\"bgselect mceNoEditor\"></textarea>
			</td>
		      </tr>
			";
		}
		$content .= "
		      <tr>
			<td><br /></td>
		      </tr>
		      <tr>
			<td colspan=\"2\" valign=\"top\">{$mklib->lang['re_review']}</td>
			</tr>
			<tr>
			<td width=\"100%\" colspan=\"2\">
			$bbeditor
 			<textarea id=\"ta\" name=\"ta\" $textarepar style=\"width: $textarew\" rows=\"14\" cols=\"40\"></textarea>
			</td>
		      </tr>
		      <tr>
			<td width=\"5%\">{$mklib->lang['re_image']}</td>
			<td width=\"95%\"><input type=\"file\" name=\"FILE_UPLOAD\" size=\"50\" class=\"bgselect\" /></td>
		      </tr>
		 <tr>
			<td width=\"5%\">{$mklib->lang['meta_key']}</td>
			<td width=\"95%\">
			  <input type=\"keywords\" name=\"keywords\"  size=\"40\" />{$mklib->lang['meta_key_des']}
			</td>
		<tr>
			<td width=\"5%\">{$mklib->lang['meta_descr']}</td>
			<td width=\"95%\">
			  <input type=\"descr\" name=\"descr\"  size=\"40\" />{$mklib->lang['meta_descr_des']}
			</td>
		      <tr>
			<td><br /></td>
		      </tr>
		      <tr>
			<td width=\"5%\"></td>
			<td width=\"95%\" class=\"mkalign1\">
			  <input type=\"submit\" value=\"{$mklib->lang['re_insert']}\" class=\"mkbutton\" />
			</td>
		      </tr>
		    </table>
		    </form>
		  </td>
		</tr>
		";
		$submit = " <a href=\"/index.php?ind=reviews&amp;op=submit_file\">[ {$mklib->lang['re_send']} ]</a> ";
		$stat = $this->retrieve_stat();
		$toolbar = "";
		$utonline = $mklib_board->get_active_users("reviews");
		$output  = $this->tpl->review_show($navbar, $maintit, $content, $content2, $submit, $stat, $toolbar, $show_pages, $utonline);
		$blocks = $Skin->view_block("{$mklib->lang['re_ptitle']}", $output);
		$mklib->printpage("1", "1", $mklib->sitename.$mklib->lang['tt_sep'].$mklib->lang['re_pagetitle'].$mklib->lang['tt_sep'].$mklib->lang['re_send'], $blocks);
	}

	function edit_file() {
		global $mkportals, $DB, $mklib, $Skin, $mklib_board, $editorscript;

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
		$maintit = "{$mklib->lang['re_ptitle']}";
		$iden = intval($mkportals->input['iden']);

		$query = $DB->query( "SELECT id_cat, title, description, review, author, idauth, descr, keywords FROM mkp_reviews WHERE id = '$iden'");
		$row = $DB->fetch_row($query);
		if(!$row) {
            		$message = "{$mklib->lang['error_404']}";
            		$mklib->error_page($message);
            		exit;
		}
	   $idcategoria = $row['id_cat'];
	   $name = $row['title'];
		$description = $row['description'];
		$description = str_replace("<br />", "\n", $description);
		$review = $row['review'];
		$review = stripslashes($review);
		$descr = stripslashes($row['descr']);
		$keywords = stripslashes($row['keywords']);
		$modname ='reviews';
		$query1 = $DB->query( "SELECT id, title, parentid FROM mkp_categories WHERE module='$modname' AND id = '$idcategoria' ");
		while( $row = $DB->fetch_row($query1) ) {
			$idevento = $row['id'];
			$evento = $row['title'];
			$parentid = $row['parentid'];
			$cselect .= "<option value=\"$idevento\">$evento</option>\n";
		}
		
		$sql = "SELECT id, title, parentid FROM mkp_categories WHERE module='$modname' ORDER BY id";
	    $result = $DB->query($sql);
		while ($row = $DB->fetch_row($result)) {
			$cid2 = $row[id];
			$title = $row[title];
			$parentid2 = $row[parentid];
			if ($parentid2!=0) $title=$mklib->getcategor($parentid2,$title,$modname);
			$cselect .="<option value=\"$cid2\">$title</option>";
		
	}

		if ($mklib->mkeditor == "BBCODE") {
			$review = str_replace("<br />", "\n", $review);
		} else {
			$review = preg_replace("/(?<=\>)<br \/>(?=\<)/" , "\n", $review);
		}

		$row7 = $row['field7'];
		$row7 = str_replace("<br />", "\n", $row7);
		$autore = $row['author'];
		$idauth = $row['idauth'];
		$nav_ev = $this->retrieve_event($idcategoria);

		/*$query1 = $DB->query( "SELECT field1, field2, field3, field4, field5, field6, field7 FROM mkp_reviews_sections WHERE id = '$idcategoria'");
		$rowt = $DB->fetch_row($query1);
*/
		if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_mod_reviews'] && (!$mklib->member['g_send_reviews'] || $mkportals->member['id'] != $idauth || $idauth == 0 )) {
			$message = "{$mklib->lang['re_noedit']}";
			$mklib->error_page($message);
			exit;
		}
		$content = "
		<tr>
		  <td class=\"modulex\">
		  
		    <form action=\"/index.php?ind=reviews&amp;op=update_file&amp;iden=$iden\" name=\"editor\" method=\"post\">
		    <table width=\"100%\" border=\"0\">
		      <tr>
			<td class=\"titadmin\" colspan=\"2\">{$mklib->lang['re_editf']}</td>
		      </tr>
		      <tr>
			<td>{$mklib->lang['re_section']}</td>
			<td>
			  <select class=\"bgselect\" name=\"evento\" size=\"1\">
			  {$cselect}
			  </select>
			</td>
		      </tr>
		      <tr>
			<td width=\"10%\">{$mklib->lang['re_title']}</td>
			<td width=\"90%\">
			  <input type=\"text\" name=\"titolo\" value=\"$name\" size=\"52\" class=\"bgselect\" />
			</td>
		      </tr>
		      <tr>
			<td width=\"10%\" valign=\"top\">{$mklib->lang['re_description']}</td>
			<td width=\"90%\">
			  <textarea cols=\"50\" rows=\"3\" name=\"descrizione\" class=\"bgselect mceNoEditor\">$description</textarea>
			</td>
		      </tr>
		";

		$content .= "
		      <tr>
			<td><br /></td>
		      </tr>
		      <tr>
			<td colspan=\"2\">{$mklib->lang['re_review']}</td>
			</tr>
			<tr>
			<td width=\"100%\" colspan=\"2\">
			$bbeditor
 			<textarea id=\"ta\" name=\"ta\" $textarepar style=\"width: $textarew\" rows=\"14\" cols=\"40\">$review</textarea>
			</td>
		      </tr>
		 <tr>
			<td width=\"10%\">{$mklib->lang['meta_key']}</td>
			<td width=\"90%\">
			  <input type=\"keywords\" name=\"keywords\" value=\"$keywords\"  size=\"40\" />{$mklib->lang['meta_key_des']}
			</td>
		<tr>
			<td width=\"10%\">{$mklib->lang['meta_descr']}</td>
			<td width=\"90%\">
			  <input type=\"descr\" name=\"descr\" value=\"$descr\"  size=\"40\" />{$mklib->lang['meta_descr_des']}
			</td>
		      <tr>
			<td><br /></td>
		      </tr>
		      <tr>
			<td width=\"5%\"></td>
			<td width=\"95%\" class=\"mkalign1\"><input type=\"submit\" value=\"{$mklib->lang['re_save']}\" class=\"mkbutton\" /></td>
		      </tr>
		    </table>
		    </form>
		
		  </td>
		</tr>
		";
		//$submit = " <a href=\"/index.php?ind=reviews&amp;op=del_file&amp;iden=$iden\">[ Elimina ]</a> ";
		$navbar = "<a href=\"/index.php?ind=reviews\">{$mklib->lang['re_ptitle2']}</a>{$mklib->lang['bc_sep']}<a href=\"/index.php?ind=reviews&amp;op=cat&amp;idc=$idcategoria\">$nav_ev</a>{$mklib->lang['bc_sep']}<a href=\"/index.php?ind=reviews&amp;op=entry_view&amp;iden=$iden\">$name</a>{$mklib->lang['bc_sep']}<a href=\"#\">{$mklib->lang['re_editf']}</a>";
		//$stat = $this->retrieve_stat();
		$toolbar = "";
		$utonline = $mklib_board->get_active_users("reviews");
		$output  = $this->tpl->review_show($navbar, $maintit, $content, $content2, $submit, $stat, $toolbar, $pages, $utonline);
		$blocks = $Skin->view_block("{$mklib->lang['re_pagetitle']}", $output);
		$mklib->printpage("1", "1", $mklib->sitename.$mklib->lang['tt_sep'].$mklib->lang['re_pagetitle'].$mklib->lang['tt_sep'].$name.$mklib->lang['tt_sep'].$mklib->lang['re_editf'], $blocks);
	}


	function add_file() {

    	global $mkportals, $DB,  $_FILES, $mklib, $mklib_board;
	
		if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_send_reviews']) {
			$message = "{$mklib->lang['re_nosend']}";
			$mklib->error_page($message);
			exit;
		}

		$evento = intval($mkportals->input['evento']);
		$title = $mkportals->input['titolo'];
		$description = $mkportals->input['descrizione'];
		$field1 = $mkportals->input['field1'];
		$field2 = $mkportals->input['field2'];
		$field3 = $mkportals->input['field3'];
		$field4 = $mkportals->input['field4'];
		$field5 = $mkportals->input['field5'];
		$field6 = $mkportals->input['field6'];
		$field7 = $mkportals->input['field7'];
		$review =  $mkportals->input['ta'];
		$review = $mklib->convert_savedb($review);
		//$review = addslashes($review);
		$FILE_UPLOAD = $mkportals->input['FILE_UPLOAD'];
		$file =  $_FILES['FILE_UPLOAD']['tmp_name'];
		$file_name =  $_FILES['FILE_UPLOAD']['name'];
		//$file_type =  $_FILES['FILE_UPLOAD']['type'];
		$keywords = $mkportals->input['keywords'];
		$keywords = $mklib->convert_savedb($keywords);
		$descr = $mkportals->input['descr'];
		$descr = $mklib->convert_savedb($descr);
		$peso =  $_FILES['FILE_UPLOAD']['size'];
		$author = $mkportals->member['name'];
		$idauth = $mkportals->member['id'];

		if (!$evento || !$title || !$description) {
			$message = "{$mklib->lang['re_reqtcd']}";
			$mklib->error_page($message);
			exit;
		}

		if ($file  && $file_name &&  $file_name != 'none' && $peso>0) {
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
				$message = "{$mklib->lang['re_notsup']}";
				$mklib->error_page($message);
				exit;
			}

			//Validate file size
			if ($mklib->config['upload_file_max'] > 0 && $peso > ($mklib->config['upload_file_max']*1024)) {
				$message = "{$mklib->lang['re_maxupl']}";
				$mklib->error_page($message);
				exit;
			}

			//Move file from server tmp directory to reviews "tmp" directory			
			if (!move_uploaded_file("$file", "mkportal/modules/reviews/images/tmp/$file_name")) {
				$message = "{$mklib->lang['re_errorupl']}";
				$mklib->error_page($message);
				exit;
			}
			@chmod("mkportal/modules/reviews/images/tmp/$file_name", 0644);

			//Validate by mime type
			$tmpfilename = "mkportal/modules/reviews/images/tmp/$file_name";
			$size = @getimagesize($tmpfilename);
			//If getimagesize does not recognize file as an image delete file
			if (!$size)  {
				@unlink($tmpfilename);
				$message .= "{$mklib->lang['error_filetype']}";
				$mklib->error_page($message);
				exit;
			}
			$file_type = $size['mime'];

			if (!$mklib->check_attach($file_type, $file_ext))  {
				//Delete invalid file and display error
				@unlink($tmpfilename);
				$message .= "{$mklib->lang['error_filetype']}";
				$mklib->error_page($message);
				exit;
			}	

			//Validate by file contents
			$fcontents = file_get_contents ($tmpfilename);
			$carray = array("html", "javascript", "vbscript", "alert", "onmouseover", "onclick", "onload", "onsubmit");		
			foreach ($carray as $fch) {
       			if (stristr($fcontents, $fch)) {
           			@unlink($tmpfilename);
					$message .= "{$mklib->lang['error_filetype']}";
					$mklib->error_page($message);
                			exit;
            			}
        		}
	        	if (preg_match("#script(.+?)/script#ies", $fcontents) || preg_match( "#<script|<html|<head|<title|<body|<pre|<table|<a\s+href|<img|<plaintext#si", $fcontents )) {
           			@unlink($tmpfilename);
				$message .= "{$mklib->lang['error_filetype']}";
				$mklib->error_page($message);
            			exit;
        		}

			$query = $DB->query("SELECT id FROM mkp_reviews ORDER BY id DESC LIMIT 1");
			$row = $DB->fetch_row($query);
			$totr = $row['id'];
			++$totr;
            
			//Edited by Kimi in C1.2.2
			$image = "a_"."$totr"."$ext";

			//move file from "tmp" directory to "album" directory
			@rename($tmpfilename, "mkportal/modules/reviews/images/$image");
		}

        $cdata = time();

		$validat = "1";
		$approval = $mklib->config['approval_review'];
		if ($approval == "2" || $approval == "3") {
			$validat = 0;
		}
		if($mkportals->member['g_access_cp']) {
			$validat = "1";
		}
		$query="INSERT INTO mkp_reviews(id_cat, title, description, field1, field2, field3, field4, field5, field6, field7, image, review, author, idauth, date, validate, descr, keywords)VALUES('$evento', '$title', '$description', '$field1', '$field2', '$field3', '$field4', '$field5', '$field6', '$field7', '$image', '$review', '$author', '$idauth', '$cdata', '$validat', '$descr', '$keywords')";
		$DB->query($query);

		if ($approval == "1") {
			$mailsubj = $mklib->lang['01mail'].$mklib->lang['reviews'];
			$mailmess = $mklib->lang['02mail'].$mklib->lang['module'].$mklib->lang['reviews']."\n".$mklib->lang['sender'].$author."\n\n\n".$mklib->lang['from']." ".$mklib->sitename;
			$mklib_board->admin_mail($mailsubj, $mailmess);
		}
		if ($approval == "2" && !$mkportals->member['g_access_cp']) {
			$mailsubj = $mklib->lang['01mail'].$mklib->lang['reviews'];
			$mailmess = $mklib->lang['03mail'].$mklib->lang['module'].$mklib->lang['reviews']."\n".$mklib->lang['sender'].$author."\n\n\n".$mklib->lang['from']." ".$mklib->sitename;
			$mklib_board->admin_mail($mailsubj, $mailmess);
			$mklib->message_page ($mklib->lang['notify_adv']);
			exit;
		}
		if ($approval == "3" && !$mkportals->member['g_access_cp']) {
			$mklib->message_page ($mklib->lang['notify_adv']);
			exit;
		} 	
		$query = $DB->query("SELECT topics FROM mkp_categories WHERE id = '$evento' AND module= 'reviews'");
			$row = $DB->fetch_row($query);
			$topics = $row['topics'];
			++$topics;
		$DB->query("UPDATE mkp_categories SET topics ='$topics' WHERE id = '$evento'");
		$this->update_total();
		$DB->close_db();
	 	Header("Location: /index.php?ind=reviews");
		exit;
  }
  function update_file() {
    	global $mkportals, $DB,  $mklib, $mklib_board;
		$iden = intval($mkportals->input['iden']);
		$evento = $mkportals->input['evento'];
		$title = $mkportals->input['titolo'];
		$description = $mkportals->input['descrizione'];
		$field1 = $mkportals->input['field1'];
		$field2 = $mkportals->input['field2'];
		$field3 = $mkportals->input['field3'];
		$field4 = $mkportals->input['field4'];
		$field5 = $mkportals->input['field5'];
		$field6 = $mkportals->input['field6'];
		$field7 = $mkportals->input['field7'];
		$review =  $mkportals->input['ta'];
		$review = $mklib->convert_savedb($review);
		$keywords = $mkportals->input['keywords'];
		$keywords = $mklib->convert_savedb($keywords);
		$descr = $mkportals->input['descr'];
		$descr = $mklib->convert_savedb($descr);
		//$review = addslashes($review);
        
		if (!$evento || !$title || !$description) {
			$message = "{$mklib->lang['re_reqtcd']}";
			$mklib->error_page($message);
			exit;
		}

		$query = $DB->query("SELECT idauth FROM mkp_reviews WHERE id = $iden");
		$row = $DB->fetch_row($query);

		if(!$row) {
            		$message = "{$mklib->lang['error_404']}";
            		$mklib->error_page($message);
            		exit;
		}

		$idauth = $row['idauth'];

		if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_mod_reviews'] && (!$mklib->member['g_send_reviews'] || $mkportals->member['id'] != $idauth || $idauth == 0 )) {
			$message = "{$mklib->lang['re_noedit']}";
			$mklib->error_page($message);
			exit;
		}

		$DB->query("UPDATE mkp_reviews SET id_cat ='$evento', title ='$title', description ='$description', field1='$field1', field2='$field2', field3='$field3', field4='$field4', field5='$field5', field6='$field6', field7='$field7', review='$review', descr ='$descr', keywords ='$keywords' WHERE id = '$iden'");
		$DB->close_db();
		Header("Location: /index.php?ind=reviews&op=entry_view&iden=$iden");
		exit;
  		}
	function del_file() {
    	global $mkportals, $DB, $mklib, $mklib_board;

		$iden= intval($mkportals->input['iden']);
		$query = $DB->query( "SELECT image, id_cat, author, idauth FROM mkp_reviews WHERE id = $iden");
		$row = $DB->fetch_row($query);
        $id_cat = $row['id_cat'];
		if(!$row) {
            		$message = "{$mklib->lang['error_404']}";
            		$mklib->error_page($message);
            		exit;
		}

		$file = $row['image'];
		$autore = $row['author'];
		$idauth = $row['idauth'];
		if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_mod_reviews'] && (!$mklib->member['g_send_reviews'] || $mkportals->member['id'] != $idauth || $idauth == 0 )) {
			$message = "{$mklib->lang['re_nodel']}";
			$mklib->error_page($message);
			exit;
		}
		$query = $DB->query("SELECT topics FROM mkp_categories WHERE id = '$id_cat' AND module= 'reviews'");
			$row = $DB->fetch_row($query);
			$topics = $row['topics'];
			--$topics;
		$DB->query("UPDATE mkp_categories SET topics ='$topics' WHERE id = '$id_cat' AND module= 'reviews'");
		
		@unlink("mkportal/modules/reviews/images/$file");
		$DB->query("DELETE FROM mkp_reviews WHERE id = $iden");
		$DB->query("DELETE FROM mkp_comments WHERE cid = $iden AND module = 'reviews'");
		$DB->query("DELETE FROM mkp_votes WHERE id_entry = $iden AND module = 'reviews'");
		
		
		$this->update_total();
		$DB->close_db();
	 	Header("Location: /index.php?ind=reviews");
		exit;
  		}

  function submit_comment() {
	  global $mkportals, $mklib, $Skin, $DB, $mklib_board;

		if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_send_comments']) {
			$message = "{$mklib->lang['re_nosendcom']}";
			$mklib->error_page($message);
			exit;
		}

		$ide= intval($mkportals->input['ide']);
		$query = $DB->query( "SELECT id, id_cat, title FROM mkp_reviews WHERE id = '$ide' AND validate = '1'");
		$row = $DB->fetch_row($query);
		if(!$row) {
            		$message = "{$mklib->lang['error_404']}";
            		$mklib->error_page($message);
            		exit;
		}
		$t_id = $row['id'];
		$t_t = $row['title'];
		$t_ev1 = $row['id_cat'];
		$t_ev2 = $this->retrieve_event($t_ev1);
		$navbar = "<a href=\"/index.php?ind=reviews\">{$mklib->lang['re_ptitle2']}</a>{$mklib->lang['bc_sep']}<a href=\"/index.php?ind=reviews&amp;op=section_view&amp;idev=$t_ev1\">$t_ev2</a>{$mklib->lang['bc_sep']}<a href=\"/index.php?ind=reviews&amp;op=entry_view&amp;iden=$t_id\">$t_t</a>{$mklib->lang['bc_sep']}<a href=\"#\">{$mklib->lang['re_commfile']}</a>";
		$content = "
		
		<tr>
		  <td class=\"modulex\">
		  
		    <script type=\"text/javascript\">
		    function emo_pop()
		    {
			  window.open('{$mkportals->base_url}act=legends&amp;CODE=emoticons&amp;s={$mkportals->session_id}','Legends','width=250,height=500,resizable=yes,scrollbars=yes');
		    }
		    </script>

		    <form action=\"/index.php?ind=reviews&amp;op=add_comment&amp;ide={$ide}\" name=\"editor\" method=\"post\" >
		    <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"8\">
		      <tr>
			<td rowspan=\"3\" align=\"center\" height=\"100%\">
			  <iframe src=\"/index.php?ind=reviews&amp;op=show_emoticons\" frameborder=\"0\"  width=\"200\" align=\"middle\" height=\"200\" scrolling=\"auto\"></iframe>
			</td>
			<td>{$mklib->lang['re_writecomm']}</td>
		      </tr>
		      <tr>
			<td width=\"70%\" align=\"left\"><textarea cols=\"10\" style=\"width:95%\" rows=\"4\" name=\"ta\"></textarea></td>
		      </tr>
		      <tr>
		      <td>{$mklib->lang['re_havetitle']} 
			<input type=\"checkbox\" name=\"scambio\" value=\"1\" /><br />
		      </td>
		    </tr>
		    <tr>
		      <td>
			<input type=\"submit\" name=\"submit\" value=\"{$mklib->lang['re_sendcomm']}\" class=\"mkbutton\" accesskey=\"s\" /><br />
		      </td>
		    </tr>		
		  </table>
		  </form>
		
		</td>
	      </tr>
	";
	$maintit = "<a href=\"/index.php?ind=reviews&amp;op=entry_view&amp;iden=$t_id\">{$mklib->lang['re_file']}: $t_t</a>";
	$stat = $this->retrieve_stat();
	$toolbar = "";
	$utonline = $mklib_board->get_active_users("reviews");
	$output  = $this->tpl->review_show($navbar, $maintit, $content, $submit, $stat, $toolbar, $show_pages, $utonline);
	$blocks = $Skin->view_block("{$mklib->lang['re_insertcomm']}", $output);
	$mklib->printpage("1", "1", $mklib->sitename.$mklib->lang['tt_sep'].$mklib->lang['re_pagetitle'].$mklib->lang['tt_sep'].$t_t.$mklib->lang['tt_sep'].$mklib->lang['re_insertcomm'], $blocks);
	}

	function add_comment() {
    	global $mkportals, $DB, $mklib, $mklib_board;


		if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_send_comments']) {
			$message = "{$mklib->lang['re_nosendcom']}";
			$mklib->error_page($message);
			exit;
		}

		$ide= intval($mkportals->input['ide']);
		$testo = $mkportals->input['ta'];
		$autore = $mkportals->member['name'];
		$id_autore = $mkportals->member['id'];
		$scambio = $mkportals->input['scambio'];
        $cdata = time();
		if (!$testo) {
			$message = "{$mklib->lang['re_reqtext']}";
			$mklib->error_page($message);
			exit;
		}

		$testo = $mklib_board->decode_smilies($testo);
		$testo = $mklib->convert_savedb($testo);
		
		//$testo = addslashes($testo);
		$query="INSERT INTO mkp_reviews_comments(identry, autore, testo, data, scambio, id_autore)VALUES('$ide', '$autore', '$testo', '$cdata', '$scambio', '$id_autore')";
		$DB->query($query);
		$DB->close_db();
	 	Header("Location: /index.php?ind=reviews&op=entry_view&iden=$ide");
		exit;
  	}
	function del_comment() {
    	global $mkportals, $DB, $mklib, $mklib_board;

		if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_mod_reviews']) {
			$message = "{$mklib->lang['re_nodelcomm']}";
			$mklib->error_page($message);
			exit;
		}

		$ide = intval($mkportals->input['iden']);
		$idcomm = intval($mkportals->input['idcomm']);
		$DB->query("DELETE FROM mkp_comments WHERE id = $idcomm AND module = 'reviews'");
		$DB->close_db();
	 	Header("Location: /index.php?ind=reviews&op=entry_view&iden=$ide");
		exit;
  	}



  		function add_rate() {
    		global $mkportals, $DB, $mklib, $mklib_board;
		$ide= intval($mkportals->input['ide']);
		$rating = intval($mkportals->input['rating']);
		$iduser = $mkportals->member['id'];
		$ipuser = $_SERVER['REMOTE_ADDR'];
		$module = "reviews";

		if (!$iduser || $iduser == 0) { //Guests: check IP address
			$query = $DB->query( "SELECT id FROM mkp_votes WHERE module = '$module' and id_entry = '$ide' and ip = '$ipuser'");
			$check = $DB->get_num_rows($query);

		} else { //Registered Members: check userid
			$query = $DB->query( "SELECT id FROM mkp_votes WHERE module = '$module' and id_entry = '$ide' and id_member = '$iduser'");
			$check = $DB->get_num_rows($query);
		}
		if($check) {
			$message = "{$mklib->lang['re_justvote']}";
			$mklib->error_page($message);
			exit;
		}

		//Validate rating value
		if ($rating < 1 || $rating > 5) {
    			$message = $mklib->lang['re_badvote'];
    			$mklib->error_page($message);
    			exit;
		}

		$query="INSERT INTO mkp_votes(id_entry, module, id_member, ip)VALUES('$ide', '$module', '$iduser', '$ipuser')";
		$DB->query($query);

		$query = $DB->query( "SELECT rate, trate FROM mkp_reviews WHERE id = '$ide'");
		$row = $DB->fetch_row($query);
		$rate = $row['rate'];
		$trate = $row['trate'];
		$votes = ($trate +1);
		$rate = round ((($trate*$rate)+$rating)/($votes), 2);

		$DB->query("UPDATE mkp_reviews SET rate ='$rate', trate ='$votes' WHERE id = '$ide'");
		$DB->close_db();
	 	Header("Location: /index.php?ind=reviews&op=entry_view&iden=$ide");
		exit;
  		}

		function search() {
		global $mkportals, $DB, $mklib, $Skin, $mklib_board;
		$maintit = "{$mklib->lang['re_searchf']}";
		$cselect.= "<option value=\"1\">{$mklib->lang['re_title']}</option>\n";
		$cselect.= "<option value=\"2\">{$mklib->lang['re_description']}</option>\n";
		$cselect.= "<option value=\"3\">{$mklib->lang['re_revtext']}</option>\n";
		$content .= "
		<tr>
		  <td class=\"modulex\">
		  
		    <form action=\"/index.php?ind=reviews&amp;op=result_search\" name=\"search\" method=\"post\">
		    <table width=\"100%\" border=\"0\">
		      <tr>
			<td>{$mklib->lang['re_searchin']}:</td>
			<td>
			  <select class=\"bgselect\" name=\"campo\" size=\"1\">
			  {$cselect}
			  </select>
			</td>
		      </tr>
		      <tr>
			<td width=\"5%\">{$mklib->lang['re_searchtext']}</td>
			<td width=\"95%\">
			  <input type=\"text\" name=\"testo\" size=\"52\" class=\"bgselect\" />
			</td>
		      </tr>
		      <tr>
			<td colspan=\"2\"><input type=\"submit\" value=\"{$mklib->lang['re_searchstart']}\" class=\"mkbutton\" /></td>
		      </tr>
		    </table>
		    </form>
		  </td>
		</tr>
		";
		$navbar = "<a href=\"/index.php?ind=reviews\">{$mklib->lang['re_ptitle2']}</a>{$mklib->lang['bc_sep']}<a href=\"#\">{$mklib->lang['re_searchf']}</a>";
		//$stat = $this->retrieve_stat();
		$toolbar = "";
		$utonline = $mklib_board->get_active_users("reviews");
		$output  = $this->tpl->review_show($navbar, $maintit, $content, $content2, $submit, $stat, $toolbar, $pages, $utonline);
		$blocks = $Skin->view_block("{$mklib->lang['re_pagetitle']}: {$mklib->lang['re_searchstart']}", $output);
		$mklib->printpage("1", "1", $mklib->sitename.$mklib->lang['tt_sep'].$mklib->lang['re_pagetitle'].$mklib->lang['tt_sep'].$mklib->lang['re_searchstart'], $blocks);
	}

	function result_search() {
		global $mkportals, $DB, $mklib, $Skin, $mklib_board;
		$campo = intval($mkportals->input['campo']);
		$testo = $mkportals->input['testo'];
		$campo = "title";
		if (intval($mkportals->input['campo']) == 2) {
			$campo = "description";
		}
		if (intval($mkportals->input['campo']) == 3) {
			$campo = "review";
		}
		if (!$testo) {
			$message = "{$mklib->lang['re_reqstring']}";
			$mklib->error_page($message);
			exit;
		}
		$navbar = "<a href=\"/index.php?ind=reviews\">{$mklib->lang['re_ptitle2']}</a>{$mklib->lang['bc_sep']}<a href=\"#\">{$mklib->lang['re_searchresult']}</a>";
		$maintit = "{$mklib->lang['re_searchresult']}";
		$content = $this->tpl->row_main_entries();
		$query = $DB->query( "SELECT id, id_cat, title, description, click, date, trate FROM mkp_reviews WHERE $campo LIKE '%$testo%' AND validate = '1'");
		while( $row = $DB->fetch_row($query) ) {
			$iden = $row['id'];
			$id_cat = $row['id_cat'];
			$name = $row['title'];
			$description = $row['description'];
			$trate = $row['trate'];
			$click = $row['click'];
			$data = $mklib->create_date($row['date'], "short");
			//$even = $this->retrieve_father($id_cat);
			
		$name = "<a href=\"/index.php?ind=reviews&amp;op=entry_view&amp;iden=$iden\">$name</a>";
		
			$content .= $this->tpl->row_main_entries_content($name, $trate, $description, $click, $data);
		}
		if (!$name) {
			$content = "<td align=\"center\" width=\"100%\" class=\"modulecell\"><br />{$mklib->lang['re_searchnot']}<br /><br /><br /></td>";
		}
		$submit = "";
  
		$toolbar = "";
		$utonline = $mklib_board->get_active_users("reviews");
		$output  = $this->tpl->review_show($navbar, $maintit, $content, $content2, $submit, $stat, $toolbar, $pages, $utonline);
		$blocks = $Skin->view_block("{$mklib->lang['re_pagetitle']}: {$mklib->lang['re_searchresult']}", $output);
		$mklib->printpage("1", "1", $mklib->sitename.$mklib->lang['tt_sep'].$mklib->lang['re_pagetitle'].$mklib->lang['tt_sep'].$mklib->lang['re_searchresult'], $blocks);
	}


		function row_select_event($jump="") {
			global $mkportals, $DB, $mklib, $mklib_board;
            $modname ="reviews";
			if($jump) {
				$cselect = "<option value=\"0\">{$mklib->lang['re_jumpcat']}</option>\n";
			}

			$query = $DB->query( "SELECT id, title FROM mkp_categories WHERE module='$modname' ORDER BY `id` DESC");
			while( $row = $DB->fetch_row($query) ) {
				$idevento = $row['id'];
				$evento = $row['title'];
				$cselect.= "<option value=\"$idevento\">$evento</option>\n";
				$cselects.= "<option value=\"$idevento\">$evento</option>\n";
			}
			if (!$idevento) {
				return FALSE;
			}
			return $cselect;
		}
		
		function retrieve_event($idevento) {
			global $mkportals, $DB;
			$modname ="reviews";
			$query = $DB->query( "SELECT title FROM mkp_categories WHERE module='$modname' AND id = '$idevento'");
			$evento = $DB->fetch_row($query);
			return $evento['title'];
		}
		function retrieve_father($idevento) {
			global $mkportals, $DB;
			 $modname ="reviews";
			$query = $DB->query( "SELECT parentid FROM mkp_categories WHERE module='$modname' AND id = '$idevento'");
			$row = $DB->fetch_row($query);
			if($row['parentid']) {
				$evento = $this->retrieve_event($row['parentid']);
			}
			return array ($row['parentid'], $evento);
		}
		function retrieve_stat() {
			global $mkportals, $DB, $mklib, $mklib_board;
			$query = $DB->query( "SELECT id FROM mkp_reviews WHERE validate = '1'");
			$count = $DB->get_num_rows($query);
			$query = $DB->query( "SELECT id, id_cat, title FROM mkp_reviews WHERE validate = '1' ORDER BY `click` DESC LIMIT 1");
			$row = $DB->fetch_row($query);
			$id = $row['id'];
			$name = $row['title'];
			$idcat = $row['id_cat'];
		
			$visitato = "<a href=\"/index.php?ind=reviews&amp;op=entry_view&amp;iden=$id\">$name</a>";
			
			$query = $DB->query( "SELECT id, id_cat, title FROM mkp_reviews WHERE validate = '1' ORDER BY `trate` DESC LIMIT 1");
			$row = $DB->fetch_row($query);
			$id = $row['id'];
			$name = $row['title'];
			$idcat = $row['id_cat'];

			$votato = "<a href=\"/index.php?ind=reviews&amp;op=entry_view&amp;iden=$id\">$name</a>";
			
			//$votato = "<a href=\"/index.php?ind=reviews&amp;op=entry_view&amp;iden=$id\">$name</a>";
			$output = "{$mklib->lang['re_have']} $count {$mklib->lang['re_totfile']}<br />{$mklib->lang['re_mosts']} $visitato<br />{$mklib->lang['re_mostv']} $votato";
			return $output;
		}


		function show_emoticons()
 	{
		global $mklib_board;
		$mklib_board->show_emoticons();
 	}
	function update_total() {
		global $DB;
		$query = $DB->query( "SELECT id FROM mkp_reviews WHERE validate = '1'");
		$count = $DB->get_num_rows($query);
		$DB->query("UPDATE mkp_stat SET valore ='$count' WHERE chiave = 'tot_reviews'");
	
	}
	
	function ajax_comment() {
	global $mkportals, $DB, $std, $print, $mklib, $Skin, $mklib_board;
	@header("Content-type: text/html; charset={$mklib->charset}");
@header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
@header('Last-Modified: '.@gmdate('D, d M Y H:i:s').' GMT');
@header('Cache-Control: no-store, no-cache, must-revalidate');
@header('Cache-Control: post-check=0, pre-check=0', false);
@header('Pragma: no-cache');
		$ide = intval($mkportals->input['ide']);
		$testo1 = $mkportals->input['ta'];
		$autore1 = $mkportals->member['name'];
		$autorid = $mkportals->member['id'];
		$autore = iconv("UTF-8", "{$mklib->charset}", $autore1);
        $testo = iconv("UTF-8", "{$mklib->charset}", $testo1);
		$modname ='reviews';
		if ($mklib->config['antibot_chek'] && !$mkportals->member['id']){
$captcha_code = $mkportals->input['check'];
$captcha_check = $mklib->antibot_check($captcha_code);
}
		if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_send_comments']) {
			$message = "{$mklib->lang['ne_nosendcom']}";
			$mklib->Ajax_error_page($message);
			exit;
		}
        $cdata = time();
		if (!$testo) {
			$message = "{$mklib->lang['ga_inserttx']}";
			$mklib->Ajax_error_page($message);
			exit;
		}
		$testo = $mklib_board->decode_smilies($testo);
		$testo = $mklib->convert_savedb($testo);
		//$testo = addslashes($testo);
		$query="INSERT INTO `mkp_comments` (`cid`, `module`, `data`, `memid`, `name`, `comment` )VALUES ('$ide', '$modname', '$cdata', '$autorid', '$autore', '$testo')";
		$DB->query($query);
	    $query1 = $DB->query( "SELECT id, cid, module, data, memid, name, memip, comment, status FROM mkp_comments WHERE cid = '$ide' AND module = '$modname'  ORDER BY `id` DESC");
	    $content2 = "
 

		<script type=\"text/javascript\">

			function makesure3() {
			if (confirm('{$mklib->lang['re_delcommconf']}')) {
			return true;
			} else {
			return false;
			}
			}

			</script>
	
		
		"; 
	 	while( $row = $DB->fetch_row($query1) ) {
			$idcomm = $row['id'];
			$autore = $row['name'];
			$testo = stripslashes($row['comment']);
			$testo = $mklib->decode_bb($testo);
			$data = $mklib->create_date($row['data'], "short");
			$delete = "
			<a href=\"/index.php?ind=reviews&amp;op=del_comment&amp;idcomm=$idcomm&amp;iden=$ide\" onclick=\"return makesure3()\">[ {$mklib->lang['re_delete']} ]</a>
			";
			if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_mod_news']) {
				$delete = "";
			}
			$content2 .= "
		
			<tr>
                            <td id =\"comments\" class=\"modulecell\" width=\"20%\" valign=\"top\">{$autore}<br />{$data}<br />{$delete}</td>
                            <td class=\"modulecell\" width=\"80%\" valign=\"middle\">{$testo}</td>
			</tr>
			
			";
		}
		echo "<table class=\"moduleborder\" width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"4\">$content2</table>";
  	
	}


}
?>
