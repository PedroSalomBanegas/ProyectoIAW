<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Login</title>
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
        //escapes special characters in a string
	$username = mysqli_real_escape_string($dbc,$username);
	$password = stripslashes($_REQUEST['password']);
	$password = mysqli_real_escape_string($dbc,$password);
	//Checking is user existing in the database or not
        $query = "SELECT * FROM `users` WHERE name='$username'and password=SHA2($password,512)";
	$result = mysqli_query($dbc,$query) or die(mysql_error());
	$rows = mysqli_num_rows($result);
        if($rows==1){
	    $_SESSION['fname'] = $username;
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
<form action="login.php" method="post" name="login">
<input type="text" name="fname" placeholder="Username" required />
<input type="password" name="password" placeholder="Password" required />
<input name="submit" type="submit" value="Login" />
</form>
<p>Not registered yet? <a href='sign_up.php'>Register Here</a></p>
</div>
<?php } ?>
</body>
</html>