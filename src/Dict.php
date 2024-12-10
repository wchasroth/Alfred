<?php
// Copyright (C) 2024 Charles Roth.  See LICENSE.

declare(strict_types=1);

namespace CharlesRothDotNet\Alfred;

class Dict {
   
   public static function value(array $dict, string $key, $defValue="") {
      if (empty($key))     return "";
      if ($dict === null)  return "";
      return isset($dict[$key]) ? $dict[$key] : $defValue;
   }
}