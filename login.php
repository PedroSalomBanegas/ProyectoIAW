<?php
session_start();
require('mysql.php');
if (isset($_SESSION['username'])) {
	header("Location: index.php");
}
// If form submitted, insert values into the database.
if (isset($_POST['username'])){
    // removes backslashes
	$username = stripslashes($_REQUEST['username']);
	$password = stripslashes($_REQUEST['password']);
    //escapes special characters in a string
	$username = mysqli_real_escape_string($dbc,$username);
	$password = mysqli_real_escape_string($dbc,$password);
	//Checking is user existing in the database or not
    $query = "SELECT * FROM users WHERE name='$username' AND password=SHA2('$password',512)";
	$result = mysqli_query($dbc,$query) or die(mysqli_error($dbc));
	$rows = mysqli_num_rows($result);
        if($rows==1){
	    $_SESSION['username'] = $username;
            // Redirect user to index.php
	    header("Location: index.php");
         }else{
            $_SESSION['errorLogin'] = true;
            header('location: login.php');
		 }
	}else{
?>
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
<div class="form">
    <?php if(isset($_SESSION['errorLogin'])) { 
        echo "<h2 style='color: red'>Username or password incorrect</h2>"; 
        $_SESSION = [];
    } ?>
	<h1>Log In</h1>
	<form action="login.php" method="post" name="login" class="formInputs">
		<input type="text" name="username" placeholder="Username" required />
		<input type="password" name="password" placeholder="Password" required />
		<input name="submit" type="submit" value="Login" />
	</form>
	<p>Not registered yet? <a href='sign_up.php'>Register Here</a></p>
</div>
<?php }  ?>
</body>
</html>