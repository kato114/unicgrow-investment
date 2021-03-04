<?php
function display($pos,$page,$img,$user_name,$parent_u_name,$name,$mode,$position,$date)
{ ?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Untitled Document</title>
	
	<style type="text/css" media="all">
	@import "css/global.css";
	</style>
	
	<script src="js/jquery.js" type="text/javascript"></script>
	<script src="js/jtip.js" type="text/javascript"></script>
	
	</head>
	<body>
	<span class="formInfo"><a href="ajax1.htm?width=375" class="jTip" id="one" name="Password must follow these rules:">?</a></span>
	<table width=800 border=0 hspace=0 vspace=0 cellspacing=0 cellpadding=0>
	<tr><th colspan=4 width=800>
	<span class="formInfo">
	<a href="binary_tree/user1.php?name=<?php print $name[0]; ?>&username=<?php print $user_name[0]; ?>&left=<?php print $left_child[0]; ?>&right=<?php print $right_child[0]; ?>&mode=<?php print $mode[0]; ?>&date=<?php print $date[0]; ?>&position=<?php print $position[0]; ?>" class="jTip" id="two" name="<?php print $name[0]; ?>">
	<img src=<?php print img[0]; ?>.jpg /></a>
	</span>
	</th></tr>
	<tr><th colspan=4 width=800 style="background-image:url(back_line.png);background-repeat: no-repeat;background-position: center;" height=10>
	</th></tr>
	<tr><th colspan=2 width=400>
	
	<span class="formInfo">
	<span class="formInfo">
	<a href="binary_tree/user2.php?name=<?php print $name[1]; ?>&username=<?php print $user_name[1]; ?>&left=<?php print $left_child[1]; ?>&right=<?php print $right_child[1]; ?>&mode=<?php print $mode[1]; ?>&date=<?php print $date[1]; ?>&position=<?php print $position[1]; ?>" class="jTip" id="two" name="<?php print $name[1]; ?>">
	<img src=<?php print img[1]; ?>.jpg /></a>
	</span>
	
	</th><th colspan=4 width=400>
	
	<span class="formInfo">
	<span class="formInfo">
	<a href="binary_tree/user3.php?name=<?php print $name[2]; ?>&username=<?php print $user_name[2]; ?>&left=<?php print $left_child[2]; ?>&right=<?php print $right_child[2]; ?>&mode=<?php print $mode[2]; ?>&date=<?php print $date[2]; ?>&position=<?php print $position[2]; ?>" class="jTip" id="two" name="<?php print $name[2]; ?>">
	<img src=<?php print img[2]; ?>.jpg /></a>
	</span>
	
	</th></tr>
	<tr><th width=200>
	
	<span class="formInfo">
	<span class="formInfo">
	<a href="binary_tree/user4.php?name=<?php print $name[3]; ?>&username=<?php print $user_name3]; ?>&left=<?php print $left_child[3]; ?>&right=<?php print $right_child[3]; ?>&mode=<?php print $mode[3]; ?>&date=<?php print $date[3]; ?>&position=<?php print $position[3]; ?>" class="jTip" id="two" name="<?php print $name[3]; ?>">
	<img src=<?php print img[3]; ?>.jpg /></a>
	</span>
	
	</th><th width=200>
	
	<span class="formInfo">
	<span class="formInfo">
	<a href="binary_tree/user5.php?name=<?php print $name[4]; ?>&username=<?php print $user_name[4]; ?>&left=<?php print $left_child[4]; ?>&right=<?php print $right_child[4]; ?>&mode=<?php print $mode[4]; ?>&date=<?php print $date[4]; ?>&position=<?php print $position[4]; ?>" class="jTip" id="two" name="<?php print $name[4]; ?>">
	<img src=<?php print img[4]; ?>.jpg /></a>
	</span>
	
	</th><th width=200>
	
	<span class="formInfo">
	<span class="formInfo">
	<a href="binary_tree/user6.php?name=<?php print $name[5]; ?>&username=<?php print $user_name[5]; ?>&left=<?php print $left_child[5]; ?>&right=<?php print $right_child[5]; ?>&mode=<?php print $mode[5]; ?>&date=<?php print $date[5]; ?>&position=<?php print $position[5]; ?>" class="jTip" id="two" name="<?php print $name[5]; ?>">
	<img src=<?php print img[5]; ?>.jpg /></a>
	</span>
	
	</th><th width=200>
	
	<span class="formInfo">
	<span class="formInfo">
	<a href="binary_tree/user7.php?name=<?php print $name[6]; ?>&username=<?php print $user_name[6]; ?>&left=<?php print $left_child[6]; ?>&right=<?php print $right_child[6]; ?>&mode=<?php print $mode[6]; ?>&date=<?php print $date[6]; ?>&position=<?php print $position[6]; ?>" class="jTip" id="two" name="<?php print $name[6]; ?>">
	<img src=<?php print img[6]; ?>.jpg /></a>
	</span>
	
	</th></tr>
	<tr></tr>
	</table>
	</body>
	</html>
<?php } 

display($pos,$page,$img,$user_name,$parent_u_name,$name,$mode,$position,$date);


?>