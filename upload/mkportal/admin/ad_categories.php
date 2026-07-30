<?php
/*
+--------------------------------------------------------------------------
|   RusMkPortal.ru
|
+--------------------------------------------------------------------------
*/
if (!defined("IN_MKP")) {
    die ("Sorry !! You cannot access this file directly.");
}
$idx = new mk_ad_categories;
class mk_ad_categories {


	function mk_ad_categories() {
		global $mkportals, $mklib, $Skin, $DB, $mklib_board;
		switch($mkportals->input['op']) {
			case 'categories_creat':
    			$this->categories_creat();
    		break;
    		case 'categories_creat_save':
    			$this->categories_creat_save();
    		break;
    		case 'categories_edit':
    			$this->categories_edit();
    		break;
    		case 'categories_edit_save':
    			$this->categories_edit_save();
    		break;
    		case 'categories_edit_craet':
    			$this->categories_edit_craet();
    		break;
    		case 'categories_delete':
    			$this->categories_delete();
    		break;
			default:
    			$this->categories();
    		break;
    		}
	}

	function categories() {
	global $mkportals, $mklib, $Skin, $DB, $mklib_board;
	$content .="<td>
    	<form name=\"main1\" method=\"post\" action=\"index.php?ind=ad_categories&amp;op=categories_creat\">
<table align=\"center\" width=\"80%\" cellspacing=\"2\" cellpadding=\"5\" class=\"tabmain\">
	<tr>
		<td width=\"30%\" height=\"60\" class=\"modulex\">{$mklib->lang['ad_categories_title_module']}</td>
		<td width=\"30%\" class=\"modulex\" align=\"center\">
		  <select class=\"moduleborder\" size=\"1\" name=\"module\">
			  <option value=\"news\">{$mklib->lang['ad_categories_title_module_news']}-news</option>
			<!---  <option value=\"links\">{$mklib->lang['ad_categories_title_module_links']}-links</option> --->
	          <option value=\"reviews\">{$mklib->lang['ad_categories_title_module_reviews']}-reviews</option>
		  </select>
		</td>
	<td  width=\"20%\" colspan=\"2\" class=\"modulex\" align = \"center\"><br />
		  <input type=\"submit\" class=\"mkbutton\" value=\" {$mklib->lang['ad_categories_mod_op']} \" name=\"B1\" />
		</td>
	      </tr>
	</table>
</td>
</tr>
	</form>";
	$content .="<td>
    	<form name=\"editcat\" method=\"post\" action=\"index.php?ind=ad_categories&amp;op=categories_edit\">
<table align=\"center\" width=\"80%\" cellspacing=\"2\" cellpadding=\"5\" class=\"tabmain\">
	<tr>
		<td width=\"30%\" height=\"60\" class=\"modulex\">{$mklib->lang['ad_categories_title_module_edit']}</td>
		<td width=\"30%\" class=\"modulex\" align=\"center\">
		  <select class=\"moduleborder\" size=\"1\" name=\"module\">
			  <option value=\"news\">{$mklib->lang['ad_categories_title_module_news']}-news</option>
			<!---  <option value=\"links\">{$mklib->lang['ad_categories_title_module_links']}-links</option> --->
	          <option value=\"reviews\">{$mklib->lang['ad_categories_title_module_reviews']}-reviews</option>
		  </select>
		</td>
	<td  width=\"20%\" colspan=\"2\" class=\"modulex\" align = \"center\"><br />
		  <input name=\"B3\" type=\"submit\" class=\"mkbutton\" value=\" {$mklib->lang['ad_categories_mod_op']} \" />
		</td>
	      </tr>
	</table>
</td>
</tr>
</form>";
	$content  .= "	      
	<tr>
	  <td>
	
	    <table class=\"moduleborder\" cellspacing=\"1\" cellpadding=\"2\" width=\"98%\" align=\"center\" border=\"0\">
	      <tr>
		<td colspan=\"7\" class=\"titadmin\">{$mklib->lang['ad_categories_edit_categ']}-$modname</td>
	      </tr>
	      <tr>
		<th class=\"modulex mkalign1\" width=\"20%\">{$mklib->lang['ad_categories_title']} {$mklib->lang['ad_categories']}</th>
		<th class=\"modulex mkalign1\" width=\"30%\">{$mklib->lang['ad_description']} {$mklib->lang['ad_categories']}</th>
		<th class=\"modulex mkalign1\" width=\"10%\">{$mklib->lang['ad_categories_img']}</th>
    	<th class=\"modulex mkalign1\" width=\"5%\">{$mklib->lang['ad_categories_edit_mod']}</th>
		<th align=\"center\" class=\"modulex mkalign1\" width=\"10%\">{$mklib->lang['ad_categories_edit']}</th>
		<th align=\"center\" class=\"modulex mkalign1\" width=\"15%\">{$mklib->lang['ad_categories_edit_del']}</th>
	      </tr>	      
	   ";
	$sql = "SELECT * FROM mkp_categories ORDER BY id";
	$result = $DB->query($sql);
		while ($row = $DB->fetch_row($result)) {
			$cid2 = $row[id];
			$title = $row[title];
			$description = $row[description];
			$img = $row[img];
			$modname = $row[module];
			$parentid2 = $row[parentid];
			if ($parentid2!=0) $title=$mklib->getcategor($parentid2,$title,$modname);
			$categor .="<option value=\"$cid2\">$title</option>";
			
			$content .= "
	      <tr>
		<td class=\"modulecell mkalign1\">$title</td>
		<td class=\"modulecell mkalign1\">$description</td>
		<td class=\"modulecell mkalign1\">$img</td>
		<td class=\"modulecell mkalign1\">$modname</td>
		<td align=\"center\" class=\"modulecell mkalign1\"><a class=\"uno\" href=\"index.php?ind=ad_categories&amp;op=categories_edit_craet&amp;idc=$cid2\"><img class=\"mkicon\" src=\"images/icons/famfamfam/silk/page_edit.png\" align=\"center\" title=\"{$this->lang['ad_categories_edit']}\" alt=\"{$this->lang['ad_categories_edit']}\" /></a></td>
		<td align=\"center\" class=\"modulecell mkalign1\" ><a class=\"uno\" href=\"index.php?ind=ad_categories&amp;op=categories_delete&amp;idc=$cid2\"><img class=\"mkicon\" src=\"images/icons/famfamfam/silk/cross.png\" align=\"center\" title=\"{$this->lang['ad_categories_edit_del']}\" alt=\"{$this->lang['ad_categories_edit_del']}\" /></a></td>
	      </tr>
			";
		}

	 $content  .= "
	   
	    </table>
	    <br /><br /><br />

	  </td>
	</tr>
		";

		$output = $Skin->view_block("{$mklib->lang['ad_categories']}", "$content");
		$mklib->printpage_admin($mklib->lang['ad_categories'], $output);

	}

	function categories_creat() {
    	global $mkportals, $mklib, $Skin, $DB, $mklib_board, $MK_TEMPLATE, $MK_PATH;
    	$modname = $mkportals->input['module'];
	$sql = "SELECT id, title, parentid FROM mkp_categories WHERE module='$modname' ORDER BY parentid,title";
	$result = $DB->query($sql);
		while ($row = $DB->fetch_row($result)) {
			$cid2 = $row[id];
			$title = $row[title];
			$parentid2 = $row[parentid];
			if ($parentid2!=0) $title=$mklib->getcategor($parentid2,$title,$modname);
			$categor .="<option value=\"$cid2\">$title</option>";
		
	}
    	$content .= "<tr>
<td>
    	<form name=\"main1\" method=\"post\" action=\"index.php?ind=ad_categories&amp;op=categories_creat_save\">
<table width=\"100%\" cellspacing=\"2\" cellpadding=\"5\" class=\"tabmain\">
	      <tr>
		<td width=\"100%\" colspan=\"2\" class=\"sottotitolo\">{$mklib->lang['ad_categories_new']}</td>
	      </tr>
    	<tr>
		<td width=\"50%\" height=\"60\" class=\"modulex\">{$mklib->lang['ad_categories_title_module']}</td>
	      	<td width=\"50%\" class=\"modulex\" align=\"center\"><input class=\"moduleborder readonly\" type=\"text\" name=\"module\" value=\"$modname\" readonly=\"readonly\" size=\"40\" /></td>
	      </tr>
    	<tr>
		<td width=\"50%\" height=\"60\" class=\"modulex\">{$mklib->lang['ad_categories_title']}</td>
	      	<td width=\"50%\" class=\"modulex\" align=\"center\"><input class=\"moduleborder\" type=\"text\" name=\"title\" size=\"40\" /></td>
	      </tr>
    	<tr>
		<td width=\"50%\" height=\"60\" class=\"modulex\">{$mklib->lang['ad_description']}</td>
	      	<td width=\"50%\" class=\"modulex\" align=\"center\"><input class=\"moduleborder\" type=\"text\" name=\"description\" size=\"40\" /></td>
	      </tr>
    	<tr>
		<td width=\"50%\" height=\"60\" class=\"modulex\">{$mklib->lang['ad_categories_title_catogor']}</td>
		<td width=\"50%\" class=\"modulex\" align=\"center\">
		  <select class=\"moduleborder\" size=\"1\" name=\"categor\">
    	<option value=\"0\">{$mklib->lang['ad_categories_ad_cat_null']}</option>
			  $categor
		  </select>
		</td>
	      </tr>";
    	$content .= "<tr>
		<td width=\"50%\" height=\"60\" class=\"modulex\">{$mklib->lang['ad_categories_img']}</td>
	      	<td width=\"50%\" class=\"modulex\" align=\"center\"><select name=\"imgcat\">
    	<option value=\"\">{$mklib->lang['ad_categories_noimg']}</option>";
    	$dir = opendir("".$MK_PATH."/mkportal/templates/".$MK_TEMPLATE."/images/categories");
	while ($images = readdir($dir)) {
		if (preg_match("/(\.gif|\.png|\.jpg|\.jpeg)$/is", $images) && $images != "." && $images != ".." && $images != "no.png") $cont[] = "<option value=\"$images\">$images</option>";
	}
	closedir($dir);
	asort($cont);
	$content .= implode("", $cont)."</select></div>";
    	$content .= "</td>
	      </tr>";
    	
    	$content .= "</table>
</td>
</tr>";
    	$content .= "
		  <tr>
		<td colspan=\"2\" class=\"titadmin\" align = \"center\"><br />
		  <input type=\"submit\" class=\"mkbutton\" value=\" {$mklib->lang['ad_save']} \" name=\"B1\" />
		</td>
	      </tr>
	  
	    </form>
	 
	";

		$output = $Skin->view_block("{$mklib->lang['ad_categories']}", "$content");
		$mklib->printpage_admin($mklib->lang['ad_categories'], $output);
  	}

function categories_creat_save() {
	global $mkportals, $mklib, $Skin, $DB, $mklib_board;
	$parentid = $mkportals->input['categor'];
    $title = $mkportals->input['title'];
	$description = $mkportals->input['description'];
	$module = $mkportals->input['module'];
	$img = $mkportals->input['imgcat'];
	
	   $DB->query("INSERT INTO mkp_categories (title, description, module, img, parentid) VALUES ('$title', '$description', '$module', '$img', '$parentid')");
		 Header("Location: index.php?ind=ad_categories");

	}
	function categories_edit() {
    	global $mkportals, $mklib, $Skin, $DB, $mklib_board;
    	$modname = $mkportals->input['module'];
    	$content  .= "	      
	<tr>
	  <td>
	
	    <table class=\"moduleborder\" cellspacing=\"1\" cellpadding=\"2\" width=\"98%\" align=\"center\" border=\"0\">
	      <tr>
		<td colspan=\"7\" class=\"titadmin\">{$mklib->lang['ad_categories_edit_categ']}-$modname</td>
	      </tr>
	      <tr>
		<th class=\"modulex mkalign1\" width=\"20%\">{$mklib->lang['ad_categories_title']} {$mklib->lang['ad_categories']}</th>
		<th class=\"modulex mkalign1\" width=\"30%\">{$mklib->lang['ad_description']} {$mklib->lang['ad_categories']}</th>
		<th class=\"modulex mkalign1\" width=\"10%\">{$mklib->lang['ad_categories_img']}</th>
    	<th class=\"modulex mkalign1\" width=\"5%\">{$mklib->lang['ad_categories_edit_mod']}</th>
		<th align=\"center\" class=\"modulex mkalign1\" width=\"10%\">{$mklib->lang['ad_categories_edit']}</th>
		<th align=\"center\" class=\"modulex mkalign1\" width=\"15%\">{$mklib->lang['ad_categories_edit_del']}</th>
	      </tr>	      
	   ";
	$sql = "SELECT * FROM mkp_categories WHERE module='$modname' ORDER BY id";
	$result = $DB->query($sql);
		while ($row = $DB->fetch_row($result)) {
			$cid2 = $row[id];
			$title = $row[title];
			$description = $row[description];
			$img = $row[img];
			$modname = $row[module];
			$parentid2 = $row[parentid];
			if ($parentid2!=0) $title=$mklib->getcategor($parentid2,$title,$modname);
			$categor .="<option value=\"$cid2\">$title</option>";
			
			$content .= "
	      <tr>
		<td class=\"modulecell mkalign1\">$title</td>
		<td class=\"modulecell mkalign1\">$description</td>
		<td class=\"modulecell mkalign1\">$img</td>
		<td class=\"modulecell mkalign1\">$modname</td>
		<td align=\"center\" class=\"modulecell mkalign1\"><a class=\"uno\" href=\"index.php?ind=ad_categories&amp;op=categories_edit_craet&amp;idc=$cid2\"><img class=\"mkicon\" src=\"images/icons/famfamfam/silk/page_edit.png\" align=\"center\" title=\"{$this->lang['ad_categories_edit']}\" alt=\"{$this->lang['ad_categories_edit']}\" /></a></td>
		<td align=\"center\" class=\"modulecell mkalign1\" ><a class=\"uno\" href=\"index.php?ind=ad_categories&amp;op=categories_delete&amp;idc=$cid2\"><img class=\"mkicon\" src=\"images/icons/famfamfam/silk/cross.png\" align=\"center\" title=\"{$this->lang['ad_categories_edit_del']}\" alt=\"{$this->lang['ad_categories_edit_del']}\" /></a></td>
	      </tr>
			";
		}

	 $content  .= "
	   
	    </table>
	    <br /><br /><br />

	  </td>
	</tr>
		";


		$output = $Skin->view_block("{$mklib->lang['ad_categories']}", "$content");
		$mklib->printpage_admin($mklib->lang['ad_categories'], $output);
  	}
  	
function categories_edit_craet() {
    	global $mkportals, $mklib, $Skin, $DB, $mklib_board, $MK_TEMPLATE, $MK_PATH;
    	$cid2 = $mkportals->input['idc'];
  	$sql = "SELECT * FROM mkp_categories WHERE id='$cid2'";
	$result = $DB->query($sql);
		while ($row = $DB->fetch_row($result)) {
			$cid = $row[id];
			$title2 = $row[title];
			//$title = $row[title];
			$description = $row[description];
			$img = $row[img];
			$modname = $row[module];
			$parentid = $row[parentid];
	
			
			  	$sql2 = "SELECT * FROM mkp_categories WHERE module='$modname'";
			  	$result2 = $DB->query($sql2);
		while ($row = $DB->fetch_row($result2)) {
			$cid2 = $row[id];
			$title = $row[title];
			$parentid2 = $row[parentid];
			$title =$mklib->getcategor($parentid2,$title,$modname);
			$categor .="<option value=\"$cid2\">$title</option>";
		
		}
    	$content .= "<tr>
<td>
    	<form name=\"main1\" method=\"post\" action=\"index.php?ind=ad_categories&amp;op=categories_edit_save&amp;idc=$cid\">
<table width=\"100%\" cellspacing=\"2\" cellpadding=\"5\" class=\"tabmain\">
	      <tr>
		<td width=\"100%\" colspan=\"2\" class=\"sottotitolo\">{$mklib->lang['ad_categories_edit']}-{$mklib->lang['ad_categories']}</td>
	      </tr>
    	<tr>
		<td width=\"50%\" height=\"60\" class=\"modulex\">{$mklib->lang['ad_categories_edit_mod']}</td>
	      	<td width=\"50%\" class=\"modulex\" align=\"center\"><input class=\"moduleborder readonly\" type=\"text\" name=\"module\" value=\"$modname\" readonly=\"readonly\" size=\"40\" /></td>
	      </tr>
    	<tr>
		<td width=\"50%\" height=\"60\" class=\"modulex\">{$mklib->lang['ad_categories_title']}</td>
	      	<td width=\"50%\" class=\"modulex\" align=\"center\"><input class=\"moduleborder\" type=\"text\" name=\"title\" value=\"$title2\" size=\"40\" /></td>
	      </tr>
    	<tr>
		<td width=\"50%\" height=\"60\" class=\"modulex\">{$mklib->lang['ad_description']}</td>
	      	<td width=\"50%\" class=\"modulex\" align=\"center\"><input class=\"moduleborder\" type=\"text\" name=\"description\" value=\"$description\" size=\"40\" /></td>
	      </tr>
    	<tr>
		<td width=\"50%\" height=\"60\" class=\"modulex\">{$mklib->lang['ad_categories']}</td>
		<td width=\"50%\" class=\"modulex\" align=\"center\">
		  <select class=\"moduleborder\" size=\"1\" name=\"categor\">
    	<option value=\"$parentid\">$title2</option>
    	<option value=\"0\">{$mklib->lang['ad_categories_ad_cat_null']}</option>
			  $categor
		  </select>
		</td>
	      </tr>";
    	$content .= "<tr>
		<td width=\"50%\" height=\"60\" class=\"modulex\">{$mklib->lang['ad_categories_img']}</td>
	      	<td width=\"50%\" class=\"modulex\" align=\"center\"><select name=\"imgcat\">
    	<option value=\"$img\">$img</option>";
     $dir = opendir("".$MK_PATH."/mkportal/templates/".$MK_TEMPLATE."/images/categories");
	while ($images = readdir($dir)) {
		if (preg_match("/(\.gif|\.png|\.jpg|\.jpeg)$/is", $images) && $images != "." && $images != ".." && $images != "no.png") $cont[] = "<option value=\"$images\">$images</option>";
	}
	closedir($dir);
	asort($cont);
	$content .= implode("", $cont)."</select></div>";
    	$content .= "</td>
	      </tr>";
    	
    	$content .= "</table>
</td>
</tr>";
    	$content .= "
		  <tr>
		<td colspan=\"2\" class=\"titadmin\" align = \"center\"><br />
		  <input type=\"submit\" class=\"mkbutton\" value=\" {$mklib->lang['ad_save']} \" name=\"B1\" />
		</td>
	      </tr>
	  
	    </form>
	 
	";
		}
    	$output = $Skin->view_block("{$mklib->lang['ad_categories']}", "$content");
		$mklib->printpage_admin($mklib->lang['ad_categories'], $output);
    	
}
function categories_edit_save() {
	global $mkportals, $DB, $mklib;
	$id = $mkportals->input['idc'];
	$parentid = $mkportals->input['categor'];
    $title = $mkportals->input['title'];
	$description = $mkportals->input['description'];
	$module = $mkportals->input['module'];
	$img = $mkportals->input['imgcat'];
	$DB->query("UPDATE mkp_categories SET title = '$title', description = '$description', img = '$img', parentid = '$parentid' WHERE id = '$id'");
	$DB->close_db();
	Header("Location: index.php?ind=ad_categories");
		exit;
}
function categories_delete() {
	global $mkportals, $DB, $mklib;
	$id = $mkportals->input['idc'];
    $DB->query("DELETE FROM mkp_categories WHERE id = $id");
    $DB->close_db();
    Header("Location: index.php?ind=ad_categories");
		exit;
}
}

?>
