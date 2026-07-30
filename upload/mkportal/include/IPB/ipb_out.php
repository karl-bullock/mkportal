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
@define('MK_SCRIPT', 'forum');

function destroy(&$var) {
    if (is_object($var)) {
        $vars = get_object_vars($var);
        if (is_array($vars)) {
            foreach ($vars as $key => $value) {
                unset($var->{$key});
            }
            $var = "";
        }
        else {
            unset ($var);
        }
    }
    else {
        $var = "";
    }
}

function mkportal_board_out($output) {

        global  $ipsclass, $DB, $mkportals, $Skin, $MK_PATH, $MK_TEMPLATE, $mklib, $mklib_board, $MK_TIMEDIFF;
	$DB       = $ipsclass->DB;

// ini_set("memory_limit",'12M');        
        //destroy($INFO);
        //destroy($forums);
        //destroy($runme);
        //destroy($choice);
        define ( 'IN_MKP', 1 );
        $MK_PATH = "../";
        require $MK_PATH."mkportal/conf_mk.php";
        $boarddir = $MK_PATH.$FORUM_PATH."/";
        $mkportals->member = $ipsclass->member;
        $mkportals->input = $ipsclass->input;
        $mkportals->base_url = $boarddir."index.php";
        $mkportals->forum_url = $MK_PATH.$FORUM_PATH;
        $mkportals->member['user_new_privmsg'] = $mkportals->member['msg_total']."/".$mkportals->member['new_msg'];
        if($mkportals->member['mgroup'] == 4) {
            $mkportals->member['g_access_cp'] = 1;
        }

        $mkportals->member['theme'] = $mkportals->member['skin'];
        $mkportals->member['timezone'] = ($ipsclass->get_time_offset() /3600);
		$mkportals->member['name'] = $ipsclass->member['members_display_name'];
	
        if (substr($ipsclass->skin['_setname'], 0, 9) == "mkportal2") {
            $MK_TEMPLATE = "default";
        }
	foreach ($ipsclass->cache['languages'] as $value) {
   		if ($value['ldir'] == $mkportals->member['language']) {
   			$mkportals->member['mk_lang'] = strtolower($value['lname']);
   		}
	} 

        $board_name = $ipsclass->vars['board_name'];

	//start mkportal query count and load mkportal	
	$DB->query_count = 0;

        require $MK_PATH."mkportal/include/functions.php";
        require_once $MK_PATH."mkportal/include/IPB/ipb_board_functions.php";
        require_once "$mklib->template/tpl_main.php";
        if($MK_OFFLINE && !$mkportals->member['g_access_cp'] && !$mklib->member['g_access_cpa']) {
            $message = $mklib->lang['offline'];
            $mklib->off_line_page($message);
            exit;
        }
       
	$mkpsubs = "#ipbwrapper{
            margin: 0px auto 0px auto;
            text-align: left;
        }";
        
	$output = preg_replace( "`(\#ipbwrapper(.*?\}))`is", $mkpsubs,$output);

        $mkpsubs = "img{ 
            border: 0;
        }";
        $output = preg_replace( "`(\img{(.*?\}))`is", $mkpsubs,$output);

        
	$mkpsubs = ".divpad{
            padding: 0px;
        }";
        //$output = preg_replace( "`(\.divpad(.*?\}))`is", $mkpsubs,$output);
        $output = str_replace("background: transparent;", " ", $output);
        
	$output = preg_replace( "`(\<div id=\"logostrip\">(.*?\</div>))`is", "",$output);

        
	$output = $mklib->printpage_forum("$mklib->forumcs", "$mklib->forumcd", $board_name, $output);
	return $output;

        
}


?>
