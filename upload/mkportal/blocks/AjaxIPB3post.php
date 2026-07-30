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
$limit = 10; 		// Р§РёСЃР»Рѕ С‚РµР�? РѕС‚РѕР±СЂРѕР¶Р°СЋС‰РёС… РІ Р±Р»РѕРєРµ 
$cutoff = 80; 		// С‡РёСЃР»Рѕ СЃРёР�?РІРѕР»РѕРІ РІ РЅР°Р·РІР°РЅРёРё
$startformat = "long"; 	// Р¤РѕСЂР�?Р°С‚ РґР°С‚С‹. 
			//Options are short, time, small, normal, long or leave blank for default.
$lastformat = "short2"; 	// С„РѕСЂР�?Р°С‚ РґР°С‚С‹ РїРѕСЃР»РµРґРЅРµРіРѕ РѕС‚РІРµС‚Р°.
			//Options are short, time, small, normal, long or leave blank for default.
/*------------------------------------------------------------------------*/

$content = "
<tr>
  <td>

    <table class=\"moduleborder\" cellspacing=\"1\" width=\"100%\">
      <tr>
	<th class=\"modulex\" width=\"42%\" style=\"padding-left: 10px;\">Название темы</th>
	<th class=\"modulex\" width=\"18%\" style=\"padding-left: 10px;\">Автор</th>
	<th class=\"modulex\" width=\"18%\" style=\"padding-left: 10px;\">Последний ответ‚</th>
	<th class=\"modulex\" width=\"10%\" style=\"padding-left: 10px;\">Дата</th>
	<th class=\"modulex\" width=\"5%\" style=\"text-align: center;\">Пр</th>
	<th class=\"modulex\" width=\"5%\">От</th>
      </tr>

";

        
		$DB->query("SELECT t.last_post, name, t.tid, t.title, t.views, t.posts, t.starter_id, t.start_date, t.starter_name, t.last_poster_name, t.last_poster_id, t.forum_id 
		
			    FROM ".IPBPREFIX."topics t
			    LEFT JOIN ".IPBPREFIX."forums f ON (t.forum_id = f.id)
			    WHERE state!='closed' AND approved=1 AND (moved_to IS NULL or moved_to='') $qe AND tid NOT IN(23570,15616,35014)
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
        $perms = IPSMember::checkPermissions('read', $post['forum_id']);
       if ( $perms == TRUE) {
		$content .= "
<!-- topic begin -->
      <tr>
	<td class=\"modulecell\" style=\"padding-left: 10px; text-align: left;\">
		<a href=\"#\" onclick=\"ajax_showPop('{$this->sitepath}index.php?&amp;act=forum&amp;id=$tid', 1);return false\">
		<img src=\"$this->images/load.png\" border=\"0\" align=\"middle\" alt=\"\" title=\"Показать последнее сообщение\"/></a>
		<a href=\"$mkportals->forum_url/index.php?showtopic=$tid\">
		<img src=\"$this->images/frec.gif\" border=\"0\" align=\"middle\" alt=\"В начало темы\" title=\"В начало темы\"/>
	  <a style=\"text-decoration: none; font-weight: bold;\" href=\"$mkportals->forum_url/index.php?act=ST&f=$forum_id&t=$tid&view=getlastpost\" title=\"Тема расположена в разделе ->$name\">$title</a>
	</td>
		<td class=\"modulecell\" style=\"padding-left: 10px; text-align: left;\">
	<!--	<a href=\"#\" onmouseover=\"ajax_showTooltip('index.php?ind=ajaxmk&amp;act=Avatar&amp;uid=$starterid',this);return false;\" onmouseout=\"ajax_hideTooltip();\"><img src=\"$this->images/load.png\" border=\"0\" align=\"middle\" alt=\"\" /></a> -->
	  <a href=\"$mkportals->forum_url/index.php?showuser=$starterid\" title=\"Тему создал: $starter\"><b>$starter</b></a>
	</td>
	<td class=\"modulecell\" style=\"padding-left: 10px; text-align: left;\">
	<!-- <a href=\"#\" onmouseover=\"ajax_showTooltip('index.php?ind=ajaxmk&amp;act=Avatar&amp;uid=$lastid',this);return false;\" onmouseout=\"ajax_hideTooltip();\"><img src=\"$this->images/load.png\" border=\"0\" align=\"middle\" alt=\"\" /></a> -->
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
}

$content .= "
    </table>

  </td>
</tr>

     
"; 

     
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
	    
	    if ($mkportals->input['act'] == "forum") {
	    	//global $mkportals, $DB, $mklib, $Skin, $mklib_board, $MK_TEMPLATE; 

@header("Content-type: text/html; charset={$this->charset}");
@header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
@header('Last-Modified: '.@gmdate('D, d M Y H:i:s').' GMT');
@header('Cache-Control: no-store, no-cache, must-revalidate');
@header('Cache-Control: post-check=0, pre-check=0', false);
@header('Pragma: no-cache');
$pid = intval($mkportals->input['id']);
$query1 = $DB->query( "SELECT title, starter_name, last_poster_name, forum_id FROM ".IPBPREFIX."topics WHERE tid = '$pid'");
while( $row = $DB->fetch_row($query1) ) {
		$title = $row['title'];
		$forum_id = $row['forum_id'];
		$starter_name = $row['starter_name'];
		$last_poster_name = $row['last_poster_name'];
}
$query = $DB->query( "SELECT pid, author_id, author_name, post, topic_id, post_date FROM ".IPBPREFIX."posts WHERE topic_id = '$pid' ORDER BY pid DESC");
$posts = $DB->fetch_row($query);
$pid = $posts['pid'];
$authorid = $posts['author_id'];
$author_name = $posts['author_name'];
$avatar =  IPSMember::buildProfilePhoto( $authorid, "small" );
$topic_id  = $posts['topic_id'];
$tit = stripslashes($posts['post']);
$tit = str_replace("<#EMO_DIR#>", "default",$tit);
$tit = $this->decode_bb($tit);
$tit = preg_replace( '#(\[quote([^\]]+?)?\].*\[/quote\])#is', '', $tit );
$pdate  = $this->create_date($posts['post_date'], "time");

$content = "
<style type=\"text/css\" title=\"Main\" media=\"screen,print\">
.ipsUserPhoto {
	padding: 1px;
	border: 1px solid #d5d5d5;
	background: #fff;
	-webkit-box-shadow: 0px 2px 2px rgba(0,0,0,0.1);
	-moz-box-shadow: 0px 2px 2px rgba(0,0,0,0.1);
	box-shadow: 0px 2px 2px rgba(0,0,0,0.1);
}
	
	.ipsUserPhotoLink:hover .ipsUserPhoto {
		border-color: #7d7d7d;
	}
	
	.ipsUserPhoto_variable { max-width: 155px; }
	.ipsUserPhoto_large { max-width: 90px; max-height: 90px; }
	.ipsUserPhoto_medium { width: 50px; height: 50px; }
	.ipsUserPhoto_mini { width: 30px; height: 30px; }
	.ipsUserPhoto_tiny { width: 20px; height: 20px;	}
	.ipsUserPhoto_icon { width: 16px; height: 16px;	}


.topic_buttons li.important a, .topic_buttons li.important span, .ipsButton .important,
.topic_buttons li a, .topic_buttons li span, .ipsButton {
	border-left:1px solid #212121; border-right:1px solid #212121; border-top:1px solid #212121; border-bottom:0 solid #212121; background:#212121 url('topic_button.png') repeat-x top; -moz-border-radius: 3px;
	-webkit-border-radius: 3px;
	border-radius: 3px;
	-moz-box-shadow: inset 0 1px 0 0 #5c5c5c, 0px 2px 3px rgba(0,0,0,0.2);
	-webkit-box-shadow: inset 0 1px 0 0 #5c5c5c, 0px 2px 3px rgba(0,0,0,0.2);
	box-shadow: inset 0 1px 0 0 #5c5c5c, 0px 2px 3px rgba(0,0,0,0.2);
	color: #fff;
	text-shadow: 0 -1px 0 #191919;
	line-height: 30px;
	height: 30px;
	text-align: center;
	min-width: 125px;
	display: inline-block;
	cursor: pointer; font-style:normal; font-variant:normal; font-weight:300; font-size:12px; font-family:Helvetica, Arial, sans-serif; padding-left:10px; padding-right:10px; padding-top:0; padding-bottom:0
}
.topic_buttons li a:hover, .ipsButton:hover { color: #fff; }
	.topic_buttons li.non_button a {
		background: transparent !important;
		background-color: transparent !important;
		border: 0;
		box-shadow: none;
		-moz-box-shadow: none;
		-webkit-box-shadow: none;
		text-shadow: none;
		min-width: 0px;
		color: #777777;
		font-weight: normal;
	}
</style>
<div style=\"width: 760px; border: 1px solid #C2C2D3;\">

	 <table cellspacing=\"2\" cellpadding=\"2\" width=\"100%\">
	 <tbody>
	 <tr>
					<td align=\"left\" class=\"tdblock\">
					&nbsp;<img src=\"$this->images/nav.gif\" border=\"0\" alt=\"\" />&nbsp;<b>{$title}</b>
					</td>
				      </tr>
				      <tr>
					<td>
					
					<table cellspacing=\"2\" cellpadding=\"2\" width=\"100%\" >
	 <tbody>
					   <tr>
					     

					      <td width=\"60%\" align=\"left\">
						 <b>Последний ответ</b> ($pdate) от: <a href=\"$mkportals->forum_url/index.php?showuser=$authorid\">$author_name</a>
					    </tr>
	   
				  </tbody>
				</table>
					
					
					  <table width=\"100%\" align=\"center\" border=\"0\" cellpadding=\"1\" cellspacing=\"1\">
					    <tbody><tr>
					      <td width=\"10%\" align=\"center\" valign=\"top\">
						  {$avatar}					 
						  </td>
						<td width=\"90%\" style=\"padding-left: 10px;\" align=\"left\">{$tit}</td>
					    </tr>
					    </tr>
	   
				  </tbody>
				</table>				   

<table cellspacing=\"2\" cellpadding=\"2\" width=\"100%\" >
	 <tbody>
					   <tr>
					      <td align=\"left\">
						 <div style=\"float: left;\">
				<a class=\"ipsButton\" href=\"$mkportals->forum_url/index.php?showtopic=$forum_id\">Перейти в тему</a>
			</div>
						  </td>
					    </tr>
	   
				  </tbody>
				</table>


</div>";
		echo $content;
		
	exit;
}

?>
