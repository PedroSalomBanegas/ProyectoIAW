<?php
require('mysql.php');
session_start();
$username = $_SESSION['username'];
$query = "SELECT * FROM history WHERE user = '$username'"; //You don't need a ; like you do in SQL
$result = mysqli_query($dbc,$query);
$total = mysqli_num_rows($result);
if (isset($_GET['row'])){
    $cont = $_GET['row'];
} else {
    $cont = 10;
}

echo "<table>"; // start a table tag in the HTML
$i = 0;
while($row = mysqli_fetch_assoc($result)){   //Creates a loop to loop through results
    if ($i < $cont) {
echo "<tr><td>" . $row['status'] . "</td><td>" . $row['playerTotal'] . $row['dealerTotal'] . $row['bet'] . "</td></tr>";  //$row['index'] the index here is a field name
$i++;
}
}
echo "</table>"; //Close the table in HTML
$cont += 10; 
if ($cont > 10) {
    echo "<a href=\"history.php?row=$cont\">Ver más</a>";
}
mysqli_close($dbc); //Make sure to close out the database connection
?>