<?php
include('../security_web_validation.php');
?>
<?php
include("condition.php");

$user_id = $_SESSION['mlmproject_user_id'];
?>
<table class="table table-bordered table-hover">
	<thead>
		<tr>
			<th class="text-center">Bank Histroy</th>
			<th class="text-center">Roi Histroy</th>
		</tr>
	</thead>
	<tr>
		<td width="50%">
		<table width="100%" border="0">
		<?php
		$sql = "SELECT * FROM income WHERE user_id = '$user_id' AND type != 2 ORDER BY id DESC";
		$query = query_execute_sqli($sql);
		$num1 = mysqli_num_rows($query);
		$tot = 0;
		if($num1 > 0){ ?>
			<tr>
				<td class="text-center">S.No.</td>
				<td class="text-center">Name</td>
				<td class="text-center">Amount</td>
				<td class="text-center">Date</td>
				<td class="text-center">Action</td>
			</tr>
			<?php
			$i = 0;
			while($row = mysqli_fetch_array($query)){
				$giver = $row['incomed_id'];
				$date = $row['date'];
				$amount = $row['amount'];
				$tot = $tot + $amount;
				$j = $i+1;
				$name =  get_user_name($giver);
				if($giver == 0)
				$name = "Admin";
				if($i > 4) continue;
				?>
				<tr class="text-center">
					<td><?=$j?></td>
					<td><?=$name?></td>
					<td><?=$amount?></td>
					<td><?=$date?></td>
					<td>Transfer To Bank</td>
				</tr> <?php
				$i++;
			} ?>
			<tr>
				<td colspan="2">Total</td>
				<td colspan="2"><?=$tot?></td>
				<td class="text-center">
					<form action="index.php?page=more&type=bank_history" method="post" target="_blank">
						<input type="submit" name="submit" value="More" class="btn btn-primary" />
					</form>
				</td>
			</tr> <?php
		}
		else{ echo "<B class='text-danger'>There Are No History !!</B>"; }
		?>
		</table>
		</td>
		<td width="50%">
			<table border="0" width="100%">
			<?php
			$sql = "SELECT * FROM income WHERE user_id = '$user_id' AND type = 2 ORDER BY id DESC";
			$query = query_execute_sqli($sql);
			$num1 = mysqli_num_rows($query);
			$tot = 0;
			if($num1 > 0){	
				$i = 0;
				?>
				<tr>
					<td class="text-center">S.No.</td>
					<td class="text-center">Name</td>
					<td class="text-center">Amount</td>
					<td class="text-center">Date</td>
					<td class="text-center">Action</td>
				</tr>
				<?php
				while($row = mysqli_fetch_array($query)){
					$giver = $row['incomed_id'];
					$date = $row['date'];
					$amount = $row['amount'];
					$tot = $tot + $amount;
					$name = get_user_name($giver);
					if($giver == 0)
					$name = "Admin";
					$j = $i+1;
					if($i > 4)continue;
					?>
					<tr class="text-center">
						<td><?=$j?></td>
						<td><?=$name?></td>
						<td><?=$amount?></td>
						<td><?=$date?></td>
						<td>Transfer To Bank</td>
					</tr> <?php
					$i++;
				} ?>
				<tr>
					<td colspan="2">Total</td>
					<td colspan="2"><?=$tot?></td>
					<td class="text-center">
						<form action="index.php?page=more&type=roi_history" method="post" target="_blank">
							<input type="submit" name="submit" value="More" class="btn btn-primary" />
						</form>
					</td>
				</tr> <?php
			}
			else{ echo "<B class='text-danger'>There Are No History !!</B>"; }
			?>
			</table>
		</td>
	</tr>
</table>