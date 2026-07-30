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
$idx = new mk_ad_boardnews;
class mk_ad_boardnews {


	function mk_ad_boardnews() {
		global $mkportals;
		switch($mkportals->input['op']) {
			case 'save_main':
    			$this->save_main();
    		break;
			default:
    			$this->boardnews_show();
    		break;
    		}
	}

	function boardnews_show() {
	global $mkportals, $mklib, $Skin, $DB, $mklib_board;

		$forum_active = unserialize($mklib->config['forum_active']);
		$news_block = $mklib->config['bnews_block'];
		$news_words= $mklib->config['bnews_words'];
		$forum_list = $mklib_board->get_forum_list();

		if (!$news_words) {
			$news_words = 0;
		}
		if (!$forum_active) {
			$forum_active = array();
   		}
		if (!$news_block) {
			$news_block = 3;
   		}

		if ($mkportals->input['mode'] == "saved") {
		$checksave = "{$mklib->lang['ad_saved']}";
   		}
	 	$content  = "
	<tr>
	  <td>
	    <form action=\"index.php?ind=ad_boardnews&amp;op=save_main\" name=\"sel_g\" method=\"post\">
	    <table width=\"100%\" border=\"0\">
	      <tr>
		<td>$checksave</td>
	      </tr>
	      <tr>
		<td class=\"titadmin\">{$mklib->lang['ad_preferences']}</td>
	      </tr>
	      <tr>
		<td>{$mklib->lang['ad_newsblockp']}</td>
	      </tr>
	      <tr>
		<td><input type=\"text\" name=\"news_block\" value=\"$news_block\" size=\"10\" class=\"bgselect\" /></td>
	      </tr>
	      <tr>
		<td>{$mklib->lang['ad_newsmaxwords']}</td>
	      </tr>
	      <tr>
		<td><input type=\"text\" name=\"news_words\" value=\"$news_words\" size=\"10\" class=\"bgselect\" /></td>
	      </tr>
	      <tr>
		<td>{$mklib->lang['ad_bnewschose']}</td>
	      </tr>
	      <tr>
	      	<td>
		  <table width =\"100%\" class=\"tabmain\">";
	  $clastr = "tdglobal";
	  	  
	  foreach ($forum_list as $row) {
	    	$chactive = "";
		if (in_array($row['id'], $forum_active)) {
			$chactive = "checked=\"checked\"";
   		}
	    	$active_form = $row['id']."_title";	    
		
		$content .= "
		<tr class=\"$clastr\"><td class=\"mkalign1\" width=\"70%\"><img class=\"mkicon\" src=\"$mklib->images/frec.gif\" align=\"left\" alt=\"\" />&nbsp;
		";
		$content .= "
		{$row['name']}
		";
		$content .= "
		</td><td width=\"30%\" class=\"mkalign1\">
		";
		$content .= "
		<input type=\"checkbox\" name=\"$active_form\" value=\"1\" $chactive />
		";
		$content .= "
		</td></tr>
		";
		if ($clastr == "tdglobal") {
			$clastr = "modulex";
		 } else {
			$clastr = "tdglobal";
		 } 
	  }
	
	
$content  .= "</table>
	      </td>
	      </tr>
	      <tr>
		<td>
		  <input type=\"submit\" name=\"Salva\" value=\"{$mklib->lang['ad_save']}\" class=\"mkbutton\" />
		</td>
	      </tr>
	    </table>
	    </form>
	    
	  </td>
	</tr>
		";
		$output = $Skin->view_block("{$mklib->lang['ad_bnewstitle']}", "$content");
		$mklib->printpage_admin($mklib->lang['ad_titlepage'].$mklib->lang['tt_sep'].$mklib->lang['ad_bnewstitle'], $output);

	}

	function save_main() {
    	global $mkportals, $DB, $mklib_board;
		
		$news_words = $mkportals->input['news_words'];
		$forum_active = array();
		$forum_list = $mklib_board->get_forum_list();
		foreach ($forum_list as $row) {
			$indform = $row['id']."_title";
			if ($mkportals->input[$indform]){
				$forum_active[] = $row['id'];
			}
		}
		$forum_active = serialize($forum_active);	
		//$forum_active = $mkportals->input['forum_active'];
		$news_block = $mkportals->input['news_block'];
		
		$DB->query("select valore FROM mkp_config WHERE chiave = 'forum_active'");
		if ($DB->fetch_row()){
			$DB->query("UPDATE mkp_config SET valore ='$forum_active' WHERE chiave = 'forum_active'");
			$DB->query("UPDATE mkp_config SET valore ='$news_block' WHERE chiave = 'bnews_block'");
		} else {
			$DB->query("INSERT INTO mkp_config(chiave, valore)VALUES('forum_active', '$forum_active')");
			$DB->query("INSERT INTO mkp_config(chiave, valore)VALUES('bnews_block', '$news_block')");
		}
		
		$DB->query("SELECT valore FROM mkp_config WHERE chiave = 'bnews_words'");
		if ($DB->fetch_row()){
			$DB->query("UPDATE mkp_config SET valore ='$news_words' WHERE chiave = 'bnews_words'");
		} else {
			$DB->query("INSERT INTO mkp_config(chiave, valore)VALUES('bnews_words', '$news_words')");
		}
		$DB->close_db();
		Header("Location: index.php?ind=ad_boardnews&mode=saved");
		exit;
  	}



}

?>
