<?php 

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_SESSION['bet'] = $_POST['bet'];
    header('location: http://localhost/ProyectoIAW/index.php?action=new');
    exit();
}

include('userCheck.php');
include('includes.php');
include('bot.php');

$_SESSION['turn'] = true;

if(isset($_GET['action']) && isset($_SESSION['token'])){
    if ($_GET['action'] == "new") {
        $gameDeck = $deck;
    } elseif (isset($_SESSION['status'])) {
        $gameDeck = json_decode($_COOKIE['deck'], true);
    }
}

if(isset($_COOKIE['playerHand']) && isset($_SESSION['token'])){
    $playerHand = json_decode($_COOKIE['playerHand'], true);
} else {
    $playerHand = [];
}

if(isset($_COOKIE['botHand']) && isset($_SESSION['token'])){
    $botHand = json_decode($_COOKIE['botHand'], true);
} else {
    $botHand = [];
}


if(isset($_GET['action']) && isset($_SESSION['token'])){
    $action = $_GET['action'];

    if($action == "hit" && isset($_SESSION['status'])){
        drawCard();
    } else if ($action == "stand" && isset($_SESSION['status'])){
        echo $_SESSION['turn'];
        stand($_SESSION['turn']);
    } elseif ($action == "new" && !isset($_SESSION['status'])) {
        $_SESSION['turn'] = true;
        begin();
    } elseif ($action == "reset" && isset($_SESSION['status'])) {
        clearCookies();
        $_SESSION=[];
        $_SESSION['username'] = $username;
        $_SESSION['token']=true;
        header('Location: http://localhost/ProyectoIAW/index.php');
    }
} elseif (isset($_GET['action']) && !isset($_SESSION['token']) && !isset($_SESSION['status'])) {
    clearCookies();
} else {
    //First time enter 
    $username = $_SESSION['username'];
    clearCookies();
    $_SESSION=[];
    $_SESSION['username'] = $username;
    $_SESSION['token']=true;
}

function drawCard() {
    // Author: Pedro
    global $gameDeck;
    global $playerHand;

    $randomCard = rand(0, count($gameDeck) - 1);
    saveCards($gameDeck[$randomCard]);
    unset($gameDeck[$randomCard]);
    $gameDeck = array_values($gameDeck);
    setcookie("deck", json_encode($gameDeck), (time()+3600*24*30));

    // stand if user have more than 21
    if ($_SESSION['turn'] && countCards($playerHand) >= 21) {
        stand($_SESSION['turn']);
    } 
}

function begin() {
    //Author: Jaime
    setcookie('playerHand', '', 0);
    setcookie('botHand', '', 0);

        for ($i=0; $i < 4 ; $i++) { 
            if ($i < 2) {
                drawCard();
            } else {
                $_SESSION['turn'] = false;
                drawCard();
            }
        }
        $_SESSION['turn'] = true;
    }

function saveCards($card) {
    //Author: Jaime
    global $playerHand;
    global $botHand;
    
    if ($_SESSION['turn']) {
        $playerHand[] = $card;
        setcookie("playerHand", json_encode($playerHand), (time()+3600*24*30));
    } else {
        $botHand[] = $card;
        setcookie("botHand", json_encode($botHand), (time()+3600*24*30));
    }
}

function countCards($hand) {
    //Author: Jaime
    $Total=0;
    $numHand = count($hand);

    for ($i=0; $i < $numHand; $i++) {
        if ($hand[$i]["value"] == 11 && $Total > 21) {
            $Total = $Total + 1;
        } else {
        $Total += $hand[$i]["value"];
        }
        if ($Total > 21) {
            $Total=0;
            for ($i=0; $i < $numHand; $i++) { 
                if ($hand[$i]["value"] == 11) {
                    $Total = $Total + 1;
                } else {
                    $Total += $hand[$i]["value"]; 
                }
            }
        }
    }
    
    if ($Total == 2) {
        return 12;
    } else {
        return $Total;
    }
}

function stand($turn){
    //Author: Pedro
    if ($turn) {
        bot();
    } else {
        header('Location: http://localhost/ProyectoIAW/index.php?action=end');
    }
}

function clearCookies(){
    //Author: Pedro
    setcookie('deck', '', 0);
    setcookie('playerHand', '', 0);
    setcookie('botHand', '', 0);
}

function updateBalance(){
    //Author: Pedro
    global $userInfo;
    global $dbc;
    $gameResult = endgame();
    $username = $userInfo['name'];
    $balance = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT balance FROM users WHERE name = '$username'"));
    $balance = $balance['balance'];

    if ($gameResult == "Dealer Wins") {
        $balanceUpdate = $balance - $_SESSION['bet'];
        $query = "UPDATE users SET balance=$balanceUpdate WHERE name = '$username'";
        mysqli_query($dbc, $query);
    } elseif ($gameResult == "Player Wins") {
        $balanceUpdate = $balance + $_SESSION['bet'];
        $query = "UPDATE users SET balance=$balanceUpdate WHERE name = '$username'";
        mysqli_query($dbc, $query);
    }

    gameHistory($username, $_SESSION['bet'], $gameResult);
}

function gameHistory($user, $bet, $result){
    //Author: Pedro
    global $playerHand;
    global $botHand;
    global $dbc;

    $userTotal = countCards($playerHand);
    $dealerTotal = countCards($botHand);
    
    switch ($result) {
        case 'Dealer Wins':
            $result = "Lose";
            break;

        case 'Player Wins':
            $result = "Win";
            break;
        
        default:
            $result = "Tie";
            break;
    }

    $query = "INSERT INTO history (status, playerTotal, dealerTotal, bet, user) VALUES ('$result', '$userTotal', '$dealerTotal', '$bet', '$user')";
    mysqli_query($dbc, $query);
    mysqli_close($dbc); 

}

function arrayToBJ($hand) {
    //Author: Jaime
    $numHand = count($hand);
    $string = "";
    for ($i=0; $i < $numHand; $i++) { 
        if ($numHand-1 == $i ) {
        $string .= $hand[$i]["type"].$hand[$i]["reference"];
        } else {
            $string .= $hand[$i]["type"].$hand[$i]["reference"]." , ";
        }
    }
    return $string;
}

if(isset($_GET['action']) && isset($_SESSION['token'])){
    $action = $_GET['action'];

    if ($_GET['action'] == "end" && isset($_SESSION['status'])) {
        if (!$_SESSION['status']) {
            header('location:index.php');
            exit();
        }
        updateBalance();
        include('table.html');
        echo '<div class="controllers">';
        echo '<a href="?action=reset">Play again</a>';
        echo "</div>";
        $_SESSION['status']=false;
    } elseif ($action == "hit" && isset($_SESSION['status'])){
            include('table.html');
            echo '<div class="controllers">';
            echo '<a href="?action=hit">Hit</a>';
            echo '<a href="?action=stand">Stand</a>';
            echo '</div>';
    } elseif ($action == "new" && !isset($_SESSION['status'])) {
            include('table.html');
            echo '<div class="controllers">';
            echo '<a href="?action=hit">Hit</a>';
            echo '<a href="?action=stand">Stand</a>';
            echo '</div>';
            $_SESSION['status']=true; //Generate here for security
    } else {
            include('table.html');
            echo '<h2>It seems that something has gone wrong...</h2>';
            echo '<a href="index.php">Return</a>';
    }
    
    } elseif (isset($_GET['action']) && !isset($_SESSION['token']) && !isset($_SESSION['status'])) { 
        echo '<h2>It seems that something has gone wrong...</h2>';
        echo '<a href="index.php">Return</a>';
    } else {
        include('table.html');
        ?>
        <form action="index.php" method="POST" class="betForm">
            <label>Bet amount: </label>
            <select name="bet" class="betSelect">
                <?php 
                    $betAvaible = 0;
                    while ($betAvaible <= $userInfo['balance']) {
                        if ($betAvaible >= 100) {
                            echo "<option value=$betAvaible>$betAvaible</option>";
                            $betAvaible += 50;
                        } else {
                            echo "<option value=$betAvaible>$betAvaible</option>";
                            $betAvaible += 10;
                        }
                    }
                ?>
            </select>
            <input type="submit" value="Play" class="playButton">
        </form>
        <?php
    }
?>
</body>
</html>