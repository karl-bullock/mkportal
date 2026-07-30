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

$idx = new mk_ad_main;
class mk_ad_main {


	function mk_ad_main() {
		global $mkportals;
		switch($mkportals->input['op']) {
			case 'main_save':
    				$this->main_save();
    			break;
    			case 'del_cache':
    				$this->del_cache();
    			break;
    			default:
    				$this->ad_show();
    			break;
    		}
	}

	function ad_show() {
	global $mkportals, $mklib, $Skin, $MK_BOARD, $MK_TIMEDIFF, $MK_OFFLINE, $MK_EDITOR, $MK_BOARD, $DB;

	require "../conf_mk.php"; //for mkportal IPB2 skin hack
	
	$mode = $mkportals->input['mode'];
	$sitename = $mklib->sitename;
	$siteurl = $mklib->siteurl;
	$adminpath = $mklib->adminpath;
	$template = $MK_TEMPLATE; //for mkportal IPB2 skin hack
	$mklang = $mklib->mklang;
	$forumpath = $mklib->forumpath;
	$forumpath = str_replace("/", "", "$forumpath");
	$forumpath = str_replace(".", "", "$forumpath");
	$forumview = $mklib->forumview;
	$portalview = $mklib->portalview;
	$forumcd = $mklib->forumcd;
	$forumcs = $mklib->forumcs;
	$portalwidth = $mklib->portalwidth;
	$columnwidth = $mklib->columnwidth;
	$disablegzip = $mklib->disablegzip;
	$disablenav = $mklib->disablenav;
	$ad_referer = $mklib->referer;
	$loadrightg = $mklib->loadcolumnright;
	$loadleftg = $mklib->loadcolumnleft;
	$loadrightf = $mklib->unloadforumright;
	$loadleftf = $mklib->unloadforumleft;

	//$postwhitelist = unserialize($mklib->config['postwhitelist']); //Post domain whitelist
	$postwhitelist = $mklib->config['postwhitelist']; //Post domain whitelist
	$metadesc = $mklib->config['metadesc'];
	$metakey = $mklib->config['metakey'];
	$rewrite_sep = $mklib->config['rewrite_sep'];
	$rewrite_url = $mklib->config['rewrite_url'];
    $cache_time = $mklib->config['cache_time'];
	if ($dir = @opendir("../templates/")) {
 		 while (($dirt = readdir($dir)) !== false) {
		 if ($MK_BOARD == "OXY" && $dirt == "Forum") {
			continue;
		 }
		 $selected = "";
		 if ($dirt != "." && $dirt != ".." && $dirt != "index.html" && $dirt != ".htaccess" && $dirt != "htaccess") {
		 	//$check = $mklib->sitepath."mkportal/templates/$dirt";
			if($template == $dirt) {
				$selected = "selected=\"selected\"";
			}
   		 	$cselect.= "<option value=\"$dirt\" $selected>$dirt</option>\n";
		 }
  		}
  	closedir($dir);
	}
	if ($dir = @opendir("../lang/")) {
 		 while (($dirt = readdir($dir)) !== false) {
		 $selected = "";
		 if ($dirt != "." && $dirt != ".." && $dirt != "index.html" && $dirt != ".htaccess" && $dirt != "htaccess") {
		 	$check = $mklib->sitepath."mkportal/lang/$dirt";
			if($mklang == "$check") {
				$selected = "selected=\"selected\"";
			}
   		 	$cselect2.= "<option value=\"$dirt\" $selected>$dirt</option>\n";
		 }
  		}
  	closedir($dir);
	}
//time
$curtime = $mklib->create_date(time());
$timediff = $MK_TIMEDIFF;
switch($timediff) {
	case '1':
    	$se1t2="selected=\"selected\"";
    break;
	case '2':
    	$se1t3="selected=\"selected\"";
    break;
	case '-1':
    	$se1t4="selected=\"selected\"";
    break;
	case '-2':
    	$se1t5="selected=\"selected\"";
    break;
    default:
    	$se1t1="selected=\"selected\"";
    break;
}
$cselect4 = "<option value=\"0\" $se1t1>0</option>\n";
$cselect4 .= "<option value=\"1\" $se1t2>+1</option>\n";
$cselect4 .= "<option value=\"2\" $se1t3>+2</option>\n";
$cselect4 .= "<option value=\"-1\" $se1t4>-1</option>\n";
$cselect4 .= "<option value=\"-2\" $se1t5>-2</option>\n";

//Editor by rusmkportal.ru

	$mkeditor = $MK_EDITOR;
	$selected1 = "selected=\"selected\"";
	if ($MK_EDITOR == "BBCODE") {
		$selected1 = "";
		$selected2 = "selected=\"selected\"";
	}

	$cselect3 .= "<option value=\"HTML\" $selected1>HTML</option>\n";
	$cselect3 .= "<option value=\"BBCODE\" $selected2>BBcode</option>\n";

	if ($mode == "saved") {
		$checksave = "<div class=\"bghighlight1 success\">{$mklib->lang['ad_saved']}</div>";
   	}
	
	//Portal width type (1=px or 0=%)
	$checkpv1 = "selected=\"selected\"";
	if ($portalview == "1") {
		$checkpv2 = "selected=\"selected\"";
		$checkpv1 = "";
   	}
	$checkfv2 = "checked=\"checked\"";
	if ($forumview == "1") {
		$checkfv1 = "checked=\"checked\"";
		$checkfv2 = "";
   	}
	$checkfcd1 = "checked=\"checked\"";
	if ($forumcd == "1") {
		$checkfcd1 = "";
		$checkfcd2 = "checked=\"checked\"";
   	}
	$checkfcs1 = "checked=\"checked\"";
	if ($forumcs == "1") {
		$checkfcs1 = "";
		$checkfcs2 = "checked=\"checked\"";
   	}
	$checkoff1 = "checked=\"checked\"";
	if ($MK_OFFLINE == "1") {
		$checkoff1 = "";
		$checkoff2 = "checked=\"checked\"";
   	}
	$checkgzipd2 = "checked=\"checked\"";
	if ($disablegzip == "1") {
		$checkgzipd = "checked=\"checked\"";
		$checkgzipd2 = "";
   	}
	$checknav2 = "checked=\"checked\"";
	if ($disablenav == "1") {
		$checknav1 = "checked=\"checked\"";
		$checknav2 = "";
	}
	$referer = "checked=\"checked\"";
	if ($ad_referer == 0) {
		$referer2 = "checked=\"checked\"";
		$referer = "";
	}
	$checkrightg1 = "checked=\"checked\"";
	if ($loadrightg == "0") {
		$checkrightg2 = "checked=\"checked\"";
		$checkrightg1 = "";
   	}
	$checkleftg1 = "checked=\"checked\"";
	if ($loadleftg == "0") {
		$checkleftg2 = "checked=\"checked\"";
		$checkleftg1 = "";
   	}

	$checkrightf2 = "checked=\"checked\"";
	if ($loadrightf == "1") {
		$checkrightf1 = "checked=\"checked\"";
		$checkrightf2 = "";
   	}
	$checkleftf2 = "checked=\"checked\"";
	if ($loadleftf == "1") {
		$checkleftf1 = "checked=\"checked\"";
		$checkleftf2 = "";
   	}
	//ЧПУ  	
   	$urlfriendly2 = "checked=\"checked\"";
	if ($urlfriendly == "1") {
		$urlfriendly1 = "checked=\"checked\"";
		$urlfriendly2 = "";
   	}
	//footer config
	$checkfoot_logo1 = "checked=\"checked\"";
	if ($mklib->config['foot_logo'] == 0) {
		$checkfoot_logo2 = "checked=\"checked\"";
		$checkfoot_logo1 = "";
   	}
	$checkfoot_version1 = "checked=\"checked\"";
	if ($mklib->config['foot_version'] == 0) {
		$checkfoot_version2 = "checked=\"checked\"";
		$checkfoot_version1 = "";
	}
	$checkfoot_debug1 = "checked=\"checked\"";
	if ($mklib->config['foot_debug'] == 0) {
		$checkfoot_debug2 = "checked=\"checked\"";
		$checkfoot_debug1 = "";
   	}

	//Portal CP template config
	$checkcp_tpl1 = "checked=\"checked\"";
	if ($mklib->config['cp_tpl'] == 0) {
		$checkcp_tpl2 = "checked=\"checked\"";
		$checkcp_tpl1 = "";
	}
	//Link icons
	$check_noicons2 = "checked=\"checked\"";
	if ($mklib->config['noicons'] == 0) {
		$check_noicons1 = "checked=\"checked\"";
		$check_noicons2 = "";
	}
	$cache2 = "checked=\"checked\"";
	if ($mklib->config['cache'] == 0) {
		$cache1 = "checked=\"checked\"";
		$cache2 = "";
	}
	$antibot2 = "checked=\"checked\"";
	if ($mklib->config['antibot_chek'] == 0) {
		$antibot1 = "checked=\"checked\"";
		$antibot2 = "";
	}
	$rewrite_url2 = "checked=\"checked\"";
	if ($mklib->config['rewrite_url'] == 0) {
		$rewrite_url1 = "checked=\"checked\"";
		$rewrite_url2 = "";
	}

	$subtitle = "{$mklib->lang['ad_preferences']}";
$modules1 = $mklib->config['modules'];
$selected = "selected=\"selected\"";
$cselect5.= "<option value=\"0\" $selected>{$mklib->lang['ad_no']}</option>\n";
if ($dirs = @opendir("../modules/")) {
 		 while (($dirm = readdir($dirs)) !== false) {
		  if($dirm == 'index.html'){
      continue;
          }
          if($dirm == 'ajaxout'){
      continue;
          }
          if($dirm == 'rajax'){
      continue;
          }
          if($dirm == 'rss'){
      continue;
          }
          if($dirm == 'contents'){
           continue;
          }
		 $selected = "";
		 if ($dirm != "." && $dirm != ".." && $dirm != "index.html" && $dirm != ".htaccess" && $dirm != "htaccess") {
		 	//$check = $mklib->sitepath."mkportal/templates/$dirt";
			if($modules1 == $dirm) {
				$selected = "selected=\"selected\"";
			}
			
   		 	$cselect5.= "<option value=\"$dirm\" $selected>$dirm</option>\n";
		 }
  		}
  		if(!$modules1) {
  			$selected = "selected=\"selected\"";
			$cselect5.= "<option value=\"0\" $selected>{$mklib->lang['ad_no']}</option>\n";
			}
  	closedir($dirs);
	}
	$rewrite_step = $mklib->config['rewrite_step'];
	$selected_rew1 = "selected=\"selected\"";
	if ($rewrite_step == "/") {
		$selected_rew1 = "";
		$selected_rew2 = "selected=\"selected\"";
	}

	$selected_rew3 .= "<option value=\"-\" $selected_rew1>-</option>\n";
	$selected_rew3 .= "<option value=\"/\" $selected_rew2>/</option>\n";
	
	


$sqlsize = $mklib->formatsize($sqlsize);
$files = $mklib->sitepath ."mkportal/cache/db/";
$cachesize = $mklib->sizedirectory("$files");
$formatsize_cache = $mklib->formatsize("$cachesize");
$delcache = @unlink( $files . $file );
$disksize = @disk_free_space( "." );
$formatsize_disk = $mklib->formatsize("$disksize");

$content .= "
<tr>
<td>
<table width=\"100%\" cellspacing=\"2\" cellpadding=\"5\" class=\"tabmain\">
	      <tr>
		<td width=\"50%\" colspan=\"2\" class=\"sottotitolo\">{$mklib->lang['ad_obstats_title']}</td>
		</tr>
		<tr>
		<td width=\"50%\" height=\"60\" class=\"modulex\">Версия портала</td>
		<td width=\"50%\" class=\"modulex\" align=\"center\">{$mklib->config['mk_version']}</td>
	      </tr>
	      <tr>
		<td width=\"50%\" height=\"60\" class=\"modulex\">{$mklib->lang['ad_obstats_sizecache']}</td>
		<td width=\"50%\" class=\"modulex\" align=\"center\">{$formatsize_cache}<a href=\"index.php?op=del_cache\"> {$mklib->lang['ad_obstats_cachedelet']}</a></td>
	      </tr>
	      <tr>
		<td width=\"50%\" height=\"60\" class=\"modulex\">{$mklib->lang['ad_obstats_sizedisk']}</td>
		<td width=\"50%\" class=\"modulex\" align=\"center\">{$formatsize_disk}</td>
	      </tr>
         <tr>
		<td width=\"50%\" height=\"60\" class=\"modulex\">OS</td>
		<td width=\"50%\" class=\"modulex\" align=\"center\">".PHP_OS."</td>
	      </tr>
        <tr>
		<td width=\"50%\" height=\"60\" class=\"modulex\">PHP</td>
		<td width=\"50%\" class=\"modulex\" align=\"center\">".phpversion()."</td>
	      </tr>
          <tr>
		<td width=\"50%\" height=\"60\" class=\"modulex\">Execution time</td>
		<td width=\"50%\" class=\"modulex\" align=\"center\">".ini_get('max_execution_time')." sek</td>
	      </tr>
         <tr>
		<td width=\"50%\" height=\"60\" class=\"modulex\">Upload file size</td>
		<td width=\"50%\" class=\"modulex\" align=\"center\">".ini_get('upload_max_filesize')."</td>
	      </tr>
         <tr>
		<td width=\"50%\" height=\"60\" class=\"modulex\">Post size</td>
		<td width=\"50%\" class=\"modulex\" align=\"center\">".ini_get('post_max_size')."</td>
	      </tr>
          <tr>
		<td width=\"50%\" height=\"60\" class=\"modulex\">Safe mode</td>
		<td width=\"50%\" class=\"modulex\" align=\"center\">".((ini_get('safe_mode') == 1) ? "<font color='#639D70'>On</font>" : "<font color='#AC4141'>Off</font>")."</td>
	      </tr>
          <tr>
		<td width=\"50%\" height=\"60\" class=\"modulex\">Mod Rewrite</td>
		<td width=\"50%\" class=\"modulex\" align=\"center\">".((function_exists('apache_get_modules')) ? ((array_search("mod_rewrite", apache_get_modules())) ? "<font color='#639D70'>On</font>" : "<font color='#AC4141'>Off</font>") : "<font color='#AC4141'>Off</font>")."</td>
	      </tr>
           <tr>
		<td width=\"50%\" height=\"60\" class=\"modulex\">Register globals</td>
		<td width=\"50%\" class=\"modulex\" align=\"center\">".((ini_get('register_globals')==1) ? "<font color=#AC4141>On</font>" : "<font color=#639D70>Off</font>")."</td>
	      </tr>
          <tr>
		<td width=\"50%\" height=\"60\" class=\"modulex\">Magic quotes gpc</td>
		<td width=\"50%\" class=\"modulex\" align=\"center\">".((ini_get('magic_quotes_gpc') == 1) ? "<font color='#639D70'>On</font>" : "<font color='#AC4141'>Off</font>")."</td>
	      </tr>
	      
	    
</table>
</td>
</tr>
	";   
	$content .= "
	<script language='JavaScript' type=\"text/javascript\">

        function ChangeOption(selectedOption) {
                document.getElementById('general').style.display = \"none\";
                document.getElementById('tools').style.display = \"none\";
                document.getElementById('ob_vids').style.display = \"none\";
                document.getElementById('vids_forum').style.display = \"none\";
                document.getElementById('protect').style.display = \"none\";
                document.getElementById('toolspanel').style.display = \"none\";
	            document.getElementById('cache').style.display = \"none\";
                if(selectedOption == 'general') {document.getElementById('general').style.display = \"\";}
                if(selectedOption == 'tools') {document.getElementById('tools').style.display = \"\";}
                if(selectedOption == 'ob_vids') {document.getElementById('ob_vids').style.display = \"\";}
                if(selectedOption == 'vids_forum') {document.getElementById('vids_forum').style.display = \"\";}
                if(selectedOption == 'protect') {document.getElementById('protect').style.display = \"\";}
                if(selectedOption == 'toolspanel') {document.getElementById('toolspanel').style.display = \"\";}
	            if(selectedOption == 'cache') {document.getElementById('cache').style.display = \"\";}
              


       }

</script>

	<tr>
	  <td>

	    <form name=\"main1\" method=\"post\" action=\"index.php?op=main_save\">
	    <table width=\"100%\">
	      <tr>
		<td>
		$checksave
		</td>
		</tr>";

	//Check for install and upgrade scripts
	$installerr = "";
	$cpwarn = 0;
	if (file_exists('../mk_install.php')) {
		$installerr = "<p>{$mklib->lang['ad_warninstall']}</p>";
		$cpwarn = 1;
	}
	if (file_exists('../upgrades')) {
		$installerr .= "<p>{$mklib->lang['ad_warnupgrade']}</p>";
		$cpwarn = 1;
	}
	if ($cpwarn) {
		

	$content .= "
<tr>
<td>
<table width=\"100%\" cellspacing=\"2\" cellpadding=\"5\" class=\"tabmain\">
	      <tr>
		<td width=\"100%\" height=\"60\" class=\"modulex bghighlight2 mktxtcontr\">$installerr</td>
	      </tr>	    
</table>
</td>
</tr>	      
	";	      
	}

$content .= "
<tr>
<td>
<table width=\"100%\" cellspacing=\"2\" cellpadding=\"5\" class=\"tabmain\">
	    <tr style=\"vertical-align:middle;\" >
 <td class=tableborder><a href=\"javascript:ChangeOption('general');\"><img title=\"{$mklib->lang['ad_preferencet1']}\" src=\"images/icons/set/tutorial.png\" border=\"0\"></a>
 <td class=tableborder><a href=\"javascript:ChangeOption('tools');\"><img title=\"{$mklib->lang['ad_preferencet2']}\" src=\"images/icons/set/tools.png\" border=\"0\"></a>
 <td class=tableborder><a href=\"javascript:ChangeOption('ob_vids');\"><img title=\"{$mklib->lang['ad_preferencet3']}\" src=\"images/icons/set/read.png\" border=\"0\"></a>
 <td class=tableborder><a href=\"javascript:ChangeOption('vids_forum');\"><img title=\"{$mklib->lang['ad_preferencet4']}\" src=\"images/icons/set/forum.png\" border=\"0\"></a>
 <td class=tableborder><a href=\"javascript:ChangeOption('protect');\"><img title=\"{$mklib->lang['ad_security']}\" src=\"images/icons/set/protect.png\" border=\"0\"></a>
 <td class=tableborder><a href=\"javascript:ChangeOption('toolspanel');\"><img title=\"{$mklib->lang['ad_preferencecp']}\" src=\"images/icons/set/toolspanel.png\" border=\"0\"></a>
 <td class=tableborder><a href=\"javascript:ChangeOption('cache');\"><img title=\"{$mklib->lang['ad_cache_tools']}\" src=\"images/icons/set/DBweb.png\" border=\"0\"></a>
 </tr>

    
</table>
</td>
</tr>	      
	";
	//Sitename & Paths
	$content .= "
<tr style='display:none' id=\"general\">
<td>
<table width=\"100%\" cellspacing=\"2\" cellpadding=\"5\" class=\"tabmain\">
	      <tr>
		<td width=\"100%\" colspan=\"2\" class=\"sottotitolo\">{$mklib->lang['ad_preferencet1']}</td>
		</tr>
		<tr>
		<td width=\"50%\" height=\"60\" class=\"modulex\">" . $mklib->helplink('had_boardname') . "{$mklib->lang['ad_boardname']}</td>
		<td width=\"50%\" class=\"modulex\" align=\"center\"><input class=\"moduleborder readonly\" type=\"text\" name=\"board\" value=\"$MK_BOARD\" size=\"40\" readonly=\"readonly\" /></td>
	      </tr>
	      <tr>
		<td width=\"50%\" height=\"60\" class=\"modulex\">" . $mklib->helplink('had_siteurl') . "{$mklib->lang['ad_siteurl']}</td>
		<td width=\"50%\" class=\"modulex\" align=\"center\"><input class=\"moduleborder readonly\" type=\"text\" name=\"siteurl\" value=\"$siteurl\" size=\"40\" readonly=\"readonly\" /></td>
	      </tr>

	      <tr>
		<td width=\"50%\" height=\"60\" class=\"modulex\">" . $mklib->helplink('had_adminpath') . "{$mklib->lang['ad_adminpath']}</td>
		<td width=\"50%\" class=\"modulex\" align=\"center\"><input class=\"moduleborder readonly\" type=\"text\" name=\"adminpath\" value=\"$adminpath\" size=\"40\" readonly=\"readonly\" /></td>
	      </tr>

	      <tr>
		<td width=\"50%\" height=\"60\" class=\"modulex\">" . $mklib->helplink('had_fpath') . "{$mklib->lang['ad_fpath']}</td>
		<td width=\"50%\" class=\"modulex\" align=\"center\"><input class=\"moduleborder readonly\" type=\"text\" name=\"forumpath\"  value=\"$forumpath\" size=\"40\" readonly=\"readonly\" /></td>
	      </tr>


	      <tr>
		<td width=\"50%\" height=\"60\" class=\"modulex\">" . $mklib->helplink('had_sitename') . "{$mklib->lang['sitename']}</td>
	      	<td width=\"50%\" class=\"modulex\" align=\"center\"><input class=\"moduleborder\" type=\"text\" name=\"sitename\" value=\"$sitename\" size=\"40\" /></td>
	      </tr>
	    
</table>
</td>
</tr>
	";

	//Offline highlighting
	$offlinebg = (!$MK_OFFLINE) ? "modulex" : "modulex bghighlight2"; //background highlighting

	//General Configuration
	$content .= "
<tr style='display:none' id=\"tools\">
<td>
<table width=\"100%\" cellspacing=\"2\" cellpadding=\"5\" class=\"tabmain\">
	      <tr>
		<td width=\"100%\" colspan=\"2\" class=\"sottotitolo\">{$mklib->lang['ad_preferencet2']}</td>
	      </tr>
	      <tr>
		<td width=\"50%\" height=\"60\" class=\"$offlinebg\">" . $mklib->helplink('had_putoff') . "<b>{$mklib->lang['putoff']}</b></td>
		<td width=\"50%\" class=\"$offlinebg\" align=\"center\">{$mklib->lang['ad_yes']}&nbsp;<input class=\"$offlinebg\" type=\"radio\" value=\"1\" name=\"offline\" $checkoff2 />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$mklib->lang['ad_no']}&nbsp;<input class=\"$offlinebg\" type=\"radio\" value=\"0\" name=\"offline\" $checkoff1 /></td>
	      </tr>
	      <tr>
		<td width=\"50%\" height=\"60\" class=\"modulex\">" . $mklib->helplink('had_lang') . "{$mklib->lang['ad_lang']}</td>
		<td width=\"50%\" class=\"modulex\" align=\"center\">
		  <select class=\"moduleborder\" size=\"1\" name=\"mklang\">
		  {$cselect2}
		  </select>
		</td>
	      </tr>
	     <tr>
		<td width=\"50%\" height=\"60\" class=\"modulex\">" . $mklib->helplink('had_editor') . "{$mklib->lang['ad_editor']}</td>
		<td width=\"50%\" class=\"modulex\" align=\"center\">
		<select class=\"moduleborder\" size=\"1\" name=\"mkeditor\">{$cselect3}</select>
		</td>
	     </tr>     
	     <tr>
		<td width=\"50%\" height=\"60\" class=\"modulex\">" . $mklib->helplink('had_disablezip') . "{$mklib->lang['ad_disablezip']}</td>
		<td width=\"50%\" class=\"modulex\" align=\"center\">{$mklib->lang['ad_yes']}&nbsp;<input type=\"radio\" value=\"1\" name=\"disablegzip\" $checkgzipd />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$mklib->lang['ad_no']}&nbsp;<input type=\"radio\" value=\"0\" name=\"disablegzip\" $checkgzipd2 /></td>
	      </tr>
	    <tr>
		<td width=\"50%\" height=\"60\" class=\"modulex\"><br />" . $mklib->helplink('had_sytime') . "{$mklib->lang['ad_sytime']}<br />{$mklib->lang['ad_curtime']}<br /> $curtime<br /><br /></td>
		<td width=\"50%\" class=\"modulex\" align=\"center\">{$mklib->lang['ad_diftime']}&nbsp;&nbsp;<select size=\"1\" name=\"timediff\" class=\"moduleborder\">{$cselect4}</select>
		</td>
	      </tr>
	      
	      
	      <tr>
		<td width=\"50%\" height=\"60\" class=\"modulex\">" . $mklib->helplink('had_metadesc') . "{$mklib->lang['ad_metadesc']}</td>
		<td width=\"50%\" class=\"modulex\" align=\"center\"><input class=\"moduleborder\" type=\"text\" name=\"metadesc\" value=\"$metadesc\" size=\"60\" /></td>
	      </tr>	      
	      
	      <tr>
		<td width=\"50%\" height=\"60\" class=\"modulex\">" . $mklib->helplink('had_metakey') . "{$mklib->lang['ad_metakey']}</td>
		<td width=\"50%\" class=\"modulex\" align=\"center\"><input class=\"moduleborder\" type=\"text\" name=\"metakey\" value=\"$metakey\" size=\"60\" /></td>
	      </tr>
	 <tr>
		<td width=\"50%\" height=\"60\" class=\"modulex\">" . $mklib->helplink('had_modules_title') . "{$mklib->lang['ad_modules_title']}</td>
		<td width=\"50%\" class=\"modulex\" align=\"center\">
		  <select class=\"moduleborder\" size=\"1\" name=\"modules_title\">
		  {$cselect5}
		  </select>
		</td>
	      </tr>
	<tr>
		<td width=\"50%\" height=\"60\" class=\"modulex\">" . $mklib->helplink('had_rewrite_url') . "{$mklib->lang['ad_rewrite_url']}</td>
		<td width=\"50%\" class=\"modulex\" align=\"center\">{$mklib->lang['ad_yes']}&nbsp;<input type=\"radio\" value=\"1\" name=\"rewrite_url\" $rewrite_url2 />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$mklib->lang['ad_no']}&nbsp;<input type=\"radio\" value=\"0\" name=\"rewrite_url\" $rewrite_url1 /></td>
	      </tr>
	<tr>
		<td width=\"50%\" height=\"60\" class=\"modulex\">" . $mklib->helplink('had_rewrite_url_step') . "{$mklib->lang['ad_rewrite_step']}</td>
          <td width=\"50%\" class=\"modulex\" align=\"center\">
		<select class=\"moduleborder\" size=\"1\" name=\"rewrite_step\">{$selected_rew3}</select>
		</td>
	      </tr>		      
	      
</table>
</td>
</tr>
	";

	//Mkportal Global Look & Feel
	$content .= "
<tr style='display:none' id=\"ob_vids\">
<td>
<table width=\"100%\" cellspacing=\"2\" cellpadding=\"5\" class=\"tabmain\">
	      <tr>
		<td width=\"100%\" colspan=\"2\" class=\"sottotitolo\">{$mklib->lang['ad_preferencet3']}</td>
	      </tr>
	      <tr>
		<td width=\"50%\" height=\"60\" class=\"modulex\">" . $mklib->helplink('had_skin') . "{$mklib->lang['ad_skin']}</td>
		<td width=\"50%\" class=\"modulex\" align=\"center\">
		  <select class=\"moduleborder\" size=\"1\" name=\"template\">
		  {$cselect}
		  </select>
		</td>
	      </tr>
	      <tr>
		<td width=\"50%\" height=\"60\" class=\"modulex\">" . $mklib->helplink('had_powidth') . "{$mklib->lang['ad_powidth']}</td>
		<td width=\"50%\" class=\"modulex\" align=\"center\"><input class=\"moduleborder\" type=\"text\" name=\"portalwidth\" value=\"$portalwidth\" size=\"5\" />&nbsp;
		  <select class=\"moduleborder\" size=\"1\" name=\"portalview\">
			  <option value=\"0\" $checkpv1>percent</option>
			  <option value=\"1\" $checkpv2>pixels</option>
		  </select>
		</td>
	      </tr>
	      <tr>
		<td width=\"50%\" height=\"60\" class=\"modulex\">" . $mklib->helplink('had_disablenav') . "{$mklib->lang['ad_disablenav']}</td>
		<td width=\"50%\" class=\"modulex\" align=\"center\">{$mklib->lang['ad_yes']}&nbsp;<input type=\"radio\" value=\"1\" name=\"shownav\" $checknav1 />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$mklib->lang['ad_no']}&nbsp;<input type=\"radio\" value=\"0\" name=\"shownav\" $checknav2 /></td>
	      </tr>

	      <tr>
		<td width=\"50%\" height=\"60\" class=\"modulex\">" . $mklib->helplink('had_noicons') . "{$mklib->lang['ad_noicons']}</td>
		<td width=\"50%\" class=\"modulex\" align=\"center\">{$mklib->lang['ad_yes']}&nbsp;<input type=\"radio\" value=\"0\" name=\"noicons\" $check_noicons1 />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$mklib->lang['ad_no']}&nbsp;<input type=\"radio\" value=\"1\" name=\"noicons\" $check_noicons2 /></td>
	      </tr>

	      <tr>
		<td width=\"50%\" height=\"60\" class=\"modulex\">" . $mklib->helplink('had_cowidth') . "{$mklib->lang['ad_cowidth']}</td>
		<td width=\"50%\" class=\"modulex\" align=\"center\"><input class=\"moduleborder\" type=\"text\" name=\"columnwidth\"  value=\"$columnwidth\" size=\"5\" /></td>
	      </tr>
	      <tr>
		<td width=\"50%\" height=\"60\" class=\"modulex\">" . $mklib->helplink('had_load_leftc') . "{$mklib->lang['ad_load_leftc']}</td>
		<td width=\"50%\" class=\"modulex\" align=\"center\">{$mklib->lang['ad_yes']}&nbsp;<input type=\"radio\" value=\"1\" name=\"loadleftg\" $checkleftg1 />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$mklib->lang['ad_no']}&nbsp;<input type=\"radio\" value=\"0\" name=\"loadleftg\" $checkleftg2 /></td>
	      </tr>
	      <tr>
		<td width=\"50%\" height=\"60\" class=\"modulex\">" . $mklib->helplink('had_load_rightc') . "{$mklib->lang['ad_load_rightc']}</td>
		<td width=\"50%\" class=\"modulex\" align=\"center\">{$mklib->lang['ad_yes']}&nbsp;<input type=\"radio\" value=\"1\" name=\"loadrightg\" $checkrightg1 />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$mklib->lang['ad_no']}&nbsp;<input type=\"radio\" value=\"0\" name=\"loadrightg\" $checkrightg2 /></td>
	      </tr>

<!-- footer config -->	      
	      <tr>
		<td width=\"50%\" height=\"60\" class=\"modulex\">" . $mklib->helplink('had_foot_logo') . "{$mklib->lang['ad_foot_logo']}</td>
		<td width=\"50%\" class=\"modulex\" align=\"center\">{$mklib->lang['ad_yes']}&nbsp;<input type=\"radio\" value=\"1\" name=\"foot_logo\" $checkfoot_logo1 />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$mklib->lang['ad_no']}&nbsp;<input type=\"radio\" value=\"0\" name=\"foot_logo\" $checkfoot_logo2 /></td>
	      </tr>	      

	      <tr>
		<td width=\"50%\" height=\"60\" class=\"modulex\">" . $mklib->helplink('had_foot_version') . "{$mklib->lang['ad_foot_version']}</td>
		<td width=\"50%\" class=\"modulex\" align=\"center\">{$mklib->lang['ad_yes']}&nbsp;<input type=\"radio\" value=\"1\" name=\"foot_version\" $checkfoot_version1 />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$mklib->lang['ad_no']}&nbsp;<input type=\"radio\" value=\"0\" name=\"foot_version\" $checkfoot_version2 /></td>
		</tr>

	      <tr>
		<td width=\"50%\" height=\"60\" class=\"modulex\">" . $mklib->helplink('had_foot_debug') . "{$mklib->lang['ad_foot_debug']}</td>
		<td width=\"50%\" class=\"modulex\" align=\"center\">{$mklib->lang['ad_yes']}&nbsp;<input type=\"radio\" value=\"1\" name=\"foot_debug\" $checkfoot_debug1 />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$mklib->lang['ad_no']}&nbsp;<input type=\"radio\" value=\"0\" name=\"foot_debug\" $checkfoot_debug2 /></td>
	      </tr>
<!-- end footer config -->

</table>
</td>
</tr>
	";

	//Forum page Look & Feel
	$content .= "
<tr style='display:none' id=\"vids_forum\">
<td>
<table width=\"100%\" cellspacing=\"2\" cellpadding=\"5\" class=\"tabmain\">
	      <tr>
		<td width=\"100%\" colspan=\"2\" class=\"sottotitolo\">{$mklib->lang['ad_preferencet4']}</td>
	      </tr>
	      <tr>
		<td width=\"50%\" height=\"60\" class=\"modulex\">" . $mklib->helplink('had_forumin') . "{$mklib->lang['ad_forumview']}<br />{$mklib->lang['ad_forumin']}</td>
		<td width=\"50%\" class=\"modulex\" align=\"center\">{$mklib->lang['ad_yes']}&nbsp;<input type=\"radio\" value=\"1\" name=\"forumview\" $checkfv1 />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$mklib->lang['ad_no']}&nbsp;<input type=\"radio\" value=\"0\" name=\"forumview\" $checkfv2 /></td>
	      </tr>
	      <tr>
		<td width=\"50%\" height=\"60\" class=\"modulex\">" . $mklib->helplink('had_rightcolumn') . "{$mklib->lang['ad_rightcolumn']}</td>
		<td width=\"50%\" class=\"modulex\" align=\"center\">{$mklib->lang['ad_yes']}&nbsp;<input type=\"radio\" value=\"0\" name=\"forumcd\" $checkfcd1 />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$mklib->lang['ad_no']}&nbsp;<input type=\"radio\" value=\"1\" name=\"forumcd\" $checkfcd2 /></td>
	      </tr>
	      <tr>
		<td width=\"50%\" height=\"60\" class=\"modulex\">" . $mklib->helplink('had_leftcolumn') . "{$mklib->lang['ad_leftcolumn']}</td>
		<td width=\"50%\" class=\"modulex\" align=\"center\">{$mklib->lang['ad_yes']}&nbsp;<input type=\"radio\" value=\"0\" name=\"forumcs\" $checkfcs1 />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$mklib->lang['ad_no']}&nbsp;<input type=\"radio\" value=\"1\" name=\"forumcs\" $checkfcs2 /></td>
	      </tr>
	      <tr>
		<td width=\"50%\" height=\"60\" class=\"modulex\">" . $mklib->helplink('had_uleftcolumn') . "{$mklib->lang['ad_uleftcolumn']}</td>
		<td width=\"50%\" class=\"modulex\" align=\"center\">{$mklib->lang['ad_yes']}&nbsp;<input type=\"radio\" value=\"1\" name=\"loadleftf\" $checkleftf1 />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$mklib->lang['ad_no']}&nbsp;<input type=\"radio\" value=\"0\" name=\"loadleftf\" $checkleftf2 /></td>
	      </tr>
	      <tr>
		<td width=\"50%\" height=\"60\" class=\"modulex\">" . $mklib->helplink('had_urightcolumn') . "{$mklib->lang['ad_urightcolumn']}</td>
		<td width=\"50%\" class=\"modulex\" align=\"center\">{$mklib->lang['ad_yes']}&nbsp;<input type=\"radio\" value=\"1\" name=\"loadrightf\" $checkrightf1 />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$mklib->lang['ad_no']}&nbsp;<input type=\"radio\" value=\"0\" name=\"loadrightf\" $checkrightf2 /></td>
	      </tr>
</table>
</td>
</tr>
	";

	//Security
	$content .= "
<tr style='display:none' id=\"protect\">
<td>
<table width=\"100%\" cellspacing=\"2\" cellpadding=\"5\" class=\"tabmain\">
	      <tr>
		<td width=\"100%\" colspan=\"2\" class=\"sottotitolo\">{$mklib->lang['ad_security']}</td>
	      </tr>
       <tr>
		<td width=\"50%\" height=\"60\" class=\"modulex\">" . $mklib->helplink('had_antibot') . "{$mklib->lang['antibot_activ']}</td>
		<td width=\"50%\" class=\"modulex\" align=\"center\">{$mklib->lang['ad_yes']}&nbsp;<input type=\"radio\" value=\"1\" name=\"antibot\" $antibot2 />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$mklib->lang['ad_no']}&nbsp;<input type=\"radio\" value=\"0\" name=\"antibot\" $antibot1 /></td>
	      </tr>
	      <tr>
		<td width=\"50%\" height=\"60\" class=\"modulex\">" . $mklib->helplink('had_referer') . "{$mklib->lang['ad_referer']}</td>
		<td width=\"50%\" class=\"modulex\" align=\"center\">{$mklib->lang['ad_yes']}&nbsp;<input type=\"radio\" value=\"1\" name=\"referer\" $referer />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$mklib->lang['ad_no']}&nbsp;<input type=\"radio\" value=\"0\" name=\"referer\" $referer2 /></td>
	      </tr>

	      <tr>
		<td width=\"50%\" height=\"60\" class=\"modulex\">" . $mklib->helplink('had_postwhitelist') . "{$mklib->lang['ad_postwhitelist']}</td>
		<td width=\"50%\" class=\"modulex\" align=\"center\"><input class=\"moduleborder\" type=\"text\" name=\"postwhitelist\" value=\"$postwhitelist\" size=\"60\" /></td>
	      </tr>
	      
</table>
</td>
</tr>
	";

	//Mkportal Global Look & Feel
	$content .= "
<tr style='display:none' id=\"toolspanel\">
<td>
<table width=\"100%\" cellspacing=\"2\" cellpadding=\"5\" class=\"tabmain\">
	      <tr>
		<td width=\"100%\" colspan=\"2\" class=\"sottotitolo\">{$mklib->lang['ad_preferencecp']}</td>
	      </tr>
	      <tr>
		<td width=\"50%\" height=\"60\" class=\"modulex\">" . $mklib->helplink('had_cp_tpl') . "{$mklib->lang['ad_cp_tpl']}</td>
		<td width=\"50%\" class=\"modulex\" align=\"center\">{$mklib->lang['ad_yes']}&nbsp;<input type=\"radio\" value=\"1\" name=\"cp_tpl\" $checkcp_tpl1 />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$mklib->lang['ad_no']}&nbsp;<input type=\"radio\" value=\"0\" name=\"cp_tpl\" $checkcp_tpl2 /></td>
	      </tr>
</table>
</td>
</tr>
";
	$content .= "
<tr style='display:none' id=\"cache\">
<td>
<table width=\"100%\" cellspacing=\"2\" cellpadding=\"5\" class=\"tabmain\">
	      <tr>
		<td width=\"100%\" colspan=\"2\" class=\"sottotitolo\">{$mklib->lang['ad_cache_tools']}</td>
	      </tr>
	      <tr>
		<td width=\"50%\" height=\"60\" class=\"modulex\">" . $mklib->helplink('had_cache_vcl') . "{$mklib->lang['ad_cache_yes']}</td>
		<td width=\"50%\" class=\"modulex\" align=\"center\">{$mklib->lang['ad_yes']}&nbsp;<input type=\"radio\" value=\"1\" name=\"cache1\" $cache2 />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$mklib->lang['ad_no']}&nbsp;<input type=\"radio\" value=\"0\" name=\"cache1\" $cache1 /></td>
	      </tr>
	 <tr>
		<td width=\"50%\" height=\"60\" class=\"modulex\">" . $mklib->helplink('had_cache_times') . "{$mklib->lang['ad_cache_time']}</td>
		<td width=\"50%\" class=\"modulex\" align=\"center\"><input class=\"moduleborder\" type=\"text\" name=\"cache_time\" value=\"$cache_time\" size=\"20\" /></td>
	      </tr>
	
</table>
</td>
</tr>
";
	$content .= "
		  <tr>
		<td colspan=\"2\" class=\"titadmin\" align = \"center\"><br />
		  <input type=\"submit\" class=\"mkbutton\" value=\" {$mklib->lang['ad_save']} \" name=\"B1\" />
		</td>
	      </tr>
	    </table>
	    </form>
	  </td>
	</tr>
	";
	$output = $Skin->view_block("$subtitle", "$content");
	$mklib->printpage_admin($mklib->lang['ad_titlepage'].$mklib->lang['tt_sep'].$mklib->lang['ad_preferences'], $output);

	}
	function main_save() {
	global $mkportals, $mklib, $Skin, $DB, $MK_BOARD;

	//HTTP_REFERER check
	if (!$mklib->referer) {
		if (!strstr($_SERVER['HTTP_REFERER'], "$mklib->mkurl/$mklib->adminpath/index.php")) {
			$message = "{$mklib->lang['error_noallow']}";
			$mklib->error_page($message);
			exit;
		}
	}

	$forumpath = $mkportals->input['forumpath'];
	$forumview = $mkportals->input['forumview'];
	$portalview = $mkportals->input['portalview'];
	$forumcd = intval($mkportals->input['forumcd']);
	$forumcs = intval($mkportals->input['forumcs']);
	$sitename = $mkportals->input['sitename'];
	$siteurl = $mkportals->input['siteurl'];
	$adminpath = $mkportals->input['adminpath'];
	$template = $mkportals->input['template'];
	$mklang = $mkportals->input['mklang'];
	$offline = $mkportals->input['offline'];
	$timediff = intval($mkportals->input['timediff']);
	$columnwidth = intval($mkportals->input['columnwidth']);
	$portalwidth = intval($mkportals->input['portalwidth']);
	$mkeditor = $mkportals->input['mkeditor'];
	$disablegzip = intval($mkportals->input['disablegzip']);
	$shownav = intval($mkportals->input['shownav']);
	$noicons = intval($mkportals->input['noicons']);
	$loadleftg = intval($mkportals->input['loadleftg']);
	$loadrightg = intval($mkportals->input['loadrightg']);
	$loadleftf = intval($mkportals->input['loadleftf']);
	$loadrightf = intval($mkportals->input['loadrightf']);
	$foot_logo = intval($mkportals->input['foot_logo']);
	$foot_version = intval($mkportals->input['foot_version']);
	$foot_debug = intval($mkportals->input['foot_debug']);
	$referer = intval($mkportals->input['referer']);
	$cp_tpl = intval($mkportals->input['cp_tpl']);
	//$postwhitelist = serialize($mkportals->input['postwhitelist']);
	$postwhitelist = $mkportals->input['postwhitelist'];
	$metadesc = $mkportals->input['metadesc'];
	$metakey = $mkportals->input['metakey'];
	
	$cache = intval($mkportals->input['cache1']);
	$cache_time = $mkportals->input['cache_time'];
    $antibot = $mkportals->input['antibot'];
    $rewrite_url = $mkportals->input['rewrite_url'];
    $rewrite_step = $mkportals->input['rewrite_step'];
    $modules2 = $mkportals->input['modules_title'];
	//Check for valid portal width	
	if ($portalview) { //Minimum & maximum pixels		
		if ($portalwidth < 780 || $portalwidth > 1660) {
         		$message = "Valid width is between between 780px and 1660px. Please go back and enter a valid width.";
			$mklib->error_page($message);
			exit;
		}
	} else { //Min & max percent
		if ($portalwidth < 75 || $portalwidth > 100) {
         		$message = "Valid width is between 75% and 100%. Please go back and enter a valid width.";
			$mklib->error_page($message);
			exit;
		}
	}

	$content = "<?php\n\n \$FORUM_PATH = \"$forumpath\"; \n \$FORUM_VIEW = \"$forumview\"; \n \$PORTAL_VIEW = \"$portalview\"; \n \$FORUM_CD = \"$forumcd\"; \n \$FORUM_CS = \"$forumcs\"; \n \$SITE_NAME = \"$sitename\";  \n \$SITE_URL = \"$siteurl\";  \n \$ADMIN_PATH = \"$adminpath\"; \n \$MK_TEMPLATE = \"$template\";\n \$MK_LANG = \"$mklang\";\n \$MK_EDITOR = \"$mkeditor\";\n \$MK_BOARD = \"$MK_BOARD\";\n \$MK_TIMEDIFF = \"$timediff\";\n \$MK_OFFLINE = \"$offline\";\n \$MK_DISABLEGZIP = \"$disablegzip\";\n \$MK_PORTALWIDTH = \"$portalwidth\";\n \$MK_COLUMNWIDTH = \"$columnwidth\";\n \$MK_DISABLENAV = \"$shownav\";\n \$MK_LOADLEFTC = \"$loadleftg\";\n \$MK_LOADRIGHTC = \"$loadrightg\";\n \$MK_UNLOADLEFTF = \"$loadleftf\";\n \$MK_UNLOADRIGHTF = \"$loadrightf\";\n \$MK_REFERER = \"$referer\";\n ?>";
		$filename = "../conf_mk.php";
   		if (!$handle = fopen($filename, 'w')) {
         		$message = "Cannot open the file conf_mk.php. Check the CHMOD (0666) of conf_mk.php and confirm that your server supports the PHP \"fopen\" function.";
			$mklib->error_page($message);
			exit;
   		}
   		if (!fwrite($handle, $content)) {
       			$message = "Cannot write to the file conf_mk.php. Check the CHMOD (0666) of conf_mk.php and confirm that your server supports the PHP \"fwrite\" function.";
			$mklib->error_page($message);
			exit;
   		}
		fclose($handle);
		
		$DB->query("UPDATE mkp_config SET valore ='$foot_logo' WHERE chiave = 'foot_logo'");
		$DB->query("UPDATE mkp_config SET valore ='$foot_version' WHERE chiave = 'foot_version'");
		$DB->query("UPDATE mkp_config SET valore ='$foot_debug' WHERE chiave = 'foot_debug'");
		//$DB->query("UPDATE mkp_config SET valore ='$referer' WHERE chiave = 'referer'"); //moved to conf_mk.php
		$DB->query("UPDATE mkp_config SET valore ='$cp_tpl' WHERE chiave = 'cp_tpl'");
		$DB->query("UPDATE mkp_config SET valore ='$noicons' WHERE chiave = 'noicons'");
		$DB->query("UPDATE mkp_config SET valore ='$postwhitelist' WHERE chiave = 'postwhitelist'");
		$DB->query("UPDATE mkp_config SET valore ='$metadesc' WHERE chiave = 'metadesc'");
		$DB->query("UPDATE mkp_config SET valore ='$metakey' WHERE chiave = 'metakey'");
		$DB->query("UPDATE mkp_config SET valore ='$cache' WHERE chiave = 'cache'");
		$DB->query("UPDATE mkp_config SET valore ='$cache_time' WHERE chiave = 'cache_time'");
		$DB->query("UPDATE mkp_config SET valore ='$antibot' WHERE chiave = 'antibot_chek'");
		$DB->query("UPDATE mkp_config SET valore ='$rewrite_url' WHERE chiave = 'rewrite_url'");
		$DB->query("UPDATE mkp_config SET valore ='$modules2' WHERE chiave = 'modules'");
		$DB->query("UPDATE mkp_config SET valore ='$rewrite_step' WHERE chiave = 'rewrite_step'");
		$DB->close_db();
		Header("Location: index.php?mode=saved");
		exit;

	}
function del_cache() {
	global $mkportals, $mklib;
$files = $mklib->sitepath ."mkportal/cache/db/";
$dirname = $mklib->sitepath ."mkportal/cache/db/";
 $dir = opendir($files);
    while (($file = readdir($dir)) !== false)
    {
      if($file != "." && $file != ".." && $file != '.htaccess')
      {
        if(is_file($dirname."/".$file))
        {
          unlink($dirname."/".$file);
        }
      }
    } 
    Header("Location: index.php");
		exit;
}
}

?>
