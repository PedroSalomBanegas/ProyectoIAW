<?php
session_start();

require('userCheck.php');
//require('mysql.php');

if (isset($_GET['row'])){
    $cont = $_GET['row'];
} else {
    $cont = 0;
}

$username = $_SESSION['username'];
$query = "SELECT * FROM history WHERE user = '$username' ORDER BY id DESC LIMIT $cont,10"; //You don't need a ; like you do in SQL
$result = mysqli_query($dbc,$query);
$total = mysqli_num_rows(mysqli_query($dbc, "SELECT * FROM history WHERE user = '$username'"));

$i = 0;

mysqli_close($dbc); //Make sure to close out the database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/history.css">
    <title>Document</title>
</head>
<body>
    <?php include('components/navbar.html') ?>
    <h1>Game History</h1>
    <div class="historyContainer">
        <?php 
            while($row = mysqli_fetch_assoc($result)){   //Creates a loop to loop through results
                    // echo "<tr><td>" . $row['status'] . "</td><td>" . $row['playerTotal'] . $row['dealerTotal'] . $row['bet'] . "</td></tr>";  //$row['index'] the index here is a field name 
            
                    $color = $row['status'];

                    if ($row['status'] == 'Win') {
                        $symbol = "+";
                    } elseif ($row['status'] == 'Lose') {
                        $symbol = "-";
                    } else {
                        $symbol = "";
                    }

                    echo "<div class='historyData$color'>";
                    echo "       <span class='gameResult'>". $row['status'] . "</span>";
                    echo "       <div class='gameScore'>";
                    echo "           <span class='playerScore'>You: " . $row['playerTotal'] . "</span>";
                    echo "           <span>vs</span>";
                    echo "           <span class='DealerScore'>Dealer: " . $row['dealerTotal'] . "</span>";
                    echo "       </div>";
                    echo "       <div class='gameBet'>";
                    echo "           <span>Bet: $symbol" . $row['bet'] . "</span>";
                    echo '           <svg class="moneyIcon" xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-coin" viewBox="0 0 16 16">';
                    echo '               <path d="M5.5 9.511c.076.954.83 1.697 2.182 1.785V12h.6v-.709c1.4-.098 2.218-.846 2.218-1.932 0-.987-.626-1.496-1.745-1.76l-.473-.112V5.57c.6.068.982.396 1.074.85h1.052c-.076-.919-.864-1.638-2.126-1.716V4h-.6v.719c-1.195.117-2.01.836-2.01 1.853 0 .9.606 1.472 1.613 1.707l.397.098v2.034c-.615-.093-1.022-.43-1.114-.9H5.5zm2.177-2.166c-.59-.137-.91-.416-.91-.836 0-.47.345-.822.915-.925v1.76h-.005zm.692 1.193c.717.166 1.048.435 1.048.91 0 .542-.412.914-1.135.982V8.518l.087.02z"/>';
                    echo '               <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>';
                    echo '               <path d="M8 13.5a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11zm0 .5A6 6 0 1 0 8 2a6 6 0 0 0 0 12z"/>';
                    echo '           </svg>';
                    echo "       </div>";
                    echo "   </div>";
                    
                    $i++;
                }
            $cont += 10; 
        ?>
    </div>
    <div class="controller">
        <?php 
            if ($cont > 10) {
                echo "<a href=\"history.php?row=" . ($cont - 20) . "\">Previos page</a>";
            }

            if ($total > 10 && $cont < $total) {
                echo "<a href=\"history.php?row=$cont\">Next Page</a>";
            }
        ?>
    </div>
</body>
</html>