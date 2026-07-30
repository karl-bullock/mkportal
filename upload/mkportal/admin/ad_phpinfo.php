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

$content = "
	<tr>
	  <td>
	    <div id=\"phpinfo\">
";

ob_start();
phpinfo();
$phpinfo = ob_get_contents();
ob_end_clean();

$phpinfo = preg_replace( "`(\<!DOCTYPE html PUBLIC(.*?\<body>))`is", "",$phpinfo);
$phpinfo = preg_replace( "`(\</body>(.*?\</html>))`is", "",$phpinfo);

$content .= $phpinfo;
	
$content .= "
	    </div>  
	  </td>
	</tr>
	";

$output = $Skin->view_block($mklib->lang['ad_phpinfo'], $content);
$mklib->printpage_admin($mklib->lang['ad_titlepage'].$mklib->lang['tt_sep'].$mklib->lang['ad_phpinfo'], $output);

?>
