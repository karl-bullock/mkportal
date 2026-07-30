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
|   Generic language functions
|   > (c) 2005 by Peter (Peter@ibforen.de)
|
+--------------------------------------------------------------------------
*/


define("trans_array","array( 'è' => '&egrave;',
                             'à' => '&agrave;',
                             'é' => '&eacute;',
                             'â' => '&acirc;',
                             'ê' => '&ecirc;',
                             'î' => '&icirc;',
                             'ô' => '&ocirc;',
                             'û' => '&ucirc;',
                             'ç' => '&ccedil;',
                             'ä' => '&auml;',
                             'Ä' => '&Auml;',
                             'ö' => '&ouml;',
                             'Ö' => '&Ouml;',
                             'ü' => '&uuml;',
                             'Ü' => '&Uuml;',
                             'ß' => '&szlig;',
                             )"); 

class mklang {

	function update_key($oldkey, $newkey, $prefix = "t_", $title = "", $file = "", $lang = "", $group = "group_block_titles") {
    	global  $mklib;
        
        $result = FALSE;
        @chmod(MK_PATH."mkportal/lang", 0777);
        $file = preg_replace("`\.php$`i", "", $file);
		$lang_dir = MK_PATH."mkportal/lang/".$lang;
        $this_file = $lang_dir."/".$file.".php";
        $this_oldkey = $prefix.$oldkey;
        $this_newkey = $prefix.$newkey;
        if (is_file($this_file)) {
            @require $this_file;
            if (is_array($langmk) ) {
                foreach ($langmk as $key => $value) {
                    if ($key == $this_oldkey) {
                        $lang_array[$this_newkey] = $value;
                    }
                    elseif ($key == $group && !isset($lang_array[$this_newkey]) && !isset($langmk[$this_oldkey])) {
                        $lang_array[$key] = $value;
                        $lang_array[$this_newkey] = $title;
                    }
                    else {
                        $lang_array[$key] = $value;
                    }
                }
                unset ($langmk);
                $content = "<?php\n\n";
                foreach($lang_array as $key => $value) {
                    $lang_array[$key] = str_replace("\"", "\\\"", $lang_array[$key]);
                    if (substr($key, 0, 6) == "group_") {
                        $content .= "\n\n// ----------------------------------------------------\n";
                        $content .= "\$langmk['$key'] = \"{$lang_array[$key]}\";\n";
                        $content .= "// ----------------------------------------------------\n";
                    }
                    else {
                        $content .= "\$langmk['$key'] = \"{$lang_array[$key]}\";\n";
                    }
                }
                $content .= "\n\n?>";
                @chmod($this_file, 0666);
                $fh = @fopen($this_file, "wb");
                if ($fh) {
                    fwrite($fh, $content);
                    fclose($fh);
                    $result = TRUE;
                }
                else {
                    $message = str_replace("<# FILE #>", $this_file, $mklib->lang['ad_langs_write_error']);
                    $mklib->error_page($message);
                    exit;
                }
            }
        }
        unset ($lang_array);
        return $result;
    }

	function lang_insert_entry($key, $value, $prefix = "t_", $file = "", $lang = "", $group = "group_block_titles") {
    	global  $mklib;

        $result = FALSE;
        @chmod(MK_PATH."mkportal/lang", 0777);
        $file = preg_replace("`\.php$`i", "", $file);
		$lang_dir = MK_PATH."mkportal/lang/".$lang;
        $this_file = $lang_dir."/".$file.".php";
        $this_key = $prefix.$key;
        if (is_file($this_file)) {
            @require $this_file;
            if (is_array($langmk) ) {
                if (!isset($langmk[$group])){
                    $langmk[$group] = $group;
                }
                if (!isset($langmk[$this_key])) {
                    foreach ($langmk as $lkey => $lvalue) {
                        $lang_array[$lkey] = $lvalue;
                        if ($lkey == $group) {
                            $lang_array[$this_key] = $value;
                        }
                    }
                    unset ($langmk);
                    if (!isset($lang_array[$key])) {
                        $lang_array[$this_key] = $value;
                    }
                    $content = "<?php\n\n";
                    foreach($lang_array as $key => $value) {
                        $lang_array[$key] = str_replace("\"", "\\\"", $lang_array[$key]);
                        if (substr($key, 0, 6) == "group_") {
                            $content .= "\n\n// ----------------------------------------------------\n";
                            $content .= "\$langmk['$key'] = \"{$lang_array[$key]}\";\n";
                            $content .= "// ----------------------------------------------------\n";
                        }
                        else {
                            $content .= "\$langmk['$key'] = \"{$lang_array[$key]}\";\n";
                        }
                    }
                    $content .= "\n\n?>";
                    @chmod($this_file, 0666);
                    $fh = @fopen($this_file, "wb");
                    if ($fh) {
                        fwrite($fh, $content);
                        fclose($fh);
                        $result = TRUE;
                    }
                    else {
                        $message = str_replace("<# FILE #>", $this_file, $mklib->lang['ad_langs_write_error']);
                        $mklib->error_page($message);
                        exit;
                    }
                }
            }
        }
        unset ($lang_array);
        return $result;
  	}

	function lang_update_entry($key, $value, $prefix = "t_", $file = "", $lang = "", $group = "group_block_titles") {
    	global  $mklib;

        $result = FALSE;
        @chmod(MK_PATH."mkportal/lang", 0777);
        $file = preg_replace("`\.php$`i", "", $file);
		$lang_dir = MK_PATH."mkportal/lang/".$lang;
        $this_file = $lang_dir."/".$file.".php";
        $this_key = $prefix.$key;
        if (is_file($this_file)) {
            @require $this_file;
            if (is_array($langmk) ) {
                if (isset($langmk[$this_key])) {
                    $langmk[$this_key] = $value;
                    $content = "<?php\n\n";
                    foreach($langmk as $key => $value) {
                        $langmk[$key] = str_replace("\"", "\\\"", $langmk[$key]);
                        if (substr($key, 0, 6) == "group_") {
                            $content .= "\n\n// ----------------------------------------------------\n";
                            $content .= "\$langmk['$key'] = \"{$langmk[$key]}\";\n";
                            $content .= "// ----------------------------------------------------\n";
                        }
                        else {
                            $content .= "\$langmk['$key'] = \"{$langmk[$key]}\";\n";
                        }
                    }
                    $content .= "\n\n?>";
                    @chmod($this_file, 0666);
                    $fh = @fopen($this_file, "wb");
                    if ($fh) {
                        fwrite($fh, $content);
                        fclose($fh);
                        $result = TRUE;
                    }
                    else {
                        $message = str_replace("<# FILE #>", $this_file, $mklib->lang['ad_langs_write_error']);
                        $mklib->error_page($message);
                        exit;
                    }
                }
            }
        }
        unset ($langmk);
        return $result;
  	}

	function lang_delete_entry($key, $prefix = "t_", $file = "", $lang = "", $group = "group_block_titles") {
    	global  $mklib;

        $result = FALSE;
        @chmod(MK_PATH."mkportal/lang", 0777);
        $file = preg_replace("`\.php$`i", "", $file);
		$lang_dir = MK_PATH."mkportal/lang/".$lang;
        $this_file = $lang_dir."/".$file.".php";
        $this_key = $prefix.$key;
        if (is_file($this_file)) {
            @require $this_file;
            if (is_array($langmk) ) {
                if (isset($langmk[$this_key])) {
                    unset($langmk[$this_key]);
                    $content = "<?php\n\n";
                    foreach($langmk as $key => $value) {
                        $langmk[$key] = str_replace("\"", "\\\"", $langmk[$key]);
                        if (substr($key, 0, 6) == "group_") {
                            $content .= "\n\n// ----------------------------------------------------\n";
                            $content .= "\$langmk['$key'] = \"{$langmk[$key]}\";\n";
                            $content .= "// ----------------------------------------------------\n";
                        }
                        else {
                            $content .= "\$langmk['$key'] = \"{$langmk[$key]}\";\n";
                        }
                    }
                    $content .= "\n\n?>";
                    @chmod($this_file, 0666);
                    $fh = @fopen($this_file, "wb");
                    if ($fh) {
                        fwrite($fh, $content);
                        fclose($fh);
                        $result = TRUE;
                    }
                    else {
                        $message = str_replace("<# FILE #>", $this_file, $mklib->lang['ad_langs_write_error']);
                        $mklib->error_page($message);
                        exit;
                    }
                }
            }
        }
        unset ($langmk);
        return $result;
  	}

	function lang_insert_all_entries($key, $value, $prefix = "t_", $file = "", $group = "group_block_titles") {
    	global  $mklib;

        @chmod(MK_PATH."mkportal/lang", 0777);
        if ($dir = @opendir(MK_PATH."mkportal/lang")) {
            while (($dirt = readdir($dir)) !== false) {
                if ($dirt != "." && $dirt != "..") {
                    $this->lang_insert_entry($key, $value, $prefix, $file, $dirt , $group);
                }
            }
            closedir($dir);
        }
  	}

	function update_all_keys($oldkey, $newkey, $prefix = "t_", $title = "", $file = "", $group = "group_block_titles") {
    	

        @chmod(MK_PATH."mkportal/lang", 0777);
        if ($dir = @opendir(MK_PATH."mkportal/lang")) {
            while (($dirt = readdir($dir)) !== false) {
                if ($dirt != "." && $dirt != "..") {
                    $this->update_key($oldkey, $newkey, $prefix, $title, $file, $dirt, $group);
                }
            }
            closedir($dir);
        }
  	}

	function lang_delete_all_entries($key, $prefix = "t_", $file = "", $group = "group_block_titles") {
    	

        @chmod(MK_PATH."mkportal/lang", 0777);
        if ($dir = @opendir(MK_PATH."mkportal/lang")) {
            while (($dirt = readdir($dir)) !== false) {
                if ($dirt != "." && $dirt != "..") {
                    $this->lang_delete_entry($key, $prefix, $file, $dirt , $group);
                }
            }
            closedir($dir);
        }
  	}

	function lang_save($lang = "", $file = "", $cut = FALSE) {
    	global  $mkportals, $mklib;

        $result = FALSE;
        if ($lang == "" || $file == "") {
            return $result;
        }

        eval("\$this->lang_trans = ".trans_array.";");

        @chmod(MK_PATH."mkportal/lang", 0777);
		$lang_dir = MK_PATH."mkportal/lang/".$lang;
        $this_file = $lang_dir."/".$file.".php";
        if (is_file($this_file)) {
            @require $this_file;
            if (is_array($langmk) ) {
                $content = "<?php\n\n";
                foreach($langmk as $key => $value) {
                    if (isset($mkportals->input[$key])) {
                        $langmk[$key] = stripslashes($_POST[$key]);
//                        $langmk[$key] = strtr($langmk[$key], $this->lang_trans );
                        unset($mkportals->input[$key]);
                    }
                    elseif ($cut) {
                        $langmk[$key] = "";
                    }
//                    $langmk[$key] = str_replace("\"", "\\\"", $langmk[$key]);
                    $langmk[$key] = addslashes($langmk[$key]);
                    $langmk[$key] = str_replace("$", "\\$", $langmk[$key]);
                    if (substr($key, 0, 6) == "group_") {
                        $content .= "\n\n// ----------------------------------------------------\n";
                        $content .= "\$langmk['$key'] = \"{$langmk[$key]}\";\n";
                        $content .= "// ----------------------------------------------------\n";
                    }
                    else {
                        $content .= "\$langmk['$key'] = \"{$langmk[$key]}\";\n";
                    }
                }
                $content .= "\n\n?>";
                @chmod($this_file, 0666);
                $fh = @fopen($this_file, "wb");
                if ($fh) {
                    fwrite($fh, $content);
                    fclose($fh);
                    $result = TRUE;
                }
                else {
                    $message = str_replace("<# FILE #>", $this_file, $mklib->lang['ad_langs_write_error']);
                    $mklib->error_page($message);
                    exit;
                }
            }
        }
        unset ($langmk);
        return $result;
	}

}

?>