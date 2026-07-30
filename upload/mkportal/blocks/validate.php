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

global $MK_TEMPLATE;
	
$content = "";

	$content = "
<tr align=\"center\">
<td>
<a href=\"http://validator.w3.org/check?uri=referer\"><img width=\"88\" height=\"31\" border=\"0\" src=\"http://www.w3.org/Icons/valid-xhtml10\" alt=\"valid\" /></a><br /><br /><a href=\"http://jigsaw.w3.org/css-validator/validator?uri=$this->siteurl/mkportal/templates/".$MK_TEMPLATE."/style.css\"><img style=\"border: 0pt none ; width: 88px; height: 31px;\" src=\"http://jigsaw.w3.org/css-validator/images/vcss\" alt=\"Valid CSS!\" /></a>
</td>
</tr>

	";

?>