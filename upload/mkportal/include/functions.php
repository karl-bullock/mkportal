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


class mklib {


	var $secret = "I'm luponero";
	var $config = array();
	var $member = array();
	var $forumpath = "";
	var $forumview = "";
	var $portalview = "";
	var $forumcd = "";
	var $forumcs = "";
	var $sitepath = "";
	var $sitename = "";
	var $siteurl = "";
	var $mkurl = "";
	var $template = "";
	var $mklang = "";
	var $lang = "";
	var $images = "";
	var $menucloseds = "";
	var $menucontents = "";
	var $menuclosedr = "";
	var $menucontentr = "";
	var $columnwidth = "";
	var $portalwidth = "";
	var $mkeditor = "";
	var $disablegzip = "";
	var $disablenav = "";
    	var $blocks_are_init = FALSE;
    	var $blocks = array();
	var $stats = array();
	var $charset = "";
	var $loadcolumnright = 1;
	var $loadcolumnleft = 1;
	var $unloadforumright = "";
	var $unloadforumleft = "";



    function logout () {
        global $mkportals;

        header("Location: {$mkportals->base_url}act=Login&CODE=03&return=$this->siteurl");
        exit;

    }
	function header ($title, $left, $right, $board_header="", $descr="", $keywords="") {
        	global $mkportals, $Skin, $DB, $mklib_board, $editorscript, $MK_BOARD, $MK_OFFLINE, $MK_TEMPLATE;
         if(!$descr) {
			$descr = $this->config['metadesc'];
		}
		if(!$keywords) {
			$keywords = $this->config['metakey'];
		}
		//Load global stylesheet
		$css = "<link href=\"/mkportal/templates/".$MK_TEMPLATE."/style.css\" rel=\"stylesheet\" type=\"text/css\" />";
		//Load Portal CP stylesheet
		if (defined('IN_MKPADMIN')) {
			$css .= file_exists($this->template.'/stylecp.css') ? "<link href=\"$this->template/stylecp.css\" rel=\"stylesheet\" type=\"text/css\" />" : "";
		}

		//RSS block css
		if ($this->config['rss_css'] == 1) { //rss block css
			$css .= "\n<link rel=\"stylesheet\" href=\"{$this->sitepath}mkportal/modules/rss/files/simplepie.css\" type=\"text/css\" media=\"screen, projector\" />\n";
		}

//Meo: Changed in C 0.1.b for Ajax engine
		$js = "<script type=\"text/javascript\" src=\"/mkportal/templates/".$MK_TEMPLATE."/mkp.js\"></script>\n  <script type=\"text/javascript\" src=\"/mkportal/templates/".$MK_TEMPLATE."/mkp.ext.ajax.js\"></script>";
// End	
		if($board_header) {
			$title = "";
		} else  {
			$title = "<title>$title</title>\n  <meta name=\"description\" content=\"{$descr}\" />\n  <meta name=\"keywords\" content=\"{$keywords}\" />\n  <meta name=\"copyright\" content=\"www.RusMKPortal.ru version R 1.0.2\" />";
		}
		if (!array_key_exists('g_access_urlobox', $this->member)) {
			$this->member['g_access_urlobox'] = 0;
		}
		if (!$this->config['mod_urlobox'] && !defined('IN_MKPADMIN')) { // Disable shoutbox in Portal CP
  			$urlo = $this->retrieve_urlo();
			if(!$mkportals->member['g_access_cp'] && !$this->member['g_access_urlobox']) {
				$urlo[0] = $this->lang['urlo_invis'];
				$urlo[1] = $this->lang['urlo_unauth'];
			}
		}
		if ($left == 0) {
			$this->menucloseds = "";
			$this->menucontents = "display:none";
		}
		if ($right == 0) {
			$this->menuclosedr = "";
			$this->menucontentr = "display:none";
		}
		//portal width in pixels or percentage		
		$mainwidth = $this->portalwidth.($this->portalview == 1 ? 'px' : '%');
		//Portal CP "default" template 100%
		$mainwidth = ($this->config['cp_tpl'] && defined('IN_MKPADMIN')) ? '100%' : $mainwidth;
		/* deprecated
		if ($this->portalview == 0) {
			$mainwidth = "100%";
		}
		*/
		// editor + PM popup
		$pmk_js = "";
		if ($editorscript) {
			$pmk_js = $this->get_editor();
		}
		if ($mkportals->member['show_popup'] && $mkportals->member['id']) {
			$pmk_js .= $mklib_board->popup_pm($this->lang['popm1'], $this->lang['popm2'], $this->lang['popm3'], $this->lang['popm4']);

		}
		
//Meo: Changed in C 0.1 for AEF shoutbox
		$delshout = "false";
		if ($mkportals->member['g_mod_urlobox'] || $mkportals->member['g_access_cp'])
			$delshout = "true";
		$pos = strpos($board_header, "universal.js");
		if($MK_BOARD == "AEF" && !$pos){
			$pmk_js .="
				<script language=\"javascript\" src=\"$this->siteurl/$this->forumpath/themes/default/js/universal.js\" type=\"text/javascript\"></script>
				<script language=\"javascript\" src=\"$this->siteurl/$this->forumpath/themes/default/js/domdrag.js\" type=\"text/javascript\"></script>
				<script language=\"javascript\" src=\"$this->siteurl/$this->forumpath/themes/default/js/shoutbox.js\" type=\"text/javascript\"></script>
				<script type=\"text/javascript\">
					boardurl = '$this->siteurl/$this->forumpath/';
					indexurl = '$this->siteurl/$this->forumpath/index.php?';
					imgurl = '$this->siteurl/$this->forumpath/themes/default/images/';
				</script>
				<script type=\"text/javascript\">
					can_del_shout = $delshout;
				</script>
				<style type=\"text/css\">
					.shout{
					padding:2px;
					margin:3px;
					background:#FFFFFF;
					border:1px solid #CCCCCC;
					}
				</style>
				";
			$output = $Skin->view_header($title, $css, $js, $pmk_js, $board_header);
			//$output = str_replace ("<body onload=\"javascript:GetPos()\">", "<body onload=\"javascript:GetPos(); bodyonload();\">", $output);
		} else {
			$output = $Skin->view_header($title, $css, $js, $pmk_js, $board_header);
		}
//End

		//Admin Offline Alert
		if($MK_OFFLINE) {
			$output .= $Skin->view_offline();
		}

		//Open main wrapper
		$output .= $Skin->open_main($mainwidth);
		//Logostrip
		$output .= $Skin->view_logo();
		//Linkbar
		$row_link = "";
		$query = $DB->query( "SELECT icon, title, url, position, target FROM mkp_mainlinks WHERE type = '1' AND active = '1' ORDER BY `position`");
		while( $row = $DB->fetch_row($query) ) {
			$showlink = $this->checklinkperm($row['url']);
//Meo: Changed in C 0.1 for AEF shoutbox
		if($MK_BOARD == "AEF"){
			if (stristr($row['url'], 'ind=urlobox')) {
				$row['url'] = defined('IN_MKPADMIN') ?  "javascript:window.alert('".$this->lang['urlo_disabled']."');" : "javascript:show_shoutbox();";
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
			if($this->config['rewrite_url'] & $this->config['rewrite_step'] == "/"){
			$row['url'] = preg_replace('/index\.php\?ind=([a-z_-]+)/', "\\1/" ,$row['url']);
			
		}
		if ($this->config['rewrite_url'] & $this->config['rewrite_step'] == "-"){
			$row['url'] = preg_replace('/index\.php\?ind=([a-z_-]+)/', "\\1.html" ,$row['url']);
			}
			//Link icon
			$row['icon'] = str_replace("<IMG>","$this->siteurl/$this->images", $row['icon']);
			$icon = (!$this->config['noicons'] && $row['icon']) ? "&nbsp;<img src=\"{$row['icon']}\" style=\"vertical-align: middle\" alt=\"{$row['title']}\" />" : '';
			$row_link .= $Skin->row_link($icon, "href=\"{$row['url']}\"{$target}", $row['title']);
		}

		if ($this->disablenav != 1) {
			$output .= $Skin->view_linkbar($row_link);
		}
		//Shoutbox		
		if (!$this->config['mod_urlobox'] && !defined('IN_MKPADMIN')) { // Disable shoutbox in Portal CP
			if(!$mkportals->member['g_access_cp'] && !$this->member['g_access_urlobox']){ // Disable shoutbox in guest
				$output .= "";
			} else {
			$output .= $Skin->view_urlo($urlo[0], $urlo[1]);
			}
// End			
		}
		//Horizontal separator - Separate header from body
		//$output .= $Skin->view_separator_h(); //deprecated
		//Open content area
		$output .= $Skin->open_body();
		return $output;

	}
//-- mod_less_queries begin (This is By Peter)
    function init_blocks () {
        global $DB;
        
        if ($this->blocks_are_init) return;
        $DB->query("SELECT * FROM mkp_blocks WHERE active='checked' ORDER BY progressive");
        while( $row = $DB->fetch_row() ) {
        	
            $this->blocks[] = $row;
        }
        $this->blocks_are_init = TRUE;
    }
    
    function get_column($position = "sinistra") {
		global $mkportals, $DB, $std, $Skin, $mklib_board, $modname;
        $this->init_blocks();
        foreach($this->blocks as $row) {
        
            if ($row['position'] == $position ) {
                $content = "";
		$perms = array();
		$indarr = "blt".$row['id'];
		$titlem = isset($this->lang[$indarr])?$this->lang[$indarr]:$row['title'];
		   
            	
                $active = $row['active'];
    			if ($row['perms']) {
    				$perms =  unserialize($row['perms']);
    			}
    			
			if(!$perms) {
				$perms = array();
			}

			if (is_array($mkportals->member['mgroup'])) { //C1.2 rc2 - phpBB3 only
				
				//Get all viewer's usergroups
				foreach ($mkportals->member['mgroup'] AS $key => $value) {

					//Check each of viewer's member groups to see if group has block view perms
					if (!in_array($value, $perms)) {
						$viewblock = true;
					}					
					//If any group the viewer belongs to has view perms allow viewing 
					if ($viewblock === true) {
						break;
					}
				}

			} else { //C1.2 rc2 - All other boards
				$viewblock = (!in_array($mkportals->member['mgroup'], $perms)) ? true : false ;
			}
		  
          $module_arr = explode(",", $row['modules']);
            $module_mas = in_array($modname,$module_arr);
            if (!$module_mas && !$row['modules'] == NULL){
            	continue;
            }
            
			if ($active == "checked" && $viewblock === true) {
    			//if ($active == "checked" && !in_array($mkportals->member['mgroup'], $perms)) {
                    switch($row['personal']) {
                        case '1':   if ($row['content'] != "") { // HTML Block
                    					$content = "<tr><td class=\"blocks\">".stripslashes($row['content'])."</td></tr>";
                    					if ($this->mkeditor == "BBCODE") {
                    						$content = $this->decode_bb($content);
                    					}
                                    }
                                    break;
                        case '2':   if ($row['content'] != "") { // Internal Page Links Block				
							$content = $row['content'];					
							$content = !$this->config['noicons'] ? str_replace("frec.gif", "{$this->images}/frec.gif", $content) : str_replace("<img class=\"mkicon\" src=\"frec.gif\" align=\"left\" alt=\"\" />", "", $content);
					                //$content = str_replace ("frec.gif", "$this->images/frec.gif", $content);
                                    }
                                    break;
                        case '3':   $file = $this->sitepath."mkportal/".$row['file']; // PHP Block
                					if (is_file($file)) {
                						@require $file;
                                        if ($content != "") {
                    						$content ="<tr><td class=\"blocks\">".$content."</td></tr>";
                                        }
                					}
                                    break;
                        default:    $file = $this->sitepath."mkportal/blocks/".$row['file']; // Uploaded Block
                                    $content = "";
                                    if (is_file($file)) {
                                        require $file;
                                    }
                                    break;
                    }
                    if ($content != "") {
                        $column .= $Skin->view_block($titlem, $content);
                    }
                }
            }
	    unset($viewblock);
        }
        return $column;
    }

    function block_left () {
		global $mkportals, $DB, $std, $Skin, $mklib_board;
        
        if (defined("OFF_LINE")) {
            return "";
        }
        if ($blocks = $this->get_column("sinistra")) {
            $output = $Skin->view_column_left($blocks);
            $output .= $Skin->view_separator_v();
        }
        return $output;
    }

    function block_right () {
		global $mkportals, $DB, $std, $Skin, $mklib_board;
        
        if (defined("OFF_LINE")) {
            return "";
        }
        if ($blocks = $this->get_column("destra")) {
            $output .= $Skin->view_separator_v();
            $output .= $Skin->view_column_right($blocks);
        }
        return $output;
    }
  
    function main_page () {
	global $mkportals, $DB, $std, $Skin, $mklib_board;
        
        $blocks = $this->get_column("centro");
        $title = $this->sitename;
        $this->printpage("1", "1", $title, $blocks);
    }
//-- mod_less_queries end
     function block_center ($content) {
        global $Skin, $mklib_board;
            $blocks_top = $this->get_column("centrotop");
            $blocks_down = $this->get_column("centrodown");
        $output = $Skin->view_column_center($content, $blocks_top, $blocks_down);
        return $output;
    }


	function footer () {
		global $Skin, $DB;
		//Close content area
		$output = $Skin->close_body();
		//Close main wrapper
		$output .= $Skin->close_main();
		$ttime = $this->etimer();
		$chekcopy = base64_decode('PHNwYW4gY2xhc3M9Im1rY29weXJpZ2h0IiBzdHlsZT0iZm9udC1zaXplOiAxMHB4Ij48YSBzdHlsZT0iZm9udC1zaXplOiAxMHB4IiBocmVmPSJodHRwOi8vd3d3Lm1rcG9ydGFsLml0LyIgdGFyZ2V0PSJfYmxhbmsiPk1LUG9ydGFsPC9hPg==');
        $chekcopy2 = base64_decode('JmNvcHk7MjAwMy0yMDA4IDxhIHN0eWxlPSJmb250LXNpemU6IDEwcHgiIGhyZWY9Imh0dHA6Ly93d3cubWtwb3J0YWwuaXQvIiB0YXJnZXQ9Il9ibGFuayI+bWtwb3J0YWwuaXQ8L2E+PC9zcGFuPjxicj48YSBzdHlsZT0iZm9udC1zaXplOiAxMHB4IiBocmVmPSJodHRwOi8vd3d3Lm15LWxvZ2FuLnJ1LyIgdGFyZ2V0PSJfYmxhbmsiPkxvZ2FuPC9hPiAmY29weTsyMDA3LTIwMTEgPGEgc3R5bGU9ImZvbnQtc2l6ZTogMTBweCIgaHJlZj0iaHR0cDovL3d3dy5ydXNta3BvcnRhbC5ydS8iIHRhcmdldD0iX2JsYW5rIj5SdXNNS1BvcnRhbDwvYT4=');
		//footer WARNING YOU CANNOT REMOVE OR MODIFY THIS COPYRIGHT CODE. 
		// ATTENZIONE E' VIETATO TOGLIERE O CAMBIARE LA STRINGA DEL COPYRIGHT !!
		$foot_logo = ($this->config['foot_logo'] == 1) ? "<img src=\"{$this->template}/images/loghino.gif\" alt=\"\" /><br />" : '';
		$foot_version = ($this->config['foot_version'] == 1) ? " {$this->config['mk_version']}" : '';
		$copy = $foot_logo.$chekcopy.$foot_version.$chekcopy2;
		if ($this->config['foot_debug'] == 1) {
		$memory =round( (memory_get_usage()/1024/1024), 2);
		$copy .= "<br /><span class=\"mkcopyright\">".$this->lang['debugout1']." <b>".$ttime."</b> ".$this->lang['debugout2']." ".$this->lang['debugout4']." <b>".$memory."</b>MB ".$this->lang['debugout3']." <b>".$DB->query_count."</b></span>";
		}
		$output .= $Skin->view_footer($copy);return $output;}
	function footer_admin () {
		global $Skin;
		//Close content area
		$output .= $Skin->close_body();
		//Close main wrapper
		$output .= $Skin->close_main();
		//footer WARNING YOU CANNOT REMOVE OR MODIFY THIS COPYRIGHT CODE.
		//footer pagina ATTENZIONE E' VIETATO TOGLIERE O CAMBIARE LA STRINGA DEL COPYRIGHT !!
		$copy = "<img src=\"$this->template/images/loghino.gif\" alt=\"\" /><br /><span class=\"mkcopyright\" style=\"font-size: 10px\"><a style=\"font-size: 10px\" href=\"http://www.mkportal.it/\" target=\"_blank\">MKPortal</a> {$this->config['mk_version']} &copy;2003-2008 <a style=\"font-size: 10px\" href=\"http://www.mkportal.it/\" target=\"_blank\">mkportal.it</a><br /><a href=\"http://www.rusmkportal.ru\" target=\"_blank\">Russian support</a> &copy;2007-2009 <a href=\"http://www.rusmkportal.ru\" target=\"_blank\">www.rusmkportal.ru</a></span>";
		$output .= $Skin->view_footer($copy);
		return $output;
	}
	function urlrewriting($content) {
    //URL Rewriting 1.4 by jack@mkportal-fr.com
    $step = $this->config['rewrite_step'];
    		  		function rewrite_url($url) {
		  		
					$url = trim($url);
					$url = strtolower($url);
					$url = preg_replace('/<(\/{0,1})img(.*?)(\/{0,1})\>/', 'image', $url);
					$url = str_replace ('<br />', '', $url);
					$url = str_replace (' ', '-', $url);

					$find = array('й',
						  		  'и',
								  'л',
								  'к'
								  );
					$url = str_replace ($find, 'e', $url);

					$find = array('н',
						  		  'м',
								  'о',
								  'п'
								  );
					$url = str_replace ($find, 'i', $url);

					$find = array('у',
						  		  'т',
								  'ф',
								  'ц');
					$url = str_replace ($find, 'o', $url);

					$find = array('б',
						  		  'а',
								  'в',
								  'д'
								  );
					$url = str_replace ($find, 'a', $url);

					$find = array('ъ',
						  		  'щ',
								  'ы',
								  'ь'
								  );
					$url = str_replace ($find, 'u', $url);

					$find = array('з');
					$url = str_replace ($find, 'c', $url);

					$url = str_replace ('--', '-', $url);

					return $url;
   				}	
	
		$search = array ("/index.php\?pid=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)<\/a>/e",
						 '@index.php\?ind=downloads&amp;op=section_view&amp;idev=([0-9]+)&amp;order=([0-9]+)&amp;st=([0-9]+)@si',
				  		 '@index.php\?ind=downloads&amp;op=section_view&amp;idev=([0-9]+)@si',
				  		 '@index.php\?ind=downloads&amp;op=entry_view&amp;iden=([0-9]+)@si',
						 '@index.php\?ind=downloads&amp;op=submit_file@si',
						 '@index.php\?ind=downloads&amp;op=add_file@si',
						 '@index.php\?ind=downloads&amp;op=update_file&amp;iden=([0-9]+)@si',
				  		 '@index.php\?ind=downloads&amp;op=search@si',
						 '@index.php\?ind=downloads&amp;op=result_search@si',
				  		 '@index.php\?ind=downloads&amp;op=submit_comment&amp;ide=([0-9]+)@si',
				  		 '@index.php\?ind=downloads&amp;op=submit_rate&amp;ide=([0-9]+)@si',
						 '@index.php\?ind=downloads&amp;op=edit_file&amp;iden=([0-9]+)@si',
						 '@index.php\?ind=downloads&amp;op=del_file&amp;iden=([0-9]+)@si',
						 '@index.php\?ind=downloads&amp;op=add_comment&amp;ide=([0-9]+)@si',
						 '@index.php\?ind=gallery&amp;op=section_view&amp;idev=([0-9]+)&amp;order=([0-9]+)&amp;st=([0-9]+)@si',
						 '@index.php\?ind=gallery&amp;op=section_view&amp;idev=([0-9]+)@si',
						 '@index.php\?ind=gallery&amp;op=foto_show&amp;ida=([0-9]+)@si',
						 '@index.php\?ind=gallery&amp;op=search@si',
						 '@index.php\?ind=gallery&amp;op=result_search@si',
						 '@index.php\?ind=gallery&amp;op=slide_start@si',
						 '@index.php\?ind=gallery&amp;op=add_file@si',
						 '@index.php\?ind=gallery&amp;op=update_file&amp;iden=([0-9]+)@si',
					     '@index.php\?ind=gallery&amp;op=submit_file@si',
						 '@index.php\?ind=gallery&amp;op=submit_postcard&amp;ide=([0-9]+)@si',
						 '@index.php\?ind=gallery&amp;op=submit_comment&amp;ide=([0-9]+)@si',
						 '@index.php\?ind=gallery&amp;op=submit_rate&amp;ide=([0-9]+)@si',
						 '@index.php\?ind=gallery&amp;op=edit_file&amp;iden=([0-9]+)@si',
						 '@index.php\?ind=gallery&amp;op=del_file&amp;iden=([0-9]+)@si',
						 '@index.php\?ind=gallery&amp;op=add_comment&amp;ide=([0-9]+)@si',
						 '@index.php\?ind=news&amp;op=section_view&amp;idev=([0-9]+)&amp;order=([0-9]+)&amp;st=([0-9]+)@si',
						 '@index.php\?ind=news&amp;op=section_view&amp;idev=([0-9]+)@si',
						 '@index.php\?ind=news&amp;op=entry_view&amp;iden=([0-9]+)@si',
						 '@index.php\?ind=news&amp;op=submit_file@si',
						 '@index.php\?ind=news&amp;op=reg_data@si',
						 '@index.php\?ind=news&amp;op=update_file&amp;idnews=([0-9]+)@si',
						 '@index.php\?ind=news&amp;op=search@si',
						 '@index.php\?ind=news&amp;op=result_search@si',
						 '@index.php\?ind=news&amp;op=edit_file&amp;iden=([0-9]+)@si',
						 '@index.php\?ind=news&amp;op=del_file&amp;iden=([0-9]+)@si',
						 '@index.php\?ind=news&amp;op=submit_comment&amp;idnews=([0-9]+)@si',
						 '@index.php\?ind=news&amp;op=cat&amp;idcat=([0-9]+)@si',
						 '@index.php\?ind=news&amp;op=lists&amp;best=1@si',
						 '@index.php\?ind=news&amp;op=lists&amp;popular=1@si',
						 '@index.php\?ind=news&amp;op=lists&amp;list=1@si',
						 '@index.php\?ind=news&amp;op=add_comment&amp;idcom=([0-9]+)@si',
						 '@index.php\?ind=news&amp;op=print_news&amp;ide=([0-9]+)@si',
						 '@index.php\?ind=topsite&amp;op=click_site&amp;idb=([0-9]+)@si',
						 '@index.php\?ind=topsite&amp;op=submit_rate&amp;ide=([0-9]+)@si',
						 '@index.php\?ind=topsite&amp;op=submit_site@si',
						 '@index.php\?ind=reviews&amp;op=cat&amp;idc=([0-9]+)&amp;order=([0-9]+)&amp;st=([0-9]+)@si',
						 '@index.php\?ind=reviews&amp;op=section_view&amp;idev=([0-9]+)@si',
						 '@index.php\?ind=reviews&amp;op=entry_view&amp;iden=([0-9]+)@si',
						 '@index.php\?ind=reviews&amp;op=result_search@si',
						 '@index.php\?ind=reviews&amp;op=search@si',
					     '@index.php\?ind=reviews&amp;op=add_file@si',
						 '@index.php\?ind=reviews&amp;op=update_file&amp;iden=([0-9]+)@si',
						 '@index.php\?ind=reviews&amp;op=submit_file1@si',
						 '@index.php\?ind=reviews&amp;op=submit_file@si',
						 '@index.php\?ind=reviews&amp;op=submit_rate&amp;ide=([0-9]+)@si',
						 '@index.php\?ind=reviews&amp;op=edit_file&amp;iden=([0-9]+)@si',
						 '@index.php\?ind=reviews&amp;op=del_file&amp;iden=([0-9]+)@si',
				         '@index.php\?ind=reviews&amp;op=submit_comment&amp;ide=([0-9]+)@si',
						 '@index.php\?ind=reviews&amp;order=([0-9]+)@si',
						 '@index.php\?ind=reviews&amp;op=cat&amp;idc=([0-9]+)@si',
						 '@index.php\?ind=reviews&amp;op=add_comment&amp;idc=([0-9]+)@si',
						 '@index.php\?ind=blog&amp;op=home&amp;idu=([0-9]+)@si',
						 '@index.php\?ind=poll&amp;op=poll_show&amp;poll_id=([0-9]+)@si',
						 '@index.php\?ind=poll&amp;op=poll_result&amp;poll_id=([0-9]+)@si',
						 '@index.php\?ind=poll&amp;op=del_comment&amp;idcomm=([0-9]+)&amp;ide=([0-9]+)@si',
						 '@index.php\?ind=poll&amp;op=add_comment@si',
						 '@index.php\?ind=poll&amp;op=poll_save@si',
						 '@index.php\?ind=quote&amp;op=submit_quote@si',
 						 );
		$replace = array ("rewrite_url('\\6').'-page-\\1.html'. stripslashes('\\5\\6') . '</a>'",
						  'downloads'.$step.'section'.$step.'\\1-\\3.html',
				   		  'downloads'.$step.'section'.$step.'\\1.html',
				   		  'downloads'.$step.'file'.$step.'\\1.html',
						  'downloads'.$step.'submit.html',
						  'downloads'.$step.'addfile.html',
						  'downloads'.$step.'editfile'.$step.'\\1.html',
				   		  'downloads'.$step.'search.html',
						  'downloads'.$step.'searchresult.html',
						  'downloads'.$step.'comment'.$step.'\\1.html',
						  'downloads'.$step.'rate'.$step.'\\1.html',
					      'downloads'.$step.'edit'.$step.'\\1.html',
						  'downloads'.$step.'delete'.$step.'\\1.html',
						  'downloads'.$step.'add_comment-\\1.html',
						  'gallery'.$step.'section'.$step.'\\1-\\3.html',
						  'gallery'.$step.'section'.$step.'\\1.html',
						  'gallery'.$step.'image'.$step.'\\1.html',
						  'gallery'.$step.'search.html',
						  'gallery'.$step.'searchresult.html',
						  'gallery'.$step.'present.html',
						  'gallery'.$step.'addfile.html',
						  'gallery'.$step.'editfile'.$step.'\\1.html',
						  'gallery'.$step.'submit.html',
						  'gallery'.$step.'ecard'.$step.'\\1.html',
						  'gallery'.$step.'comment'.$step.'\\1.html',
						  'gallery'.$step.'rate'.$step.'\\1.html',
						  'gallery'.$step.'edit'.$step.'\\1.html',
						  'gallery'.$step.'delete'.$step.'\\1.html',
						  'gallery'.$step.'add_comment-\\1.html',
						  'news'.$step.'section'.$step.'\\1-\\3.html', 
						  'news'.$step.'section'.$step.'\\1.html',
						  'news'.$step.'page'.$step.'\\1.html',
						  'news'.$step.'submit.html',
						  'news'.$step.'addfile.html', 
						  'news'.$step.'editfile'.$step.'\\1.html', 
						  'news'.$step.'search.html', 
						  'news'.$step.'searchresult.html',
						  'news'.$step.'edit'.$step.'\\1.html',
					      'news'.$step.'delete'.$step.'\\1.html',
						  'news'.$step.'comment'.$step.'\\1.html',
						  'news'.$step.'cat'.$step.'\\1.html',
						  'news'.$step.'best'.$step.'1\\1.html',
						  'news'.$step.'popular'.$step.'1\\1.html',
						  'news'.$step.'list'.$step.'1\\1.html',
						  'news'.$step.'add_comment'.$step.'\\1.html',
						  'news'.$step.'print'.$step.'\\1.html',
						  'site'.$step.'\\1.html',
						  'rate'.$step.'site'.$step.'\\1.html',
						  'topsite'.$step.'submit.html',
						  'reviews'.$step.'cat'.$step.'\\1-\\3.html',
						  'reviews'.$step.'section'.$step.'\\1.html',
						  'reviews'.$step.'article'.$step.'\\1.html',
						  'reviews'.$step.'searchresult.html',
						  'reviews'.$step.'search.html',
						  'reviews'.$step.'addfile.html',
						  'reviews'.$step.'editfile'.$step.'\\1.html',
						  'reviews'.$step.'submitfile.html',
						  'reviews'.$step.'submit.html',
                          'reviews'.$step.'rate'.$step.'\\1.html',
						  'reviews'.$step.'edit'.$step.'\\1.html',
					      'reviews'.$step.'delete'.$step.'\\1.html',
						  'reviews'.$step.'comment'.$step.'\\1.html',
						  'reviews'.$step.'sectionorder'.$step.'\\1.html',
						  'reviews'.$step.'cat'.$step.'\\1.html',
						  'reviews'.$step.'add_comment'.$step.'\\1.html',
						  'blog'.$step.'\\1.html',
						  'poll'.$step.'show'.$step.'\\1.html', 
						  'poll'.$step.'result'.$step.'\\1.html',
						  'poll'.$step.'deletecom'.$step.'\\1-\\2.html',
						  'poll'.$step.'addcoment.html',
						  'poll'.$step.'save.html',
						  'quote'.$step.'submit.html',
						  );  
        $output = preg_replace($search, $replace, $content);  
               // $output = preg_replace('/index\.php\?ind=([a-z_-]+)&amp;op=([a-z_-]+)&amp;[a-z_]*id[a-z_]*=(\d+)/', "\\1/\\2/\\4.html" ,$content);
		
        return $output;
    } //ЧПУ
	function printpage ($left, $right, $title, $content_main, $descr="", $keywords="") {
		global $DB, $Skin, $mklib_board, $mkportals;
		
		// parse content if there is header. Remove header and other duplicates tag
		$board_header = "";
		$pos = strpos($content_main, "<head>");
		$pos2 = strpos($content_main, "</head>");
		if ($pos && $pos2)  {
			$board_header = substr($content_main, ($pos +6), ($pos2 - $pos -6));
			$content_main = substr($content_main, $pos2);
			$content_main = str_replace ("</head>", "", $content_main);
			$content_main = str_replace ("<body>", "", $content_main);
			$content_main = str_replace ("</body>", "", $content_main);
			$content_main = str_replace ("</html>", "", $content_main);
			$content_main = "<tr><td valign=\"top\" class=\"mkalign1\">".$content_main."</td></tr>";
		}
		//end parse
		$output = $this->header($title, $left, $right, $board_header, $descr, $keywords);
		if ($this->loadcolumnleft)  {
			$output .= $this->block_left();
		}
		
	//	$output .= $this->block_center1();
		
		$output .= $this->block_center($content_main);
		//$output .= $this->block_center2();
		if ($this->loadcolumnright)  {
			$output .= $this->block_right();
		}
		$output .= $editor;
//Meo: Changed in C 0.1 for AEF shoutbox
		global $MK_BOARD;
		if($MK_BOARD == "AEF"){
			$output .= $Skin->AEF_shoutbox();
		}
// End
		$this->update_counter(); //Update page views
		$output .= $this->footer();
        //--added ЧПУ
	if($this->config['rewrite_url']){
        $output = $this->urlrewriting($output);
        }
        //--end ЧПУ

		$DB->close_db();
		
		@header("Content-type: text/html; charset={$this->charset}");
		
		print $output;
		exit;
	}
	
	function printpage_forum ($left, $right, $title, $content_main, $editor="") {
		global $DB, $Skin, $mklib_board, $mkportals, $modname;
		$modname = "forum";
		// parse content if there is header. Remove header and other duplicates tag
		$board_header = "";
		$pos = strpos($content_main, "<head>");
		$pos2 = strpos($content_main, "</head>");
		if ($pos && $pos2)  {
			$board_header = substr($content_main, ($pos +6), ($pos2 - $pos -6));
			$content_main = substr($content_main, $pos2);
			$content_main = str_replace ("</head>", "", $content_main);
			//$content_main = str_replace ("<body>", "", $content_main);
			$content_main = preg_replace('/<body(.*?)>/i',"",$content_main); //changed for phpBB3 compatibility
			$content_main = str_replace ("</body>", "", $content_main);
			$content_main = str_replace ("</html>", "", $content_main);
			$content_main = "<tr><td valign=\"top\" class=\"mkalign1\">".$content_main."</td></tr>";
		}

		//end parse
		$output = $this->header($title, $left, $right, $board_header);
		if ($this->unloadforumleft == 0)  {
			$output .= $this->block_left();
		}
		$output .= $this->block_center($content_main);
		if ($this->unloadforumright == 0)  {
			$output .= $this->block_right();
		}
		$output .= $editor;
		$this->update_counter(); //Update page views
		$output .= $this->footer();
		//--added ЧПУ
	
        //--end ЧПУ
		return $output;
		//deprecated
		//$DB->close_db();
		//exit;
	}



	function printpage_admin ($title, $content_admin, $editor="") {
		global $Skin, $DB;
		require "ad_menu.php";
		$output = $this->header($title, "1", "0");
		$output .= $Skin->view_column_left($menu);
		$output .= $Skin->view_separator_v();
		$output .= $this->block_center($content_admin);
		$output .= $editor;
		$output .= $this->footer_admin();
		//--added ЧПУ
	
        //--end ЧПУ		
		$DB->close_db();
		@header("Content-type: text/html; charset={$this->charset}");
				
		print $output;
		exit;
	}

	function printpage_blog ($left, $right, $title, $content_blog, $editor="") {
		global $mkportals, $DB, $Skin, $mklib_board, $mklib;
		require "mkportal/modules/blog/menusx.php";
		require "mkportal/modules/blog/menudx.php";

		$output = $this->header($title, $left, $right);
		if ($this->loadcolumnleft)  {
			$output .= $Skin->view_column_left($menusx);
			$output .= $Skin->view_separator_v();
		}
		$output .= $this->block_center($content_blog);
		if ($this->loadcolumnright)  {
			$output .= $Skin->view_separator_v();
			$output .= $Skin->view_column_right($menudx);
		}
		$output .= $editor;
// Meo: Changed in C 0.1 for AEF shoutbox
		global $MK_BOARD;
		if($MK_BOARD == "AEF"){
			$output .= $Skin->AEF_shoutbox();
		}
// End
		$this->update_counter(); //Update page views
		$output .= $this->footer();
//--added ЧПУ
	
        //--end ЧПУ
		$DB->close_db();
		
		@header("Content-type: text/html; charset={$this->charset}");

		print $output;
		exit;
	}

	function error_page ($message) {
		global $mkportals, $DB, $Skin;

		$titlem = "";
  		$content ="
			<tr>
			  <td class=\"contents\">
			    <div align=\"center\" class=\"tabmain\"><br />
			      <img src=\"$this->template/images/error.gif\" alt=\"\" /><br />
			      <span class=\"mkerror\">! {$this->lang['error']} !</span><br /><br />
			      <b> {$this->lang['error_pre']}<br />
			      $message</b><br /><br /><br /><br />
				<table>
				  <tr>
				    <td><a href=\"javascript:history.go(-1)\"><img src=\"$this->template/images/f2.gif\" alt=\"\" /></a>
				    </td>
				    <td><a href=\"javascript:history.go(-1)\">{$this->lang['back']}</a>
				    </td>
				  </tr>
				</table>
			    </div>
			  </td>
			</tr>
		";
		$blocks .= $Skin->view_block($titlem, $content);
  		$title = "{$this->lang['error']}";
		$this->printpage("1", "1", $title, $blocks);
	}
	function message_page ($message) {
		global $mkportals, $DB, $Skin;
		$content ="
		<tr>
  		<td>
    		<table class=\"moduleborder\" cellspacing=\"1\" cellpadding=\"0\" width=\"98%\" align=\"center\" border=\"0\">
      		<tr>
 		<td class=\"titadmin\" width=\"100%\" align=\"center\">
		<br /><br />{$message}<br /><br /><br />
		</td>
      		</tr>
      		<tr>
		<td align=\"center\" class=\"modulecell\">
		<br /><br /><div align=\"center\"><span class=\"mkcopyright\" style=\"font-size: 10px\"><a style=\"font-size: 10px\" href=\"http://www.mkportal.it/\" target=\"_blank\">MKPortal</a> {$this->config['mk_version']} &copy;2003-2008 <a style=\"font-size: 10px\" href=\"http://www.mkportal.it/\" target=\"_blank\">mkportal.it</a></span></div>
		</td>
      		</tr>
    		</table>
  		</td>
		</tr>
		";
		$title = "{$this->lang['messaget']}";
		$blocks .= $Skin->view_block($title, $content);
		$this->printpage("1", "1", $title, $blocks);
	}
	function off_line_page () {
		global $mkportals, $DB, $Skin, $mklib_board, $mklib;

		$message = '<p>'.$mklib->lang['offline'].'</p><p>'.$mklib->lang['offline2'].'</p>';

		$css = "<link href=\"$this->template/style.css\" rel=\"stylesheet\" type=\"text/css\" />";		

		if ($Skin = "Forum"){
		$css = $mklib_board->import_css();
		}

		$output ="
<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"
        \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\" lang=\"en\">		
<head>
  <meta http-equiv=\"content-type\" content=\"text/html; charset={$mklib->charset}\" />
  <meta name=\"generator\" content=\"MKPortal\" />
  <title>$this->sitename</title>
  {$css}
</head>
<body style=\"margin: 100px;\">
  <div class=\"offlinetxt\" style=\"padding: 10px;\" align=\"center\">
    <img src=\"$this->template/images/error.gif\" alt=\"\" /><br />
    $this->sitename
    <p>{$this->lang['error_pre']}</p>
    $message
  </div>			
</body>
</html>
		";
		print $output;
		//$DB->close_db();
		exit;
	}
	function update_counter () {
		global $DB;
		$counter = $this->config['counter'];
		++$counter;
		$DB->query("UPDATE mkp_config SET valore ='$counter' WHERE chiave = 'counter'");
	}

	function retrieve_urlo() {
//Meo: Changed in C 0.1 for AEF shoutbox
			
 		global $mkportals, $DB, $MK_BOARD, $dbtables, $mklib_board, $mklib;

		if ($MK_BOARD == "AEF") {
			$DB->query("SELECT sh.*, u.username FROM ".$dbtables['shouts']." sh
					LEFT JOIN ".$dbtables['users']." u ON (sh.shuid = u.id)
					ORDER BY sh.shid DESC LIMIT 3");
			while( $row = $DB->fetch_row($query) ) {
				$uid = $row['shuid'];
				$name = "<a class='uno' href='$mkportals->forum_url/index.php?mid=$uid'>" . $row['username'] . "</a>";
				$message = preg_replace('/\[URL=(.+?)\](.+)\[\/URL\]/i',"",$row['shtext']);
				$message = preg_replace('/\[IMG\](.+?)\[\/IMG\]/i',"",$message);
				$message = str_replace("ttp","", $message);
				$urlo2.= "[ " . $this->create_date($row['shtime'], 'short2') . " ] " . $name;
				$urlo2 .= ": ". $this->decode_bb($message);
				$urlo2 .= "<br />";
			}
			$urlo2 = smileyfy($urlo2);
		} else {
			$limit = $mklib->config['urlo_block'];
			if (!$limit) {$limit = 3;}			 $link_user = $mklib_board->forum_link("profile");
			$DB->query( "SELECT id, idaut, name, message, time FROM mkp_urlobox ORDER BY `id` DESC LIMIT $limit");
			while( $row = $DB->fetch_row($query) ) {
				$uid = $row['shuid'];
				$name = "<a class='uno' href='$link_user={$row['idaut']}'>" . $row['name'] . "</a>";
				$message = preg_replace('/\[URL=(.+?)\](.+)\[\/URL\]/i',"",$row['message']);
				$message = preg_replace('/\[IMG\](.+?)\[\/IMG\]/i',"",$message);
				$urlo2.= "[ " . $this->create_date($row['time'], 'short2') . " ] " . $name;
				$urlo2 .= ": ". $this->decode_bb($message);
				$urlo2 .= "<br />";
			}
		}
 		return array($urlo1, $urlo2);
//End		
 	}

	//Prepare $_POST data for insertion into database
	function convert_savedb($t="")
	{
		global $MK_BOARD;

		$t = str_replace( "&#39;"   , "'", $t );
		$t = str_replace( "&#33;"   , "!", $t );
		$t = str_replace( "&#036;"   , "$", $t );
		$t = str_replace( "&#124;"  , "|", $t );
		$t = str_replace( "&amp;"   , "&", $t );
		$t = str_replace( "&gt;"    , ">", $t );
		$t = str_replace( "&lt;"    , "<", $t );
		$t = str_replace( "&quot;"  , '"', $t );
		$t = $this->clean_script($t);

		//added by alez in C1.2.2
		$t = addslashes($t);
    
		return $t;
	}

	//addslashes before inserting data into database - Portal CP
	function convert_savedbadmin($t="")
	{
  //added by alez in C1.2.2
		$t = addslashes($t);
		return $t;
	}

	//stripslashes for php block & page preview  - Portal CP
	function convert_viewphpadmin($t="")
	{
		global $MK_BOARD;

  //added by alez in C1.2.2
		$t = stripslashes($t);
		
		return $t;		
	}


	function build_pages($data)
	{
		$work = array();
		$section = ($data['leave_out'] == "") ? 4 : $data['leave_out'];
		$work['pages']  = 1;
		if ( ($data['TOTAL_POSS'] % $data['PER_PAGE']) == 0 )
		{
			$work['pages'] = $data['TOTAL_POSS'] / $data['PER_PAGE'];
		}
		else
		{
			$number = ($data['TOTAL_POSS'] / $data['PER_PAGE']);
			$work['pages'] = ceil( $number);
		}
		$work['total_page']   = $work['pages'];
		$work['current_page'] = $data['CUR_ST_VAL'] > 0 ? ($data['CUR_ST_VAL'] / $data['PER_PAGE']) + 1 : 1;
		if ($work['pages'] > 1)
		{
			$work['first_page'] = "<span class=\"mkpagelink\">{$work['pages']} {$this->lang['pages']}</span>";

			for( $i = 0; $i <= $work['pages'] - 1; ++$i )
			{
				$RealNo = $i * $data['PER_PAGE'];
				$PageNo = $i+1;

				if ($RealNo == $data['CUR_ST_VAL'])
				{
					$work['page_span'] .= "&nbsp;<span class=\"mkpagecurrent\">{$PageNo}</span>";
				}
				else
				{
					if ($PageNo < ($work['current_page'] - $section))
					{
						$work['st_dots'] = "<span class=\"mkpagelinklast\"><a href=\"{$data['BASE_URL']}&amp;st=0\" title=\"{$this->lang['page']} 1\">&laquo;</a></span>&nbsp;...";
						continue;
					}

					if ($PageNo > ($work['current_page'] + $section))
					{
						$work['end_dots'] = "...&nbsp;&nbsp;<span class=\"mkpagelinklast\"><a href=\"{$data['BASE_URL']}&amp;st=".($work['pages']-1) * $data['PER_PAGE']."\" title=\"{$this->lang['page']} {$work['pages']}\">&raquo;</a></span>";
						break;
					}

					$work['page_span'] .= "&nbsp;<span class=\"mkpagelink\"><a href=\"{$data['BASE_URL']}&amp;st={$RealNo}\">{$PageNo}</a></span>";
				}
			}

			$work['return']    = $work['first_page'].$work['st_dots'].$work['page_span'].'&nbsp;'.$work['end_dots'];
		}
		else
		{
			$work['return']    = $data['L_SINGLE'];
		}

		return $work['return'];
	}
	function read_config() {
 		global $DB;

		$myquery = $DB->query("SELECT * FROM mkp_config");
		while( $row = $DB->fetch_row($myquery) ) {
			$chiave = $row['chiave'];
			$valore = $row['valore'];
			$config[$chiave] = $valore;
		}
 		return $config;
 	}
	
	function read_stat() {
 		global $DB;

		$myquery = $DB->query("SELECT * FROM mkp_stat");
		while( $row = $DB->fetch_row($myquery) ) {
			$chiave = $row['chiave'];
			$valore = $row['valore'];
			$stat[$chiave] = $valore;
		}
 		return $stat;
 	}
	
	function read_member() {
 		global $DB, $mkportals;

		if (is_array($mkportals->member['mgroup'])) { //C1.2 rc2 - phpBB3 only
			$group = '';
			foreach ($mkportals->member['mgroup'] AS $key => $value) {
				$sep = ($key != 0) ? ',' : '';				
				$group .= $sep.$value;
			}
		} else { //C1.2 rc2 - All other boards
			$group = $mkportals->member['mgroup'];
		}		

		//$myquery = $DB->query( "SELECT * FROM mkp_pgroups WHERE g_id = '$group'");
		$myquery = $DB->query( "SELECT * FROM mkp_pgroups WHERE g_id IN ($group)");

		while( $row = $DB->fetch_row($myquery) ) {
			$this->member['g_send_news'] = 		$row['g_send_news'];
			$this->member['g_mod_news'] = 		$row['g_mod_news'];
			$this->member['g_access_download'] = 	$row['g_access_download'];
			$this->member['g_download_files'] = 	$row['g_download_files'];
			$this->member['g_send_download'] = 	$row['g_send_download'];
			$this->member['g_mod_download'] = 	$row['g_mod_download'];
			$this->member['g_access_gallery'] = 	$row['g_access_gallery'];
			$this->member['g_send_gallery'] = 	$row['g_send_gallery'];
			$this->member['g_mod_gallery'] = 	$row['g_mod_gallery'];
			$this->member['g_access_urlobox'] = 	$row['g_access_urlobox'];
			$this->member['g_send_urlobox'] = 	$row['g_send_urlobox'];
			$this->member['g_mod_urlobox'] = 	$row['g_mod_urlobox'];
			$this->member['g_access_chat'] = 	$row['g_access_chat'];
			$this->member['g_access_cpa'] = 	$row['g_access_cpa'];
			$this->member['g_access_blog'] = 	$row['g_access_blog'];
			$this->member['g_send_blog'] = 		$row['g_send_blog'];
			$this->member['g_access_topsite'] = 	$row['g_access_topsite'];
			$this->member['g_send_topsite'] = 	$row['g_send_topsite'];
			$this->member['g_send_ecard'] = 	$row['g_send_ecard'];
			$this->member['g_access_quote'] = 	$row['g_access_quote'];
			$this->member['g_send_quote'] = 	$row['g_send_quote'];
			$this->member['g_send_comments'] = 	$row['g_send_comments'];
			$this->member['g_access_reviews'] = 	$row['g_access_reviews'];
			$this->member['g_send_reviews'] = 	$row['g_send_reviews'];
			$this->member['g_mod_reviews'] = 	$row['g_mod_reviews'];
			// mod poll
            $this->member['g_send_poll'] =     $row['g_send_poll'];
            $this->member['g_mod_poll'] =     $row['g_mod_poll'];
            $this->member['g_access_contact'] =     $row['g_access_contact'];
            $this->member['g_access_recommend'] =     $row['g_access_recommend'];
              
            // end poll
		}

		return $this->member;
 	}

	//clean script in HTML
	function clean_script( $h )
	{

		$h = preg_replace( "#<(\s+?)?s(\s+?)?c(\s+?)?r(\s+?)?i(\s+?)?p(\s+?)?t#si"        , "&lt;script" , $h );
		$h = preg_replace( "#<(\s+?)?/(\s+?)?s(\s+?)?c(\s+?)?r(\s+?)?i(\s+?)?p(\s+?)?t#si", "&lt;/script", $h );
		
		$h = preg_replace( "/javascript/i" , "j&#097;v&#097;script", $h );
		$h = preg_replace( "/ecmascript/i" , "e&#099;m&#097;script", $h );
		$h = preg_replace( "/vbscript/i" , "v&#098;s&#099ript", $h );
		$h = preg_replace( "/alert/i"      , "&#097;lert"          , $h );
		$h = preg_replace( "/about:/si"     , "&#097;bout&#58;"         , $h );
		$h = preg_replace( "/data:/si"     , "d&#097;ta&#58;"         , $h );

		$h = preg_replace( "/onfocus/i"    , "&#111;nfocus"        , $h );
		$h = preg_replace( "/onblur/i"    , "&#111;nblur"        , $h );
		$h = preg_replace( "/ondblclick/i"    , "&#111;ndblclick"        , $h );
		$h = preg_replace( "/onclick/i"    , "&#111;nclick"        , $h );
		$h = preg_replace( "/onmousedown/i"    , "&#111;nmousedown"        , $h );
		$h = preg_replace( "/onmouseup/i"    , "&#111;nmouseup"        , $h );
		$h = preg_replace( "/onmouseover/i", "&#111;nmouseover"    , $h );
		$h = preg_replace( "/onmousemove/i"    , "&#111;nmousemove"        , $h );
		$h = preg_replace( "/onmouseout/i"    , "&#111;nmouseout"        , $h );
		$h = preg_replace( "/onkeypress/i"    , "&#111;nkeypress"        , $h );
		$h = preg_replace( "/onkeydown/i"    , "&#111;nkeydown"        , $h );
		$h = preg_replace( "/onkeyup/i"    , "&#111;nkeyup"        , $h );
		$h = preg_replace( "/onunload/i"   , "&#111;nunload"   		, $h );
        	$h = preg_replace( "/onabort/i"    , "&#111;nabort"    		, $h );
        	$h = preg_replace( "/onerror/i"    , "&#111;nerror"    		, $h );
		$h = preg_replace( "/onchange/i"   , "&#111;nchange"   		, $h );
		$h = preg_replace( "/onreset/i"    , "&#111;nreset"    		, $h );
		$h = preg_replace( "/onselect/i"   , "&#111;nselect"   		, $h );
		$h = preg_replace( "/onload/i"     , "&#111;nload"         , $h );
		$h = preg_replace( "/onsubmit/i"   , "&#111;nsubmit"       , $h );

		$h = preg_replace( "/<html/i"      , "&lt;html"            , $h );
		$h = preg_replace( "/<head/i"      , "&lt;head"            , $h );
		$h = preg_replace( "/<base/i"      , "&lt;base"            , $h );
		$h = preg_replace( "/<meta/i"      , "&lt;meta"            , $h );
		$h = preg_replace( "/<title/i"      , "&lt;title"            , $h );
		$h = preg_replace( "/<body/i"      , "&lt;body"            , $h );
		$h = preg_replace( "/<!DOCTYPE/i"      , "&lt;!DOCTYPE"            , $h );
		$h = preg_replace( "/document\./i" , "&#100;ocument."      , $h );
		$h = preg_replace( "/window\./i" , "win&#100;ow."      , $h );

		//added by visiblesoul C1.2 rc2
		$h = preg_replace( "/<applet/i"      , "&lt;applet"            , $h );
		//$h = preg_replace( "/<embed/i"      , "&lt;embed"            , $h );
		//$h = preg_replace( "/<object/i"      , "&lt;object"            , $h );
		$h = preg_replace( "/<link/i"      , "&lt;link"            , $h );
		$h = preg_replace( "/<iframe/i"      , "&lt;iframe"            , $h );
		$h = preg_replace( "/<frame/i"      , "&lt;frame"            , $h );
		$h = preg_replace( "/<frameset/i"      , "&lt;frameset"            , $h );
		//$h = preg_replace( "/<style/i"      , "&lt;style"            , $h );
		//$h = preg_replace( "/style([\s]*)=/i", "style&#61;", $h ); //disable style attributes
		//$h = preg_replace( "/style([\s]*)=([\s]*)(?:'[^']*'|\"\"[^\"\"]*\"\"|[^\s>]+)/i", "", $h ); //strip style attributes
		
		//added by Kimi in C1.2.2
		$h = preg_replace( "/onfinish/i"      , "&#111;nfinish"            , $h );
		$h = preg_replace( "/<salertcript/i"  , "&lt;salertcript"          , $h );
		$h = preg_replace( "/aalertlert/i"    , "&#097;&#097;lertlert"     , $h );
		$h = preg_replace( "/aleonsubmitrt/i" , "&#097;leonsubmitrt"       , $h );
		$h = preg_replace( "/ononsubmitload/i", "&#111;n&#111;nsubmitload" , $h );
		

		return $h;
	}

	function hostname() {
	
		global $_SERVER, $_ENV;
		
		if ($_SERVER['HTTP_HOST'] || $_ENV['HTTP_HOST']) {
			$h = ($_SERVER['HTTP_HOST'] ? $_SERVER['HTTP_HOST'] : $_ENV['HTTP_HOST']);
		} else {
			$h = ($_SERVER['SERVER_NAME'] ? $_SERVER['SERVER_NAME'] : $_ENV['SERVER_NAME']);
		}

		return $h;

	}

	function check_referer() {
	
//		global $mkportals;

		//this include hostnames allow to do POST, separated by a comma
		//$allowedreferrers = unserialize($this->config['postwhitelist']);
		$allowedreferrers = $this->config['postwhitelist'];
		$allowedreferrers = str_replace(' ', '', $allowedreferrers);

		//Is $_POST referer check disabled?
		if ($allowedreferrers == 'disable') {
			return true;
		}

		$http_host = $this->hostname();
		$pass_ref_check = false;
		if ( $http_host && isset($_SERVER['HTTP_REFERER']) )
		{
			$referrer_parts = @parse_url($_SERVER['HTTP_REFERER']);
			$ref_port = (isset($referrer_parts['port'])) ? intval($referrer_parts['port']) : '';
			$ref_host = $referrer_parts['host'] . (!empty($ref_port) ? ":$ref_port" : '');

			$allowed = explode(',', $allowedreferrers);
			$allowed[] = preg_replace('#^www\.#i', '', $http_host);			
			//$allowed[] = '.2checkout.com';
			//$allowed[] = '.paypal.com';

			foreach ($allowed as $host)
			{
				if (preg_match('#' . preg_quote($host, '#') . '$#siU', $ref_host))
				{
					$pass_ref_check = true;
					break;
				}
			}
		}
		return $pass_ref_check;
	}

	//--------------------------------------------------------------------------
	// check if a POST is from allowed hosts
	//--------------------------------------------------------------------------
	function check_post_referrer() {

		global $mklib;

		if (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST')
		{
			$pass_ref_check = $this->check_referer();
			if ($pass_ref_check == false)
			{						
				$message = "{$this->lang['post_nowhitelist']}";
				$mklib->error_page($message);
				exit;
			}
		}
	}

	function mkp_input()
    {
        global $HTTP_GET_VARS, $HTTP_POST_VARS, $mklib, $mkportals;
		
		$var_not_int = array('langid', 'skinid', 'idl'); //this includes names of vars begun or ended with ID but they are not INTEGER - making some exception here
		$var_int = array('order', 'st', 'campo', 'rating', 'code', 'cat', 'categoria','scambio','maxmess');  //this includes some variable FORCED to be an INTEGER - ALL OF THEM WILL BE CONVERTED TO INTEGER

		if (!defined('IN_MKPADMIN')) {
			$var_int = array_merge($var_int, array('evento'));
		}
		
		if (!isset($HTTP_POST_VARS) && isset($_POST))
		{
			$HTTP_POST_VARS = $_POST;
			$HTTP_GET_VARS = $_GET;
		}

		$result = array();

		//check POST referer (if poster is not Admin)
		if (!$mkportals->member['g_access_cp'] && !$mklib->member['g_access_cpa']) {
			$this->check_post_referrer();
		}
		
		//used in rating - a correct IP shouldn't be affected by this
		$_SERVER['REMOTE_ADDR'] = htmlspecialchars($_SERVER['REMOTE_ADDR']);

		//import GET
        if( is_array($HTTP_GET_VARS) ) {
            while(list($k, $v) = each($HTTP_GET_VARS)) {
				$k = preg_replace( "/[^a-zA-Z0-9\.\-\_]+/", "", $k ); //all variable names must be standardized
				$k = str_replace( ".."           , ""  , $k );
   				$k = preg_replace( "/\_\_(.+?)\_\_/"  , ""  , $k );

				//golden rule : STRIP FIRST, CONVERT LATER to avoid some funny business
				if ($k != '') {
	                if(is_array($HTTP_GET_VARS[$k])) {
    	                while( list($k2, $v2) = each($HTTP_GET_VARS[$k])) {
							$k2 = preg_replace( "/[^a-zA-Z0-9\.\-\_]+/", "", $k2 );
							$k2 = str_replace( '..'           , ""  , $k2 );
        					$k2 = preg_replace( "/\_\_(.+?)\_\_/"  , ""  , $k2 );
							if ( $k2 != '' ) {
								// Null byte characters
								$v2 = preg_replace( '/\\\0/' , '', $v2 );
								$v2 = str_replace(chr(0), '', $v2);
								$v2 = preg_replace( '/\\x00/', '', $v2 );
								$v2 = str_replace( '%00'     , '', $v2 );
								//others
        						$v2 = str_replace( "\r"        , ""              , $v2 );
								$v2 = trim($v2, "\\");

//								$v2 = str_replace( "&"            , "&amp;"         , $v2 );
								$v2 = preg_replace('/&(?!(#[0-9]{2,5}|[a-z]{2,8});)/si', '&amp;',	$v2 ); //unicode compatible
        						$v2 = str_replace( "<!--"         , "&#60;&#33;--"  , $v2 );
        						$v2 = str_replace( "-->"          , "--&#62;"       , $v2 );
        						$v2 = preg_replace( "/<script/i"  , "&#60;script"   , $v2 );
       							$v2 = str_replace( ">"            , "&gt;"          , $v2 );
        						$v2 = str_replace( "<"            , "&lt;"          , $v2 );
        						$v2 = str_replace( '"'           , "&quot;"        , $v2 );
        						$v2 = str_replace( "\n"        , "<br />"        , $v2 );
        						$v2 = str_replace( '$'      , "&#036;"        , $v2 );
        						$v2 = str_replace( "!"            , "&#33;"         , $v2 );
								$v2 = str_replace( "'"            , "&#39;"         , $v2 );
								$v2 = str_replace(','				, '&#44;'			, $v2);

								$result[$k][$k2] = $v2;
							}
            	        }
                	} else {
						// Null byte characters
						$v = preg_replace( '/\\\0/' , '', $v );
						$v = str_replace(chr(0), '', $v);
						$v = preg_replace( '/\\x00/', '', $v );
						$v = str_replace( '%00'     , '', $v );

        				$v = str_replace( "\r"        , ""              , $v );
						$v = trim($v, "\\");
//						$v = str_replace( "&"            , "&amp;"         , $v );
						$v = preg_replace('/&(?!(#[0-9]{2,5}|[a-z]{2,8});)/si', '&amp;',	$v );
        				$v = str_replace( "<!--"         , "&#60;&#33;--"  , $v );
        				$v = str_replace( "-->"          , "--&#62;"       , $v );
        				$v = preg_replace( "/<script/i"  , "&#60;script"   , $v );
       					$v = str_replace( ">"            , "&gt;"          , $v );
        				$v = str_replace( "<"            , "&lt;"          , $v );
        				$v = str_replace( '"'           , "&quot;"        , $v );
        				$v = str_replace( "\n"        , "<br />"        , $v );
        				$v = str_replace( '$'      , "&#036;"        , $v );
        				$v = str_replace( "!"            , "&#33;"         , $v );
						$v = str_replace( "'"            , "&#39;"         , $v );
						$v = str_replace(','				, '&#44;'			, $v);
						//fix bug 1 - in shortcut - some others will need to do manually
						if ( ((substr( strtolower($k),0,2 ) == 'id' || substr( strtolower($k), strlen($k)- 2, 2 ) == 'id') && !in_array($k, $var_not_int)) ||  in_array($k, $var_int) ) {
							$result[$k] = intval($v);
						} else {
							$result[$k] = $v;
						}
						
               		}
				}
			}
        }
		//import POST
        if( is_array($HTTP_POST_VARS)) {
            while(list($k, $v) = each($HTTP_POST_VARS)) {
				$k = preg_replace( "/[^a-zA-Z0-9\.\-\_]+/", "", $k ); //all variable names must be standardized
				$k = str_replace( ".."           , ""  , $k );
   				$k = preg_replace( "/\_\_(.+?)\_\_/"  , ""  , $k );
				if ($k != '') {
	                if (is_array($HTTP_POST_VARS[$k]) ) {
    	                while(list($k2, $v2) = each($HTTP_POST_VARS[$k])) {
							$k2 = preg_replace( "/[^a-zA-Z0-9\.\-\_]+/", "", $k2 );
							$k2 = str_replace( '..'           , ""  , $k2 );
        					$k2 = preg_replace( "/\_\_(.+?)\_\_/"  , ""  , $k2 );
							if ($k2 != '') {

        						$v2 = str_replace( "\r"        , ""              , $v2 );
								$v2 = trim($v2, "\\");

//								$v2 = str_replace( "&"            , "&amp;"         , $v2 );
								$v2 = preg_replace('/&(?!(#[0-9]{2,5}|[a-z]{2,8});)/si', '&amp;',	$v2 );
        						$v2 = str_replace( "<!--"         , "&#60;&#33;--"  , $v2 );
        						$v2 = str_replace( "-->"          , "--&#62;"       , $v2 );
        						$v2 = preg_replace( "/<script/i"  , "&#60;script"   , $v2 );
       							$v2 = str_replace( ">"            , "&gt;"          , $v2 );
        						$v2 = str_replace( "<"            , "&lt;"          , $v2 );
        						$v2 = str_replace( '"'           , "&quot;"        , $v2 );
        						$v2 = str_replace( "\n"        , "<br />"        , $v2 );
        						$v2 = str_replace( '$'      , "&#036;"        , $v2 );
        						$v2 = str_replace( "!"            , "&#33;"         , $v2 );
								$v2 = str_replace( "'"            , "&#39;"         , $v2 );

								$result[$k][$k2] = $v2;
							}
                    	}
            	    } else {
        				$v = str_replace( "\r"        , ""              , $v );
						$v = trim($v, "\\");
						$v = preg_replace('/&(?!(#[0-9]{2,5}|[a-z]{2,8});)/si', '&amp;',	$v );
//						$v = str_replace( "&"            , "&amp;"         , $v );
        				$v = str_replace( "<!--"         , "&#60;&#33;--"  , $v );
        				$v = str_replace( "-->"          , "--&#62;"       , $v );
        				$v = preg_replace( "/<script/i"  , "&#60;script"   , $v );
       					$v = str_replace( ">"            , "&gt;"          , $v );
        				$v = str_replace( "<"            , "&lt;"          , $v );
        				$v = str_replace( '"'           , "&quot;"        , $v );
        				$v = str_replace( "\n"        , "<br />"        , $v );
        				$v = str_replace( '$'      , "&#036;"        , $v );
        				$v = str_replace( "!"            , "&#33;"         , $v );
						$v = str_replace( "'"            , "&#39;"         , $v );
						$v = trim($v, "\\");
						if ( ((substr( strtolower($k),0,2 ) == 'id' || substr( strtolower($k), strlen($k)- 2, 2 ) == 'id') && !in_array($k, $var_not_int)) ||  in_array($k, $var_int) ) {
							$result[$k] = intval($v);
						} else {
							$result[$k] = $v;
						}
                	}
				}
            }
        }
		
		//process _FILES if there is
		if( !empty($_FILES) ) {
			if (is_array($_FILES)) {
				foreach ($_FILES as $k => $v) {
					$_FILES[$k]['name'] = trim(strval($_FILES[$k]['name']));
					//fix some browsers
					if ($_FILES[$k]['name'] == 'http://') $_FILES[$k]['name'] = '';
					$_FILES[$k]['name'] = preg_replace("#/$#", '', $_FILES[$k]['name']);
					$_FILES[$k]['name'] = preg_replace("/[^a-zA-Z0-9\_\-\.]/", '' , $_FILES[$k]['name']);
					$_FILES[$k]['name'] = preg_replace('#\.{1,}#s', '.', $_FILES[$k]['name']);
					$_FILES[$k]['name'] = preg_replace('#\_{2,}#s', '_', $_FILES[$k]['name']);
					$_FILES[$k]['type'] = trim(strval($_FILES[$k]['type']));				
					//fix for Opera
					$_FILES[$k]['type'] = preg_replace("/^(.+?);.*$/", "\\1", $_FILES[$k]['type']);
					$_FILES[$k]['tmp_name'] = trim(strval($_FILES[$k]['tmp_name']));
					$_FILES[$k]['size'] = intval($_FILES[$k]['size']);
				}
				
			} else {
					$_FILES = array(
						'name'     => '',
						'type'     => '',
						'tmp_name' => '',
						'size'     => 0,
					);
					
			}
		}


        return $result;
    }

	function create_date($now, $form="")
	{
 		global $mkportals, $MK_TIMEDIFF;

 		switch($form) {
  			case 'short':
      			$format = "d M Y";
     		break;
  			case 'time':
      			$format = "H:i";
     		break;
  			case 'small':
      			$format = "F Y";
     		break;
  			case 'normal':
      			$format= "l, d F Y";
     		break;
//Meo: Changed in C 0.1 to increase available date format
			case 'short2':
      			$format = "M d, H:i";
     		break;
// End
  			default:
   				$format = "l, d F Y H:i";
     		break;
 		}

 		if ( empty($translate) && $this->mklang != 'English' ) {
  			@reset($this->lang);
  			while ( list($match, $replace) = @each($this->lang) )
  			{
   				$translate[$match] = $replace;
  			}
 		}
 		$diff = $mkportals->member['timezone'];
 		if (substr($mkportals->member['timezone'], 0, 1) == '-') {
  			$diff = str_replace("-", "", $diff);
  			$now = $now - (3600 * $diff);
 		} else {
  			$now = $now + (3600 * $diff);
 		}
		$diff = $MK_TIMEDIFF;
 		if (substr($mkportals->member['timezone'], 0, 1) == '-') {
  			$diff = str_replace("-", "", $diff);
  			$now = $now - (3600 * $diff);
 		} else {
  			$now = $now + (3600 * $diff);
 		}

 		return ( !empty($translate) ) ? strtr(@gmdate($format, $now), $translate) : @gmdate($format, $now);
	}


// Meo: Recoded in C 0.1.b to have a better thumbs (same w x h) and extended image format (jpeg -gif - png)
	function CreateImage($thumbSize, $source, $dest, $border=0) {

		static $gd_version_number = null;
   		if ($gd_version_number === null) {
       			ob_start();
       			phpinfo(8);
       			$module_info = ob_get_contents();
       			ob_end_clean();
       			if (preg_match("/\bgd\s+version\b[^\d\n\r]+?([\d\.]+)/i", $module_info,$matches)) {
           			$gd_version_number = $matches[1];
				}
   			}
			if ($gd_version_number) {
				$sourcedate = 0;
				$destdate = 0;
				if (file_exists($dest)) {
					clearstatcache();
					$sourceinfo = stat($source);
					$destinfo = stat($dest);
					$sourcedate = $sourceinfo[10];
					$destdate = $destinfo[10];
				}
	 			if (!file_exists("$dest") or ($sourcedate > $destdate)) {
    
 				//getting the image dimensions  
				$imgSrc = $source;
				$imgsize = GetImageSize($imgSrc);
				$width = $imgsize[0];
				$height = $imgsize[1];

				if ($this->config['square_thumbs']) { //Square thumbnails

     				if (preg_match("/jpg|jpeg/",$imgsize['mime'])){$myImage= @ImageCreateFromJPEG($imgSrc);}  
     				if (preg_match("/png/",$imgsize['mime'])){$myImage= @imagecreatefrompng($imgSrc);}  
     				if (preg_match("/gif/",$imgsize['mime'])){$myImage= @imagecreatefromgif($imgSrc);}  

				if($width > $height){  
     					$biggestSide = $width;   
     					$cropPercent = .5;   
     					$cropWidth   = $biggestSide*$cropPercent;   
     					$cropHeight  = $biggestSide*$cropPercent;   
     					$c1 = array("x"=>($width-$cropWidth)/2, "y"=>($height-$cropHeight)/2);  
 				}else{
     					$biggestSide = $height;
     					$cropPercent = .5;
     					$cropWidth   = $biggestSide*$cropPercent;   
     					$cropHeight  = $biggestSide*$cropPercent;   
     					$c1 = array("x"=>($width-$cropWidth)/2, "y"=>($height-$cropHeight)/7);  
 				}
		
				if ($myImage && $gd_version_number >= 2) {
					$thumb = @ImageCreateTrueColor($thumbSize, $thumbSize);
				}
					if ($myImage && $gd_version_number < 2) {
   					$thumb = @ImageCreate($thumbSize, $thumbSize);
				}
				if ($thumb) {
 						@imagecopyresampled($thumb, $myImage, 0, 0, $c1['x'], $c1['y'], $thumbSize, $thumbSize, $cropWidth, $cropHeight);
   						ImageJPEG($thumb,$dest,$this->config['thumb_jpg_quality']); 
 						imagedestroy($thumb);
						imagedestroy($myImage);
					}
					
				} else { //Classic thumbnails

					/* Max width only ( <=M1.1.2b )
					$new_width = $thumbSize;
					$new_height = ceil($thumbSize * $height / $width);
					$myImage = @ImageCreateFromJPEG($imgSrc); */

					// Max height & Max width
                       			if($width > $height && $width > $thumbSize){ //horizontal image
                        			$new_width = $thumbSize;
                        			$new_height = ceil($thumbSize * $height / $width);                
                       			} else if ($width < $height && $height > $thumbSize) { //vertical image
                        			$new_height = $thumbSize;
                        			$new_width = ceil($thumbSize * $width / $height);
                       			} else {
						return; //Do not resize image
                       			}
					
					if (preg_match("/jpg|jpeg/",$imgsize['mime'])){$myImage= @ImageCreateFromJPEG($imgSrc);}  
     					if (preg_match("/png/",$imgsize['mime'])){$myImage= @imagecreatefrompng($imgSrc);}  
					if (preg_match("/gif/",$imgsize['mime'])){$myImage= @imagecreatefromgif($imgSrc);}

					if ($myImage && $gd_version_number >= 2) {
						$thumb=@ImageCreateTrueColor($new_width,$new_height);
					}
					if ($myImage && $gd_version_number < 2) {
   						$thumb = @ImageCreate($new_width,$new_height);
					}
					if ($thumb) {
						@imagecopyresized($thumb,$myImage,0,0,0,0,$new_width,$new_height,ImageSX($myImage),ImageSY($myImage));
   						ImageJPEG($thumb,$dest,$this->config['thumb_jpg_quality']); 
 						imagedestroy($thumb);
						imagedestroy($myImage);
					}
				} //end Classic thumbnails
			}

		}
	}
// End

		function ResizeImage($max_width, $im) {
			
			if (!$im) {return array();}
			$image_details = @getimagesize("$im");
			if (!$image_details) {return array();}
			$imagesize_x = $image_details[0];
			$imagesize_y = $image_details[1];
			$thumb_width=$max_width;
			
			$thumb_height = ceil($max_width * $imagesize_y / $imagesize_x);
			if ($imagesize_x < $max_width) {
				$thumb_width = $imagesize_x;
				$thumb_height = $imagesize_y;
			}
			return array ($thumb_width, $thumb_height);
		}

		function check_attach($file_type = "", $file_ext = "") {
    		if ($file_type == "" AND $file_ext == "") {
        		return FALSE;
    		}
    		$com_types = array('com' => 1, 'exe' => 1, 'bat' => 1, 'scr' => 1, 'pif' => 1, 'asp' => 1, 'cgi' => 1, 'pl' => 1, 'php' => 1 );
    		$mime = file("$this->sitepath/mkportal/include/mime_types.php");
			reset($mime);
			while (list($key, $val) = each($mime)) {
				$mime[$key] = trim($val);
			}
			if (in_array($file_type, $mime)) {
        		if (isset($com_types[strtolower($file_ext)])) {
            		return FALSE;
        		}
        		return TRUE;
    		}
		}


		function get_editor() {
		global $MK_LANG, $MK_EDITOR;
		if ($MK_EDITOR == "HTML") {
            		
			$langedit =  strtolower($MK_LANG);
			$langedit = substr($langedit, 0, 2);
			if (!is_file("/mkportal/editor/jscripts/tiny_mce/langs/{$langedit}.js")) {
        		$langedit = "ru";
        	}
			$editorpath = "/mkportal/editor/jscripts/tiny_mce/tiny_mce.js";
//edited by alez in C1.2.2	(edit by Kimi: removed from tinyMCE the "styleselect" that wasnt working and was useless)		
      $out = "
			<!-- tinyMCE -->
			<script language=\"javascript\" type=\"text/javascript\" src=\"$editorpath\"></script>
 			<script language=\"javascript\" type=\"text/javascript\">
			tinyMCE.init({
			relative_urls : false,
			remove_script_host : false,			
			mode : \"specific_textareas\",
			editor_deselector : \"mceNoEditor\",
			force_p_newlines : false,
            apply_source_formatting : false,
			theme : \"advanced\",
		plugins : \"images,safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template\",

		// Theme options
		theme_advanced_buttons1 : \"images,save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,formatselect,fontselect,fontsizeselect\",
		theme_advanced_buttons2 : \"cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor\",
		theme_advanced_buttons3 : \"tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen\",
		theme_advanced_toolbar_location : \"top\",
		theme_advanced_toolbar_align : \"left\",
		theme_advanced_statusbar_location : \"bottom\",
		extended_valid_elements : \"hr[class|width|size|noshade]\",
		
    // Example content CSS (should be your site CSS)
		content_css : \"css/content.css\",		
		
    paste_use_dialog : false,
		language : \"$langedit\",
		theme_advanced_resizing : true,
		theme_advanced_resize_horizontal : false,
		theme_advanced_link_targets : \"_something=My somthing;_something2=My somthing2;_something3=My somthing3;\"
			});
		
			</script>
			<!-- /tinyMCE -->";
		}

			return $out;
		}

		// mod rusmkportal.ru
		function get_bbeditor($textarena = "") {
		global $mklib_board, $mklib;
		//$smile = "<iframe align=\"top\" src=\"index.php?ind=urlobox&amp;op=show_emoticons\" frameborder=\"0\" width=\"22%\" align=\"middle\" height=\"212\" scrolling=\"auto\"></iframe>";
		$smile = "<iframe src=\"$this->sitepath/index.php?ind=urlobox&amp;op=show_emoticons\" frameborder=\"0\" width=\"22%\" align=\"left\" height=\"212\" scrolling=\"auto\"></iframe>";
		if ($nosmile) {
			$smile = "";
		}
		if (!$textarena) {
			$textarena ="ta";
		}
		$editorpath = $this->sitepath."mkportal/editor/bbeditor/ed.js";
		return "
		<script type=\"text/javascript\">
        <!--
            var text_enter_url      = \"{$this->lang['editor_url_info']}\";
            var text_enter_url_name = \"{$this->lang['editor_url_name']}\";
            var text_enter_image    = \"{$this->lang['editor_image_info']}\";
            var text_enter_email    = \"{$this->lang['editor_email_info']}\";
            var error_no_url        = \"{$this->lang['editor_error_no_url']}\";
            var error_no_title      = \"{$this->lang['editor_error_no_title']}\";
            var error_no_email      = \"{$this->lang['editor_error_no_email']}\";
            var list_prompt         = \"{$this->lang['editor_list_info']}\";
		    var text_enter_size       = \"{$this->lang['editor_flashsize']}\";
            var text_enter_flash       = \"{$this->lang['editor_flashlink']}\";
		    var text_enter_youtube       = \"{$this->lang['editor_tubelinks']}\";
		    var text_enter_music       = \"{$this->lang['editor_mp3links']}\"; 
		    var text_enter_video       = \"{$this->lang['editor_videolinks']}\"; 
	
        //-->
        </script>
      <script type=\"text/javascript\" src=\"$editorpath\"></script>  
		
	<img class=\"button\" src=\"" .  "/mkportal/editor/bbeditor/images/bold.png\" name=\"btnBold\" onClick=\"doAddTags('[b]','[/b]','$textarena')\">
	<img class=\"button\" src=\"" .  "/mkportal/editor/bbeditor/images/italic.png\" name=\"btnItalic\" onClick=\"doAddTags('[i]','[/i]','$textarena')\">
	<img class=\"button\" src=\"" .  "/mkportal/editor/bbeditor/images/underline.png\" name=\"btnUnderline\" onClick=\"doAddTags('[u]','[/u]','$textarena')\">
	<img class=\"button\" src=\"" .  "/mkportal/editor/bbeditor/images/strike.png\" name=\"btnUnderline\" onClick=\"doAddTags('[s]','[/s]','$textarena')\">
	<img class=\"button\" src=\"" .  "/mkportal/editor/bbeditor/images/hr.png\" name=\"hr\" onClick=\"doAddTags('[hr]','','$textarena')\">
	<img  border=\"0\" src=\"" .  "/mkportal/editor/bbeditor/images/to_down_pipe.gif\">
	<img class=\"button\" src=\"" .  "/mkportal/editor/bbeditor/images/align_left.png\" name=\"btnUnderline\" onClick=\"doAddTags('[LEFT]','[/LEFT]','$textarena')\">
	<img class=\"button\" src=\"" .  "/mkportal/editor/bbeditor/images/align_center.png\" name=\"btnUnderline\" onClick=\"doAddTags('[CENTER]','[/CENTER]','$textarena')\">
	<img class=\"button\" src=\"" .  "/mkportal/editor/bbeditor/images/align_right.png\" name=\"btnUnderline\" onClick=\"doAddTags('[RIGHT]','[/RIGHT]','$textarena')\">
	<img  border=\"0\" src=\"" .  "/mkportal/editor/bbeditor/images/to_down_pipe.gif\">
	<img class=\"button\" src=\"" .  "/mkportal/editor/bbeditor/images/link.png\" name=\"btnLink\" onClick=\"doURL('$textarena')\">
	<img class=\"button\" src=\"" .  "/mkportal/editor/bbeditor/images/picture.png\" name=\"btnPicture\" onClick=\"doImage('$textarena')\">
	<img class=\"button\" src=\"" .  "/mkportal/editor/bbeditor/images/email.png\" name=\"email\" onclick=\"tag_email('$textarena')\">
	<img  border=\"0\" src=\"" .  "/mkportal/editor/bbeditor/images/to_down_pipe.gif\">
	<img class=\"button\" src=\"" .  "/mkportal/editor/bbeditor/images/quote.png\" name=\"btnQuote\" onClick=\"doAddTags('[quote]','[/quote]','$textarena')\">
	<img class=\"button\" src=\"" .  "/mkportal/editor/bbeditor/images/code.png\" name=\"btnCode\" onClick=\"doAddTags('[code]','[/code]','$textarena')\">
	<img class=\"button\" src=\"" .  "/mkportal/editor/bbeditor/images/hide.png\" name=\"btnCode\" onClick=\"doAddTags('[hide]','[/hide]','$textarena')\">
<br/>
	<img class=\"button\" src=\"" .  "/mkportal/editor/bbeditor/images/zoom_in.png\" name=\"zoom\" onClick=\"RowsTextarea('$textarena', 1)\">
	<img class=\"button\" src=\"" .  "/mkportal/editor/bbeditor/images/zoom_out.png\" name=\"zoom\" onClick=\"RowsTextarea('$textarena', 0)\">
	<img  border=\"0\" src=\"" .  "/mkportal/editor/bbeditor/images/to_down_pipe.gif\">
	<select class=\"button\" name=\"fontsize{$textarena}\" onchange=\"doAddTags('[SIZE=' + this.form.fontsize{$textarena}.options[this.form.fontsize{$textarena}.selectedIndex].value + ']', '[/SIZE]', '$textarena')\">
       <option value=\"0\">{$this->lang['bb_size']}</option>
       <option value=\"1\">1</option>
	   <option value=\"2\">2</option>
	   <option value=\"4\">4</option>
       <option value=\"7\">7</option>
	   <option value=\"10\">10</option>
       <option value=\"14\">14</option>
    </select>
    <select class=\"button\" name=\"ffont{$textarena}\" onchange=\"doAddTags('[FONT=' + this.form.ffont{$textarena}.options[this.form.ffont{$textarena}.selectedIndex].value + ']', '[/FONT]', '$textarena')\">
       <option value=\"0\">{$this->lang['bb_Font']}</option>
       <option value=\"Arial\" style=\"font-family:Arial\">Arial</option>
       <option value=\"Times\" style=\"font-family:Times\">Times</option>
       <option value=\"Courier\" style=\"font-family:Courier\">Courier</option>
       <option value=\"Impact\" style=\"font-family:Impact\">Impact</option>
       <option value=\"Geneva\" style=\"font-family:Geneva\">Geneva</option>
       <option value=\"Optima\" style=\"font-family:Optima\">Optima</option>
       </select>
		<select class=\"button\" name=\"fcolor{$textarena}\" onchange=\"doAddTags('[COLOR=' + this.form.fcolor{$textarena}.options[this.form.fcolor{$textarena}.selectedIndex].value + ']', '[/COLOR]', '$textarena')\">
       <option value=\"0\">{$this->lang['bb_color']}</option>
       <option style=\"color: silver;\" value=\"silver\">{$this->lang['bb_color']}</option>
       <option style=\"color: gray;\" value=\"gray\">{$this->lang['bb_color']}</option>
        <option style=\"color: white;\" value=\"white\">{$this->lang['bb_color']}</option>
        <option style=\"color: maroon;\" value=\"maroon\">{$this->lang['bb_color']}</option>
        <option style=\"color: orange;\" value=\"orange\">{$this->lang['bb_color']}</option>
        <option style=\"color: orangered;\" value=\"orangered\">{$this->lang['bb_color']}</option>
        <option style=\"color: red;\" value=\"red\">{$this->lang['bb_color']}</option>
        <option style=\"color: purple;\" value=\"purple\">{$this->lang['bb_color']}</option>
        <option style=\"color: fuchsia;\" value=\"fuchsia\">{$this->lang['bb_color']}</option>
        <option style=\"color: green;\" value=\"green\">{$this->lang['bb_color']}</option>
        <option style=\"color: lime;\" value=\"lime\">{$this->lang['bb_color']}</option>
        <option style=\"color: olive;\" value=\"olive\">{$this->lang['bb_color']}</option>
        <option style=\"color: yellow;\" value=\"yellow\">{$this->lang['bb_color']}</option>
        <option style=\"color: navy;\" value=\"navy\">{$this->lang['bb_color']}</option>
      <option style=\"color: blue;\" value=\"blue\">{$this->lang['bb_color']}</option>
      <option style=\"color: teal;\" value=\"teal\">{$this->lang['bb_color']}</option>
      <option style=\"color: aqua;\" value=\"aqua\">{$this->lang['bb_color']}</option>
       </select>
		<img  border=\"0\" src=\"" .  "/mkportal/editor/bbeditor/images/to_down_pipe.gif\">
		<img class=\"button\" src=\"" .  "/mkportal/editor/bbeditor/images/flash.png\" name=\"btnCode\" onClick=\"flash('$textarena')\">
	    <img class=\"button\" src=\"" .  "/mkportal/editor/bbeditor/images/tube.gif\" name=\"btnCode\" onClick=\"youtube('$textarena')\">
		<img class=\"button\" src=\"" .  "/mkportal/editor/bbeditor/images/music.png\" name=\"btnCode\" onClick=\"music('$textarena')\">
	    <img class=\"button\" src=\"" .  "/mkportal/editor/bbeditor/images/film.png\" name=\"btnCode\" onClick=\"video('$textarena')\">
		<a id=\"cont\" OnClick=\"SwitchMenu('sm')\"><img class=\"button\" src=\"" .  "/mkportal/editor/bbeditor/images/smaile.gif\"></a>
	  <div id=\"sm\" style=\"display:none;\">
<iframe src=\"$mklib->siteurl/index.php?ind=urlobox&op=show_emoticons\" width=\"100\" height=\"200\" scrolling=\"auto\"></iframe>
</div>
	
		
 ";
		}
		function get_commentbbeditor($textarena = "") {
		global $mklib_board, $mklib;
		//$smile = "<iframe align=\"top\" src=\"index.php?ind=urlobox&amp;op=show_emoticons\" frameborder=\"0\" width=\"22%\" align=\"middle\" height=\"212\" scrolling=\"auto\"></iframe>";
		$smile = "<iframe src=\"/index.php?ind=urlobox&amp;op=show_emoticons\" frameborder=\"0\" width=\"22%\" align=\"left\" height=\"212\" scrolling=\"auto\"></iframe>";
		if ($nosmile) {
			$smile = "";
		}
		if (!$textarena) {
			$textarena ="ta";
		}
		$editorpath = "/mkportal/editor/bbeditor/ed.js";
		return "
		<script type=\"text/javascript\">
        <!--
            var text_enter_url      = \"{$this->lang['editor_url_info']}\";
            var text_enter_url_name = \"{$this->lang['editor_url_name']}\";
            var text_enter_image    = \"{$this->lang['editor_image_info']}\";
            var text_enter_email    = \"{$this->lang['editor_email_info']}\";
            var error_no_url        = \"{$this->lang['editor_error_no_url']}\";
            var error_no_title      = \"{$this->lang['editor_error_no_title']}\";
            var error_no_email      = \"{$this->lang['editor_error_no_email']}\";
            var list_prompt         = \"{$this->lang['editor_list_info']}\";
		    var text_enter_size       = \"{$this->lang['editor_flashsize']}\";
            var text_enter_flash       = \"{$this->lang['editor_flashlink']}\";
		    var text_enter_youtube       = \"{$this->lang['editor_tubelinks']}\";
		    var text_enter_music       = \"{$this->lang['editor_mp3links']}\"; 
		    var text_enter_video       = \"{$this->lang['editor_videolinks']}\"; 
	
        //-->
        </script>
      <script type=\"text/javascript\" src=\"$editorpath\"></script>  
		
	<img class=\"button\" src=\"" .  "/mkportal/editor/bbeditor/images/bold.png\" name=\"btnBold\" onClick=\"doAddTags('[b]','[/b]','$textarena')\">
	<img class=\"button\" src=\"" .  "/mkportal/editor/bbeditor/images/italic.png\" name=\"btnItalic\" onClick=\"doAddTags('[i]','[/i]','$textarena')\">
	<img class=\"button\" src=\"" .  "/mkportal/editor/bbeditor/images/underline.png\" name=\"btnUnderline\" onClick=\"doAddTags('[u]','[/u]','$textarena')\">
	<img class=\"button\" src=\"" .  "/mkportal/editor/bbeditor/images/strike.png\" name=\"btnUnderline\" onClick=\"doAddTags('[s]','[/s]','$textarena')\">
	<img class=\"button\" src=\"" .  "/mkportal/editor/bbeditor/images/hr.png\" name=\"hr\" onClick=\"doAddTags('[hr]','','$textarena')\">
	<img  border=\"0\" src=\"" .  "/mkportal/editor/bbeditor/images/to_down_pipe.gif\">
	<img class=\"button\" src=\"" .  "/mkportal/editor/bbeditor/images/align_left.png\" name=\"btnUnderline\" onClick=\"doAddTags('[LEFT]','[/LEFT]','$textarena')\">
	<img class=\"button\" src=\"" .  "/mkportal/editor/bbeditor/images/align_center.png\" name=\"btnUnderline\" onClick=\"doAddTags('[CENTER]','[/CENTER]','$textarena')\">
	<img class=\"button\" src=\"" .  "/mkportal/editor/bbeditor/images/align_right.png\" name=\"btnUnderline\" onClick=\"doAddTags('[RIGHT]','[/RIGHT]','$textarena')\">
	<img  border=\"0\" src=\"" .  "/mkportal/editor/bbeditor/images/to_down_pipe.gif\">
	<img class=\"button\" src=\"" .  "/mkportal/editor/bbeditor/images/link.png\" name=\"btnLink\" onClick=\"doURL('$textarena')\">
	<img class=\"button\" src=\"" .  "/mkportal/editor/bbeditor/images/picture.png\" name=\"btnPicture\" onClick=\"doImage('$textarena')\">
	<img class=\"button\" src=\"" .  "/mkportal/editor/bbeditor/images/email.png\" name=\"email\" onclick=\"tag_email('$textarena')\">
	<img  border=\"0\" src=\"" .  "/mkportal/editor/bbeditor/images/to_down_pipe.gif\">
	<img class=\"button\" src=\"" .  "/mkportal/editor/bbeditor/images/quote.png\" name=\"btnQuote\" onClick=\"doAddTags('[quote]','[/quote]','$textarena')\">
	<img class=\"button\" src=\"" .  "/mkportal/editor/bbeditor/images/code.png\" name=\"btnCode\" onClick=\"doAddTags('[code]','[/code]','$textarena')\">
	<img class=\"button\" src=\"" .  "/mkportal/editor/bbeditor/images/hide.png\" name=\"btnCode\" onClick=\"doAddTags('[hide]','[/hide]','$textarena')\">
	<img class=\"button\" src=\"" .  "/mkportal/editor/bbeditor/images/zoom_in.png\" name=\"zoom\" onClick=\"RowsTextarea('$textarena', 1)\">
	<img class=\"button\" src=\"" .  "/mkportal/editor/bbeditor/images/zoom_out.png\" name=\"zoom\" onClick=\"RowsTextarea('$textarena', 0)\">
	<img  border=\"0\" src=\"" .  "/mkportal/editor/bbeditor/images/to_down_pipe.gif\">

		<a id=\"cont\" OnClick=\"SwitchMenu('sm')\"><img class=\"button\" src=\"" .  "/mkportal/editor/bbeditor/images/smaile.gif\"></a>
	  <div id=\"sm\" style=\"display:none;\">
<iframe src=\"$mklib->siteurl/index.php?ind=urlobox&op=show_emoticons\" width=\"100\" height=\"200\" scrolling=\"auto\"></iframe>
</div>
	
		
 ";
		}
		// end
// mod rusmkportal.ru
		function decode_bb($txt)
		{

			//Used for global shout bar filtering
			if (is_array($txt)) {
				$urloloc = intval($txt['1']);
				$txt = $txt['0'];
			}
			
			global $Skin, $mkportals;
			$pos = "";
			$mk_sub = "";
			$content = "";
			$author = "";
			//$txt = nl2br($txt);
			while ( preg_match( "#\[size=([^\]]+)\](.+?)\[/size\]#ies", $txt ) ) {
				$txt = preg_replace( "#\[size=([^\]]+)\](.+?)\[/size\]#ies"    , "\$this->parse_bbfont(array('s'=>'size','1'=>'\\1','2'=>'\\2'))", $txt );
			}
			while ( preg_match( "#\[font=([^\]]+)\](.*?)\[/font\]#ies", $txt ) ) {
				$txt = preg_replace( "#\[font=([^\]]+)\](.*?)\[/font\]#ies"    , "\$this->parse_bbfont(array('s'=>'font','1'=>'\\1','2'=>'\\2'))", $txt );
			}
			while( preg_match( "#\[color=([^\]]+)\](.+?)\[/color\]#ies", $txt ) ) {
				$txt = preg_replace( "#\[color=([^\]]+)\](.+?)\[/color\]#ies"  , "\$this->parse_bbfont(array('s'=>'col' ,'1'=>'\\1','2'=>'\\2'))", $txt );
			}
			$txt = preg_replace( "#\[b\](.+?)\[/b\]#is", "<b>\\1</b>", $txt );
			$txt = preg_replace( "#\[i\](.+?)\[/i\]#is", "<i>\\1</i>", $txt );
			$txt = preg_replace( "#\[u\](.+?)\[/u\]#is", "<u>\\1</u>", $txt );
			$txt = preg_replace( "#\[s\](.+?)\[/s\]#is", "<s>\\1</s>", $txt );
			$txt = preg_replace( "#\[left\](.+?)\[/left\]#is", "<left>\\1</left>", $txt );
			$txt = preg_replace( "#\[center\](.+?)\[/center\]#is", "<center>\\1</center>", $txt );
			$txt = preg_replace( "#\[right\](.+?)\[/right\]#is", "<p align=\"right\">\\1</p>", $txt );
			$txt = preg_replace( "#\[email\](\S+?)\[/email\]#i"                                                                , "<a href=\"mailto:\\1\">\\1</a>", $txt );
			$txt = preg_replace( "#\[email\s*=\s*\&quot\;([\.\w\-]+\@[\.\w\-]+\.[\.\w\-]+)\s*\&quot\;\s*\](.*?)\[\/email\]#i"  , "<a href=\"mailto:\\1\">\\2</a>", $txt );
			$txt = preg_replace( "#\[email\s*=\s*([\.\w\-]+\@[\.\w\-]+\.[\w\-]+)\s*\](.*?)\[\/email\]#i"                       , "<a href=\"mailto:\\1\">\\2</a>", $txt );
			//$txt = preg_replace( "#\[url=(.+?)\](.+)\[\/url\]#i",'<a href="\\1" target="_blank">\\2</a>',$txt);
			//$txt = preg_replace('/\[URL\](.+?)\[\/URL\]/','<a href="\\1">\\1</a>',$txt);
			$txt = preg_replace( "#\[url\](\S+?)\[/url\]#ie", "\$this->bb_build_url(array('1' => '\\1', '2' => '\\1'))", $txt );
			$txt = preg_replace( "#\[url\s*=\s*\&quot\;\s*(\S+?)\s*\&quot\;\s*\](.*?)\[\/url\]#ie", "\$this->bb_build_url(array('1' => '\\1', '2' => '\\2'))", $txt );
			$txt = preg_replace( "#\[url\s*=\s*(\S+?)\s*\](.*?)\[\/url\]#ie", "\$this->bb_build_url(array('1' => '\\1', '2' => '\\2'))", $txt );
           	$txt = preg_replace( "#\[code\](.+?)\[/code\]#is", "<div class=\"codemain\">\\1</div>", $txt );
           	$txt = preg_replace( "#\[hr\]#is", "<hr>", $txt );
			$txt = preg_replace('/\[img\](.+?)\[\/img\]/is','<img src="\\1" border="0" alt="" />',$txt);
			$txt = preg_replace( "#\[flash=([^\]]+)\](.+?)\[/flash\]#ies", "\$this->bb_build_flash('\\1', '\\2')", $txt );
			$txt = preg_replace( "#\[youtube=([^\]]+)\]#ies", "\$this->bb_build_youtube('\\1')", $txt );
			$txt = preg_replace( "#\[music\s*=\s*(\S.+?)\s*\]#ie", "\$this->bb_build_audio('\\1')", $txt );
			$txt = preg_replace( "#\[video\s*=\s*(\S.+?)\s*\]#ie", "\$this->bb_build_video('\\1')", $txt );
			if ($urloloc == '1') { //global shoutbox - limit to one quote			
				$txt = preg_replace( "#\[quote(=*?)([^=]*?)\](.+?)\[/quote\]#is", $Skin->view_quote("\\3", "\\2"), $txt, 1 );
				$txt = preg_replace( "#\[quote(=*?)([^=]*?)\](.+?)\[/quote\]#is", ' ', $txt);
			} else {
				$txt = preg_replace( "#\[quote(=*?)([^=]*?)\](.+?)\[/quote\]#is", $Skin->view_quote("\\3", "\\2"), $txt);
			}
			if ($mkportals->member['id'] < 1) {
                                                $txt = preg_replace( "#\[hide\](.+?)\[/hide\]#is", "{$this->lang['bb_hides']}", $txt );
                                    }
                                    else {
                                    $txt = preg_replace( "#\[hide\](.+?)\[/hide\]#is", "\\1", $txt );
                                    }
			

			return stripslashes($txt);
		}
		function bb_build_video($url) {
		
		$option = explode( "|", trim( $url ) );
		
		$url = $this->clear_url( urldecode( $option[0] ) );
		
		$type = explode( ".", $url );
		$type = strtolower( end( $type ) );
		
		if( preg_match( "/[?&;%<\[\]]/", $url ) ) {
			
			return "[video=" . $url . "]";
		
		}
		
		if( $option[1] != "" ) {
			
			$option[1] = htmlspecialchars( strip_tags( stripslashes( $option[1] ) ), ENT_QUOTES );
			$decode_url = $url . "|" . $option[1];
		
		} else
			$decode_url = $url;
		
		if( $type == "flv" or $type == "mp4" or $type == "m4v" or $type == "m4a") {
			$id_player = md5( microtime() );
			
			$list = explode( ",", $url );
			$url = array ();
			
			foreach ( $list as $value ) {
				
				$url[] = "{url:'" . trim( $value ) . "?source=1'}";
			
			}
			
			$url = implode( ", ", $url );
			
			return "<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0\" width=\"425\" height=\"325\" id=\"Player-{$id_player}\">
				<param name=\"movie\" value=\"" .  "/mkportal/editor/flashplayer/flv_player.swf?config={embedded:true,playList:[{overlayId:'play',url:'{$option[1]}'},{$url}],initialScale:'fit',showMenu:false,controlBarGloss:'low',controlBarBackgroundColor:0,controlsOverVideo:'ease',startingBufferLength:1,showOnLoadBegin:true,loop:false,autoRewind:true,autoBuffering:false,autoPlay:false{$watermark}}\" />
				<param name=\"allowFullScreen\" value=\"true\" />
				<param name=\"quality\" value=\"high\" />
				<param name=\"bgcolor\" value=\"#000000\" />
				<param name=\"wmode\" value=\"transparent\" />
				<embed src=\"" .  "/mkportal/editor/flashplayer/flv_player.swf?config={embedded:true,playList:[{overlayId:'play',url:'{$option[1]}'},{$url}],initialScale:'fit',showMenu:false,controlBarGloss:'low',controlBarBackgroundColor:0,controlsOverVideo:'ease',startingBufferLength:1,showOnLoadBegin:true,loop:false,autoRewind:true,autoBuffering:false,autoPlay:false{$watermark}}\" quality=\"high\" bgcolor=\"#000000\" wmode=\"transparent\" allowFullScreen=\"true\" width=\"425\" height=\"325\" align=\"middle\" type=\"application/x-shockwave-flash\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" />
				</object>";
		
		} elseif( $type == "avi" or $type == "divx" ) {
			
			return "<object classid=\"clsid:67DABFBF-D0AB-41fa-9C46-CC0F21721616\" width=\"425\" height=\"341\" codebase=\"http://go.divx.com/plugin/DivXBrowserPlugin.cab\">
				<param name=\"custommode\" value=\"none\" />
				<param name=\"mode\" value=\"zero\" />
				<param name=\"autoPlay\" value=\"false\" />
				<param name=\"src\" value=\"{$url}\" />
				<param name=\"previewImage\" value=\"{$option[1]}\" />
				<embed type=\"video/divx\" src=\"{$url}\" custommode=\"none\" width=\"425\" height=\"341\" mode=\"zero\"  autoPlay=\"false\" previewImage=\"{$option[1]}\" pluginspage=\"http://go.divx.com/plugin/download/\">
				</embed>
				</object>";
		
		} else {
			
			return "<object id=\"mediaPlayer\" width=\"380\" height=\"310\" classid=\"CLSID:6BF52A52-394A-11d3-B153-00C04F79FAA6\" standby=\"Loading Microsoft Windows Media Player components...\" type=\"application/x-oleobject\">
				<param name=\"url\" VALUE=\"{$url}\" />
				<param name=\"autoStart\" VALUE=\"false\" />
				<param name=\"showControls\" VALUE=\"true\" />
				<param name=\"TransparentatStart\" VALUE=\"false\" />
				<param name=\"AnimationatStart\" VALUE=\"true\" />
				<param name=\"StretchToFit\" VALUE=\"true\" />
				<embed pluginspage=\"http://www.microsoft.com/Windows/Downloads/Contents/MediaPlayer/\" src=\"{$url}\" width=\"380\" height=\"310\" type=\"application/x-mplayer2\" autorewind=\"1\" showstatusbar=\"1\" showcontrols=\"1\" autostart=\"0\" allowchangedisplaysize=\"1\" volume=\"70\" stretchtofit=\"1\" />
				</object>";
		}
	
	}
		function bb_build_audio($url) {
		
		
		$url = $this->clear_url( urldecode( $url ) );
		
		if( $url == "" ) return;
		
		if( preg_match( "/[?&;%<\[\]]/", $url ) ) {
			
			return "[music=" . $url . "]";
		}
		
		$list = explode( ",", $url );
		$url = array ();
		
		foreach ( $list as $value ) {
			
			$url[] = "{url:'" . trim( $value ) . "'}";
		
		}
		
		$url = implode( ", ", $url );
		$list = implode( ",", $list );
		
		return "<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" \"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0\" width=\"425\" height=\"45\">
				<param name=\"movie\" value=\"" .$this->sitepath. "mkportal/editor/flashplayer/flv_player.swf?config={embedded:true,playList:[{$url}],initialScale:'fit',showMenu:false,backgroundColor:'-1',controlsOverVideo:'locked',controlBarGloss:'low',controlBarBackgroundColor:0,showFullScreenButton:false,usePlayOverlay:false,showOnLoadBegin:false,loop:false,autoRewind:true,autoBuffering:false,autoPlay:false}\" />
				<param name=\"allowFullScreen\" value=\"true\" />
				<param name=\"quality\" value=\"high\" />
				<param name=\"bgcolor\" value=\"#000000\" />
				<param name=\"wmode\" value=\"transparent\" />
				<embed src=\"" .$this->sitepath. "mkportal/editor/flashplayer/flv_player.swf?config={embedded:true,playList:[{$url}],initialScale:'fit',showMenu:false,backgroundColor:'-1',controlsOverVideo:'locked',controlBarGloss:'low',controlBarBackgroundColor:0,showFullScreenButton:false,usePlayOverlay:false,showOnLoadBegin:false,loop:false,autoRewind:true,autoBuffering:false,autoPlay:false}\" quality=\"high\" bgcolor=\"#000000\" wmode=\"transparent\" allowFullScreen=\"true\" width=\"425\" height=\"45\" align=\"middle\" type=\"application/x-shockwave-flash\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" />
				</object>";
	
	}
    function bb_build_flash($size, $url) {

		$size = explode(",", $size);

		$width = trim(intval($size[0]));
		$height = trim(intval($size[1]));

		if (!$width OR !$height) return "[flash=".implode(",",$size)."]".$url."[/flash]";

		$url = $this->clear_url( urldecode( $url ) );
		
		if( $url == "" ) return;

		$type = explode( ".", $url );
		$type = strtolower( end( $type ) );
		
		if ( strtolower($type) != "swf" )
		{
			return "[flash=".implode(",",$size)."]".$url."[/flash]";
		}

		return "<object classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' width='$width' height='$height'><param name='movie' value='$url'><param name='wmode' value='transparent' /><param name='play' value='true'><param name='loop' value='true'><param name='quality' value='high'><param name='allowscriptaccess' value='never'><embed AllowScriptAccess='never' src='$url' width='$width' height='$height' play='true' loop='true' quality='high' wmode='transparent'></embed></object>";


	}
		function bb_build_youtube($url) {	

		$url = $this->clear_url( urldecode( $url ) );
		$url = str_replace("&amp;","&", $url );
		
		if( $url == "" ) return;

		$source = @parse_url ( $url );

		$source['host'] = str_replace( "www.", "", strtolower($source['host']) );

		if ($source['host'] != "youtube.com" AND $source['host'] != "rutube.ru") return "[youtube=".$url."]";

		$a = explode('&', $source['query']);
		$i = 0;

		while ($i < count($a)) {
		    $b = split('=', $a[$i]);
		    if ($b[0] == "v") $video_link = $b[1];
		    $i++;
		}

		if ($source['host'] == "youtube.com")
			return '<object width="425" height="344"><param name="movie" value="http://www.youtube.com/v/'.$video_link.'&hl=ru&fs=1"></param><param name="wmode" value="transparent" /><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/'.$video_link.'&hl=ru&fs=1" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" wmode="transparent" width="425" height="344"></embed></object>';
		else
			return '<OBJECT width="425" height="344"><PARAM name="movie" value="http://video.rutube.ru/'.$video_link.'"></PARAM><param name="wmode" value="transparent" /></PARAM><PARAM name="allowFullScreen" value="true"></PARAM><EMBED src="http://video.rutube.ru/'.$video_link.'" type="application/x-shockwave-flash" wmode="transparent" width="425" height="344" allowFullScreen="true" ></EMBED></OBJECT>';

	}
	function clear_url($url) {
		
		$url = strip_tags( trim( stripslashes( $url ) ) );
		
		$url = str_replace( '\"', '"', $url );
		
		if( ! $this->safe_mode or $this->wysiwyg ) {
			
			$url = htmlspecialchars( $url, ENT_QUOTES );
		
		}
		
		$url = str_replace( "document.cookie", "", $url );
		$url = str_replace( " ", "%20", $url );
		$url = str_replace( "'", "", $url );
		$url = str_replace( '"', "", $url );
		$url = str_replace( "<", "&#60;", $url );
		$url = str_replace( ">", "&#62;", $url );
		$url = preg_replace( "/javascript:/i", "", $url );
		$url = preg_replace( "/data:/i", "", $url );
		
		return $url;
	
	}
		// end

		
		function bb_build_url($url=array()) {
			return "<a href=\"".$url['1']."\" target=\"_blank\">".$url['2']."</a>";
		}
		function parse_bbfont($fattrbb) {
			if (!is_array($fattrbb)) return "";
			if ( preg_match( "/;/", $fattrbb['1'] ) ) {
				$attr = explode( ";", $fattrbb['1'] );
				$fattrbb['1'] = $attr[0];
			}
			$fattrbb['1'] = preg_replace( "/[&\(\)\.\%]/", "", $fattrbb['1'] );
			if ($fattrbb['s'] == 'size') {
				$fattrbb['1'] = ($fattrbb['1'] <= 14) ? ($fattrbb['1'] + 7) : 1;
				return "<span style=\"font-size:".$fattrbb['1']."pt;line-height:100%\">".$fattrbb['2']."</span>";
			}
			else if ($fattrbb['s'] == 'col') {
			return "<span style=\"color:".$fattrbb['1']."\">".$fattrbb['2']."</span>";
			}
			else if ($fattrbb['s'] == 'font') {
			return "<span style=\"font-family:".$fattrbb['1']."\">".$fattrbb['2']."</span>";
			}
		}

	function load_lang($file_lang) {
		global $mkportals;
		$mlang = $this->mklang;
		if ($mkportals->member['mk_lang']) {	
			$dir = @opendir($this->sitepath."mkportal/lang/");
			while (($dirt = readdir($dir)) !== false) {
				$mkl = strtolower (substr($dirt, 0, 3));		
				$bol = strtolower (substr($mkportals->member['mk_lang'], 0, 3));
				if ($mkl == $bol && $dirt != ".htaccess" && $dirt != "htaccess" && $dirt != "index.html" && $dirt != "English_Reference") {
					$mlang = $this->sitepath."mkportal/lang/".$dirt;
				}	
			}	
			closedir($dir);
		}

		require "$mlang/$file_lang";
		foreach ($langmk as $k => $v) {
        		$this->lang[$k] = stripslashes($v);
        	}
	}
	
	function watermark($filename, $filedest="") {
		
		$POSITION = $this->config['watermark_pos'];
		$LEVEL = $this->config['watermark_level'];
		if (!$filedest) {
			$filedest = $filename;
		} 
		$watermarkimage = $this->sitepath."mkportal/modules/gallery/wt.png";
		if (!function_exists('imagecopymerge') || !file_exists($watermarkimage)) {
			return;
		}
		$lst=GetImageSize($filename);
 		$image_width=$lst[0];
 		$image_height=$lst[1];
 		$image_format=$lst[2];

 		if ($image_format==2) {
  			$old_image=imagecreatefromjpeg($filename);
 		} elseif ($image_format==3) {
  			$old_image=imagecreatefrompng($filename);
 		} else {
   			return;
 		}


 		$lst2=GetImageSize($watermarkimage);
 		$image2_width=$lst2[0];
 		$image2_height=$lst2[1];
 		$image2_format=$lst2[2];

		 if ($image2_format==2 && function_exists('imagecreatefromjpeg')) {
  			$wt_image=imagecreatefromjpeg($watermarkimage);
 		} elseif ($image2_format==3 && function_exists('imagecreatefrompng')) {
  			$wt_image=imagecreatefrompng($watermarkimage);
 		}

  		if (!$wt_image) {
  			return;
  		}

   		$wt_y= "10";
   		$wt_x=$image_width-$image2_width-10;
		
		if ($POSITION == 1) {
			$wt_y=(int)($image_height/2-$image2_height/2);
			$wt_x=(int)($image_width/2-$image2_width/2);
		}
		if ($POSITION == 2) {
			$wt_y=$image_height-$image2_height-10;
			$wt_x=$image_width-$image2_width-10;
		}
		
   		imagecopymerge($old_image, $wt_image, $wt_x, $wt_y, 0, 0, $image2_width, $image2_height, $LEVEL);
  


 		if ($image_format==2) {
  			//Header("Content-Type: image/jpeg");
  			imageJpeg($old_image, $filedest);
 		}
 		if ($image_format==3) {
  			imagePng($old_image, $filedest);
 		}
		# cleaning cache
		imageDestroy($old_image);
		imageDestroy($wt_image);

	}
	function checklinkperm($url) {
		global $mkportals;
		$perm = 0;
		if (stristr($url, 'ind=blog')) {
			if ($this->config['mod_blog']) {return TRUE;}
			if(!$mkportals->member['g_access_cp'] && !$this->member['g_access_blog']) {return TRUE;}
			
		}
		if (stristr($url, 'ind=gallery')) {
			if ($this->config['mod_gallery']) {return TRUE;}
			if(!$mkportals->member['g_access_cp'] && !$this->member['g_access_gallery']) {return TRUE;}
			
		}
		if (stristr($url, 'ind=urlobox')) {
			if ($this->config['mod_urlobox']) {return TRUE;}
			if(!$mkportals->member['g_access_cp'] && !$this->member['g_access_urlobox']) {return TRUE;}
			
		}
		if (stristr($url, 'ind=downloads')) {
			if ($this->config['mod_downloads']) {return TRUE;}
			if(!$mkportals->member['g_access_cp'] && !$this->member['g_access_download']) {return TRUE;}
			
		}
		if (stristr($url, 'ind=news')) {
			if ($this->config['mod_news']) {return TRUE;}	
		}
		if (stristr($url, 'ind=topsite')) {
			if ($this->config['mod_topsite']) {return TRUE;}
			if(!$mkportals->member['g_access_cp'] && !$this->member['g_access_topsite']) {return TRUE;}
			
		}
		if (stristr($url, 'ind=reviews')) {
			if ($this->config['mod_reviews']) {return TRUE;}
			if(!$mkportals->member['g_access_cp'] && !$this->member['g_access_reviews']) {return TRUE;}
			
		}
		if (stristr($url, 'ind=chat')) {
			if ($this->config['mod_chat']) {return TRUE;}
			if(!$mkportals->member['g_access_cp'] && !$this->member['g_access_chat']) {return TRUE;}
			
		}
		if (stristr($url, 'ind=quote')) {
			if ($this->config['mod_quote']) {return TRUE;}
			if(!$mkportals->member['g_access_cp'] && !$this->member['g_access_quote']) {return TRUE;}
			
		}
		if (stristr($url, 'ind=contact')) {
			if ($this->config['mod_contact']) {return TRUE;}
			if(!$mkportals->member['g_access_cp'] && !$this->member['g_access_contact']) {return TRUE;}
			
		}
		if (stristr($url, 'ind=recommend')) {
			if ($this->config['mod_recommend']) {return TRUE;}
			if(!$mkportals->member['g_access_cp'] && !$this->member['g_access_recommend']) {return TRUE;}
			
		}
		if (stristr($url, 'ind=poll')) {
			if ($this->config['mod_poll']) {return TRUE;}
			if(!$mkportals->member['g_access_cp'] && !$this->member['g_mod_poll']) {return TRUE;}
			
		}
		
		
		
		return FALSE;
	}
	
	
	function post_htmlspecialchars($text = "") {
        	$text = preg_replace("/&(?!#[0-9]+;)/s", '&amp;', $text);
        	$text = str_replace( "<", "&lt;"  , $text);
        	$text = str_replace( ">", "&gt;"  , $text);
        	$text = str_replace( '"', "&quot;", $text);
        	$text = str_replace( "'", "&#039;", $text);
        	return $text;
    	}
	function stimer() {
        	global $Stime;
        	$mtime = explode (' ', microtime ());
        	$Stime = $mtime[1] + $mtime[0];
    	}
	function etimer() {
        global $Stime;
        $mtime = explode (' ', microtime ());
        $endtime = $mtime[1] + $mtime[0];
        $ttime = round (($endtime - $Stime), 5);
        return $ttime;
    }

    
	//Help Popups
    	function helplink($helptext,$float='right') {	    
	global $MK_PATH;

		$content = '';
			if ($helptext) {
	    			$float = ($float == 'none') ? '' : "style=\"float: $float\" ";
	    			$content = "<img class=\"mkicon\" src=\"{$MK_PATH}mkportal/templates/default/images/help.png\" {$float}alt=\"(?)\"  onclick=\"ajax_showTooltip('{$MK_PATH}index.php?ind=ajax&amp;act=HelpToolTip&amp;helptext={$helptext}',this,1);return false;\" onmouseout=\"ajax_hideTooltip();\" />";
	    		}

		return $content;
    	}
   /* new function messages ok */ 	
function ok_page ($message) {
		global $mkportals, $DB, $Skin;

		$titlem = "";
  		$content ="
			<tr>
			  <td class=\"contents\">
			    <div align=\"center\" class=\"tabmain\"><br />
			      <img src=\"$this->template/images/info.png\" alt=\"\" /><br />
			      <span class=\"mkerror\">{$this->lang['ok_page']}</span><br /><br />
			      <b> {$this->lang['ok_page2']}<br />
			      $message</b><br /><br /><br /><br />
				<table>
				  <tr>
				    <td><a href=\"javascript:history.go(-1)\"><img src=\"$this->template/images/f2.gif\" alt=\"\" /></a>
				    </td>
				    <td><a href=\"javascript:history.go(-1)\">{$this->lang['back']}</a>
				    </td>
				  </tr>
				</table>
			    </div>
			  </td>
			</tr>
		";
		$blocks .= $Skin->view_block($titlem, $content);
  		$title = "{$this->lang['ok_page']}";
		$this->printpage("1", "1", $title, $blocks);
	}
	   /* new function messages ok */ 	
function Ajax_ok_page ($message) {
		global $mkportals, $DB, $Skin;

		$titlem = "";
  		$content1 ="
			<tr>
			  <td width=100% class=\"\">
			    <div align=\"center\" class=\"\"><br />
			      <img src=\"$this->template/images/info.png\" alt=\"\" /><br />
			      <span class=\"mkerror\">{$this->lang['ajax_message']}</span><br /><br />
			      <b>$message</b><br /><br /><br /><br />
			    </div>
			  </td>
			</tr>
		";
		echo $content1;
	}
	function Ajax_error_page ($message) {
		global $mkportals, $DB, $Skin;

		$titlem = "";
  		$content1 ="
			<tr>
			  <td width=100% class=\"contents\">
			    <div align=\"center\" class=\"\"><br />
			      <img src=\"$this->template/images/error.gif\" alt=\"\" /><br />
			      <span class=\"mkerror\">! {$this->lang['error']} !</span><br /><br />
			      <b> {$this->lang['error_pre']}<br />
			      $message</b><br /><br /><br /><br />
				<table>
				  <tr>
				    <td><a href=\"javascript:window.location=window.location;\"><img src=\"$this->template/images/f2.gif\" alt=\"\" /></a>
				    </td>
				    <td><a href=\"javascript:window.location=window.location;\">{$this->lang['back']}</a>
				    </td>
				  </tr>
				</table>
			    </div>
			  </td>
			</tr>
		";
		echo $content1;
	}
/*
function Rating 
made in rusmkpotral.ru
*/
function pullRating ($id, $modname, $rate, $trate) {
	global $DB, $mkportals, $mklib;
	$text = '';
	$trate = (intval($trate)) ? $trate : 1;
	$width = number_format($rate / $trate, 2) * 17;
	$title = substr($rate / $trate, 0, 4);
	$idauth = $mkportals->member['id'];
	

		return $text.'
		<div id="loading_'.$id.'">
			    <ul class="mbratings" id="rater_'.$id.'">
				<li class="cur-rating" style="width:'.$width.'px;" id="ul_'.$id.'"></li>
				<li><a onclick="rate(\'1\',\''.$id.'\',\''.$modname.'\'); return false;" href="#" title="'.$mklib->lang['ajax_rate'].''.$title.'/5('.$mklib->lang['ajax_rate_votes'].' '.$trate.')" class="rati1" ></a></li>
				<li><a onclick="rate(\'2\',\''.$id.'\',\''.$modname.'\'); return false;" href="#" title="'.$mklib->lang['ajax_rate'].''.$title.'/5('.$mklib->lang['ajax_rate_votes'].' '.$trate.')" class="rati2"></a></li>
				<li><a onclick="rate(\'3\',\''.$id.'\',\''.$modname.'\'); return false;" href="#" title="'.$mklib->lang['ajax_rate'].''.$title.'/5('.$mklib->lang['ajax_rate_votes'].' '.$trate.')" class="rati3"></a></li>
				<li><a onclick="rate(\'4\',\''.$id.'\',\''.$modname.'\'); return false;" href="#" title="'.$mklib->lang['ajax_rate'].''.$title.'/5('.$mklib->lang['ajax_rate_votes'].' '.$trate.')" class="rati4"></a></li>
				<li><a onclick="rate(\'5\',\''.$id.'\',\''.$modname.'\'); return false;" href="#" title="'.$mklib->lang['ajax_rate'].''.$title.'/5('.$mklib->lang['ajax_rate_votes'].' '.$trate.')" class="rati5"></a></li>
			</ul>
			</div>';
	
	
}
/* new function categor*/
function getcategor($parentid2,$title, $modname) {
   global $mkportals, $DB, $std, $print, $mklib, $Skin, $mklib_board;
   
    $sql = "SELECT id, title, parentid FROM mkp_categories WHERE id='$parentid2' AND module='$modname'";
    $result = $DB->query($sql);
    $row = $DB->fetch_row($result);
    $cid = $row[id];
    $ptitle = $row[title];
    $pparentid = $row[parentid];
    if ($ptitle!="") $title=$ptitle."/".$title;
    if ($pparentid!=0) {
        $title= $this->getcategor($pparentid,$title, $modname);
    }
    return $title;
}

/*Функция определения размера директории*/
function sizedirectory($directory) {
	
	if( ! is_dir( $directory ) ) return - 1;
	
	$size = 0;
	
	if( $DIR = opendir($directory) ) {
		
		while ( ($dirfile = readdir( $DIR )) !== false ) {
			
			if( @is_link( $directory . '/' . $dirfile ) || $dirfile == '.' || $dirfile == '..' ) continue;
			
			if( @is_file( $directory . '/' . $dirfile ) ) $size += filesize( $directory . '/' . $dirfile );
			
			else if( @is_dir( $directory . '/' . $dirfile ) ) {
				
				$dirSize = dirsize( $directory . '/' . $dirfile );
				if( $dirSize >= 0 ) $size += $dirSize;
				else return - 1;
			}
		}
		closedir( $DIR );
	}	
	return $size;
}
/*функция преоразования размера из байт*/
function formatsize($file_size) {
	if( $file_size >= 1073741824 ) {
		$file_size = round( $file_size / 1073741824 * 100 ) / 100 . " Gb";
	} elseif( $file_size >= 1048576 ) {
		$file_size = round( $file_size / 1048576 * 100 ) / 100 . " Mb";
	} elseif( $file_size >= 1024 ) {
		$file_size = round( $file_size / 1024 * 100 ) / 100 . " Kb";
	} else {
		$file_size = $file_size . " b";
	}
	return $file_size;
}
function antibot_start() {
	global $mkportals;
	if (extension_loaded('gd') && $this->config['antibot_chek'] && !$mkportals->member['id'] ) {
		$content .= '<div class="left">'.$this->lang['antibot_code'].':&nbsp;
		<img src="mkportal/include/antibot/antibot.php" onclick="if(!this.adress)this.adress = this.src; this.src=adress+\'?rand=\'+Math.random();" border="1" title="'.$this->lang['antibot_povtor'].'" style="cursor:pointer;" alt="'.$this->lang['antibot_code'].'"></div>';
		$content .= '<p><div class="left">'.$this->lang['antibot_return'].':&nbsp;&nbsp;&nbsp;
		<input type="text" name="check" size="15"></div></p>';
	} else {
		$content .= "<input type=\"hidden\" name=\"check\" value=\"0\">";
	}
		return $content;
}

function antibot_check($captcha_code) {
		$code = $_SESSION['captcha_keystring'];
		unset($_SESSION['captcha_keystring']);
		if (extension_loaded('gd') && $code != $captcha_code || !$captcha_code) {
			$message = "{$this->lang['antibot_error']}";
			$this->Ajax_error_page($message);
			exit();
} else {
			return 0;
                  }
  }

  
}

	$mklib = new mklib;
	$mklib->stimer();

	$mklib->siteurl = $SITE_URL;
	$mklib->forumpath = $FORUM_PATH;
	$mklib->forumview = $FORUM_VIEW;
	$mklib->portalview = $PORTAL_VIEW;
	$mklib->forumcd = $FORUM_CD;
	$mklib->forumcs = $FORUM_CS;
	$mklib->sitepath = $MK_PATH;
    $mklib->sitename = stripslashes($SITE_NAME);
	$mklib->mkurl = $SITE_URL."/mkportal";
	$mklib->adminpath = $ADMIN_PATH;
	$mklib->template = $MK_PATH."mkportal/templates/".$MK_TEMPLATE;
	$mklib->images = "/mkportal/templates/".$MK_TEMPLATE."/images";
	$mklib->mklang = $MK_PATH."mkportal/lang/".$MK_LANG;
	$mklib->menucloseds = "display:none";
	$mklib->menucontents = "";
	$mklib->menuclosedr = "display:none";
	$mklib->menucontentr = "";
	$mklib->mkeditor = $MK_EDITOR;
	$mklib->config = $mklib->read_config();
	$mklib->member = $mklib->read_member();
	$mklib->stats = $mklib->read_stat();
	$mklib->portalwidth = $MK_PORTALWIDTH;
	$mklib->columnwidth = $MK_COLUMNWIDTH;	
	$mklib->disablegzip = $MK_DISABLEGZIP;
	$mklib->disablenav = $MK_DISABLENAV;
	$mklib->loadcolumnright = $MK_LOADRIGHTC;
	$mklib->loadcolumnleft = $MK_LOADLEFTC;
	$mklib->unloadforumright = $MK_UNLOADRIGHTF;
	$mklib->unloadforumleft = $MK_UNLOADLEFTF;
	//$mklib->config['referer'] is retained for legacy support.
	$mklib->referer = isset($MK_REFERER) ? $MK_REFERER : $mklib->config['referer'];
	//--added ЧПУ 
	$mklib->friendurl = $MK_FRIENDURL;
        //--end ЧПУ
	$mklib->load_lang("lang_global.php");
	$mklib->load_lang("lang_blocktitle.php");
	$mklib->charset = $mklib->lang['charset'];
	if (!$mklib->charset) {
        	$mklib->charset = "iso-8859-1";
    	}
	$mklib->xml = $mklib->lang['xml'] ? $mklib->lang['xml'] : "en";

//Meo: Added in C 0.1c for Phpbb3 UTF8 compatibility
	if($MK_BOARD == "PHPBB3"){
		$mklib->charset = "UTF-8";
	}
// End

	//Constrain portal width	
	if ($mklib->portalview) { //Minimum & maximum pixels
		if ($mklib->portalwidth < 780) {
        		$mklib->portalwidth = '780';
		}
		if ($mklib->portalwidth > 1660) {
        		$mklib->portalwidth = '1600';
		}
	} else {
		if ($mklib->portalwidth < 75) { //Minimum & maximum percentage
        		$mklib->portalwidth = '75';
		}
		if ($mklib->portalwidth > 100) {
        		$mklib->portalwidth = '100';
		}
	}
	if ($mklib->columnwidth < 120) {
        		$mklib->columnwidth = 140;
	}
	if ($mklib->columnwidth > 280) {
        		$mklib->columnwidth = 280;
	}

?>
