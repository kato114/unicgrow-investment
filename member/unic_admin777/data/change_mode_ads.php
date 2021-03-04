<?php
include('../../security_web_validation.php');
?>
<?php 
$add_id=$_REQUEST['add_id'];
$mode=$_REQUEST['mode'];
if($mode==0)
{
  $sql_mode="update advertisement set mode=1 where id='$add_id'";
  query_execute_sqli($sql_mode);
        echo "<script type=\"text/javascript\">";
		echo "window.location = \"index.php?page=approved_advertisement\"";
		echo "</script>";
  
}
else
{
  $sql_mode_nn="update advertisement set mode=0 where id='$add_id'";
  query_execute_sqli($sql_mode_nn);
        echo "<script type=\"text/javascript\">";
		echo "window.location = \"index.php?page=approve_advertisement\"";
		echo "</script>";

}



?>