<?php
   function bot() {
       global $botHand;
       global $turn;
       $totalBot = countCards($botHand);
       $_SESSION['turn'] = false;
       //print_r($botHand);
       if ($totalBot == 21 ) {
           stand($_SESSION['turn']);
       } else {
           while ($totalBot < 21) {
               drawCard();
               $totalBot = countCards($botHand);
               if ($totalBot > 21) {
                   $error = rand(1, 10);
                   if ($error >= 5) {
                        unset($botHand[count($botHand) - 1]);
                        $botHand = array_values($botHand);
                        setcookie("botHand", json_encode($botHand), (time()+3600*24*30));
                        stand($_SESSION['turn']);
                   } else {
                        setcookie("botHand", json_encode($botHand), (time()+3600*24*30));
                        stand($_SESSION['turn']);
                   }
                }
           }
       }
   }

   function endgame() {
       echo "<h1>ENDGAME</h1>";
       global $playerHand;
       global $botHand;

       print_r($playerHand);
       echo "<br><br>";
       print_r($botHand);
       $finalPlayer = countCards($playerHand);
       $finalBot = countCards($botHand);

       if ($finalPlayer > 21) {
           if ($finalBot > 21) {
               return "Tie";
           } else {
               return "Bot Wins";
           }
       } elseif ($finalPlayer == $finalBot) {
            return "Tie";
       } else {
           if ($finalBot > 21) {
               return "Player Wins";
           } else {
               if ($finalPlayer > $finalBot) {
                   return "PlayerWins";
               } else {
                   return "Bot Wins";
               }
           }
       }
   }
?>