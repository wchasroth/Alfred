<?php
declare(strict_types=1);

namespace CharlesRothDotNet\Alfred;

class IpAddress {
   private string $ip;
   private string $method;

   function __construct() {
      $this->ip = $_SERVER['HTTP_CLIENT_ID'] ?? '';
      if (! empty($this->ip)) {  $this->method = 'client';   return; }

      $this->ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? '';
      if (! empty($this->ip)) {
         $ipList = explode(',', $this->ip);
         $this->ip = trim($ipList[0]);
         $this->method = 'XFF';
         return;
      }

      $this->ip = $_SERVER['REMOTE_ADDR'] ?? '';
      $this->method = 'remote';
   }

   public function getIp(): string {
      return $this->ip;
   }

   public function getMethod(): string {
      return $this->method;
   }
}