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

$idx = new mk_urlobox;
class mk_urlobox {

	var $tpl       = "";

	function mk_urlobox() {

		global $mkportals, $mklib, $Skin, $mklib_board;
		
		$mklib->load_lang("lang_urlo.php");
//Meo: Changed in C 0.1		
		if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_access_urlobox'] && $mkportals->input['op'] != "AEF") {
// End
			$message = "{$mklib->lang['ur_noenteru']}";
			$mklib->error_page($message);
			exit;
		}
		if ($mklib->config['mod_urlobox'] && $mkportals->input['op'] != "show_emoticons") {
		$message = "{$mklib->lang['ur_mnoactive']}";
			$mklib->error_page($message);
			exit;
		}

		//location
		$mklib_board->store_location("urlobox");

    		switch($mkportals->input['op']) {
    			case 'reg_data':
    				$this->reg_data();
    			break;
			case 'delete':
    				$this->delete_urlo();
    			break;
    			case 'show_emoticons':
    				$this->show_emoticons();
    			break;
//Meo: Changed in C 0.1	
			case 'ajax_save':
    				$this->ajax_save();  
    			break;
// End
    			default:
    				$this->urlo_show();
    			break;
    		}
	}
	function urlo_show() {
		global $mkportals, $DB, $std, $print, $mklib, $Skin, $mklib_board;
		$link_user = $mklib_board->forum_link("profile");

		$start = intval($mkportals->input['start']);

		$query = $DB->query("SELECT id FROM mkp_urlobox");
		$count = $DB->get_num_rows($query);

		$q_page		=	intval($mkportals->input['st']);
		if ($q_page=="" or $q_page <= 0) {
			$q_page	=	0;
		}
		$per_page = $mklib->config['urlo_page'];
		if ($per_page=="" or $per_page <= 0) {
			$per_page	=	10;
		}

	    $start = $q_page;
		$show_pages = $mklib->build_pages( array( TOTAL_POSS  => $count,
							PER_PAGE    => $per_page,
							CUR_ST_VAL  => $q_page,
							L_SINGLE    => '',
							L_MULTI     => 'pagine',
						    BASE_URL    => '/index.php?ind=urlobox',
										  )
								   );

	$query = $DB->query( "SELECT id, idaut, name, message, time FROM mkp_urlobox ORDER BY `id` DESC LIMIT $start, $per_page");
	while( $row = $DB->fetch_row($query) ) {
		$name = $row['name'];
		$id_orig_name = $row['idaut'];
		$content.= "<div class=\"trattini3\">";
		$content.= "<br />{$mklib->lang['ur_sentby']} <b><a href=\"$link_user=$id_orig_name\">";
		$content .= $row['name'];
		$content.= "</a></b> {$mklib->lang['ur_on']} ";
		$content .= $mklib->create_date($row['time']);

		if($mkportals->member['g_access_cp'] || $mklib->member['g_mod_urlobox']) {
			$content .= "
			<script type=\"text/javascript\">

			function makesure() {
			if (confirm('{$mklib->lang[ur_delcommconf]}')) {
			return true;
			} else {
			return false;
			}
			}

			</script><a href=\"/index.php?ind=urlobox&amp;op=delete&amp;idurlo={$row['id']}\" onclick=\"return makesure()\">[ {$mklib->lang['ur_delete']} ]</a>
			";
		}
		$content .= "<br /><br />";
		$message = $mklib->decode_bb($row['message']);
		$content .= "$message";
		$content .= "<br /><br /></div>";
	}
	$bbeditor= $mklib->get_commentbbeditor();
	$output = "
<tr>
  <td>
  <script type=\"text/javascript\">
  function emo_pop()
  {
	  window.open('{$mkportals->base_url}act=legends&amp;CODE=emoticons&amp;s={$mkportals->session_id}','Legends','width=250,height=500,resizable=yes,scrollbars=yes');
  }
  </script>

  <form action=\"/index.php?ind=urlobox&amp;op=reg_data\" name=\"editor\" method=\"post\" >

    <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">
      <tr>
	<td class=\"taburlo\">
	  <div class=\"taburlo\">		    
		    
	  <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"8\" align=\"center\">
<!-- questa è la cella della form -->
            <tr>
<!-- questa è la cella della form -->

	      <td>{$mklib->lang['ur_typeu']}</td>
	    </tr>
	    <tr>
	      <td width=\"70%\" align=\"left\">
		  $bbeditor
		<textarea cols=\"10\" style=\"width:75%\" rows=\"12\" name=\"ta\" id=\"ta\"></textarea>
	      </td>
	    </tr>
	    <tr>
	      <td>
		<input type=\"submit\" name=\"submit\" value=\"{$mklib->lang['ur_send']}\" class=\"mkbutton\" accesskey=\"s\" /><br />
	      </td>
	    </tr>
	  </table>
		      
	  </div>
	</td>
      </tr>
		  
<!-- questa è la cella di visualizzazione messaggini -->
      <tr>
	<td class=\"taburlo\">
	<div class=\"taburlo\">
	
	  <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
	    <tr>
	      <td valign=\"top\">{$content}</td>
	    </tr>
	  </table>
	  
	</div>
	</td>
      </tr>
    </table>

  </form>

  <div style=\"margin: 4px\">{$show_pages}</div>
  </td>
</tr>
		

			
<tr>
  <td align=\"center\"><br /><br />
    <div align=\"center\"><a href=\"http://www.mkportal.it\" target=\"_blank\">MKPUrlobox</a> &copy;2003-2008 <a href=\"http://www.mkportal.it\" target=\"_blank\">mkportal.it</a></div>
  </td>
</tr>
			
	    	 

	";
	$blocks .= $Skin->view_block("{$mklib->lang['ur_pagetitle']}", $output);
	$mklib->printpage("1", "1", $mklib->sitename.$mklib->lang['tt_sep'].$mklib->lang['ur_pagetitle'], $blocks);

 }
	function reg_data() {
    		global $mkportals, $DB, $std, $print, $mklib, $mklib_board;


		if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_send_urlobox']) {
			$message = "{$mklib->lang['ur_noautsendu']}";
			$mklib->error_page($message);
			exit;
		}
		if (!$mkportals->input['ta'] && !$mkportals->input['ta2']) {
			$message = "{$mklib->lang['ur_inserttx']}";
			$mklib->error_page($message);
			exit;
		}

		$urli_totali = $mklib->config['urlo_max'];
		if(!$urli_totali OR $urli_totali < "1") {
			$urli_totali = 300;
		}

		$message .= $mkportals->input['ta'];
		$message .= $mkportals->input['ta2'];
		$autore = $mkportals->member['name'];
		$idaut = $mkportals->member['id'];
		$curdata = time();
		$no_url = $mklib->lang['ur_no_url'];
		$no_img = $mklib->lang['ur_no_img'];

		if(!$mkportals->member['g_access_cp']) {
			$message = stripslashes($message);
			$message = preg_replace('/\[URL=(.+?)\](.+)\[\/URL\]/i',$no_url,$message);
			$message = preg_replace('/\[IMG\](.+?)\[\/IMG\]/i',$no_img,$message);
			$message = str_replace("ttp","", $message);
		}
		$message = $mklib_board->decode_smilies($message);

		$query="INSERT INTO mkp_urlobox(idaut, name, message, time)VALUES('$idaut', '$autore', '$message', '$curdata')";
	$DB->query($query);
	$this->update_last();
    //If the shouts are above the maximum storable eliminate the oldest.
	$query = $DB->query("SELECT id FROM mkp_urlobox ORDER BY `id` DESC");
	$count = $DB->get_num_rows($query);
	$row = $DB->fetch_row($query);

	while($count > $urli_totali) {
		$query = $DB->query("SELECT id FROM mkp_urlobox ORDER BY `id`");
		$row = $DB->fetch_row($query);
		$id = $row['id'];
		$DB->query("DELETE FROM mkp_urlobox WHERE id = $id");
		--$count;
	}
		$DB->close_db();
	 	Header("Location: /index.php?ind=urlobox");
		exit;
  }
  function delete_urlo() {
    		global $mkportals, $DB, $std, $mklib;

		if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_mod_urlobox']) {
			$message = "{$mklib->lang['ur_noautdelu']}";
			$mklib->error_page($message);
			exit;
		}

		$id = intval($mkportals->input['idurlo']);

		$DB->query("DELETE FROM mkp_urlobox WHERE id = $id");
		$this->update_last();
		$DB->close_db();
	 	Header("Location: /index.php?ind=urlobox");
		exit;
	}

	
 function show_emoticons()
 	{
		global $mklib_board;
		$mklib_board->show_emoticons();
 	}
function update_last() {
		global $DB;
		$DB->query( "SELECT id, name, message, time FROM mkp_urlobox ORDER BY `id` DESC LIMIT 1");
		$row = $DB->fetch_row($query);
		$DB->query("UPDATE mkp_stat SET valore ='$row[name]' WHERE chiave = 'urlo_name'");
		$DB->query("UPDATE mkp_stat SET valore ='$row[message]' WHERE chiave = 'urlo_message'");
		$DB->query("UPDATE mkp_stat SET valore ='$row[time]' WHERE chiave = 'urlo_time'");
	
	}

	function ajax_save() {
		global $mkportals, $DB, $MK_BOARD, $dbtables, $mklib_board, $mklib;
		@header("Content-type: text/html; charset={$mklib->charset}");
		@header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		@header('Last-Modified: '.@gmdate('D, d M Y H:i:s').' GMT');
		@header('Cache-Control: no-store, no-cache, must-revalidate');
		@header('Cache-Control: post-check=0, pre-check=0', false);
		@header('Pragma: no-cache');
       if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_send_urlobox']) {
			echo  $mklib->lang['ur_noautsendu'];
			exit;
		}
		$messages = $mkportals->input['value'];
		$autore = $mkportals->member['name'];
		$idaut = $mkportals->member['id'];
		$curdata = time();
		$message = iconv("UTF-8", "$mklib->charset", $messages);
		$query2="INSERT INTO mkp_urlobox(idaut, name, message, time)VALUES('$idaut', '$autore', '$message', '$curdata')";
		$DB->query($query2);
		$limit = $mklib->config['urlo_block'];
			if (!$limit) {$limit = 3;}			 $link_user = $mklib_board->forum_link("profile");
			$query=$DB->query( "SELECT id, idaut, name, message, time FROM mkp_urlobox ORDER BY `id` DESC LIMIT $limit");
			while( $row = $DB->fetch_row($query) ) {
				$uid = $row['shuid'];
				$name = "<a class='uno' href='$link_user={$row['idaut']}'>" . $row['name'] . "</a>";
				$message = preg_replace('/\[URL=(.+?)\](.+)\[\/URL\]/i',"",$row['message']);
				$message = preg_replace('/\[IMG\](.+?)\[\/IMG\]/i',"",$message);
				//$message = str_replace("ttp","", $message);
				$urlo2.= "[ " . $mklib->create_date($row['time'], 'short2') . " ] " . $name;
				$urlo2 .= ": ". $mklib->decode_bb($message);
				$urlo2 .= "<br />";
			}
		

		echo  $urlo2;
		//$DB->close_db();
		exit;
	}


}
?>
