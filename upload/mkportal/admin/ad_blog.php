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

$idx = new mk_ad_blog;
class mk_ad_blog {


	function mk_ad_blog() {
		global $mkportals;
		switch($mkportals->input['op']) {
			case 'save_main':
    			$this->save_main();
    		break;
			case 'del_blog':
    			$this->del_blog();
    		break;
		//Refresh Static Blog Redirect Pages
            	case 'refresh_static':
                	$this->refresh_static();
            	break;		
			default:
    			$this->blog_show();
    		break;
    		}
	}

	function blog_show() {
	global $mkportals, $mklib, $Skin, $DB;

		$blog_page = $mklib->config['blog_page'];
		$blog_filenum = $mklib->config['blog_upload_num'];
		$blog_filewidth = $mklib->config['blog_upload_width'];
		// Admin Approval combo
		$approval = $mklib->config['approval_blog'];
		switch($approval) {
			case '1':
    			$selap1="selected=\"selected\"";
    		break;
			case '2':
    			$selap2="selected=\"selected\"";
    		break;
			case '3':
    			$selap3="selected=\"selected\"";
    		break;
    		default:
    			$selap="selected=\"selected\"";
    		break;
		}
		$cselecta = "<option value=\"0\" $selap>{$mklib->lang['ad_approp_0']}</option>\n";
		$cselecta .= "<option value=\"1\" $selap1>{$mklib->lang['ad_approp_1']}</option>\n";
		$cselecta .= "<option value=\"2\" $selap2>{$mklib->lang['ad_approp_2']}</option>\n";
		$cselecta .= "<option value=\"3\" $selap3>{$mklib->lang['ad_approp_3']}</option>\n";

		if ($mklib->config['mod_blog']) {
		$checkactive =  "checked=\"checked\"";
   		}
		if ($mkportals->input['mode'] == "saved") {
		$checksave = "{$mklib->lang['ad_saved']}";
   		}
		if ($mkportals->input['mode'] == "deleted") {
		$checksave = "{$mklib->lang['ad_delblog']}";
   		}
		//Refresh Static Blog Redirect Pages
        	if ($mkportals->input['mode'] == "refresh_static") {
        	$checksave = "{$mklib->lang['ad_refblog']}";
           	}
		$query = $DB->query("SELECT id FROM mkp_blog");
		$count = $DB->get_num_rows($query);

		$q_page		=	intval($mkportals->input['st']);
		if ($q_page=="" or $q_page <= 0) {
			$q_page	=	0;
		}
		$per_page = 30;
		if ($per_page=="" or $per_page <= 0) {
			$per_page	=	10;
		}

	    $start = $q_page;
		$show_pages = $mklib->build_pages( array( TOTAL_POSS  => $count,
							PER_PAGE    => $per_page,
							CUR_ST_VAL  => $q_page,
							L_SINGLE    => '',
							L_MULTI     => 'pagine',
						    BASE_URL    => 'index.php?ind=ad_blog',
										  )
								   );
	 	$content  = "		
	<tr>
	  <td valign=\"top\">

	  <script type=\"text/javascript\">

			function makesureblog() {
			if (confirm('{$mklib->lang[ad_delblogconfirm]}')) {
			return true;
			} else {
			return false;
			}
			}

	  </script>
			
	    <form action=\"index.php?ind=ad_blog&amp;op=save_main\" name=\"save_main\" method=\"post\">
	    <table width=\"100%\" border=\"0\">
	      <tr>
		<td>$checksave</td>
	      </tr>
	      <tr>
		<td class=\"titadmin\" valign=\"top\">{$mklib->lang['ad_preferences']}</td>
	      </tr>
	      <tr>
		<td><span class=\"mktxtcontr\">{$mklib->lang['ad_blogdisactive']}</span> <input type=\"checkbox\" name=\"stato\" value=\"1\" $checkactive /></td>
	      </tr>
	      <tr>
		<td>{$mklib->lang['ad_blogpages']}</td>
	      </tr>
	      <tr>
		<td><input type=\"text\" name=\"blog_page\" value=\"$blog_page\" size=\"10\" class=\"bgselect\" /></td>
	      </tr>
		  <tr>
		<td>{$mklib->lang['ad_apprtit']}</td>
	      </tr>
	      <tr>
		<td>
		  <select class=\"bgselect\" size=\"1\" name=\"approvalc\">
		  {$cselecta}
		  </select>
		</td>
	      </tr>
	      <tr>
		<td>{$mklib->lang['ad_maxim_bg']}</td>
	      </tr>
	      <tr>
		<td><input type=\"text\" name=\"blog_filenum\" value=\"$blog_filenum\" size=\"10\" class=\"bgselect\" /></td>
	      </tr>
	      <tr>
		<td>{$mklib->lang['ad_maxim_bgl']}</td>
	      </tr>
	      <tr>
		<td><input type=\"text\" name=\"blog_filewidth\" value=\"$blog_filewidth\" size=\"10\" class=\"bgselect\" /></td>
	      </tr>
	      <tr>
		<td><br /><input type=\"submit\" name=\"Salve\" value=\"{$mklib->lang['ad_save']}\" class=\"mkbutton\" /></td>
	      </tr>
	    </table>
	    </form>

	  </td>
	</tr>
	<tr>
	  <td class=\"titadmin\" style=\"font-weight: normal\"><br />	  
	   
	    <table class=\"moduleborder\" cellspacing=\"1\" cellpadding=\"2\" width=\"98%\" align=\"center\" border=\"0\">
	      <tr>
		<td colspan=\"5\" class=\"titadmin\">{$mklib->lang['ad_bloglist']}</td>
	      </tr>
	      <tr>
		<th class=\"modulex mkalign1\" width=\"10%\">{$mklib->lang['ad_delete']}</th>
		<th class=\"modulex mkalign1\" width=\"10%\">{$mklib->lang['ad_address']}</th>
		<th class=\"modulex mkalign1\" width=\"20%\">{$mklib->lang['ad_author']}</th>
		<th class=\"modulex mkalign1\" width=\"20%\">{$mklib->lang['ad_title']}</th>
		<th class=\"modulex mkalign1\" width=\"40%\">{$mklib->lang['ad_description']}</th>
	      </tr>	 
	   ";
		$query = $DB->query( "SELECT id, autore, titolo, descrizione FROM mkp_blog ORDER BY `autore` LIMIT $start, $per_page");
		while( $row = $DB->fetch_row($query) ) {
			$idb = $row['id'];
			$autore = $row['autore'];
			$titolo = $row['titolo'];
			$descrizione = $row['descrizione'];

			$content .= "
	      <tr>
		<td class=\"modulecell mkalign1\"><a href=\"index.php?ind=ad_blog&amp;op=del_blog&amp;idb=$idb\" onclick=\"return makesureblog()\"><span class=\"mktxtcontr\">{$mklib->lang['ad_delete']}</span></a></td>
		<td class=\"modulecell mkalign1\"><a href=\"$mklib->siteurl/index.php?ind=blog&amp;op=home&amp;idu=$idb\" title=\"{$mklib->mkurl}/blog/$autore.html\" target=\"_blank\"><b>{$mklib->lang['ad_show']}</b></a></td>
		<td class=\"modulecell mkalign1\" ><b>$autore</b></td>
		<td class=\"modulecell mkalign1\">$titolo</td>
		<td class=\"modulecell mkalign1\">$descrizione</td>
	      </tr>
			";
		}
	 //Pagination
	 $content  .= "
	   
	    </table>
	  </td>
	</tr>

	<tr>
 	  <td>
	  &nbsp;&nbsp;{$show_pages}
	  </td>
	</tr>

	<!--Refresh Static Blog Redirect Pages-->
	<tr>
	  <td class=\"titadmin\" valign=\"top\">&nbsp;</td>
	</tr>          
	<tr>
	  <td>

	  <script type=\"text/javascript\">
		function makesurerefresh() {
		if (confirm('{$mklib->lang['ad_refblogconfirm']}')) {
		return true;
		} else {
		return false;
		}
		}
	  </script>
            
	    <form action=\"index.php?ind=ad_blog&amp;op=refresh_static\" name=\"refresh_static\" method=\"post\">
	    <table width=\"100%\" border=\"0\">  
	      <tr>
		<td>{$mklib->lang['ad_blogrefresh']}</td>
	      </tr>
	      <tr>
		<td><br /><input type=\"submit\" name=\"regenerate\" value=\"{$mklib->lang['ad_refsubmit']}\" class=\"mkbutton\" onclick=\"return makesurerefresh()\" /></td>
	      </tr>
	    </table>
	    </form>

	  </td>
	</tr>       
	<!-- end Refresh Static -->  
	

		";
		$output = $Skin->view_block("{$mklib->lang['ad_blogtitle']}", "$content");
		$mklib->printpage_admin($mklib->lang['ad_titlepage'].$mklib->lang['tt_sep'].$mklib->lang['ad_blogtitle'], $output);

	}

	function save_main() {
    	global $mkportals, $DB, $mklib;
		$blog_page = $mkportals->input['blog_page'];
		$mod_blog = $mkportals->input['stato'];
		$approval = $mkportals->input['approvalc'];
		$blog_filenum = $mkportals->input['blog_filenum'];
		$blog_filewidth = $mkportals->input['blog_filewidth'];
		
		if (!$blog_page) {
			$message = "{$mklib->lang['ad_all_rows']}";
			$mklib->error_page($message);
			exit;
		}
		$DB->query("UPDATE mkp_config SET valore ='$blog_page' WHERE chiave = 'blog_page'");
		$DB->query("UPDATE mkp_config SET valore ='$mod_blog' WHERE chiave = 'mod_blog'");
		$DB->query("UPDATE mkp_config SET valore ='$approval' WHERE chiave = 'approval_blog'");
		
		$DB->query("UPDATE mkp_config SET valore ='$blog_filenum' WHERE chiave = 'blog_upload_num'");
		$DB->query("UPDATE mkp_config SET valore ='$blog_filewidth' WHERE chiave = 'blog_upload_width'");
		
		$DB->close_db();
		Header("Location: index.php?ind=ad_blog&mode=saved");
		exit;
  	}
	function del_blog () {
		global $mkportals, $DB, $Skin, $mklib;
		$idb = intval($mkportals->input['idb']);

		$DB->query("DELETE FROM mkp_blog_commenti WHERE id_blog = '$idb'");
		$DB->query("DELETE FROM mkp_blog_post WHERE id_blog = '$idb'");
        $DB->query("DELETE FROM mkp_blog WHERE id = '$idb'");

		$usfile = "../blog/{$mkportals->member['name']}.html";
        	@unlink($usfile);
		$this->update_total();
		$DB->close_db();
	 	Header("Location: index.php?ind=ad_blog&mode=deleted");
		exit;

    }
    function update_total() {
		global $DB;
		$query = $DB->query( "SELECT id FROM mkp_blog WHERE validate = '1'");
		$count = $DB->get_num_rows($query);
		$DB->query("UPDATE mkp_stat SET valore ='$count' WHERE chiave = 'tot_blog'");
	
	}

//Refresh Static Blog Redirect Pages
    function refresh_static() {
		global $DB, $mklib;

		$query = $DB->query( "SELECT id, autore FROM mkp_blog");
		while( $row = $DB->fetch_row($query) ) {
		    $idu = $row['id'];
		    $name = $row['autore'];
            
		    $urlb = strtolower ($name);
		    $urlb = str_replace(" ", "", $urlb);
		    $urlb = "../blog/$urlb".".html";
		    $filename = "../blog/tpl_blog.html";

		    $filename2 = $urlb;
		    copy($filename, $filename2);
		    $fp = fopen($filename2, "w") or die("error opening w");
			    $testo = "<script type=\"text/javascript\">
			    <!--
				    location.href = \"$mklib->siteurl/index.php?ind=blog&op=home&idu=$idu\";
			    //-->
			    </script>";
		    fwrite($fp, $testo);
		    fclose($fp);
		}

         Header("Location: index.php?ind=ad_blog&mode=refresh_static");
        exit;
        }
//end Refresh Static

}

?>
