
<?php 
session_start();
include("function/frontpage_function.php");
$login_id = $_SESSION['mlmproject_user_id'];
$val = $_REQUEST['page'];

$menu = main_menu_name($val);
$sub_menu = sub_menu($val);
$menu_icon = get_menu_icon($val);
//echo "M:".$menu."**S:".$sub_menu;
if($val == ''){ $menu = 'Dashboard';}
if($val == 'level_income1'){ $menu = 'Summary';}
if($val == 'my_ticket'){ $menu = 'Support';}
?>
<style type="text/css"> 
    marquee span { 
    margin-right: 100%; 
    } 
    marquee p { 
    white-space:nowrap;
    margin-right: 1000px; 
    } 
</style> 

<div class="app-content content">
<?php //include("top_marquee.php");?>
	<div class="content-wrapper">
		<div class="content-header row">
			<?php
			//if($val != '' and $val != 'welcome') { ?>
			<div class="content-header-left col-md-6 col-12 mb-2">
				<h3 class="content-header-title"><?=$menu?></h3>
			</div>
			<div class="content-header-right col-md-6 col-12">
				<div class="btn-group float-md-right" aria-label="Button group with nested dropdown" role="group">
					<div class="row breadcrumbs-top">
						<div class="breadcrumb-wrapper col-12">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><i class="ft-home"></i> <a href="http://unicgrow.com/" target="_blank">Home</a></li>
								<?php if($sub_menu != 'Dashboard'){ ?>
								<li class="breadcrumb-item"><a href="#"><?=$sub_menu;?></a></li>
							    <?php }?>
								<li class="breadcrumb-item active"><?=$menu;?> </li>
							</ol>
						</div>
					</div>
				</div>
			</div> <?php
			//} ?>
		</div>
		<?php
		if($val != '' and $val != 'welcome') { ?>
			<div class="content-body">
				<div class="table-responsive">
					<div class="card">
						<div class="card-content">
							<div class="card-body">
								<?php
								$file = $val.".php";
								if ($val == '')
								include("data/welcome.php");
								else
								include("data/".$file);
								?>
							</div>
						</div>
					</div>
				</div>
			</div> <?php
		}
		else{ ?>
		<div class="row">
			<div class="content-body">
			<?php
			$file = $val.".php";
			if ($val == '')
			include("data/welcome.php");
			else
			include("data/".$file);
			?>
			</div>
		</div>
		<?php
		} ?>
	</div>
</div>