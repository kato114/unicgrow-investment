<?php
ini_set("display_errors",'on');

session_start();
$login_id = $_SESSION['mlmproject_user_id'];
if(isset($_POST["sponsor_username"]))
{
	require_once("config.php");
	$sponsor_username =  $_POST["sponsor_username"]; 
	$results = query_execute_sqli("SELECT * FROM users WHERE username = '$sponsor_username'");
	$username_exist = mysqli_num_rows($results); //total records
	if($username_exist) 
	{
		while($rrrrr = mysqli_fetch_array($results))
		{
			$name = ucfirst($rrrrr['f_name'])." ".ucfirst($rrrrr['l_name']); 
			$_SESSION['sponsor_email_id'] = $name;	
		}
		die("<B style='color:#008000;'>$name</B>");
	}
	else{ die("<B style='color:red;'>Incorrect Sponsor Id !</B>"); }
}

if(isset($_POST["search_username"]))
{
	require_once("config.php");
	$search_username =  $_POST["search_username"]; 
	$results = query_execute_sqli("SELECT * FROM users WHERE username = '$search_username'");
	$username_exist = mysqli_num_rows($results); //total records
	if($username_exist) 
	{
		//$qu = query_execute_sqli(" SELECT `get_chield_by_parent`($login_id) AS `get_chield_by_parent`;");
		//$resultt = explode(",",mysqli_fetch_array($qu)[0]);
		
		while($rrrrr = mysqli_fetch_array($results))
		{
			$name = ucfirst($rrrrr['f_name'])." ".ucfirst($rrrrr['l_name']); 
			$request_user_id = $rrrrr['id_user'];
		}
		$resultt[0] = $login_id;
		$resultt[1] = $request_user_id;
		if(in_array($request_user_id,$resultt)){
			die("<B style='color:#008000;'>$name</B>");
		}
		else{
			die("<B style='color:red;'>Requested Member Have Not In Your Network List</B>");
		}
	}
	else{ die("<B style='color:red;'>Incorrect Requested Id !</B>"); }
}
if(isset($_POST["search_username_plan"]))
{
	require_once("config.php");
	$search_username =  $_POST["search_username_plan"]; 
	$results = query_execute_sqli("SELECT * FROM users WHERE username = '$search_username'");
	$username_exist = mysqli_num_rows($results); //total records
	if($username_exist) 
	{
		while($rrrrr = mysqli_fetch_array($results))
		{
			$request_user_id = $rrrrr['id_user']; 
		}
		//$qu = query_execute_sqli(" SELECT `get_chield_by_parent`($login_id) AS `get_chield_by_parent`;");
		//$resultt = explode(",",mysqli_fetch_array($qu)[0]);
		$resultt[] = $login_id;
		$resultt[] = $request_user_id;
		if(in_array($request_user_id,$resultt)){
			?>
			<option value="">Select Plan</option>
			<?php
			$chk_sqk = "SELECT invest_type FROM reg_fees_structure WHERE user_id='$request_user_id' order by id desc limit 1";
			$sql = "SELECT *,(
					CASE WHEN EXISTS($chk_sqk)
					  THEN ($chk_sqk)
					  ELSE 0
					END 
					)AS invest_type from plan_setting having id>=invest_type";
			$query = query_execute_sqli($sql);
			$k = 0;
			while($r = mysqli_fetch_array($query))
			{
				$amount = $r['amount'];
				$chk = "";
				if($k==0)$chk = "checked=\"checked\""; ?>
				<option value="<?=$r['id']?>"><?=$r['plan_name']?>&nbsp;($<?=$amount;?>)</option>
				<?php
				$k++;
			} ?>
			
			<?php
		}
	}
	
}
if(isset($_POST["change_username"]))
{
	require_once("config.php");
	$username =  $_POST["change_username"]; 
	$results = query_execute_sqli("SELECT * FROM users WHERE username = '$username'");
	$username_exist = mysqli_num_rows($results); //total records
	if($username_exist) 
	{ die("<B style='color:red;'>Username Already Exist !</B>"); }
	else{ die("<B style='color:green;'>OK</B>"); }
}
?>