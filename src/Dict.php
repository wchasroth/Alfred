<?php
// Copyright (C) 2024 Charles Roth.  See LICENSE.

declare(strict_types=1);

namespace CharlesRothDotNet\Alfred;

class Dict {
   
   public static function value(array $dict, $key, string $defValue="", string $append = "") {
      if ($key  !== 0  &&  empty($key))   return "";   // allow a numeric key of 0 !
      if ($dict === null)                 return "";
      return isset($dict[$key]) ? $dict[$key] . $append : $defValue;
   }

   public static function getArray(array $dict, string $key): array {
       if ($dict === null)  return [];
       if (empty($key))     return [];
       return isset($dict[$key]) ? $dict[$key] : [];
   }
}
