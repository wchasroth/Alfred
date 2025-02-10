<?php

namespace CharlesRothDotNet\Alfred;

class CookieVerifier {

   public static function getEmail(CookieBoss $boss, $cookieName, $redirectOnFail = "index.php"): string {
      $email = $boss->getValueFromHashedCookie($cookieName);
      if (empty($email))  Html::redirectTo($redirectOnFail);
      return $email;
   }

}