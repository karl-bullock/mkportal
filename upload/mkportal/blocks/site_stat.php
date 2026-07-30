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
	$link_user = $mklib_board->forum_link("profile");
	$counter = $this->config['counter'];
	$stat = $mklib_board->get_site_stat();
	$count_holder = sprintf ("%07d", "$counter");
	
	
	$countgal = $this->stats['tot_gallery'];
	$countdown = $this->stats['tot_download'];
	$countblog = $this->stats['tot_blog'];
	$countsite = $this->stats['tot_topsite'];
	$countrev = $this->stats['tot_reviews'];

for ($n = 0; $n < strlen($count_holder); $n++) ($nbhits .= "<img src=\"$this->images/led/{$count_holder[$n]}.gif\" style=\"border: solid 1px #ffffff;\" alt=\"\" />");

$content .= "
				<tr>
				  <td class=\"tdblock\">
				  <span class=\"mktxtcontr\">{$stat['total_posts']}</span> {$this->lang['posts']}:
				  </td>
				</tr>
				<tr>
				  <td class=\"tdglobal\">
				  {$stat['topics']} {$this->lang['topics']}
				  </td>
				</tr>
				<tr>
				  <td class=\"tdglobal\">
				  {$stat['replies']} {$this->lang['replies']}<br />
				  </td>
				</tr>";

if (!$this->config['mod_blog']) {		
	$content .= "				
				<tr>
				  <td class=\"tdblock\">
				  <span class=\"mktxtcontr\">$countblog</span> {$this->lang['blog']}:
				  </td>
				</tr>
				<tr>
				  <td class=\"tdglobal\">
				  {$this->lang['user_created']}<br />
				  </td>
				</tr>";
}				
if (!$this->config['mod_gallery']) {				
	$content .= "
				<tr>
				  <td class=\"tdblock\">
				  <span class=\"mktxtcontr\">$countgal</span> {$this->lang['images']}:
				  </td>
				</tr>
				<tr>
				  <td class=\"tdglobal\">
				  {$this->lang['in_gallery']}<br />
				  </td>
				</tr>";
}
if (!$this->config['mod_downloads']) {				
	$content .= "
				<tr>
				  <td class=\"tdblock\">
				  <span class=\"mktxtcontr\">$countdown</span> {$this->lang['files']}:
				  </td>
				</tr>
				<tr>
				  <td class=\"tdglobal\">
				  {$this->lang['in_download']}<br />
				  </td>
				</tr>";
}
if (!$this->config['mod_topsite']) {				
	$content .= "				
				<tr>
				  <td class=\"tdblock\">
				  <span class=\"mktxtcontr\">$countsite</span> {$this->lang['sites']}:
				  </td>
				</tr>
				<tr>
				  <td class=\"tdglobal\">
				  {$this->lang['in_topsite']}<br />
				  </td>
				</tr>";
}
if (!$this->config['mod_reviews']) {				
	$content .= "				
				<tr>
				  <td class=\"tdblock\">
				  <span class=\"mktxtcontr\">$countrev</span> {$this->lang['reviews']}:
				  </td>
				</tr>
				<tr>
				  <td class=\"tdglobal\">
				  {$this->lang['in_reviews']}<br />
				  </td>
				</tr>";
}

$content .= "				
				<tr>
				  <td class=\"tdblock\">
				  <span class=\"mktxtcontr\">{$stat['members']}</span> {$this->lang['u_registered']}:
				  </td>
				</tr>
				<tr>
				  <td class=\"tdglobal\">
				  {$this->lang['last']}: <a class=\"uno\" href=\"$link_user={$stat['last_member']}\">{$stat['last_member_name']}</a>
				  </td>
				</tr>
				<tr>
				  <td class=\"tdblock\">
				  {$this->lang['access_counter']}:
				  </td>
				</tr>
				<tr>
				  <td class=\"tdglobal\">
				  <center>$nbhits</center>
				  </td>
				</tr>
";

	unset($link_user);
	unset($counter);
	unset($stat);
	unset($count_holder);
	unset($query);
	unset($countgal);
	unset($countdown);
	unset($countblog);
	unset($countsite);
	unset($countrev);


?>