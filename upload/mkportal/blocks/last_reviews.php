<?php
$content .= "
  <tr>
  <td>
<table class=\"moduleborder\" cellspacing=\"1\" width=\"100%\">
      <tr>
	<th class=\"modulex\" width=\"50%\" style=\"padding-left: 10px;\">Последние 5 статей</th>
	<th class=\"modulex\" width=\"25%\" style=\"padding-left: 10px;\">Автор статьи</th>
	<th class=\"modulex\" width=\"5%\" style=\"padding-left: 10px;\">Просмотров</th>
     <th class=\"modulex\" width=\"15%\" style=\"padding-left: 10px;\">Дата</th>
	 </tr>

"; 
$query = $DB->query("SELECT id, id_cat, title, click, author, date  FROM mkp_reviews ORDER BY `date` DESC LIMIT 0, 10");
while( $row = $DB->fetch_row($query) ) {
	$ide = $row['id'];
    $title = strip_tags($row['title']);
    $title = str_replace( "!" , "!" ,$title );
    $title = str_replace( "&quot;", "\"", $title );
     $dates  = $this->create_date($row['date'], "short");   
		$content .= "
<tr>
<td class=\"modulecell\">
	 <a href=\"$this->siteurl/index.php?ind=reviews&amp;op=entry_view&amp;iden=$ide\" class=\"uno\">$title</a>
</td>
<td class=\"modulecell\">
	 {$row['author']}
</td>
		<td class=\"modulecell\">
	 {$row['click']}
</td>
		</td>
		<td class=\"modulecell\">
	 {$dates}
</td>
		</tr>";
	}
$content .= "
    </table>

  </td>
</tr>

     
";
?>
