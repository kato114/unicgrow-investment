<?php
function paging($table)
{
	$thispage = $PHP_SELF ;
    $num = count($table); // number of items in list
    $per_page = 2; // Number of items to show per page
    $showeachside = 5 //  Number of items to show either side of selected page

    if(empty($start))$start=0;  // Current start position

    $max_pages = ceil($num / $per_page); // Number of pages
    $cur = ceil($start / $per_page)+1; //

	
?>	
	<td width="99" align="center" valign="middle" bgcolor="#EAEAEA"> 
	<?php
	if(($start-$per_page) >= 0)
	{
		$next = $start-$per_page;
	?>
	<a href="<?php print("$thispage".($next>0?("?start=").$next:""));?>"><<</a> 
	<?php
	}
	?>
	</td>
<?php
}