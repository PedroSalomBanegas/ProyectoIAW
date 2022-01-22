<?php 
require('mysql.php');
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $query = "SELECT name, email, balance FROM users WHERE name = '$username'";
    $result = mysqli_query($dbc, $query);
    $data = mysqli_fetch_assoc($result);
    $totalRows = mysqli_num_rows($result);

    if ($totalRows > 0) {
        $userInfo = $data;
    } else {
        header('location: login.php');
    }
} else {
    header('location: login.php');
}

?>