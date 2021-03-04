<?php
include('../../security_web_validation.php');
?>
<link rel="stylesheet" type="text/css" href="assets/css/phpmyadmin.css">
<script>
$(document).ready(function() {
	$("#checkAll").click(function () {
		$('input:checkbox').not(this).prop('checked', this.checked);
	});
	$("input[id^='checkall_Data_priv_']").click(function() {
		var id = parseInt(this.id.replace("checkall_Data_priv_", ""), 10);
		var child = $(".parent-s" + id);
		$(child).not(this).prop('checked', this.checked);
	});
});
$(document).on('click', '.remove_btn', function (e) {
    if(confirm("Are you sure you want to remove this account?")){
    
	}
    else{
        return false;
    }
});
$(document).on('click', '#cancel_submit', function (e) {
	window.location = "index.php?page=admin_privileges";
});

</script>

<?PHP
session_start();
include("condition.php");
include("../function/setting.php");
include("../function/functions.php");
include("../function/send_mail.php");
$newp = $_GET['p'];
$plimit = 20;

if(isset($_REQUEST['remove_account'])){
	$id = $_REQUEST['table_id'];
	if($id != 1){
		$sql = "delete from admin where id_user='$id'";
		query_execute_sqli($sql);
		$sql = "delete from privileges where user_id='$id'";
		query_execute_sqli($sql);
		query_execute_sqli("ALTER TABLE `privileges` DROP `id`");
		query_execute_sqli("ALTER TABLE `privileges` ADD `id` INT NOT NULL AUTO_INCREMENT FIRST ,ADD PRIMARY KEY ( id )");
		
		?> <script> 
			alert('Account Remove Form Account List Successfully'); 
			window.location = "index.php?page=admin_privileges"; 
		</script> <?php
	}
	else{
		?> <script> 
			alert('Super Account Not Remove Form Account List'); 
			window.location = "index.php?page=admin_privileges"; 
		</script> <?php
	}
}
elseif(isset($_POST['final_edit_account'])){
	$id = $_POST['edituser_submit'];
	$username = $_POST['username'];
	$email = $_POST['email'];
	$password = $_POST['password'];
	$sql = "select * from admin where username = '$username' and id_user not in($id)";
	$num = mysqli_num_rows(query_execute_sqli($sql));
	if($num == 0){
		
		$sql = "update admin set `username`='$username',`password`='$password',`email`='$email'
				where id_user='$id'";
		query_execute_sqli($sql);
		
		?> <script> 
			alert('Edit Account Successfully !!'); window.location = "index.php?page=admin_privileges"; 
		</script> <?php
	}
	else{ echo "<B class='text-danger'>Username Already Exists</B>"; }
}
elseif(isset($_POST['final_add_account'])){
	$username = $_POST['username'];
	$email = $_POST['email'];
	$password = $_POST['password'];
	$sql = "select * from admin where username = '$username'";
	$num = mysqli_num_rows(query_execute_sqli($sql));
	if($num == 0){
		
		$sql = "insert into admin (`username`,`password`,`email`)
				values('$username','$password','$email')";
		query_execute_sqli($sql);
		?> <script> 
			alert('Add Account Successfully !!'); window.location = "index.php?page=admin_privileges"; 
		</script> <?php
	}
	else{ echo "<B class='text-danger'>Username Already Exists</B>"; }
}
elseif(isset($_POST['edit_account'])){
	$id = $_POST['table_id'];
	$sql = "select * from admin where id_user = '$id'";
	$q = query_execute_sqli($sql);
	while($row = mysqli_fetch_array($q))
	{
		$password = $row['password'];
		$email = $row['email'];
		$username = $row['username'];
	}
	?>
	<form action="" method="post">
		<fieldset id="fieldset_add_user_login">
			<legend>Login Information</legend>
			<div class="item col-md-6">
				<div class="col-md-3"><label for="select_pred_username"> Username : </label></div>
				<div class="col-md-3">
					<input class="autofocus" type="text" required="required" onchange="pred_username.value = 'userdefined'; this.required = true;" title="User name" value="<?=$username?>" maxlength="32" name="username">
				</div>
			</div>
			<div class="item col-md-6">
				<div class="col-md-3"><label for="select_pred_username"> Email : </label></div>
				<div class="col-md-3">
					<input class="autofocus" type="email" required="required" onchange="pred_email.value = 'userdefined'; this.required = true;" title="Email" name="email" value="<?=$email?>">
				</div>
			</div>
			<div class="item col-md-6">
				<div class="col-md-3"><label for="select_pred_username"> Password : </label></div>
				<div class="col-md-3">
				<input class="autofocus" type="password" required="required" onchange="pred_password.value = 'userdefined'; this.required = true;" title="Password" maxlength="32" minlength=6 name="password" value="<?=$password?>">
				</div>
			</div>
		</fieldset>
		<fieldset id="fieldset_add_user_footer" class="tblFooters">
			<input type="hidden" value="<?=$id?>" name="edituser_submit">
			<input id="cancel_submit" type="submit" value="Cancel" name="cancel_add_account" class="btn btn-info" />
			<input id="edituser_submit" type="submit" value="Go" name="final_edit_account" class="btn btn-warning" />
		</fieldset>
	</form>
	<?php
}
elseif(isset($_POST['add_account'])){
	?>
	<form action="" method="post">
		<fieldset id="fieldset_add_user_login">
			<legend>Login Information</legend>
			<div class="item col-md-6">
				<div class="col-md-3"><label for="select_pred_username"> Username : </label></div>
				<div class="col-md-3">
					<input class="autofocus" type="text" required="required" onchange="pred_username.value = 'userdefined'; this.required = true;" title="User name" maxlength="32" name="username">
				</div>
			</div>
			<div class="item col-md-6">
				<div class="col-md-3"><label for="select_pred_username"> Email : </label></div>
				<div class="col-md-3">
					<input class="autofocus" type="email" required="required" onchange="pred_email.value = 'userdefined'; this.required = true;" title="Email" name="email">
				</div>
			</div>
			<div class="item col-md-6">
				<div class="col-md-3"><label for="select_pred_username"> Password : </label></div>
				<div class="col-md-3">
					<input class="autofocus" type="password" required="required" onchange="pred_password.value = 'userdefined'; this.required = true;" title="Password" maxlength="32" minlength=6 name="password">
				</div>
			</div>
		</fieldset>
		<fieldset id="fieldset_add_user_footer" class="tblFooters">
			<input type="hidden" value="1" name="adduser_submit">
			<input id="cancel_submit" type="submit" value="Cancel" name="cancel_add_account" class="btn btn-info" />
			<input id="adduser_submit" type="submit" value="Go" name="final_add_account" class="btn btn-info" />
		</fieldset>
	</form>
	<?php
}
elseif(isset($_POST['go'])){
	$id = $_POST['table_id'];
	$select_mm = $_POST['Select_priv'];
	$sql = "update `privileges` set mode=0 where user_id='$id'";
	query_execute_sqli($sql);
	for($i = 0; $i < count($select_mm); $i++){
		$menu_id = $select_mm[$i];
		$sql = "select * from `privileges` where user_id='$id' and menu_id = '$menu_id'";
		$num= mysqli_num_rows(query_execute_sqli($sql));
		if($num == 0){
			$main_menu_id = get_main_menuId($menu_id);
			$sql = "insert into `privileges`(`user_id`,`main_menuId`,`menu_id`,`mode`) 
			values('$id','$main_menu_id','$menu_id','1')";
			query_execute_sqli($sql);
		}
		else{
			$sql = "update `privileges` set `mode`=1 where `user_id`='$id' and menu_id='$menu_id'";
			query_execute_sqli($sql);
		}
	}
	?> <script> 
		alert('Privileges Updated Successfully'); 
		window.location = "index.php?page=admin_privileges"; 
	</script> <?php
}
elseif(isset($_POST['edit_privilege'])){
	$user_id = $_POST['table_id'];
	$members_menuId = $members_menuIds = $members_main_menuIds = "";
	$members_menuId = get_members_menuId($user_id);
	for($i = 0; $i < count($members_menuId); $i++){
		$members_menuIds[$i] = $members_menuId[$i][0];
		$members_main_menuIds[$i] = $members_menuId[$i][1];
	}
	?>
	<h2>Edit privileges: User account <i>''@'<?=$_POST['username']?>'</i></h2>
	<form action="" method="post">
		<fieldset id="fieldset_user_global_rights">
			<legend data-submenu-label="Global">Global privileges
				<input id="checkAll" class="checkall_box" title="Check all" type="checkbox"> 
				<label for="addUsersForm_checkall">Check all</label>
			</legend>
			<p><small><i>Note: Administrative menu privilege names are expressed.</i></small></p>
			<?php
			$chk_box = "checked='checked'";
			//for($i = 0;$i < count($parent_menu); $i++){
				for($i=0;$i<$total_main_menu;$i++){
					$main_submenu=0;
					$main_submenu = submenu($parent_menu[$i][1]);
					$count_main_submenu = count($main_submenu);
					$mchk = "";
					if(get_active_menu($user_id,$parent_menu[$i][2]))$mchk = $chk_box;
					?>
					<div class="row">
					<div class="col-md-4">
						<fieldset class="sub_box">
							<legend>
								<input class="s<?=$parent_menu[$i][2]?>" id="checkall_Data_priv_<?=$parent_menu[$i][2]?>" title="Check all" type="checkbox" <?=$mchk?>>
								<label for="checkall_Data_priv"><?=$parent_menu[$i][0]?></label>
							</legend>
							<?php
							if($count_main_submenu == 0){
								$pm_ck = "";
								if(in_array($parent_menu[$i][2],$members_menuIds)){
									$pm_ck = $chk_box;
								}
								
							?>
							<div class="item">
								<input class="checkall parent-s<?=$parent_menu[$i][2]?>" name="Select_priv[]" value="<?=$parent_menu[$i][2]?>" type="checkbox"<?=$pm_ck?>> <?=$parent_menu[$i][0]?> 
							</div>
							<?php
							}
							else{
								for($j=0;$j<$count_main_submenu;$j++){
									$tab = tab_menu_calculation($main_submenu[$j][1]);
									$total = count($tab);
									if($total == 0){
										$ms_ck = "";
										if(in_array($main_submenu[$j][2],$members_menuIds)){
											$ms_ck = $chk_box;
										}
										?>
										<div class="item">
											<input class="checkall parent-s<?=$parent_menu[$i][2]?>" name="Select_priv[]" value="<?=$main_submenu[$j][2]?>" type="checkbox" <?=$ms_ck?>> <?=$main_submenu[$j][0];?> 
										</div>
										<?php
									}
									else{
										for($k=0;$k<$total;$k++){
											$ts_ck = "";
											if(in_array($tab[$k][2],$members_menuIds)){
												$ts_ck = $chk_box;
											}
											?>
											<div class="item">
												<input class="checkall parent-s<?=$parent_menu[$i][2]?>" name="Select_priv[]" value="<?=$tab[$k][2]?>" title="Allows reading data." type="checkbox" <?=$ts_ck?>> <?=$tab[$k][0];?> 
											</div>
											<?php
										}
									}
								}
							}
						?>
						</fieldset>
					</div>
					</div>
					<?php
				}
			//}
			?>
			
		</fieldset>
		<fieldset id="fieldset_user_privtable_footer" class="tblFooters">
			<input type="hidden" name="table_id" value="<?=$_POST['table_id']?>" />
			<input id="cancel_submit" type="submit" value="Cancel" name="cancel_add_account" class="btn btn-info" />
			<input  type="submit" name="go" value="Go" class="btn btn-warning" />
		</fieldset>
	</form>
	<?php
}
else{
	$sqli = "SELECT * from admin ";
	$query = query_execute_sqli($sqli);
	$num = mysqli_num_rows($query);
	if($num > 0)
	{ ?>
		<table class="table table-bordered">
			<thead>
			<tr>
				<th class="text-center">Sr no.</th>
				<th class="text-center">User name</th>
				<th class="text-center">Password</th>
				<th class="text-center">Global privileges</th>
				<th class="text-center">Action</th>
			</tr>
			</thead>
			<?php
			$pnums = ceil ($num/$plimit);
			if ($newp==''){ $newp='1'; }
			$start = ($newp-1) * $plimit;
			$starting_no = $start + 1;
			
			$quer = query_execute_sqli("$sqli LIMIT $start,$plimit ");
			$sr_no = 1;
			while($row = mysqli_fetch_array($quer))
			{  
				$id = $row['id_user'];
				$user_id = $id;
				$username = $row['username'];
				$password = $row['password'];
				$privileges = $row['privileges'];
				$user_name = get_user_name($user_id);
				
				$q = query_execute_sqli("select * from privileges where user_id = '$id' and mode=1");
				$pnum = mysqli_num_rows($q);
			
				$privileges = "USAGE";
				if($pnum == ($global_total_menu)){
					$privileges = "ALL PRIVILEGES";
				}
				if($pnum == 0){
					$privileges = "Not Assign";
				}
				?>
				<tr align="center">
					<td><?=$sr_no;?></td>
					<td><?=$username;?></td>
					<td><?=$password;?></td>
					<td><?=$privileges;?></td>
					<td>
						<div class="col-md-12">
							<div class="col-md-4">
							<form action="" method="POST">
								<input type="hidden" value="<?=$id?>" name="table_id" />
								<input type="hidden" value="<?=$user_id?>" name="user_id" />
								<input type="hidden" value="<?=$row['username']?>" name="username" />
								<input type="Submit"  style="height:18px; width:18px; border:0;background-color:transparent; background-image:url(images/edit.png);cursor:pointer" value="" name="edit_privilege" title="Edit Privilege" />
								<label>Privilege</label>
							</form>
							</div>
							<div class="col-md-4">
							<form action="" method="POST">
								<input type="hidden" value="<?=$id?>" name="table_id" />
								<input type="Submit"  style="height:18px; width:18px; border:0;background-color:transparent; background-image:url(images/edit.png);cursor:pointer" value="" name="edit_account" title="Edit Account" />
								<label>Account</label>
							</form>
							</div>
							<div class="col-md-4">
							<form action="" method="POST">
								<input type="hidden" value="<?=$id?>" class="remove_account" name="table_id" />
								<input type="Submit"  style="height:18px; width:18px; border:0;background-color:transparent; background-image:url(images/delete.png);cursor:pointer" data-cid="<?=$id?>" value="" name="remove_account" title="Remove Account"  class="remove_btn" />
								<label>Account</label>
							</form>
							</div>
						</div>
					</td>
				</tr> <?php	
				$sr_no++;
			} ?>
		</table> <?php
		pagging_admin_panel($newp,$pnums,$val) ?>
		<div class="row">
			<fieldset>
				<legend>NEW</legend>
				<div class="item">
					<form action="" method="post">
					   <input type="submit" name="add_account" style="height:18px; width:18px; border:0;background-color:transparent; cursor:pointer;background:url(images/edit.png) no-repeat;" value="" title="Add Account" />
					   <span class="text-danger"> Add user account</span>
					 </form>
				</div>
			</fieldset>
		</div>
		<?php
	}
	else{ echo "<B class='text-danger'>There are no information to show !!</b>";}
}


?>
<style>
.item{
	text-align:left;
}
label {
    display: inline;
    padding-bottom: 3px;
}
</style>

