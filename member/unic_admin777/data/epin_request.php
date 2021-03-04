<?php
include('../../security_web_validation.php');

session_start();
include("condition.php");
include("../function/setting.php");
include("../function/functions.php");
include("../function/send_mail.php");


$newp = $_GET['p'];
$plimit = 25;
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;


$qur_set_search = '';

if(count($_GET) > 1){
	$_POST['Search'] = '1';
	$_POST['search_username'] = $_SESSION['SESS_search_username'];
	$_POST['date'] = $_SESSION['SESS_search_date'];
	$_POST['search_status'] = $_SESSION['SESS_search_status'];
	$_POST['serch_trno'] = $_SESSION['SESS_serch_trno'];
}
else{
	unset($_SESSION['SESS_search_username'],$_SESSION['SESS_search_date'],$_SESSION['SESS_search_status'],$_SESSION['SESS_serch_trno']);
}

if(isset($_POST['Search']))
{
	if($_POST['date'] != '')
	$_SESSION['SESS_search_date'] = $date = date('Y-m-d', strtotime($_POST['date']));
	$_SESSION['SESS_search_username'] = $search_username = $_POST['search_username'];
	$_SESSION['SESS_search_status'] = $search_status = $_POST['search_status'];
	$_SESSION['SESS_serch_trno'] = $serch_trno = $_POST['serch_trno'];
	
	$search_id = get_new_user_id($search_username);
	
	if($_POST['date'] !=''){
		$qur_set_search = " WHERE date = '$date' ";
	}
	if($_POST['search_username'] !=''){
		$qur_set_search = " WHERE user_id = '$search_id' ";
	}
	if($_POST['search_status'] != ''){
		$qur_set_search = " WHERE mode = '$search_status' ";
	}
	if($_POST['serch_trno'] != ''){
		$qur_set_search = " WHERE transaction_no = '$serch_trno' ";
	}
}

?>
<form method="post" action="index.php?page=epin_request">
<table class="table table-bordered">
	<tr>
		<td>
			<select name="search_status" class="form-control">
				<option value="">Select Status</option>
				<option value="0" <?php if(isset($_POST['search_status']) and $_POST['search_status'] == 0){?> selected="selected" <?php } ?>>
					Approved
				</option>
				<option value="1" <?php if($_POST['search_status'] == 1){?> selected="selected" <?php } ?>>
					Pending
				</option>
				<option value="4" <?php if($_POST['search_status'] == 4){?> selected="selected" <?php } ?>>
					Cancelled
				</option>
			</select>
		</td>
		<td><input type="text" name="serch_trno" placeholder="Search By Transaction No." class="form-control" /></td>
		<td><input type="text" name="search_username" placeholder="Search By Username" class="form-control" /></td>
		<td>
			<div class="form-group" id="data_1">
				<div class="input-group date">
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					<input type="text" name="date" placeholder="Search By Date" class="form-control" />
				</div>
			</div>
		</td>
		<th><input type="submit" value="Submit" name="Search" class="btn btn-info"></th>
	</tr>
</table>
</form>	

<?php
if(isset($_POST['submit']))
{
	$table_id = $_POST['table_id'];
	$adm_remarks = $_POST['adm_remarks'];
	
	if($_POST['submit'] == 'Accept')
	{
		$query = query_execute_sqli("select * from epin_request where id = '$table_id' ");			
		while($row = mysqli_fetch_array($query))
		{
			$new_user_id = $row['user_id'];
			$amount = $row['epin_amount']; 	
			$epin_type = $row['epin_type']; 
			$epin_no = $row['epin_no']; 
			$plan = $row['plan']; 	
		}
			
		for($p = 1; $p <= $epin_no; $p++ )
		{
			do
			{
				$unique_epin = mt_rand(1000000000, 9999999999);
				$query = query_execute_sqli("select * from e_pin where epin = '$unique_epin' ");
				$num = mysqli_num_rows($query);
			}while($num > 0);
			
			$mode = 1;
			$date = date('Y-m-d');
			$t = date('h:i:s');
			
			$sql = "INSERT INTO e_pin (epin , epin_type , user_id , amount , mode , time , date ,plan) 
			VALUES ('$unique_epin' , '$epin_type' , '$new_user_id' ,'$amount' , '$mode' , '$t' , '$systems_date' , '$plan')";
			query_execute_sqli($sql);
		}	
		
		query_execute_sqli("UPDATE epin_request SET mode = 0 , app_date = '$systems_date' , admin_remarks = '$adm_remarks' WHERE id = '$table_id' ");			
		if($soft_chk == "LIVE"):
			$epin_generate_username = "rapidforx2";
			$epin_amount = $fees;
			$payee_epin_username = $mew_user;
			$epin .= $unique_epin."<br>";
			$title = "E-pin mail";
			$to = get_user_email($new_user_id);
			$from = 0;
			
			$db_msg = $epin_generate_message;
			include("../function/full_message.php");
				
			$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from, $to, $title, $full_message);
			//$SMTPChat = $SMTPMail->SendMail();
		endif;
		?> 
		<script>alert("E-pin generated Successfully !"); window.location="index.php?page=epin_request";</script> <?php
	}
	elseif($_POST['submit'] == 'Cancel'){
		query_execute_sqli("UPDATE epin_request SET mode = 4 , app_date = '$systems_date' , admin_remarks = '$adm_remarks' WHERE id = '$table_id' ");		
		?>
		<script>alert("E-pin Cancelled Successfully !"); window.location="index.php?page=epin_request";</script> 
		<?php
	}
}

echo $sql = "SELECT * FROM epin_request $qur_set_search ORDER BY mode ASC";
$SQL = "$sql LIMIT $tstart,$tot_p ";
$query = query_execute_sqli($SQL);
$totalrows = mysqli_num_rows($query);

$sqlk = "SELECT COUNT(t1.id) num FROM ($sql) t1";
$query = query_execute_sqli($sqlk);
while($ro = mysqli_fetch_array($query))
{
	$tot_rec = $ro['num'];
	$lpnums = ceil ($tot_rec/$plimit);
}

if($totalrows != 0)
{ ?>
	<table class="table table-bordered">
		<thead>
		<tr>
			<th class="text-center">Sr. No.</th>
			<th class="text-center">User Id</th>
			<th class="text-center">Package/Amount</th>
			<th class="text-center">No. Of E-pin </th>
			<th class="text-center">Transation No.</th>
			<th class="text-center">Chq No.</th>
			<th class="text-center">Receipt</th>
			<th class="text-center">Remarks</th>
			<th class="text-center">Date</th>
			<th class="text-center">Admin Remarks</th>
			<th class="text-center">Action</th>
		</tr> 
		</thead>
		<?php
		$pnums = ceil($totalrows/$plimit);
		if ($newp==''){ $newp='1'; }
			
		$start = ($newp-1) * $plimit;
		$starting_no = $start + 1;
		$sr_no = $starting_no;
		$rand1 = 21;
		
		$query = query_execute_sqli("$sql LIMIT $start,$plimit ");			
		while($row = mysqli_fetch_array($query))
		{
			$tbl_id = $row['id'];
			$user_id = get_user_name($row['user_id']);
			$amount = $row['epin_amount']; 	
			$date = $row['date'];
			$mode = $row['mode'];
			$pin_no = $row['epin_no'];
			$tr_no = $row['transaction_no'];
			$receipt = $row['receipt'];
			$remarks = $row['remarks'];
			$chq_no = $row['chq_no'];
			$payment_mode = strtoupper($row['payment_mode']);
			$admin_remarks = $row['admin_remarks'];
			
			$package = my_package($row['user_id'])[0];
			
			$img = 'No Image';
			if($receipt != ''){ $img="<img id='$rand1' src='../images/mlm_epin_receipt/$receipt' width='50' />"; }
			
			if($tr_no == '' or $tr_no == 0) $tr_no = 'XXXXXX';
			if($chq_no == '') $chq_no = 'XXXXXX';
			
			switch($mode)
			{
				case '0' : 
				$sts_btn = "<span class='label label-success'>Approved</span>"; 
				$adm_remark = $admin_remarks;
				break;
				
				case '4' : 
				$sts_btn = "<span class='label label-warning'>Cancelled</span>"; 
				$adm_remark = $admin_remarks;
				break;
				
				case '1' : 
				$sts_btn = "<p><input type='submit' name='submit' value='Accept' class='btn btn-primary btn-xs' onclick='javascript:return confirm(&quot; Are You Sure to Accept E-pin request &quot;);' /></p>
					<input type='submit' name='submit' value='Cancel' class='btn btn-danger btn-xs' onclick='javascript:return confirm(&quot; Are You Sure to Cancel E-pin request &quot;);' />"; 
				$adm_remark = "<textarea name='adm_remarks' class='form-control'></textarea>";
				break;
			}
			?>
			<form action="index.php?page=epin_request" method="post">
			<input type="hidden" name="table_id" value="<?=$tbl_id?>" />
			<tr class="text-center">
				<td><?=$sr_no?></td>
				<td><?=$user_id?></td>
				<td class="text-left">
					Package - <?=$package?><br />	Amount - <?=$amount?> &#36;<br />
					Payment Mode - <?=$payment_mode?>
				</td>
				<td><?=$pin_no?></td>
				<td><?=$tr_no?></td>
				<td><?=$chq_no?></td>
				<td><?=$img?></td>
				
				<td><div style="word-wrap: break-word; width:100px"><?=$remarks?></div></td>
				<td><?=$date?></td>
				<td><div style="word-wrap: break-word; width:100px"><?=$adm_remark?></div></td>
				<td><?=$sts_btn?>
					<!--<p><input type="submit" name="submit" value="Accept" class="btn btn-primary btn-xs" onclick="javascript:return confirm(&quot; Are You Sure to Accept E-pin request &quot;);" /></p>
					<input type="submit" name="submit" value="Cancel" class="btn btn-danger btn-xs" onclick="javascript:return confirm(&quot; Are You Sure to Cancel E-pin request &quot;);" />-->
				</td>
			</tr>
			</form> <?php
			$sr_no++;
		} ?>
	</table> <?php
	pagging_initation_last_five_admin($newp,$lpnums,$show_tab,$val);
}
else{ echo "<B class='text-danger'>There are no E-pin to show !!</B>"; }	

include("modal.php");
?>
