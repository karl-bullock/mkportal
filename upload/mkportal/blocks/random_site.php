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


	$count = $this->stats['tot_topsite'];
	$start	=	rand(0, ($count -1));
	$query = $DB->query("SELECT id, title, link, banner, banner2 FROM mkp_topsite WHERE validate = '1' LIMIT $start, 1");
	$foto = $DB->fetch_row($query);
	$id = $foto['id'];
	$title = $foto['title'];
	$link = $foto['link'];
	$banner = $foto['banner'];
	$banner2 = $foto['banner2'];

	if(!$banner2) {
		$banner2 = $banner;
	}

			$links = str_replace("http://", "", $link);
			$links2 = str_replace("/", "", $links);
			$img2 = "<img style='height:152px;width:202px'src='http://images.websnapr.com/?url=$links&size=s'>" ;
			$img = "<img src='http://webmorda.kz/site2img/?u={$links2}&s=s&q=5&r={4}'>" ;
	$content = "
				<tr>
				  <td align=\"center\"><a href=\"$link\" target=\"_blank\">$img</a>
				  </td>
				</tr>
				<tr>
				  <td class=\"tdblock\" align=\"center\">$title<br />
				  </td>
				</tr>
				";




	if(!$id) {
			$content = "
				<tr>
				  <td class=\"tdblock\" align=\"center\">
				  {$this->lang['no_rsite']}
				  </td>
				</tr>
				";
	}

	if(!$mkportals->member['g_access_cp'] && !$this->member['g_access_topsite']) {
			$content = "
				<tr>
				  <td class=\"tdblock\" align=\"center\">
				  {$this->lang['rsite_noallow']}
				  </td>
				</tr>
				";
	}
	unset($query);
	unset($count);
	unset($start);
	unset($foto);
	unset($id);
	unset($title);
	unset($link);
	unset($banner);
	unset($banner2);

?>