<?php
include('../security_web_validation.php');
?>
<?php
session_start();
include "../config.php";
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" ); 
header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" ); 
header("Cache-Control: no-cache, must-revalidate" ); 
header("Pragma: no-cache" );
header("Content-Type: text/xml; charset=utf-8");

//////////////////////////////////////
// set these variables for your MySQL
//$dbhost = 'localhost';	// usually localhost
//$dbuser = 'mahendra';		// database username
//$dbpass = 'mahendra123';		// database password
//////////////////////////////////////
$id = $_SESSION['mlmproject_user_id'];
$s_date = $_SESSION['s_date'];
$e_date = $_SESSION['e_date'];
$cal_amount = $_SESSION['cal_amount'];
$sql_reg_per = "select profit from reg_fees_structure where user_id='$id' and start_date='$s_date' and end_date = '$e_date'";
$quer = query_execute_sqli($sql_reg_per);
$row = mysqli_fetch_array($quer);
$profit = $row[0];
$profit_amount = $cal_amount*$profit/100;


//$con=mysqli_connect($db_host,$db_username,$db_password);

/*$con = mysqli_connect($dbhost,$dbuser,$dbpass) or die ("<?xml version=\"1.0\" ?><response><content><![CDATA[<span class='error'>Database connection failed.</span>]]></content></response>");
mysqli_select_db($db,$con);*/

$xml = '<?xml version="1.0" ?><response><content><![CDATA[';

if($_GET['event'] != '') {
	$fields = explode("-",$_GET['event']);
	$result = query_execute_sqli("SELECT *,DATE_FORMAT(`date`,'%b %e, %Y at %l:%i%p') as thedate,DATE_FORMAT(`date`,'%c') as themonth,DATE_FORMAT(`date`,'%Y') as theyear FROM `events` WHERE YEAR(`date`) = ".$fields[0]." AND MONTH(`date`) = ".$fields[1]." AND DAYOFMONTH(`date`) = ".$fields[2]." ORDER BY `num` ASC");
	
	$i = 0;
	while($row = mysqli_fetch_array($result)) {
		$xml .= "<div id='event'";
		if($i < (mysqli_num_rows($result)-1)) $xml .= " style='border-bottom:none'";
		$xml .= "><div class='heading'><div class='title'>".$row['heading']."</div><div class='posted'>".$row['thedate']."</div>";
		if($i == 0) $xml .= "<div class='back'><a href='javascript:navigate(".$row['themonth'].",".$row['theyear'].",\"\")'>Return to calendar</a></div>";
		$xml .= "</div><div class='line'>".$row['body']."</div><br /></div><br />";
		$i++;
	}
	
} else {
	$month = $_GET['month'];
	$year = $_GET['year'];
		
	if($month == '' && $year == '') { 
		$time = time();
		$month = date('n',$time);
	    $year = date('Y',$time);
	}
	
	$date = getdate(mktime(0,0,0,$month,1,$year));
	$today = getdate();
	$hours = $today['hours'];
	$mins = $today['minutes'];
	$secs = $today['seconds'];
	
	if(strlen($hours)<2) $hours="0".$hours;
	if(strlen($mins)<2) $mins="0".$mins;
	if(strlen($secs)<2) $secs="0".$secs;
	
	$days=date("t",mktime(0,0,0,$month,1,$year));
	$start = $date['wday']+1;
	$name = $date['month'];
	$year2 = $date['year'];
	$offset = $days + $start - 1;
	 
	if($month==12) { 
		$next=1; 
		$nexty=$year + 1; 
	} else { 
		$next=$month + 1; 
		$nexty=$year; 
	}
	
	if($month==1) { 
		$prev=12; 
		$prevy=$year - 1; 
	} else { 
		$prev=$month - 1; 
		$prevy=$year; 
	}
	
	if($offset <= 28) $weeks=28; 
	elseif($offset > 35) $weeks = 42; 
	else $weeks = 35; 
	
	$xml .= "<table class='cal' cellpadding='0' cellspacing='1'>
			<tr>
				<td colspan='7' class='calhead'>
					<table>
					<tr>
						<td>
							<a href='javascript:navigate($prev,$prevy,\"\")' style='border:none'><img src='images/calLeft.gif' alt='prev' /></a> <a href='javascript:navigate(\"\",\"\",\"\")' style='border:none'><img src='images/calCenter.gif' alt='current' /></a> <a href='javascript:navigate($next,$nexty,\"\")' style='border:none'><img src='images/calRight.gif' alt='next' /></a> <a href='javascript:void(0)' onClick='showJump(this)' style='border:none'><img src='images/calDown.gif' alt='jump' /></a> 
						</td>
						<td align='right'>
							$name $year2
						</td>
					</tr>
					</table>
				</td>
			</tr>
			<tr class='dayhead'>
				<td>Sun</td>
				<td>Mon</td>
				<td>Tue</td>
				<td>Wed</td>
				<td>Thu</td>
				<td>Fri</td>
				<td>Sat</td>
			</tr>";
	
	$col=1;
	$cur=1;
	$next=0;
	
	for($i=1;$i<=$weeks;$i++) { 
		if($next==3) $next=0;
		if($col==1) $xml.="\n<tr class='dayrow'>"; 
	  	
		$xml.="\t<td valign='top' onMouseOver=\"this.className='dayover'\" onMouseOut=\"this.className='dayout'\">";
	
		if($i <= ($days+($start-1)) && $i >= $start) {
			$xml.="<div class='day'><b";
	
			if(($cur==$today[mday]) && ($name==$today[month]) && ($year2==$today[year])) $xml.=" style='color:#C00'";
			
			$mm = $date['month'];
			$mm = date("m", strtotime($mm));
			$yy = $date['year'];
			$originalDate = "$yy-$mm-$cur";
			$newDate = date("Y-m-d", strtotime($originalDate));
			$days_name = date('D',strtotime($newDate));
			$xml.=">$cur</b></div>"; // paid or unpaid information 
			$p = 1;
			$result = query_execute_sqli("SELECT DATE_FORMAT(`date`,'%Y-%m-%e') FROM `events` WHERE MONTHNAME(`date`)='$name' AND DAYOFMONTH(`date`)=$cur AND YEAR(`date`)=$year2");
			if(mysqli_num_rows($result) > 0) {
			$p = 2;
				$row = mysqli_fetch_row($result);
				/*$xml.="<div class='calevent'><a href='javascript:navigate(\"\",\"\",\"".$row[0]."\")'>
				<b style='color:#C00'>Holiday</b></a></div>";	*/	
				$xml.="<br><div class='calevent'>
				<b style='color:#C00'>Holiday</b></div>";	
			}
			if($p == 1)
			{
					if($days_name == "Sun" or $days_name == "Sat")
					{
						$up = "<br><div class='calevent'><b style='color:#C00'>Holiday</b></div>";
					}
					else
					{
						$newDate1 = $newDate;
						$newDate1=date('Y-m-d', strtotime($newDate1));
						//echo $paymentDate; // echos today! 
						$s_date = date('Y-m-d', strtotime($s_date));
						$e_date = date('Y-m-d', strtotime($e_date));
					
						if (($newDate1 >= $s_date) && ($newDate1 <= $e_date))
						{
							
							$sql = "select * from income where user_id='$id' and date='$newDate' and type=2";
							$query = query_execute_sqli($sql);
							if(mysqli_num_rows($query) > 0) {
								{
									$up = "<br>
											<div class='calevent'>Paid 
												<span style='color:#155202;'>$profit_amount</span>
											</div>";
								}
								
							}
							else{ 
									if($newDate1 < date('Y-m-d')){
									$up = "<br>
											<div class='calevent'><span style='color:#c00;'>N/A</span>
											</div>";
									}
									else{
										
										$up = "<br>
											<div class='calevent'>Unpaid
												<span style='color:#0621f1;'>$profit_amount</span>
											</div>";
									}
									
								}
						}
						else
						$up = "<br>
											<div class='calevent'> 
												<span style='color:#c00;'>N/A</span>
											</div>";
					}
				
				$xml.="$up";
				
			}
			
			$xml.="\n\t</td>\n";
	
			$cur++; 
			$col++; 
			
		} else { 
			$xml.="&nbsp;\n\t</td>\n"; 
			$col++; 
		}  
		    
	    if($col==8) { 
		    $xml.="\n</tr>\n"; 
		    $col=1; 
	    }
	}
	
	$xml.="</table>";
	  
}
	
$xml .= "]]></content></response>";
echo $xml;

?>