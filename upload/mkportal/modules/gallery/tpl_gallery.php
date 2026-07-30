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

class tpl_gallery {


function gallery_show($navbar, $maintit, $content, $submit, $stat, $toolbar, $pages, $utonline) {
global $mkportals, $mklib;
return <<<EOF

<tr>
  <td>
<script type="text/javascript">
      <!--
	function selChd(jumpsection) {
         var selIdx = jumpsection.selectedIndex;
		 var newSel = jumpsection.options[selIdx].value;
         location.href='index.php?ind=gallery&op=section_view&idev=' + newSel;
    }
	function selChoc(jumpsection) {
         var selIdx = jumpsection.selectedIndex;
		 var newSel = jumpsection.options[selIdx].value;
         location.href='index.php?ind=gallery&order=' + newSel;
    }
	function selChoe(jumpsection, idev) {
         var selIdx = jumpsection.selectedIndex;
		 var newSel = jumpsection.options[selIdx].value;
         location.href='/index.php?ind=gallery&op=section_view&idev=' + idev + '&order=' + newSel;
    }
      //-->
</script>
  <br />
    <table width="98%" border="0" cellspacing="0" cellpadding="0" align="center">
      <tr>
	<td width="60%" class="mkalign1">
	<img src="$mklib->images/locbar.gif" alt="" />{$navbar}
	</td>
	<td width="40%" class="modulelite mkalign2">
	$submit
	</td>
      </tr>
    </table>
    <br />
    <table width="98%" border="0" cellspacing="1" cellpadding="0" align="center" class="moduleborder">
      <tr>
	<td>
	  <table width="100%" border="0" cellpadding="0" cellspacing="2" class="modulebg">
            <tr>
              <td width="100%" height="25" class="tdblock"> <img class="mkicon" src="$mklib->images/arrow.gif" alt="" />{$maintit}</td>
	    </tr>
            <tr>
              <td>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td>
                      <table width="100%" border="0" cellspacing="1" cellpadding="5" class="mkalign1">
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
                          <td class="modulex mkalign1" colspan="2">
                          {$mklib->lang['gat_stats']}
                          </td>
                        </tr>
                        <tr>
                          <td width="40" align="center" class="modulecell"><img src="$mklib->images/stats.gif" alt="" /></td>
                          <td class="modulecell mkalign1">{$stat}</td>
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
      <td align="center"><br /><br />
      <div align="center"><a href="http://www.mkportal.it" target="_blank">MKPGallery</a> &copy;2003-2008 <a href="http://www.mkportal.it" target="_blank">mkportal.it</a></div>
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

<!-- START explaincatparent START -->
                        <tr>
                          <th class="modulex" width="80">{$mklib->lang['gat_prev']}</th>
                          <th class="modulex mkalign1" width="*" colspan="2">{$mklib->lang['ga_category']}</th>
			  <th class="modulex" width="100" align="center">{$mklib->lang['gat_subcat']}</th>
                          <th class="modulex" width="100" align="center">{$mklib->lang['gat_images']}</th>
                          <th class="modulex mkalign1" width="100">{$mklib->lang['gat_lentry']}</th>
                        </tr>
EOF;
}
function row_main_category_content($name, $image, $description, $totfile, $lastentry, $checksub) {
global $mkportals;
return <<<EOF
			<tr>
                          <td class="modulecell" width="80" align="center">{$image}</td>
                          <td class="modulecell mkalign1" width="*" colspan="2"><b>{$name}</b><br /><span class="modulelite">{$description}</span></td>
			  <td class="modulecell" width="100" align="center">{$checksub}</td>
			  <td class="modulecell" width="100" align="center">{$totfile}</td>
                          <td class="modulecell mkalign1" width="150">{$lastentry}</td>
			</tr>
EOF;
}

function row_entry($id, $name, $description, $click, $trate, $rate, $width2, $width, $autore, $peso, $dimensioni, $cdata, $rating) {
global $mkportals, $mklib;

if($mklib->member['g_send_comments'] || $mkportals->member['g_access_cp']) {
  //  $comment_pic = "<a href=\"/index.php?ind=gallery&amp;op=submit_comment&amp;ide={$id}\"><img src=\"$mklib->images/comment.gif\" border=\"0\" alt=\"\" /></a>";
    $comment_text = "<a href=\"/index.php?ind=gallery&amp;op=submit_comment&amp;ide={$id}\">{$mklib->lang['ga_insertcom']}</a>";
}
else {
    $comment_pic = "";
    $comment_text = "";
}

return <<<EOF

			<tr>
                          <td class="modulecell mkalign1" width="20%" valign="top">{$mklib->lang['gat_name']}</td>
                          <td class="modulecell mkalign1" width="80%"><b>{$name}</b></td>
			</tr>
			<tr>
                          <td class="modulecell mkalign1" width="20%" valign="top">{$mklib->lang['ga_des']}</td>
                          <td class="modulecell mkalign1" width="80%">{$description}</td>
			</tr>
			<tr>
                          <td class="modulecell mkalign1" width="20%" valign="top">{$mklib->lang['gat_sendby']}</td>
                          <td class="modulecell mkalign1" width="80%">{$autore}</td>
			</tr>
			<tr>
                          <td class="modulecell mkalign1" width="20%" valign="top">{$mklib->lang['gat_sdate']}</td>
                          <td class="modulecell mkalign1" width="80%">{$cdata}</td>
			</tr>
			<tr>
                          <td class="modulecell mkalign1" width="20%" valign="top">{$mklib->lang['gat_wei']}</td>
                          <td class="modulecell mkalign1" width="80%">{$peso}</td>
			</tr>
			<tr>
                          <td class="modulecell mkalign1" width="20%" valign="top">{$mklib->lang['gat_dim']}</td>
                          <td class="modulecell mkalign1" width="80%">{$dimensioni}</td>
			</tr>
			<tr>
                            <td class="modulecell mkalign1" width="20%" valign="top">{$mklib->lang['gat_clicks']}</td>
                            <td class="modulecell mkalign1" width="80%">{$click}</td>
			</tr>

			<tr>
                          <td class="modulecell mkalign1" width="20%" valign="top">{$mklib->lang['gat_score']}</td>
                          <td class="modulecell mkalign1" colspan="2" width="80%">$rating</td>
			</tr>

			<tr>
			  <td class="modulecell" colspan="2">
                            <table width="50%" align="center">
                              <tr>
				<td align="center" class="functions" width="200"><a href="/index.php?ind=gallery&amp;op=submit_postcard&amp;ide={$id}"><img src="$mklib->images/ecards.gif" border="0" alt="" /></a></td>
				         </tr>
                              <tr>
				<td align="center" class="functions" width="200"><a href="/index.php?ind=gallery&amp;op=submit_postcard&amp;ide={$id}">{$mklib->lang['gat_pcard']}</a></td>
				         </tr>
                            </table>
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
      <table style="padding: 4px 8px 4px 14px; width: 100%" "border="0" cellpadding="3" cellspacing="1" class="moduleborder mkalign2">
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
