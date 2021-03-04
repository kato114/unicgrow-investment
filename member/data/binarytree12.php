<?php
include('../security_web_validation.php');
include("function/total_child_count.php");
include("function/setting.php");
include("function/best_position.php");
$login_id = $id = $_SESSION['mlmproject_user_id'];


/*$sql = "select * from network_users where user_id='$login_id'";
$query = query_execute_sqli($sql);
while($row = mysqli_fetch_array($query)){
	$bottem_left = get_user_name(explode(",",$row['left_network'])[0]);
	$bottem_left = $bottem_left != '' ? $bottem_left : 0;
	$bottem_right = get_user_name(explode(",",$row['right_network'])[0]);
	$bottem_right = $bottem_right != '' ? $bottem_right : 0;
}*/
$user_username =$_SESSION['mlmproject_user_username'];
if(isset($_POST['update'])){
	$power_leg = $_POST['power_leg'] == "" ? "" : "power_leg=".($_POST['power_leg']-1);
	if($power_leg != ""){
		$sql = "UPDATE users SET $power_leg WHERE id_user = '$id'";
		query_execute_sqli($sql);
		echo "<B class='text-success'>Power Leg Updated Successfully ! </B>";
	}
	else{
		echo "<B class='text-danger'>Please Select Power Leg ! </B>";
	}
}

if(count($_GET) == 1){
	unset($_SESSION['bck_frd']);
}
if(isset($_POST['Search'])){
	if($_POST['search_username'] != ''){
		$_SESSION['SESS_search_username'] = $search_username = $_POST['search_username'];
		$logins_id = $id = get_new_user_id($search_username);
		unset($_SESSION['bck_frd']);
		
		$sql = "SELECT * FROM network_users WHERE (FIND_IN_SET($logins_id,left_network) OR 
		FIND_IN_SET($logins_id,right_network)) AND user_id = $login_id";
		$query = query_execute_sqli($sql);
		$num = mysqli_num_rows($query);
		if($num > 0){
			$_SESSION['bck_frd'][] = $login_id = $id = $logins_id;
		}
		/*else{ ?> 
			<script>
				alert("This User ID is not in your downline !"); window.location = "index.php?page=binarytree";
			</script> <?PHP 
		}*/
	}
}
if(isset($_GET['btp'])){
	
	$request_user_id = geting_best_position($_SESSION['btp_pos_user'],$_GET['btp'])[0];
	$login_id = $request_user_id > 0 ? $request_user_id : $login_id;
	unset($_SESSION['bck_frd']);
	$_SESSION['bck_frd'][] = $login_id;
}
if(isset($_GET['uid'])){
	unset($_SESSION['btp_pos_user']);
	$request_user_id = get_new_user_id($_GET['uid']);
	$login_id = $request_user_id > 0 ? $request_user_id : $login_id;
	unset($_SESSION['bck_frd']);
	$_SESSION['bck_frd'][] = $login_id;
}
if(isset($_GET['pl']) and count($_SESSION['bck_frd']) > 0){
	$pc = count($_SESSION['bck_frd']);
	$bck_frd = $_SESSION['bck_frd'][0] > 0 ? $_SESSION['bck_frd'][0] : $login_id;
	if($bck_frd != $login_id and $login_id < $bck_frd){
		$sql = "select * from users where id_user='$bck_frd'";
		$query = query_execute_sqli($sql);
		while($row = mysqli_fetch_array($query)){
			$login_id = $row['parent_id'];
			unset($_SESSION['bck_frd']);
			$_SESSION['bck_frd'][] = $login_id;
		}
	}
	else{
		unset($_SESSION['bck_frd']);
	}
	
	array_values($_SESSION['bck_frd']);
}



$sql = "select * from users where id_user = '$id' ";
$query = query_execute_sqli($sql);
while($row = mysqli_fetch_array($query))
{	
	$power_leg =$row['power_leg'] == NULL ? $row['power_leg'] : $row['power_leg']+1;
}
mysqli_free_result($query);
?>
<style>
@media (max-width: 768px) {
	.level-table img:not(.main-image) { max-width: 20px; max-height: 20px; }
	.level-table * { font-size: 9px; }
}
.col-lg-2_1 {
	width: 12%;
	float:left;
}
</style>
		  
<style>
.tree-binary { position: relative;min-height:300px; } /*min-height:500px; FOR 5 level tree*/
.tree-binary .tree-row { position: relative; }
.tree-binary .index-1 { position: absolute; z-index: 1; }
.tree-binary .index-2 { position: absolute; z-index: 2; }
.tree-binary .tree-element { text-align: center; }
.tree-binary .tree-line { font-size: 1px; pointer-events: none; }
.tree-binary .tree-line.horz { border: 2px solid #ccc; border-bottom: 0;
	-webkit-border-top-left-radius: 10px;
	-webkit-border-top-right-radius: 10px;
	-moz-border-radius-topleft: 10px;
	-moz-border-radius-topright: 10px;
	border-top-left-radius: 10px;
	border-top-right-radius: 10px;
}
.tree-binary .tree-line.vert { border-left: 2px solid #ccc; }

.tree-binary .tree-team-label { width: 50%; position: absolute; z-index: 3; text-align: center; pointer-events: none; }
.tree-binary .tree-left-team { left: 0; }
.tree-binary .tree-right-team { right: 0; }
  .tree-binary .tree-label {
	background: rgba(255,255,255,.8); 
	border: 1px solid #ccc; 
	line-height: 20px; 
	font-size: 14px; 
	padding: 0px 5px; 
	display: inline-block; 
	margin-top: 2px;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius: 5px;
	pointer-events: none;
}

.tree-binary div[role="tooltip"] .list-group { margin: 5px 0; }
.tree-binary div[role="tooltip"] .list-group-item { padding: 4px 10px; font-size: 12px; }
.tree-binary div[role="tooltip"] .tree-value { font-weight: bold; padding-left: 20px; }
</style>

<div class="col-lg-12">
	<div class="widget-area">
		<div class="col-sm-4">
			<a href="index.php?page=binarytree" class="btn btn-success">Back to Top</a>
			<a href="index.php?page=binarytree&pl=1" class="btn btn-danger">Previous Level</a>
		</div>
		<form action="index.php?page=<?=$val?>" method="post">
			<div class="col-sm-3">
				<input type="text" name="search_username" class="form-control" value="<?=$_POST['search_username']?>" placeholder='Serch by User ID' />
			</div>
			<div class="col-sm-1">
				<input type="submit" name="Search" value="Search" class="btn btn-info" />
			</div>
		</form>
			
		<form action="index.php?page=<?=$val?>" method="post">
			<div class="col-sm-3">
			<select name="power_leg" class="form-control">
				<option value="">Change Power Leg</option>
				<option value="1" <?=$power_leg == 1 ? "selected='selected'" : "";?>>Left</option>
				<option value="2" <?=$power_leg == 2 ? "selected='selected'" : "";?>>Right</option>
			</select>
			</div>
			<div class="col-sm-1">
			<input type="submit" name="update" value="Update" class="btn btn-primary" />
			</div>
		</form>
		<div class="row">&nbsp;</div>	
		<hr>
		<div class="row">
			<div class="col-md-5 col-md-offset-7 text-right">
				<div class="pull-left">
					<form action="index.php?page=spons_info" method="post">
						<input type="hidden" value="<?=$_SESSION['mlmproject_user_id']?>" name="user_id" />
						<input type="submit" name="my_sponsor" value="My Sponsor" class="btn btn-success btn-sm" />
					</form>
				</div>
				&nbsp;
				<a href="index.php?page=current_matching_status" class="btn btn-danger btn-sm">
					<i class="fa fa-users"></i> Binary History
				</a>
				&nbsp;
				<div class="pull-right">
					<form action="index.php?page=member_list" method="post">
						<input type="hidden" value="<?=$val?>" name="url_from" />
						<input type="hidden" value="<?=$login_id?>" name="user_id" />
						<input type="submit" name="more" value="Member List" class="btn btn-success btn-sm" />
					</form>
				</div>
			</div>
		</div>
		<div class="tree-binary" data-settings="{padding: 1}"></div>
		
		<div class="row">&nbsp;</div>
		<div class="row">&nbsp;</div>
		<div class="row">
			<div class="col-sm-6">
				<div style="padding-bottom: 10px;">
					<a href="index.php?page=binarytree&btp=0" class="btn btn-block btn-info">Bottom Left</a>
				</div>
				<!--<ul class="list-group">
					<li class="list-group-item">Left Team
						<span class="pull-right"><?=get_network_lr_team($login_id,'left_network',1)?> Members</span>
					</li>
				</ul>-->
			</div>
			<div class="col-sm-6">
				<div style="padding-bottom: 10px;">
					<a href="index.php?page=binarytree&btp=1" class="btn btn-block btn-info">Bottom Right</a>
				</div>
				<!--<ul class="list-group">
					<li class="list-group-item">Right Team
						<span class="pull-right"><?=get_network_lr_team($login_id,'right_network',1)?> Members</span>
					</li>
				</ul>-->
			</div>
		</div>
	</div>
</div>

<div class="col-lg-12">&nbsp;</div>
<div class="col-lg-4 text-center">
	 <img src="images/mlm_tree_view/a7.png" height="50"><br /><B>Available</B>
</div>
<div class="col-lg-4 text-center">
	 <img src="images/mlm_tree_view/a6.png" height="50"><br /><B>Blank Position</B>
</div>

<div class="col-lg-4 text-center">
	 <img src="images/mlm_tree_view/a4.png" height="50"><br /><B>Registered ID</B>
</div>
<div class="col-lg-12">&nbsp;</div>


<div class="col-lg-6 text-center">
	 <img src="images/mlm_tree_view/aa1.png" height="50"><br /><B>ETH SILVER</B>
</div>
<div class="col-lg-6 text-center">
	 <img src="images/mlm_tree_view/aa2.png" height="50"><br /><B>BTC GOLD</B>
</div>

<?php

$pos_arr = $date = $username = $f_name = $l_name = $email = $phone_no = $country = $spons_id = $date = $img = $left_network = $right_network = $arr1 = $tree_arr = array();

$pos_arr[0] = $login_id;
$pos_inc = 1;
$week_pn = get_pre_nxt_date($systems_date , $binary_froward_day);
$p_date = $week_pn[0];
$n_date = $week_pn[1];
$_SESSION['btp_pos_user'] = $login_id;
$sql = "SELECT t1.*,COALESCE(t2.username,'') spons_id,COALESCE(t3.date,'0000-00-00') act_date,max(t3.invest_type) invest_type FROM users t1 
		LEFT JOIN users t2 ON t1.real_parent = t2.id_user
		LEFT JOIN reg_fees_structure t3 ON t3.user_id = t1.id_user
		WHERE t1.id_user = '$login_id' GROUP by t1.id_user";
$query = query_execute_sqli($sql);
while($row = mysqli_fetch_array($query)){
	$id_user = $row['id_user'];
	$username = $row['username'];
	$name = ucwords($row['f_name']." ".$row['l_name']);
	$email = $row['email'];
	$phone_no = $row['phone_no'];
	$country = $row['country'];
	$date = $row['date'];
	$spons_id = $row['spons_id'];
	$pack_name = $plan_name[$row['invest_type']-1];
	$pack_name = $pack_name == NULL ? "" : $pack_name; 
	$act_date = $row['act_date'];
	$type = $row['type'];
	$img = get_img($id_user,$type,$row['invest_type']);
	$total_child = give_total_children($id_user);
	$status = $user_id != NULL ? 'Active' : 'Inactive';
	
	
	$ul_lps = $row['l_lps'];
	$ur_lps = $row['r_lps'];
	$step_topup = $row['package'];
	$business_summary = business_summary_cf($id_user,$ul_lps,$ur_lps,$step_topup,$p_date,$n_date);
	$cleft_point = $business_summary[0];
	$cright_point = $business_summary[1];
	$LCF = $business_summary[2];
	$RCF = $business_summary[3];
	$bin_qul = $business_summary[4];
	
	
	
	$bina_q = "Not Qualified";
	if($bin_qul == 1){ $bina_q = "Qualified"; }
	
	$arr1[$pos_inc] = array("uid"=>$id_user,"username"=>$username,"spons_id"=>$user_username,"level_position"=>(1+$row['position']),
			"image"=>$img,"usertype_name"=>"Purple",
			"tooltip"=>array("User ID"=>$username,"Sponsor"=>$spons_id,"Package"=>$pack_name,"Activate Date"=>$act_date,"<B>LEFT POSITION</B>"=>"",
			"Left Total Member"=>$total_child[0][3],"Left Paid Member"=>$total_child[0][0],"Left Unpaid Member"=>$total_child[0][1],"Left Business"=>$cleft_point,"Left CF"=>$LCF,"<B>RIGHT POSITION</B>"=>"","Right Total Member"=>$total_child[1][3],"Right Paid Member"=>$total_child[1][0],"Right Unpaid Member"=>$total_child[1][1],"Right Business"=>$cright_point,"Right CF"=>$RCF,"Binary Qualify"=>$bina_q));
}
$tree_arr[1] = $arr1;

$c = 1;
$j = 0;
$m = 2;
for($i = 0; $i < 4; $i++){
	$arr1 = array();
	$pos_inc = 1;
	
	
	for($ps = 0; $ps < pow(2,($i+1)); $ps++){
		$nps = ($ps) % 2;
		$k = ((int)($m/2))-1;
		$n_id = $pos_arr[$k];
		$m++;
		$sql = "SELECT t2.*,t3.username spons_id,COALESCE(t4.date,'0000-00-00') act_date,max(t4.invest_type) invest_type FROM users t1
		LEFT JOIN users t2 ON t1.id_user = t2.parent_id AND t2.position = '$nps'
		LEFT JOIN users t3 ON t2.real_parent = t3.id_user
		LEFT JOIN reg_fees_structure t4 ON t4.user_id = t2.id_user
		WHERE t1.id_user = '$n_id'
		group by t1.id_user";
		//print $sql."<br><br>";
		$query = query_execute_sqli($sql);
		$j++;
		$num = mysqli_num_rows($query);
		if($num > 0){
			while($row = mysqli_fetch_array($query))
			{
				
				$id_user = $row['id_user'];
				if($id_user == NULL or $id_user == "" or $id_user == 0){
					$pos_arr[$j] = 0;
					$pos_inc++;
				} 
				else{
					$pos_arr[$j] = $id_user;
					$username = $row['username'];
					$name = ucwords($row['f_name']." ".$row['l_name']);
					$email = $row['email'];
					$phone_no = $row['phone_no'];
					$country = $row['country'];
					$date = $row['date'];
					$type = $row['type'];
					$spons_id = $row['spons_id'];
					$act_date = $row['act_date'];
					$pack_name = $plan_name[$row['invest_type']-1];
					$pack_name = $pack_name == NULL ? "" : $pack_name;
					$img = get_img($id_user,$type,$row['invest_type'] > 0 ? $row['invest_type'] : 0);
					$total_child = give_total_children($id_user);
					
					$ul_lps = $row['l_lps'];
					$ur_lps = $row['r_lps'];
					$step_topup = $row['package'];
					$business_summary = business_summary_cf($id_user,$ul_lps,$ur_lps,$step_topup,$p_date,$n_date);
					$cleft_point = $business_summary[0];
					$cright_point = $business_summary[1];
					$LCF = $business_summary[2];
					$RCF = $business_summary[3];
					$bin_qul = $business_summary[4];
					
					
					
					$bina_q = "Not Qualified";
					if($bin_qul == 1){ $bina_q = "Qualified"; }
					//$status = $user_id != NULL ? 'Active' : 'Inactive';
					$arr1[$pos_inc] = array("uid"=>$id_user,"username"=>$username,"spons_id"=>$user_username,"level_position"=>($pos_inc),
									"image"=>$img,"usertype_name"=>$img,
									"tooltip"=>array("User ID"=>$username,"Sponsor"=>$spons_id,"Package"=>$pack_name,"Activate Date"=>$act_date,"<B>LEFT POSITION</B>"=>"",
			"Left Total Member"=>$total_child[0][3],"Left Paid Member"=>$total_child[0][0],"Left Unpaid Member"=>$total_child[0][1],"Left Business"=>$cleft_point,"Left CF"=>$LCF,"<B>RIGHT POSITION</B>"=>"","Right Total Member"=>$total_child[1][3],"Right Paid Member"=>$total_child[1][0],"Right Unpaid Member"=>$total_child[1][1],"Right Business"=>$cright_point,"Right CF"=>$RCF,"Binary Qualify"=>$bina_q));
					$pos_inc++;
				}
			}
		}
		else{
			$pos_arr[$j] = 0;
			$pos_inc++;
		}
	}
	$tree_arr[$i+2] = $arr1;
}

$str =  json_encode($tree_arr); 
function get_img($id,$type,$invest_type){
	$invest_type = $invest_type > 0 ? $invest_type : 0;
	$num = $invest_type > 0 ? 1 : 0;
	//if($id != "")$num = 1;
	if($type == 'B' and $num != 0) { 
		switch($invest_type){
			case 1 : $img = 'aa1';break;
			case 2 : $img = 'aa2';break;
			case 3 : $img = 'aa3';break;
			case 4 : $img = 'aa4';break;
			case 5 : $img = 'aa5';break;
			case 6 : $img = 'aa6';break;
			case 7 : $img = 'aa7';break;
			case 8 : $img = 'aa8';break;
			case 9 : $img = 'aa9';break;
			case 10 : $img = 'aa10';break;
			case 11 : $img = 'aa11';break;
			case 12 : $img = 'aa12';break;
			case 13 : $img = 'aa13';break;
			default : $img = 'aa3';break;
		}
		$imges = "images/mlm_tree_view/$img.png"; 
	}
	if($type == 'B' and $num == 0) { $imges = "images/mlm_tree_view/a4.png"; }
	elseif($type == 'C'){ $imges = "assets/img/1.png"; }
	elseif($type == 'D'){ $imges = "images/mlm_tree_view/a5.png"; }
	return $imges;
}
?>
<script>
var posData = $.parseJSON('<?=$str?>');
$(document).ready(function(){
	// draw initial tree
	drawAllBinaryTrees();
	
	$(window).resize(function(){
		// redraw tree
		hideAllBinaryTrees();
		
		setTimeout(function(){
			drawAllBinaryTrees();
		}, 300);
	});
});
</script>	

	
<script>
function drawBinaryTree(target, levels) {
				
	var container = (typeof target == 'object') ? target : $(target);
	var containerWidth = container.width();
	var settings = (container.data('settings') != null && container.data('settings') != '') ? eval('['+container.data('settings')+']') : [{}];					
	settings = settings[0];
	settings.padding = (settings.padding != null) ? parseInt(settings.padding) : 0;
	settings.lineHeight = (settings.lineHeight != null) ? parseInt(settings.lineHeight) : 20;
	
	var levels = (levels == null) ? 4 : levels;
	var maxElements = Math.pow(2, levels); // levels-1 For width control of image
	var itemWidth = containerWidth/maxElements-settings.padding*2;
	var itemHeight = itemWidth;
	
	leftName = (containerWidth < 500) ? 'Left:' : 'Left Team:';
	rightName = (containerWidth < 500) ? 'Right:' : 'Right Team:';
	
	// empty container
	container.html('');			
	container.append('<div class="tree-left-team tree-team-label">'+leftName+' </div>');		
	container.append('<div class="tree-right-team tree-team-label">'+rightName+'</div>');	
				
	container.find('.tree-team-label').css({
		top: (itemHeight-13)/2+'px',	
		lineHeight: 13+'px',
		fontSize: 13+'px',
	});
				
	for (l = 1; l <= levels; l++) {
		var row = $('<div class="tree-row"></div>');
		row.css({
			height: itemHeight+settings.padding*2+'px',
		});
		
		elementsInLevel = Math.pow(2, l-1);
		levelWidth = containerWidth/elementsInLevel;
		
		// draw arrows
		if (l == 1) {
			row.append('<div class="tree-left-arrow tree-arrow index-2"><img src="assets/img/left.gif" width="100%"></div>');
			row.append('<div class="tree-right-arrow tree-arrow index-2"><img src="assets/img/right.gif" width="100%"></div>');
			row.find('.tree-arrow').css({
				width: itemWidth+'px',
				marginTop: itemWidth/3+'px',
			});
			
			leftPos = levelWidth/2 - itemWidth*2;
			row.find('.tree-left-arrow').css({
				left: leftPos+'px'
			});
			
			leftPos = levelWidth/2 + itemWidth;
			row.find('.tree-right-arrow').css({
				left: leftPos+'px'
			});
		}
		
		// draw level elements
		for (i = 1; i <= elementsInLevel; i++) {
			var element = $('<div class="tree-element index-2"></div>');
			element.data('levelWidth', levelWidth);
			element.append('<img class="tree-element-image" width="100%" src="images/mlm_tree_view/a6.png">');
			
			leftPos  = levelWidth*(i-1) + (levelWidth-itemWidth)/2;
			element.addClass('pos-'+l+'-'+i);
			element.css({
				width: itemWidth+'px',
				height: itemWidth+'px',
				top: settings.padding+'px',
				left: leftPos+'px',
			});
			row.append(element);
		}
		
		// draw lines
		for (i = 1; i <= elementsInLevel; i++) {
			// create bottom vertical line
			if (l < levels) {
				vertLine = $('<div class="tree-line vert index-1"></div>');
				bottomPos = -settings.lineHeight/2;
				leftPos  = levelWidth*(i-1) + (levelWidth)/2;
				lineHeight = (settings.lineHeight+4)/2;
				
				vertLine.css({
					height: lineHeight+'px',	
					left: leftPos+'px',
					bottom: bottomPos+'px',
				});
				row.append(vertLine);
			}
			
			// create top vertical line
			if (l > 1 && false) {
				vertLine = $('<div class="tree-line vert index-1"></div>');
				topPos = -settings.lineHeight/2-2;
				leftPos  = levelWidth*(i-1) + (levelWidth)/2;
				lineHeight = (settings.lineHeight+4)/2;
				
				vertLine.css({
					height: lineHeight+'px',	
					left: leftPos+'px',
					top: topPos+'px',
				});
				
				row.append(vertLine);
			}
			
			// create horizontal line
			if (l < levels) {
				horzLine = $('<div class="tree-line horz index-1"></div>');
				lineWidth = levelWidth/2;
				lineHeight = (settings.lineHeight+4)/2;
				
				//bottomPos = -settings.lineHeight/2; 
				bottomPos = -(settings.lineHeight+2);
				leftPos  = levelWidth*(i-1)+lineWidth/2;
				horzLine.css({
					width: lineWidth+'px',
					height: lineHeight+'px',
					left: leftPos+'px',
					bottom: bottomPos+'px',
				});
				row.append(horzLine);
			}
		}
		
		// create margin for line spacing
		if (l < levels) {
			row.css({
				marginBottom: settings.lineHeight+'px',	
			});
		}
		//alert('l: '+l+' :: elementsInLevel: '+elementsInLevel);
		container.append(row);
	}
	
				
	// populate data
	labelDepth = (containerWidth > 500) ? 3 : 2;  
	positionUsers = new Array();
	for (ul in posData) { 
		users = posData[ul];
		elementsInLevel = Math.pow(2, ul-1);
		if (positionUsers[ul] == null) {
			positionUsers[ul] = new Array();
		}
		for (up in users) {
			user = users[up];
			
			positionUsers[ul][up] = user;
			
			className = '.pos-'+ul+'-'+up;
			element = $(className);
			element.html('<a href="index.php?page=binarytree&uid='+user.username+'"><img src="'+user.image+'" width="100%"></a>');
			
			
			// create tooltop
			tooltipContent = '';
			for (t in user.tooltip) {
				tKey = t;
				tValue = user.tooltip[t];
				tKey = ucwords(tKey.replace(/_/gi, ' '));
				tooltipContent += '<li class="list-group-item">'+tKey+': <span class="pull-right tree-value">'+tValue+'</span></li>';
			}
			
			if (tooltipContent != '') {
				tooltipContent = '<ul class="list-group">'+tooltipContent+'</ul>';

				tooltipPlacement = (up < elementsInLevel/1.999) ? 'right' : 'left';
				if (containerWidth < 500 || ul == 1) {
					tooltipPlacement = 'bottom';
				}
				
				element.popover({
					html: true,
					content: tooltipContent,
					trigger: 'hover',
					placement: tooltipPlacement,
					animation: false,
				});
			}
			
			
			if (ul <= labelDepth) {
				elementLevelWidth = element.data('levelWidth');
				nameLabel = $('<div style="pointer-events: none;"></div>');
				nameLabel.css({
					width: elementLevelWidth+'px',
					marginLeft: -(elementLevelWidth-itemWidth)/2+'px',
				});
				nameLabel.append('<span class="tree-label">'+user.username+'</span>');
				element.append(nameLabel);
			}
			
			//element.append('test');
		}
	}
				
				 
	for (l = 1; l <= levels; l++) {
		elementsInLevel = Math.pow(2, l-1);
		for (i = 1; i <= elementsInLevel; i++) {
			emptyPos = true;
			if (positionUsers[l] != null) { 
				if (positionUsers[l][i] != null) {
					emptyPos = false;
				}
			}
			
			if (emptyPos) {
				// empty position, check for parent
				parentLevel = l-1;
				parentPosition = Math.ceil(i/2);
				placement = (i/2 % 1 != 0) ? 'left' : 'right';
				
				if (positionUsers[parentLevel] != null) {
					if (positionUsers[parentLevel][parentPosition] != null) {
						//found parent
						parentUser = positionUsers[parentLevel][parentPosition];
						
						// register user link
						className = '.pos-'+l+'-'+i;
						element = $(className);
						element.html('<a href="#"><img src="images/mlm_tree_view/a7.png" width="100%"></a>');
						//element.html('<a href="register.php?ref='+parentUser.spons_id+'&bp='+placement+'&place='+parentUser.username+'" target="_blank"><img src="images/mlm_tree_view/a7.png" width="100%"></a>');
						
					}
				}
			}
		}
	}
}
			
function drawAllBinaryTrees(target) {
	if (target == null) { target = '.tree-binary'; }
	$(target).each(function(){
		drawBinaryTree($(this), 4);
	});	
}

function hideAllBinaryTrees(target) {
	if (target == null) { target = '.tree-binary'; }
	$(target).each(function(){
		$(this).html('<p align="center">Updating...</p>');
	});	
}

function ucwords(str) {			
  return (str + '')
	.replace(/^([a-zA-Z])|\s+([a-zA-Z])/g, function($1) {
	  return $1.toUpperCase();
	});
}
</script>

<script>
$('input[name="placement"]').click(function(){
	$.get('index.php?page=binarytree&placement='+$(this).val(), function(response){
		try { 
		
			data = $.parseJSON(response);
			className = (data.status == '1') ? 'alert-success' : 'alert-error';
			
			output = $('.change-settings-output');
			output.html('<div class="alert '+className+'">'+data.msg+'</div>');
			
			setTimeout(function(){
				output = $('.change-settings-output');
				output.find('.alert').fadeOut().remove();
			},3000);
			
		} catch(err) {}
	});	
});
</script>
<?php
function business_summary_cf($id_user,$ul_lps,$ur_lps,$step_topup,$p_date,$n_date){
	include "function/setting.php";
	global $systems_date;
	$cleft_point = $cright_point = $LCF = $RCF = $bin_qul = 0;
	if($step_topup == 2){
		if( ($ul_lps > 1 and $ur_lps >= 1) or ($ul_lps >= 1 and $ur_lps > 1) ){
			$bin_qul = 1;
			$sql = "select cf_left,cf_right from pair_point where user_id = '$id_user' and date between '$p_date' and '$n_date' order by id desc limit 1 ";
			$que = query_execute_sqli($sql);
			$num = mysqli_num_rows($que);
			if($num > 0){
				while($ro = mysqli_fetch_array($que)){
					$cleft_point = $ro['cf_left'];
					$cright_point = $ro['cf_right'];
				}
				$left_point = $right_point = 0;
				$sql = "select left_point,right_point from pair_point 
				where user_id = '$id_user' order by id desc limit 1,1 ";
				$que1 = query_execute_sqli($sql);
				$num = mysqli_num_rows($que1);
				if($num > 0){
					while($ro = mysqli_fetch_array($que1)){
						$left_point = $ro['left_point'];
						$right_point = $ro['right_point'];
					}
					$total_pair = 0;
					$max_pair = min($left_point,$right_point);
					/*$pc = 1;
					do
					{
						$pair_calc = $per_day_multiple_pair*$pc;
						$pc++;
					}
					while($pair_calc <= $max_pair);*/
					$total_pair = $max_pair;//$pair_calc-$per_day_multiple_pair;
					$LCF = $left_point - $total_pair;
					$RCF = $right_point - $total_pair;
				}
				mysqli_free_result($que1);
				
			}
			else{
				$cleft_point = $cright_point = $LCF = $RCF = 0;
				$sql = "select left_point,right_point from pair_point 
				where user_id = '$id_user' order by id desc limit 1 ";
				$que1 = query_execute_sqli($sql);
				$num = mysqli_num_rows($que1);
				while($ro = mysqli_fetch_array($que1)){
					$left_point = $ro['left_point'];
					$right_point = $ro['right_point'];
				}
				mysqli_free_result($que1);
				$total_pair = 0;
				$max_pair = min($left_point,$right_point);
				$pc = 1;
				do
				{
					$pair_calc = $per_day_multiple_pair*$pc;
					$pc++;
				}
				while($pair_calc <= $max_pair);
				$total_pair = $max_pair;//$pair_calc-$per_day_multiple_pair;
				
				$LCF = $left_point -$total_pair;
				$RCF = $right_point -$total_pair;
			}
			mysqli_free_result($que);
		}
		else{
			$cleft_point = $cright_point = $LCF = $RCF = 0;
			$sql = "select * from pair_point 
					where user_id = '$id_user' and date between '$p_date' and '$n_date' order by id desc limit 1";
			$que1 = query_execute_sqli($sql);
			$num = mysqli_num_rows($que1);
			if($num > 0){
				while($ro = mysqli_fetch_array($que1)){
					$cleft_point = $ro['cf_left'];
					$cright_point = $ro['cf_right'];
					$LCF = $ro['left_point'] - $ro['cf_left'];
					$RCF = $ro['right_point'] - $ro['cf_right'];
				}
			}
			mysqli_free_result($que1);
		}
	}
	else{
		$cleft_point = $cright_point = $LCF = $RCF = 0;
	}
	return array($cleft_point,$cright_point,$LCF,$RCF,$bin_qul);
}
?>