<?php
	ob_start();
	session_start();
	$pageTitle='Login | TDL';
	include 'init.php';

	if($_SERVER['REQUEST_METHOD']=='POST') {
		$username=$_POST['username'];
		$password=$_POST['password'];
		$hashedPass=sha1($password);

		$stmt=$connect->prepare("SELECT UserID,Username,Password FROM users WHERE Username=? AND Password=?");
		$stmt->execute(array($username,$hashedPass));
		$row=$stmt->fetch();
		$count=$stmt->rowCount();

		if($count>0) {
			$_SESSION['name']=$row['Username'];
			$_SESSION['ID']=$row['UserID'];
			header("Location:index.php");
			exit();
		}
	}
?>

	<div class="login">
		<div class="container">
			<h1>Login</h1>
			<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
				<label>Username:</label>
				<input type="text" name="username">
				<label>Password:</label>
				<input type="password" name="password">
				<input type="submit" value="Login">
			</form>
		</div>
	</div>

<?php
	include 'includes/templates/footer.php';
	ob_end_flush();
?>