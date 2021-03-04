<?php
include('../../security_web_validation.php');
?>
<?php
session_start();
include("condition.php");
include("../function/setting.php");
include("../function/functions.php");
include("../function/direct_income.php");
include("../function/check_income_condition.php");
include("../function/pair_point_calc.php");
?>
<script>
$(document).ready(function() {	
	$("#invest_dd").keyup(function (e) {	
		var amt = $(this).val();
		$.post('../selcet_plan.php', {'check_amt':amt},function(data2)
		{
		  	var obj = JSON.parse(data2);
			var genp = obj.p;
			if(genp > 0){
				var deduct = amt*<?=$set_activation_wallet_invest?>/100;			
				$("#genpad").html(deduct);
				$("#genpgd").html(amt-deduct);
				$("#msg_info").html(obj.m);
			}
			else{
				return false;
			}
		});
		
	});	
});	
</script>
<?php
if(isset($_POST['submit']))
{
	if($_POST['submit'] == 'Make Investment')
	{
		$user_id = $id = $_SESSION['admin_make_invst_id'];
	}
	elseif($_POST['submit'] == 'Stop Income'){
		$id = $_POST['mid'];
		$sql = "update users set type='X'where id_user='$id'";
		query_execute_sqli($sql);
		echo "<script type=\"text/javascript\">";
		echo "alert('All Income Stop Successfully !!');window.location = \"index.php?page=stop_income\"";
		echo "</script>";
	}
	elseif($_POST['submit'] == 'Start Income'){
		$id = $_POST['mid'];
		$sql = "update users set type='B'where id_user='$id'";
		query_execute_sqli($sql);
		echo "<script type=\"text/javascript\">";
		echo "alert('Income Start Successfully !!');window.location = \"index.php?page=stop_income\"";
		echo "</script>";
	}
	elseif($_POST['submit'] == 'Submit')
	{
		$u_name = $_REQUEST[user_name];
		$q = query_execute_sqli("select * from users where username = '$u_name' ");
		$num = mysqli_num_rows($q);
		if($num == 0)
		{
			echo "<h3>Please Enter right User Name!</h3>"; 
		}
		else
		{
			while($id_row = mysqli_fetch_array($q))
				$_SESSION['admin_make_invst_id'] = $id = $id_row['id_user'];
		?>
			<table align="center" border="0" width=450>
				<form name="my_form" action="" method="post">
				<input type="hidden" name="mid" value="<?=$id?>" />
				<tr>
					<td><p><input type="submit" name="submit" value="Stop Income"  class="btn btn-info"/></p></td>
					<td><p><input type="submit" name="submit" value="Start Income" class="btn btn-info"/></p></td>
				 </tr>
				  </form>
			</table>
<?php	}
	}
	else { print "there are Some Conflict !"; }
}
else
{ 
unset($_SESSION['make_investment']);
?>	
	<table align="center" border="0" width=450>
<form name="my_form" action="" method="post">
<!--<tr>
    <td colspan="2" class="td_title" style="font-size:16px; color:#CC0000;"><strong>Wallet Information</strong></td>
  	</tr>-->
<tr>
    <td class="td_title"><p>Enter Member UserName</p></td>
    <td><p><input type="text" name="user_name" size=3 class="form-control"/></p></td>
  </tr>
  <tr>
    <td align="center" colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="submit" value="Submit" class="btn btn-info"  /></td>
    
  </tr>
  </form>
</table>
	
<?php } ?>

