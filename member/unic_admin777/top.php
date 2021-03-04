<?php
session_start();
?>
<div class="row border-bottom">
	<nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
		<div class="navbar-header">
			<a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
		</div>
		<ul class="nav navbar-top-links navbar-right">
			<li>
				<span class="m-r-sm text-muted welcome-message">
					Welcome to <B>Admin</B>
				</span>
			</li>
			<?php
				$query = query_execute_sqli("SELECT * FROM message WHERE receive_id = '$id' order by id desc");
				$num = mysqli_num_rows($query);
			?>
			<li class="dropdown">
				<a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
					<i class="fa fa-envelope"></i>  
					<span class="label label-warning"><?=$num;?></span>
				</a>
				<ul class="dropdown-menu dropdown-messages">
				<?php
					if($num > 0)
					{
						while($row = mysqli_fetch_array($query))
						{
							$id  = $row['id'];
							$receive_id  = $row['receive_id'];
							$title = $row['title'];
							$message = $row['message'];
							$message_date = $row['message_date'];
							$time = $row['time'];
							
							$datetime1 = new DateTime();
							$datetime2 = new DateTime($time);
							$interval = $datetime1->diff($datetime2);
							$interval->format('%Y-%m-%d %H:%i:%s');
							$days = $interval->format('%d');
							$hour = $interval->format('%H');
							$minute = $interval->format('%i');
							$clock_days = '';
							$clock_hour = '';
							$clock_minute = '';
							
							if($days != 0)
							$clock_days = $days." Days";
							if($hour != 0)
							$clock_hour = $hour." Hour";
							if($minute != 0)
							$clock_minute = $minute." Min";
							?>	
							<li>
								<div class="dropdown-messages-box">
									<a href="profile.html" class="pull-left">
										<img alt="image" class="img-circle" src="img/a7.jpg">
									</a>
									<div class="media-body">
										<small class="pull-right">
											<?=$clock_hour; ?>
										</small>
										<strong>Mike Loreipsum</strong> started following 
										<strong>Monica Smith</strong>. <br>
										<small class="text-muted">
											<?=$clock_days." ".$clock_hour." ".$clock_minute; ?>
										</small>
									</div>
								</div>
							</li>
							<li class="divider"></li> <?php 	
						}  ?>
						<li>
							<div class="text-center link-block">
								<a href="mailbox.html">
									<i class="fa fa-envelope"></i> <strong>Read All Messages</strong>
								</a>
							</div>
						</li> <?php
					}
					else{ ?> <li><div class="media-body">There are no information to show !!</div></li> <?php } ?>
				</ul>
			</li>
			<li>
				<a href="logout.php" title="Logout" onclick="javascript:return confirm(&quot; Are You Sure? You want to logout !! &quot;);"><i class="fa fa-sign-out"></i> Logout</a>
			</li>
		</ul>
	</nav>
</div>