<?php
define('DB_USER', 'id18328178_sjo_admin');
define('DB_PASSWORD', 'HA_S}#CR}*)54<If');
define('DB_HOST', 'localhost');
define('DB_NAME', 'id18328178_blackjackdb');
$dbc = @mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) OR die('Could not connect to MySQL: ' . mysqli_connect_error());
mysqli_set_charset($dbc, 'utf8');
?>