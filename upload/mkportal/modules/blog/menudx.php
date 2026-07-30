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
|   > Written By Amedeo de longis & Monica Vecchi
|   > Date started: 9.2.2004
|
+--------------------------------------------------------------------------
*/

$this->load_lang("lang_blogmenudx.php");

$queryb = $DB->query("SELECT id FROM mkp_blog WHERE validate = '1'");
	$countblog = $DB->get_num_rows($queryb);
	$queryb = $DB->query("SELECT id FROM mkp_blog_post");
	$countpost = $DB->get_num_rows($queryb);
	$queryb = $DB->query("SELECT id FROM mkp_blog_commenti");
	$countcomm = $DB->get_num_rows($queryb);

//Blog Stats
$contentdx = "
				  <tr>
				    <td class=\"tdblock\">
				      <span class=\"mktxtcontr\">$countblog</span> <b>{$this->lang['mb_blog']}</b>:
				    </td>
				  </tr>
				  <tr>
				    <td class=\"tdglobal\">
				    {$this->lang['mb_usercreated']}<br />
				  </td>
				</tr>
				<tr>
				  <td class=\"tdblock\">
				  <span class=\"mktxtcontr\">$countpost</span> <b>{$this->lang['mb_msgs']}</b>:
				  </td>
				</tr>
				<tr>
				  <td class=\"tdglobal\">
				  {$this->lang['mb_userwrite']}<br />
				  </td>
				</tr>
				<tr>
				  <td class=\"tdblock\">
				  <span class=\"mktxtcontr\">$countcomm</span> <b>{$this->lang['mb_comments']}</b>:
				  </td>
				</tr>
				<tr>
				  <td class=\"tdglobal\">
				  {$this->lang['mb_msgwrite']}<br />
				  </td>
				</tr>
     	 	";
	$menudx .= $Skin->view_block("{$this->lang['mb_stats']}", $contentdx);

	
	//Most recent Posts
	$contentdx = "";
	$query = $DB->query("select id, id_blog, post FROM mkp_blog_post ORDER BY id DESC LIMIT 10");

	if ($DB->get_num_rows($query)) {
		while( $row = $DB->fetch_row($query) ) {
			$id = $row['id'];
			$idb = $row['id_blog'];
			$post = $row['post'];
			$post = str_replace ("<br />", " ", $post);
			$post = $this->decode_bb($post);
			$post = strip_tags ($post);
			$post = stripslashes($post);
			$post = substr ($post, 0, 80);
			$post = wordwrap($post, 20, "\n", 1);
            $contentdx .= "
				<tr>
				  <td class=\"tdglobal\">
				  <img class=\"mkicon\" src=\"$this->images/frec.gif\" align=\"left\" alt=\"\" />&nbsp;$post ...
				  </td>
				</tr>
				<tr>
				  <td class=\"tdblock\">
				  <a href=\"$this->siteurl/index.php?ind=blog&amp;op=home&amp;idu=$idb\">{$this->lang['mb_continued']}...</a>
				  </td>
				</tr>
			";
        	}
	$menudx .= $Skin->view_block("{$this->lang['mb_precents']}", $contentdx);
	}

	//Last Comments
	$contentdx = "";
	$query = $DB->query("SELECT id, id_blog, id_post, autore, commento FROM mkp_blog_commenti ORDER BY id DESC LIMIT 5");
	
	if ($DB->get_num_rows($query)) {
		while( $row = $DB->fetch_row($query) ) {
			$id = $row['id'];
			$idb = $row['id_blog'];
			$idp = $row['id_post'];
			$autore = $row['autore'];
			$post = $row['commento'];
			$post = str_replace ("<br />", " ", $post);
			$post = $this->decode_bb($post);
			$post = strip_tags ($post);
			$post = stripslashes($post);
			$post = substr ($post, 0, 80);
			$post = wordwrap($post, 20, "\n", 1);
            $contentdx .= "
				<tr>
				  <td class=\"tdglobal\">
				  <img class=\"mkicon\" src=\"$this->images/frec.gif\" align=\"left\" alt=\"\" />&nbsp;$post ...
				  </td>
				</tr>
				<tr>
				  <td class=\"tdblock\">
				  {$this->lang['from']}: <span class=\"mktxtcontr\">$autore&nbsp;</span><a href=\"$this->siteurl/index.php?ind=blog&amp;op=home&amp;idu=$idb&amp;singlepost=$idp\">>...</a>
				  </td>
				</tr>
			";
        	}
	$menudx .= $Skin->view_block("{$this->lang['b_lastcomms']}", $contentdx);
	}

	//Most Commented
	$contentdx = "";
	$query = $DB->query("SELECT id_blog, post, ncom FROM mkp_blog_post ORDER BY ncom DESC LIMIT 10");

	if ($DB->get_num_rows($query)) {
		while( $row = $DB->fetch_row($query) ) {
			$idb = $row['id_blog'];
			$post = $row['post'];
			$ncom = $row['ncom'];
			$post = str_replace ("<br />", " ", $post);
			$post = $this->decode_bb($post);
			$post = strip_tags ($post);
			$post = stripslashes($post);
			$post = substr ($post, 0, 80);
			$post = wordwrap($post, 20, "\n", 1);
            $contentdx .= "
				<tr>
				  <td class=\"tdglobal\">
				  <img class=\"mkicon\" src=\"$this->images/frec.gif\" align=\"left\" alt=\"\" />&nbsp;$post ...
				  </td>
				</tr>
				<tr>
				  <td class=\"tdblock\">
				  <span class=\"mktxtcontr\">$ncom</span> {$this->lang['mb_comments']}&nbsp;<a href=\"$this->siteurl/index.php?ind=blog&amp;op=home&amp;idu=$idb\">>...</a>
				  </td>
				</tr>
			";
        	}
	$menudx .= $Skin->view_block("{$this->lang['mb_pcomments']}", $contentdx);
	}

?>