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

$forumsearch = $mklib_board->forum_link("forumsearch");
$content = "";

// Internal Pages
$query = $DB->query( "SELECT id FROM mkp_pages LIMIT 1");
$row = $DB->fetch_row($query);

if ($row['id']) {				
	$content .= "<tr><td class=\"tdblock\">";	
	$content .= $Skin->row_link_block(!$this->config['noicons'] ? "<img class=\"mkicon\" src=\"$this->images/frec.gif\" style=\"vertical-align: middle\" align=\"left\" alt=\"{$this->lang['internalpages']}\" />" : "", "href=\"$this->siteurl/index.php?ind=search\"", $this->lang['internalpages']);
	$content .= "</td></tr>";
}
//Gallery
if (!$this->config['mod_gallery']) {				
	$content .= "<tr><td class=\"tdblock\">";	
	$content .= $Skin->row_link_block(!$this->config['noicons'] ? "<img class=\"mkicon\" src=\"$this->images/frec.gif\" style=\"vertical-align: middle\" align=\"left\" alt=\"{$this->lang['images']}\" />" : "", "href=\"$this->siteurl/index.php?ind=gallery&amp;op=search\"", $this->lang['images']);
	$content .= "</td></tr>";
}
//Downloads
if (!$this->config['mod_downloads']) {
	$content .= "<tr><td class=\"tdblock\">";	
	$content .= $Skin->row_link_block(!$this->config['noicons'] ? "<img class=\"mkicon\" src=\"$this->images/frec.gif\" style=\"vertical-align: middle\" align=\"left\" alt=\"{$this->lang['download']}\" />" : "", "href=\"$this->siteurl/index.php?ind=downloads&amp;op=search\"", $this->lang['download']);
	$content .= "</td></tr>";
}
//Reviews
if (!$this->config['mod_reviews']) {				
	$content .= "<tr><td class=\"tdblock\">";	
	$content .= $Skin->row_link_block(!$this->config['noicons'] ? "<img class=\"mkicon\" src=\"$this->images/frec.gif\" style=\"vertical-align: middle\" align=\"left\" alt=\"{$this->lang['reviews']}\" />" : "", "href=\"$this->siteurl/index.php?ind=reviews&amp;op=search\"", $this->lang['reviews']);
	$content .= "</td></tr>";
}				
//Forum
	$content .= "<tr><td class=\"tdblock\">";	
	$content .= $Skin->row_link_block(!$this->config['noicons'] ? "<img class=\"mkicon\" src=\"$this->images/frec.gif\" style=\"vertical-align: middle\" align=\"left\" alt=\"{$this->lang['forum']}\" />" : "", "href=\"$forumsearch\"", $this->lang['forum']);
	$content .= "</td></tr>";

//Google Search
$content .= "
				<tr>
				  <td class=\"tdblock\" align=\"center\">
				    <a href=\"http://www.google.it/\"><img src=\"$this->siteurl/mkportal/modules/search/google_logo.gif\" alt=\"Google\" align=\"top\" /></a><br />

				    <form action=\"http://www.google.com/search\" method=\"get\" target=\"blank\">	    
				    <input size=\"12\" name=\"q\" class=\"mkblkinput\" /><br />
				    <input type=\"hidden\" name=\"hl\" />
				    <input type=\"submit\" name=\"btnG\" value=\"{$this->lang['m_search']}\" class=\"mkbutton\" />
				    </form>
				  </td>
				</tr>
";

unset ($forumsearch);


?>
