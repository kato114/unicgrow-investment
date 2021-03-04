<?php
include('../security_web_validation.php');
?>
<?php
session_start();
?>
<html>
<head>
<script type='text/javascript' src='edit_ validition.js'></script> 
<link rel="stylesheet" type="text/css" href="../css/style.css" />
</head>
<body>
<div id="content" class="narrowcolumn">
<div class="comment odd alt thread-odd thread-alt depth-1" style="width:90%">
<h1 align="left">Search Member</h1>
<?php
include("config.php");
include("condition.php");
include("function/display.php");
if(isset($_POST['submit']))
{
	$u_name = $_REQUEST[user_name];
	$page = "index.php?val=search_member&open=3";
	display_member($u_name,$page);
}
else{ ?>
<table align="center" border="0" width=450>
<form name="my_form" action="index.php?val=search_member&open=4" method="post">
  <tr>
    <td colspan="2" class="td_title"><strong>Member Information</strong></td>
  </tr>
  <tr>
    <td class="td_title">Enter Member UserName</td>
    <td><input type="text" name="user_name" size=3/></td>
  </tr>
  <tr>
    <td align="center" colspan="2"><input type="submit" name="submit" value="Submit" class="normal-button"/></td>
    
  </tr>
  </form>
</table>
<?php  }  ?>
</div>
</div>
</body>
</html>