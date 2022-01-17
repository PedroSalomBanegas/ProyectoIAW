<?php
   function bot() {
       global $botHand;
       global $turn;
       $totalBot = countCards($botHand);
       //print_r($botHand);
       if ($totalBot == 21 ) {
           stand($_SESSION['turn']);
       } else {
           $_SESSION['turn'] = false;
           while ($totalBot < 21) {
               drawCardF();
               $totalBot = countCards($botHand);
           }
           $turn = true;
       }
   }

   function endgame() {
       echo "<h1>ENDGAME</h1>";
       global $playerHand;
       global $botHand;

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