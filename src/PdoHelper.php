<?php
// Copyright (C) 2024 Charles Roth.  See LICENSE.

declare(strict_types=1);

namespace CharlesRothDotNet\Alfred;

use \PDOStatement;
use \PDO;
use CharlesRothDotNet\Alfred\Str;

class PdoHelper {

   public static function bindKeyValueArray (PDOStatement &$stmt, array $keysToValues): void {
      foreach ($keysToValues as $key => $value) {
         $stmt->bindValue($key, $value, (is_int($value) ? \PDO::PARAM_INT : \PDO::PARAM_STR));
      }
   }

   public static function getRawSql (PDOStatement &$stmt): string {
      ob_start();
      $stmt->debugDumpParams();
      $text = ob_get_clean();
      $text = Str::substringBetween ($text, "Sent SQL:", "\n");
      $text = Str::substringAfter   ($text, "] ");
      return $text;
   }

   public static function makeMySqlPDO (string $host, string $dbname, string $dbuser, string $dbpw): PDO {
      $options = [PDO::ATTR_EMULATE_PREPARES => true];
      return new PDO("mysql:host=$host;dbname=$dbname;port=3306;charset=utf8", $dbuser, $dbpw, $options);
   }
}
