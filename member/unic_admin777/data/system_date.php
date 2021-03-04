<?php
include('../../security_web_validation.php');

session_start();
include("condition.php");
if(isset($_SESSION['sys_date'])){
	echo $_SESSION['sys_date'];
	unset($_SESSION['sys_date']);
}

if(isset($_POST['submit']))
{
	$system_date = $_REQUEST['system_date'];
	if($system_date == ''){
		echo "<B class='text-danger'> Please Enter System Date !!</span>";
	}
	else{
		$ex_date = explode(" ",$system_date);
		$system_date1 = $ex_date[0];
		query_execute_sqli("update system_date set sys_time = '$system_date',sys_date='$system_date1' where id = 1 ");
		
		$_SESSION['sys_date'] = "<B class='text-success'> System Date Changed Successfully !!</B>";
		?><script>window.location="index.php?page=system_date";</script><?php
	}	
}

else
{ 
	$q = query_execute_sqli("select * from system_date where id = 1 ");
	while($row = mysqli_fetch_array($q))
	{
		$current_d = $row['sys_date'];
		$current_time = $row['sys_time'];
	}
?>

<link rel="stylesheet" type="text/css" media="screen" href="assets/datetime/sbootstrap-datetimepicker.min.css">
<form name="my_form" action="index.php?page=system_date" method="post" >
<table class="table table-bordered">
	<thead><tr><th>System Date : </th><th colspan="2"><?=$current_time; ?></th></tr></thead>
	<tr>
		<th>Enter System Date:</th>
		<td>
			<div id="datetimepicker" class="input-append date">
				<input type="text" name="system_date" value="<?=$current_time;?>" />
				<span class="add-on"><i data-time-icon="icon-time" data-date-icon="icon-calendar"></i></span>
			</div>
		</td>
		<td><input type="submit" name="submit" value="Submit" class="btn btn-info" /></td>
	</tr>
</table>
</form>
<?php  }  ?>

<script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script> 
<script type="text/javascript" src="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.2.2/js/bootstrap.min.js">
    </script>
<script type="text/javascript" src="http://tarruda.github.com/bootstrap-datetimepicker/assets/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="http://tarruda.github.com/bootstrap-datetimepicker/assets/js/bootstrap-datetimepicker.pt-BR.js"></script>
<script type="text/javascript">
  $('#datetimepicker').datetimepicker({
	format: 'yyyy-MM-dd hh:mm:ss',
	language: 'en'
  });
</script>
