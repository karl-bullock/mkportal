<?php
class tpl_news {


function news_show($navbar, $maintit, $content, $menu, $toolbar, $pages, $utonline) {
global $mkportals, $mklib;
return <<<EOF
<tr align="center">
<td>

<script type="text/javascript">
      <!--
	function selChd(jumpsection) {
         var selIdx = jumpsection.selectedIndex;
		 var newSel = jumpsection.options[selIdx].value;
         location.href='/index.php?ind=news&op=section_view&idev=' + newSel;
    }
	function selChoc(jumpsection) {
         var selIdx = jumpsection.selectedIndex;
		 var newSel = jumpsection.options[selIdx].value;
         location.href='/index.php?ind=news&order=' + newSel;
    }
	function selChoe(jumpsection, idev) {
         var selIdx = jumpsection.selectedIndex;
		 var newSel = jumpsection.options[selIdx].value;
         location.href='/index.php?ind=news&op=section_view&idev=' + idev + '&order=' + newSel;
    }
      //-->
</script>

<br />
<table width="98%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="60%" class="mkalign1">
    <img src="$mklib->images/locbar.gif" alt="" />{$navbar}
    </td>
    $menu
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
<table width="98%" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td class="modulebg">
      <div class="mkalign1" style="margin: 4px 0;">{$pages}</div>
    </td>
  </tr>
</table>
<br />


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
      <br /><br /><div align="center"><a href="http://www.rusmkportal.ru" target="_blank">MKPNews</a> &copy;2008-2010 <a href="http://www.rusmkportal.ru" target="_blank">www.rusmkportal.ru</a></div>
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
  <th class="modulex" width="*" colspan="2">{$mklib->lang['ne_category']}</th>
  <th class="modulex" width="100" align="center">{$mklib->lang['ne_subcat']}</th>
  <th class="modulex" width="100" align="center">{$mklib->lang['ne_novostei']}</th>
  <th class="modulex" width="150" align="center">{$mklib->lang['ne_lastn']}</th>
</tr>
EOF;
}
function row_main_category_content($name, $description, $totfile, $lastentry, $link, $cecksub) {
global $mkportals, $mklib;

return <<<EOF
<tr>
  <td class="modulecell" width="40" align="center">$link</td>
  <td class="modulecell" width="*" colspan="2"><b>{$name}</b><br /><span class="modulelite">{$description}</span></td>
  <td class="modulecell" width="100" align="center">{$cecksub}</td>
  <td class="modulecell" width="100" align="center">{$totfile}</td>
  <td class="modulecell" width="150" align="center">{$lastentry}</td>
</tr>
EOF;
}


function row_main_entries() {
global $mkportals, $mklib;
return <<<EOF
<tr>
<td class="mkalign2" valign="top">

</td>
		  </tr>
EOF;
}

function row_main_entries_content($iden, $name1, $href, $postautor, $click, $data, $short_testo, $totcomments) {
global $mkportals, $mklib;
if($mkportals->member['g_access_cp'] || $mklib->member['g_mod_news']) {
			$content.= "
		    <td class=\"tdblock mkalign2\" width=\"20%\">
		      <div align=\"center\">
		      [<a href=\"/index.php?ind=news&amp;op=edit_file&amp;iden={$iden}\">{$mklib->lang['ne_modify']}</a>&nbsp;|&nbsp;<a href=\"/index.php?ind=news&amp;op=del_file&amp;iden={$iden}\" onclick=\"return makesure2()\">{$mklib->lang['ne_delete']}</a>]
		      </div>
		    </td>
			";
		}
return <<<EOF

		
<table class="tabnews" cellspacing="2" cellpadding="2" width="100%">
		  <tbody>
		  <tr>
<td class="tdblock" valign="top">{$name1}{$pinned}
		    </td>
		    {$content}
		    </tr>
		    <tr><td colspan="2"><br />$short_testo<br /><br /></td></tr>
		    <tr><td class="mkalign2" colspan="2">
		    <br /><i>{$mklib->lang['from']}<b> {$postautor}</b>, $data, $name2
		<img src="{$mklib->images}/read.gif"border="0" alt="{$mklib->lang['n_read']}" title="{$mklib->lang['n_read']}">($click) | <img src="{$mklib->images}/comments.gif" border="0" alt="{$mklib->lang['comments']}" title="{$mklib->lang['comments']}">($totcomments)
		{$href}
		&nbsp;&nbsp;<a href="/index.php?ind=news&amp;op=print_news&amp;ide={$row['id']}">
		<img src="$mklib->images/print.png "border="0" alt="{$mklib->lang['n_print']}" title="{$mklib->lang['n_print']}"></a></i>
		</td>
		  </tr>
		  </tbody>
		

		
EOF;
}


function row_entry($id, $titolo, $testo, $postautor, $cdata, $rating, $hits, $totalcomm) {
global $mkportals, $mklib;
if($mkportals->member['g_access_cp'] || $mklib->member['g_mod_news']) {
		$content.= "
		      <td class=\"tdblock mkalign2\" width=\"20%\">
			<div align=\"center\">
			[<a href=\"/index.php?ind=news&amp;op=edit_file&amp;iden={$id}\">{$mklib->lang['ne_modify']}</a> | <a href=\"/index.php?ind=news&amp;op=del_file&amp;iden={$id}\" onclick=\"return makesure2()\">{$mklib->lang['ne_delete']}</a>]
			</div>
		      </td>
			";
		}
return <<<EOF
<script type="text/javascript">
			function makesure2() {
			if (confirm('{$mklib->lang['ne_delneconfirm']}')) {
			return true;
			} else {
			return false;
			}
			}
			</script>
		<table class="tabnews" cellspacing="2" cellpadding="2" width="100%">
		  <tbody>
		    <tr>
		      <td class="tdblock"><span class="mktxtcontr">$titolo</span>$pinned</td>
		      {$content}
		      </tr>
		<tr><td colspan="2"><br />$testo<br /><br /></td></tr>
		<tr><td class="mkalign2" colspan="2">
		<tr>
					      <td class="mkalign2" colspan="2">
					      <table class="tabnews" width="100%">
							<tr>
		                       <td align="left">$rating</td>
								<td width="623" align="right">
								{$mklib->lang['from']}<b> {$postautor}</b>, $cdata 
								<img src="{$mklib->images}/read.gif" border="0" alt="{$mklib->lang['n_read']}" title="{$this->lang['n_read']}"></a>
								($hits) | <img src="{$mklib->images}/comments.gif" border="0" alt="{$mklib->lang['comments']}" title="{$mklib->lang['comments']}">($totalcomm)
								 </td>
							</tr>
							</table>
					      </td>
					    </tr>
		
		    </td>
		  </tr>
		
		  </tbody>
		  </table>
		
		

EOF;
}
function row_main_coments($content1, $content2, $bbcomnt) {
global $mkportals, $mklib;
return <<<EOF
</td>
	      </tr>
	      <tr>
		<td colspan="3">
		
		  <div class="">
		  <table class="modulecell" width="100%" border="0" cellspacing="0" cellpadding="4">
		    <tr>
		      <td valign="top">
		      {$content1}
		      </td>
		    </tr>
		    <tr>
		      <td id="comments">
			<table class="moduleborder" width="100%" border="0" cellspacing="1" cellpadding="4">
	
			{$content2}
	
			</table>
	$bbcomnt
		      </td>
		    </tr>
		  </table>
		  </div>
		
		</td>
	      </tr>
	   
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
