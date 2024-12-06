<?php
// Copyright (C) 2024 Charles Roth.  See LICENSE.

declare(strict_types=1);


namespace CharlesRothDotNet\Alfred;

use \PDOStatement;

class PdoHelper {

   public static function bindKeyValueArray (PDOStatement &$stmt, array $keysToValues): void {
      foreach ($keysToValues as $key => $value) {
         $stmt->bindValue($key, $value, (is_int($value) ? \PDO::PARAM_INT : \PDO::PARAM_STR));
      }
   }
}
