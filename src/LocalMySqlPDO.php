<?php

namespace CharlesRothDotNet\Alfred;

use CharlesRothDotNet\Alfred\Str;
use \PDO;
use \PDOException;
use \PDOStatement;

// Convenience class, extending PDO.
//   Assumes localhost and MySQL, and DOESN'T throw an exception, but
//   rather has a failed/error status and info.
//
//   Also adds some useful utilities for working with PDOStatement's.
//
// Copyright (C) 2024 Charles Roth, all rights reserved.
// Released as open-source under the MIT license.

class LocalMySqlPDO extends PDO {
   private string $error;
 
   function __construct(string $dbname, string $dbuser, string $dbpw) {
      $this->error = "";
      $options = [PDO::ATTR_EMULATE_PREPARES => true];
      $dsn = "mysql:host=localhost;dbname=$dbname;port=3306;charset=utf8";
      try {
         parent::__construct("mysql:host=localhost;dbname=$dbname;port=3306;charset=utf8", $dbuser, $dbpw);
      }
      catch (PDOException $e) {
         $this->error = "DSN: $dsn, user=$dbuser, dbpw=$dbpw, error = " . $e->getMessage();
      }
   }

   function failed(): bool {
      return ! empty($this->error);
   }

   function getError(): string {
      return $this->error;
   }

   function getRawSql (PDOStatement &$stmt): string {
      ob_start();
      $stmt->debugDumpParams();
      $text = ob_get_clean();
      $text = Str::substringBetween ($text, "Sent SQL:", "\n");
      $text = Str::substringAfter   ($text, "] ");
      return $text;
   }

   function bindKeyValueArray (PDOStatement &$stmt, array $keysToValues): void {
      foreach ($keysToValues as $key => $value) {
         $stmt->bindValue($key, $value, (is_int($value) ? \PDO::PARAM_INT : \PDO::PARAM_STR));
      }
   }

}
