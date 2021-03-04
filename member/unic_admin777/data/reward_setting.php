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
			$plan_names = $_REQUEST['rwtitle_'.$j];
			$rwleft = $_REQUEST['rwleft_'.$j];
			$rwright = $_REQUEST['rwright_'.$j];
			$id = $j+1;	
			$sql = "update business_reward set title = '$plan_names' , `left` = '$rwleft' ,
			 `right` = '$rwleft' where id = '$plan_ids' ";  	
			query_execute_sqli($sql);
		}
		$rwstart_date = $_REQUEST['sdate'];
		$rwend_date = $_REQUEST['edate'];
		$sql = "update business_reward_date set start_date = '$rwstart_date' , end_date = '$rwend_date' order by id desc limit 1 ";
		query_execute_sqli($sql);
	}
	if($_REQUEST['submit'] == 'Add')
	{
		$sql = "insert into business_reward (title) values ('Enter All Info') ";
		query_execute_sqli($sql);
	}
	 
	if($_POST['submit'] == 'Deactive')
	{	
		$plan_ids = $_REQUEST['plans_id'];
		if($plan_ids == '')
		{
			print "Please Select Any One Plan For Deactive !";
		}
		else
		{
			query_execute_sqli("update business_reward set mode=0 where id = '$plan_ids' ");
		}
	} 
	if($_POST['submit'] == 'Active')
	{	
		$plan_ids = $_REQUEST['plans_id'];
		if($plan_ids == '')
		{
			print "Please Select Any One Plan For Active !";
		}
		else
		{
			query_execute_sqli("update business_reward set mode=1 where id = '$plan_ids' ");
		}
	} 
	echo "<script type=\"text/javascript\">";
	echo "window.location = \"index.php?page=reward_setting\"";
	echo "</script>";		 	 	 	 	 	
}

$query = query_execute_sqli("select * from business_reward_date ");
while($row = mysqli_fetch_array($query))
{
	$rwstart_date = $row['start_date'];
	$rwend_date = $row['end_date'];
}

$q = query_execute_sqli("select * from business_reward order by id asc ");
$plan_count = mysqli_num_rows($q);
$p=0;
while($row = mysqli_fetch_array($q))
{
	$plan_id[$p] = $row['id'];
	$rwtitle[$p] = $row['title'];
	$rwleft[$p] = $row['left'];
	$rwright[$p] = $row['right'];
	$rwmode[$p] = 'Active';
	if($row['mode'] == 0)
		$rwmode[$p] = 'Inactive';	 		
	$p++;
}
$chked = "selected='selected'";
?>	


<table width="100%" border="0">
<form name="setting" method="post" action="index.php?page=reward_setting">
<input type="hidden" name="plan_cnt" value="<?=$plan_count; ?>"  />
	<?php if($p == 1) { ?>
	<tr>
		<td style="color:#FF0000" colspan="5"> Updating completed Successfully</td>
	</tr> <?php
	} ?>
	<tr>
		<th class="text-center">Select For Delete</th>
		<th class="text-center">Reward</th>
		<th class="text-center">Business Matching(&#36;)
		<!--<table width="100%">
			<tr><th>Left</th><th>Right</th></tr>
		</table>-->
		</th>
		<th class="text-center">Status</th>
	</tr>
  	<?php 
  	for($pi = 0; $pi < $plan_count; $pi++)
  	{ ?>
	<tr>
		<td>
			<input type="radio" name="plans_id" value="<?=$plan_id[$pi]; ?>"/>
			<input type="hidden" name="plan_id_<?=$pi; ?>" value="<?=$plan_id[$pi]; ?>"/>
			<input type="hidden" name="plan_type" value="first_plan"/>
		</td>
		<td><input type="text" name="rwtitle_<?=$pi; ?>" value="<?=$rwtitle[$pi]; ?>" class="form-control"/></td>
		<td><input type="text" name="rwleft_<?=$pi; ?>" value="<?=$rwleft[$pi]; ?>" class="form-control" /></td>
		<!--<td><input type="text" name="rwright_<?=$pi; ?>" value="<?=$rwright[$pi]; ?>" class="form-control"/></td>-->
		<td><?=$rwmode[$pi]?></td>
	</tr> <?php 
	} ?>
	<tr><td colspan="4">&nbsp;</td></tr> 
	<tr>
		<td colspan="2">Start Date</td>
		<td colspan="2"><input type="text" name="sdate" value="<?=$rwstart_date?>" class="input-medium flexy_datepicker_input"  /></td>
	</tr>
	<tr>
		<td colspan="2">End Date</td>
		<td colspan="2"><input type="text" name="edate" value="<?=$rwend_date?>" class="input-medium flexy_datepicker_input"  /></td>
	</tr>
	<tr><td colspan="4">&nbsp;</td></tr> 
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Update" class="btn btn-info" /></td>
		<td><input type="submit" name="submit" value="Add" class="btn btn-info"  /></td>
		<td><input type="submit" name="submit" value="Active" class="btn btn-info"  /></td>
		<td><input type="submit" name="submit" value="Deactive" class="btn btn-info"  /></td>
	</tr>
	<tr><td colspan="4">&nbsp;</td></tr>
	
  </form>
</table>

