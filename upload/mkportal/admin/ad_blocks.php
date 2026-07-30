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
$idx = new mk_ad_blocks;
class mk_ad_blocks {


	function mk_ad_blocks() {
		global $mkportals;
		switch($mkportals->input['op']) {
				case 'blocks_modules':
    			$this->blocks_modules();
		break;
		case 'blocks_modules_save':
    			$this->blocks_modules_save();
		break;
			case 'blocks_save':
    			$this->blocks_save();
    		break;
			case 'blocks_list':
    			$this->blocks_list();
    		break;
			case 'blocks_main_new':
    			$this->blocks_main_new();
    		break;
			case 'blocks_new':
    			$this->blocks_new();
    		break;
			case 'blocks_new_php':
    			$this->blocks_new_php();
    		break;
			case 'blocks_new_link':
    			$this->blocks_new_link();
    		break;
			case 'blocks_savenew':
    			$this->blocks_savenew();
    		break;
			case 'blocks_save_php':
    			$this->blocks_save_php();
    		break;
			case 'blocks_save_link':
    			$this->blocks_save_link();
    		break;
			case 'blocks_edit':
    			$this->blocks_edit(intval($mkportals->input['idc']), $mkportals->input['personal']);
    		break;
			case 'blocks_edit_php':
    			$this->blocks_edit_php(intval($mkportals->input['idblock']));
    		break;
			case 'blocks_edit_link':
    			$this->blocks_edit_link(intval($mkportals->input['idblock']));
    		break;
			case 'blocks_update':
    			$this->blocks_update(intval($mkportals->input['idc']));
    		break;
			case 'blocks_update_php':
    			$this->blocks_update_php();
    		break;
			case 'blocks_update_link':
    			$this->blocks_update_link(intval($mkportals->input['idblock']));
    		break;
			case 'blocks_delete':
    			$this->blocks_delete(intval($mkportals->input['idc']));
    		break;
			case 'show_code':
    			$this->show_code();
		break;
		case 'blocks_perms':
    			$this->blocks_perms();
		break;
		case 'bsave_perms':
    			$this->bsave_perms();
		break;
		case 'show_codelink':
    			$this->show_codelink();
    		break;
		case 'blocks_titles':
    			$this->blocks_titles();
    		break;
		case 'blocks_title_save':
    			$this->blocks_title_save();
    		break;
    		default:
    			$this->blocks_show();
    		break;
    	
    		}
	}
	function blocks_modules() {
	global $mkportals, $mklib, $Skin, $DB;
	    $idblock = intval($mkportals->input['idc']);
	    
	    $content .= "<form name=\"modul\" method=\"post\" action=\"index.php?ind=ad_blocks&amp;op=blocks_modules_save&amp;idc=$idblock\">";
		$dirm = $mklib->sitepath."mkportal/modules/";
		$myquery = $DB->query("SELECT title, modules FROM mkp_blocks WHERE id='$idblock'");
	while( $row = $DB->fetch_row($myquery) ) {
		$module = explode(",", $row['modules']);
		$title_block = $row['title'];
	}
	$content .= "<tr>
		<td colspan=\"3\" class=\"tdblock\" align=\"center\">{$mklib->lang['ad_blocks_minfo2']} $title_block.</td>
		</tr>";
		$handle_main = opendir($dirm);
		while ($file_title = readdir($handle_main)) {
			if($file_title==".." OR $file_title=="."){
     continue;
  }       if($file_title == 'index.html'){
      continue;
          }
          if($file_title == 'ajaxout'){
      continue;
          }
          if($file_title == 'rajax'){
      continue;
          }
          if($file_title == 'rss'){
      continue;
          }
          if($file_title == 'contents'){
           continue;
          }
			if (!preg_match("/\./", $file_title)) {
			$title_title = str_replace("_", " ", $file_title);
			}
			$checked = (in_array($file_title,$module)) ? "checked" : "";
			$content .= "<td><input type=\"checkbox\" $checked name=\"modul[]\" value=\"".$title_title."\">$title_title</td>";
			
		if ($a == 2) {
			$content .= "</tr><tr>";
			$a = 0;
		} else {
			$a++;
		}
		}
		closedir($handle_main);
		$contents = (in_array("contents", $module)) ? "checked" : "";
	    $forum = (in_array("forum", $module) && empty($hel)) ? "checked" : "";
	    $all = (in_array("", $module)) ? "checked" : "";
		$content .= "<tr>
		<td><input type=\"checkbox\" name=\"modul[]\" value=\"contents\" $contents><b>{$mklib->lang['ad_blocks_mindex']}</b></td>
		<td><input type=\"checkbox\" name=\"modul[]\" value=\"forum\" $forum><b>{$mklib->lang['ad_blocks_mforum']}</b></td>
		<td><input type=\"checkbox\" name=\"modul[]\" value=\"all\" $all><b>{$mklib->lang['ad_blocks_mall']}</b></td>
		</tr>";
		$content .= "<tr>
		<td colspan=\"3\" class=\"tdblock\" align=\"center\">{$mklib->lang['ad_blocks_minfo']}</td>
		</tr>";
	   $content .= "<tr>
	      <td colspan=\"3\" class=\"trattini\" align=\"center\"><input value=\"{$mklib->lang['ad_save']}\" name=\"B1\" class=\"mkbutton\" type=\"submit\"></td>
	    </tr>
";
	$output = $Skin->view_block("{$mklib->lang['ad_blocks_mod']}", "$content");
	$mklib->printpage_admin("{$mklib->lang['ad_blocks_mod']}", $output);
	}
	function blocks_modules_save() {
	global $mkportals, $mklib, $Skin, $DB;
	$idblock = intval($mkportals->input['idc']);
	$modul= $mkportals->input['modul'];
	if($modul){
	$modules = implode(",", $modul);
	 if (in_array("all", $modul)) $modules = "";
	}
		$DB->query("UPDATE mkp_blocks SET modules ='$modules' WHERE id='$idblock'");
		$DB->close_db();
		Header("Location: index.php?ind=ad_blocks&op=blocks_list&mode=saved");
		exit;
	}
	//Blocks: position
function blocks_show() {
    global $mkportals, $mklib, $Skin, $DB;
    $mode = $mkportals->input['mode'];
    if ($mode == "saved") {
        $checksave = "<div class=\"bghighlight1 success\">{$mklib->lang['ad_saved']}</div>";
       }
    $myquery = $DB->query("SELECT file FROM mkp_blocks");
    while( $row = $DB->fetch_row($myquery) ) {
        $listfile .= $row['file'];
    }
    $dirb = $mklib->sitepath."mkportal/blocks/";

    //Add database entries for new system blocks (uploaded php block files)
    if ($handle = opendir($dirb)) {
        while (false !== ($file = readdir($handle))) {        
        $ext = substr(strrchr($file, "."), 1); //Get file extension            
        if ($ext == "php") {
            $pos = strpos($listfile, $file);
            if ($pos === false) {
                $title = str_replace (".php", "", $file);
                $DB->query("INSERT INTO mkp_blocks(file, title, content) VALUES('$file', '$title', '')");
            }
            $listfile2 .= $file;
        }
        }
        closedir($handle);
       }
    
    //Delete database entries for missing system blocks
    $myquery = $DB->query("SELECT file FROM mkp_blocks WHERE personal='0'");
    while( $row = $DB->fetch_row($myquery) ) {
        $checkdel = $row['file'];        
        $pos = strpos($listfile2, $checkdel);
            if ($pos === false) {
            $DB->query("DELETE FROM mkp_blocks WHERE file='$checkdel'");
        }
    }

    $subtitle = "{$mklib->lang['ad_blcfg']}";

    $content = "
      <tr>
    <td>

      $checksave

      <form name=\"config_blocks\" method=\"post\" action=\"index.php?ind=ad_blocks&amp;op=blocks_save\">
      <table width=\"100%\">
        <tr>
          <!-- open left column -->
          <td align=\"center\" valign=\"top\" class=\"trattini2\" style=\"width:20%\">
     ";
    $progoption = "";
    $myquery = $DB->query("SELECT * FROM mkp_blocks WHERE position ='sinistra' ORDER BY active DESC, progressive, id");
    $totprog = $DB->get_num_rows($myquery);
    for ($i = 1; $i <= $totprog; $i++) {
           $progoption .= "<option value=\"$i\">$i</option>";
    }

    while( $row = $DB->fetch_row($myquery) ) {
        $id = $row['id'];
        $title = $row['title'];
        //$position = $row['position'];
        $position = $mklib->lang['ad_left'];
        $progressive = $row['progressive'];
        if ($progressive == 100) {
               $progressive = $totprog;
        }

        $active = $row['active'];        
        $activestyle = $active ? "tablemenu bghighlight1" : "tablemenu"; //Active block highlighting
        $active = $active ? "checked=\"checked\"" : "";

        $title_form = $id."_title";
        $position_form = $id."_position";
        $progressive_form = $id."_progressive";
        $active_form = $id."_active";

        $content .= "
        <!-- block table -->
        <table class=\"$activestyle\" style=\"width: 98%; margin-bottom: 5px\">
          <tr>
            <td width=\"43%\" align=\"left\">
              <select class=\"bgselect\" size=\"1\" name=\"$position_form\">
            <option value=\"sinistra\">$position</option>\n
            <option value=\"sinistra\">{$mklib->lang['ad_left']}</option>\n
            <option value=\"centro\">{$mklib->lang['ad_center']}</option>\n
            <option value=\"destra\">{$mklib->lang['ad_right']}</option>\n
            <option value=\"centrotop\">{$mklib->lang['ad_blocks_centrotop']}</option>\n
            <option value=\"centrodown\">{$mklib->lang['ad_blocks_centrodown']}</option>\n
              </select>
            </td>
            <td width=\"23%\" align=\"left\">
              <select class=\"bgselect\" size=\"1\" name=\"$progressive_form\">
            <option value=\"$progressive\">$progressive</option>
            $progoption
              </select>
            </td>
            <td width=\"33%\" align=\"left\">{$mklib->lang['ad_active']}
              <input type=\"checkbox\" name=\"$active_form\" value=\"checked\" $active />
            </td>
          </tr>
          <tr>
            <td colspan=\"5\" class=\"bgselect\" align=\"left\"><input type=\"text\" name=\"$title_form\" size=\"40\" value=\"$title\" class=\"bgselect\" style=\"border: 0; float: left\" readonly=\"readonly\" /><span style=\"float: right\">$id</span></td>
          </tr>
            </table>
            <!-- end block table -->
         ";
    }
    $content .= "
          <!-- close left column -->
          </td>    

          <!-- open centrotop column -->
          <td align=\"center\" valign=\"top\" class=\"trattini2\" style=\"width:20%\">
    ";
    $progoption = "";
    $myquery = $DB->query("SELECT * FROM mkp_blocks WHERE position ='centrotop' ORDER BY active DESC, progressive, id");
    $totprog = $DB->get_num_rows($myquery);
    for ($i = 1; $i <= $totprog; $i++) {
           $progoption .= "<option value=\"$i\">$i</option>";
    }

    while( $row = $DB->fetch_row($myquery) ) {
        $id = $row['id'];
        $title = $row['title'];
        //$position = $row['position'];
        $position = "{$mklib->lang['ad_blocks_centrotop']}";
        $progressive = $row['progressive'];
        if ($progressive == 100) {
               $progressive = $totprog;
        }
        
        $active = $row['active'];        
        $activestyle = $active ? "tablemenu bghighlight1" : "tablemenu"; //Active block highlighting
        $active = $active ? "checked=\"checked\"" : "";

        $title_form = $id."_title";
        $position_form = $id."_position";
        $progressive_form = $id."_progressive";
        $active_form = $id."_active";

        $content .= "
        <!-- block table -->
        <table class=\"$activestyle\" style=\"width: 98%; margin-bottom: 5px\">
          <tr>
            <td width=\"43%\" align=\"left\">
              <select class=\"bgselect\" size=\"1\" name=\"$position_form\">
            <option value=\"centrotop\">$position</option>
            <option value=\"sinistra\">{$mklib->lang['ad_left']}</option>\n
            <option value=\"centro\">{$mklib->lang['ad_center']}</option>\n
            <option value=\"destra\">{$mklib->lang['ad_right']}</option>\n
            <option value=\"centrotop\">{$mklib->lang['ad_blocks_centrotop']}</option>\n
            <option value=\"centrodown\">{$mklib->lang['ad_blocks_centrodown']}</option>\n
              </select>
            </td>
            <td width=\"23%\" align=\"left\">
              <select class=\"bgselect\" size=\"1\" name=\"$progressive_form\">
            <option value=\"$progressive\">$progressive</option>
            $progoption
              </select>
            </td>
            <td width=\"33%\" align=\"left\">{$mklib->lang['ad_active']}<input type=\"checkbox\" name=\"$active_form\" value=\"checked\" $active />
            </td>
          </tr>
          <tr>
            <td colspan=\"3\" class=\"bgselect\" align=\"left\"><input type=\"text\" name=\"$title_form\" size=\"40\" value=\"$title\" class=\"bgselect\" style=\"border: 0; float: left\" readonly=\"readonly\" /><span style=\"float: right\">$id</span></td>
          </tr>
        </table>
            <!-- end block table -->
         ";
    }
    $content .= "
          <!-- close centrotop column -->
          </td>

          <!-- open center column -->
          <td align=\"center\" valign=\"top\" class=\"trattini2\" style=\"width:20%\">
    ";
    $progoption = "";
    $myquery = $DB->query("SELECT * FROM mkp_blocks WHERE position ='centro' ORDER BY active DESC, progressive, id");
    $totprog = $DB->get_num_rows($myquery);
    for ($i = 1; $i <= $totprog; $i++) {
           $progoption .= "<option value=\"$i\">$i</option>";
    }

    while( $row = $DB->fetch_row($myquery) ) {
        $id = $row['id'];
        $title = $row['title'];
        //$position = $row['position'];
        $position = $mklib->lang['ad_center'];
        $progressive = $row['progressive'];
        if ($progressive == 100) {
               $progressive = $totprog;
        }
        
        $active = $row['active'];        
        $activestyle = $active ? "tablemenu bghighlight1" : "tablemenu"; //Active block highlighting
        $active = $active ? "checked=\"checked\"" : "";

        $title_form = $id."_title";
        $position_form = $id."_position";
        $progressive_form = $id."_progressive";
        $active_form = $id."_active";

        $content .= "
        <!-- block table -->
        <table class=\"$activestyle\" style=\"width: 98%; margin-bottom: 5px\">
          <tr>
            <td width=\"43%\" align=\"left\">
              <select class=\"bgselect\" size=\"1\" name=\"$position_form\">
            <option value=\"centro\">$position</option>
            <option value=\"sinistra\">{$mklib->lang['ad_left']}</option>\n
            <option value=\"centro\">{$mklib->lang['ad_center']}</option>\n
            <option value=\"destra\">{$mklib->lang['ad_right']}</option>\n
            <option value=\"centrotop\">{$mklib->lang['ad_blocks_centrotop']}</option>\n
            <option value=\"centrodown\">{$mklib->lang['ad_blocks_centrodown']}</option>\n
              </select>
            </td>
            <td width=\"23%\" align=\"left\">
              <select class=\"bgselect\" size=\"1\" name=\"$progressive_form\">
            <option value=\"$progressive\">$progressive</option>
            $progoption
              </select>
            </td>
            <td width=\"33%\" align=\"left\">{$mklib->lang['ad_active']}<input type=\"checkbox\" name=\"$active_form\" value=\"checked\" $active />
            </td>
          </tr>
          <tr>
            <td colspan=\"3\" class=\"bgselect\" align=\"left\"><input type=\"text\" name=\"$title_form\" size=\"40\" value=\"$title\" class=\"bgselect\" style=\"border: 0; float: left\" readonly=\"readonly\" /><span style=\"float: right\">$id</span></td>
          </tr>
        </table>
            <!-- end block table -->
         ";
    }
    $content .= "
          <!-- close center column -->
          </td>
          
          <!-- open center right column -->
          <td align=\"center\" valign=\"top\" class=\"trattini2\" style=\"width:20%\">
    ";
    $progoption = "";
    $myquery = $DB->query("SELECT * FROM mkp_blocks WHERE position ='centrodown' ORDER BY active DESC, progressive, id");
    $totprog = $DB->get_num_rows($myquery);
    for ($i = 1; $i <= $totprog; $i++) {
           $progoption .= "<option value=\"$i\">$i</option>";
    }

    while( $row = $DB->fetch_row($myquery) ) {
        $id = $row['id'];
        $title = $row['title'];
        //$position = $row['position'];
        $position = "{$mklib->lang['ad_blocks_centrodown']}";
        $progressive = $row['progressive'];
        if ($progressive == 100) {
               $progressive = $totprog;
        }
        
        $active = $row['active'];        
        $activestyle = $active ? "tablemenu bghighlight1" : "tablemenu"; //Active block highlighting
        $active = $active ? "checked=\"checked\"" : "";

        $title_form = $id."_title";
        $position_form = $id."_position";
        $progressive_form = $id."_progressive";
        $active_form = $id."_active";

        $content .= "
        <!-- block table -->
        <table class=\"$activestyle\" style=\"width: 98%; margin-bottom: 5px\">
          <tr>
            <td width=\"43%\" align=\"left\">
              <select class=\"bgselect\" size=\"1\" name=\"$position_form\">
            <option value=\"centrodown\">$position</option>
            <option value=\"sinistra\">{$mklib->lang['ad_left']}</option>\n
            <option value=\"centro\">{$mklib->lang['ad_center']}</option>\n
            <option value=\"destra\">{$mklib->lang['ad_right']}</option>\n
            <option value=\"\">{$mklib->lang['ad_blocks_centrotop']}</option>\n
            <option value=\"centrodown\">{$mklib->lang['ad_blocks_centrodown']}</option>\n
              </select>
            </td>
            <td width=\"23%\" align=\"left\">
              <select class=\"bgselect\" size=\"1\" name=\"$progressive_form\">
            <option value=\"$progressive\">$progressive</option>
            $progoption
              </select>
            </td>
            <td width=\"33%\" align=\"left\">{$mklib->lang['ad_active']}<input type=\"checkbox\" name=\"$active_form\" value=\"checked\" $active />
            </td>
          </tr>
          <tr>
            <td colspan=\"3\" class=\"bgselect\" align=\"left\"><input type=\"text\" name=\"$title_form\" size=\"40\" value=\"$title\" class=\"bgselect\" style=\"border: 0; float: left\" readonly=\"readonly\" /><span style=\"float: right\">$id</span></td>
          </tr>
        </table>
            <!-- end block table -->
         ";
    }
    $content .= "
          <!-- close centrodown column -->
          </td>

          <!-- open right column -->
          <td align=\"center\" valign=\"top\" class=\"trattini2\" style=\"width:20%\">
        
    ";
    $progoption = "";
    $myquery = $DB->query("SELECT * FROM mkp_blocks WHERE position ='destra' ORDER BY active DESC, progressive, id");
    $totprog = $DB->get_num_rows($myquery);
    for ($i = 1; $i <= $totprog; $i++) {
           $progoption .= "<option value=\"$i\">$i</option>";
    }

    while( $row = $DB->fetch_row($myquery) ) {
        $id = $row['id'];
        $title = $row['title'];
        //$position = $row['position'];
        $position = $mklib->lang['ad_right'];
        $progressive = $row['progressive'];
        if ($progressive == 100) {
               $progressive = $totprog;
        }
        
        $active = $row['active'];        
        $activestyle = $active ? "tablemenu bghighlight1" : "tablemenu"; //Active block highlighting
        $active = $active ? "checked=\"checked\"" : "";

        $title_form = $id."_title";
        $position_form = $id."_position";
        $progressive_form = $id."_progressive";
        $active_form = $id."_active";

        $content .= "
        <!-- block table -->
        <table class=\"$activestyle\" style=\"width: 98%; margin-bottom: 5px\">
          <tr>
            <td width=\"43%\" align=\"left\">
              <select class=\"bgselect\" size=\"1\" name=\"$position_form\">
            <option value=\"destra\">$position</option>
            <option value=\"sinistra\">{$mklib->lang['ad_left']}</option>\n
            <option value=\"centro\">{$mklib->lang['ad_center']}</option>\n
            <option value=\"destra\">{$mklib->lang['ad_right']}</option>\n
            <option value=\"centrotop\">{$mklib->lang['ad_blocks_centrotop']}</option>\n
            <option value=\"centrodown\">{$mklib->lang['ad_blocks_centrodown']}</option>\n
              </select>
            </td>
            <td width=\"23%\" align=\"left\">
              <select class=\"bgselect\" size=\"1\" name=\"$progressive_form\">
            <option value=\"$progressive\">$progressive</option>
            $progoption
              </select>
            </td>
            <td width=\"33%\" align=\"left\">{$mklib->lang['ad_active']}<input type=\"checkbox\" name=\"$active_form\" value=\"checked\" $active /></td>
          </tr>
          <tr>
            <td colspan=\"5\" class=\"bgselect\" align=\"left\"><input type=\"text\" name=\"$title_form\" size=\"40\" value=\"$title\" class=\"bgselect\" style=\"border: 0; float: left\" readonly=\"readonly\" /><span style=\"float: right\">$id</span></td>
          </tr>
            </table>
            <!-- end block table -->
         ";
        }
    $content .= "
          <!-- close right column -->
          </td>
        </tr>";

    //Submit button    
    $content .= "
        <tr>
          <td colspan=\"5\" class=\"trattini\" align=\"center\"><input type=\"submit\" value=\"{$mklib->lang['ad_save']}\" name=\"B1\" class=\"mkbutton\" /></td>
        </tr>
      </table>
      </form>
      
    </td>
      </tr>
    ";
    $output = $Skin->view_block("$subtitle", "$content");
    $mklib->printpage_admin($mklib->lang['ad_titlepage'].$mklib->lang['tt_sep'].$mklib->lang['ad_mblocks'].$mklib->lang['tt_sep'].$mklib->lang['ad_mposition'], $output);

    }
	
	//Blocks: position save
	function blocks_save() {
	global $mkportals, $mklib, $Skin,  $DB;
		$myquery = $DB->query("SELECT id FROM mkp_blocks");
 		while( $row = $DB->fetch_row($myquery) ) {
		$id = $row['id'];
		$title_form = $id."_title";
		$title_form = $mklib->convert_savedbadmin($title_form);
		$position_form = $id."_position";
		$progressive_form = $id."_progressive";
		$active_form = $id."_active";
		$DB->query("UPDATE mkp_blocks SET title ='{$mkportals->input[$title_form]}', position ='{$mkportals->input[$position_form]}', progressive ='{$mkportals->input[$progressive_form]}', active ='{$mkportals->input[$active_form]}'  WHERE id='$id'");
		}

		$DB->close_db();
		Header("Location: index.php?ind=ad_blocks&mode=saved");
		exit;

	}
	
	//Blocks: management
	function blocks_list() {
	global $mkportals, $mklib, $Skin, $DB;

	if ($mkportals->input['mode'] == "saved") {
		$checksave = "<div class=\"bghighlight1 success\">{$mklib->lang['ad_saved']}</div>";
	}
	if ($mkportals->input['mode'] == "deleted") {
		$checksave = "<div class=\"bghighlight1 success\">{$mklib->lang['ad_deleted']}</div>";
	}

	//Personal Blocks (added via Portal CP)	
	$myquery = $DB->query("SELECT id, title, active, personal FROM mkp_blocks WHERE personal > '0' ORDER BY active DESC, id");

	$content = "
	<tr>
	  <td>
	    <script type=\"text/javascript\">

		    function makesure() {
		    if (confirm('{$mklib->lang[ad_delblockconfirm]}')) {
		    return true;
		    } else {
		    return false;
		    }
		    }

	    </script>
	    $checksave
	    <table width=\"100%\" class=\"tabmain\">
	      <tr>
		<td class=\"tdblock\" colspan=\"7\">" . $mklib->helplink('had_bllist','left') . "&nbsp;{$mklib->lang['ad_bllist']}</td>
	      </tr> 
	      <tr>
		<td width=\"5%\" class=\"tdblock\">{$mklib->lang['ad_id']}</td>
		<td width=\"3%\" class=\"tdblock\">{$mklib->lang['ad_type']}</td>
		<td width=\"58%\" class=\"tdblock\">{$mklib->lang['ad_title']}</td>
		<td width=\"34%\" colspan=\"4\" class=\"tdblock\">{$mklib->lang['ad_actions']}</td>
	      </tr>
	";
	  while( $row = $DB->fetch_row($myquery) ) {

		//Active block highlighting  
		$active = $row['active'];
		if ($clastr == "tdglobal" || $clastr == "tdglobal bghighlight1") {
			$clastr = $active ? "modulex bghighlight1" : "modulex"; //Active block highlighting
		} else {
			$clastr = $active ? "tdglobal bghighlight1" : "tdglobal"; //Active block highlighting
		}
		$personal = $row['personal'];

		//Block type
		switch($personal) {
			case '1': // HTML Block
				$typeimg = 'page_white_html.png';
				$typealt = 'HTML';
                        break;
			case '2': // Internal Page Links Block
				$typeimg = 'page_white_link.png';
				$typealt = 'Links';
                        break;
			default: // case: 3 - PHP Block
				$typeimg = 'page_white_php.png';
				$typealt = 'PHP';
                        break;
		}

		$content .= "
		<tr class=\"$clastr\">";
		$content .= "
		<td width=\"5%\"><a href=\"index.php?ind=ad_blocks&amp;op=blocks_edit&amp;idc={$row['id']}&amp;personal=$personal\" title=\"{$mklib->lang['ad_edit']}\">{$row['id']}</a></td>
		";
		$content .= "
		<td width=\"3%\" style=\"text-align: center\"><img src=\"images/icons/famfamfam/silk/$typeimg\" title=\"$typealt\" alt=\"$typealt\" /></td>
		";
		$content .= "
		<td width=\"58%\"><a href=\"index.php?ind=ad_blocks&amp;op=blocks_edit&amp;idc={$row['id']}&amp;personal=$personal\" title=\"{$mklib->lang['ad_edit']}\">{$row['title']}</a></td>
		";
		$content .= "
		<td width=\"8%\" align=\"center\" nowrap=\"nowrap\">
		";
		$content .= "
		[<a href=\"index.php?ind=ad_blocks&amp;op=blocks_perms&amp;idc={$row['id']}\">{$mklib->lang['ad_mperm']}</a>]
		";
		$content .= "
		</td><td width=\"8%\" align=\"center\" nowrap=\"nowrap\">
		";
		$content .= "
		[<a href=\"index.php?ind=ad_blocks&amp;op=blocks_edit&amp;idc={$row['id']}&amp;personal=$personal\">{$mklib->lang['ad_edit']}</a>]
		";
		$content .= "
		</td><td width=\"8%\" align=\"center\" nowrap=\"nowrap\">
		";
		$content .= "
		[<a href=\"index.php?ind=ad_blocks&amp;op=blocks_delete&amp;idc={$row['id']}\" onclick=\"return makesure()\">{$mklib->lang['ad_delete']}</a>]
		";
		$content .= "
		</td><td width=\"5%\" align=\"center\" nowrap=\"nowrap\">
		";
		$content .= "
		<a href=\"index.php?ind=ad_blocks&amp;op=blocks_modules&amp;idc={$row['id']}\"><img src=\"images/icons/famfamfam/silk/block_edit.png\" title=\"{$mklib->lang['ad_blocks_medit']}\" alt=\"{$mklib->lang['ad_blocks_medit']}\" /></a>
		";
		$content .= "
		</td></tr>
		";
	  }
	  $content .= "
	    </table>
	  </td>
	</tr>
	";
	$content .= "
	<tr>
	  <td>
	    <table width = \"100%\" class=\"tabmain\">
	    <tr>
		<td class=\"tdblock\" colspan=\"6\">" . $mklib->helplink('had_sbllist','left') . "&nbsp;{$mklib->lang['ad_sbllist']}</td>
	      </tr>   
	      <tr>
		<td width=\"5%\" class=\"tdblock\">{$mklib->lang['ad_id']}</td>
		<td width=\"3%\" class=\"tdblock\">{$mklib->lang['ad_type']}</td>
		<td width=\"68%\" class=\"tdblock\">{$mklib->lang['ad_title']}</td>
		<td width=\"24%\" colspan=\"3\" class=\"tdblock\">{$mklib->lang['ad_actions']}</td>
	      </tr>      
	";
	
	//System Blocks (uploaded php block files)
	$DB->query("SELECT id, title, active, personal FROM mkp_blocks WHERE personal = '0' ORDER BY active DESC, id");
	while( $row = $DB->fetch_row() ) {
		//Active block highlighting  
		$active = $row['active'];
		if ($clastr == "tdglobal" || $clastr == "tdglobal bghighlight1") {
			$clastr = $active ? "modulex bghighlight1" : "modulex"; //Active block highlighting
		} else {
			$clastr = $active ? "tdglobal bghighlight1" : "tdglobal"; //Active block highlighting
		}
		$personal = $row['personal'];
		$content .= "
		<tr class=\"$clastr\"><td width=\"5%\">{$row['id']}
		";
		$content .= "
		<td width=\"3%\" style=\"text-align: center\"><img src=\"images/icons/famfamfam/silk/page_white_php.png\" title=\"PHP\" alt=\"PHP\" /></td>
		";
	    	$content .= "
		</td><td width=\"68%\">
		";
    		$content .= "
                   	".(isset($mklib->lang[$row['title']])?$mklib->lang[$row['title']]:$row['title'])."
	        ";
		$content .= "
		</td><td width=\"8%\" align=\"center\">
		";
		$content .= "
		[<a href=\"index.php?ind=ad_blocks&amp;op=blocks_perms&amp;idc={$row['id']}\">{$mklib->lang['ad_mperm']}</a>]
		";
		$content .= "
		</td><td width=\"8%\" align=\"center\">
		";
		$content .= "<a href=\"index.php?ind=ad_blocks&amp;op=blocks_modules&amp;idc={$row['id']}\"><img src=\"images/icons/famfamfam/silk/block_edit.png\" title=\"{$mklib->lang['ad_blocks_medit']}\" alt=\"{$mklib->lang['ad_blocks_medit']}\" /></a>";	
		$content .= "
		</td><td width=\"8%\" align=\"center\">
		";
		$content .= "-";
		$content .= "
		</td></tr>
		";
	  }
	$content .= "
	    </table>
	  </td>
	</tr>
	";
	$output = $Skin->view_block("{$mklib->lang['ad_mblocks']}", "$content");
	$mklib->printpage_admin($mklib->lang['ad_titlepage'].$mklib->lang['tt_sep'].$mklib->lang['ad_mblocks'].$mklib->lang['tt_sep'].$mklib->lang['ad_mmanage'], $output);

	}
	
	//Blocks: switch type
	function blocks_edit($id, $personal) {
		switch($personal) {
			case '2':
			$this->blocks_edit_link($id);
    		break;
			case '3':
			$this->blocks_edit_php($id);
    		break;
    		default:
    			$this->blocks_edit_html($id);
    		break;
		}
		exit;
	}
	
	//HTML blocks: edit
	function blocks_edit_html($id) {
	global $mkportals, $mklib, $Skin, $DB, $editorscript;
		$editorscript = 1;
		$textarepar = "mce_editable=\"true\"";
		$textarew = "100%";
		$bbeditor= "";
		if ($mklib->mkeditor == "BBCODE") {
			$editorscript = "";
			$textarepar = "";
			$textarew = "75%";
			$bbeditor= $mklib->get_bbeditor("1");
		}
		$myquery = $DB->query("SELECT title, content FROM mkp_blocks WHERE id='$id'");
		$row = $DB->fetch_row($myquery);
		$testo = stripslashes($row['content']);
		$title = stripslashes($row['title']);
	   $content = "
		<tr>
		  <td>
		  
		    <form action=\"index.php?ind=ad_blocks&amp;op=blocks_update&amp;idc=$id\" method=\"post\" id=\"editor\" name=\"editor\">
		    <table width=\"100%\">
		      <tr>
			<td class=\"tdblock\">
			  {$mklib->lang['ad_title']}:
			  <input type=\"text\" name=\"titleblock\" value=\"{$title}\" size=\"40\" />
			</td>
		      </tr>
		      <tr>
			<td class=\"tdblock\">
			$bbeditor
 			<textarea id=\"ta\" name=\"ta\" $textarepar style=\"width: $textarew\" rows=\"14\" cols=\"40\">$testo</textarea>
			</td>
		      </tr>
		      <tr>
			<td class=\"tdblock\">
			  <input type=\"submit\" name=\"ok\" value=\"{$mklib->lang['ad_save']}\" class=\"mkbutton\" />		
			</td>
		      </tr>
		    </table>
		    </form>
		    
		  </td>
		</tr>

	";
	$output = $Skin->view_block("{$mklib->lang['ad_blpedit']}", "$content");
	$mklib->printpage_admin($mklib->lang['ad_titlepage'].$mklib->lang['tt_sep'].$mklib->lang['ad_mblocks'].$mklib->lang['tt_sep'].$mklib->lang['ad_mmanage'], $output);
	}
	
	//Blocks: choose type for new block
	function blocks_main_new() {
	global $mkportals, $mklib, $Skin, $DB;
	   $content .= "		
		  <tr align=\"center\">
		    <td><br /><a href=\"index.php?ind=ad_blocks&amp;op=blocks_new\"><img src=\"images/icons/foood/block.gif\" border=\"0\" alt=\"\" /></a><br />
		    </td>
		  </tr>
		  <tr align=\"center\">
		    <td>{$mklib->lang['ad_blcreateh']}<br /><br /><br /><br /></td>
		  </tr>
		  <tr align=\"center\">
		    <td><a href=\"index.php?ind=ad_blocks&amp;op=blocks_new_php\"><img src=\"images/icons/foood/block3.gif\" border=\"0\" alt=\"\" /></a><br /></td>
		  </tr>
		  <tr align=\"center\">
		    <td>{$mklib->lang['ad_blcreatep']}<br /><br /><br /><br /></td>
		  </tr>
		  <tr align=\"center\">
		    <td><a href=\"index.php?ind=ad_blocks&amp;op=blocks_new_link\"><img src=\"images/icons/foood/block2.gif\" border=\"0\" alt=\"\" /></a><br /></td>
		  </tr>
		  <tr align=\"center\">
		    <td>{$mklib->lang['ad_blcreatel']}<br />  <br /><br /></td>
		  </tr>		
	";
	$output = $Skin->view_block("{$mklib->lang['ad_blcreatetit']}", "$content");
	$mklib->printpage_admin($mklib->lang['ad_titlepage'].$mklib->lang['tt_sep'].$mklib->lang['ad_mblocks'].$mklib->lang['tt_sep'].$mklib->lang['ad_mcreation'], $output);
	}
	
	//HTML blocks: create
	function blocks_new() {
	global $mkportals, $mklib, $Skin, $DB, $editorscript;
		$editorscript = 1;
		$textarepar = "mce_editable=\"true\"";
		$textarew = "100%";
		$bbeditor= "";
		if ($mklib->mkeditor == "BBCODE") {
			$editorscript = "";
			$textarepar = "";
			$textarew = "75%";
			$bbeditor= $mklib->get_bbeditor("1");
		}
	   $content = "
		<tr>
		  <td>
		  
		    <form action=\"index.php?ind=ad_blocks&amp;op=blocks_savenew\" method=\"post\" id=\"editor\" name=\"editor\">
		    <table width=\"100%\">
		      <tr>
			<td class=\"tdblock\">
			  {$mklib->lang['ad_title']}:
			  <input type=\"text\" name=\"titleblock\"  size=\"40\" />
			</td>
		      </tr>
		      <tr>
			<td class=\"tdblock\">
			$bbeditor
 			<textarea id=\"ta\" name=\"ta\" $textarepar style=\"width: $textarew\" rows=\"14\" cols=\"40\"></textarea>
			</td>
		      </tr>
		      <tr>
			<td>
			  <input type=\"submit\" name=\"ok\" value=\"{$mklib->lang['ad_save']}\" class=\"mkbutton\" />
			</td>
		      </tr>		
		    </table>
		    </form>
		    
		  </td>
		</tr>

	";
	$output = $Skin->view_block("{$mklib->lang['ad_blcreateh']}", "$content");
	$mklib->printpage_admin($mklib->lang['ad_titlepage'].$mklib->lang['tt_sep'].$mklib->lang['ad_mblocks'].$mklib->lang['tt_sep'].$mklib->lang['ad_mcreation'], $output);
	}
	
	//Internal Page Link blocks: create
	function blocks_new_link() {
	global $mkportals, $mklib, $Skin, $DB;
		$titleblock = stripslashes($mkportals->input['titleblock']);
		$filename = "../cache/tmp_block.php";
		$link = $mkportals->input['vlink'];
		$filetext = " ";
		if ($link && $mkportals->input['mode'] == "add") {
			$tpage = $this->retr_title($link);
			$handle = fopen($filename, "r");
			$filetext = fread($handle, filesize($filename));
			fclose($handle);
			$filetext .= "<tr><td width=\"100%\" class=\"tdblock\"><img class=\"mkicon\" src=\"frec.gif\" align=\"left\" alt=\"\" />&nbsp;<a class=\"uno\" href=\"$mklib->siteurl/index.php?pid=$link\">$tpage</a></td></tr>\n";
		}
		if ($link && $mkportals->input['mode'] == "remove") {
			$tpage = $this->retr_title($link);
			$handle = fopen($filename, "r");
			$filetext = fread($handle, filesize($filename));
			fclose($handle);
			$remove = "<tr><td width=\"100%\" class=\"tdblock\"><img class=\"mkicon\" src=\"frec.gif\" align=\"left\" alt=\"\" />&nbsp;<a class=\"uno\" href=\"$mklib->siteurl/index.php?pid=$link\">$tpage</a></td></tr>\n";
			$filetext = str_replace($remove, "", $filetext);
		}
		if (!$filetext) {
			$filetext = " ";
		}
		if (!$handle = fopen($filename, 'w')) {
   			$message = "{$mklib->lang['ad_blnofile']}";
			$mklib->error_page($message);
			exit;
   		}
   		if (!fwrite($handle, $filetext)) {
       		$message = "{$mklib->lang['ad_blnofile']}";
			$mklib->error_page($message);
			exit;
   		}
		fclose($handle);
		$query = $DB->query("SELECT id, title FROM mkp_pages");
	  	while( $row = $DB->fetch_row($query) ) {
			$idpage = $row['id'];
			$page = $row['title'];
			$cselect.= "<option value='$idpage'>$page</option>\n";
		}
	   $content .= "
		<tr>
		  <td>

		    <form action=\"index.php?ind=ad_blocks&amp;op=blocks_new_link&amp;mode=add\" name=\"ADD\" method=\"post\">
		    <table width=\"100%\">
		
		    <tr>
		      <td class=\"tdblock\">
			{$mklib->lang['ad_title']}:
			<input type=\"text\" value=\"$titleblock\" name=\"titleblock\"  size=\"40\" />
		      </td>
		    </tr>
		    <tr>
		      <td class=\"tdblock\"> {$mklib->lang['ad_blavpages']}
			<select class=\"bgselect\" name=\"vlink\" size=\"1\">
			{$cselect}
			</select>
		      </td>
		    </tr>
		    <tr>
		      <td class=\"tdblock\">
			<input type=\"submit\" value=\"{$mklib->lang['ad_bladdlink']}\" class=\"mkbutton\" />
		      </td>
		    </tr>
		  </table>
		  </form>
		  
		</td>
	      </tr>
		  
	      <tr>
		<td>		    
		
		  <form action=\"index.php?ind=ad_blocks&amp;op=blocks_new_link&amp;mode=remove\" name=\"Rem\" method=\"post\">
		  <table class=\"trattini\" width=\"100%\">
		    <tr>
		      <td class=\"tdblock\"> {$mklib->lang['ad_bllremlink']}
			<select class=\"bgselect\" name=\"vlink\" size=\"1\">
			{$cselect}
			</select>
			<input type=\"hidden\" value=\"$titleblock\" name=\"titleblock\" />
		      </td>
		    </tr>
		    <tr>
		      <td class=\"tdblock\">
			<input type=\"submit\" name=\"ok\" value=\"{$mklib->lang['ad_blremlink']}\" class=\"mkbutton\" />
		      </td>
		    </tr>		
		  </table>
		  </form>
		    
		</td>
		</tr>
		    
		<tr>
		  <td align=\"left\" height=\"100%\">
		    <table width=\"150\">
		      <tr>
			<td>		      
			  <iframe src=\"index.php?ind=ad_blocks&amp;op=show_codelink&amp;titleblock=$titleblock\" frameborder=\"0\"  width=\"150\" align=\"middle\" height=\"200\" scrolling=\"auto\"></iframe>
			</td>
		      </tr>
		    </table>
		  </td>
		</tr>
		<tr>
		  <td  align=\"left\">
		    
		    <form action=\"index.php?ind=ad_blocks&amp;op=blocks_save_link\" method=\"post\" name=\"s_block\">
		      <input type=\"hidden\" value=\"$titleblock\" name=\"titleblock\" />
		      <input type=\"submit\" name=\"oks\" value=\"{$mklib->lang['ad_blocksave']}\" class=\"mkbutton\" />
		    </form>
		      
		  </td>
		</tr>	

	";

	$output = $Skin->view_block("{$mklib->lang['ad_blcreatel']}", "$content");
	$mklib->printpage_admin($mklib->lang['ad_titlepage'].$mklib->lang['tt_sep'].$mklib->lang['ad_mblocks'].$mklib->lang['tt_sep'].$mklib->lang['ad_mcreation'], $output);
	}

	//PHP blocks: create & preview
	function blocks_new_php() {
	global $mkportals, $mklib, $Skin, $DB;	

		//Save or Preview?
		if (isset($mkportals->input['oksave'])) {
			$this->blocks_save_php();
		}

		//User-defined title & text values
		$titleblock = stripslashes($mkportals->input['titleblock']); //strip slashes added by $_POST
		
		//Important! Cannot use stripslashes here or it will strip escape characters in the php code
		$testo = $mklib->convert_viewphpadmin($_POST['ta']);
		
		//Default title & text values
		if (!$titleblock) {
			$titleblock = $mklib->lang['ad_title'];
   		}		
		if (!$testo) {
			$testo = $mklib->lang['ad_blphpcode'];
		}		
		$testo =  trim ($testo);

		//Add css for preview
		$css = "$mklib->template/style.css";
 		$filetext = "<head>\n<link href=\"{$css}\" rel=\"stylesheet\" type=\"text/css\">\n</head>\n";
		$filetext .= $testo;

		//Open tmp_block.php
		$filename = "../cache/tmp_block.php";
		if (!$handle = fopen($filename, 'wb')) {
         		$message = $mklib->lang['ad_fopen_error'];
			$mklib->error_page($message);
			exit;
		}
		//Write content to tmp_block.php
   		if (!fwrite($handle, $filetext)) {
       			$message = $mklib->lang['ad_blnofile'];
			$mklib->error_page($message);
			exit;
   		}
		fclose($handle);   		

	   $content .= "
		<tr>
		  <td>
		  
		    <form action=\"index.php?ind=ad_blocks&amp;op=blocks_new_php\" method=\"post\" id=\"editor\" name=\"editor\">
		    <table width=\"300\">
		      <tr>
			<td class=\"tdblock\">
			  {$mklib->lang['ad_title']}:
			  <input type=\"text\" value=\"$titleblock\" name=\"titleblock\" size=\"40\" />
			</td>
		      </tr>
		      <tr>
			<td class=\"tdblock\">
			  <textarea id=\"ta\" name=\"ta\"  rows=\"20\" cols=\"75\">$testo</textarea>
			  <input type=\"submit\" name=\"okpreview\" value=\"{$mklib->lang['ad_blpreview']}\" class=\"mkbutton\" />
			  <input type=\"submit\" name=\"oksave\" value=\"{$mklib->lang['ad_blocksave']}\" class=\"mkbutton\" />
			</td>
		      </tr>
		    </table>
		    </form>

		  </td>
		</tr>		    		    
		<tr>
		  <td align=\"left\" height=\"100%\">
		    <table width=\"150\">
		      <tr>
			<td>
			  <iframe src=\"index.php?ind=ad_blocks&amp;op=show_code&amp;titleblock=$titleblock\" frameborder=\"0\"  width=\"150\" align=\"middle\" height=\"200\" scrolling=\"auto\"></iframe>
			</td>
		      </tr>
		    </table>
		  </td>
		</tr>
	";

	$output = $Skin->view_block("{$mklib->lang['ad_blcreatep']}", "$content");
	$mklib->printpage_admin($mklib->lang['ad_titlepage'].$mklib->lang['tt_sep'].$mklib->lang['ad_mblocks'].$mklib->lang['tt_sep'].$mklib->lang['ad_mcreation'], $output);
	}

	//PHP blocks: edit and preview
	function blocks_edit_php($idblock, $check="") {
	global $mkportals, $mklib, $Skin, $DB;	

		//Save or Preview?
		if (isset($mkportals->input['oksave'])) {			
			$this->blocks_update_php();
			//index.php?ind=ad_blocks&amp;op=blocks_update_php&amp;idblock=$idblock&amp;titleblock=$titleblock
		}

		//User-defined title and text values
		$titleblock = stripslashes($mkportals->input['titleblock']); //strip slashes added by $_POST
		
		//Important! Cannot use stripslashes here or it will strip escape characters in the php code
		$testo = $mklib->convert_viewphpadmin($_POST['ta']);

		//Get cached block filename & title
		$query = $DB->query("SELECT file, title FROM mkp_blocks WHERE id = '$idblock'");
		$row = $DB->fetch_row($query);
		if (!$titleblock) {
			$titleblock = $row['title'];
		}

		//Cached title and text values
		if (!$testo) {
			$filename = $row['file'];
			if (!$handle = fopen('../'.$filename, "rb")) {
         			$message = $mklib->lang['ad_fopen_error'];
				$mklib->error_page($message);
				exit;
			}
			$testo = fread($handle, filesize('../'.$filename));
			fclose($handle);
		}		
		$testo =  trim($testo);

		//Add css for preview
		$css = "$mklib->template/style.css";
 		$filetext = "<head>\n<link href=\"{$css}\" rel=\"stylesheet\" type=\"text/css\">\n</head>\n";
		$filetext .= $testo;

		$filename = "../cache/tmp_block.php";

		//Open tmp_block.php or display error
		if (!$handle = fopen($filename, 'wb')) {
         		$message = $mklib->lang['ad_fopen_error'];
			$mklib->error_page($message);
			exit;
		}
		//Write text to tmp_block.php or display error
   		if (!fwrite($handle, $filetext)) {
       			$message = $mklib->lang['ad_blnofile'];
			$mklib->error_page($message);
			exit;
   		}
		fclose($handle);		

	   $content .= "
		<tr>
		  <td>
		  
		    <form action=\"index.php?ind=ad_blocks&amp;op=blocks_edit_php&amp;idblock=$idblock\" method=\"post\" id=\"editor\" name=\"editor\">
		    <table width=\"300\">
		      <tr>
			<td class=\"tdblock\">
			  {$mklib->lang['ad_title']}:
			  <input type=\"text\" value = \"$titleblock\" name=\"titleblock\"  size=\"40\" />
			</td>
		      </tr>
		      <tr>
			<td class=\"tdblock\">
			  <textarea id=\"ta\" name=\"ta\"  rows=\"20\" cols=\"75\">$testo</textarea>
			  <input type=\"submit\" name=\"okpreview\" value=\"{$mklib->lang['ad_blpreview']}\" class=\"mkbutton\" />
			  <input type=\"submit\" name=\"oksave\" value=\"{$mklib->lang['ad_blocksave']}\" class=\"mkbutton\" />
			</td>
		      </tr>
		    </table>
		    </form>
		    
		  </td>
		</tr>
		
		<tr>
		  <td align=\"left\" height=\"100%\">
		    <table width=\"150\">
		      <tr>
			<td>
			  <iframe src=\"index.php?ind=ad_blocks&amp;op=show_code&amp;titleblock=$titleblock\" frameborder=\"0\"  width=\"150\" align=\"middle\" height=\"200\" scrolling=\"auto\"></iframe>
			</td>
		      </tr>
		    </table>
		  </td>
		</tr>
	";

	$output = $Skin->view_block("{$mklib->lang['ad_bleditp']}", "$content");
	$mklib->printpage_admin($mklib->lang['ad_titlepage'].$mklib->lang['tt_sep'].$mklib->lang['ad_mblocks'].$mklib->lang['tt_sep'].$mklib->lang['ad_mmanage'], $output);
	}
	
	//Internal Page Link blocks: edit
	function blocks_edit_link($idblock) {
	global $mkportals, $mklib, $Skin, $DB;
		$titleblock = $mkportals->input['titleblock'];
		$filename = "../cache/tmp_block.php";
		$link = $mkportals->input['vlink'];

		if (!$mkportals->input['mode']) {
			$query = $DB->query("SELECT title, content FROM mkp_blocks WHERE id = '$idblock'");
			$row = $DB->fetch_row($query);
			$titleblock = $row['title'];
			$filetext = $row['content']."\n";
		}
		if ($link && $mkportals->input['mode'] == "add") {		
			$tpage = $this->retr_title($link);
			$handle = fopen($filename, "r");
			$filetext = fread($handle, filesize($filename));
			fclose($handle);
			$filetext .= "<tr><td width=\"100%\" class=\"tdblock\"><img class=\"mkicon\" src=\"frec.gif\" align=\"left\" alt=\"\" />&nbsp;<a class=\"uno\" href=\"$mklib->siteurl/index.php?pid=$link\">$tpage</a></td></tr>\n";
		}
		if ($link && $mkportals->input['mode'] == "remove") {
			$tpage = $this->retr_title($link);
			$handle = fopen($filename, "r");
			$filetext = fread($handle, filesize($filename));
			fclose($handle);
			if (!$tpage) {
				$pos = (strpos($filetext, "pid=".$link."\""));
				$pos = strpos($filetext, ">", $pos) +1;
				$pos2 = strpos($filetext, "<", $pos);
				$tpage = substr($filetext, $pos, ($pos2 - $pos));
			}
			$remove = "<tr><td width=\"100%\" class=\"tdblock\"><img class=\"mkicon\" src=\"frec.gif\" align=\"left\" alt=\"\" />&nbsp;<a class=\"uno\" href=\"$mklib->siteurl/index.php?pid=$link\">$tpage</a></td></tr>\n";
			$filetext = str_replace($remove, "", $filetext);
		}
		if (!$filetext) {
			$filetext = " ";
		}
		if (!$handle = fopen($filename, 'w')) {
         	$message = "{$mklib->lang['ad_blnofile']}";
			$mklib->error_page($message);
			exit;
   		}
   		if (!fwrite($handle, $filetext)) {
       		$message = "{$mklib->lang['ad_blnofile']}";
			$mklib->error_page($message);
			exit;
   		}
		fclose($handle);
		$query = $DB->query("SELECT id, title FROM mkp_pages");
	  	while( $row = $DB->fetch_row($query) ) {
			$idpage = $row['id'];
			$page = $row['title'];
			$cselect.= "<option value=\"$idpage\">$page</option>\n";
		}
		$handle = fopen($filename, "r");
		$linkrem = fread($handle, filesize($filename));
		fclose($handle);
		$linklist = array();
		$cselect2 = "";
		$linklist = explode("</tr>", $linkrem);
		foreach ($linklist as $value) {
			$pos = (strpos($value, "pid=")) + 4;
			if ($pos > strlen($value)) {continue;}
			$pos2 = strpos($value, "\"", $pos);
			$idpage = substr($value, $pos, ($pos2 - $pos));
			$pos = strpos($value, ">", $pos2) +1;
			$pos2 = strpos($value, "<", $pos);
			$page = substr($value, $pos, ($pos2 - $pos));
			if ($idpage) {
				$cselect2.= "<option value=\"$idpage\">$page</option>\n";
			}
		}
		
	   $content .= "
		<tr>
		  <td>
		
		    <form action=\"index.php?ind=ad_blocks&amp;op=blocks_edit_link&amp;mode=add&amp;idblock='$idblock\" name=\"ADD\" method=\"post\">
		    <table width=\"100%\">
		      <tr>
			<td class=\"tdblock\">
			  {$mklib->lang['ad_title']}:
			  <input type=\"text\" value=\"$titleblock\" name=\"titleblock\" size=\"40\" />
			  <input type=\"hidden\" value=\"$idblock\" name=\"idblock\" />
			</td>
		      </tr>
		      <tr>
			<td class=\"tdblock\"> {$mklib->lang['ad_blavpages']}:
			  <select class=\"bgselect\" name=\"vlink\" size=\"1\">
			  {$cselect}
			  </select>
			</td>
		      </tr>
		      <tr>
			<td class=\"tdblock\">
			  <input type=\"submit\" value=\"{$mklib->lang['ad_bladdlink']}\" class=\"mkbutton\" />
			</td>
		      </tr>
		    </table>
		    </form>
		    
		  </td>
		</tr>
		<tr>
		  <td>
		
		    <form action=\"index.php?ind=ad_blocks&amp;op=blocks_edit_link&amp;mode=remove&amp;idblock='$idblock\" name=\"Rem\" method=\"post\">
		    <table class=\"trattini\" width=\"100%\">
		      <tr>
			<td class=\"tdblock\"> {$mklib->lang['ad_bllremlink']}
			  <select class=\"bgselect\" name=\"vlink\" size=\"1\">
			  {$cselect2}
			  </select>
			  <input type=\"hidden\" value=\"$titleblock\" name=\"titleblock\" />
			  <input type=\"hidden\" value=\"$idblock\" name=\"idblock\" />
			</td>
		      </tr>
		      <tr>
			<td class=\"tdblock\">
			  <input type=\"submit\" name=\"ok\" value=\"{$mklib->lang['ad_blremlink']}\" class=\"mkbutton\" />
			</td>
		      </tr>		
		    </table>
		    </form>

		  </td>
		</tr>
		<tr>
		  <td align=\"left\" height=\"100%\">
		    <table width=\"150\">
		      <tr>
			<td>
			  <iframe src=\"index.php?ind=ad_blocks&amp;op=show_codelink&amp;titleblock=$titleblock\" frameborder=\"0\"  width=\"150\" align=\"middle\" height=\"200\" scrolling=\"auto\"></iframe>
			</td>
		      </tr>
		    </table>
		  </td>
		</tr>
		<tr>
		  <td  align=\"left\">
		    <form action=\"index.php?ind=ad_blocks&amp;op=blocks_update_link&amp;idblock=$idblock&amp;titleblock=$titleblock\" method=\"post\" name=\"s_block\">
		      <input type=\"hidden\" value=\"$titleblock\" name=\"titleblock\" />
		      <input type=\"hidden\" value=\"$idblock\" name=\"idblock\" />
		      <input type=\"submit\" name=\"oks\" value=\"{$mklib->lang['ad_blocksave']}\" class=\"mkbutton\" />
		    </form>
		  </td>
		</tr>
	";

	$output = $Skin->view_block("{$mklib->lang['ad_bleditl']}", "$content");
	$mklib->printpage_admin($mklib->lang['ad_titlepage'].$mklib->lang['tt_sep'].$mklib->lang['ad_mblocks'].$mklib->lang['tt_sep'].$mklib->lang['ad_mmanage'], $output);
	}

	//HTML blocks: save
	function blocks_savenew() {
	global $mkportals, $mklib, $Skin,  $DB;

		//Error if title or content field is empty
		$message = '';		
		$message .= !$mkportals->input['titleblock'] ? '<p>'.$mklib->lang['ad_notitle'].'</p>' : '';
		$message .= !$_POST['ta'] ? '<p>'.$mklib->lang['ad_nocontent'].'</p>' : '';
		if (!$mkportals->input['titleblock'] || !$_POST['ta']) {
		      $mklib->error_page($message);
		      exit;
		}
		
		//normally done by $mkportals->input
		$content = str_replace( "\r", "", $_POST['ta']);
		//If newline is not preceeded by ">" and is not followed by "</" tag
		//$content = preg_replace( "/(?<!\>)\n(?!\<\/(.+?)\>)/"  , "<br />"   , $content ); //working

		$content = $mklib->convert_savedbadmin($content);
		$titleblock = $mklib->convert_savedbadmin($mkportals->input['titleblock']);
		$DB->query("INSERT INTO mkp_blocks (title, personal, content) VALUES ('$titleblock', '1', '$content')");
		$DB->close_db();
		Header("Location: index.php?ind=ad_blocks&op=ad_blocks&mode=saved");
		exit;
	}
	//Internal Page Link blocks: save
	function blocks_save_link() {
	global $mkportals, $mklib, $Skin,  $DB;
		$filename = "../cache/tmp_block.php";
		$titleblock = $mkportals->input['titleblock'];
		if (!$titleblock) {
			$titleblock = "{$mklib->lang['ad_title']}??";
   		}
		$titleblock = $mklib->convert_savedbadmin($titleblock);
		$handle = fopen($filename, "r");
		$content = fread($handle, filesize($filename));
		fclose($handle);
		$content =  trim ($content);
		$content = $mklib->convert_savedbadmin($content);
		$DB->query("INSERT INTO mkp_blocks (title, personal, content) VALUES ('$titleblock', '2', '$content')");
		$DB->close_db();
		Header("Location: index.php?ind=ad_blocks&op=ad_blocks&mode=saved");
		exit;
	}
	//HTML blocks: edit save
	function blocks_update($id) {
		global $mkportals, $mklib, $Skin,  $DB;
		//Error if title or content field is empty
		$message = '';		
		$message .= !$mkportals->input['titleblock'] ? '<p>'.$mklib->lang['ad_notitle'].'</p>' : '';
		$message .= !$_POST['ta'] ? '<p>'.$mklib->lang['ad_nocontent'].'</p>' : '';
		if (!$mkportals->input['titleblock'] || !$_POST['ta']) {
		      $mklib->error_page($message);
		      exit;
		}
		
		$content = str_replace( "\r", "", $_POST['ta']);
		//If newline is not preceeded by ">" and is not followed by "</" tag
		//$content = preg_replace( "/(?<!\>)\n(?!\<\/(.+?)\>)/"  , "<br />"   , $content ); //working

		$content = $mklib->convert_savedbadmin($content);
		$titleblock = $mklib->convert_savedbadmin($mkportals->input['titleblock']);
		$DB->query("UPDATE mkp_blocks SET content ='$content', title='$titleblock' WHERE id='$id'");
		$DB->close_db();
		Header("Location: index.php?ind=ad_blocks&op=blocks_list&mode=saved");
		exit;
	}
	//Internal Page Link blocks: edit save
	function blocks_update_link() {
	global $mkportals, $mklib, $Skin, $DB;
		$idblock = intval($mkportals->input['idblock']);
		$titleblock = $mklib->convert_savedbadmin($mkportals->input['titleblock']);
		$filename = "../cache/tmp_block.php";
		if (!$titleblock) {
			$titleblock = "{$mklib->lang['ad_title']}??";
   		}
		$handle = fopen($filename, "r");
		$content = fread($handle, filesize($filename));
		fclose($handle);
		$content =  trim ($content);
		$content = $mklib->convert_savedbadmin($content);
		$DB->query("UPDATE mkp_blocks SET content ='$content', title='$titleblock' WHERE id='$idblock'");
		$DB->close_db();
		Header("Location: index.php?ind=ad_blocks&op=blocks_list&mode=saved");
		exit;
	}
	
	//Blocks: delete
	function blocks_delete($id) {
	global $mkportals, $mklib, $Skin,  $DB;
		if ($id != 1) {
			$query = $DB->query("SELECT file, personal FROM mkp_blocks WHERE id = '$id'");
			$row = $DB->fetch_row($query);
			$personal = $row['personal'];
			$filename = $row['file'];
			if ($personal == 3) {
				@unlink("$filename");	
			}
			$DB->query("DELETE FROM mkp_blocks WHERE id='$id'");
		}
		$DB->close_db();
		Header("Location: index.php?ind=ad_blocks&op=blocks_list&mode=deleted");
		exit;
	}

	function show_code()
 	{
 		global $mkportals, $DB, $Skin, $mklib, $std, $MK_BOARD;

		//This is a temporary hack
		//Slashes need to be stripped twice in the php block & page preview for SMF only
		//This should be addressed in the next release
		$title = ($MK_BOARD == 'SMF') ? stripslashes($mkportals->input['titleblock']) : $mkportals->input['titleblock'];

		$title = stripslashes($title);
		if (!$title) {
			$title = "{$mklib->lang['ad_blpreview']}";
   		}
		@require "../cache/tmp_block.php";
		$output = $Skin->view_block("$title", "$content");

		print $output;


 	}
	function show_codelink()
 	{
 		global $mkportals, $DB, $Skin, $mklib, $std;
		$titleblock = $mkportals->input['titleblock'];
		if (!$titleblock) {
			$titleblock = "{$mklib->lang['ad_blpreview']}";
   		}
		$css = "$mklib->template/style.css";
 		$content = "<head>\n<link href=\"{$css}\" rel=\"stylesheet\" type=\"text/css\">\n</head>\n";
		$filename = "../cache/tmp_block.php";
		$handle = fopen($filename, "r");
		$content .= fread($handle, filesize($filename));
		$content = str_replace ("frec.gif", "$mklib->images/frec.gif", $content);
		fclose($handle);
		$output = $Skin->view_block("$titleblock", "$content");

		print $output;
 	}

	//PHP blocks: new save
	function blocks_save_php() {
	global $mkportals, $mklib, $Skin, $DB;

		//User input cleanup
		$titleblock = stripslashes($mkportals->input['titleblock']); //strip slashes added by $_POST
		
		$titleblock = $mklib->convert_savedbadmin($titleblock);
		//$titleblock = $mklib->post_htmlspecialchars($titleblock);
		if (!$titleblock) {
			$titleblock = $mklib->lang['ad_title'];
			//$titleblock = $mklib->post_htmlspecialchars($titleblock);
		}
		//Important! Cannot use stripslashes here or it will strip escape characters in the php code
		$testo = $mklib->convert_viewphpadmin($_POST['ta']);
		$testo = trim ($testo);

		//Create new block ID
		$query = $DB->query("SELECT id FROM mkp_blocks ORDER BY id DESC LIMIT 1");
		$row = $DB->fetch_row($query);
		$filename2 = "cache/pblock_";
		$filename2 .= ++$row['id'];
		$filename2 .= ".php";

		//Create cached php file
		if (!$handle = fopen('../'.$filename2, 'wb')) {
         		$message = $mklib->lang['ad_blnofile'];
			$mklib->error_page($message);
			exit;
   		}
		//Write $testo to cached php file
		if (!fwrite($handle,$testo)) {
       			$message = $mklib->lang['ad_blnofile'];
			$mklib->error_page($message);
			exit;
   		}
		fclose($handle);
		//Close cached php file		
   		
		//Insert filename, title, & content into database
		$DB->query("INSERT INTO mkp_blocks (file, title, personal) VALUES ('$filename2', '$titleblock', '3')");
		$DB->close_db();
		Header("Location: index.php?ind=ad_blocks&op=ad_blocks&mode=saved");
		exit;
	}
	
	//PHP blocks: edit save
	function blocks_update_php() {
	global $mkportals, $mklib, $Skin, $DB;
	
		//User input cleanup
		$idblock = intval($mkportals->input['idblock']);
		$titleblock = stripslashes($mkportals->input['titleblock']); //strip slashes added by $_POST
		
		$titleblock = $mklib->convert_savedbadmin($titleblock);
		//$titleblock = $mklib->post_htmlspecialchars($titleblock);
		if (!$titleblock) {
			$titleblock = $mklib->lang['ad_title'];
			//$titleblock = $mklib->post_htmlspecialchars($titleblock);
   		}
		
		//Important! Cannot use stripslashes here or it will strip escape characters in the php code
		$testo = $mklib->convert_viewphpadmin($_POST['ta']);
		$testo = trim($testo);

		//Get cached filename from database
		$query = $DB->query("SELECT file FROM mkp_blocks WHERE id = '$idblock'");
		$row = $DB->fetch_row($query);
		if(!$row) {
            		$message = $mklib->lang['error_404'];
            		$mklib->error_page($message);
            		exit;
		}

		//Open cached php file
		$filename2 = $row['file'];
		if (!$handle = fopen('../'.$filename2, 'wb')) {
         		$message = $mklib->lang['ad_blnofile'];
			$mklib->error_page($message);
			exit;
   		}
		//Write $testo to cached php file
		if (!fwrite($handle,$testo)) {
       		$message = $mklib->lang['ad_blnofile'];
			$mklib->error_page($message);
			exit;
   		}
		fclose($handle);
		//Close cached php file	
   		
		//Update block title in database
		$DB->query("UPDATE mkp_blocks SET title='$titleblock' WHERE id='$idblock'");
		$DB->close_db();
		Header("Location: index.php?ind=ad_blocks&op=blocks_list&mode=saved");
		exit;
	}

	function retr_title($id) {
		global $DB;
		$query = $DB->query("SELECT title FROM mkp_pages WHERE id = '$id'");
		$row = $DB->fetch_row($query);
		return $row['title'];
	}
	
	//Blocks permissions
	function blocks_perms() {
	global $mkportals, $mklib, $Skin, $DB, $mklib_board;
	$idc = intval($mkportals->input['idc']);
	$DB->query("SELECT id, title, perms FROM mkp_blocks WHERE id =  '$idc'");
	$row = $DB->fetch_row();
	$groups = $mklib_board->build_grouplist2();
	$perms = array();
	if ($row['perms']) {
		$perms =  unserialize($row['perms']);
	}
	if(!$perms) {
		$perms = array();
	}
	$content = "
	<tr>
	  <td>
	    <form name=\"main1\" method=\"post\" action=\"index.php?ind=ad_blocks&amp;op=bsave_perms&amp;idc=$idc\">
		<tr>
		<td class=\"titadmin\" style=\"border: 0\">{$mklib->lang['ad_groupsallow']} {$row['title']}</td>
	      </tr>
	      
	";
	$clastr = "tdglobal";
	foreach ($groups as $value) {
   		//echo "id: $value[id] title: $value[title]<br />\n";
		$checkactive = "checked=\"checked\"";
		if (in_array($value[id], $perms)) {
			$checkactive ="";
   		}
		$name = "group".$value[id];
		$content .= "
		<tr class=\"$clastr\">
		<td><br />&nbsp;&nbsp;<input type=\"checkbox\" name=\"$name\" value=\"1\" $checkactive />&nbsp;&nbsp; $value[title]</td>
	      </tr>
		";
		if ($clastr == "tdglobal") {
			$clastr = "modulex";
		 } else {
			$clastr = "tdglobal";
		 }
	}
	$content .= "
		
		<tr>
		<td><br />
		&nbsp;&nbsp;<input type=\"submit\" value=\"{$mklib->lang['ad_save']}\" name=\"B1\" class=\"mkbutton\" /><br />
		<br />
		</td>
	      </tr>
	    </form>
	  </td>
	</tr>
	";
	$output = $Skin->view_block("{$mklib->lang['ad_mperm']}", "$content");
	$mklib->printpage_admin($mklib->lang['ad_titlepage'].$mklib->lang['tt_sep'].$mklib->lang['ad_mblocks'].$mklib->lang['tt_sep'].$mklib->lang['ad_mperm'], $output);

	}
	
	//Block permissions: save
	function bsave_perms() {
		global $mkportals, $mklib, $Skin, $DB, $mklib_board;
		$permissions = array();
		$idblock = intval($mkportals->input['idc']);
		$groups = $mklib_board->build_grouplist2();
		foreach ($groups as $value) {
			$idgroup = $value[id];
			$groupperm = "group".$idgroup;
			$groupperm = $mkportals->input[$groupperm];
			//echo "idblock: $idblock idgroup: $idgroup title: $value[title] permission: $groupperm<br />\n";
			if (empty($groupperm)) {
				$permissions[] = $idgroup;
			}
		}
		$permissions = serialize($permissions);
		$DB->query("UPDATE mkp_blocks SET perms ='$permissions' WHERE id='$idblock'");
		$DB->close_db();
		Header("Location: index.php?ind=ad_blocks&op=blocks_list&mode=saved");
		exit;
	}
	
	//Block titles: edit
	function blocks_titles() {
	global $mkportals, $mklib, $Skin, $DB;
	
	if ($mkportals->input['mode'] == "saved") {
		$checksave = "<div class=\"bghighlight1 success\">{$mklib->lang['ad_saved']}</div>";
   	}
	if ($dir = @opendir("{$mklib->sitepath}mkportal/lang")) {
            $clang = "\n<select class='bgselect' size='1' name='lang' onchange=\"mk_goto(this.form)\">\n";
          
	    while (($dirt = readdir($dir)) !== false) {
                if ($dirt != "." && $dirt != ".." && $dirt != ".htaccess" && $dirt != "htaccess" && $dirt != "English_Reference" && is_dir("{$mklib->sitepath}mkportal/lang/".$dirt)) {
                    $selected = "";
		    $check = $mklib->sitepath."mkportal/lang/$dirt";
		    $check1 = $mklib->mklang;
		    if($mkportals->input['lang']) {
		    	$check1 = $mklib->sitepath."mkportal/lang/".$mkportals->input['lang'];
		    }
		    if($check1 == $check) {
                        $selected = "selected='selected'";
                    }
                    $clang .= "<option value='$dirt' $selected >$dirt</option>\n";
                    $lang_array[strtolower($dirt)] = "1";
                    if (substr(sprintf("%o", @fileperms("../lang/".$dirt)), -1, 3) != "777") {
                        @chmod("../lang/$dirt", 0777);
                    }
                }
            }
            closedir($dir);
            $clang .= "</select>\n";
        }
	
	$langfile = $mklib->mklang;
	if($mkportals->input['lang']) {
		    	$langfile = $mklib->sitepath."mkportal/lang/".$mkportals->input['lang'];
	}
	require "$langfile/lang_blocktitle.php";
	
	$myquery = $DB->query("SELECT id, title, active, personal FROM mkp_blocks ORDER BY active DESC, id");
	$content .= "	
	<tr>
	  <td>
	    <script type=\"text/javascript\">
	    <!--
	    function mk_goto(form) { 
	    var index=form.lang.selectedIndex
	    if (form.lang.options[index].value != \"0\") {
	    location='index.php?ind=ad_blocks&op=blocks_titles&lang='+form.lang.options[index].value;
	    }
	    }
	    //-->
	    </script>
	 $checksave</td>	  
	</tr>		
	<tr>
	  <td>
	    <form name=\"title_blocks\" method=\"post\" action=\"index.php?ind=ad_blocks&amp;op=blocks_title_save\">
	    <table width=\"100%\" class=\"tabmain\">
	    <tr>
		<td class=\"tdblock\" width=\"5%\">{$mklib->lang['ad_id']}</td>
		<td class=\"tdblock\" width=\"50%\">{$mklib->lang['ad_bdeflang']}</td>
		<td class=\"tdblock\" width=\"45%\">{$mklib->lang['ad_bthlang']} $clang</td>
	      </tr> 
	";
	  $clastr = "modulex";
	  while( $row = $DB->fetch_row($myquery) ) {
		//Active block highlighting  
		$active = $row['active'];
		if ($clastr == "tdglobal" || $clastr == "tdglobal bghighlight1") {
			$clastr = $active ? "modulex bghighlight1" : "modulex"; //Active block highlighting
		} else {
			$clastr = $active ? "tdglobal bghighlight1" : "tdglobal"; //Active block highlighting
		}  
		$personal = $row['personal'];
		$indarr = "blt".$row['id'];
		$title_form = $row['id']."_title";
		$title = isset($langmk[$indarr])?$langmk[$indarr]:$row['title'];
		$title = stripslashes($title);
		$content .= "
	      <tr class=\"$clastr\"><td width=\"5%\" align=\"left\">{$row['id']}</td><td width=\"50%\">
		";
		$content .= "
		{$row['title']}
		";
		$content .= "
		</td><td width=\"45%\" align=\"left\">
		";
		$content .= "
		<input type=\"text\" name=\"$title_form\" size=\"40\" value=\"$title\" class=\"bgselect\" />
		";
		$content .= "
		</td>
	      </tr>
		";
	  }
	  $content .= "
	    </table>	    
	    <p align=\"center\"><input type=\"submit\" value=\"{$mklib->lang['ad_save']}\" name=\"B1\" class=\"mkbutton\" /></p><br />
	    </form>
	  </td>
	</tr>	
	";
	
	$output = $Skin->view_block("{$mklib->lang['ad_mblocks']}", "$content");
	$mklib->printpage_admin($mklib->lang['ad_titlepage'].$mklib->lang['tt_sep'].$mklib->lang['ad_mblocks'].$mklib->lang['tt_sep'].$mklib->lang['ad_mbtitles'], $output);

	}
	
	//Block titles: save
	function blocks_title_save() {
		global $mkportals, $mklib, $Skin, $DB, $mklib_board;
		$lang = $mkportals->input['lang'];
		$langfile = $mklib->sitepath."mkportal/lang/".$mkportals->input['lang'];
		$filename = "$langfile/lang_blocktitle.php";
		$query = $DB->query("SELECT id, title FROM mkp_blocks ORDER BY id");
		$out = "<?php\n\n";
		while( $row = $DB->fetch_row($query) ) {
	    		$id = $row['id'];
			$key = "\$langmk['blt".$id."']";
			$value = $id."_title";
			$value =  $mkportals->input[$value];
			$out .= $key." = \"".$value."\";\n"; 
		}
		$out .= "\n\n";
		$out .= "?>";
		if (!$handle = fopen($filename, 'w')) {
         	$message = "{$mklib->lang['ad_blchmod']} $filename .";
			$mklib->error_page($message);
			exit;
   		}
   		if (!fwrite($handle, $out)) {
       		$message = "{$mklib->lang['ad_blchmod']} $filename .";
			$mklib->error_page($message);
			exit;
   		}
		fclose($handle);
		$DB->close_db();
		Header("Location: index.php?ind=ad_blocks&op=blocks_titles&mode=saved&lang=$lang");
		exit;
	
	
	}
	
	
}

?>
