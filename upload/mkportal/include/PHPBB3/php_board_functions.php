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
		global $mkportals, $DB;
		$ipu = $mkportals->member['ip'];
		$sid = $mkportals->member['session_id'];
		$DB->query("UPDATE  ".SESSIONS_TABLE." SET session_page ='$loc'  WHERE session_ip = '$ipu' AND session_id = '$sid'");
	}


	function get_active_users($loc) {

		global $DB, $mkportals, $mklib, $config, $auth;

		$logged_visible_online = 0;
		$logged_hidden_online = 0;
		$guests_online = 0;

		$load_online_time = intval($config['load_online_time']) * 60;
		$sql = "SELECT u.username, u.username_clean, u.user_id, u.user_type, u.user_allow_viewonline, u.user_colour, s.session_ip, s.session_viewonline
			FROM ".USERS_TABLE." u, ".SESSIONS_TABLE." s
			WHERE u.user_id = s.session_user_id
			AND s.session_time >= ".( time() - $load_online_time ) . "
			AND s.session_page = '$loc'
			ORDER BY u.username_clean ASC, s.session_ip ASC";
			$query = $DB->query($sql);

			$prev_user_id = 0;
			$prev_user_ip = '';

			while ($row = $DB->fetch_row($query) ) {
				if ( $row['user_id'] != ANONYMOUS ) {
					if ( $row['user_id'] != $prev_user_id ) {
						if ($row['user_colour']) {
							$style_color = ' style="color:#' . $row['user_colour'] . '"';
							$row['username'] = '<strong>' . $row['username'] . '</strong>';
						}
						else
						{
							$style_color = '';
						}

						if ($row['user_allow_viewonline'] && $row['session_viewonline'])
						{
							$user_online_link = $row['username'];
							$logged_visible_online++;
						}
						else
						{
							$user_online_link = '<em>' . $row['username'] . '</em>';
							$logged_hidden_online++;
						}
						if (($row['user_allow_viewonline'] && $row['session_viewonline']) || $auth->acl_get('u_viewonline'))
						{
							if ($row['user_type'] <> USER_IGNORE)
							{
								$user_online_link = '<a href="' . append_sid("{$mkportals->forum_url}/memberlist.php", 'mode=viewprofile&amp;u=' . $row['user_id']) . '"' . $style_color . '>' . $user_online_link . '</a>';
							}
							else
							{
								$user_online_link = ($style_color) ? '<span' . $style_color . '>' . $user_online_link . '</span>' : $user_online_link;
							}

							$online_userlist .= ($online_userlist != '') ? ', ' . $user_online_link : $user_online_link;
						}
					}

					$prev_user_id = $row['user_id'];
				}
				else
				{
					if ( $row['session_ip'] != $prev_session_ip )
					{
						$guests_online++;
					}
				}
				$prev_session_ip = $row['session_ip'];
			}

			$DB->free_result($query1);

			unset($row);
			$total_online_users = $logged_visible_online + $logged_hidden_online + $guests_online;
			$utenti_in = "{$mklib->lang['b_tusers']} ";
			$utenti_in .= $total_online_users;
			$utenti_in .= " ($logged_visible_online {$mklib->lang['b_rusers']} $guests_online {$mklib->lang['b_guests']} {$mklib->lang['b_and']} {$logged_hidden_online} {$mklib->lang['b_anons']})<br />";
			$utenti_in .= "{$mklib->lang['b_vusers']}  {$online_userlist}";
			return $utenti_in;
	}

	function show_emoticons() {
 		global $mkportals, $DB, $Skin, $mklib, $config;
		$css = $this->import_css();
        	$this->emo_path = $config['smilies_path'].'/';
        	$output = $this->get_emo_header($css);
        	$countr = 0;
		$lastemo = "meo";
		$DB->query("SELECT code, smiley_url AS image FROM ".SMILIES_TABLE."");
        	if ( $DB->get_num_rows() ) {
            		while ( $r = $DB->fetch_row() ) {
			if ($lastemo == $r['image']) {
				continue;
			}
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
		$lastemo = $r['image'];
            }
        }
        $output .= "<td></td></tr></table></body></html>";
        print $output;
 	}

	function decode_smilies($message)
 	{
		global $mklib, $DB, $config;
		$DB->query("SELECT * FROM ".SMILIES_TABLE."");
		$impath = $config['smilies_path'];
		while ( $r = $DB->fetch_row() )
		{
			$code = stripslashes($r['code']);
			$image = stripslashes($r['smiley_url']);
			$image = "<img src=\"$mklib->siteurl/$mklib->forumpath/$impath/$image\" border=\"0\" alt=\"\" />";
			$message = str_replace($code, $image, $message);
		}

		return $message;
	}
	function popup_pm($m1, $m2, $m3, $m4)
 	{
		global $DB, $mklib, $mkportals;

		$u1 = $mklib->siteurl.'/'.$mklib->forumpath."/ucp.php?i=pm&folder=inbox";
		$pmk_js = '<script type="text/javascript">
     				<!--
       				window.open(\''.$mklib->siteurl.'/mkportal/pmpopup.php?m1=$m1&m2=$m2&m3=$m3&m4=$m4&u1='.$u1.'\',\'NewPM\',\'width=500,height=250,resizable=yes,scrollbars=yes\');
     				//-->
     				</script>';


		return $pmk_js;
	}

// admin functions

	//ad_perms
	function build_grouplist($ind)
	{
		global $DB;

		$DB->query( "SELECT  group_id, group_name FROM " . GROUPS_TABLE . " ORDER BY `group_id`");
		while( $row = $DB->fetch_row() ) {
			if($row['group_id'] == 5) {
				continue;
			}
			$g_id= $row['group_id'];
			$g_title = $row['group_name'];
			$selected = "";
			if($g_id == $ind) {
				$selected = "selected=\"selected\"";
			}
			$cselect.= "<option value=\"$g_id\" $selected>$g_title</option>\n";
		}
		return $cselect;
	}
	function build_grouplist2()
	{
		global $DB;
		$group = array();
		$DB->query( "SELECT  group_id, group_name FROM " . GROUPS_TABLE . " ORDER BY `group_id`");
		while( $row = $DB->fetch_row() ) {
			if($row['gid'] == 5) {
				continue;
			}
			$g_id = $row['group_id'];
			$group[$g_id][id] = $row['group_id'];
			$group[$g_id][title] = $row['group_name'];
		}
		return $group;
	}
	//ad_perms
	function update_groupperms($g_id)
	{
		global $DB;

		$query = $DB->query( "SELECT group_name FROM " . GROUPS_TABLE . " WHERE group_id = '$g_id'");
		$row = $DB->fetch_row($query);
		$g_title = $row['group_name'];
		return $row['group_name'];

	}

	//ad_poll
	function get_poll_list()
	{
		global $mklib, $DB;

		$poll_active = $mklib->config['poll_active'];
		$DB->query("SELECT topic_id, poll_title FROM " . TOPICS_TABLE . " WHERE poll_title != '' ORDER BY topic_id DESC LIMIT 30");

		while( $poll = $DB->fetch_row() ) {
			$id = $poll['topic_id'];
//$title = utf8_encode($poll['poll_title']);
			$title = $poll['poll_title'];
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
    			$out = "/{$mkportals->forum_url}/memberlist.php?mode=viewprofile&amp;u";
    		break;
			case 'cpaforum':
    			$out = "/{$mkportals->forum_url}/adm/index.php?sid=".$mkportals->member['session_id']."";
    		break;
			case 'cpapers':
    			$out = "/{$mkportals->forum_url}/ucp.php";
    		break;
			case 'pm':
    			$out = "/{$mkportals->forum_url}/ucp.php?i=pm&amp;folder=inbox";
    		break;
			case 'forumsearch':
    			$out = "/{$mkportals->forum_url}/search.php";
    		break;
			case 'logout':
    			$out = "/{$mkportals->forum_url}/ucp.php?mode=logout&amp;sid=".$mkportals->member['session_id']."";
    		break;
			case 'postlink':
    			$out = "/{$mkportals->forum_url}/ucp.php?mode=login";
    		break;
			case 'register':
    			$out = "/{$mkportals->forum_url}/ucp.php?mode=register";
    		break;
			case 'onlinelist':
    			$out = "/{$mkportals->forum_url}/viewonline.php";
    		break;
		case 'login_extra':
    			$out = '<tr>
                  <td width="100%" colspan="2" class="tdglobal mkalign1">
				  <b>'.$mklib->lang['auto_login'].'</b>
                  <input type="checkbox" name="autologin" /></td>
		  <td>
		  <input type="hidden" name="redirect" value="'.htmlspecialchars($_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']).'" />
		  <input type="hidden" name="sid" value="'.$mkportals->member['session_id'].'" />
		  <input type="hidden" name="login" value="1" />
		  </td>

		  </tr>';
    		break;
			case 'login_user':
    			$out = 'username';
    		break;
			case 'login_passw':
    			$out = 'password';
    		break;
			case 'calendar_event':
    			$out = "#";
    		break;
			default:
    			$out = "";
    		break;
    		}

		return $out;

	}

	function get_avatar() {
		global $DB, $mkportals, $mklib, $config;

		if (!$mkportals->member['id']) return '';
		$query = $DB->query( "SELECT user_avatar, user_avatar_type, user_avatar_width, user_avatar_height FROM ".USERS_TABLE." WHERE user_id = '{$mkportals->member['id']}'");
		$row = $DB->fetch_row($query);
		$avatar_img = '';
		if ($row['user_avatar'] && $row['user_avatar_type']) {
			switch ($row['user_avatar_type'])
			{
				case AVATAR_UPLOAD:
					$avatar_img = $mklib->siteurl.'/'.$mklib->forumpath.'/download/file.php?avatar=';
				break;

				case AVATAR_GALLERY:
					$avatar_img = $mklib->siteurl.'/'.$mklib->forumpath.'/'. $config['avatar_gallery_path'] . '/';
				break;
			}

			$avatar_img .= $row['user_avatar'];
			$avatar_img = '<img src="' . $avatar_img . '" width="' . $row['user_avatar_width'] . '" height="' . $row['user_avatar_height'] . '" alt="" />';
		}
			return $avatar_img;
	}

	function get_forumnav()
 	{
		global $mklib, $mkportals, $Skin;
		require "$mklib->mklang/lang_global.php";

		$out = "<tr><td class=\"tdblock\">";
		$out .= $Skin->row_link_block(!$mklib->config['noicons'] ? "<img class=\"mkicon\" src=\"$mklib->images/atb_npost.gif\" style=\"vertical-align: middle\" align=\"left\" alt=\"{$mklib->lang['m_newpost']}\" />" : "", "href=\"/{$mkportals->forum_url}/search.php?search_id=newposts\"", $mklib->lang['m_newpost']);
		$out .= "</td></tr><tr><td class=\"tdblock\">";
		
		$out .= $Skin->row_link_block(!$mklib->config['noicons'] ? "<img class=\"mkicon\" src=\"$mklib->images/atb_members.gif\" style=\"vertical-align: middle\" align=\"left\" alt=\"{$mklib->lang['m_users']}\" />" : "", "href=\"/{$mkportals->forum_url}/memberlist.php\"", $mklib->lang['m_users']);
		$out .= "</td></tr><tr><td class=\"tdblock\">";

		$out .= $Skin->row_link_block(!$mklib->config['noicons'] ? "<img class=\"mkicon\" src=\"$mklib->images/atb_help.gif\" style=\"vertical-align: middle\" align=\"left\" alt=\"{$mklib->lang['m_help']}\" />" : "", "href=\"/{$mkportals->forum_url}/faq.php\"", $mklib->lang['m_help']);
		$out .= "</td></tr>";

		return $out;


	}
	function get_site_stat()
 	{
		global $DB, $config;


		$stat = array();
		$stat['members'] = $config['num_users'];

		$stat['last_member'] = $config['newest_user_id'];
		$stat['last_member_name'] = $config['newest_username'];

		$stat['topics'] = $config['num_topics'];
		$stat['total_posts'] = $config['num_posts'];
		$stat['replies'] = $stat['total_posts'] - $stat['topics'];
		return $stat;


	}

	function get_onlineblock()
 	{
		global $DB, $mkportals, $config, $user, $auth;

		$load_online_time = intval($config['load_online_time']) * 60;

		$sql = "SELECT u.username, u.username_clean, u.user_id, u.user_type, u.user_allow_viewonline, u.user_colour, s.session_ip, s.session_viewonline
		FROM ".USERS_TABLE." u, ".SESSIONS_TABLE." s
		WHERE u.user_id = s.session_user_id
			AND s.session_time >= ".( time() - $load_online_time ) . "
		ORDER BY u.username_clean ASC, s.session_ip ASC";
		$query = $DB->query($sql);


		$logged_visible_online = $logged_hidden_online = $guests_online = $prev_user_id = 0;
		$prev_user_ip = $online_userlist = '';

		while ($row = $DB->fetch_row($query) )
		{
			if ( $row['user_id'] != ANONYMOUS )
			{
				if ( $row['user_id'] != $prev_user_id )
				{
					if ($row['user_colour'])
					{
						$style_color = ' style="color:#' . $row['user_colour'] . '"';
						$row['username'] = '<strong>' . $row['username'] . '</strong>';
					}
					else
					{
						$style_color = '';
					}

					if ($row['user_allow_viewonline'] && $row['session_viewonline'])
					{
						$user_online_link = $row['username'];
						$logged_visible_online++;
					}
					else
					{
						$user_online_link = '<em>' . $row['username'] . '</em>';
						$logged_hidden_online++;
					}

					if (($row['user_allow_viewonline'] && $row['session_viewonline']) || $auth->acl_get('u_viewonline'))
					{
						if ($row['user_type'] <> USER_IGNORE)
						{
							$user_online_link = '<a href="' . append_sid("{$mkportals->forum_url}/memberlist.php", 'mode=viewprofile&amp;u=' . $row['user_id']) . '"' . $style_color . '>' . $user_online_link . '</a>';
						}
						else
						{
							$user_online_link = ($style_color) ? '<span' . $style_color . '>' . $user_online_link . '</span>' : $user_online_link;
						}

						$online_userlist .= ($online_userlist != '') ? ', ' . $user_online_link : $user_online_link;
					}
				}

				$prev_user_id = $row['user_id'];
			}
			else
			{
				if ( $row['session_ip'] != $prev_session_ip )
				{
					$guests_online++;
				}
			}
			$prev_session_ip = $row['session_ip'];
		}

		$DB->free_result($query);

		return array($logged_visible_online, $logged_hidden_online, $guests_online, $online_userlist);

	}


	function get_onlinehome($languest) {

	global $DB, $mkportals, $config, $user, $auth;

	$content = '';
	$inter = ',';
	$online = array();
	$guestlocation = array();
	$loctypes = array('portale', 'blog', 'gallery', 'urlobox', 'downloads', 'news', 'topsite', 'chat', 'reviews');
	$load_online_time = intval($config['load_online_time']) * 60;
	
	$sql = "SELECT u.username, u.username_clean, u.user_id, u.user_type, u.user_allow_viewonline, u.user_colour, s.session_ip, s.session_viewonline, s.session_page
		FROM ".USERS_TABLE." u, ".SESSIONS_TABLE." s
		WHERE u.user_id = s.session_user_id
			AND s.session_time >= ".( time() - $load_online_time ) . "
			AND s.session_user_id <> ''
		ORDER BY u.username_clean ASC, s.session_ip ASC";
	$query1 = $DB->query($sql);

	$total_online_users = $logged_visible_online = $logged_hidden_online = $guests_online = $prev_user_id = 0;
	$prev_user_ip = $online_userlist = '';

	while ( $row = $DB->fetch_row($query1) )
	{
		$row['location'] = $row['session_page'];
		$pos = strpos($row['location'], "ajax");
		if ($pos) {
			$row['location'] = "portale";
		}
		if (!in_array($row['location'], $loctypes) ) {
			$row['location'] = "forum";
		}
		// User is logged in and therefore not a guest
		if ( $row['user_id'] != ANONYMOUS )
		{
			// Skip multiple sessions for one user
			if ( $row['user_id'] != $prev_user_id )
			{
				if ($row['user_colour'])
				{
					$style_color = ' style="color:#' . $row['user_colour'] . '"';
					$row['username'] = '<strong>' . $row['username'] . '</strong>';
				}
				else
				{
					$style_color = '';
				}
				if ($row['user_allow_viewonline'] && $row['session_viewonline'])
				{
					$user_online_link = $row['username'];
					$logged_visible_online++;
				}
				else
				{
					$user_online_link = '<em>' . $row['username'] . '</em>';
					$logged_hidden_online++;
				}

				if (($row['user_allow_viewonline'] && $row['session_viewonline']) || $auth->acl_get('u_viewonline'))
				{
					if ($row['user_type'] <> USER_IGNORE)
					{
						$user_online_link = '<a href="' . append_sid("{$mkportals->forum_url}/memberlist.php", 'mode=viewprofile&amp;u=' . $row['user_id']) . '"' . $style_color . '>' . $user_online_link . '</a>';
					}
					else
					{
						$user_online_link = ($style_color) ? '<span' . $style_color . '>' . $user_online_link . '</span>' : $user_online_link;
					}

					$online[$row['location']] .= (!empty($online[$row['location']])) ? "{$inter} ".$user_online_link : $user_online_link;
				}
			}
			$prev_user_id = $row['user_id'];
		}
		else
		{
			if ( $row['session_ip'] != $prev_session_ip )
			{
				$guests_online++;
				$guestlocation[$row['location']] = (isset($guestlocation[$row['location']])) ? $guestlocation[$row['location']] + 1 : 1;
			}
		}
		$prev_session_ip = $row['session_ip'];
	}

	$DB->free_result($query1);
	unset($row);

	$total_online_users = $logged_visible_online + $logged_hidden_online + $guests_online;

	if (!empty($guestlocation)) {
		foreach ($guestlocation as $loc => $n)
		{
			$online["$loc"] .= (empty($online["$loc"])) ? $n."&nbsp;{$languest}" : "{$inter} ".$n."&nbsp;{$languest}";
		}

	}

	return array($logged_visible_online, $logged_hidden_online, $guests_online, $online['portale'], $online['blog'], $online['gallery'], $online['urlobox'], $online['downloads'], $online['news'], $online['chat'], $online['topsite'], $online['reviews'], $online['forum']);

	}

	function get_last_posts($by, $sdate)
 	{
		global $DB, $mklib, $mkportals, $auth;;

		$limit = 5;
		$taglio = 25;
		$content = '';

		$good = array();
		$sql = "SELECT forum_id FROM " . FORUMS_TABLE . " WHERE forum_type=". FORUM_POST;
		$query1 = $DB->query($sql);
		while ( $row = $DB->fetch_row($query1) ) {
			if ($auth->acl_get('f_read', $row['forum_id'])) {
				$good[] = $row['forum_id'];
			}
		}

		$DB->free_result($query1);

		if ( empty($good) ) {
			return '';
		}

		$sql = "SELECT p.*, u.username, u.user_id, u.user_colour
				FROM " . POSTS_TABLE . " p, " . USERS_TABLE . " u
					WHERE p.forum_id IN (".implode(',', $good ).")
					AND p.poster_id = u.user_id
					AND p.post_approved = 1
					ORDER BY p.post_time DESC LIMIT 0,$limit";
		$query1 = $DB->query($sql);

		while ( $post = $DB->fetch_row($query1) ) {
	  		$post['post_subject'] = $mklib->post_htmlspecialchars(strip_tags($post['post_subject']));
			if (strlen($post['post_subject']) > $taglio) {
				$post['post_subject'] = substr( $post['post_subject'],0,($taglio - 3) ) . "...";
				$post['post_subject'] = preg_replace( '/&(#(\d+;?)?)?(\.\.\.)?$/', '...',$post['post_subject'] );
			}
	
	 		$post['date']  = $mklib->create_date($post['post_time']);
			$pid = $post['post_id'];
//$title = utf8_encode($post['post_subject']);
			$title = $post['post_subject'];
			$mid = $post['user_id'];
			
			if ($post['user_colour'])
			{
				$style_color = ' style="color:#' . $post['user_colour'] . '"';
				$post['username'] = '<strong>' . $post['username'] . '</strong>';
			}
			else
			{
				$style_color = '';
			}

			$date = $post['date'];
			$content .= "
				<tr>
				  <td width=\"100%\" class=\"tdblock\">
				  <a class=\"uno\" href=\"/{$mkportals->forum_url}/viewtopic.php?f={$post['forum_id']}&amp;t={$post['topic_id']}&amp;p={$pid}#p{$pid}\">{$title}</a>
				  </td>
				</tr>
				<tr>
				  <td class=\"tdglobal\">
				  {$by}: <a href=\"/" . append_sid("{$mkportals->forum_url}/memberlist.php", 'mode=viewprofile&amp;u=' . $post['user_id']) . '"' . $style_color . '>' . $post['username'] . "</a><br /> {$sdate}: {$date}
				  </td>
				</tr>
			";
 		}

		$DB->free_result($query1);

		return $content;
	}

	function get_poll_active($post_id)
 	{
		global $DB, $mklib, $mkportals, $config;
		
		$query1 = $DB->query("SELECT t.topic_id, t.forum_id, t.poll_title, p.bbcode_bitfield, p.bbcode_uid FROM " . TOPICS_TABLE . " t, ". POSTS_TABLE ." p WHERE p.post_id=t.topic_first_post_id AND t.poll_start>0 AND t.topic_id=$post_id");
		$poll = $DB->fetch_row($query1);


		require_once $mkportals->forum_url.'/includes/bbcode.php';
		$poll_bbcode = new bbcode();

		$id = $poll['topic_id'];
		$title = censor_text($poll['poll_title']);
		$title = str_replace("\n", ' ', $title);
		if ($poll['bbcode_bitfield']) {
			$poll_bbcode->bbcode_second_pass($title, $poll['bbcode_uid'], $poll['bbcode_bitfield']);
			
		}

		$poll_options = array();
		$query1 = $DB->query("SELECT * FROM " . POLL_OPTIONS_TABLE . "
					WHERE topic_id = $post_id
					ORDER BY poll_option_id");
		while( $opoll = $DB->fetch_row($query1) ) {
			$poll_options[] = $opoll;
			$poll_total += $opoll['poll_option_total'];
		}
//$title = utf8_encode($title);
		$DB->free_result($query1);

		$out = "
				<tr>
				  <td class=\"tdblock\">
				  <a href=\"/{$mkportals->forum_url}/viewtopic.php?t={$post_id}&amp;view=viewpoll\">{$title}</a>
				  </td>
				</tr>
            ";

		for ($i = 0, $size = count($poll_options); $i < $size; $i++)
		{
			$poll_options[$i]['poll_option_text'] = censor_text($poll_options[$i]['poll_option_text']);
			$poll_options[$i]['poll_option_text'] = str_replace("\n", ' ', $poll_options[$i]['poll_option_text']);

			if ($poll['bbcode_bitfield'])
			{
				$poll_bbcode->bbcode_second_pass($poll_options[$i]['poll_option_text'], $poll['bbcode_uid'], $poll['bbcode_bitfield']);
			}

// $poll_options[$i]['poll_option_text'] = utf8_encode($poll_options[$i]['poll_option_text']);
			$poll_options[$i]['poll_option_text'] = $poll_options[$i]['poll_option_text'];
			$percent = ($poll_total > 0) ? $poll_options[$i]['poll_option_total'] * 100 / $poll_total : 0;
            		$percent = sprintf( '%.2f' , $percent );
            		$width   = $percent > 0 ? floor( round( $percent ) * ( 122 / 100 ) ) : 0;
			$out .= "
				<tr>
				  <td class=\"tdblock3\">
				  {$poll_options[$i]['poll_option_text']}
				  </td>
				</tr>
				<tr>
				  <td align=\"left\">
					<div align=\"left\" style=\"width: 130px; background-color: #FFFDEF; border: solid 1px;\">
				  		<img src=\"{$mklib->images}/bar-start.gif\" width=\"4\" height=\"11\" alt=\"\" /><img src=\"{$mklib->images}/bar.gif\" width=\"{$width}\" height=\"11\" alt=\"\" /><img src=\"{$mklib->images}/bar-end.gif\" width=\"4\" height=\"11\" alt=\"\" />
					</div>
		  		</td>
				</tr>

                ";

		}

		unset($poll_bbcode);

		$out .= "
				<tr>
				  <td class=\"tdblock\">
				  <span class=\"mktxtcontr\">{$poll_total}</span> {$mklib->lang['poll_totalvotes']}
				  </td>
				</tr>
				<tr>
				  <td class=\"tdglobal\">
				  <a href=\"/{$mkportals->forum_url}/viewtopic.php?t={$post_id}&amp;view=viewpoll\">{$mklib->lang['poll_go']}</a>
				  </td>
				</tr>
            ";

		return $out;
	}


	function get_forum_list()
	{

		global $mklib, $DB;

		$cselect = array();

		$query1 = $DB->query("SELECT forum_id AS id, forum_name AS name FROM " . FORUMS_TABLE . " WHERE forum_type=". FORUM_POST);

		while( $board = $DB->fetch_row($query1) ) {
			$cselect[] = $board;
		}

		$DB->free_result($query1);

		return $cselect;
	}
	function get_board_news()
 	{
		global $DB, $mklib, $mkportals, $config, $auth;

		require_once $mkportals->forum_url.'/includes/bbcode.php';
		$limit = $mklib->config['bnews_block'];
		$news_words= $mklib->config['bnews_words'];
		//$taglio = 17;

		$temp_forum_active = unserialize($mklib->config['forum_active']);
		if(!$temp_forum_active) {
				return '';
		}

		$good = array();
		$sql = "SELECT forum_id FROM " . FORUMS_TABLE . " WHERE forum_type=". FORUM_POST;
		$query1 = $DB->query($sql);
		while ( $row = $DB->fetch_row($query1) ) {
			if ($auth->acl_get('f_read', $row['forum_id'])) {
				$good[] = $row['forum_id'];
			}
		}

		$DB->free_result($query1);

		if ( empty($good) ) {
			return '';
		}

		$forum_active = array();
		foreach ($temp_forum_active as $fa) {
			if ( in_array($fa, $good) ) {
				$forum_active[] = $fa;
			}
		}

		if(empty($forum_active)) {
				return '';
		}

		$out = '';

		$sql = "SELECT p.*, f.forum_id, f.forum_name, t.*, u.username, u.user_id, u.user_sig, u.user_sig_bbcode_uid, u.user_avatar, u.user_avatar_type, u.user_avatar_width, u.user_avatar_height, u.user_colour
				FROM " . FORUMS_TABLE . " f, " . TOPICS_TABLE . " t, " . USERS_TABLE . " u, " . POSTS_TABLE . " p
					WHERE p.forum_id IN (".implode(',', $forum_active ).")
					AND f.forum_id = p.forum_id
					AND p.post_id = t.topic_first_post_id
					AND p.poster_id = u.user_id
					AND p.post_approved = 1
					AND t.topic_moved_id = 0
					GROUP BY t.topic_id
					ORDER BY t.topic_id DESC LIMIT 0,$limit";
		$query1 = $DB->query($sql);

		while ( $post = $DB->fetch_row($query1) ) {
//$title = utf8_encode($post['post_subject']);
			$title = $post['post_subject'];
	  		$title = $mklib->post_htmlspecialchars(strip_tags($title));
	 		$date  = $mklib->create_date($post['post_time']);
			$tid = $post['topic_id'];
			$pid = $post['post_id'];
			$mid = $post['user_id'];
			$message = censor_text($post['post_text']);
//$message = utf8_encode($message);
			$bbcode_uid = $post['bbcode_uid'];
			if ($post['bbcode_bitfield'])
			{
				$post_bbcode = new bbcode();
				$post_bbcode->bbcode_second_pass($message, $bbcode_uid, $post['bbcode_bitfield']);
				$message = preg_replace('/<a[^>]+onclick=\"selectCode\(this\)[^>]+>[^<]+<\/a>/', '', $message); //used to delete SELECT ALL in CODE bbcode
				$message = smiley_text($message);
			}
			if ($news_words) {
				$message = $mklib->post_htmlspecialchars(strip_tags($message));
				$message = substr ($message, 0, $news_words);
				$message .= " ...";
			}
			$message = str_replace("\n", "<br />", $message);
			$fname = $post['forum_name'];
			$icona = $mklib->siteurl.'/mkportal/templates/default/images/icona_news.gif';
			$numreplies = $post['topic_replies']." ".$mklib->lang['replies'];

			$avatar_img = '';

			if ($post['user_avatar'] && $post['user_avatar_type'])
			{
				switch ($post['user_avatar_type'])
				{
					case AVATAR_UPLOAD:
						$avatar_img = $mklib->siteurl.'/'.$mklib->forumpath.'/download/file.php?avatar=';
					break;

					case AVATAR_GALLERY:
						$avatar_img = $mklib->siteurl.'/'.$mklib->forumpath.'/'.$config['avatar_gallery_path'] . '/';
					break;
				}

				$avatar_img .= $post['user_avatar'];
				$avatar_img = '<img src="' . $avatar_img . '" width="' . $post['user_avatar_width'] . '" height="' . $post['user_avatar_height'] . '" alt="" />';
			}

			if (!$avatar_img)
  			{
				$avatar_img = '<img hspace="0" src="'.$icona.'" align="bottom" border="0" alt="" />';
			}
			
			if ($post['user_colour'])
			{
				$style_color = ' style="color:#' . $post['user_colour'] . '"';
				$post['username'] = '<strong>' . $post['username'] . '</strong>';
			}
			else
			{
				$style_color = '';
			}

		$out .= "
				  <table class=\"tabnews\" cellspacing=\"2\" cellpadding=\"2\" width=\"100%\">
				    <tbody>
				    <tr>
				      <td class=\"tdblock\" align=\"center\" width=\"5%\">
				      {$avatar_img}
				      </td>
				      <td class=\"tdblock\" valign=\"middle\" align=\"center\" width=\"95%\">
				      <b>{$fname}<br /><a href=\"/{$mkportals->forum_url}/viewtopic.php?f={$post['forum_id']}&amp;t={$post['topic_id']}&amp;p={$pid}#p{$pid}\">{$title}</a></b>
				      <br /><div class=\"mkalign2\" style='font-style: italic; font-weight: normal;'><a href=\"/{$mkportals->forum_url}/viewtopic.php?f={$post['forum_id']}&amp;t={$tid}\">{$numreplies}</a>&nbsp;</div>
				      </td>
				    </tr>
				    <tr>
				      <td colspan=\"2\" style=\"padding: 6px;\"><br />
				      {$message}
				      </td>
				    </tr>
				    <tr>
				      <td class=\"mkalign2\" colspan=\"2\">
				      <br /><i>{$mklib->lang['from']}<b> <a href=\"/" . append_sid("{$mkportals->forum_url}/memberlist.php", 'mode=viewprofile&amp;u=' . $post['user_id']) . '"' . $style_color . '>' . $post['username'] . "</a></b>, {$date} <a href=\"/{$mkportals->forum_url}/viewtopic.php?f={$post['forum_id']}&amp;t={$tid}\"> [ {$mklib->lang['readall']} ]</a></i>
				      </td>
				    </tr>
				  </tbody>
				</table>
		";
 		}

		$DB->free_result($query1);

		$out = str_replace('<img src="{SMILIES_PATH}', '<img src="'.$mklib->siteurl.'/'.$mklib->forumpath.'/'.$config['smilies_path'], $out);
		return $out;
	}


	
	function calendar_birth($chosen_month, $chosen_year)
 	{
		global $mkportals, $DB, $mklib;

		$birthdays = array();
		$tool_birthdays = array();

		$chosen_month = intval($chosen_month);
		$chosen_year = intval($chosen_year);
		$query = $DB->query("SELECT user_id, username, user_colour, user_birthday FROM " . USERS_TABLE . " WHERE user_birthday LIKE '%".sprintf('-%2d-', $chosen_month)."%'
								AND user_type IN (" . USER_NORMAL . ', ' . USER_FOUNDER . ')');
    		while ($row = $DB->fetch_row($query)) {
			$user_colour = ($row['user_colour']) ? ' style="color:#' . $row['user_colour'] .'"' : '';
			$user = explode('-', $row['user_birthday']);
       	 		$birthdays[ $user[0] ]++;
        		if ($birthdays[ $user[0] ] < 10) {
            			$tool_birthdays[$user[0]] .=  '<a' . $user_colour . ' href="' . append_sid("{$mkportals->forum_url}/memberlist.php", 'mode=viewprofile&amp;u=' . $row['user_id']) . '">' . $mklib->post_htmlspecialchars($row['username']) . '</a>' .' ('.($chosen_year - $user['2']).')&nbsp;';
        		} else if ($birthdays[ $user[0] ] == 10) {
            			$tool_birthdays[$user[0]] .=  '...';
        		}
    		}
		$DB->free_result($query);
		return array($birthdays, $tool_birthdays);
	}

	function calendar_events($chosen_month, $chosen_year)
 	{

		$events = array();
		$tool_events = array();
		return array($events, $tool_events);
	}

	function langselect() {

		global $DB, $mklib, $mkportals;
	
		if (!$mkportals->member['id']) return '';
		$content = '<form name="mklanglist" action="post">'."\n".'<select name="seleclang" class="bgselect" onchange="document.location.href=mklanglist.seleclang.options[this.selectedIndex].value">';

		$query = $DB->query("SELECT lang_id, lang_iso, lang_english_name FROM " . LANG_TABLE );
		while ( $r = $DB->fetch_row($query) ) {
			$selected = '';
			$name = $r['lang_english_name'];
			if ($mkportals->member['mk_lang'] == $r['lang_iso']) {
				$selected = ' selected="selected"';
			}
			$content .= "\n".'<option value="'.$mklib->siteurl.'/index.php?langid='.$r['lang_iso'].'"'.$selected.'>'.$name.'</option>';
		}

		$DB->free_result($query);
		$content .= "\n</select>\n</form>";
   		$content = '
				<tr>
				  <td class="tdblock" align="center" valign="middle">'.$content.'</td>
				</tr>
                ';
		return $content;
    	}

	function update_lang($langid) {
        	global $mkportals, $DB, $mklib;

		if (!$mkportals->member['id']) {return;}
		$DB->query("UPDATE ".USERS_TABLE." SET user_lang ='$langid' WHERE user_id = '{$mkportals->member['id']}'");
		$DB->close_db();
		header("Location: {$mkportals->forum_url}/index.php");
		exit;
	}

	function skinselect() {
		global $DB, $mklib, $mkportals;

		if (!$mkportals->member['id']) return '';
		$templateslist .= '<form name="skinlist" action="post">'."\n".'<select name="selectskin" class="bgselect" onchange="document.location.href=skinlist.selectskin.options[this.selectedIndex].value">';
		$query = $DB->query("SELECT style_id, style_name FROM " . STYLES_TABLE);
		while ( $r = $DB->fetch_row($query) )
		{
			$selected = '';
			if ($mkportals->member['theme'] == $r['style_id']) {
				$selected = ' selected="selected"';
			}
			if (strlen($r['style_name']) > 14 ) {
				$r['style_name'] = substr($r['style_name'], 0, 14);
			}
			$templateslist .= "\n".'<option value="'.$mklib->siteurl.'/index.php?skinid='.$r['style_id'].'"'.$selected.'>'.$r['style_name'].'</option>';
		}
		$DB->free_result($query);
		
		$templateslist .= "\n</select>\n</form>";
    		$templateslist = '
				<tr>
				  <td class="tdblock" align="center" valign="middle">'.$templateslist.'</td>
				</tr>
                ';
		return $templateslist;
	}

	function update_skin($skinid) {
		global $mkportals, $DB, $mklib;
		if (!$mkportals->member['id']) {
			return;
		}
		$query1 = $DB->query("SELECT style_id FROM " . STYLES_TABLE . " WHERE style_id = '$skinid'");
		if ($DB->fetch_row($query1)){
			$DB->query("UPDATE ".USERS_TABLE." SET user_style ='$skinid' WHERE user_id = '{$mkportals->member['id']}'");
			$DB->close_db();
	 		header("Location: {$mkportals->forum_url}/index.php");
			exit;
		}

	}

	function import_css() {
		global $mkportals, $DB, $mklib;

		$newstyle = true;		
		$css2 = $mkportals->forum_url."/styles/".$mkportals->member['theme_path']."/theme/colours.css";
		if (!is_file($css2)){
			$css2 = $mkportals->forum_url."/styles/".$mkportals->member['theme_path']."/theme/stylesheet.css";
			$newstyle = false;
		}
		$images_url = $mkportals->forum_url."/styles/".$mkportals->member['theme_path']."/theme";
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

		if ($newstyle == true) {
			// Set board images path
			$css2 = str_replace("{T_THEME_PATH}", "$images_url", $css2);
			//importing body
			$pos = strpos($css2, "body");
			$pos2 = strpos($css2, "}", $pos);
			if ($pos) {
				$mkpsubs = substr($css2, $pos, ($pos2 - ($pos -1)));
				//$mkpsubs = "body {font-size: 10px; font-family: Verdana, Arial, Helvetica, sans-serif;}".$mkpsubs;
				$css = preg_replace( "`(\.importbody(.*?\}))`is", $mkpsubs, $css);
			}

			//importing light background
			$pos = strpos($css2, ".bg1");
			$pos2 = strpos($css2, "}", $pos);
			if ($pos) {
				$mkpsubs = substr($css2, $pos, ($pos2 - ($pos -1)));
				$css = preg_replace( "`(\.importlightback(.*?\}))`is", $mkpsubs, $css);
			}

			//importing medium background
			$pos = strpos($css2, ".bg3");
			$pos2 = strpos($css2, "}", $pos);
			if ($pos) {
				$mkpsubs = substr($css2, $pos, ($pos2 - ($pos -1)));
				$css = preg_replace( "`(\.importmediumback(.*?\}))`is", $mkpsubs, $css);
			}

			//importing dark background
			$pos = strpos($css2, ".forabg");
			$pos2 = strpos($css2, "}", $pos);
			if ($pos) {
				$mkpsubs = substr($css2, $pos, ($pos2 - ($pos -1)));
				$mkpsubs = str_replace("}", " color: #FFFFFF; padding: 4px;}", $mkpsubs);
				$css = preg_replace( "`(\.importdarkback(.*?\}))`is", $mkpsubs, $css);
			}

			//importing module table headers
			$pos = strpos($css2, "header");
			$pos2 = strpos($css2, "}", $pos);
			if ($pos) {
				$mkpsubs = substr($css2, $pos, ($pos2 - ($pos -1)));
				//$mkpsubs = preg_replace( "/border(.*?\;)/mi", "border: 0px;", $mkpsubs);
				$css = preg_replace( "`(\.importmodulex(.*?\}))`is", $mkpsubs, $css);
			}
		}

		if ($newstyle == false) {
			//importing body
			$pos = strpos($css2, "body");
			$pos2 = strpos($css2, "}", $pos);
			if ($pos) {
				$mkpsubs = substr($css2, $pos, ($pos2 - ($pos -1)));
				$css = preg_replace( "`(\.importbody(.*?\}))`is", $mkpsubs, $css);
			}

			// set td padding
			$pos = strpos($css2, "td {");
			$pos2 = strpos($css2, "}", $pos);
			if ($pos) {
				$mkpsubs = "td { padding: 0; }";
				$css = preg_replace( "`(\.importbgbody(.*?\}))`is", $mkpsubs, $css);
			}
			//importing light background
			$pos = strpos($css2, ".row1");
			$pos2 = strpos($css2, "}", $pos);
			if ($pos) {
				$mkpsubs = substr($css2, $pos, ($pos2 - ($pos -1)));
				$mkpsubs = preg_replace( "/padding(.*?\;)/mi", "padding: 0;", $mkpsubs);
				$css = preg_replace( "`(\.importlightback(.*?\}))`is", $mkpsubs, $css);
			}

			//importing medium background
			$pos = strpos($css2, ".row2");
			$pos2 = strpos($css2, "}", $pos);
			if ($pos) {
				$mkpsubs = substr($css2, $pos, ($pos2 - ($pos -1)));
				$mkpsubs = preg_replace( "/padding(.*?\;)/mi", "padding: 0;", $mkpsubs);
				$css = preg_replace( "`(\.importmediumback(.*?\}))`is", $mkpsubs, $css);
			}
			//importing dark background
			$pos = strpos($css2, "th {");
			$pos2 = strpos($css2, "}", $pos);
			if ($pos) {
				$mkpsubs = substr($css2, $pos, ($pos2 - ($pos -1)));
				$css = preg_replace( "`(\.importdarkback(.*?\}))`is", $mkpsubs, $css);
			}
			
			//importing module table headers
			$pos = strpos($css2, ".cat");
			$pos2 = strpos($css2, "}", $pos);
			if ($pos) {
				$mkpsubs = substr($css2, $pos, ($pos2 - ($pos -1)));
				$css = preg_replace( "`(\.importmodulex(.*?\}))`is", $mkpsubs, $css);
			}

			//importing form styles
			$pos = strpos($css2, "input");
			$pos2 = strpos($css2, "}", $pos);
			if ($pos) {
				$mkpsubs = substr($css2, $pos, ($pos2 - ($pos -1)));
				$css = preg_replace( "`(\.importforms(.*?\}))`is", $mkpsubs, $css);
			}
			
			//adjust board images path
			$css = str_replace("url('./images", "url('$images_url/images", $css);
		}

		//adjust mkp images path		
		$css = str_replace ("MKPORTALIMGDIR", "$mklib->images", $css);
		$css = "<style type=\"text/css\">\n$css\n</style>\n";
		//Load Portal CP stylesheet
		if (defined('IN_MKPADMIN')) {
			$css .= file_exists($mklib->template.'/stylecp.css') ? "<link href=\"/$mklib->template/stylecp.css\" rel=\"stylesheet\" type=\"text/css\" />" : "";
		}
		unset($css2);
		
		//RSS block css
		if ($mklib->config['rss_css'] == 1) { //rss block css
			$css .= "\n<link rel=\"stylesheet\" href=\"/{$this->sitepath}mkportal/modules/rss/files/simplepie.css\" type=\"text/css\" media=\"screen, projector\" />\n";
		}

		return $css;
	}
	function simple_mail($subject, $message, $iduser)
 	{
		global $DB, $mklib;

		$headers = "From: webmaster@" . $mklib->sitename . "\r\n" . "Reply-To: webmaster@" . $mklib->sitename . "\r\n" . "X-Mailer: MKportal Mail";
		$dest = "";
		$query1 = $DB->query("SELECT user_email FROM ".USERS_TABLE." WHERE user_id = '$iduser'");
		$row = $DB->fetch_row($query1);
		$dest = $row['user_email'];

		mail($dest, $subject, $message,  $headers);
	}
	function admin_mail($subject, $message)
 	{
		global $DB, $mklib;

		$headers = "From: webmaster@" . $mklib->sitename . "\r\n" . "Reply-To: webmaster@" . $mklib->sitename . "\r\n" . "X-Mailer: MKportal Mail";

		$dest = "";
		$query = $DB->query("SELECT user_email FROM ".USERS_TABLE." WHERE user_type = '".USER_FOUNDER."'");
		while ( $row = $DB->fetch_row($query) ) {
			$dest .= $row['user_email'].", ";
		}
		$DB->free_result($query);
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
