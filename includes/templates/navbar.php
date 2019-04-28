
<body>
	<div class="header">
		<div class="container">
			<div class="left-header">
				<a href="index.php"><img src="layout/images/logo.jpg" alt="LOGO" title="TDL"></a>
				
			</div>
			<div class="middle-header">
				<ul>
			<?php
				if(isset($_SESSION['name'])) { ?>
					<li><a href="index.php">Home</a></li>
					<li><a href="profile.php">Profile</a></li>
			<?php	
				}
				else { ?>
					<li><a href="index.php">Home</a></li>
			<?php
				}
			?>

				</ul>
			</div>
			<div class="right-header">
				<ul>
			<?php 
				if(isset($_SESSION['name'])) { ?>
					
					<li><?php echo $_SESSION['name'] ?></i></li>
					<li> | </li>
					<li><a href="logout.php">Logout <i class="fa fa-sign-out"></i></a></li>
			<?php
				}
				else {
			?>
					<li><a href="login.php">Login <i class="fa fa-sign-in"></i></a></li>
					<li> | </li>
					<li><a href="register.php">Register</a></li>
			<?php
				}
			?>
				
				</ul>
			</div>
		</div>
	</div>