<?php 

include('includes.php');
include('bot.php');

$gameDeck = $deck;
$playerHand = [];
$botHand = [];
$turn = true;

function drawCard() {
    // Author: Pedro
    global $gameDeck;

    $randomCard = rand(0, count($gameDeck) - 1);
    // echo count($gameDeck);
    // echo "<br>";
    // echo $gameDeck[$randomCard]['reference'] . " of " . $gameDeck[$randomCard]['type']; 
    // echo "<br><br>";
    
    saveCards($gameDeck[$randomCard]);

    unset($gameDeck[$randomCard]);
    $gameDeck = array_values($gameDeck);
    // print_r($gameDeck[$randomCard]); 
    // print_r($gameDeck);
}

function begin() {
    //Author: Jaime
    global $turn;
        for ($i=0; $i < 4 ; $i++) { 
            if ($i < 2) {
                drawCard();
            } else {
                $turn = false;
                drawCard();
            }
        }
        $turn = true;
    }

// begin();

function saveCards($card) {
    //Author: Jaime
    global $playerHand;
    global $botHand;
    global $turn;
    
    if ($turn) {
        $playerHand[] = $card;
    } else {
        $botHand[] = $card;
    }
    echo "<h2>Player</h2>";
    print_r($playerHand);
    echo "<h2>Bot</h2>";
    print_r($botHand);
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

begin();
echo "<br>";
echo "<br>";
echo "<br>";
echo "<br>";

echo countCards($playerHand);
echo "<br>";
echo countCards($botHand);

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
    <button id="draw">Draw Card</button>
</body>

<script>
    const drawButton = document.getElementById("draw");

    drawButton.addEventListener("click", () => {
        <?php drawCard() ?>
    });
</script>

</html>