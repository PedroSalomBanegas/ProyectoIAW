<?php
$page_title = 'Sign Up';
require('./mysql.php'); 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {


	$errors = [];

	if (empty($_POST['fname'])) {
		$errors[] = 'You forgot to enter your name.';
	} else {
		$fname = mysqli_real_escape_string($dbc, trim($_POST['fname']));
	}

	if (empty($_POST['email'])) {
		$errors[] = 'You forgot to enter your email address.';
	} else {
		$mail = mysqli_real_escape_string($dbc, trim($_POST['email']));
	}

	if (!empty($_POST['pass1'])) {
		if ($_POST['pass1'] != $_POST['pass2']) {
			$errors[] = 'Your password did not match the confirmed password.';
		} else {
			$p = mysqli_real_escape_string($dbc, trim($_POST['pass1']));
		}
	} else {
		$errors[] = 'You forgot to enter your password.';
	}

	if (empty($errors)) { 

		$query = "SELECT * FROM users WHERE email = '$mail' OR name = '$fname'";
		$result = mysqli_query($dbc, $query);
		if (mysqli_num_rows($result) == 0) {
			$q = "INSERT INTO users (name, email, password, balance) VALUES ('$fname', '$mail', SHA2('$p', 512),300)";
			$r = @mysqli_query($dbc, $q); 
		} else {
			$existInfo = true;
		}
		
	} else {
		echo '<h1 style="color: red">Error!</h1>
		<p class="error" style="color: white">The following error(s) occurred:<br>';
		foreach ($errors as $msg) { 
			echo " - $msg<br>\n";
		}
		echo '</p><p style="color: orange"><b>Please try again.</b></p>';

	} 

} 
?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="css/login.css">
	<title>Document</title>
</head>
<body>
<?php 
	if (isset($existInfo)) {
	    echo "<h2 style='color: red'>This email or name is already registered!</h2>";
	}
				
	if (isset($r) && $r && empty($errors)) { 
	
    	echo '<h1 style="color: white; font-weight: bold; margin-bottom: 8px; font-size: 2.75em">Welcome player!</h1>
    	<p style="color: white; font-size: 1.85em">You are now registered!</p>
        <a style="font-size: 1.5em" href="login.php">Login</a>';
    } else {

?>
	<div class="form">
		<h1>Register</h1>
		<form action="sign_up.php" method="post" class="formInputs">
			<label for="fname">Name</label>
			<input type="text" name="fname" size="15" maxlength="20" value="<?php if (isset($_POST['fname'])) echo $_POST['fname']; ?>">
			<label for="email">Email</label>
			<input type="email" name="email" size="20" maxlength="60" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>" >
			<label for="pass1">Password</label>
			<input type="password" name="pass1" size="10" maxlength="20" value="<?php if (isset($_POST['pass1'])) echo $_POST['pass1']; ?>">
			<label for="pass2">Confirm password</label>
			<input type="password" name="pass2" size="10" maxlength="20" value="<?php if (isset($_POST['pass2'])) echo $_POST['pass2']; ?>" >
			<input type="submit" name="submit" value="Register">
		</form>
	</div>
	<?php } ?>
</body>
</html>
<?php mysqli_close($dbc); ?>