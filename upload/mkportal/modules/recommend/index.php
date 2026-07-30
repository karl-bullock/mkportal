<?
if (!defined("IN_MKP")) {
    die ("Sorry !! You cannot access this file directly.");
}
$idx = new mk_recommend;
class mk_recommend {
function mk_recommend() {
global $mkportals, $DB, $std, $print, $mklib, $Skin, $mklib_board;

		$mklib->load_lang("lang_rocommend.php");

		if ($mklib->config['mod_recommend']) {
		$message = "{$mklib->lang['mod_offline']}";
			$mklib->error_page($message);
			exit;
			
		}
	/*	$sec =$mklib->member['g_access_recommend'];
		if(!$mklib->member['g_access_recommend'] && !$mkportals->member['g_access_cp']) {
			$message = "{$mklib->lang['mod_nodostup']}";
			$mklib->error_page( $sec);
			exit;
		}*/
		if(!$mklib->member['g_access_recommend'] && !$mkportals->member['g_access_cp']) {
			$message = "{$mklib->lang['mod_nodostup']}";
			$mklib->error_page($message);
			exit;
		}

    		switch($mkportals->input['op']) {
				default:
    				$this->recommend();
    			break;
    			case 'send_mail':
    				$this->send_mail();
    			break;
		
				
    		}
	}

	function recommend() {
global $mkportals, $DB, $std, $print, $mklib, $Skin, $mklib_board;
if ($mkportals->member['id']) {$nome = $mkportals->member['name'];}
if ($mkportals->member['id']) {$nome1 = $mkportals->member['email'];}
$captcha = $mklib->antibot_start();
		$output ="
		<tr><td align=center width=100% id=\"conta\">
<form name=\"forms\" id=\"forms\" action=\"javascript:sendRecommend();\" method=\"post\">
    <table border=0 align=center width=60%>
        <tr>
            <td align=left>{$mklib->lang['recommend_yname']}
                  
            </td>
            <td align=center>
                <input type=text size=30 name=yname value=\"$nome\">
            </td>
        </tr>
        <tr>
            <td align=left>{$mklib->lang['recommend_ymail']}</td>
            <td align=center><input type=text size=30 name=ymail value=\"$nome1\"></td>
        </tr>
        <tr>
            <td align=left>{$mklib->lang['recommend_fname']}</td>
            <td align=center><input type=text size=30 name=fname></td>
        </tr>
        <tr>
            <td align=left>{$mklib->lang['recommend_fmail']}</td>
            <td align=center><input type=text size=30 name=fmail></td>
        </tr>
		<tr>
		<td align=left colspan=2><p align=\"center\">$captcha</p></td>
		</tr>
        <tr>
            <td align=left colspan=2><p align=\"center\"><input type=submit value=\"{$mklib->lang['recommend_send']}\"></p></td>
        </tr>
	
</table>
</form>
</td>
        </tr>";
$output .="<tr>
	  <td align=\"center\"><br /><br />
	    <div align=\"center\"><a href=\"http://www.rusmkportal.ru\" target=\"_blank\">MKPRecommend</a> &copy;2007-2009 <a href=\"http://www.rusmkportal.ru\" target=\"_blank\">www.rusmkportal.ru</a></div>
	  </td>
	</tr>	";

	$blocks .= $Skin->view_block("{$mklib->lang['recommend_title']}", $output);
	$mklib->printpage("1", "1", "{$mklib->lang['recommend_title']}", $blocks);

	}
	
	function send_mail() {
global $mkportals, $DB, $std, $print, $mklib, $Skin, $SITE_NAME, $SITE_URL;
//session_start();

@header("Content-type: text/html; charset={$mklib->charset}");
@header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
@header('Last-Modified: '.@gmdate('D, d M Y H:i:s').' GMT');
@header('Cache-Control: no-store, no-cache, must-revalidate');
@header('Cache-Control: post-check=0, pre-check=0', false);
@header('Pragma: no-cache');

$fmail = $mkportals->input['fmail'];
$fname1 = $mkportals->input['fname'];
$yname1 = $mkportals->input['yname'];
$ymail = $mkportals->input['ymail'];
$fname = iconv("UTF-8", "$mklib->charset", $fname1);
$yname = iconv("UTF-8", "$mklib->charset", $yname1);
if ($mklib->config['antibot_chek'] && !$mkportals->member['id']){
$captcha_code = $mkportals->input['check'];
$captcha_check = $mklib->antibot_check($captcha_code);
}
if (!$yname || !$ymail || !$fname || !$fmail) {
			$message = "{$mklib->lang['recommend_error']}";
			$mklib->Ajax_error_page($message);
			exit();
		}
if (!eregi("^[\'+\\./0-9A-Z^_\`a-z{|}~\-]+@[a-zA-Z0-9_\-]+(\.[a-zA-Z0-9_\-]+){1,3}$", $ymail)) { 
        $message = "{$mklib->lang['recommend_error1']}";
			$mklib->Ajax_error_page($message);
			exit();
}
if (!eregi("^[\'+\\./0-9A-Z^_\`a-z{|}~\-]+@[a-zA-Z0-9_\-]+(\.[a-zA-Z0-9_\-]+){1,3}$", $fmail)) { 
       $message = "{$mklib->lang['recommend_error2']}";
			$mklib->Ajax_error_page($message);
			exit();
}

$subject = "{$mklib->lang['recommend_intersite']} $SITE_NAME";
$message = "{$mklib->lang['recommend_hello']} $fname:\n\n {$mklib->lang['recommend_youfrend']} $yname {$mklib->lang['recommend_visitsite']} $SITE_NAME {$mklib->lang['recommend_siteinters2']} \n\n\n {$mklib->lang['recommend_sitenames']} $SITE_NAME\n\n {$mklib->lang['recommend_siteurl']} $SITE_URL\n";
$headers = "Content-Type: text/plain; charset={$mklib->charset}\n";
$headers .= "From: $yname <$ymail>\n";

mail($fmail, $subject, $message, $headers);
$message = "{$mklib->lang['recommend_oksend1']} $fname <p> {$mklib->lang['recommend_oksend2']}";
			$mklib->Ajax_ok_page($message);
			exit();
         }
}

?>
