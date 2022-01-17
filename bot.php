<?php
   function bot() {
       global $botHand;
       global $turn;
       $totalBot = countCards($botHand);
       //print_r($botHand);
       if ($totalBot == 21 ) {
       } else {
           $turn = false;
           while ($totalBot < 21) {
               drawCard();
               $totalBot = countCards($botHand);
           }
           $turn = true;
       }
   }

   function endgame() {
       global $playerHand;
       global $botHand;

       $finalPlayer = countCards($playerHand);
       $finalBot = countCards($botHand);

       if ($finalPlayer > 21) {
           if ($totalBot > 21) {
               return "Tie";
           } else {
               return "Bot Wins";
           }
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