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

$idx = new mk_quote;
class mk_quote {

	var $tpl       = "";

	function mk_quote() {

		global $mkportals, $mklib, $Skin;
		
		$mklib->load_lang("lang_quote.php");

		if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_access_quote']) {
			$message = $mklib->lang['qu_noenteru'];
			$mklib->error_page($message);
			exit;
		}
		if ($mklib->config['mod_quote']) {
		$message = "{$mklib->lang['qu_mnoactive']}";
			$mklib->error_page($message);
			exit;
		}


    		switch($mkportals->input['op']) {
    			case 'reg_data':
    				$this->reg_data();
    			break;
				case 'submit_quote':
    				$this->submit_quote();
    			break;
    			case 'show_emoticons':
    				$this->show_emoticons();
    			break;
    			default:
    				$this->quote_show();
    			break;
    		}
	}
	function quote_show() {
		global $mkportals, $DB, $std, $print, $mklib, $Skin, $mklib_board;


		$start = intval($mkportals->input['start']);
		$link_user = $mklib_board->forum_link("profile");

		$query = $DB->query("SELECT id FROM mkp_quotes WHERE validate = '1'");
		$count = $DB->get_num_rows($query);

		$q_page		=	intval($mkportals->input['st']);
		if ($q_page=="" or $q_page <= 0) {
			$q_page	=	0;
		}
		$per_page = $mklib->config['quote_page'];
		if ($per_page=="" or $per_page <= 0) {
			$per_page	=	10;
		}

	    $start = $q_page;
		$show_pages = $mklib->build_pages( array( TOTAL_POSS  => $count,
							PER_PAGE    => $per_page,
							CUR_ST_VAL  => $q_page,
							L_SINGLE    => '',
							L_MULTI     => 'pagine',
						    BASE_URL    => '/index.php?ind=quote',
										  )
								   );

	$submit = "<br />[ <a href=\"/index.php?ind=quote&amp;op=submit_quote\">{$mklib->lang['qu_add']}</a> ] <br />";
	if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_send_quote']) {
		$submit ="";
	}

	$query = $DB->query( "SELECT id, author, member, member_id, quote, date_added FROM mkp_quotes WHERE validate = '1' ORDER BY `id` DESC LIMIT $start, $per_page");
	while( $row = $DB->fetch_row($query) ) {
		$author = $row['author'];
		$member = $row['member'];
		$member_id = $row['member_id'];
		$quote = $row['quote'];
		$date_added = $mklib->create_date($row['date_added']);

		$content.= "<div class=\"trattini3\">";
		$content.= "<br />{$mklib->lang['urlo_by']} <i><a href=\"$link_user=$member_id\" class=\"uno\">$member</a></i>   {$mklib->lang['urlo_time']} $date_added";
		$content .= "<br /><br /><i><span class=\"mkquote\">$quote</span></i>   ($author)<br /><br /></div>";
	}
	$output = "

		<!-- questa è la cella di visualizzazione citazioni -->
		<tr>
		  <td width=\"95%\" class=\"mkalign2\">
		  $submit
		  </td>
		</tr>
		<tr>
		  <td class=\"taburlo\">
		  <div class=\"taburlo\">
		    <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
        	      <tr>
			<td valign=\"top\">{$content}</td>
          	      </tr>
          	    </table>
		  </div>

		  <div style=\"margin: 4px 0\">{$show_pages}</div>
		  </td>
		</tr>
					
		<tr>
		  <td align=\"center\"><br /><br />
		  <div align=\"center\"><a href=\"http://www.mkportal.it\" target=\"_blank\">MKPQuote</a> &copy;2003-2008 <a href=\"http://www.mkportal.it\" target=\"_blank\">mkportal.it</a></div>
		  </td>
		</tr>
	    		

	";
	$blocks .= $Skin->view_block("{$mklib->lang['qu_pagetitle']}", $output);
	$mklib->printpage("1", "1", $mklib->sitename.$mklib->lang['tt_sep'].$mklib->lang['qu_pagetitle'], $blocks);

 }

	function submit_quote() {
		global $mkportals, $DB, $mklib, $Skin;

		$iduser = $mkportals->member['id'];
		//$mode = $mkportals->input['mode']; //deprecated

		if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_send_quote']) {
			$message = "{$mklib->lang['qu_noautsendu']}";
			$mklib->error_page($message);
			exit;
		}

		$output = "
<tr>
  <td><br />
    <table class=\"moduleborder\" cellspacing=\"1\" cellpadding=\"0\" width=\"98%\" align=\"center\" border=\"0\">
      <tr>
	<td>
	  <table class=\"modulebg\" cellspacing=\"1\" cellpadding=\"0\" width=\"100%\" border=\"0\">
	    <tr>
	      <td class=\"tdblock\" width=\"100%\" height=\"25\"><img class=\"mkicon\" src=\"$mklib->images/arrow.gif\" alt=\"\" />{$mklib->lang['qu_sign']}</td>
	    </tr>
	    <tr>
	      <td>
	    	<table cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" border=\"0\">
		  <tr>
		    <td>
		      <table cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" border=\"0\">	  
			<tr>
			  <td class=\"modulex\">

			    <form action=\"/index.php?ind=quote&amp;op=reg_data\" name=\"e_b\" method=\"post\">
			    <table width=\"100%\" border=\"0\">
			      <tr>
				<td class=\"titadmin\"><br />{$mklib->lang['qu_instruction']}<br /><br /> </td>
			      </tr>
			      <tr>
				<td>{$mklib->lang['qu_author']}</td>
			      </tr>
			      <tr>
				<td>
				  <input type=\"text\" name=\"author\" size=\"70\" class=\"bgselect\" />
				</td>
			      </tr>
			      <tr>
				<td>{$mklib->lang['qu_text']}</td>
			      </tr>
			      <tr>
			      <td>
				<textarea cols=\"70\" rows=\"8\" name=\"Post\"></textarea>
			      </td>
			    </tr>
			    <tr>
			      <td>
				<input type=\"submit\" value=\"{$mklib->lang['qu_send']}\" class=\"mkbutton\" />
			      </td>
			    </tr>
			  </table>
			  </form>				  
		
			</td>
		      </tr>
		    </table>
		  </td>
		</tr>
	      </table>
	    </td>
	  </tr>
 	</table>
      </td>
    </tr>
  </table>
  </td>
</tr>

<tr>
  <td align=\"center\"><br /><br />
  <div align=\"center\"><a href=\"http://www.mkportal.it\" target=\"_blank\">MKPQuote</a> &copy;2003-2008 <a href=\"http://www.mkportal.it\" target=\"_blank\">mkportal.it</a></div>
  </td>
</tr>
	";
		$blocks .= $Skin->view_block("{$mklib->lang['qu_pagetitle']}", $output);
		$mklib->printpage("1", "1", $mklib->sitename.$mklib->lang['tt_sep'].$mklib->lang['qu_pagetitle'].$mklib->lang['tt_sep'].$mklib->lang['qu_sign'], $blocks);

	}

	function reg_data() {
    		global $mkportals, $DB, $std, $print, $mklib, $mklib_board;


		if(!$mkportals->member['g_access_cp'] && !$mklib->member['g_send_quote']) {
			$message = "{$mklib->lang['qu_noautsendu']}";
			$mklib->error_page($message);
			exit;
		}
		if (!$mkportals->input['Post']) {
			$message = "{$mklib->lang['qu_inserttx']}";
			$mklib->error_page($message);
			exit;
		}

		$quote = $mkportals->input['Post'];
		$author = $mkportals->input['author'];
		$member = $mkportals->member['name'];
		$member_id = $mkportals->member['id'];
		$curdata = time();

		if (!$author) {
			$author = $mklib->lang['qu_anon'];
		}

		$validat = "1";
		$approval = $mklib->config['approval_quote'];
		if ($approval == "2" || $approval == "3") {
			$validat = 0;
		}
		if($mkportals->member['g_access_cp']) {
			$validat = "1";
		}

		$query="INSERT INTO mkp_quotes(author, member, member_id, quote, date_added, validate)VALUES('$author', '$member', '$member_id', '$quote', '$curdata', '$validat')";
		$DB->query($query);
		if ($approval == "1") {
			$mailsubj = $mklib->lang['01mail'].$mklib->lang['quote'];
			$mailmess = $mklib->lang['02mail'].$mklib->lang['module'].$mklib->lang['quote']."\n".$mklib->lang['sender'].$member."\n\n\n".$mklib->lang['from']." ".$mklib->sitename;
			$mklib_board->admin_mail($mailsubj, $mailmess);
		}
		if ($approval == "2" && !$mkportals->member['g_access_cp']) {
			$mailsubj = $mklib->lang['01mail'].$mklib->lang['quote'];
			$mailmess = $mklib->lang['03mail'].$mklib->lang['module'].$mklib->lang['quote']."\n".$mklib->lang['sender'].$member."\n\n\n".$mklib->lang['from']." ".$mklib->sitename;
			$mklib_board->admin_mail($mailsubj, $mailmess);
			$mklib->message_page ($mklib->lang['qu_signok']);
			exit;
		}
		if ($approval == "3" && !$mkportals->member['g_access_cp']) {
			$mklib->message_page ($mklib->lang['qu_signok']);
			exit;
		}
		$query = $DB->query( "SELECT id FROM mkp_quotes WHERE validate = '1'");
		$count = $DB->get_num_rows($query);
		$DB->query("UPDATE mkp_stat SET valore ='$count' WHERE chiave = 'tot_quotes'");
		$DB->close_db();
	 	Header("Location: /index.php?ind=quote");
		exit;
  }

	

}
?>
