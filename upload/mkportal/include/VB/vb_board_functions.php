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
        	global $mkportals, $DB, $mklib;
        
//        	$idu = $mkportals->member['session_id'];
        
        	switch($loc) {
                            	case 'blog':
                                $location= $mklib->siteurl."/index.php?ind=blog";
                            break;
                            	case 'gallery':
                                $location= $mklib->siteurl."/index.php?ind=gallery";
                            break;
                            	case 'urlobox':
                                $location= $mklib->siteurl."/index.php?ind=urlobox";
                            break;
                            	case 'downloads':
                                $location= $mklib->siteurl."/index.php?ind=downloads";
                            break;
                            	case 'news':
                                $location= $mklib->siteurl."/index.php?ind=news";
                            break;
                            	case 'topsite':
                                $location= $mklib->siteurl."/index.php?ind=topsite";
                            break;
                            	case 'chat':
                                $location= $mklib->siteurl."/index.php?ind=chat";
                            break;
                            	case 'reviews':
                                $location= $mklib->siteurl."/index.php?ind=reviews";
                            break;
                            default:
                            $location= $mklib->siteurl."/index.php";
                            break;
                        }

        	$DB->query("UPDATE " . TABLE_PREFIX . "session SET location ='$location' WHERE sessionhash = '{$mkportals->member['session_id']}'");
	}
	


	function get_active_users($loc) {

		global $DB, $mkportals, $mklib, $vbulletin;

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

		$DB->query("SELECT
			user.username, (user.options & 512) AS invisible, user.usergroupid,
			session.userid, session.inforum, session.lastactivity, session.location, groups.opentag, groups.closetag,
			IF(displaygroupid=0, user.usergroupid, displaygroupid) AS displaygroupid
		FROM " . TABLE_PREFIX . "session AS session
		LEFT JOIN " . TABLE_PREFIX . "user AS user ON(user.userid = session.userid)
		LEFT JOIN " . TABLE_PREFIX . "usergroup AS groups ON(groups.usergroupid = user.usergroupid)
		WHERE session.lastactivity > $time
		AND session.location LIKE '%$location%'
		ORDER BY username ASC
		");
			$cached = array();
			$active = array( 'guests' => 0, 'anon' => 0, 'members' => 0, 'names' => "");
			while ($result = $DB->fetch_row() ) {

				if ($result['userid'] == 0) {
					$active['guests']++;
				} else {
						if ($cached[ $result['userid'] ]) {
							continue;
						}
						$cached[ $result['userid'] ] = 1;
						if ($result['invisible'] == 512) {
							if ( $mkportals->member['mgroup'] == "6") {
								$active['names'] .= "<a href=\"/{$mkportals->forum_url}/member.php?u={$result['userid']}\">{$result['opentag']}{$result['username']}{$result['closetag']}</a>*, ";
								$active['anon']++;
							} else {
								$active['anon']++;
							}
						} else {
							$active['members']++;
							$active['names'] .= "<a href=\"/{$mkportals->forum_url}/member.php?u={$result['userid']}\">{$result['opentag']}{$result['username']}{$result['closetag']}</a>, ";
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
 		$DB->query("SELECT smilietext AS code, smiliepath AS image FROM " . TABLE_PREFIX . "smilie");
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

		$DB->query("SELECT smilietext, smiliepath FROM " . TABLE_PREFIX . "smilie");
		while ( $r = $DB->fetch_row() )
		{
			$code = stripslashes($r['smilietext']);
			$image = stripslashes($r['smiliepath']);
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

		$DB->query("UPDATE " . TABLE_PREFIX . "user SET  pmpopup='1' WHERE userid={$mkportals->member['id']}");


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

		$DB->query( "SELECT  usergroupid, title FROM " . TABLE_PREFIX . "usergroup ORDER BY `usergroupid`");
		while( $row = $DB->fetch_row() ) {
			if($row['usergroupid'] == 6) {
				continue;
			}
			$g_id= $row['usergroupid'];
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
		$DB->query( "SELECT usergroupid, title FROM " . TABLE_PREFIX . "usergroup ORDER BY `usergroupid`");
		while( $row = $DB->fetch_row() ) {
			if($row['usergroupid'] == 6) {
				continue;
			}
			$g_id = $row['usergroupid'];
			$group[$g_id][id] = $row['usergroupid'];
			$group[$g_id][title] = $row['title'];
		}
		return $group;
	}
	//ad_perms
	function update_groupperms($g_id)
	{
		global $DB;

		$query = $DB->query( "SELECT title FROM " . TABLE_PREFIX . "usergroup WHERE usergroupid = '$g_id'");
		$row = $DB->fetch_row($query);
		$g_title = $row['title'];
		return $row['title'];

	}

	//ad_poll
	function get_poll_list()
	{
		global $mklib, $DB;

		$poll_active = $mklib->config['poll_active'];
		$DB->query("SELECT  pollid, question FROM " . TABLE_PREFIX . "poll ORDER BY pollid DESC LIMIT 30");

        if ($DB->get_num_rows()) {
    		while( $poll = $DB->fetch_row() ) {
    			$id = $poll['pollid'];
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
		global $mklib, $mkportals, $vbulletin;

		$idu = $mkportals->member['id'];

		switch($link) {
			case 'profile':
    			$out = "/{$mkportals->forum_url}/member.php?u";
    		break;
			case 'cpaforum':
    			$out = "/{$mkportals->forum_url}/admincp/index.php";
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
    			$out = "/{$mkportals->forum_url}/login.php?do=logout&amp;logouthash=".$vbulletin->userinfo['logouthash'];
    		break;
			case 'postlink':
    			$out = "/{$mkportals->forum_url}/login.php";
    		break;
			case 'postlink2':
    			$out = "name=\"LOGIN\" onsubmit=\"md5hash(vb_login_password,vb_login_md5password,vb_login_md5password_utf)\"";
    		break;
			case 'register':
    			$out = "/{$mkportals->forum_url}/register.php";
    		break;
			case 'onlinelist':
    			$out = "/{$mkportals->forum_url}/online.php";
    		break;
			case 'login_extra':
    			$out = "				
				<tr>
                    <td class=\"tdblock mkalign1\" width=\"100%\" colspan=\"2\"><b>{$mklib->lang['auto_login']}</b>&nbsp;<input type=\"checkbox\" name=\"cookieuser\" value=\"1\"  style=\"margin:0px;\" />
					<script type=\"text/javascript\" src=\"/{$mkportals->forum_url}/clientscript/vbulletin_md5.js\"></script>
				    <input type=\"hidden\" name=\"s\" value=\"\" />
				    <input type=\"hidden\" name=\"do\" value=\"login\" />
				    <input type=\"hidden\" name=\"forceredirect\" value=\"1\" />
				    <input type=\"hidden\" name=\"vb_login_md5password\" />
				    <input type=\"hidden\" name=\"vb_login_md5password_utf\" />
				  </td>
				</tr>";
    		break;
    		case 'login_user':
    			$out = "vb_login_username";
    		break;
			case 'login_passw':
    			$out = "vb_login_password";
    		break;
			case 'calendar_event':
    			$out = "$mkportals->forum_url/calendar.php?s=";
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
		$DB->query("SELECT question, options, votes, voters FROM " . TABLE_PREFIX . "poll WHERE pollid = $post_id");
		$result = $DB->fetch_row();
		if ( ! $result['question'] ) {
            return "";
        }
		$question = $result['question'];
		$choise = explode("|||", $result['options']);
		$votes = explode("|||", $result['votes']);
		$total_votes = $result['voters'];

		$DB->query("SELECT threadid FROM " . TABLE_PREFIX . "thread WHERE pollid = $post_id");
		$result = $DB->fetch_row();
		$poll_id = $result['threadid'];
		$out = "
				<tr>
				  <td class=\"tdblock\">
				  <a href=\"$mkportals->forum_url/showthread.php?t=$poll_id\">$question</a>
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
				  <a href=\"$mkportals->forum_url/showthread.php?t=$poll_id\">{$mklib->lang['poll_go']}</a>
				  </td>
				</tr>
            ";
		return $out;
	}

	function get_avatar()
 	{
		global $mkportals, $DB, $mklib;

		require_once("$mkportals->forum_url/includes/functions_user.php");
		$avatar = fetch_avatar_url($mkportals->member['id']);
		$avatar = $avatar[0];
		if ($avatar) {
			if (!strpos($avatar, "ttp://")) {
				$avatar = "$mkportals->forum_url/".$avatar;
			}
			$avatar = "<img src=\"$avatar\" border=\"0\" alt=\"\" />";
		}
		return $avatar;
	}

	function get_forumnav()
 	{
		global $mklib, $mkportals, $Skin;

		$out = "<tr><td class=\"tdblock\">";
		$out .= $Skin->row_link_block(!$mklib->config['noicons'] ? "<img class=\"mkicon\" src=\"$mklib->images/atb_npost.gif\" style=\"vertical-align: middle\" align=\"left\" alt=\"{$mklib->lang['m_newpost']}\" />" : "", "href=\"/{$mkportals->forum_url}/search.php?do=getnew\"", $mklib->lang['m_newpost']);
		$out .= "</td></tr><tr><td class=\"tdblock\">";
		
		$out .= $Skin->row_link_block(!$mklib->config['noicons'] ? "<img class=\"mkicon\" src=\"$mklib->images/atb_members.gif\" style=\"vertical-align: middle\" align=\"left\" alt=\"{$mklib->lang['m_users']}\" />" : "", "href=\"/{$mkportals->forum_url}/memberlist.php\"", $mklib->lang['m_users']);
		$out .= "</td></tr><tr><td class=\"tdblock\">";

		$out .= $Skin->row_link_block(!$mklib->config['noicons'] ? "<img class=\"mkicon\" src=\"$mklib->images/atb_calendario.gif\" style=\"vertical-align: middle\" align=\"left\" alt=\"{$mklib->lang['m_calendar']}\" />" : "", "href=\"/{$mkportals->forum_url}/calendar.php\"", $mklib->lang['m_calendar']);
		$out .= "</td></tr><tr><td class=\"tdblock\">";		

		$out .= $Skin->row_link_block(!$mklib->config['noicons'] ? "<img class=\"mkicon\" src=\"$mklib->images/atb_help.gif\" style=\"vertical-align: middle\" align=\"left\" alt=\"{$mklib->lang['m_help']}\" />" : "", "href=\"/{$mkportals->forum_url}/faq.php\"", $mklib->lang['m_help']);
		$out .= "</td></tr>";

		return $out;


	}
	function get_site_stat()
 	{
		global $DB, $vbulletin;


		$sql = "SELECT COUNT(userid) AS total
				FROM " . TABLE_PREFIX . "user";

		$DB->query($sql);
		$row = $DB->fetch_row();

		$stat['members'] = $row['total'];

		$sql = "SELECT userid, username
				FROM " . TABLE_PREFIX . "user
				ORDER BY userid DESC
				LIMIT 1";
		$DB->query($sql);
		$row = $DB->fetch_row();

		$stat['last_member'] = $row['userid'];
		$stat['last_member_name'] = $row['username'];

		//vB showprivateforums settings
		//0==no; 1==yes_hide_post_counts; 2==yes_display_post_counts
        	switch($vbulletin->options['showprivateforums']) { 
                		case 2:
                		$show_privtopic = "";
				$show_privpost = "";
                        break;
                            	default:
				$show_privtopic = "WHERE t.visible = 1";
				$show_privpost = "AND p.visible = 1";
                        break;
                        }

		$sql = "SELECT COUNT(t.threadid) AS total
				FROM " . TABLE_PREFIX . "thread AS t $show_privtopic";
		$DB->query($sql);
		$row = $DB->fetch_row();
		$stat['topics'] = $row['total'];

		$sql = "SELECT COUNT(p.postid) AS total
			FROM " . TABLE_PREFIX . "post AS p
			LEFT JOIN " . TABLE_PREFIX . "thread AS t ON(p.threadid = t.threadid)
			$show_privtopic
			$show_privpost
			";
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

		$DB->query("SELECT
			user.username, (user.options & 512) AS invisible, user.usergroupid,
			session.userid, session.inforum, session.lastactivity, groups.opentag, groups.closetag,
			IF(displaygroupid=0, user.usergroupid, displaygroupid) AS displaygroupid
		FROM " . TABLE_PREFIX . "session AS session
		LEFT JOIN " . TABLE_PREFIX . "user AS user ON(user.userid = session.userid)
		LEFT JOIN " . TABLE_PREFIX . "usergroup AS groups ON(groups.usergroupid = user.usergroupid)
		WHERE session.lastactivity > $time
		ORDER BY username ASC
		");

			$active = array( 'guests' => 0, 'anon' => 0, 'members' => 0, 'names' => "");
			$cached = array ();

			while ($result = $DB->fetch_row() ) {
				if ($result['userid'] == 0) {
					$active['guests']++;
				} else {
						if ($cached[ $result['userid'] ]) {
							continue;
						}
						$cached[ $result['userid'] ] = 1;
						if ($result['invisible'] == 512) {
							if ( $mkportals->member['mgroup'] == "6") {
								$active['names'] .= "<a href=\"/{$mkportals->forum_url}/member.php?u={$result['userid']}\">{$result['opentag']}{$result['username']}{$result['closetag']}</a>*, ";
								$active['anon']++;
							} else {
								$active['anon']++;
							}
						} else {
							$active['members']++;
							$active['names'] .= "<a href=\"/{$mkportals->forum_url}/member.php?u={$result['userid']}\">{$result['opentag']}{$result['username']}{$result['closetag']}</a>, ";
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
	$DB->query("SELECT
			user.username, (user.options & 512) AS invisible, user.usergroupid, groups.opentag, groups.closetag,
			session.userid, session.inforum, session.lastactivity, session.location AS location,
			IF(displaygroupid=0, user.usergroupid, displaygroupid) AS displaygroupid
		FROM " . TABLE_PREFIX . "session AS session
		LEFT JOIN " . TABLE_PREFIX . "user AS user ON(user.userid = session.userid)
		LEFT JOIN " . TABLE_PREFIX . "usergroup AS groups ON(groups.usergroupid = user.usergroupid)
		WHERE session.lastactivity > $time
		ORDER BY username ASC
		");
		$online = array();
		$cached  = array();
		$online['members'] = 0;
		$online['guests'] = 0;
		$online['anon'] = 0;

		while ($result = $DB->fetch_row() ) {
				//$result['location'] = $this->decodeloc($result['session.location']);
				$result['location'] =  $this->decodeloc($result['location']);
				if ($cached[ $result['userid'] ] && $result['userid'] != 0) {
							continue;
				}
				$cached[ $result['userid'] ] = 1;
				if ($result['userid'] == 0) {
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
				} else if ($result['invisible'] == 512) {
						if ( $mkportals->member['mgroup'] == "6") {
							switch($result['location']) {
							case 'portale':
    							$online['portale'] .= "<a class=\"uno\" href=\"/{$mkportals->forum_url}/member.php?u={$result['userid']}\">{$result['opentag']}{$result['username']}{$result['closetag']}</a>*{$inter} \n";
    						break;
							case 'blog':
    							$online['blog'] .= "<a class=\"uno\" href=\"/{$mkportals->forum_url}/member.php?u={$result['userid']}\">{$result['opentag']}{$result['username']}{$result['closetag']}</a>*{$inter} \n";
    						break;
							case 'gallery':
    							$online['gallery'] .= "<a class=\"uno\" href=\"/{$mkportals->forum_url}/member.php?u={$result['userid']}\">{$result['opentag']}{$result['username']}{$result['closetag']}</a>*{$inter} \n";
    						break;
							case 'urlobox':
    							$online['urlobox'] .= "<a class=\"uno\" href=\"/{$mkportals->forum_url}/member.php?u={$result['userid']}\">{$result['opentag']}{$result['username']}{$result['closetag']}</a>*{$inter} \n";
    						break;
							case 'downloads':
    							$online['downloads'] .= "<a class=\"uno\" href=\"/{$mkportals->forum_url}/member.php?u={$result['userid']}\">{$result['opentag']}{$result['username']}{$result['closetag']}</a>*{$inter} \n";
    						break;
							case 'news':
    							$online['news'] .= "<a class=\"uno\" href=\"/{$mkportals->forum_url}/member.php?u={$result['userid']}\">{$result['opentag']}{$result['username']}{$result['closetag']}</a>*{$inter} \n";
    						break;
							case 'chat':
    							$online['chat'] .= "<a class=\"uno\" href=\"/{$mkportals->forum_url}/member.php?u={$result['userid']}\">{$result['opentag']}{$result['username']}{$result['closetag']}</a>*{$inter} \n";
    						break;
							case 'topsite':
    							$online['topsite'] .= "<a class=\"uno\" href=\"/{$mkportals->forum_url}/member.php?u={$result['userid']}\">{$result['opentag']}{$result['username']}{$result['closetag']}</a>*{$inter} \n";
    						break;
							case 'reviews':
    							$online['reviews'] .= "<a class=\"uno\" href=\"/{$mkportals->forum_url}/member.php?u={$result['userid']}\">{$result['opentag']}{$result['username']}{$result['closetag']}</a>*{$inter} \n";
    						break;
							default:
							$online['forum'] .= "<a class=\"uno\" href=\"/{$mkportals->forum_url}/member.php?u={$result['userid']}\">{$result['opentag']}{$result['username']}{$result['closetag']}</a>*{$inter} \n";
    						break;
							}
							$online['anon']++;
                    	} else {
                        	$online['anon']++;
                    	}
				} else  {
					 $online['members']++;
						switch($result['location']) {
							case 'portale':
    							$online['portale'] .= "<a class=\"uno\" href=\"/{$mkportals->forum_url}/member.php?u={$result['userid']}\">{$result['opentag']}{$result['username']}{$result['closetag']}</a>{$inter} \n";
    						break;
							case 'blog':
    							$online['blog'] .= "<a class=\"uno\" href=\"/{$mkportals->forum_url}/member.php?u={$result['userid']}\">{$result['opentag']}{$result['username']}{$result['closetag']}</a>{$inter} \n";
    						break;
							case 'gallery':
    							$online['gallery'] .= "<a class=\"uno\" href=\"/{$mkportals->forum_url}/member.php?u={$result['userid']}\">{$result['opentag']}{$result['username']}{$result['closetag']}</a>{$inter} \n";
    						break;
							case 'urlobox':
    							$online['urlobox'] .= "<a class=\"uno\" href=\"/{$mkportals->forum_url}/member.php?u={$result['userid']}\">{$result['opentag']}{$result['username']}{$result['closetag']}</a>{$inter} \n";
    						break;
							case 'downloads':
    							$online['downloads'] .= "<a class=\"uno\" href=\"/{$mkportals->forum_url}/member.php?u={$result['userid']}\">{$result['opentag']}{$result['username']}{$result['closetag']}</a>{$inter} \n";
    						break;
							case 'news':
    							$online['news'] .= "<a class=\"uno\" href=\"/{$mkportals->forum_url}/member.php?u={$result['userid']}\">{$result['opentag']}{$result['username']}{$result['closetag']}</a>{$inter} \n";
    						break;
							case 'chat':
    							$online['chat'] .= "<a class=\"uno\" href=\"/{$mkportals->forum_url}/member.php?u={$result['userid']}\">{$result['opentag']}{$result['username']}{$result['closetag']}</a>{$inter} \n";
    						break;
							case 'topsite':
    							$online['topsite'] .= "<a class=\"uno\" href=\"/{$mkportals->forum_url}/member.php?u={$result['userid']}\">{$result['opentag']}{$result['username']}{$result['closetag']}</a>{$inter} \n";
    						break;
							case 'reviews':
    							$online['reviews'] .= "<a class=\"uno\" href=\"/{$mkportals->forum_url}/member.php?u={$result['userid']}\">{$result['opentag']}{$result['username']}{$result['closetag']}</a>{$inter} \n";
    						break;
							default:
							$online['forum'] .= "<a class=\"uno\" href=\"/{$mkportals->forum_url}/member.php?u={$result['userid']}\">{$result['opentag']}{$result['username']}{$result['closetag']}</a>{$inter} \n";
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
		global $DB, $mklib, $mkportals, $db_prefix, $user_info;
		$limit = 5;
		$taglio = 17;

     $DB->query("
	SELECT post.*,post.username AS postusername,user.userid,thread.forumid,thread.title AS ttitle
	FROM " . TABLE_PREFIX . "post AS post
    LEFT JOIN " . TABLE_PREFIX . "user AS user ON(user.userid = post.userid)
	LEFT JOIN " . TABLE_PREFIX . "thread AS thread ON(thread.threadid = post.threadid)
	LEFT JOIN " . TABLE_PREFIX . "deletionlog AS deletionlog ON(deletionlog.primaryid = post.postid AND type = 'post')
	WHERE post.visible=1 AND deletionlog.primaryid IS NULL
	ORDER BY dateline DESC
	LIMIT 0, $limit
");



		while ( $post = $DB->fetch_row() ) {
		$forumid= $post['forumid'];
		$foruminfo = verify_id('forum', $forumid, 1, 1);
		$_permsgetter_ = 'forumdisplay';
		$forumperms = fetch_permissions($forumid);
		if (!$forumperms || $foruminfo['password']) {
			continue;
		}
		$title = (!empty($post['title'])) ? strip_tags($post['title']) : 'RE: '.strip_tags($post['ttitle']);
		$title = str_replace( "&#33;" , "!" ,$title );
		$title = str_replace( "&quot;", "\"", $title );
			if (strlen($title) > $taglio) {
				$title = substr( $title,0,($taglio - 3) ) . "...";
				$title = preg_replace( '/&(#(\d+;?)?)?(\.\.\.)?$/', '...',$title );
			}

 		$date  = $mklib->create_date($post['dateline']);
		$tid = $post['postid'];

		$mid = $post['userid'];
		$mname = $post['postusername'];

		$content .= "
				<tr>
				  <td width=\"100%\" class=\"tdblock\">
				  <a class=\"uno\" href=\"$mkportals->forum_url/showthread.php?p=$tid#post$tid\">$title</a>
				  </td>
				</tr>
				<tr>
				  <td class=\"tdglobal\">
				  <a class=\"uno\" href=\"$mkportals->forum_url/member.php?u=$mid\">$by: $mname</a><br /> $sdate: $date
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

		$DB->query("SELECT forumid AS id, title AS name FROM " . TABLE_PREFIX . "forum WHERE parentid > '0' ORDER BY forumid");

		while( $board = $DB->fetch_row() ) {
			$cselect[] = $board;
		}

		return $cselect;
	}

	function get_board_news()
 	{
		global $DB, $mklib, $mkportals, $db_prefix, $user_info, $vbulletin;

		require_once("$mkportals->forum_url/includes/functions_user.php");
		require_once("$mkportals->forum_url/includes/class_bbcode.php");
		$bbcode_parser =& new vB_BbCodeParser($vbulletin, fetch_tag_list());
		$limit = $mklib->config['bnews_block'];
		$news_words= $mklib->config['bnews_words'];
		//$taglio = 17;
		$db_prefix = DBPREFIX;
		$forum_active = unserialize($mklib->config['forum_active']);
		if(!$forum_active) {
				return "";
		}


		$DB->query("
		SELECT post.*,post.username AS postusername, user.userid, thread.forumid, thread.replycount, thread.iconid AS icona, thread.threadid, thread.title AS ttitle, forum.title AS f_title
		 FROM (" . TABLE_PREFIX . "post AS post, " . TABLE_PREFIX . "thread AS thread)
    		LEFT JOIN " . TABLE_PREFIX . "user AS user ON(user.userid = post.userid)
		LEFT JOIN " . TABLE_PREFIX . "forum AS forum ON(thread.forumid = forum.forumid)
		LEFT JOIN " . TABLE_PREFIX . "deletionlog AS deletionlog ON(deletionlog.primaryid = post.postid AND type = 'post')
		WHERE post.postid = thread.firstpostid AND thread.forumid IN (".implode(',', $forum_active ).") AND post.visible=1 AND deletionlog.primaryid IS NULL
		ORDER BY post.dateline DESC
		LIMIT $limit
		");
		while ( $post = $DB->fetch_row() ) {
		$avatar = "";
  		$title = strip_tags($post['ttitle']);
		$title = str_replace( "&#33;" , "!" ,$title );
		$title = str_replace( "&quot;", "\"", $title );

 		$date  = $mklib->create_date($post['dateline']);
		$tid = $post['threadid'];

		$mid = $post['userid'];
		$mname = $post['postusername'];
		$testo = $post['pagetext'];
		if ($news_words) {
			$testo = substr ($testo, 0, $news_words);
			$testo .= " ...";
   		}
		$testo = $bbcode_parser->parse($testo, $post['forumid'], TRUE);
		$testo = str_replace("img src=\"images/smilies", "img src=\"$mkportals->forum_url/images/smilies", $testo); 
		$testo = str_replace("src=\"images/buttons", "src=\"$mkportals->forum_url/images/buttons", $testo); 	
		$testo = str_replace("href=\"showthread.php", "href=\"$mkportals->forum_url/showthread.php", $testo);
		$fname = $post['f_title'];
		if(!$post['icona']) {
			$post['icona'] = "1";
		}
		$numreplies = $post['replycount']." ".$mklib->lang['replies'];
		$icona = $mkportals->forum_url."/images/icons/icon".$post['icona'].".gif";
		$avatar = fetch_avatar_url($mid);
		$avatar = $avatar[0];
			
		if ($avatar) {
			if (!strpos($avatar, "ttp://")) {
				$avatar = "$mkportals->forum_url/".$avatar;
			}
			$avatar = "<img src=\"$avatar\" border=\"0\" alt=\"\" />";
		} else {
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
					<b>$fname<br /><a href=\"$mkportals->forum_url/showthread.php?t=$tid\">$title</a></b>
					<br /><div class=\"mkalign2\" style='font-style: italic; font-weight: normal;'><a href=\"$mkportals->forum_url/showthread.php?t=$tid\">$numreplies</a>&nbsp;</div>
					</td>
				      </tr>
				      <tr>
					<td colspan=\"2\"><br />
					$testo
					</td>
				      </tr>
				      <tr>
					<td class=\"mkalign2\" colspan=\"2\">
					<br /><i>{$mklib->lang['from']}<b> <a href=\"$mkportals->forum_url/member.php?u=$mid\">$mname</a></b>, $date <a href=\"$mkportals->forum_url/showthread.php?t=$tid\"> [ {$mklib->lang['readall']} ]</a></i>
					</td>
				      </tr>
				    </tbody>
				  </table>
		";
 		}
		return $out;
	}
	
	function langselect() {
		global $DB, $mklib, $mkportals, $vbulletin;

		if (!$mkportals->member['id']) {
			return '';
		}
/*		
		$boardlang = $bbuserinfo['languageid'];
		if ($bbuserinfo['languageid'] == 0) {
			$boardlang = $vboptions['languageid'];
		}
*/
		if ($vbulletin->userinfo['languageid']) {
			$boardlang = $vbulletin->userinfo['languageid'];
		} else {
			$boardlang = $vbulletin->options['languageid'];
		}
		
		$content = "<form name=\"mklanglist\" action=\"post\">\n <select name=\"seleclang\" class=\"bgselect\" onchange=\"document.location.href=mklanglist.seleclang.options[this.selectedIndex].value\">\n";
		$query = $DB->query("SELECT languageid, title FROM " . TABLE_PREFIX . "language WHERE userselect='1'");
		
		while ( $r = $DB->fetch_row($query) ) {
			$selected = "";
//			$name =& $r['title']; 
			if ($boardlang == $r['languageid']) {
				$selected = " selected=\"selected\"";
			}
			$content .= "\n<option value=\"{$mklib->siteurl}/index.php?langid={$r['languageid']}\"{$selected}>{$r['title']}</option>";
			
		}
		
		$content .= "\n</select>\n</form>";
    	$output = "
				<tr>
				  <td class=\"tdblock\" align=\"center\" valign=\"middle\">{$content}</td>
				</tr>
                ";
		
		return $output;
    }
	
    function update_lang($langid)
     {
        global $mkportals, $DB, $mklib;

//	$idu = $mkportals->member['id'];
		if (!$mkportals->member['id']) {
			return;
		}
		
		$langid = intval($langid);
		
        $query = $DB->query("SELECT languageid FROM " . TABLE_PREFIX . "language WHERE languageid = '$langid'");
        if ($DB->fetch_row($query)){
            $DB->query("UPDATE " . TABLE_PREFIX . "user SET languageid ='$langid' WHERE userid = '{$mkportals->member['id']}'");
            $DB->close_db();
            header("Location: /{$mkportals->forum_url}/index.php");
            exit;
        }
    }

	function skinselect()
 	{
		global $DB, $mklib, $mkportals;

		if (!$mkportals->member['id']) {
			return;
		}
		$templateslist .= "<form name=\"skinlist\" action=\"post\">\n <select name=\"selectskin\" class=\"bgselect\" onchange=\"document.location.href=skinlist.selectskin.options[this.selectedIndex].value\">\n";
		$query = $DB->query("SELECT  styleid, title FROM " . TABLE_PREFIX . "style WHERE userselect='1'");
		while ( $r = $DB->fetch_row($query) )
		{
			$selected = "";
			if ($mkportals->member['theme'] == $r['styleid']) {
				$selected = " selected=\"selected\"";
			}
			if (strlen($r['title']) > 14 ) {
				$r['title'] = substr($r['title'], 0, 14);
			}
			$templateslist .= "\n<option value=\"$mklib->siteurl/index.php?skinid={$r['styleid']}\"{$selected}>{$r['title']}</option>";

		}

		$templateslist .= "\n</select>\n</form>";
    	$templateslist = "
				<tr>
				  <td class=\"tdblock\" align=\"center\" valign=\"middle\">{$templateslist}</td>
				</tr>
                ";
		return $templateslist;
	}

	function update_skin($skinid)
 	{
		global $mkportals, $DB, $mklib;
		$DB->close_db();

		$skinid = intval($skinid);

 		header("Location: /{$mkportals->forum_url}/index.php?styleid=$skinid");
		exit;
	}

	function calendar_birth($chosen_month, $chosen_year)
 	{
		global $mkportals, $DB, $mklib;

		$birthdays = array();

		$chosen_month = intval($chosen_month);
		$chosen_year = intval($chosen_year);
		
		$DB->query("SELECT username, DAYOFMONTH(birthday_search) AS bday_day, YEAR(birthday_search) AS bday_year FROM " . TABLE_PREFIX . "user WHERE MONTH(birthday_search)='".$chosen_month."' AND showbirthday = 2");
    	while ($user = $DB->fetch_row()) {
       	 	$birthdays[ $user['bday_day'] ]++;
        	if ($birthdays[ $user['bday_day'] ] < 10) {
            	$tool_birthdays[$user['bday_day']] .=  $user['username']." (".($chosen_year - $user['bday_year']).")&nbsp;";
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

		$chosen_month = intval($chosen_month);
		$chosen_year = intval($chosen_year);

		$startt   = mktime( 0, 0, 0, $chosen_month, 1, $chosen_year);
		$endt   = mktime( 0, 0, 0, $chosen_month+1, 0, $chosen_year);

		//$today = date("d");
		//$startt  = mktime(0, 0, 0, date("m")  , date("d")-$today, date("Y"));
		//$endt  = mktime(0, 0, 0, date("m")  , date("d")+31, date("Y"));


    	$DB->query("SELECT eventid, title, dateline_from AS mmday FROM " . TABLE_PREFIX . "event WHERE dateline_from >='".$startt."' AND dateline_from  <= '".$endt."' AND visible = '1'");
		while ( $event = $DB->fetch_row() ) {
			$event['mday'] = intval(date("d", $event['mmday']));
       	 	$events[ $event['mday'] ][] = $event;
       	 	$entry = substr($event['title'], 0, 20);
     	 	if ( strlen($event['title']) > 20 ) {
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
		
		//Inline stylesheet
		$DB->query("SELECT css FROM " . TABLE_PREFIX . "style WHERE styleid = '{$mkportals->member['theme']}'");
		$r = $DB->fetch_row();
		$css2 = $r['css'];
		unset ($r);

		//Linked stylesheet
		/* vB < 3.7.0
		$pos1 = strpos($css2, "link rel");
		if ($pos1) {
			$pos1 = strpos($css2, "href=");
			$pos1 = ($pos1 +6);
		*/

		//vB 3.7.0		
		$pos1 = strpos($css2, "@import"); 
		if ($pos1) {
			$pos1 = strpos($css2, "url(");
			$pos1 = ($pos1 +5);
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
		//end Linked stylesheet

		//importing body
		$pos = strpos($css2, "body");
		$pos2 = strpos($css2, "}", $pos);
			if ($pos || $pos === 0) {
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
		$pos = strpos($css2, ".page");
		$pos2 = strpos($css2, "}", $pos);
			if ($pos) {
				$mkpsubs = substr($css2, $pos, ($pos2 - ($pos -1)));
				$css = preg_replace( "`(\#importlogostrip(.*?\}))`is", $mkpsubs, $css);
			}

		//importing main table bg (if different than body bg)
		$pos = strpos($css2, ".page");
		$pos2 = strpos($css2, "}", $pos);
			if ($pos) {
				$mkpsubs = substr($css2, $pos, ($pos2 - ($pos -1)));
				$css = preg_replace( "`(\.importmain(.*?\}))`is", $mkpsubs, $css);
			}

		//importing light background
		$pos = strpos($css2, ".alt1");
		$pos2 = strpos($css2, "}", $pos);
			if ($pos) {
				$mkpsubs = substr($css2, $pos, ($pos2 - ($pos -1)));
				$css = preg_replace( "`(\.importlightback(.*?\}))`is", $mkpsubs, $css);
			}

		//importing medium background
		$pos = strpos($css2, ".alt2");
		$pos2 = strpos($css2, "}", $pos);
			if ($pos) {
				$mkpsubs = substr($css2, $pos, ($pos2 - ($pos -1)));
				$css = preg_replace( "`(\.importmediumback(.*?\}))`is", $mkpsubs, $css);
			}

		//importing dark background
		$pos = strpos($css2, ".tcat");
		$pos2 = strpos($css2, "}", $pos);
			if ($pos) {
				$mkpsubs = substr($css2, $pos, ($pos2 - ($pos -1)));
				$css = preg_replace( "`(\.importdarkback(.*?\}))`is", $mkpsubs, $css);
			}

		//importing module table headers
		$pos = strpos($css2, ".thead");
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
				$css = preg_replace( "`(\.importborders(.*?\}))`is", $mkpsubs, $css);
			}

		//importing form styles
		$pos = strpos($css2, ".bginput");
		$pos2 = strpos($css2, "}", $pos);
			if ($pos) {
				$mkpsubs = substr($css2, $pos, ($pos2 - ($pos -1)));
				$css = preg_replace( "`(\.importforms(.*?\}))`is", $mkpsubs, $css);
			}

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
		$DB->query("SELECT email FROM " . TABLE_PREFIX . "user WHERE userid = '$iduser'");
		$row = $DB->fetch_row();
		$dest = $row['email'];

		mail($dest, $subject, $message,  $headers);
	}
	function admin_mail($subject, $message)
 	{
		global $DB, $mklib;

		$headers = "From: webmaster@" . $mklib->sitename . "\r\n" . "Reply-To: webmaster@" . $mklib->sitename . "\r\n" . "X-Mailer: MKportal Mail";

		$dest = "";
		$DB->query("SELECT email FROM " . TABLE_PREFIX . "user WHERE  usergroupid  = '6'");
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
