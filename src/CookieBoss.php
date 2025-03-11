<?php
declare(strict_types=1);

namespace CharlesRothDotNet\Alfred;

use DateTimeZone;
use DateTime;

class CookieBoss {
    private string $domain;
    private string $path;
    private string $securekey;
    private bool   $isLocal;
    private DateTimeZone $tz;

    public function __construct(string $domain, string $path, string $securekey, string $timezone='America/New_York') {
        $this->domain    = $domain;
        $this->path      = $path;
        $this->securekey = $securekey;
        $this->isLocal   = $domain == "localhost";
        $this->tz        = new DateTimeZone($timezone);
    }

    public function setWithHash (string $name, string $value, int $expiryDays): void {
        $hashedValue = $this->makeSecureHash($value);
        $this->set($name, "$value:$hashedValue", $expiryDays);
    }

    public function clear(string $name): void {
        setCookie($name, "", -1);
    }

    public function getValueFromHashedCookie(string $name): string {
        $cookieValue = ($_COOKIE[$name] ?? "") . "::";
        $cookieParts = explode(":", $cookieValue, 3);
        $result = $cookieParts[0];
        return ($cookieParts[1] == $this->makeSecureHash($result)) ? $result : "";
    }

    public function set(string $name, string $value, int $expiryDays): void {
        $expiryTime = time() + 86400 * $expiryDays;
        if ($this->isLocal)  setcookie($name, $value, $expiryTime);
        else                 setcookie($name, $value, $expiryTime, $this->path, $this->domain);
    }

    private function makeSecureHash($value): string {
      $date  = new DateTime("now", $this->tz);
      $today = $date->format('Y-m-d');
      return hash("sha256", $value . $today . $this->securekey);
   }

}