<?php
include('../../security_web_validation.php');
?>
<?PHP
session_start();
include("condition.php");
include("../function/setting.php");
include("../function/functions.php");
include("../function/send_mail.php");

$newp = $_GET['p'];
$plimit = 50;
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;

$qur_set_search = $selct1 = $selct2 = $selct3 = $selct4 = $selct5 = $selst0 = $selst1 = $selst2 = '';

if(count($_GET) > 1){
	$_POST['Search'] = '1';
	$_POST['search_text'] = $_SESSION['SESS_search_text'];
	$_POST['search_opt'] = $_SESSION['SESS_search_opt'];
	$_POST['status'] = $_SESSION['SESS_search_status'];
	$_POST['st_date'] = $_SESSION['SESS_st_date'];
	$_POST['en_date'] = $_SESSION['SESS_en_date'];
}
else{
	unset($_SESSION['SESS_search_text'],$_SESSION['SESS_search_opt'],$_SESSION['SESS_search_status'],$_SESSION['SESS_en_date'],$_SESSION['SESS_st_date']);
}
if(isset($_POST['Search']))
{
	if($_POST['search_opt'] !='' and $_POST['search_text'] !=''){
		
		$_SESSION['SESS_search_opt'] = $search_opt = $_POST['search_opt'];
		$_SESSION['SESS_search_text'] = $search_text = $_POST['search_text'];
		
		$search_id = get_new_user_id($search_text);
		
		switch($search_opt) {
			case 1 : $qur_set_search = " AND t1.unique_id = '$search_text'"; $selct1='selected="selected"'; break;
			case 2 : $qur_set_search = " AND CONCAT(t1.f_name, ' ', t1.l_name) = '$search_text'"; 
				$selct2 = 'selected="selected"'; 
			case 3 : $qur_set_search = " AND t1.user_id = '$search_id'"; $selct3='selected="selected"'; break;
			case 4 : $qur_set_search = " AND t4.category = '$search_text'"; $selct4='selected="selected"'; break;
			case 5 : $qur_set_search = " AND t3.phone_no = '$search_text'"; $selct5='selected="selected"'; break;
		}
	}
	if($_POST['st_date'] != '' and $_POST['en_date'] != ''){
		$_SESSION['SESS_st_date'] = $st_date = date('Y-m-d', strtotime($_POST['st_date']));
		$_SESSION['SESS_en_date'] = $en_date = date('Y-m-d', strtotime($_POST['en_date']));
		$qur_set_search = " AND DATE(t1.date) >= '$st_date' AND DATE(t1.date) <= '$en_date' ";
	}
	
	if($_POST['status'] !=''){
		$_SESSION['SESS_search_status'] = $status = $_POST['status'];
		$qur_set_search = " AND t1.mode = '$status'";
		
		switch($status){
			case 0 : $selst0 = 'selected="selected"';	break;
			case 1 : $selst1 = 'selected="selected"';	break;
			case 2 : $selst2 = 'selected="selected"';	break;
		}
	}
}



if(isset($_SESSION['edit_succ'])){
	echo $_SESSION['edit_succ'];
	unset($_SESSION['edit_succ']);
}

if(isset($_POST['delete_tickt'])){
	$table_id = $_POST['table_id'];
	query_execute_sqli("DELETE FROM `my_ticket` WHERE id = '$table_id'");
	query_execute_sqli("ALTER TABLE `my_ticket` DROP `id`");
	query_execute_sqli("ALTER TABLE `my_ticket` ADD `id` INT NOT NULL AUTO_INCREMENT FIRST , ADD PRIMARY KEY (id)");
			
	echo "<B style='color:#008000;'>Ticket Delete Successfully !!</B>";
}
if(isset($_POST['update'])){
	$tickt_id = $_POST['tickt_id'];
	$user_id = $_POST['user_id'];
	$message = $_POST['message'];
	$status = $_POST['status'];
	$unique_id = $_POST['unique_id'];
	
	$date = date('Y-m-d H:i:s');
	$ip_add =  $_SERVER['REMOTE_ADDR'];
	
	if($message != ""){
		$sql = "INSERT INTO my_ticket_message (user_id , ticket_id , message , message_by , ip_address , 
		date , mode , unique_id) 
		VALUES('$user_id' , '$tickt_id' , '$message' , 'admin' , '$ip_add' , 
		'$date', '$status' , '$unique_id')";
		query_execute_sqli($sql);
		
		query_execute_sqli("UPDATE my_ticket SET mode = '$status' WHERE unique_id = '$unique_id'");
		if(strtoupper($soft_chk) == "LIVE"){
			$to = get_user_email($user_id);
			$title = "Support Ticket";
			$db_msg = $message." http://unicgrow.com";
			include("../function/full_message.php");
			
			$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from, $to, $title, $full_message);
			//$SMTPChat = $SMTPMail->SendMail();
		}
		$_SESSION['edit_succ'] = "<B class='text-success'>Ticket Edit Successfully !!</B>";
		?> <script>window.location = "index.php?page=support_ticket";</script> <?php 
	}
	else{ echo "<B class='text-danger'>Please fill all field !!</B>"; }
}
elseif(isset($_POST['edit_tickt'])){ 
	$table_id = $_POST['table_id'];
	$unique_id = $_POST['unique_id'];
	$user_id = $_POST['user_id'];
	$mode = $_POST['mode'];
	$title = $_POST['title'];
	
	if($mode == 0){ $status = "<span class='label label-warning'>Open</span>";}
	elseif($mode == 1){ $status = "<span class='label label-info'>Processing</span>";}
	else{ $status = "<span class='label label-success'>Closed</span>";} ?>
	
	<div class="col-md-6 text-left">
		<B style="color:#036281; font-size:14px">Ticket No. &nbsp;- </B>
		<span style="padding-left:7px;color:#333;"><?=$unique_id;?></span>
	</div>
	<div class="col-md-6 text-right">
		<B style="color:#036281; font-size:14px">Status &nbsp;-</B> 
		<span style="padding-left:7px;"><?=$status;?></span>
	</div>
	<div class="col-md-12">&nbsp;</div>
	<div class="col-md-12"><h4>Ticket Title : <B class=" text-success"><?=$title;?></B></h4></div>
	
	<div class="col-md-12">&nbsp;</div>
	<?php
	$sql = "select * from my_ticket_message where ticket_id = '$table_id' AND user_id = '$user_id'";
	$query = query_execute_sqli($sql);
	while($row = mysqli_fetch_array($query)){  
		$id = $row['id'];
		$user_id = $row['user_id'];
		$message = $row['message'];
		$message_by = $row['message_by'];
		$mode = $row['mode'];
		$unique_id = $row['unique_id'];
		$ticket_id = $row['ticket_id'];
		$date = $row['date'];
		$date1 = date('d/m/Y H:i:s',strtotime($date));
		
		if($message_by == 'admin'){ 
			$name = "<span style='padding-left:20px;'>Admin</span>"; 
			$color = "text-primary";
			$text_class = 'text-center';
		}
		else{ 
			$name = get_user_name($user_id);; 
			$color = "text-danger";
			$text_class = 'text-center';
		} ?>
		<div class="<?=$color;?>">
			<p class="text-muted no-margn"> 
				<div class="col-md-6 text-left">
					<B><?=$name;?></B> 
					<span style="padding-left:40px;"><?=$message;?></span> 
				</div>
				<div class="col-md-6 text-right">
					<span class="text-right"><B><?=$date1;?></B></span>
				</div>
			</p>
		</div> <?php 
	}

	if($mode != 2){ ?> 
		<div class="col-sm-12">&nbsp;</div>
		<div class="col-sm-12">&nbsp;</div>
		<form name="message" action="" method="post" class="form-horizontal">
			<input type="hidden" value="<?=$ticket_id;?>" name="tickt_id" />
			<input type="hidden" value="<?=$user_id;?>" name="user_id" />
			<input type="hidden" value="<?=$unique_id;?>" name="unique_id" />
			
			<label class="col-sm-1">Message</label>
			<div class="col-sm-11"><textarea name="message" class="form-control"></textarea></div>
			<div class="col-sm-12">&nbsp;</div>
			<label class="col-sm-1">Status</label>
			<div class="col-sm-11">
				<select name="status" class="form-control" required>
					<option value="">Select Status</option>
					<option value="0" <?php if($_POST['status']==0){ ?> selected="selected" <?php } ?>>Open</option>
					<option value="1" <?php if($_POST['status'] == 1){ ?> selected="selected" <?php } ?>>Processing</option>
					<option value="2" <?php if($_POST['status'] == 2){ ?> selected="selected" <?php } ?>>Closed</option>
				</select>
			</div>
			<div class="col-sm-12">&nbsp;</div>
			<div class="col-sm-12 text-center">
				<input type="submit" name="update" value="Update" class="btn btn-info"/>
			</div>
		</form>  <?php
	} 
}
else{ 
	$sql = "SELECT t1.*,t2.message,t2.mode t_mode,t2.file,t3.f_name,t3.l_name,t3.username,t3.phone_no,t4.category
	FROM `my_ticket` t1 
	LEFT JOIN (SELECT user_id,message,mode,file FROM my_ticket_message group by user_id) t2 ON t1.user_id=t2.user_id 
	LEFT JOIN users t3 ON t1.user_id = t3.id_user
	LEFT JOIN my_ticket_categry t4 ON t1.catg_id = t4.id
	where DATE(t1.date) >= '2019-05-23' $qur_set_search";
	
	$SQL = "$sql LIMIT $tstart,$tot_p ";
	
	$sqlk = "SELECT COUNT(*) num FROM ($sql) t1";
	$query = query_execute_sqli($sqlk);
	while($ro = mysqli_fetch_array($query)){
		$tot_rec = $ro['num'];
		$lpnums = ceil ($tot_rec/$plimit);
	}
	
	
	$query = query_execute_sqli($SQL);
	$totalrows = mysqli_num_rows($query);
	if($totalrows > 0){ ?>
		<div class="col-md-2">
			<form method="post" action="index.php?page=<?=$val?>">
				<input type="hidden" name="Search" value="Search" />
				<select name="status" class="form-control" onchange="this.form.submit();">
					<option value="">Select Status</option>
					<option value="0" <?=$selst0?>>Pending</option>
					<option value="1" <?=$selst1?>>In Process</option>
					<option value="2" <?=$selst2?>>Solved</option>
				</select>
			</form>
		</div>
		<form method="post" action="index.php?page=<?=$val?>">
			<div class="col-md-2">
				<div class="form-group" id="data_1">
					<div class="input-group date">
						<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
						<input type="text" name="st_date" placeholder="From Date" class="form-control" />
					</div>
				</div>
			</div>
			<div class="col-md-2">
				<div class="form-group" id="data_1">
					<div class="input-group date">
						<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
						<input type="text" name="en_date" placeholder="To Date" class="form-control" />
					</div>
				</div>
			</div>
			<div class="col-md-2">
				<select name="search_opt" class="form-control">
					<option value="">Search Option</option>
					<option value="1" <?=$selct1?>>Ticket No.</option>
					<option value="2" <?=$selct2?>>User Name</option>
					<option value="3" <?=$selct3?>>User ID</option>
					<option value="4" <?=$selct4?>>Ticket Category</option>
					<option value="5" <?=$selct5?>>Mobile</option>
				</select>
			</div>
			<div class="col-md-3">
				<input type="text" name="search_text" placeholder="Search Text" class="form-control" />
			</div>
			<div class="col-md-1">
				<input type="submit" value="Search" name="Search" class="btn btn-info btn-sm">
			</div>
		</form>
		
		<table class="table table-bordered">
			<thead>
			<tr>
				<th class="text-center">Sr. No.</th>
				<th class="text-center">Ticket No.</th>
				<th class="text-center">Date</th>
				<th class="text-center">User ID</th>
				<th class="text-center">User Name</th>
				<th class="text-center">Category</th>
				<th class="text-center">Phone</th>
				<th class="text-center">Subject</th>
				<!--<th class="text-center">Message</th>-->
				<th class="text-center">Comment</th>
				<!--<th class="text-center">Image</th>-->
				<th class="text-center">Status</th>
				<th class="text-center">Action</th>
			</tr>
			</thead>
			<?php
			$pnums = ceil ($totalrows/$plimit);
			if ($newp==''){ $newp='1'; }
			$start = ($newp-1) * $plimit;
			$starting_no = $start + 1;
			$sr_no = $starting_no;
			
			$quer = query_execute_sqli("$sql LIMIT $start,$plimit ");
			while($row = mysqli_fetch_array($quer)){  
				$id = $row['id'];
				$user_id = $row['user_id'];
				$title = $row['title'];
				$message = $row['message'];
				$mode = $row['mode'];
				$unique_id = $row['unique_id'];
				$file = $row['file'];
				$date = date('d M Y', strtotime($row['date']));
				$name= ucwords($row['f_name']." ".$row['l_name']);
				$username = $row['username'];
				$phone = $row['phone_no'];
				$category = $row['category'];
				$title = $row['title'];
				
				switch($mode) {
					case 0 : $status = "<span class='label label-warning'>Open</span>"; break;
					case 1 : $status = "<span class='label label-info'>Processing</span>"; break;
					default : $status = "<span class='label label-success'>Closed</span>"; 
				}
				?>
				<tr class="text-center">
					<td><?=$sr_no.$user_id?></td>
					<td>
						<form action="" method="POST" target="_blank">
							<input type="hidden" value="<?=$id?>" name="table_id" />
							<input type="hidden" value="<?=$user_id?>" name="user_id" />
							<input type="hidden" value="<?=$mode?>" name="mode" />
							<input type="hidden" value="<?=$unique_id?>" name="unique_id" />
							<input type="hidden" value="<?=$title?>" name="title" />
							<input type="Submit"  style="border:0;background-color:transparent; cursor:pointer; color:#0066FF" value="<?=$unique_id?>" name="edit_tickt" />
						</form>
					</td>
					<td><?=$date?></td>
					<td><?=$username?></td>
					<td><?=$name?></td>
					<td><?=$category?></td>
					<td><?=$phone?></td>
					<td><?=$title?></td>
					<!--<td><?=$title?></td>-->
					<td><?=$message?></td>
					<!--<td>
						<img src="../images/mlm_support/<?=$file?>" width="70" />
						<a href="ticket_img.php?unique_id=<?=$unique_id?>&file=<?=$file?>" target="_blank">
							Click Here
						</a>
					</td>-->
					<td><?=$status?></td>
					<td>
						<form action="" method="POST">
							<input type="hidden" value="<?=$id?>" name="table_id" />
							<input type="hidden" value="<?=$user_id?>" name="user_id" />
							<input type="hidden" value="<?=$mode?>" name="mode" />
							<input type="hidden" value="<?=$unique_id?>" name="unique_id" />
							<input type="Submit"  style="width:18px; height:18px; border:0;background-color:transparent; background-image:url(images/edit.png);cursor:pointer" value="" name="edit_tickt" title="Edit This Ticket" />
							<input type="Submit" value="" style="width:18px; height:18px; margin-left:5px;border:0;background-color:transparent; background-image:url(images/delete.png);cursor:pointer" name="delete_tickt" title="Delete This Ticket" />
						</form>
					</td>
				</tr> <?php	
				$sr_no++;
			} ?>
		</table> <?php
		pagging_initation_last_five_admin($newp,$lpnums,$show_tab,$val);
	}
	else{ echo "<B class='text-danger'>There are no information to show !!</b>";}
} ?>


