<?php
include('../../security_web_validation.php');
?>
<?php
session_start();
include("condition.php");
include("../function/functions.php");

$newp = $_GET['p'];
$plimit = "15";
?>
<div style="width:90%; text-align:right;height:70px;">

<div style="width:100%; text-align:right;  height:70px;">
<form action="index.php?page=full_detail" method="post"><font style="color:#002953; font-style:normal;">User Id : </font>
<input type="text" name="search_username"  />
<input type="submit" name="Search" value="Search" class="btn btn-info" />
</form>
</div>
</div>

<?php
$qur_set_search = '';
if((isset($_POST['Search'])))
{
	if(!isset($newp))
	{
		$search_username = $_POST['search_username'];
		$search_id = get_new_user_id($search_username);
		if($search_id == 0)
			print "<div style=\"width:80%; text-align:right; color:#FF0000; font-style:normal; font-size:14px; height:50px; padding-right:100px;\">Enter Correct User Id !</div>";
		else
		{
			$sql = "SELECT * FROM users where username = '$search_username' ";
		}	
	}
	else
	{	
		$sql = '';
	}		
}
elseif(isset($_REQUEST['gen'] ))
{
	$id = $_SESSION['id'];
	$result = query_execute_sqli("select username as Username,f_name as First_Name ,l_name as Last_Name ,real_parent as Sponsor ,real_parent as Sponsor_Name , email as Email ,phone_no as Phone from users where id_user = '$id' ");
	$num = mysqli_num_rows($result);
	if($num > 0)
	{
		if(!mkdir("mlm_user excel files/full_detail", 0700)) {}
		else{}
		
		$file_name = time()."users".date('Y-m-d');
		//define separator (defines columns in excel & tabs in word)
		$sep = "\t"; //tabbed character
		$fp = fopen('mlm_user excel files/full_detail/'.$file_name.'.xls', "w");
		$schema_insert = "";
		$schema_insert_rows = "";
		//start of printing column names as names of MySQL fields
		
		
		//start of adding column names as names of MySQL fields
		for ($i = 0; $i < mysqli_num_fields($result); $i++)
		{
		$schema_insert_rows.=mysqli_field_name($result,$i) . "\t";
		}
		$schema_insert_rows.="\n";
		//echo $schema_insert_rows;
		fwrite($fp, $schema_insert_rows);
		//end of adding column names
		
		
		//start while loop to get data
		while($row = mysqli_fetch_row($result))
		{
		//set_time_limit(60); //
			$schema_insert = "";
			for($j=0; $j<mysqli_num_fields($result);$j++)
			{
				if($j == 3)
				{
					$schema_insert .= get_user_name("$row[$j]").$sep;
				}
				elseif($j == 4)
				{
					$schema_insert .= get_full_name("$row[$j]").$sep;
				}
				else
				{	
					if(!isset($row[$j]))
					$schema_insert .= "NULL".$sep;
					elseif ($row[$j] != "")
					$schema_insert .= strip_tags("$row[$j]").$sep;
					else
					$schema_insert .= "".$sep;
				}	
			}
			$schema_insert = str_replace($sep."$", "", $schema_insert);
			
			//this corrects output in excel when table fields contain \n or \r
			//these two characters are now replaced with a space
			
			$schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
			$schema_insert .= "\n";
			//$schema_insert = (trim($schema_insert));
			//print $schema_insert .= "\n";
			//print "\n";
			
		
			fwrite($fp, $schema_insert);
		}
		fclose($fp);
		
		print "Excel File Created Successfully !!<br><br><br>";
?>		
		<p><a style="color:#333368; font-weight:600;" href="index.php?page=show_daily_income">Back</a></p>
	click here for download file = <a href="mlm_user excel files/<?php print $file_name;?>.xls"><?php print $file_name; ?></a>	
<?php		
		unset($_SESSION['id']);
	}	
	else
	{
		print "<p style='color:red'>There is No users to write !</p><br>";
	}
}
else
{
	$sql = '';
	unset($_SESSION['session_search_username']);
}

$query = query_execute_sqli($sql);
$totalrows = mysqli_num_rows($query);
if($totalrows > 0)
{
	print "
	
		<table align=\"center\" hspace = 0 cellspacing=0 cellpadding=0 border=0 height=\"40\" width=100%>
		<tr>
			<th colspan=8 class=\"message tip\" height=40 align=right>
				<B style=\"font-size:20px\">
					To Create Excel File <a href=\"index.php?page=full_detail&gen=yes\">Click Here</a>
				</B>
			</th>
		</tr>
		<tr>
		<th class=\"message tip\"><strong>Sr. No.</strong></th>
		<th class=\"message tip\"><strong>User Name</strong></th>
		
		<th class=\"message tip\"><strong>Name</strong></th>
		<th class=\"message tip\"><strong>Sponsor</strong></th>
		<th class=\"message tip\"><strong>Sponsor Name</strong></th>
		<th class=\"message tip\"><strong>E-mail</strong></th>
		<th class=\"message tip\"><strong>Bitcoin A/c</strong></th>
		<th class=\"message tip\"><strong>Phone No.</strong></th>
		</tr>";
		
		while($row = mysqli_fetch_array($query))
		{
			$cnt++;
			$id = $_SESSION['id'] = $row['id_user'];
			$password = $row['password'];
			$username = get_user_name($id);
			$email = $row['email'];
			$phone_no = $row['phone_no'];
			$liberty_email = $row['liberty_email'];
			$alert_email = $row['alert_email'];
			$parent_id = $row['real_parent'];
			$name = $row['f_name']." ".$row['l_name'];
			$beneficiery_name = $row['beneficiery_name'];
			$ac_no = $row['ac_no'];
			$bank = $row['bank'];
			$bank_code = $row['bank_code'];
			
			print "<tr>
			<td class=\"input-medium\" style=\"padding-left:5px; width:120px; color:$col;\">$cnt</td>
			<td class=\"input-medium\" style=\"padding-left:5px; width:90px; color:$col;\"> $username</td>
			
			<td class=\"input-medium\" style=\"padding-left:5px; width:70px; color:$col;\"><small>$name</small></td>
			<td class=\"input-medium\" style=\"padding-left:5px; width:100px; color:$col;\"><small>". $real_parent = get_user_name($parent_id)."</small></td>
			<td class=\"input-medium\" style=\"padding-left:5px; width:100px; color:$col;\"><small>". $real_parent = get_full_name($parent_id)."</small></td>
			<td class=\"input-medium\" style=\"padding-left:5px; width:100px; color:$col;\"><small>$email</small></td>
			<td class=\"input-medium\" style=\"padding-left:10px\">$ac_no</td>
			<td class=\"input-medium\" style=\"padding-left:5px; width:70px; color:$col;\"><small>$phone_no</small></td>
			
			</tr>";
				
		}
		print "</table>";
}
else
{
	print "<p style='color:red'>Please Enter Username</p>";
}		

?>
