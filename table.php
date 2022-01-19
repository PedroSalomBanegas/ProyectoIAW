<?php 

$player = [["value" => 11, "type" => "&#x2660;", "reference" => "A"], ["value" => 10, "type" => "&#x2665;", "reference" => "Q"]];
$dealer = [["value" => 7, "type" => "&#x2666;", "reference" => 7], 
["value" => 10, "type" => "&#x2663;", "reference" => 10], 
["value" => 8, "type" => "&#x2660;", "reference" => 8]];

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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/gameTable.css">
    <title>Document</title>
</head>
<body>
    <div class="table">
        <div class="score">
            <h2>Player: <span><?php echo arrayToBJ($player)?></span></h2>
            <h2>Dealer: <span><?php echo arrayToBJ($dealer) ?></span></h2>
        </div>
    </div>
    <div class="scoreTableContainer">
        <table class="scoreTable">
            <thead>
                <tr>
                    <th>Player</th>
                    <th>Score</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>You</td>
                    <td><?php echo countCards($player) ?></td>
                </tr>
                <tr>
                    <td>Dealer</td>
                    <td><?php echo countCards($dealer) ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>