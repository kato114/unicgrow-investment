<?php
include('../../security_web_validation.php');
?>
<?php
session_start();
include("condition.php");
include("../function/setting.php");


if(isset($_POST['submit']))
{
	if($_POST['submit'] == 'Update')
	{
		$plan_cnt = $_REQUEST['plan_cnt'];
		for($j = 0; $j < $plan_cnt; $j++)
		{
			$plan_ids = $_REQUEST['plan_id_'.$j];
			$address = $_REQUEST['address_'.$j];
			$key1 = $_REQUEST['key1_'.$j];
			$description = $_REQUEST['description_'.$j];
			$sql = "update admin_btc_address set address = '$address' , key1 = '$key1', description = '$description'  where id = '$plan_ids' ";
			//, end_amount = '$end_amount' , days = '$dayss' , profit = '$profits' , direct_inc = '$direct_inc'
			query_execute_sqli($sql);
		}
	}
	
	if($_POST['submit'] == 'Add Address')
	{
			query_execute_sqli("insert into admin_btc_address (address) values ('Enter All Info') ");
	} 
	if($_POST['submit'] == 'Delete Address')
	{	
		$plan_ids = $_REQUEST['plans_id'][0];
		if($plan_ids == '')
		{
			print "Please Select Any One Plan For Delete !";
		}
		else
		{
			query_execute_sqli("delete from admin_btc_address where id = '$plan_ids' ");
			query_execute_sqli("ALTER TABLE `admin_btc_address` DROP `id`");
			query_execute_sqli("ALTER TABLE `admin_btc_address` ADD `id` INT NOT NULL AUTO_INCREMENT FIRST ,
			ADD PRIMARY KEY ( id )");
		}
	} 
	
	
	echo "<script type=\"text/javascript\">";
	echo "window.location = \"index.php?page=btc_address_setting\"";
	echo "</script>";
}	

$pp=0;
$q = query_execute_sqli("select * from admin_btc_address order by id desc");
while($row = mysqli_fetch_array($q))
{
	$tbl_id[] = $row['id'];
	$address[] = $row['address'];
	$key1[] = $row['key1'];
	$description[]  = $row['description'];
	$pp++;
}

if($up == 1) { print "Updating completed Successfully"; }
?>	
<style>
input{ width:200px;}
</style>
<form name="setting" method="post" action="index.php?page=btc_address_setting">

<table width="90%" class="in-table">
<input type="hidden" name="plan_cnt" value="<?=$pp; ?>"  />
	
	
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Update" class="btn btn-info"  /></td>
		<td><input type="submit" name="submit" value="Add Address" class="btn btn-info"  /></td>
		<td><input type="submit" name="submit" value="Delete Address" class="btn btn-info"  /></td>
	</tr>
	<tr>
		<th width="5%" class="text-center">&nbsp;</th>
		<th class="text-center">Address</th>
        <th class="text-center">Key</th>
		<th class="text-center">Description</th>
		<!--<th>Income Days</th>
		<th>Direct Income</th>
			
		<th>Amount (<i class="fa fa-bitcoin"></i>)</th>-->
		
	</tr>
	 
 <?php 
	for($pi = 0; $pi < $pp; $pi++)
	{ ?>
	<tr>
		<input type="hidden" name="plan_id_<?=$pi; ?>" value="<?=$tbl_id[$pi];?>"/>
		<td><input type="checkbox" name="plans_id[]" value="<?=$tbl_id[$pi];?>"/></td>
		<td><input type="text" name="address_<?=$pi;?>" value="<?=$address[$pi];?>" /></td>
		<td><input type="text" name="key1_<?=$pi;?>" value="<?=$key1[$pi];?>" /></td>
		<td><input type="text" name="description_<?=$pi;?>" value="<?=$description[$pi];?>" /></td>
	</tr>
<?php 
	} ?>
	
	</table>
</form>


<style>
	input {
		background-color: #eaeaea;
		border: 1px solid #959595;
		color: #333;
		font-family: "Lucida Grande","Lucida Sans Unicode",Arial,Helvetica,Verdana,sans-serif;
		font-size: 12px;
		padding: 5px;	
		width:130px;
	}
	.in-table input {
		background-color: #eaeaea;
		border: 1px solid #959595;
		color: #333;
		font-family: "Lucida Grande","Lucida Sans Unicode",Arial,Helvetica,Verdana,sans-serif;
		font-size: 12px;
		padding: 5px;	
		width:240px;
	}
</style>
