<?php

error_reporting  (E_ERROR | E_WARNING | E_PARSE);
$MK_PATH = "../../";
require $MK_PATH."mkportal/conf_mk.php";

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
		  Для обновления выберете версию портала до которой хотите обновится <br>
<a  href=\"/mkportal/upgrades/upgrade102.php\"\> RUSMKPORTAL R 1.0.1 -> R 1.0.2</a><br>
<br>
         <a  href=\"/mkportal/upgrades/upgrade10.php\"\> RUSMKPORTAL R0.0.3 -> R 1.0.1</a> <br>
<br>
         <a  href=\"/mkportal/upgrades/upgrade03.php\"\> RUSMKPORTAL C1.2.2 R0.0.2 -> R 0.0.3</a> <br>
<br>
          <a  href=\"/mkportal/upgrades/upgrade02.php\"\>RUSMKPORTAL C1.2.2 R0.0.1 -> C1.2.2 R0.0.2</a>
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
			
				default:
    				start();
    			break;
}

//Is upgrade necessary? Is upgrade possible?
function start() {
	global $header, $footer, $langmk, $ug_config, $MK_BOARD, $FORUM_PATH;


	$output = $header.$content.$footer;
	print $output;

} 

?>