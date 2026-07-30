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

	$idb = $this->stats['blog_id_blog'];
	$post = $this->stats['blog_post'];
	$post = str_replace ("<br />", " ", $post);
	$post = $this->decode_bb($post);
	$post = strip_tags ($post);
	$post = stripslashes($post);
        $post = substr ($post, 0, 300);
	$titolo = $this->stats['blog_titolo'];
	
            $content = "
				<tr>
				  <td class=\"tdblock\">
				  {$this->lang['blog_from']} <a href=\"$this->siteurl/index.php?ind=blog&amp;op=home&amp;idu=$idb\">$titolo</a>: 
				  </td>
				</tr>
				<tr>
				  <td class=\"tdglobal\">
				  <img class=\"mkicon\" src=\"$this->images/frec.gif\" align=\"left\" alt=\"\" />&nbsp; $post ...
				  <a href=\"$this->siteurl/index.php?ind=blog&amp;op=home&amp;idu=$idb\">{$this->lang['continue']}...</a>
				  </td>
				</tr>
			";
	
	if($idb == NULL) {
			$content = "
				<tr>
				  <td class=\"tdblock\" align=\"center\">
				  {$this->lang['no_blog']}
				  </td>
				</tr>
			";
	}	
	if(!$mkportals->member['g_access_cp'] && !$this->member['g_access_blog']) {
			$content = "
				<tr>
				  <td class=\"tdblock\" align=\"center\">
				  {$this->lang['blog_noallow']}
				  </td>
				</tr>
			";
	}
	unset($post);
	unset($id);
	unset($idb);
	unset($titolo);
	unset($quey);


?>