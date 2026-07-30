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

	
	$count = $this->stats['tot_quotes'];
	$start	=	rand(0, ($count -1));
	$query = $DB->query("SELECT id, author, member, member_id, quote, date_added FROM mkp_quotes WHERE validate = '1' LIMIT $start, 1");
	$row = $DB->fetch_row($query);
		$id = $row['id'];
		$author = $row['author'];
		$member = $row['member'];
		$member_id = $row['member_id'];
		$quote = $row['quote'];
		$date_added = $row['date_added'];
		$date_added = $this->create_date($date_added, "short");
		$quote = strip_tags($quote);
            $content = "			
				<tr>
				  <td class=\"tdblock\">
				  <img class=\"mkicon\" src=\"$this->images/frec.gif\" align=\"left\" alt=\"\" /> {$this->lang['quoted_by']} <i><a href=\"$link_user=$member_id\" class=\"uno\">$member</a></i>{$this->lang['urlo_time']} $date_added
				  </td>
				</tr>
				<tr>
				  <td class=\"tdglobal\">
				  <i>$quote</i><br />(<b>$author</b>)
				  </td>
				</tr>			
				";
	if(!$id) {
			$content = "
				<tr>
				  <td class=\"tdblock\" align=\"center\">
				  {$this->lang['no_quote']}
				  </td>
				</tr>			
				";
	}

	if(!$mkportals->member['g_access_cp'] && !$this->member['g_access_quote']) {
			$content = "
				<tr>
				  <td class=\"tdblock\" align=\"center\">
				  {$this->lang['rquote_noallow']}
				  </td>
				</tr>
				";
	}


	$link_user = $mklib_board->forum_link("profile");

	unset($query);
	unset($count);
	unset($start);
	unset($id);
	unset($author);
	unset($member);
	unset($member_id);
	unset($quote);
	unset($date_added);


?>
