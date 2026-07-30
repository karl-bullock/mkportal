<?

if (!defined("IN_MKP")) {
    die ("Sorry !! You cannot access this file directly.");
}

 
$idx = new mk_contact;
class mk_contact {
function mk_contact() {
global $mkportals, $DB, $std, $print, $mklib, $Skin, $mklib_board;

		$mklib->load_lang("lang_contact.php");

		if ($mklib->config['mod_contact']) {
		$message = "{$mklib->lang['mod_offline']}";
			$mklib->error_page($message);
			exit;
			
		}
		if(!$mklib->member['g_access_contact'] && !$mkportals->member['g_access_cp']) {
			$message = "{$mklib->lang['mod_nodostup']}";
			$mklib->error_page($message);
			exit;
		}
    		switch($mkportals->input['op']) {
				default:
    				$this->mail();
    			break;
    			case 'send_mail':
    				$this->send_mail();
    			break;
			/*	case 'news_show_single':
    				$this->news_show_single();
    			break;*/
				
    		}
	}
function mail () {
global $mkportals, $DB, $std, $print, $mklib, $Skin, $mklib_board;

if ($mkportals->member['id']) {$nome = $mkportals->member['name'];}
if ($mkportals->member['id']) {$nome1 = $mkportals->member['email'];}
$captcha = $mklib->antibot_start();
     $output .="<tr>
	  <td align=\"center\">
	    <div align=\"center\">{$mklib->lang['mcon_hello']}</div>
	  <br /><br /></td>
	</tr>	";
   $output .= "
		<tr><td align=center width=100% id=\"contact\">
<form name=\"forms\" id=\"forms\" action=\"javascript:sendContact();\" method=\"post\">
    <table border=0 align=center width=60%>
        <tr>
            <td align=left>{$mklib->lang['mcon_yname']}
                  
            </td>
            <td align=center>
                <input type=text size=30 name=yname value=\"$nome\">
            </td>
        </tr>
        <tr>
            <td align=left>{$mklib->lang['mcon_ymail']}</td>
            <td align=center><input type=text size=30 name=ymail value=\"$nome1\"></td>
        </tr>
        <tr>
            <td align=left>{$mklib->lang['mcon_tsoob']}</td>
            <td align=center><input type=text size=30 name=tsoob></td>
        </tr>
        <tr>
            <td align=left>{$mklib->lang['mcon_soob']}</td>
            <td align=center><textarea name=soob cols=50 rows=10></textarea><br>
        <i>{$mklib->lang['mcon_pozlan']}</i></td>
   <tr>
		<td align=left colspan=2><p align=\"center\">$captcha</p></td>
		</tr>
        </tr>
         <tr>
            <td align=center>{$mklib->lang['mcon_pola']}</td>
        </tr>
        <tr>
            <td align=left colspan=2><p align=\"center\"><input type=submit value=\"{$mklib->lang['mcon_send']}\"></p></td>
        </tr>
</table>
</form>
</td></tr>";
   $output .="<tr>
	  <td align=\"center\"><br /><br />
	    <div align=\"center\"><a href=\"http://www.rusmkportal.ru\" target=\"_blank\">MKPContact</a> &copy;2007-2009 <a href=\"http://www.rusmkportal.ru\" target=\"_blank\">www.rusmkportal.ru</a></div>
	  </td>
	</tr>	";

$blocks .= $Skin->view_block("{$mklib->lang['mcon_mcontact']}", $output);
$mklib->printpage("1", "1", "{$mklib->lang['mcon_mcontact']}", $blocks);		
}		
		function send_mail() {
			global $mkportals, $DB, $Skin, $print, $mklib, $mklib_board, $SITE_NAME; 
@header("Content-type: text/html; charset={$mklib->charset}");
@header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
@header('Last-Modified: '.@gmdate('D, d M Y H:i:s').' GMT');
@header('Cache-Control: no-store, no-cache, must-revalidate');
@header('Cache-Control: post-check=0, pre-check=0', false);
@header('Pragma: no-cache');		
	$yname1 = $mkportals->input['yname'];
	$ymail = $mkportals->input['ymail'];
	$tsoob1 = $mkportals->input['tsoob'];
	$soob1 = strip_tags( stripslashes( $_POST['soob'] ) );;
	

$yname = iconv("UTF-8", "$mklib->charset", $yname1);
$tsoob = iconv("UTF-8", "$mklib->charset", $tsoob1);
$soob = iconv("UTF-8", "$mklib->charset", $soob1);
if ($mklib->config['antibot_chek'] && !$mkportals->member['id']){
$captcha_code = $mkportals->input['check'];
$captcha_check = $mklib->antibot_check($captcha_code);
}
	if (!$yname || !$ymail || !$tsoob || !$soob) {
			$message = "{$mklib->lang['mcon_meseror1']}";
			$mklib->Ajax_error_page($message);
			exit();
		}
		if (!eregi("^[\'+\\./0-9A-Z^_\`a-z{|}~\-]+@[a-zA-Z0-9_\-]+(\.[a-zA-Z0-9_\-]+){1,3}$", $ymail)) { 
        $message = "{$mklib->lang['mcon_meseror2']}";
			$mklib->Ajax_error_page($message);
			exit();
}
$ip = $_SERVER['REMOTE_ADDR'];
if($mklib->config['contact_ip'] == "1") {
			$ipsend ="{$mklib->lang['mcon_ipot']} $ip \n";
		   }


$subj = "{$mklib->lang['mcon_mcontact']}-$SITE_NAME";
$to = "{$mklib->config['contact_send']}";
$mess = "
{$mklib->lang['mcon_nameot']}  $yname \n
{$mklib->lang['mcon_mailot']}  $ymail \n 
{$mklib->lang['mcon_tsoob']}:  $tsoob\n
{$mklib->lang['mcon_soob']}:  $soob \n
$ipsend
";


$headers .= "Content-Type: text/plain; charset=\"{$mklib->charset}\"\r\n";
$headers .="From: $yname <$ymail>\n";

mail($to, $subj, $mess, $headers); 
$message = "<div class=\"bghighlight1 success\"><p align=\"center\">{$mklib->lang['mcon_spaz']} <b>$yname</b> {$mklib->lang['mcon_spaz2']}</p></div>";
$mklib->Ajax_ok_page($message);
			exit();
  }
			
}	
?>
