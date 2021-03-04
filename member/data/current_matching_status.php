<?php
include('../security_web_validation.php');

session_start();
include("function/setting.php");

$login_id = $_SESSION['mlmproject_user_id'];

$newp = $_GET['p'];
$plimit = 20;
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;


$sql = "SELECT * FROM pair_point where user_id = '$login_id' GROUP BY date ORDER BY date DESC";

$SQL = "$sql LIMIT $tstart,$tot_p ";

$query = query_execute_sqli($SQL);
$totalrows = mysqli_num_rows($query);

$sqlk = "SELECT COUNT(t1.id) num FROM ($sql) t1";
$quer = query_execute_sqli($sqlk);
while($ro = mysqli_fetch_array($quer))
{
	$tot_rec = $ro['num'];
	$lpnums = ceil ($tot_rec/$plimit);
}

if($totalrows > 0)
{ 
	$team_act_l = get_network_lr_team($login_id,'left_network',1);
	$team_dct_l = get_network_lr_team($login_id,'left_network',2);
	$team_l = $team_act_l+$team_dct_l;
	
	$team_act_r = get_network_lr_team($login_id,'right_network',1);
	$team_dct_r = get_network_lr_team($login_id,'right_network',2);
	$team_r = $team_act_r+$team_dct_r;
	
	$dir_mem_act = get_direct_act_dct_total($login_id,1);
	$dir_mem_dct = get_direct_act_dct_total($login_id,2);
	
	//$left_bus = get_network_lr_business($login_id,'left_network');
	//$right_bus = get_network_lr_business($login_id,'right_network');
	$lr_business = get_network_lr_business($login_id);
	$left_bus = $lr_business[0];
	$right_bus = $lr_business[1];
	
	
	$dir_bus_l = get_tot_direct_business($login_id,$systems_date,0);
	$dir_bus_r = get_tot_direct_business($login_id,$systems_date,1);
	
	//$cur_netbus_l = get_today_network_business($login_id,$systems_date,'left_network');
	//$cur_netbus_r = get_today_network_business($login_id,$systems_date,'right_network');
	$tot_business = get_today_network_business_new($login_id,$systems_date);
	$cur_netbus_l = $tot_business[0];
	$cur_netbus_r = $tot_business[1];
	
	
	$carry_forwd = get_user_today_carry_forward($login_id,$systems_date);
	$car_forwd_l = $carry_forwd[0];
	$car_forwd_r = $carry_forwd[1];
	?>
				
	<!--<table class="table table-bordered table-hover ">
		<thead>
		
		<tr>
			<th>Business Summary</th><th class="text-center">Active</th>
			<th class="text-center">Register</th><th class="text-center">Total</th>
		</tr>
		</thead>
		<tr class="text-center">
			<th>Total Team</th>						<td><?=$team_act_l+$team_act_r?></td>
			<td><?=$team_dct_l+$team_dct_r?></td>	<td><?=$team_l+$team_r?></td>
		</tr>
		<tr class="text-center">
			<th>Total Referral</th>					<td><?=$dir_mem_act?></td>
			<td><?=$dir_mem_dct?></td>				<td><?=$dir_mem_act+$dir_mem_dct?></td>
		</tr>
		<tr class="text-center">
			<th>Left Side</th>						<td><?=$team_act_l?></td>
			<td><?=$team_dct_l?></td>				<td><?=$team_l?></td>
		</tr>
		<tr class="text-center">
			<th>Right Side</th>						<td><?=$team_act_r?></td>
			<td><?=$team_dct_r?></td>				<td><?=$team_r?></td>
		</tr>
		
		<thead>
		<tr>
			<th>&nbsp;</th><th class="text-center">Left</th>
			<th class="text-center">Right</th><th class="text-center">Total</th>
		</tr>
		</thead>
		<tr class="text-center">
			<th>Total Business</th>			<td>&#36;<?=$left_bus?></td>
			<td>&#36;<?=$right_bus?></td>	<td>&#36;<?=$left_bus+$right_bus?></td>
		</tr>
		
		<tr class="text-center">
			<th>Referral Business</th>			<td>&#36;<?=$dir_bus_l?></td>
			<td>&#36;<?=$dir_bus_r?></td>	<td>&#36;<?=$dir_bus_l+$dir_bus_r?></td>
		</tr>
		
		<tr class="text-center">
			<th>Current Business</th>			<td>&#36;<?=$cur_netbus_l?></td>
			<td>&#36;<?=$cur_netbus_r?></td>	<td>&#36;<?=$cur_netbus_l+$cur_netbus_r?></td>
		</tr>
		<tr class="text-center">
			<th>Carry Forward</th>			<td>&#36;<?=$car_forwd_l?></td>
			<td>&#36;<?=$car_forwd_r?></td>	<td>&#36;<?=$car_forwd_l+$car_forwd_r?></td>
		</tr>
	</table>-->
	<div class="col-md-12">
		<a class="btn btn-danger" href="index.php?page=binarytree"><i class="fa fa-reply"></i> Back</a>
	</div>		
	<div class="col-md-12">&nbsp;</div>	
	<table class="table table-bordered table-hover">
		<thead>
		<tr>
			<th class="text-center" rowspan="2">Sr. No.</th>
			<th class="text-center" rowspan="2">Date</th>
			<th class="text-center" colspan="2">Carry Forward</th>
			
			<th class="text-center" colspan="2">Current Business</th>
			<th class="text-center" colspan="2">Total Business</th>
			
			<!--<th class="text-center" rowspan="2">Total Current </th>-->
			<th class="text-center" rowspan="2">Matching Business</th>
			<th class="text-center" rowspan="2">Flush Business</th>
			<th class="text-center" rowspan="2">Net Business</th>
		</tr>
		<tr>	
			<th class="text-center">Left</th>
			<th class="text-center">Right</th>
			
			<th class="text-center">Left</th>
			<th class="text-center">Right</th>
			
			<th class="text-center">Left</th>
			<th class="text-center">Right</th>
		</tr>
		</thead>
		<?php
		$pnums = ceil($num/$plimit);
		if($newp == ''){ $newp = '1'; }
			
		$start = ($newp-1) * $plimit;
		$starting_no = $start + 1;
		$sr_no = $starting_no;
		
		$pcl = array(0); 
		$pcr = array(0);
		$cp_l = array(0); 
		$cp_r = array(0);
		$i = 0;
		//$bin_date = get_user_binary_qualifier_date($login_id);
		/*if($bin_date !=''){
			$bin_date = date('Y-m-d' , strtotime($bin_date."- 1 Day"));
		}else{
			$bin_date = date('Y-m-d' , strtotime($systems_date."+ 1 Day"));
		}*/
		$bin_qual = get_user_binary_qualifier($login_id,$systems_date);
		$que = query_execute_sqli("$sql LIMIT $start,$plimit");
		while($row = mysqli_fetch_array($que))
		{
			$user_id = $row['user_id'];
			$cp_l[] = $left_point1 = $left_point = $row['left_point'];
			$cp_r[] = $right_point1 = $right_point = $row['right_point'];
			$date_p = $row['date'];
			$cf_left = $row['cf_left'];
			$cf_right = $row['cf_right'];
			$bkl = $row['bkl'];
			$bkr = $row['bkr'];
			$flush_business = $row['flush_business']; //$row['flush_business']*10;
			$lb_member = $row['lb_member'];
			$rb_member = $row['rb_member'];
			$date = date('d/m/Y', strtotime($date_p));
			$user_name = get_user_name($user_id);
			
			
			$cur_business = $rest_lp = $rest_rp = $tot_bus = $left_carry = $right_carry = $max_pair = $curb_left = $curb_right = 0;
			
			
			
			//if(strtotime($date_p) >= strtotime($bin_date)){
			if($bin_qual > 0){
				$max_pair = (int)(min($left_point,$right_point)/$per_day_multiple_pair)*$per_day_multiple_pair;  
				// Original Pair Point
				
				// Start Pair Point after deduction 10%
				$max_pair_per = $max_pair*10/100;
				$active_investmnt = get_user_active_investment($user_id);
				
				
				if($active_investmnt < $max_pair_per){ $pair_point = $active_investmnt; }
				else{ $pair_point = $max_pair_per; }
				// End Pair Point after deduction 10%
				
				
				$left_carry = $right_carry = 0;
				
				$right_pair = (int)($right_point/$per_day_multiple_pair);
				$left_pair = (int)($left_point/$per_day_multiple_pair);
				
				if($right_point == 0){ $left_carry = $left_point; }
				elseif($right_point < $left_point){ 
					//$left_carry = $left_point-($per_day_multiple_pair*$right_pair);
					//$right_carry = $right_point-($per_day_multiple_pair*$right_pair);
				}
				
				if($left_point == 0){ $right_carry = $right_point; }
				elseif($left_point < $right_point){ 
					//$right_carry = $right_point-($per_day_multiple_pair*$left_pair);
					//$left_carry = $left_point-($per_day_multiple_pair*$left_pair);
				}
				
				//$flush_business = user_flush_business($login_id,$max_pair);
				
				$pcl[] = $left_carry;
				$pcr[] = $right_carry;
				
				$rest_lp = $left_point-$pcl[$i];	//Value Stored in Array
				$rest_rp = $right_point-$pcr[$i];	//Value Stored in Array
				if($left_point1 > 0 and $right_point1 > 0){
					$rest_lp = max($left_point1,$cp_l[$i])-min($left_point1,$cp_l[$i]);	//Value Stored in Array
					$rest_rp = max($right_point1,$cp_r[$i])- min($right_point1,$cp_r[$i]);	//Value Stored in Array
				}
				
				$i++;
				//$rest_lp = get_today_network_business($user_id,$date_p,'left_network');
				//$rest_rp = get_today_network_business($user_id,$date_p,'right_network');
				$tot_bus = $rest_lp+$rest_rp;
				
				$cur_business = $cf_left+$cf_right;
				
				/*if($date_p == '2019-01-08'){
					$left_carry = $right_carry = $flush_business = $max_pair = $cf_left =$cf_right=$cur_business =0;
					$left_point1 = $bkl;
					$right_point1 = $bkr;
				}*/
				
				$left_carry = $left_point1 - $cf_left;
				$right_carry = $right_point1 - $cf_right;
				
				
				if($left_carry == 0 and $max_pair == 0){
					$left_carry = $left_point1;
				}
				if($right_carry == 0 and $max_pair == 0){
					$right_carry = $right_point1;
				}
				$curb_left = $cf_left;
				$curb_right = $cf_right;
			}
			
			//$curb_left = $cf_left;
			//if($cf_left > 0){ 
				/*$curb_left = "<form method='post' action='index.php?page=current_business' target='_blank'>
				<input type='hidden' name='user_id' value='$user_id' />
				<input type='hidden' name='username' value='$user_name' />
				<input type='hidden' name='position' value='left' />
				<input type='hidden' name='member' value='$lb_member' />
				<input type='hidden' name='date' value='$date_p' />
				<input type='hidden' name='tot_bus' value='$cf_left' />
				<input type='submit' name='view_all' value='$cf_left' class='btn btn-success btn-sm' />
			</form>";*/
				//$curb_left = "<button type='button' class='btn btn-success btn-sm' data-toggle='modal' data-target='#myModal5' data-member='$lb_member' data-position='left' data-username='$user_name' data-date='$date_p'>$cf_left</button>";
			//}
			
			//$curb_right = $cf_right;
			//if($cf_right > 0){ 
				/*$curb_right = "<form method='post' action='index.php?page=current_business' target='_blank'>
				<input type='hidden' name='user_id' value='$user_id' />
				<input type='hidden' name='username' value='$user_name' />
				<input type='hidden' name='position' value='right' />
				<input type='hidden' name='member' value='$rb_member' />
				<input type='hidden' name='date' value='$date_p' />
				<input type='hidden' name='tot_bus' value='$cf_right' />
				<input type='submit' name='view_all' value='$cf_right' class='btn btn-success btn-sm' />
			</form>";*/
				//$curb_right = "<button type='button' class='btn btn-success btn-sm' data-toggle='modal' data-target='#myModal5' data-member='$rb_member' data-position='right' data-username='$user_name' data-date='$date_p'>$cf_right</button>";
			//}
			$net_business = $max_pair-$flush_business;
			?>
			<tr class="text-center">
				<td><?=$sr_no;?></td>
				<td><?=$date;?></td>
				<td><?=$left_carry;?></td>
				<td><?=$right_carry;?></td>
				
				<td><?=$curb_left;?></td>
				<td><?=$curb_right;?></td>
				<td><?=$left_point1;?></td>
				<td><?=$right_point1;?></td><!--<td><?=$rest_lp;?></td><td><?=$rest_rp;?></td>-->
				
				<!--<td><?=$cur_business;?></td>-->
				<td><?=$max_pair;?></td>
				<td><?=$flush_business;?></td>
				<td><?=$net_business?></td>
			</tr> <?php
			$sr_no++;
		} ?>
	</table> <?php
	pagging_initation_last_five($newp,$pnums,$val,$lpnums,$show_tab);
}
else { echo "<B class='text-danger'>There Are no information to show !</B>"; }	
?>


<script>
$(document).ready(function(){
	$('#myModal5').on('show.bs.modal', function (e) {
		var id = $(e.relatedTarget).data('id');
		var position = $(e.relatedTarget).data('position');
		var username = $(e.relatedTarget).data('username');
		var member = $(e.relatedTarget).data('member');
		var date = $(e.relatedTarget).data('date');
        $.ajax({
            type : 'post',
            url : 'current_business.php',
            data :  {'id': id, 'position': position, 'username': username, 'member': member, 'date': date},
            success : function(data){
				$('.show_user').html(data);
			}
		});
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
				<h4 class="modal-title">Downline Business</h4>
				<!--<small class="font-bold">Member Direct</small>-->
			</div>
			<div class="modal-body">
				<div class="show_user"></div> 
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
				<!--<button type="button" class="btn btn-primary">Save changes</button>-->
			</div>
		</div>
	</div>
</div>
