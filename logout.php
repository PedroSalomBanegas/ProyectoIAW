<?php
    session_start();

    $_SESSION=[];

    setcookie('deck', '', 0);
    setcookie('playerHand', '', 0);
    setcookie('botHand', '', 0);

    if (!isset($_SESSION['username'])) {
        header('location: login.php');
    }
?>