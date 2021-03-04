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
			$plan_ids		=	$_REQUEST['plan_id_'.$j];
			$address		=	$_REQUEST['address_'.$j];
			$key1			=	$_REQUEST['key1_'.$j];
			$description	=	$_REQUEST['description_'.$j];
			$merchant		=	$_REQUEST['merchant_'.$j];
			$success_url1	=	$_REQUEST['success_url_'.$j];
			$cancel_url1	=	$_REQUEST['cancel_url_'.$j];
			$secret			=	$_REQUEST['secret_'.$j];
			$email			=	$_REQUEST['email_'.$j];
			$pubkey			=	$_REQUEST['pubkey_'.$j];
			$prikey			=	$_REQUEST['prikey_'.$j];
			$sql = "update admin_btc_address set address = '$address' , key1 = '$key1', description = '$description'  			,merchant_key='$merchant',success_url='$success_url1',cancel_url='$cancel_url1',secret='$secret'
			,email='$email' ,public_key='$pubkey',private_key='$prikey'
			where id = '$plan_ids' ";
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
	$tbl_id[]		=	$row['id'];
	$address[]		=	$row['address'];
	$key1[]			=	$row['key1'];
	$description[]	=	$row['description'];
	$merchant[]		=	$row['merchant_key'];
	$success_url1[]	=	$row['success_url'];
	$cancel_url1[]	=	$row['cancel_url'];
	$secret[]		=	$row['secret'];
	$email[]		=	$row['email'];
	$pubkey[]		=	$row['public_key'];
	$prikey[]		=	$row['private_key'];
	$pp++;
}

if($up == 1) { print "Updating completed Successfully"; }
?>	
<style>
input{ width:200px;}
</style>
<form name="setting" method="post" action="index.php?page=btc_address_setting">

<table width="100%">
<input type="hidden" name="plan_cnt" value="<?=$pp; ?>"  />
	
	
	<tr>
		<td colspan="3"><input type="submit" name="submit" value="Update" class="btn btn-info"  /></td>
		<td><input type="submit" name="submit" value="Add Address" class="btn btn-info"  /></td>
		<td><input type="submit" name="submit" value="Delete Address" class="btn btn-info"  /></td>
	</tr>
	
	 
 <?php 
	for($pi = 0; $pi < $pp; $pi++)
	{ ?>
	<tr><td colspan="5">&nbsp;</td></tr>
	<tr valign="top">
		<input type="hidden" name="plan_id_<?=$pi; ?>" value="<?=$tbl_id[$pi];?>"/>
		<td><input type="checkbox" name="plans_id[]" value="<?=$tbl_id[$pi];?>"/></td>
		<td>
			<div style="display:table;width:100%;">
				<div style="display:table-row;padding-bottom:10px;">
					<div style="display:table-cell; font:left;width:40%">Address</div>
					<div style="display:table-cell;inline-table; font:left;width:60%">
						<input type="text" name="address_<?=$pi;?>" value="<?=$address[$pi];?>" />
					</div>
				</div>
				<div style="display:table-row;padding-bottom:10px;">
					<div style="display:table-cell; font:left;width:40%">Merchant Key</div>
					<div style="display:table-cell;inline-table; font:left;width:60%">
						<input type="text" name="merchant_<?=$pi;?>" value="<?=$merchant[$pi];?>" />
					</div>
				</div>
				<div style="display:table-row;padding-bottom:10px;">
					<div style="display:table-cell; font:left;width:40%">Public Key</div>
					<div style="display:table-cell;inline-table; font:left;width:60%">
						<input type="text" name="pubkey_<?=$pi;?>" value="<?=$pubkey[$pi];?>" />
					</div>
				</div>
				<div style="display:table-row;padding-bottom:10px;">
					<div style="display:table-cell; font:left;width:40%">Private Key</div>
					<div style="display:table-cell;inline-table; font:left;width:60%">
						<input type="text" name="prikey_<?=$pi;?>" value="<?=$prikey[$pi];?>" />
					</div>
				</div>
			</div>
			<!--General Key --><input type="hidden" name="key1_<?=$pi;?>" value="<?=$key1[$pi];?>" />
			
		</td>
		<!--<td>
			Success Cancel
		</td>-->
			 <input type="hidden" name="success_url_<?=$pi;?>" value="<?=$success_url1[$pi];?>" />
			 <input type="hidden" name="cancel_url_<?=$pi;?>" value="<?=$cancel_url1[$pi];?>" />
		
		<td>
			<div style="display:table;width:100%;">
				<div style="display:table-row;padding-bottom:10px;">
					<div style="display:table-cell; font:left;width:40%">Secret Key</div>
			 		<div style="display:table-cell;inline-table; font:left;width:60%">
						<input type="text" name="secret_<?=$pi;?>" value="<?=$secret[$pi];?>" />
					</div>
				</div>
			</div>
		</td>
		<td>
			<div style="display:table;width:100%;">
				<div style="display:table-row;padding-bottom:10px;">
					<div style="display:table-cell; font:left;width:40%">Email</div>
			 		<div style="display:table-cell;inline-table; font:left;width:60%">
						 <input type="text" name="email_<?=$pi;?>" value="<?=$email[$pi];?>" />
					</div>
				</div>
			</div>
		
		</td>
		<td>
			<div style="display:table;width:100%;">
				<div style="display:table-row;padding-bottom:10px;">
					<div style="display:table-cell; font:left;width:40%">Description</div>
			 		<div style="display:table-cell;inline-table; font:left;width:60%">
						 <input type="text" name="description_<?=$pi;?>" value="<?=$description[$pi];?>" />
					</div>
				</div>
			</div>
		</td>
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
	input [type="radio"] {
		width:10px;
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
