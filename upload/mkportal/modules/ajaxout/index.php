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

global $mkportals, $DB, $mklib, $Skin, $mklib_board, $MK_TEMPLATE; 
@header("Content-type: text/html; charset={$mklib->charset}");
@header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
@header('Last-Modified: '.@gmdate('D, d M Y H:i:s').' GMT');
@header('Cache-Control: no-store, no-cache, must-revalidate');
@header('Cache-Control: post-check=0, pre-check=0', false);
@header('Pragma: no-cache');

// Make the path absolute to call functions inside and outside board.
	$mkportals->forum_url = $mklib->siteurl . "/" . $mklib->forumpath;
	$mklib->images = $mklib->siteurl."/mkportal/templates/".$MK_TEMPLATE."/images";


//Meo added in C 0.1.b
// Gallery Image
if ($mkportals->input['act'] == "showgal") {
	$id = intval($mkportals->input['idp']);
	$query = $DB->query("SELECT titolo, file FROM mkp_gallery WHERE id = '$id'");
	$row = $DB->fetch_row($query);

	switch(MK_SCRIPT) {
		case 'admin':
			$_MK_PATH = '../../';
		break;
		case 'forum':
			$_MK_PATH = '../';
    		break;
		default:
			$_MK_PATH = './';
    		break;
	}

	$file = $_MK_PATH . "/mkportal/modules/gallery/album/" .$row['file'];
	$file2 = $mklib->siteurl . "/mkportal/modules/gallery/album/" .$row['file'];
	if (function_exists('GetImageSize')) {
		$lst= GetImageSize($file);
		$imagedim = "style=\"width:{$lst[0]}px; height: {$lst[1]}px;\"";
	}
	$content = "
		<div $imagedim>
			<img src=\"{$file2}\" border=\"0\">
		</div>
	";   

/* This code does not work when allow_url_fopen = Off
	$file = $mklib->siteurl . "/mkportal/modules/gallery/album/" .$row['file'];
	if (function_exists('GetImageSize')) {
		$lst= GetImageSize($file);
		$imagedim = "style=\"width:$lst[0]px; height: $lst[1]px;\"";
	}
	$content = "
		<div $imagedim>
			<img src=\"{$file}\" border=\"0\">
		</div>
     	    ";
*/

	echo $content;
	exit;
}
// End


//Meo: added in C 0.1.b
// AvatarTip (currently used only in AEF board)
if ($mkportals->input['act'] == "AvatarTip") {
	
	$uid = intval($mkportals->input['uid']);

	$avatar_ar =  $mklib_board->get_avatar_onid("$uid");
	$avatar_username = $avatar_ar[0];
	$avatar_file = $avatar_ar[1];
	

	if (function_exists('GetImageSize')) {
		$lst= @GetImageSize($avatar_file);
		$imagedim = "style=\"width:$lst[0]px; height: $lst[1]px;\"";
	}
	
	$content = "
		<div style=\"border: 1px solid #C2C2D3;\" >
			
			
				<table cellspacing=\"1\" cellpadding=\"0\" align=\"center\" border=\"0\">
				      	<tr>
						<td class=\"sottotitolo\" style=\"padding: 0; height: 22px; text-align: center;\">
							{$avatar_username}
						</td>
				      	</tr>
				      	<tr>
						<td style=\"text-align: center;\">
						<img src=\"{$avatar_file}\" $imagedim border=\"0\">
				  		</td>
					</tr>		  
			
					<tr>
						<td class=\"tdblock\" style=\"padding: 0; vertical-align: middle;\"><img src=\"{$mklib->images}/tiploghino.gif\" border=\"0\" ></td>
					</tr>
				</table>
		</div>
     	    ";

	echo $content;
	exit;
}

//Meo: added in C 0.1.b
// Showpost (currently used only in AEF board)
if ($mkportals->input['act'] == "showspost") {

	$pid = intval($mkportals->input['idp']);
	$row = $mklib_board->get_single_post($pid);

	$content = "
<div style=\"width: 740px; border: 1px solid #C2C2D3;\">
	<table cellspacing=\"1\" cellpadding=\"1\" width=\"100%\" align=\"center\" border=\"0\">
		<tr>
			<td class=\"sottotitolo\" style=\"padding: 0; height: 22px; text-align: left;\">
				&nbsp; {$row[0]}
			</td>
		</tr>
		<tr>
			<td class=\"modulecell\" style=\"padding: 4px; text-align: left; background-color:#FEFEFE;\">
				{$row[1]}
			</td>
		</tr>		  
		<tr>
			<td class=\"tdblock\" style=\"padding: 0; vertical-align: middle;\"><img src=\"{$mklib->images}/tiploghino.gif\" border=\"0\" ></td>
		</tr>
	</table>
</div>";

	echo $content;
	exit;
}


//Meo: added in C 0.1.b
// AjaxSiteMonitor
if ($mkportals->input['act'] == "sitemon") {
	$lonlinelist= $mklib_board->forum_link("onlinelist");
	$users= $mklib_board->get_onlinehome($mklib->lang['guest']);

	$content = "";
	$logged_visible_online = $users[0];
	$logged_hidden_online = $users[1];
	$guests_online = $users[2];
	$online['portale'] = $users[3];
	$online['blog'] = $users[4];
	$online['gallery'] = $users[5];
	$online['urlobox'] = $users[6];
	$online['downloads'] = $users[7];
	$online['news'] = $users[8];
	$online['chat'] = $users[9];
	$online['topsite'] = $users[10];
	$online['reviews'] = $users[11];
	$online['forum'] = $users[12];
	unset ($users);
	
	$total_online_users = $logged_visible_online + $logged_hidden_online + $guests_online;

	$content = "
		<div style=\"width: 740px; border: 1px solid #C2C2D3;\">
			<table cellspacing=\"1\" cellpadding=\"1\" width=\"100%\" align=\"center\" border=\"0\">
				<tr>
				  <td width=\"100%\">
				    <table class=\"moduleborder\" cellspacing=\"1\" cellpadding=\"0\" width=\"100%\" align=\"center\" border=\"0\">
				      <tr>
					<td class=\"sottotitolo\">
					&nbsp;<span class=\"mktxtcontr\">$total_online_users</span> {$mklib->lang['onlineusers']}: $guests_online &nbsp;{$mklib->lang['guests']}, $logged_hidden_online &nbsp;{$mklib->lang['anons']}, $logged_visible_online &nbsp;{$mklib->lang['noanons']}
					</td>
				      </tr>

				      <tr>
					<td>
					  <table class=\"moduleborder\" cellspacing=\"1\" cellpadding=\"1\" width=\"100%\" align=\"center\" border=\"0\">
					    <tr>
					      <td style=\"text-align:left\" class=\"modulecell\" width=\"20%\" ><b>{$mklib->lang['portal_home']}</b></td>
					      <td style=\"text-align:left\" class=\"modulecell\" width=\"80%\">{$online['portale']}</td>
					    </tr>
					    <tr>
					      <td style=\"text-align:left\" class=\"modulecell\" width=\"20%\" >{$mklib->lang['forum']}</td>
					      <td style=\"text-align:left\" class=\"modulecell\" width=\"80%\">{$online['forum']}</td>
					    </tr>";

			if (!$mklib->config['mod_blog']) {
					    $content .= "
					    <tr>
					      <td style=\"text-align:left\" class=\"modulecell\" width=\"20%\" >{$mklib->lang['blog']}</td>
					      <td style=\"text-align:left\" class=\"modulecell\" width=\"80%\">{$online['blog']}</td>
					    </tr>";
			}
			if (!$mklib->config['mod_gallery']) {
					    $content .= "
					    <tr>
					      <td style=\"text-align:left\" class=\"modulecell\" width=\"20%\" >{$mklib->lang['gallery']}</td>
					      <td style=\"text-align:left\" class=\"modulecell\" width=\"80%\">{$online['gallery']}</td>
					    </tr>";
			}
			if (!$mklib->config['mod_urlobox']) {
					    $content .= "
					    <tr>
					      <td style=\"text-align:left\" class=\"modulecell\" width=\"20%\" >{$mklib->lang['urlobox']}</td>
					      <td style=\"text-align:left\" class=\"modulecell\" width=\"80%\">{$online['urlobox']}</td>
					    </tr>";
			}
			if (!$mklib->config['mod_downloads']) {
					    $content .= "
					    <tr>
					      <td style=\"text-align:left\" class=\"modulecell\" width=\"20%\" >{$mklib->lang['download']}</td>
					      <td style=\"text-align:left\" class=\"modulecell\" width=\"80%\">{$online['downloads']}</td>
					    </tr>";
			}
			if (!$mklib->config['mod_news']) {
					    $content .= "
					    <tr>
					      <td style=\"text-align:left\" class=\"modulecell\" width=\"20%\" >{$mklib->lang['news']}</td>
					      <td style=\"text-align:left\" class=\"modulecell\" width=\"80%\">{$online['news']}</td>
					    </tr>";
			}
			if (!$mklib->config['mod_chat']) {
					    $content .= "
					    <tr>
					      <td style=\"text-align:left\" class=\"modulecell\" width=\"20%\" >{$mklib->lang['chat']}</td>
					      <td style=\"text-align:left\" class=\"modulecell\" width=\"80%\">{$online['chat']}</td>
					    </tr>";
			}
			if (!$mklib->config['mod_topsite']) {
					    $content .= "
					    <tr>
					      <td style=\"text-align:left\" class=\"modulecell\" width=\"20%\" >{$mklib->lang['topsite']}</td>
					      <td style=\"text-align:left\" class=\"modulecell\" width=\"80%\">{$online['topsite']}</td>
					    </tr>";
			}
			if (!$mklib->config['mod_reviews']) {
					    $content .= "
					    <tr>
					      <td style=\"text-align:left\" class=\"modulecell\" width=\"20%\" >{$mklib->lang['reviews']}</td>
					      <td style=\"text-align:left\" class=\"modulecell\" width=\"80%\">{$online['reviews']}</td>
					    </tr>";
			}
					    
			$content .= "
					  </table>		
					</td>
				      </tr>
				      <tr>
					<td class=\"modulecell\">
					&nbsp;<img src=\"$mklib->images/atb_members.gif\" align=\"middle\" alt=\"\" />&nbsp;<a class=\"uno\" href=\"$lonlinelist\">{$mklib->lang['lastclick']}</a>
					</td>
				      </tr>		  
				    </table>
				  </td>
				</tr>
				<tr>
					<td class=\"tdblock\" style=\"padding: 0; vertical-align: middle;\"><img src=\"{$mklib->images}/tiploghino.gif\" border=\"0\" ></td>
				</tr>
			</table>
		</div>	
     	    ";

	echo $content;
	exit;
}
// End


//################################################################################
//                             AJAX AEF BOARD ONLY
//################################################################################

// Thanks for AEF BOARD
if ($mkportals->input['act'] == "Aef_Thanks") { 
	global $mklib, $mkportals, $globals, $dbtables;
	$pid = $mkportals->input['pid'];
	$divid = "thank_".$pid;

	$output = $mkportals->member['name'];
    	if (!$pid || !$mkportals->member['id']) {
        	exit;
    	}

	$DB->query("SELECT p.pid, p.poster_id, p.post_tid, u.username FROM ".$dbtables['posts']." p
		LEFT JOIN ".$dbtables['users']." u ON (u.id=p.poster_id)
		WHERE p.pid = '$pid'
	");
	$row = $DB->fetch_row();
	$userpost = $row['username'];
    	$userpostid = $row['poster_id'];
	$threadid = $row['post_tid'];

    	if (!$userpost || !$userpostid) {
        	exit;
    	}

	$curdate = time();
    	$userid = $mkportals->member['id'];
    	$username = $mkportals->member['name'];

    	$DB->query("INSERT INTO {$globals['dbprefix']}post_thanks (userid, username, date, postid, threadid) VALUES('$userid', '$username', '$curdate', '$pid', '$threadid')");

   	$DB->query("SELECT thanks_r FROM ".$dbtables['users']." WHERE id = '$userpostid'");
    	$row = $DB->fetch_row();
    	$thanks_r = $row['thanks_r'];
    	++$thanks_r;

    	$DB->query("UPDATE ".$dbtables['users']." SET thanks_r ='$thanks_r' WHERE id = '$userpostid'");


    	$DB->query("SELECT * FROM {$globals['dbprefix']}post_thanks WHERE postid = '$pid' ORDER BY username ASC");
    	$thank_tot = $DB->get_num_rows();
    	if ($thank_tot) {
		$thank_text1 .= "<div style=\"margin-top: 1px; border-left: 1px  solid #CCC; border-right: 1px  solid #CCC;\" class=\"ptip\">";
		$thank_text1 .= "The Following {$thank_tot} Users Say Thank You to {$userpost} For This Useful Post:";
		$thank_text1 .= "</div>";
		$thank_text1 .= "<div style=\"margin-bottom: 1px; padding: 5px; border: 1px solid #CCC;\" align=\"top\" class=\"ntrc\">";
        	while($row = $DB->fetch_row()) {
			$tannk_date = datify($row['date']);
            	$thank_text .= "<a href=\"{$globals['index_url']}mid=".$row['userid']."\">".$row['username']."</a> (".$tannk_date."), ";
        	}
       		 $thank_text = preg_replace( "/,\s+$/", "" , $thank_text);
		$thank_text .= "</div>";
        	$output =    "{$thank_text1}
       	 	{$thank_text}";
        	$output = str_replace("|", "", $output);
    	}

	echo "$divid|$output";
	exit;
}

// HelpToolTip
if ($mkportals->input['act'] == "HelpToolTip") {

	$mklib->load_lang("lang_help_admin.php");
	$testo = $mkportals->input['helptext'];
	$helptext = $mklib->lang[''.$testo.'']; //Thanks Kimi

	$content = "
		<div style=\"border: 1px solid #C2C2D3; width: 250px\">		
			
				<table cellspacing=\"1\" cellpadding=\"3\" align=\"center\" border=\"0\" width=\"100%\">
				      	<tr>
						<td class=\"modulecell\">
							{$helptext}
						</td>
				      	</tr>
				</table>
		</div>
     	    ";

	echo $content;
	exit;
}
//end HelpToolTip

?>
