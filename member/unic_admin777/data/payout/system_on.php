<?php
include("../../../config.php");
include("../../../function/functions.php");
include("../../../function/setting.php");
include("../../../data/api.php");
$day = date("l",strtotime($systems_date_time));
if($day == $lottery_result_day){
	query_execute_sqli("update income_process set mode = '0' ");
	?>
	<span class="btn btn-danger">System Currently ON Mode !!</span>
	<?php
}
else{?>
	<span class="btn btn-danger">Today Can't Change System Mode !!</span>
	<?php
}