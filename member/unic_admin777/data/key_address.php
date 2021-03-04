<?php
include('../../security_web_validation.php');
?>
<?php
include("../function/functions.php");
$newp = $_GET['p'];
$plimit = "25";
?>
<form method="post" action="">
<table cellpadding="0" cellspacing="0" width="66%" align="right">
	<tr>
		<th><input type="text" name="public_key" placeholder="Search By Public Key" class="form-control"></th>
		<th><input type="text" name="private_key" placeholder="Search By Private Key" class="form-control"></th>
		<th><input type="submit" value="Submit" name="Search" class="btn btn-info"></th>
	</tr>
</table>
</form>	<br /><br />

<?php
$qur_set_search = '';
if((isset($_POST['Search'])) or ((isset($newp)) and (isset($_POST['public_key'])) or (isset($_POST['private_key']))))
{
	$public_key = $_POST['public_key'];
	$private_key = $_POST['private_key'];
	
	if(!isset($newp))
	{
		if($public_key !='' and $private_key == '')
		{
			if($public_key == 0){ echo "<B style='color:#FF0000; '>Please enter correct Key !</B>"; } 
			else
			{
				$_SESSION['session_public_key'] = $public_key;
				$qur_set_search = " where btc_publickey = '$public_key' ";
			}	
		}
		if($private_key !='' and $public_key == '')
		{
			if($private_key == 0){ echo "<B style='color:#FF0000; '>Please enter correct Key !</B>"; } 
			else
			{
				$qur_set_search = " where btc_privatekey = '$private_key' ";
			}
		}
	}
	else
	{	
		if(isset($_SESSION['session_public_key']))
		$public_key = $_SESSION['session_public_key'];
		if($public_key > 0)
		{
			$newp = '';
			$qur_set_search = " where btc_privatekey = '$private_key' ";
		}
	}	
}
else
{
	unset($_SESSION['session_public_key']);
}


$SQL = "select * from users $qur_set_search";
$q = query_execute_sqli($SQL);
$totalrows = mysqli_num_rows($q);

if($totalrows > 0)
{ ?>	
	<table align="center" hspace=0 cellspacing=0 cellpadding=0 border=0 width=100%>
		<tr>
			<th class="text-center" width="7%">Sr. No.</th>
			<th class="text-center" width="10%">User ID</th>
			<th class="text-center" width="29%">BTC Address</th>
			<th class="text-center">Key</th>
		</tr>
	<?php		
	$pnums = ceil ($totalrows/$plimit);
	if ($newp==''){ $newp='1'; }
		
	$start = ($newp-1) * $plimit;
	$starting_no = $start + 1;
	$sr_no = $starting_no;
	$query = query_execute_sqli("$SQL LIMIT $start,$plimit ");		
	while($row = mysqli_fetch_array($query))
	{
		$user_id = $row['username'];
		$name = ucfirst($row['f_name'])." ".ucfirst($row['l_name']);
		$btc_address = $row['btc_address'];
		$publickey = $row['btc_publickey'];
		$privatekey = $row['btc_privatekey'];
		$date = date('d/m/Y H:i:s', strtotime($row['date']));
		
		?>
		<tr align="center">
			<td class="input-small"><small><?=$sr_no?></small></td>
			<td class="input-small"><small><?=$user_id?></small></td>
			<td style="text-align:left;"><small><?=$btc_address?> </small></td>
			<td style="text-align:left;">
				Public Key - <small><?=$publickey?> </small><br />
				Private Key - <small><?=$privatekey?></small>
			</td>
		</tr> <?php
		$sr_no++;
	} ?>
		<tr><td colspan=7>&nbsp;</td></tr>
		<tr>
			<td colspan=7 height=30 class="text-center">
				<?php
				if($newp > 1)
				{ ?> <a href="<?="index.php?page=btc_investment&p=".($newp-1);?>">&laquo;</a> <?php }
				for($i = 1; $i <= $pnums; $i++) 
				{ 
					if ($i != $newp)
					{ ?> <a href="<?="index.php?page=btc_investment&p=$i";?>"><?php print_r("$i");?></a><?php }
					else
					{ print_r("$i"); }
				} 
				if ($newp < $pnums) 
				{ ?> <a href="<?="index.php?page=btc_investment&p=".($newp+1);?>">&raquo;</a> <?php } ?>
			</td>
		</tr>
	</table>
<?php
}
else 
{  echo "<B style='color:#FF0000; font-size:16px;'>There are no information to show !!</B>"; }
?>