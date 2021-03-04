<?php
//security functionalty
function set_post_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
function validate_post_input($data) {
	return $data;
}

function validate_all_post_from_input($data){
	global $con;
	if(count($data)> 0)
	{       
	    	foreach($data as $key){
		    $key = mysqli_real_escape_string($con,$key);
		    if($key == 0 or is_numeric($key)){ continue;}
			if(!validate_post_input($key)){
					echo "<b class='text-danger'>Unconditional Values Found !! CLick here for <a href=".$_SERVER['HTTP_REFERER'].">return back</a>. </b>";
				die;
			}
		}
	}
	else{ return true; }
}
?>