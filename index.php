<?php 

// ------------------------          
//          NOTAS
// No se van borrar las cookies si se utilizará alguna función que las genere (ej: begin, hit, etc...)
// ¿Solución?
// Para evitar que alguien no ejecute la web con una acción directa y rompa la ejecución del juego, poner una cookie de control que se genere con ?action=new??
// -------------------------

session_start();

include('includes.php');
include('bot.php');

$_SESSION['turn'] = true;

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
        $_SESSION['fname'] = $username;
        $_SESSION['token']=true;
        header('Location: http://localhost/ProyectoIAW/index.php');
    }
} elseif (isset($_GET['action']) && !isset($_SESSION['token']) && !isset($_SESSION['status'])) {
    clearCookies();
} else {
    $username = $_SESSION['fname'];
    //First time enter 
    clearCookies();
    // setcookie('token', true, (time()+3600*24*30)); //HACERLO CON SESIONES!!!!
    $_SESSION=[];
    $_SESSION['fname'] = $username;
    $_SESSION['token']=true;
}

function drawCard() {
    // Author: Pedro
    global $gameDeck;
    global $playerHand;
    global $botHand;

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

    global $playerHand;
    global $botHand;

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
    // print_r($hand);
    // echo "<br><br>";
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
    global $playerHand;
    global $botHand;
    if ($turn) {
        bot();
    } else {
        header('Location: http://localhost/ProyectoIAW/index.php?action=end');
    }
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

function arrayToBJ($hand) {
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
        include('table.html');
        echo '<a href="?action=reset">Play again</a>';
    } elseif ($action == "hit" && isset($_SESSION['status'])){
            include('table.html');
            echo '<a href="?action=hit">Hit</a>';
            echo '<a href="?action=stand">Stand</a>';   
    } elseif ($action == "new" && !isset($_SESSION['status'])) {
            include('table.html');
            echo '<a href="?action=hit">Hit</a>';
            echo '<a href="?action=stand">Stand</a>';
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
        echo '<a href="?action=new">New Game</a>';
    }
    // echo $_SESSION['fname'];   
?>
</body>
</html>