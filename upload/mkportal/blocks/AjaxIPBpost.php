<?php
/*-------------------------------------------------------------------------
|  MKPortal IPB 2.1.x Last Posts Table with Permissions 2.1 (for center block) 17.05.2006 
|  for MKP 1.1 x IPB 2.1.x
|  by visiblesoul <visiblesoul.net>
|  Support: http://www.visiblesoul.net/resources/forum/
+--------------------------------------------------------------------------

Want to show your appreciation for this block?
Link to me on your website using the link code below:
 
Get free <a href="http://www.visiblesoul.net/" target="_blank">MKPortal modules, blocks, hacks, and skins</a> at <a href="http://www.visiblesoul.net/" target="_blank">Visible Soul Web Design - Corpus Christi, Texas</a>!

---------------------------------------------------------------------------
Config:
--------------------------------------------------------------------------*/
$prefix = "ibf_";	// Перфикс базы форума
$limit = 10; 		// Число тем отоброжающих в блоке 
$cutoff = 30; 		// число символов в названии
$startformat = "long"; 	// Формат даты. 
			//Options are short, time, small, normal, long or leave blank for default.
$lastformat = "time"; 	// формат даты последнего ответа.
			//Options are short, time, small, normal, long or leave blank for default.
/*------------------------------------------------------------------------*/

$content = "
<tr>
  <td>

    <table class=\"moduleborder\" cellspacing=\"1\" width=\"100%\">
      <tr>
	<th class=\"modulex\" width=\"38%\" style=\"padding-left: 10px;\">Название темы</th>
	<th class=\"modulex\" width=\"22%\" style=\"padding-left: 10px;\">Автор</th>
	<th class=\"modulex\" width=\"22%\" style=\"padding-left: 10px;\">Последний ответ</th>
	<th class=\"modulex\" width=\"8%\" style=\"padding-left: 10px;\">Дата</th>
	<th class=\"modulex\" width=\"5%\" style=\"text-align: center;\">Пр</th>
	<th class=\"modulex\" width=\"5%\">От</th>
      </tr>

";

 	$DB->query("SELECT id, password, permission_array FROM ".$prefix."forums");
 	while( $f = $DB->fetch_row() ) {
  	    	$perms = unserialize(stripslashes($f['permission_array']));
		if ($mklib_board->check_permissions($perms['read_perms']) != TRUE or ($f['password'] != "" ) ) {
            		$bad[] = $f['id'];
        	} else {
         		$good[] = $f['id'];
        	}
    	}

 	if ( count($bad) > 0 ) {
     		$qe = " AND forum_id NOT IN(".implode(',', $bad ).") ";
    	}

		$DB->query("SELECT t.last_post, name, t.tid, t.title, t.views, t.posts, t.starter_id, t.start_date, t.starter_name, t.last_poster_name, t.last_poster_id, t.forum_id 
		
			    FROM ".$prefix."topics t
			    LEFT JOIN ".$prefix."forums f ON (t.forum_id = f.id)
			    WHERE state!='closed' AND approved=1 AND (moved_to IS NULL or moved_to='') $qe
			    GROUP BY t.title
			    ORDER BY t.last_post DESC LIMIT 0,$limit");		

 		while ( $post = $DB->fetch_row() ) {
		$post['title'] = strip_tags($post['title']);
		$post['title'] = str_replace( "&#33;" , "!" , $post['title'] );
		$post['title'] = str_replace( "&quot;", "\"", $post['title'] );
			if (strlen($post['title']) > $cutoff) {
				$post['title'] = substr( $post['title'],0,($cutoff - 3) ) . "...";
				$post['title'] = preg_replace( '/&(#(\d+;?)?)?(\.\.\.)?$/', '...',$post['title'] );
			} 		
        $name = $post['name'];
		$title = $post['title'];
 		$tid = $post['tid'];		
		$views = $post['views'];
		$posts = $post['posts'];
		$starterid = $post['starter_id'];
		$starter = $post['starter_name'];
		$lastname = $post['last_poster_name'];
		$lastid = $post['last_poster_id'];
		$forum_id = $post['forum_id'];
		$startdate  = $this->create_date($post['start_date'], $startformat);
		$lastdate  = $this->create_date($post['last_post'], $lastformat);

		$content .= "
<!-- topic begin -->
      <tr>
	<td class=\"modulecell\" style=\"padding-left: 10px; text-align: left;\">
		<a href=\"#\" onclick=\"ajax_showPop('{$this->sitepath}index.php?ind=ajaxmk&amp;act=forum&amp;id=$tid', 1);return false\">
		<img src=\"$this->images/load.png\" border=\"0\" align=\"middle\" alt=\"\" /></a>
	  <a style=\"text-decoration: none; font-weight: bold;\" href=\"$mkportals->forum_url/index.php?act=ST&f=$forum_id&t=$tid&view=getlastpost\" title=\"Тема расположена в разделе ->$name\">$title</a>
	</td>
		<td class=\"modulecell\" style=\"padding-left: 10px; text-align: left;\">
		<a href=\"#\" onmouseover=\"ajax_showTooltip('index.php?ind=ajaxmk&amp;act=Avatar&amp;uid=$starterid',this);return false;\" onmouseout=\"ajax_hideTooltip();\"><img src=\"$this->images/load.png\" border=\"0\" align=\"middle\" alt=\"\" /></a>
	  <a href=\"$mkportals->forum_url/index.php?showuser=$starterid\" title=\"Тему создал: $starter\"><b>$starter</b></a>
	</td>
	<td class=\"modulecell\" style=\"padding-left: 10px; text-align: left;\">
		<a href=\"#\" onmouseover=\"ajax_showTooltip('index.php?ind=ajaxmk&amp;act=Avatar&amp;uid=$lastid',this);return false;\" onmouseout=\"ajax_hideTooltip();\"><img src=\"$this->images/load.png\" border=\"0\" align=\"middle\" alt=\"\" /></a>
	  <a href=\"$mkportals->forum_url/index.php?showuser=$lastid\" title=\"Последний ответил: $lastname\"><b>$lastname</b></a>
	</td>
	<td class=\"modulecell\" style=\"padding-left: 10px; text-align: left;\">

		$lastdate</td>
	<td class=\"modulecell\" style=\"padding-right: 10px; text-align: right;\">$views</td>
	<td class=\"modulecell\" style=\"padding-right: 10px; text-align: right;\">$posts</td>		    	
	</td>
      </tr>
		
		
<!-- topic end -->    
		";
}

$content .= "
    </table>

  </td>
</tr>

     
";  
$content .= "
  <tr>
  <td>
<table class=\"moduleborder\" cellspacing=\"1\" width=\"100%\">
      <tr>
	<th class=\"modulex\" width=\"50%\" style=\"padding-left: 10px;\">Последние 5 статей</th>
	<th class=\"modulex\" width=\"25%\" style=\"padding-left: 10px;\">Автор статьи</th>
	<th class=\"modulex\" width=\"5%\" style=\"padding-left: 10px;\">Просмотров</th>
     <th class=\"modulex\" width=\"15%\" style=\"padding-left: 10px;\">Дата</th>
	 </tr>

"; 
$query = $DB->query("SELECT id, id_cat, title, click, author, date  FROM mkp_reviews WHERE id_cat NOT IN (11) ORDER BY `date` DESC LIMIT 0, 5");
while( $row = $DB->fetch_row($query) ) {
	$ide = $row['id'];
    $title = strip_tags($row['title']);
    $title = str_replace( "!" , "!" ,$title );
    $title = str_replace( "&quot;", "\"", $title );
     $dates  = $this->create_date($row['date'], "short");   
		$content .= "
<tr>
<td class=\"modulecell\">
	 <a href=\"$this->siteurl/index.php?ind=reviews&amp;op=entry_view&amp;iden=$ide\" class=\"uno\">$title</a>
</td>
<td class=\"modulecell\">
	 {$row['author']}
</td>
		<td class=\"modulecell\">
	 {$row['click']}
</td>
		</td>
		<td class=\"modulecell\">
	 {$dates}
</td>
		</tr>";
	}
$content .= "
    </table>

  </td>
</tr>

     
";
        unset($prefix);
        unset($limit);
        unset($cutoff);
        unset($startformat);
	unset($lastformat);
	unset($perms);
        unset($good);
        unset($bad);
        unset($qe);
        unset($post);
	unset($tid);
        unset($title);
        unset($views);
	unset($posts);
	unset($startdate);
	unset($lastdate);
        unset($starter);
        unset($lastname);
        unset($lastid);
	unset($forum_id);

?>
