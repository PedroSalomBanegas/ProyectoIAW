<?php
   function bot() {
       global $botHand;
       $totalBot = countCards($botHand);
       if ($totalBot == 21 ) {
           return $totalBot;
       } else {
           while ($totalBot < 21) {
               drawcard();
               $totalBot = countCards($botHand);
           }
           return $totalBot;
       }
   }
?>