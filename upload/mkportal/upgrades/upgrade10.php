<?php
/*
+--------------------------------------------------------------------------
|   MkPortal
|   Upgrade to C1.2.2 from versions >= M1.0
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

/*-------------------------------------------------------------------------
  UPGRADE SCRIPT CONFIG
-------------------------------------------------------------------------*/
$ug_config['newver'] = 'R1.0.1'; //New MKPortal version number - change this for each new version

//List of MKP versions since M1.0
$ug_config['goodver'] = array(
	 "0" => "M1.0"
	,"1" => "M1.1 Rc1"
	,"2" => "M1.1"
	,"3" => "M1.1.1"
	,"4" => "M1.1.2"
	,"5" => "M1.1.2b"
	,"6" => "C1.2 beta1"
	,"7" => "C1.2 beta3"
	,"8" => "C1.2 rc1"
	,"9" => "C1.2 rc2"
	,"10" => "C1.2"
	//Added by Kimi in C1.2.2
	,"11" => "C1.2.1"
	,"12" => "C1.2.2 R0.0.2"
	,"13" => "R0.0.3"
); 
/*-----------------------------------------------------------------------*/

/*
//Debug
echo '<strong>$ug_config[\'goodver\']:</strong><br /><pre>';
print_r($ug_config['goodver']);
echo '</pre><br /><br />';
$output = array_slice($ug_config['goodver'], 0, 9);
echo '<strong>array_slice:</strong><br /><pre>';
print_r($output);
echo '</pre>';
exit;
*/

error_reporting  (E_ERROR | E_WARNING | E_PARSE);

require "../conf_mk.php";

//Language
$MK_LANG = !file_exists("../lang/$MK_LANG/lang_upgrade.php") ? 'English' : $MK_LANG; //Check for lang file	
require "../lang/$MK_LANG/lang_upgrade.php"; //Load lang file
$langmk = str_replace  ('%1', $ug_config['newver'], $langmk); //Language replacements

//Template header & footer
$header = "
<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"
        \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\" lang=\"en\">

<!-- begin document head -->
<head>
  <meta http-equiv=\"content-type\" content=\"text/html; charset=windows-1251\" />
  <meta name=\"generator\" content=\"MKPortal\" />
  <meta http-equiv=\"Pragma\" content=\"no-cache\" />
  <meta content=\"no-cache\" http-equiv=\"no-cache\" />
  <meta http-equiv=\"Cache-Control\" content=\"no-cache\" />
  <title>{$langmk['ug_title']}</title>
  <link href=\"../templates/default/style.css\" rel=\"stylesheet\" type=\"text/css\" />
  <style type=\"text/css\">
	body, td {font-size: 14px; font-weight: bold}
  </style>
</head>

<body>
<!-- end document head -->


<!-- begin open main table -->
<div id=\"mkwrapper\" style=\"width: 100%\">
<table class=\"tabmain\" style=\"text-align: center; width: 700px; margin: 10px auto 10px auto;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">

  <tr>
    <td width=\"100%\" align=\"center\">

      <table border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
<!-- end open main table -->

<!-- begin logostrip -->  
	<tr>
	  <td id=\"mklogostrip\" style=\"background-image: url('../templates/default/images/sf_logo.jpg')\" width=\"100%\">
          <img src=\"../templates/default/images/logo.gif\" border=\"0\" alt=\"\" />
          </td>
	</tr>	
<!-- end logostrip -->
    
<!-- begin open portal body -->
	<tr align=\"center\">
	  <td width=\"100%\">
	    <table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">
	      <tr>
		<td style=\"text-align: center; padding: 40px\" valign=\"top\">
<!-- end open portal body -->			
		  
		  <h1 class=\"mktxtcontr\" style=\"font-weight: bold; font-size: 16px;\">{$langmk['ug_title']}</h1>
";
$footer = "
<!-- begin close portal body -->
		</td>
	      </tr>
	    </table>
	  </td>
	</tr>	
<!-- end close portal body -->

<!-- begin close main table -->
      </table>
    </td>
  </tr>
</table>
</div>
<!-- end close main table -->

<!-- begin footer -->
<div style=\"text-align: center; padding-bottom: 10px;\"><span style=\"font-size: 10px;\"><a style=\"text-decoration: none;\" href=\"http://www.mkportal.it/\" target=\"_blank\">MKPortal</a> &copy;2003-2008 <a style=\"text-decoration: none;\" href=\"http://www.mkportal.it/\" target=\"_blank\">mkportal.it</a></span></div>
<!-- end footer -->

</body>
</html>
";

//Input
switch(intval($_GET['op'])) {
				case '1':
    				upgrade_config();
			break;
				case '2':
    				upgrade_db();
    			break;
				default:
    				start();
    			break;
}

//Is upgrade necessary? Is upgrade possible?
function start() {
	global $header, $footer, $langmk, $ug_config, $MK_BOARD, $FORUM_PATH;

	//$content = "Step 1 of 2";

	//Database connection
	$checkdb_conn = get_db();
	if (!$checkdb_conn) {
		$content .= "<p style=\"color: red\">{$langmk['ug_nodb']}</p>";
		$output = $header.$content.$footer;
		print $output;
		exit;
	}

	$mkp_version = get_version(); // Get version number

	// Error if you are already running current version
	if (preg_match("/^".$ug_config['newver']."$/i", $mkp_version)) {
		$content .= "<p style=\"color: green\">{$langmk['ug_noupgrade']}</p><p style=\"color: red\">{$langmk['ug_succes4']}</p>";
		$output = $header.$content.$footer;
		print $output;
		mysql_close();
		exit;
	}

	// Print "upgrade" button for MKP versions >= M1.0 - Error if wrong version
	if (!in_array($mkp_version, $ug_config['goodver'])) {
        	$content = "<p style=\"color: red\">{$langmk['ug_wrongvers']}</p><p><p style=\"color: red\">{$langmk['ug_succes4']}</p>";
		$output = $header.$content.$footer;
		print $output;
		mysql_close();
		exit;
	}
	else {
		$content .= "<p>{$langmk['ug_nowrunning1']} $mkp_version {$langmk['ug_nowrunning2']}</p>";
		$content .= "<form name=\"main\" method=\"post\" action=\"upgrade10.php?op=2\">
		<input type=\"submit\" value=\"{$langmk['ug_go']}\" name=\"B1\" class=\"mkbutton\" />
		</form>";
	}

	$output = $header.$content.$footer;
	print $output;

} //end function start

//Upgrade config and database 
/*
function upgrade_config() {
	global $header, $footer, $langmk, $ug_config, $MK_BOARD, $FORUM_PATH;

	//$content = "Step 2 of 2";

	require "../conf_mk.php";

	//Database connection
	$checkdb_conn = get_db();
	if (!$checkdb_conn) {
		$content .= "<p style=\"color: red\">{$langmk['ug_nodb']}</p>";
		$output = $header.$content.$footer;
		print $output;
		exit;
	}

	$mkp_version = get_version(); // Get version number

	// Error if you are already running current version
	if (preg_match("/^".$ug_config['newver']."$/i", $mkp_version)) {
		$content .= "<p style=\"color: green\">{$langmk['ug_noupgrade']}</p><p style=\"color: red\">{$langmk['ug_succes4']}</p>";
		$output = $header.$content.$footer;
		print $output;
		mysql_close();
		exit;
	}

// Update database & config file - Error if wrong version
if (!in_array($mkp_version, $ug_config['goodver'])) {
        	$content = "<p style=\"color: red\">{$langmk['ug_wrongvers']}</p><p><p style=\"color: red\">{$langmk['ug_succes4']}</p>";
		$output = $header.$content.$footer;
		print $output;
		mysql_close();
		exit;
}
else {

	//Upgrade conf_mk.php if needed
	if (!isset($MK_REFERER)) {

		//Default values for versions >= M1.0
		$ADMIN_PATH = isset($ADMIN_PATH) ? $ADMIN_PATH : "admin";
		$MK_TIMEDIFF = isset($MK_TIMEDIFF) ? $MK_TIMEDIFF : "0";
		$MK_OFFLINE = isset($MK_OFFLINE) ? $MK_OFFLINE : "0";
		$MK_DISABLEGZIP = isset($MK_DISABLEGZIP) ? $MK_DISABLEGZIP : "0";
		$MK_PORTALWIDTH = isset($MK_PORTALWIDTH) ? $MK_PORTALWIDTH : "780";
		$MK_COLUMNWIDTH = isset($MK_COLUMNWIDTH) ? $MK_COLUMNWIDTH : "140";
		$MK_DISABLENAV = isset($MK_DISABLENAV) ? $MK_DISABLENAV : "0";		
		$MK_LOADLEFTC = isset($MK_LOADLEFTC) ? $MK_LOADLEFTC : "1";
		$MK_LOADRIGHTC = isset($MK_LOADRIGHTC) ? $MK_LOADRIGHTC : "1";
		$MK_UNLOADLEFTF = isset($MK_UNLOADLEFTF) ? $MK_UNLOADLEFTF : "0";
		$MK_UNLOADRIGHTF = isset($MK_UNLOADRIGHTF) ? $MK_UNLOADRIGHTF : "0";
		$MK_REFERER = "0";

		$contentfile = "<?php\n\n \$FORUM_PATH = \"$FORUM_PATH\"; \n \$FORUM_VIEW = \"$FORUM_VIEW\"; \n \$PORTAL_VIEW = \"$PORTAL_VIEW\"; \n \$FORUM_CD = \"$FORUM_CD\"; \n \$FORUM_CS = \"$FORUM_CS\"; \n \$SITE_NAME = \"$SITE_NAME\"; \n \$SITE_URL = \"$SITE_URL\"; \n \$ADMIN_PATH = \"$ADMIN_PATH\"; \n \$MK_TEMPLATE = \"$MK_TEMPLATE\"; \n \$MK_LANG = \"$MK_LANG\"; \n \$MK_EDITOR = \"$MK_EDITOR\"; \n \$MK_BOARD = \"$MK_BOARD\"; \n \$MK_TIMEDIFF = \"$MK_TIMEDIFF\"; \n \$MK_OFFLINE = \"$MK_OFFLINE\"; \n \$MK_DISABLEGZIP = \"$MK_DISABLEGZIP\"; \n \$MK_PORTALWIDTH = \"$MK_PORTALWIDTH\"; \n \$MK_COLUMNWIDTH = \"$MK_COLUMNWIDTH\"; \n \$MK_DISABLENAV = \"$MK_DISABLENAV\"; \n \$MK_LOADLEFTC = \"$MK_LOADLEFTC\"; \n \$MK_LOADRIGHTC = \"$MK_LOADRIGHTC\"; \n \$MK_UNLOADLEFTF = \"$MK_UNLOADLEFTF\"; \n \$MK_UNLOADRIGHTF = \"$MK_UNLOADRIGHTF\"; \n \$MK_REFERER = \"$MK_REFERER\"; \n ?>";
		if (!$handle = fopen("../conf_mk.php", 'w')) {
         		$content = "$noopen";
			$output = $header.$content.$footer;
			print $output;
         		exit;
   		}
   		if (!fwrite($handle, $contentfile)) {
       			$content = $nowrite;
			$output = $header.$content.$footer;
			print $output;
       			exit;
   		}
		fclose($handle);
	}

	//Upgrade AEF
	if ($MK_BOARD == "AEF") {
		header("Location: $SITE_URL/aeforum/upgrade/index.php");
	} else {
		upgrade_db();
		exit;
	}
} 
} // end function upgrade_config

*/
// Upgrade database
function upgrade_db() {
	global $header, $footer, $langmk, $ug_config, $MK_BOARD, $FORUM_PATH;

	require "../conf_mk.php";

	//Database connection
	$checkdb_conn = get_db();
	if (!$checkdb_conn) {
		$content .= "<p style=\"color: red\">{$langmk['ug_nodb']}</p>";
		$output = $header.$content.$footer;
		print $output;
		exit;
	}

	$mkp_version = get_version(); // Get version number

	// Error if you are already running current version
	if (preg_match("/^".$ug_config['newver']."$/i", $mkp_version)) {
		$content .= "<p style=\"color: green\">{$langmk['ug_noupgrade']}</p><p style=\"color: red\">{$langmk['ug_succes4']}</p>";
		$output = $header.$content.$footer;
		print $output;
		mysql_close();
		exit;
	}


//Upgrade from C1.2rc1 to C1.2 rc2 ($mkp_version >= "M1.0" && <= "C1.2 rc1")
if (in_array($mkp_version, $ug_config['goodver'])) {
/*
$query = "INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('cache', '1')";
mysql_query($query);
$query = "INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('cache_time', '3600')";
mysql_query($query);*/
$query = "UPDATE mkp_config SET valore ='{$ug_config['newver']}' where chiave = 'mk_version'";
mysql_query($query);
$query = "INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('rewrite_url', '0')";
mysql_query($query);
$query = "INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('rewrite_step', '/')";
mysql_query($query);
$query = "INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('modules', '0')";
mysql_query($query);
$query = "ALTER TABLE `mkp_blocks` ADD (`modules` text NULL)";
mysql_query($query);

	//Success message

	$content .= "<p><a href=\"../../index.php\">{$langmk['ug_succes3']}</a></p>";

	$output = $header.$content.$footer;
	print $output;
	mysql_close();
	exit;

} //end function upgrade_db
}

//Database connection
function get_db() {

require "../conf_mk.php";

switch($MK_BOARD) {
	case 'IPB':
		$confff = "conf_global.php";
    break;
    case 'IPB3':
		$confff = "conf_global.php";
    break;
	case 'PHPBB':
    		$confff = "config.php";
    break;
	case 'PHPBB3':
    		$confff = "config.php";
    break;
	case 'VB':
    		$confff = "includes/config.php";
    break;
	case 'AEF':
    		$confff = "universal.php";
    break;
    	case 'IPB13':
    		$confff = "conf_global.php";
    break;
	case 'MYBB':
    		$confff = "inc/config.php";
    break;
    case 'SMF2':
    				$confff = "Settings.php";
    break;
	default: //SMF
    		$confff = "Settings.php";
    break;
}

	require "../../$FORUM_PATH/$confff";

	switch($MK_BOARD) {
		      case 'IPB3':
    				$dbhost = $INFO['sql_host'];
					$dbname = $INFO['sql_database'];
					$dbuser = $INFO['sql_user'];
					$dbpasswd = $INFO['sql_pass'];
				case 'IPB':
    				$dbhost = $INFO['sql_host'];
					$dbname = $INFO['sql_database'];
					$dbuser = $INFO['sql_user'];
					$dbpasswd = $INFO['sql_pass'];
    			break;
				case 'PHPBB':
    					$dbhost = $dbhost;
					$dbname = $dbname;
					$dbuser = $dbuser;
					$dbpasswd = $dbpasswd;
					break;
				case 'PHPBB3':
    					$dbhost = $dbhost;
					$dbname = $dbname;
					$dbuser = $dbuser;
					$dbpasswd = $dbpasswd;
    			break;
				case 'VB':
    					$dbhost = $config['MasterServer']['servername'];
  					$dbname = $config['Database']['dbname'];
  					$dbuser = $config['MasterServer']['username'];
  					$dbpasswd = $config['MasterServer']['password'];
			break;
				case 'AEF':
					$dbhost = $globals['server'];
					$dbname = $globals['database'];
					$dbuser = $globals['user'];
					$dbpasswd = $globals['password'];					
			break;
				case 'IPB13':
    					$dbhost = $INFO['sql_host'];
					$dbname = $INFO['sql_database'];
					$dbuser = $INFO['sql_user'];
					$dbpasswd = $INFO['sql_pass'];
			break;
				case 'MYBB':
    					$dbhost = $config['hostname'];
					$dbname = $config['database'];
					$dbuser = $config['username'];
					$dbpasswd = $config['password'];
    			break;
				default:
    					$dbhost = $db_server;
					$dbname = $db_name;
					$dbuser = $db_user;
					$dbpasswd = $db_passwd;
    			break;
	}

	mysql_connect($dbhost, $dbuser, $dbpasswd);
	$checkdb_conn = mysql_select_db($dbname);

	return $checkdb_conn;

} //end function get_db


//Get current version
function get_version() {
	$query = mysql_query( "SELECT valore FROM mkp_config WHERE chiave = 'mk_version'");
	$row = mysql_fetch_array($query);
	return $row['valore'];
}


?>