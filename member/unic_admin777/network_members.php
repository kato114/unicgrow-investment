<?php
//include('security_web_validation.php');
include('../config.php');
?>
<?php
error_reporting(0);
session_start();	
$login_id = 1;
?>
<!--<link rel="stylesheet" type="text/css" href="css/style.css" />-->
<link rel="stylesheet" type="text/css" href="js/jquery/plugins/simpleTree/style.css" />
<script type="text/javascript" src="js/jquery/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery/plugins/jquery.cookie.js"></script>
<script type="text/javascript" src="js/jquery/plugins/simpleTree/jquery.simple.tree.js"></script>
<script type="text/javascript" src="js/langManager.js" ></script>
<script type="text/javascript" src="js/treeOperations.js"></script>
<script type="text/javascript" src="js/init.js"></script>

<?php 
if(isset($_POST['tree_member'])){
	$name = $_POST['search_by_name'];
	$ednet_user_id = $_SESSION['mlmproject_user_id'];
	$sql = "select * from users where username = '$name' && id_user > '$login_id'";
	$query = query_execute_sqli($sql);
	$num = mysqli_num_rows($query);
	if($num > 0){
		while($row = mysqli_fetch_array($query)){
			$id = $row['id_user'];
			$username = $row['username'];
		}
		$_SESSION['tree_session_id'] = $id;
	}
	else{ $error = "<B class='text-danger'>Please Use Correct Name For Search</B>"; }
}
else{ $_SESSION['tree_session_id'] = $login_id; }
	
	
define("IN_PHP", true);

require_once("common.php");
$ednet_user_id = $_SESSION['tree_session_id'];
$sql = "select * from users where id_user = '".$_SESSION['tree_session_id']."' ";
$query = query_execute_sqli($sql);
while($row = mysqli_fetch_array($query))
{
	$welcome_name = $row['f_name']." ".$row['l_name'];
} 
$rootName = "Tree of ".$welcome_name."&nbsp;";
$treeElements = $treeManager->getElementList( $_SESSION['tree_session_id'], "manageStructure.php");	

?>
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
			<li class="root" id='<?=$treeManager->getRootId();  ?>'><span><?=$rootName; ?></span>
				<ul><?=$treeElements; ?></ul>				
			</li>
		</ul>						
	</div>	
	<div>		
	</div>
</div> 
<div id='processing'></div>
