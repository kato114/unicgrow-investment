<?php
include('../../security_web_validation.php');
include("../function/functions.php");

$file_name = $_REQUEST['bonus_name'];
$inc_type = $_REQUEST['inc_type'];
$url = $_REQUEST['url'];

$file_name = $file_name.date('Y-m-d').time();
$sep = "\t";
$fp = fopen('mlm_user excel files/'.$file_name.'.xls', "w");
$insert = ""; 
$insert_rows = ""; 

/*$SQL = "SELECT t1.*,t2.username,t2.f_name,t2.l_name FROM income t1 
LEFT JOIN users t2 ON t1.user_id = t2.id_user
WHERE t1.type = '$inc_type' ORDER BY t1.date DESC";*/	
$SQL = $_SESSION['search_result'];
$result = query_execute_sqli($SQL);              

$insert_rows.="User Id \t Name \t Amount \t Date ";
$insert_rows.="\n";
fwrite($fp, $insert_rows);
while($row = mysqli_fetch_array($result)){
	
	$insert = "";
	$username = $row['username'];
	$name = ucwords($row['f_name']." ".$row['l_name']);
	$amount = $row['amount'];
	$date = date('d/m/Y' , strtotime($row['date']));
	$income_id = $row['incomed_id'];
	$by_id = get_user_name($income_id);
	
	$insert .= $username.$sep;
	$insert .= $name.$sep;
	$insert .= $amount.$sep;
	$insert .= $date.$sep;
	
	$insert = str_replace($sep."$", "", $insert);
	
	$insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $insert);
	$insert .= "\n";
	fwrite($fp, $insert);
}
fclose($fp);
unset($_SESSION['search_result']);
?>
<p><a class="btn btn-danger" href="index.php?page=<?=$url?>"><i class="fa fa-reply"></i> Back</a></p>
<div class="alert alert-success alert-dismissable">
	<button aria-hidden="true" data-dismiss="alert" class="close" type="button">&times;</button>
	<B>Excel File Created Successfully !</B>
</div>

Click here for download file <i class="fa fa-hand-o-right"></i>  <a href="mlm_user excel files/<?=$file_name;?>.xls"><?=$file_name; ?></a>
