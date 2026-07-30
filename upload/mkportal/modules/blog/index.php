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

$idx = new mk_blog;
class mk_blog {

	function mk_blog() {
		global $mkportals, $DB, $mklib, $Skin, $mklib_board;

		$mklib->load_lang("lang_blog.php");

		if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_access_blog']) {
			$message = "{$mklib->lang['b_unauth']}";
			$mklib->error_page($message);
			exit;
		}
		if ($mklib->config['mod_blog']) {
		$message = "{$mklib->lang['b_mnoactive']}";
			$mklib->error_page($message);
			exit;
		}

		//location
		$mklib_board->store_location("blog");

		switch($mkportals->input['op']) {
    			case 'create':
    				$this->create();
    			break;
				case 'create_save':
    				$this->create_save();
    			break;
				case 'main_edit':
    				$this->main_edit();
    			break;
				case 'main_change':
    				$this->main_change();
    			break;
    			case 'show_preview':
    				$this->show_preview();
    			break;
				case 'insert_post':
    				$this->insert_post();
    			break;
				case 'update_post':
    				$this->update_post();
    			break;
				case 'del_post':
    				$this->del_post();
    			break;
				case 'show_comments':
    				$this->show_comments();
    			break;
				case 'insert_comments':
    				$this->insert_comments();
    			break;
				case 'del_comment':
    				$this->del_comment();
    			break;
				case 'del_blog':
    				$this->del_blog();
    			break;
				case 'edit_blog':
    				$this->edit_blog();
    			break;
				case 'edit_save':
    				$this->edit_save();
    			break;
				case 'edit_blog_link':
    				$this->edit_blog_link();
    			break;
				case 'add_link':
    				$this->add_link();
    			break;
				case 'rem_link':
    				$this->rem_link();
    			break;
				case 'edit_template':
    				$this->edit_template();
    			break;
				case 'save_template':
    				$this->save_template();
    			break;
				case 'show_gallery':
    				$this->show_gallery();
    			break;
				case 'home':
    				$this->home();
    			break;
				case 'preview_blog':
    				$this->preview_blog();
    			break;
				case 'submit_rate':
    				$this->submit_rate();
    			break;
				case 'add_rate':
    				$this->add_rate();
    			break;
				case 'chart':
    				$this->chart();
    			break;
				case 'edit_banner':
    				$this->edit_banner();
    			break;
				case 'save_banner':
    				$this->save_banner();
    			break;
			case 'popup' :
                    		$this->show_magic_words();
                    	break;
			case 'p_gal' :
                    		$this->p_gal();
                    	break;
			case 'upload_imm' :
                    		$this->upload_imm();
                    	break;
			case 'delete_im' :
                    		$this->delete_im();
                    	break;
			case 'frame_gallery' :
                    		$this->frame_gallery();
                    	break;
				default:
    				$this->main_page();
    			break;
    		}

		}

	function main_page() {
		global $mkportals, $DB, $mklib, $Skin, $mklib_board;

		$link_user = $mklib_board->forum_link("profile");
		$start = intval($mkportals->input['start']);
		$query = $DB->query("SELECT id FROM mkp_blog WHERE validate = '1'");
		$count = $DB->get_num_rows($query);

		$q_page = intval($mkportals->input['st']);
		if ($q_page=="" or $q_page <= 0) {
			$q_page	=	0;
		}
		$per_page = $mklib->config['blog_page'];
		if ($per_page=="" or $per_page <= 0) {
			$per_page	=	10;
		}

	    $start = $q_page;
		$show_pages = $mklib->build_pages( array( TOTAL_POSS  => $count,
							PER_PAGE    => $per_page,
							CUR_ST_VAL  => $q_page,
							L_SINGLE    => '',
							L_MULTI     => 'pagine',
						    BASE_URL    => 'index.php?ind=blog',
										  )
								   );



		$utenti_in = $mklib_board->get_active_users("blog");

		$output = "
	<tr>
	  <td><br />
	    <table class=\"moduleborder\" cellspacing=\"1\" cellpadding=\"0\" width=\"98%\" align=\"center\" border=\"0\">
	      <tr>
		<td>
		  <table class=\"modulebg\" cellspacing=\"1\" cellpadding=\"0\" width=\"100%\" border=\"0\">
		    <tr>
		      <td class=\"tdblock\" width=\"100%\" height=\"25\"><img class=\"mkicon\" src=\"$mklib->images/arrow.gif\" alt=\"\" />{$mklib->lang['b_blog']}</td>
		    </tr>
		    <tr>
		      <td>
			<table cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" border=\"0\">
			  <tr>
			    <td>
			      <table cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" border=\"0\">
				<tr>
				  <td>
				    <table cellspacing=\"1\" cellpadding=\"5\" width=\"100%\" border=\"0\">
				      <tr>
					<th class=\"modulex\" width=\"5%\">{$mklib->lang['b_vote']}</th>
					<th class=\"modulex\" width=\"20%\" >{$mklib->lang['b_title']}</th>
					<th class=\"modulex\" width=\"20%\">{$mklib->lang['b_author']}</th>
					<th class=\"modulex\" width=\"35%\">{$mklib->lang['b_description']}</th>
					<th class=\"modulex\" width=\"20%\" align=\"center\">{$mklib->lang['b_updated']}</th>
				      </tr>
	";

	$query = $DB->query( "SELECT id, autore, titolo, descrizione, aggiornato FROM mkp_blog WHERE validate='1' ORDER BY `aggiornato` DESC LIMIT $start, $per_page");
		while( $row = $DB->fetch_row($query) ) {
			$idb = $row['id'];
			$autore = $row['autore'];
			$titolo = $row['titolo'];
			$descrizione = $row['descrizione'];
			$aggiornato = $row['aggiornato'];
			$cdata = "[{$mklib->lang['b_nomsg']}]";
			if ($aggiornato) {
				$cdata = $mklib->create_date($aggiornato, "short");
			}
			if (!$descrizione) {
				$descrizione = "[{$mklib->lang['b_nodescrip']}]";
			}
			if (!$titolo) {
				$titolo = "[{$mklib->lang['b_notitle']}]";
			}
			$output .= "
				      <tr>
					<td class=\"modulecell\" align=\"center\"><a href=\"$mklib->siteurl/index.php?ind=blog&amp;op=submit_rate&amp;ide=$idb\"><img src=\"$mklib->images/rate.gif\" border=\"0\" alt=\"\" /></a></td>
					<td class=\"modulecell\" ><a href=\"$mklib->siteurl/index.php?ind=blog&amp;op=home&amp;idu=$idb\" target=\"_blank\">$titolo</a></td>
					<td class=\"modulecell\" ><a href=\"$link_user=$idb\" class=\"uno\">$autore</a></td>
					<td class=\"modulecell\" >$descrizione</td>
					<td class=\"modulecell\" align=\"center\">$cdata</td>
				      </tr>
			";
	}


	$output .= "
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
	    </table>
	</td>
	</tr>
	    
	<tr>
 	  <td>
	    <table>
	      <tr>
 		<td>
		<div style=\"margin: 4px\">{$show_pages}</div>
		</td>
	      </tr>
	    </table>
	  </td>
	</tr>
	<tr>
 	  <td><br />
	    <table class=\"moduleborder\" cellspacing=\"1\" cellpadding=\"1\" width=\"98%\" align=\"center\" border=\"0\">
	      <tr>
		<td class=\"modulex\">
		  {$utenti_in}
		</td>
	      </tr>
	    </table>
	  </td>
	</tr>

	<tr>
	  <td align=\"center\"><br /><br />
	    <div align=\"center\"><a href=\"http://www.mkportal.it\" target=\"_blank\">MKPBlog</a> &copy;2003-2008 <a href=\"http://www.mkportal.it\" target=\"_blank\">mkportal.it</a></div>
	  </td>
	</tr>	 
	";
		$output = $Skin->view_block($mklib->lang['b_pagetitle'], $output);
		$mklib->printpage_blog("1", "1", $mklib->sitename.$mklib->lang['tt_sep'].$mklib->lang['b_pagetitle'], $output);


	}

	function create() {
		global $mkportals, $DB, $mklib, $Skin;

		if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_send_blog']) {
			$message = "{$mklib->lang['b_unauthc']}";
			$mklib->error_page($message);
			exit;
		}

		$idu = $mkportals->member['id'];
		$urlb = $mkportals->member['name'];
		$urlb = strtolower ($urlb);
		$urlb = str_replace(" ", "", $urlb);
		$urlb = str_replace("&#39;", "", $urlb);
		$urlb = "$mklib->mkurl/blog/$urlb".".html";
		$query = $DB->query("SELECT id FROM mkp_blog WHERE id = '$idu'");
        $result = $DB->get_num_rows($query);
        if ($result) {
			$message = "{$mklib->lang['b_delbeforec']}";
			$mklib->error_page($message);
			exit;
		}
		if ($idu < 1) {
			$message = "{$mklib->lang['b_unauthv']}";
			$mklib->error_page($message);
			exit;
		}
		/* deprecated - moved to language files
		$subtitle = "{$mklib->lang['b_createtitle']}";
		$filename = "mkportal/modules/blog/disclaimer.txt";
		$fd = fopen ($filename, "r");
		$disclaimer = fread ($fd, filesize ($filename));
		fclose ($fd); */

			$content = "
	<tr>
	  <td>
	  
	    <form name=\"main1\" method=\"post\" action=\"index.php?ind=blog&amp;op=create_save\">
	    <table>
	      <tr>
		<td class=\"titadmin\">{$mklib->lang['b_title']}</td>
	      </tr>
	      <tr>
		<td>
		  <input class=\"bgselect\" type=\"text\" name=\"titolo\" size=\"40\" />
		</td>
	      </tr>
	      <tr>
		<td class=\"titadmin\"><br />{$mklib->lang['b_description']}</td>
	      </tr>
	      <tr>
		<td>
		  <textarea class=\"mkwrap1\" cols=\"40\" rows=\"5\" name=\"descrizione\"></textarea>
		</td>
	      </tr>
	      <tr>
		<td class=\"titadmin\"><br />{$mklib->lang['b_url']}</td>
	      </tr>
	      <tr>
		<td><b>$urlb</b></td>
	      </tr>
	      <tr>
		<td class=\"titadmin\"><br />{$mklib->lang['b_disclaimer']}</td>
	      </tr>
	      <tr>
		<td>
		  <textarea class=\"mkwrap1\" cols=\"80\" rows=\"10\" name=\"disclaimer\" readonly=\"readonly\">{$mklib->lang['b_disclaimertxt']}</textarea>
		</td>
	      </tr>
	      
	      <tr>
		<td colspan=\"2\" class=\"titadmin\"><br />
		  <input type=\"submit\" value=\"{$mklib->lang['b_acceptdisc']}\" name=\"B1\" class=\"mkbutton\" />
		</td>
	      </tr>
	    </table>
	    </form>
	    
	  </td>
	</tr>
			";
			$output = $Skin->view_block("{$mklib->lang['b_createtitle']}", "$content");
			$mklib->printpage_blog("1", "1", $mklib->sitename.$mklib->lang['tt_sep'].$mklib->lang['b_pagetitle'].$mklib->lang['tt_sep'].$mklib->lang['b_createtitle'], $output);

		}

		function create_save() {
		global $mkportals, $DB, $mklib, $Skin, $mklib_board, $MK_LANG;

		if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_send_blog']) {
			$message = "{$mklib->lang['b_unauthc']}";
			$mklib->error_page($message);
			exit;
		}

		$idu = $mkportals->member['id'];
		$name = $mkportals->member['name'];
		//$home = $mkportals->input['indirizzo']; //deprecated
		$titolo = $mkportals->input['titolo'];
		$descrizione = nl2br($mkportals->input['descrizione']);
		$data = time();
		$urlb = strtolower ($name);
		$urlb = str_replace(" ", "", $urlb);
		$urlb = str_replace("&#39;", "", $urlb);
		$urlb = "mkportal/blog/$urlb".".html";

		$filename = "mkportal/modules/blog/templates/template.html";
		if ($MK_LANG == "English") {
			$filename = "mkportal/modules/blog/templates/English/template.html";
		}
		$fd = fopen ($filename, "r");
		$contents = fread ($fd, filesize ($filename));
		fclose ($fd);

		$pos = strpos($contents, "<!-- template2 -->");
		$template2 = substr($contents, ($pos + 18));
		$template = substr($contents, 0, $pos);

		$data = time();
		$filename = "mkportal/blog/tpl_blog.html";

		$filename2 = $urlb;
		copy($filename, $filename2);
		$fp = fopen($filename2, "w") or die("error opening w");
        $testo = "<script type=\"text/javascript\">
         <!--
             location.href = \"$mklib->siteurl/index.php?ind=blog&op=home&idu=$idu\";
         //-->
        </script>";
        fwrite($fp, $testo);
        fclose($fp);

		$validat = "1";
		$approval = $mklib->config['approval_blog'];
		if ($approval == "2" || $approval == "3") {
			$validat = 0;
		}
		if($mkportals->member['g_access_cp']) {
			$validat = "1";
		}

		$query="INSERT INTO mkp_blog(id, autore, titolo, descrizione, template, template2, creato, validate) VALUES ('$idu', '$name', '$titolo', '$descrizione', '$template', '$template2', '$data', '$validat')";
		$DB->query($query);

		if ($approval == "1") {
			$mailsubj = $mklib->lang['01mail'].$mklib->lang['blog'];
			$mailmess = $mklib->lang['02mail'].$mklib->lang['module'].$mklib->lang['blog']."\n".$mklib->lang['sender'].$name."\n\n\n".$mklib->lang['from']." ".$mklib->sitename;
			$mklib_board->admin_mail($mailsubj, $mailmess);
		}
		if ($approval == "2" && !$mkportals->member['g_access_cp']) {
			$mailsubj = $mklib->lang['01mail'].$mklib->lang['blog'];
			$mailmess = $mklib->lang['03mail'].$mklib->lang['module'].$mklib->lang['blog']."\n".$mklib->lang['sender'].$name."\n\n\n".$mklib->lang['from']." ".$mklib->sitename;
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
	 	Header("Location: index.php?ind=blog&op=edit_blog");
		exit;
		}

		function frame_gallery() {
			global $mkportals, $DB, $mklib, $mklib_board;
			$css = $mklib_board->import_css();
		$idu = $mkportals->member['id'];
		
		$output = "
 		<head>
		{$css}		
		</head>
 		<script type=\"text/javascript\">
		<!--
		function add_linkim(ima)
		{
			temp = prompt( '{$mklib->lang[b_copyimlink]}', ima );
		
		}
		//-->
		</script>

		<table width=\"100%\">
		<tr>
	  	<td  width=\"100%\" align=\"center\" class=\"tdblock\" valign=\"middle\">{$mklib->lang['b_pgaltit']}</td>
		</tr>
		";

 		$DB->query("SELECT id, file FROM mkp_blog_pimages WHERE iduser = '$idu'");
		if ( $DB->get_num_rows() ) {
			while ( $r = $DB->fetch_row() ) {
				$image = stripslashes($r['file']);
				$image = "mkportal/blog/images/$image";
				$thumb_mes = $mklib->ResizeImage(140,"$image");
				$image = "$mklib->siteurl/$image";
				$output .= "
					<tr>
	  				<td  width=\"100%\" align=\"center\" class=\"tdblock\" valign=\"middle\"><a href=\"javascript:add_linkim('$image')\"><img src=\"$image\" width='$thumb_mes[0]' height='$thumb_mes[1]' border=\"0\" align=\"left\" alt=\"$image\" /></a></td>
					</tr>
				";				
			}
		}
		$output .= "</table>";
		print $output;
		
		}
		function main_edit() {
		global $mkportals, $DB, $mklib, $Skin, $editorscript;

		$mygal = "<iframe src=\"index.php?ind=blog&amp;op=frame_gallery\" align=\"top\" frameborder=\"0\" width=\"164\" height=\"290\" scrolling=\"auto\"></iframe>";
		
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

		if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_send_blog']) {
			$message = "{$mklib->lang['b_unauthw']}";
			$mklib->error_page($message);
			exit;
		}
		$idu = $mkportals->member['id'];
		$curmese = $mkportals->input['curmese'];
		$query = $DB->query("SELECT id, titolo, validate FROM mkp_blog WHERE id = '$idu'");
        $result = $DB->get_num_rows($query);
        if (!$result) {
			$message = "{$mklib->lang['b_c_b_w']}";
			$mklib->error_page($message);
			exit;
		}
		$row = $DB->fetch_row($query);
		$id = $row['id'];
		$validate = $row['validate'];

		if ($validate == 0) {
			$message = "{$mklib->lang['wait_valid']}";
			$mklib->error_page($message);
			exit;
		}
		$content = "
		<tr>
		  <td>
		    <form action=\"index.php?ind=blog&amp;op=insert_post\" method=\"post\" id=\"editor\" name=\"editor\">
		    <table width=\"100%\">
		      <tr>
		      <td>
		      <table width=\"100%\">
		      <tr>
			<td width=\"90%\" id=\"tdblock\">
			$bbeditor
 			<textarea id=\"ta\" name=\"ta\" $textarepar style=\"width: $textarew\" rows=\"14\" cols=\"40\"></textarea>
		      </td>
		      <td width=\"160\" class=\"tablemenu\">
		      $mygal
		      </td>
		      </tr>
		      </table>
		      </td>
		    </tr>
		    <tr>
		      <td>
			<input type=\"submit\" name=\"ok\" value=\"{$mklib->lang['b_writesave']}\" class=\"mkbutton\" />		
		      </td>
		    </tr>
		  </table>
		  </form>
		  <table width=\"100%\">
		    <tr>
		      <td>
			<iframe src=\"index.php?ind=blog&amp;op=show_preview&amp;curmese=$curmese\" name=\"inferiore\" frameborder=\"0\"  width=\"100%\" align=\"middle\" height=\"600\" scrolling=\"auto\"></iframe>
		      </td>
		    </tr>		
		  </table>
		</td>
	      </tr>
	";
		$output = $Skin->view_block("{$mklib->lang['b_writetitle']}", "$content");
		$mklib->printpage_blog("0", "0", $mklib->sitename.$mklib->lang['tt_sep'].$mklib->lang['b_pagetitle'].$mklib->lang['tt_sep'].$row['titolo'].$mklib->lang['tt_sep'].$mklib->lang['b_writetitle'], $output);

		}
		function main_change () {
			global $mkportals, $DB, $mklib, $Skin, $editorscript;
			
			$mygal = "<iframe src=\"index.php?ind=blog&amp;op=frame_gallery\" align=\"top\" frameborder=\"0\" width=\"164\" height=\"290\" scrolling=\"auto\"></iframe>";
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
			if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_send_blog']) {
				$message = "{$mklib->lang['b_unauthw']}";
				$mklib->error_page($message);
				exit;
			}

			$idm = intval($mkportals->input['idm']);
			$query = $DB->query("SELECT id_blog, post FROM mkp_blog_post WHERE id = '$idm'");
			$result = $DB->fetch_row($query);
			$message = $result['post'];
			$message = stripslashes($message);
			if ($result['id_blog'] != $mkportals->member['id']) {
				$message = "{$mklib->lang['b_hack']}";
				$mklib->error_page($message);
				exit;
			}

			if ($mklib->mkeditor == "BBCODE") {
				$message = str_replace("<br />", "\n", $message);
			} else {
				$message = preg_replace("/(?<=\>)<br \/>(?=\<)/" , "\n", $message);
			}

		$content = "
		<tr>
		  <td>
		  
		    <form action=\"index.php?ind=blog&amp;op=update_post\" method=\"post\" id=\"editor\" name=\"editor\">
		    <table width=\"100%\">
		      <tr>
		      <td>
		      <table width=\"100%\">
		      <tr>
			<td width=\"90%\" id=\"tdblock\">
			$bbeditor
 			<textarea id=\"ta\" name=\"ta\" $textarepar style=\"width: $textarew\" rows=\"14\" cols=\"40\">$message</textarea>
			</td>
		      <td width=\"160\" class=\"tablemenu\">
		      $mygal
		      </td>
		      </tr>
		      </table>
		      </td>
		    </tr>
		      <tr>
			<td>
			  <input type=\"hidden\" name=\"idm\" value=\"$idm\" />
			  <input type=\"submit\" name=\"ok\" value=\"{$mklib->lang['b_writesave']}\" class=\"mkbutton\" />
			</td>
		      </tr>		    
		    </table>
		    </form>
		    <table width=\"100%\">
		      <tr>
			<td>
			  <iframe src=\"index.php?ind=blog&amp;op=show_preview\" name=\"inferiore\" frameborder=\"0\" width=\"100%\" align=\"middle\" height=\"600\" scrolling=\"auto\"></iframe>
			</td>
		      </tr>
		    </table>
		  </td>
		</tr>
	";
		$output = $Skin->view_block("{$mklib->lang['b_edittitle']}", "$content");
		$mklib->printpage_blog("0", "0", $mklib->sitename.$mklib->lang['tt_sep'].$mklib->lang['b_pagetitle'].$mklib->lang['tt_sep'].$mklib->lang['b_edittitle'], $output);

		}

		function show_preview() {
 		global $mkportals, $DB, $Skin, $mklib;

		if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_send_blog']) {
			$message = "{$mklib->lang['b_unauthw']}";
			$mklib->error_page($message);
			exit;
		}

		$curmese = $mkportals->input['curmese'];
		$template = "<base target=\"_top\" />";
		 $template .= $this->createmp($curmese);

		print $template;


 	}


	function insert_post () {
		global $mkportals, $DB, $Skin, $mklib;

		if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_send_blog']) {
			$message = "{$mklib->lang['b_unauthw']}";
			$mklib->error_page($message);
			exit;
		}

		$idu = $mkportals->member['id'];
		$post = $mkportals->input['ta'];
		$post = $mklib->convert_savedb($post);
		//$post = addslashes($post);
        	$data = time();
		if ($post) {
       		$DB->query("INSERT INTO mkp_blog_post(id_blog, post, data) VALUES ('$idu', '$post', '$data')");
        	$DB->query("UPDATE mkp_blog SET aggiornato = '$data' WHERE id = '$idu'");
		$this->update_last();
		}
		$DB->close_db();
	 	Header("Location: index.php?ind=blog&op=main_edit");
		exit;

    }

	function update_post () {
		global $mkportals, $DB, $Skin, $mklib;

		if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_send_blog']) {
			$message = "{$mklib->lang['b_unauthw']}";
			$mklib->error_page($message);
			exit;
		}

		$idm = intval($mkportals->input['idm']);
		$post = $mkportals->input['ta'];
		$post = $mklib->convert_savedb($post);
		//$post = addslashes($post);
       		$DB->query("UPDATE mkp_blog_post SET post = '$post' WHERE id = '$idm'");
		$this->update_last();
		$DB->close_db();
	 	Header("Location: index.php?ind=blog&op=main_edit");
		exit;
    }
	function del_post () {
		global $mkportals, $DB, $Skin, $mklib;

		$idm = intval($mkportals->input['idm']);
		$query = $DB->query( "SELECT id_blog FROM mkp_blog_post WHERE id = '$idm'");
		$row = $DB->fetch_row($query);

		if ($mkportals->member['id'] == $row['id_blog']) {
        	$DB->query("delete FROM mkp_blog_post WHERE id = '$idm'");
			$DB->query("delete FROM mkp_blog_commenti WHERE id_post = '$idm'");
			$this->update_last();
		}
		$DB->close_db();
	 	Header("Location: index.php?ind=blog&op=main_edit");
		exit;

    }

	function show_comments () {
		global $mkportals, $DB, $Skin, $mklib;

		$idm = intval($mkportals->input['idm']);
		$mode = $mkportals->input['mode'];
		
		$query = $DB->query("SELECT id, autore, home, commento, ipaddress, data FROM mkp_blog_commenti WHERE id_post = '$idm' ORDER BY id DESC");
    
	while( $row = $DB->fetch_row($query) ) {
	    $idcom = $row['id'];
	    $autore  = $row['autore'];
	    $home  = $row['homet'];
	    $commento = $row['commento'];
	    $ipaddress  = $row['ipaddress'];
	    $data  = $row['data'];
	    $ipcomm = "";
	    $ora = $mklib->create_date($data, "time");
	    $data = $mklib->create_date($data, "short");
	    if($mkportals->member['g_access_cp']) {
	    	$ipcomm = "($ipaddress)";
	    }
	    $messaggi .= "<style type=\"text/css\" ><!--
                    .commenti {
                font-family: Tahoma, Arial, Verdana, Helvetica, Georgia, serif, \"Times New Roman\"; font-size:7pt; color:#000000;
                letter-spacing:1px;
                border-top: #FFB72F 1px solid;
                border-left: #FFB72F 1px solid;
                border-right: #EF8F0F 1px solid;
                border-bottom: #DF6700 1px solid;
                padding:1px;
                 text-align:left;
                }
                -->
                </style>";
            $messaggi .= "<table width=\"100%\" cellspacing=\"4\" cellpadding=\"4\" class=\"commenti\"><tr>";
	    $messaggi .= "<td width=\"100%\" style=\"font-size: 9px; font-family: Verdana; font-style: italic\">";
	    if ($mode) {
		if($autore == $mklib->lang['guest']) {
	    		$ipcomm = "($ipaddress)";
	    	}
            	$messaggi .= "<a href=\"index.php?ind=blog&amp;op=del_comment&amp;idcom=$idcom&amp;idm=$idm\"><img src=\"mkportal/modules/blog/templates/images/cancella.jpg\" border=\"0\" alt=\"elimina\" /></a><br /><br />";
	    }
	    $messaggi .= "$commento </td></tr>";
            $messaggi .= "<tr><td width=\"100%\" bgcolor=\"#F0A962\" style=\"font-family: Verdana; font-size: 9px\"><em>$data - $ora</em> $ipcomm <br />{$mklib->lang['b_commentby']} $autore {$mklib->lang['b_commentvisit']} <a href=\"$home\" target=\"_new\">{$mklib->lang['b_blog']}</a></td>";
            $messaggi .= "</tr></table><br />";

        }
		$home = "$mklib->mkurl/blog/{$mkportals->member['name']}.html";
 		$home = strtolower ($home);
 		$home = str_replace(" ", "", $home);
		$home = str_replace("&#39;", "", $home);

		$autore = $mkportals->member['name'];
		if(!$mkportals->member['id']) {
			$autore = $mklib->lang['guest'];
		}
		$query = $DB->query( "SELECT autore FROM mkp_blog WHERE autore='$autore'");
		if ($DB->fetch_row($query)) {
			include("mkportal/modules/blog/popup/commenti.html");
		} elseif (!$DB->fetch_row($query)) {
			include("mkportal/modules/blog/popup/commenti1.html");
		} else  {
			include("mkportal/modules/blog/popup/commentino2.html");
		}
		$DB->close_db();
		exit;

	}
	function insert_comments () {
		global $mkportals, $DB, $Skin, $mklib, $mklib_board;

		$idm = intval($mkportals->input['idm']);
		$autore = $mkportals->input['autore'];
		$home = $mkportals->input['home'];
		$commento = $mkportals->input['commento'];
		$ipaddress = $_SERVER['REMOTE_ADDR'];
		$data = time();
		$query = $DB->query( "SELECT id_blog FROM mkp_blog_post WHERE id = '$idm'");
		$row = $DB->fetch_row($query);
		$id_blog = $row['id_blog'];
		
		$query = $DB->query( "SELECT autore, mailcomm, anon_comm FROM mkp_blog WHERE id = '$id_blog'");
		$row = $DB->fetch_row($query);
		
		$checkauth = "";
		if ($row['anon_comm'] == 1 && !$mkportals->member['id']) {
			$checkauth = 1;
		}
		if ($row['anon_comm'] == 2) {
			$checkauth = 1;
		}
		if ($checkauth) {
			echo($mklib->lang['b_unauthcomm']);
			exit;
		}
		if ($commento) {
			if ($row['mailcomm'] == 1) {
				$mailtext = $row['autore'].",\n\n $autore ".$mklib->lang['b_mailcommt']."\n\n\n".$mklib->lang['from']." ".$mklib->sitename;
				$mklib_board->simple_mail($mklib->lang['b_mailcomms'], $mailtext, $id_blog);	
			} 
       			$DB->query("INSERT INTO mkp_blog_commenti(id_blog, id_post, autore, home, commento, ipaddress, data) values ('$id_blog', '$idm', '$autore', '$home', '$commento', '$ipaddress', '$data')");
       			 $query = $DB->query("SELECT ncom FROM mkp_blog_post WHERE id = '$idm'");
        		$result = $DB->fetch_row($query);
       			 $count = $result['ncom'];
        		++ $count;
       			 $DB->query("UPDATE mkp_blog_post SET ncom = '$count'  WHERE id = '$idm'");
		}
		$DB->close_db();
	 	Header("Location: index.php?ind=blog&op=show_comments&idm=$idm");
		exit;

    }
	function del_comment () {
		global $mkportals, $DB, $Skin, $mklib;
		$idm = intval($mkportals->input['idm']);
		$idcom = intval($mkportals->input['idcom']);

		$query = $DB->query( "SELECT id_blog FROM mkp_blog_commenti WHERE id = '$idcom'");
		$row = $DB->fetch_row($query);

		if ($mkportals->member['id'] == $row['id_blog']) {
			$DB->query("delete FROM mkp_blog_commenti WHERE id = '$idcom'");
		}
		$query = $DB->query("SELECT ncom FROM mkp_blog_post WHERE id = '$idm'");
        $result = $DB->fetch_row($query);
        $count = $result['ncom'];
        -- $count;
        $DB->query("UPDATE mkp_blog_post SET ncom = '$count'  WHERE id = '$idm'");

		$DB->close_db();
	 	Header("Location: index.php?ind=blog&op=show_comments&idm=$idm");
		exit;

    }
	function del_blog () {
		global $mkportals, $DB, $Skin, $mklib;
		$idb = $mkportals->member['id'];

		$query = $DB->query("SELECT id FROM mkp_blog WHERE id = '$idb'");
        $result = $DB->get_num_rows($query);
        if (!$result) {
			$message = "{$mklib->lang['b_c_b_d']}";
			$mklib->error_page($message);
			exit;
		}
		$DB->query("DELETE FROM mkp_blog_commenti WHERE id_blog = '$idb'");
		$DB->query("DELETE FROM mkp_blog_post WHERE id_blog = '$idb'");
        	$DB->query("DELETE FROM mkp_blog WHERE id = '$idb'");

		$usfile = "mkportal/blog/{$mkportals->member['name']}.html";
 		$usfile = strtolower ($usfile);
 		$usfile = str_replace(" ", "", $usfile);
		$usfile = str_replace("&#39;", "", $usfile);
        	@unlink($usfile);
		$this->update_total();
		$this->update_last();
		$DB->close_db();
	 	Header("Location: index.php?ind=blog&op=create");
		exit;

    }

	function edit_blog () {
		global $mkportals, $DB, $Skin, $mklib;

		if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_send_blog']) {
			$message = "{$mklib->lang['b_unauthw']}";
			$mklib->error_page($message);
			exit;
		}

		$mode = $mkportals->input['mode'];
		$idu = $mkportals->member['id'];
		$urlb = $mkportals->member['name'];
		$urlb = strtolower ($urlb);
		$urlb = str_replace(" ", "", $urlb);
		$urlb = str_replace("&#39;", "", $urlb);
		$urlb = "$mklib->mkurl/blog/$urlb".".html";
		$query = $DB->query("SELECT id FROM mkp_blog WHERE id = '$idu'");
        $result = $DB->get_num_rows($query);
        if (!$result) {
			$message = "{$mklib->lang['b_c_b_e']}";
			$mklib->error_page($message);
			exit;
		}
		$query = $DB->query("SELECT titolo, descrizione, eta, segno, citta, libri, film, canzoni, amo, odio, citazione, maxmess, anon_comm, mailcomm FROM mkp_blog WHERE id = '$idu'");
		$row = $DB->fetch_row($query);
		$titolo = $row['titolo'];
		$descrizione = $row['descrizione'];
		$eta = $row['eta'];
		$segno = $row['segno'];
		$citta = $row['citta'];
		$libri = $row['libri'];
		$libri = str_replace("<br />", "\n", $libri);
		$film = $row['film'];
		$film = str_replace("<br />", "\n", $film);
		$canzoni = $row['canzoni'];
		$canzoni = str_replace("<br />", "\n", $canzoni);
		$amo = $row['amo'];
		$amo = str_replace("<br />", "\n", $amo);
		$odio = $row['odio'];
		$odio = str_replace("<br />", "\n", $odio);
		$citazione = $row['citazione'];
		$citazione = str_replace("<br />", "\n", $citazione);
		$maxmess = $row['maxmess'];
		$anon_comm = $row['anon_comm'];
		$mailcomm = $row['mailcomm'];
		//$subtitle = "{$mklib->lang['b_configtitle']}"; //deprecated
		if ($mode == "saved") {
			$checksave = "{$mklib->lang['b_saved']}<br /><br />";
   		}
		$home = "$mklib->mkurl/blog/{$mkportals->member['name']}.html";
 		$home = strtolower ($home);
 		$home = str_replace(" ", "", $home);
		$home = str_replace("&#39;", "", $home);
		$curtime = $mklib->create_date(time());
		switch($anon_comm) {
			case '1':
    			$se1t2="selected=\"selected\"";
    		break;
		case '2':
    			$se1t3="selected=\"selected\"";
   		 break;
    		default:
    		$se1t1="selected=\"selected\"";
    		break;
		}
		$cselect4 = "<option value=\"0\" $se1t1>{$mklib->lang['b_cancom0']}</option>\n";
		$cselect4 .= "<option value=\"1\" $se1t2>{$mklib->lang['b_cancom1']}</option>\n";
		$cselect4 .= "<option value=\"2\" $se1t3>{$mklib->lang['b_cancom2']}</option>\n";
		if ($mailcomm == 1) {
			$checkactivemail =  "checked=\"checked\"";
   		}
		$content = "
		<tr>
		  <td>
		  
		    <form name=\"conf1\" method=\"post\" action=\"index.php?ind=blog&amp;op=edit_save\">
		    <table>
		      <tr>
			<td>
			$checksave
			</td>
		      </tr>
		      <tr>
			<td class=\"titadmin\"><br />{$mklib->lang['b_url']}</td>
		      </tr>
		      <tr>
			<td><a href=\"$urlb\" target=\"_blank\" class=\"mktxtcontr\"><b>$home</b></a></td>
		      </tr>
		      <tr>
			<td class=\"titadmin\"><br />{$mklib->lang['b_title']}</td>
		      </tr>
		      <tr>
			<td>
			  <input class=\"bgselect\" type=\"text\" name=\"titolo\" value=\"$titolo\" size=\"40\" />
			</td>
		      </tr>
		      <tr>
			<td class=\"titadmin\"><br />{$mklib->lang['b_description']}</td>
		      </tr>
		      <tr>
		      <td>
			<textarea class=\"mkwrap1\" cols=\"40\" rows=\"5\" name=\"descrizione\">$descrizione</textarea>
			</td>
		      </tr>
		      <tr>
			<td class=\"titadmin\"><br />{$mklib->lang['b_cancomsel']}</td>
		      </tr>
		      <tr>
		      <td>
		      <select class=\"bgselect\" size=\"1\" name=\"anon_comm\">
		  	{$cselect4}
		      </select>
		      </td>
		      </tr>
		      <tr>
			<td class=\"titadmin\"><br />{$mklib->lang['b_mailcomm']} <input type=\"checkbox\" name=\"mailcomm\" value=\"1\" $checkactivemail /></td>
	      	      </tr>
		      <tr>
			<td class=\"titadmin\"> <br />{$mklib->lang['b_minusmsg']}</td>
		      </tr>
		      <tr>
			<td>
			  <select class=\"bgselect\" name=\"maxmess\" size=\"1\">
			    <option value=\"$maxmess\">$maxmess</option>
			    <option value=\"0\">0</option>
			    <option value=\"5\">5</option>
			    <option value=\"10\">10</option>
			    <option value=\"15\">15</option>
			    <option value=\"20\">20</option>
			    <option value=\"25\">25</option>
			    <option value=\"30\">30</option>
			  </select>
			</td>
		      </tr>
		      <tr>
			<td class=\"titadmin\"><br />{$mklib->lang['b_age']}</td>
		      </tr>
		      <tr>
			<td>
			  <input class=\"bgselect\" type=\"text\" name=\"eta\" value=\"$eta\" size=\"40\" />
			</td>
		      </tr>
		      <tr>
			<td class=\"titadmin\">{$mklib->lang['b_zodiac']}</td>
		      </tr>
		      <tr>
			<td>
			  <input class=\"bgselect\" type=\"text\" name=\"segno\" value=\"$segno\" size=\"40\" />
			</td>
		      </tr>
		      <tr>
			<td class=\"titadmin\">{$mklib->lang['b_city']}</td>
		      </tr>
		      <tr>
			<td>
			  <input class=\"bgselect\" type=\"text\" name=\"citta\" value=\"$citta\" size=\"40\" />
			</td>
		      </tr>
		      <tr>
			<td class=\"titadmin\"><br />{$mklib->lang['b_links']}</td>
		      </tr>
		      <tr>
			<td class=\"bgselect\" align=\"center\"><br /><b><a href=\"index.php?ind=blog&amp;op=edit_blog_link\" class=\"mktxtcontr\"> [ {$mklib->lang['b_linksclick']} ] </a></b><br /></td>
		      </tr>
		      <tr>
			<td class=\"titadmin\"><br /> {$mklib->lang['b_books']}</td>
		      </tr>
		      <tr>
			<td>
			  <textarea class=\"mkwrap1\" cols=\"40\" rows=\"5\" name=\"libri\">$libri</textarea>
			</td>
		      </tr>
		      <tr>
			<td class=\"titadmin\"><br />{$mklib->lang['b_movies']}</td>
		      </tr>
		      <tr>
			<td>
			  <textarea class=\"mkwrap1\" cols=\"40\" rows=\"5\" name=\"film\">$film</textarea>
			</td>
		      </tr>
		      <tr>
			<td class=\"titadmin\"><br />{$mklib->lang['b_songs']}</td>
		      </tr>
		      <tr>
			<td>
			  <textarea class=\"mkwrap1\" cols=\"40\" rows=\"5\" name=\"canzoni\">$canzoni</textarea>
			</td>
		      </tr>
		      <tr>
			<td class=\"titadmin\"><br />{$mklib->lang['b_love']}</td>
		      </tr>
		      <tr>
			<td>
			  <textarea class=\"mkwrap1\" cols=\"40\" rows=\"5\" name=\"amo\">$amo</textarea>
			</td>
		      </tr>
		      <tr>
			<td class=\"titadmin\"><br />{$mklib->lang['b_hate']}</td>
		      </tr>
		      <tr>
			<td>
			  <textarea class=\"mkwrap1\" cols=\"40\" rows=\"5\" name=\"odio\">$odio</textarea>
			</td>
		      </tr>
		      <tr>
			<td class=\"titadmin\"><br />{$mklib->lang['b_cit']}</td>
		      </tr>
		      <tr>
			<td>
			  <textarea class=\"mkwrap1\" cols=\"40\" rows=\"5\" name=\"citazione\">$citazione</textarea>
			</td>
		      </tr>
		      <tr>
			<td colspan=\"2\" class=\"titadmin\"><br />
			  <input type=\"submit\" value=\"{$mklib->lang['b_savecfg']}\" name=\"B1\" class=\"mkbutton\" />
			</td>
		      </tr>
		    </table>
		    </form>

		  </td>
		</tr>
			";
			$output = $Skin->view_block("{$mklib->lang['b_configtitle']}", "$content");
			$mklib->printpage_blog("1", "1", $mklib->sitename.$mklib->lang['tt_sep'].$mklib->lang['b_pagetitle'].$mklib->lang['tt_sep'].$titolo.$mklib->lang['tt_sep'].$mklib->lang['b_configtitle'], $output);

    }

	function edit_save () {
		global $mkportals, $DB, $Skin, $mklib;

		if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_send_blog']) {
			$message = "{$mklib->lang['b_unauthw']}";
			$mklib->error_page($message);
			exit;
		}

		$idb = $mkportals->member['id'];

		$titolo = stripslashes($mkportals->input['titolo']);
		$descrizione = stripslashes($mkportals->input['descrizione']);
		$eta = stripslashes($mkportals->input['eta']);
		$segno = stripslashes($mkportals->input['segno']);
		$citta = stripslashes($mkportals->input['citta']);
		$libri = stripslashes($mkportals->input['libri']);
		$film = stripslashes($mkportals->input['film']);
		$canzoni = stripslashes($mkportals->input['canzoni']);
		$amo = stripslashes($mkportals->input['amo']);
		$odio = stripslashes($mkportals->input['odio']);
		$citazione = stripslashes($mkportals->input['citazione']);
		$maxmess = $mkportals->input['maxmess'];
		$anon_comm = $mkportals->input['anon_comm'];
		$mailcomm = $mkportals->input['mailcomm'];

		$DB->query("UPDATE mkp_blog set titolo = '$titolo', descrizione = '$descrizione', eta = '$eta', segno = '$segno', citta = '$citta', libri = '$libri', film = '$film', canzoni = '$canzoni', amo = '$amo', odio = '$odio', citazione = '$citazione', maxmess = '$maxmess', anon_comm = '$anon_comm', mailcomm = '$mailcomm'  WHERE id = '$idb'");

		$DB->close_db();
	 	Header("Location: index.php?ind=blog&op=edit_blog&mode=saved");
		exit;

    }

	function edit_blog_link () {
		global $mkportals, $DB, $Skin, $mklib;

		if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_send_blog']) {
			$message = "{$mklib->lang['b_unauthw']}";
			$mklib->error_page($message);
			exit;
		}

		$mode = $mkportals->input['mode'];
		$idu = $mkportals->member['id'];
		$query = $DB->query("SELECT id FROM mkp_blog WHERE id = '$idu'");
        $result = $DB->get_num_rows($query);
        if (!$result) {
			$message = "{$mklib->lang['b_c_b_e']}";
			$mklib->error_page($message);
			exit;
		}
		$query = $DB->query("SELECT link FROM mkp_blog WHERE id = $idu");
		$row = $DB->fetch_row($query);
		$link2 = $row['link'];
		$cselect = "";

		if ($link2) {
			$stringa = unserialize($link2);
			foreach ($stringa AS $linktitle => $linkurl) {
				$cselect .= "<option value=\"$linktitle\">$linktitle</option>\n";
			}
		}

		if ($mode == "saved") {
			$checksave = "{$mklib->lang['b_saved']}<br /><br />";
   		}
		$content .= "
		<tr>
		  <td>

		  <form action=\"index.php?ind=blog&amp;op=add_link\" name=\"ADD\" method=\"post\">		  
		  <table width=\"100%\">		
		    <tr>
		      <td>
		      $checksave
		      </td>
		    </tr>
		    <tr>
		      <td class=\"titadmin\"><br />{$mklib->lang['b_linkadd']}</td>
		    </tr>
		    <tr>
		      <td class=\"tdblock\">
			{$mklib->lang['b_title']}
			<input type=\"text\" value=\"{$mklib->lang['b_linkname']}\" name=\"link\" size=\"40\" />
		      </td>
		    </tr>
		    <tr>
		      <td class=\"tdblock\"> {$mklib->lang['b_linkurl']}
			<input type=\"text\" value=\"http://\" name=\"link2\"  size=\"60\" />
		      </td>
		    </tr>
		    <tr>
		      <td class=\"tdblock\">
			<input type=\"submit\" value=\"{$mklib->lang['b_linkadd2']}\" class=\"mkbutton\" />
		      </td>
		    </tr>
		  </table>
		  </form>

		</td>
	      </tr>
		
	      <tr>
		<td>
		  
		
		  <form action=\"index.php?ind=blog&amp;op=rem_link\" name=\"Rem\" method=\"post\">
		  <table width=\"100%\">
		    <tr>
		      <td class=\"trattini\"><br />{$mklib->lang['b_linkrem']}</td>
		    </tr>
		    <tr>
		      <td class=\"tdblock\">
			{$mklib->lang['b_linkrem2']}
			<select class=\"bgselect\" name=\"vlink\" size=\"1\">
			{$cselect}
			</select>
		      </td>
		    </tr>
		    <tr>
		      <td class=\"tdblock\">
			<input type=\"submit\" name=\"ok\" value=\"{$mklib->lang['b_linkrem3']}\" class=\"mkbutton\" />
		      </td>
		    </tr>
		  </table>
		  </form>
	
		</td>
	      </tr>

	";
			$output = $Skin->view_block("{$mklib->lang['b_linktitle']}", "$content");
			$mklib->printpage_blog("1", "1", $mklib->sitename.$mklib->lang['tt_sep'].$mklib->lang['b_pagetitle'].$mklib->lang['tt_sep'].$mklib->lang['b_linktitle'], $output);
	}

	function add_link () {
		global $mkportals, $DB, $Skin, $mklib;

		if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_send_blog']) {
			$message = "{$mklib->lang['b_unauthw']}";
			$mklib->error_page($message);
			exit;
		}

		$idb = $mkportals->member['id'];
		$link = $mkportals->input['link'];
		$link2 = $mkportals->input['link2'];

		$link = $this->convert_savedb_bloglink($link);
		$link2 = $this->convert_savedb_bloglink($link2);

		if ($link == $mklib->lang['b_linkname']) {
			$message = "{$mklib->lang['b_reqlinkn']}";
			$mklib->error_page($message);
			exit;
		}
		if ($link && $link2) {
				$query = $DB->query("SELECT link FROM mkp_blog WHERE id = '$idb'");
				$result = $DB->fetch_row($query);
				$outputs1 = unserialize($result['link']);				
				$outputs2 = array($link => $link2);
				if (is_array($outputs1) ) {
					$outputs = array_merge($outputs1, $outputs2);
				} else {
					$outputs = $outputs2;
				}
				$outputs = serialize($outputs);
				
				$DB->query("UPDATE mkp_blog set link = '$outputs' WHERE id = '$idb'");
		}
		$DB->close_db();
	 	Header("Location: index.php?ind=blog&op=edit_blog_link&mode=saved");
		exit;

    }
	function rem_link () {
		global $mkportals, $DB, $Skin, $mklib;

		if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_send_blog']) {
			$message = "{$mklib->lang['b_unauthw']}";
			$mklib->error_page($message);
			exit;
		}

		$idb = $mkportals->member['id'];
		$link = stripslashes($mkportals->input['vlink']); //remove slashes added by $mkportals->input

		if ($link) {
				$query = $DB->query("SELECT link FROM mkp_blog WHERE id = '$idb'");
				$result = $DB->fetch_row($query);

				$stringa = unserialize($result['link']);
				foreach ($stringa AS $linktitle => $linkurl) {
					if ($linktitle == $link) {
						unset($stringa[$linktitle]);
					}					
				}
				$outputs = serialize($stringa);

				$DB->query("UPDATE mkp_blog SET link = '$outputs' WHERE id = '$idb'");
		}
		$DB->close_db();
	 	Header("Location: index.php?ind=blog&op=edit_blog_link&mode=saved");
		exit;

    }

	function edit_template () {
		global $mkportals, $DB, $Skin, $mklib, $MK_LANG;

		$idu = $mkportals->member['id'];
		$query = $DB->query("SELECT id FROM mkp_blog WHERE id = '$idu'");
		$result = $DB->get_num_rows($query);
		if (!$result) {
			$message = "{$mklib->lang['b_c_b_et']}";
			$mklib->error_page($message);
			exit;
		}

		$mft = $mkportals->input['mft'];
/*
		$mft = str_replace("../", "", $mft);
		$mft = str_replace("./", "", $mft);
		$mft = str_replace(".\/", "", $mft);
*/
		$mft = preg_replace("#/$#", '', $mft);
		$mft = preg_replace("/[^a-zA-Z0-9\_\-\.]/", '' , $mft);
		$mft = preg_replace('#\.{1,}#s', '.', $mft);
		$mft = preg_replace('#\_{2,}#s', '_', $mft);

		if ($mft) {
			$filename = "mkportal/modules/blog/templates/$mft";
			if ($MK_LANG == "English") {
				$filename = "mkportal/modules/blog/templates/English/$mft";
			}
			$fd = fopen ($filename, "r");
			if (!$fd) {
            	$message = "File not found";
				$mklib->error_page($message);
            	exit;
        	}
			$contents = fread ($fd, filesize ($filename));
			fclose ($fd);
			$pos = strpos($contents, "<!-- template2 -->");
			$template2 = substr($contents, ($pos + 18));
			$template = substr($contents, 0, $pos);
   		} else  {
			$query = $DB->query("SELECT template, template2 FROM mkp_blog WHERE id = '$idu'");
			$row = $DB->fetch_row($query);
			$template = $row['template'];
			$template2 = $row['template2'];
		}
		$template = $mklib->post_htmlspecialchars($template);
		$template2 = $mklib->post_htmlspecialchars($template2);
		//$magic_words_link = "<a class=\"uno\" href=\"javascript:popup_magics();\">{$mklib->lang['group_magic_words']}</a>"."<br />{$mklib->lang['info_magic_words']}";
		
		//$subtitle = "{$mklib->lang['b_ettitle']}"; //deprecated
		$content .= "
		<script type=\"text/javascript\">

			function popup_magics() {
				window.open('index.php?ind=blog&op=popup','MagicWords','width=280,height=700,top,left,resizable=yes,scrollbars=yes,dependent=yes');
			}

	  </script>
		
		<tr>
		  <td>
		    <table width=\"100%\" cellpadding=\"2\" cellspacing=\"1\" >
		    <form action=\"index.php?ind=blog&amp;op=save_template\" name=\"ADD\" method=\"post\">	
		      <tr>
		      <td>
		      <p align=\"center\"><b><a href=\"index.php?ind=blog&amp;op=show_gallery\" class=\"mktxtcontr\"> [ {$mklib->lang['b_seltempl']} ] </a><a href=\"javascript:popup_magics();\"> [ {$mklib->lang['title_magic_words']} ]</a></b></p>
			</td>
		      </tr>
		      <tr>
			<td class=\"titadmin\" align=\"center\"><br />{$mklib->lang['b_templm']}</td>
		      </tr>
		      <tr>
			<td align=\"center\" width=\"80%\">
			  <textarea class=\"mkwrap2\" cols=\"70\" rows=\"25\" name=\"template\">$template</textarea>
			</td>
		      </tr>
		      <tr>
			<td class=\"titadmin\" align=\"center\"><br />{$mklib->lang['b_templp']}</td>
		      </tr>
		      <tr>
			<td align=\"center\" width=\"80%\"><textarea class=\"mkwrap2\" cols=\"70\" rows=\"6\" name=\"template2\">$template2</textarea>
			</td>
		      </tr>
		      <tr>
			<td><br /><br /></td>
		      </tr>
		      <tr>
			<td align=\"center\" class=\"tdblock\">
			  <input type=\"submit\" name=\"ok\" value=\"{$mklib->lang['b_templsave']}\" class=\"mkbutton\" />
			</td>
		      </tr>		
		    </table>
		    </form>
		  </td>
		</tr>
	";
			$output = $Skin->view_block("{$mklib->lang['b_ettitle']}", "$content");
			$mklib->printpage_blog("1", "1", $mklib->sitename.$mklib->lang['tt_sep'].$mklib->lang['b_pagetitle'].$mklib->lang['tt_sep'].$mklib->lang['b_ettitle'], $output);
	}

	function save_template () {
		global $mkportals, $DB, $Skin, $mklib;

		if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_send_blog']) {
			$message = "{$mklib->lang['b_unauthw']}";
			$mklib->error_page($message);
			exit;
		}

		$idb = $mkportals->member['id'];
		$template = $_POST['template'];
		$template = $this->clean_template($template);
		$template2 = $_POST['template2'];
		$template2 = $this->clean_template($template2);


		$DB->query("UPDATE mkp_blog SET template = '$template', template2 = '$template2' WHERE id = '$idb'");

		$DB->close_db();
		Header("Location: index.php?ind=blog&op=main_edit");
		exit;

    }


	function show_gallery () {
		global $mkportals, $DB, $Skin, $mklib;

		if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_send_blog']) {
			$message = "{$mklib->lang['b_unauthw']}";
			$mklib->error_page($message);
			exit;
		}

		//$mode = $mkportals->input['mode'];

		//$subtitle = "{$mklib->lang['b_galtitle']}"; //deprectaed

		$content = "
		<tr>
		  <td width=\"100%\" class=\"titadmin\" align=\"center\">
		  <br />{$mklib->lang['b_seltgal']}<br /><br />
		  
		    <script type=\"text/javascript\">
		  <!--
		  function winnyg(doctoopen) {
		  window.open(doctoopen, 'winny', 'scrollbars=yes,height=561,width=745,top,left');
		  }
		  //-->
		  </script>
		  
		    <table cellpadding=\"3\" cellspacing=\"3\" width=\"100%\">		
		      <tr>
		";
		$cont = 0;
		if ($dir = @opendir("mkportal/modules/blog/templates/thumbnails")) {
       	while (($file = readdir($dir)) !== false) {
		   if ($file != ".." && $file != ".") {
		   	   $filehtml = str_replace(".jpg", ".html", $file);
			   $content .= "
			<td align=\"center\"><a href=\"javascript:winnyg('mkportal/modules/blog/templates/immagini/$file')\">			      <img src=\"mkportal/modules/blog/templates/thumbnails/$file\" border=\"0\" width=\"150\" height=\"100\" alt=\"{$mklib->lang['b_galzoom']}\" /></a><br /><br />";
			   $content .= "
			  <span class=\"mkbutton\"><a href=\"index.php?ind=blog&amp;op=edit_template&amp;mft=$filehtml\" style=\"text-decoration: none;\">{$mklib->lang['b_galuset']}</a></span><br /><br />
			</td>
			";
			   ++ $cont;
			   if ($cont == 3) {
					$cont = 0;
					 $content .= "</tr><tr><td><br /><br /></td></tr><tr>";
			   }

		   }

	     }
        closedir($dir);
   	   }

		$content .= "";

		$content .= "
			<td><br /><br /></td>
		      </tr>
		    </table>
		  </td>
		</tr>
		";
			$output = $Skin->view_block("{$mklib->lang['b_galtitle']}", "$content");
			$mklib->printpage_blog("1", "1", $mklib->sitename.$mklib->lang['tt_sep'].$mklib->lang['b_pagetitle'].$mklib->lang['tt_sep'].$mklib->lang['b_galtitle'], $output);
	}

	function home () {
	global $mkportals, $DB, $Skin, $mklib, $_SERVER;

		$mid = intval($mkportals->input['idu']);
		$curmese = $mkportals->input['curmese'];
		$template = "
		<style>	#mentemp {position: absolute; top: 0px; left: 0px; width: 100%;} </style>
		
		<div id=\"mentemp\">
		  <table border=\"0\" width=\"100%\" bgcolor=\"#000000\" cellspacing=\"3\" cellpadding=\"0\">
		    <tr>
		      <td width=\"100%\">
			<div style=\"font-family: Verdana; font-size: 9px; color: #F0A962\"><b>{$mklib->lang['b_alsocreate']} </b><a href=\"index.php\" style=\"font-family: Verdana; font-size: 9px; color: #F0A962\"><b>$mklib->sitename</b></a> | <a href=\"index.php\" style=\"font-family: Verdana; font-size: 9px; color: #F0A962\"><b>HOME</b></a>
			</div>
		      </td>
		    </tr>
		  </table>
		</div>
		";

		$ip = $_SERVER["REMOTE_ADDR"];
		$query = $DB->query("SELECT click, ip_address, validate FROM mkp_blog WHERE id = '$mid'");
		$row = $DB->fetch_row($query);
		$conto = $row['click'];
		$ip_address = $row['ip_address'];
		$validate = $row['validate'];

		if ($validate == 0) {
			$message = "{$mklib->lang['wait_valid']}";
			$mklib->error_page($message);
			exit;
		}
		if (strcmp($ip, $ip_address) != 0) {
			++ $conto;
		}

		$DB->query("UPDATE mkp_blog SET click = '$conto', ip_address = '$ip' WHERE id = '$mid'");


 		$template .= $this->crea_homearchivio($mid, $curmese);
		$DB->close_db();
		print $template;
		exit;

	}

	function preview_blog () {
		global $mkportals, $DB, $Skin, $mklib;

		$idu = $mkportals->member['id'];
		$query = $DB->query("SELECT id FROM mkp_blog WHERE id = '$idu'");
        $result = $DB->get_num_rows($query);
        if (!$result) {
			$message = "{$mklib->lang['b_c_b_se']}";
			$mklib->error_page($message);
			exit;
		}
		$DB->close_db();
		Header("Location: $mklib->siteurl/index.php?ind=blog&op=home&idu=$idu");
		exit;
	}


	function submit_rate() {
    	global $mkportals, $mklib, $Skin, $DB, $mklib_board;
		$ide= intval($mkportals->input['ide']);

		$iduser = $mkportals->member['id'];
		$ipuser = $_SERVER['REMOTE_ADDR'];
		$module = "blog";

		if (!$iduser || $iduser == 0) { //Guests: check IP address
			$query = $DB->query( "SELECT id FROM mkp_votes WHERE module = '$module' AND id_entry = '$ide' AND ip = '$ipuser'");
			$check = $DB->get_num_rows($query);

		} else { //Registered Members: check userid
			$query = $DB->query( "SELECT id FROM mkp_votes WHERE module = '$module' AND id_entry = '$ide' AND id_member = '$iduser'");
			$check = $DB->get_num_rows($query);
		}
		if($check) {
			$message = "{$mklib->lang['b_jvote']}";
			$mklib->error_page($message);
			exit;
		}

		$query = $DB->query( "SELECT autore, titolo FROM mkp_blog WHERE id = '$ide'");
		$row = $DB->fetch_row($query);
		$t_aut = $row['autore'];
		$t_t = $row['titolo'];
		$maintit = "{$mklib->lang['b_votetitle']} $t_t";

		$utenti_in = $mklib_board->get_active_users("blog");

	   $content .= "
	<tr>
   	  <td><br />
	    <table width=\"98%\" border=\"0\" cellspacing=\"1\" cellpadding=\"0\" align=\"center\" class=\"moduleborder\">
  	      <tr>
   		<td>
		  <table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"2\" class=\"modulebg\">
		    <tr>
		      <td width=\"100%\" height=\"25\" class=\"tdblock\"> <img class=\"mkicon\" src=\"$mklib->images/arrow.gif\" alt=\"\" />{$maintit}</td>
		    </tr>
		    <tr>
		      <td>
			<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"moduleborder\">
			  <tr>
			    <td>
			      <table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"5\">
				<tr>
				  <td bgcolor=\"#ffffff\">
				  
				    <form action=\"index.php?ind=blog&amp;op=add_rate&amp;ide={$ide}\" method=\"post\" id=\"ratea\" name=\"ratea\">
				    <table width=\"100%\">
				      <tr>
					<td class=\"modulex\" width=\"60%\" valign=\"top\">{$mklib->lang['b_voteof']} <b>$t_aut</b> ({$mklib->lang['b_votemax']})
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
					  <input type=\"submit\" name=\"ok\" value=\"{$mklib->lang['b_vote']}\" class=\"mkbutton\" />
					</td>
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
	    <br />
	    <table class=\"moduleborder\" cellspacing=\"1\" cellpadding=\"1\" width=\"98%\" align=\"center\" border=\"0\">
	      <tr>
		<td class=\"modulex\">
		  {$utenti_in}
		</td>
	      </tr>
	    </table>
	  </td>
	</tr>

	<tr>
	  <td align=\"center\"><br /><br />
	    <div align=\"center\"><a href=\"http://www.mkportal.it\" target=\"_blank\">MKPBlog</a> &copy;2003-2008 <a href=\"http://www.mkportal.it\" target=\"_blank\">mkportal.it</a></div>
	  </td>
	</tr>	 
	";
	$blocks = $Skin->view_block("{$mklib->lang['b_vote']}", $content);
	$mklib->printpage_blog("1", "1", $mklib->sitename.$mklib->lang['tt_sep'].$mklib->lang['b_pagetitle'].$mklib->lang['tt_sep'].$t_t.$mklib->lang['tt_sep'].$mklib->lang['b_votetitle'], $blocks);
	}

	function add_rate() {
    	global $mkportals, $DB, $mklib;
		$ide= intval($mkportals->input['ide']);
		$rating = intval($mkportals->input['rating']);
		$iduser = $mkportals->member['id'];
		$ipuser = $_SERVER['REMOTE_ADDR'];
		$module = "blog";

		if (!$iduser || $iduser == 0) { //Guests: check IP address
			$query = $DB->query( "SELECT id FROM mkp_votes WHERE module = '$module' AND id_entry = '$ide' AND ip = '$ipuser'");
			$check = $DB->get_num_rows($query);

		} else { //Registered Members: check userid
			$query = $DB->query( "SELECT id FROM mkp_votes WHERE module = '$module' AND id_entry = '$ide' AND id_member = '$iduser'");
			$check = $DB->get_num_rows($query);
		}
		if($check) {
			$message = "{$mklib->lang['b_jvote']}";
			$mklib->error_page($message);
			exit;
		}

		//Validate rating value
		if ($rating < 1 || $rating > 5) {
    			$message = $mklib->lang['b_badvote'];
    			$mklib->error_page($message);
    			exit;
		}

		$query="INSERT INTO mkp_votes(id_entry, module, id_member, ip)VALUES('$ide', '$module', '$iduser', '$ipuser')";
		$DB->query($query);

		$query = $DB->query( "SELECT rate, trate FROM mkp_blog WHERE id = '$ide'");
		$row = $DB->fetch_row($query);
		$rate = $row['rate'];
		$trate = $row['trate'];
		$votes = ($trate +1);
		$rate = round ((($trate*$rate)+$rating)/($votes), 2);

		$DB->query("UPDATE mkp_blog SET rate ='$rate', trate ='$votes' WHERE id = '$ide'");
		$DB->close_db();
	 	Header("Location: index.php?ind=blog");
		exit;
  	}


	function chart() {
		global $mkportals, $DB, $mklib, $Skin, $mklib_board;

		$iduser = $mkportals->member['id'];

		$utenti_in = $mklib_board->get_active_users("blog");


		$output = "
	<tr>
	  <td><br />
	    <table class=\"moduleborder\" cellspacing=\"1\" cellpadding=\"0\" width=\"98%\" align=\"center\" border=\"0\">
	      <tr>
		<td>
		  <table class=\"modulebg\" cellspacing=\"1\" cellpadding=\"0\" width=\"100%\" border=\"0\">
		    <tr>
		      <td class=\"tdblock\" width=\"100%\" height=\"25\"><img class=\"mkicon\" src=\"$mklib->images/arrow.gif\" alt=\"\" />{$mklib->lang['b_topten']}</td>
		    </tr>
		    <tr>
		      <td>
			<table cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" border=\"0\">
			  <tr>
			    <td>
			      <table cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" border=\"0\">
				<tr>
				  <td>
				    <table cellspacing=\"1\" cellpadding=\"5\" width=\"100%\" border=\"0\">
				      <tr>
					<th class=\"modulex\" width=\"5%\" align=\"center\">{$mklib->lang['b_cpos']}</th>
					<th class=\"modulex\" width=\"5%\" align=\"center\">{$mklib->lang['b_votes']}</th>
					<th class=\"modulex\" width=\"80%\" align=\"center\">{$mklib->lang['b_blog']}</th>
					<th class=\"modulex\" width=\"5%\" align=\"center\">{$mklib->lang['b_clicks']}</th>
					<th class=\"modulex\" width=\"5%\" align=\"center\">{$mklib->lang['b_mrate']}</th>
				      </tr>
	";

	$query = $DB->query( "SELECT id, titolo, descrizione, click, rate, trate, banner FROM mkp_blog WHERE trate > '0' ORDER BY `trate` DESC, `rate` DESC, `click` DESC LIMIT 10");
		$counterpos = 1;
		while( $row = $DB->fetch_row($query) ) {
			$idb = $row['id'];
			$banner = $row['banner'];
			$click = $row['click'];
			$link = "";
			if ($idb == $iduser || $mklib->member['g_access_cpa'] || $mkportals->member['g_access_cp']) {
				$link = "<a href=\"index.php?ind=blog&amp;op=edit_banner&amp;idb=$idb\"> [ {$mklib->lang['b_banchange']} ] </a>";
			}
			if (!$banner) {
				$banner = "$mklib->images/banner_blog.gif";
			}
			$titolo = $row['titolo'];
			$descrizione = $row['descrizione'];
			$rate = $row['rate'];
			$trate = $row['trate'];
			$width = round(($rate*100)/4) - 6;
	 		$width2 = $width - 4;
			if (!$descrizione) {
				$descrizione = "[{$mklib->lang['b_nodescrip']}]";
			}
			if (!$titolo) {
				$titolo = "[{$mklib->lang['b_notitle']}]";
			}
			switch($counterpos) {
				case '1':
					$counterimage = "<img src=\"$mklib->images/1.gif\" border=\"0\" alt=\"\" />";
    			break;
				case '2':
					$counterimage = "<img src=\"$mklib->images/2.gif\" border=\"0\" alt=\"\" />";
    			break;
				case '3':
					$counterimage = "<img src=\"$mklib->images/3.gif\" border=\"0\" alt=\"\" />";
    			break;
				default:
    				$counterimage = $counterpos;
    			break;
			}
			$output .= "
				      <tr>
					<td class=\"modulecell\" align=\"center\"><span class=\"mktxtcontr\">$counterimage</span></td>
					<td class=\"modulecell\" align=\"center\"><b>$trate</b></td>
					<td class=\"modulecell\" align=\"center\"><a href=\"$mklib->siteurl/index.php?ind=blog&amp;op=home&amp;idu=$idb\" target=\"_blank\">$titolo<br /><img src=\"$banner\" border=\"0\" width=\"468\" height=\"60\" alt=\"\" /></a><br />$descrizione<br />$link</td>
					<td class=\"modulecell\" align=\"center\"><b>$click</b></td>
					<td class=\"modulecell\" align=\"center\" ><span class=\"mktxtcontr\">  {$rate}</span>
					</td>
				      </tr>
			";
			++$counterpos;
	}



	$output .= "
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
	    </table>
	  </td>
	</tr>
		
	<tr>
 	  <td><br />
	    <table class=\"moduleborder\" cellspacing=\"1\" cellpadding=\"1\" width=\"98%\" align=\"center\" border=\"0\">
	      <tr>
		<td class=\"modulex\">
		  {$utenti_in}
		</td>
	      </tr>
	    </table>
	  </td>
	</tr>
		
	<tr>
	  <td align=\"center\"><br /><br />
	    <div align=\"center\"><a href=\"http://www.mkportal.it\" target=\"_blank\">MKPBlog</a> &copy;2003-2008 <a href=\"http://www.mkportal.it\" target=\"_blank\">mkportal.it</a></div>
	  </td>
	</tr>	 
	";
		$mklib->printpage_blog("1", "1", $mklib->sitename.$mklib->lang['tt_sep'].$mklib->lang['b_pagetitle'].$mklib->lang['tt_sep'].$mklib->lang['b_charttitle'], $output, "");

	}
	function edit_banner() {
		global $mkportals, $DB, $mklib, $Skin;

		if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_send_blog']) {
			$message = "{$mklib->lang['b_unauthban']}";
			$mklib->error_page($message);
			exit;
		}

		$iduser = $mkportals->member['id'];
		$idb = intval($mkportals->input['idb']);

		if ($mklib->member['g_access_cpa'] || $mkportals->member['g_access_cp']) {
			$checkauth = 1;
		}
		if ($idb != $iduser && !$checkauth) {
			$message = "{$mklib->lang['b_unauthban']}";
			$mklib->error_page($message);
			exit;
		}
		$query = $DB->query( "SELECT titolo, banner FROM mkp_blog WHERE id = '$idb'");
		$row = $DB->fetch_row($query);
		$link = $row['banner'];

		$output = "
	<tr>
	  <td><br />
	    <table class=\"moduleborder\" cellspacing=\"1\" cellpadding=\"0\" width=\"98%\" align=\"center\" border=\"0\">
	      <tr>
		<td>
		  <table class=\"modulebg\" cellspacing=\"1\" cellpadding=\"0\" width=\"100%\" border=\"0\">
		    <tr>
		      <td class=\"tdblock\" width=\"100%\" height=\"25\"><img class=\"mkicon\" src=\"$mklib->images/arrow.gif\" alt=\"\" />{$mklib->lang['b_banchange']}</td>
		    </tr>
		    <tr>
		      <td>
			<table class=\"moduleborder\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" border=\"0\">
			  <tr>
			    <td>
			      <table cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" border=\"0\">
				<tr>
				  <td class=\"modulex\">

				    <form action=\"index.php?ind=blog&amp;op=save_banner&amp;idb=$idb\" name=\"e_b\" method=\"post\">
				    <table width=\"100%\" border=\"0\">
				      <tr>
					<td><br />{$mklib->lang['b_bancwarn']}<br /><br /> </td>
				      </tr>
				      <tr>
					<td class=\"titadmin\">{$mklib->lang['b_banurl']}</td>
				      </tr>
				      <tr>
					<td>
					  <input type=\"text\" name=\"link\" value=\"$link\" size=\"52\" class=\"bgselect\" />
					</td>
				      </tr>
				      <tr>
					<td>
					  <input type=\"submit\" value=\"{$mklib->lang['b_bansave']}\" class=\"mkbutton\" />
					</td>
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
		
	<tr>
	  <td align=\"center\"><br /><br />
	    <div align=\"center\"><a href=\"http://www.mkportal.it\" target=\"_blank\">MKPBlog</a> &copy;2003-2008 <a href=\"http://www.mkportal.it\" target=\"_blank\">mkportal.it</a></div>
	  </td>
	</tr>	
	";
	$blocks = $Skin->view_block("{$mklib->lang['b_bantitle']}", $output);
	$mklib->printpage_blog("1", "1", $mklib->sitename.$mklib->lang['tt_sep'].$mklib->lang['b_pagetitle'].$mklib->lang['tt_sep'].$row['titolo'].$mklib->lang['tt_sep'].$mklib->lang['b_bantitle'], $blocks);

	}
	function save_banner() {
    	global $mkportals, $DB, $mklib;

	if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_send_blog']) {
		$message = "{$mklib->lang['b_unauthban']}";
		$mklib->error_page($message);
		exit;
	}

	$link = $mkportals->input['link'];
		$ide = intval($mkportals->input['idb']);
		$iduser = $mkportals->member['id'];

		if ($mklib->member['g_access_cpa'] || $mkportals->member['g_access_cp']) {
			$checkauth = 1;
		}
		if ($ide != $iduser && !$checkauth) {
			$message = "{$mklib->lang['b_unauthban']}";
			$mklib->error_page($message);
			exit;
		}

			$DB->query("UPDATE mkp_blog SET banner ='$link' WHERE id = '$ide'");

		$DB->close_db();
	 	Header("Location: index.php?ind=blog&op=chart");
		exit;
  	}

	function createmp ($idu, $curmese="0") {
		global $mkportals, $DB, $Skin, $mklib, $mklib_board;
		$curmese = $mkportals->input['curmese'];

		$idu = $mkportals->member['id'];
		 $username = $mkportals->member['name'];
		$lastpage = 0;

        if ($curmese == "0" || !$curmese) {
            $dat = time();
            $curmese = $mklib->create_date($dat, "small");
			$lastpage = 1;
        }


        $query = $DB->query("SELECT autore, titolo, descrizione, template, template2, eta, segno, citta, libri, film, canzoni, link, amo, odio, umore, citazione, click, link_blog, maxmess FROM mkp_blog WHERE id = '$idu'");
        $query1 = $DB->query("SELECT id, post, data, ncom FROM mkp_blog_post WHERE id_blog = '$idu' ORDER BY id DESC");

	$row = $DB->fetch_row($query);
		$username = $row['autore'];
		$titolo = $row['titolo'];
		$descrizione = $row['descrizione'];
		$template = $row['template'];
		$template2 = $row['template2'];
		$eta = $row['eta'];
		$segno = $row['segno'];
		$citta = $row['citta'];
		$libri2 = $row['libri'];
		$libri = str_replace ("\n", "<br />", $libri2);
		$film2 = $row['film'];
		$film = str_replace ("\n", "<br />", $film2);
		$canzoni2 = $row['canzoni'];
		$canzoni = str_replace ("\n", "<br />", $canzoni2);
		$link2 = $row['link'];
		$amo2 = $row['amo'];
		$amo = str_replace ("\n", "<br />", $amo2);
		$odio2 = $row['odio'];
		$odio = str_replace ("\n", "<br />", $odio2);
		$umore = $row['umore'];
		$citazione = $row['citazione'];
		$click = $row['click'];
		$bloglink2 = $row['link_blog'];
		$maxmess = $row['maxmess'];			
			
		$link = "";
		if ($link2) {
			$stringa = unserialize($link2);
			foreach ($stringa AS $linktitle => $linkurl) {
				$link .= "<a href=\"$linkurl\" target=\"_blank\">$linktitle</a><br />";
			}
		}

        $click.="<br /><script type=\"text/javascript\">
                    <!--
                    function winny(doctoopen) {
                    window.open(doctoopen, 'winny', 'scrollbars=yes,height=400,width=450');
                    }
                    //-->
                    </script>";
		//$linkbutton = "<img src=\"images/addblog.gif\" alt=\"\" /><br />";
        $template = str_replace("!BlogTitle!", $titolo, $template);
        $template = str_replace("!BlogDescription!", $descrizione, $template);
        $template = str_replace("!UtenteEta!", $eta, $template);
        $template = str_replace("!UtenteSegno!", $segno, $template);
        $template = str_replace("!UtenteCitta!", $citta, $template);
        $template = str_replace("!UtenteLibri!", $libri, $template);
        $template = str_replace("!UtenteFilm!", $film, $template);
        $template = str_replace("!UtenteCanzoni!", $canzoni, $template);
        $template = str_replace("!UtenteLink!", $link, $template);
        $template = str_replace("!UtenteAmo!", $amo, $template);
        $template = str_replace("!UtenteOdio!", $odio, $template);
        $template = str_replace("!UtenteCitazione!", nl2br($citazione), $template);
        //$template = str_replace("!UtenteUmore!", "<img src=\"images/$umore.gif\" alt=\"\" />", $template);
        $template = str_replace("!BlogCounter!", "$click", $template);
		//$template = str_replace("!LinkButton!", "$linkbutton", $template);
		//$template = str_replace("!BlogLink!", "$bloglink", $template);
	//magic words
	foreach ($mklib->lang as $key => $value) {
             	$pos = strpos($key, "magic_");
             	if ($pos === 0) {
                	$key = "{".strtoupper(substr($key, $pos + 6))."}";
			$template = str_replace($key, $value, $template);
	     	}
	}
        $archivio = "<a href=\"$mklib->siteurl/index.php?ind=blog&amp;op=main_edit\" target=\"_top\">{$mklib->lang['b_lastdays']}</a><br />";
		$contomess = 0;
	while( $row = $DB->fetch_row($query1) ) {
            $idpost = $row['id'];
            $post  = $row['post'];
            $data  = $row['data'];
            $ncom  = $row['ncom'];
            $post = stripslashes($post);
            if ($mklib->mkeditor == "BBCODE") {
		$post = $mklib->decode_bb($post);
		$post = $mklib_board->decode_smilies($post);
            }
            $ora = $mklib->create_date($data, "time");
            $mese = $mklib->create_date($data, "small");
            $datam = $mklib->create_date($data, "normal");
            $messaggio2 = "";
            $checkm = strcmp($mese, $curmese);
            if (!strpos($archivio, $mese)) {
		$archivio .= "<a href=\"$mklib->siteurl/index.php?ind=blog&amp;op=main_edit&amp;curmese=$mese\" target=\"_top\">$mese</a><br />";
            }
            if ($checkm != 0) {
				 if ($contomess >= $maxmess || $lastpage == 0) {
                	continue;
				 }
            }

            $checkd = strcmp($datam, $dataprec);
            $datains = "";
            if ($checkd != 0) {
                $datains= $datam;
            }
            if ($ncom > 0) {
                $ncom = "($ncom)";
            } else  {
                $ncom = "";
            }
            $messaggio2 .= "<a href=\"index.php?ind=blog&amp;op=main_change&amp;idm=$idpost\"><img src=\"mkportal/modules/blog/templates/images/aggiorna.jpg\" border=\"0\" alt=\"\" /></a>&nbsp;";
            $messaggio2 .= "<a href=\"index.php?ind=blog&amp;op=del_post&amp;idm=$idpost\"><img src=\"mkportal/modules/blog/templates/images/cancella.jpg\" border=\"0\" alt=\"\" /></a><br />";
            $messaggio2 .= $post;
            $messaggio = str_replace("!BlogPost!", $messaggio2, $template2);
            $messaggio = str_replace("!PostData!", $datains, $messaggio);
            $messaggio = str_replace("!BlogUtente!", $username, $messaggio);
            $messaggio = str_replace("!PostOra!", $ora, $messaggio);
            $messaggio = str_replace("!PostCommenti!", "<a href=\"javascript:winny('index.php?ind=blog&amp;op=show_comments&amp;idm=$idpost&amp;mode=1')\" target=\"_self\">{$mklib->lang['b_mcomments']} $ncom</a><br />", $messaggio);
            //magic words
	    foreach ($mklib->lang as $key => $value) {
             	$pos = strpos($key, "magic_");
             	if ($pos === 0) {
                	$key = "{".strtoupper(substr($key, $pos + 6))."}";
			$messaggio = str_replace($key, $value, $messaggio);
	     	}
	     }
	    $messaggi .= $messaggio;
            $dataprec = $datam;
            ++$contomess;
        }

        $template = str_replace("!CorpoMessaggi!", $messaggi, $template);
        $template = str_replace("!UtenteArchivio!", "$archivio", $template);

        return $template;
    }


	function crea_homearchivio ($idu, $curmese="0") {
		global $mkportals, $DB, $Skin, $mklib, $mklib_board;
		$singlepost = $mkportals->input['singlepost'];
		$lastpage = 0;

        if ($curmese == "0" || !$curmese) {
            $dat = time();
            $curmese = $mklib->create_date($dat, "small");
			$lastpage = 1;
        }

        $query = $DB->query("SELECT autore, titolo, descrizione, template, template2, eta, segno, citta, libri, film, canzoni, link, amo, odio, umore, citazione, click, link_blog, maxmess FROM mkp_blog WHERE id = '$idu'");
        
	if ($singlepost) {
		$query1 = $DB->query("SELECT id, post, data, ncom FROM mkp_blog_post WHERE id = '$singlepost' AND id_blog = '$idu'");
	} else  {
		$query1 = $DB->query("SELECT id, post, data, ncom FROM mkp_blog_post WHERE id_blog = '$idu' ORDER BY id DESC");
	}
	$row = $DB->fetch_row($query);
            $username = $row['autore'];
            $titolo = $row['titolo'];
	    $descrizione = $row['descrizione'];
	    $template = $row['template'];
	    $template2 = $row['template2'];
	    $eta = $row['eta'];
	    $segno = $row['segno'];
	    $citta = $row['citta'];
	    $libri2 = $row['libri'];
	    $libri = str_replace ("\n", "<br />", $libri2);
	    $film2 = $row['film'];
	    $film = str_replace ("\n", "<br />", $film2);
	    $canzoni2 = $row['canzoni'];
	    $canzoni = str_replace ("\n", "<br />", $canzoni2);
	    $link2 = $row['link'];
	    $amo2 = $row['amo'];
	    $amo = str_replace ("\n", "<br />", $amo2);
	    $odio2 = $row['odio'];
	    $odio = str_replace ("\n", "<br />", $odio2);
	    $umore = $row['umore'];
	    $citazione = $row['citazione'];
	    $click = $row['click'];
	    $bloglink2 = $row['link_blog'];
	    $maxmess = $row['maxmess'];
            $link = "";

	    if ($link2) {
	    	$stringa = unserialize($link2);
	    	foreach ($stringa AS $linktitle => $linkurl) {
			$link .= "<a href=\"$linkurl\"  target=\"_blank\">$linktitle</a><br />";
	    	}
	    }

        $counter = str_pad($click, 6, "0", STR_PAD_LEFT);

		//$linkbutton = "<a href=\"$this->sitehome/linka_blog.php?mid=$idu\"><img src=\"images/addblog.gif\" alt=\"\" /></a><br />";

        $template = str_replace("!BlogTitle!", $titolo, $template);
        $template = str_replace("!BlogDescription!", $descrizione, $template);
        $template = str_replace("!UtenteEta!", $eta, $template);
        $template = str_replace("!UtenteSegno!", $segno, $template);
        $template = str_replace("!UtenteCitta!", $citta, $template);
        $template = str_replace("!UtenteLibri!", $libri, $template);
        $template = str_replace("!UtenteFilm!", $film, $template);
        $template = str_replace("!UtenteCanzoni!", $canzoni, $template);
        $template = str_replace("!UtenteLink!", $link, $template);
        $template = str_replace("!UtenteAmo!", $amo, $template);
        $template = str_replace("!UtenteOdio!", $odio, $template);
        $template = str_replace("!UtenteCitazione!", nl2br($citazione), $template);
        //$template = str_replace("!UtenteUmore!", "<img src=\"images/$umore.gif\" alt=\"\" />", $template);
        $template = str_replace("!BlogCounter!", "$counter", $template);
		//$template = str_replace("!LinkButton!", "$linkbutton", $template);
		//$template = str_replace("!BlogLink!", "$bloglink", $template);
	//magic words
	foreach ($mklib->lang as $key => $value) {
             	$pos = strpos($key, "magic_");
             	if ($pos === 0) {
                	$key = "{".strtoupper(substr($key, $pos + 6))."}";
			$template = str_replace($key, $value, $template);
	     	}
	}
	$archivio = "<a href=\"$mklib->siteurl/index.php?ind=blog&amp;op=home&amp;idu=$idu\">{$mklib->lang['b_lastdays']}</a><br />";
	$contomess = 0;
	while( $row = $DB->fetch_row($query1) ) {
            $idpost = $row['id'];
	    $post  = $row['post'];
	    $post = stripslashes($post);
	    $data  = $row['data'];
            $ncom  = $row['ncom'];
            if ($mklib->mkeditor == "BBCODE") {
		$post = $mklib->decode_bb($post);
		$post = $mklib_board->decode_smilies($post);
            }
            $ora = $mklib->create_date($data, "time");
            $mese = $mklib->create_date($data, "small");
            $datam = $mklib->create_date($data, "normal");
            $messaggio2 = "";
            $checkm = strcmp($mese, $curmese);

			if (!strpos($archivio, $mese)) {
                    $archivio .= "<a href=\"$mklib->siteurl/index.php?ind=blog&amp;op=home&amp;idu=$idu&amp;curmese=$mese\">$mese</a><br />";
            }
            if ($checkm != 0) {
				 if ($contomess >= $maxmess || $lastpage == 0) {
                	continue;
				 }
            }
            $checkd = strcmp($datam, $dataprec);
            $datains = "";
            if ($checkd != 0) {
                $datains= $datam;
            }
            if ($ncom > 0) {
                $ncom = "($ncom)";
            } else  {
                $ncom = "";
            }

            $messaggio2 .= $post;
            $messaggio = str_replace("!BlogPost!", $messaggio2, $template2);
            $messaggio = str_replace("!PostData!", $datains, $messaggio);
            $messaggio = str_replace("!BlogUtente!", $username, $messaggio);
            $messaggio = str_replace("!PostOra!", $ora, $messaggio);
	    $messaggio = str_replace("!PostCommenti!", " - <a href=\"$mklib->siteurl/index.php?ind=blog&amp;op=home&amp;idu=$idu&amp;singlepost=$idpost\">{$mklib->lang['b_permalink']}</a> - <a href=\"javascript:winny('index.php?ind=blog&amp;op=show_comments&amp;idm=$idpost')\" target=\"_self\">{$mklib->lang['b_mcomments']} $ncom</a><br />", $messaggio);

	    //magic words
	    foreach ($mklib->lang as $key => $value) {
             	$pos = strpos($key, "magic_");
             	if ($pos === 0) {
                	$key = "{".strtoupper(substr($key, $pos + 6))."}";
			$messaggio = str_replace($key, $value, $messaggio);
	     	}
	     }
	
	    
            $messaggi .= $messaggio;
			//$messaggi .= "$contomess > $maxmess $lastpage $mese, $curmese";
            $dataprec = $datam;
            ++$contomess;
        }

        $template = str_replace("!CorpoMessaggi!", $messaggi, $template);
        $template = str_replace("!UtenteArchivio!", "$archivio", $template);
		$template.="\n<script type=\"text/javascript\">
                    <!--
                    function winny(doctoopen) {
                    window.open(doctoopen, 'winny', 'scrollbars=yes,height=400,width=450');
                    }
                    //-->
                    </script>\n";

        return $template;

	}
    //-- magic words begin (Code by Peter - Peter@ibforen.de)
    function show_magic_words() {
        global $mklib, $Skin, $mklib_board;
        
        $default_magics = array('BlogTitle' => $mklib->lang['b_title'],
                                'BlogDescription' => $mklib->lang['b_description'],
                                'UtenteEta' => $mklib->lang['b_age'],
                                'UtenteSegno' => $mklib->lang['b_zodiac'],
                                'UtenteCitta' => $mklib->lang['b_city'],
                                'UtenteLibri' => $mklib->lang['b_books'],
                                'UtenteFilm' => $mklib->lang['b_movies'],
                                'UtenteCanzoni' => $mklib->lang['b_songs'],
                                'UtenteCitazione' => $mklib->lang['b_cit'],
                                'UtenteLink' => $mklib->lang['b_links'],
                                'UtenteAmo' => $mklib->lang['b_love'],
                                'UtenteOdio' => $mklib->lang['b_hate'],
                                'BlogCounter' => $mklib->lang['b_clicks'],
                                );
	$css = $mklib_board->import_css();
        $output = "
 		<head>
		{$css}		
		</head>
	";
	$output .= "<table class=\"moduleborder\" cellspacing=\"1\" cellpadding=\"2\" width=\"98%\" align=\"center\" border=\"0\">\n";
        $output .= "<tr><td class=\"tdblock\" colspan=\"2\" ><b>".$mklib->lang['default_magic_words']."</b></td></tr>";
        foreach ($default_magics as $key => $value) {
            $output .= "<tr><td class=\"modulecell\" >!".$key."!</td><td class=\"modulecell\">{$mklib->lang['entry']}: $value</td>";
        }
	$output .= "<tr><td class=\"tdblock\" colspan=\"2\" ><b>".$mklib->lang['group_magic_words']."</b></td></tr>";
	
	foreach ($mklib->lang as $key => $value) {
            $pos = strpos($key, "magic_");
            if ($pos === 0) {
                $output .= "<tr><td class=\"modulecell\" >{".strtoupper(substr($key, $pos + 6))."}</td><td class=\"modulecell\">$value</td>";
            }
        }
	
        //$output .= "<tr><td class=\"tdblock\" colspan=\"2\" ><b>".$mklib->lang['group_d_magic_words']."</b></td></tr>";
        $output .= "</table>";
        print $output;
        exit();
    }
//-- magic words end
	
	function p_gal () {
		global $mkportals, $DB, $mklib, $Skin;
		
		$idu = $mkportals->member['id'];
		$mywidth = $mklib->config['blog_upload_width'];
		$mymaxup = $mklib->config['blog_upload_num'];
		$full = "";
		//check if user has a blog.
		$query = $DB->query("SELECT id, titolo, validate FROM mkp_blog WHERE id = '$idu'");
		//changed from "get_num_rows" to "fetch_row" to get blog title
		//$result = $DB->get_num_rows($query);
		$result = $DB->fetch_row($query);
        	if (!$result) {
			$message = "{$mklib->lang['b_c_b_w']}";
			$mklib->error_page($message);
			exit;
		}
		
		//check if can upload and show settings
		$query = $DB->query("SELECT id, file FROM mkp_blog_pimages WHERE iduser = '$idu' ORDER by id DESC");
		$mymax = $DB->get_num_rows($query);
		if ($mymax >= $mymaxup) {
			$full = "<tr><td><span class=\"mktxtcontr\">{$mklib->lang['b_maxuperr']}</span></td></tr>";
		}
		
		$content .= "
		<script type=\"text/javascript\">
			function makesure() {
			if (confirm('{$mklib->lang[b_delimgconfirm]}')) {
			return true;
			} else {
			return false;
			}
			}
	    </script>
		<tr>
		  <td>
		  <table class=\"tabnews\" cellspacing=\"2\" cellpadding=\"2\" width=\"100%\">
		  <tbody>
		  <tr>
			<td class=\"tdblock\" valign=\"top\">{$mklib->lang['b_pgalset']}</td>
	         </tr>
		 <tr>
			<td>{$mklib->lang['b_maxim_bg']} <b> $mymaxup </b></td>
	         </tr>
		 <tr>
			<td>{$mklib->lang['b_maxim_bgl']} <b> $mywidth </b></td>
	         </tr>
		  $full
		  </tbody>
		  </table>
		  </td>
		</tr>
		";
		//show images
$content.= "<td><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\"><tr>";
		while( $row = $DB->fetch_row($query) ) {
		$idimage = $row['id'];
		$imageurl = $mklib->siteurl."/mkportal/blog/images/".$row['file'];
		$image = $row['file'];
		$thumb_mes = $mklib->ResizeImage(360,"mkportal/blog/images/$image");
		$content.= "<td align=\"center\" valign=\"bottom\">";
		$content.= "<a href=\"$imageurl\">$image</a>";
		$content.= "<table border=\"0\" width=\"100\" cellspacing=\"0\" cellpadding=\"0\">";
  		$content.= "<tr>";
    		$content.= "<td width=\"1%\" style=\"font-size: 4px;\"><img src=\"$mklib->images/a_sx_a.gif\" style=\"vertical-align: bottom\" alt=\"\" /></td>";
    		$content.= "<td width=\"98%\" style=\"background-image: url($mklib->images/a_sf_s.gif); font-size: 4px;\"><img src=\"$mklib->images/a_sf_s.gif\" height=\"8\" style=\"vertical-align: bottom\" alt=\"\" /></td>";
    		$content.= "<td width=\"1%\" style=\"font-size: 4px;\"><img src=\"$mklib->images/a_dx_a.gif\" style=\"vertical-align: bottom\" alt=\"\" /></td>";
  		$content.= "</tr><tr>";
    		$content.= "<td width=\"1%\" style=\"background-image: url($mklib->images/a_sx_s.gif);\"><img src=\"$mklib->images/a_sx_s.gif\" width=\"11\" height=\"15\" alt=\"\" /></td>";
    		$content.= "<td width=\"98%\" style=\"background-color:#ffffff;\" align=\"center\">";
		$content.= "<img src=\"mkportal/blog/images/$image\" width='$thumb_mes[0]' height='$thumb_mes[1]' border=\"0\" alt=\"\" />";
		$content.= "</td>";
   		$content.= "<td width=\"1%\" style=\"background-image: url($mklib->images/a_dx_s.gif);\"><img src=\"$mklib->images/a_dx_s.gif\" width=\"11\" height=\"14\" alt=\"\" /></td>";
  		$content.= "</tr><tr>";
    		$content.= "<td width=\"1%\"><img src=\"$mklib->images/a_sx_g.gif\" height=\"22\" style=\"vertical-align: top\" alt=\"\" /></td>";
    		$content.= "<td width=\"98%\" style=\"background-image: url($mklib->images/a_sf_g.gif);\"></td>";
    		$content.= "<td width=\"1%\" valign=\"top\"><img src=\"$mklib->images/a_dx_g.gif\" height=\"22\" style=\"vertical-align: top\" alt=\"\" /></td>";
  		$content.= "</tr></table>";
		$content.= " <a href=\"index.php?ind=blog&amp;op=delete_im&amp;idim=$idimage\" onclick=\"return makesure()\">{$mklib->lang['b_idelete']}</a> <br /><br />";
		$content.= "</td>";
		$content.= "</tr>";
		
			
		}
		$content.= "</tr></table></td>";
			
		$content .= "
		<tr>
		  <td>
		  <br />
		  <table class=\"tabnews\" cellspacing=\"2\" cellpadding=\"2\" width=\"100%\">
		  <tbody>
		  <tr>
		  <td> 
		    <form action=\"index.php?ind=blog&amp;op=upload_imm\" name=\"UPLOAD\" method=\"post\" enctype=\"multipart/form-data\">
		    <br />
		    <table width=\"100%\" border=\"0\">
			<tr>
			  <td width=\"10%\">{$mklib->lang['b_file']}</td>
			  <td width=\"90%\"><input type=\"file\" name=\"FILE_UPLOAD\" size=\"50\" class=\"bgselect\" /></td>
			</tr>
			<tr>
			  <td colspan=\"2\"><input type=\"submit\" value=\"{$mklib->lang['b_addfile']}\" class=\"mkbutton\" /></td>
			</tr>
		      </table>
		      </form>
		      </td>
		      </tr>
		      </tbody>
		     </table>
		    </td>
		  </tr>
		";
		$output = $Skin->view_block("{$mklib->lang['b_pgaltit']}", $content);
		$mklib->printpage_blog("1", "1", $mklib->sitename.$mklib->lang['tt_sep'].$mklib->lang['b_pagetitle'].$mklib->lang['tt_sep'].$result['titolo'].$mklib->lang['tt_sep'].$mklib->lang['b_pgaltit'], $output);
	}
	function upload_imm () {
		global $mkportals, $DB, $mklib, $Skin, $_FILES;

		if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_send_blog']) {
			$message = "{$mklib->lang['b_unauthw']}";
			$mklib->error_page($message);
			exit;
		}

		$iduser = $mkportals->member['id'];
	
		//check if can uplaod
		$query = $DB->query("SELECT id FROM mkp_blog_pimages WHERE iduser = '$iduser'");
		$mymax = $DB->get_num_rows($query);
		if ($mymax >= $mklib->config['blog_upload_num']) {
			$message = "{$mklib->lang['b_maxuperr']}";
			$mklib->error_page($message);
			exit;
		}
		$iduser = $mkportals->member['id'];
		//check if dir is writable.
		$dir = "./mkportal/blog/images/";
		@copy("mkportal/blog/images/index.html", "mkportal/blog/images/test");
		if(!is_file("mkportal/blog/images/test") ) {
			$message = "{$mklib->lang['b_nopermupl']}";
			$mklib->error_page($message);
			exit;
		}
		@unlink("mkportal/blog/images/test");
		
		$file =  $_FILES['FILE_UPLOAD']['tmp_name'];
		$file_name =  $_FILES['FILE_UPLOAD']['name'];
		//$file_type =  $_FILES['FILE_UPLOAD']['type'];
		$peso =  $_FILES['FILE_UPLOAD']['size'];		
		
		if (!$file) {
			$message = "{$mklib->lang['b_compfile']}";
			$mklib->error_page($message);
			exit;
		}
		
		//Validate file extension
		//Edited by Kimi in C1.2.2 (code by visiblesoul)
		$file_ext = preg_replace("`.*(\..*)`", "\\1", $file_name);
	//	$file_ext = substr ($file_name, (strlen($file_name)-4), 4); //Removed in C1.2.2 by Kimi (thanks to BMG)
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
			$message = "{$mklib->lang['b_gnotsup']}";
			$mklib->error_page($message);
			exit;
		}

		//Validate image width
		if ($peso > ($mklib->config['blog_upload_width']*1024)) {
			$message = "{$mklib->lang['b_maxwupl']}";
			$mklib->error_page($message);
			exit;
		}

		//Move file from server tmp directory to blog "tmp" directory			
		if (!move_uploaded_file("$file", "mkportal/blog/images/tmp/$file_name")) {
			$message = "{$mklib->lang['b_nopermupl']}";
			$mklib->error_page($message);
			exit;
		}
		@chmod("mkportal/blog/images/tmp/$file_name", 0644);
	
		//Validate by mime type
		$tmpfilename = "mkportal/blog/images/tmp/$file_name";
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
			$message .= "{$mklib->lang['b_gnotsup']}";
			$mklib->error_page($message);
			exit;
		}

		//Validate by file contents
		$fcontents = file_get_contents ($tmpfilename);
		$carray = array("html", "javascript", "vbscript", "alert", "onmouseover", "onclick", "onload", "onsubmit");		
		foreach ($carray as $fch) {
            		if (strstr($fcontents, $fch)) {
                		@unlink($tmpfilename);
				$message .= "{$mklib->lang['error_filetype']}";
				$mklib->error_page($message);
                		exit;
            		}
        	}
        	if (preg_match("#script(.+?)/script#ies", $fcontents)) {
           		@unlink($tmpfilename);
			$message .= "{$mklib->lang['error_filetype']}";
			$mklib->error_page($message);
            		exit;
		}
		
		$query = $DB->query("SELECT id FROM mkp_blog_pimages ORDER BY id DESC LIMIT 1");
		$row = $DB->fetch_row($query);
		$totr = $row['id'];
		++$totr;

		$image = $totr.$file_name;

		//move file from "tmp" directory to "images" directory
		@rename($tmpfilename, "mkportal/blog/images/$image");

		if (!is_file ("mkportal/blog/images/$image")) {
			$message = "{$mklib->lang['b_snoupl']}";
			$mklib->error_page($message);
			exit;
		}
		$query="INSERT INTO mkp_blog_pimages(iduser, file)VALUES('$iduser', '$image')";
		$DB->query($query);
		$DB->close_db();

	 	Header("Location: index.php?ind=blog&op=p_gal");
		exit;
	}
	function delete_im() {
    	global $mkportals, $mklib, $DB;

		if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_send_blog']) {
			$message = "{$mklib->lang['b_unauthc']}";
			$mklib->error_page($message);
			exit;
		}

		$idim = intval($mkportals->input['idim']);
		$iduser = $mkportals->member['id'];

		$query = $DB->query("SELECT file FROM mkp_blog_pimages WHERE iduser = '$iduser' AND id = '$idim'");
		$row = $DB->fetch_row($query);
		$file = $row['file'];
		if ($file) { 
			@unlink("mkportal/blog/images/$file");
			$DB->query("DELETE FROM mkp_blog_pimages WHERE id = $idim");
		}
		$DB->close_db();
		Header("Location: index.php?ind=blog&op=p_gal");
		exit;

	}
	function clean_template ($t="") {

        $t = str_replace( "&#"			, "", $t );
        $t = str_replace( "&gt;"		, ">", $t );
        $t = str_replace( "&lt;"		, "<", $t );
        $t = str_replace( "<?"			, "", $t );
        $t = str_replace( "?"			, "", $t );
		//added by Kimi in C1.2.2
		$t = str_replace( "'" , "&#39;", $t ); //Thanks Hondo
		$t = str_replace( "%" , "&#38;", $t );
		$t = str_replace( "_" , "&#95;", $t );
		
		//edited by Kimi in C1.2.2
		/*
        while( preg_match( "#script(.+?)/script#ies", $t ) ) {
                $t = preg_replace( "#script(.+?)/script#ies", "" , $t);
        }
		*/
		//added by Kimi in C1.2.2
		$t = preg_replace( "#<(\s+?)?s(\s+?)?c(\s+?)?r(\s+?)?i(\s+?)?p(\s+?)?t#si"        , "" , $t );
		$t = preg_replace( "#<(\s+?)?/(\s+?)?s(\s+?)?c(\s+?)?r(\s+?)?i(\s+?)?p(\s+?)?t#si", "", $t );
		
        $t = preg_replace( "/javascript/i"	, "", $t );
        //$t = preg_replace( "/about/i"		, "", $t );
        $t = preg_replace( "/vbscript/i"	, "", $t );
        $t = preg_replace( "/alert/i"		, "", $t );
        $t = preg_replace( "/onmouseover/i"	, "", $t );
        $t = preg_replace( "/onclick/i"		, "", $t );
        $t = preg_replace( "/onload/i"		, "", $t );
        $t = preg_replace( "/onsubmit/i"	, "", $t );
		
    //added by Kimi in C1.2.2
	$t = preg_replace( "/<salertcript/i"    , "", $t );
	$t = preg_replace( "/aalertlert/i"      , "", $t );
	$t = preg_replace( "/aleonsubmitrt/i"   , "", $t );
	$t = preg_replace( "/ononsubmitload/i"	, "", $t );
	$t = preg_replace( "/onfinish/i"        , "", $t );

	//added by visiblesoul C1.2 rc2
	//$t = preg_replace( "#<(\s+?)?s(\s+?)?c(\s+?)?r(\s+?)?i(\s+?)?p(\s+?)?t#si", "", $t );
	//$t = preg_replace( "#<(\s+?)?/(\s+?)?s(\s+?)?c(\s+?)?r(\s+?)?i(\s+?)?p(\s+?)?t#si", "", $t );
	$t = preg_replace( "/ecmascript/i"	, "", $t );
 	$t = preg_replace( "/about:/si"		, "", $t );
	$t = preg_replace( "/data:/si"		, "", $t );
	$t = preg_replace( "/onfocus/i"		, "", $t );
	$t = preg_replace( "/onblur/i"		, "", $t );
	$t = preg_replace( "/ondblclick/i"	, "", $t );
	$t = preg_replace( "/onmousedown/i"	, "", $t );
	$t = preg_replace( "/onmouseup/i"	, "", $t );
	$t = preg_replace( "/onmousemove/i"	, "", $t );
	$t = preg_replace( "/onmouseout/i"	, "", $t );
	$t = preg_replace( "/onkeypress/i"	, "", $t );
	$t = preg_replace( "/onkeydown/i"	, "", $t );
	$t = preg_replace( "/onkeyup/i"		, "", $t );
	$t = preg_replace( "/onunload/i"	, "", $t );
        $t = preg_replace( "/onabort/i"		, "", $t );
        $t = preg_replace( "/onerror/i"		, "", $t );
	$t = preg_replace( "/onchange/i"	, "", $t );
	$t = preg_replace( "/onreset/i"		, "", $t );
	$t = preg_replace( "/onselect/i"	, "", $t );	
	$t = preg_replace( "/document\./i"	, "", $t );
	$t = preg_replace( "/window\./i"	, "", $t );
	
	$t = preg_replace( "/<base/i"		, "&lt;base",	$t );	
	$t = preg_replace( "/<applet/i"		, "&lt;applet", $t );
	$t = preg_replace( "/<embed/i"		, "&lt;embed",	$t );
	$t = preg_replace( "/<object/i"		, "&lt;object", $t );
	$t = preg_replace( "/<link/i"		, "&lt;link",	$t );
	$t = preg_replace( "/<iframe/i"		, "&lt;iframe", $t );
	$t = preg_replace( "/<frame/i"		, "&lt;frame",	$t );
	$t = preg_replace( "/<frameset/i" 	, "&lt;frameset",$t );
	//$t = preg_replace( "/<style/i"	, "&lt;style"	,$t );
	//$t = preg_replace( "/style([\s]*)=([\s]*)(?:'[^']*'|\"\"[^\"\"]*\"\"|[^\s>]+)/i", "", $t ); //strip style attributes

        return $t;
    }

	//Prepare $_POST data for serialization and insertion into database
	function convert_savedb_bloglink($t="")
	{
		global $mklib;

		$t = str_replace( "&#39;"   , "'", $t );
		$t = str_replace( "&#33;"   , "!", $t );
		$t = str_replace( "&#036;"   , "$", $t );
		$t = str_replace( "&#124;"  , "|", $t );
		$t = str_replace( "&amp;"   , "&", $t );
		$t = str_replace( "&gt;"    , ">", $t );
		$t = str_replace( "&lt;"    , "<", $t );
		$t = str_replace( "&quot;"  , '"', $t );
		$t = $mklib->clean_script($t);

		//single and double quotes reverted
		$t = str_replace( "'", "&#39;", $t );
		$t = str_replace( '"'  , "&quot;", $t );

		$t = stripslashes($t); //Slashes are not allowed in serialized array		

		return $t;
	}
	function update_total() {
		global $DB;
		$query = $DB->query( "SELECT id FROM mkp_blog WHERE validate = '1'");
		$count = $DB->get_num_rows($query);
		$DB->query("UPDATE mkp_stat SET valore ='$count' WHERE chiave = 'tot_blog'");
	
	}
	function update_last() {
		global $DB;
		$query = $DB->query("SELECT id, id_blog, post FROM mkp_blog_post ORDER BY id DESC LIMIT 1");
		$row = $DB->fetch_row($query);
		$post = addslashes($post); //do not use convert_savedb
		$idblog = $row['id_blog'];
		$DB->query("UPDATE mkp_stat SET valore ='$idblog' WHERE chiave = 'blog_id_blog'");
		$DB->query("UPDATE mkp_stat SET valore ='$post' WHERE chiave = 'blog_post'");
		$query = $DB->query( "SELECT titolo FROM mkp_blog WHERE id = '$idblog'");
		$row = $DB->fetch_row($query);
		$titolo = addslashes($row['titolo']); //do not use convert_savedb
		$DB->query("UPDATE mkp_stat SET valore ='$titolo' WHERE chiave = 'blog_titolo'");	
	}
}
?>
