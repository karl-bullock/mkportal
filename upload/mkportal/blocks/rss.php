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
|   Block RSS Reader 1.1 
|       optimized for restricted server environment
|       with allow_url_fopen = off
|
|   > (c) 2005 by Peter (Peter@ibforen.de)
|
+--------------------------------------------------------------------------
*/
if (!defined("IN_MKP")) {
    die ("Sorry !! You cannot access this file directly.");
}

global $mklib, $DB;

define ('CACHE_PATH_RSS', $mklib->sitepath."mkportal/cache/");

unset($rss_source);

$DB->query("SELECT * FROM mkp_rss WHERE active='1' ORDER BY position ASC");
if ($DB->get_num_rows()) {
    while ($r = $DB->fetch_row()) {
        $rss_source[$r['name']] = $r['url'];
    }
}

function rss_clean_html($text = "") {
    $text = str_replace("√§", "‰", $text);
    $text = str_replace("√∂", "ˆ", $text);
    $text = str_replace("√º", "¸", $text);
    $text = str_replace("√Ñ", "ƒ", $text);
    $text = str_replace("√ñ", "÷", $text);
    $text = str_replace("√ú", "‹", $text);
    $text = str_replace("√ü", "ﬂ", $text);
    $text = str_replace("‚Äò", "'", $text);
    $text = str_replace("‚Äô", "'", $text);
    $text = str_replace("‚Äú", "'", $text);
    $text = str_replace("‚Äù", "'", $text);
    $text = str_replace("¬¥", "'", $text);    
    $text = str_replace("‚Äì", "-", $text);
    $text = str_replace("&amp;", "&", $text);
    return $text;
}

function rss_clean_entry($entry = "") {
    global $mklib;

    if ($entry == "") return;
    $entry = str_replace("<"."![CDATA[", "", $entry);
    $entry = trim(str_replace("]]".">", "", $entry));
    $entry = $mklib->convert_savedb($entry);
    $entry = stripslashes($entry);    
    return rss_clean_html($entry);
}

function rss_get_rdf($rdf_file = "", $cache_file = "") {
    global $mklib;
        
    $cache_file = CACHE_PATH_RSS.$cache_file.".rss";
    if (!file_exists($cache_file)) {
        $fh = fopen($cache_file, "wb");
        fwrite($fh, "");
        fclose($fh);
        $new = TRUE;
    }
    $ctime = time() - $mklib->config['rss_cache_time'];
    if ($new || filemtime($cache_file)<$ctime) {
        $current_version = (int)str_replace('.', '', phpversion());
        if ($current_version < 420) {
            $allow_url = ini_get ('allow_url_fopen');
        } else {
            $a = ini_get_all();
            $allow_url = $a['allow_url_fopen']['global_value'];
            unset ($a);
        }
        if ($allow_url) {
            $rdf = implode("",file($rdf_file));
        }
        else {
            $url = parse_url($rdf_file);
            $fp = fsockopen ($url['host'], 80, $errno, $errstr, 30);
            if (!$errno && !$errstr) {
                fwrite ($fp, "GET {$url['path']}?{$url['query']}#{$url['fragment']} HTTP/1.0\r\nHost: {$url['host']}\r\n\r\n");
                while (!feof($fp)) {
                    $rdf .= fgets($fp,1024);
                }
                $rdf = preg_replace("`.*?(<\?xml)`is", "\\1", $rdf);
                fclose($fp);
            }
        }
        $fh = fopen($cache_file, "wb");
        fwrite($fh, $rdf);
        fclose($fh);
    }
    else {
        $rdf = implode ("", file ($cache_file));
    }

    $rdf = rss_clean_html($rdf);
    if (preg_match_all("|<channel(.*)</channel>|Uism",$rdf, $title, PREG_PATTERN_ORDER)) {
        preg_match_all("|<title>(.*)</title>.*<link>(.*)</link>.*<description>(.*)</description>|Uism", $title[1][0], $regs, PREG_PATTERN_ORDER);
        $content .= "<b><a href=\"".$regs[2][0]."\" target=\"_blank\" class=\"uno\" ><span style=\"font-size:11pt;\">".$regs[1][0]."</span></a></b><br /><i>".$regs[3][0]."</i><br /><br />";
    };

    $result = preg_match_all("|<item>(.*?)</item>|is",$rdf, $items, PREG_PATTERN_ORDER);
    if (!$result) {
        preg_match_all("|<item.*?>(.*?)</item>|is",$rdf, $items, PREG_PATTERN_ORDER);
    }
    for ($i = 0; $i < min($mklib->config['rss_max_items'], count($items[1])); $i++){
        preg_match("|<title>(.*)</title>.*<link>(.*)</link>|Uism",$items[1][$i], $matches);
        $content .= "<b><a href=\"".$matches[2]."\" target=\"_blank\" class=\"uno\" >".rss_clean_entry($matches[1])."</a></b><br />";
        if ($mklib->config['rss_desc']) {
            preg_match("|<description>(.*)</description>|Uism",$items[1][$i], $matches);
            if (count($matches)) {
                $desc = rss_clean_entry($matches[1]);
                if ((strlen($desc) > $mklib->config['rss_desc_length']) && ($mklib->config['rss_desc_length'] != 0)) {
			$desc = strip_tags($desc);
			$desc = substr($desc, 0, $mklib->config['rss_desc_length']);
                    $desc = substr($desc, 0, strrpos($desc, " " ));
                    $desc .= " ...";
                }
                $content .= $desc."<br /><br />\n";
            }
        }
    }
    return $content."<br /><br />";
}

$content = "";

if (is_array($rss_source)) {
    global $std;
    if ($mklib->config['rss_marquee']) {
        $marquee_begin = "\n<marquee direction=\"up\" scrolldelay=\"0\" scrollamount=\"1\" height=\"{$mklib->config['rss_marquee_height']}\">\n";
        $marquee_end = "\n</marquee>\n";
    }
    $content = "<tr><td><div style=\"margin:2px;\">\n".$marquee_begin;
    $content .= "<div class=\"tdblock\">".rss_clean_html($mklib->create_date(time(), "long"))."<br /><br /></div>\n";
    foreach($rss_source as $source => $url) {
        $content .= rss_get_rdf($url,$source );
    }
    $content .= $marquee_end."</div></td></tr>\n";
}

//Error if block not selected in RSS Admin
if ($mklib->config['rss_parser'] != 'mkportal') {
	$content = "
				<tr>
				  <td class=\"tdblock\" align=\"center\">
				  {$mklib->lang['rss_nodouble']}
				  </td>
				</tr>
	";
}

//Error if no active feeds
if (!$rss_source) {
	$content = "
				<tr>
				  <td class=\"tdblock\" align=\"center\">
				  {$mklib->lang['rss_noactive']}
				  </td>
				</tr>
	";
}

?>
