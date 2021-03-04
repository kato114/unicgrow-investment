<?php
include('../security_web_validation.php');

include("condition.php");
include("function/setting.php");
include("function/e_pin.php");
include("function/send_mail.php");

$id = $login_id = $_SESSION['mlmproject_user_id'];


$allowedfiletypes = array("jpeg","jpg","png","gif");
$uploadfolder = $epin_receipt_folder;
$thumbnailheight = 100; //in pixels
$thumbnailfolder = $uploadfolder."thumbs/" ;
?>
<script type='text/javascript' src='js/new_jquery.js'></script>
<script type="text/javascript">
$(document).ready(function(){
	$(".box").hide();
	$(".neft").show();
	
	var select = document.getElementById("inv_mode1");
	select.onchange = function(){
		
		var selected_val = select.options[select.selectedIndex].value;
		//alert(selected_val)
		if(selected_val == "neft" || selected_val == "imps" || selected_val == "rtgs"){
			$(".box").hide();
			$(".neft").show();
			$("#pay_mode1").val($(this).attr("value"));
		}
		if(selected_val == "cash"){
			$(".box").hide();
			$(".cash").show();
			$("#pay_mode1").val($(this).attr("value"));
		}
		if(selected_val == "cheque"){
			$(".box").hide();
			$(".cheque").show();
			$("#pay_mode1").val($(this).attr("value"));
		}
		if(selected_val == "demanddraft"){
			$(".box").hide();
			$(".ddraft").show();
			$("#pay_mode1").val($(this).attr("value"));
		}
		if(selected_val == "phonepay" || selected_val == "paytm"){
			$(".box").hide();
			$(".other").show();
			$("#pay_mode1").val($(this).attr("value"));
		}
	}
	
	/*$('input[type="radio"]').click(function(){
		if($(this).attr("value")=="neft"){
			$(".box").hide();
			$(".neft").show();
			$("#pay_mode1").val($(this).attr("value"));
		}
		if($(this).attr("value")=="imps"){
			$(".box").hide();
			$(".neft").show();
			$("#pay_mode1").val($(this).attr("value"));
		}
		if($(this).attr("value")=="rtgs"){
			$(".box").hide();
			$(".neft").show();
			$("#pay_mode1").val($(this).attr("value"));
		}
		if($(this).attr("value")=="cash"){
			$(".box").hide();
			$(".cash").show();
			$("#pay_mode1").val($(this).attr("value"));
		}
		if($(this).attr("value")=="cheque"){
			$(".box").hide();
			$(".cheque").show();
			$("#pay_mode1").val($(this).attr("value"));
		}
	});*/
});
</script>
<script>
$( document ).ready(function() {
	$(".epin_amt").val("");
	$(".epin_qunty").keyup(function() {
		var list = [<?=implode(",",$set_amount);?>];
		var value = $(this).val();
		var sq = $(".plan").val();
		$(".epin_amt").val(value*list[sq-1]);
	});
});

$( document ).ready(function() {
	$(".plan").change(function() {
		$(".epin_amt").val("");
		$(".epin_qunty").val("");
	});
});
</script>
<style type="text/css">
    .box{ display: none; }
    .cash{ background: none; }
    .neft{ background: none; }
</style>
<?php
if(isset($_SESSION['epin_success'])){
	echo $_SESSION['epin_success'];
	unset($_SESSION['epin_success']);
}
if(isset($_POST['submit']))
{
	$request_amount = $_POST['request_amount'];
	$trans_no = $_POST['trans_no'];
	$receipt = $_POST['receipt'];
	$remarks = $_POST['remarks'];
	$pay_mode = $_POST['payment_mode'];
	$company_ac = $_POST['company_ac'];
	$chq_no = $_POST['chq_no'];
	$bank_name = $_POST['bank_name'];
	
	$receipt = $_POST['receipt'];
	$unique_time = time();
	$unique_name =	"TR".$unique_time;
	$uploadfilename = $_FILES['receipt']['name'];
	
	
	
	if(get_user_refrence_no($trans_no) == 0)
	{
		if($request_amount == 0){ echo "<B class='text-danger'>Please Input Valid Number</B>"; }
		else
		{
			$amount = $request_amount;
			if($pay_mode == 'neft' or $pay_mode == 'rtgs' or $pay_mode == 'imps'){
				if(get_epin_request_trans_password($trans_no) > 0 ){
					echo "<B class='text-danger'>Please Enter Correct Transaction No. ! </B>";
					$pass_stage = 0;
				}
				else{
					$pass_stage = 1;
				}
			}
			else{
				$pass_stage = 1;
			}
			if($pass_stage == 1){
				if(!empty($_FILES['receipt']['name']))
				{
					$fileext = strtolower(substr($uploadfilename,strrpos($uploadfilename,".")+1));
					if (!in_array($fileext,$allowedfiletypes)) 
					{ echo "<B class='text-danger'>Invalid Extension</B>"; }
					else 
					{
						$fulluploadfilename = $uploadfolder.$unique_name.".".$fileext;
						if (copy($_FILES['receipt']['tmp_name'], $fulluploadfilename))
						{ $receipt = $unique_name.".".$fileext; }
					}
				}
				
				$pay_mode = array_search($pay_mode,$add_fund_mode);
				$sql = "INSERT INTO fund_request (user_id , amount, date, paid, plan, payment_mode, transaction_no, 
				company_ac, chq_no , receipt , bank_name , remarks , mode) 
				VALUES ('$login_id' , '$amount', '$systems_date', '0' , '$pay_mode' , '$pay_mode' , '$trans_no' ,
				 '$company_ac' , '$chq_no' , '$receipt' , '$bank_name' , '$remarks' , 1)";
				query_execute_sqli($sql);
				$_SESSION['epin_success']="<B class='text-success'>Request For Add Fund Successfully Sent To Admin!</B>";
				if($soft_chk == 'LIVE'){
					//Fund add message
					include("email_letter/deposti_fund_msg.php");
					$to = get_user_email($login_id);
					//include("function/full_message.php");
					$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from_email, $to,
					$title, $db_msg);
				}
				?> <script>window.location ="index.php?page=request_add_fund";</script> <?php 
			}
		}
	}
	else{ echo "<B class='text-danger'>Refrence no already exist ! </B>"; }
}
?>
<form method="post" action="" enctype="multipart/form-data">
<input type="hidden" name="pay_mode" id="pay_mode1" />
<table class="table table-bordered table-hover">
	<!--<tr>
		<th width="30%">Payment Mode </th>
		<td>
			<div class="radio">
				<label><input type="radio" id="inv_mode" name="payment_mode" value="<?=$add_fund_mode[0]?>" /><B><?=$add_fund_mode_value[0]?></B></label>
				<label><input type="radio" id="inv_mode" name="payment_mode" value="<?=$add_fund_mode[1]?>" /><B><?=$add_fund_mode_value[1]?></B></label>
				<label><input type="radio" id="inv_mode" name="payment_mode" value="<?=$add_fund_mode[2]?>" /><B><?=$add_fund_mode_value[2]?></B></label>
				<label><input type="radio" id="inv_mode" name="payment_mode" value="<?=$add_fund_mode[3]?>" /><B><?=$add_fund_mode_value[3]?></B></label>
				<label><input type="radio" id="inv_mode" name="payment_mode" value="<?=$add_fund_mode[4]?>" /><B><?=$add_fund_mode_value[4]?></B></label>
				<label><input type="radio" id="inv_mode" name="payment_mode" value="<?=$add_fund_mode[5]?>" /><B><?=$add_fund_mode_value[5]?></B></label>
				<label><input type="radio" id="inv_mode" name="payment_mode" value="<?=$add_fund_mode[5]?>" /><B><?=$add_fund_mode_value[6]?></B></label>
				<label><input type="radio" id="inv_mode" name="payment_mode" value="<?=$add_fund_mode[5]?>" /><B><?=$add_fund_mode_value[7]?></B></label>
			</div>
		</td>
	</tr>-->
	<tr>
		<th width="30%">Select Bank </th>
		<td>
			<select name="bank_name" class="form-control" required>
				<option value="">Select Bank</option>
				<?php
				for($i = 0; $i < count($bank_name_for_fund); $i++){ ?>
					<option value="<?=$bank_name_for_fund[$i]?>"><?=$bank_name_for_fund[$i]?></option> <?php
				} ?>
			</select>
		</td>
	</tr>
	<tr>
		<th width="30%">Payment Mode </th>
		<td>
			<select name="payment_mode" id="inv_mode1" class="form-control" required>
				<option value="">Select Payment Method</option>
				<option value="<?=$add_fund_mode[0]?>"><?=$add_fund_mode_value[0]?></option>
				<option value="<?=$add_fund_mode[1]?>"><?=$add_fund_mode_value[1]?></option>
				<option value="<?=$add_fund_mode[2]?>"><?=$add_fund_mode_value[2]?></option>
				<option value="<?=$add_fund_mode[3]?>"><?=$add_fund_mode_value[3]?></option>
				<option value="<?=$add_fund_mode[4]?>"><?=$add_fund_mode_value[4]?></option>
				<option value="<?=$add_fund_mode[5]?>"><?=$add_fund_mode_value[5]?></option>
				<option value="<?=$add_fund_mode[6]?>"><?=$add_fund_mode_value[6]?></option>
				<option value="<?=$add_fund_mode[7]?>"><?=$add_fund_mode_value[7]?></option>
			</select>
		</td>
	</tr>
	<tr>
		<th>Request Amount</th>
		<td><input type="text" name="request_amount" class="form-control epin_qunty" required /></td>
	</tr>
	
	<tr class="cheque box">
		<th>Cheque No.</th>
		<td><input type="text" name="chq_no" class="form-control" /></td>
	</tr>
	<tr class="neft ddraft other box">
		<th>Reference No.</th>
		<td><input type="text" name="trans_no" class="form-control" /></td>
	</tr>
	<!--<tr>
		<th>Company A/C</th>
		<td><input type="text" name="company_ac" class="form-control" required /></td>
	</tr>-->
	<tr class="neft ddraft cheque box">
		<th>Receipt</th>
		<td><input type="file" name="receipt" /></td>
	</tr>
	<tr>
		<th>Remarks</th>
		<td><textarea name="remarks" class="form-control"></textarea></td>
	</tr>
	<!--<tr>
		<th>Transaction Password</th>
		<td><input type="password" name="tr_pass" class="form-control" /></td>
	</tr>-->
	<tr>
		<td colspan="2" class="text-center">
			<input type="submit" name="submit" value="Submit" class="btn btn-primary" />
		</td>
	</tr>
	
</table>
</form>
<?php
$newp = $_GET['p'];
$plimit = 10;
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;

$sql = "SELECT t1.*,t2.plan_name FROM epin_request t1 
LEFT JOIN plan_setting t2 ON t1.plan = t2.id
WHERE t1.user_id = '$login_id'";
$SQL = "$sql LIMIT $tstart,$tot_p ";
$query = query_execute_sqli($SQL);
$totalrows = mysqli_num_rows($query);

$sqlk = "SELECT COUNT(id) num FROM epin_request WHERE user_id = '$login_id'";
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
			<th class="text-center">Package</th>
			<th class="text-center">Amount</th>
			<th class="text-center">No. Of E-pin </th>
			<th class="text-center">Payment Mode</th>
			<th class="text-center">Date</th>
			<th class="text-center">Remarks</th>
			<th class="text-center">Status</th>
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
			
			$package = $row['plan_name'];
			
			if($tr_no == '' or $tr_no == 0) $tr_no = 'XXXXXX';
			if($chq_no == '') $chq_no = 'XXXXXX';
			
			switch($mode){
				case '0' : $status = "<span class='label label-success'>Approved</span>"; break;
				case '4' : $status = "<span class='label label-danger'>Cancelled</span>"; break;
				case '1' : $status = "<span class='label label-warning'>Pending</span>"; break;
			}
			?>
			<form action="index.php?page=epin_request" method="post">
			<input type="hidden" name="table_id" value="<?=$tbl_id?>" />
			<tr class="text-center">
				<td><?=$sr_no?></td>
				<td class="text-left"><?=$package?></td>
				<td><?=$amount?> &#36;</td>
				<td><?=$pin_no?></td>
				<td><?=$payment_mode?></td>
				<td><?=$date?></td>
				<td><div style="word-wrap: break-word; width:100px"><?=$remarks?></div></td>
				
				<td><?=$status?></td>
			</tr>
			</form> <?php
			$sr_no++;
		} ?>
	</table> <?php
	pagging_initation_last_five($newp,$pnums,$val,$lpnums,$show_tab); 
}

function get_user_refrence_no($trans_no){ 
	$sql = "SELECT * FROM fund_request WHERE transaction_no = '$trans_no' AND paid NOT IN(3)"; 
	$query = query_execute_sqli($sql);
	$num = mysqli_num_rows($query);	
	mysqli_free_result($query);
	if($trans_no == ''){ $nums = 0;}
	else{ $nums = $num; }
	return $nums;
}
 