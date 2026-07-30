<?php
if (!defined("IN_MKP")) {
    die ("Sorry !! You cannot access this file directly.");
}

$idx = new mk_news;
class mk_news {

	var $tpl       = "";
	var $chache_sections = array();
	var $chache_download = array();

	function mk_news() {

		global $mkportals, $mklib,  $Skin, $DB, $mklib_board;
		
		$mklib->load_lang("lang_news.php");

		if ($mklib->config['mod_news']) {
		$message = "{$mklib->lang['ne_mnoactive']}";
			$mklib->error_page($message);
			exit;
		}

		$DB->query( "SELECT * FROM mkp_categories WHERE module ='news' ORDER BY `id`");
		while( $row = $DB->fetch_row() ) {
            		$this->chache_sections[] = $row;
       	 	}
		$DB->query( "SELECT * FROM mkp_news WHERE validate = '1' ORDER BY `id`");
		while( $row = $DB->fetch_row() ) {
            		$this->chache_download[] = $row;
       	 	}
		//location
		$mklib_board->store_location("news");

		if ($mklib->config['mod_news']) {
		$message = "{$mklib->lang['ne_mnoactive']}";
			$mklib->error_page($message);
			exit;
			
		}

		require "mkportal/modules/news/tpl_news.php";
		$this->tpl = new tpl_news();

    		switch($mkportals->input['op']) {
    			case 'section_view':
    				$this->section_view();
    			break;
    		case 'lists':
			        $this->lists();
			   break;				
			case 'entry_view':
    				$this->entry_view();
    			break;
			case 'ajax_comment':
    				$this->ajax_comment();
    			break;
			case 'add_comment':
    				$this->add_comment();
    			break;
			case 'del_comment':
    				$this->del_comment();
    			break;
    		case 'add_file':
    				$this->add_file();
    			break;
			case 'edit_file':
    				$this->edit_file();
    			break;
			case 'submit_file':
    				$this->submit_file();
    			break;
			case 'update_file':
    				$this->update_file();
    			break;
			case 'del_file':
    				$this->del_file();
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
    				$this->news_show();
    			break;
    		}
	}
    function menu() {
			global $mkportals, $DB, $mklib, $mklib_board;
			if($mkportals->member['g_access_cp'] || $mklib->member['g_send_news']) {
		$adds = "<td><a href=\"/index.php?ind=news&amp;op=submit_file\">{$mklib->lang['ne_insertn']}</a></td>";
	}
			$output = "<tr><td><table align=\"right\" class=\"navigator\">
			<tr><td><a href=\"/index.php?ind=news\">{$mklib->lang['home']}</a></td>
		    <td><a href=\"/index.php?ind=news&amp;op=lists&amp;best=1\">{$mklib->lang['new_best']}</a></td>
			<td><a href=\"/index.php?ind=news&amp;op=lists&amp;popular=1\">{$mklib->lang['new_popular']}</a></td>
			<td><a href=\"/index.php?ind=news&amp;op=lists&amp;list=1\">{$mklib->lang['new_list']}</a></td>
			$adds
			</tr></table></td></tr>";
			return $output;
		}
	function news_show() {
        global $mkportals, $DB, $mklib, $Skin, $mklib_board;

	$navbar = "<a href=\"/index.php?ind=news\">{$mklib->lang['ne_news']}</a>";
	$maintit = $mklib->lang['ne_news'];
	$menu .= $this->menu();
	$content = $this->tpl->row_main_category();
	switch(intval($mkportals->input['order'])) {
		case '2':
			$order = "ORDER BY `title`";
    	break;
		case '3':
			$order = "ORDER BY `id` DESC";
    	break;
		default:
    		$order = "ORDER BY `ordern`";
    	break;
	}
	$query = $DB->query("SELECT id, title, ordern, description, parentid, img FROM mkp_categories WHERE module = 'news' AND parentid = '0' $order ");
        while( $row = $DB->fetch_row($query) ) {
		$count = 0;
		$idevento = $row['id'];
		$evento = $row['title'];
		$descrizione = $row['descrizione'];
		$totalson = $this->total_son($idevento);
		$countsub = $totalson[0];
		$count = $totalson[1];
		$lastentry = $totalson[2];
		$img = $row['img'];
		
  if (!$img){
  	$catimig ="";
  }
  else {
  	$catimig ="<img src=\"{$mklib->images}/categories/".$img."\" border=\"0\" alt=\"$description\" title=\"$description\" hspace=\"10\" vspace=\"10\"></img>";
  }
		
		$name ="<a href=\"/index.php?ind=news&amp;op=section_view&amp;idev=$idevento\">$evento</a>";
		$link = "<a href=\"/index.php?ind=news&amp;op=section_view&amp;idev=$idevento\">$catimig</a>";
		$content .= $this->tpl->row_main_category_content($name, $descrizione, $count, $lastentry, $link, $countsub);
	}
		
	
	$jump1 = $this->row_select_event("1");
	$jump = "
	<select name=\"jumpsection\" size=\"1\" onchange=\"selChd(this)\" class=\"bgselect\">
       	$jump1
	</select>
	 ";
	 $sort = "
	<select name=\"order\" size=\"1\" onchange=\"selChoc(this)\" class=\"bgselect\">
	  <option value=\"0\">{$mklib->lang['ne_order']}</option>\n
	  <option value=\"1\">{$mklib->lang['ne_ordpos']}</option>\n
	  <option value=\"2\">{$mklib->lang['ne_ordnamec']}</option>\n
	  <option value=\"3\">{$mklib->lang['ne_ordcrea']}</option>\n
      	</select>
	 ";
	$toolbar = $this->tpl->row_toolbar($jump, $sort);	
	$utonline = $mklib_board->get_active_users("news");
	$output  = $this->tpl->news_show($navbar, $maintit, $content, $menu, $toolbar, $show_pages, $utonline);	
	$blocks = $Skin->view_block("{$mklib->lang['ne_news']}", $output);
	$mklib->printpage("1", "1", $mklib->lang['ne_news'].$mklib->lang['tt_sep'].$mklib->sitename, $blocks);

	}
	function section_view()  {
		global $mkportals, $DB, $mklib, $Skin, $mklib_board;
		$content = "";
		$idev = intval($mkportals->input['idev']);
		$even = $this->retrieve_event($idev);
		$menu .= $this->menu();
		$link_user = $mklib_board->forum_link("profile");	
		$navbar = "<a href=\"/index.php?ind=news\">{$mklib->lang['ne_news']}</a>";
		$navfather = $this->retrieve_father($idev);
		if($navfather['1']) {
			
			$navbar .= "{$mklib->lang['bc_sep']}<a href=\"/index.php?ind=news&amp;op=section_view&amp;idev={$navfather['0']}\">{$navfather['1']}</a>";
		}
		
		$navbar .= "{$mklib->lang['bc_sep']}<a href=\"#\">$even</a>";
		$maintit = $even;
		$query = $DB->query("SELECT id, title, ordern, description, parentid, img FROM mkp_categories WHERE module = 'news' AND parentid = '$idev' ORDER by ordern ");
		$cecksub = $DB->get_num_rows($query);
		if($cecksub) {
			$content = $this->tpl->row_main_category();
			while( $row = $DB->fetch_row($query) ) {
			$idevento = $row['id'];
			$evento = $row['title'];
			$descrizione = $row['descrizione'];
			$totalson = $this->total_son($idevento);
			$countsub = $totalson[0];
			$count = $totalson[1];
			$lastentry = $totalson[2];
			$img = $row['img'];
		
  if (!$img){
  	$catimig ="";
  }
  else {
  	$catimig ="<img src=\"{$mklib->images}/categories/".$img."\" border=\"0\" alt=\"$description\" title=\"$description\" hspace=\"10\" vspace=\"10\"></img>";
  }
		
		$name ="<a href=\"/index.php?ind=news&amp;op=section_view&amp;idev=$idevento\">$evento</a>";
		$link = "<a href=\"/index.php?ind=news&amp;op=section_view&amp;idev=$idevento\">$catimig</a>";
			$content .= $this->tpl->row_main_category_content($name, $descrizione, $count, $lastentry, $link, $countsub);
			}
		}


		switch(intval($mkportals->input['order'])) {
		case '1':
			$order = "ORDER BY `titolo`";
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

		$per_page = $mklib->config['news_page'];
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
						    BASE_URL    => '/index.php?ind=news&amp;op=section_view&amp;idev='.$idev.'&amp;order='.intval($mkportals->input['order'])
										  )
		);
		$query = $DB->query( "SELECT id, idautore, titolo, autore, short_testo, testo, data, pinned, totalcomm, hits, trate FROM mkp_news WHERE idcategoria = '$idev' AND validate = '1' $order LIMIT $start, $per_page");	
		while( $row = $DB->fetch_row($query) ) {
			$iden = $row['id'];
			$name = $row['titolo'];
			$totcomments = $row['totalcomm'];
			$trate = $row['trate'];
			$click = $row['hits'];
			$data = $mklib->create_date($row['data'], "short");
			$idautore = $row['idautore'];
			$autore = $row['autore'];
			$postautor = "<a href=\"$link_user=$idautore\">$autore</a>";
		
			$name1 ="<a href=\"/index.php?ind=news&amp;op=entry_view&amp;iden=$iden\">$name</a>";
			$href ="<a href=\"/index.php?ind=news&amp;op=entry_view&amp;iden=$iden\">{$mklib->lang['ne_readall']}</a>";
			$short_testo = stripslashes($row['short_testo']);
		if ($mklib->mkeditor == "BBCODE") {
			$short_testo = $mklib->decode_bb($short_testo);
		}
		
		$testo = stripslashes($row['testo']);
		if ($mklib->mkeditor == "BBCODE") {
			$testo = $mklib->decode_bb($testo);
		}
		$news_words= $mklib->config['news_words'];
		if ($mklib->config['news_html']) {
			$testo = str_replace ("<br />", " ", $testo);
			$testo = strip_tags ($testo);
   		}
   		if ($mklib->config['news_html']) {
			$short_testo = str_replace ("<br />", " ", $short_testo);
			$short_testo = strip_tags ($short_testo);
   		}
		if ($news_words) {
			$testo = substr ($testo, 0, $news_words);
			$testo = $testo." ...";
		}
		
		if (!$short_testo) {
		$short_testo = $testo;
		}
		
		$pinned = $row['pinned'] ? "&nbsp;<img src=\"$mklib->images/pin.gif\" border=\"0\" alt=\"\" />" : '';	
		$content .= $this->tpl->row_main_entries_content($iden, $name1, $href, $postautor, $click, $data, $short_testo, $totcomments, $pinned);
		}
		
		$jump1 = $this->row_select_event("1");
		$jump = "
		<select name=\"jumpsection\" size=\"1\" onchange=\"selChd(this)\"  class=\"bgselect\">
       		$jump1
		</select>
	 	";
	 	$sort = "
		<select name=\"order\" size=\"1\" onchange=\"selChoe(this, '$idev')\"  class=\"bgselect\">
		  <option value=\"0\">{$mklib->lang['ne_order']}</option>\n
		  <option value=\"1\">{$mklib->lang['ne_ordnamef']}</option>\n
		  <option value=\"2\">{$mklib->lang['ne_ordinsert']}</option>\n
		</select>
		 ";
		$toolbar = $this->tpl->row_toolbar($jump, $sort);
		$utonline = $mklib_board->get_active_users("news");
		$output  = $this->tpl->news_show($navbar, $maintit, $content, $menu, $toolbar, $show_pages, $utonline);
        $blocks = $Skin->view_block("{$mklib->lang['ne_news']}", $output);
		$mklib->printpage("1", "1", $maintit.$mklib->lang['tt_sep'].$mklib->lang['ne_news'].$mklib->lang['tt_sep'].$mklib->sitename, $blocks);
	
	}
	function lists() {
	global $mkportals, $DB, $std, $print, $mklib, $Skin, $mklib_board;
	$best = $mkportals->input['best'];
	$popular = $mkportals->input['popular'];
	$list = $mkportals->input['list'];
			$link_user = $mklib_board->forum_link("profile");
	if ($best == 1) {
		$order ="ORDER BY `rate` DESC";
		$urls ="/index.php?ind=news&amp;op=lists&amp;best=1";
		}
		if ($popular == 1) {
		$order ="ORDER BY `hits` DESC";
		$urls ="/index.php?ind=news&amp;op=lists&amp;popular=1";
		}
		if ($list == 1) {
		$order ="ORDER BY `id` DESC";
		$urls ="/index.php?ind=news&amp;op=lists&amp;list=1";
		}
	$start = intval($mkportals->input['start']);
	$modname ='news';
	$menu .= $this->menu();
	$query = $DB->query("SELECT id FROM mkp_news WHERE validate = '1'");
	$count = $DB->get_num_rows ($query);
$q_page = intval($mkportals->input['st']);
		if ($q_page=="" or $q_page <= 0) {
			$q_page	=	0;
		}
		$per_page = $mklib->config['news_page'];
		if ($per_page=="" or $per_page <= 0) {
			$per_page	=	10;
		}

	    $start = $q_page;
		$show_pages = $mklib->build_pages( array( TOTAL_POSS  => $count,
							PER_PAGE    => $per_page,
							CUR_ST_VAL  => $q_page,
							L_SINGLE    => '',
							L_MULTI     => 'pagine',
						    BASE_URL    => $urls,
										  )
								   );

	$query = $DB->query( "SELECT id, idcategoria, idautore, titolo, autore, short_testo, testo, data, pinned, totalcomm, hits, trate FROM mkp_news WHERE  validate = '1' $order LIMIT $start, $per_page");	
		while( $row = $DB->fetch_row($query) ) {
			$iden = $row['id'];
			$name = $row['titolo'];
			$totcomments = $row['totalcomm'];
			$trate = $row['trate'];
			$click = $row['hits'];
			$data = $mklib->create_date($row['data'], "short");
			$idautore = $row['idautore'];
			$autore = $row['autore'];
			$postautor = "<a href=\"$link_user=$idautore\">$autore</a>";
			//$navfather = $this->retrieve_rewrite_urls($row['idcategoria']);
		/*if($navfather['1']) {
			if($mklib->config['rewrite_url']){
			$navbar .= "{$mklib->lang['bc_sep']}<a href=\"/news{$mklib->config['rewrite_sep']}{$navfather['2']}{$mklib->config['rewrite_sep']}\">{$navfather['1']}</a>";
		} else {
			$navbar .= "{$mklib->lang['bc_sep']}<a href=\"/index.php?ind=news&amp;op=section_view&amp;idev={$navfather['0']}\">{$navfather['1']}</a>";
		}
		}*/
		
			$name1 ="<a href=\"/index.php?ind=news&amp;op=entry_view&amp;iden=$iden\">$name</a>";
			$href ="<a href=\"/index.php?ind=news&amp;op=entry_view&amp;iden=$iden\">{$mklib->lang['ne_readall']}</a>";
			$short_testo = stripslashes($row['short_testo']);
		if ($mklib->mkeditor == "BBCODE") {
			$short_testo = $mklib->decode_bb($short_testo);
		}
		
		$testo = stripslashes($row['testo']);
		if ($mklib->mkeditor == "BBCODE") {
			$testo = $mklib->decode_bb($testo);
		}
		$news_words= $mklib->config['news_words'];
		if ($mklib->config['news_html']) {
			$testo = str_replace ("<br />", " ", $testo);
			$testo = strip_tags ($testo);
   		}
   		if ($mklib->config['news_html']) {
			$short_testo = str_replace ("<br />", " ", $short_testo);
			$short_testo = strip_tags ($short_testo);
   		}
		if ($news_words) {
			$testo = substr ($testo, 0, $news_words);
			$testo = $testo." ...";
		}
		
		if (!$short_testo) {
		$short_testo = $testo;
		}
		
		$pinned = $row['pinned'] ? "&nbsp;<img src=\"$mklib->images/pin.gif\" border=\"0\" alt=\"\" />" : '';	
		$content .= $this->tpl->row_main_entries_content($iden, $name1, $href, $postautor, $click, $data, $short_testo, $totcomments, $pinned);
		}
		
		$jump1 = $this->row_select_event("1");
		$jump = "
		<select name=\"jumpsection\" size=\"1\" onchange=\"selChd(this)\"  class=\"bgselect\">
       		$jump1
		</select>
	 	";
	 	$sort = "
		<select name=\"order\" size=\"1\" onchange=\"selChoe(this, '$idev')\"  class=\"bgselect\">
		  <option value=\"0\">{$mklib->lang['ne_order']}</option>\n
		  <option value=\"1\">{$mklib->lang['ne_ordnamef']}</option>\n
		  <option value=\"2\">{$mklib->lang['ne_ordinsert']}</option>\n
		</select>
		 ";
		$toolbar = $this->tpl->row_toolbar($jump, $sort);
		$utonline = $mklib_board->get_active_users("news");
		$output  = $this->tpl->news_show($navbar, $maintit, $content, $menu, $toolbar, $show_pages, $utonline);
	$blocks .= $Skin->view_block("{$mklib->lang['new_best']}", $output);
	$mklib->printpage("1", "1", "{$mklib->lang['new_best']}", $blocks);
}

	function entry_view() {
		global $mkportals, $DB, $mklib, $Skin, $mklib_board;
		$ide = intval($mkportals->input['iden']);
		$link_user = $mklib_board->forum_link("profile");
		$menu .= $this->menu();
		$editorscript = "";
			$textarepar = "";
			$textarew = "75%";
			$bbeditor= $mklib->get_commentbbeditor();	
	    $query = $DB->query( "SELECT id, idcategoria, idautore, titolo, autore, testo, data, pinned, rate, trate, hits, totalcomm, allow_comm, allow_rating, descr, keywords FROM mkp_news WHERE id = '$ide' AND validate = '1'");
		$row = $DB->fetch_row($query);
		if(!$row) {
            		$message = "{$mklib->lang['error_404']}";
            		$mklib->error_page($message);
            		exit;
		}
		$titolo = stripslashes($row['titolo']);
		$idcategoria = $row['idcategoria'];
		$testo = stripslashes($row['testo']);
		if ($mklib->mkeditor == "BBCODE") {
				$testo = $mklib->decode_bb($testo);
				$testo = $mklib_board->decode_smilies($testo);
		}
		$autore = $row['autore'];
		$id_orig_name = $row['idautore'];
		$postautor = "<a href=\"$link_user={$row['idautore']}\">$autore</a>";
		$cdata = $mklib->create_date($row['data']);
		$rate = $row['rate'];
		$trate = $row['trate'];
		$totalcomm = $row['totalcomm'];
		$hits = $row['hits'];
		$allow_rating = $row['allow_rating'];
		$allow_comm = $row['allow_comm'];
		$descr = stripslashes($row['descr']);
		$keywords = stripslashes($row['keywords']);
		$id = $ide;
		$even = $this->retrieve_event($idcategoria);
		$modname ="news";
	  if ($allow_rating == 0) {
			$rating = "";
   		}
   		else {
        $rating = $mklib->pullRating($id, "news", $rate, $trate);
   		}
	$content1 .= $this->tpl->row_entry($id, $titolo, $testo, $postautor, $cdata, $rating, $hits, $totalcomm);
	
	$content2 = "
        <tr>
        <td class=\"tdblock\" colspan=\"2\">
		{$mklib->lang['ne_comments']}

		<script type=\"text/javascript\">

			function makesure3() {
			if (confirm('{$mklib->lang['ne_delcommconf']}')) {
			return true;
			} else {
			return false;
			}
			}

			</script>
		</td>
		</tr>
		
		"; 
	$link_user = $mklib_board->forum_link("profile");
	$query1 = $DB->query( "SELECT id, cid, module, data, memid, name, memip, comment, status FROM mkp_comments WHERE cid = '$ide' AND module = 'news'  ORDER BY `id` DESC");			
	while( $row = $DB->fetch_row($query1) ) {
			$idcomm = $row['id'];
			$autore = $row['name'];
			$id_name = $row['memid'];
			$testo = stripslashes($row['comment']);
			$testo = $mklib->decode_bb($testo);
			$data = $mklib->create_date($row['data'], "short");
			$delete = "
			<a href=\"/index.php?ind=news&amp;op=del_comment&amp;idcomm=$idcomm&amp;ide=$ide\" onclick=\"return makesure3()\">[ {$mklib->lang['ne_delete']} ]</a>
			";
			if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_mod_news']) {
				$delete = "";
			}
			$content2 .= "
			<tr>
                            <td class=\"modulecell\" width=\"20%\" valign=\"top\"><b><a href=\"$link_user=$id_name\">$autore</a></b><br /><br />{$data}<br />{$delete}</td>
                            <td class=\"modulecell\" width=\"80%\" valign=\"middle\">{$testo}</td>
			</tr>
			";
			
		}
if($mklib->member['g_send_comments'] || $mkportals->member['g_access_cp']) {
$captcha = $mklib->antibot_start();
if ($allow_comm == 0) {
			$bbcomnt = "";
   		}
   		else {
$bbcomnt ="<form id =\"editor\" name=\"editor\" action=\"javascript:sendcomentnews();\" method=\"post\" >
				<table class=\"modulecell\" width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"8\">		
				  <tr>
        	
				    <td rowspan=\"3\" align=\"center\" height=\"100%\">
				      <input type=\"hidden\" name=\"ide\" value=\"$ide\" />
		
				      <td width=\"70%\" align=\"left\">
		                      $bbeditor
		             <textarea cols=\"10\" style=\"width:75%\" rows=\"5\" name=\"ta\" id=\"ta\"></textarea>
				    <td>{$mklib->lang['ne_writecomm']}</td>
				  </tr>
				  <tr>
				    <td width=\"70%\" align=\"left\">
                    $captcha
				    </td>
				  </tr>
				  <tr>
				    <td>
				      <input type=\"submit\" name=\"submit\" value=\"{$mklib->lang['ne_sendcomm']}\" class=\"button2\" accesskey=\"s\" /><br />
				    </td>
				  </tr>		
				</table>
				</form>";
   		}
}
	
	   $content .= $this->tpl->row_main_coments($content1, $content2, $bbcomnt);
        $DB->query("UPDATE mkp_news SET hits=hits+1 WHERE id = '$ide'");
        
		$navbar = "<a href=\"/index.php?ind=news\">{$mklib->lang['ne_news']}</a>{$mklib->lang['bc_sep']}<a href=\"/index.php?ind=news&amp;op=section_view&amp;idev=$idcategoria\">$even</a>{$mklib->lang['bc_sep']}<a href=\"#\">$titolo</a>";
		$maintit = $titolo;
		$toolbar = "";
		$utonline = $mklib_board->get_active_users("news");
		$output  = $this->tpl->news_show($navbar, $maintit, $content, $menu, $toolbar, $show_pages, $utonline);
		$blocks = $Skin->view_block("$titolo", $output);
		$mklib->printpage("1", "1", $titolo.$mklib->lang['tt_sep'].$even.$mklib->lang['tt_sep'].$mklib->sitename, $blocks, $descr, $keywords);
	}

function submit_file() {
		global $mkportals, $DB, $mklib, $Skin, $editorscript;

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
		$cselect = $this->row_select_event();
	
		$options = "<tr>
        
        <td><input type=\"checkbox\" name=\"approve\" value=\"1\" checked> {$mklib->lang['ne_approve']}<br /><br />

	<input type=\"checkbox\" name=\"allow_main\" value=\"1\" checked> {$mklib->lang['ne_allow_main']}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type=\"checkbox\" name=\"allow_comm\" value=\"1\" checked> {$mklib->lang['ne_allow_comm']}<br />

	<input type=\"checkbox\" name=\"allow_rating\" value=\"1\" checked> {$mklib->lang['ne_allow_rating']}&nbsp;&nbsp;&nbsp;
		<input type=\"checkbox\" name=\"pinned\" value=\"1\"> {$mklib->lang['ne_pinned']}<br />
   </td>
	</tr>";

		$content = "
		<tr>
		  <td>
		    <form action=\"/index.php?ind=news&amp;op=add_file\" method=\"post\" class=\"editor\" name=\"editor\">
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
		     <tr>
   			<td class=\"tdblock\" valign=\"top\">{$mklib->lang['short_testo']} {$mklib->lang['ne_news']}<br>
			$bbeditor2
 			<textarea id=\"short\" name=\"short\" $textarepar style=\"width: $textarew\" rows=\"14\" cols=\"40\"></textarea>
			</td>
		      </tr>
		      <tr> 
   			<td class=\"tdblock\" valign=\"top\">{$mklib->lang['full_testo']} {$mklib->lang['ne_news']}<br>
			$bbeditor
 			<textarea id=\"ta\" name=\"ta\" $textarepar style=\"width: $textarew\" rows=\"14\" cols=\"40\"></textarea>
			</td>
		      </tr>
		     <tr>
			<td class=\"tdblock\">
			{$mklib->lang['meta_key']} {$mklib->lang['ne_news']}: <input type=\"keywords\" name=\"keywords\"  size=\"40\" />{$mklib->lang['meta_key_des']}
			</td>
		   <tr>
			<td class=\"tdblock\">
			{$mklib->lang['meta_descr']} {$mklib->lang['ne_news']}: <input type=\"descr\" name=\"descr\"  size=\"40\" />{$mklib->lang['meta_descr_des']}
			</td>
		   </tr>
		  
		       $options
		      <tr>
			<td>
			  <div class=\"mkalign1\">
			  <input type=\"submit\" name=\"ok\" value=\"{$mklib->lang['ne_save']}\" class=\"mkbutton\" />
			  </div>		
			</td>
		      </tr>
		    </table>
		    </form>
		  </td>
		</tr>

	";
		$blocks = $Skin->view_block("{$mklib->lang['ne_insertn']}", $content);
		$mklib->printpage("1", "1", $mklib->sitename.$mklib->lang['tt_sep'].$mklib->lang['ne_news'].$mklib->lang['tt_sep'].$mklib->lang['ne_insertn'], $blocks);
	}
	function add_file() {
    	global $mkportals, $DB, $std, $print, $mklib, $mklib_board;

		if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_send_news']) {
			$message = "{$mklib->lang['ne_noautsendn']}";
			$mklib->error_page($message);
			exit;
		}
    if (!$mkportals->input['short']) {
			$message = "{$mklib->lang['ne_inserttx']}";
			$mklib->error_page($message);
			exit;
		}
		if (!$mkportals->input['ta']) {
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
		$testo = $mkportals->input['ta'];
		$short_testo = $mkportals->input['short'];
		$testo = $mklib->convert_savedb($testo);
		$short_testo = $mklib->convert_savedb($short_testo);
		$autore = $mkportals->member['name'];
		$titolo = $mkportals->input['titlepage'];
		$titolo = $mklib->convert_savedb($titolo);
		$validat = intval($mkportals->input['approve']);
		$allow_main = intval($mkportals->input['allow_main']);
		$allow_comm = intval($mkportals->input['allow_comm']);
		$allow_rating = intval($mkportals->input['allow_rating']);
		$keywords = $mkportals->input['keywords'];
		$keywords = $mklib->convert_savedb($keywords);
		$descr = $mkportals->input['descr'];
		$descr = $mklib->convert_savedb($descr);
		$pinned = intval($mkportals->input['pinned']);
		$cdata = time();

		
		$approval = $mklib->config['approval_news'];
		if ($approval == "2" || $approval == "3") {
			$validat = 0;
		}
		

		$query="INSERT INTO mkp_news(idcategoria, idautore, titolo, autore, short_testo, testo, data, validate, pinned, allow_main, allow_comm, allow_rating, descr, keywords)VALUES('$categoria', '$idaut', '$titolo', '$autore', '$short_testo', '$testo', '$cdata', '$validat', '$pinned', '$allow_main', '$allow_comm', '$allow_rating', '$descr', '$keywords')";
		$DB->query($query);
		
		if ($approval == "1") {
			$mailsubj = $mklib->lang['01mail'].$mklib->lang['news'];
			$mailmess = $mklib->lang['02mail'].$mklib->lang['module'].$mklib->lang['news']."\n".$mklib->lang['sender'].$autore."\n\n\n".$mklib->lang['from']." ".$mklib->sitename;
			$mklib_board->admin_mail($mailsubj, $mailmess);
		}
		if ($approval == "2" && !$mkportals->member['g_access_cp']) {
			$mailsubj = $mklib->lang['01mail'].$mklib->lang['news'];
			$mailmess = $mklib->lang['03mail'].$mklib->lang['module'].$mklib->lang['news']."\n".$mklib->lang['sender'].$autore."\n\n\n".$mklib->lang['from']." ".$mklib->sitename;
			$mklib_board->admin_mail($mailsubj, $mailmess);
			$mklib->message_page ($mklib->lang['notify_adv']);
			exit;
		}
		if ($approval == "3" && !$mkportals->member['g_access_cp']) {
			$mklib->message_page ($mklib->lang['notify_adv']);
			exit;
		}
		$DB->close_db();
	 	Header("Location: /index.php?ind=news&op=section_view&idev=$categoria");
		exit;
  }
  function edit_file() {
		global $mkportals, $DB, $mklib, $Skin, $editorscript;
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
		

		if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_mod_news']) {
			$message = "{$mklib->lang['ne_noautmodn']}";
			$mklib->error_page($message);
			exit;
		}
		$idnews = intval($mkportals->input['iden']);
		$query = $DB->query( "SELECT idcategoria, titolo, short_testo, testo, pinned, allow_main, allow_comm, allow_rating, descr, keywords FROM mkp_news WHERE id = '$idnews'");
		$row = $DB->fetch_row($query);
		if(!$row) {
            		$message = "{$mklib->lang['error_404']}";
            		$mklib->error_page($message);
            		exit;
		}
		$idcategoria = $row['idcategoria'];
		$titolo = stripslashes($row['titolo']);
		$testo = stripslashes($row['testo']);
		$descr = stripslashes($row['descr']);
		$keywords = stripslashes($row['keywords']);
		if ($mklib->mkeditor == "BBCODE") {
			$testo = str_replace("<br />", "\n", $testo);
		} else {
			$testo = preg_replace("/(?<=\>)<br \/>(?=\<)/" , "\n", $testo);
		}
		$short_testo = stripslashes($row['short_testo']);
		if ($mklib->mkeditor == "BBCODE") {
			$short_testo = str_replace("<br />", "\n", $short_testo);
		} else {
			$short_testo = preg_replace("/(?<=\>)<br \/>(?=\<)/" , "\n", $short_testo);
		}
		$active = $row['pinned'] ? 'checked="checked"' : '';
	    $active_allow_main = $row['allow_main'] ? 'checked="checked"' : '';
	    $active_allow_comm = $row['allow_comm'] ? 'checked="checked"' : '';
	    $active_allow_rating = $row['allow_rating'] ? 'checked="checked"' : '';
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
			$idevento = $row['id'];
				$selected = "";
				if($idevento == $idcategoria) {
					$selected = "selected=\"selected\"";
				}
				$evento = $row['title'];
				$father = $row['parentid'];
				if(!$listall[$idevento]) {
					$cselect.= "<option value='$idevento' $selected>$evento</option>\n";
				}
				$listall[$idevento] = 1;
				$query1 = $DB->query( "SELECT id, title, parentid FROM mkp_categories WHERE module='$modname' AND parentid = '$idevento' ORDER BY `id`");
				while( $row2 = $DB->fetch_row($query1) ) {
					$idevento = $row2['id'];
					$selected = "";
					if($idevento == $idcategoria) {
						$selected = "selected=\"selected\"";
					}
					$evento = $row2['title'];
					if(!$listall[$idevento]) {
						$cselect.= "<option value='$idevento' $selected>- $evento</option>\n";
					}
					$listall[$idevento] = 1;
				}
			}
	if($mkportals->member['g_access_cp']){
		$options = "<tr>
        
        <td><br /><input type=\"checkbox\" name=\"approve\" value=\"1\" checked> {$mklib->lang['ne_approve']}<br /><br />

	<input type=\"checkbox\" name=\"allow_main\" value=\"1\" $active_allow_main> {$mklib->lang['ne_allow_main']}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type=\"checkbox\" name=\"allow_comm\" value=\"1\" $active_allow_comm> {$mklib->lang['ne_allow_comm']}<br />

	<input type=\"checkbox\" name=\"allow_rating\" value=\"1\" $active_allow_rating> {$mklib->lang['ne_allow_rating']}&nbsp;&nbsp;&nbsp;
		<input type=\"checkbox\" name=\"pinned\" value=\"1\" $active> {$mklib->lang['ne_pinned']}<br /><br />
   </td>
	</tr>";
	}
		$content = "
		<tr>
		  <td>
		    <form action=\"/index.php?ind=news&amp;op=update_file&amp;iden=$idnews\" method=\"post\" class=\"editor\" name=\"editor\">
		    <table width=\"100%\">
		      <tr>
			<td class=\"tdblock\">
			{$mklib->lang['ne_title']}:<input type=\"text\" name=\"titlepage\" value=\"$titolo\" size=\"40\" />
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
			<td class=\"tdblock\">
			{$mklib->lang['meta_key']} {$mklib->lang['ne_news']}: <input type=\"keywords\" name=\"keywords\" value=\"$keywords\"  size=\"60\" />{$mklib->lang['meta_key_des']}
			</td>
		   <tr>
			<td class=\"tdblock\">
			{$mklib->lang['meta_descr']} {$mklib->lang['ne_news']}: <input type=\"descr\" name=\"descr\" value=\"$descr\" size=\"60\" />{$mklib->lang['meta_descr_des']}
			</td>
	
		$options 
		      <tr>
			<td>
			  <div class=\"mkalign1\">
			  <input type=\"submit\" name=\"ok\" value=\"{$mklib->lang['ne_save']}\" class=\"mkbutton\" />
			  </div>		  
			</td>
		      </tr>
		    </table>
		    </form>
		  </td>
		</tr>
	";
		$blocks = $Skin->view_block("{$mklib->lang['ne_editn']}", $content);
		$mklib->printpage("1", "1", $mklib->sitename.$mklib->lang['tt_sep'].$mklib->lang['ne_news'].$mklib->lang['tt_sep'].$titolo.$mklib->lang['tt_sep'].$mklib->lang['ne_modifyn'], $blocks);
	}

	function update_file() {
    		global $mkportals, $DB, $std, $print, $mklib;

		if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_mod_news']) {
			$message = "{$mklib->lang['ne_noautmodn']}";
			$mklib->error_page($message);
			exit;
		}
          if (!$mkportals->input['short']) {
			$message = "{$mklib->lang['ne_inserttx']}";
			$mklib->error_page($message);
			exit;
		}
		if (!$mkportals->input['ta']) {
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
		$testo = $mkportals->input['ta'];
		$testo = $mklib->convert_savedb($testo);
		$titolo = $mkportals->input['titlepage'];
		$titolo = $mklib->convert_savedb($titolo);
		$short_testo = $mkportals->input['short'];
		$short_testo = $mklib->convert_savedb($short_testo);
		//$testo = addslashes($testo);
		//$titolo = addslashes($titolo);
		$idnews = intval($mkportals->input['iden']);
		$pinned = intval($mkportals->input['pinned']);
		$allow_main = intval($mkportals->input['allow_main']);
		$allow_comm = intval($mkportals->input['allow_comm']);
		$allow_rating = intval($mkportals->input['allow_rating']);
		$keywords = $mkportals->input['keywords'];
		$keywords = $mklib->convert_savedb($keywords);
		$descr = $mkportals->input['descr'];
		$descr = $mklib->convert_savedb($descr);
		$DB->query("UPDATE mkp_news SET idcategoria = '$categoria', titolo ='$titolo', short_testo='$short_testo', testo='$testo', pinned='$pinned', allow_main='$allow_main', allow_comm='$allow_comm', allow_rating='$allow_rating', descr='$descr', keywords='$keywords' WHERE id='$idnews'");
		$DB->close_db();
	 	Header("Location: /index.php?ind=news&op=news_show_single&ide=$idnews");
		exit;
  }

  function del_file() {
    		global $mkportals, $DB, $std, $mklib;

		if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_mod_news']) {
			$message = "{$mklib->lang['ne_noautdeln']}";
			$mklib->error_page($message);
			exit;
		}

		$idnews = intval($mkportals->input['iden']);

		$DB->query("DELETE FROM mkp_news WHERE id = $idnews");
		$DB->query("DELETE FROM mkp_votes WHERE id_entry = $idnews AND module = 'news'");
		$DB->query("DELETE FROM mkp_comments WHERE cid = $idnews AND module = 'news'");
		$DB->close_db();
	 	Header("Location: /index.php?ind=news");
		exit;
	}
	function del_comment() {
    	global $mkportals, $DB, $mklib;

		if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_mod_news']) {
			$message = "{$mklib->lang['ne_nodelcomm']}";
			$mklib->error_page($message);
			exit;
		}

		$ide= intval($mkportals->input['ide']);
		$idcomm= intval($mkportals->input['idcomm']);
		$DB->query("DELETE FROM mkp_comments WHERE id = $idcomm AND module = 'news'");
		$query = $DB->query( "SELECT totalcomm FROM mkp_news WHERE id = '$ide'");
		$row = $DB->fetch_row($query);
		$totalcomm = $row['totalcomm'];
		--$totalcomm;
		$DB->query("UPDATE mkp_news SET totalcomm ='$totalcomm' WHERE id = '$ide'");
		$DB->close_db();
	 	Header("Location: index.php?ind=news&op=news_show_single&ide=$ide");
		exit;
  	}	
		/*function search() {
		global $mkportals, $DB, $mklib, $Skin, $mklib_board;
		$maintit = "{$mklib->lang['ne_search']}";
		$cselect.= "<option value='1'>{$mklib->lang['ne_title']}</option>\n";
		$cselect.= "<option value='2'>{$mklib->lang['ne_readall']}</option>\n";
		$content .= "
		<tr>
		  <td>
		  
		    <form action=\"/index.php?ind=news&amp;op=result_search\" name=\"search\" method=\"post\">
		    <table width=\"100%\" border=\"0\">
		      <tr>
			<td>{$mklib->lang['ne_searchin']}</td>
			<td>
			  <select class=\"bgselect\" name=\"campo\" size=\"1\">
			  {$cselect}
			  </select>
			</td>
		      </tr>
		      <tr>
			<td width=\"20%\">{$mklib->lang['ne_searchtext']}</td>
			<td width=\"80%\"><input type=\"text\" name=\"testo\" size=\"52\" class=\"bgselect\" /></td>
		      </tr>
		      <tr>
			<td colspan=\"2\"><input type=\"submit\" value=\"{$mklib->lang['ne_searchstart']}\" class=\"mkbutton\" /></td>
		      </tr>
		    </table>
		    </form>
		    
		</td>
	      </tr>
		";
		$navbar = "<a href=\"/index.php?ind=news\">{$mklib->lang['dw_ptitle2']}</a>{$mklib->lang['bc_sep']}<a href=\"#\">{$mklib->lang['dw_searchf']}</a>";
		$toolbar = "";
		$utonline = $mklib_board->get_active_users("news");
		$output  = $this->tpl->news_show($navbar, $maintit, $content, $submit, $toolbar, $show_pages, $utonline);
		$blocks = $Skin->view_block("{$mklib->lang['ne_search']}", $output);
		$mklib->printpage("1", "1", $mklib->sitename.$mklib->lang['tt_sep'].$mklib->lang['ne_search'].$mklib->lang['tt_sep'].$mklib->lang['ne_news'], $blocks);
	}

	function result_search() {
		global $mkportals, $DB, $mklib, $Skin, $mklib_board;
		//$campo = $mkportals->input['campo'];  //deprecated
		$testo = $mkportals->input['testo'];
		$campo = "titolo";
		if (intval($mkportals->input['campo']) == 2) {
			$campo = "testo";
		}
		if (!$testo) {
			$message = "{$mklib->lang['dw_reqstring']}";
			$mklib->error_page($message);
			exit;
		}
		$navbar = "<a href=\"/index.php?ind=news\">{$mklib->lang['dw_ptitle2']}</a>{$mklib->lang['bc_sep']}<a href=\"#\">{$mklib->lang['dw_searchresult']}</a>";
		$maintit = "{$mklib->lang['dw_searchresult']}";
		$content = $this->tpl->row_main_entries();
		$query = $DB->query( "SELECT id, titolo, short_testo, testo, hits, data, trate FROM mkp_news WHERE $campo LIKE '%$testo%' AND validate = '1'");
		while( $row = $DB->fetch_row($query) ) {
			$iden = $row['id'];
			$name1 = $row['titolo'];
			$trate = $row['trate'];
		//	$downloads = $row['downloads'];
			$click = $row['hits'];
			$data = $mklib->create_date($row['data'], "short");
			$name1 ="<a href=\"/index.php?ind=news&amp;op=entry_view&amp;iden=$iden\">$name</a>";
			$content .= $this->tpl->row_main_entries_content($iden, $name1, $href, $postautor, $click, $data, $short_testo, $totcomments);
		}
		if (!$name) {
			$content = "<td align=\"center\" width=\"100%\" class=\"modulecell\"><br />{$mklib->lang['dw_searchnot']}<br /><br /><br /></td>";
		}
		$submit = "";
		$toolbar = "";
		$utonline = $mklib_board->get_active_users("news");
		$output  = $this->tpl->news_show($navbar, $maintit, $content, $submit, $toolbar, $show_pages, $utonline);
		$blocks = $Skin->view_block("{$mklib->lang['ne_search']}", $output);
		$mklib->printpage("1", "1", $mklib->sitename.$mklib->lang['tt_sep'].$mklib->lang['ne_news'].$mklib->lang['tt_sep'].$mklib->lang['dw_searchresult'], $blocks);

	}
*/
	
		function row_select_event($jump="") {
			global $mklib;
			
			if($jump) {
				$cselect = "<option value=\"0\">{$mklib->lang['ne_jumpcat']}</option>\n";
			}
			$listall = array();
			$children = array();
			if (!$this->chache_sections) {
				return FALSE;
			}
			foreach ($this->chache_sections as $row) {
				if ($row['parentid']) {
					$children[ $row['parentid'] ][] = $row;
				}
			}
			
			foreach ($this->chache_sections as $row) {
				$idevento = $row['id'];
				$evento = $row['title'];
				$father = $row['parentid'];
				if (!in_array($idevento, $listall) && !$row['parentid']) {
					$cselect.= "<option value=\"$idevento\">$evento</option>\n";
					$listall[] = $idevento;
				}
				$pref = "|";
				if (count($children[$idevento]) > 0) {
					foreach($children[$idevento] as $row3) {
						$pref = "|--";
						if (!in_array($row3['id'], $listall)) {
							$cselect.= "<option value=\"$row3[id]\">$pref $row3[title]</option>\n";
							$listall[] = $row3['id'];
						}
						$idevento = $row3['id'];
						while ($idevento) {
				   			$pref .= "--";
				   			$newfather = "";
				   			reset ($this->chache_sections);
				   			foreach ($this->chache_sections as $row2) {
								if ($idevento == $row2['parentid']) {
									$newfather = $row2['id'];
									if (!in_array($row2['id'], $listall)) {
										$cselect.= "<option value=\"{$row2['id']}\">$pref {$row2['title']}</option>\n";
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
			return $r['title'];
		}
		function retrieve_rewrite_urls($idevento) {
			foreach ($this->chache_sections as $r) {
				if($r['id'] == $idevento) {
					break;
				}
			}
			$rewrite_urls = $r['rewrite_urls'];
			if ($rewrite_urls) {
	   return $rewrite_urls = "$idevento-{$rewrite_urls}";
		}
		else {
		return	$rewrite_urls = $idevento;
		}
		}
		function retrieve_father($idevento) {
			foreach ($this->chache_sections as $row) {
				if($row['id'] == $idevento) {
					break;
				}
			}
			if($row['parentid']) {
				$evento = $this->retrieve_event($row['parentid']);
				$rewrite_urls = $this->retrieve_rewrite_urls($row['parentid']);
			}
			return array ($row['parentid'], $evento, $rewrite_urls);
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
			if (in_array($r['parentid'], $father)) {
				$father[] = $r['id'];
   				++$count;
   			}
		
		}
		foreach ($this->chache_download as $r) {
			global $mklib;
			if (in_array($r['idcategoria'], $father)) {
				$lastfile = "<a href=\"/index.php?ind=news&amp;op=entry_view&amp;iden={$r['id']}\">".$r['titolo']."</a>";
   				++$countfile;
   			}
		
		}	
		
		return array($count, $countfile, $lastfile);
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
		$autore = $mkportals->member['name'];
		$autorid = $mkportals->member['id'];
		$autore = iconv("UTF-8", "{$mklib->charset}", $autore);
        $testo = iconv("UTF-8", "{$mklib->charset}", $testo1);
		$modname ='news';
		if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_send_comments']) {
			$message = "{$mklib->lang['ne_nosendcom']}";
			$mklib->Ajax_error_page($message);
			exit;
		}
		echo $autore;
		if ($mklib->config['antibot_chek'] && !$mkportals->member['id']){
$captcha_code = $mkportals->input['check'];
$captcha_check = $mklib->antibot_check($captcha_code);
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
		$query = $DB->query( "SELECT totalcomm FROM mkp_news WHERE id = '$ide'");
		$row = $DB->fetch_row($query);
		$totalcomm = $row['totalcomm'];
		++$totalcomm;
		$DB->query("UPDATE mkp_news SET totalcomm ='$totalcomm' WHERE id = '$ide'");
		//$DB->close_db();
	    $query1 = $DB->query( "SELECT id, cid, module, data, memid, name, memip, comment, status FROM mkp_comments WHERE cid = '$ide' AND module = 'news'  ORDER BY `id` DESC");
	    $content2 = "
        <tr>
        <td class=\"tdblock\" width=\"100%\" colspan=\"2\">
		{$mklib->lang['ne_comments']}

		<script type=\"text/javascript\">

			function makesure3() {
			if (confirm('{$mklib->lang['ne_delcommconf']}')) {
			return true;
			} else {
			return false;
			}
			}

			</script>
		</td>
		</tr>
		
		"; 
	 	while( $row = $DB->fetch_row($query1) ) {
			$idcomm = $row['id'];
			$autore = $row['name'];
			$testo = stripslashes($row['comment']);
			$testo = $mklib->decode_bb($testo);
			$data = $mklib->create_date($row['data'], "short");
			$delete = "
			<a href=\"/index.php?ind=news&amp;op=del_comment&amp;idcomm=$idcomm&amp;ide=$ide\" onclick=\"return makesure3()\">[ {$mklib->lang['ne_delete']} ]</a>
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
	


}?>
