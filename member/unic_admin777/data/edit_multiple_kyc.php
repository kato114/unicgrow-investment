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
if(isset($_POST['add_member']) and $_POST['add_member'] != ""){
	extract($_POST);
	$by_user_id = $user_id;
	if(($user_id = get_new_user_id($kusername)) > 0){
		$sql = "insert into kycm set user_id='$user_id',name ='$benf_name',bank_ac ='$ac_no',bank ='$bank',
		branch='$branch',ifsc='$ifsc_code',city='$city',pan_no='$pan_no',
		by_user_id='$by_user_id'";
		query_execute_sqli($sql);
		$_POST['add_member'] = "";
		$_POST['submit'] = 'submit';
		$_POST['user_name'] = 'user_name';
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
	if($_POST['submit'] == 'Submit')
	{
		$u_name = $_POST['user_name'];
		$sql = "select t1.id_user,t2.*,t3.username from users t1
		left join kycm t2 ON t1.id_user = t2.by_user_id
		left join users t3 on t3.id_user = t2.user_id
		where t1.username = '$u_name' ";
		$query = query_execute_sqli($sql);
		$num = mysqli_num_rows($query);
		if($num == 0){ echo "<B class='text-danger'>Please Enter right Username! </B>"; }
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
				</th>
			</tr>
			</thead>
			<?php
			while($row = mysqli_fetch_array($query))
			{
				$id_user = $row['id_user'];
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
						<th colspan="4">KYC From</th><th colspan="5" id="ke_member"><?=$u_name?></th>
					</tr>
					<tr>
						<th>S.No.</th>
						<th>KYC Member</th>
						<th>A/c Holder Name</th>
						<th>A/c Number</th>
						<th>Bank Name</th>
						<th>Branch</th>
						<th>IFSC Code</th>
						<th>PAN NO.</th>
						<th>&nbsp;</th>
					</tr>
					</thead>
					<?php
					}
					?>
					<tr>
						<th><?=$i+1;?></th>
						<th><?=$username?></th>
						<th><?=$name?></th>
						<th><?=$bank_ac?></th>
						<th><?=$bank?></th>
						<th><?=$branch?></th>
						<th><?=$ifsc?></th>
						<th><?=$pan_no?></th>
						<th>
							<form action="index.php?page=edit_multiple_kyc" method="post" target="_blank">
								<input type="hidden" name="user_name" value="<?=$username?>" />
								<input type="submit" name="edit_kyc" value="EDIT" class="btn btn-success" />
							</form>
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
}  ?>
<script>
$(document).ready(function(){
	$('#myModal5').on('show.bs.modal', function (e) {
		var id = $(e.relatedTarget).data('id');
		var uname = $(e.relatedTarget).data('username');
       
	});
	$("#add_member").click(function(){
		var kmem = $("#kusername").val();
		if(kmem != ''){
			 var ke_member = $("#ke_member").html();
			 if(ke_member != kmem){
			 
			 }
			 else{
			 	$("#user-result").html("<B class='text-danger'>Try To Another Member");
			 	return false;
			 }
		}
		else{
			$("#user-result").html("<B class='text-danger'>Please Fill Kyc Member");
			return false;
		}
	});
	$("#kusername").keyup(function (e) {
		//removes spaces from username
		$(this).val($(this).val().replace(/\s/g, ''));
		var kusername = $(this).val();
		if(kusername.length < 3){$("#user-result").html('');return;}
		
		if(kusername.length >= 3){
			$("#user-result").html('Lodding...');
			$.post('../check_username.php', {'kusername':kusername},function(data)
			{
			  $("#user-result").html(data);
			});
		}
	});	
});

</script>
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
					<th>KYC Member</th>
					<td><input type="text" name="kusername" id="kusername" class="form-control" />
						<span id="user-result"></span>
					</td>
					<th>A/c Holder Name</th>
					<td><input type="text" name="benf_name" class="form-control" /></td>
				</tr>
				<tr>
					<th>A/c Number</th>
					<td><input type="text" name="ac_no" class="form-control" /></td>
					<th>Bank Name</th>
					<td><input type="text" name="bank" class="form-control" maxlength="10" /></td>
				</tr>
				<tr>
					<th>Branch</th>
					<td><input type="text" name="branch" class="form-control" /></td>
					<th>IFSC Code</th>
					<td><input type="text" name="ifsc_code" class="form-control" /></td>
				</tr>
				<tr>
					<th>PAN NO.</th>
					<td><input type="text" name="pan_no" class="form-control" /></td>
					<th>City</th>
					<td><input type="text" name="city" class="form-control" /></td>
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
