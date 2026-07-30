<?php 
/* 
+-------------------------------------------------------------------------- 
|   MkPortal 
|   ======================================== 
|   by Meo <Amedeo de longis> 
|   Email: luponero@mclink.it 
| 
+--------------------------------------------------------------------------- 
| 
|   > MKPortal 
|   > Written By Amedeo de Longis 
|   > Date started: 9.2.2004 
| 
+-------------------------------------------------------------------------- 
|   > Last Comments in Portal v1.0 
|   > Released By Duilio 
|   > http://www.9thwonders.net     
+-------------------------------------------------------------------------- 
*/ 

global $mklib, $DB; 

        $comm_size = 150;    //Max number of characters of the comment; 
        $num_comm = 10;        //Total of comments to list in block; 
        
$content .= " 
        <tr> 
          <td> 
       <table class=\"moduleborder\" cellspacing=\"1\" cellpadding=\"2\" width=\"98%\" align=\"center\" border=\"0\"> 
          <tr> 
                <td colspan=\"7\" class=\"titadmin\">{$mklib->lang['cm_comments']}</td> 
          </tr> 
          <tr> 
              <th class=\"titadmin\" width=\"13%\" align=\"left\" >{$mklib->lang['cm_author']}</th> 
              <th class=\"titadmin\" width=\"15%\" align=\"left\">{$mklib->lang['cm_module']}</th> 
              <th class=\"titadmin\" width=\"25%\" align=\"left\">{$mklib->lang['cm_title']}</th> 
              <th class=\"titadmin\" width=\"25%\" align=\"left\">{$mklib->lang['cm_comment']}</th> 
              <th class=\"titadmin\" width=\"18%\" align=\"left\">{$mklib->lang['cm_date']}</th> 

          </tr> 
       "; 


     $SQL = " 
             ( select CONCAT('p') as `mod`, p.`id` as `id`, fp.`poll_title` as `ttl`, fp.`poll_id` as `idf`, p.`name` as `aut`, p.`comment` as `comm`, p.`data` as `dt` from `mkp_comments` as p, `mkp_poll` as fp  WHERE fp.`poll_id` = p.`cid`AND p.`module`='poll') 
                   UNION ALL
                 
               ( select CONCAT('r') as `mod`, r.`id` as `id`, fr.`title` as `ttl`, fr.`id` as `idf`, r.`name` as `aut`, r.`comment` as `comm`, r.`data` as `dt` from `mkp_comments` as r, `mkp_reviews` as fr  WHERE fr.`id` = r.`cid` AND r.`module`='reviews') 
                   UNION ALL
             ( select CONCAT('n') as `mod`, n.`id` as `id`, fn.`titolo` as `ttl`, fn.`id` as `idf`, n.`name` as `aut`, n.`comment` as `comm`, n.`data` as `dt` from `mkp_comments` as n, `mkp_news` as fn  WHERE fn.`id` = n.`cid` AND n.`module`='news') 
                   UNION ALL 
           ( select CONCAT('d') as `mod`, d.`id` as `id`, fd.`name` as `ttl`, fd.`id` as `idf`, d.`autore` as `aut`, d.`testo` as `comm`, d.`data` as `dt` from `mkp_download_comments` as d, `mkp_download` as fd WHERE fd.`id` = d.`identry` ) 
                   UNION ALL 
           ( select CONCAT('g') as `mod`, g.`id` as `id`, fg.`titolo` as `ttl`, fg.`id` as `idf`, g.`autore` as `aut`, g.`testo` as `comm`, g.`data` as `dt` from `mkp_gallery_comments` as g, `mkp_gallery` as fg WHERE fg.`id` = g.`identry` ) 
           ORDER BY `dt` DESC 
           LIMIT 0, $num_comm 
     "; 
        $DB->allow_sub_select = 1;
        $query = $DB->query($SQL); 
        while($row = $DB->fetch_row($query)) { 

              $mod = $row['mod']; 
            $id  = $row['id']; 
            $ttl = $row['ttl']; 
            $idf = $row['idf']; 
            $aut = $row['aut']; 
            $datecomm = $mklib->create_date($row['dt']); 
            $comm = stripslashes($row['comm']); 
            if ($comm_size > 0) { 
                     if (strlen($comm) > $comm_size) { 
                   $comm_cut = substr($comm, 0, $comm_size); 
                  $inipos = strpos($comm_cut, '<img'); 
                  if ( $inipos > 0){ 
                    $nova_pos = strpos($comm_cut, '>', $inipos);                     
                    if ( $nova_pos > 0){ 
                      $comm = substr($comm, 0, $nova_pos);  
                    } 
                    else { 
                      $nova_pos = strpos($comm, '>', $inipos); 
                      $comm = substr($comm, 0, $nova_pos);  
                    }                     
                  } 
                  else { 
                  $comm = $comm_cut; 
                  }                   
                  $comm = $comm . " ..."; 
                } 
              } 

            $dt = $mklib->create_date($row['dt']); 

            switch($mod) { 
             case 'p': 
                    $module = "опросы"; 
                    $mod_link = "$mklib->siteurl/index.php?ind=poll&op=poll_show&poll_id"; 
                  break; 
                case 'n': 
                    $module = "новости"; 
                    $mod_link = "$mklib->siteurl/index.php?ind=news&op=news_show_single&ide"; 
                  break; 
                  case 'd': 
                    $module = "загрузки"; 
                    $mod_link = "$mklib->siteurl/index.php?ind=downloads&op=entry_view&iden"; 
                  break; 
                case 'r': 
                    $module = "статьи"; 
                    $mod_link = "$mklib->siteurl/index.php?ind=reviews&op=entry_view&iden"; 
                  break; 
                case 'g': 
                    $module = "галерея"; 
                    $mod_link = "$mklib->siteurl/index.php?ind=gallery&op=foto_show&ida"; 
                  break; 
            } 

            $content .= " 
                <tr> 
                <td class=\"modulecell\" align=\"left\">$aut</td> 
                <td class=\"modulecell\" align=\"left\">$module</td> 
                <td class=\"modulecell\" align=\"left\"><a class=\"mktxtcontr2\" href=\"$mod_link=$idf\" target=\"_blank\">$ttl</a></td> 
                <td class=\"modulecell\" align=\"left\">$comm</td> 
                <td class=\"modulecell\" align=\"left\">$datecomm</td> 
                </tr> 
              "; 
        } 

       
$content .= "</table></td></tr>";

unset($mod, $id, $ttl, $idf); 
unset($aut, $datecomm, $comm, $comm_size); 
unset($comm_cut, $inipos, $nova_pos, $module); 
unset($mod_link); 

?>