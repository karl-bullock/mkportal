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

error_reporting  (E_ERROR | E_WARNING | E_PARSE);



//Language
$lang = $_POST['lang'];

##########################
# English (default)
##########################

//function start
$mklang['title'] = "! MKPORTAL R1.0.2  Установка !";
$mklang['selectlang'] = "<span style=\"color: #5c88c8;\">&bull;</span> Язык Установки <span style=\"color: #5c88c8;\">&bull;</span>";
$mklang['next'] = "Далее >>";

//function step0
$mklang['welcome'] = "Лицензионное соглашение MKPortal.";

$mklang['agree'] = "Согласен";

$mklang['licenza'] = "

THE USE OF THIS SCRIPT IS ABSOLUTELY AND TOTALLY FREE. THIS SCRIPT MAY BE FREELY USED FOR COMMERCIAL AND NON-COMMERCIAL PURPOSES WITHOUT ANY RESTRICTION, LIMIT, OR EXPIRATION.

IT IS NOT ALLOWED TO REMOVE OR MODIFY IN ANY WAY THE MKPORTAL COPYRIGHT AT THE BOTTOM OF THE PAGE. THE LICENSE WILL BE IMMEDIATELY REVOKED FOR ANYONE WHO WILL NOT RESPECT THESE TERMS AND CONDITIONS. IT IS ALLOWED TO REMOVE THE SMALL \"MKPORTAL\" LOGO AT THE BOTTOM OF THE PAGE.

IT IS ALLOWED TO MODIFY THIS SCRIPT, BUT FOR PERSONAL USE ONLY AND UNDER THE CONDITION NOT TO DISTRIBUTE MODIFICATIONS BUT UNDER THE FOLLOWING TERMS.

IT IS ALLOWED TO CREATE AND DISTRIBUTE ADD-ONS FOR THIS SCRIPT BUT EXCLUSIVELY AS \"BLOCKS\" AND \"MODULES\". THAT MEANS ADDING PARTS TO BASE SCRIPT WHICH DO NOT MODIFY ORIGINAL CODE.

IT IS FORBIDDEN TO REDISTRIBUTE THIS SCRIPT. THE SCRIPT CAN BE DISTRIBUTED EXCLUSIVELY BY THE OWNER OF THE COPYRIGHT OR AUTHORIZED PEOPLE ONLY.

IT IS FORBIDDEN TO DISTRIBUTE MODIFIED COPIES OF THE SCRIPT. SCRIPT MODIFICATIONS CAN BE DISTRIBUTED ONLY AS \"INSTRUCTIONS\" AND NOT AS PORTIONS OF MODIFIED CODE. IT IS ALSO FORBIDDEN TO DISTRIBUTE \"SCRIPTS\" OR OTHER PROGRAMS THAT MODIFY IN ANY WAY THE SOURCE CODE OF THIS SCRIPT.

AS THE SCRIPT IS GRANTED FOR FREE, THERE IS NO GUARANTEE. THE OWNER OF THE COPYRIGHT AND OTHERS SUPPLY THE SCRIPT \"AS IS\" WITHOUT ANY KIND OF GUARANTEE, EITHER EXPLICIT OR IMPLICIT; THIS IMPLIES THE GUARANTEE FOR ANY AIM. THE WHOLE RISK CONCERNING THE QUALITY AND PERFORMANCE OF THIS SCRIPT IS ASSUMED BY THE CUSTOMER. IF THE SCRIPT TURNS OUT TO BE DEFECTIVE, THE CUSTOMER WILL BE RESPONSIBLE FOR ALL MAINTENANCE, REPAIR OR NECESSARY CORRECTION.

NEITHER THE COPYRIGHT HOLDER NOR OTHER PARTIES WHO CAN MODIFY OR REDISTRIBUTE THIS SCRIPT AS ALLOWED IN THIS LICENCE ARE RESPONSIBLE FOR DAMAGES TO USERS. THIS IMPLIES GENERIC, SPECIAL OR ACCIDENTAL DAMAGES, INCLUDING DAMAGES RESULTING FROM THE USE OR IMPOSSIBILITY TO USE THE SCRIPT. THIS IMPLIES, WITHOUT LIMITS, THE LOSS OF DATA, THE CORRUPTION OF DATA, ANY LOSS CONCERNING BOTH CUSTOMERS AND THIRD PARTIES, AND INCOMPATIBILITY OF THE SCRIPT WITH OTHER PROGRAMS, EVEN IF THE HOLDER OR OTHERS HAVE BEEN INFORMED OF THE POSSIBILITY OF THESE DAMAGES.

'TinyMCE' EDITOR, 'PJIRC' JAVA APPLET, AND 'SIMPLEPIE' ARE THE EXCLUSIVE PROPERTY OF THEIR RESPECTIVE COPYRIGHT HOLDERS AND THEY HAVE NOTHING TO DO WITH THIS LICENCE, BUT THEY ARE UNDER THEIR OWN LICENCE, AVAILABLE IN THEIR PACKAGES AND/OR DIRECTORIES.

THE ICONS USED IN THE MKPORTAL \"DEFAULT\" SKIN ARE PROPERTY OF Foood's ICONS (http://www.foood.net). THEY CANNOT BE USED FOR PURPOSES OTHER THAN MKPORTAL WITHOUT THE EXPLICIT CONSENT OF THE AUTHOR.

THE \"SILK\" ICONS USED IN THE MKPORTAL CONTROL PANEL ARE COPYRIGHT MARK JAMES (http://www.famfamfam.com/lab/icons/silk/). THEY ARE LICENSED UNDER A CREATIVE COMMONS ATTRIBUTION 2.5 LICENSE (http://creativecommons.org/licenses/by/2.5/).

\n\n";

//function step1
$mklang['error1'] ="<br />Ошибка: Установите права на запись";
$mklang['error2'] ="(chmod 0777).";
$mklang['error3'] ="and of";

//function step22
$mklang['gostring'] = "Далее >>";
$mklang['chooseboard'] = "Выберите форум ";
$mklang['choosewarn'] = "";

//function step2
$mklang['urlstring'] = "Site URL: <br /><span style=\"font-weight: normal;\">Адрес сайта где расположен файл index.php портала MKPortal.<br />Например (e.g. http://www.mysite.ru ).</span>";
$mklang['pathstring'] = "Название Директории форума: <br /><span style=\"font-weight: normal;\">Название директории где уже установлен форум.<br />Например, если ваш форум находится в директории 'forum'<br />Напишите просто: forum</span>";
$mklang['gostring'] = "Делее >>";


//function step3
//$mklang = "English";
$mklang['error4'] = "<br />ОШИБКА: Заполните все поля.<br />";
$mklang['error5'] = "<br />Ошибка: Путь до форума не верный! Не возможно найти файл конфигураций форума ";
$mklang['error5b'] = " .";
$mklang['error6'] = "Ошибка: Не удалось открыть файл ";
$mklang['error7'] = "Не удалось записать в файл ";
$mklang['error7b'] = ". Установите права на запись этого файла (CHMOD 0666).";

//function step4
$mklang['okend'] = "ПОЗДРАВЛЯЕМ MKPORTAL УСПЕШНО УСТНОВЛЕН!<br /><br /><br />Удалите установочный файл mk_install.php и директорию mkportal/upgrades с вашего сервера.";
$mklang['okend2'] = "";
$mklang['loginmk'] = "Перейти в MKPortal";
$mklang['exists'] = "<p>Error: MKPORTAL Уже установлен.</p><p>В целях безопасности удалите mk_install.php и директорию mkportal/upgrades с вашего сервера.</p>";

//End English Language



//Template
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
  <title>{$mklang['title']}</title>
  <link href=\"templates/default/style.css\" rel=\"stylesheet\" type=\"text/css\" />
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
	  <td id=\"mklogostrip\" style=\"background-image: url('templates/default/images/sf_logo.jpg')\" width=\"100%\">
          <img src=\"templates/default/images/logo.gif\" border=\"0\" alt=\"\" />
          </td>
	</tr>	
<!-- end logostrip -->
    
<!-- begin open portal body -->
	<tr align=\"center\">
	  <td width=\"100%\">
	    <table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">
	      <tr>
		<td style=\"text-align: center; font-weight: bold; padding: 20px 0 40px; 0;\" valign=\"top\">
<!-- end open portal body -->
			
		  <!--<img src=\"templates/default/images/error.gif\" alt=\"\" /><br />-->
		  <span class=\"mktxtcontr\" style=\"font-weight: bold; font-size: 16px;\">$mklang[title]</span>
		  <br />
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
<div style=\"text-align: center; padding-bottom: 10px;\"><span style=\"font-size: 10px;\"><a style=\"text-decoration: none;\" href=\"http://www.mkportal.it/\" target=\"_blank\">MKPortal</a> &copy;2003-2008 <a style=\"text-decoration: none;\" href=\"http://www.mkportal.it/\" target=\"_blank\">mkportal.it</a></br>
<a style=\"text-decoration: none;\" href=\"http://www.rusmkportal.ru/\" target=\"_blank\">RusMKPortal.ru</a> &copy;2007-2009 <a style=\"text-decoration: none;\" href=\"http://www.rusmkportal.ru/\" target=\"_blank\">Russian Support</a></span></div>
<!-- end footer -->

</body>
</html>
";
//End Template

switch($_GET['op']) {
				case 'step0':
    				step0();
    			break;
				case 'step1':
    				step1();
    			break;
				case 'step2':
    				step2();
    			break;
				case 'step3':
    				step3();
    			break;
				case 'step3AEF':
    				step3AEF();
    			break;
				case 'step4':
    				step4();
    			break;
				default:
    				start();
    			break;
		}

function start() {
	global $header, $footer, $BOARD, $mklang;
	
	$content = "
		  <br /><br /><br />	

		  <form name=\"main\" method=\"post\" action=\"mk_install.php?op=step0\">
			{$mklang['selectlang']}<br /><br />
		    <select size=\"1\" name=\"lang\" class=\"bgselect\">
	        <option value=\"ru\">Russian</option>		      
		    
		    </select>";

	$content .= "
		    <br /><br /><br />
		    <input type=\"submit\" value=\"{$mklang['next']}\" name=\"B1\" class=\"mkbutton\" />	
		  </form>
	";

	$output = $header.$content.$footer;
	print $output;	
	exit;	
}

function step0() {
	global $header, $footer, $BOARD, $mklang;

	$lang = $_POST['lang'];
	$BOARD = $_POST['BOARD'];

	$content = "	
		  $step<br /><br /><br />	
		  
		  <form name=\"main\" method=\"post\" action=\"mk_install.php?op=step1\">
			{$mklang['welcome']} <br /><br />
		    <textarea cols=\"100\" rows=\"20\" name=\"licenza\" readonly=\"readonly\">{$mklang['licenza']}</textarea><br /><br />
		    <input type=\"hidden\" name=\"lang\" value=\"$lang\" />
		    <input type=\"hidden\" name=\"BOARD\" value=\"$BOARD\" /><br />
		    <input type=\"submit\" value=\"{$mklang['agree']}\" name=\"B1\" class=\"mkbutton\" />
		  </form>
	";

	$output = $header.$content.$footer;
	print $output;
	exit;
}

function step1() {

	global $header, $footer, $mklang;

	$error = "";
	$lang = $_POST['lang'];
	$BOARD = $_POST['BOARD'];

	$filename = "conf_mk.php";

   	if (!$handle = @fopen($filename, 'a')) {
         $error = "{$mklang['error1']} file conf_mk.php {$mklang['error2']}<br />";
	}
   		if (!$bo = @fwrite($handle, "\n")) {
       		$error = "{$mklang['error1']} file conf_mk.php {$mklang['error2']}<br />";
   		}
		@fclose($handle);



	$filename = "modules/downloads/file";
   	if (!is_writable($filename)) {
         $error .= "{$mklang['error1']} dir modules/downloads/file {$mklang['error2']}<br />";
	}

	$filename = "modules/gallery/album";
   	if (!is_writable($filename)) {
         $error .= "{$mklang['error1']} dir modules/gallery/album {$mklang['error2']}<br />";
	}

	$filename = "modules/gallery/album/tmp";
   	if (!is_writable($filename)) {
         $error .= "{$mklang['error1']} dir modules/gallery/album/tmp {$mklang['error2']}<br />";
	}

	$filename = "modules/reviews/images";
   	if (!is_writable($filename)) {
         $error .= "{$mklang['error1']} dir modules/reviews/images {$mklang['error2']}<br />";
	}

	$filename = "modules/reviews/images/tmp";
   	if (!is_writable($filename)) {
         $error .= "{$mklang['error1']} dir modules/reviews/images/tmp {$mklang['error2']}<br />";
	}

	$filename = "blog/images";
   	if (!is_writable($filename)) {
         $error .= "{$mklang['error1']} dir blog/images {$mklang['error2']}<br />";
	}

	$filename = "blog/images/tmp";
   	if (!is_writable($filename)) {
         $error .= "{$mklang['error1']} dir blog/images/tmp {$mklang['error2']}<br />";
	}

	$filename = "cache/tmp_block.php";
   	if (!$handle = @fopen($filename, 'a')) {
         $error .= "{$mklang['error1']} dir cache {$mklang['error3']} file cache/tmp_block.php {$mklang['error2']}<br />";
	}

	$filename = "blog";
   	if (!is_writable($filename)) {
         $error .= "{$mklang['error1']} dir blog {$mklang['error2']}<br />";
	}

		@fclose($handle);

	if (!$error) {
		step22($lang, $BOARD);
		exit;
	}
	$content = $error;
	$output = $header.$content.$footer;
	print $output;
	exit;
}

function step22($lang, $BOARD) {

	global $header, $footer, $_SERVER, $mklang;

	$error = "";


//Meo Changed in C 0.1.c for PHPBB3 support (added only option in select)
	$content = "	
		  $step<br /><br /><br />

	<form name=\"main1\" method=\"post\" action=\"mk_install.php?op=step2\">
		<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">
		   <tr>
			<td>
			{$mklang['chooseboard']}<br /><br />
			<input type=\"hidden\" name=\"lang\" value=\"$lang\" />
			{$mklang['choosewarn']}<br /><br />			
		    		<select size=\"1\" name=\"BOARD\" class=\"bgselect\">
		      		<option value=\"SMF\">SMF External Board</option>
	                <option value=\"SMF2\">SMF2 External Board</option>
	                <option value=\"IPB3\">IPB 3  External Board</option>
		      		<option value=\"IPB\">IPB 2  External Board</option>
		      		<option value=\"IPB13\">IPB 1.3  External Board</option>
		      		<option value=\"PHPBB\">PhpBB2  External Board</option>
				<option value=\"PHPBB3\">PhpBB3  External Board</option>
		      		<option value=\"VB\">VBulletin  External Board</option>
				<option value=\"MYBB\">MyBB  External Board</option>
		    		</select>
			<td>
		   </tr>
		   <tr>
		      <td align=\"center\"><br /><input type=\"submit\" value=\"{$mklang['gostring']}\" name=\"B1\" class=\"mkbutton\" /></td>
		   </tr>
		</table>
	</form>
	";
// End changed
	$output = $header.$content.$footer;
	print $output;
	exit;
}


function step2() {

	global $header, $footer,  $_SERVER, $mklang;
	$BOARD = $_POST['BOARD'];
	$lang = $_POST['lang'];
	$error = "";

	$siteurl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
	$siteurl = str_replace("/mkportal/mk_install.php", "", $siteurl);
	$siteurl = str_replace ("http:///", "http://", $siteurl);

	$forumpathtype = "";
	if ($BOARD == "AEF") {
		$forumpath = "aeforum";
		$forumpathtype = "readonly";
	}
	$content = "	
		  $step<br /><br /><br />

		  <form name=\"main1\" method=\"post\" action=\"mk_install.php?op=step3\">

		  <table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">
		    <tr>
		      <td>
			<input type=\"hidden\" name=\"lang\" value=\"$lang\" />
			<input type=\"hidden\" name=\"BOARD\" value=\"$BOARD\" />
		      </td>		      
		    </tr>
		    <tr>
		      <td align=\"left\">{$mklang['urlstring']}<br /></td>
		    </tr>
		    <tr>
		      <td style=\"padding-top: 5px; text-align: left;\"><input class=\"bgselect\" type=\"text\" name=\"siteurl\" value=\"$siteurl\" size=\"60\"/></td>
		    </tr>
		    <tr>
		      <td align=\"left\"><br /><br /><br />{$mklang['pathstring']}</td>
		    </tr>
		    <tr>
		      <td style=\"padding-top: 5px; text-align: left;\"><input class=\"bgselect\" type=\"text\" name=\"forumpath\" value=\"$forumpath\" $forumpathtype size=\"60\" /></td>
		    </tr>
		    <tr>
		      <td align=\"center\"><br /><input type=\"submit\" value=\"{$mklang['gostring']}\" name=\"B1\" class=\"mkbutton\" /></td>
		    </tr>
		  </table>
		  </form>
	";
	$output = $header.$content.$footer;
	print $output;
	exit;
}

function step3() {

	global $header, $footer, $mklang;

	$error = "";
	$lang = $_POST['lang'];
	$BOARD = $_POST['BOARD'];

//da cambiare a seconda della board
	switch($BOARD) {
			case 'AEF':
    				$confff = "universal.php";
    			break;
    			case 'IPB3':
    				$confff = "conf_global.php";
    			break;
				case 'IPB':
    				$confff = "conf_global.php";
    			break;
				case 'PHPBB':
    				$confff = "config.php";
    			break;
//Meo: Added in C 0.1.c for PHPBB3 support
			case 'PHPBB3':
    				$confff = "config.php";
    			break;
//End
				case 'VB':
    				$confff = "includes/config.php";
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
				default:
    				$confff = "Settings.php";
    			break;
	}

	//Language for conf_mk.php
	$mkconflang = "Russian";
    if ($BOARD == "PHPBB3") {
		$mkconflang = "UTF8";
	}
	 if ($BOARD == "IPB3") {
		$mkconflang = "UTF8";
	}
	$siteurl = $_POST['siteurl'];
	$forumpath = $_POST['forumpath'];

	if (!$siteurl || !$forumpath) {
		$error = $mklang['error4'];
	}

	$filename = "../$forumpath/$confff";

   	if (!$handle = @fopen($filename, 'r')) {
         $error = $mklang['error5'].$confff.$mklang['error5b'];
	}

	if (!$error) {
		$defaultfw = "0";
		if ($BOARD == "AEF") {
			$defaultfw = "1";
		}
		$content = "<?php\n\n \$FORUM_PATH = \"$forumpath\"; \n \$FORUM_VIEW = \"$defaultfw\"; \n \$PORTAL_VIEW = \"0\"; \n \$FORUM_CD = \"1\"; \n \$FORUM_CS = \"1\"; \n \$SITE_NAME = \"MKPortal\";  \n \$SITE_URL = \"$siteurl\";  \n \$ADMIN_PATH = \"admin\"; \n \$MK_TEMPLATE = \"rusmkportal\"; \n \$MK_LANG = \"$mkconflang\"; \n \$MK_EDITOR = \"BBCODE\"; \n \$MK_BOARD = \"$BOARD\"; \n \$MK_TIMEDIFF = \"0\"; \n \$MK_OFFLINE = \"0\"; \n \$MK_DISABLEGZIP = \"0\"; \n \$MK_PORTALWIDTH = \"780\"; \n \$MK_COLUMNWIDTH = \"140\"; \n \$MK_DISABLENAV = \"0\"; \n \$MK_LOADLEFTC = \"1\"; \n \$MK_LOADRIGHTC = \"1\"; \n \$MK_UNLOADLEFTF = \"0\"; \n \$MK_UNLOADRIGHTF = \"0\"; \n \$MK_REFERER = \"0\"; \n ?>";
		$filename = "conf_mk.php";
   		if (!$handle = fopen($filename, 'w')) {
         	print $mklang['error6'].$confff;
         	exit;
   		}
   		if (!fwrite($handle, $content)) {
       		print $mklang['error7'].$confff.$mklang['error7b'];
       		exit;
   		}
		fclose($handle);

		if ($BOARD == "AEF") {
			header("Location: $siteurl/aeforum/setup/index.php");
		} else { 
			step4($forumpath, $lang, $BOARD);
			exit;
		}
	}
	$content = $error;
	$output = $header.$content.$footer;
	print $output;
	exit;


}


function step4($forumpath, $lang, $BOARD) {

	global $header, $footer, $mklang;

	//Block titles
	$mtopic = "Latest Topics";
	$mperson = "Personal Menu";
	$monline = "Online Users";
	$mmain = "Main Menu";
	$mforum = "Board Menu";
	$mstats = "Site Stats";
	$mshout = "Last Shouts";
	$mrandomg = "Random Image";
	$mchat = "Chat";
	$mcalend = "Calendar";
	$mnews = "Latest News";
	$mpoll = "Poll";


//da cambiare a seconda della board

	switch($BOARD) {
				case 'AEF':
    				$confff = "universal.php";
    				break;
    				case 'IPB3':
    				$confff = "conf_global.php";
    				break;
				case 'IPB':
    				$confff = "conf_global.php";
    				break;
				case 'PHPBB':
    				$confff = "config.php";
				break;
//Meo: Added in C 0.1.c for PHPBB3 support
				case 'PHPBB3':
    				$confff = "config.php";
				$chrst = " CHARACTER SET `utf8` COLLATE `utf8_bin`";
				break;
//End
				case 'VB':
    				$confff = "includes/config.php";
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
				default:
    				$confff = "Settings.php";
    			break;
	}
	//define('IN_PHPBB', true);
	require "../$forumpath/$confff";

	switch($BOARD) {
				case 'AEF':
    					$dbuser = $globals['user'];
					$dbpasswd = $globals['password'];
					$dbname = $globals['database'];
					$dbhost = $globals['server'];
    				break;
    				case 'IPB3':
    				$dbhost = $INFO['sql_host'];
					$dbname = $INFO['sql_database'];
					$dbuser = $INFO['sql_user'];
					$dbpasswd = $INFO['sql_pass'];
    				break;
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
//Meo: Added in C 0.1.c for PHPBB3 support
				case 'PHPBB3':
    					$dbhost = $dbhost;
					$dbname = $dbname;
					$dbuser = $dbuser;
					$dbpasswd = $dbpasswd;
    				break;
//End
				case 'VB':
    				$dbhost = $config['MasterServer']['servername'];
  				$dbname = $config['Database']['dbname'];
  				$dbuser = $config['MasterServer']['username'];
  				$dbpasswd = $config['MasterServer']['password'];
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
    			case 'SMF2':
    				$dbhost = $db_server;
					$dbname = $db_name;
					$dbuser = $db_user;
					$dbpasswd = $db_passwd;
    			    break;
				default:
    				$dbhost = $db_server;
					$dbname = $db_name;
					$dbuser = $db_user;
					$dbpasswd = $db_passwd;
    				break;
	}

		$checkdb_conn = mysql_connect($dbhost, $dbuser, $dbpasswd);
		mysql_select_db($dbname);

	if (!$checkdb_conn) {
		echo "Error, Couldn't connect to database";
		exit;
	}
//Meo: Added in C 0.1.c for PHPBB3 support
	$chrst = "";
	if ($BOARD == "PHPBB3") {
		$mymysql_version = mysql_get_server_info($checkdb_conn);
		if (version_compare($mymysql_version, '4.1.3', '>=')) {
			@mysql_query("SET NAMES 'utf8'", $checkdb_conn);
			$chrst = " CHARACTER SET `utf8` COLLATE `utf8_general_ci`";
		} else {
			"Sorry you need MySQL >= 4.1.3 to install this Mkportal Version.";
			exit;
		}
	}
	if ($BOARD == "IPB3") {
					$chrst = " CHARACTER SET `utf8` COLLATE `utf8_general_ci`";
	}
if ($BOARD == "IPB3") {
$BOARD = "IPB3";
	}
//Is MKPortal already installed?
$query = "SELECT id FROM `mkp_blocks` LIMIT 1";
$exists = mysql_query($query);
if ($exists) {
	$content = $mklang['exists'];
	$output = $header.$content.$footer;
	print $output;
	mysql_close();
	exit;

}

//Meo: WARNING Added in C 0.1.c for PHPBB3 support variable to all the tables
//Install database tables
$query1="
	CREATE TABLE `mkp_blocks` (
  `id` int(11) NOT NULL auto_increment,
  `file` varchar(255) NOT NULL default '',
  `title` varchar(255) NOT NULL default '',
  `position` varchar(20) NOT NULL default 'sinistra',
  `progressive` int(3) NOT NULL default '100',
  `active` varchar(10) default NULL,
  `personal` int(2) NOT NULL default '0',
  `content` text NULL,
  `perms` text NULL,
  `modules` text NULL,
  PRIMARY KEY  (`id`)
)$chrst;";

mysql_query($query1);

$query2 = "INSERT INTO `mkp_blocks` (`id`, `file`, `title`, `position`, `progressive`, `active`, `personal`, `content`) VALUES (3, 'last_forum_post.php', '$mtopic', 'destra', 2, 'checked', 0, '')";
$query3 = "INSERT INTO `mkp_blocks` (`id`, `file`, `title`, `position`, `progressive`, `active`, `personal`, `content`) VALUES (4, 'login.php', '$mperson', 'sinistra', 2, 'checked', 0, '')";
$query4 = "INSERT INTO `mkp_blocks` (`id`, `file`, `title`, `position`, `progressive`, `active`, `personal`, `content`) VALUES (5, 'online.php', '$monline', 'sinistra', 3, 'checked', 0, '')";
$query5 = "INSERT INTO `mkp_blocks` (`id`, `file`, `title`, `position`, `progressive`, `active`, `personal`, `content`) VALUES (6, 'sitenav.php', '$mmain', 'sinistra', 1, 'checked', 0, '')";
$query6 = "INSERT INTO `mkp_blocks` (`id`, `file`, `title`, `position`, `progressive`, `active`, `personal`, `content`) VALUES (40, 'forumnav.php', '$mforum', 'destra', 1, 'checked', 0, '')";
$query7 = "INSERT INTO `mkp_blocks` (`id`, `file`, `title`, `position`, `progressive`, `active`, `personal`, `content`) VALUES (44, 'site_stat.php', '$mstats', 'destra', 5, 'checked', 0, '')";
$query8 = "INSERT INTO `mkp_blocks` (`id`, `file`, `title`, `position`, `progressive`, `active`, `personal`, `content`) VALUES (45, 'last_urlo.php', '$mshout', 'destra', 3, 'checked', 0, '')";
$query9 = "INSERT INTO `mkp_blocks` (`id`, `file`, `title`, `position`, `progressive`, `active`, `personal`, `content`) VALUES (46, 'random_pic.php', '$mrandomg', 'sinistra', 5, 'checked', 0, '')";
$query10 = "INSERT INTO `mkp_blocks` (`id`, `file`, `title`, `position`, `progressive`, `active`, `personal`, `content`) VALUES (47, 'chat.php', '$mchat', 'sinistra', 4, 'checked', 0, '')";
$query12 = "INSERT INTO `mkp_blocks` (`id`, `file`, `title`, `position`, `progressive`, `active`, `personal`, `content`) VALUES (49, 'news.php', '$mnews', 'centro', 1, 'checked', 0, '')";
$query13 = "INSERT INTO `mkp_blocks` (`id`, `file`, `title`, `position`, `progressive`, `active`, `personal`, `content`) VALUES (64, 'poll.php', '$mpoll', 'sinistra', 6, 'checked', 0, '')";


//mysql_query($query2);
mysql_query($query3);
mysql_query($query4);
mysql_query($query5);
mysql_query($query6);
mysql_query($query7);
mysql_query($query8);
mysql_query($query9);
mysql_query($query10);
mysql_query($query11);
mysql_query($query12);
//mysql_query($query13);

$query14="
CREATE TABLE `mkp_chat` (
  `id` int(10) NOT NULL default '0',
  `nick` varchar(40) NOT NULL default '',
  `run_time` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`)
)$chrst;";

mysql_query($query14);

$query15="
CREATE TABLE `mkp_config` (
  `id` int(11) NOT NULL auto_increment,
  `chiave` varchar(255) NOT NULL default '',
  `valore` text NULL,
  PRIMARY KEY  (`id`)
)$chrst;";

mysql_query($query15);
#
# Dumping data for table `mkp_config`
#

$query16 = "INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('counter', '0')";
$query17 = "INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('download_sec_page', '10')";
$query18 = "INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('download_file_page', '20')";
$query19 = "INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('gallery_sec_page', '9')";
$query20 = "INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('gallery_file_page', '10')";
$query21 = "INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('chat_server', 'irc.example.com')";
$query22 = "INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('chat_port', '6667')";
$query23 = "INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('chat_channel', '#channel_name')";
$query24 = "INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('urlo_page', '20')";
$query25 = "INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('urlo_max', '300')";
$query26 = "INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('urlo_block', '10')";
$query27 = "INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('upload_file_max', '1000')";
$query28 = "INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('upload_image_max', '1000')";
$query29 = "INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('news_page', '10')";
$query30 = "INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('news_block', '10')";
$query31 = "INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('poll_active', '0')";
$query32 = "INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('news_words', '0')";
$query33 = "INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('news_html', '0')";
$query34 = "INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('mod_reviews', '0')";
$query35 = "INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('rev_sec_page', '10')";
$query36 = "INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('rev_file_page', '10')";
$query37 = "INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('quote_page', '50')";
$query38 = "INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('mod_quote', '0')";
$query39 = "INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('poll_page', '0')";
$query40 = "INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('mod_poll', '0')";
$query41 = "INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('contact_send', 'site@site.ru')";
$query42 = "INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('contact_ip', '0')";
$query43 = "INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('mod_contact', '0')";
$query44 = "INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('mod_recommend', '0')";

mysql_query($query16);
mysql_query($query17);
mysql_query($query18);
mysql_query($query19);
mysql_query($query20);
mysql_query($query21);
mysql_query($query22);
mysql_query($query23);
mysql_query($query24);
mysql_query($query25);
mysql_query($query26);
mysql_query($query27);
mysql_query($query28);
mysql_query($query29);
mysql_query($query30);
mysql_query($query31);
mysql_query($query32);
mysql_query($query33);
mysql_query($query34);
mysql_query($query35);
mysql_query($query36);
mysql_query($query37);
mysql_query($query38);
mysql_query($query39);
mysql_query($query40);
mysql_query($query41);
mysql_query($query42);
mysql_query($query43);
mysql_query($query44);

//added in M.09

$query16 = "INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('approval_quote', '0')";
$query17 = "INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('approval_blog', '0')";
$query18 = "INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('approval_gallery', '0')";
$query19 = "INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('approval_download', '0')";
$query20 = "INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('approval_news', '0')";
$query21 = "INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('approval_topsite', '0')";
$query22 = "INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('approval_review', '0')";
$query23 = "INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('watermark_enable', '0')";
$query24 = "INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('watermark_pos', '2')";
$query25 = "INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('watermark_level', '60')";

mysql_query($query16);
mysql_query($query17);
mysql_query($query18);
mysql_query($query19);
mysql_query($query20);
mysql_query($query21);
mysql_query($query22);
mysql_query($query23);
mysql_query($query24);
mysql_query($query25);

//added in M1.1.1
$query = "INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('foot_logo', '1');";
mysql_query($query);
$query = "INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('foot_version', '1');";
mysql_query($query);
$query = "INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('foot_debug', '1');";
mysql_query($query);

//Added in C1.2 rc1
//$query = "INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('referer', '0')";
//mysql_query($query); //moved to conf_mk.php
$query = "INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('cp_tpl', '0')";
mysql_query($query);
$query = "INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('noicons', '0')";
mysql_query($query);
$query = "INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('postwhitelist', '')";
mysql_query($query);
$query = "INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('metadesc', '')";
mysql_query($query);
$query = "INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('metakey', '')";
mysql_query($query);
$query = "INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('thumb_max_dimen', '120')";
mysql_query($query);
$query = "INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('square_thumbs', '0')";
mysql_query($query);
$query = "INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('thumb_jpg_quality', '90')";
mysql_query($query);

//Added in C1.2 rc2
$query = "INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('classic_thumbs', '0')";
mysql_query($query);
$query = "INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('rss_media', '0')";
mysql_query($query);
$query = "INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('rss_css', '1')";
mysql_query($query);
$query = "INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('rss_parser', 'mkp')";
mysql_query($query);
$query = "INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('rss_merge', '0')";
mysql_query($query);
// C1.2.2 R0.0.2
$query = "INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('cache', '1')";
mysql_query($query);
$query = "INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('cache_time', '60')";
mysql_query($query);
$query = "INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('antibot_chek', '1')";
mysql_query($query);
// update r101
$query = "INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('rewrite_url', '0')";
mysql_query($query);
$query = "INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('rewrite_step', '/')";
mysql_query($query);
$query = "INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('modules', '0')";
mysql_query($query);


$query39="
CREATE TABLE `mkp_download` (
  `id` int(10) NOT NULL auto_increment,
  `idcategoria` int(10) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `description` text NULL,
  `file` text NULL,
  `downloads` int(10) NOT NULL default '0',
  `click` int(10) NOT NULL default '0',
  `data` int(10) NOT NULL default '0',
  `rate` int(10) NOT NULL default '0',
  `trate` int(10) NOT NULL default '0',
  `screen1` varchar(255) NOT NULL default '',
  `screen2` varchar(255) NOT NULL default '',
  `demo` varchar(255) NOT NULL default '',
  `autore` varchar(40) NOT NULL default '',
  `idauth` int(10) NOT NULL default '0',
  `peso` int(11) NOT NULL default '0',
  `validate` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`id`)
)$chrst;";

mysql_query($query39);


$query40="
CREATE TABLE `mkp_download_comments` (
  `id` int(10) NOT NULL auto_increment,
  `identry` int(10) NOT NULL default '0',
  `autore` varchar(255) NOT NULL default '',
  `testo` text NULL,
  `data` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`)
)$chrst;";

mysql_query($query40);


$query41="
CREATE TABLE `mkp_download_sections` (
  `id` int(11) NOT NULL auto_increment,
  `evento` varchar(255) NOT NULL default '',
  `descrizione` text NULL,
  `position` int(4) NOT NULL default '1',
  `father` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`)
)$chrst;";

mysql_query($query41);

$query42="
CREATE TABLE `mkp_gallery` (
  `id` int(11) NOT NULL auto_increment,
  `evento` int(4) NOT NULL default '0',
  `titolo` varchar(255) NOT NULL default '',
  `descrizione` varchar(255) NOT NULL default '',
  `file` text NULL,
  `click` int(10) NOT NULL default '0',
  `rate` int(10) NOT NULL default '0',
  `trate` int(10) NOT NULL default '0',
  `autore` varchar(40) NOT NULL default '',
  `idauth` int(10) NOT NULL default '0',
  `peso` int(11) NOT NULL default '0',
  `data` int(10) NOT NULL default '0',
  `validate` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`id`)
)$chrst;";

mysql_query($query42);

$query43="
CREATE TABLE `mkp_gallery_comments` (
  `id` int(10) NOT NULL auto_increment,
  `identry` int(10) NOT NULL default '0',
  `autore` varchar(255) NOT NULL default '',
  `testo` text NULL,
  `data` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`)
)$chrst;";

mysql_query($query43);


$query44="
CREATE TABLE `mkp_gallery_events` (
  `id` int(11) NOT NULL auto_increment,
  `evento` varchar(255) NOT NULL default '',
  `position` int(4) NOT NULL default '1',
  `father` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`)
)$chrst;";

mysql_query($query44);


$query45="
CREATE TABLE `mkp_news` (
  `id` int(11) NOT NULL auto_increment,
  `idcategoria` int(10) NOT NULL default '0',
  `idautore` int(10) NOT NULL default '0',
  `titolo` varchar(255) NOT NULL default '',
  `autore` varchar(34) NOT NULL default '',
  `short_testo` text NULL,
  `testo` text NULL,
  `data` int(10) NOT NULL default '0',
  `validate` tinyint(1) NOT NULL default '1',
  `pinned` tinyint(1) NOT NULL default '0',
  `rate` int(10) NOT NULL default '0',
  `trate` int(10) NOT NULL default '0',
  `hits` int(11) NOT NULL default '0',
  `totalcomm` int(10) NOT NULL default '0',
  `allow_main` tinyint(1) NOT NULL default '1',
  `allow_comm` tinyint(1) NOT NULL default '1',
  `allow_rating` tinyint(1) NOT NULL default '1',
  `descr` varchar(200) NOT NULL default '',
  `keywords` text NOT NULL,
  PRIMARY KEY  (`id`)
)$chrst;";

mysql_query($query45);

$query46="
CREATE TABLE `mkp_comments` (
  `id` int(11) NOT NULL auto_increment,
  `cid` int(11) NOT NULL default '0',
  `module` varchar(60) NOT NULL default '',
  `data` int(10) NOT NULL default '0',
  `memid` int(11) NOT NULL default '0',
  `name` varchar(25) NOT NULL default '',
  `memip` varchar(60) default NULL,
  `comment` text NOT NULL,
  `status` int(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
)$chrst;";

mysql_query($query46);
/*if ($lang == "it") {
	
$query46 = "INSERT INTO `mkp_news` (`id`, `idcategoria`, `idautore`, `titolo`, `autore`, `testo`, `data`) VALUES (1, 1, 1, 'Benvenuti in MKPortal', 'meo', '\r\n        \r\n        \r\n        \r\n        \r\n        <div style=\"float: left\"><img src=\"mkportal/include/mkbox.jpg\" align=\"left\" width=\"197\" height=\"311\" alt=\"\" /></div>\r\n\r\n<b>MKPortal</b> &egrave; un portale/CMS che si integra perfettamente  con i pi&ugrave; popolari software di forum (Simple Machines [SMF], Invision Power Board 1.3 e 2.x [IP.Board], phpBB, phpBB3, vBulletin, MyBB, e AEF). Esso usa il sistema di gestione utenti e altre funzioni dal forum e aggiunge molti potenti moduli per creare e gestire un sito web leggero ma potente. MKPortal ha una interfaccia utente intuitiva ed &egrave; molto semplice da installare e da gestire.<br /><br />\r\nMkportal &egrave; un prodotto che si va ad inserire in una categoria particolare.<br />L\'obiettivo di mkportal &egrave; di fornire all\'utente un portal-system di nuova concezione <br />Esso usa i membri del forum e le altre funzioni e non ha bisogno di modifiche ai file del forum per funzionare. Necessita solo di una semplice modifica al file della board per vederla integrata all\'interno del Portale ma non &egrave; obbligatoria. MKPortal condivide il database del forum ma tutte le tabelle del database sono separate rendendo semplice il processo di installazione e disinstallazione. Questa divisione unica tra CMS e forum permette ai webmaster di eseguire e sviluppare applicazioni per il forum scelto e applicare patch di sicurezza e aggiornamenti via via che vengono rilasciati.\r\n\r\n<p><b>Caratteristiche di MKPortal:</b></p>\r\n<ul>\r\n<li>Integrazione Totale con i membri del forum. No doppi-login!</li>\r\n<li>Xhtml e css valido</li>\r\n<li>Importazione del Tema/Skin del forum (utilizza la skin del forum per il portale)</li>\r\n<li>Tema/Skin selezionabile (permetti agli utenti di scegliere la skin del portale e del forum)</li>\r\n<li>Lingua selezionabile (permetti agli utenti di scegliere la lingua del portale e del forum)</li>\r\n<li>Pi&ugrave; di 25 Blocchi per il tuo portale inclusi con la possibilit&agrave; di creare blocchi in php e html personalizzati</li>\r\n<li>Creazione di pagine interne in php e html personalizzate</li>\r\n</ul>\r\n\r\n<p><b>Moduli inclusi:</b></p>\r\n<ul>\r\n<li><a href=\"index.php?ind=blog\">Blog</a> (dai agli utenti la possibilit&agrave; di hostare un proprio blog)</li>\r\n<li><a href=\"index.php?ind=gallery\">Gallery</a> (carica, organizza, vota e commenta le immagini)</li>\r\n<li><a href=\"index.php?ind=urlobox\">Urlobox</a> (possibilit&agrave; di lasciar dei messaggi istantanei sullo stile di un guestbook)</li>\r\n<li><a href=\"index.php?ind=downloads\">Downloads</a> (possibilit&agrave; di caricare file, gestirli votarli e commentarli)</li>\r\n<li><a href=\"index.php?ind=news\">News</a> (creare delle news e gestirle, classificarle e archiviarle comodamente)</li>\r\n<li><a href=\"index.php?ind=topsite\">TopSite</a> (carica, gestisci, banner, link e vota  siti web)</li>\r\n<li><a href=\"index.php?ind=reviews\">Recensioni</a> (carica, organizza, vota e commenta le recensioni)</li>\r\n<li><a href=\"index.php?ind=quote\">Citazioni</a> (create e gestisci citazioni)</li>\r\n<li><a href=\"index.php?ind=chat\">Chat</a> (chiacchierare online con gli altri utenti direttamente dal vostro portale)</li>\r\n</ul>\r\n\r\n        \r\n        \r\n        \r\n        \r\n        \r\n        ', 1080395927)";

}*/


	
//$query46 = "INSERT INTO `mkp_news` (`id`, `idcategoria`, `idautore`, `titolo`, `autore`, `testo`, `data`) VALUES (1, 1, 1, 'Welcome to MKPortal', 'meo', '\r\n        \r\n        \r\n        \r\n    	\r\n		<div style=\"float: left\"><img src=\"mkportal/include/mkbox.jpg\" align=\"left\" width=\"197\" height=\"311\" alt=\"\" /></div>\r\n\r\n<b>MKPortal</b> is a free portal/CMS system which seamlessly integrates with the most popular forum softwares (Simple Machines [SMF], Invision Power Board 1.3 and 2.x [IP.Board], phpBB, phpBB3, vBulletin, MyBB, and AEF). It uses the forum user management system and other features and adds many powerful modules to create and manage a light but powerful web site. MKPortal has an intuitive user interface and is very simple to install and administer.<br /><br />\r\nMKPortal is a new and unique concept in Content Management Systems. Mkportal does not include an actual forum, but remains a separate application which runs off of the forum backend. It uses forum member and other functions and does not require any modifications to the forum files to function. It does require simple editing of forum files in order to view the forum inside the Portal but these modifications are optional. MKPortal shares the forum database but all portal database tables are separate making the install and uninstall process easy. This unique separation of CMS and forum programs allows webmasters to run the main development branch of their chosen forum software so that they can apply forum security patches and upgrades as they are released.\r\n\r\n<p><b>MKPortal Features:</b></p>\r\n<ul>\r\n<li>Full integration with forum member database. No double-logins!</li>\r\n<li>Valid xhtml and css</li>\r\n<li>Forum Themes/Skins importing (use forum skins for portal)</li>\r\n<li>User-selectable Themes/Skins (lets members choose portal and forum skins)</li>\r\n<li>User-selectable Languages (lets members choose portal and forum language)</li>\r\n<li>More than 25 Portal blocks included with the ability to create custom html and php blocks</li>\r\n<li>Create custom html and php internal pages</li>\r\n</ul>\r\n\r\n<p><b>Included Modules:</b></p>\r\n<ul>\r\n<li><a href=\"index.php?ind=blog\">Blog</a> (give members their own hosted, full-featured blog)</li>\r\n<li><a href=\"index.php?ind=gallery\">Gallery</a> (upload, manage, rate and comment images with categories)</li>\r\n<li><a href=\"index.php?ind=urlobox\">Shoutbox</a> (post messages in the style of a guestbook and display them globally in the header)</li>\r\n<li><a href=\"index.php?ind=downloads\">Downloads</a> (upload, manage, rate and comment files with categories)</li>\r\n<li><a href=\"index.php?ind=news\">News</a> (create, manage, rate and comment news articles with categories)</li>\r\n<li><a href=\"index.php?ind=topsite\">TopSite</a> (upload, manage, and rate website link banners)</li>\r\n<li><a href=\"index.php?ind=reviews\">Reviews</a> (create, manage, rate and comment reviews with categories)</li>\r\n<li><a href=\"index.php?ind=quote\">Quotes</a> (create and manage quotes)</li>\r\n<li><a href=\"index.php?ind=chat\">Chat</a> (chat online with other users directly from your portal)</li>\r\n</ul>\r\n\r\n		\r\n		\r\n		\r\n		\r\n		\r\n		', 1080395927)";



/*if ($lang == "fr") {
	
$query46 = "INSERT INTO `mkp_news` (`id`, `idcategoria`, `idautore`, `titolo`, `autore`, `testo`, `data`) VALUES (1, 1, 1, 'Welcome to MKPortal', 'meo', '\r\n        \r\n        \r\n        \r\n    	\r\n		<div style=\"float: left\"><img src=\"mkportal/include/mkbox.jpg\" align=\"left\" width=\"197\" height=\"311\" alt=\"\" /></div>\r\n\r\n<b>MKPortal</b> est un systeme de portail gratuit qui est compatible avec les forums les plus populaires (Simple Machines [SMF], Invision Power Board 1.3 and 2.x [IP.Board], phpBB, phpBB3, vBulletin, MyBB, et AEF). Il utilise une zone d\'administration comme le forum et ajoute diff&eacute;rents modules pour pouvoir cr&eacute;er et manager un site web l&eacute;ger mais tr&egrave;s complet. MKPortal utilise un espace membre tr&egrave;s intuitif ce qui le rend tr&egrave;s simple &agrave; installer et &agrave; administrer.<br /><br />\r\nMKPortal est un concept nouveau et unique dans le syst&egrave;me de gestion. Mkportal n\'inclut pas de forum, mais est un script s&eacute;par&eacute; qui fonctionne avec un forum. Il utilise les membres du forum et autres fonctions mais ne n&eacute;cessite pas de modifications sur les fichiers du forum pour fonctionner. Il requiert juste une simple &eacute;dition des fichiers du forum pour visualiser le forum &agrave; l\'int&eacute;rieur du portail mais cette modification est optionnelle. MKPortal utilise les tables du forums mais la base de donn&eacute;e du portail avec ses tables sont s&eacute;par&eacute;s pour pouvoir faciliter l\'installation et la d&eacute;sinstallation du portail. Cette s&eacute;paration entre entre le portail et le forum permet aux webmestres de suivre le d&eacute;veloppement de leur forum pour pouvoir appliquer les diff&eacute;rents patchs de s&eacute;curit&eacute; et de mettre &agrave; jour leur forum d&egrave;s leur publication.\r\n\r\n<p><b>Fonctionnalit&eacute;s de MKPortal :</b></p>\r\n<ul>\r\n<li>Int&eacute;gration compl&egrave;te avec la base de donn&eacute;e des membres donc pas de double identification !</li>\r\n<li>Validit&eacute; XHTML et CSS</li>\r\n<li>Skin du forum import&eacute; sur le portail (Utilisation du skin du forum sur le portail)</li>\r\n<li>S&eacute;lection du skin par les utilisateurs (les membres peuvent choisir leur skin)</li>\r\n<li>S&eacute;lection du langage par les utilsateurs utilisateurs (les membres peuvent choisir leur propre langage)</li>\r\n<li>Plus de 25 blocks d&eacute;j&agrave; int&eacute;gr&eacute; dans le portail avec la possibilit&eacute; de cr&eacute;er des blocks personnalis&eacute;es en PHP et HTML</li>\r\n<li>Possibilit&eacute; de cr&eacute;er ses propres pages internes en PHP ou en HTML</li>\r\n</ul>\r\n\r\n<p><b>Modules inclus :</b></p>\r\n<ul>\r\n<li><a href=\"index.php?ind=blog\">Blog</a> (Permet &agrave; tous les membres de pouvoir cr&eacute;er leurs propres blog)</li>\r\n<li><a href=\"index.php?ind=gallery\">Galerie</a> (upload, gestion, note et commentaire des images rang&eacute;es par cat&eacute;gories)</li>\r\n<li><a href=\"index.php?ind=urlobox\">Shoutbox</a> (Poster des messages &eacute;clair et affichage dans l\'ent&ecirc;te)</li>\r\n<li><a href=\"index.php?ind=downloads\">Downloads</a> (upload, gestion, note et commentaires des t&eacute;l&eacute;chargements rang&eacute;s par cat&eacute;gories)</li>\r\n<li><a href=\"index.php?ind=news\">Nouvelles</a> (syst&egrave;me de nouvelles rang&eacute;es par cat&eacute;gories avec possibilit&eacute; d\'affichage sur le portail)</li>\r\n<li><a href=\"index.php?ind=topsite\">Top Site</a> (Syst&egrave;me d\'annuaire de site avec possibilit&eacute; de noter les sites)</li>\r\n<li><a href=\"index.php?ind=reviews\">Articles</a> (Syst&egrave;me de gestion d\'articles rang&eacute;s par cat&eacute;gories)</li>\r\n<li><a href=\"index.php?ind=quote\">Citations</a> (Cr&eacute;ation et gestion d\'un syst&egrave;me de citations)</li>\r\n<li><a href=\"index.php?ind=chat\">Chat</a> (tchat online avec les autres membres directement par votre portail)</li>\r\n</ul>\r\n\r\n		\r\n		\r\n		\r\n		\r\n		\r\n		', 1080395927)";

}*/

//mysql_query($query46);


$query47="
CREATE TABLE `mkp_categories` (
  `id` int(11) NOT NULL auto_increment,
  `module` varchar(50) NOT NULL default '',
  `title` varchar(100) NOT NULL default '',
  `description` text NOT NULL,
  `img` varchar(100) NOT NULL default '',
  `parentid` int(11) NOT NULL default '0',
  `cstatus` int(1) NOT NULL default '0',
  `ordern` int(11) NOT NULL default '0',
  `topics` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
)$chrst;";

mysql_query($query47);

/*
$cattitle = "Announcements";


$query48 = "INSERT INTO `mkp_news_sections` (`id`, `titolo`, `icona`, `position`) VALUES (1, '$cattitle', '1', 0)";
mysql_query($query48);
*/
$query49="
CREATE TABLE `mkp_pages` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(250) NOT NULL default '',
  `content` text NULL,
   `file` varchar(255) NOT NULL default '',
   `perms` text NULL,
   `active` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
)$chrst;";

mysql_query($query49);

$query50="
CREATE TABLE `mkp_pgroups` (
  `g_id` int(3) NOT NULL default '0',
  `g_title` varchar(23) NOT NULL default '',
  `g_send_news` tinyint(1) NOT NULL default '0',
  `g_mod_news` tinyint(1) NOT NULL default '0',
  `g_access_download` tinyint(1) NOT NULL default '0',
  `g_download_files` tinyint(1) NOT NULL default '0',
  `g_send_download` tinyint(1) NOT NULL default '0',
  `g_mod_download` tinyint(1) NOT NULL default '0',
  `g_access_gallery` tinyint(1) NOT NULL default '0',
  `g_send_gallery` tinyint(1) NOT NULL default '0',
  `g_mod_gallery` tinyint(1) NOT NULL default '0',
  `g_access_urlobox` tinyint(1) NOT NULL default '0',
  `g_send_urlobox` tinyint(1) NOT NULL default '0',
  `g_mod_urlobox` tinyint(1) NOT NULL default '0',
  `g_access_chat` tinyint(1) NOT NULL default '0',
  `g_access_cpa` tinyint(1) NOT NULL default '0',
  `g_access_blog` tinyint(1) NOT NULL default '1',
  `g_send_blog` tinyint(1) NOT NULL default '0',
  `g_access_topsite` tinyint(1) NOT NULL default '1',
  `g_send_topsite` tinyint(1) NOT NULL default '0',
  `g_send_ecard` tinyint(1) NOT NULL default '0',
  `g_access_quote` tinyint(1) NOT NULL default '0',
  `g_send_quote` tinyint(1) NOT NULL default '0',
  `g_send_comments` tinyint(1) NOT NULL default '0',
  `g_access_reviews` tinyint(1) NOT NULL default '0',
  `g_send_reviews` tinyint(1) NOT NULL default '0',
  `g_mod_reviews` tinyint(1) NOT NULL default '0',
  `g_send_poll` tinyint(1) NOT NULL default '0',
  `g_mod_poll` tinyint(1) NOT NULL default '0',
  `g_access_contact` tinyint(1) NOT NULL default '0',
  `g_access_recommend` tinyint(1) NOT NULL default '0',
   
  PRIMARY KEY  (`g_id`)
)$chrst;";

mysql_query($query50);

//da cambiare a seconda della board
switch($BOARD) {
				case 'AEF':
    				$query101 = "INSERT INTO `mkp_pgroups` (`g_id`, `g_title`, `g_send_news`, `g_mod_news`, `g_access_download`, `g_send_download`, `g_mod_download`, `g_access_gallery`, `g_send_gallery`, `g_mod_gallery`, `g_access_urlobox`, `g_send_urlobox`, `g_mod_urlobox`, `g_access_chat`, `g_access_cpa`, `g_send_poll`, `g_mod_poll`, `g_access_contact`, `g_access_recommend`) VALUES (-3, 'Banned', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0)";
					$query102 = "INSERT INTO `mkp_pgroups` (`g_id`, `g_title`, `g_send_news`, `g_mod_news`, `g_access_download`, `g_send_download`, `g_mod_download`, `g_access_gallery`, `g_send_gallery`, `g_mod_gallery`, `g_access_urlobox`, `g_send_urlobox`, `g_mod_urlobox`, `g_access_chat`, `g_access_cpa`, `g_send_poll`, `g_mod_poll`, `g_access_contact`, `g_access_recommend`) VALUES (-1, 'Guests', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0)";
					$query103 = "INSERT INTO `mkp_pgroups` (`g_id`, `g_title`, `g_send_news`, `g_mod_news`, `g_access_download`, `g_send_download`, `g_mod_download`, `g_access_gallery`, `g_send_gallery`, `g_mod_gallery`, `g_access_urlobox`, `g_send_urlobox`, `g_mod_urlobox`, `g_access_chat`, `g_access_cpa`, `g_send_poll`, `g_mod_poll`, `g_access_contact`, `g_access_recommend`) VALUES (2, 'Universal Moderator', 0, 0, 1, 0, 0, 1, 0, 0, 1, 1, 0, 1, 0, 0, 0, 0, 0)";
					$query104 = "INSERT INTO `mkp_pgroups` (`g_id`, `g_title`, `g_send_news`, `g_mod_news`, `g_access_download`, `g_send_download`, `g_mod_download`, `g_access_gallery`, `g_send_gallery`, `g_mod_gallery`, `g_access_urlobox`, `g_send_urlobox`, `g_mod_urlobox`, `g_access_chat`, `g_access_cpa`, `g_send_poll`, `g_mod_poll`, `g_access_contact`, `g_access_recommend`) VALUES (3, 'Moderator', 0, 0, 1, 0, 0, 1, 0, 0, 1, 1, 0, 1, 0, 0, 0, 0, 0)";
					$query105 = "INSERT INTO `mkp_pgroups` (`g_id`, `g_title`, `g_send_news`, `g_mod_news`, `g_access_download`, `g_send_download`, `g_mod_download`, `g_access_gallery`, `g_send_gallery`, `g_mod_gallery`, `g_access_urlobox`, `g_send_urlobox`, `g_mod_urlobox`, `g_access_chat`, `g_access_cpa`, `g_send_poll`, `g_mod_poll`, `g_access_contact`, `g_access_recommend`) VALUES (0, 'Member', 0, 0, 1, 0, 0, 1, 0, 0, 1, 1, 0, 1, 0, 0, 0, 0, 0)";
					mysql_query($query101);
					mysql_query($query102);
					mysql_query($query103);
					mysql_query($query104);
					mysql_query($query105);
    				break;
				case 'IPB':
    				$query51 = "INSERT INTO `mkp_pgroups` (`g_id`, `g_title`, `g_send_news`, `g_mod_news`, `g_access_download`, `g_send_download`, `g_mod_download`, `g_access_gallery`, `g_send_gallery`, `g_mod_gallery`, `g_access_urlobox`, `g_send_urlobox`, `g_mod_urlobox`, `g_access_chat`, `g_access_cpa`, `g_send_poll`, `g_mod_poll`, `g_access_contact`, `g_access_recommend`) VALUES (3, 'Members', 0, 0, 1, 0, 0, 1, 0, 0, 1, 1, 0, 1, 0, 0, 0, 0, 0)";
					$query52 = "INSERT INTO `mkp_pgroups` (`g_id`, `g_title`, `g_send_news`, `g_mod_news`, `g_access_download`, `g_send_download`, `g_mod_download`, `g_access_gallery`, `g_send_gallery`, `g_mod_gallery`, `g_access_urlobox`, `g_send_urlobox`, `g_mod_urlobox`, `g_access_chat`, `g_access_cpa`, `g_send_poll`, `g_mod_poll`, `g_access_contact`, `g_access_recommend`) VALUES (1, 'Validating', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0)";
					$query53 = "INSERT INTO `mkp_pgroups` (`g_id`, `g_title`, `g_send_news`, `g_mod_news`, `g_access_download`, `g_send_download`, `g_mod_download`, `g_access_gallery`, `g_send_gallery`, `g_mod_gallery`, `g_access_urlobox`, `g_send_urlobox`, `g_mod_urlobox`, `g_access_chat`, `g_access_cpa`, `g_send_poll`, `g_mod_poll`, `g_access_contact`, `g_access_recommend`) VALUES (2, 'Guests', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0)";
					mysql_query($query51);
					mysql_query($query52);
					mysql_query($query53);
    				break;
				case 'PHPBB':
    				$query51 = "INSERT INTO `mkp_pgroups` (`g_id`, `g_title`, `g_send_news`, `g_mod_news`, `g_access_download`, `g_send_download`, `g_mod_download`, `g_access_gallery`, `g_send_gallery`, `g_mod_gallery`, `g_access_urlobox`, `g_send_urlobox`, `g_mod_urlobox`, `g_access_chat`, `g_access_cpa`, `g_send_poll`, `g_mod_poll`, `g_access_contact`, `g_access_recommend`) VALUES (3, 'Members', 0, 0, 1, 0, 0, 1, 0, 0, 1, 1, 0, 1, 0, 0, 0, 0, 0)";
					$query52 = "INSERT INTO `mkp_pgroups` (`g_id`, `g_title`, `g_send_news`, `g_mod_news`, `g_access_download`, `g_send_download`, `g_mod_download`, `g_access_gallery`, `g_send_gallery`, `g_mod_gallery`, `g_access_urlobox`, `g_send_urlobox`, `g_mod_urlobox`, `g_access_chat`, `g_access_cpa`, `g_send_poll`, `g_mod_poll`, `g_access_contact`, `g_access_recommend`) VALUES (2, 'Moderators', 0, 0, 1, 0, 0, 1, 0, 0, 1, 1, 0, 1, 0, 0, 0, 0, 0)";
					$query53 = "INSERT INTO `mkp_pgroups` (`g_id`, `g_title`, `g_send_news`, `g_mod_news`, `g_access_download`, `g_send_download`, `g_mod_download`, `g_access_gallery`, `g_send_gallery`, `g_mod_gallery`, `g_access_urlobox`, `g_send_urlobox`, `g_mod_urlobox`, `g_access_chat`, `g_access_cpa`, `g_send_poll`, `g_mod_poll`, `g_access_contact`, `g_access_recommend`) VALUES (9, 'Guests', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0)";
					mysql_query($query51);
					mysql_query($query52);
					mysql_query($query53);
    				break;
//Meo: Added in C 0.1.c for PHPBB3 support
				case 'PHPBB3':

				define('IN_PHPBB', true);
				$phpEx = substr(strrchr(__FILE__, '.'), 1);
				include('../'.$forumpath.'/config.'.$phpEx);
				include('../'.$forumpath.'/includes/constants.'.$phpEx);

				//Get phpBB3 Group IDs
				//GUESTS	
				$query = mysql_query("SELECT group_id FROM " . GROUPS_TABLE . " WHERE group_name = 'GUESTS' LIMIT 1");
				$row = mysql_fetch_array($query);
				$pgroup_guest = $row['group_id'];

				//REGISTERED
				$query = mysql_query("SELECT group_id FROM " . GROUPS_TABLE . " WHERE group_name = 'REGISTERED' LIMIT 1");
				$row = mysql_fetch_array($query);
				$pgroup_reg = $row['group_id'];

				//REGISTERED_COPPA
				$query = mysql_query("SELECT group_id FROM " . GROUPS_TABLE . " WHERE group_name = 'REGISTERED_COPPA' LIMIT 1");
				$row = mysql_fetch_array($query);
				$pgroup_coppa = $row['group_id'];

				//GLOBAL_MODERATORS
				$query = mysql_query("SELECT group_id FROM " . GROUPS_TABLE . " WHERE group_name = 'GLOBAL_MODERATORS' LIMIT 1");
				$row = mysql_fetch_array($query);
				$pgroup_mod = $row['group_id'];

				//BOTS
				$query = mysql_query("SELECT group_id FROM " . GROUPS_TABLE . " WHERE group_name = 'BOTS' LIMIT 1");
				$row = mysql_fetch_array($query);
				$pgroup_bot = $row['group_id'];


					$query100 = "INSERT INTO `mkp_pgroups` (`g_id`, `g_title`, `g_send_news`, `g_mod_news`, `g_access_download`, `g_send_download`, `g_mod_download`, `g_access_gallery`, `g_send_gallery`, `g_mod_gallery`, `g_access_urlobox`, `g_send_urlobox`, `g_mod_urlobox`, `g_access_chat`, `g_access_cpa`, `g_send_poll`, `g_mod_poll`, `g_access_contact`, `g_access_recommend`) VALUES ($pgroup_guest, 'GUESTS', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0)";
					$query101 = "INSERT INTO `mkp_pgroups` (`g_id`, `g_title`, `g_send_news`, `g_mod_news`, `g_access_download`, `g_send_download`, `g_mod_download`, `g_access_gallery`, `g_send_gallery`, `g_mod_gallery`, `g_access_urlobox`, `g_send_urlobox`, `g_mod_urlobox`, `g_access_chat`, `g_access_cpa`, `g_send_poll`, `g_mod_poll`, `g_access_contact`, `g_access_recommend`) VALUES ($pgroup_reg, 'REGISTERED', 0, 0, 1, 0, 0, 1, 0, 0, 1, 1, 0, 1, 0, 0, 0, 0, 0)";
					$query102 = "INSERT INTO `mkp_pgroups` (`g_id`, `g_title`, `g_send_news`, `g_mod_news`, `g_access_download`, `g_send_download`, `g_mod_download`, `g_access_gallery`, `g_send_gallery`, `g_mod_gallery`, `g_access_urlobox`, `g_send_urlobox`, `g_mod_urlobox`, `g_access_chat`, `g_access_cpa`, `g_send_poll`, `g_mod_poll`, `g_access_contact`, `g_access_recommend`) VALUES ($pgroup_coppa, 'REGISTERED_COPPA', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0)";
					$query103 = "INSERT INTO `mkp_pgroups` (`g_id`, `g_title`, `g_send_news`, `g_mod_news`, `g_access_download`, `g_send_download`, `g_mod_download`, `g_access_gallery`, `g_send_gallery`, `g_mod_gallery`, `g_access_urlobox`, `g_send_urlobox`, `g_mod_urlobox`, `g_access_chat`, `g_access_cpa`, `g_send_poll`, `g_mod_poll`, `g_access_contact`, `g_access_recommend`) VALUES ($pgroup_mod, 'GLOBAL_MODERATORS', 0, 0, 1, 0, 0, 1, 0, 0, 1, 1, 0, 1, 0, 0, 0, 0, 0)";
					$query104 = "INSERT INTO `mkp_pgroups` (`g_id`, `g_title`, `g_send_news`, `g_mod_news`, `g_access_download`, `g_send_download`, `g_mod_download`, `g_access_gallery`, `g_send_gallery`, `g_mod_gallery`, `g_access_urlobox`, `g_send_urlobox`, `g_mod_urlobox`, `g_access_chat`, `g_access_cpa`, `g_send_poll`, `g_mod_poll`, `g_access_contact`, `g_access_recommend`) VALUES ($pgroup_bot, 'BOTS', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0)";
					mysql_query($query100);
					mysql_query($query101);
					mysql_query($query102);
					mysql_query($query103);
					mysql_query($query104);
					break;
//End
				case 'VB':
    				$query51 = "INSERT INTO `mkp_pgroups` (`g_id`, `g_title`, `g_send_news`, `g_mod_news`, `g_access_download`, `g_send_download`, `g_mod_download`, `g_access_gallery`, `g_send_gallery`, `g_mod_gallery`, `g_access_urlobox`, `g_send_urlobox`, `g_mod_urlobox`, `g_access_chat`, `g_access_cpa`, `g_send_poll`, `g_mod_poll`, `g_access_contact`, `g_access_recommend`) VALUES (2, 'Members', 0, 0, 1, 0, 0, 1, 0, 0, 1, 1, 0, 1, 0, 0, 0, 0, 0)";
					$query52 = "INSERT INTO `mkp_pgroups` (`g_id`, `g_title`, `g_send_news`, `g_mod_news`, `g_access_download`, `g_send_download`, `g_mod_download`, `g_access_gallery`, `g_send_gallery`, `g_mod_gallery`, `g_access_urlobox`, `g_send_urlobox`, `g_mod_urlobox`, `g_access_chat`, `g_access_cpa`, `g_send_poll`, `g_mod_poll`, `g_access_contact`, `g_access_recommend`) VALUES (5, 'Moderators', 0, 0, 1, 0, 0, 1, 0, 0, 1, 1, 0, 1, 0, 0, 0, 0, 0)";
					$query53 = "INSERT INTO `mkp_pgroups` (`g_id`, `g_title`, `g_send_news`, `g_mod_news`, `g_access_download`, `g_send_download`, `g_mod_download`, `g_access_gallery`, `g_send_gallery`, `g_mod_gallery`, `g_access_urlobox`, `g_send_urlobox`, `g_mod_urlobox`, `g_access_chat`, `g_access_cpa`, `g_send_poll`, `g_mod_poll`, `g_access_contact`, `g_access_recommend`) VALUES (1, 'Guests', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0)";
					$query100 = "INSERT INTO `mkp_pgroups` (`g_id`, `g_title`, `g_send_news`, `g_mod_news`, `g_access_download`, `g_send_download`, `g_mod_download`, `g_access_gallery`, `g_send_gallery`, `g_mod_gallery`, `g_access_urlobox`, `g_send_urlobox`, `g_mod_urlobox`, `g_access_chat`, `g_access_cpa`, `g_send_poll`, `g_mod_poll`, `g_access_contact`, `g_access_recommend`) VALUES (7, 'Super Moderators', 0, 0, 1, 0, 0, 1, 0, 0, 1, 1, 0, 1, 0, 0, 0, 0, 0)";
					mysql_query($query51);
					mysql_query($query52);
					mysql_query($query53);
					mysql_query($query100);
    			break;
				case 'IPB13':
    				$query51 = "INSERT INTO `mkp_pgroups` (`g_id`, `g_title`, `g_send_news`, `g_mod_news`, `g_access_download`, `g_send_download`, `g_mod_download`, `g_access_gallery`, `g_send_gallery`, `g_mod_gallery`, `g_access_urlobox`, `g_send_urlobox`, `g_mod_urlobox`, `g_access_chat`, `g_access_cpa`, `g_send_poll`, `g_mod_poll`, `g_access_contact`, `g_access_recommend`) VALUES (3, 'Members', 0, 0, 1, 0, 0, 1, 0, 0, 1, 1, 0, 1, 0, 0, 0, 0, 0)";
					$query52 = "INSERT INTO `mkp_pgroups` (`g_id`, `g_title`, `g_send_news`, `g_mod_news`, `g_access_download`, `g_send_download`, `g_mod_download`, `g_access_gallery`, `g_send_gallery`, `g_mod_gallery`, `g_access_urlobox`, `g_send_urlobox`, `g_mod_urlobox`, `g_access_chat`, `g_access_cpa`, `g_send_poll`, `g_mod_poll`, `g_access_contact`, `g_access_recommend`) VALUES (1, 'Validating', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0)";
					$query53 = "INSERT INTO `mkp_pgroups` (`g_id`, `g_title`, `g_send_news`, `g_mod_news`, `g_access_download`, `g_send_download`, `g_mod_download`, `g_access_gallery`, `g_send_gallery`, `g_mod_gallery`, `g_access_urlobox`, `g_send_urlobox`, `g_mod_urlobox`, `g_access_chat`, `g_access_cpa`, `g_send_poll`, `g_mod_poll`, `g_access_contact`, `g_access_recommend`) VALUES (2, 'Guests', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0)";
					mysql_query($query51);
					mysql_query($query52);
					mysql_query($query53);
    			break;
			case 'MYBB':
    				$query51 = "INSERT INTO `mkp_pgroups` (`g_id`, `g_title`, `g_send_news`, `g_mod_news`, `g_access_download`, `g_send_download`, `g_mod_download`, `g_access_gallery`, `g_send_gallery`, `g_mod_gallery`, `g_access_urlobox`, `g_send_urlobox`, `g_mod_urlobox`, `g_access_chat`, `g_access_cpa`, `g_send_poll`, `g_mod_poll`, `g_access_contact`, `g_access_recommend`) VALUES (2, 'Members', 0, 0, 1, 0, 0, 1, 0, 0, 1, 1, 0, 1, 0, 0, 0, 0, 0)";
					$query52 = "INSERT INTO `mkp_pgroups` (`g_id`, `g_title`, `g_send_news`, `g_mod_news`, `g_access_download`, `g_send_download`, `g_mod_download`, `g_access_gallery`, `g_send_gallery`, `g_mod_gallery`, `g_access_urlobox`, `g_send_urlobox`, `g_mod_urlobox`, `g_access_chat`, `g_access_cpa`, `g_send_poll`, `g_mod_poll`, `g_access_contact`, `g_access_recommend`) VALUES (6, 'Moderators', 0, 0, 1, 0, 0, 1, 0, 0, 1, 1, 0, 1, 0, 0, 0, 0, 0)";
					$query53 = "INSERT INTO `mkp_pgroups` (`g_id`, `g_title`, `g_send_news`, `g_mod_news`, `g_access_download`, `g_send_download`, `g_mod_download`, `g_access_gallery`, `g_send_gallery`, `g_mod_gallery`, `g_access_urlobox`, `g_send_urlobox`, `g_mod_urlobox`, `g_access_chat`, `g_access_cpa`, `g_send_poll`, `g_mod_poll`, `g_access_contact`, `g_access_recommend`) VALUES (1, 'Guests', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0)";
					$query100 = "INSERT INTO `mkp_pgroups` (`g_id`, `g_title`, `g_send_news`, `g_mod_news`, `g_access_download`, `g_send_download`, `g_mod_download`, `g_access_gallery`, `g_send_gallery`, `g_mod_gallery`, `g_access_urlobox`, `g_send_urlobox`, `g_mod_urlobox`, `g_access_chat`, `g_access_cpa`, `g_send_poll`, `g_mod_poll`, `g_access_contact`, `g_access_recommend`) VALUES (3, 'Super Moderators', 0, 0, 1, 0, 0, 1, 0, 0, 1, 1, 0, 1, 0, 0, 0, 0, 0)";
					$query101 = "INSERT INTO `mkp_pgroups` (`g_id`, `g_title`, `g_send_news`, `g_mod_news`, `g_access_download`, `g_send_download`, `g_mod_download`, `g_access_gallery`, `g_send_gallery`, `g_mod_gallery`, `g_access_urlobox`, `g_send_urlobox`, `g_mod_urlobox`, `g_access_chat`, `g_access_cpa`, `g_send_poll`, `g_mod_poll`, `g_access_contact`, `g_access_recommend`) VALUES (5, 'Validating', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0)";
					mysql_query($query51);
					mysql_query($query52);
					mysql_query($query53);
					mysql_query($query100);
					mysql_query($query101);
    			break;
    			case 'SMF2':
    				$query100 = "INSERT INTO `mkp_pgroups` (`g_id`, `g_title`, `g_send_news`, `g_mod_news`, `g_access_download`, `g_send_download`, `g_mod_download`, `g_access_gallery`, `g_send_gallery`, `g_mod_gallery`, `g_access_urlobox`, `g_send_urlobox`, `g_mod_urlobox`, `g_access_chat`, `g_access_cpa`, `g_send_poll`, `g_mod_poll`, `g_access_contact`, `g_access_recommend`) VALUES (2, 'Global Moderator', 0, 0, 1, 0, 0, 1, 0, 0, 1, 1, 0, 1, 0, 0, 0, 0, 0)";
					$query101 = "INSERT INTO `mkp_pgroups` (`g_id`, `g_title`, `g_send_news`, `g_mod_news`, `g_access_download`, `g_send_download`, `g_mod_download`, `g_access_gallery`, `g_send_gallery`, `g_mod_gallery`, `g_access_urlobox`, `g_send_urlobox`, `g_mod_urlobox`, `g_access_chat`, `g_access_cpa`, `g_send_poll`, `g_mod_poll`, `g_access_contact`, `g_access_recommend`) VALUES (3, 'Moderator', 0, 0, 1, 0, 0, 1, 0, 0, 1, 1, 0, 1, 0, 0, 0, 0, 0)";
					$query102 = "INSERT INTO `mkp_pgroups` (`g_id`, `g_title`, `g_send_news`, `g_mod_news`, `g_access_download`, `g_send_download`, `g_mod_download`, `g_access_gallery`, `g_send_gallery`, `g_mod_gallery`, `g_access_urlobox`, `g_send_urlobox`, `g_mod_urlobox`, `g_access_chat`, `g_access_cpa`, `g_send_poll`, `g_mod_poll`, `g_access_contact`, `g_access_recommend`) VALUES (4, 'Newbie', 0, 0, 1, 0, 0, 1, 0, 0, 1, 1, 0, 1, 0, 0, 0, 0, 0)";
					$query103 = "INSERT INTO `mkp_pgroups` (`g_id`, `g_title`, `g_send_news`, `g_mod_news`, `g_access_download`, `g_send_download`, `g_mod_download`, `g_access_gallery`, `g_send_gallery`, `g_mod_gallery`, `g_access_urlobox`, `g_send_urlobox`, `g_mod_urlobox`, `g_access_chat`, `g_access_cpa`, `g_send_poll`, `g_mod_poll`, `g_access_contact`, `g_access_recommend`) VALUES (5, 'Jr. Member', 0, 0, 1, 0, 0, 1, 0, 0, 1, 1, 0, 1, 0, 0, 0, 0, 0)";
					$query104 = "INSERT INTO `mkp_pgroups` (`g_id`, `g_title`, `g_send_news`, `g_mod_news`, `g_access_download`, `g_send_download`, `g_mod_download`, `g_access_gallery`, `g_send_gallery`, `g_mod_gallery`, `g_access_urlobox`, `g_send_urlobox`, `g_mod_urlobox`, `g_access_chat`, `g_access_cpa`, `g_send_poll`, `g_mod_poll`, `g_access_contact`, `g_access_recommend`) VALUES (6, 'Full Member', 0, 0, 1, 0, 0, 1, 0, 0, 1, 1, 0, 1, 0, 0, 0, 0, 0)";
					$query105 = "INSERT INTO `mkp_pgroups` (`g_id`, `g_title`, `g_send_news`, `g_mod_news`, `g_access_download`, `g_send_download`, `g_mod_download`, `g_access_gallery`, `g_send_gallery`, `g_mod_gallery`, `g_access_urlobox`, `g_send_urlobox`, `g_mod_urlobox`, `g_access_chat`, `g_access_cpa`, `g_send_poll`, `g_mod_poll`, `g_access_contact`, `g_access_recommend`) VALUES (7, 'Sr. Member', 0, 0, 1, 0, 0, 1, 0, 0, 1, 1, 0, 1, 0, 0, 0, 0, 0)";
					$query106 = "INSERT INTO `mkp_pgroups` (`g_id`, `g_title`, `g_send_news`, `g_mod_news`, `g_access_download`, `g_send_download`, `g_mod_download`, `g_access_gallery`, `g_send_gallery`, `g_mod_gallery`, `g_access_urlobox`, `g_send_urlobox`, `g_mod_urlobox`, `g_access_chat`, `g_access_cpa`, `g_send_poll`, `g_mod_poll`, `g_access_contact`, `g_access_recommend`) VALUES (8, 'Hero Member', 0, 0, 1, 0, 0, 1, 0, 0, 1, 1, 0, 1, 0, 0, 0, 0, 0)";
					$query107 = "INSERT INTO `mkp_pgroups` (`g_id`, `g_title`, `g_send_news`, `g_mod_news`, `g_access_download`, `g_send_download`, `g_mod_download`, `g_access_gallery`, `g_send_gallery`, `g_mod_gallery`, `g_access_urlobox`, `g_send_urlobox`, `g_mod_urlobox`, `g_access_chat`, `g_access_cpa`, `g_send_poll`, `g_mod_poll`, `g_access_contact`, `g_access_recommend`) VALUES (99, 'Guests', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0)";
					mysql_query($query100);
					mysql_query($query101);
					mysql_query($query102);
					mysql_query($query103);
					mysql_query($query104);
					mysql_query($query105);
					mysql_query($query106);
					mysql_query($query107);
    			break;
				default:
    				$query100 = "INSERT INTO `mkp_pgroups` (`g_id`, `g_title`, `g_send_news`, `g_mod_news`, `g_access_download`, `g_send_download`, `g_mod_download`, `g_access_gallery`, `g_send_gallery`, `g_mod_gallery`, `g_access_urlobox`, `g_send_urlobox`, `g_mod_urlobox`, `g_access_chat`, `g_access_cpa`, `g_send_poll`, `g_mod_poll`, `g_access_contact`, `g_access_recommend`) VALUES (2, 'Global Moderator', 0, 0, 1, 0, 0, 1, 0, 0, 1, 1, 0, 1, 0, 0, 0, 0, 0)";
					$query101 = "INSERT INTO `mkp_pgroups` (`g_id`, `g_title`, `g_send_news`, `g_mod_news`, `g_access_download`, `g_send_download`, `g_mod_download`, `g_access_gallery`, `g_send_gallery`, `g_mod_gallery`, `g_access_urlobox`, `g_send_urlobox`, `g_mod_urlobox`, `g_access_chat`, `g_access_cpa`, `g_send_poll`, `g_mod_poll`, `g_access_contact`, `g_access_recommend`) VALUES (3, 'Moderator', 0, 0, 1, 0, 0, 1, 0, 0, 1, 1, 0, 1, 0, 0, 0, 0, 0)";
					$query102 = "INSERT INTO `mkp_pgroups` (`g_id`, `g_title`, `g_send_news`, `g_mod_news`, `g_access_download`, `g_send_download`, `g_mod_download`, `g_access_gallery`, `g_send_gallery`, `g_mod_gallery`, `g_access_urlobox`, `g_send_urlobox`, `g_mod_urlobox`, `g_access_chat`, `g_access_cpa`, `g_send_poll`, `g_mod_poll`, `g_access_contact`, `g_access_recommend`) VALUES (4, 'Newbie', 0, 0, 1, 0, 0, 1, 0, 0, 1, 1, 0, 1, 0, 0, 0, 0, 0)";
					$query103 = "INSERT INTO `mkp_pgroups` (`g_id`, `g_title`, `g_send_news`, `g_mod_news`, `g_access_download`, `g_send_download`, `g_mod_download`, `g_access_gallery`, `g_send_gallery`, `g_mod_gallery`, `g_access_urlobox`, `g_send_urlobox`, `g_mod_urlobox`, `g_access_chat`, `g_access_cpa`, `g_send_poll`, `g_mod_poll`, `g_access_contact`, `g_access_recommend`) VALUES (5, 'Jr. Member', 0, 0, 1, 0, 0, 1, 0, 0, 1, 1, 0, 1, 0, 0, 0, 0, 0)";
					$query104 = "INSERT INTO `mkp_pgroups` (`g_id`, `g_title`, `g_send_news`, `g_mod_news`, `g_access_download`, `g_send_download`, `g_mod_download`, `g_access_gallery`, `g_send_gallery`, `g_mod_gallery`, `g_access_urlobox`, `g_send_urlobox`, `g_mod_urlobox`, `g_access_chat`, `g_access_cpa`, `g_send_poll`, `g_mod_poll`, `g_access_contact`, `g_access_recommend`) VALUES (6, 'Full Member', 0, 0, 1, 0, 0, 1, 0, 0, 1, 1, 0, 1, 0, 0, 0, 0, 0)";
					$query105 = "INSERT INTO `mkp_pgroups` (`g_id`, `g_title`, `g_send_news`, `g_mod_news`, `g_access_download`, `g_send_download`, `g_mod_download`, `g_access_gallery`, `g_send_gallery`, `g_mod_gallery`, `g_access_urlobox`, `g_send_urlobox`, `g_mod_urlobox`, `g_access_chat`, `g_access_cpa`, `g_send_poll`, `g_mod_poll`, `g_access_contact`, `g_access_recommend`) VALUES (7, 'Sr. Member', 0, 0, 1, 0, 0, 1, 0, 0, 1, 1, 0, 1, 0, 0, 0, 0, 0)";
					$query106 = "INSERT INTO `mkp_pgroups` (`g_id`, `g_title`, `g_send_news`, `g_mod_news`, `g_access_download`, `g_send_download`, `g_mod_download`, `g_access_gallery`, `g_send_gallery`, `g_mod_gallery`, `g_access_urlobox`, `g_send_urlobox`, `g_mod_urlobox`, `g_access_chat`, `g_access_cpa`, `g_send_poll`, `g_mod_poll`, `g_access_contact`, `g_access_recommend`) VALUES (8, 'Hero Member', 0, 0, 1, 0, 0, 1, 0, 0, 1, 1, 0, 1, 0, 0, 0, 0, 0)";
					$query107 = "INSERT INTO `mkp_pgroups` (`g_id`, `g_title`, `g_send_news`, `g_mod_news`, `g_access_download`, `g_send_download`, `g_mod_download`, `g_access_gallery`, `g_send_gallery`, `g_mod_gallery`, `g_access_urlobox`, `g_send_urlobox`, `g_mod_urlobox`, `g_access_chat`, `g_access_cpa`, `g_send_poll`, `g_mod_poll`, `g_access_contact`, `g_access_recommend`) VALUES (99, 'Guests', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0)";
					mysql_query($query100);
					mysql_query($query101);
					mysql_query($query102);
					mysql_query($query103);
					mysql_query($query104);
					mysql_query($query105);
					mysql_query($query106);
					mysql_query($query107);
    			break;
		}



$query54="
CREATE TABLE `mkp_urlobox` (
  `id` int(11) NOT NULL auto_increment,
  `idaut` int(10) NOT NULL default '0',
  `name` varchar(40) NOT NULL default '',
  `message` text NULL,
  `time` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
)$chrst;";

mysql_query($query54);

$query55="
CREATE TABLE `mkp_blog` (
  `id` int(11) NOT NULL default '0',
  `autore` varchar(40) NOT NULL default '',
  `titolo` varchar(25) NOT NULL default '',
  `descrizione` text NULL,
  `template` text NULL,
  `template2` text NULL,
  `eta` varchar(25) NOT NULL default '',
  `segno` varchar(25) NOT NULL default '',
  `citta` varchar(25) NOT NULL default '',
  `libri` text NULL,
  `film` text NULL,
  `canzoni` text NULL,
  `link` text NULL,
  `amo` text NULL,
  `odio` text NULL,
  `umore` varchar(30) NOT NULL default 'felice',
  `citazione` text NULL,
  `click` int(11) NOT NULL default '0',
  `privacy` varchar(5) NOT NULL default 'ok',
  `mailcomm` varchar(5) NOT NULL default 'ok',
  `mailbloga` varchar(5) NOT NULL default 'ok',
  `maxmess` int(3) NOT NULL default '10',
  `aggiornato` int(11) NOT NULL default '0',
  `categoria` varchar(30) NOT NULL default '',
  `link_blog` text NULL,
  `anon_comm` char(2) NOT NULL default 'no',
  `creato` int(11) NOT NULL default '0',
  `ip_address` varchar(32) NOT NULL default '',
  `rate` varchar(10) NOT NULL default '',
  `trate` int(10) NOT NULL default '0',
  `banner` varchar(255) NOT NULL default '',
  `validate` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`id`)
)$chrst;";

mysql_query($query55);

$query56="
CREATE TABLE `mkp_blog_commenti` (
  `id` int(11) NOT NULL auto_increment,
  `id_blog` int(11) NOT NULL default '0',
  `id_post` int(11) NOT NULL default '0',
  `autore` varchar(25) NOT NULL default '',
  `home` varchar(80) NOT NULL default '',
  `commento` text NULL,
  `ipaddress` varchar(16) NOT NULL default '',
  `data` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
)$chrst;";

mysql_query($query56);

$query57="
CREATE TABLE `mkp_blog_post` (
  `id` int(11) NOT NULL auto_increment,
  `id_blog` int(11) NOT NULL default '0',
  `post` text NULL,
  `data` int(11) NOT NULL default '0',
  `ncom` int(4) NOT NULL default '0',
  PRIMARY KEY  (`id`)
)$chrst;";

mysql_query($query57);

$query58="
CREATE TABLE `mkp_topsite` (
  `id` int(10) NOT NULL auto_increment,
  `id_member` int(10) NOT NULL default '0',
  `data` int(11) NOT NULL default '0',
  `autor` varchar(25) NOT NULL default '',
  `title` varchar(80) NOT NULL default '',
  `description` varchar(200) NOT NULL default '',
  `link` varchar(255) NOT NULL default '',
  `banner` varchar(255) NOT NULL default '',
  `banner2` varchar(255) NOT NULL default '',
  `click` int(6) NOT NULL default '0',
  `rate` int(10) NOT NULL default '0',
  `trate` int(10) NOT NULL default '0',
  `validate` tinyint(1) NOT NULL default '0',
  `email` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
)$chrst;";

mysql_query($query58);


$query59="
CREATE TABLE `mkp_votes` (
  `id` int(10) NOT NULL auto_increment,
  `id_entry` int(10) NOT NULL default '0',
  `module` varchar(40) NOT NULL default '',
  `id_member` int(10) NOT NULL default '0',
  `ip` varchar(40) NOT NULL default '',
  PRIMARY KEY  (`id`)
)$chrst;";

mysql_query($query59);

$query60="INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('topsite_page', '10')";
$query61="INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('blog_page', '10')";
$query62="INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('mod_blog', '')";
$query63="INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('mod_gallery', '')";
$query64="INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('mod_urlobox', '')";
$query65="INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('mod_downloads', '')";
$query66="INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('mod_news', '')";
$query67="INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('mod_topsite', '')";
$query68="INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('mod_chat', '')";

mysql_query($query60);
mysql_query($query61);
mysql_query($query62);
mysql_query($query63);
mysql_query($query64);
mysql_query($query65);
mysql_query($query66);
mysql_query($query67);
mysql_query($query68);


$query70="
CREATE TABLE `mkp_quotes` (
  `id` int(11) NOT NULL auto_increment,
  `author` varchar(64) NOT NULL default 'Unknown',
  `member` varchar(64) NOT NULL default 'Staff',
  `member_id` int(11) NOT NULL default '0',
  `quote` varchar(255) NOT NULL default 'No quote',
  `date_added` int(11) NOT NULL default '0',
  `validate` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
)$chrst;";

mysql_query($query70);

$query71="
CREATE TABLE `mkp_ecards` (
  `id` int(10) NOT NULL auto_increment,
  `titolo` varchar(64) NOT NULL default '',
  `file` varchar(255) NOT NULL default '',
  `destinatario` varchar(64) NOT NULL default '',
  `mittente` varchar(64) NOT NULL default '',
  `emailmit` varchar(64) NOT NULL default '',
  `testo` text NULL,
  `member` varchar(64) NOT NULL default '',
  `date` int(10) NOT NULL default '0',
  `code` int(4) NOT NULL default '0',
  PRIMARY KEY  (`id`)
)$chrst;";

mysql_query($query71);


$query72="
CREATE TABLE `mkp_reviews` (
  `id` int(10) NOT NULL auto_increment,
  `id_cat` int(10) NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `description` varchar(255) NOT NULL default '',
  `field1` varchar(255) NOT NULL default '',
  `field2` varchar(255) NOT NULL default '',
  `field3` varchar(255) NOT NULL default '',
  `field4` varchar(255) NOT NULL default '',
  `field5` varchar(255) NOT NULL default '',
  `field6` varchar(255) NOT NULL default '',
  `field7` text NULL,
  `image` varchar(255) NOT NULL default '',
  `review` text NULL,
  `author` varchar(40) NOT NULL default '',
  `idauth` int(10) NOT NULL default '0',
  `click` int(10) NOT NULL default '0',
  `rate` int(10) NOT NULL default '0',
  `trate` int(10) NOT NULL default '0',
  `date` int(10) NOT NULL default '0',
  `validate` tinyint(1) NOT NULL default '1',
  `descr` varchar(200) NOT NULL default '',
  `keywords` text NOT NULL,
  PRIMARY KEY  (`id`)
)$chrst;";

mysql_query($query72);


$query75="
CREATE TABLE `mkp_mainlinks` (
  `id` int(10) NOT NULL auto_increment,
  `icon` text NULL,
  `title` varchar(255) NOT NULL default '',
  `url` text NULL,
  `type` tinyint(2) NOT NULL default '0',
  `position` int(2) NOT NULL default '12',
  `target` tinyint(1) NOT NULL default '0',
  `active` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
)$chrst;";

mysql_query($query75);

$query76="
CREATE TABLE `mkp_poll` (
  `poll_id` int(11) NOT NULL auto_increment,
  `poll_date` int(10) default NULL,
  `poll_title` varchar(255) NOT NULL default '',
  `poll_questions` text NOT NULL,
  `poll_answer_1` int(11) NOT NULL default '0',
  `poll_answer_2` int(11) NOT NULL default '0',
  `poll_answer_3` int(11) NOT NULL default '0',
  `poll_answer_4` int(11) NOT NULL default '0',
  `poll_answer_5` int(11) NOT NULL default '0',
  `poll_answer_6` int(11) NOT NULL default '0',
  `poll_answer_7` int(11) NOT NULL default '0',
  `poll_answer_8` int(11) NOT NULL default '0',
  `poll_answer_9` int(11) NOT NULL default '0',
  `poll_answer_10` int(11) NOT NULL default '0',
  `poll_answer_11` int(11) NOT NULL default '0',
  `poll_answer_12` int(11) NOT NULL default '0',
  `pool_vote` int(11) NOT NULL default '0',
  `pool_comments` int(11) NOT NULL default '0',
  `acomm` int(1) NOT NULL default '0',
  PRIMARY KEY  (`poll_id`)
)$chrst;";
mysql_query($query76);
$query77="
CREATE TABLE `mkp_poll_check` (
  `ip` varchar(20) NOT NULL default '',
  `time` varchar(14) NOT NULL default '',
  `poll_id` int(10) NOT NULL default '0',
  `mem_id` int(10) default '0'
)$chrst;";

mysql_query($query77);
$query78="
CREATE TABLE `mkp_poll_comments` (
  `id` int(10) NOT NULL auto_increment,
  `idpoll` int(10) NOT NULL default '0',
  `autid` int(10) NOT NULL default '0',
  `autore` varchar(255) NOT NULL default '',
  `testo` text,
  `data` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`)
)$chrst;";

mysql_query($query78);
$query = "INSERT INTO `mkp_mainlinks` (icon, title, url, type, position, active) VALUES ('<IMG>/atb_home.gif', '<LNG>home', '<MKURL>/index.php', '1', '1', '1')";
mysql_query($query);
$query = "INSERT INTO `mkp_mainlinks` (icon, title, url, type, position, active) VALUES ('<IMG>/atb_forum.gif', '<LNG>forum', '<MKFURL>', '1', '2', '1')";
mysql_query($query);
$query = "INSERT INTO `mkp_mainlinks` (icon, title, url, type, position, active) VALUES ('<IMG>/atb_blog.gif', '<LNG>blog', '<MKURL>/index.php?ind=blog', '1', '3', '1')";
mysql_query($query);
$query = "INSERT INTO `mkp_mainlinks` (icon, title, url, type, position, active) VALUES ('<IMG>/atb_foto.gif', '<LNG>gallery', '<MKURL>/index.php?ind=gallery', '1', '4', '1')";
mysql_query($query);
$query = "INSERT INTO `mkp_mainlinks` (icon, title, url, type, position, active) VALUES ('<IMG>/atb_urlo.gif', '<LNG>urlobox', '<MKURL>/index.php?ind=urlobox', '1', '5', '1')";
mysql_query($query);
$query = "INSERT INTO `mkp_mainlinks` (icon, title, url, type, position, active) VALUES ('<IMG>/atb_down.gif', '<LNG>download', '<MKURL>/index.php?ind=downloads', '1', '6', '1')";
mysql_query($query);
$query = "INSERT INTO `mkp_mainlinks` (icon, title, url, type, position, active) VALUES ('<IMG>/atb_racconti.gif', '<LNG>news', '<MKURL>/index.php?ind=news', '1', '7', '1')";
mysql_query($query);
$query = "INSERT INTO `mkp_mainlinks` (icon, title, url, type, position, active) VALUES ('<IMG>/atb_toplist.gif', '<LNG>topsite', '<MKURL>/index.php?ind=topsite', '1', '8', '1')";
mysql_query($query);
$query = "INSERT INTO `mkp_mainlinks` (icon, title, url, type, position, active) VALUES ('<IMG>/atb_media.gif', '<LNG>reviews', '<MKURL>/index.php?ind=reviews', '1', '9', '1')";
mysql_query($query);
$query = "INSERT INTO `mkp_mainlinks` (icon, title, url, type, position, active) VALUES ('<IMG>/atb_chat.gif', '<LNG>chat', '<MKURL>/index.php?ind=chat', '1', '10', '1')";
mysql_query($query);
$query = "INSERT INTO `mkp_mainlinks` (icon, title, url, type, position, active) VALUES ('<IMG>/Voting.gif', 'Poll', '<MKURL>/index.php?ind=poll', '1', '11', '1')";
mysql_query($query);
$query = "INSERT INTO `mkp_mainlinks` (icon, title, url, type, position, active) VALUES ('<IMG>/atb_racconti.gif', 'Contact', '<MKURL>/index.php?ind=contact', '1', '11', '1')";
mysql_query($query);
$query = "INSERT INTO `mkp_mainlinks` (icon, title, url, type, position, active) VALUES ('<IMG>/atb_racconti.gif', 'Recommend', '<MKURL>/index.php?ind=recommend', '1', '11', '1')";
mysql_query($query);

$query = "INSERT INTO `mkp_mainlinks` (icon, title, url, type, position, active) VALUES ('<IMG>/atb_home.gif', '<LNG>home', '<MKURL>/index.php', '2', '1', '1')";
mysql_query($query);
$query = "INSERT INTO `mkp_mainlinks` (icon, title, url, type, position, active) VALUES ('<IMG>/atb_forum.gif', '<LNG>forum', '<MKFURL>', '2', '2', '1')";
mysql_query($query);
$query = "INSERT INTO `mkp_mainlinks` (icon, title, url, type, position, active) VALUES ('<IMG>/atb_blog.gif', '<LNG>blog', '<MKURL>/index.php?ind=blog', '2', '3', '1')";
mysql_query($query);
$query = "INSERT INTO `mkp_mainlinks` (icon, title, url, type, position, active) VALUES ('<IMG>/atb_foto.gif', '<LNG>gallery', '<MKURL>/index.php?ind=gallery', '2', '4', '1')";
mysql_query($query);
$query = "INSERT INTO `mkp_mainlinks` (icon, title, url, type, position, active) VALUES ('<IMG>/atb_urlo.gif', '<LNG>urlobox', '<MKURL>/index.php?ind=urlobox', '2', '5', '1')";
mysql_query($query);
$query = "INSERT INTO `mkp_mainlinks` (icon, title, url, type, position, active) VALUES ('<IMG>/atb_down.gif', '<LNG>download', '<MKURL>/index.php?ind=downloads', '2', '6', '1')";
mysql_query($query);
$query = "INSERT INTO `mkp_mainlinks` (icon, title, url, type, position, active) VALUES ('<IMG>/atb_racconti.gif', '<LNG>news', '<MKURL>/index.php?ind=news', '2', '7', '1')";
mysql_query($query);
$query = "INSERT INTO `mkp_mainlinks` (icon, title, url, type, position, active) VALUES ('<IMG>/atb_toplist.gif', '<LNG>topsite', '<MKURL>/index.php?ind=topsite', '2', '8', '1')";
mysql_query($query);
$query = "INSERT INTO `mkp_mainlinks` (icon, title, url, type, position, active) VALUES ('<IMG>/atb_media.gif', '<LNG>reviews', '<MKURL>/index.php?ind=reviews', '2', '9', '1')";
mysql_query($query);
$query = "INSERT INTO `mkp_mainlinks` (icon, title, url, type, position, active) VALUES ('<IMG>/atb_quote.gif', '<LNG>quote', '<MKURL>/index.php?ind=quote', '2', '10', '1')";
mysql_query($query);
$query = "INSERT INTO `mkp_mainlinks` (icon, title, url, type, position, active) VALUES ('<IMG>/atb_chat.gif', '<LNG>chat', '<MKURL>/index.php?ind=chat', '2', '11', '1')";
mysql_query($query);
$query = "INSERT INTO `mkp_mainlinks` (icon, title, url, type, position, active) VALUES ('<IMG>/Voting.gif', 'Poll', '<MKURL>/index.php?ind=poll', '2', '12', '1')";
mysql_query($query);
$query = "INSERT INTO `mkp_mainlinks` (icon, title, url, type, position, active) VALUES ('<IMG>/atb_racconti.gif', 'Recommend', '<MKURL>/index.php?ind=recommend', '2', '12', '1')";
mysql_query($query);
$query = "INSERT INTO `mkp_mainlinks` (icon, title, url, type, position, active) VALUES ('<IMG>/atb_racconti.gif', 'Contact', '<MKURL>/index.php?ind=contact', '2', '12', '1')";
mysql_query($query);

$query="
CREATE TABLE `mkp_rss` (
  `id` int(10) NOT NULL auto_increment,
  `name` varchar(40) NOT NULL default '',
  `url` varchar(255) NOT NULL default '',
  `position` int(3) DEFAULT 1,
  `active` tinyint(1),
  PRIMARY KEY  (`id`)
)$chrst;";
mysql_query($query);

$query = "INSERT INTO `mkp_rss` (name, url, position, active) VALUES ('Reuters', 'http://www.microsite.reuters.com/rss/worldNews', '1', '0')";
mysql_query($query);

$query = "INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('rss_max_items', '10')";
mysql_query($query);
$query = "INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('rss_cache_time', '60')";
mysql_query($query);
$query = "INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('rss_desc', '1')";
mysql_query($query);
$query = "INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('rss_desc_length', '255')";
mysql_query($query);
$query = "INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('blog_upload_width', '100')";
mysql_query($query);
$query = "INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('blog_upload_num', '10')";
mysql_query($query);

$query ="
CREATE TABLE `mkp_blog_pimages` (
  `id` int(10) NOT NULL auto_increment,
  `iduser` int(10) NOT NULL default '0',
  `file` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
)$chrst;";
mysql_query($query);

$query="ALTER TABLE `mkp_news` ADD `totalcomm` int(3) NOT NULL default '0' AFTER `data`";
mysql_query($query);

$query="
CREATE TABLE `mkp_stat` (
  `id` int(11) NOT NULL auto_increment,
  `chiave` varchar(255) NOT NULL default '',
  `valore` mediumtext NULL,
  PRIMARY KEY  (`id`)
)$chrst;";
mysql_query($query);

$count = 0;
$query = "INSERT INTO `mkp_stat` (`chiave`, `valore`) VALUES ('tot_gallery', '$count')";
mysql_query($query);

$query = "INSERT INTO `mkp_stat` (`chiave`, `valore`) VALUES ('tot_download', '$count')";
mysql_query($query);

$query = "INSERT INTO `mkp_stat` (`chiave`, `valore`) VALUES ('tot_blog', '$count')";
mysql_query($query);

$query = "INSERT INTO `mkp_stat` (`chiave`, `valore`) VALUES ('tot_topsite', '$count')";
mysql_query($query);

$query = "INSERT INTO `mkp_stat` (`chiave`, `valore`) VALUES ('tot_reviews', '$count')";
mysql_query($query);

$query = "INSERT INTO `mkp_stat` (`chiave`, `valore`) VALUES ('tot_quotes', '$count')";
mysql_query($query);

$query = mysql_query( "SELECT id, name, message, time FROM mkp_urlobox ORDER BY `id` DESC LIMIT 1");
$row = mysql_fetch_array($query);
$query = "INSERT INTO `mkp_stat` (`chiave`, `valore`) VALUES ('urlo_name', '$row[name]')";
mysql_query($query);
$query = "INSERT INTO `mkp_stat` (`chiave`, `valore`) VALUES ('urlo_message', '$row[message]')";
mysql_query($query);
$query = "INSERT INTO `mkp_stat` (`chiave`, `valore`) VALUES ('urlo_time', '$row[time]')";
mysql_query($query);

$query = mysql_query( "select id, id_blog, post from mkp_blog_post ORDER BY 'id' DESC LIMIT 1");
$row = mysql_fetch_array($query);
$query = "INSERT INTO `mkp_stat` (`chiave`, `valore`) VALUES ('blog_id_blog', '$row[id_blog]')";
mysql_query($query);
$query = "INSERT INTO `mkp_stat` (`chiave`, `valore`) VALUES ('blog_post', '$row[post]')";
mysql_query($query);
$idblog = $row['id_blog'];
$query = mysql_query( "select titolo from mkp_blog where id = '$idblog'");
$row = mysql_fetch_array($query);
$query = "INSERT INTO `mkp_stat` (`chiave`, `valore`) VALUES ('blog_titolo', '$row[titolo]')";
mysql_query($query);

//this MUST be last query in all the installations
$query = "INSERT INTO `mkp_config` (`chiave`, `valore`) VALUES ('mk_version', 'R1.0.2')";
mysql_query($query);

	$okend = $mklang['okend'];

	if ($BOARD == "AEF") {
			$okend .= "<br /><br /><b>{$mklang['okend2']}</b>";
	}
	$content = "<br /><br />$okend<br /><br />
	<br /><br /><span style=\"color: rgb(255, 0, 0); font-weight: bold;\"><a href=\"../index.php\">{$mklang['loginmk']}</a> </span>";
	$output = $header.$content.$footer;
	print $output;
	mysql_close();
	exit;
}

?>