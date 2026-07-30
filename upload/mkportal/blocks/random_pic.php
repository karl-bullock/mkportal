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

$content = "";


	
	$count = $this->stats['tot_gallery'];
	$start	=	rand(0, ($count -1));
	$query = $DB->query("SELECT id, titolo, file FROM mkp_gallery WHERE validate = '1' LIMIT $start, 1");
	$foto = $DB->fetch_row($query);
	$id = $foto['id'];
	$titolo = $foto['titolo'];
	$file = $foto['file'];
	$thumb = "t_$file";

	if (!file_exists("mkportal/modules/gallery/album/$thumb")) { //No thumbnail image

		//Classic thumbnails
		if ($this->config['classic_thumbs'] == 1) {
  			$thumb_mes = $this->ResizeImage(120,"$this->sitepath/mkportal/modules/gallery/album/$file");
			$content = "
				<tr>
				  <td align=\"center\"><a href=\"$this->siteurl/index.php?ind=gallery&amp;op=foto_show&amp;ida=$id\"><img src=\"$this->siteurl/mkportal/modules/gallery/album/$file\" border=\"0\" width=\"$thumb_mes[0]\" height=\"$thumb_mes[1]\" alt=\"{$this->lang['gallery_zoom']}\" /></a>
				  </td>
				</tr>
				<tr>
				  <td class=\"tdblock\" align=\"center\"><a class=\"uno\" href=\"$this->siteurl/index.php?ind=gallery&amp;op=foto_show&amp;ida=$id\">$titolo</a><br />
				  </td>
				</tr>
			";
		//Ajax thumbnails					
		} else { 
  			$thumb_mes = $this->ResizeImage($this->config['thumb_max_dimen'],"$this->sitepath/mkportal/modules/gallery/album/$file");
			$content = "
				<tr>
				  <td align=\"center\"><img style=\"cursor: url($this->template/images/mkajax/zoomin.cur), pointer;\" src=\"$this->siteurl/mkportal/modules/gallery/album/$file\" border=\"0\" width=\"$thumb_mes[0]\" height=\"$thumb_mes[1]\" alt=\"{$this->lang['gallery_zoom']}\" onclick=\"ajax_showPop('{$this->sitepath}index.php?ind=ajax&amp;act=showgal&amp;idp=$id', 1);return false\" />
				  </td>
				</tr>
				<tr>
				  <td class=\"tdblock\" align=\"center\"><a class=\"uno\" href=\"$this->siteurl/index.php?ind=gallery&amp;op=foto_show&amp;ida=$id\">$titolo</a><br />
				  </td>
				</tr>
			";
		}				
	} else { //Thumbnail available
		if ($this->config['classic_thumbs'] == 1) {
			//Classic thumbnails
			$content = "
				<tr>
				  <td align=\"center\"><a href=\"$this->siteurl/index.php?ind=gallery&amp;op=foto_show&amp;ida=$id\"><img src=\"$this->siteurl/mkportal/modules/gallery/album/$thumb\" border=\"0\" alt=\"{$this->lang['gallery_zoom']}\" /></a>
				  </td>
				</tr>
				<tr>
				  <td class=\"tdblock\" align=\"center\"><a class=\"uno\" href=\"$this->siteurl/index.php?ind=gallery&amp;op=foto_show&amp;ida=$id\">$titolo</a><br />
				  </td>
				</tr>
			";
		//Ajax thumbnails
		} else {
			$content = "
				<tr>
				  <td align=\"center\"><img style=\"cursor: url($this->template/images/mkajax/zoomin.cur), pointer;\" src=\"$this->siteurl/mkportal/modules/gallery/album/$thumb\" border=\"0\" alt=\"{$this->lang['gallery_zoom']}\" onclick=\"ajax_showPop('{$this->sitepath}index.php?ind=ajax&amp;act=showgal&amp;idp=$id', 1);return false\" />
				  </td>
				</tr>
				<tr>
				  <td class=\"tdblock\" align=\"center\"><a class=\"uno\" href=\"$this->siteurl/index.php?ind=gallery&amp;op=foto_show&amp;ida=$id\">$titolo</a><br />
				  </td>
				</tr>
			";
		}
	}

	if(!$id) {
			$content = "
				<tr>
				  <td class=\"tdblock\" align=\"center\">
				  {$this->lang['no_galleryim']}
				  </td>
				</tr>
				";
	}

	if(!$mkportals->member['g_access_cp'] && !$this->member['g_access_gallery']) {
			$content = "
				<tr>
				  <td class=\"tdblock\" align=\"center\">
				  {$this->lang['gallery_noallow']}
				  </td>
				</tr>
				";
	}

	unset($query);
	unset($count);
	unset($start);
	unset($foto);
	unset($id);
	unset($titolo);
	unset($file);
	unset($thumb);

?>
