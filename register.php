<?php
	ob_start();
	$pageTitle='Register | TDL';
	include 'init.php';

	if($_SERVER['REQUEST_METHOD']=='POST') {
		$username=ucfirst($_POST['username']);
		$email=$_POST['email'];
		$password=$_POST['password'];
		$cpassword=$_POST['cpassword'];
		$hashedPass=sha1($password);
		
		if($password===$cpassword) {
			insertDB('users','Username','Email','Password',$username,$email,$hashedPass,'login.php');
		}
	}
?>

	<div class="login">
		<div class="container">
			<h1>Register</h1>
			<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
				<label>Username:</label>
				<input type="text" name="username" required="required">
				<label>E-mail:</label>
				<input type="email" name="email" required="required">
				<label>Password:</label>
				<input type="password" name="password" required="required">
				<label>Confirm Password:</label>
				<input type="password" name="cpassword" required="required">
				<input type="submit" value="Register">
			</form>
		</div>
	</div>

<?php
	include 'includes/templates/footer.php';
	ob_end_flush();
?>