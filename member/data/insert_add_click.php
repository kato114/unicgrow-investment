<?php
include('../security_web_validation.php');
?>
<?php
        $add_id=$_REQUEST['add_id'];
		$uid=$_SESSION['mlmproject_user_id'];
		$remote_addr=$_SERVER['REMOTE_ADDR'];
		$date=date('Y-m-d');
		$time=date("H:i:s");
		
		
		$sql_click="insert into add_clicks set
		                              add_id='$add_id',
									  date='$date',
									  time='$time',
									  user_id='$uid',
									  ip='$remote_addr'";
									  query_execute_sqli($sql_click); 
		
		
		

        echo "<script type=\"text/javascript\">";
		echo "window.location = \"index.php?page=my_clicks\"";
		echo "</script>";

?>