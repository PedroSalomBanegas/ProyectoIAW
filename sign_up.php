<?php
$page_title = 'Sign Up';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	require('./mysql.php'); 

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

		$query = "SELECT * FROM users WHERE email = '$mail'";
		$result = mysqli_query($dbc, $query);
		if (mysqli_num_rows($result) == 0) {
			$q = "INSERT INTO users (name, email, password, balance) VALUES ('$fname', '$mail', SHA2('$p', 512),0)";
			$r = @mysqli_query($dbc, $q); 
			if ($r) { 
	
				echo '<h1>Thank you!</h1>
			<p>You are now registered!</p><p><br></p>';
	
			} else { 
				echo '<h1>System Error</h1>
				<p class="error">You could not be registered due to a system error.</p>';
	
				echo '<p>' . mysqli_error($dbc) . '<br><br>Query: ' . $q . '</p>';
	
			} 
	
			mysqli_close($dbc); 
			exit();
		} else {
			$existRegister = true;
		}
	} else {

		echo '<h1>Error!</h1>
		<p class="error">The following error(s) occurred:<br>';
		foreach ($errors as $msg) { 
			echo " - $msg<br>\n";
		}
		echo '</p><p>Please try again.</p><p><br></p>';

	} 

	mysqli_close($dbc); 

} 
?>
<h1>Register</h1>
<form action="sign_up.php" method="post">
	<p>Name: <input type="text" name="fname" size="15" maxlength="20" value="<?php if (isset($_POST['fname'])) echo $_POST['fname']; ?>"></p>
	<p>Email: <input type="email" name="email" size="20" maxlength="60" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>" > </p>
	<p>Password: <input type="password" name="pass1" size="10" maxlength="20" value="<?php if (isset($_POST['pass1'])) echo $_POST['pass1']; ?>" ></p>
	<p>Confirm Password: <input type="password" name="pass2" size="10" maxlength="20" value="<?php if (isset($_POST['pass2'])) echo $_POST['pass2']; ?>" ></p>
	<p><input type="submit" name="submit" value="Register"></p>
	<?php 
		if (isset($existRegister)) {
			echo "<h2 style='color: red'>This email is already registered!<h2>";
		}
	?>
</form>