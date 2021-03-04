<?php
/********************************************
*
*	Filename:	index.php
*	Author:		Ahmet Oguz Mermerkaya
*	E-mail:		ahmetmermerkaya@hotmail.com
*	Begin:		Tuesday, Feb 23, 2009  10:21
*
*********************************************/
session_start();
define("IN_PHP", true);
$login_id = $id = $_SESSION['mlmproject_user_id'];
if(isset($_POST['tree_member']))
{
	$name = $_POST['search_by_name'];
	$ednet_user_id = $_SESSION['bitfinfull_user_id'];
	$sql = "select * from users where username = '$name' && id_user > '$login_id'";
	$query = query_execute_sqli($sql);
	$num = mysqli_num_rows($query);
	if($num > 0)
	{
		while($row = mysqli_fetch_array($query))
		{
			$id = $row['id_user'];
			$username = $row['username'];
		}
		$_SESSION['tree_session_id'] = $id;
	}
	else
	{
		$error = "<div style=\"color:red; font-size:14px;\" align=\"center\">Please Use Correct Name For Search</div>";
	}
}
else
{
	$_SESSION['tree_session_id'] = $_SESSION['bitfinfull_user_id'];
}
require_once("common.php");
$ednet_user_id = $_SESSION['tree_session_id'];
$sql = "select * from users where id_user = '".$_SESSION['tree_session_id']."' ";
$query = query_execute_sqli($sql);
while($row = mysqli_fetch_array($query))
{
	$welcome_name = $row['f_name']." ".$row['l_name'];
} 
$rootName = "Personal Tree of ".$welcome_name."&nbsp;";
$treeElements = $treeManager->getElementList($ednet_user_id, "manageStructure.php");

?>	

<link rel="stylesheet" type="text/css" href="css/style.css" />
<link rel="stylesheet" type="text/css" href="assets/js/jquery/plugins/simpleTree/style.css" />
<script type="text/javascript" src="assets/js/jquery/jquery.min.js"></script>
<script type="text/javascript" src="assets/js/jquery/plugins/jquery.cookie.js"></script>
<script type="text/javascript" src="assets/js/jquery/plugins/simpleTree/jquery.simple.tree.js"></script>
<script type="text/javascript" src="assets/js/langManager.js" ></script>
<script type="text/javascript" src="assets/js/treeOperations.js"></script>
<script type="text/javascript" src="assets/js/init.js"></script>

<div align="right">
<form action="" method="post">
	<input type="text" name="search_by_name" value="" placeholder="Search Username" class="input-medium" />
	<input type="submit" name="tree_member" value="Search" class="btn btn-success" />
</form>
</div>
<?=$error;?>


<div id="wrap">
	<div id="annualWizard">	
		<ul class="simpleTree" id='pdfTree'>		
			<li class="root" id='<?php echo $treeManager->getRootId();  ?>'><span><?php echo $rootName; ?></span>
				<ul><?php echo $treeElements; ?></ul>				
			</li>
		</ul>						
	</div>	
	<div>		
	</div>
</div> 
<div id='processing'></div>
<DIV style="padding-top:150px;"></DIV>
