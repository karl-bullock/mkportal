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
			<td width=\"100%\" style=\"text-align:center;\">
				<a href=\"#\" onclick=\"ajax_showPop('{$this->sitepath}index.php?ind=ajax&amp;act=sitemon', 1);return false\"><img src=\"$this->images/monitor.gif\" border=\"0\" align=\"middle\" alt=\"\" /></a>
	
			</td>
		</tr>";

?>
