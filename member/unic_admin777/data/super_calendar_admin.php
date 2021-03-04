<?php
include('../../security_web_validation.php');
?>
<?php
session_start();
include("condition.php");

//////////////////////////////////////
// add and remove users here
/*$vu = array('admin' => 'pass'); 
//////////////////////////////////////

function doAuth() {
	header('WWW-Authenticate: Basic realm="Protected Area"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Valid username / password required.';
    exit;	
}

function checkUser() {
	global $vu;
	$b = false;
	if($_SERVER['PHP_AUTH_USER']!='' && $_SERVER['PHP_AUTH_PW']!='') {
		if($vu[$_SERVER['PHP_AUTH_USER']] == $_SERVER['PHP_AUTH_PW']) $b = true;
	}
	return $b;
}*/

//if(!checkUser()) doAuth();

//////////////////////////////////////
// set these variables for your MySQL
//$dbhost = 'localhost';	// usually localhost
//$dbuser = 'mahendra';		// database username
//$dbpass = 'mahendra123';		// database password
//////////////////////////////////////

$f = ($_GET['f'] != '')? $_GET['f']:$_POST['f'];
$sf = ($_GET['sf'] != '')? $_GET['sf']:$_POST['sf'];

if($f == 'cal') {
	$output = '';
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
	
	$output .= "
	<table class='cal' cellspacing='1'>
	<tr>
		<td colspan='7' style='padding:0px'>
			<table class='calhead'>
			<tr>
				<td align='left'>
					<a href='javascript:navigate($prev,$prevy)'><img src='../images/calLeft.gif'></a> <a href='javascript:navigate(\"\",\"\")'><img src='../images/calCenter.gif'></a> <a href='javascript:navigate($next,$nexty)'><img src='../images/calRight.gif'></a>
				</td>
				<td align='right'>
					<div>$name $year2</div>
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
		if($col==1) $output.="<tr class='dayrow'>"; 
	  	
		$output.="<td valign='top' onMouseOver=\"this.className='dayover'\" onMouseOut=\"this.className='dayout'\">";
	
		if($i <= ($days+($start-1)) && $i >= $start) {
			$m = ($month<10)? "0".$month:$month;
			$d = ($cur<10)? "0".$cur:$cur;
			$output.="<a href=\"javascript:loadDate('".$year2."-".$m."-".$d."')\" class=\"day\"><b";
	
			if(($cur==$today[mday]) && ($name==$today[month])) $output.=" style='color:#C00'";
	
			$output.=">$cur</b></a></td>";
	
			$cur++; 
			$col++; 
			
		} else { 
			$output.="&nbsp;</td>"; 
			$col++; 
		}  
		    
	    if($col==8) { 
		    $output.="</tr>"; 
		    $col=1; 
	    }
	}

	$output.="</table>";
  
	echo $output;
	exit;
}



?>

<style type="text/css">


#main {
	margin: 0 auto;
	background: #444;
	border: 2px solid #FFF;
	width: 760px;
}

#top, #bottom {
	text-align: left;
	width: 740px;
	padding: 10px;
}

#top {
	position: relative;
	height: 40px;
	line-height: 40px;
	background: transparent url('../images/calIcon.gif') no-repeat 10px 9px;
	font-weight: bold;
	border-bottom: 1px solid #EEE;
}

#top h3 {
	padding: 0;
	margin: 0;
	margin-left: 55px;
}

#top .menu {
	position: absolute;
	top: 10px;
	right: 10px;
	height: 30px;
	line-height: 30px;
	font-size: 12px;
}

#top .menu a {
	font-size: 12px;
	font-weight: bold;
	text-decoration: none;
	color: #FFF;
	padding: 5px;	
}

#top .menu a:hover {
	text-decoration: underline;
}

#bottom {
	background: #676767;
	overflow: auto;
}

#bottom h3 {
	margin-top: 0;
	padding-top: 0;
}

#bottom a {
	color: #FFF;
	font-size: 11px;
	font-weight: bold;
}

#fields {	
	width: 100%;
	height: 100%;
	overflow: auto;
}

.field, #fields div {
	padding-top: 10px;
	clear: both;
}

.label {
	width: 125px;
}

.label, .box {
	float: left;
}

#calendar td {
	padding: 3px;
}

#calwin {
	width: 230px;
	position: absolute;
	top: 100px;
	left: 50%;
	margin-left: -150px;
	display: none;
	background: transparent;
}

.bar {
	text-align: right;
	background: url('../images/calBar.gif') no-repeat top left;
}

.bar img {
	border: none;
}

.bar a {
	margin-right: 5px;
}

#calback {
	width: 100%;
	background: #FFF url('../images/calWaiting.gif') no-repeat center center;
}

#calendar {
	width: 100%;
	height: 100%;
	opacity: 0;
	filter: alpha(opacity=0); 
	-moz-opacity: 0;
}

.cal {
	background: #444;
	width: 100%;
}

.calhead {
	width: 100%;
	font-weight: bold;
	color: #FFF;
	font-size: 14px;
}

.calhead td {
	padding: 0px;
}

.calhead img {
	border: none;
}

.dayhead {
	height: 18px;
	background: #EEE;
}

.dayhead td {
	font-size: 11px;
	text-align: center;
	color: #000;
}

.dayrow {
	background: #FFF;
	height: 20px;
}

.dayrow td {
	width: 14%;
	color: #000;
	font-size: .7em;
	text-align: right;
}

.day {
	text-decoration: none;
	color: #000;
	display: block;
	width: 100%;
	height: 100%;
}

.dayover {
	background: #EEE;
}

.dayout {
	background: #FFF;
}
</style>

<div id="main">
	<div id="top">
		<h3>Calendar</h3>
		<div class="menu">
			<a href="index.php?page=super_calendar_admin&f=new&amp;sf=list">New Event</a> | <a href="index.php?page=super_calendar_admin&f=edit&amp;sf=list">Edit / Delete Event</a>
		</div>
	</div>
	<div id="bottom">
<?php

if($f == '') {
	$f = 'new';
	$sf = 'list';
}


if($f == 'new') {
	echo "<h3>New Event</h3>";
	$time = $_POST['date']." ".$_POST['hour'].":".$_POST['min'].":00";
	
	if($sf == 'save') {	
		$query = "INSERT INTO `events` (`date`,`heading`,`body`) VALUES ('".$time."','".$_POST['heading']."','".$_POST['event']."')";
		query_execute_sqli($query);
		
		if(mysqli_error() != '') $err .= mysqli_errno().": ".mysqli_error()."<br />";
		
		if($err!='') {
			echo "<img src=\"../images/calError.gif\" /> Sorry, a database error occured.<br />".$err;
		} else {
			echo "<img src=\"../images/calSuccess.gif\" /> The event was stored successfully";
		}
		
	} else if($sf == 'list') {
?>	
		<form name="f" method="post" action="index.php?page=super_calendar_admin&f=new&amp;sf=save">
		<div id="fields">
			<div class="field"><span class="label">Date / Time:</span><span class="box"><input type="text" name="date" /> <a href="javascript:showCal('','')"><img src="../images/calFunc.gif" style="border:none" /></a><p style="margin: 4px 0px 0px 0px"><select name="hour">
<?php			
		for($i=0;$i<24;$i++) {
			$out = ($i < 10)? "0".$i:$i;
			echo "<option value=\"".$out."\">".$out."</option>";
		}
?>			
			</select> : <select name="min">
<?php		
		for($i=0;$i<60;$i++) {
			$out = ($i < 10)? "0".$i:$i;
			echo "<option value=\"".$out."\">".$out."</option>";
		}
?>
			</select></p></span></div>
			<div class="field"><span class="label">Heading:</span><span class="box"><input type="text" size="50" name="heading" /></span></div>
			<div class="field"><span class="label">Event:<br /><span style="font-size:9px">(HTML is OK)</span></span><span class="box"><textarea name="event" cols="50" rows="6"></textarea></span></div>
		</div>
		<br />
		<div class="field"><span class="label">&nbsp;</span><span class="box"><input type="submit" value="    Add Event    " /></span></div>
		</form>
<?php
	}
	
} else if($f == 'edit') {
	echo "<h3>Edit / Delete Event</h3>";
	
	if($sf == 'list') {
		$query = "SELECT `num`,`heading`,DATE_FORMAT(`date`,'%m-%d-%Y') as `fdate` FROM `events` ORDER BY `date` DESC";
		$result = query_execute_sqli($query);
		
		while($row = mysqli_fetch_array($result)) {
			$evtlist .= "<option value=\"".$row['num']."\">[ ";
			$evtlist .= $row['fdate']." ] ".substr($row['heading'],0,30);
			if(strlen($row['heading'])>30) $evtlist .= "...";
			$evtlist .= "</option>";
		}
?>
		<form name="f" method="post" action="index.php?page=super_calendar_admin&f=edit&amp;sf=show">
		<div id="fields">
			<div class="field"><span class="label">Event:</span><span class="box"><select name="num"><option value=""></option><?=$evtlist;?></select> <input type="submit" value="    Edit Event    " /></span></div>
		</div>
		</form>
	
<?php
		
	} else if($sf == 'show') {
		$query = "SELECT `num`,`heading`,`body`,DATE_FORMAT(`date`,'%Y-%m-%d') as `date`,DATE_FORMAT(`date`,'%H') as `hour`,DATE_FORMAT(`date`,'%i') as `min` FROM `events` WHERE `num`=".$_POST['num']." LIMIT 1";
		$result = query_execute_sqli($query);
		$row = mysqli_fetch_array($result);		
?>
		<form name="f" method="post" action="index.php?page=super_calendar_admin&f=edit&amp;sf=save">
		<input type="hidden" name="num" value="<?=$row['num'];?>" />
		<img src="../images/calDelete.gif" /> <a href="index.php?page=super_calendar_admin&f=delete&amp;num=<?=$row['num'];?>" onClick="return confirm('Are you sure? This is permanent!')" style="color:#F26343;font-weight:bold">Delete Event</a>
		<div id="fields">
			<div class="field"><span class="label">Date / Time:</span><span class="box"><input type="text" name="date" value="<?=$row['date'];?>" /> <a href="javascript:showCal('','')"><img src="../images/calFunc.gif" style="border:none" /></a><p style="margin: 4px 0px 0px 0px"><select name="hour">
<?php		
		for($i=0;$i<24;$i++) {
			$out = ($i < 10)? "0".$i:$i;
			echo "<option value=\"".$out."\"";
			if($row['hour'] == $out) echo " selected";
			echo ">".$out."</option>";
		}
?>
			</select> : <select name="min">
<?php		
		for($i=0;$i<60;$i++) {
			$out = ($i < 10)? "0".$i:$i;
			echo "<option value=\"".$out."\"";
			if($row['min'] == $out) echo " selected";			
			echo ">".$out."</option>";
		}
?>
			</select></p></span></div>
			<div class="field"><span class="label">Heading:</span><span class="box"><input type="text" size="50" name="heading" value="<?=$row['heading'];?>" /></span></div>
			<div class="field"><span class="label">Event:<br /><span style="font-size:9px">(HTML is OK)</span></span><span class="box"><textarea name="event" cols="50" rows="6"><?=$row['body'];?></textarea></span></div>
		</div>
		<br />
		
		<div class="field"><span class="label">&nbsp;</span><span class="box"><input type="submit" value="    Save Event    " /></span></div>
		</form>

<?php
		
	} else if($sf == 'save') {
		$time = $_POST['date']." ".$_POST['hour'].":".$_POST['min'].":00";		
		$query = "UPDATE `events` SET `date`='".$time."',`heading`='".$_POST['heading']."',`body`='".$_POST['event']."' WHERE `num`=".$_POST['num'];
		query_execute_sqli($query);
		if(mysqli_error() != '') $err .= mysqli_errno().": ".mysqli_error()."<br />";
			
		if($err!='') {
			echo "<img src=\"../images/calError.gif\" /> Sorry, a database error occured.<br />".$err;
		} else {
			$status = ($cur>0)? "currently active.":"not currently active.";
			echo "<img src=\"../images/calSuccess.gif\" /> The event was updated successfully.";
		}
	}
	
} else if($f == 'delete') {
	query_execute_sqli("DELETE FROM `events` WHERE `num`=".$_GET['num']);
	query_execute_sqli("ALTER TABLE `events` auto_increment = 1");
	if(mysqli_error() != '') $err .= mysqli_errno().": ".mysqli_error()."<br />";

	if($err!='') {
			echo "<img src=\"../images/calError.gif\" /> Sorry, a database error occured.<br />".$err;
	} else {
			echo "<img src=\"../images/calSuccess.gif\" /> The event was deleted successfully.";		
	}
}	
?>	
	</div>
</div>
<div id="calwin">
	<div class="bar"><a href="javascript:hideCal()"><img src="../images/calClose.gif" alt="close" /></a></div>
	<div id="calback">
		<div id="calendar"></div>
	</div>
</div>
