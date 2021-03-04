<?php
include('../security_web_validation.php');
?>
<?php
session_start();
include("condition.php");
include("function/all_child.php");


$id = $_SESSION['mlmproject_user_id'];

$childrens = give_all_children($id);

$r_b_member = findDuplicates($childrens[1], "C");
$l_b_member = findDuplicates($childrens[0], "C");

$total_left = count($childrens[0]);
if($total_left == 1 and $childrens[0][0] == 0) $total_left = 0;
$total_right = count($childrens[1]);
if($total_right == 1 and $childrens[1][0] == 0) $total_right = 0;
$total_left_act = $total_left-$l_b_member;
$total_right_act = $total_right-$r_b_member;

if(isset($_POST['Submit']))
{
	$start_date = $_POST['start_date'];
	$end_date = $_POST['end_date'];
}
elseif(isset($_REQUEST['gen'] ))
{
	//if(!mkdir("full_detail", 0700)) {}
		
	$file_name = "Member_list".time().'_'.date('Y-m-d');
	$sep = "\t"; //tabbed character
	$fp = fopen('Member_list/'.$file_name.'.xls', "w");
	$schema_insert = "";
	$schema_insert_rows = "";
	
	$schema_insert_rows.= "Username Id\t";
	$schema_insert_rows.= "Name\t";
	$schema_insert_rows.= "Phone No\t";
	$schema_insert_rows.= "Email\t";
	$schema_insert_rows.= "Status\t";
	$schema_insert_rows.= "Top-Up\t";
	$schema_insert_rows.= "Date\t";
	
	$schema_insert_rows.= "Username Id\t";
	$schema_insert_rows.= "Name\t";
	$schema_insert_rows.= "Phone No\t";
	$schema_insert_rows.= "Email\t";
	$schema_insert_rows.= "Status\t";
	$schema_insert_rows.= "Top-Up\t";
	$schema_insert_rows.= "Date\t";

	$schema_insert_rows.="\n";
	//echo $schema_insert_rows;
	fwrite($fp, $schema_insert_rows);
		
	$i = 0;
	while($childrens[0][$i][0] != '' or $childrens[1][$i][0] != '')
	{
		$schema_insert = "";
		$left_detail = get_user_info($childrens[0][$i][0]);
		$right_detail = get_user_info($childrens[1][$i][0]);
		
		$left_topup_info = get_user_topup($childrens[0][$i][0]);
		$right_topup_info = get_user_topup($childrens[1][$i][0]);
		
		if( $left_topup_info[0] > 0)
			$left_status = "Paid";
		else
			$left_status = " ";
			
		if($right_topup_info[0] > 0)
			$right_status = "Paid";
		else
			$right_status = " ";				
		
		$left_username = $left_detail[0]; 
		$left_name = $left_detail[3]; 
		$left_phone = $left_detail[1];  
		$left_email = $left_detail[2]; 	
		$left_status = $left_status; 
		$left_top_up = $left_topup_info[0]; 
		$left_top_date = $left_topup_info[1]; 
		
		$right_username = $right_detail[0]; 
		$right_name = $right_detail[3]; 
		$right_phone = $right_detail[1]; 
		$right_email = $right_detail[2];  
		$right_status = $right_status;  
		$right_top_up = $right_topup_info[0];
		$right_top_date = $right_topup_info[1]; 
		
		$schema_insert .= strtoupper(($left_username).$sep);
		$schema_insert .= strtoupper(($left_name).$sep);
		$schema_insert .= strtoupper(($left_phone).$sep);
		$schema_insert .= strtoupper(($left_email).$sep);
		$schema_insert .= strtoupper(($left_status).$sep);
		$schema_insert .= strtoupper(($left_top_up).$sep);
		$schema_insert .= strtoupper(($left_top_date).$sep);
		
		$schema_insert .= strtoupper(($right_username).$sep);
		$schema_insert .= strtoupper(($right_name).$sep);
		$schema_insert .= strtoupper(($right_phone).$sep);
		$schema_insert .= strtoupper(($right_email).$sep);
		$schema_insert .= strtoupper(($right_status).$sep);
		$schema_insert .= strtoupper(($right_top_up).$sep);
		$schema_insert .= strtoupper(($right_top_date).$sep);

		$schema_insert = (str_replace($sep."$", "", $schema_insert));
			
		$schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
		$schema_insert .= "\n";
	
		fwrite($fp, $schema_insert);

		$i++;
	}	print "Excel File Created Successfully !!<br>";
?>		
		<p><a style="color:#333368; font-weight:600;" href="index.php?page=network-member-list">Back</a></p>
		click here for download file = <a href="Member_list/<?=$file_name;?>.xls"><?=$file_name; ?></a>	
<?php	
	fclose($fp);		
}	
else
{
	$start_date = date('Y-m-d');
	$end_date = date('Y-m-d');
}

	$title = 'Display';
	$message = 'Network Members List';
	data_logs($id,$title,$message,0);

$left_total_inv = total_business_left_right($childrens, $total_left ,0,$start_date , $end_date);
$right_total_inv = total_business_left_right($childrens, $total_right ,1,$start_date , $end_date);

if($total_left != '0' or $total_right != '0')
{ ?>
	<form method="post" action="">
	<table class="table table-bordered table-hover">
		<tr>
			<th colspan="5">
				<?php
				if(!isset($_REQUEST['gen'])){ ?>
					<a href="index.php?page=network-member-list&gen=yes"><B>Click Here For Export</B></a> <?php
				}
				?>
			</th>
		</tr>
		<tr>
			<th>Start Date</th>
			<td>
				<input type="text" name="start_date" placeholder="Start Date" id="datepicker1" class="form-control" />
			</td>
		
			<th>End Date</th>
			<td>
				<input type="text" name="end_date" placeholder="End Date" id="datepicker2" class="form-control" />
			</td>
			<td><input type="submit" value="Submit" name="Submit" class="btn btn-primary" /></td>
		</tr>
	</table>
	</form>	
	<table class="table table-bordered table-hover">
	<thead>
	<!--<tr>
		<th colspan=2>Total Left</th>
		<th colspan=5><?=$left_total_inv;?></th>
		<th colspan=4 class="text-right">Total Right</th>
		<th colspan=3><?=$right_total_inv;?></th>
	</tr>-->
	
	<tr>
		<th class="text-center">User Name</th>
		<th class="text-center">Name</th>
		<th class="text-center">Phone No</th>
		<th class="text-center">Email</th>
		<th class="text-center">Status</th>
		<th class="text-center">Top Up</th>
		<th class="text-center">Date</th>
		
		<!--<th class="text-center">User Name</th>
		<th class="text-center">Name</th>
		<th class="text-center">Phone No</th>
		<th class="text-center">Email</th>
		<th class="text-center">Status</th>
		<th class="text-center">Top Up</th>
		<th class="text-center">Date</th>-->
	</tr>
	</thead>
	<?php	
	$i = 0;
	$s_no = 1;
	while($childrens[0][$i][0] != '' or $childrens[1][$i][0] != '')
	{
		//$type[0] = get_type_user($childrens[0][$i][0]);
		//$type[1] = get_type_user($childrens[1][$i][0]);
		$left_topup_info = get_user_topup($childrens[0][$i][0]);
		$right_topup_info = get_user_topup($childrens[1][$i][0]);
		
		$left_detail = get_user_info($childrens[0][$i][0]);
		$right_detail = get_user_info($childrens[1][$i][0]);
		
		if( $left_topup_info[0] > 0)
			$left_status = "Paid";
		else
			$left_status = "Unpaid";
			
		if($right_topup_info[0] > 0)
			$right_status = "Paid";
		else
			$right_status = "Unpaid";				
		
		if($s_no%2==0)
		{
			$class = "odd";
		}
		else
		{
			$class = "even";
		}
	?>	
		<tr>
			<td class="text-center"><?=$left_detail[0];?></td>
			<td class="text-center"><?=$left_detail[3];?></td>
			<td class="text-center"><?=$left_detail[1];?></td>
			<td class="text-center"><?=$left_detail[2];?></td>
			<td class="text-center"><?=$left_status;?></td>
			<td class="text-center"><?=$left_topup_info[0];?></td>
			<td class="text-center"><?=$left_topup_info[1];?></td>
			<!--<td class="text-center"><?=$right_detail[0];?></td>
			<td class="text-center"><?=$right_detail[3];?></td>
			<td class="text-center"><?=$right_detail[1];?></td>
			<td class="text-center"><?=$right_detail[2];?></td>
			<td class="text-center"><?=$right_status?></td>
			<td class="text-center"><?=$right_topup_info[0];?></td>
			<td class="text-center"><?=$right_topup_info[1];?></td>-->
		</tr>
	<?php
		$i++;
		$s_no++;
	}	
	?></table>
<?php }
else { echo "<B class='text-danger'>You have no child !</B>"; }	
	
function findDuplicates($data,$dupval) { 
$nb= 0;
foreach($data as $key => $val)
if ($val[1]==$dupval) $nb++;
return $nb;
}
	
function get_user_topup($id)	
{
	$sql = "SELECT * FROM (SELECT * FROM reg_fees_structure ORDER BY id DESC ) AS inv WHERE user_id ='$id' GROUP BY user_id ";
	$q = query_execute_sqli($sql);
	while($row = mysqli_fetch_array($q))
	{
		$user_id  = $row['user_id'];
		$topup_info[0] = $row['update_fees'];
		$topup_info[1] = $row['date'];
	}
	if($user_id > 0)
	{
		return $topup_info;
	}
	else
	{
		$topup_info[0] = '';
		$topup_info[1] = '';
		return $topup_info = '';
	}
}

function total_business_left_right($user_id , $cnt , $pos,$start_date , $end_date)
{
	$total_business = 0;
	$pos = $pos;
	$date = date('Y-m-d');
	for($i = 0; $i < $cnt; $i++)
	{
		$id = $user_id[$pos][$i][0];
		$sql = "select sum(update_fees) from reg_fees_structure where user_id = '$id'  ";
		$query = query_execute_sqli($sql);
		while($row = mysqli_fetch_array($query))
		{
			$total_business = $total_business+$row[0];
		}
	}
	return $total_business;	
}

function get_user_info($id)
{
	$query = query_execute_sqli("SELECT * FROM users WHERE id_user = '$id' ");
	while($row = mysqli_fetch_array($query))
	{
		$detail[0] = $row['username'];
		$detail[1] = $row['phone_no'];
		$detail[2] = $row['email'];
		$detail[3] = $row['f_name'].' '.$row['l_name'];
		
		return $detail;
	}	
}	
?>
