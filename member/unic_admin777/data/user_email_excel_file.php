<?php
include('../../security_web_validation.php');
?>
<?php
session_start();
include("condition.php");
include("../function/setting.php");
include("../function/functions.php");
	
	$sqli = "select * from users ";
	
	$sql = $sqli;
	$result = query_execute_sqli($sql);
	$num = mysqli_num_rows($result);
	if($num > 0)
	{
		$file_name = time()."User_information".date('Y-m-d');
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
				if($j == 8)
				{ $schema_insert .= strtoupper("AC - ".strip_tags("$row[$j]").$sep); }
				elseif($j == 11)
				{
					if($row[$j] > 0)
					$schema_insert .= strtoupper(strip_tags("Paid").$sep);
					else
					$schema_insert .= strtoupper(strip_tags("Unpaid").$sep);
				}
				elseif($j == 3)
				{ $schema_insert .= strtoupper(strip_tags(get_user_name($row[$j])).$sep); }
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
		print "<span style=\"color:red; font-weight:600;\">Excel File Created Successfully !!</span><br><br><br>";
	}	
	else
	{ print "There is No users to write !<br><br><br>"; }
	
	?>
	<p><a style="color:#000; font-weight:600;" href="index.php?page=user_email">Back</a></p>
	<span style="color:#000; font-size:14pt;">click here for download file </span>= <a href="mlm_user excel files/<?=$file_name;?>.xls"><?=$file_name; ?></a>	
