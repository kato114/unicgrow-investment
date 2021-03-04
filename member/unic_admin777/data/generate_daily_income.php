<?php
include('../../security_web_validation.php');
?>
<?php
session_start();
include("condition.php");
include("../function/setting.php");
//include("../../config.php");
/* $sql = "SELECT t1.f_name as First_Name, t1.l_name as Last_Name, t1.phone_no as Phone, t1.ac_no as Beneficiary_Account_Number,
		sum(t2.income)-(sum(t2.income)*$daily_roi_tax)/100 AS Instrument_Amount,
		t1.beneficiery_name as Beneficiary_Name, 
		t1.bank_code as IFSC_Code, t1.bank as Bene_Bank_Name, t1.email As Beneficiary_Email,
		t1.username as Id 
		FROM users as t1 
		inner join daily_income as t2
		on t1.id_user = t2.user_id and t2.paid = 0 
		inner join franchise as tt4 on tt4.id = t1.location
		group by t2.user_id,t1.location
		having Instrument_Amount > $minimum_roi_amount";*/
	$sqli	= "select * from(".$_SESSION['sql_transfer_to_franchise'].") as tt";
	$sqli = str_replace('select *','SELECT tt.f_name as First_Name, tt.l_name as Last_Name, tt.phone_no as Phone, 
						tt.ac_no as Beneficiary_Account_Number,
						tt.total_inc AS Instrument_Amount,
						tt.beneficiery_name as Beneficiary_Name, 
						tt.bank_code as IFSC_Code, tt.bank as Bene_Bank_Name, tt.email As Beneficiary_Email,
						tt.username as Id',$sqli);
	$sql = $sqli;
	$result = query_execute_sqli($sql);
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
	//	$schema_insert_rows = "Transaction Type". "\t";
		for ($i = 0; $i < mysqli_num_fields($result); $i++)
		{
				
				$schema_insert_rows.=strtoupper(str_replace("_"," ",mysqli_field_name($result,$i))) . "\t";
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
		//	$schema_insert = strtoupper("r")."\t";
			for($j=0; $j<mysqli_num_fields($result);$j++)
			{
				if($j == 3)
				{
					$schema_insert .= strtoupper("AC - ".strip_tags("$row[$j]").$sep);
				}
				elseif($j == 5)
				{
					$schema_insert .= strtoupper(strip_tags("$row[$j]").$sep);
				}
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
			$schema_insert = (str_replace($sep."$", "", $schema_insert));
			
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
