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
		global $mkportals, $DB, $dbtables;
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
		
			$DB->query("UPDATE ".$dbtables['sessions']." SET last_activity ='$location' WHERE sid = '" .$mkportals->member['session_id'] ."'");
		
	}

	function get_active_users($loc) {

		global $DB, $mkportals, $mklib, $dbtables;
		$loc = trim($loc, ",");
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

			$qresult = $DB->query("SELECT DISTINCT s.uid, s.last_activity, u.id, u.username, s.anonymous,
					ug.mem_gr_colour
					FROM ".$dbtables['sessions']." s
					LEFT JOIN ".$dbtables['users']." u ON (s.uid = u.id)
					LEFT JOIN ".$dbtables['user_groups']." ug ON (u.u_member_group = ug.member_group)
					WHERE s.last_activity='$location'
					AND s.time > '$time'
					ORDER BY s.time DESC, u.username ASC");

			$cached = array();
			$active = array( 'guests' => 0, 'anon' => 0, 'members' => 0, 'names' => "");
			while ($result = $DB->fetch_row() ) {
				
				if ($result['uid'] == -1) {
					$active['guests']++;
				} else {
					if (empty( $cached[ $result['uid'] ] ) ) {
						$cached[ $result['uid'] ] = 1;
						if ($result['anonymous']) {
							if ( $mkportals->member['view_anonymous']) {
								$active['names'] .= "<a href=\"{$mkportals->base_url}?mid={$result['uid']}\" style=\"color: {$result['mem_gr_colour']}\">{$result['username']}</a>*, ";
								$active['anon']++;
							} else {
								$active['anon']++;
							}
						} else {
							$active['members']++;
							$active['names'] .= "<a href=\"{$mkportals->base_url}?mid={$result['uid']}\" style=\"color: {$result['mem_gr_colour']}\">{$result['username']}</a>, ";
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

 		global $mkportals, $DB, $std, $Skin, $mklib, $dbtables;
		$css = $this->import_css();
		$DB->query("SELECT smcode AS code, smfile, smfolder FROM ".$dbtables['smileys']." ORDER BY smid");
        $output = $this->get_emo_header($css);
        $countr = 0;
        if ( $DB->get_num_rows() ) {
            while ( $r = $DB->fetch_row() ) {
		$image = "smileys/". $r['smfolder'] . "/" . $r['smfile'];
                if ($countr == 3) {
                    $output .= "</tr><tr>";
                    $countr = 0;
                }
                $output .= $this->get_emo_row($r['code'], $image);
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

		global $mkportals, $DB, $mklib, $dbtables;

		$DB->query("SELECT smcode, smfile, smfolder FROM ".$dbtables['smileys']." ORDER BY smid DESC");
		while ( $r = $DB->fetch_row() )
		{
			$code = stripslashes($r['smcode']);
			$image = stripslashes($r['smfile']);
			$spath = stripslashes($r['smfolder']);
			$image = "<img src=\"$mklib->siteurl/$mklib->forumpath/smileys/$spath/$image\" border=\"0\" alt=\"\" />";
			$message = str_replace($code, $image, $message);
		}

		return $message;
	}

// Not used at the present in AEF
	function popup_pm($m1, $m2, $m3, $m4)
 	{
/*
		global $DB, $mklib, $mkportals;

		$u1 = "$mklib->siteurl/$mklib->forumpath/index.php?act=Msg";

		$DB->query("UPDATE ibf_members SET show_popup=0 WHERE id={$mkportals->member['id']}");


		$pmk_js = "<script type=\"text/javascript\">
     				<!--
       				window.open('$mklib->siteurl/mkportal/pmpopup.php?m1=$m1&amp;m2=$m2&amp;m3=$m3&amp;m4=$m4&amp;u1=$u1','NewPM','width=500,height=250,resizable=yes,scrollbars=yes');
     				//-->
     				</script>";


*/
		return $pmk_js;
	}

// admin functions

	//ad_perms
	function build_grouplist($ind)
	{
		global $DB, $dbtables;

		$DB->query( "SELECT member_group,  mem_gr_name, post_count FROM ".$dbtables['user_groups']." ORDER BY `member_group` ASC");
		while( $row = $DB->fetch_row() ) {
			if($row['member_group'] == 1 || $row['post_count'] != -1) {
				continue;
			}
			$g_id= $row['member_group'];
			$g_title = $row['mem_gr_name'];
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
		global $DB, $dbtables;

		$group = array();
		$DB->query( "SELECT member_group, mem_gr_name, post_count FROM ".$dbtables['user_groups']." ORDER BY `member_group` ASC");
		while( $row = $DB->fetch_row() ) {
			if($row['member_group'] == 1  || $row['post_count'] != -1) {
				continue;
			}
			$g_id = $row['member_group'];
			$group[$g_id][id] = $row['member_group'];
			$group[$g_id][title] = $row['mem_gr_name'];
		}

		return $group;
	}
	
	//ad_perms
	function update_groupperms($g_id)
	{
		global $DB, $dbtables;

		$query = $DB->query( "SELECT mem_gr_name FROM ".$dbtables['user_groups']." WHERE member_group = '$g_id'");
		$row = $DB->fetch_row($query);
		return $row['mem_gr_name'];

	}

	//ad_poll
	function get_poll_list()
	{
		global $mklib, $DB, $dbtables;

		$poll_active = $mklib->config['poll_active'];
		$DB->query("SELECT poid, poll_tid, poll_qt FROM ".$dbtables['polls']." ORDER BY poid DESC LIMIT 30");

        	if ($DB->get_num_rows()) {
    			while( $poll = $DB->fetch_row() ) {
    				$id = $poll['poid'];
    				$title = $poll['poll_qt'];
    				$selected = "";
    				if($id == $poll_active) {
    					$selected = "selected=\"selected\"";
    				}
    			$cselect.= "<option value=\"$id\" $selected>$title</option>\n";
    			}
       	 	} else {
            		$cselect.= "<option value=\"0\"></option>\n";
        	}

		return $cselect;
	}

//blocks functions

	function forum_link($link)
	{

		global $mklib, $mkportals;
		
		$return = 0;
		if (!strpos(getcwd(), "aeforum")) {
			$return = 1;
		}
		switch($link) {
			case 'profile':
    			$out = "{$mkportals->forum_url}/index.php?mid";
    		break;
			case 'cpaforum':
    			$out = "{$mkportals->forum_url}/index.php?act=admin&amp;adact=conpan";
    		break;
			case 'cpapers':
    			$out = "{$mkportals->forum_url}/index.php?act=usercp";
    		break;
			case 'pm':
    			$out = "{$mkportals->forum_url}/index.php?act=usercp&amp;ucpact=inbox";
    		break;
			case 'forumsearch':
    			$out = "{$mkportals->forum_url}/index.php?act=search";
    		break;
			case 'logout':
    			$out = "{$mkportals->forum_url}/index.php?act=logout&amp;mk_return=$return";
    		break;
			case 'postlink':
    			$out = "{$mkportals->forum_url}/index.php?act=login";
    		break;
			case 'postlink2':
    			$out = "";
    		break;
			case 'register':
    			$out = "{$mkportals->forum_url}/index.php?act=register";
    		break;
			case 'onlinelist':
    			$out = "{$mkportals->forum_url}/index.php?act=active";
    		break;
			case 'login_extra':
    			$out = "<tr>
                   <td class=\"tdblock mkalign1\" width=\"100%\" colspan=\"2\"><b>{$mklib->lang['anon_login']}</b>&nbsp;<input type=\"checkbox\" name=\"anonymously\" value=\"1\" style=\"margin:0px;\" />
				<input type=\"hidden\" name=\"login\" value=\"1\" />
				<input type=\"hidden\" name=\"remember\" value=\"1\" />
				<input type=\"hidden\" name=\"mk_return\" value=\"$return\" />
				  </td>
				</tr>
				";
    		break;
			case 'login_user':
    			$out = "username";
    		break;
			case 'login_passw':
    			$out = "password";
    		break;
			case 'calendar_event':
    			$out = "$mklib->siteurl/index.php?";
    		break;
			default:
    			$out = "n/a";
    		break;
    		}

		return $out;

	}

	function get_poll_active($poid)
 	{

		global $DB, $mklib, $mkportals, $dbtables;

		if (!$poid) {
            		return;
        	}
		$user_voted = 0;	
		$total_votes = 0;
		$logged_in = 0;		
        	if ( $mkportals->member['id'] ) {
			$logged_in = 1;
        	}
		$qresult = $DB->query("SELECT po.*, poo.*".($logged_in ? ", pv.*" : "")."
		FROM ".$dbtables['polls']." po
		LEFT JOIN ".$dbtables['poll_options']." poo ON (po.poid = poo.poo_poid)
		".($logged_in ? "LEFT JOIN ".$dbtables['poll_voters']." pv ON (
										pv.pv_mid = '".$mkportals->member['id']."' 
										AND pv.pv_pooid = poo.pooid 
										AND pv.pv_poid = po.poid)" : "")."
		WHERE  po.poid = '".$poid."'");
		if(mysql_num_rows($qresult) < 1){
			return "";
		}
		for($p=1; $p <= mysql_num_rows($qresult); $p++){
			$poll_opt[$p] = mysql_fetch_assoc($qresult);
			$options[$p] = array('pooid' => $poll_opt[$p]['pooid'],
							'poo_option' => $poll_opt[$p]['poo_option'],
							'poo_votes' => $poll_opt[$p]['poo_votes']
							);

			$total_votes = $total_votes + $poll_opt[$p]['poo_votes'];
			if($logged_in && $poll_opt[$p]['pv_mid']){
				$user_voted = $poll_opt[$p]['pooid'];
			}
		}
		$poll = array('poid' => $poll_opt[1]['poid'],
				'qt' => $poll_opt[1]['poll_qt'],
				'mid' => $poll_opt[1]['poll_mid'],
				'locked' => $poll_opt[1]['poll_locked'],
				'tid' => $poll_opt[1]['poll_tid'],
				'expiry' => $poll_opt[1]['poll_expiry'],//Must be whole No.
				'expired' => 0,
				'change_vote' => $poll_opt[1]['poll_change_vote'],
				'started' => $poll_opt[1]['poll_started'],
				'votes' => $total_votes,
				'user_voted' => $user_voted,
				'show_when' => $poll_opt[1]['poll_show_when'],
				'what_to_show' => 0,
				'options' => $options
				);

		unset($options);
		unset($poll_opt);

		$show_poll = (time() > $poll['started'] + ($poll['expiry'] * 24 * 60 * 60)) ? 1 : 0 ;
		
        	if ( $poll['state'] == 'locked' ) {
            		$controllo = 1;
            		$poll_footer = "<tr><td>{$mklib->lang['poll_closed']}";
        	} else if (! $mkportals->member['id'] ) {
            		$controllo = 1;
			$poll_footer = "<tr><td>{$mklib->lang['poll_noallow']}";
		} else if ( $poll['show_when'] == "2" && $show_poll != 1 ) { //show results after poll expires
			$controllo = 2;
			$end_poll = $mklib->create_date($poll['started'] + ($poll['expiry'] * 24 * 60 * 60));		
			$poll_footer = "<tr><td class=\"tdglobal\">{$mklib->lang['poll_future']} {$end_poll}";
        	} else if ( $poll['user_voted'] ) {
            		$controllo = 1;
            		$poll_footer = "<tr><td class=\"tdglobal\">{$mklib->lang['poll_voted']}";
        	} else {
            		$controllo = 0;
            		$poll_footer = "<input type=\"submit\" name=\"vote_poll\" value=\"{$mklib->lang['poll_vote']}\" class=\"mkbutton\" style=\"margin-top: 10px;\" /></form>";
        	}
        	if ($controllo == 1 || ($controllo == 2 && $show_poll == 1) ) {
            		$output = "            
				<tr>
				  <td class=\"tdblock\">
				  <a class=\"uno\" href=\"$mkportals->forum_url/index.php?tid={$poll['tid']}\">{$poll['qt']}</a>
				  </td>
				</tr>
            ";
		foreach($poll['options'] as $opk => $opt){
				if($poll['votes'] != 0){
					$percentage = ($opt['poo_votes']/$poll['votes'])*100;
					$img_width = (130*$percentage)/100;
					$img_width = (($img_width > 0) ? $img_width : 0);
				}else{
					$img_width = 0;
					$percentage = 0;
				}
                $output .= "
				<tr>
				  <td class=\"tdglobal\">
				  <b>{$opt['poo_option']}</b>
				  </td>
				</tr>
				<tr>
				 <td class=\"tdglobal\">
				  <div align=\"left\" style=\"width: 138px; background-color: #FFFDEF; border: solid 1px;\">
				  	<img src=\"$mklib->images/bar-start.gif\" border=\"0\" width=\"4\" height=\"11\" alt=\"\" /><img src=\"$mklib->images/bar.gif\" border=\"0\" width=\"$img_width\" height=\"11\" alt=\"\" /><img src=\"$mklib->images/bar-end.gif\" border=\"0\" width=\"4\" height=\"11\" alt=\"\" />
				  </div>
				  </td>
				</tr>
                ";
            }
		} 

		if ($controllo === 0) {

            $output = "		<tr>
				  <td class=\"tdblock\">
				  <a  class=\"uno\" href=\"$mkportals->forum_url/index.php?tid={$poll['tid']}\">{$poll['qt']}</a>
				  </td>
				</tr>
				<tr>
				  <td class=\"tdglobal\" style=\"padding: 2px\">
				    <form action=\"$mkportals->forum_url/index.php?tid={$poll['tid']}\" method=\"post\">
            ";

            foreach($poll['options'] as $opk => $opt) {
                $output   .= "
				    <div style=\"margin-top: 5px;\"><input type=\"radio\" name=\"uservote\" value=\"{$opt['pooid']}\" class=\"bgselect\" />&nbsp;<strong>{$opt['poo_option']}</strong></div>                    
                ";
            }
        }

         $output   .= "	                       
              $poll_footer	      
				  </td>
				</tr>
				<tr>
				  <td class=\"tdblock\">
				  <span class=\"mktxtcontr\">" .$poll['votes']."</span> {$mklib->lang['poll_totalvotes']}
				  </td>
				</tr>                    
                ";

         return $output;

	}

	function get_avatar()
 	{
		global $mkportals;

			$avatarfile = "";
			if($mkportals->member['avatar']) {
					$av = array('avatar' => $mkportals->member['avatar'],
						'avatar_type' => $mkportals->member['avatartype'],
						'avatar_width' => $mkportals->member['avatar_width'],
						'avatar_height' => $mkportals->member['avatar_height'],
					);
				$avatar = urlifyavatar(100, $av);
				$avatarfile = "<img src=\"{$avatar[0]}\" alt=\"\" border=\"\" />";
			}
			return $avatarfile;


	}

//Meo: used in Ajax Preview
	function get_avatar_onid ($uid) {
		
		global $mklib, $dbtables, $DB;
		$DB->query("
        	SELECT id, username, avatar, avatar_type, avatar_width, avatar_height
        	FROM ".$dbtables['users']."
 		WHERE id = '$uid'
        	");
		$post = $DB->fetch_row();
		if ($post['avatar']) {
			$av = array('avatar' => $post['avatar'],
			'avatar_type' => $post['avatar_type'],
			'avatar_width' => $post['avatar_width'],
			'avatar_height' => $post['avatar_height'],
			);	
			$avatar = urlifyavatar(100, $av);
			$avatar_file = $avatar[0];
		} else {
			$avatar_file = "{$mklib->images}/noavatar.jpg";
		}
		
		return array($post['username'], $avatar_file);
	}

	//Meo: used in Ajax Preview
	function get_single_post ($pid) {
		global $mkportals, $mklib, $dbtables, $DB;

		// build the list of the forum that the user can view
		$DB->query("SELECT fid, member_group FROM ".$dbtables['forums']."");
		while( $row = $DB->fetch_row() ) {
			$all_mem = explode(',' , $row['member_group']);
			if(!in_array($mkportals->member['mgroup'], $all_mem) && !$mkportals->member['g_access_cp']){
        			$bad[] = $row['fid'];
        		} else {
        			$good[] = $row['fid'];
        		}
        	}
 		if ( count($bad) > 0 ) {
    			$qe = " AND p.post_fid NOT IN(".implode(',', $bad ).") ";
    		}
		$DB->query("
        		SELECT p.pid, p.post_tid, p.post_fid, p.post, t.tid, t.topic as title
        		FROM ".$dbtables['posts']." p
        		LEFT JOIN ".$dbtables['topics']." t on (t.tid=p.post_tid)
 			WHERE p.pid = '$pid' $qe
        	");
		$row = $DB->fetch_row();

		$testo = format_text($row['post']);
		$testo = parse_special_bbc($testo);
		$testo = parse_br($testo);
		$testo = smileyfy($testo); 

		return array($row['title'], $testo);
	}


	function get_forumnav()
 	{

		global $mklib, $mkportals, $Skin;

		$out = "<tr><td class=\"tdblock\">";
		$out .= $Skin->row_link_block(!$mklib->config['noicons'] ? "<img class=\"mkicon\" src=\"$mklib->images/atb_npost.gif\" style=\"vertical-align: middle\" align=\"left\" alt=\"{$mklib->lang['m_newpost']}\" />" : "", "href=\"{$mkportals->forum_url}/index.php?act=unread\"", $mklib->lang['m_newpost']);
		$out .= "</td></tr><tr><td class=\"tdblock\">";
		
		$out .= $Skin->row_link_block(!$mklib->config['noicons'] ? "<img class=\"mkicon\" src=\"$mklib->images/atb_members.gif\" style=\"vertical-align: middle\" align=\"left\" alt=\"{$mklib->lang['m_users']}\" />" : "", "href=\"{$mkportals->forum_url}/index.php?act=members\"", $mklib->lang['m_users']);
		$out .= "</td></tr><tr><td class=\"tdblock\">";

		$out .= $Skin->row_link_block(!$mklib->config['noicons'] ? "<img class=\"mkicon\" src=\"$mklib->images/atb_calendario.gif\" style=\"vertical-align: middle\" align=\"left\" alt=\"{$mklib->lang['onlineusers']}\" />" : "", "href=\"{$mkportals->forum_url}/index.php?act=active\"", $mklib->lang['onlineusers']);
		$out .= "</td></tr><tr><td class=\"tdblock\">";		

		$out .= $Skin->row_link_block(!$mklib->config['noicons'] ? "<img class=\"mkicon\" src=\"$mklib->images/atb_racconti.gif\" style=\"vertical-align: middle\" align=\"left\" alt=\"{$mklib->lang['news']}\" />" : "", "href=\"{$mkportals->forum_url}/index.php?act=news\"", $mklib->lang['news']);
		$out .= "</td></tr>";

		return $out;

	}
	function get_site_stat()
 	{
		global $DB, $dbtables;
		$sql = "SELECT COUNT(id) AS total
				FROM " .$dbtables['users']. "";
		$DB->query($sql);
		$row = $DB->fetch_row();
		$stat['members'] = $row['total'];
		$sql = "SELECT id, username
				FROM " .$dbtables['users']. "
				ORDER BY id DESC
				LIMIT 1";
		$DB->query($sql);
		$row = $DB->fetch_row();
		$stat['last_member'] = $row['id'];
		$stat['last_member_name'] = $row['username'];

		$sql = "SELECT COUNT(tid) AS total
				FROM " .$dbtables['topics']. "";
		$DB->query($sql);
		$row = $DB->fetch_row();
		$stat['topics'] = $row['total'];

		$sql = "SELECT COUNT(pid) AS total
				FROM " .$dbtables['posts']. "";
		$DB->query($sql);
		$row = $DB->fetch_row();
		$stat['total_posts'] = $row['total'];


		$stat['replies'] = $stat['total_posts'] - $stat['topics'];
		return $stat;


	}

	function get_onlineblock()
 	{
		global $DB, $mkportals, $dbtables;

		$time = (time() - 900);
		$qresult = $DB->query("SELECT DISTINCT s.uid, s.last_activity, u.id, u.username, s.anonymous,
					ug.mem_gr_colour
					FROM ".$dbtables['sessions']." s
					LEFT JOIN ".$dbtables['users']." u ON (s.uid = u.id)
					LEFT JOIN ".$dbtables['user_groups']." ug ON (u.u_member_group = ug.member_group)
					WHERE s.time > '$time'
					ORDER BY s.time DESC, u.username ASC");

			$cached = array();
			$active = array( 'guests' => 0, 'anon' => 0, 'members' => 0, 'names' => "");
			while ($result = $DB->fetch_row() ) {
				
				if ($result['uid'] == -1) {
					$active['guests']++;
				} else {
					if (empty( $cached[ $result['uid'] ] ) ) {
						$cached[ $result['uid'] ] = 1;
						if ($result['anonymous']) {
							if ( $mkportals->member['view_anonymous']) {
								$active['names'] .= "<a href=\"{$mkportals->base_url}?mid={$result['uid']}\" style=\"color: {$result['mem_gr_colour']}\">{$result['username']}</a>*, ";
								$active['anon']++;
							} else {
								$active['anon']++;
							}
						} else {
							$active['members']++;
							$active['names'] .= "<a href=\"{$mkportals->base_url}?mid={$result['uid']}\" style=\"color: {$result['mem_gr_colour']}\">{$result['username']}</a>, ";
						}
					}
				}
			}
			$active['names'] = preg_replace( "/,\s+$/", "" , $active['names'] );
		return array($active['members'], $active['anon'], $active['guests'], $active['names']);
	}


	function get_onlinehome($languest)
 	{
		global $DB, $mkportals, $dbtables;

		$content = "";
		$inter = ",";
		$time = (time() - 900);
		$DB->query("SELECT DISTINCT s.uid, s.ip, s.last_activity, u.id, u.username, s.anonymous,
					ug.mem_gr_colour
					FROM ".$dbtables['sessions']." s
					LEFT JOIN ".$dbtables['users']." u ON (s.uid = u.id)
					LEFT JOIN ".$dbtables['user_groups']." ug ON (u.u_member_group = ug.member_group)
					WHERE s.time > '$time'
					ORDER BY s.time DESC, u.username ASC");
		$online = array();
		$cached  = array();
		$online['members'] = 0;
		$online['guests'] = 0;
		$online['anon'] = 0;

		while ($result = $DB->fetch_row() ) {
				$result['location'] =  $result['last_activity'];
				if ($cached[ $result['uid'] ] && $result['uid'] != 0) {
							continue;
				}
				$cached[ $result['uid'] ] = 1;
				if ($result['uid'] == -1) {
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
				} else if ($result['anonymous']) {
						if ( $mkportals->member['view_anonymous']) {
							switch($result['location']) {
							case 'portale':
    							$online['portale'] .= "<a href=\"{$mkportals->forum_url}/index.php?mid={$result['uid']}\" style=\"color: {$result['mem_gr_colour']}\">{$result['username']}</a>*{$inter} \n";
    						break;
							case 'blog':
    							$online['blog'] .= "<a href=\"{$mkportals->forum_url}/index.php?mid={$result['uid']}\" style=\"color: {$result['mem_gr_colour']}\">{$result['username']}</a>*{$inter} \n";
    						break;
							case 'gallery':
    							$online['gallery'] .= "<a href=\"{$mkportals->forum_url}/index.php?mid={$result['uid']}\" style=\"color: {$result['mem_gr_colour']}\">{$result['username']}</a>*{$inter} \n";
    						break;
							case 'urlobox':
    							$online['urlobox'] .= "<a href=\"{$mkportals->forum_url}/index.php?mid={$result['uid']}\" style=\"color: {$result['mem_gr_colour']}\">{$result['username']}</a>*{$inter} \n";
    						break;
							case 'downloads':
    							$online['downloads'] .= "<a href=\"{$mkportals->forum_url}/index.php?mid={$result['uid']}\" style=\"color: {$result['mem_gr_colour']}\">{$result['username']}</a>*{$inter} \n";
    						break;
							case 'news':
    							$online['news'] .= "<a href=\"{$mkportals->forum_url}/index.php?mid={$result['uid']}\" style=\"color: {$result['mem_gr_colour']}\">{$result['username']}</a>*{$inter} \n";
    						break;
							case 'chat':
    							$online['chat'] .= "<a href=\"{$mkportals->forum_url}/index.php?mid={$result['uid']}\" style=\"color: {$result['mem_gr_colour']}\">{$result['username']}</a>*{$inter} \n";
    						break;
							case 'topsite':
    							$online['topsite'] .= "<a href=\"{$mkportals->forum_url}/index.php?mid={$result['uid']}\" style=\"color: {$result['mem_gr_colour']}\">{$result['username']}</a>*{$inter} \n";
    						break;
							case 'reviews':
    							$online['reviews'] .= "<a href=\"{$mkportals->forum_url}/index.php?mid={$result['uid']}\" style=\"color: {$result['mem_gr_colour']}\">{$result['username']}</a>*{$inter} \n";
    						break;
							default:
							$online['forum'] .= "<a href=\"{$mkportals->forum_url}/index.php?mid={$result['uid']}\" style=\"color: {$result['mem_gr_colour']}\">{$result['username']}</a>*{$inter} \n";
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
    							$online['portale'] .= "<a href=\"{$mkportals->forum_url}/index.php?mid={$result['uid']}\" style=\"color: {$result['mem_gr_colour']}\">{$result['username']}</a>{$inter} \n";
    						break;
							case 'blog':
    							$online['blog'] .= "<a href=\"{$mkportals->forum_url}/index.php?mid={$result['uid']}\" style=\"color: {$result['mem_gr_colour']}\">{$result['username']}</a>{$inter} \n";
    						break;
							case 'gallery':
    							$online['gallery'] .= "<a href=\"{$mkportals->forum_url}/index.php?mid={$result['uid']}\" style=\"color: {$result['mem_gr_colour']}\">{$result['username']}</a>{$inter} \n";
    						break;
							case 'urlobox':
    							$online['urlobox'] .= "<a href=\"{$mkportals->forum_url}/index.php?mid={$result['uid']}\" style=\"color: {$result['mem_gr_colour']}\">{$result['username']}</a>{$inter} \n";
    						break;
							case 'downloads':
    							$online['downloads'] .= "<a href=\"{$mkportals->forum_url}/index.php?mid={$result['uid']}\" style=\"color: {$result['mem_gr_colour']}\">{$result['username']}</a>{$inter} \n";
    						break;
							case 'news':
    							$online['news'] .= "<a href=\"{$mkportals->forum_url}/index.php?mid={$result['uid']}\" style=\"color: {$result['mem_gr_colour']}\">{$result['username']}</a>{$inter} \n";
    						break;
							case 'chat':
    							$online['chat'] .= "<a href=\"{$mkportals->forum_url}/index.php?mid={$result['uid']}\" style=\"color: {$result['mem_gr_colour']}\">{$result['username']}</a>{$inter} \n";
    						break;
							case 'topsite':
    							$online['topsite'] .= "<a href=\"{$mkportals->forum_url}/index.php?mid={$result['uid']}\" style=\"color: {$result['mem_gr_colour']}\">{$result['username']}</a>{$inter} \n";
    						break;
							case 'reviews':
    							$online['reviews'] .= "<a href=\"{$mkportals->forum_url}/index.php?mid={$result['uid']}\" style=\"color: {$result['mem_gr_colour']}\">{$result['username']}</a>{$inter} \n";
    						break;
							default:
							$online['forum'] .= "<a href=\"{$mkportals->forum_url}/index.php?mid={$result['uid']}\" style=\"color: {$result['mem_gr_colour']}\">{$result['username']}</a>{$inter} \n";
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
		
		global $DB, $mklib, $mkportals, $dbtables, $globals;
		$limit = 5;
		$taglio = 17;
// build the list of the forum that the user can view
		$DB->query("SELECT fid, member_group FROM ".$dbtables['forums']."");
		while( $row = $DB->fetch_row() ) {
			$all_mem = explode(',' , $row['member_group']);
			if(!in_array($mkportals->member['mgroup'], $all_mem) && !$mkportals->member['g_access_cp']){
        			$bad[] = $row['fid'];
        		} else {
        			$good[] = $row['fid'];
        		}
        	}

 		if ( count($bad) > 0 ) {
    			$qe = " AND p.post_fid NOT IN(".implode(',', $bad ).") ";
    		}

 		$DB->query("SELECT p.pid, p.post_tid, p.post_fid, p.poster_id, p.ptime, t.tid, t.topic as title, t.n_posts, t.t_status, u.id, u.username
 			FROM ".$dbtables['posts']." p
			LEFT JOIN ".$dbtables['topics']." t on (t.tid=p.post_tid)
			LEFT JOIN ".$dbtables['users']." u on (u.id = p.poster_id)
 		            WHERE t.t_status=1 $qe
 		            ORDER BY p.pid DESC LIMIT 0,$limit");

 		while ( $post = $DB->fetch_row() ) {
		$post['title'] = strip_tags($post['title']);
		$post['title'] = str_replace( "&#33;" , "!" , $post['title'] );
		$post['title'] = str_replace( "&quot;", "\"", $post['title'] );
			if (strlen($post['title']) > $taglio) {
				$post['title'] = substr( $post['title'],0,($taglio - 3) ) . "...";
				$post['title'] = preg_replace( '/&(#(\d+;?)?)?(\.\.\.)?$/', '...',$post['title'] );
			}

		$pid = $post['pid'];
		$tid = $post['post_tid'];
		$tpg = ceil(($post['n_posts'] + 1)/$globals['maxpostsintopics']);
		$title = $post['title'];
		$mid = $post['poster_id'];
		$mname = $post['username'];
        	$date  = $mklib->create_date($post['ptime']);

		$content .= "
				<tr>
				  <td width=\"100%\" class=\"tdblock\">
				  <a class=\"uno\" href=\"$mkportals->forum_url/index.php?tid=$tid&amp;tpg=$tpg#p$pid\">$title</a>
				  </td>
				</tr>
				<tr>
				  <td class=\"tdglobal\">
				  <a class=\"uno\" href=\"$mkportals->forum_url/index.php?mid=$mid\">$by: $mname</a><br /> $sdate: $date
				  </td>
				</tr>
		";
 		}

		return $content;
	}

	function get_forum_list()
	{
		global $mklib, $DB, $dbtables;

		$DB->query("SELECT fid AS id, fname AS name FROM ".$dbtables['forums']." ORDER BY fid");

		while( $board = $DB->fetch_row() ) {
			$cselect[] = $board;
		}

		return $cselect;

	}
	function get_board_news()
 	{
		global $DB, $mklib, $mkportals, $dbtables, $globals;

		$limit = $mklib->config['bnews_block'];
		$news_words= $mklib->config['bnews_words'];
	
		$forum_active = unserialize($mklib->config['forum_active']);
		if(!$forum_active) {
				return "";
		}		

// build the list of the forum that the user can view
		$DB->query("SELECT fid, member_group FROM ".$dbtables['forums']."");
		while( $row = $DB->fetch_row() ) {
			$all_mem = explode(',' , $row['member_group']);
			if(!in_array($mkportals->member['mgroup'], $all_mem) && !$mkportals->member['g_access_cp']){
        			$bad[] = $row['fid'];
        		} else {
        			$good[] = $row['fid'];
        		}
        	}

 		if ( count($bad) > 0 ) {
    			$qe = " AND p.post_fid NOT IN(".implode(',', $bad ).") ";
    		}

		$DB->query("
        	SELECT p.pid, p.post_tid, p.post_fid, p.poster_id, p.ptime, p.post, t.tid, t.first_post_id, t.topic as title, t.n_posts, t.t_status, u.id, u.username, u.avatar, u.avatar_type, u.avatar_width, u.avatar_height
        	FROM ".$dbtables['posts']." p
        	LEFT JOIN ".$dbtables['topics']." t on (t.tid=p.post_tid)
		LEFT JOIN ".$dbtables['users']." u on (u.id = p.poster_id)
 		WHERE p.pid = t.first_post_id AND p.post_fid IN (".implode(',', $forum_active ).") AND t.t_status!=2 $qe
        	GROUP BY p.post_tid
        	ORDER BY t.tid DESC
        	LIMIT $limit
        	");

		while ( $post = $DB->fetch_row() ) {
		$avatar = "";
  		$title = strip_tags($post['title']);
		$title = str_replace( "&#33;" , "!" ,$title );
		$title = str_replace( "&quot;", "\"", $title );

 		$date  = $mklib->create_date($post['ptime']);
		$tid = $post['post_tid'];
		$pid = $post['pid'];
		$mid = $post['id'];
		$mname = $post['username'];
		$testo = $post['post'];
		if ($news_words) {
			$testo = substr ($testo, 0, $news_words);
			$testo .= " ...";
   		}
		
		$testo = format_text($testo);
		$testo = parse_special_bbc($testo);
		$testo = parse_br($testo);
		$testo = smileyfy($testo); 
		$fname = "";
		$numreplies = $post['n_posts']." ".$mklib->lang['replies'];

		$icona = $mkportals->forum_url."/themes/default/images/icons/ok.png";
		$av = array('avatar' => $post['avatar'],
				'avatar_type' => $post['avatar_type'],
				'avatar_width' => $post['avatar_width'],
				'avatar_height' => $post['avatar_height'],
			);	
		$avatar = urlifyavatar(100, $av);
		$avatarfile = "<img src=\"{$avatar[0]}\" alt=\"\" border=\"\" />";
		if (!$post['avatar'])  {
			$avatarfile = "<img hspace=\"0\" src=\"$icona\" align=\"bottom\" border=\"0\" alt=\"\" />";
		}
		
		$out .= "
				    <table class=\"tabnews\" cellspacing=\"2\" cellpadding=\"2\" width=\"100%\">
				      <tbody>
				      <tr>
					<td class=\"tdblock\" align=\"center\" width=\"5%\">
					$avatarfile
					</td>
					<td class=\"tdblock\" valign=\"middle\" align=\"center\" width=\"95%\">
					<b>$fname<br /><a href=\"$mkportals->forum_url/index.php?tid=$tid\">$title</a></b>
					<br /><div class=\"mkalign2\" style='font-style: italic; font-weight: normal;'><a href=\"$mkportals->forum_url/index.php?tid=$tid\">$numreplies</a>&nbsp;</div>
					</td>
				      </tr>
				      <tr>
					<td colspan=\"2\"><br />
					$testo
					</td>
				      </tr>
				      <tr>
					<td class=\"mkalign2\" colspan=\"2\">
					<br /><i>{$mklib->lang['from']}<b> <a href=\"$mkportals->forum_url/index.php?mid=$mid\">$mname</a></b>, $date <a href=\"$mkportals->forum_url/index.php?tid=$tid\"> [ {$mklib->lang['readall']} ]</a></i>
					</td>
				      </tr>
				    </tbody>
				  </table>
		";
 		}

		return $out;
	}
// Meo: Added in C 1.2 
	function langselect() {
		global $mklib, $mkportals;
		$dir = @opendir("$mkportals->forum_url/languages");
		while($lang = readdir($dir)) {
			$ext = strtolower(get_extension($lang));
			if($lang != "." && $lang != ".."  && $lang != "index.html" && $ext != "php") {
				$languages[] = $lang;
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

// Meo: Added in C 1.2 
    function update_lang($langid) {

        global $mkportals, $DB, $mklib, $dbtables;

	$idu = $mkportals->member['id'];
	if (!$mkportals->member['id']) {
			return;
	}
	$DB->query("UPDATE ".$dbtables['users']." SET  language ='$langid' WHERE id = '{$mkportals->member['id']}'");
	$DB->close_db();
	Header("Location: $mkportals->forum_url/index.php");
	exit;

    }
	function skinselect()
 	{
		global $DB, $mklib, $mkportals, $dbtables;


		if (!$mkportals->member['id']) {
			return;
		}
		$templateslist .= "<form name=\"skinlist\" action=\"post\">\n <select name=\"selectskin\" class=\"bgselect\" onchange=\"document.location.href=skinlist.selectskin.options[this.selectedIndex].value\">\n";
		$DB->query("SELECT  thid, th_name FROM ".$dbtables['themes']."");
		while ( $r = $DB->fetch_row() ) {
			$selected = "";
			if ($mkportals->member['theme'] == $r['thid']) {
				$selected = "selected=\"selected\"";
			}
			if (strlen($r['th_name']) > 14 ) {
				$r['th_name'] = substr($r['title'], 0, 14);
			}
			$templateslist .= "\n<option value=\"$mklib->siteurl/index.php?skinid={$r['thid']}\" $selected >{$r['th_name']}</option>";

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
		global $mkportals, $DB, $mklib, $dbtables;

		if (!$mkportals->member['id']) {
			return;
		}
		$DB->query("SELECT thid FROM ".$dbtables['themes']." WHERE thid = '$skinid'");
		if ($DB->fetch_row()){
			$DB->query("UPDATE ".$dbtables['users']." SET user_theme ='$skinid' WHERE id = '{$mkportals->member['id']}'");
			$DB->close_db();
	 		Header("Location: $mkportals->forum_url/index.php");
			exit;
		}
	
	}

	function calendar_birth($chosen_month, $chosen_year) {

        	global $mkportals, $DB, $mklib, $dbtables;
        	$birthdays = array();
        	$DB->query("SELECT DAYOFMONTH(birth_date) AS bday_day, YEAR(birth_date) AS bday_year, username FROM ".$dbtables['users']." WHERE MONTH(birth_date)='".$chosen_month."' AND YEAR(birth_date) != '0001'");
       	 	while ($user = $DB->fetch_row()) {
                	$birthdays[ $user['bday_day'] ]++;
            		if ($birthdays[ $user['bday_day'] ] < 10) {
                		$tool_birthdays[$user['bday_day']] .=  $user['username']." (".($chosen_year - $user['bday_year']).")&nbsp;";
            		} else if ($birthdays[ $user['bday_day'] ] == 10) {
            			$tool_birthdays[$user['bday_day']] .=  "...";
            		}
        	}
        	return array($birthdays, $tool_birthdays);
    	}
		
	function calendar_events($chosen_month, $chosen_year)
 	{

// Not used in current AEF version.
/*
		global $mkportals, $DB, $mklib;
		$events = array();

    	$DB->query("SELECT eventid, title, userid, priv_event, read_perms, mday from ibf_calendar_events WHERE month='".$chosen_month."' AND year='".$chosen_year."'");

		while ( $event = $DB->fetch_row() ) {
			if ( $event['priv_event'] == 1 ) {
        		if ($mkportals->member['id'] != $event['userid']) {
           			continue;
            	}
        	}
       		if ( $event['read_perms'] != '*' ) {
       	     	if ( ! preg_match( "/(^|,)".$mkportals->member['mgroup']."(,|$)/", $event['read_perms'] ) ) {
         	       continue;
         	   }
       	 	}
       	 	$events[ $event['mday'] ][] = $event;
       	 	$entry = substr($event['title'], 0, 20);
     	 	if ( strlen($event['title']) > 20 ) {
       	     	$entry .= "...";
       	 	}
       	 	$tool_events[$event['mday']] .= $entry."<br />";
    	}
*/
		return array($events, $tool_events);
	}

	function import_css()
	{
		global $mkportals, $DB, $mklib, $dbtables;
		$skinid = $mkportals->member['theme'];
		$DB->query("SELECT * FROM ".$dbtables['themes']." t
				LEFT JOIN ".$dbtables['theme_registry']." tr ON (tr.thid = t.thid)
				WHERE t.thid = '$skinid'");

		$row = $DB->fetch_row();
		$tsets = aefunserialize($row['theme_registry']);
		$css2 = $tsets['path']."/style.css";
		$images_url = $tsets['images'];
		$images_url2 = $tsets['url'];
		unset ($row);
		unset ($tsets);
//print_r($tsets);
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

		//importing body
		$pos = strpos($css2, "body");
		$pos2 = strpos($css2, "}", $pos);
		$mkpsubs = substr($css2, $pos, ($pos2 - ($pos -1)));

		$css = preg_replace( "`(\.importbody(.*?\}))`is", $mkpsubs, $css);
			
/*
		//importing logostrip
		$sflogo =  $mkportals->forum_url."/style_images/".$images_url."/sf_logo.jpg";
		if (is_file("$sflogo") ) {
			$mkpsubs = "#logostrip {background-image: url(style_images/".$images_url."/sf_logo.jpg); text-align: left;}";
		} else {
			$pos = strpos($css2, ".header");
			$pos2 = strpos($css2, "}", $pos);
		$mkpsubs = substr($css2, $pos, ($pos2 - ($pos -1)));
		}
		$css = preg_replace( "`(\#importlogostrip(.*?\}))`is", $mkpsubs, $css);
*/

		//importing light background
		$pos = strpos($css2, ".ttmod");
		$pos2 = strpos($css2, "}", $pos);
			if ($pos) {
				$mkpsubs = substr($css2, $pos, ($pos2 - ($pos -1)));
				$mkpsubs = str_replace("rgb(240, 240, 240);", "#FFF", $mkpsubs);
				$mkpsubs = str_replace("height:40px;", "", $mkpsubs);
				$css = preg_replace( "`(\.importlightback(.*?\}))`is", $mkpsubs, $css);
			}

		//importing medium background
		$pos = strpos($css2, ".miimg");
		$pos2 = strpos($css2, "}", $pos);
			if ($pos) {
				$mkpsubs = substr($css2, $pos, ($pos2 - ($pos -1)));
				$mkpsubs = str_replace("000000", "444444", $mkpsubs);
				$mkpsubs = str_replace("}", " padding-top: 1px; padding-bottom: 1px;}", $mkpsubs);
				$css = preg_replace( "`(\.importmediumback(.*?\}))`is", $mkpsubs, $css);
			}

		//importing dark background
		$pos = strpos($css2, ".cbg");
		$pos2 = strpos($css2, "}", $pos);
			if ($pos) {
				$mkpsubs = substr($css2, $pos, ($pos2 - ($pos -1)));
				$mkpsubs = str_replace("height:26px;", "", $mkpsubs);
				$css = preg_replace( "`(\.importdarkback(.*?\}))`is", $mkpsubs, $css);
			}

		//importing module table headers
		$pos = strpos($css2, ".cbg1");
		$pos2 = strpos($css2, "}", $pos);
			if ($pos) {
				$mkpsubs = substr($css2, $pos, ($pos2 - ($pos -1)));
				$css = preg_replace( "`(\.importmodulex(.*?\}))`is", $mkpsubs, $css);
			}
		
		//importing borders
		$pos = strpos($css2, ".cbor");
		$pos2 = strpos($css2, "}", $pos);
			if ($pos) {
				$mkpsubs = substr($css2, $pos, ($pos2 - ($pos -1)));		
				$mkpsubs = preg_replace( "/back(.*?\;)/mi", "", $mkpsubs);
				$css = preg_replace( "`(\.importborders(.*?\}))`is", $mkpsubs, $css);
			}
/*
		//importing form styles
		$pos = strpos($css2, "input");
		$pos2 = strpos($css2, "}", $pos);
			if ($pos) {
				$mkpsubs = substr($css2, $pos, ($pos2 - ($pos -1)));
				$css = preg_replace( "`(\.importforms(.*?\}))`is", $mkpsubs, $css);
			}
*/
		//importing table font formatting
		$pos = strpos($css2, "table");
		$pos2 = strpos($css2, "}", $pos);
			if ($pos) {
				$mkpsubs = substr($css2, $pos, ($pos2 - ($pos -1)));
				$css = preg_replace( "`(\.importfont(.*?\}))`is", $mkpsubs, $css);
			}

		//importing hyperlink a:link style
		$pos = strpos($css2, ".forlink a:link");
		$pos2 = strpos($css2, "}", $pos);
			if ($pos) {
				$mkpsubs = substr($css2, $pos, ($pos2 - ($pos -1)));
				$css = preg_replace( "`(\.importlink(.*?\}))`is", $mkpsubs, $css);
			}

		//importing hyperlink a:visited style
		$pos = strpos($css2, ".forlink a:link");
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
		$css = str_replace( "url(", "url(".$images_url2."/", $css);
		$css = str_replace( "<#IMG_DIR#>", $images_url, $css);
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

		global $DB, $mklib, $dbtables;

		$headers = "From: webmaster@" . $mklib->sitename . "\r\n" . "Reply-To: webmaster@" . $mklib->sitename . "\r\n" . "X-Mailer: MKportal Mail";
		$dest = "";
		$DB->query("SELECT email FROM ".$dbtables['users']." WHERE id = '$iduser'");
		$row = $DB->fetch_row();
		$dest = $row['email'];

		mail($dest, $subject, $message,  $headers);

	}
	function admin_mail($subject, $message)
 	{

		global $DB, $mklib, $dbtables;

		$headers = "From: webmaster@" . $mklib->sitename . "\r\n" . "Reply-To: webmaster@" . $mklib->sitename . "\r\n" . "X-Mailer: MKportal Mail";
		$dest = "";
		$DB->query("SELECT email FROM ".$dbtables['users']." WHERE u_member_group = '1'");
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
            $image = $mklib->siteurl."/".$mklib->forumpath."/".$image;
        }
        $short = preg_replace("`^.*\/`", "", $image);

        return "
        <td width=\"50%\" align=\"center\" class=\"tdblock\" valign=\"middle\"><a href={$out}javascript:add_smilie({$code}){$out}><img src=\"$image\" border=\"0\" valign=\"middle\" alt=\"$short\" title=\"$short\" /></a></td>
        ";
    }

    function get_emo_header($css = "") {
	global $mkportals;

	$myopened = "1";
	$myeditor = "parent.document.editor.ta";
	$mysel = "document.selection";
	if ($mkportals->input['shouton']) {
		$myeditor = "window.opener.$('addshout')";
		$mysel = "window.opener.document.selection";
		$myopened = "2";
	}

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
        var obj_ta = $myeditor;
	var sel = $mysel;
	var myopened = $myopened;
        if ( (myVersion >= 4) && is_ie && is_win) {
            if(obj_ta.isTextEdit){
                obj_ta.focus();
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
	if(myopened == "2") {
		self.close();
	}
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
