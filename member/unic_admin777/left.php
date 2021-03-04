<nav class="navbar-default navbar-static-side" role="navigation">
	<div class="sidebar-collapse">
		<ul class="nav" id="side-menu">
			<li class="nav-header">
				<div class="dropdown profile-element"> <span>
					<img alt="image" class="img-circle" src="img/profile_small.jpg" />
					 </span>
					<a data-toggle="dropdown" class="dropdown-toggle" href="#">
					<span class="clear"> 
						<span class="block m-t-xs"> 
							<strong class="font-bold">Admin</strong>
					 	</span> 
						<span class="text-muted text-xs block">Admin <b class="caret"></b></span> 
					</span> 
					</a>
					<ul class="dropdown-menu animated fadeInRight m-t-xs">
						<!--<li><a href="index.php?page=user_profile">Profile</a></li>
						<li><a href="index.php?page=edit_profile">Settings</a></li>
						<li><a href="index.php?page=inbox">Mailbox</a></li>
						<li class="divider"></li>-->
						<li><a href="logout.php">Logout</a></li>
					</ul>
				</div>
				<div class="logo-element">UNICGROW</div>
			</li>
			<?php include "left_menu.php"; ?>
		</ul>
	</div>
</nav>