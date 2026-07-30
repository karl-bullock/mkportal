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

$limit = $this->config['news_block'];
if (!$limit) {
	$limit = 5;
}

$cont = "";
$content = "";
$link_user = $mklib_board->forum_link("profile");

$query1 = $DB->query("SELECT id FROM mkp_news WHERE validate = '1'");
$count = $DB->get_num_rows($query1);
$q_page		=	intval($mkportals->input['st']);
		if ($q_page=="" or $q_page <= 0) {
			$q_page	=	0;
		}
		$per_page = $limit;
		if ($per_page=="" or $per_page <= 0) {
			$per_page	=	10;
		}

	    $start = $q_page;
		$show_pages = $this->build_pages( array( TOTAL_POSS  => $count,
							PER_PAGE    => $per_page,
							CUR_ST_VAL  => $q_page,
							L_SINGLE    => '',
							L_MULTI     => 'pagine',
						    BASE_URL    => 'index.php?',
										  )
								   );
	$file = "mkportal/cache/db/block_news_$q_page.php";
if ($this->config['cache'] && file_exists($file) &&
    filemtime($file) > (time() - $this->config['cache_time'])) {
    $cont = unserialize(file_get_contents($file));
} else {
	$query = $DB->query( "SELECT n.id, n.idcategoria, n.idautore, n.titolo, n.autore, n.short_testo, n.testo, n.data, n.totalcomm, rate, trate, hits, allow_rating, s.id AS idcat, s.title AS titcat, s.img, n.pinned
	FROM mkp_news AS n
	LEFT JOIN mkp_categories AS s ON(s.id = n.idcategoria)
	WHERE validate = '1' AND allow_main = '1' ORDER BY `pinned` DESC, `id` DESC LIMIT $start, $per_page");								   
	while( $row = $DB->fetch_row($query) ) {
		$idnt = $row['id'];
		$totcomments = $row['totalcomm'];
		$id_orig_name = $row['idautore'];
		$idcategoria = $row['idcategoria'];
		$titolo = stripslashes($row['titolo']);
		$name = $row['autore'];
		$hits = $row['hits'];
		$testo = stripslashes($row['testo']);
		$short_testo = stripslashes($row['short_testo']);
		$rate = $row['rate'];
		$trate = $row['trate'];
		$allow_rating = $row['allow_rating'];
		
		if ($this->mkeditor == "BBCODE") {
			$short_testo = $this->decode_bb($short_testo);
		//	$short_testo = $mklib_board->decode_smilies($short_testo);
		}
		if ($this->mkeditor == "BBCODE") {
			$testo = $this->decode_bb($testo);
		//	$testo = $mklib_board->decode_smilies($testo);
		}
		$sezione = $row['titcat'];
		$img = $row['img'];
		$image = "{$this->images}/categories/$img";
		$pinned = $row['pinned'] ? "&nbsp;<img src=\"$this->images/pin.gif\" border=\"0\" alt=\"\" />" : '';
		$cdata = $this->create_date($row['data']);
		$news_words= $this->config['news_words'];
		
		if ($this->config['news_html']) {
			$short_testo = str_replace ("<br />", " ", $short_testo);
			$short_testo = strip_tags ($short_testo);
   		}
   		if ($this->config['news_html']) {
			$testo = str_replace ("<br />", " ", $testo);
			$testo = strip_tags ($testo);
   		}
		if ($news_words) {
			$testo = substr ($testo, 0, $news_words);
			$testo .= " ...";
   		}
   		if (!$short_testo) {
		$short_testo = $testo;
		}
   		$id = $row['id']; 
   		//$modname ='news';
   		if ($allow_rating == 0) {
			$rating = "";
   		}
   		else {
        $rating = $this->pullRating($id, "news", $rate, $trate);
   		}
   		$href = "index.php?ind=news&amp;op=entry_view&amp;iden={$row['id']}";
		$cont .= "
					  <table class=\"tabnews\" cellspacing=\"2\" cellpadding=\"2\" width=\"100%\">
					    <tbody>
					    <tr>
					      <td class=\"tdblock\" align=\"center\" width=\"5%\">
					      <img hspace=\"0\" src=\"$image\" align=\"bottom\" border=\"0\" alt=\"\" />
					      </td>
					      <td class=\"tdblock\" valign=\"top\" width=\"95%\">
					      <b>$sezione<br /><a href=\"{$href}\">$titolo</a></b>$pinned
					      </td>
					    </tr>
					    <tr>
					      <td colspan=\"2\"><br />
					      $short_testo
					      </td>
					    </tr>
					    <tr>
					      <td class=\"mkalign2\" colspan=\"2\">
					      <table class=\"tabnews\" width=\"100%\">
							<tr>
		                       <td align=\"left\">$rating</td>
								<td width=\"623\" align=\"right\">
								{$this->lang['from']}<b> <a href=\"$link_user=$id_orig_name\">$name</a></b>, $cdata <img src=\"{$this->images}/read.gif\"border=\"0\" alt=\"{$this->lang['n_read']}\" title=\"{$this->lang['n_read']}\"></a>($hits) | <img src=\"{$this->images}/comments.gif\"border=\"0\" alt=\"{$this->lang['comments']}\" title=\"{$this->lang['comments']}\">($totcomments) <a href=\"{$href}\">{$this->lang['readall']}</a></td>
							</tr>
							</table>
					      </td>
					    </tr>
					    </tbody>
					  </table>
		";
	}
	if ($this->config['cache']){
	$cache = serialize($cont);
    $fp = fopen($file,"w");
    fputs($fp, $cache);
    fclose($fp);
	}
} 
	$content = "
				<tr>
				  <td class=\"contents\">
				  <div class=\"taburlo\">
				    <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\">
				      <tr>
					<td class=\"taburlo\" valign=\"top\">
					{$cont}
					</td>
				      </tr>
				    </table>
				  </div>
				  </td>
			      	</tr>
	<tr>
					      <td class=\"mkalign2\" colspan=\"2\">
					      <table class=\"tabnews\" width=\"100%\">
							<tr>
		                       <td align=\"center\">{$show_pages}</td>
							</tr>
							</table>
					      </td>
					    </tr>
				  ";

		unset($cont);
		unset($row);
		unset($idcat);
		unset($categoria);
		unset($idnt);
		unset($query);
		unset($query2);
		unset($totcomments);
		unset($id_orig_name);
		unset($idcategoria);
		unset($titolo);
		unset($name);
		unset($testo);
		unset($sezione);
		unset($icona);
		unset($cdata);
		unset($news_words);




?>
