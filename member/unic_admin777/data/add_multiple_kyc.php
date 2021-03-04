<?php
include('../../security_web_validation.php');
?>
<?php
session_start();
include("condition.php");
require_once "../function/formvalidator.php";
include("../function/setting.php");
include("../function/functions.php");
$admin_id = $_SESSION['intrade_admin_id'];

$newp = $_GET['p'];
$plimit = 100;
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;

if(isset($_POST['add_member']) and $_POST['add_member'] != ""){
	extract($_POST);
	$by_user_id = $user_id;
	if(($user_id = get_new_user_id($user_name)) > 0){
		$sql = "select * from kycm 
		where user_id='$user_id' and name ='$benf_name' and bank_ac ='$ac_no' and bank ='$bank' and 
		branch='$branch' and ifsc='$ifsc_code' and city='$city'";
		$query = query_execute_sqli($sql);
		$num = mysqli_num_rows($query);
		if($num == 0){
			$sql = "insert into kycm set user_id='$user_id',name ='$benf_name',bank_ac ='$ac_no',bank ='$bank',
			branch='$branch',ifsc='$ifsc_code',city='$city',pan_no='$pan_no',
			by_user_id='$by_user_id',date='$systems_date_time'";
			query_execute_sqli($sql);
		}
		mysqli_free_result($query);
		unset($_POST['add_member']);
		$_POST['add_member'] = "";
		$_POST['submit'] = 'Submit';
		$_POST['user_name'] = $user_name;
	}
	else{
		echo "<B class='text-danger'>Please Enter Right Kyc Member! </B>";
	}
}
if(isset($_POST['edit_member']) and $_POST['edit_member'] != ""){
	extract($_POST);
	$by_user_id = $user_id;
	if(($user_id = get_new_user_id($user_name)) > 0){
		$sql = "update kycm set user_id='$user_id',name ='$benf_name',bank_ac ='$ac_no',bank ='$bank',
		branch='$branch',ifsc='$ifsc_code',city='$city',pan_no='$pan_no',
		by_user_id='$by_user_id',date='$systems_date_time' where id='$e_id'";
		query_execute_sqli($sql);
		mysqli_free_result($query);
		unset($_POST['edit_member']);
		$_POST['edit_member'] = "";
		$_POST['submit'] = 'Submit';
		$_POST['user_name'] = $user_name;
	}
	else{
		echo "<B class='text-danger'>Please Enter Right Kyc Member! </B>";
	}
}
if(isset($_POST['delete_kyc']) and $_POST['delete_kyc'] != ""){
	extract($_POST);
	$by_user_id = $user_id;
	if(($user_id = get_new_user_id($user_name)) > 0){
		$sql = "delete from kycm where user_id='$user_id' and id ='$kycm_id'";
		$query = query_execute_sqli($sql);
		unset($_POST['delete_kyc']);
		$_POST['delete_kyc'] = "";
		$_POST['submit'] = 'Submit';
		$_POST['user_name'] = $user_name;
	}
	else{
		echo "<B class='text-danger'>Please Enter Right Kyc Member! </B>";
	}
}
if(isset($_SESSION['edit_userid'])){
	$_POST['submit'] = 'Submit';
	$_POST['user_name'] = $_SESSION['edit_userid'];
	unset($_SESSION['edit_userid']);
}
if(isset($_POST['submit']))
{
	if($_POST['submit'] == 'Submit' or $_POST['submit'] == 'View')
	{
		$u_name = $_POST['user_name'];
		$sql = "select t1.id_user,t2.*,t3.username,t2.id kycm_id from users t1
		left join kycm t2 ON t1.id_user = t2.by_user_id
		left join users t3 on t3.id_user = t2.user_id
		inner join kyc t4 on t1.id_user = t4.user_id and t4.mode_pan=1 and t4.mode_id=1 and t4.mode_photo=1 and t4.mode_chq=1
		where t1.username = '$u_name' ";
		$query = query_execute_sqli($sql);
		$num = mysqli_num_rows($query);
		if($num == 0){ echo "<B class='text-danger'>Please Enter Right KYC Approved Member ! </B>"; }
		else
		{
			$i = 0;
			?>
			<table class="table table-bordered">
			<thead>
			<tr>
				<th colspan="9">
					<button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModal5"
			data-id="<?=$id_user?>" >Add Member</button>
					<span id="ke_member" style="display:none;"><?=$u_name?></th>
				</th>
			</tr>
			</thead>
			<?php
			while($row = mysqli_fetch_array($query))
			{
				$id_user = $row['id_user'];
				$kycm_id = $row['kycm_id'];
				$user_id = $row['user_id'];
				$username = $row['username'];
				$name = $row['name'];
				$date = $row['date'];
				$bank_ac = $row['bank_ac'];
				$bank = $row['bank'];
				$branch =  $row['branch'];
				$ifsc = $row['ifsc'];
				$city = $row['city'];
				$pan_no = $row['pan_no'];
				if($user_id > 0){
					if($i == 0){ ?>
					<thead>
					<tr>
						<th colspan="4">KYC Member</th><th colspan="5"><?=$u_name?></th>
					</tr>
					<tr>
						<th>S.No.</th>
						<th>A/c Holder Name</th>
						<th>A/c Number</th>
						<th>Bank Name</th>
						<th>Branch</th>
						<th>IFSC Code</th>
						<th>PAN NO.</th>
						<th>City</th>
						<th>&nbsp;</th>
					</tr>
					</thead>
					<?php
					}
					?>
					<tr>
						<th><?=$i+1;?></th>
						<th><?=$name?></th>
						<th><?=$bank_ac?></th>
						<th><?=$bank?></th>
						<th><?=$branch?></th>
						<th><?=$ifsc?></th>
						<th><?=$pan_no?></th>
						<th><?=$city?></th>
						<th>
							<div class="col-md-12">
							<div class="col-md-6">
							<button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModal2"
			data-id="<?=$kycm_id?>" data-name="<?=$name?>" data-ac="<?=$bank_ac?>" data-bank="<?=$bank?>" data-branch="<?=$branch?>" data-ifsc="<?=$ifsc?>" data-pan="<?=$pan_no?>" data-city="<?=$city?>" >Edit</button>
							
							</div>
							<div class="col-md-6">
							<form action="index.php?page=<?=$val?>" method="post">
								<input type="hidden" name="user_name" value="<?=$username?>" />
								<input type="hidden" name="kycm_id" value="<?=$kycm_id?>" />
								<input type="submit" name="delete_kyc" value="Delete" class="btn btn-danger" />
							</form>
							</div>
							</div>
						</th>
					</tr>
					<?php
					$i++;
				}
			}
			?>
			</table>
<?php					
		}
		mysqli_free_result(query);
	}		
}	
else
{ ?> 
	<form action="" method="post">
	<table class="table table-bordered">
		<tr><thead><th colspan="3">Enter Information</th></thead></tr>
		<tr>
			<th>Enter Member UserName</th>
			<td><input type="text" name="user_name" class="form-control" /></td>
			<td><input type="submit" name="submit" value="Submit" class="btn btn-info" /></td>
		</tr>
	</table>
	</form>
	<?php 
	$sql = "select t1.*,t2.username,CONCAT(t2.f_name,' ',t2.l_name) uname,t2.phone_no,t2.email,count(t1.user_id) cnt from kycm t1
			left join users t2 on t1.user_id = t2.id_user
			group by t1.user_id"; 
	$SQL = "$sql LIMIT $tstart,$tot_p ";
	$query = query_execute_sqli($SQL);
	$totalrows = mysqli_num_rows($query);
	
	$sqlk = "SELECT COUNT(*) num FROM ($sql) t1";
	$query = query_execute_sqli($sqlk);
	while($ro = mysqli_fetch_array($query))
	{
		$tot_rec = $ro['num'];
		$lpnums = ceil ($tot_rec/$plimit);
	}
	
	if($totalrows > 0){
		?>
		<table class="table table-bordered">
		<thead>
		<tr>
			<th>S.No.</th>
			<th>User Id</th>
			<th>Name</th>
			<th>Phone</th>
			<th>Email</th>
			<th>No. Of KYC</th>
			<th>&nbsp;</th>
		</tr>
		</thead>
		<?php
		$pnums = ceil($totalrows/$plimit);
		if($newp == ''){ $newp = '1'; }
			
		$start = ($newp-1) * $plimit;
		$starting_no = $start + 1;
		
		$sr_no = $starting_no;
		$que = query_execute_sqli("$sql LIMIT $start,$plimit");
		while($row = mysqli_fetch_array($que))
		{ ?>
			<tr>
				<th><?=$sr_no;?></th>
				<th><?=$row['username'];?></th>
				<th><?=$row['uname'];?></th>
				<th><?=$row['phone_no'];?></th>
				<th><?=$row['email'];?></th>
				<th><?=$row['cnt'];?></th>
				<th>
					<form action="index.php?page=<?=$val?>" method="post">
						<input type="hidden" name="user_name" value="<?=$row['username']?>" />
						<input type="submit" name="submit" value="View" class="btn btn-success" />
					</form>
				</th>
			</th>
		<?php
		$sr_no++;
		}
		print "</table>";
		pagging_initation_last_five_admin($newp,$lpnums,$show_tab,$val);
	}
}
mysqli_free_result(query);  ?>
<script>
$(document).ready(function(){
	$('#myModal5').on('show.bs.modal', function (e) {
		var id = $(e.relatedTarget).data('id');
		var uname = $(e.relatedTarget).data('username');
       
	});
});
$(document).ready(function(){
	$('#myModal2').on('show.bs.modal', function (e) {
		$('#e_id').val($(e.relatedTarget).data('id'));
		$('#name').val($(e.relatedTarget).data('name'));
		$('#ac_no').val($(e.relatedTarget).data('ac'));
		$('#bank').val($(e.relatedTarget).data('bank'));
		$('#branch').val($(e.relatedTarget).data('branch'));
		$('#ifsc').val($(e.relatedTarget).data('ifsc'));
		$('#pan_no').val($(e.relatedTarget).data('pan'));
		$('#city').val($(e.relatedTarget).data('city'));
	});
});
</script>
<div class="modal inmodal fade" id="myModal2" tabindex="-1" role="dialog"  aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
				</button>
				<h4 class="modal-title">EDIT KYC Member</h4>
				<!--<small class="font-bold">Member Direct</small>-->
			</div>
			<div class="modal-body">
			<form name="register" action="" method="post">
			<input type="hidden" name="user_id" value="<?=$id_user?>" />
			<input type="hidden" name="e_id" id="e_id" value="<?=$id_user?>" />
			<input type="hidden" name="user_name" value="<?=$u_name?>" />
			<table class="table table-bordered">
				<thead>
				</thead>
				<tr>
					<!--<th>KYC Member</th>
					<td><input type="text" name="kusername" id="kusername" class="form-control" />
						<span id="user-result"></span>
					</td>-->
					<th colspan="2">A/c Holder Name</th>
					<td colspan="2"><input type="text" name="benf_name" id="name" class="form-control" required /></td>
				</tr>
				<tr>
					<th>A/c Number</th>
					<td><input type="text" name="ac_no" id="ac_no" class="form-control" required /></td>
					<th>Bank Name</th>
					<td><input type="text" name="bank" id="bank" class="form-control" required /></td>
				</tr>
				<tr>
					<th>Branch</th>
					<td><input type="text" name="branch" id="branch" class="form-control" required /></td>
					<th>IFSC Code</th>
					<td><input type="text" name="ifsc_code" id="ifsc" class="form-control" required /></td>
				</tr>
				<tr>
					<th>PAN NO.</th>
					<td><input type="text" name="pan_no" id="pan_no" class="form-control" required /></td>
					<th>City</th>
					<td><input type="text" name="city" id="city" class="form-control" required /></td>
				</tr>
				<tr>
					<td colspan="4" class="text-center">
						<input type="submit"  name="edit_member" id="edit_member" value="Edit" class="btn btn-info" />
					</td>
				</tr>
			</table>
			</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
				<!--<button type="button" class="btn btn-primary">Save changes</button>-->
			</div>
		</div>
	</div>
</div>
<div class="modal inmodal fade" id="myModal5" tabindex="-1" role="dialog"  aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
				</button>
				<h4 class="modal-title">KYC Member</h4>
				<!--<small class="font-bold">Member Direct</small>-->
			</div>
			<div class="modal-body">
			<form name="register" action="" method="post">
			<input type="hidden" name="user_id" value="<?=$id_user?>" />
			<input type="hidden" name="user_name" value="<?=$u_name?>" />
			<table class="table table-bordered">
				<thead>
				</thead>
				<tr>
					<!--<th>KYC Member</th>
					<td><input type="text" name="kusername" id="kusername" class="form-control" />
						<span id="user-result"></span>
					</td>-->
					<th colspan="2">A/c Holder Name</th>
					<td colspan="2"><input type="text" name="benf_name" class="form-control" required /></td>
				</tr>
				<tr>
					<th>A/c Number</th>
					<td><input type="text" name="ac_no" class="form-control" required /></td>
					<th>Bank Name</th>
					<td><input type="text" name="bank" class="form-control" required /></td>
				</tr>
				<tr>
					<th>Branch</th>
					<td><input type="text" name="branch" class="form-control" required /></td>
					<th>IFSC Code</th>
					<td><input type="text" name="ifsc_code" class="form-control" required /></td>
				</tr>
				<tr>
					<th>PAN NO.</th>
					<td><input type="text" name="pan_no" class="form-control" required /></td>
					<th>City</th>
					<td><input type="text" name="city" class="form-control" required /></td>
				</tr>
				<tr>
					<td colspan="4" class="text-center">
						<input type="submit" name="add_member" id="add_member" value="ADD" class="btn btn-info" />
					</td>
				</tr>
			</table>
			</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
				<!--<button type="button" class="btn btn-primary">Save changes</button>-->
			</div>
		</div>
	</div>
</div>

<!--CREATE TABLE `kycm` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `bank_ac` varchar(100) NOT NULL,
  `bank` varchar(255) NOT NULL,
  `branch` varchar(255) NOT NULL,
  `ifsc` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `pan_no` varchar(100) NOT NULL,
  `date` datetime NOT NULL,
  `mode` int(11) NOT NULL,
  `by_user_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;-->