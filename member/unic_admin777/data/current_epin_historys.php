
<?php
include('../../security_web_validation.php');
?>
<?php
include("../function/functions.php");
$epin = $_GET['epin'];
?>
<table cellpadding="0" cellspacing="1" width="90%" style="font-style:normal;">
<?php	
$sql = "select * from e_pin as t1 inner join epin_history as t2 on t1.id = t2.epin_id where t2.epin_id = '$epin'";
$query = query_execute_sqli($sql);
$num = mysqli_num_rows($query);
	$arr_gen = array();
	$arr_tra = array();
	$date = array();
	while($row = mysqli_fetch_array($query))
	{
		if(!in_array($row['generate_id'],$arr_gen)){
			$arr_gen[] = $row['generate_id'];
		}
		if(!in_array($row['transfer_to'],$arr_tra)){
			$arr_tra[] = $row['transfer_to'];
		}
		$date1 = $row['date'];
		$date[] = date('d-m-Y', strtotime($date1));
		$used_date = $row['used_date'];
		$used_id = $row['used_id'];
		
		if($used_id == 0){
			$used_date = "<span class='text-danger'>No Date</span>";
		}
	}
	$new_arr = array();
	for($i = 0; $i < count($arr_gen); $i++){
		$new_arr[] = $arr_gen[$i];
	}
	
	for($i = 0; $i < count($arr_tra); $i++){
		$new_arr[] = $arr_tra[$i];
	}
	$new_arr = array_values(array_unique($new_arr, SORT_REGULAR));
	//echo_r($new_arr);
	echo "<tr height='30px'><th>";
		for($i = 0; $i < count($new_arr); $i++){
			if($i == count($new_arr)-1){
				if($new_arr[$i] == 0){
					echo "&nbsp;<span style='color:#000;font-size:12pt;'>Admin &nbsp;&nbsp;</span>";
					$dates = "dates1";
				}
				else{
					echo "&nbsp;&nbsp;<span style='color:#000;font-size:12pt;'>"
					.get_user_name($new_arr[$i])."</span>&nbsp;&nbsp;";
				}
			}
			else{
				if($new_arr[$i] == 0){
					echo "<span style='color:#000;font-size:12pt;'>Admin</span> 
					&nbsp;<img src='images/aero.png'>"."&nbsp;&nbsp;";
					$dates = "dates1";
				}
				else
				{
					echo "&nbsp;<span style='color:#000; font-size:12pt;'>".get_user_name($new_arr[$i])."</span>
					&nbsp;<img src='images/aero.png'>";
				}
				//echo get_user_name($new_arr[$i])."--";
			}
		}
		echo "<img src='images/aero.png'>&nbsp;";
		
		if($used_id == 0 or $used_id == ''){ echo "<span style='color:#FF0000; font-size:12pt;'>Unused</span>"; }
		else{
			echo "<span style='color:#006600; font-size:12pt;' title='Used By'>".get_user_name($used_id)."</span>";
		}
					
	echo "</th></tr>";

	echo"<tr height='30px'><th>";
		for($j = 0; $j < count($date); $j++){
			if($j == count($date)-1){ 
				if($dates == "dates1"){ 
					echo "&nbsp;&nbsp;<span style='color:#000; font-size:10pt;'>".($date[$j])."</span>
					&nbsp;&nbsp;<img src='images/aero1.png'>"; 
				}
				echo "&nbsp;&nbsp;<span style='color:#000; font-size:10pt;'>".($date[$j])."</span>
				&nbsp;&nbsp;<img src='images/aero1.png'>"; 
			}
			else{ 
				echo "<span style='color:#000; font-size:9pt;'>".($date[$j])."</span>
				&nbsp;&nbsp;<img src='images/aero1.png'>";
			}
		}
		echo "&nbsp;&nbsp;<span style='color:#006600; font-size:10pt;'>".date('d-m-Y', strtotime($used_date))."</span>";
	echo "</th></tr>";	
?>				
	</table>

<?php
?>