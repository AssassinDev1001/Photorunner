<?php
$conditions = array();
$home = $common->getrecord('pr_home','*',$conditions);	
?>
<div class="wrapper">
	<header class="main-header">
		<a href="home.php" class="logo">
			<span class="logo-mini"><b></b>P R</span>
			<span class="logo-lg"><b>Photo</b>Runner</span>
		</a>
		<nav class="navbar navbar-static-top" role="navigation">
			<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
			<span class="sr-only">Toggle navigation</span>
		</a>
		<div class="navbar-custom-menu">
			<ul class="nav navbar-nav">
				<li class="dropdown user user-menu">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<img src="dist/img/default.png" class="user-image" alt="User Image">
						<span class="hidden-xs">Admin</span>
					</a>
					<ul class="dropdown-menu">
						<li class="user-header">
							<img src="dist/img/default.png" class="img-circle" alt="User Image">
							<p>Photo Runner</p>
						</li>
						<li class="user-footer">
							<div class="pull-left">
								<a href="change-password.php" class="btn btn-default btn-flat">Change Password</a>
							</div>
							<div class="pull-right">
								<a href="logout.php" class="btn btn-default btn-flat">Sign out</a>
							</div>
						</li>
					</ul>
				</li>
			</ul>
		</div>
	</nav>
</header>

