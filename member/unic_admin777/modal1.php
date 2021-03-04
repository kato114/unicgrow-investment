<?php
ini_set('display_errors','on');

require('../config.php');
require('../function/setting.php');
require('../function/functions.php');
extract($_REQUEST);
if(!isset($_REQUEST['sponser_tids']) and isset($_REQUEST['tids'])){
	$sql = "select * from reg_fees_structure where id='$tids'";
	$que = query_execute_sqli("$sql");
	while($row = mysqli_fetch_array($que))
	{ 	
		$dates = $row['date'];
		$email = $row['email'];
		$tid = $row['id'];
		$username = $row['username'];
		$update_fees = $row['update_fees'];
		$profit = $row['profit'];
		$count = $row['count'];
		$total_days = $row['total_days'];
		$remain = $total_days - $count;
	}
?>
	<div class="modal" id="myModal" role="dialog" style="display:block;">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Future ROI</h4>
				</div>
				<div class="modal-body">
					<div class="col-md-12 badge-success text-white">
						<div class="col-md-3">S.No.</div>
						<div class="col-md-3">Date</div>
						<div class="col-md-3">Profit</div>
						<div class="col-md-3">Status</div>
					</div>
					<?php
					for($i = 0; $i < $total_days; $i++){
						$date = date('d/m/Y' , strtotime(($i+1)." MONTH".$dates));
						$paid_date = date('Y-m-d' , strtotime(($i+1)." MONTH".$dates));
						$status = "<span class='text-danger'>Un-Paid</span>";
						if(strtotime($paid_date) < strtotime($systems_date)){$status = "<span class='text-success'>Paid</span>";}
						?>
						<div class="col-md-12">
							<div class="col-md-3"><?=($i+1)?></div>
							<div class="col-md-3"><?=$date?></div>
							<div class="col-md-3"><?=$profit?></div>
							<div class="col-md-3"><?=$status?></div>
						</div>
						<?php
						
					}
					?>
				</div>
				
				<div class="modal-footer">
					<div class="col-md-12">&nbsp;</div>
					<div class="col-md-12" align="right">
					<button type="button" class="btn btn-default modal_close" data-dismiss="modal" onclick="javascript:close_popup()" style="display:block;">Close</button>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php
}
if(isset($_REQUEST['sponser_tids']) and !isset($_REQUEST['tids'])){
	$sponser_tids = get_new_user_id($_REQUEST['sponser_tids']);
	$u_tids = get_new_user_id($_REQUEST['u_tids']);
	$position = "";
	if(chk_network_members($sponser_tids,$u_tids,0)){
		$position = "Left";
	}
	if(chk_network_members($sponser_tids,$u_tids,1)){
		$position = "Right";
	}
	?>
	<div class="modal" id="myModal" role="dialog" style="display:block;">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Member Detail</h4>
				</div>
					<div class="col-md-12 badge-success text-white">
						<div class="col-md-4">Sponser Id</div>
						<div class="col-md-4">User Id</div>
						<div class="col-md-4">Position</div>
							<div class="col-md-4"><?=$_REQUEST['sponser_tids']?></div>
							<div class="col-md-4"><?=$_REQUEST['u_tids']?></div>
							<div class="col-md-4"><?=$position?></div>
					</div>
					
					
						
				
				<div class="modal-footer">
					<div class="col-md-12">&nbsp;</div>
					<div class="col-md-12" align="right">
					<button type="button" class="btn btn-default modal_close" data-dismiss="modal" onclick="javascript:close_popup()" style="display:block;">Close</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
}
function chk_network_members($search_id,$u_tids,$position){
	$result = "";
	$sqls = "SELECT id_user FROM users WHERE parent_id = '$search_id' AND position = $position";
	$quer = query_execute_sqli($sqls);	
	$ro = mysqli_fetch_array($quer);
	$id_total = $ro[0];
	if($id_total != ""){
		$result = $id_total.",".mysqli_fetch_array(query_execute_sqli("SELECT get_chield_by_parent ($id_total)"))[0];
	}
	$result = explode(",",rtrim($result,","));
	if(in_array($u_tids,$result))return true;
	else return false;
}