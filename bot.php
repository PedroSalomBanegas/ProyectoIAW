<?php
   function bot() {
       global $botHand;
       $totalBot = countCards($botHand);
       $_SESSION['turn'] = false;

       if ($totalBot == 21 ) {
           stand($_SESSION['turn']);
       } else {
           while ($totalBot < 21) {
               drawCard();
               $totalBot = countCards($botHand);
               if ($totalBot > 21) {
                   $error = rand(1, 10);
                   if ($error > 3) {
                        unset($botHand[count($botHand) - 1]);
                        $botHand = array_values($botHand);
                        $_SESSION['botHand'] = json_encode($botHand);
                        stand($_SESSION['turn']);
                   } else {
                        $_SESSION['botHand'] = json_encode($botHand);
                        stand($_SESSION['turn']);
                   }
                } elseif ($totalBot == 21) {
                    stand($_SESSION['turn']);
                }
           }
       }
   }

   function endgame() {
       global $playerHand;
       global $botHand;

       $finalPlayer = countCards($playerHand);
       $finalBot = countCards($botHand);

       if ($finalPlayer > 21) {
           if ($finalBot > 21) {
               return "Tie";
           } else {
               return "Dealer Wins";
           }
       } elseif ($finalPlayer == $finalBot) {
            return "Tie";
       } else {
           if ($finalBot > 21) {
               return "Player Wins";
           } else {
               if ($finalPlayer > $finalBot) {
                   return "Player Wins";
               } else {
                   return "Dealer Wins";
               }
           }
       }
   }
?>