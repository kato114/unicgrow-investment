<?php
include('../../security_web_validation.php');
include("../function/functions.php");
include("../function/setting.php");
include("../function/send_mail.php");


$newp = $_GET['p'];
$plimit = "20";
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;


if(isset($_SESSION['IMG_UPLOAD']))
{
	print $_SESSION['IMG_UPLOAD'];
	unset( $_SESSION['IMG_UPLOAD']);
}


$qur_set_search = '';
if(count($_GET) == 1){
	unset($_SESSION['SESS_search_date'],$_SESSION['SESS_USERNAME']);
}
else{
	$_POST['Search'] = '1';
	$_POST['search_date'] = $_SESSION['SESS_search_date'];
	$_POST['search_username'] = $_SESSION['SESS_USERNAME'];
}
if(isset($_POST['Search']))
{
	if($_POST['search_date'] != '')
	$_SESSION['SESS_search_date'] = $search_date = date('Y-m-d', strtotime($_POST['search_date']));
	$_SESSION['SESS_USERNAME'] = $search_username = $_POST['search_username'];
	
	$search_id = get_new_user_id($search_username);
	
	if($search_username !=''){
		$qur_set_search = " AND t1.user_id = '$search_id' ";
	}
	if($search_date != ''){
		$qur_set_search = "AND t1.date = '$search_date' ";
	}
}

?>
<form method="post" action="index.php?page=kyc">
<table class="table table-bordered">
	<tr>
		<td><input type="text" name="search_username" placeholder="Search By Username" class="form-control" /></td>
		<td>
			<div class="form-group" id="data_1">
				<div class="input-group date">
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					<input type="text" name="search_date" placeholder="Search By Date" class="form-control" />
				</div>
			</div>
		</td>
		<th><input type="submit" value="Submit" name="Search" class="btn btn-info"></th>
	</tr>
</table>
</form>	

<?php
if(isset($_POST['cancel_kyc']))
{
	$table_id = $_POST['table_id'];
	$user_id = $_POST['user_id'];
	
	query_execute_sqli("UPDATE kyc SET mode = 4 WHERE id = '$table_id'");
			
	echo "<B class='text-success'>KYC Cancel Successfully !!</B>";
	
	$username = get_user_name($user_id);
	$mesgs = "Hi $username, Your KYC Cancelled by Admin. Thanks https://www.unicgrow.com";
	send_sms(get_user_phone($user_id),$mesgs);
	
	$title = "KYC Cancelled ";
	$to = get_user_email($user_id);
	$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from_email, $to, $title, $mesgs);	
}
if(isset($_POST['approve_kyc']))
{
	$table_id = $_POST['table_id'];
	$user_id = $_POST['user_id'];
	
	query_execute_sqli("UPDATE kyc SET mode = 1 WHERE id = '$table_id'");
			
	echo "<B class='text-success'>KYC Approve Successfully !!</B>";
	
	$username = get_user_name($user_id);
	$mesgs = "Hi $username, Your KYC Approved by Admin. Thanks https://www.unicgrow.com";
	send_sms(get_user_phone($user_id),$mesgs);
	
	$title = "KYC Approved ";
	$to = get_user_email($user_id);
	$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from_email, $to, $title, $mesgs);	
}


$sql = "SELECT t1.*,t2.username FROM kyc t1 
LEFT JOIN users t2 ON t1.user_id = t2.id_user
WHERE t1.mode <> 150 $qur_set_search ORDER BY t1.date DESC";

$SQL = "$sql LIMIT $tstart,$tot_p ";

$sqlk = "SELECT COUNT(t1.id) num FROM ($sql) t1";
$query = query_execute_sqli($sqlk);
while($ro = mysqli_fetch_array($query))
{
	$tot_rec = $ro['num'];
	$lpnums = ceil ($tot_rec/$plimit);
}


$query = query_execute_sqli($SQL);
$totalrows = mysqli_num_rows($query);
if($totalrows != 0)
{ ?>
	<table class="table table-bordered">
		<thead>
		<tr>
			<th class="text-center">Sr. No.</th>
			<th class="text-center">User ID</th>
			<th class="text-center">Name</th>
			<th class="text-center">PAN Card</th>
			<th class="text-center">KYC Docs</th>
			<th class="text-center" width="20%">Bank Details</th>
			<th class="text-center">Date</th>
			<th class="text-center">Status</th>
		</tr>
		</thead>
		<?php
		$pnums = ceil($totalrows/$plimit);
		if($newp == ''){ $newp = '1'; }
			
		$start = ($newp-1) * $plimit;
		$starting_no = $start + 1;
		
		$sr_no = $starting_no;
		$rand = 1;
		$rand1 = 21;

		$que = query_execute_sqli("$sql LIMIT $start,$plimit");
		while($row = mysqli_fetch_array($que))
		{ 	
			$id = $row['id'];
			$member_id = $row['user_id'];
			$date = date('d/m/Y' , strtotime($row['date']));
			$id_proof = $row['id_proof'];
			$pan_card = $row['pan_card'];
			$pan_no = $row['pan_no'];
			$username = $row['username'];
			$mode = $row['mode'];
			$aadhar = $row['aadhar_no'];
			$name = ucwords($row['name']);
			$addr_proof = $row['address_proof'];
			$chq_pas_b = $row['chq_passbook'];
			
			$id_frnt = $row['id_proof_front'];
			$id_back = $row['id_proof_back'];
			$photo = $row['photo'];
			$sign = $row['signature'];
			
			$bank_info = "A/C No. = ".$row['bank_ac']."<br>Bank Name = ".$row['bank']." <br>Branch = ".$row['branch'].'<br />IFSC Code = '.$row['ifsc'];
			?>
			<tr class="text-center">
				<td><?=$sr_no?></td>
				<td><?=$username;?></td>
				<td><?=$name?></td>
				<td><?=$pan_no?></td>
				<td>
					<table width="100%">
						<tr><td>Photo</td><td>Signature</td></tr>
						<tr>
							<td><img id="<?=$rand?>" src="../images/mlm_kyc/<?=$photo;?>" width="30" /></td>
							<td><img id="<?=$rand1?>" src="../images/mlm_kyc/<?=$sign;?>" width="30" /></td>
						</tr>
						<tr><td>Front ID Card</td><td>Back ID Card</td></tr>
						<tr>
							<td><img id="<?=$rand?>" src="../images/mlm_kyc/<?=$id_frnt;?>" width="30" /></td>
							<td><img id="<?=$rand1?>" src="../images/mlm_kyc/<?=$id_back;?>" width="30" /></td>
						</tr>
						<tr><td>Pan Card</td><td>Bank Chq</td></tr>
						<tr>
							<td><img id="<?=$rand1?>" src="../images/mlm_kyc/<?=$pan_card;?>" width="30" /></td>
							<td><img id="<?=$rand1?>" src="../images/mlm_kyc/<?=$chq_pas_b;?>" width="30" /></td>
						</tr>
					</table>
				</td>
				<td class="text-left"><?=$bank_info?></td>
				<td><?=$date?></td>
				<td>
					<?php
					if($mode == 0){ ?>
					<form method="post" action="index.php?page=<?=$val?>">
						<input type="hidden" name="table_id" value="<?=$id?>" />
						<input type="hidden" name="user_id" value="<?=$member_id?>" />
						<input type="submit" name="approve_kyc" value="Approve" class="btn btn-info btn-sm" />
						<br /><br />
						<input type="submit" name="cancel_kyc" value="Cancel" class="btn btn-danger btn-sm" />
					</form> <?php
					}
					elseif($mode == 1){ ?> 
						<span class="label label-success">Approved</span><br /><br />
						<form method="post" action="index.php?page=<?=$val?>">
							<input type="hidden" name="table_id" value="<?=$id?>" />
							<input type="hidden" name="user_id" value="<?=$member_id?>" />
							<input type="submit" name="cancel_kyc" value="Cancel" class="btn btn-danger btn-sm" />
						</form> <?php 
					} 
					else{ ?> <span class="label label-danger">Cancelled</span> <?php } ?>
					<!--<form method="post" action="index.php?page=kyc_edit">
						<input type="hidden" name="User_id" value="<?=$member_id?>">
						<input type="submit" name="edit_kyc" value="Edit" class="btn btn-info">
					</form>-->
				</td>
			</tr> <?php
			$sr_no++;
			$rand++;
			$rand1++;
		} ?>
	</table> <?PHP
	pagging_initation_last_five_admin($newp,$lpnums,$show_tab,$val);
}
else{ echo "<B class='text-danger'>There are no information to show!</B>";  }

include("modal.php");
?>

