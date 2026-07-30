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
define ( 'IN_MKP', 1 );
define ( 'IN_PMPOP', 1 );

$MK_PATH = "../";
require $MK_PATH."mkportal/conf_mk.php";

global $DB, $mklib, $mkportals;

switch($MK_BOARD) {
	case 'IPB':
		$driverf = "IPB/ipb_driverf.php";
		$board_functions = "IPB/ipb_board_functions.php";
    break;
	case 'PHPBB':
		$driverf = "PHPBB/php_driverf.php";
		$board_functions = "PHPBB/php_board_functions.php";
    break;
//Meo: Added in C 0.1.c for Phpbb3 integration
	case 'PHPBB3':
		$driverf = "PHPBB3/php_driverf.php";
		$board_functions = "PHPBB3/php_board_functions.php";
    break;
	case 'VB':
		$driverf = "VB/vb_driverf.php";
		$board_functions = "VB/vb_board_functions.php";
    break;
	case 'IPB13':
		$driverf = "IPB13/ipb13_driverf.php";
		$board_functions = "IPB13/ipb13_board_functions.php";
    break;
	case 'MYBB':
    		$driverf = "MYBB/mybb_driverf.php";
		$board_functions = "MYBB/mybb_board_functions.php";
	break;
	default:
		$driverf = "SMF/smf_driverf.php";
		$board_functions = "SMF/smf_board_functions.php";
    break;
}

require $MK_PATH."mkportal/include/$driverf";
require $MK_PATH."mkportal/include/functions.php";
require $MK_PATH."mkportal/include/$board_functions";

switch($MK_BOARD) {
    case 'IPB':
		$u1 = "$mklib->siteurl/$mklib->forumpath/index.php?act=Msg";
    break;
    case 'PHPBB':
		$u1 = "$mklib->siteurl/$mklib->forumpath/privmsg.php?folder=inbox";
    break;
//Meo: Added in C 0.1.c for Phpbb3 integration
    case 'PHPBB3':
		$u1 = "$mklib->siteurl/$mklib->forumpath/ucp.php?i=pm&folder=inbox";
    break;
    case 'VB':
		$u1 = "$mklib->siteurl/$mklib->forumpath/private.php";
    break;
	case 'IPB13':
		$u1 = "$mklib->siteurl/$mklib->forumpath/index.php?act=Msg";
    break;
	case 'MYBB':
		$u1 = "$mklib->siteurl/$mklib->forumpath/private.php";
    break;
    default:
		$u1 = "$mklib->siteurl/$mklib->forumpath/index.php?action=pm";
    break;
}

$m1 = $mklib->lang['popm1'];
$m2 = $mklib->lang['popm2'];
$m3 = $mklib->lang['popm3'];
$m4 = $mklib->lang['popm4'];

$output = "<script language=\"javascript\" type=\"text/javascript\">
<!--
function jump_to_inbox()
{
	opener.document.location.href = \"$u1\";
	window.close();
}
//-->
</script>
<body>
  <table width=\"100%\" height=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\" bgcolor=\"#F5F5F5\">
    <tr>
      <td>
	<table align=\"center\" width=\"95%\"  border=\"1\" cellspacing=\"0\" cellpadding=\"0\">
	  <tr>
	    <td valign=\"top\" width=\"100%\" bgcolor=\"#DFE6EF\" align=\"center\"><br /><strong><font face=\"verdana\" size=\"2\">$m1 <a href=$u1 onclick=\"jump_to_inbox();return false;\" target=\"_new\">$m2</a> $m3</font></strong><br /><br /><font face=\"verdana\" size=\"2\"><a href=\"javascript:window.close();\" >$m4</a></font><br /><br />
	    </td>
	  </tr>
	</table>
      </td>
    </tr>
  </table>
</body>
  
  ";

  print $output;

  ?>
