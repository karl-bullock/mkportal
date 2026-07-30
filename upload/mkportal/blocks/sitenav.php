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

		$query = $DB->query( "SELECT icon, title, url, position, target FROM mkp_mainlinks WHERE type = '2' AND active = '1' ORDER BY `position`");
		while( $row = $DB->fetch_row($query) ) {
			$showlink = $this->checklinkperm($row['url']);
//Meo: Changed in C 0.1 for shoutbox
		global $MK_BOARD; 
		if($MK_BOARD == "AEF"){
			if (stristr($row['url'], 'ind=urlobox')) {
				$row['url'] = "javascript:show_shoutbox();";
			}
		}
// End
			if($showlink) {continue;}
			$target = "";
			$row['url'] = str_replace("<MKURL>","$this->siteurl", $row['url']);
			$row['url'] = str_replace("<MKFURL>","$mkportals->base_url", $row['url']);
			if (stristr($row['title'], '<LNG>')) {
				$titlel = str_replace("<LNG>","", $row['title']); 
				$row['title'] = $this->lang[$titlel];
			}
			if ($row['target'] == 1 && !stristr($row['url'], 'javascript')) {
				$target = " target=\"_blank\"";
			}
			$row['icon'] = str_replace("<IMG>","$this->images", $row['icon']);
			if($this->config['rewrite_url']){
			$row['url'] = preg_replace("/index\.php\?ind=([a-z_-]+)/", "\\1/" ,$row['url']);
		}
			$row_link = $Skin->row_link_block(($row['icon'] && !$this->config['noicons']) ? "<img class=\"mkicon\" src=\"{$row['icon']}\" style=\"vertical-align: middle\" alt=\"{$row['title']}\" />" : "", "href=\"{$row['url']}\"{$target}", $row['title']);

			$content .= "
		      		<tr><td width=\"100%\" class=\"tdblock\">$row_link</td></tr>
			";
		}

unset($query);
unset($row);
unset($row_link);

?>
