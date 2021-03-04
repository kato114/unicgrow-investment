<?php
include('../../security_web_validation.php');
?>
<?php
include("condition.php");
//include("../../config.php");
	$result = query_execute_sqli("select f_name , l_name , email from users group by email ");
	$num = mysqli_num_rows($result);
	if($num > 0)
	{
		$file_name = time()."users_email_info".date('Y-m-d');
		//define separator (defines columns in excel & tabs in word)
		$sep = "\t"; //tabbed character
		$fp = fopen('../mlm_user excel files/'.$file_name.'.xls', "w");
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
	<p><a style="color:#333368; font-weight:600;" href="index.php?page=projects_summary">Back</a></p>
	click here for download file = <a href="mlm_user excel files/<?=$file_name;?>.xls"><?=$file_name; ?></a>
		
