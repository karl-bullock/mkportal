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

$idx = new mk_topsite;
class mk_topsite {

	var $tpl       = "";

	function mk_topsite() {
	global $mkportals, $DB, $mklib, $Skin, $mklib_board;

	$mklib->load_lang("lang_topsite.php");

	if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_access_topsite']) {
			$message = "{$mklib->lang['to_unauth']}";
			$mklib->error_page($message);
			exit;
		}
		if ($mklib->config['mod_topsite']) {
		$message = "{$mklib->lang['to_mnoactive']}";
			$mklib->error_page($message);
			exit;
		}

		//location
		$mklib_board->store_location("topsite");

    		switch($mkportals->input['op']) {
    			case 'reg_data':
    				$this->reg_data();
    			break;
    			case 'viev_site':
    				$this->viev_site();
    			break;
    			case 'delete':
    				$this->delete();
    			break;
    			case 'edit':
    				$this->edit();
    			break;
    			case 'edit_save':
    				$this->edit_save();
    			break;
				case 'submit_site':
    				$this->submit_site();
    			break;
    			case 'click_site':
    				$this->click_site();
    			break;
				case 'submit_rate':
    				$this->submit_rate();
    			break;
				case 'add_rate':
    				$this->add_rate();
    			break;
    			default:
    				$this->topsite_show();
    			break;
    		}
	}
function viev_site() {
		global $mkportals, $DB, $std, $print, $mklib, $Skin, $mklib_board;
		$idb = intval($mkportals->input['idb']);
		$startformat = "short";
		$link_user = $mklib_board->forum_link("profile");
		$query = $DB->query( "SELECT id, id_member, autor, data, title, description, link, banner, click, rate, trate FROM mkp_topsite WHERE id = '$idb'");

		while( $row = $DB->fetch_row($query) ) {
			$idb = $row['id'];
			$id_member = $row['id_member'];
			$autor = $row['autor'];
			$data = $mklib->create_date($row['data'], $startformat);
			$banner = $row['banner'];
			$click = $row['click'];
			$link = $row['link'];
			$links = str_replace("http://", "", $link);
			$links2 = str_replace("/", "", $links);
			$titolo = $row['title'];
			$descrizione = $row['description'];
			$rate = $row['rate'];
			$trate = $row['trate'];
			$width = round(($rate*100)/4) - 6;
	 		$width2 = $width - 4;
			if ($mklib->mkeditor == "BBCODE") {
			$descrizione = $mklib->decode_bb($descrizione);
			//$descrizione = $mklib_board->decode_smilies($descrizione);
		}
		}
			$modname ="topsite";
			$rating = $mklib->pullRating($idb, $modname, $rate, $trate);
			$links = str_replace("http://", "", $link);
			$links2 = str_replace("/", "", $links);
			$img2 = "<img style='height:152px;width:202px'src='http://images.websnapr.com/?url=$links&size=s'>" ;
			//$img = "<img src='http://webmorda.kz/site2img/?u={$links2}&s=b&q=5&r=1440_900'>" ;
			$img ="<img src='$banner'>";
			if(!$banner){
						$img = "<img src='http://webmorda.kz/site2img/?u={$links2}&s=b&q=5&r=1440_900'>" ;
			}
			$submits = "<br /><a href=\"/index.php?ind=topsite&amp;op=submit_site\"> [ {$mklib->lang['to_sign']} ]</a>";
	if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_send_topsite']) {
			$submits ="&nbsp;&nbsp;&nbsp;";
	}
	if($mkportals->member['g_access_cp']) {
			$editdel ="	<td class=\"modulecell\" align=\"right\"><a href=\"$mklib->siteurl//index.php?ind=topsite&amp;op=delete&amp;idb=$idb\">{$mklib->lang['to_delete']}</a>
			&nbsp;&nbsp;<a href=\"$mklib->siteurl//index.php?ind=topsite&amp;op=edit&amp;idb=$idb\">{$mklib->lang['to_edit']}</a></td>";
	}
		
			$output .= "<tr>
  <td>
    <table cellspacing=\"1\" cellpadding=\"0\" width=\"98%\" align=\"center\" border=\"0\">
      <tr>
	<td class=\"mkalign2\">
	$submits
	</td>
      </tr>
    </table>";
		$output .= "<tr>
			  <td>
			    <table cellspacing=\"1\" cellpadding=\"5\" width=\"100%\" border=\"0\">
		<tr>
		<td class=\"modulecell\" align=\"left\">{$mklib->lang['to_goviev']}&nbsp;<a href=\"$mklib->siteurl//index.php?ind=topsite&amp;op=click_site&amp;idb=$idb\" target=\"_blank\"><b>$link</b></a></td>
		<td class=\"modulecell\" align=\"right\">{$mklib->lang['to_datasend']}&nbsp;<b>$data</b></td>
		</tr>
		<tr>
		<td class=\"modulecell\" align=\"left\">{$mklib->lang['to_poster']}&nbsp;<a href=\"$link_user=$id_member\"><b>$autor</b></a></td>
		<td class=\"modulecell\" align=\"right\">{$mklib->lang['to_clicks']}:&nbsp;<b>$click</b></td>
		</tr>
		<tr>
		<td class=\"modulecell\" align=\"left\">$rating</td>
		$editdel
		</tr>
		</table>
			  </td>
			</tr>
		<tr>
			  <td>
			    <table cellspacing=\"1\" cellpadding=\"5\" width=\"100%\" border=\"0\">
		<tr>
		<td class=\"modulecell\" align=\"center\"><a href=\"$mklib->siteurl//index.php?ind=topsite&amp;op=click_site&amp;idb=$idb\" target=\"_blank\"><span style=\"color: #ff9900;\"><span style=\"font-size: small;\"><b>$titolo</b></span></span></a></td>
		</tr>
		<tr>
		<td class=\"modulecell\" align=\"center\"><a href=\"$mklib->siteurl//index.php?ind=topsite&amp;op=click_site&amp;idb=$idb\" target=\"_blank\">$img</a></td>
		</tr>
		<tr>
		<td class=\"modulecell\" align=\"left\">{$mklib->lang['to_descript']}&nbsp;$descrizione</td>
		</tr>";
		$output .= " </table>
			  </td>
			</tr>";
		$output .= "<tr>
			  <td>
			    <table cellspacing=\"1\" cellpadding=\"5\" width=\"100%\" border=\"0\">
		<tr>
		<td class=\"modulecell\" align=\"center\"><input type=\"submit\" OnClick=\"window.open('$link')\" value=\"{$mklib->lang['to_sitevievs']}\"></td>
		</tr>";
		$output .= "
		 </table>
			  </td>
			</tr>";
		$output .="<tr>
	  <td align=\"center\"><br /><br />
	    <div align=\"center\"><a href=\"http://www.rusmkportal.ru\" target=\"_blank\">MKPTopSite</a> &copy;2007-2009 <a href=\"http://www.rusmkportal.ru\" target=\"_blank\">www.rusmkportal.ru</a></div>
	  </td>
	</tr>";
$blocks = $Skin->view_block("{$mklib->lang['to_title']}{$mklib->lang['tt_sep']}$titolo", $output);
$mklib->printpage("1", "1", "{$mklib->lang['to_title']}{$mklib->lang['tt_sep']}$titolo", $blocks);		
}
function delete() {
    		global $mkportals, $DB, $std, $mklib;

		if(!$mkportals->member['g_access_cp']) {
			$message = "{$mklib->lang['to_title']}";
			$mklib->error_page($message);
			exit;
		}

		$id = intval($mkportals->input['idb']);

		$DB->query("DELETE FROM mkp_topsite WHERE id = $id");
		$DB->query("DELETE FROM mkp_votes WHERE id_entry = $id AND module = 'topsite'");
		$DB->close_db();
	 	Header("Location: /index.php?ind=topsite");
		exit;
	}
	function edit() {
		global $mkportals, $DB, $std, $print, $mklib, $Skin, $mklib_board;

		if(!$mkportals->member['g_access_cp']) {
			$message = "{$mklib->lang['to_title']}";
			$mklib->error_page($message);
			exit;
		}

		$idb = intval($mkportals->input['idb']);
$query = $DB->query( "SELECT id, id_member, autor, data, title, description, link, banner, click, rate, trate, email FROM mkp_topsite WHERE id = '$idb'");

		while( $row = $DB->fetch_row($query) ) {
			$idb = $row['id'];
			$id_member = $row['id_member'];
			$autor = $row['autor'];
			$data = $mklib->create_date($row['data'], $startformat);
			$banner = $row['banner'];
			$click = $row['click'];
			$link = $row['link'];
			$links = str_replace("http://", "", $link);
			$links2 = str_replace("/", "", $links);
			$titolo = $row['title'];
			$rate = $row['rate'];
			$trate = $row['trate'];
			$email = $row['email'];
			$descrizione = $row['description'];

			$descrizione = str_replace("<br />", "\n", $descrizione);
		}
		
		
		$output = "

<tr>
  <td><br />
    <table class=\"moduleborder\" cellspacing=\"1\" cellpadding=\"0\" width=\"98%\" align=\"center\" border=\"0\">
      <tr>
	<td>
	  <table class=\"modulebg\" cellspacing=\"1\" cellpadding=\"0\" width=\"100%\" border=\"0\">
	    <tr>
	      <td>
		<table cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" border=\"0\">
		  <tr>
		    <td>
		      <table cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" border=\"0\">
			<tr>			
			  <td class=\"modulex\">
			  
			    <form action=\"/index.php?ind=topsite&amp;op=edit_save&amp;idb=$idb\" name=\"e_b\" method=\"post\">
			    <table width=\"100%\" border=\"0\">
			      <tr>
				<td class=\"titadmin\"><br />{$mklib->lang['to_edit_site']}{$mklib->lang['tt_sep']}$titolo<br /><br /></td>
			      </tr>
			      <tr>
				<td>{$mklib->lang['to_sitename']}</td>
			      </tr>
			      <tr>
				<td><input type=\"text\" name=\"title\" value=\"$titolo\" size=\"70\" class=\"bgselect\"></td>
			      </tr>
			      <tr>
				<td>{$mklib->lang['to_siteurl']}</td>
			      </tr>
			      <tr>
				<td><input type=\"text\" name=\"link\" value=\"$link\" size=\"70\" class=\"bgselect\" /></td>
			      </tr>
			      <tr>
				<td>{$mklib->lang['to_emailw']}</td>
			      </tr>
			      <tr>
				<td><input type=\"text\" name=\"email\" value=\"$email\" size=\"70\" class=\"bgselect\" /></td>
			      </tr>
		<td>{$mklib->lang['to_bannerurl']}</td>
			      </tr>
			      <tr>
				<td><input type=\"text\" name=\"banner\" value=\"$banner\" size=\"70\" class=\"bgselect\" /></td>
			      </tr>
			      <tr>			      
				<td>{$mklib->lang['to_sitedes']}</td>
			      </tr>
			      <tr>
				<td> <textarea rows=\"15\" name=\"description\" cols=\"100\">$descrizione</textarea>
		</td>
			      </tr>
			      
			      <tr>
				<td><input type=\"submit\" value=\"{$mklib->lang['to_senddata']}\" class=\"mkbutton\" /></td>
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
  <div align=\"center\"><a href=\"http://www.rusmkprtal.ru\" target=\"_blank\">MKPTopSite</a> &copy;2007-2009 <a href=\"http://www.rusmkprtal.ru\" target=\"_blank\">rusmkprtal.ru</a></div>
  </td>
</tr>
	";

		$blocks = $Skin->view_block($mklib->lang['to_edit_site'], $output);
		$mklib->printpage("1", "1", $mklib->sitename.$mklib->lang['tt_sep'].$mklib->lang['to_title'].$mklib->lang['tt_sep'].$mklib->lang['to_edit_site'], $blocks);

	}
	function edit_save() {
    		global $mkportals, $DB, $std, $mklib;

		if(!$mkportals->member['g_access_cp']) {
			$message = "{$mklib->lang['to_title']}";
			$mklib->error_page($message);
			exit;
		}

        $idb = intval($mkportals->input['idb']);
		$title = $mkportals->input['title'];
		$description = $mkportals->input['description'];
		$link = $mkportals->input['link'];
		$banner = $mkportals->input['banner'];
		$banner2 = $mkportals->input['banner2'];
		$email = $mkportals->input['email'];
		if (!$title || !$description || !$link || !$email) {
			$message = "{$mklib->lang['to_reqall']}";
			$mklib->error_page($message);
			exit;
		}
		if (!preg_match("`^http\://`i", $link)) {
            $link = preg_replace("`^.*\://`i", "", $link);
            $link  = "http://www".$link;
        }

		$DB->query("UPDATE mkp_topsite SET title = '$title', description ='$description', link='$link', email='$email', banner='$banner' WHERE id='$idb'");
		$DB->close_db();
	 	Header("Location: /index.php?ind=topsite&op=viev_site&idb=$idb");
		exit;
  }
	
	function topsite_show() {
		global $mkportals, $DB, $std, $print, $mklib, $Skin, $mklib_board;


		$start = intval($mkportals->input['start']);


		$query = $DB->query("SELECT id FROM mkp_topsite WHERE validate = '1'");
		$count = $DB->get_num_rows($query);

		$q_page		=	intval($mkportals->input['st']);
		if ($q_page=="" or $q_page <= 0) {
			$q_page	=	0;
		}
		$per_page = $mklib->config['topsite_page'];
		if ($per_page=="" or $per_page <= 0) {
			$per_page	=	10;
		}

	    $start = $q_page;
		$show_pages = $mklib->build_pages( array( TOTAL_POSS  => $count,
							PER_PAGE    => $per_page,
							CUR_ST_VAL  => $q_page,
							L_SINGLE    => '',
							L_MULTI     => 'pagine',
						    BASE_URL    => '/index.php?ind=topsite',
										  )
								   );

	$iduser = $mkportals->member['id'];

	$utenti_in = $mklib_board->get_active_users("topsite");

	$submits = "<br /><a href=\"/index.php?ind=topsite&amp;op=submit_site\"> [ {$mklib->lang['to_sign']} ]</a>";
	if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_send_topsite']) {
			$submits ="&nbsp;&nbsp;&nbsp;";
	}
		$output = "
<tr>
  <td>
    <table cellspacing=\"1\" cellpadding=\"0\" width=\"98%\" align=\"center\" border=\"0\">
      <tr>
	<td class=\"mkalign2\">
	$submits
	</td>
      </tr>
    </table>
    <br />
    <table class=\"moduleborder\" cellspacing=\"1\" cellpadding=\"0\" width=\"98%\" align=\"center\" border=\"0\">
      <tr>
	<td>
	  <table class=\"modulebg\" cellspacing=\"1\" cellpadding=\"0\" width=\"100%\" border=\"0\">
	    <tr>
	      <td class=\"tdblock\" width=\"100%\" height=\"25\"><img class=\"mkicon\" src=\"$mklib->images/arrow.gif\" alt=\"\" />{$mklib->lang['to_title']}</td>
	    </tr>
	    <tr>
	      <td>
		<table class=\"moduleborder\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" border=\"0\">
		  <tr>
		    <td>
		      <table class=\"moduleborder\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" border=\"0\">
			<tr>
			  <td>
			    <table cellspacing=\"1\" cellpadding=\"5\" width=\"100%\" border=\"0\">
			      <tr>
				<th class=\"modulex\" width=\"5%\" align=\"center\">{$mklib->lang['to_pos']}</th>
				<th class=\"modulex\" width=\"5%\" align=\"center\">{$mklib->lang['to_images']}</th>
				<th class=\"modulex\" width=\"75%\" align=\"center\">{$mklib->lang['to_site']}</th>
				<th class=\"modulex\" width=\"5%\" align=\"center\">{$mklib->lang['to_clicks']}</th>
				<th class=\"modulex\" width=\"10%\" align=\"center\">{$mklib->lang['to_mrate']}</th>
			      </tr>
	";

	$query = $DB->query( "SELECT id, title, description, link, banner, click, rate, trate FROM mkp_topsite WHERE validate = '1' ORDER BY `trate` DESC, `rate` DESC, `click` DESC  LIMIT $start, $per_page");
		$counterpos = $start +1;
		while( $row = $DB->fetch_row($query) ) {
			$idb = $row['id'];
			$id_member = $row['id_member'];
			$autor = $row['autor'];
			$data = $mklib->create_date($row['data'], $startformat);
			$banner = $row['banner'];
			$click = $row['click'];
			$link = $row['link'];
			$links = str_replace("http://", "", $link);
			$links2 = str_replace("/", "", $links);
			$titolo = $row['title'];
			$descrizione = $row['description'];
			$banner = $row['banner'];
			$rate = $row['rate'];
			$trate = $row['trate'];
			$width = round(($rate*100)/4) - 6;
	 		$width2 = $width - 4;
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
			if ($mklib->mkeditor == "BBCODE") {
			$descrizione = $mklib->decode_bb($descrizione);
		}
			$modname ="topsite";
			$rating = $mklib->pullRating($idb, $modname, $rate, $trate);
			$links = str_replace("http://", "", $link);
			$links2 = str_replace("/", "", $links);
			$img2 = "<img style='height:152px;width:202px'src='http://images.websnapr.com/?url=$links&size=s'>" ;
			$img ="<img style='height:152px;width:202px'src='$banner'>";
			if(!$banner){
			$img = "<img src='http://webmorda.kz/site2img/?u={$links2}&s=s&q=5&r={4}'>" ;
			}
			$output .= "
			      <tr>
				<td class=\"modulecell\" align=\"center\"><span class=\"mktxtcontr\">$counterimage</span></td>
				<td class=\"modulecell\" align=\"center\"><a href=\"$mklib->siteurl//index.php?ind=topsite&amp;op=click_site&amp;idb=$idb\" target=\"_blank\"><b>$img</b></a></td>
				<td class=\"modulecell\" align=\"left\"><a href=\"$mklib->siteurl//index.php?ind=topsite&amp;op=viev_site&amp;idb=$idb\"><span style=\"font-size: small;\">$titolo</span><br /></a><br />$descrizione<br /><br />{$mklib->lang['to_goviev']}&nbsp;<b><a href=\"$mklib->siteurl//index.php?ind=topsite&amp;op=click_site&amp;idb=$idb\" target=\"_blank\">$links2</b></a><br /></td>
				<td class=\"modulecell\" align=\"center\"><b>$click</b></td>
				<td class=\"modulecell\" align=\"left\">{$rating}</td>
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
  <div align=\"center\"><a href=\"http://www.mkportal.it\" target=\"_blank\">MKPTopSite</a> &copy;2003-2008 <a href=\"http://www.mkportal.it\" target=\"_blank\">mkportal.it</a></div>
  </td>
</tr>
	";
		//No block template - to prevent stretching the MKP wrapper
		//$mklib->printpage("1", "1", $mklib->sitename.$mklib->lang['tt_sep'].$mklib->lang['to_title'], $output);
	
		//Use block template - Because most users have 1024 x 768 monitor resolution now
		$blocks = $Skin->view_block("{$mklib->lang['to_title']}", $output);
		$mklib->printpage("1", "1", $mklib->sitename.$mklib->lang['tt_sep'].$mklib->lang['to_title'], $blocks);

	}
	function submit_site() {
		global $mkportals, $DB, $mklib, $Skin;

		$iduser = $mkportals->member['id'];
		//$mode = $mkportals->input['mode']; //deprecated

		if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_send_topsite']) {
			$message = "{$mklib->lang['to_nosign']}";
			$mklib->error_page($message);
			exit;
		}
		
		$output = "
<tr>
  <td><br />
    <table class=\"moduleborder\" cellspacing=\"1\" cellpadding=\"0\" width=\"98%\" align=\"center\" border=\"0\">
      <tr>
	<td>
	  <table class=\"modulebg\" cellspacing=\"1\" cellpadding=\"0\" width=\"100%\" border=\"0\">
	    <tr>
	      <td class=\"tdblock\" width=\"100%\" height=\"25\"><img class=\"mkicon\" src=\"$mklib->images/arrow.gif\" alt=\"\" />{$mklib->lang['to_sign']}</td>
	    </tr>
	    <tr>
	      <td>
		<table cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" border=\"0\">
		  <tr>
		    <td>
		      <table cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" border=\"0\">
			<tr>			
			  <td class=\"modulex\">
			  
			    <form action=\"/index.php?ind=topsite&amp;op=reg_data\" name=\"e_b\" method=\"post\">
			    <table width=\"100%\" border=\"0\">
			      <tr>
				<td class=\"titadmin\"><br />{$mklib->lang['to_instruction']}<br /><br /></td>
			      </tr>
			      <tr>
				<td>{$mklib->lang['to_sitename']}</td>
			      </tr>
			      <tr>
				<td><input type=\"text\" name=\"title\" size=\"70\" class=\"bgselect\" /></td>
			      </tr>
			      <tr>
				<td>{$mklib->lang['to_siteurl']}</td>
			      </tr>
			      <tr>
				<td><input type=\"text\" name=\"link\" size=\"70\" class=\"bgselect\" /></td>
			      </tr>
			      <tr>
				<td>{$mklib->lang['to_emailw']}</td>
			      </tr>
			      <tr>
				<td><input type=\"text\" name=\"email\" size=\"70\" class=\"bgselect\" /></td>
			      </tr>
		<td>{$mklib->lang['to_bannerurl']}</td>
			      </tr>
			      <tr>
				<td><input type=\"text\" name=\"banner\" size=\"70\" class=\"bgselect\" /></td>
			      </tr>
			      <tr>			      
				<td>{$mklib->lang['to_sitedes']}</td>
			      </tr>
			      <tr>
				<td> <textarea rows=\"15\" name=\"description\" cols=\"100\"></textarea>
		</td>
			      </tr>
			      
			      <tr>
				<td><input type=\"submit\" value=\"{$mklib->lang['to_senddata']}\" class=\"mkbutton\" /></td>
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
  <div align=\"center\"><a href=\"http://www.mkportal.it\" target=\"_blank\">MKPTopSite</a> &copy;2003-2008 <a href=\"http://www.mkportal.it\" target=\"_blank\">mkportal.it</a></div>
  </td>
</tr>
	";

		$blocks = $Skin->view_block($mklib->lang['to_sign'], $output);
		$mklib->printpage("1", "1", $mklib->sitename.$mklib->lang['tt_sep'].$mklib->lang['to_title'].$mklib->lang['tt_sep'].$mklib->lang['to_sign'], $blocks);

	}

	function reg_data() {
		global $mkportals, $mklib, $Skin, $DB, $mklib_board;

		if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_send_topsite']) {
			$message = "{$mklib->lang['to_nosign']}";
			$mklib->error_page($message);
			exit;
		}

		$id_member = intval($mkportals->member['id']);
		$member_name = $mkportals->member['name'];
		$title = $mkportals->input['title'];
		$description = $mkportals->input['description'];
		$link = $mkportals->input['link'];
		$banner = $mkportals->input['banner'];
		$banner2 = $mkportals->input['banner2'];
		$email = $mkportals->input['email'];
		$data = time();


		if (!$title || !$description || !$link || !$email) {
			$message = "{$mklib->lang['to_reqall']}";
			$mklib->error_page($message);
			exit;
		}

		if (strstr($link, "admin/index.php") || strstr($email, "admin/index.php") || strstr($link, "{$mklib->adminpath}/index.php") || strstr($email, "{$mklib->adminpath}/index.php")) {
			$message = "{$mklib->lang['error_noallow']}";
			$mklib->error_page($message);
			exit;
		}

		if (!preg_match("`^http\://`i", $link)) {
            $link = preg_replace("`^.*\://`i", "", $link);
            $link  = "http://www.".$link;
        }
      if (!preg_match("`^http\://`i", $banner)) {
            $banner = preg_replace("`^.*\://`i", "", $banner);
            if(!$banner){$banner = "";}
            else {
            $banner  = "http://".$banner;
            }
        }
        if (!preg_match("`^http\://`i", $banner2)) {
            $banner2 = preg_replace("`^.*\://`i", "", $banner2);
            $banner2  = "http://".$banner2;
        }
        if (!preg_match("`^http\://`i", $banner2)) {
            $banner2 = preg_replace("`^.*\://`i", "", $banner2);
            $banner2  = "http://".$banner2;
        }

		$validat = "1";
		$approval = $mklib->config['approval_topsite'];
		if ($approval == "2" || $approval == "3") {
			$validat = 0;
		}
		if($mkportals->member['g_access_cp']) {
			$validat = "1";
		}

		$query="INSERT INTO mkp_topsite(id_member, autor, data, title, description, link, banner, banner2, email, validate)VALUES('$id_member', '$member_name', '$data', '$title', '$description', '$link', '$banner', '$banner2', '$email', '$validat')";
		$DB->query($query);
		
		if ($approval == "1") {
			$mailsubj = $mklib->lang['01mail'].$mklib->lang['topsite'];
			$mailmess = $mklib->lang['02mail'].$mklib->lang['module'].$mklib->lang['topsite']."\n".$mklib->lang['sender'].$email."\n\n\n".$mklib->lang['from']." ".$mklib->sitename;
			$mklib_board->admin_mail($mailsubj, $mailmess);
		}
		if ($approval == "2" && !$mkportals->member['g_access_cp']) {
			$mailsubj = $mklib->lang['01mail'].$mklib->lang['topsite'];
			$mailmess = $mklib->lang['03mail'].$mklib->lang['module'].$mklib->lang['topsite']."\n".$mklib->lang['sender'].$email."\n\n\n".$mklib->lang['from']." ".$mklib->sitename;
			$mklib_board->admin_mail($mailsubj, $mailmess);
			$mklib->message_page ($mklib->lang['to_signok']);
			exit;
		}
		if ($approval == "3" && !$mkportals->member['g_access_cp']) {
			$mklib->message_page ($mklib->lang['to_signok']);
			exit;
		}
		$query = $DB->query( "SELECT id FROM mkp_topsite WHERE validate = '1'");
		$count = $DB->get_num_rows($query);
		$DB->query("UPDATE mkp_stat SET valore ='$count' WHERE chiave = 'tot_topsite'");
		$DB->close_db();
	 	Header("Location: /index.php?ind=topsite");
		exit;

  	}
	function click_site() {
		global $mkportals, $mklib, $Skin, $DB;

		$idb = intval($mkportals->input['idb']);
		$query = $DB->query( "SELECT link, click FROM mkp_topsite WHERE id = '$idb'");
		$row = $DB->fetch_row($query);
		$link = $row['link'];
		$click = $row['click'];

		++$click;
		$DB->query("UPDATE mkp_topsite SET click ='$click' WHERE id = '$idb'");


		$DB->close_db();
	 	Header("Location: $link");
		exit;

  	}
	function submit_rate() {
    	global $mkportals, $mklib, $Skin, $DB, $mklib_board;
		$ide = intval($mkportals->input['ide']);

		$iduser = $mkportals->member['id'];
		$ipuser = $_SERVER['REMOTE_ADDR'];
		$module = "topsite";

		if (!$iduser || $iduser == 0) { //Guests: check IP address
			$query = $DB->query( "SELECT id FROM mkp_votes WHERE module = '$module' AND id_entry = '$ide' AND ip = '$ipuser'");
			$check = $DB->get_num_rows($query);

		} else { //Registered Members: check userid
			$query = $DB->query( "SELECT id FROM mkp_votes WHERE module = '$module' AND id_entry = '$ide' AND id_member = '$iduser'");
			$check = $DB->get_num_rows($query);
		}
		if($check) {
			$message = "{$mklib->lang['to_justvote']}";
			$mklib->error_page($message);
			exit;
		}

		$query = $DB->query( "SELECT title FROM mkp_topsite WHERE id = '$ide' AND validate = '1'");
		$row = $DB->fetch_row($query);
		$t_t = $row['title'];
		$maintit = "{$mklib->lang['to_vote']} $t_t";

		$utenti_in = $mklib_board->get_active_users("topsite");

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
			  <td style=\"background-color: #ffffff;\">

			    <form action=\"/index.php?ind=topsite&amp;op=add_rate&amp;ide={$ide}\" method=\"post\" id=\"ratea\" name=\"ratea\">
			    <table width=\"100%\">
			      <tr>
				<td class=\"modulex\" width=\"60%\" valign=\"top\">{$mklib->lang['to_vote']} <b>$t_t</b> {$mklib->lang['to_maxvote']}
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
				  <input type=\"submit\" name=\"ok\" value=\"{$mklib->lang['to_sendvote']}\" class=\"mkbutton\" />
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
  <div align=\"center\"><a href=\"http://www.mkportal.it\" target=\"_blank\">MKPTopSite</a> &copy;2003-2008 <a href=\"http://www.mkportal.it\" target=\"_blank\">mkportal.it</a></div>
  </td>
</tr>
	";
	$blocks = $Skin->view_block("{$mklib->lang['to_sendvote']}", $content);
	$mklib->printpage("1", "1", $mklib->sitename.$mklib->lang['tt_sep'].$mklib->lang['to_title'].$mklib->lang['tt_sep'].$t_t.$mklib->lang['tt_sep'].$mklib->lang['to_vote'], $blocks);
	}

	function add_rate() {
    	global $mkportals, $DB, $mklib;
		$ide = intval($mkportals->input['ide']);
		$rating = intval($mkportals->input['rating']);
		$iduser = $mkportals->member['id'];
		$ipuser = $_SERVER['REMOTE_ADDR'];
		$module = "topsite";

		if (!$iduser || $iduser == 0) { //Guests: check IP address
			$query = $DB->query( "SELECT id FROM mkp_votes WHERE module = '$module' AND id_entry = '$ide' AND ip = '$ipuser'");
			$check = $DB->get_num_rows($query);

		} else { //Registered Members: check userid
			$query = $DB->query( "SELECT id FROM mkp_votes WHERE module = '$module' AND id_entry = '$ide' AND id_member = '$iduser'");
			$check = $DB->get_num_rows($query);
		}
		if($check) {
			$message = "{$mklib->lang['to_justvote']}";
			$mklib->error_page($message);
			exit;
		}

		//Validate rating value
		if ($rating < 1 || $rating > 5) {
    			$message = $mklib->lang['to_badvote'];
    			$mklib->error_page($message);
    			exit;
		}

		$query="INSERT INTO mkp_votes(id_entry, module, id_member, ip)VALUES('$ide', '$module', '$iduser', '$ipuser')";
		$DB->query($query);

		$query = $DB->query( "SELECT rate, trate FROM mkp_topsite WHERE id = '$ide'");
		$row = $DB->fetch_row($query);
		$rate = $row['rate'];
		$trate = $row['trate'];
		$votes = ($trate +1);
		$rate = round ((($trate*$rate)+$rating)/($votes), 2);

		$DB->query("UPDATE mkp_topsite SET rate ='$rate', trate ='$votes' WHERE id = '$ide'");
		$DB->close_db();
	 	Header("Location: /index.php?ind=topsite");
		exit;
  	}

}
?>
