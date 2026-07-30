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

// This class contains the board-dependent functions

if (!defined("IN_MKP")) {
    die ("Sorry !! You cannot access this file directly.");
}

class mklib_board {

//common functions

    function store_location($loc) {
        global $mkportals, $DB, $user_info;
        $prefix = DBPREFIX;
        switch($loc) {
                            case 'portale':
                                $location= "-20";
                            break;
                            case 'blog':
                                $location= "-21";
                            break;
                            case 'gallery':
                                $location= "-22";
                            break;
                            case 'urlobox':
                                $location= "-23";
                            break;
                            case 'downloads':
                                $location= "-24";
                            break;
                            case 'news':
                                $location= "-25";
                            break;
                            case 'topsite':
                                $location= "-26";
                            break;
                            case 'chat':
                                $location= "-27";
                            break;
                            case 'reviews':
                                $location= "-28";
                            break;
                            default:
                            $location= "-20";
                            break;
                        }
        $idu = $mkportals->member['id'];
	$session_id = $user_info['is_guest'] ? 'ip' . $user_info['ip'] : session_id();
        $DB->query("UPDATE {$prefix}log_online SET url ='$location' WHERE session = '$session_id'");
    }


    function get_active_users($loc) {

        global $DB, $mkportals, $mklib;

        switch($loc) {
                            case 'portale':
                                $location= "-20";
                            break;
                            case 'blog':
                                $location= "-21";
                            break;
                            case 'gallery':
                                $location= "-22";
                            break;
                            case 'urlobox':
                                $location= "-23";
                            break;
                            case 'downloads':
                                $location= "-24";
                            break;
                            case 'news':
                                $location= "-25";
                            break;
                            case 'topsite':
                                $location= "-26";
                            break;
                            case 'chat':
                                $location= "-27";
                            break;
                            case 'reviews':
                                $location= "-28";
                            break;
                            default:
                            $location= "-20";
                            break;
                        }

    $prefix = DBPREFIX;
    $context['users_online'] = array();
    $context['list_users_online'] = array();
    $context['online_groups'] = array();
    $context['num_guests'] = 0;
    $context['num_users_hidden'] = 0;

        $sql = "SELECT lo.id_member, lo.log_time, lo.url, mem.real_name, mem.member_name, mem.show_online, mg.online_color, mg.id_group, mg.group_name
        FROM {$prefix}log_online AS lo
            LEFT JOIN {$prefix}members AS mem ON (mem.id_member = lo.id_member)
            LEFT JOIN {$prefix}membergroups AS mg ON (mg.id_group = IF(mem.id_group = 0, mem.ID_POST_GROUP, mem.id_group))
            WHERE lo.url = '$location'";

    $DB->query($sql);

    while ($row = $DB->fetch_row() )
    {
        if (!isset($row['real_name']))
        {
            $context['num_guests']++;
            continue;
        }
        elseif (!empty($row['show_online']) || allowedTo('moderate_forum'))
        {

            if (!empty($row['online_color']))
                $link = '<a href="/' . $mkportals->forum_url . '/index.php?action=profile;u=' . $row['id_member'] . '" style="color: ' . $row['online_color'] . ';">' . $row['real_name'] . '</a>';
            else
                $link = '<a href="/' . $mkportals->forum_url . '/index.php?action=profile;u=' . $row['id_member'] . '">' . $row['real_name'] . '</a>';

            $context['users_online'][$row['log_time'] . $row['member_name']] = array(
                'id' => $row['id_member'],
                'username' => $row['member_name'],
                'name' => $row['real_name'],
                'group' => $row['id_group'],
                'href' => $scripturl . '?action=profile;u=' . $row['id_member'],
                'link' => $link,
                'hidden' => empty($row['show_online']),
            );

            $context['list_users_online'][$row['log_time'] . $row['member_name']] = empty($row['show_online']) ? '<i>' . $link . '</i>' : $link;

        }
        else
            $context['num_users_hidden']++;
    }

    krsort($context['users_online']);
    krsort($context['list_users_online']);
    ksort($context['online_groups']);
    $context['users_online'] = count($context['users_online']);

    $listusers = implode(', ', $context['list_users_online']);


            $total_online_users = $context['users_online'] + $context['num_users_hidden'] + $context['num_guests'];
            $utenti_in = "{$mklib->lang['b_tusers']} ";
            $utenti_in .= $total_online_users;
            $utenti_in .= " ({$context['users_online']} {$mklib->lang['b_rusers']} {$context['num_guests']} {$mklib->lang['b_guests']} {$mklib->lang['b_and']} {$context['num_users_hidden']} {$mklib->lang['b_anons']})<br />";
            $utenti_in .= "{$mklib->lang['b_vusers']}  $listusers";
            return $utenti_in;
    }

    function show_emoticons() {
        global $mkportals, $DB, $Skin, $mklib, $user_info, $modSettings;
        $css = $this->import_css();
        
        $smilieset = $mkportals->member['smiley_set'] = (!in_array($user_info['smiley_set'], explode(',', $modSettings['smiley_sets_known'])) && $user_info['smiley_set'] != 'none') || empty($modSettings['smiley_sets_enable']) ? (!empty($settings['smiley_sets_default']) ? $settings['smiley_sets_default'] : $modSettings['smiley_sets_default']) : $user_info['smiley_set'];
        
        if (!$smilieset) {
            $smilieset = "default";
        }
        
        $DB->query("SELECT code, filename AS image FROM ".DBPREFIX.smileys." WHERE hidden = '0'");
        $this->emo_path = "Smileys/$smilieset/";
        $output = $this->get_emo_header($css);
        $countr = 0;
        if ( $DB->get_num_rows() ) {
            while ( $r = $DB->fetch_row() ) {
                if ($countr == 3) {
                    $output .= "</tr><tr>";
                    $countr = 0;
                }
                $output .= $this->get_emo_row($r['code'], $r['image']);
                ++$countr;
                if ($countr == 2) {
                    $output .= "</tr><tr>";
                    $countr = 0;
                }                                
            }
        }
        $output .= "<td></td></tr></table></body></html>";
        print $output;
     }

    function decode_smilies($message)
     {
        global $mklib, $user_info, $modSettings;

        $user_info['smiley_set'] = $mkportals->member['smiley_set'] = (!in_array($user_info['smiley_set'], explode(',', $modSettings['smiley_sets_known'])) && $user_info['smiley_set'] != 'none') || empty($modSettings['smiley_sets_enable']) ? (!empty($settings['smiley_sets_default']) ? $settings['smiley_sets_default'] : $modSettings['smiley_sets_default']) : $user_info['smiley_set'];
        if (!$user_info['smiley_set']) {
            $user_info['smiley_set'] = "default";
        }

        //$user_info['smiley_set'] = "default";
        //$message = doUBBC($message);
        parsesmileys($message);
        //$message = str_replace("images/smiles/", "$mklib->siteurl/$mklib->forumpath/images/smiles/", $message);

        return $message;
    }
    function popup_pm($m1, $m2, $m3, $m4)
     {
        global $DB, $mklib, $mkportals;


        $u1 = "$mklib->siteurl/$mklib->forumpath/index.php?action=pm";

        //$DB->query("UPDATE ".DBPREFIX."members SET unreadMessages='0' WHERE id_member='{$mkportals->member['id']}'");


        $pmk_js = "<script type=\"text/javascript\">
                     <!--
                       window.open('$mklib->siteurl/mkportal/pmpopup.php?m1=$m1&m2=$m2&m3=$m3&m4=$m4&u1=$u1','NewPM','width=500,height=250,resizable=yes,scrollbars=yes');
                     //-->
                     </script>";


        return $pmk_js;
    }

// admin functions

    //ad_perms
    function build_grouplist($ind)
    {
        global $DB, $mklib;

        $prefix = DBPREFIX;
        $DB->query( "SELECT id_group, group_name FROM {$prefix}membergroups ORDER BY `id_group`");
        while( $row = $DB->fetch_row() ) {
            if($row['id_group'] == 1) {
                continue;
            }
            $g_id= $row['id_group'];
            $g_title = $row['group_name'];
            $selected = "";
            if($g_id == $ind) {
                $selected = "selected=\"selected\"";
            }
            $cselect.= "<option value=\"$g_id\" $selected>$g_title</option>\n";
        }
        //adds guests
        $selected = "";
	if($ind == "99") {
                $selected = "selected=\"selected\"";
            }
        $cselect.= "<option value=\"99\" $selected>{$mklib->lang['guests']}</option>\n";
        return $cselect;
    }
    function build_grouplist2()
    {
        global $DB, $mklib;
        $group = array();
        $prefix = DBPREFIX;
        $DB->query( "SELECT id_group, group_name FROM {$prefix}membergroups ORDER BY `id_group`");
        while( $row = $DB->fetch_row() ) {
            if($row['id_group'] == 1) {
                continue;
            }
            $g_id = $row['id_group'];
            $group[$g_id][id] = $row['id_group'];
            $group[$g_id][title] = $row['group_name'];
        }
        //add guests
        $g_id = 99;
        $group[$g_id][id] = 99;
        $group[$g_id][title] = $mklib->lang['guests'];
        return $group;
    }
    
    
    //ad_perms
    function update_groupperms($g_id)
    {
        global $DB;

        $prefix = DBPREFIX;
        $query = $DB->query( "SELECT group_name FROM {$prefix}membergroups WHERE id_group = '$g_id'");
        $row = $DB->fetch_row($query);
		//Changed by Kimi in C1.2.2 
        return $row['group_name'];

    }

    //ad_poll
    function get_poll_list()
    {
        global $mklib, $DB;

        $prefix = DBPREFIX;
        $poll_active = $mklib->config['poll_active'];
        $DB->query("SELECT ID_POLL, question FROM {$prefix}polls ORDER BY ID_POLL DESC LIMIT 30");

        while( $poll = $DB->fetch_row() ) {
            $id = $poll['ID_POLL'];
            $title = $poll['question'];
            $selected = "";
            if($id == $poll_active) {
                $selected = "selected=\"selected\"";
            }
            $cselect.= "<option value=\"$id\" $selected>$title</option>\n";
        }

        return $cselect;
    }

//blocks functions

    function forum_link($link)
    {
        global $mklib, $mkportals;
        switch($link) {
            case 'profile':
                $out = "/{$mkportals->forum_url}/index.php?action=profile;u";
            break;
            case 'cpaforum':
                $out = "/{$mkportals->forum_url}/index.php?action=admin";
            break;
            case 'cpapers':
                $out = "/{$mkportals->forum_url}/index.php?action=profile";
            break;
            case 'pm':
                $out = "/{$mkportals->forum_url}/index.php?action=pm";
            break;
            case 'forumsearch':
                $out = "/{$mkportals->forum_url}/index.php?action=search";
            break;
            case 'logout':
                $out = "/{$mkportals->forum_url}/index.php?action=logout;sesc={$mkportals->member['session_id']}";
            break;
            case 'postlink':
                $out = "/{$mkportals->forum_url}/index.php?action=login2";
            break;
            case 'register':
                $out = "/{$mkportals->forum_url}/index.php?action=register";
            break;
            case 'onlinelist':
                $out = "/{$mkportals->forum_url}/index.php?action=who";
            break;
            case 'login_extra':
                $out = "<tr>
                  <td width=\"100%\" colspan=\"2\" class=\"tdblock mkalign1\"><b>{$mklib->lang['auto_login']}</b>
                  <input type=\"checkbox\" name=\"cookieneverexp\" id=\"mkcookieneverexp\" checked=\"checked\" /></td>
                </tr>";
                $_SESSION['login_url'] = $mklib->siteurl;
                $_SESSION['logout_url'] = $mklib->siteurl;
            break;
            case 'login_user':
                $out = "user";
            break;
            case 'login_passw':
                $out = "passwrd";
            break;
            case 'calendar_event':
                $out = "/$mkportals->forum_url/index.php?action=calendar";
            break;
            default:
                $out = "";
            break;
            }

        return $out;

    }

    function get_avatar()
     {
        global $mkportals, $DB;

        $prefix = DBPREFIX;;

            if (substr($mkportals->member['avatar']['url'], 0, 7) == 'http://')
            {
                $dimension = url_image_size($mkportals->member['avatar']['url']);
                if ($dimension[0] > 80) {
                    $dimension[1] = ceil(80 * $dimension[1] / $dimension[0]);
                    $dimension[0] = 80;
                }
                $avatar_img = "<img src=\"{$mkportals->member['avatar']['url']}\" width=\"$dimension[0]\" height=\"$dimension[1]\" alt=\"\" border=\"\" />";

            } else {

                $avatar_img = "<img src=\"{$mkportals->forum_url}/avatars/{$mkportals->member['avatar']['url']}\" alt=\"\" border=\"\" />";
            }

            if (!$mkportals->member['avatar']['url'])
            {
                $avatar_img = "";
            }
            if ($mkportals->member['avatar']['ID_ATTACH']) {
                $idattach = $mkportals->member['avatar']['ID_ATTACH'];
                $query = $DB->query( "SELECT filename FROM {$prefix}attachments WHERE ID_ATTACH = '$idattach'");
                $row = $DB->fetch_row($query);
		$avatar_img = "<img src=\"{$mkportals->forum_url}/index.php?action=dlattach;attach=$idattach;type=avatar\" alt=\"\" border=\"\" />";                
            }
            return $avatar_img;
            exit;
    }

    function get_forumnav()
     {
        global $mklib, $mkportals, $Skin;

		$out = "<tr><td class=\"tdblock\">";
		$out .= $Skin->row_link_block(!$mklib->config['noicons'] ? "<img class=\"mkicon\" src=\"$mklib->images/atb_npost.gif\" style=\"vertical-align: middle\" align=\"left\" alt=\"{$mklib->lang['m_newpost']}\" />" : "", "href=\"{$mkportals->forum_url}/index.php?action=unread\"", $mklib->lang['m_newpost']);
		$out .= "</td></tr><tr><td class=\"tdblock\">";

		$out .= $Skin->row_link_block(!$mklib->config['noicons'] ? "<img class=\"mkicon\" src=\"$mklib->images/atb_npost.gif\" style=\"vertical-align: middle\" align=\"left\" alt=\"{$mklib->lang['new_replies']}\" />" : "", "href=\"{$mkportals->forum_url}/index.php?action=unreadreplies\"", $mklib->lang['new_replies']);
		$out .= "</td></tr><tr><td class=\"tdblock\">";	

		$out .= $Skin->row_link_block(!$mklib->config['noicons'] ? "<img class=\"mkicon\" src=\"$mklib->images/atb_members.gif\" style=\"vertical-align: middle\" align=\"left\" alt=\"{$mklib->lang['m_users']}\" />" : "", "href=\"{$mkportals->forum_url}/index.php?action=mlist\"", $mklib->lang['m_users']);
		$out .= "</td></tr><tr><td class=\"tdblock\">";	

		$out .= $Skin->row_link_block(!$mklib->config['noicons'] ? "<img class=\"mkicon\" src=\"$mklib->images/atb_help.gif\" style=\"vertical-align: middle\" align=\"left\" alt=\"{$mklib->lang['m_help']}\" />" : "", "href=\"{$mkportals->forum_url}/index.php?action=help\"", $mklib->lang['m_help']);
		$out .= "</td></tr>";

		return $out;

    }
    function get_site_stat()
     {
        global $DB;
        $prefix = DBPREFIX;

        $sql = "SELECT COUNT(id_member) AS total
                FROM {$prefix}members";

        $DB->query($sql);
        $row = $DB->fetch_row();

        $stat['members'] = $row['total'];

        $sql = "SELECT id_member, real_name
                FROM {$prefix}members
                ORDER BY id_member DESC
                LIMIT 1";
        $DB->query($sql);
        $row = $DB->fetch_row();

        $stat['last_member'] = $row['id_member'];
        $stat['last_member_name'] = $row['real_name'];

        $sql = "SELECT COUNT(ID_TOPIC) AS total
                FROM {$prefix}topics";
        $DB->query($sql);
        $row = $DB->fetch_row();
        $stat['topics'] = $row['total'];

        $sql = "SELECT COUNT(id_msg) AS total
                FROM {$prefix}messages";
        $DB->query($sql);
        $row = $DB->fetch_row();
        $stat['total_posts'] = $row['total'];


        $stat['replies'] = $stat['total_posts'] - $stat['topics'];
        return $stat;


    }

    function get_onlineblock()
     {
        global $DB, $mkportals;



    $prefix = DBPREFIX;
    $context['users_online'] = array();
    $context['list_users_online'] = array();
    $context['online_groups'] = array();
    $context['num_guests'] = 0;
    $context['num_users_hidden'] = 0;
    $logged_visible_online = 0;

        $sql = "SELECT lo.id_member, lo.log_time, mem.real_name, mem.member_name, mem.show_online, mg.online_color, mg.id_group, mg.group_name
        FROM {$prefix}log_online AS lo
            LEFT JOIN {$prefix}members AS mem ON (mem.id_member = lo.id_member)
            LEFT JOIN {$prefix}membergroups AS mg ON (mg.id_group = IF(mem.id_group = 0, mem.ID_POST_GROUP, mem.id_group))";

    $DB->query($sql);

    while ($row = $DB->fetch_row() )
    {
        if (!isset($row['real_name']))
        {
            $context['num_guests']++;
            continue;
        }
        elseif (!empty($row['show_online']) || allowedTo('moderate_forum'))
        {
            if (!empty($row['show_online'])) {
                $logged_visible_online++;
            } else {
                $context['num_users_hidden']++;
            }
            if (!empty($row['online_color']))
                $link = '<a href="/' . $mkportals->forum_url . '/index.php?action=profile;u=' . $row['id_member'] . '" style="color: ' . $row['online_color'] . ';">' . $row['real_name'] . '</a>';
            else
                $link = '<a href="/' . $mkportals->forum_url . '/index.php?action=profile;u=' . $row['id_member'] . '">' . $row['real_name'] . '</a>';

            $context['users_online'][$row['log_time'] . $row['member_name']] = array(
                'id' => $row['id_member'],
                'username' => $row['member_name'],
                'name' => $row['real_name'],
                'group' => $row['id_group'],
                'href' => $scripturl . '?action=profile;u=' . $row['id_member'],
                'link' => $link,
                'hidden' => empty($row['show_online']),
            );

            $context['list_users_online'][$row['log_time'] . $row['member_name']] = empty($row['show_online']) ? '<i>' . $link . '</i>' : $link;


        }
        else
            $context['num_users_hidden']++;
    }

    krsort($context['users_online']);
    krsort($context['list_users_online']);
    ksort($context['online_groups']);

    $listusers = implode(', ', $context['list_users_online']);

        return array($logged_visible_online, $context['num_users_hidden'], $context['num_guests'], $listusers);


    }


    function get_onlinehome($languest)
     {

        global $DB, $mkportals;

    $content = "";
    $inter = ",";
    $total_online_users = 0;
    $logged_visible_online = 0;
    $logged_hidden_online = 0;
    $guests_online = 0;

    $prefix = DBPREFIX;
    $context['users_online'] = array();
    $context['list_users_online'] = array();

    $context['num_users_hidden'] = 0;

        $sql = "SELECT lo.id_member, lo.log_time, lo.url, mem.real_name, mem.member_name, mem.show_online, mg.online_color, mg.id_group, mg.group_name
        FROM {$prefix}log_online AS lo
            LEFT JOIN {$prefix}members AS mem ON (mem.id_member = lo.id_member)
            LEFT JOIN {$prefix}membergroups AS mg ON (mg.id_group = IF(mem.id_group = 0, mem.ID_POST_GROUP, mem.id_group))";

    $DB->query($sql);
    while ($row = $DB->fetch_row() )
    {
        if (!isset($row['real_name']))
        {
            $guests_online++;
            $user_online_link = "$languest, \n";
                switch($row['url']) {
                            case '-20':
                                $online['portale'] .= "$user_online_link \n";
                            break;
                            case '-21':
                                $online['blog'] .= "$user_online_link \n";
                            break;
                            case '-22':
                                $online['gallery'] .= "$user_online_link \n";
                            break;
                            case '-23':
                                $online['urlobox'] .= "$user_online_link \n";
                            break;
                            case '-24':
                                $online['downloads'] .= "$user_online_link \n";
                            break;
                            case '-25':
                                $online['news'] .= "$user_online_link \n";
                            break;
                            case '-26':
                                $online['topsite'] .= "$user_online_link \n";
                            break;
                            case '-27':
                                $online['chat'] .= "$user_online_link \n";
                            break;
                            case '-28':
                                $online['reviews'] .= "$user_online_link \n";
                            break;
                            default:
                            $online['forum'] .= "$user_online_link \n";
                            break;
                        }
            continue;
        }
        elseif (!empty($row['show_online']) || allowedTo('moderate_forum'))
        {

            if (!empty($row['show_online'])) {
                $logged_visible_online++;
            } else {
                $logged_hidden_online++;
            }
            if (!empty($row['online_color']))
                $link = '<b><a href="/' . $mkportals->forum_url . '/index.php?action=profile;u=' . $row['id_member'] . '" style="color: ' . $row['online_color'] . ';">' . $row['real_name'] . '</a></b>';
            else
                $link = '<a href="/' .$mkportals->forum_url . '/index.php?action=profile;u=' . $row['id_member'] . '">' . $row['real_name'] . '</a>';

            $context['users_online'][$row['log_time'] . $row['member_name']] = array(
                'id' => $row['id_member'],
                'username' => $row['member_name'],
                'name' => $row['real_name'],
                'group' => $row['id_group'],
                'href' => $scripturl . '?action=profile;u=' . $row['id_member'],
                'link' => $link,
                'hidden' => empty($row['show_online']),
            );

            $user_online_link = empty($row['show_online']) ? '<i>' . $link . '</i>' : $link;
            switch($row['url']) {
                            case '-20':
                                $online['portale'] .= "$user_online_link".", \n";
                            break;
                            case '-21':
                                $online['blog'] .= "$user_online_link".", \n";
                            break;
                            case '-22':
                                $online['gallery'] .= "$user_online_link".", \n";
                            break;
                            case '-23':
                                $online['urlobox'] .= "$user_online_link".", \n";
                            break;
                            case '-24':
                                $online['downloads'] .= "$user_online_link".", \n";
                            break;
                            case '-25':
                                $online['news'] .= "$user_online_link".", \n";
                            break;
                            case '-26':
                                $online['topsite'] .= "$user_online_link".", \n";
                            break;
                            case '-27':
                                $online['chat'] .= "$user_online_link".", \n";
                            break;
                            case '-28':
                                $online['reviews'] .= "$user_online_link".", \n";
                            break;
                            default:
                            $online['forum'] .= "$user_online_link".", \n";
                            break;
                        }

        }
        else
            $logged_hidden_online++;
    }

        $total_online_users = $logged_visible_online + $logged_hidden_online + $guests_online;

        $online['portale'] = preg_replace( "/".preg_quote($inter)."$/", "", trim($online['portale']) );
        $online['blog'] = preg_replace( "/".preg_quote($inter)."$/", "", trim($online['blog']) );
        $online['gallery'] = preg_replace( "/".preg_quote($inter)."$/", "", trim($online['gallery']) );
        $online['urlobox'] = preg_replace( "/".preg_quote($inter)."$/", "", trim($online['urlobox']) );
        $online['downloads'] = preg_replace( "/".preg_quote($inter)."$/", "", trim($online['downloads']) );
        $online['news'] = preg_replace( "/".preg_quote($inter)."$/", "", trim($online['news']) );
        $online['chat'] = preg_replace( "/".preg_quote($inter)."$/", "", trim($online['chat']) );
        $online['topsite'] = preg_replace( "/".preg_quote($inter)."$/", "", trim($online['topsite']) );
        $online['reviews'] = preg_replace( "/".preg_quote($inter)."$/", "", trim($online['reviews']) );
        $online['forum'] = preg_replace( "/".preg_quote($inter)."$/", "", trim($online['forum']) );

        return array($logged_visible_online, $logged_hidden_online, $guests_online, $online['portale'], $online['blog'], $online['gallery'], $online['urlobox'], $online['downloads'], $online['news'], $online['chat'], $online['topsite'], $online['reviews'], $online['forum']);

    }

    function get_last_posts($by, $sdate)
     {
        global $DB, $mklib, $mkportals, $db_prefix, $user_info, $modSettings;
        $limit = 5;
        $taglio = 17;
        $db_prefix = DBPREFIX;

        $sql = "
	SELECT
	m.poster_time, m.subject, m.ID_TOPIC, m.id_member, m.id_msg,
	IFNULL(mem.real_name, m.poster_name) AS poster_name, t.id_board, b.name AS bName,
	m.body, m.smileys_enabled
	FROM ({$db_prefix}messages AS m, {$db_prefix}topics AS t, {$db_prefix}boards AS b)
	LEFT JOIN {$db_prefix}members AS mem ON (mem.id_member = m.id_member)
	WHERE t.ID_TOPIC = m.ID_TOPIC
	AND m.id_msg >= " . max(0, $modSettings['maxMsgID'] - 20 * $limit) . "
	AND b.id_board = t.id_board" . (!empty($modSettings['recycle_enable']) && $modSettings['recycle_board'] > 0 ? "
	AND b.id_board != $modSettings[recycle_board]" : '') . "
	AND $user_info[query_see_board]
	ORDER BY m.id_msg DESC
	LIMIT $limit";

        $DB->query($sql);

        while ( $post = $DB->fetch_row() ) {
          $title = strip_tags($post['subject']);
        $title = str_replace( "&#33;" , "!" ,$title );
        $title = str_replace( "&quot;", "\"", $title );
            if (strlen($title) > $taglio) {
                $title = substr( $title,0,($taglio - 3) ) . "...";
                $title = preg_replace( '/&(#(\d+;?)?)?(\.\.\.)?$/', '...',$title );
            }

         $date  = $mklib->create_date($post['poster_time']);
        $tid = $post['ID_TOPIC'];

        $mid = $post['id_member'];
        $mname = $post['poster_name'];

        $content .= "
                <tr>
                  <td width=\"100%\" class=\"tdblock\">
                  <a class=\"uno\" href=\"$mkportals->forum_url/index.php?topic=$tid\">$title</a>
                  </td>
                </tr>
                <tr>
                  <td class=\"tdglobal\">
                  <a class=\"uno\" href=\"$mkportals->forum_url/index.php?action=profile;u=$mid\">$by: $mname</a><br /> $sdate: $date
                  </td>
                </tr>
        ";
         }

        return $content;


    }

    function get_poll_active($post_id)
     {
        global $DB, $mklib, $mkportals, $sc;

        $prefix = DBPREFIX;


        $sql = "SELECT id_member
        FROM {$prefix}log_polls
        WHERE ID_POLL = $post_id
        AND id_member = {$mkportals->member['id']}
        LIMIT 1
        ";
        $DB->query($sql);
        $allow_vote = $DB->get_num_rows($request) == 0;


        $sql = "SELECT p.ID_POLL, p.question, m.ID_TOPIC, m.ID_POLL
        FROM {$prefix}polls AS p
        LEFT JOIN {$prefix}topics AS m ON (m.ID_POLL = p.ID_POLL)
        WHERE p.ID_POLL = $post_id";

        $DB->query($sql);
        $result = $DB->fetch_row();
        if ( ! $result['ID_POLL'] ) {
            return "";
        }
        $question = $result['question'];
        $poll_id = $result['ID_POLL'];
        $topic_id = $result['ID_TOPIC'];

        $out = "
                <tr>
                  <td class=\"tdblock\">
                  <a href=\"$mkportals->forum_url/index.php?topic=$topic_id\">$question</a>
                  </td>
                </tr>
            ";
/*
	$DB->query("
			SELECT pc.ID_CHOICE, pc.label, pc.votes, IFNULL(lp.ID_CHOICE, -1) AS votedThis
			FROM {$prefix}poll_choices AS pc
				LEFT JOIN {$prefix}log_polls AS lp ON (lp.ID_CHOICE = pc.ID_CHOICE AND lp.ID_POLL = $poll_id AND lp.id_member = {$mkportals->member['id']})
				WHERE pc.ID_POLL = $poll_id");
*/


        $DB->query("SELECT ID_CHOICE, label, votes FROM {$prefix}poll_choices WHERE ID_POLL = $poll_id");
        $ind = 0;
        $total_votes = 0;
        while ($poll = $DB->fetch_row() ) {
            $idch = $poll['ID_CHOICE'];
            $choise[$ind]['text'] = $poll['label'];
            $choise[$ind]['vote'] = $poll['votes'];
            $choise[$ind]['id'] = $poll['ID_CHOICE'];
            $total_votes += $poll['votes'];
            ++$ind;
        }
        if ($allow_vote && $mkportals->member['id']) {
            $out .= "
                <tr>
                  <td class=\"tdblock\">
                    <form action=\"$mkportals->forum_url/index.php?action=vote;topic=$topic_id;poll=$poll_id\" method=\"post\" style=\"margin: 0px;\">
            ";
        }
        foreach ($choise as $entry) {
            $percent = $entry['vote'] == 0 ? 0 : $entry['vote'] / $total_votes * 100;
            $percent = sprintf( '%.2f' , $percent );
            $width   = $percent > 0 ? floor( round( $percent ) * ( 122 / 100 ) ) : 0;
            if ($allow_vote && $mkportals->member['id']) {
                $out .= "
                    
                    <div style=\"margin-top: 5px;\"><input type=\"radio\" name=\"options[]\" value=\"{$entry['id']}\" class=\"bgselect\" />&nbsp;<strong>{$entry['text']}</strong></div>
                    
                ";
            } else {
                $out .= "
                <tr>
                  <td class=\"tdblock\">
                  {$entry['text']}
                  </td>
                </tr>
                <tr>
                  <td align=\"left\">
                  <img src=\"$mklib->images/bar-start.gif\" border=\"0\" width=\"4\" height=\"11\" alt=\"\" /><img src=\"$mklib->images/bar.gif\" border=\"0\" width=\"$width\" height=\"11\" alt=\"\" /><img src=\"$mklib->images/bar-end.gif\" border=\"0\" width=\"4\" height=\"11\" alt=\"\" />
                  </td>
                </tr>
                ";
            }
        }

        if ($allow_vote && $mkportals->member['id']) {

            $out .= "
                
                      <input type=\"hidden\" name=\"sc\" value=\"$sc\" />
                      <input type=\"submit\" value=\"{$mklib->lang['poll_vote']}\" class=\"mkbutton\" style=\"margin-top: 10px;\" />
                    </form>
                  </td>
                </tr>
                <tr>
                  <td class=\"tdblock\">
                  <span class=\"mktxtcontr\">$total_votes</span> {$mklib->lang['poll_totalvotes']}
                  </td>
                </tr>
                <tr>
                  <td class=\"tdglobal\">
                    <a href=\"$mkportals->forum_url/index.php?topic=$topic_id\">{$mklib->lang['poll_go']}</a>
                  </td>
                </tr>
            ";
        } else {

            $gvot = $mklib->lang['poll_go'];
            if ($mkportals->member['id']) {
                $gvot = $mklib->lang['poll_voted'];
            }
            $out .= "
                <tr>
                  <td class=\"tdblock\">
                  <span class=\"mktxtcontr\">$total_votes</span> {$mklib->lang['poll_totalvotes']}
                  </td>
                </tr>
                <tr>
                  <td class=\"tdglobal\">
                  <a href=\"$mkportals->forum_url/index.php?topic=$topic_id\">$gvot</a>
                  </td>
                </tr>
            ";
        }


  return $out;
    }


    function get_forum_list()
    {
        global $mklib, $DB;

        $prefix = DBPREFIX;
        $forum_active = $mklib->config['forum_active'];

        $DB->query("SELECT id_board AS id, name FROM {$prefix}boards ORDER BY id_board");

        while( $board = $DB->fetch_row() ) {
			$cselect[] = $board;
	}

        return $cselect;
    }
    function get_board_news()
     {
        global $DB, $mklib, $mkportals, $db_prefix, $user_info, $modSettings;

        $user_info['smiley_set'] = $mkportals->member['smiley_set'] = (!in_array($user_info['smiley_set'], explode(',', $modSettings['smiley_sets_known'])) && $user_info['smiley_set'] != 'none') || empty($modSettings['smiley_sets_enable']) ? (!empty($settings['smiley_sets_default']) ? $settings['smiley_sets_default'] : $modSettings['smiley_sets_default']) : $user_info['smiley_set'];

        if (!$user_info['smiley_set']) {
            $user_info['smiley_set'] = "default";
        }
        $limit = $mklib->config['bnews_block'];
	$news_words= $mklib->config['bnews_words'];
        //$taglio = 17;
        $db_prefix = DBPREFIX;
        $forum_active = unserialize($mklib->config['forum_active']);
        if(!$forum_active) {
                return "";
        }
        $sql = "SELECT
            m.poster_time, m.subject, m.id_topic, m.id_member, m.id_msg, m.id_board,
            IFNULL(mem.real_name, m.poster_name) AS poster_name, t.id_board, t.num_replies, b.name AS bName,
            m.body, m.smileys_enabled, m.icon, mem.avatar, av.filename, av.ID_ATTACH
		FROM ({$db_prefix}topics AS t, {$db_prefix}messages AS m, {$db_prefix}boards AS b)
            LEFT JOIN {$db_prefix}members AS mem ON (mem.id_member = m.id_member)
	    LEFT JOIN {$db_prefix}attachments AS av ON (mem.id_member = av.id_member)
            WHERE m.id_msg = t.ID_FIRST_MSG
	    AND m.id_msg >= " . max(0, $modSettings['maxMsgID'] - 200 * $limit) . "
            AND b.id_board IN (".implode(',', $forum_active ).")
            AND b.id_board = t.id_board
        ORDER BY m.id_msg DESC
        LIMIT $limit";

        $DB->query($sql);

        while ( $post = $DB->fetch_row() ) {
	$avatar_img = "";
          $title = strip_tags($post['subject']);
        $title = str_replace( "&#33;" , "!" ,$title );
        $title = str_replace( "&quot;", "\"", $title );

         $date  = $mklib->create_date($post['poster_time']);
        $tid = $post['id_topic'];

        $mid = $post['id_member'];
        $mname = $post['poster_name'];
        $testo = $post['body'];
	if ($news_words) {
		$testo = substr ($testo, 0, $news_words);
		$testo .= " ...";
   	}
        $testo = parse_bbc($testo);
	parsesmileys($testo);
        $fname = $post['bName'];
	$num_replies = $post['num_replies']." ".$mklib->lang['replies'];
        $icona = $mkportals->forum_url."/Themes/default/images/post/".$post['icon'].".gif";
        $avatar_url = $post['filename'];
	//$idattach = $mkportals->member['avatar']['ID_ATTACH']; //this is the avatar of the viewer, not the poster
	$idattach = $post['ID_ATTACH'];

	if ($avatar_url) {
		$avatar_img = "<img src=\"{$mkportals->forum_url}/index.php?action=dlattach;attach=$idattach;type=avatar\" alt=\"\" border=\"\" />";
	} else  {
		$avatar_url = $post['avatar'];
		if (substr($avatar_url, 0, 7) == 'http://')
            {
                $dimension = url_image_size($avatar_url);
                if ($dimension[0] > 80) {
                    $dimension[1] = ceil(80 * $dimension[1] / $dimension[0]);
                    $dimension[0] = 80;
                }
                $avatar_img = "<img src=\"$avatar_url\" width=\"$dimension[0]\" height=\"$dimension[1]\" alt=\"\" border=\"\" />";

            } else {

                $avatar_img = "<img src=\"{$mkportals->forum_url}/avatars/$avatar_url\" alt=\"\" border=\"\" />";
            }
	}
	if (!$avatar_url) {
		$avatar_img = "<img hspace=\"0\" src=\"$icona\" align=\"bottom\" border=\"0\" alt=\"\" />";
	}
	$out .= "
                    <table class=\"tabnews\" cellspacing=\"2\" cellpadding=\"2\" width=\"100%\">
                      <tbody>
                      <tr>
                    <td class=\"tdblock\" align=\"center\" width=\"5%\">
                    $avatar_img
                    </td>
                    <td class=\"tdblock\" valign=\"middle\" align=\"center\" width=\"95%\">
                    <b>$fname<br /><a href=\"$mkportals->forum_url/index.php?topic=$tid\">$title</a></b>
                    <br /><div class=\"mkalign2\" style='font-style: italic; font-weight: normal;'><a href=\"$mkportals->forum_url/index.php?topic=$tid\">$num_replies</a>&nbsp;</div>
		    </td>
                      </tr>
                      <tr>
                    <td colspan=\"2\"><br />
                    $testo
                    </td>
                      </tr>
                      <tr>
                    <td class=\"mkalign2\" colspan=\"2\">
                    <br /><i>{$mklib->lang['from']}<b> <a href=\"$mkportals->forum_url/index.php?action=profile;u=$mid\">$mname</a></b>, $date <a href=\"$mkportals->forum_url/index.php?topic=$tid\"> [ {$mklib->lang['readall']} ]</a></i>
                    </td>
                      </tr>
                      </tbody>
                    </table>
        ";
         }
        return $out;
    }
     function langselect() {
	
	global $DB, $mklib, $mkportals, $user_info;
	$content = "<form name=\"mklanglist\" action=\"post\">\n <select name=\"seleclang\" class=\"bgselect\" onchange=\"document.location.href=mklanglist.seleclang.options[this.selectedIndex].value\">\n";

	$dir = dir("$mkportals->forum_url/Themes/default/languages");
		while ($entry = $dir->read()) {
			$selected = "";
			if (substr($entry, 0, 6) == 'index.' && substr($entry, -4) == '.php' && strlen($entry) > 10) {
				$name = substr($entry, 6, -4);
				if ($user_info['language'] == $name) {
					$selected = "selected=\"selected\"";
				}
				$content .= "\n<option value=\"$mklib->siteurl/index.php?langid={$name}\" $selected >$name</option>";		
			}
		}
	$dir->close();
			
	$content .= "\n</select>\n</form>";
    	$output = "
				<tr>
				  <td class=\"tdblock\" align=\"center\" valign=\"middle\">$content</td>
				</tr>
                ";
	return $output;
    }
    
    function update_lang($lang) {
        global $mkportals, $DB, $mklib;
	     $_SESSION['language'] = $lang;
	     Header("Location: $mkportals->forum_url/index.php");
            exit;
    }
    
    function skinselect()
     {
        global $DB, $mklib, $mkportals, $sc;

        if ( $mkportals->member['mgroup'] == 99) {
            return "";
        }
        $templateslist .= "<form name=\"skinlist\" action=\"post\">\n <select name=\"selectskin\" class=\"bgselect\" onchange=\"document.location.href=skinlist.selectskin.options[this.selectedIndex].value\">\n";
        $DB->query("SELECT * FROM ".DBPREFIX.themes."");
        while ( $r = $DB->fetch_row() )
        {
            $selected = "";
            if ($r['variable'] == "name") {
                if ($mkportals->member['theme'] == $r['id_theme']) {
                    $selected = "selected=\"selected\"";
                }
                if (strlen($r['value']) > 12 ) {
                    $r['value'] = substr($r['value'], 0, 12);
                }
            $templateslist .= "\n<option value=\"$mklib->siteurl/index.php?skinid={$r['id_theme']}\" $selected >{$r['value']}</option>";

            }
        }

        $templateslist .= "\n</select>\n</form>";
        $templateslist = "
                <tr>
                  <td class=\"tdblock\" align=\"center\" valign=\"middle\">$templateslist</td>
                </tr>
                ";
        return $templateslist;
    }

    function update_skin($skinid)
     {
        global $mkportals, $DB, $mklib;

        $DB->query("SELECT id_theme FROM ".DBPREFIX.themes." WHERE id_theme = '$skinid'");
        if ($DB->fetch_row()){
            $DB->query("UPDATE  ".DBPREFIX.members." SET id_theme ='$skinid' WHERE id_member = '{$mkportals->member['id']}'");
            $DB->close_db();
             Header("Location: $mkportals->forum_url/index.php");
            exit;
        }
    }

    function calendar_birth($chosen_month, $chosen_year)
     {
        global $mkportals, $DB, $mklib, $modSettings;

        $birthdays = array();

        $DB->query("SELECT DAYOFMONTH(birthdate) AS bday_day, YEAR(birthdate) AS bday_year, real_name FROM ".DBPREFIX.members." WHERE MONTH(birthdate)='".$chosen_month."' AND YEAR(birthdate) != '0001'");
        while ($user = $DB->fetch_row()) {
                $birthdays[ $user['bday_day'] ]++;
            if ($birthdays[ $user['bday_day'] ] < 10) {
                $tool_birthdays[$user['bday_day']] .=  $user['real_name']." (".($chosen_year - $user['bday_year']).")&nbsp;";
            }
            else if ($birthdays[ $user['bday_day'] ] == 10) {
            $tool_birthdays[$user['bday_day']] .=  "...";
            }
        }

        return array($birthdays, $tool_birthdays);
    }

    function calendar_events($chosen_month, $chosen_year)
 {
 	global $mkportals, $DB, $mklib;
 	$events = array();
 	$startt  = mktime( 0, 0, 0, $chosen_month, 1, $chosen_year);
 	$endt  = mktime( 0, 0, 0, $chosen_month+1, 0, $chosen_year);
   	$startt = strftime('%Y-%m-%d', $startt);
 	$endt = strftime('%Y-%m-%d', $endt);
 	$DB->query("SELECT id_topic, title, start_date AS mday FROM ".DBPREFIX.calendar." WHERE start_date >='".$startt."' AND start_date <= '".$endt."'");
 	while ( $event = $DB->fetch_row() ) {
		$event['mday'] = ltrim(substr($event['mday'], 8), "0");
     		$events[ $event['mday'] ][] = $event;
     		$entry = substr($event['title'], 0, 20);
    		if ( strlen($event['title']) > 20 ) {
        		$entry .= "...";
     		}
     		$tool_events[$event['mday']] .= $entry."<br />";
   	}
 
 	return array($events, $tool_events, $tool_idevents);
}

   
    function import_css()
    {
        global $mkportals, $DB, $mklib;
        $DB->query("SELECT * FROM ".DBPREFIX."themes WHERE id_theme = '{$mkportals->member['theme']}'");
        while ( $r = $DB->fetch_row() )
        {
            if ($r['variable'] == "images_url") {
                $images_url = $r['value'];
            }
            if ($r['variable'] == "theme_dir") {
                $theme_dir = $r['value'];
            }
        }
        unset ($r);
        $css2 = $theme_dir."/style.css";
        $fh = @fopen($css2, "r");
        if ($fh) {
            $css2 = fread($fh, filesize($css2));
            @fclose($fh);
        }
        $css = "$mklib->template/style.css";
        $fh = @fopen($css, "r");
        if ($fh) {
            $css = fread($fh, filesize($css));
            @fclose($fh);
        }        


        //importing body bg
        $pos = strpos($css2, "body\n");
        $pos2 = strpos($css2, "}", $pos);
            if ($pos) {
                $mkpsubs = substr($css2, $pos, ($pos2 - ($pos -1)));
                $css = preg_replace( "`(\.importbgbody(.*?\}))`is", $mkpsubs, $css);
            }

        //importing body fonts
        $pos = strpos($css2, "body, td");
        $pos2 = strpos($css2, "}", $pos);
            if ($pos) {
                $mkpsubs = substr($css2, $pos, ($pos2 - ($pos -1)));
                $css = preg_replace( "`(\.importbody(.*?\}))`is", $mkpsubs, $css);
            }

        //importing main table bg (if different than body bg)
        $pos = strpos($css2, "#bodyarea");
        $pos2 = strpos($css2, "}", $pos);
            if ($pos) {
                $mkpsubs = substr($css2, $pos, ($pos2 - ($pos -1)));
                $css = preg_replace( "`(\.importmain(.*?\}))`is", $mkpsubs, $css);
            }

        //importing logostrip
        $sflogo =  $theme_dir."/images/sf_logo.jpg";
        if (is_file("$sflogo") ) {
            $mkpsubs = "#logostrip {background-image: url($images_url/sf_logo.jpg); text-align: left;}";
        } else {
            $pos = strpos($css2, "#headerarea");
            $pos2 = strpos($css2, "}", $pos);
                if ($pos) {
                    $mkpsubs = substr($css2, $pos, ($pos2 - ($pos -1)));
                }
        }
        $css = preg_replace( "`(\#importlogostrip(.*?\}))`is", $mkpsubs, $css);

        
        //importing light background
        $pos = strpos($css2, ".windowbg2");
        $pos2 = strpos($css2, "}", $pos);
            if ($pos) {
                $mkpsubs = substr($css2, $pos, ($pos2 - ($pos -1)));
                $css = preg_replace( "`(\.importlightback(.*?\}))`is", $mkpsubs, $css);
            }
        

        //importing medium background
        $pos = strpos($css2, ".windowbg");
        $pos2 = strpos($css2, "}", $pos);
            if ($pos) {
                $mkpsubs = substr($css2, $pos, ($pos2 - ($pos -1)));
                $css = preg_replace( "`(\.importmediumback(.*?\}))`is", $mkpsubs, $css);
            }

        //importing dark background
        $pos = strpos($css2, ".titlebg");
        $pos2 = strpos($css2, "}", $pos);
            if ($pos) {
                $mkpsubs = substr($css2, $pos, ($pos2 - ($pos -1)));
                $css = preg_replace( "`(\.importdarkback(.*?\}))`is", $mkpsubs, $css);
            }

        //importing module table headers
        $pos = strpos($css2, ".catbg");
        $pos2 = strpos($css2, "}", $pos);
            if ($pos) {
                $mkpsubs = substr($css2, $pos, ($pos2 - ($pos -1)));
                $css = preg_replace( "`(\.importmodulex(.*?\}))`is", $mkpsubs, $css);
            }
        
        //importing borders
        $pos = strpos($css2, ".tborder");
        $pos2 = strpos($css2, "}", $pos);
            if ($pos) {
                $mkpsubs = substr($css2, $pos, ($pos2 - ($pos -1)));        
                $mkpsubs = preg_replace( "/back(.*?\;)/mi", "", $mkpsubs);
                $mkpsubs = str_replace("color:", "border-color:", $mkpsubs);
                $css = preg_replace( "`(\.importborders(.*?\}))`is", $mkpsubs, $css);
            }

        //importing form styles
        $pos = strpos($css2, "input");
        $pos2 = strpos($css2, "}", $pos);
            if ($pos) {
                $mkpsubs = substr($css2, $pos, ($pos2 - ($pos -1)));
                $css = preg_replace( "`(\.importforms(.*?\}))`is", $mkpsubs, $css);
            }

        //importing table font formatting
        $pos = strpos($css2, "body, td");
        $pos2 = strpos($css2, "}", $pos);
            if ($pos) {
                $mkpsubs = substr($css2, $pos, ($pos2 - ($pos -1)));
                $css = preg_replace( "`(\.importfont(.*?\}))`is", $mkpsubs, $css);
            }

        //importing hyperlink a:link style
        $pos = strpos($css2, "a:link");
        $pos2 = strpos($css2, "}", $pos);
            if ($pos) {
                $mkpsubs = substr($css2, $pos, ($pos2 - ($pos -1)));
                $css = preg_replace( "`(\.importlink(.*?\}))`is", $mkpsubs, $css);
            }

        //importing hyperlink a:visited style
        $pos = strpos($css2, "a:visited");
        $pos2 = strpos($css2, "}", $pos);
            if ($pos) {
                $mkpsubs = substr($css2, $pos, ($pos2 - ($pos -1)));
                $css = preg_replace( "`(\.importvisited(.*?\}))`is", $mkpsubs, $css);
            }

        //importing hyperlink a:hover style
        $pos = strpos($css2, "a:hover");
        $pos2 = strpos($css2, "}", $pos);
            if ($pos) {
                $mkpsubs = substr($css2, $pos, ($pos2 - ($pos -1)));
                $css = preg_replace( "`(\.importhover(.*?\}))`is", $mkpsubs, $css);
            }

        //adjust images path
        $css = str_replace("url(images", "url($images_url", $css);
        $css = str_replace ("MKPORTALIMGDIR", "$mklib->images", $css);
	$css = "<style type=\"text/css\">\n$css\n</style>\n";
	//Load Portal CP stylesheet
		if (defined('IN_MKPADMIN')) {
			$css .= file_exists($mklib->template.'/stylecp.css') ? "<link href=\"$mklib->template/stylecp.css\" rel=\"stylesheet\" type=\"text/css\" />" : "";
		}
	unset($css2);
	
	//RSS block css
		if ($mklib->config['rss_css'] == 1) { //rss block css
			$css .= "\n<link rel=\"stylesheet\" href=\"{$this->sitepath}mkportal/modules/rss/files/simplepie.css\" type=\"text/css\" media=\"screen, projector\" />\n";
		}

        return $css;
    }
    function simple_mail($subject, $message, $iduser)
     {
        global $DB, $mklib;

        $headers = "From: webmaster@" . $mklib->sitename . "\r\n" . "Reply-To: webmaster@" . $mklib->sitename . "\r\n" . "X-Mailer: MKportal Mail";
        $dest = "";
        $DB->query("SELECT emailAddress FROM ".DBPREFIX."members WHERE id_member = '$iduser'");
        $row = $DB->fetch_row();
        $dest = $row['emailAddress'];

        mail($dest, $subject, $message,  $headers);
    }
    function admin_mail($subject, $message)
     {
        global $DB, $mklib;

        $headers = "From: webmaster@" . $mklib->sitename . "\r\n" . "Reply-To: webmaster@" . $mklib->sitename . "\r\n" . "X-Mailer: MKportal Mail";

        $dest = "";
        $DB->query("SELECT  emailAddress FROM ".DBPREFIX."members WHERE id_group = '1'");
        while ( $row = $DB->fetch_row() ) {
            $dest .= $row['emailAddress'].", ";
        }
        $dest=rtrim($dest, ", ");
        mail($dest, $subject, $message,  $headers);
    }


    function get_emo_row($code, $image) {
        global $mklib, $mkportals;
        
        if (strstr($code, "&quot;" ) ) {
            $in  = "'";
            $out = '"';
        }
        else {
            $in  = '"';
            $out = "'";
        }
        $code = stripslashes($code);
        $code = str_replace("'", "&#39;", $code);
        $code = str_replace($in, "\\".$in, $code);
        $code = $in.$code.$in;
        $image = stripslashes($image);
        if (!strpos($image, "ttp://")) {
            $image = $mklib->siteurl."/".$mklib->forumpath."/".$this->emo_path.$image;
        }
        $short = preg_replace("`^.*\/`", "", $image);
        return "
        <td width=\"50%\" align=\"center\" class=\"tdblock\" valign=\"middle\"><a href={$out}javascript:add_smilie({$code}){$out}><img src=\"$image\" border=\"0\" valign=\"middle\" alt=\"$short\" title=\"$short\" /></a></td>
        ";
    }

    function get_emo_header($css = "") {
    
    return <<< EOT
<head>
{$css}
</head>
<body>
<script type="text/javascript">
<!--
    var myAgent   = navigator.userAgent.toLowerCase();
    var myVersion = parseInt(navigator.appVersion);
    
    var is_ie   = ((myAgent.indexOf("msie") != -1)  && (myAgent.indexOf("opera") == -1));
    var is_nav  = ((myAgent.indexOf('mozilla')!=-1) && (myAgent.indexOf('spoofer')==-1)
                    && (myAgent.indexOf('compatible') == -1) && (myAgent.indexOf('opera')==-1)
                    && (myAgent.indexOf('webtv') ==-1)       && (myAgent.indexOf('hotjava')==-1));
    
    var is_win   =  ((myAgent.indexOf("win")!=-1) || (myAgent.indexOf("16bit")!=-1));
    var is_mac    = (myAgent.indexOf("mac")!=-1);
    var is_opera = (myAgent.indexOf("opera") != -1);
    if (is_opera) {
        var myVersion = parseFloat(myAgent.substr(myAgent.indexOf('opera') + 6, 4) );
    }
    
    function add_smilie(code) {
        code = " " + code + " ";
        var obj_ta = parent.document.editor.ta;
        if ( (myVersion >= 4) && is_ie && is_win) {
            if(obj_ta.isTextEdit){
                obj_ta.focus();
                var sel = document.selection;
                var rng = sel.createRange();
                rng.colapse;
                if((sel.type == "Text" || sel.type == "None") && rng != null){
                    rng.text = code;
                }
            }
            else {
                obj_ta.value += code;
            }
        }
        else {
            if ( (myVersion >= 4) && is_win && (!is_opera || (is_opera && myVersion >= 8))) {
                var length = obj_ta.textLength;
                var start = obj_ta.selectionStart;
                var end = obj_ta.selectionEnd;
                var head = obj_ta.value.substring(0,start);
                var rng = obj_ta.value.substring(start, end);
                var tail = obj_ta.value.substring(end, length);
                if( start != end ){
                    rng = code;
                    obj_ta.value = head + rng + tail;
                    start = start + rng.length;
                }
                else{
                    obj_ta.value = head + code + tail;
                    start = start + code.length;
                }
                obj_ta.selectionStart = start;
                obj_ta.selectionEnd = start;
            }
            else {
                obj_ta.value += code;
            }
        }
        obj_ta.focus();
    }
//-->
</script>

<table class="tablemenu" width="100%">
<tr>

EOT;

    }

}

$mklib_board = new mklib_board;

?>
