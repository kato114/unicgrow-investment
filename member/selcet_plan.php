<?php
include "config.php";
include("function/setting.php");
extract($_REQUEST);
if(isset($_REQUEST['plan_id']))
{
$sql = "select * from plan_setting where id='".$_REQUEST['plan_id']."'";
	$query = query_execute_sqli($sql);
	while($row = mysqli_fetch_array($query))
	{
		$amount = $row['amount'];
		$max_amt = $row['shr_limit'];
		$opt = "<option value=''>Select Amount </option>";
		for($j = $amount; $j <= $max_amt; $j=$j+$amount) { 	
			$opt .= "<option value='$j'>$j</option>";
		}
		echo $opt;		
	}
}
elseif(isset($_REQUEST['check_amt']))
{
	$amounts = $_REQUEST['check_amt'];
	$sql = "select * from plan_setting where amount <= '$amounts' and shr_limit >= '$amounts'";
	$query = query_execute_sqli($sql);
	$num = mysqli_num_rows($query);
	if($num > 0)
	{
		while($row = mysqli_fetch_array($query)){
			$id = $row['id'];
			$amountt = $amount[0];
			$max_amount = $max_share_amt[$plan_count-1];
			$msg =  "Minimum Investment &#36; $amountt and Maximum Investment &#36; $max_amount";
			$obj = array('p'=>$id,'m'=> $msg);
			echo json_encode($obj);
		}
	}else
	{
 		$msg =  "Share Limit Exceed";
		$obj = array('p'=>NULL,'m'=> $msg);
		echo json_encode($obj);
	}
}
else
{
	$sql = "select * from plan_setting where id='$val'";
	$query = query_execute_sqli($sql);
	while($row = mysqli_fetch_array($query)){
		$shr_limit = $row['shr_limit'];
		$amount = $row['amount'];
		$max_amount = $shr_limit;
		print "Minimum Investment &#36; $amount and Maximum Investment &#36; $max_amount";
	}
}
?>