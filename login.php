<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="css/login.css">
	<title>Document</title>
</head>
<body>
<?php
require('mysql.php');
session_start();
if (isset($_SESSION['fname'])) {
	header("Location: index.php");
}
// If form submitted, insert values into the database.
if (isset($_POST['fname'])){
    // removes backslashes
	$username = stripslashes($_REQUEST['fname']);
	$password = stripslashes($_REQUEST['password']);
    //escapes special characters in a string
	$username = mysqli_real_escape_string($dbc,$username);
	$password = mysqli_real_escape_string($dbc,$password);
	//Checking is user existing in the database or not
    $query = "SELECT * FROM `users` WHERE name='$username' AND password=SHA2($password,512)";
	$result = mysqli_query($dbc,$query) or die(mysqli_error($err));
	$rows = mysqli_num_rows($result);
        if($rows==1){
	    $_SESSION['username'] = $username;
            // Redirect user to index.php
	    header("Location: index.php");
         }else{
	echo "<div class='form'>
<h3>Username/password is incorrect.</h3>
<br/>Click here to <a href='login.php'>Login</a></div>";
		 }
	}else{
?>
<div class="form">
	<h1>Log In</h1>
	<form action="login.php" method="post" name="login" class="formInputs">
		<input type="text" name="fname" placeholder="Username" required />
		<input type="password" name="password" placeholder="Password" required />
		<input name="submit" type="submit" value="Login" />
	</form>
	<p>Not registered yet? <a href='sign_up.php'>Register Here</a></p>
</div>
<?php }  ?>
</body>
</html>