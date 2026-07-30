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
		
		$idu = $mkportals->member['id'];
		$location = $_SERVER["REQUEST_URI"];

		$DB->query("UPDATE " . TABLE_PREFIX . "sessions SET location ='$location' WHERE uid = '$idu'");

	}


	function get_active_users($loc) {

		global $DB, $mkportals, $mklib;

		switch($loc) {
							case 'portale':
    							$location= "portale";
    						break;
							case 'blog':
    							$location= "blog";
    						break;
							case 'gallery':
    							$location= "gallery";
    						break;
							case 'urlobox':
    							$location= "urlobox";
    						break;
							case 'downloads':
    							$location= "downloads";
    						break;
							case 'news':
    							$location= "news";
    						break;
							case 'topsite':
    							$location= "topsite";
    						break;
							case 'chat':
    							$location= "chat";
    						break;
							case 'reviews':
    							$location= "reviews";
    						break;
							default:
							$location= "portale";
    						break;
						}
		$time = (time() - 900);

		$DB->query("SELECT s.sid, s.ip, s.uid, s.time, s.location, u.username, u.invisible, u.usergroup, u.displaygroup, g.namestyle
		FROM ".TABLE_PREFIX."sessions s
		LEFT JOIN ".TABLE_PREFIX."users u ON (s.uid=u.uid)
		LEFT JOIN ".TABLE_PREFIX."usergroups g ON (g.gid=u.usergroup)
		WHERE s.time>'$time'
		AND s.location LIKE '%$location%'
		ORDER BY u.username ASC, s.time DESC
		");
			$cached = array();
			$active = array( 'guests' => 0, 'anon' => 0, 'members' => 0, 'names' => "");
			while ($result = $DB->fetch_row() ) {

				if ($result['uid'] == 0) {
					$active['guests']++;
				} else {
						if ($cached[ $result['uid'] ]) {
							continue;
						}
						$cached[ $result['uid'] ] = 1;
						$result['username'] = str_replace("{username}", $result['username'], $result['namestyle']);
						if ($result['invisible'] != "no") {
							if ( $mkportals->member['mgroup'] == "4") {
								$active['names'] .= "<a href=\"/{$mkportals->forum_url}/member.php?action=profile&amp;uid={$result['uid']}\">{$result['username']}</a>*, ";
								$active['anon']++;
							} else {
								$active['anon']++;
							}
						} else {
							$active['members']++;
							$active['names'] .= "<a href=\"/{$mkportals->forum_url}/member.php?action=profile&amp;uid={$result['uid']}\">{$result['username']}</a>, ";
						}
				}
			}
			$active['names'] = preg_replace( "/,\s+$/", "" , $active['names'] );
			$utenti_in = "{$mklib->lang['b_tusers']} ";
			$utenti_in .= ($active['members'] + $active['guests'] + $active['anon'] );
			$utenti_in .= " ({$active['members']} {$mklib->lang['b_rusers']} {$active['guests']} {$mklib->lang['b_guests']} {$mklib->lang['b_and']} {$active['anon']} {$mklib->lang['b_anons']})<br />";
			$utenti_in .= "{$mklib->lang['b_vusers']}  {$active['members']} {$active['names']}";

			return $utenti_in;
	}

	function show_emoticons()
 	{
 		global $mkportals, $DB, $Skin, $mklib;
		$css = $this->import_css();
 		$DB->query("SELECT find as code, image FROM " . TABLE_PREFIX . "smilies");
        $this->emo_path = "";
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
		global $mkportals, $DB, $mklib;

		$DB->query("SELECT find, image FROM " . TABLE_PREFIX . "smilies");
		while ( $r = $DB->fetch_row() )
		{
			$code = stripslashes($r['find']);
			$image = stripslashes($r['image']);
			if (!strpos($image, "ttp://")) {
					$image = "$mklib->siteurl/$mklib->forumpath/".$image;
			}
			$image = "<img src=\"$image\" border=\"0\" alt=\"\" />";
			$message = str_replace($code, $image, $message);
		}
		return $message;
	}
	function popup_pm($m1, $m2, $m3, $m4)
 	{
		global $DB, $mklib, $mkportals;

		$u1 = "$mklib->siteurl/$mklib->forumpath/private.php";

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
		global $DB;

		$DB->query( "SELECT  gid, title FROM " . TABLE_PREFIX . "usergroups ORDER BY `gid`");
		while( $row = $DB->fetch_row() ) {
			if($row['gid'] == 4) {
				continue;
			}
			$g_id= $row['gid'];
			$g_title = $row['title'];
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
		$DB->query( "SELECT  gid, title FROM " . TABLE_PREFIX . "usergroups ORDER BY `gid`");
		while( $row = $DB->fetch_row() ) {
			if($row['gid'] == 4) {
				continue;
			}
			$g_id = $row['gid'];
			$group[$g_id][id] = $row['gid'];
			$group[$g_id][title] = $row['title'];
		}
		return $group;
	}
	//ad_perms
	function update_groupperms($g_id)
	{
		global $DB;

		$query = $DB->query( "SELECT title FROM " . TABLE_PREFIX . "usergroups WHERE gid = '$g_id'");
		$row = $DB->fetch_row($query);
		$g_title = $row['title'];
		return $row['title'];

	}

	//ad_poll
	function get_poll_list()
	{
		global $mklib, $DB;

		$poll_active = $mklib->config['poll_active'];
		$DB->query("SELECT  pid, question FROM " . TABLE_PREFIX . "polls ORDER BY pid DESC LIMIT 30");

        if ($DB->get_num_rows()) {
    		while( $poll = $DB->fetch_row() ) {
    			$id = $poll['pid'];
    			$title = $poll['question'];
    			$selected = "";
    			if($id == $poll_active) {
    				$selected = "selected=\"selected\"";
    			}
    			$cselect.= "<option value=\"$id\" $selected>$title</option>\n";
    		}
        }
        else {
            $cselect.= "<option value=\"0\"></option>\n";
        }

		return $cselect;
	}

//blocks functions

	function forum_link($link)
	{
		global $mklib, $mkportals, $mybb;

		$idu = $mkportals->member['id'];

		switch($link) {
			case 'profile':
    			$out = "/{$mkportals->forum_url}/member.php?action=profile&amp;uid";
    		break;
			case 'cpaforum':
    			$out = "/{$mkportals->forum_url}/admin/index.php";
    		break;
			case 'cpapers':
    			$out = "/{$mkportals->forum_url}/usercp.php";
    		break;
			case 'pm':
    			$out = "/{$mkportals->forum_url}/private.php?";
    		break;
			case 'forumsearch':
    			$out = "/{$mkportals->forum_url}/search.php";
    		break;
			case 'logout':
				//$out = "{$mkportals->forum_url}/member.php?action=logout&amp;uid=".$idu."&amp;sid=".$mkportals->member['session_id']; //MyBB < 1.2.10
				$out = "/{$mkportals->forum_url}/member.php?action=logout&amp;logoutkey={$mybb->user['logoutkey']}"; //MyBB >= 1.2.10
    		break;
			case 'postlink':
    			$out = "/{$mkportals->forum_url}/member.php";
    		break;
			case 'postlink2':
    			$out = "";
    		break;
			case 'register':
    			$out = "/{$mkportals->forum_url}/member.php?action=register";
    		break;
			case 'onlinelist':
    			$out = "/{$mkportals->forum_url}/online.php";
    		break;
			case 'login_extra':
    			$out = "				
				<tr>
					<td>
				    <input type=\"hidden\" name=\"action\" value=\"do_login\" />
				    <input type=\"hidden\" name=\"url\" value=\"{$mklib->siteurl}\" />
				  </td>
				</tr>";
    		break;
    		case 'login_user':
    			$out = "username";
    		break;
			case 'login_passw':
    			$out = "password";
    		break;
			case 'calendar_event':
    			$out = "/$mkportals->forum_url/calendar.php?action=dayview";
    		break;
			default:
    			$out = "n/a";
    		break;
    		}

		return $out;

	}

	function get_poll_active($post_id)
 	{
		global $DB, $mklib, $mkportals;
		$DB->query("SELECT question, options, votes, numvotes FROM " . TABLE_PREFIX . "polls WHERE pid = $post_id");
		$result = $DB->fetch_row();
		if ( ! $result['question'] ) {
            return "";
        }
		$question = $result['question'];
		$choise = explode("||~|~||", $result['options']);
		$votes = explode("||~|~||", $result['votes']);
		$total_votes = $result['numvotes'];

		$DB->query("SELECT tid FROM " . TABLE_PREFIX . "threads WHERE poll = $post_id");
		$result = $DB->fetch_row();
		$poll_id = $result['tid'];
		$out = "
				<tr>
				  <td class=\"tdblock\">
				  <a href=\"/$mkportals->forum_url/showthread.php?tid=$poll_id\">$question</a>
				  </td>
				</tr>
            ";

		$ind = 0;
		foreach ($choise as $entry) {
			$percent = $votes[$ind] == 0 ? 0 : $votes[$ind] / $total_votes * 100;
            $percent = sprintf( '%.2f' , $percent );
            $width   = $percent > 0 ? floor( round( $percent ) * ( 122 / 100 ) ) : 0;
			$out .= "
				<tr>
				  <td class=\"tdblock3\">
				  {$entry}
				  </td>
				</tr>
				<tr>
				  <td align=\"left\">
				  <img src=\"$mklib->images/bar-start.gif\" border=\"0\" width=\"4\" height=\"11\" alt=\"\" /><img src=\"$mklib->images/bar.gif\" border=\"0\" width=\"$width\" height=\"11\" alt=\"\" /><img src=\"$mklib->images/bar-end.gif\" border=\"0\" width=\"4\" height=\"11\" alt=\"\" />
				  </td>
				</tr>

                ";
			++$ind;
		}
		$out .= "
				<tr>
				  <td class=\"tdblock\">
				  <span class=\"mktxtcontr\">$total_votes</span> {$mklib->lang['poll_totalvotes']}
				  </td>
				</tr>
				<tr>
				  <td class=\"tdglobal\">
				  <a href=\"/$mkportals->forum_url/showthread.php?tid=$poll_id\">{$mklib->lang['poll_go']}</a>
				  </td>
				</tr>
            ";
		return $out;
	}

	function get_avatar()
 	{
		global $mkportals, $DB, $mklib;

		switch($mkportals->member['avatartype']) {
							case 'gallery':
								$avatar = "$mkportals->forum_url/".$mkportals->member['avatar'];
    							$avatar = "<img src=\"$avatar\" border=\"0\" alt=\"\" />";
    						break;
							case 'upload':
    							$dim = explode("|", $mkportals->member['avatardimensions']);
								$avatar = "$mkportals->forum_url/".$mkportals->member['avatar'];
								$avatar = "<img src=\"$avatar\" width=\"$dim[0]\" height=\"$dim[1]\" border=\"0\" alt=\"\" />";
    						break;					
							case 'remote':
    							$avatar = "<img src=\"{$mkportals->member['avatar']}\" border=\"0\" alt=\"\" />";
    						break;
							default:
							$avatar = "";
    						break;
						}

		return $avatar;
	}

	function get_forumnav()
 	{
		global $mklib, $mkportals, $Skin;

		$out = "<tr><td class=\"tdblock\">";
		$out .= $Skin->row_link_block(!$mklib->config['noicons'] ? "<img class=\"mkicon\" src=\"$mklib->images/atb_npost.gif\" style=\"vertical-align: middle\" align=\"left\" alt=\"{$mklib->lang['m_newpost']}\" />" : "", "href=\"/$mkportals->forum_url/search.php?action=getnew\"", $mklib->lang['m_newpost']);
		$out .= "</td></tr><tr><td class=\"tdblock\">";
		
		$out .= $Skin->row_link_block(!$mklib->config['noicons'] ? "<img class=\"mkicon\" src=\"$mklib->images/atb_members.gif\" style=\"vertical-align: middle\" align=\"left\" alt=\"{$mklib->lang['m_users']}\" />" : "", "href=\"/$mkportals->forum_url/memberlist.php\"", $mklib->lang['m_users']);
		$out .= "</td></tr><tr><td class=\"tdblock\">";

		$out .= $Skin->row_link_block(!$mklib->config['noicons'] ? "<img class=\"mkicon\" src=\"$mklib->images/atb_calendario.gif\" style=\"vertical-align: middle\" align=\"left\" alt=\"{$mklib->lang['m_calendar']}\" />" : "", "href=\"/$mkportals->forum_url/calendar.php\"", $mklib->lang['m_calendar']);
		$out .= "</td></tr><tr><td class=\"tdblock\">";		

		$out .= $Skin->row_link_block(!$mklib->config['noicons'] ? "<img class=\"mkicon\" src=\"$mklib->images/atb_help.gif\" style=\"vertical-align: middle\" align=\"left\" alt=\"{$mklib->lang['m_help']}\" />" : "", "href=\"/$mkportals->forum_url/misc.php?action=help\"", $mklib->lang['m_help']);
		$out .= "</td></tr>";

		return $out;

	}
	function get_site_stat()
 	{
		global $DB;


		$sql = "SELECT COUNT(uid) AS total
				FROM " . TABLE_PREFIX . "users";

		$DB->query($sql);
		$row = $DB->fetch_row();

		$stat['members'] = $row['total'];

		$sql = "SELECT uid, username
				FROM " . TABLE_PREFIX . "users
				ORDER BY uid DESC
				LIMIT 1";
		$DB->query($sql);
		$row = $DB->fetch_row();

		$stat['last_member'] = $row['uid'];
		$stat['last_member_name'] = $row['username'];

		$sql = "SELECT COUNT(tid) AS total
				FROM " . TABLE_PREFIX . "threads";
		$DB->query($sql);
		$row = $DB->fetch_row();
		$stat['topics'] = $row['total'];

		$sql = "SELECT COUNT(pid) AS total
				FROM " . TABLE_PREFIX . "posts";
		$DB->query($sql);
		$row = $DB->fetch_row();
		$stat['total_posts'] = $row['total'];


		$stat['replies'] = $stat['total_posts'] - $stat['topics'];
		return $stat;


	}


	function get_onlineblock()
 	{
		global $DB, $mkportals;

	$time = (time() - 900);

		$DB->query("SELECT s.sid, s.ip, s.uid, s.time, s.location, u.username, u.invisible, u.usergroup, u.displaygroup, g.namestyle
		FROM ".TABLE_PREFIX."sessions s
		LEFT JOIN ".TABLE_PREFIX."users u ON (s.uid=u.uid)
		LEFT JOIN ".TABLE_PREFIX."usergroups g ON (g.gid=u.usergroup)
		WHERE s.time>'$time'
		ORDER BY u.username ASC, s.time DESC
		");

			$active = array( 'guests' => 0, 'anon' => 0, 'members' => 0, 'names' => "");
			$cached = array ();

			while ($result = $DB->fetch_row() ) {
				if ($result['uid'] == 0) {
					$active['guests']++;
				} else {
						if ($cached[ $result['uid'] ]) {
							continue;
						}
						$cached[ $result['uid'] ] = 1;
						$result['username'] = str_replace("{username}", $result['username'], $result['namestyle']);
						if ($result['invisible'] != "no") {
							if ( $mkportals->member['mgroup'] == "4") {
								$active['names'] .= "<a href=\"/{$mkportals->forum_url}/member.php?action=profile&amp;uid={$result['uid']}\">{$result['username']}</a>*, ";
								$active['anon']++;
							} else {
								$active['anon']++;
							}
						} else {
							$active['members']++;
							$active['names'] .= "<a href=\"/{$mkportals->forum_url}/member.php?action=profile&amp;uid={$result['uid']}\">{$result['username']}</a>, ";
						}

				}
			}
			$active['names'] = preg_replace( "/,\s+$/", "" , $active['names'] );
			$utenti_in = "{$mklib->lang['b_tusers']} ";
			$utenti_in .= ($active['members'] + $active['guests'] + $active['anon'] );
			$utenti_in .= " ({$active['members']} {$mklib->lang['b_rusers']} {$active['guests']} {$mklib->lang['b_guests']} {$mklib->lang['b_and']} {$active['anon']} {$mklib->lang['b_anons']})<br />";
			$utenti_in .= "{$mklib->lang['b_vusers']}  {$active['members']} {$active['names']}";

		return array($active['members'], $active['anon'], $active['guests'], $active['names']);


	}


	function get_onlinehome($languest)
 	{

		global $DB, $mkportals;

	$content = "";
	$inter = ",";

	$time = (time() - 900);
	$DB->query("SELECT s.sid, s.ip, s.uid, s.time, s.location, u.username, u.invisible, u.usergroup, u.displaygroup, g.namestyle
		FROM ".TABLE_PREFIX."sessions s
		LEFT JOIN ".TABLE_PREFIX."users u ON (s.uid=u.uid)
		LEFT JOIN ".TABLE_PREFIX."usergroups g ON (g.gid=u.usergroup)
		WHERE s.time>'$time'
		ORDER BY u.username ASC, s.time DESC
		");
		$online = array();
		$cached  = array();
		$online['members'] = 0;
		$online['guests'] = 0;
		$online['anon'] = 0;

		while ($result = $DB->fetch_row() ) {
				$result['location'] =  $this->decodeloc($result['location']);
				if ($cached[ $result['uid'] ] && $result['uid'] != 0) {
							continue;
				}
				$cached[ $result['uid'] ] = 1;
				if ($result['uid'] == 0) {
					$online['guests']++;
					switch($result['location']) {
							case 'portale':
    							$online['portale'] .= "$languest{$inter} \n";
    						break;
							case 'blog':
    							$online['blog'] .= "$languest{$inter} \n";
    						break;
							case 'gallery':
    							$online['gallery'] .= "$languest{$inter} \n";
    						break;
							case 'urlobox':
    							$online['urlobox'] .= "$languest{$inter} \n";
    						break;
							case 'downloads':
    							$online['downloads'] .= "$languest{$inter} \n";
    						break;
							case 'news':
    							$online['news'] .= "$languest{$inter} \n";
    						break;
							case 'chat':
    							$online['chat'] .= "$languest{$inter} \n";
    						break;
							case 'topsite':
    							$online['topsite'] .= "$languest{$inter} \n";
    						break;
							case 'reviews':
    							$online['reviews'] .= "$languest{$inter} \n";
    						break;
							default:
							$online['forum'] .= "$languest{$inter} \n";
    						break;
					}
				} else if ($result['invisible'] != "no") {
						$result['username'] = str_replace("{username}", $result['username'], $result['namestyle']);
						if ( $mkportals->member['mgroup'] == "4") {
							switch($result['location']) {
							case 'portale':
    							$online['portale'] .= "<a href=\"/{$mkportals->forum_url}/member.php?action=profile&amp;uid={$result['uid']}\">{$result['username']}</a>*{$inter} \n";
    						break;
							case 'blog':
    							$online['blog'] .= "<a href=\"/{$mkportals->forum_url}/member.php?action=profile&amp;uid={$result['uid']}\">{$result['username']}</a>*{$inter} \n";
    						break;
							case 'gallery':
    							$online['gallery'] .= "<a href=\"/{$mkportals->forum_url}/member.php?action=profile&amp;uid={$result['uid']}\">{$result['username']}</a>*{$inter} \n";
    						break;
							case 'urlobox':
    							$online['urlobox'] .= "<a href=\"/{$mkportals->forum_url}/member.php?action=profile&amp;uid={$result['uid']}\">{$result['username']}</a>*{$inter} \n";
    						break;
							case 'downloads':
    							$online['downloads'] .= "<a href=\"/{$mkportals->forum_url}/member.php?action=profile&amp;uid={$result['uid']}\">{$result['username']}</a>*{$inter} \n";
    						break;
							case 'news':
    							$online['news'] .= "<a href=\"/{$mkportals->forum_url}/member.php?action=profile&amp;uid={$result['uid']}\">{$result['username']}</a>*{$inter} \n";
    						break;
							case 'chat':
    							$online['chat'] .= "<a href=\"/{$mkportals->forum_url}/member.php?action=profile&amp;uid={$result['uid']}\">{$result['username']}</a>*{$inter} \n";
    						break;
							case 'topsite':
    							$online['topsite'] .= "<a href=\"/{$mkportals->forum_url}/member.php?action=profile&amp;uid={$result['uid']}\">{$result['username']}</a>*{$inter} \n";
    						break;
							case 'reviews':
    							$online['reviews'] .= "<a href=\"/{$mkportals->forum_url}/member.php?action=profile&amp;uid={$result['uid']}\">{$result['username']}</a>*{$inter} \n";
    						break;
							default:
							$online['forum'] .= "<a href=\"/{$mkportals->forum_url}/member.php?action=profile&amp;uid={$result['uid']}\">{$result['username']}</a>*{$inter} \n";
    						break;
							}
							$online['anon']++;
                    	} else {
                        	$online['anon']++;
                    	}
				} else  {
					 $online['members']++;
						$result['username'] = str_replace("{username}", $result['username'], $result['namestyle']);
						switch($result['location']) {
							case 'portale':
    							$online['portale'] .= "<a href=\"/{$mkportals->forum_url}/member.php?action=profile&amp;uid={$result['uid']}\">{$result['username']}</a>{$inter} \n";
    						break;
							case 'blog':
    							$online['blog'] .= "<a href=\"/{$mkportals->forum_url}/member.php?action=profile&amp;uid={$result['uid']}\">{$result['username']}</a>{$inter} \n";
    						break;
							case 'gallery':
    							$online['gallery'] .= "<a href=\"/{$mkportals->forum_url}/member.php?action=profile&amp;uid={$result['uid']}\">{$result['username']}</a>{$inter} \n";
    						break;
							case 'urlobox':
    							$online['urlobox'] .= "<a href=\"/{$mkportals->forum_url}/member.php?action=profile&amp;uid={$result['uid']}\">{$result['username']}</a>{$inter} \n";
    						break;
							case 'downloads':
    							$online['downloads'] .= "<a href=\"/{$mkportals->forum_url}/member.php?action=profile&amp;uid={$result['uid']}\">{$result['username']}</a>{$inter} \n";
    						break;
							case 'news':
    							$online['news'] .= "<a href=\"/{$mkportals->forum_url}/member.php?action=profile&amp;uid={$result['uid']}\">{$result['username']}</a>{$inter} \n";
    						break;
							case 'chat':
    							$online['chat'] .= "<a href=\"/{$mkportals->forum_url}/member.php?action=profile&amp;uid={$result['uid']}\">{$result['username']}</a>{$inter} \n";
    						break;
							case 'topsite':
    							$online['topsite'] .= "<a href=\"/{$mkportals->forum_url}/member.php?action=profile&amp;uid={$result['uid']}\">{$result['username']}</a>{$inter} \n";
    						break;
							case 'reviews':
    							$online['reviews'] .= "<a href=\"/{$mkportals->forum_url}/member.php?action=profile&amp;uid={$result['uid']}\">{$result['username']}</a>{$inter} \n";
    						break;
							default:
							$online['forum'] .= "<a href=\"/{$mkportals->forum_url}/member.php?action=profile&amp;uid={$result['uid']}\">{$result['username']}</a>{$inter} \n";
    						break;
						}

                    }

		}



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

        $online['total']    = $online['members'] + $online['guests'] + $online['anon'];
        $online['visitors'] = $online['guests']  + $online['anon'];

		return array($online['members'], $online['anon'], $online['guests'], $online['portale'], $online['blog'], $online['gallery'], $online['urlobox'], $online['downloads'], $online['news'], $online['chat'], $online['topsite'], $online['reviews'], $online['forum']);

	}

	function get_last_posts($by, $sdate)
 	{
		global $DB, $mklib, $mkportals;
		$limit = 5;
		$taglio = 17;


$DB->query("SELECT * FROM " . TABLE_PREFIX . "forums WHERE active != 'no'");
while($forum = $DB->fetch_row())
{
	$fcache[$forum['pid']][$forum['disporder']][$forum['fid']] = $forum;

}
$forumpermissions = forum_permissions();
$unviewable = get_unviewable_forums();
if($unviewable)
{
	$unviewwhere = "WHERE t.fid NOT IN ($unviewable)";
}

$DB->query("SELECT t.tid, t.subject, t.replies, t.lastposteruid, t.lastpost, t.lastposter, t.views, t.fid, m.uid, m.username, m.usergroup, m.displaygroup
		FROM ".TABLE_PREFIX."threads t
		LEFT JOIN ".TABLE_PREFIX."users m on (m.uid=t.lastposteruid)
		$unviewwhere
		ORDER BY t.lastpost DESC
		LIMIT $limit");

		while ( $post = $DB->fetch_row() ) {
		$title = strip_tags($post['subject']);
		$title = str_replace( "&#33;" , "!" ,$title );
		$title = str_replace( "&quot;", "\"", $title );
			if (strlen($title) > $taglio) {
				$title = substr( $title,0,($taglio - 3) ) . "...";
				$title = preg_replace( '/&(#(\d+;?)?)?(\.\.\.)?$/', '...',$title );
			}
		$mname = format_name($post['username'], $post['usergroup'], $post['displaygroup']);
 		$date  = $mklib->create_date($post['lastpost']);
		$tid = $post['tid'];

		$mid = $post['lastposteruid'];

		$content .= "
				<tr>
				  <td width=\"100%\" class=\"tdblock\">
				  <a class=\"uno\" href=\"/$mkportals->forum_url/showthread.php?tid=$tid&amp;action=lastpost\">$title</a>
				  </td>
				</tr>
				<tr>
				  <td class=\"tdglobal\">
				  $by: <a class=\"uno\" href=\"/$mkportals->forum_url/member.php?action=profile&amp;uid=$mid\">$mname</a><br /> $sdate: $date
				  </td>
				</tr>
		";
 		}

		return $content;


	}

	function get_forum_list()
	{
		global $mklib, $DB;

		$prefix = DBPREFIX;
		$forum_active = $mklib->config['forum_active'];

		$DB->query("SELECT fid AS id, name FROM " . TABLE_PREFIX . "forums WHERE type = 'f' AND active = 'yes' ORDER BY fid");

		while( $board = $DB->fetch_row() ) {
			$cselect[] = $board;
		}

		return $cselect;
	}

	function get_board_news()
 	{
		global $DB, $mklib, $mkportals;

		 require_once "$mkportals->forum_url/inc/class_parser.php";
		$parser = new postParser;

		$parser_options = array(
		"allow_html" => "no",
		"allow_mycode" =>"yes",
		"allow_smilies" => "yes",
		"allow_imgcode" => "yes",
		"me_username" => "yes"
		);
		$limit = $mklib->config['bnews_block'];
		$news_words= $mklib->config['bnews_words'];
		//$taglio = 17;
		//$db_prefix = DBPREFIX;
		$forum_active = unserialize($mklib->config['forum_active']);
		if(!$forum_active) {
				return "";
		}

        	$unviewable = get_unviewable_forums();
        	if($unviewable)
        	{
            		$unviewwhere = " AND t.fid NOT IN ({$unviewable})";
        	}

        	$DB->query("
        	SELECT p.*, m.username, m.usergroup, m.displaygroup, m.avatar, m.avatardimensions, m.avatartype, t.replies
        	FROM ".TABLE_PREFIX."posts AS p
        	LEFT JOIN ".TABLE_PREFIX."users m on (m.uid=p.uid)
        	LEFT JOIN ".TABLE_PREFIX."threads t on (p.tid=t.tid)
        	WHERE p.fid IN (".implode(',', $forum_active ).") AND p.visible=1 AND t.closed NOT LIKE 'moved|%' AND t.visible=1 {$unviewwhere} AND t.firstpost = p.pid
        	GROUP BY p.tid
        	ORDER BY t.tid DESC
        	LIMIT $limit
        	");



		while ( $post = $DB->fetch_row() ) {
		$avatar = "";
  		$title = strip_tags($post['subject']);
		$title = str_replace( "&#33;" , "!" ,$title );
		$title = str_replace( "&quot;", "\"", $title );

 		$date  = $mklib->create_date($post['dateline']);
		$tid = $post['tid'];
		$pid = $post['pid'];
		$mid = $post['uid'];
		$mname = format_name($post['username'], $post['usergroup'], $post['displaygroup']);;
		$testo = $post['message'];
		if ($news_words) {
			$testo = substr ($testo, 0, $news_words);
			$testo .= " ...";
   		}
		$testo = $parser->parse_message($testo, $parser_options);
		$testo = str_replace("img src=\"images/smilies", "img src=\"$mkportals->forum_url/images/smilies", $testo); 
		$fname = $post['f_title'];
		$post['icon']= "exclamation.gif";
		$numreplies = $post['replies']." ".$mklib->lang['replies'];

		$icona = $mkportals->forum_url."/images/icons/".$post['icon'];
		switch($post['avatartype']) {
							case 'gallery':
								$avatar = "$mkportals->forum_url/".$post['avatar'];
    							$avatar = "<img src=\"$avatar\" border=\"0\" alt=\"\" />";
    						break;				
							case 'remote':
    							$avatar = "<img src=\"{$post['avatar']}\" border=\"0\" alt=\"\" />";
    						break;
							default:
							$dim = explode("|", $post['avatardimensions']);
								$avatar = "$mkportals->forum_url/".$post['avatar'];
								$avatar = "<img src=\"$avatar\" width=\"$dim[0]\" height=\"$dim[1]\" border=\"0\" alt=\"\" />";
    						break;
		}
		if (!$post['avatar'])  {
			$avatar = "<img hspace=\"0\" src=\"$icona\" align=\"bottom\" border=\"0\" alt=\"\" />";
		}
		
		$out .= "
				    <table class=\"tabnews\" cellspacing=\"2\" cellpadding=\"2\" width=\"100%\">
				      <tbody>
				      <tr>
					<td class=\"tdblock\" align=\"center\" width=\"5%\">
					$avatar
					</td>
					<td class=\"tdblock\" valign=\"middle\" align=\"center\" width=\"95%\">
					<b>$fname<br /><a href=\"/$mkportals->forum_url/showthread.php?tid=$tid&amp;pid=$pid#pid$pid\">$title</a></b>
					<br /><div class=\"mkalign2\" style='font-style: italic; font-weight: normal;'><a href=\"/$mkportals->forum_url/showthread.php?tid=$tid&amp;pid=$pid#pid$pid\">$numreplies</a>&nbsp;</div>
					</td>
				      </tr>
				      <tr>
					<td colspan=\"2\"><br />
					$testo
					</td>
				      </tr>
				      <tr>
					<td class=\"mkalign2\" colspan=\"2\">
					<br /><i>{$mklib->lang['from']}<b> <a href=\"/$mkportals->forum_url/member.php?action=profile&amp;uid=$mid\">$mname</a></b>, $date <a href=\"/$mkportals->forum_url/showthread.php?tid=$tid&amp;pid=$pid#pid$pid\"> [ {$mklib->lang['readall']} ]</a></i>
					</td>
				      </tr>
				    </tbody>
				  </table>
		";
 		}
		return $out;
	}
	
	function langselect() {
		global $DB, $mklib, $mkportals;
$dir = @opendir("$mkportals->forum_url/inc/languages");
		while($lang = readdir($dir))
		{
			$ext = strtolower(get_extension($lang));
			if($lang != "." && $lang != ".." && $ext == "php")
			{
				$lname = str_replace(".".$ext, "", $lang);
				
					$languages[] = $lname;
				
			}
		}
		@ksort($languages);
		closedir($dir);;
		if (!$mkportals->member['id']) {
			return;
		}		
		
		$content = "<form name=\"mklanglist\" action=\"post\">\n <select name=\"seleclang\" class=\"bgselect\" onchange=\"document.location.href=mklanglist.seleclang.options[this.selectedIndex].value\">\n";
		
		foreach ($languages as $value) {
			$selected = ""; 
			if ($mkportals->member['mk_lang'] == $value) {
				$selected = "selected=\"selected\"";
			}
			$content .= "\n<option value=\"$mklib->siteurl/index.php?langid={$value}\" $selected >$value</option>";		
			
		}
	
			
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
		$idu = $mkportals->member['id'];
		if (!$mkportals->member['id']) {
				return;
		}
		$DB->query("UPDATE " . TABLE_PREFIX . "users SET language ='$lang' WHERE uid = '$idu'");
			$DB->close_db();
	 		Header("Location: $mkportals->forum_url/index.php");
			exit;
    }

	function skinselect()
 	{
		global $DB, $mklib, $mkportals, $sc;

		if (!$mkportals->member['id']) {
			return;
		}
		$templateslist .= "<form name=\"skinlist\" action=\"post\">\n <select name=\"selectskin\" class=\"bgselect\" onchange=\"document.location.href=skinlist.selectskin.options[this.selectedIndex].value\">\n";
		$DB->query("SELECT  tid, name FROM " . TABLE_PREFIX . "themes");
		while ( $r = $DB->fetch_row() )
		{
			if ($r['tid'] == 1) {
				continue;
			}
			$selected = "";
			if ($mkportals->member['theme'] == $r['tid']) {
				$selected = "selected=\"selected\"";
			}
			if (strlen($r['title']) > 14 ) {
				$r['title'] = substr($r['title'], 0, 14);
			}
			$templateslist .= "\n<option value=\"$mklib->siteurl/index.php?skinid={$r['tid']}\" $selected >{$r['name']}</option>";

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
			$idu = $mkportals->member['id'];
		if (!$mkportals->member['id']) {
				return;
		}
			$DB->query("UPDATE " . TABLE_PREFIX . "users SET style ='$skinid' WHERE uid = '$idu'");
			$DB->close_db();
	 		Header("Location: $mkportals->forum_url/index.php");
			exit;
	}

	function calendar_birth($chosen_month, $chosen_year)
 	{
		global $mkportals, $DB, $mklib, $modSettings;

		$birthdays = array();
		

		$DB->query("SELECT username, birthday FROM " . TABLE_PREFIX . "users WHERE birthday LIKE '%-$chosen_month-%'");
    	while ($row = $DB->fetch_row()) {
			$user = explode("-", $row['birthday']);
       	 	$birthdays[ $user[0] ]++;
        	if ($birthdays[ $user[0] ] < 10) {
            	$tool_birthdays[$user[0]] .=  $row['username']." (".($chosen_year - $user['2']).")&nbsp;";
        	}
        	else if ($birthdays[ $user[0] ] == 10) {
            $tool_birthdays[$user[0]] .=  "...";
        	}
    	}

		return array($birthdays, $tool_birthdays);
	}
	function calendar_events($chosen_month, $chosen_year)
 	{
		global $mkportals, $DB, $mklib;

    	$DB->query("SELECT eid, subject, date FROM " . TABLE_PREFIX . "events WHERE date LIKE '%-$chosen_month-$chosen_year'");
		while ( $event = $DB->fetch_row() ) {
			$row1 = explode("-", $event['date']);
			$event['mday'] = $row1[0];
       	 	$events[ $event['mday'] ][] = $event;
       	 	$entry = substr($event['subject'], 0, 20);
     	 	if ( strlen($event['subject']) > 20 ) {
       	     	$entry .= "...";
       	 	}
       	 	$tool_events[$event['mday']] .= $entry."<br />";
    	}

		return array($events, $tool_events, $tool_idevents);
	}
	function decodeloc($loc)
	{
		global $mkportals, $FORUM_PATH;
		$location = "portale";
		if (strpos($loc, "=blog")) {
			$location = "blog";
		}
		if (strpos($loc, "=gallery")) {
			$location = "gallery";
		}
		if (strpos($loc, "=urlobox")) {
			$location = "urlobox";
		}
		if (strpos($loc, "=downloads")) {
			$location = "downloads";
		}
		if (strpos($loc, "=news")) {
			$location = "news";
		}
		if (strpos($loc, "=topsite")) {
			$location = "topsite";
		}
		if (strpos($loc, "=chat")) {
			$location = "chat";
		}
		if (strpos($loc, "=reviews")) {
			$location = "reviews";
		}
		if (strpos($loc, $FORUM_PATH)) {
			$location = "";
		}
		return $location;
	}

	function import_css()
	{
		global $mkportals, $DB, $mklib;
		$DB->query("SELECT css FROM " . TABLE_PREFIX . "themes WHERE tid = '{$mkportals->member['theme']}'");
		$r = $DB->fetch_row();
		$css2 = $r['css'];
		unset ($r);

		$pos1 = strpos($css2, "link rel");
		if ($pos1) {
			$pos1 = strpos($css2, "href=");
			$pos1 = ($pos1 +6);
			$pos2 = strpos($css2, ".css");
			$pos2 = ($pos2 +4);
			$mksub = substr($css2, ($pos1), ($pos2 - $pos1));
			$mksub = "$mkportals->forum_url/".$mksub;
			$fh = @fopen($mksub, "r");
    		if ($fh) {
       			 $css2 = fread($fh, filesize($mksub));
        		@fclose($fh);
			}
		}
		$css = "$mklib->template/style.css";
		$fh = @fopen($css, "r");
    	if ($fh) {
        	$css = fread($fh, filesize($css));
        	@fclose($fh);
		}

		//importing body
		$pos = strpos($css2, "body");
		$pos2 = strpos($css2, "}", $pos);
			if ($pos) {
				$mkpsubs = substr($css2, $pos, ($pos2 - ($pos -1)));
				$mkpsubs = preg_replace( "/margin(.*?\;)/mi", "", $mkpsubs);
				$css = preg_replace( "`(\.importbody(.*?\}))`is", $mkpsubs, $css);
			}

/*
		//importing logostrip
		$sflogo =  $mkportals->forum_url."/images/misc/sf_logo.jpg";
		if (is_file("$sflogo") ) {
			$mkpsubs = "#logostrip {background-image: url(images/misc/sf_logo.jpg); text-align: left;}";
		} else {
			$pos = strpos($css2, ".page");
			$pos2 = strpos($css2, "}", $pos);
		$mkpsubs = substr($css2, $pos, ($pos2 - ($pos -1)));
		}
		$css = preg_replace( "`(\#importlogostrip(.*?\}))`is", $mkpsubs, $css);
*/		

		//importing logostrip
		$pos = strpos($css2, "#container");
		$pos2 = strpos($css2, "}", $pos);
			if ($pos) {
				$mkpsubs = substr($css2, $pos, ($pos2 - ($pos -1)));
				$css = preg_replace( "`(\#importlogostrip(.*?\}))`is", $mkpsubs, $css);
			}
/*
		//importing main table bg (if different than body bg)
		$pos = strpos($css2, "#container");
		$pos2 = strpos($css2, "}", $pos);
			if ($pos) {
				$mkpsubs = substr($css2, $pos, ($pos2 - ($pos -1)));
				$css = preg_replace( "`(\.importmain(.*?\}))`is", $mkpsubs, $css);
			}
*/
		//importing light background
		$pos = strpos($css2, ".trow1");
		$pos2 = strpos($css2, "}", $pos);
			if ($pos) {
				$mkpsubs = substr($css2, $pos, ($pos2 - ($pos -1)));
				$css = preg_replace( "`(\.importlightback(.*?\}))`is", $mkpsubs, $css);
			}

		//importing medium background
		$pos = strpos($css2, ".trow2");
		$pos2 = strpos($css2, "}", $pos);
			if ($pos) {
				$mkpsubs = substr($css2, $pos, ($pos2 - ($pos -1)));
				$css = preg_replace( "`(\.importmediumback(.*?\}))`is", $mkpsubs, $css);
			}

		//importing dark background
		$pos = strpos($css2, ".thead");
		$pos2 = strpos($css2, "}", $pos);
			if ($pos) {
				$mkpsubs = substr($css2, $pos, ($pos2 - ($pos -1)));
				$css = preg_replace( "`(\.importdarkback(.*?\}))`is", $mkpsubs, $css);
			}

		//importing module table headers
		$pos = strpos($css2, ".tcat");
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
				$mkpsubs = preg_replace( "/margin(.*?\;)/mi", "", $mkpsubs);
				$mkpsubs = preg_replace( "/width(.*?\;)/mi", "", $mkpsubs);
				$css = preg_replace( "`(\.importborders(.*?\}))`is", $mkpsubs, $css);
			}
/*
		//importing form styles
		$pos = strpos($css2, "input.textbox");
		$pos2 = strpos($css2, "}", $pos);
			if ($pos) {
				$mkpsubs = substr($css2, $pos, ($pos2 - ($pos -1)));
				$css = preg_replace( "`(\.importforms(.*?\}))`is", $mkpsubs, $css);
			}
*/
		//importing table font formatting
		$pos = strpos($css2, "td, th, p, li");
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
		$css = str_replace("url(", "url($mkportals->forum_url/", $css);
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
		$DB->query("SELECT email FROM " . TABLE_PREFIX . "users WHERE uid = '$iduser'");
		$row = $DB->fetch_row();
		$dest = $row['email'];

		mail($dest, $subject, $message,  $headers);
	}
	function admin_mail($subject, $message)
 	{
		global $DB, $mklib;

		$headers = "From: webmaster@" . $mklib->sitename . "\r\n" . "Reply-To: webmaster@" . $mklib->sitename . "\r\n" . "X-Mailer: MKportal Mail";

		$dest = "";
		$DB->query("SELECT email FROM " . TABLE_PREFIX . "users WHERE  usergroup  = '4'");
		while ( $row = $DB->fetch_row() ) {
			$dest .= $row['email'].", ";
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
