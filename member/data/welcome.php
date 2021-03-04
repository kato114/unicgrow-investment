<?php
include('../security_web_validation.php');
ini_set("display_errors","off");
include('condition.php');
include('function/setting.php');
include("function/pair_point_income.php");
include("function/send_mail.php");
include("function/income.php");

$login_id = $_SESSION['mlmproject_user_id'];

//$currency = get_USD_TO_ETH_DOGE_LTC_BTC('BTC','USD');
$cur_btc_rate = array_shift(array_values($currency));

$main_wall = get_user_allwallet($login_id,'amount');
$act_wall = get_user_allwallet($login_id,'activationw');
$com_wall = get_user_allwallet($login_id,'companyw');
$tot_inc = get_user_total_bonus($login_id);



$my_plan = my_package($login_id);
$plan_name = $my_plan[0];
$plan_amt = $my_plan[1];

$user_mode = query_execute_sqli("select * from users as t1 inner join wallet as t2 on t1.id_user = t2.id and t1.id_user = '$login_id' inner join franchise as t3 on t1.location = t3.id ");
$num_mode = mysqli_num_rows($user_mode);
if($num_mode == 0)
{
	$pay_mode = 'Bank';
	$franchise_mode = 'Not Selected';
}
else
{
	$row = mysqli_fetch_array($user_mode);
	$p_mode = $row['roi_pay_mode'];
	$loc = $row['franchise_location'];	
	if($p_mode == 0)
		$pay_mode = 'Bank';
	else
		$pay_mode = 'Cash';	
		$franchise_mode = $loc;
}

$query = query_execute_sqli("SELECT * FROM users WHERE id_user = '$login_id' ");
while($row = mysqli_fetch_array($query))
{
	$username = $row['username'];
	$full_name = ucfirst($row['f_name'])." ".ucfirst($row['l_name']);
	$user_name = $row['username'];
	$phone_no = $row['phone_no'];
	$date_join = date("d/M/Y", strtotime($row['date']));
	$cash_back_date = $row['date'];
	$real_parent = get_user_name($row['real_parent']);
	$email = $row['email'];
	$phone_no = $row['phone_no'];
	$city = $row['city'];
	$country = $row['country'];
	$address = $row['address'];
	$rand_btc_address = $row['ac_no'];
}
if(isset($_REQUEST['ipaid']) and $_REQUEST['ipaid'] == "I Have Paid" and $_SESSION['send_payment'] == 0){
	$pid = $_REQUEST['pid'];
	$ppid = $_REQUEST['ppid'];
	?>
	<script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
	<script>
		 $(document ).ready(function() {
			$('.loading-container').show();
			$.ajax({
				url: "data/verify.php",
				type: "POST",
				cache: false,
				data: "&pid=<?=$pid?>&ppid= <?=$ppid?>",
				success : function(html){
					$('.loading-container').hide(); 
					if(html == "")
						alert("Oopsss some error occur.!");
					else{
						
						obj = JSON.parse(html);
						if(obj.result > 0){
							alert(obj.info);
							window.location = "index.php?page=deposit_history";
						}
					}
				}
			});
		});
	</script>
	<?php
}
$act_info = get_paid_member($login_id);
$status = "<span class='text-red'>Deactivate</span>";
if($act_info > 0){$status = "<span class='text-white'>Activate</span>";}
$sql = "SELECT * FROM request_crown_wallet WHERE user_id = '$login_id' AND status = 0 and ac_type=1 and DATE_ADD(date, INTERVAL 180 DAY) >= '$systems_date_time'   ORDER BY id ASC LIMIT 1";
	$qwtt = query_execute_sqli($sql);
	$num_inf = mysqli_num_rows($qwtt);
	if($num_inf > 0)
	{ 
		unset($_SESSION['send_payment']);
		$_SESSION['send_payment'] = 0;
		while($roe = mysqli_fetch_array($qwtt))
		{
			$bank_acc = $roe['bitcoin_address'];
			$bit_amount = $roe['request_crowd'];
			$paid_level = $roe['level_id'];
			$pay_to = $roe['username'];
			$table_id = $roe['id'];
			$ppid = $roe['transaction_id'];
			$tkey = $roe['tkey'];
			$btc_user_email = get_user_email($roe['login_id']);
			$cry_name = $roe['ac_type'] == 1 ? "BTC" : "ETH";
			$qr_path = "https://blockchain.info/qr?data=$bank_acc&amount=$bit_amount";
		}
?>

<div class="payment_row">
	<div class="col-md-12">
		<div class="panel panel-info report_info">
			<div class="panel-heading">
				<a href="#dialog-approve-report" data-toggle="modal" class="btn btn-block btn-warning btn-lg"><?=$cry_name?> is Un-Confirmed</a><br />
				<div class="modal fade dialog-approve" id="dialog-approve-report">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h3 class="modal-title" id="hierarchy_title">Payment Deposit BTC</h3>
							</div>
							<div class="modal-footer">
								<form method="post" action="">
									<div class="col-md-8 text-left">
										<h4>Please Pay To Following Address : </h4>
										 Amount :  <?=$bit_amount?> BTC<br />
										 <?=$cry_name?> Address :  <?=$bank_acc?><br />
									</div>
									<div class="col-md-4">
										<img src="<?=$qr_path?>" height="150" width="150" border="0" />
									</div>
									<div class="col-md-12 text-left">
									<div class="panel panel-danger">
										CLICK "I HAVE PAID" AFTER SUBMITTING PAYMENT<br />
									</div>
									</div>
									<input type="hidden" name="pid" value="<?=$table_id?>"  />
									<input type="hidden" name="ppid" value="<?=$ppid?>"  />
									<input class="btn btn-primary btn-sm" value="I Have Paid" type="submit" name="ipaid">
									<!--<a href="#dialog-approve-admin-report" data-toggle="modal" class="btn btn-success">Request Admin</a>-->
									<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal">Cancel</button>
								
								</form>
							</div>
						</div>
					</div>
				</div>
					
			</div>
		</div> 
	</div>
		<?php
	}
$sql = "SELECT * FROM request_crown_wallet WHERE user_id = '$login_id' AND status = 0 and ac_type=3 and DATE_ADD(date, INTERVAL 180 DAY) >= '$systems_date_time'   ORDER BY id ASC LIMIT 1";
	$qwtt = query_execute_sqli($sql);
	$num_inf = mysqli_num_rows($qwtt);
	if($num_inf > 0)
	{ 
		unset($_SESSION['send_payment']);
		$_SESSION['send_payment'] = 0;
		while($roe = mysqli_fetch_array($qwtt))
		{
			$bank_acc = $roe['bitcoin_address'];
			$bit_amount = $roe['request_crowd'];
			$paid_level = $roe['level_id'];
			$pay_to = $roe['username'];
			$table_id = $roe['id'];
			$ppid = $roe['transaction_id'];
			$tkey = $roe['tkey'];
			$btc_user_email = get_user_email($roe['login_id']);
			$cry_name = $roe['ac_type'] == 1 ? "BTC" : "ETH";
			$qr_path = "https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl=ethereum:".$bank_acc."?amount=$bit_amount";
		}
?>
<div class="payment_row">
	<div class="col-md-12">
		<div class="panel panel-info report_info">
			<div class="panel-heading">
				<a href="#dialog-approve-report-eth" data-toggle="modal" class="btn btn-block btn-info btn-lg"><?=$cry_name?> is Un-Confirmed</a><br />
				<div class="modal fade dialog-approve-eth" id="dialog-approve-report-eth">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h3 class="modal-title" id="hierarchy_title">Payment Deposit ETH</h3>
							</div>
							<div class="modal-footer">
								<form method="post" action="">
									<div class="col-md-8 text-left">
										<h4>Please Pay To Following Address : </h4>
										 Amount :  <?=$bit_amount?> ETH<br />
										 <?=$cry_name?> Address :  <?=$bank_acc?><br />
									</div>
									<div class="col-md-4">
										<!--<img src="https://www.coinpayments.net/qrgen.php?id=<?=$ppid?>&key=<?=$tkey?>" height="150" width="150" />-->
										<img src="<?=$qr_path?>" height="150" width="150" border="0" />
									</div>
									<div class="col-md-12 text-left">
									<div class="panel panel-danger">
										CLICK "I HAVE PAID" AFTER SUBMITTING PAYMENT<br />
									</div>
									</div>
									<input type="hidden" name="pid" value="<?=$table_id?>"  />
									<input type="hidden" name="ppid" value="<?=$ppid?>"  />
									<input class="btn btn-primary btn-sm" value="I Have Paid" type="submit" name="ipaid">
									<!--<a href="#dialog-approve-admin-report" data-toggle="modal" class="btn btn-success">Request Admin</a>-->
									<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal">Cancel</button>
								
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div> <?php
	}	
?>
	</div>
</div>


<div class="row">
	<div class="col-md-12">
		<div class="col-md-4 box box1 shadow1">
			<h3>Total Income<br />
				<?=round($main_wall,2)?>
			</h3>
		</div>
		<div class="col-md-4 box box2 shadow1">
			<h3>Deposit Wallet<br />
				<?=round($act_wall,2)?>
			</h3>
		</div>
		<div class="col-md-4 box box3 shadow1">
			<h3>Total Withdraw<br />
				<?=get_user_tot_withdrawal($login_id)?>
			</h3>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">&nbsp;</div>
</div>
	
<!--<div id="crypto-stats-3" class="row">
	<div class="col-xl-4 col-12">
		<div class="card crypto-card-3 pull-up">
			<div class="card-content">
				<div class="card-body pb-0">
					<div class="row">
						<div class="col-2">
							<h1><i class="la la-usd warning font-large-2" title="BTC"></i></h1>
						</div>
						<div class="col-5 pl-2">
							<h4>Total</h4>
							<h6 class="text-muted">Income</h6>
						</div>
						<div class="col-5 text-right">
							<h4><?=round($main_wall,2)?></h4>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-12">
						<canvas id="btc-chartjs" class="height-75"></canvas>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-xl-4 col-12">
		<div class="card crypto-card-3 pull-up">
			<div class="card-content">
				<div class="card-body pb-0">
					<div class="row">
						<div class="col-2">
							<h1><i class="la la-usd info font-large-2" title="BTC"></i></h1>
						</div>
						<div class="col-5 pl-2">
							<h4>Deposit</h4>
							<h6 class="text-muted">Wallet</h6>
						</div>
						<div class="col-5 text-right">
							<h4><?=round($act_wall,2)?></h4>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-12">
						<canvas id="xrp-chartjs" class="height-75"></canvas>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-xl-4 col-12">
		<div class="card crypto-card-3 pull-up">
			<div class="card-content">
				<div class="card-body pb-0">
					<div class="row">
						<div class="col-2">
							<h1><i class="la la-usd blue-grey font-large-2" title="BTC"></i></h1>
						</div>
						<div class="col-5 pl-2">
							<h5>Total</h5>
							<h6 class="text-muted">Withdraw</h6>
						</div>
						<div class="col-5 text-right">
							<h4><?=get_user_tot_withdrawal($login_id)?></h4>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-12">
						<canvas id="eth-chartjs" class="height-75"></canvas>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>-->
<!--<div class="row">
	<div class="col-md-12">
		<img class="img-responsive" src="assets/images/banner.gif" width="100%" />
	</div>
</div>-->
<br />

<!-- Trade History & Place Order -->
<div class="row">
	<div class="col-12 col-xl-6">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">ACCOUNT INFO </h4>
				<a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
			</div>
			<div class="card-content">
				<div class="table-responsive mt-1">
					<table class="table table-striped table-bordered">
						<tr>
							<th><i class="la la-user"></i> Username</th>
							<td> <?=$user_name?></td>
						</tr>
						<tr>
							<th><i class="la la-envelope"></i> Email </th>
							<td colspan="3"> <?=$email?></td>
							
						</tr>
						<tr>
							<th><i class="la la-usd"></i> Buy Package</th>
							<td><?=get_user_active_investment_confirm($login_id);?> &#36;</td>
						</tr>
						<tr>
							<th><i class="la la-calendar"></i> Registration  Date</th>
							<td><?=$date_join;?></td>
						</tr>
						<tr>
							<th><i class="la la-calendar"></i> Last Access  : </th>
							<td><?=$_SESSION['mlmproject_user_lastaccess']?>&nbsp;&nbsp;</td>
						</tr>
						
						<tr>
							<td>
								<a href="index.php?page=edit-profile" class="btn round btn-warning btn-block">
									<i class="la la-edit m-right-xs"></i> Edit Info
								</a>
							</td>
							<td class="text-right">
								<a href="index.php?page=user-profile" class="btn round btn btn-danger btn-block">
									<i class="la la-refresh"></i> View Profile
								</a>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</div>
	
		<div class="col-12 col-xl-6">
			<div class="card">
				<div class="card-content">
					<div class="table-responsive mt-1">
						<table class="table table-striped table-bordered">
							<tr>
								<th><a href="index.php?page=deposit_wallet"><button type="button" class="btn btn-warning btn-block">Deposit Wallet</button></a></td>
							</tr>
							<tr>	
								<th><a href="index.php?page=activation_company_wallet"><button type="button" class="btn btn-danger btn-block">Buy Package</button></a></td>
							</tr>
							<tr>
								<th><a href="index.php?page=request-fund-transfer"><button type="button" class="btn btn-warning btn-block">Withdrawal Request</button></a></td>
								
							</tr>
							<tr>
								<th><a href="index.php?page=direct_mem_report"><button type="button" class="btn btn-success btn-block">Direct Members</button></a></td>
							</tr>
							<tr>
								<th><a href="index.php?page=support"><button type="button" class="btn btn-warning btn-block">Support Ticket</button></a></td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>
	
	<!--<div class="col-12 col-xl-6">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">Support Ticket</h4>
				<a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
			</div>
			<div class="card-content">
				<div class="table-responsive mt-1">
					<?php
					if(isset($_REQUEST['Submit']))
					{
						if($_REQUEST['catg_id'] != '' && $_REQUEST['my_ticket'] != '' && $_REQUEST['comment'] != '')
						{
							if(isset($_REQUEST["captcha"]) && $_REQUEST["captcha"] != "" && $_SESSION["code"] ==$_REQUEST["captcha"])
							{ ?>
								<form id="myform" action="index.php?page=support" method="post">
									<input type="hidden" name="catg_id" value="<?=$_REQUEST['catg_id']?>" />
									<input type="hidden" name="my_ticket" value="<?=$_REQUEST['my_ticket']?>" />
									<input type="hidden" name="comment" value="<?=$_REQUEST['comment']?>" />
									<input type="hidden" name="captcha" value="<?=$_REQUEST['captcha']?>" />
									<input type="hidden" name="Submit" class="btn btn-info" value="Create" />
								</form>
								<script type="text/javascript">document.getElementById("myform").submit();</script>
								<?php
							}
							else{ $error = "<B class='text-danger'>Please enter correct captcha code!</B>"; }
						}
						else{ $error = "<B class='text-danger'>Please fill all field!</B>"; }
					} ?>
					<?=$error?>
				
				
					<table class="table table-bordered table-hover">
					<form action="" method="post" class="form-horizontal"
						<tr>
							<th>Category:</th>
							<td colspan="2"><select name="catg_id" class="form-control">
									<option value="">Category</option>
									<?php
									$sql = "select * from my_ticket_categry";
									$query = query_execute_sqli($sql);
									while($row = mysqli_fetch_array($query))
									{  
										$id = $row['id'];
										$category = $row['category']; ?>
										<option value="<?=$id;?>"><?=$category;?></option>
									<?php
									}
									?>
								</select>
							</td>
						</tr>
						<tr>
							<th>Subject:</th>
							<td colspan="2"><input type="text" name="my_ticket" class="form-control" /></td>
						</tr>
						<tr>
							<th>Message:</th>
							<td colspan="2"><textarea name="comment" cols="20" rows="3" class="form-control"></textarea></td>
						</tr>
						<tr>
							<th>Captcha:</th>
							<td>
								<input type="password" name="captcha" class="form-control" maxlength="5" />
								<div align="right" style="margin-top:-40px;"><img src="captcha.php" /></div>
							</td>
							<td><input type="submit" name="Submit" class="btn btn-info" value="Create" /></td>
						</tr>
					</form>
				</table>
				</div>
			</div>
		</div>
	</div>-->
</div>

<!--<div class="row">
	<div class="col-md-6" style="margin-top:-10px;">
		<div class="btcwdgt-chart" bw-theme="light"></div>
		<script>
		  (function(b,i,t,C,O,I,N) {
			window.addEventListener('load',function() {
			  if(b.getElementById(C))return;
			  I=b.createElement(i),N=b.getElementsByTagName(i)[0];
			  I.src=t;I.id=C;N.parentNode.insertBefore(I, N);
			},false)
		  })(document,'script','https://widgets.bitcoin.com/widget.js','btcwdgt');
		</script>
	</div>
	<div class="col-12  col-xl-6">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">Monthly Revenue</h4>
				<a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
				<div class="heading-elements">
					<ul class="list-inline mb-0">
						<li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
						<li><a data-action="collapse"><i class="ft-minus"></i></a></li>
						<li><a data-action="expand"><i class="ft-maximize"></i></a></li>
						<li><a data-action="close"><i class="ft-x"></i></a></li>
					</ul>
				</div>
			</div>
			<div class="card-content collapse show">
				<div class="card-body chartjs">
					<canvas id="line-chart" height="280"></canvas>
				</div>
			</div>
		</div>
	</div>
</div>-->
