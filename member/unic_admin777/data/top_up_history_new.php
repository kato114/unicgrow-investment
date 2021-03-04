<?php
include('../../security_web_validation.php');
?>
<?php
include("../function/functions.php");
$date = $systems_date;
$set = ' order by date asc ';

if(isset($_POST['Submit']))
{

$result = query_execute_sqli("select  t1.by_user , t2.f_name , t2.l_name , t2.phone_no , t2.username, t1.date, t1.update_fees, t3.epin from reg_fees_structure as t1 inner join users as t2 on t1.user_id = t2.id_user inner join e_pin as t3 on t2.id_user = t3.used_id and t1.date <= '$date' and t1.end_date >= '$date'  group by t3.used_id order by t1.date asc ");
	$num = mysqli_num_rows($result);
	if($num > 0)
	{
		$file_name = time()."Daily_income".date('Y-m-d');
		//define separator (defines columns in excel & tabs in word)
		$sep = "\t"; //tabbed character
		$fp = fopen('mlm_user excel files/'.$file_name.'.xls', "w");
		$schema_insert = "";
		$schema_insert_rows = "";
		//start of printing column names as names of MySQL fields
		
		
		//start of adding column names as names of MySQL fields
		for ($i = 0; $i < mysqli_num_fields($result); $i++)
		{
		$schema_insert_rows.=mysqli_field_name($result,$i) . "\t";
		}
		$schema_insert_rows.="\n";
		//echo $schema_insert_rows;
		fwrite($fp, $schema_insert_rows);
		//end of adding column names
		
		
		//start while loop to get data
		while($row = mysqli_fetch_row($result))
		{
		//set_time_limit(60); //
			$schema_insert = "";
			for($j=0; $j<mysqli_num_fields($result);$j++)
			{
				if(!isset($row[$j]))
				$schema_insert .= "NULL".$sep;
				elseif ($row[$j] != "")
				$schema_insert .= strip_tags("$row[$j]").$sep;
				else
				$schema_insert .= "".$sep;
			}
			$schema_insert = str_replace($sep."$", "", $schema_insert);
			
			//this corrects output in excel when table fields contain \n or \r
			//these two characters are now replaced with a space
			
			$schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
			$schema_insert .= "\n";
			//$schema_insert = (trim($schema_insert));
			//print $schema_insert .= "\n";
			//print "\n";
			
		
			fwrite($fp, $schema_insert);
		}
		fclose($fp);
		
		print "Excel File Created Successfully !!<br><br><br>";
		
	}	
	else
	{
		print "There is No users to write !<br><br><br>";
	}
	
	?>
		<p><a style="color:#333368; font-weight:600;" href="index.php?page=show_daily_income">Back</a></p>
	click here for download file = <a href="mlm_user excel files/<?php print $file_name;?>.xls"><?php print $file_name; ?></a>

<?php
}

elseif(isset($_POST['date_search']))
{
	$date = $_POST['date'];
	$set = " where date = '$date' order by date asc ";
	$sql = "select t1.by_user,  t2.f_name , t2.l_name , t2.phone_no , t2.username, t1.date, t1.update_fees, t3.epin from reg_fees_structure as t1 inner join users as t2 on t1.user_id = t2.id_user inner join e_pin as t3 on t2.id_user = t3.used_id and t1.date <= '$date' and t1.end_date >= '$date' and t1.date = '$date'  group by t3.used_id order by t1.date asc ";
}

else
{
	$sql = '';
	//$sql = "select t1.by_user , t2.f_name , t2.l_name , t2.phone_no , t2.username, t1.date, t1.update_fees, t3.epin from reg_fees_structure as t1 inner join users as t2 on t1.user_id = t2.id_user inner join e_pin as t3 on t2.id_user = t3.used_id and t1.date <= '$date' and t1.end_date >= '$date'  group by t3.used_id order by t1.date asc ";
}

$query = query_execute_sqli($sql);
$num = mysqli_num_rows($query);
?>	
<table border="0" width="90%">
 <tr>
  <th>
	<?php if($num > 0){?>
	<form method="post" action="index.php?page=top_up_history_new">
		<table cellpadding="0" cellspacing="0" width="90%">
			<tr>
				<th><font size="+1" > Click Here For Export </font></th>
				<th align="center">
					<input type="submit" value="Export" name="Submit" class="button3" style="cursor:pointer">		
				</th>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
		  </tr>
		</table>
	</form>	
<?php	}?>
  </th>
  <th align="right">
	 <form method="post" action="index.php?page=top_up_history_new">
		<table cellpadding="0" cellspacing="5" width="20%">
		  <tr height="120px">
				<th><p style="padding:2px">Date </p></th>
				<th>
					<input type="text" name="date" placeholder="Insert Date" class="input-medium flexy_datepicker_input">
				</th>
			
				<th colspan="2" align="center">
					<input type="submit" value="Submit" name="date_search" class="button3" style="cursor:pointer">		
				</th>
			</tr>
		</table>
	</form>
  </th>
 </tr>
</table>

<?php	
	if($sql != '')
	{
		if($num > 0)
		{
			$que = query_execute_sqli("select sum(update_fees) from reg_fees_structure $set ");
			$rows = mysqli_fetch_array($que);
			$total_commit = $rows['sum(update_fees)'];
	
			print "<table border=0 width=92%>
						<tr>
							<th class=\"message tip\"><p style=\"padding:4px;\">Total Commitment</p></th>
							<th class=\"message tip\"><p style=\"padding:4px;\">$total_commit</p></th>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
					</table>";
							
			print "<table width=92%>
					<tr>
						<th class=\"message tip\">
							<p style=\"padding:5px;\">SR</p>
						</th>
					
						<th class=\"message tip\">
							<p style=\"padding:5px;\">Username</p>
						</th>
						
						<th class=\"message tip\">
							<p style=\"padding:5px;\">Name</p>
						</th>
						
						<th class=\"message tip\">
							<p style=\"padding:5px;\">Mobile No.</p>
						</th>
						
						<th class=\"message tip\">
							<p style=\"padding:5px;\">Epin</p>
						</th>
						
						<th class=\"message tip\">
							<p style=\"padding:5px;\">Amount</p>
						</th>
						
						<th class=\"message tip\">
							<p style=\"padding:5px;\">Date</p>
						</th>
						
						<th class=\"message tip\">
							<p style=\"padding:5px;\">By User</p>
						</th>
					  </tr>";
			$sr = 1;
			while($row = mysqli_fetch_array($query))
			{
				$username = $row['username'];
				$f_name = $row['f_name'];
				$l_name = $row['l_name'];
				$name = $f_name." ".$l_name;
				$amount = $row['update_fees'];
				$phone = $row['phone_no'];
				$date = $row['date'];
				$epin = $row['epin'];
				$by_user = $row['by_user'];
	
				if($by_user == 0)
					$by_user = "No Information";				
				else
					$by_user = get_user_name($by_user);
					
				print "<tr>
						<th class=\"input-medium\">
							<p style=\"padding:5px;\"><small>$sr</small></p>
						</th>
					
						<th class=\"input-medium\">
							<p style=\"padding:5px;\"><small>$username</small></p>
						</th>
						
						<th class=\"input-medium\">
							<p style=\"padding:5px;\"><small>$name</small></p>
						</th>
						
						<th class=\"input-medium\">
							<p style=\"padding:5px;\"><small>$phone</small></p>
						</th>
						
						<th class=\"input-medium\">
							<p style=\"padding:5px;\"><small>$epin</small></p>
						</th>
						
						<th class=\"input-medium\">
							<p style=\"padding:5px;\"><small>$amount</small></p>
						</th>
						
						<th class=\"input-medium\">
							<p style=\"padding:5px;\"><small>$date</small></p>
						</th>
						
						<th class=\"input-medium\">
							<p style=\"padding:5px;\"><small>$by_user</small></p>
						</th>
					  </tr>";
				$sr++;	  
			}
			print "</table>";			
		}		
		else{print "<br /><font style=\"color:#FF0000\">There Are No Top Up</font>";}
	}
	else{print "<br /><font style=\"color:#FF0000\">Please Select Date First</font>";}	
?>
