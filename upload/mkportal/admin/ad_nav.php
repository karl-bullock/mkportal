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
if (!defined("IN_MKP")) {
    die ("Sorry !! You cannot access this file directly.");
}

$idx = new mk_ad_nav;
class mk_ad_nav {


	function mk_ad_nav() {
		global $mkportals;
		switch($mkportals->input['op']) {
			case 'menu':
    			$this->menu_show();
    		break;
			case 'edit1':
    			$this->edit1();
    		break;
			case 'update_link':
    			$this->update_link();
    		break;
			case 'delete_link':
    			$this->delete_link();
    		break;
			case 'save_link_bar':
    			$this->save_link_bar();
		break;
			case 'save_link_bulk':
    			$this->save_link_bulk();
    		break;
			case 'save_menu_bar':
    			$this->save_menu_bar();
    		break;
			default:
    			$this->link_show();
    		break;
    		}
	}
	function link_show() {
	global $mkportals, $mklib, $Skin, $DB;

		$mainltitle = "";

		if ($mkportals->input['mode'] == "saved") {
			$checksave = "<div class=\"bghighlight1 success\">{$mklib->lang['ad_saved']}</div>";
		}
		if ($mkportals->input['mode'] == "deleted") {
			$checksave = "<div class=\"bghighlight1 success\">{$mklib->lang['ad_deleted']}</div>";
		}

	 	$content  = "
	<tr>		
	  <td>
	  
	    <script type=\"text/javascript\">

			function makesurelink() {
			if (confirm('{$mklib->lang[ad_dellinkconf]}')) {
			return true;
			} else {
			return false;
			}
			}

	    </script>

	    <table width=\"100%\" border=\"0\">
	      <tr>
		<td>$checksave</td>
	      </tr>
	      <tr>
		<td>
		  <form name=\"config_topnav\" method=\"post\" action=\"index.php?ind=ad_nav&amp;op=save_link_bulk&amp;type=1\">
		  <table class=\"moduleborder\" cellspacing=\"1\" cellpadding=\"2\" width=\"98%\" align=\"center\" border=\"0\">
		    <tr>
		      <td colspan=\"7\" class=\"titadmin\">{$mklib->lang['ad_navlbar']}</td>
		    </tr>

		    <tr>
		      <th class=\"modulex mkalign1\" width=\"1%\">{$mklib->lang['ad_icon']}</th>
		      <th class=\"modulex mkalign1\" width=\"34%\">{$mklib->lang['ad_title']}</th>
		      <th class=\"modulex mkalign1\" width=\"57%\">{$mklib->lang['ad_urll']}</th>
		      <th class=\"modulex mkalign1\" width=\"1%\">{$mklib->lang['ad_position']}</th>
		      <th class=\"modulex mkalign1\" width=\"1%\">{$mklib->lang['ad_edit']}</th>
		      <th class=\"modulex mkalign1\" width=\"1%\">{$mklib->lang['ad_delete']}</th>
		      <th class=\"modulex mkalign1\" width=\"5%\">{$mklib->lang['ad_active']}</th>
		    </tr>
    ";
	$query = $DB->query( "SELECT id, icon, title, url, position, active FROM mkp_mainlinks WHERE type = '1' ORDER BY `active` DESC, `position`, `id`");
	while( $row = $DB->fetch_row($query) ) {
	$row['icon'] = str_replace("<IMG>","$mklib->images", $row['icon']);
	$row['url'] = str_replace("<MKURL>","$mklib->siteurl", $row['url']);
	$row['url'] = str_replace("<MKFURL>","$mkportals->base_url", $row['url']);
	if (stristr($row['title'], '<LNG>')) {
		$titlel = str_replace("<LNG>","", $row['title']); 
		$row['title'] = $mklib->lang[$titlel];
	}
	$idl = $row['id'];
	$icon = $row['icon'];
	$title = $row['title'];
	$urll = $row['url'];
	$position = $row['position'];

	//Active page highlighting
	$active = $row['active'];		
	$activestyle = $active ? "modulecell bghighlight1" : "modulecell"; //Active block highlighting
	$active = $active ? "checked=\"checked\"" : "";
	$active_form = $idl."_active";
	$position_form = $idl."_position";

	$content .= "
		    <tr>
		      <td class=\"$activestyle\" width=\"1%\" align=\"center\"><img src=\"$icon\" border=\"0\" alt=\"\" /></td>
		      <td class=\"$activestyle mkalign1\" width=\"34%\">$title</td>
		      <td class=\"$activestyle mkalign1\" width=\"57%\">$urll</td>
		      <td class=\"$activestyle mkalign1\" width=\"1%\"><input type=\"text\" name=\"$position_form\" value=\"$position\" size=\"4\" class=\"bgselect\" /></td>
		      <td class=\"$activestyle mkalign1\" width=\"1%\"><a class=\"mktxtcontr2\" href=\"index.php?ind=ad_nav&amp;op=edit1&amp;idl=$idl\">{$mklib->lang['ad_edit']}</a></td>
		      <td class=\"$activestyle mkalign1\" width=\"1%\"><a class=\"mktxtcontr\" href=\"index.php?ind=ad_nav&amp;op=delete_link&amp;idl=$idl\" onclick=\"return makesurelink()\">{$mklib->lang['ad_delete']}</a></td>
		      <td class=\"$activestyle\" width=\"5%\" align=\"center\"><input type=\"checkbox\" name=\"$active_form\" value=\"1\" $active /></td>
		    </tr>
    ";
	}
	if ($idl) {
		$content .= "
		    <tr>
		      <td colspan=\"7\" class=\"mkalign2\"><input type=\"submit\" value=\"{$mklib->lang['ad_save']}\" name=\"B1\" class=\"mkbutton\" /></td>
		    </tr>
		";
	}
	$content  .= "
		  </table></form><br />
		  
		  <form action=\"index.php?ind=ad_nav&amp;op=save_link_bar\" name=\"ad1\" method=\"post\">
		  <table width=\"100%\">	
		    <tr>
		      <td class=\"titadmin\" colspan=\"3\">{$mklib->lang['ad_adlink']}</td>
		    </tr>
		    <tr>
		      <td width=\"10%\">{$mklib->lang['ad_icon']}</td>
		      <td width=\"90%\" colspan=\"2\"><input type=\"text\" name=\"icon\" size=\"52\" class=\"bgselect\" /></td>
		    </tr>
		    <tr>
		      <td width=\"10%\">{$mklib->lang['ad_title']}</td>
		      <td width=\"90%\" colspan=\"2\"><input type=\"text\" name=\"title\" size=\"52\" class=\"bgselect\" /></td>
		    </tr>
		    <tr>
		      <td width=\"10%\">{$mklib->lang['ad_urll']}</td>
		      <td width=\"90%\" colspan=\"3\"><input type=\"text\" name=\"urll\" size=\"52\" class=\"bgselect\" /></td>
		    </tr>
		    
		    <tr>
		      <td style=\"padding: 3px; width: 10%\">{$mklib->lang['ad_position']} <input type=\"text\" name=\"position\" value=\"$position\" size=\"2\" class=\"bgselect\" /></td>
		      <td style=\"padding: 3px; width: 10%\">{$mklib->lang['ad_active']} <input type=\"checkbox\" name=\"linkactive\" value=\"1\" /></td>
		      <td style=\"padding: 3px; width: 80%\">{$mklib->lang['ad_targetnew']} <input type=\"checkbox\" name=\"target\" value=\"1\" /></td>
		    </tr>		    
		    
		    <tr>
		      <td colspan=\"3\"><br /><input type=\"submit\" value=\"{$mklib->lang['ad_bladdlink']}\" class=\"mkbutton\" /></td>
		    </tr>	
		  </table>
		  </form>
		  
		</td>
	      </tr>
	    </table>
	  </td>
	</tr>
	   ";
		$output = $Skin->view_block("{$mklib->lang['ad_navl']}", "$content");
		$mklib->printpage_admin($mklib->lang['ad_titlepage'].$mklib->lang['tt_sep'].$mklib->lang['ad_navlbar'], $output);

	}

	function menu_show() {
	global $mkportals, $mklib, $Skin, $DB;

		$mainltitle = "";

		if ($mkportals->input['mode'] == "saved") {
			$checksave = "<div class=\"bghighlight1 success\">{$mklib->lang['ad_saved']}</div>";
		}
		if ($mkportals->input['mode'] == "deleted") {
			$checksave = "<div class=\"bghighlight1 success\">{$mklib->lang['ad_deleted']}</div>";
		}

	 	$content  = "
	<tr>		
	  <td>

	    <script type=\"text/javascript\">

			function makesurelink() {
			if (confirm('{$mklib->lang[ad_dellinkconf]}')) {
			return true;
			} else {
			return false;
			}
			}

	    </script>
	   
	    <table width=\"100%\" border=\"0\">
	      <tr>
		<td>$checksave</td>
	      </tr>
	      <tr>
		<td>
		  <form name=\"config_menu\" method=\"post\" action=\"index.php?ind=ad_nav&amp;op=save_link_bulk&amp;type=2\">
		  <table class=\"moduleborder\" cellspacing=\"1\" cellpadding=\"2\" width=\"98%\" align=\"center\" border=\"0\">
		    <tr>
		      <td colspan=\"7\" class=\"titadmin\">{$mklib->lang['ad_navlmenu']}</td>
		    </tr>
		    
		    <tr>
		      <th class=\"modulex mkalign1\" width=\"1%\">{$mklib->lang['ad_icon']}</th>
		      <th class=\"modulex mkalign1\" width=\"34%\">{$mklib->lang['ad_title']}</th>
		      <th class=\"modulex mkalign1\" width=\"57%\">{$mklib->lang['ad_urll']}</th>
		      <th class=\"modulex mkalign1\" width=\"1%\">{$mklib->lang['ad_position']}</th>
		      <th class=\"modulex mkalign1\" width=\"1%\">{$mklib->lang['ad_edit']}</th>
		      <th class=\"modulex mkalign1\" width=\"1%\">{$mklib->lang['ad_delete']}</th>
		      <th class=\"modulex mkalign1\" width=\"5%\">{$mklib->lang['ad_active']}</th>
		    </tr>
    ";
	$query = $DB->query( "SELECT id, icon, title, url, position, active FROM mkp_mainlinks WHERE type = '2' ORDER BY `active` DESC, `position`, `id`");
	while( $row = $DB->fetch_row($query) ) {
	$row['icon'] = str_replace("<IMG>","$mklib->images", $row['icon']);
	$row['url'] = str_replace("<MKURL>","$mklib->siteurl", $row['url']);
	$row['url'] = str_replace("<MKFURL>","$mkportals->base_url", $row['url']);
	if (stristr($row['title'], '<LNG>')) {
		$titlel = str_replace("<LNG>","", $row['title']); 
		$row['title'] = $mklib->lang[$titlel];
	}
	$idl = $row['id'];
	$icon = $row['icon'];
	$title = $row['title'];
	$urll = $row['url'];
	$position = $row['position'];

	//Active page highlighting
	$active = $row['active'];		
	$activestyle = $active ? "modulecell bghighlight1" : "modulecell"; //Active block highlighting
	$active = $active ? "checked=\"checked\"" : "";
	$active_form = $idl."_active";
	$position_form = $idl."_position";

	$content .= "
		    <tr>
		      <td class=\"$activestyle\" width=\"1%\" align=\"center\"><img src=\"$icon\" border=\"0\" alt=\"\" /></td>
		      <td class=\"$activestyle mkalign1\" width=\"34%\">$title</td>
		      <td class=\"$activestyle mkalign1\" width=\"57%\">$urll</td>
		      <td class=\"$activestyle mkalign1\" width=\"1%\"><input type=\"text\" name=\"$position_form\" value=\"$position\" size=\"4\" class=\"bgselect\" /></td>
		      <td class=\"$activestyle mkalign1\" width=\"1%\"><a class=\"mktxtcontr2\" href=\"index.php?ind=ad_nav&amp;op=edit1&amp;idl=$idl&amp;retl=1\">{$mklib->lang['ad_edit']}</a></td>
		      <td class=\"$activestyle mkalign1\" width=\"1%\"><a class=\"mktxtcontr\" href=\"index.php?ind=ad_nav&amp;op=delete_link&amp;idl=$idl&amp;retl=1\" onclick=\"return makesurelink()\">{$mklib->lang['ad_delete']}</a></td>
		      <td class=\"$activestyle\" width=\"5%\" align=\"center\"><input type=\"checkbox\" name=\"$active_form\" value=\"1\" $active /></td>
		    </tr>
    ";
	}
	if ($idl) {
		$content .= "
		    <tr>
		      <td colspan=\"7\" class=\"mkalign2\"><input type=\"submit\" value=\"{$mklib->lang['ad_save']}\" name=\"B2\" class=\"mkbutton\" /></td>
		    </tr>
		";
	}
	$content  .= "
		  </form></table><br />
		  
		  <form action=\"index.php?ind=ad_nav&amp;op=save_menu_bar\" name=\"ad1\" method=\"post\">
		  <table width=\"100%\">	
		    <tr>
		      <td class=\"titadmin\" colspan=\"3\">{$mklib->lang['ad_adlink']}</td>
		    </tr>
		    <tr>
		      <td width=\"10%\">{$mklib->lang['ad_icon']}</td>
		      <td width=\"90%\" colspan=\"2\"><input type=\"text\" name=\"icon\" size=\"52\" class=\"bgselect\" /></td>
		    </tr>
		    <tr>
		      <td width=\"10%\">{$mklib->lang['ad_title']}</td>
		      <td width=\"90%\" colspan=\"2\"><input type=\"text\" name=\"title\" size=\"52\" class=\"bgselect\" /></td>
		    </tr>
		    <tr>
		      <td width=\"10%\">{$mklib->lang['ad_urll']}</td>
		      <td width=\"90%\" colspan=\"2\"><input type=\"text\" name=\"urll\" size=\"52\" class=\"bgselect\" /></td>
		    </tr>

		    <tr>
		      <td style=\"padding: 3px; width: 10%\">{$mklib->lang['ad_position']} <input type=\"text\" name=\"position\" value=\"$position\" size=\"2\" class=\"bgselect\" /></td>
		      <td style=\"padding: 3px; width: 10%\">{$mklib->lang['ad_active']} <input type=\"checkbox\" name=\"linkactive\" value=\"1\" /></td>
		      <td style=\"padding: 3px; width: 80%\">{$mklib->lang['ad_targetnew']} <input type=\"checkbox\" name=\"target\" value=\"1\" /></td>
		    </tr>

		    <tr>
		      <td colspan=\"2\"><br /><input type=\"submit\" value=\"{$mklib->lang['ad_bladdlink']}\" class=\"mkbutton\" /></td>
		    </tr>	
		  </table>
		  </form>

		</td>
	      </tr>
	    </table>
	  </td>
	</tr>
	   ";
		$output = $Skin->view_block("{$mklib->lang['ad_navl']}", "$content");
		$mklib->printpage_admin($mklib->lang['ad_titlepage'].$mklib->lang['tt_sep'].$mklib->lang['ad_navlmenu'], $output);

	}

	function edit1() {
		global $mkportals, $DB, $mklib, $Skin;

		$idl = $mkportals->input['idl'];
		$retl = $mkportals->input['retl'];

		$query = $DB->query( "SELECT icon, title, url, position, target, active FROM mkp_mainlinks WHERE id = '$idl'");
		$row = $DB->fetch_row($query);
		$icon = $row['icon'];
		$title = $row['title'];
		$urll = $row['url'];
		$position = $row['position'];
		$target = $row['target'];
		if ($target == 1) {
			$checkactive =  "checked=\"checked\"";
		}
		$linkactive = $row['active'] ? "checked=\"checked\"" : "";

		$output = "
	<tr>
	  <td><br />
	    <table class=\"moduleborder\" cellspacing=\"1\" cellpadding=\"0\" width=\"100%\" align=\"center\" border=\"0\">
	      <tr>
		<td>
		  <table class=\"modulebg\" cellspacing=\"1\" cellpadding=\"0\" width=\"100%\" border=\"0\">
		    <tr>
		      <td class=\"tdblock\" width=\"100%\" height=\"25\"><img class=\"mkicon\" src=\"$mklib->images/arrow.gif\" alt=\"\" />{$mklib->lang['ad_modlink']}</td>
		    </tr>
		    <tr>
		      <td>
			<table class=\"moduleborder\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" border=\"0\">
			  <tr>
			    <td>
			      <table class=\"moduleborder\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" border=\"0\">
				<tr>				 
				  <td class=\"modulex\">
				  
				    <form action=\"index.php?ind=ad_nav&amp;op=update_link&amp;idl=$idl&amp;retl=$retl\" name=\"e_b\" method=\"post\">
				    <table border=\"0\">
				      <tr>
					<td colspan=\"3\" class=\"titadmin\"><br /></td>
				      </tr>
				      <tr>
					<td>{$mklib->lang['ad_icon']}</td>
				      </tr>
				      <tr>
					<td colspan=\"3\"><input type=\"text\" name=\"icon\" value=\"$icon\" size=\"70\" class=\"bgselect\" /></td>
				      </tr>
				      <tr>
					<td colspan=\"3\">{$mklib->lang['ad_title']}</td>
				      </tr>
				      <tr>
					<td colspan=\"3\"><input type=\"text\" name=\"title\" value=\"$title\" size=\"70\" class=\"bgselect\" /></td>
				      </tr>
				      <tr colspan=\"3\">
					<td>{$mklib->lang['ad_urll']}</td>
				      </tr>
				      <tr>
					<td colspan=\"3\"><input type=\"text\" name=\"urll\" value=\"$urll\" size=\"70\" class=\"bgselect\" /></td>
				      </tr>
				      <tr>
					<td style=\"padding: 5px\">{$mklib->lang['ad_position']} <input type=\"text\" name=\"position\" value=\"$position\" size=\"2\" class=\"bgselect\" /></td>
					<td style=\"padding: 5px\">{$mklib->lang['ad_active']} <input type=\"checkbox\" name=\"linkactive\" value=\"1\" $linkactive /></td>
					<td style=\"padding: 5px\">{$mklib->lang['ad_targetnew']} <input type=\"checkbox\" name=\"target\" value=\"1\" $checkactive /></td>
				      </tr>

				      <tr>
					<td colspan=\"3\"><br /><input type=\"submit\" value=\"{$mklib->lang['ad_save']}\" class=\"mkbutton\" /></td>
				      </tr>
				    </table>
				    </form>
				  </td>
				</tr>   		
			      </table>
			    </td>
			  </tr>
			</table>
		      </td>
		    </tr>
		  </table>
		</td>
	      </tr>
	    </table>
	  </td>
	</tr>
	";
		$output = $Skin->view_block("{$mklib->lang['ad_modlink']}", "$output");
		$mklib->printpage_admin($mklib->lang['ad_titlepage'].$mklib->lang['tt_sep'].$mklib->lang['ad_modlink'], $output);

	}

	function update_link() {
    	global $mkportals, $DB, $mklib;

		$idl = intval($mkportals->input['idl']);
		$retl = $mkportals->input['retl'];
		$icon = $_POST['icon'];
		$title = $_POST['title'];
		$urll = $_POST['urll'];
		$position = intval($mkportals->input['position']);
		$target = ( !empty($mkportals->input['target']) ) ? $mkportals->input['target'] : "0";
		$active = $_POST['linkactive'];
		if (!$title || !$urll) {
			$message = "{$mklib->lang['ad_all_rows']}";
			$mklib->error_page($message);
			exit;
		}
		$DB->query("UPDATE mkp_mainlinks set icon = '$icon', title = '$title', url = '$urll', position = '$position', target = '$target', active = '$active' WHERE id = '$idl'");
		$DB->close_db();
		$loc = "index.php?ind=ad_nav&mode=saved";
		if ($retl) {
			$loc = "index.php?ind=ad_nav&op=menu&mode=saved";
		}
		Header("Location: $loc");
		exit;
  	}
	function delete_link() {
    	global $mkportals, $DB, $mklib;

		$idl = intval($mkportals->input['idl']);
		$retl = $mkportals->input['retl'];
		$DB->query("DELETE FROM mkp_mainlinks WHERE id = $idl");
		$DB->close_db();
		$loc = "index.php?ind=ad_nav&mode=deleted";
		if ($retl) {
			$loc = "index.php?ind=ad_nav&op=menu&mode=deleted";
		}
		Header("Location: $loc");
		exit;
  	}
	function save_link_bar() {
    	global $mkportals, $DB, $mklib;
		$icon = $_POST['icon'];
		$title = $_POST['title'];
		$urll = $_POST['urll'];
		$target = ( !empty($mkportals->input['target']) ) ? $mkportals->input['target'] : "0";
		if (!$title || !$urll) {
			$message = "{$mklib->lang['ad_all_rows']}";
			$mklib->error_page($message);
			exit;
		}
		$position = $_POST['position'];
		$active = $_POST['linkactive'];
		$DB->query("INSERT INTO mkp_mainlinks (icon, title, url, type, position, target, active) VALUES ('$icon', '$title', '$urll', '1', '$position', '$target', '$active')");
		$DB->close_db();
		Header("Location: index.php?ind=ad_nav&mode=saved");
		exit;
	}
	//Bulk save navbar and main menu config
	function save_link_bulk() {
		global $mkportals, $mklib, $Skin,  $DB;
		$type = intval($mkportals->input['type']);
		$myquery = $DB->query("SELECT id FROM mkp_mainlinks WHERE type = '$type'");
 		while( $row = $DB->fetch_row($myquery) ) {
			$id = $row['id'];
			$active_form = $id."_active";
			$position_form = $id."_position";			
			$mkportals->input[$active_form] = ($mkportals->input[$active_form]) ? '1' : '0'; //default value = "0"
			$DB->query("UPDATE mkp_mainlinks SET position ='{$mkportals->input[$position_form]}', active ='{$mkportals->input[$active_form]}' WHERE id='$id'");
		}
		$DB->close_db();

		//Where to go now?
		if ($type == 1) {
			Header("Location: index.php?ind=ad_nav&mode=saved");
		} else {
			Header("Location: index.php?ind=ad_nav&op=menu&mode=saved");
		}
		exit;
	}
	function save_menu_bar() {
    	global $mkportals, $DB, $mklib;
		$icon = $_POST['icon'];
		$title = $_POST['title'];
		$urll = $_POST['urll'];
		$target = ( !empty($mkportals->input['target']) ) ? $mkportals->input['target'] : "0";
		$position = $_POST['position'];
		$active = $_POST['linkactive'];
		if (!$title || !$urll) {
			$message = "{$mklib->lang['ad_all_rows']}";
			$mklib->error_page($message);
			exit;
		}
		$DB->query("INSERT INTO mkp_mainlinks (icon, title, url, type, position, target, active) VALUES ('$icon', '$title', '$urll', '2', '$position', '$target', '$active')");
		$DB->close_db();
		Header("Location: index.php?ind=ad_nav&op=menu&mode=saved");
		exit;
  	}



}

?>
