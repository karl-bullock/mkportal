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
define ( 'IN_IPB', 1 );
global $MK_BOARD;
$MK_BOARD = "IPB";

class mklib_board {

var $users_are_init = FALSE;
var $usercache = array();

//common functions

	function store_location($loc) {
		global $mkportals, $DB;
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
		$idu = $mkportals->member['id'];
		
		$DB->query("UPDATE ibf_sessions SET location ='$location' WHERE member_id = '$idu'");
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


		$time = (time() - 300);

		$DB->query("SELECT s.member_id, s.member_name, s.running_time, s.login_type, s.location, g.suffix, g.prefix, g.g_perm_id, m.org_perm_id
					    FROM ibf_sessions s
					     LEFT JOIN ibf_groups g ON (g.g_id=s.member_group)
					     LEFT JOIN ibf_members m on (s.member_id=m.id)
					    WHERE s.location='$location'
					    AND s.running_time > '$time'
					     ORDER BY s.running_time DESC");
			$cached = array();
			$active = array( 'guests' => 0, 'anon' => 0, 'members' => 0, 'names' => "");
			while ($result = $DB->fetch_row() ) {
				$result['g_perm_id'] = $result['org_perm_id'] ? $result['org_perm_id'] : $result['g_perm_id'];
				if ($result['member_id'] == 0) {
					$active['guests']++;
				} else {
					if (empty( $cached[ $result['member_id'] ] ) ) {
						$cached[ $result['member_id'] ] = 1;
						if ($result['login_type'] == 1) {
							if ( $mkportals->member['mgroup'] == "4") {
								$active['names'] .= "<a href=\"/{$mkportals->base_url}?showuser={$result['member_id']}\">{$result['prefix']}{$result['member_name']}{$result['suffix']}</a>*, ";
								$active['anon']++;
							} else {
								$active['anon']++;
							}
						} else {
							$active['members']++;
							$active['names'] .= "<a href=\"/{$mkportals->base_url}?showuser={$result['member_id']}\">{$result['prefix']}{$result['member_name']}{$result['suffix']}</a>, ";
						}
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
 		$DB->query("SELECT typed AS code, image, emo_set FROM ibf_emoticons");
        $output = $this->get_emo_header($css);
        $countr = 0;
        if ( $DB->get_num_rows() ) {
            while ( $r = $DB->fetch_row() ) {
                if ($countr == 3) {
                    $output .= "</tr><tr>";
                    $countr = 0;
                }
		$this->emo_path = "style_emoticons/".$r['emo_set']."/";
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
		global $DB, $mkportals, $ipsclass, $mklib;

		$DB->query("SELECT typed, image, emo_set FROM ibf_emoticons");
		while ( $r = $DB->fetch_row() ) {
		//foreach ($ipsclass->cache['emoticons'] as $r) {
			$code = stripslashes($r['typed']);
			$image = stripslashes($r['image']);
			$pathemo = stripslashes($r['emo_set']);
			$image = "<img src=\"$mklib->siteurl/$mklib->forumpath/style_emoticons/$pathemo/$image\" border=\"0\" alt=\"\" />";
			//hack limit smilie start
			$count = substr_count($message, "img src=");
			$count2 = substr_count($message, $code);
			if ($count > 4 || $count2 > 4) {$image = ""; }
			//hack limit smilie end
			$message = str_replace($code, $image, $message);
		}
		return $message;
	}
	function popup_pm($m1, $m2, $m3, $m4)
 	{
		global $DB, $mklib, $mkportals;

		$u1 = "$mklib->siteurl/$mklib->forumpath/index.php?act=Msg";

		$DB->query("UPDATE ibf_members SET show_popup=0 WHERE id={$mkportals->member['id']}");


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

		$DB->query( "SELECT g_id, g_title FROM ibf_groups ORDER BY `g_id`");
		while( $row = $DB->fetch_row() ) {
			if($row['g_id'] == 4) {
				continue;
			}
			$g_id= $row['g_id'];
			$g_title = $row['g_title'];
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
		$DB->query( "SELECT g_id, g_title FROM ibf_groups ORDER BY `g_id`");
		while( $row = $DB->fetch_row() ) {
			if($row['g_id'] == 4) {
				continue;
			}
			$g_id = $row['g_id'];
			$group[$g_id][id] = $row['g_id'];
			$group[$g_id][title] = $row['g_title'];
		}
		return $group;
	}
	//ad_perms
	function update_groupperms($g_id)
	{
		global $DB;

		$query = $DB->query( "SELECT g_title FROM ibf_groups WHERE g_id = '$g_id'");
		$row = $DB->fetch_row($query);
		$g_title = $row['g_title'];
		return $row['g_title'];

	}

	//ad_poll
	function get_poll_list()
	{
		global $mklib, $DB;

		$prefix = DBPREFIX;
		$poll_active = $mklib->config['poll_active'];
		$DB->query("SELECT pid, tid, poll_question FROM ibf_polls ORDER BY pid DESC LIMIT 30");

        if ($DB->get_num_rows()) {
    		while( $poll = $DB->fetch_row() ) {
    			$id = $poll['tid'];
    			$title = $poll['poll_question'];
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
		global $mklib, $mkportals, $ipsclass;

		switch($link) {
			case 'profile':
    			$out = "{$mkportals->forum_url}/index.php?showuser";
    		break;
			case 'cpaforum':
    			$out = "{$mkportals->forum_url}/admin.php";
    		break;
			case 'cpapers':
    			$out = "{$mkportals->forum_url}/index.php?act=UserCP&amp;CODE=00";
    		break;
			case 'pm':
    			$out = "{$mkportals->forum_url}/index.php?act=Msg";
    		break;
			case 'forumsearch':
    			$out = "{$mkportals->forum_url}/index.php?act=Search";
    		break;
			case 'logout':
    			$return = $mklib->siteurl;
			$pos = strpos($_SERVER["PHP_SELF"], $mklib->forumpath);
			if ($pos) {
				$return = "";
			}
			$out = "{$mkportals->forum_url}/index.php?act=Login&amp;CODE=03&amp;k={$ipsclass->md5_check}&amp;return=$return";
    		break;
			case 'postlink':
			$return = $mklib->siteurl;
			$pos = strpos($_SERVER["PHP_SELF"], $mklib->forumpath);
			if ($pos) {
				$return = "";
			}
    			$out = "{$mkportals->forum_url}/index.php?act=Login&amp;CODE=01&amp;CookieDate=1&amp;return=$return";
    		break;
			case 'postlink2':
    			$out = "name=\"LOGIN\" onsubmit=\"return ValidateForm()\"";
    		break;
			case 'register':
    			$out = "{$mkportals->forum_url}/index.php?act=Reg&amp;CODE=00";
    		break;
			case 'onlinelist':
    			$out = "{$mkportals->forum_url}/index.php?act=Online&amp;CODE=listall&amp;sort_key=click";
    		break;
			case 'login_extra':
    			$out = "<tr>
                   <td class=\"tdblock mkalign1\" width=\"100%\" colspan=\"2\"><b>{$mklib->lang['anon_login']}</b>&nbsp;<input type=\"checkbox\" name=\"Privacy\" value=\"1\" style=\"margin:0px;\" />
				  <input type=\"hidden\" name=\"CookieDate\" value=\"1\" />
				  </td>
				</tr>
				";
    		break;
			case 'login_user':
    			$out = "UserName";
    		break;
			case 'login_passw':
    			$out = "PassWord";
    		break;
			case 'calendar_event':
    			$out = "$mkportals->forum_url/index.php?act=calendar";
    		break;
			default:
    			$out = "n/a";
    		break;
    		}

		return $out;

	}

	function get_poll_active($tid)
 	{
		global $DB, $mklib, $mkportals;

		if (!$tid) {
            return;
        }
        if ( $mkportals->member['id'] ) {
            $extra = "LEFT JOIN ibf_voters v ON (v.member_id={$mkportals->member['id']} and v.tid=t.tid)";
            $sql   = ", v.member_id as member_voted";
        }
        $DB->query("SELECT t.tid, t.title, t.state, t.last_vote, p.* $sql
                     FROM (ibf_topics t, ibf_polls p)
                     $extra
                     WHERE t.tid=$tid AND p.tid=t.tid");
        $poll = $DB->fetch_row();
          if ( ! $poll['pid'] ) {
            return;
        }
          $poll['poll_question'] = $poll['poll_question'] ? $poll['poll_question'] : $poll['title'];
        if ( $poll['state'] == 'closed' ) {
            $controllo = 1;
            $poll_footer = "<tr><td>{$mklib->lang['poll_closed']}";
        }
        else if (! $mkportals->member['id'] ) {
            $controllo = 1;
            $poll_footer = "<tr><td>{$mklib->lang['poll_noallow']}";
        }
        else if ( $poll['member_voted'] ) {
            $controllo = 1;
            $poll_footer = "<tr><td>{$mklib->lang['poll_voted']}";
        }

        else {
            $controllo = 0;
            $poll_footer = "<tr><td><input type=\"submit\" value=\"{$mklib->lang['poll_vote']}\" class=\"mkbutton\" style=\"margin-top: 10px;\" /></form>";
        }
        if ($controllo == 1) {
            $total_votes = 0;
            $output .= "            
				<tr>
				  <td class=\"tdblock\">
				  <a class=\"uno\" href=\"$mkportals->forum_url/index.php?showtopic={$poll['tid']}\">{$poll['poll_question']}</a>
				  </td>
				</tr>
            ";
            $poll_answers = unserialize(stripslashes($poll['choices']));
            reset($poll_answers);
            foreach ($poll_answers as $id => $data) {
                $question    = $data['question'];
		$tv_poll     = 0;
		$output .= "            
				<tr>
				  <td class=\"tdblock\">
				  {$question}
				  </td>
				</tr>
            	";
		foreach( $poll_answers[ $id ]['votes'] as $index => $number) {
        			$tv_poll += intval( $number );
        	}
		foreach( $data['choice'] as $choice_id => $text ) {
        		$scelta  = $text;
        		$votes   = intval($data['votes'][ $choice_id ]);
			$total_votes += $votes;		
			if ( strlen($scelta) < 1 ) {
				continue;
			}
                	$percent = $votes == 0 ? 0 : $votes / $tv_poll * 100;
                	$percent = sprintf( '%.2f' , $percent );
                	$width   = $percent > 0 ? floor( round( $percent ) * ( 120 / 100 ) ) : 0;
			$output .= "
				<tr>
				  <td class=\"tdglobal\">
				  $scelta
				  </td>
				</tr>
				<tr>
				  <td class=\"tdblock\" align=\"left\">
				  <img src=\"$mklib->images/bar-start.gif\" border=\"0\" width=\"4\" height=\"11\" alt=\"\" /><img src=\"$mklib->images/bar.gif\" border=\"0\" width=\"$width\" height=\"11\" alt=\"\" /><img src=\"$mklib->images/bar-end.gif\" border=\"0\" width=\"4\" height=\"11\" alt=\"\" />
				  </td>
				</tr>                    
                	";
            	}
	}
	
} else {
            $poll_answers = unserialize(stripslashes($poll['choices']));
            reset($poll_answers);

            $output = "            
				<tr>
				  <td class=\"tdblock\">
				  <a class=\"uno\" href=\"$mkportals->forum_url/index.php?showtopic={$poll['tid']}\">{$poll['poll_question']}</a>
				  </td>
				</tr>
				    <form action=\"$mkportals->forum_url/index.php?showtopic={$poll['tid']}&amp;st=&amp;addpoll=1\" method=\"post\">
            ";

            foreach ($poll_answers as $id => $data) {
                $question    = $data['question'];
		$output .= "            
				<tr>
				  <td class=\"tdblock\">
				  {$question}
				  </td>
				</tr>
            	";
            	foreach( $data['choice'] as $choice_id => $text ) {
        		$scelta  = $text;
			$votes   = intval($data['votes'][ $choice_id ]);
			$total_votes += $votes;
			$namef = "choice[{$id}]";		
			if ( strlen($scelta) < 1 ) {
				continue;
			}                
                	$output   .= "                    
				    <tr>
				  <td class=\"tdglobal\">
				<div style=\"margin-top: 5px;\"><input type=\"radio\" name=\"$namef\" value=\"$choice_id\" class=\"bgselect\" />&nbsp;<strong>$scelta</strong></div>
				</td>
				</tr>
               		 ";
		}

            }

        }

         $output   .= "	                       
              $poll_footer	      
				  </td>
				</tr>
				<tr>
				  <td class=\"tdblock\">
				  <span class=\"mktxtcontr\">$total_votes</span> {$mklib->lang['poll_totalvotes']}
				  </td>
				</tr>                    
                ";
         return $output;


	}

	function get_avatar()
 	{
		global $mkportals, $DB, $mklib, $ipsclass;

			$idu = $mkportals->member['id'];
			$DB->query("SELECT avatar_location, avatar_size, avatar_type FROM ibf_member_extra WHERE id = '$idu'");

			$row = $DB->fetch_row($query);

			if ( $row['avatar_type'] == 'local' && $row['avatar_location'])
			{
				$avatar = "<img src=\"$mklib->siteurl/$mklib->forumpath/style_avatars/{$row['avatar_location']}\" border=\"0\" alt=\"\" />";
			}
			if ( $row['avatar_type'] == 'url' )
			{
				$dimension = explode("x", $row['avatar_size']);
				$avatar = "<img src=\"{$row['avatar_location']}\" border=\"0\" width=\"$dimension[0]\" height=\"$dimension[1]\" alt=\"\" />";
			}
			if ( $row['avatar_type'] == 'upload' )
  			{
   				$upload_url = $ipsclass->vars['upload_url'];
				$row['avatar_location'] = str_replace("upload:", "", $row['avatar_location']);
  				//$avatar = "$mklib->forumpath/uploads/{$row['avatar_location']}";
   				$dimension = explode("x", $row['avatar_size']);
   				$avatar = "<img src=\"$upload_url/{$row['avatar_location']}\" width=\"$dimension[0]\" height=\"$dimension[1]\" border=\"0\" alt=\"\" />";
  			}


			return $avatar;


	}

	function get_forumnav()
 	{
		global $mklib, $mkportals, $Skin;

		$out = "<tr><td class=\"tdblock\">";
		$out .= $Skin->row_link_block(!$mklib->config['noicons'] ? "<img class=\"mkicon\" src=\"$mklib->images/atb_npost.gif\" style=\"vertical-align: middle\" align=\"left\" alt=\"{$mklib->lang['m_newpost']}\" />" : "", "{$mkportals->base_url}?act=Search&amp;CODE=getnew\"", $mklib->lang['m_newpost']);
		$out .= "</td></tr><tr><td class=\"tdblock\">";
		
		$out .= $Skin->row_link_block(!$mklib->config['noicons'] ? "<img class=\"mkicon\" src=\"$mklib->images/atb_members.gif\" style=\"vertical-align: middle\" align=\"left\" alt=\"{$mklib->lang['m_users']}\" />" : "", "href=\"{$mkportals->base_url}?act=Members\"", $mklib->lang['m_users']);
		$out .= "</td></tr><tr><td class=\"tdblock\">";

		$out .= $Skin->row_link_block(!$mklib->config['noicons'] ? "<img class=\"mkicon\" src=\"$mklib->images/atb_calendario.gif\" style=\"vertical-align: middle\" align=\"left\" alt=\"{$mklib->lang['m_calendar']}\" />" : "", "href=\"{$mkportals->base_url}?act=calendar\"", $mklib->lang['m_calendar']);
		$out .= "</td></tr><tr><td class=\"tdblock\">";		

		$out .= $Skin->row_link_block(!$mklib->config['noicons'] ? "<img class=\"mkicon\" src=\"$mklib->images/atb_help.gif\" style=\"vertical-align: middle\" align=\"left\" alt=\"{$mklib->lang['m_help']}\" />" : "", "href=\"{$mkportals->base_url}?act=Help\"", $mklib->lang['m_help']);
		$out .= "</td></tr>";

		if (stristr($_SERVER['PHP_SELF'], $mklib->forumpath)) {
			$out .= "<tr><td class=\"tdblock\">";
			$out .= $Skin->row_link_block(!$mklib->config['noicons'] ? "<img class=\"mkicon\" src=\"$mklib->images/atb_assistant.gif\" style=\"vertical-align: middle\" align=\"left\" alt=\"{$mklib->lang['m_assistent']}\" />" : "", "href=\"javascript:buddy_pop();\"", $mklib->lang['m_assistent']);
			$out .= "</td></tr>";
		}

		return $out;


	}
	function get_site_stat()
 	{
		global $ipsclass;

		foreach ($ipsclass->cache['stats'] as $k => $v)
        	{
        		$r[$k] = stripslashes($v);

        	}

		$stat['members'] = $r['mem_count'];
		$stat['last_member'] = $r['last_mem_id'];
		$stat['last_member_name'] = $r['last_mem_name'];
		$stat['topics'] = $r['total_topics'];
		$stat['total_posts'] = $r['total_replies']+$r['total_topics'];
		$stat['replies'] = $r['total_replies'];
		//unset ($temp);
		return $stat;


	}

	function get_onlineblock()
 	{
		global $DB, $mkportals, $mklib_board;


        $link_user = $mklib_board->forum_link("profile");
		$time = (time() - 900);

		if (!$this->users_are_init) {
		    $DB->query("SELECT s.id, s.member_id, s.member_name, s.login_type, s.location, g.suffix, g.prefix
                    FROM ibf_sessions s
                    LEFT JOIN ibf_groups g ON (g.g_id=s.member_group)
                    WHERE running_time > $time
                    ORDER BY s.running_time DESC");
	 
	 	     while( $row = $DB->fetch_row() ) {
            			$this->usercache[] = $row;
        		}
        		$this->users_are_init = TRUE;    
		}
			$cached = array();
			$active = array( 'guests' => 0, 'anon' => 0, 'members' => 0, 'names' => "");
			foreach($this->usercache as $result) {
				$result['g_perm_id'] = $result['org_perm_id'] ? $result['org_perm_id'] : $result['g_perm_id'];
				if ($result['member_id'] == 0) {
					$active['guests']++;
				} else {
					if (empty( $cached[ $result['member_id'] ] ) ) {
						$cached[ $result['member_id'] ] = 1;
						if ($result['login_type'] == 1) {
							if ( $mkportals->member['mgroup'] == "4") {
								$active['names'] .= "<a href=\"$link_user{$result['member_id']}\">{$result['prefix']}{$result['member_name']}{$result['suffix']}</a>*, ";
								$active['anon']++;
							} else {
								$active['anon']++;
							}
						} else {
							$active['members']++;
							$active['names'] .= "<a href=\"$link_user{$result['member_id']}\">{$result['prefix']}{$result['member_name']}{$result['suffix']}</a>, ";
						}
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

		global $DB, $mkportals, $mklib_board;
    $link_user = $mklib_board->forum_link("profile");
	$content = "";
	$inter = ",";

	$time = (time() - 900);
	
	if (!$this->users_are_init) {
	$DB->query("SELECT s.id, s.member_id, s.member_name, s.login_type, s.location, g.suffix, g.prefix
                    FROM ibf_sessions s
                      LEFT JOIN ibf_groups g ON (g.g_id=s.member_group)
                    WHERE running_time > $time
                    ORDER BY s.running_time DESC");
	 
	 	while( $row = $DB->fetch_row() ) {
            	$this->usercache[] = $row;
        }
        $this->users_are_init = TRUE;
		    
	}
        
	$cached = array();
        $online = array();
        foreach($this->usercache as $result) {
            if ( strstr( $result['id'], '_session' ) ) {
                if ( $mkportals->vars['spider_anon'] ) {
                    if ( $mkportals->member['mgroup'] == "4" ) {
						switch($result['location']) {
							case 'portale':
    							$online['portale'] .= "{$result['member_name']}*{$inter} \n";
    						break;
							case 'blog':
    							$online['blog'] .= "{$result['member_name']}*{$inter} \n";
    						break;
							case 'gallery':
    							$online['gallery'] .= "{$result['member_name']}*{$inter} \n";
    						break;
							case 'urlobox':
    							$online['urlobox'] .= "{$result['member_name']}*{$inter} \n";
    						break;
							case 'downloads':
    							$online['downloads'] .= "{$result['member_name']}*{$inter} \n";
    						break;
							case 'news':
    							$online['news'] .= "{$result['member_name']}*{$inter} \n";
    						break;
							case 'chat':
    							$online['chat'] .= "{$result['member_name']}*{$inter} \n";
    						break;
							case 'topsite':
    							$online['topsite'] .= "{$result['member_name']}*{$inter} \n";
    						break;
							case 'reviews':
    							$online['reviews'] .= "{$result['member_name']}*{$inter} \n";
    						break;
							default:
							$online['forum'] .= "{$result['member_name']}*{$inter} \n";
    						break;
						}
                    }
                } else {
					switch($result['location']) {
							case 'portale':
    							$online['portale'] .= "{$result['member_name']}{$inter} \n";
    						break;
							case 'blog':
    							$online['blog'] .= "{$result['member_name']}{$inter} \n";
    						break;
							case 'gallery':
    							$online['gallery'] .= "{$result['member_name']}{$inter} \n";
    						break;
							case 'urlobox':
    							$online['urlobox'] .= "{$result['member_name']}{$inter} \n";
    						break;
							case 'downloads':
    							$online['downloads'] .= "{$result['member_name']}{$inter} \n";
    						break;
							case 'news':
    							$online['news'] .= "{$result['member_name']}{$inter} \n";
    						break;
							case 'chat':
    							$online['chat'] .= "{$result['member_name']}{$inter} \n";
    						break;
							case 'topsite':
    							$online['topsite'] .= "{$result['member_name']}{$inter} \n";
    						break;
							case 'reviews':
    							$online['reviews'] .= "{$result['member_name']}{$inter} \n";
    						break;
							default:
							$online['forum'] .= "{$result['member_name']}{$inter} \n";
    						break;
						}
                }
            } else if ($result['member_id'] == 0 )
            {
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
            } else {
                if ( empty( $cached[ $result['member_id'] ] ) ) {
                    $cached[ $result['member_id'] ] = 1;

                    if ($result['login_type'] == 1) {
                        if ($mkportals->member['mgroup'] == 4) {

						switch($result['location']) {
							case 'portale':
    							$online['portale'] .= "<a class=\"uno\" href=\"$link_user{$result['member_id']}\">{$result['prefix']}{$result['member_name']}{$result['suffix']}</a>*{$inter} \n";
    						break;
							case 'blog':
    							$online['blog'] .= "<a class=\"uno\" href=\"$link_user{$result['member_id']}\">{$result['prefix']}{$result['member_name']}{$result['suffix']}</a>*{$inter} \n";
    						break;
							case 'gallery':
    							$online['gallery'] .= "<a class=\"uno\" href=\"$link_user{$result['member_id']}\">{$result['prefix']}{$result['member_name']}{$result['suffix']}</a>*{$inter} \n";
    						break;
							case 'urlobox':
    							$online['urlobox'] .= "<a class=\"uno\" href=\"$link_user{$result['member_id']}\">{$result['prefix']}{$result['member_name']}{$result['suffix']}</a>*{$inter} \n";
    						break;
							case 'downloads':
    							$online['downloads'] .= "<a class=\"uno\" href=\"$link_user{$result['member_id']}\">{$result['prefix']}{$result['member_name']}{$result['suffix']}</a>*{$inter} \n";
    						break;
							case 'news':
    							$online['news'] .= "<a class=\"uno\" href=\"$link_user{$result['member_id']}\">{$result['prefix']}{$result['member_name']}{$result['suffix']}</a>*{$inter} \n";
    						break;
							case 'chat':
    							$online['chat'] .= "<a class=\"uno\" href=\"$link_user{$result['member_id']}\">{$result['prefix']}{$result['member_name']}{$result['suffix']}</a>*{$inter} \n";
    						break;
							case 'topsite':
    							$online['topsite'] .= "<a class=\"uno\" href=\"$link_user{$result['member_id']}\">{$result['prefix']}{$result['member_name']}{$result['suffix']}</a>*{$inter} \n";
    						break;
							case 'reviews':
    							$online['reviews'] .= "<a class=\"uno\" href=\"$link_user{$result['member_id']}\">{$result['prefix']}{$result['member_name']}{$result['suffix']}</a>*{$inter} \n";
    						break;
							default:
							$online['forum'] .= "<a class=\"uno\" href=\"$link_user{$result['member_id']}\">{$result['prefix']}{$result['member_name']}{$result['suffix']}</a>*{$inter} \n";
    						break;
						}
							$online['anon']++;
                        } else {
                            $online['anon']++;
                        }
                    } else {
                        $online['members']++;
						switch($result['location']) {
							case 'portale':
    							$online['portale'] .= "<a class=\"uno\" href=\"$link_user{$result['member_id']}\">{$result['prefix']}{$result['member_name']}{$result['suffix']}</a>{$inter} \n";
    						break;
							case 'blog':
    							$online['blog'] .= "<a class=\"uno\" href=\"$link_user{$result['member_id']}\">{$result['prefix']}{$result['member_name']}{$result['suffix']}</a>{$inter} \n";
    						break;
							case 'gallery':
    							$online['gallery'] .= "<a class=\"uno\" href=\"$link_user{$result['member_id']}\">{$result['prefix']}{$result['member_name']}{$result['suffix']}</a>{$inter} \n";
    						break;
							case 'urlobox':
    							$online['urlobox'] .= "<a class=\"uno\" href=\"$link_user{$result['member_id']}\">{$result['prefix']}{$result['member_name']}{$result['suffix']}</a>{$inter} \n";
    						break;
							case 'downloads':
    							$online['downloads'] .= "<a class=\"uno\" href=\"$link_user{$result['member_id']}\">{$result['prefix']}{$result['member_name']}{$result['suffix']}</a>{$inter} \n";
    						break;
							case 'news':
    							$online['news'] .= "<a class=\"uno\" href=\"$link_user{$result['member_id']}\">{$result['prefix']}{$result['member_name']}{$result['suffix']}</a>{$inter} \n";
    						break;
							case 'chat':
    							$online['chat'] .= "<a class=\"uno\" href=\"$link_user{$result['member_id']}\">{$result['prefix']}{$result['member_name']}{$result['suffix']}</a>{$inter} \n";
    						break;
							case 'topsite':
    							$online['topsite'] .= "<a class=\"uno\" href=\"$link_user{$result['member_id']}\">{$result['prefix']}{$result['member_name']}{$result['suffix']}</a>{$inter} \n";
    						break;
							case 'reviews':
    							$online['reviews'] .= "<a class=\"uno\" href=\"$link_user{$result['member_id']}\">{$result['prefix']}{$result['member_name']}{$result['suffix']}</a>{$inter} \n";
    						break;
							default:
							$online['forum'] .= "<a class=\"uno\" href=\"$link_user{$result['member_id']}\">{$result['prefix']}{$result['member_name']}{$result['suffix']}</a>{$inter} \n";
    						break;
						}

                    }
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
		global $DB, $mklib, $mkportals, $ipsclass;
		$limit = 5;
		$taglio = 17;

		//$DB->query("SELECT id, password, permission_array FROM ibf_forums");
		//while( $f = $DB->fetch_row() ) {
		foreach( $ipsclass->cache['forum_cache'] as $f )
		{	
			//$perms = unserialize(stripslashes($f['permission_array']));
//-- mod_ipb20 begin
            if ($this->check_permissions($f['show_perms']) != TRUE or ($f['password'] != "" ) ) {
//-- mod_ipb20 end
        		$bad[] = $f['id'];
        	}	else {
        		$good[] = $f['id'];
        	}
        }

		if ( count($bad) > 0 ) {
    		$qe = " AND forum_id NOT IN(".implode(',', $bad ).") ";
    	}

		$DB->query("SELECT tid, title, posts, starter_id as member_id, starter_name as member_name, start_date as post_date, views
 		            FROM ibf_topics
 		            WHERE state!='closed' AND approved=1 AND (moved_to IS NULL or moved_to='') $qe
 		            ORDER BY start_date DESC LIMIT 0,$limit");


		while ( $post = $DB->fetch_row() ) {
  		$title = strip_tags($post['title']);
		$title = str_replace( "&#33;" , "!" ,$title );
		$title = str_replace( "&quot;", "\"", $title );
			if (strlen($title) > $taglio) {
				$title = substr( $title,0,($taglio - 3) ) . "...";
				$title = preg_replace( '/&(#(\d+;?)?)?(\.\.\.)?$/', '...',$title );
			}

 		$date  = $mklib->create_date($post['post_date']);
		$tid = $post['tid'];

		$mid = $post['member_id'];
		$mname = $post['member_name'];

		$content .= "
				<tr>
				  <td width=\"100%\" class=\"tdblock\">
				  <a class=\"uno\" href=\"$mkportals->forum_url/index.php?showtopic=$tid\">$title</a>
				  </td>
				</tr>
				<tr>
				  <td class=\"tdglobal\">
				  <a class=\"uno\" href=\"$mkportals->forum_url/index.php?showuser=$mid\">$by: $mname</a><br /> $sdate: $date
				  </td>
				</tr>
		";
 		}

		return $content;


	}

	function get_forum_list()
	{
		global $mklib, $DB;

		$DB->query("SELECT id, name FROM ibf_forums WHERE parent_id > '0' ORDER BY id");

		while( $board = $DB->fetch_row() ) {
			$cselect[] = $board;
		}

		return $cselect;
	}
	function get_board_news()
 	{
		global $DB, $mklib, $mkportals, $ipsclass;

		$limit = $mklib->config['bnews_block'];
		//$taglio = 17;
		$news_words= $mklib->config['bnews_words'];
		$db_prefix = DBPREFIX;
		$forum_active = unserialize($mklib->config['forum_active']);
		if(!$forum_active) {
				return "";
		}


		$DB->query("SELECT t.*, p.*, p.icon_id as icona, f.name as forum_name, m.id as member_id, m.members_display_name as member_name
		FROM ibf_posts p
		LEFT JOIN ibf_topics t on (t.tid=p.topic_id and t.topic_firstpost=p.pid and t.approved=1 and t.moved_to IS NULL)
		LEFT JOIN ibf_members m on (p.author_id=m.id)
		
		LEFT JOIN ibf_forums f on (t.forum_id=f.id)
		WHERE t.forum_id IN (".implode(',', $forum_active ).")
		GROUP BY p.topic_id
		ORDER BY t.start_date DESC
		LIMIT $limit");

		while ( $post = $DB->fetch_row() ) {
		$avatar = "";
  		$title = strip_tags($post['title']);
		$title = str_replace( "&#33;" , "!" ,$title );
		$title = str_replace( "&quot;", "\"", $title );

 		$date  = $mklib->create_date($post['start_date']);
		$tid = $post['tid'];

		$mid = $post['member_id'];
		$mname = $post['member_name'];
		$testo = $post['post'];
		if ($news_words) {
			$testo = substr ($testo, 0, $news_words);
			$testo .= " ...";
   		}
		$testo = str_replace("style_emoticons/<#EMO_DIR#>", "style_emoticons/default", $testo); // IPB 2.2

		//$testo = str_replace("style_emoticons/<#EMO_DIR#>", "$mkportals->forum_url/style_emoticons/default", $testo); // IPB 2.1
		//$testo = doUBBC($testo, "1");
		$fname = $post['forum_name'];
		if(!$post['icona']) {
			$post['icona'] = "11";
		}
		$numreplies = $post['posts']." ".$mklib->lang['replies'];
		$icona = $mkportals->forum_url."/style_images/1/folder_post_icons/icon".$post['icona'].".gif";
		/*if ( $post['avatar_type'] == 'local' && $post['avatar_location'])
			{
				$avatar = "<img src=\"$mklib->siteurl/$mklib->forumpath/style_avatars/{$post['avatar_location']}\" border=\"0\" alt=\"\" />";
			}
			if ( $post['avatar_type'] == 'url' )
			{
				$dimension = explode("x", $post['avatar_size']);
				$avatar = "<img src=\"{$post['avatar_location']}\" border=\"0\" width=\"$dimension[0]\" height=\"$dimension[1]\" alt=\"\" />";
			}
			if ( $post['avatar_type'] == 'upload' )
  			{
   				$upload_url = $ipsclass->vars['upload_url'];
				$post['avatar_location'] = str_replace("upload:", "", $post['avatar_location']);
  				$avatar = "$mklib->forumpath/uploads/{$post['avatar_location']}";
   				$dimension = explode("x", $post['avatar_size']);
   				$avatar = "<img src=\"$upload_url/{$post['avatar_location']}\" width=\"$dimension[0]\" height=\"$dimension[1]\" border=\"0\" alt=\"\" />";
  			}
			if (!$avatar)
  			{
				$avatar = "<img hspace=\"0\" src=\"$icona\" align=\"bottom\" border=\"0\" alt=\"\" />";
			}*/
			$avatar = "<img hspace=\"0\" src=\"$icona\" align=\"bottom\" border=\"0\" alt=\"\" />";
		$out .= "
				  <table class=\"tabnews\" cellspacing=\"2\" cellpadding=\"2\" width=\"100%\">
				    <tbody>
				    <tr>
				        
				      <td class=\"tdblock\" valign=\"middle\" align=\"center\" width=\"95%\">
				      <b>$fname<br /><a href=\"$mkportals->forum_url/index.php?showtopic=$tid\">$title</a></b>
				      <br /><div class=\"mkalign2\" style='font-style: italic; font-weight: normal;'><a href=\"$mkportals->forum_url/index.php?showtopic=$tid\">$numreplies</a>&nbsp;</div>
				      </td>
				    </tr>
				    <tr>
				      <td colspan=\"2\"><br />
				      $testo
				      </td>
				    </tr>
				    <tr>
				      <td class=\"mkalign2\" colspan=\"2\">
				      <br /><i>{$mklib->lang['from']}<b> <a href=\"$mkportals->forum_url/index.php?showuser=$mid\">$mname</a></b>, $date <a href=\"$mkportals->forum_url/index.php?showtopic=$tid\"> [ {$mklib->lang['readall']} ]</a></i>
				      </td>
				    </tr>
				  </tbody>
				</table>
		";
 		}
		return $out;
	}
	function langselect() {
	
	global $DB, $mklib, $mkportals, $ipsclass;
	$content = "<form name=\"mklanglist\" action=\"post\">\n <select name=\"seleclang\" class=\"bgselect\" onchange=\"document.location.href=mklanglist.seleclang.options[this.selectedIndex].value\">\n";
	
	foreach ($ipsclass->cache['languages'] as $value) {
		
			$selected = "";
			if ($mkportals->member['language'] == $value['ldir']) {
				$selected = "selected=\"selected\"";
			}
			$content .= "\n<option value=\"{$mkportals->forum_url}/index.php?setlanguage=1&amp;langid={$value['ldir']}\" $selected >{$value['lname']}</option>";
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
        	return;
    	}
	function skinselect()
 	{
		global $mklib, $mkportals, $ipsclass;

		if (!$mkportals->member['id']) {
			return;
		}
		$templateslist .= "<form name=\"skinlist\" action=\"post\">\n <select name=\"selectskin\" class=\"bgselect\" onchange=\"document.location.href=skinlist.selectskin.options[this.selectedIndex].value\">\n";
		
		foreach( $ipsclass->cache['skin_id_cache'] as $sid => $data )
		{
			if ($data['set_skin_set_id'] == "1") {
				continue;
			}
			if ($data['set_hidden'] != "1") {
				$selected = "";
				if ($mkportals->member['theme'] == $data['set_skin_set_id']) {
					$selected = "selected=\"selected\"";
				}
				$data['set_name'] = str_replace("(Import)", "", $data['set_name']);
				if (strlen($data['set_name']) > 12 ) {
					$data['set_name'] = substr($data['set_name'], 0, 12);
				}
				$templateslist .= "\n<option value=\"$mklib->siteurl/index.php?skinid={$data['set_skin_set_id']}\" $selected >{$data['set_name']}</option>";
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

		if (!$mkportals->member['id']) {
			return;
		}
		$DB->query("SELECT  set_skin_set_id FROM ibf_skin_sets WHERE set_skin_set_id = '$skinid'");
		if ($DB->fetch_row()){
			$DB->query("UPDATE ibf_members SET skin ='$skinid' WHERE id = '{$mkportals->member['id']}'");
			$DB->close_db();
	 		Header("Location: $mkportals->forum_url/index.php");
			exit;
		}
	}

	function calendar_birth($chosen_month, $chosen_year)
 	{
		global $mkportals, $DB, $mklib;

		$birthdays = array();
		$DB->query("SELECT bday_day, bday_year, name FROM ibf_members WHERE bday_month='".$chosen_month."' ORDER BY lower(name) ASC");

    	while ($user = $DB->fetch_row()) {
       	 	$birthdays[ $user['bday_day'] ]++;
        	if ($birthdays[ $user['bday_day'] ] < 10) {
            	$tool_birthdays[$user['bday_day']] .=  $user['name']." (".($chosen_year - $user['bday_year']).")&nbsp;";
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
		$startt   = mktime( 0, 0, 0, $chosen_month, 1, $chosen_year);
		$endt   = mktime( 0, 0, 0, $chosen_month+1, 1, $chosen_year);
		$DB->query("SELECT event_id, event_calendar_id, event_title, event_member_id, event_private, event_perms, event_unix_from AS mmday FROM ibf_cal_events WHERE event_unix_from >='".$startt."' AND event_unix_from  <= '".$endt."' AND event_approved = '1'");

		while ( $event = $DB->fetch_row() ) {
			if ( $event['event_private'] == 1 ) {
        			if ($mkportals->member['id'] != $event['event_member_id']) {
           				continue;
            			}
        		}
       			if ( $event['event_perms'] != '*' ) {
       	     			if ( ! preg_match( "/(^|,)".$mkportals->member['mgroup']."(,|$)/", $event['event_perms'] ) ) {
         	      			 continue;
         	  		 }
       	 		}
       	 		$event['mday'] = intval(date("d", $event['mmday']));
			$events[ $event['mday'] ][] = $event;
       	 		$entry = substr($event['event_title'], 0, 20);
     	 		if ( strlen($event['event_title']) > 20 ) {
       	     			$entry .= "...";
       	 		}
       	 		$tool_events[$event['mday']] .= $entry."<br />";
    		}
		return array($events, $tool_events);
	}

	function import_css()
	{
		global $mkportals, $DB, $mklib;
		if ($mkportals->member['theme']) {
			$DB->query("SELECT set_image_dir, set_cache_css FROM ibf_skin_sets WHERE set_skin_set_id = '{$mkportals->member['theme']}'");
		}
		else {
			$DB->query("SELECT set_image_dir, set_cache_css FROM ibf_skin_sets WHERE set_default = '1'");
		}
		$r = $DB->fetch_row();
		$images_url = $r['set_image_dir'];

		$css2 = $r['set_cache_css'];
		unset ($r);
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
				$css = preg_replace( "`(\.importbody(.*?\}))`is", $mkpsubs, $css);
			}

		//importing logostrip
		$sflogo =  $mkportals->forum_url."/style_images/".$images_url."/sf_logo.jpg";
		if (is_file("$sflogo") ) {
			$mkpsubs = "#logostrip {background-image: url(style_images/".$images_url."/sf_logo.jpg); text-align: left;}";
		} else {
			$pos = strpos($css2, "#logostrip");
			$pos2 = strpos($css2, "}", $pos);
		$mkpsubs = substr($css2, $pos, ($pos2 - ($pos -1)));
		}
		$css = preg_replace( "`(\#importlogostrip(.*?\}))`is", $mkpsubs, $css);

		/*
		//importing main table bg (if different than body bg)
		$pos = strpos($css2, "body");
		$pos2 = strpos($css2, "}", $pos);
		$mkpsubs = substr($css2, $pos, ($pos2 - ($pos -1)));
		$css = preg_replace( "`(\.importmain(.*?\}))`is", $mkpsubs, $css);
		*/

		//importing light background
		$pos = strpos($css2, ".post1");
		$pos2 = strpos($css2, "}", $pos);
			if ($pos) {
				$mkpsubs = substr($css2, $pos, ($pos2 - ($pos -1)));
				$css = preg_replace( "`(\.importlightback(.*?\}))`is", $mkpsubs, $css);
			}

		//importing medium background
		$pos = strpos($css2, ".row1");
		$pos2 = strpos($css2, "}", $pos);
			if ($pos) {
				$mkpsubs = substr($css2, $pos, ($pos2 - ($pos -1)));
				$css = preg_replace( "`(\.importmediumback(.*?\}))`is", $mkpsubs, $css);
			}

		//importing dark background
		$pos = strpos($css2, ".maintitle");
		$pos2 = strpos($css2, "}", $pos);
			if ($pos) {
				$mkpsubs = substr($css2, $pos, ($pos2 - ($pos -1)));
				$css = preg_replace( "`(\.importdarkback(.*?\}))`is", $mkpsubs, $css);
			}

		//importing module table headers
		$pos = strpos($css2, ".subtitle");
		$pos2 = strpos($css2, "}", $pos);
			if ($pos) {
				$mkpsubs = substr($css2, $pos, ($pos2 - ($pos -1)));
				$css = preg_replace( "`(\.importmodulex(.*?\}))`is", $mkpsubs, $css);
			}
		
		//importing borders
		$pos = strpos($css2, ".borderwrap");
		$pos2 = strpos($css2, "}", $pos);
			if ($pos) {
				$mkpsubs = substr($css2, $pos, ($pos2 - ($pos -1)));		
				$mkpsubs = preg_replace( "/back(.*?\;)/mi", "", $mkpsubs);
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
		$pos = strpos($css2, "td.ipbtable");
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
		$css = str_replace( "url(", "url(".$mklib->siteurl."/".$mklib->forumpath."/", $css);
		$css = str_replace( "<#IMG_DIR#>", $images_url, $css);
		//$css = str_replace ("MKPORTALIMGDIR", "$mklib->images", $css);
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
		$DB->query("SELECT email FROM ibf_members WHERE id = '$iduser'");
		$row = $DB->fetch_row();
		$dest = $row['email'];		
		mail($dest, $subject, $message,  $headers);
	}
	function admin_mail($subject, $message)
 	{
		global $DB, $mklib;

		$headers = "From: webmaster@" . $mklib->sitename . "\r\n" . "Reply-To: webmaster@" . $mklib->sitename . "\r\n" . "X-Mailer: MKportal Mail";
		$dest = "";
		$DB->query("SELECT email FROM ibf_members WHERE mgroup = '4'");
		while ( $row = $DB->fetch_row() ) {
			$dest .= $row['email'].", ";
		}
		$dest=rtrim($dest, ", ");
		mail($dest, $subject, $message,  $headers);
	}

    function check_permissions($permission = "") {
        global $mkportals;
        
        $perm_ids = explode( ",", $mkportals->member['g_perm_id'] );
        if (!is_array( $perm_ids))
            return FALSE;
        if ($permission == "" ) 
            return FALSE;
        else if ($permission == '*' )
            return TRUE;
        else {
            $permission_array = explode(",", $permission);
            foreach($perm_ids as $user_id ) {
                if (in_array( $user_id, $permission_array) )
                    return TRUE;
            }
            return FALSE;
        }
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
