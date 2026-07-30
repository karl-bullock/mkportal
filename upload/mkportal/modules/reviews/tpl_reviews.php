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

class tpl_reviews {

function review($navbar, $maintit, $content, $submit, $stat, $toolbar, $pages, $utonline) {
global $mkportals, $mklib;
$search = "<a href=\"/index.php?ind=reviews&amp;op=search\">[ {$mklib->lang['re_search']} ]</a>";

return <<<EOF
<tr align="center">
  <td>

    <script type="text/javascript">
	  <!--
	    function selChd(jumpsection) {
	    var selIdx = jumpsection.selectedIndex;
		    var newSel = jumpsection.options[selIdx].value;
	    location.href='index.php?ind=reviews&op=section_view&idev=' + newSel;
	}
	    function selChoc(jumpsection) {
	    var selIdx = jumpsection.selectedIndex;
		    var newSel = jumpsection.options[selIdx].value;
	    location.href='index.php?ind=reviews&order=' + newSel;
	}
	    function selChoe(jumpsection, idev) {
	    var selIdx = jumpsection.selectedIndex;
		    var newSel = jumpsection.options[selIdx].value;
	    location.href='index.php?ind=reviews&op=section_view&idev=' + idev + '&order=' + newSel;
	}
	  //-->
    </script>
    
    <br />
    <table width="98%" border="0" cellspacing="0" cellpadding="0">
      <tr>
	<td width="60%" class="mkalign1">
	<img src="$mklib->images/locbar.gif" alt="" />{$navbar}
	</td>
	<td width="40%" class="modulelite mkalign2">
	$submit  $search
	</td>
      </tr>
    </table>
    <br />
    <table width="98%" border="0" cellspacing="1" cellpadding="0" class="moduleborder mkalign1">
      <tr>
	<td>
	  <table width="100%" border="0" cellpadding="0" cellspacing="1" class="modulebg">
            <tr>
              <td width="100%" height="25" class="tdblock"> <img class="mkicon" src="$mklib->images/arrow.gif" alt="" />{$maintit}</td>
            </tr>
            <tr>
              <td>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td>
                      <table width="100%" border="0" cellspacing="1" cellpadding="5">
		      {$content}
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
    {$toolbar}
    <br />
    <table width="98%" border="0" cellspacing="1" cellpadding="0" align="center" class="moduleborder">
      <tr>
	<td>
	  <table width="100%" border="0" cellpadding="0" cellspacing="2" class="modulebg">
	    <tr>
	      <td>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
		    <td>
		      <table width="100%" border="0" cellspacing="1" cellpadding="5">
			<tr>
			  <td class="modulex" colspan="2">
			    {$mklib->lang['re_stat']}
			  </td>
			</tr>
			<tr>
			  <td width="40" align="left" class="modulecell"><img src="$mklib->images/stats.gif" alt="" /></td>
			  <td align="left" class="modulecell">{$stat}</td>
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
    <br />
    <table width="98%" border="0" cellspacing="0" cellpadding="0" align="center">
      <tr>
	<td class="modulebg">
	<div class="mkalign1" style="margin: 4px 0;">{$pages}</div>
	</td>
      </tr>
    </table>

    <table width="98%" class="moduleborder" border="0" cellspacing="1" cellpadding="1" align="center">
      <tr>
	<td class="modulebg">
	  <table width="100%" border="0" cellspacing="0" cellpadding="4" class="mkalign2">
	    <tr>
	      <td class="modulex mkalign1" width="*">
		{$utonline}
	      </td>
	    </tr>
	  </table>
	</td>
      </tr>
    </table>

    <table width="98%" align="center">
      <tr>
	<td align="center">
	<br /><br /><div align="center"><a href="http://www.mkportal.it" target="_blank">MKPReviews</a> &copy;2003-2008 <a href="http://www.mkportal.it" target="_blank">mkportal.it</a></div>
	</td>
      </tr>
    </table>
  </td>
</tr>
EOF;
}
function review_show($navbar, $maintit, $content, $content2, $submit, $stat, $toolbar, $pages, $utonline) {
global $mkportals, $mklib;
$search = "<a href=\"/index.php?ind=reviews&amp;op=search\">[ {$mklib->lang['re_search']} ]</a>";
if($mklib->config['rewrite_url']){
$search = "<a href=\"/reviews/search/\">[ {$mklib->lang['re_search']} ]</a>";	
}
return <<<EOF
<tr align="center">
  <td>

    <script type="text/javascript">
	  <!--
	    function selChd(jumpsection) {
	    var selIdx = jumpsection.selectedIndex;
		    var newSel = jumpsection.options[selIdx].value;
	    location.href='index.php?ind=reviews&op=section_view&idev=' + newSel;
	}
	    function selChoc(jumpsection) {
	    var selIdx = jumpsection.selectedIndex;
		    var newSel = jumpsection.options[selIdx].value;
	    location.href='index.php?ind=reviews&order=' + newSel;
	}
	    function selChoe(jumpsection, idev) {
	    var selIdx = jumpsection.selectedIndex;
		    var newSel = jumpsection.options[selIdx].value;
	    location.href='index.php?ind=reviews&op=section_view&idev=' + idev + '&order=' + newSel;
	}
	  //-->
    </script>
    
    <br />
    <table width="98%" border="0" cellspacing="0" cellpadding="0">
      <tr>
	<td width="60%" class="mkalign1">
	<img src="$mklib->images/locbar.gif" alt="" />{$navbar}
	</td>
	<td width="40%" class="modulelite mkalign2">
	$submit  $search
	</td>
      </tr>
    </table>
    <br />
    <table width="98%" border="0" cellspacing="1" cellpadding="0" class="moduleborder mkalign1">
      <tr>
	<td>
	  <table width="100%" border="0" cellpadding="0" cellspacing="1" class="modulebg">
            <tr>
              <td width="100%" height="25" class="tdblock"> <img class="mkicon" src="$mklib->images/arrow.gif" alt="" />{$maintit}</td>
            </tr>
            <tr>
              <td>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td>
                      <table width="100%" border="0" cellspacing="1" cellpadding="5">
		      {$content}
		     
                      </table>
                    </td>
                  </tr>
                   {$content2}
                </table>
              </td>
	    </tr>
	    
	  </table>
	</td>
      </tr>
    </table>
    {$toolbar}
    <br />
   
    <table width="98%" border="0" cellspacing="0" cellpadding="0" align="center">
      <tr>
	<td class="modulebg">
	<div class="mkalign1" style="margin: 4px 0;">{$pages}</div>
	</td>
      </tr>
    </table>

    <table width="98%" class="moduleborder" border="0" cellspacing="1" cellpadding="1" align="center">
      <tr>
	<td class="modulebg">
	  <table width="100%" border="0" cellspacing="0" cellpadding="4" class="mkalign2">
	    <tr>
	      <td class="modulex mkalign1" width="*">
		{$utonline}
	      </td>
	    </tr>
	  </table>
	</td>
      </tr>
    </table>

    <table width="98%" align="center">
      <tr>
	<td align="center">
	<br /><br /><div align="center"><a href="http://www.mkportal.it" target="_blank">MKPReviews</a> &copy;2003-2008 <a href="http://www.mkportal.it" target="_blank">mkportal.it</a></div>
	</td>
      </tr>
    </table>
  </td>
</tr>
EOF;
}
function cat($navbar, $maintit, $content, $submit, $stat, $toolbar, $pages, $utonline, $content5) {
global $mkportals, $mklib;
$search = "<a href=\"/index.php?ind=reviews&amp;op=search\">[ {$mklib->lang['re_search']} ]</a>";
if($mklib->config['rewrite_url']){
$search = "<a href=\"/reviews/search/\">[ {$mklib->lang['re_search']} ]</a>";	
}
return <<<EOF
<tr align="center">
  <td>

    <script type="text/javascript">
	  <!--
	    function selChd(jumpsection) {
	    var selIdx = jumpsection.selectedIndex;
		    var newSel = jumpsection.options[selIdx].value;
	    location.href='index.php?ind=reviews&op=section_view&idev=' + newSel;
	}
	    function selChoc(jumpsection) {
	    var selIdx = jumpsection.selectedIndex;
		    var newSel = jumpsection.options[selIdx].value;
	    location.href='index.php?ind=reviews&order=' + newSel;
	}
	    function selChoe(jumpsection, idev) {
	    var selIdx = jumpsection.selectedIndex;
		    var newSel = jumpsection.options[selIdx].value;
	    location.href='index.php?ind=reviews&op=section_view&idev=' + idev + '&order=' + newSel;
	}
	  //-->
    </script>
    
    <br />
    <table width="98%" border="0" cellspacing="0" cellpadding="0">
      <tr>
	<td width="60%" class="mkalign1">
	<img src="$mklib->images/locbar.gif" alt="" />{$navbar}
	</td>
	<td width="40%" class="modulelite mkalign2">
	$submit  $search
	</td>
      </tr>
    </table>
    <br />
    <table width="98%" border="0" cellspacing="1" cellpadding="0" class="moduleborder mkalign1">
      <tr>
	<td>
	  <table width="100%" border="0" cellpadding="0" cellspacing="1" class="modulebg">
            <tr>
              <td width="100%" height="25" class="tdblock"> <img class="mkicon" src="$mklib->images/arrow.gif" alt="" />{$mklib->lang['categories']}</td>
            </tr>
            <tr>
              <td>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td>
                      <table width="100%" border="0" cellspacing="1" cellpadding="5">
		      {$content}
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
    <tr>
              <td width="100%" height="25" class="tdblock"> <img class="mkicon" src="$mklib->images/arrow.gif" alt="" />{$maintit}</td>
            </tr>
    <tr>
              <td>
                      <table width="100%" border="0" cellspacing="1" cellpadding="5">
        
                          <tr>
                            <th class="modulex" width="40">&nbsp;</th>
                            <th class="modulex" width="*" align="left">{$mklib->lang['re_name']}</th>
                            <th class="modulex" width="75" align="center">{$mklib->lang['re_votes']}</th>
                            <th class="modulex" width="75" align="center">{$mklib->lang['re_clicks']}</th>
                            <th class="modulex" width="150" align="center">{$mklib->lang['re_insdate']}</th>
                        </tr>
                        $content5
	 </table>
	  
    <table width="98%" border="0" cellspacing="0" cellpadding="0" align="center">
      <tr>
	<td class="modulebg">
	<div class="mkalign1" style="margin: 4px 0;">{$pages}</div>
	</td>
      </tr>
    </table>

    <table width="98%" class="moduleborder" border="0" cellspacing="1" cellpadding="1" align="center">
      <tr>
	<td class="modulebg">
	  <table width="100%" border="0" cellspacing="0" cellpadding="4" class="mkalign2">
	    <tr>
	      <td class="modulex mkalign1" width="*">
		{$utonline}
	      </td>
	    </tr>
	  </table>
	</td>
      </tr>
    </table>

    <table width="98%" align="center">
      <tr>
	<td align="center">
	<br /><br /><div align="center"><a href="http://www.rusmkportal.ru" target="_blank">MKPReviews</a> &copy;2003-2008 <a href="http://www.rusmkportal.ru" target="_blank">www.rusmkportal.ru</a></div>
	</td>
      </tr>
    </table>
  </td>
</tr>
EOF;
}
function row_main_category() {
global $mkportals, $mklib;
return <<<EOF
                       <tr>
                            <th class="modulex" width="40">&nbsp;</th>
                            <th class="modulex" width="*">{$mklib->lang['re_mcat']}</th>
                            <th class="modulex" width="100" align="center">{$mklib->lang['re_ptitle']}</th>

                       </tr>

EOF;
}
function row_main_category_content($name, $description, $totfile, $lastentry, $link) {
global $mkportals, $mklib;
return <<<EOF
			<tr>
                            <td class="modulecell" width="40" align="center">$link</td>
                            <td class="modulecell" width="*">{$name}<br /><span class="modulelite">{$description}</span></td>
			    <td class="modulecell" width="100" align="center">{$totfile}</td>

			</tr>
EOF;
}

function row_main_entries() {
global $mkportals, $mklib;
return <<<EOF
                        <tr>
                            <th class="modulex" width="40">&nbsp;</th>
                            <th class="modulex" width="*">{$mklib->lang['re_name']}</th>
                            <th class="modulex" width="75" align="center">{$mklib->lang['re_votes']}</th>
                            <th class="modulex" width="75" align="center">{$mklib->lang['re_clicks']}</th>
                            <th class="modulex" width="150" align="center">{$mklib->lang['re_insdate']}</th>
                        </tr>
                        
EOF;
}

function row_main_entries_content($name, $trate, $description, $click, $data) {
global $mkportals, $mklib;
return <<<EOF
			<tr>
                            <td class="modulecell" width="40" align="center"><img src="$mklib->images/entry.gif" alt="" /></td>
                            <td class="modulecell" width="*">{$name}<br />{$description}</td>
			    <td class="modulecell" width="75" align="center">{$trate}</td>
                            <td class="modulecell" width="75" align="center">{$click}</td>
                            <td class="modulecell" width="150" align="center">{$data}</td>
			</tr>
EOF;
}


function row_entry($id, $name, $description, $trate, $rate, $width2, $width, $autore, $image, $field1, $field2, $field3, $field4, $field5, $field6, $field7, $review, $rating) {
global $mkportals, $mklib;
return <<<EOF
							{$image}
			<tr>
                            <td class="modulecell mkalign1" width="20%" valign="top">{$mklib->lang['re_title']}</td>
                            <td class="modulecell mkalign1" colspan="2" width="80%"><b>{$name}</b></td>
			</tr>
			<tr>
                            <td class="modulecell mkalign1" width="20%" valign="top">{$mklib->lang['re_description']}</td>
                            <td class="modulecell mkalign1" colspan="2" width="80%">{$description}</td>
			</tr>
			{$field1}
			{$field2}
			{$field3}
			{$field4}
			{$field5}
			{$field6}
			{$field7}
			<tr>
                            <td class="modulecell mkalign1" width="20%" valign="top">{$mklib->lang['re_sendby']}</td>
                            <td class="modulecell mkalign1" colspan="2" width="80%">{$autore}</td>
			</tr>
			<tr>
                            <td colspan="3" class="modulecell mkalign1">{$review}</td>
			</tr>

			<tr>
                            <td class="modulecell mkalign1" width="20%" valign="top">{$mklib->lang['re_score']}</td>
                            <td class="modulecell mkalign1" colspan="2" width="80%">$rating
                            <!-- Put this script tag to the <head> of your page -->

			    </td>
			</tr>



			

EOF;
}

function row_toolbar($jump, $sort) {
global $mkportals, $mklib;
return <<<EOF
<table width="98%" border="0" cellspacing="4" cellpadding="0" align="center">
  <tr>
    <td>
      <table style="padding: 4px 8px 4px 14px; width: 100%" border="0" cellpadding="3" cellspacing="1" class="moduleborder mkalign2">
	<tr>
     	  <td class="modulex mkalign2">
       	    {$jump}
	    &nbsp;
       	    {$sort}
    	  </td>
	</tr>
      </table>
    </td>
  </tr>
</table>
EOF;
}



}
?>
