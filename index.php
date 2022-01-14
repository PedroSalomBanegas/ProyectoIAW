<?php 

// ------------------------          
//          NOTAS
// No se van borrar las cookies si se utilizará alguna función que las genere (ej: begin, hit, etc...)
// ¿Solución?
// Para evitar que alguien no ejecute la web con una acción directa y rompa la ejecución del juego, poner una cookie de control que se genere con ?action=new??
// ------------------------

session_start();

include('includes.php');
include('bot.php');

// -- EXPERIMENTAL!! --
if(isset($_GET['action']) && isset($_SESSION['token'])){
    if ($_GET['action'] == "new") {
        $gameDeck = $deck;
    } elseif (isset($_SESSION['status'])) {
        $gameDeck = json_decode($_COOKIE['deck'], true);
    }
}
// -- EXPERIMENTAL !! --

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

$turn = true;

if(isset($_GET['action']) && isset($_SESSION['token'])){
    $action = $_GET['action'];

    if($action == "hit" && isset($_SESSION['status'])){
        drawCardF();
    } elseif ($action == "end" && isset($_SESSION['status'])) {
        setcookie('deck', '', 0);
        setcookie('playerHand', '', 0);
        setcookie('botHand', '', 0);
    } elseif ($action == "new" && !isset($_SESSION['status'])) {
        begin();
    }
} elseif (isset($_GET['action']) && !isset($_SESSION['token']) && !isset($_SESSION['status'])) {
    clearCookies();
} else {
    //First time enter 
    clearCookies();
    // setcookie('token', true, (time()+3600*24*30)); //HACERLO CON SESIONES!!!!
    $_SESSION['token']=true;
}

function drawCard() {
    // Author: Pedro
    global $gameDeck;

    $randomCard = rand(0, count($gameDeck) - 1);
    saveCards($gameDeck[$randomCard]);
    unset($gameDeck[$randomCard]);
    $gameDeck = array_values($gameDeck);
    setcookie("deck", json_encode($gameDeck), (time()+3600*24*30));
}

function drawCardF() {
    // Author: Pedro
    global $gameDeck;
    global $playerHand;
    global $botHand;

    $randomCard = rand(0, count($gameDeck) - 1);
    saveCards($gameDeck[$randomCard]);
    unset($gameDeck[$randomCard]);
    $gameDeck = array_values($gameDeck);
    setcookie("deck", json_encode($gameDeck), (time()+3600*24*30));

    echo "<h2>Player Hand</h2> <br>\n";
    print_r($playerHand);
    echo "<h2>Bot Hand</h2> <br>\n";
    print_r($botHand);
}

function begin() {
    //Author: Jaime
    setcookie('playerHand', '', 0);
    setcookie('botHand', '', 0);

    global $turn;
    global $playerHand;
    global $botHand;

        for ($i=0; $i < 4 ; $i++) { 
            if ($i < 2) {
                drawCard();
            } else {
                $turn = false;
                drawCard();
            }
        }
        $turn = true;

        echo "<h2>Player Hand</h2> <br>\n";
        print_r($playerHand);
        echo "<h2>Bot Hand</h2> <br>\n";
        print_r($botHand);
    }

function saveCards($card) {
    //Author: Jaime
    global $playerHand;
    global $botHand;
    global $turn;
    
    if ($turn) {
        $playerHand[] = $card;
        setcookie("playerHand", json_encode($playerHand), (time()+3600*24*30));
    } else {
        $botHand[] = $card;
        setcookie("botHand", json_encode($botHand), (time()+3600*24*30));
    }
}

function countCards($hand) {
    $Total=0;
    $numHand = count($hand);
    for ($i=0; $i < $numHand; $i++) {
        //if ($hand[$i]["value"] = 11 && $Total > 21) {
        //    $Total = $Total + 1;
        //} else {
        $Total += $hand[$i]["value"];
        //}
    }
    return $Total;
}

function clearCookies(){
    setcookie('deck', '', 0);
    setcookie('playerHand', '', 0);
    setcookie('botHand', '', 0);
}

// echo "<br>";
// echo "<br>";
// echo "<br>";
// echo "<br>";

// echo countCards($playerHand);
// echo "<br>";
// echo countCards($botHand);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>BlackJack</h1>
    <?php 
    if(isset($_GET['action']) && isset($_SESSION['token'])){
        $action = $_GET['action'];
    
        if($action == "hit" ){
            echo '<a href="?action=hit">Hit</a>';
            echo '<br>';
            echo '<a href="?action=end">End Game</a>';
        } elseif ($action == "end") {
            echo '<a href="?action=new">New Game</a>';
        } elseif ($action == "new" && !isset($_SESSION['status'])) {
            echo '<a href="?action=hit">Hit</a>';
            echo '<br>';
            echo '<a href="?action=end">End Game</a>';
            $_SESSION['status']=true; //Generate here for security
        } else {
            echo '<h2>It seems that something has gone wrong...</h2>';
            echo '<a href="index.php">Return</a>';
        }
    
    } elseif(isset($_GET['action']) && !isset($_SESSION['token']) && !isset($_SESSION['status'])) { 
        echo '<h2>It seems that something has gone wrong...</h2>';
        echo '<a href="index.php">Return</a>';
    } else {
        echo '<a href="?action=new">New Game</a>';
    }
    ?>
</body>
</html>