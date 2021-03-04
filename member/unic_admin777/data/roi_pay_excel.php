<?php
include('../../security_web_validation.php');
?>
<?php
session_start();
include("condition.php");
include("../function/functions.php");
include("../function/setting.php");

	$sql = $_SESSION['sql_payment'];
	$result = query_execute_sqli($sql);
	$num = mysqli_num_rows($result);
	if($num > 0)
	{
		$file_name = time()."Payment_Report".date('Y-m-d');
		//define separator (defines columns in excel & tabs in word)
		$sep = "\t"; //tabbed character
		$fp = fopen('paymet_report_files/'.$file_name.'.xls', "w");
		$schema_insert = "";
		$schema_insert_rows = "";
		//start of printing column names as names of MySQL fields
		
		
		//start of adding column names as names of MySQL fields
	//	$schema_insert_rows = "Transaction Type". "\t";
		/*for ($i = 0; $i < mysqli_num_fields($result); $i++)
		{
			$schema_insert_rows.=strtoupper(str_replace("_"," ",mysqli_field_name($result,$i))) . "\t";
		}*/
		$schema_insert_rows.= "User Id\t";
		$schema_insert_rows.= "Name\t";
		$schema_insert_rows.= "ROI Bonus\t";
		$schema_insert_rows.= "Total Income\t";
		$schema_insert_rows.= "Admin Tax\t";
		$schema_insert_rows.= "TDS Tax\t";
		$schema_insert_rows.= "Net Payble Amount\t";
		$schema_insert_rows.= "Date\t";
		$schema_insert_rows.="\n";
		//echo $schema_insert_rows;
		fwrite($fp, $schema_insert_rows);
		//end of adding column names
		
		//start while loop to get data
		
		while($row = mysqli_fetch_row($result))
		{
		//set_time_limit(60); //
			
			$schema_insert = "";
		//	$schema_insert = strtoupper("r")."\t";
			/*for($j=0; $j<mysqli_num_fields($result);$j++)
			{
				if($j == 3)
				{	$schema_insert .= strtoupper("AC - ".strip_tags("$row[$j]").$sep);	}
				elseif($j == 5)
				{	$schema_insert .= strtoupper(strip_tags("$row[$j]").$sep);	}
				else
				{
					if(!isset($row[$j]))
					$schema_insert .= strtoupper("NULL".$sep);
					elseif ($row[$j] != "")
					$schema_insert .= strtoupper(strip_tags("$row[$j]").$sep);
					else
					$schema_insert .= strtoupper("".$sep);
				}

			}
			$schema_insert = (str_replace($sep."$", "", $schema_insert));*/
			$u_id = $row[0];
			$username = get_user_name($u_id);
			$name = get_full_name($u_id);
			$wall_bal = wallet_balance($u_id);
			$start_date = $row[1];
			$end_date = $row[2];
			$roi_bonus = $row[3];
			$tot_bonus = $row[4];
						
			$admin_tds_anount = $tot_bonus*($setting_withdrawal_tax/100);
			$admin_tax_anount = $tot_bonus*($setting_admin_tax/100);
			$left_amnt = $tot_bonus-($admin_tax_anount+$admin_tds_anount);			
			
			//this corrects output in excel when table fields contain \n or \r
			//these two characters are now replaced with a space
			$schema_insert .= strtoupper(strip_tags($username).$sep);
			$schema_insert .= strtoupper(strip_tags($name).$sep);
			$schema_insert .= strtoupper(strip_tags($roi_bonus).$sep);
			$schema_insert .= strtoupper(strip_tags($tot_bonus).$sep);
			$schema_insert .= strtoupper(strip_tags($admin_tax_anount).$sep);
			$schema_insert .= strtoupper(strip_tags($admin_tds_anount).$sep);
			$schema_insert .= strtoupper(strip_tags($left_amnt).$sep);
			$schema_insert .= strtoupper(strip_tags($start_date." To ".$end_date).$sep);
			
			$schema_insert = (str_replace($sep."$", "", $schema_insert));
			
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
		<p><a style="color:#333368; font-weight:600;" href="index.php?page=roi_pay">Back</a></p>
	click here for download file = <a href="paymet_report_files/<?=$file_name;?>.xls"><?=$file_name; ?></a>	
