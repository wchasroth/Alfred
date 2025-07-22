<?php
// Copyright (C) 2024 Charles Roth.  See LICENSE.

declare(strict_types=1);

namespace CharlesRothDotNet\Alfred;

// can only contain strings, ints, or other SafeMaps.  That's it!

class SafeMap {
   private array $map;

   public function __construct() {
      $this->map = array();
   }

   public function getKeys(): array {
      return array_keys($this->map);
   }

   public function getStr(string $key): string {
      if (! $this->exists($key)) { $this->map[$key] = "";   return ""; }
      $value = $this->map[$key];
      if (is_string($value))  return $value;
      if (is_int   ($value))  return strval($value);
      return "";
   }

   public function putStr(string $key, string $value): SafeMap {
      $this->map[$key] = $value;
      return $this;
   }

   public function getInt(string $key): int {
      if (! $this->exists($key)) { $this->map[$key] = 0;    return 0; }
      $value = $this->map[$key];
      if (is_int    ($value))  return $value;
      if (is_string ($value))  return intval($value);
      return 0;
   }

   public function putInt(string $key, int $value): SafeMap {
      $this->map[$key] = $value;
      return $this;
   }

   public function addInt($key, int $value): SafeMap {
      $old = $this->getInt($key);
      $this->map[$key] = $old + $value;
      return $this;
   }

   public function getMap(string $key): SafeMap {
      if (! $this->exists($key)) {
         $newMap = new SafeMap();
         $this->map[$key] = $newMap;
         return $newMap;
      }
      $value = $this->map[$key];
      if ($value instanceof SafeMap) return $value;
      return new SafeMap(); // Hmmm...!
   }

   public function putMap(string $key, SafeMap $value): SafeMap {
      $this->map[$key] = $value;
      return $this;
   }

   public function exists(string $key): bool {
      return isset($this->map[$key])  ||  array_key_exists($key, $this->map);
   }

}
