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
|   Module Language Managament
|   > (c) 2005 by Peter (Peter@ibforen.de)
|
+--------------------------------------------------------------------------
*/

if (!defined("IN_MKP")) {
    die ("Sorry !! You cannot access this file directly.");
}

define ("MK_PATH", "../../");

require MK_PATH."mkportal/include/lang_functions.php";

$mklang = new mklang();

$idx = new mk_ad_langs;

class mk_ad_langs {

	function mk_ad_langs() {
        global $mkportals, $mklib;
        
        $mklib->load_lang("lang_ad_langs.php");
		switch($mkportals->input['op']) {
			case 'process':
    			$this->process();
        		break;
			case 'save':
    			$this->save();
        		break;
			case 'single':
    			$this->process_single();
        		break;
			case 'save_single':
    			$this->save_single();
        		break;
			case 'translate':
    			$this->process_translate();
        		break;
			case 'save_translate':
    			$this->save_translate();
        		break;
			default:
    			$this->list_current();
    	    	break;
    		}
	}

	function list_current() {
    	global $mklib,  $Skin, $mkportals;

        if (isset($mkportals->input['copy'])) {
            $copy_name = $mkportals->input['cname'];
            if (@is_dir("../lang/".$copy_name)) {
                $copy_info = "&nbsp;&nbsp;<span style='color:red;font-weight:bold;'>{$mklib->lang['ad_langs_copy_error']}</span>";
            }
            else {
                $copy_info = $this->copy_lang($mkportals->input['lang'], $copy_name);
            }
        }
        if (isset($mkportals->input['sync'])) {
            $sync_info = $this->sync_lang();
        }
        if ($dir = @opendir("../lang")) {
            $clang = "\n<select class='bgselect' size='1' name='lang'>\n";
            while (($dirt = readdir($dir)) !== false) {
                if ($dirt != "." && $dirt != ".." && is_dir(MK_PATH."mkportal/lang/".$dirt)) {
                    $selected = "";
                    if ((MK_PATH."mkportal/lang/".$dirt) == $mklib->mklang) {
                        $selected = "selected='selected'";
                    }
                    $clang .= "<option value='$dirt' $selected >$dirt</option>\n";
                    $lang_array[strtolower($dirt)] = "1";
                    if (substr(sprintf("%o", fileperms("../lang/".$dirt)), -1, 3) != "777") {
                        @chmod(MK_PATH."mkportal/lang/$dirt", 0777);
                    }
                }
            }
            closedir($dir);
            $clang .= "</select>\n";
        }
    
        $content = "
        <tr><td>
        <form action='index.php?ind=ad_langs&op=process' name='AdminLang' method='post'>
        <table width='100%' border='0'>
            <tr>
                <td class='titadmin' colspan='2'>{$mklib->lang['ad_langs_sel_info']}<br /></td>
            </tr>
            <tr>
                <td>$clang</td>
            </tr>
            <tr>
                <td>
                    <br />
                    <input type='submit' name='run' value='{$mklib->lang['ad_langs_sel_ok']}' class='mkbutton'>
                </td>
            </tr>
        </table>
        </form>
        </td></tr>
        ";
        $output = $Skin->view_block($mklib->lang['ad_langs_sel_title'], "$content");
        $content = "
        <tr><td>
        <form action='index.php?ind=ad_langs' name='AdminCopy' method='post'>
        <input type='hidden' name='copy' value='1' />
        <table width='100%' border='0'>
            <tr>
                <td class='titadmin' colspan='2'>{$mklib->lang['ad_langs_copy_info']}<br /></td>
            </tr>
            <tr>
                <td>$clang</td>
            </tr>
            <tr>
                <td class='titadmin' colspan='2'>{$mklib->lang['ad_langs_copy_new']}<br /></td>
            </tr>
            <tr>
                <td><input type='text' name='cname' value='' size='20'  class='bgselect' />$copy_info</td>
            </tr>
            <tr>
                <td><br /><input type='submit' name='run' value='{$mklib->lang['ad_langs_copy_ok']}' class='mkbutton'></td>
            </tr>
        </table>
        </form>
        </td></tr>
        ";
        $output .= $Skin->view_block($mklib->lang['ad_langs_copy_title'], "$content");
        $dlang = str_replace("<select class='bgselect' size='1' name='lang'>",
                             "<select class='bgselect' size='".min(5,count($lang_array))."' name='dest[]' multiple>",
                             $clang);
        if ($sync_info) {
            $sync_info = "
                         <tr>
                            <td class=\"tdblock\">
                                $sync_info
                            </td>
                         </tr>
                         ";
        }
        $content = "
        $sync_info
        <tr><td>
        <form action='index.php?ind=ad_langs' name='AdminSync' method='post'>
        <input type='hidden' name='sync' value='1' />
        <table width='100%' border='0'>
            <tr>
                <td class='titadmin' colspan='2'>{$mklib->lang['ad_langs_sync_src']}<br /></td>
            </tr>
            <tr>
                <td>$clang</td>
            </tr>
            <tr>
                <td class='titadmin' colspan='2'>{$mklib->lang['ad_langs_sync_dest']}<br /></td>
            </tr>
            <tr>
                <td>$dlang</td>
            </tr>
            <tr>
                <td colspan='2'><input type='checkbox' name='del' value='1' />&nbsp;{$mklib->lang['ad_langs_sync_del']}<br /></td>
            </tr>
            <tr>
                <td colspan='2'><input type='checkbox' name='del_file' value='1' />&nbsp;{$mklib->lang['ad_langs_sync_del_file']}<br /></td>
            </tr>
            <tr>
                <td colspan='2'><input type='checkbox' name='new' value='1' />&nbsp;{$mklib->lang['ad_langs_sync_copy']}<br /></td>
            </tr>
            <tr>
                <td><br /><input type='submit' name='run' value='{$mklib->lang['ad_langs_sync_ok']}' class='mkbutton'></td>
            </tr>
        </table>
        </form>
        </td></tr>
        ";
        $output .= $Skin->view_block($mklib->lang['ad_langs_sync_title'], "$content");
        $mklib->printpage_admin($mklib->lang['ad_titlepage'].$mklib->lang['tt_sep'].$mklib->lang['ad_langs_sync_title'], $output);
	}

    function copy_lang($source = "", $dest = "") {
        global $mklib,  $mkportals;
        
        $write_error = "&nbsp;&nbsp;<span style='color:red;font-weight:bold;'>{$mklib->lang['ad_langs_copy_write_error']}</span>";
        if (empty($source) || empty($dest)) {
            return $write_error;
        }
		$source_dir = MK_PATH."mkportal/lang/".$source;
		$dest_dir = MK_PATH."mkportal/lang/".$dest;
        if (!is_dir($source_dir)) {
            return "&nbsp;&nbsp;<span style='color:red;font-weight:bold;'>{$mklib->lang['ad_langs_copy_read_error']}</span>";
        }        
        if (!mkdir($dest_dir, 0777)) {
            return $write_error;
        }
        @chmod($dest_dir, 0777);
        if ($dir = @opendir($source_dir)) {
            while (($file = readdir($dir)) !== false) {
                if ($file != "." && $file != ".." && $file != ".htaccess" && $file != "htaccess" && !is_dir($file)) {
                    $source_file = $source_dir."/".$file;
                    $dest_file = $dest_dir."/".$file;
                    if (!@copy($source_file, $dest_file)) {
                        return $write_error;
                    }
                    @chmod($dest_file, 0666);
                }
            }
            closedir($dir);
        }
        return "&nbsp;&nbsp;<span style='color:blue;font-weight:bold;'>{$mklib->lang['ad_langs_copy_success']}</span>";
    }

    function synchronize($source_file, $dest_file, $del = FALSE) {
        global $mklib;
        
        @require($source_file);
        if (is_array($langmk)) {
            foreach ($langmk as $key => $value) {
                $source[$key] = $value;
            }
            unset ($langmk);
            @require($dest_file);
            if (is_array($langmk)) {
                $content = "<?php\n\n";
                foreach ($source as $key => $value) {
                    if (isset($langmk[$key])) {
                        $dest[$key] = str_replace("\"", "\\\"", $langmk[$key]);
                        unset($langmk[$key]);
                    }
                    else {
                        $dest[$key] = str_replace("\"", "\\\"", $source[$key]);
                    }
                    if (substr($key, 0, 6) == "group_") {
                        $content .= "\n\n// ----------------------------------------------------\n";
                        $content .= "\$langmk['$key'] = \"{$dest[$key]}\";\n";
                        $content .= "// ----------------------------------------------------\n";
                    }
                    else {
                        $content .= "\$langmk['$key'] = \"{$dest[$key]}\";\n";
                    }

                }
                if (!$del && count($langmk)) {
                    $title = str_replace("<# LANG #>", $source_file, $mklib->lang['ad_langs_sync_unused']);
                    $content .= "\n\n// ----------------------------------------------------\n";
                    $content .= "\$langmk['group_unassigned'] = \"{$title}\";\n";
                    $content .= "// ----------------------------------------------------\n";
                    foreach ($langmk as $key => $value) {
                        $value = str_replace("\"", "\\\"", $value);
                        if (substr($key, 0, 6) == "group_") {
                            $content .= "\n\n// ----------------------------------------------------\n";
                            $content .= "\$langmk['$key'] = \"{$value}\";\n";
                            $content .= "// ----------------------------------------------------\n";
                        }
                        else {
                            $content .= "\$langmk['$key'] = \"{$value}\";\n";
                        }
                    }
                }
                $content .= "\n\n?>";
                @chmod($dest_file, 0666);
                $fh = @fopen($dest_file, "wb");
                if ($fh) {
                    fwrite($fh, $content);
                    fclose($fh);
                    $result = TRUE;
                }
                else {
                    $message = str_replace("<# FILE #>", $dest_file, $mklib->lang['ad_langs_write_error']);
                    $mklib->error_page($message);
                    exit;
                }
            }
        }
    }

    function sync_lang_dir($source = "", $dest_lang = "", $new = FALSE, $del = FALSE, $del_file = FALSE) {
        global $mklib, $mkportals;
        
		$source_dir = MK_PATH."mkportal/lang/".$source;
		$dest_dir = MK_PATH."mkportal/lang/".$dest_lang;
        if ($dir = @opendir($source_dir)) {
            while (($file = readdir($dir)) !== false) {
                if ($file != "." && $file != ".." && $file != ".htaccess" && $file != "htaccess" ) {
                    if (!is_dir($source_dir."/".$file)) {
                        $source_file = $source_dir."/".$file;
                        $dest_file = $dest_dir."/".$file;
                        if (!is_file($dest_file)) {
                            if ($new) {
                                if (!@copy($source_file, $dest_file)) {
                                    return $write_error;
                                }
                            }
                            continue;
                        }
                        $this->synchronize($source_file, $dest_file, $del);
                    }
                    else {
                        if (!is_dir($dest_dir."/".$file)) {
                            if ($new) {
                                if (!@mkdir($dest_dir."/".$file)) {
                                    return str_replace("<%DIR%>", $dest_dir."/".$file, $mklib->lang['error_upload']);
                                }
                            }
                            else {
                                continue;
                            }
                        }
                        $this->sync_lang_dir($source."/".$file, $dest_lang."/".$file, $new, $del);
                    }
                }
            }
            closedir($dir);
        }
        if ($del_file) {
            if ($dir = @opendir($dest_dir)) {
                while (($file = readdir($dir)) !== false) {
                    if ($file != "." && $file != ".."  && $file != ".htaccess" && $file != "htaccess" && !is_file($source_dir."/".$file)) {
                       @unlink($dest_dir."/".$file);
                    }
                }
                closedir($dir);
            }
        }
        return "";
    }
    
    function sync_lang() {
        global $mklib,  $mkportals;
        
        $write_error = "&nbsp;&nbsp;<span style='color:red;font-weight:bold;'>{$mklib->lang['ad_langs_sync_write_error']}</span>";
        $del = $mkportals->input['del'];
        $del_file = $mkportals->input['del_file'];
        $new = $mkportals->input['new'];
        $source = $mkportals->input['lang'];
        $dest = $mkportals->input['dest'];
        if (empty($source) || !is_array($dest)) {
            return $write_error;
        }
		$source_dir = MK_PATH."mkportal/lang/".$source;
        if (!is_dir($source_dir)) {
            return "&nbsp;&nbsp;<span style='color:red;font-weight:bold;'>{$mklib->lang['ad_langs_sync_read_error']}</span>";
        }        
        foreach ($dest as $dest_lang) {
            if ($dest_lang == $source) {
                continue;
            }
            $message = $this->sync_lang_dir($source, $dest_lang, $new, $del, $del_file);
            if ($message) {
                return $message;
            }
        }
        return "&nbsp;&nbsp;<span style='color:blue;font-weight:bold;'>{$mklib->lang['ad_langs_sync_success']}</span>";
    }
    
	function process($message = "") {
        global $mklib, $Skin,  $mkportals;
        
        if (isset($mkportals->input['back'])) {
            $this->list_current();
        }
        $file = $mkportals->input['file'];
		$lang = $mkportals->input['lang'];
		$lang_dir = MK_PATH."mkportal/lang/".$lang;
        if (!is_dir($lang_dir)) {
            $this->list_current();
            exit;
        }
        if ($dir = @opendir($lang_dir)) {
            $cfile = "\n<select class=\"bgselect\" size=\"1\" name=\"file\">\n";
            while (($dfile = readdir($dir)) !== false) {
                if ($dfile != "." && $dfile != ".." && !is_dir($dfile) && preg_match("`\.php$`i", $dfile)) {
                    $lang_file = str_replace(".php", "", $dfile);
                    if ($lang_file == $file) {
                        $selected= "selected='selected'";
                    }
                    else {
                        $selected= "";
                    }
                    $cfile .= "<option value='".$lang_file."' $selected >$lang_file</option>\n";
                }
            }
            closedir($dir);
            $cfile .= "</select>\n";
        }
        if ($message and !isset($mkportals->input['translate'])) {
            $message = "
                         <tr>
                            <td class=\"tdblock\">
                                $message
                            </td>
                         </tr>
                         ";
        }
        $content = "
        $message
        <tr><td>
        <form action='index.php?ind=ad_langs&op=process' name='AdminFile' method='post'>
        <input type='hidden' name='lang' value='{$lang}' />
        <table width=\"100%\" border=\"0\">
            <tr>
                <td class=\"titadmin\" colspan='2'>{$mklib->lang['ad_langs_edit_info']}<br /></td>
            </tr>
            <tr>
                <td>$cfile</td>
            </tr>
            <tr>
                <td>
                    <br />
                    <input type=\"submit\" name=\"run\" value=\"{$mklib->lang['ad_langs_edit_ok']}\" class=\"mkbutton\">&nbsp;&nbsp;
                    <input type='submit' name='translate' value='{$mklib->lang['ad_langs_translate']}' class='mkbutton'>&nbsp;
                    <input type=\"submit\" name=\"back\" value=\"{$mklib->lang['ad_langs_back']}\" class=\"mkbutton\">
                </td>
            </tr>
        </table>
        </form>
        ";
        $content .= "
        </td></tr>
        ";
        $output = $Skin->view_block($mklib->lang['ad_langs_edit_title']."&nbsp;[$lang]", "$content");
        if (isset($mkportals->input['translate'])) {
            $this->process_translate($output, $message);
            exit;
        }
        $this_file = $lang_dir."/".$file.".php";
        if (is_file($this_file)) {
            @require $this_file;
            if (is_array($langmk)) {
                $content = "
                     <tr><td>
                        <form action='index.php?ind=ad_langs&op=save' name='AdminEdit' method='post'>
                        <input type='hidden' name='lang' value='{$lang}' />
                        <input type='hidden' name='file' value='{$file}' />
                        <table width=\"100%\" border=\"0\" cellpadding=\"2\" cellspacing=\"1\">
                            <tr>
                                <th class=\"bgselect\" width='15%'>{$mklib->lang['ad_langs_language']}</th>
                                <th class=\"bgselect\" width='1%'>&nbsp;</th>
                                <th class=\"bgselect\" width='84%'>{$mklib->lang['ad_langs_file']}</th>
                            </tr>
                            <tr>
                                <td class=\"tdblock\"><b><br />$lang</b><br /><br /></td>
                                <td class=\"tdblock\">&nbsp;</td>
                                <td class=\"tdblock\"><b><br />$file</b><br /><br /></td>
                            </tr>
                            <tr>
                                <th class=\"bgselect\" width='15%'><br />{$mklib->lang['ad_langs_file_key']}</th>
                                <th class=\"bgselect\" width='1%'><br />Editor</th>
                                <th class=\"bgselect\" width='84%'><br />{$mklib->lang['ad_langs_file_value']}</th>
                            </tr>
                           ";
                $editor_status = "normal";
                foreach($langmk as $key => $value) {
                    $act_editor_status = "normal";
                    if (isset($mkportals->input["switch_".$key])) {
                        if ($mkportals->input['editor_key'] != $key) {
                            $act_editor_status = "html";
                        }
                        else {
                            $act_editor_status = $mkportals->input['editor']=="html"?"normal":"html";
                        }
                        $editor_key = $key;
                        $content .= "
                        <input type='hidden' name='editor_key' value='$editor_key' />
                        ";
                    }
                    $linefeeds = substr_count($value, "\n");
                    $linefeeds += substr_count($value, "<br />");
                    $linefeeds += substr_count($value, "<br>");
                    $rows = min(5, max(2, $linefeeds));
                    $group_key = $key;
                    $group = "";
                    $style = "";
                    if (substr($key, 0, 6) == "group_") {
                        $group = "<i>{$mklib->lang['ad_langs_group_title']}</i><br />";
                        $style_row = "style='background-color:#DDDDCC;'";
                    }
                    else {
                        $style_row = "";
                    }
                    $value = $mklib->post_htmlspecialchars(stripslashes($value));
                    if ($act_editor_status == "html") {
                        $editor_status = "html";
                        $js = $mklib->get_editor();
                        $rows += 5;
                        $content .= "
                            <tr>
                                <td class=\"tdblock\" $style_row>
                                    $js
                                    $group
                                    <b>$key</b>
                                </td>
                                <td class=\"tdblock\" $style_row >
                                    <center><input type='submit' name='switch_".$key."' value='RAW'  class='mkbutton' /></center>
                                </td>
                                <td class=\"tdblock\" $style_row>
                                    <textarea class='bgselect' style='background-color:#FFFFFF;' name='$key' mce_editable=\"true\" style=\"width:100%\"  cols='120' rows='$rows' />$value</textarea>
                                </td>
                            </tr>
                                ";
                    }
                    else {
                        $content .= "
                            <tr>
                                <td class=\"tdblock\" $style_row >
                                    $group
                                    <b>$key</b>
                                </td>
                                <td class=\"tdblock\" $style_row>
                                    <center><input type='submit' name='switch_".$key."' value='HTML'  class='mkbutton' /></center>
                                </td>
                                <td class=\"tdblock\" $style_row>
                                    <textarea class='bgselect' style='background-color:#FFFFFF;' name='$key' cols='120' rows='$rows' />$value</textarea>
                                </td>
                            </tr>
                                ";
                    }
                }
                $content .= "
                     <tr>
                        <td>
                             <input type='hidden' name='editor' value='$editor_status' />
                            <br /><input type='submit' name='run' value='{$mklib->lang['ad_langs_file_save']}' class='mkbutton' size=>
                        </td>
                    </tr>
                    ";
                $content .= "\n                     </table>\n";
                $content .= "\n                 </td></tr>\n";
                $output .= $Skin->view_block($mklib->lang['ad_langs_file_title'], "$content");
            }
        }
        $mklib->printpage_admin($mklib->lang['ad_titlepage'].$mklib->lang['tt_sep'].$mklib->lang['ad_langs_file_title'], $output, $editor['load']);
  	}

	function process_single($message = "") {
        global $mklib, $Skin,  $mkportals;
        
        if (!isset($mkportals->input['key'])) {
            $this->list_current();
        }
        if (isset($mkportals->input['back'])) {
            $this->list_current();
        }
		$lang_dir = MK_PATH."mkportal/lang/";
        if (!is_dir($lang_dir)) {
            $this->list_current();
            exit;
        }
        if ($dh = @opendir($lang_dir)) {
            while (($dir = readdir($dh)) !== false) {
                if ($dir != "." && $dir != ".." && is_dir($lang_dir.$dir)) {
                    $dirs[] = $dir;
                }
            }
            closedir($dh);
        }
        if (!is_array($dirs)) {
            $this->list_current();
            exit;
        }
        $file = $mkportals->input['file'];
        $group = $mkportals->input['group'];
        $key = $mkportals->input['key'];
        $rows = $mkportals->input['rows'];
        foreach($dirs as $dir) {
            if (is_file($lang_dir.$dir."/".$file.".php")) {
                require($lang_dir.$dir."/".$file.".php");
                $langs[$dir] = $langmk[$key];
                unset($langmk);
            }
        }
        if (!is_array($langs)) {
            $this->list_current();
            exit;
        }
        unset ($dirs);
        $num = count($langs);
        if ($message) {
            $message = "
                         <tr>
                            <td class=\"tdblock\">
                                $message
                            </td>
                         </tr>
                         ";
        }
        $content = "
        $message
        <tr>
            <td>
                <form action='index.php?ind=ad_langs&op=save_single' name='AdminFile' method='post'>
                <input type='hidden' name='file' value='{$file}' />
                <input type='hidden' name='group' value='{$group}' />
                <input type='hidden' name='key' value='{$key}' />
                <input type='hidden' name='num' value='{$num}' />
                <table width=\"100%\" border=\"0\" cellpadding=\"2\" cellspacing=\"1\">
                    <tr>
                        <td class=\"tdblock\" colspan=\"2\">
                            <br />{$mklib->lang['ad_langs_edit_all']}<br /><br />
                        </td>
                    </tr>
                        <th class=\"bgselect\" width='15%'>{$mklib->lang['ad_langs_file']}</th>
                        <th class=\"bgselect\" width='85%'>{$mklib->lang['ad_langs_file_key']}</th>
                    <tr>
                        <td class=\"tdblock\"><b><br />$file.php</b><br /><br /></td>
                        <td class=\"tdblock\"><b><br />$key</b><br /><br /></td>
                    </tr>
                    <tr>
                        <th class=\"bgselect\" width='15%'><br />{$mklib->lang['ad_langs_language']}</th>
                        <th class=\"bgselect\" width='85%'><br />{$mklib->lang['ad_langs_file_value']}</th>
                    </tr>
                        ";
        foreach ($langs as $lang => $lang_entry) {
            $pos++;
            $content .= "
                    <tr>
                        <td class='tdblock'><b>$lang</b></td>
                        <td class=\"tdblock\">
                            <input type='hidden' name='lang_{$pos}' value='{$lang}' />
                            <textarea class='bgselect' name='$lang' cols='120' rows='$rows' />$lang_entry</textarea>
                        </td>
                    </tr>
                            ";
        }
        $content .= "
                    <tr>
                        <td>&nbsp;</td>
                        <td>
                            <br />
                            <input type='submit' name='run' value='{$mklib->lang['ad_langs_files_save']}' class='mkbutton' size=>
                            <br /><br />
                        </td>
                    </tr>
                </table>
                </form>
            </td>
        </tr>
        ";
        $output .= $Skin->view_block($mklib->lang['ad_langs_file_title'], "$content");
        $mklib->printpage_admin($mklib->lang['ad_titlepage'].$mklib->lang['tt_sep'].$mklib->lang['ad_langs_file_title'], $output);
  	}

	function process_translate($output = "", $message = "") {
        global $mklib, $Skin,  $mkportals;
        
        if (isset($mkportals->input['back'])) {
            $this->list_current();
            exit;
        }
		$lang = $mkportals->input['lang'];
		$lang_dir = MK_PATH."mkportal/lang/".$lang;
        $reference_dir = MK_PATH."mkportal/lang/English_Reference";
        if (!is_dir($lang_dir) || !is_dir($reference_dir)) {
            $this->list_current();
            exit;
        }
        $file = $mkportals->input['file'].".php";
        if (!is_file($reference_dir."/".$file) || !is_file($lang_dir."/".$file)) {
            $this->list_current();
            exit;
        }
        require ($reference_dir."/".$file);
        if (!is_array($langmk)) {
            $this->list_current();
            exit;
        }
        foreach($langmk as $key => $value) {
            $ref_lang[$key] = $value;
        }
        unset($langmk);
        require ($lang_dir."/".$file);
        if (!is_array($langmk)) {
            unset ($ref_lang);
            $this->list_current();
            exit;
        }
        $num = count($langmk);
        if ($message) {
            $message = "
                         <tr>
                            <td class=\"tdblock\">
                                $message
                            </td>
                         </tr>
                         ";
        }
        $content = "
        $message
        <tr>
            <td>
                <form action='index.php?ind=ad_langs&op=save_translate' name='AdminFile' method='post'>
                <input type='hidden' name='file' value='{$mkportals->input['file']}' />
                <input type='hidden' name='lang' value='{$lang}' />
                <table width=\"100%\" border=\"0\" cellpadding=\"2\" cellspacing=\"1\">
                    <tr>
                        <td class=\"tdblock\" colspan=\"2\">
                            <br />{$mklib->lang['ad_langs_translate_todo']}<br /><br />
                        </td>
                    </tr>
                        <th class=\"bgselect\" width='15%'>{$mklib->lang['ad_langs_language']}</th>
                        <th class=\"bgselect\" width='85%'>{$mklib->lang['ad_langs_file']}</th>
                    <tr>
                        <td class=\"tdblock\"><b><br />$lang</b><br /><br /></td>
                        <td class=\"tdblock\"><b><br />$file</b><br /><br /></td>
                    </tr>
                    <tr>
                        <th class=\"bgselect\" width='15%'><br />{$mklib->lang['ad_langs_file_key']}</th>
                        <th class=\"bgselect\" width='85%'><br />{$mklib->lang['ad_langs_file_value']}</th>
                    </tr>
                ";
        foreach ($langmk as $key => $value) {
            if (empty($key)) {
                $num--;
                continue;
            }
            if ($langmk[$key] != $ref_lang[$key]) {
                $num--;
                continue;
            }
            $pos++;
            $linefeeds = substr_count($value, "\n");
            $linefeeds += substr_count($value, "<br />");
            $linefeeds += substr_count($value, "<br>");
            $rows = min(5, max(1, $linefeeds));
            $group = "";
            $style = "";
            if (substr($key, 0, 6) == "group_") {
                $group = "<i>{$mklib->lang['ad_langs_group_title']}</i><br />";
                $style_row = "style='background-color:#DDDDCC;'";
            }
            else {
                $style_row = "";
            }
            $content .= "
                <tr>
                    <td class=\"tdblock\">
                        <i>{$mklib->lang['ad_langs_translate_ref']}</i>
                    </td>
                    <td class=\"tdblock\">
                        <textarea class='bgselect' style='background-color:#CCCCCC;' name='ref' cols='120' rows='$rows' readonly=\"readonly\"/>{$ref_lang[$key]}</textarea>
                    </td>
                </tr>
                <tr>
                    <td class=\"tdblock\" $style_row>
                        $group
                        <b>$key</b>
                    </td>
                    <td class=\"tdblock\" $style_row>
                        <textarea class='bgselect' style='background-color:#FFFFFF;'  name='$key' cols='120' rows='$rows' />$value</textarea>
                    </td>
                </tr>
                <tr>
                    <td class=\"tdblock2\" colspan=\"2\">
                        &nbsp;
                    </td>
                </tr>
                    ";
        }
        if ($num == 0) {
            $content = "
                    <tr>
                        <td>
                            {$mklib->lang['ad_langs_translate_done']}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <br />
                                <input type=\"submit\" name=\"back\" value=\"{$mklib->lang['ad_langs_back']}\" class=\"mkbutton\">
                            <br /><br />
                        </td>
                    </tr>
                       ";
        }
        else {
            $content .= "
                        <tr>
                            <td>&nbsp;</td>
                            <td>
                                <br />
                                    <input type=\"submit\" name=\"save\" value=\"{$mklib->lang['ad_langs_edit_ok']}\" class=\"mkbutton\">&nbsp;
                                    <input type=\"submit\" name=\"back\" value=\"{$mklib->lang['ad_langs_back']}\" class=\"mkbutton\">
                                <br /><br />
                            </td>
                        </tr>
                        ";
        }
        $content .= "
                </table>
                </form>
            </td>
        </tr>
        ";
        $output .= $Skin->view_block($mklib->lang['ad_langs_translate_title'], $content);
        $mklib->printpage_admin($mklib->lang['ad_titlepage'].$mklib->lang['tt_sep'].$mklib->lang['ad_langs_translate_title'], $output);
  	}

	function save() {
    	global $mklib, $mklang,  $mkportals;
        
        $mklang->lang_save($mkportals->input['lang'], $mkportals->input['file'], TRUE );
        unset ($mkportals->input['back']);
        $this->process($mklib->lang['ad_langs_file_saved']);
  	}

	function save_single() {
    	global $mklib, $mklang,  $mkportals;
        
        $file = $mkportals->input['file'];
        $group = $mkportals->input['group'];
        $key = $mkportals->input['key'];
        $num = intval($mkportals->input['num']);
        for ($i = 1; $i <= $num; $i++) {
            $lang = $mkportals->input["lang_".$i];
            $value = $mkportals->input[$lang];
            if (!$mklang->lang_update_entry($key, $value, "", $file, $lang, $group)) {
                $mklang->lang_insert_entry($key, $value, "", $file, $lang, $group);
            }
        }
        unset ($mkportals->input['back']);
        $this->process_single($mklib->lang['ad_langs_file_saved']);
  	}

	function save_translate() {
    	global $mklib, $mklang,  $mkportals;
        
        $file = $mkportals->input['file'];
        $lang = $mkportals->input['lang'];
        $mklang->lang_save($lang, $file, FALSE);
        unset ($mkportals->input['back']);
        $mkportals->input['translate'] = "1";
        $this->process($mklib->lang['ad_langs_file_saved']);
  	}

}

?>
