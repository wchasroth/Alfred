<?php

namespace CharlesRothDotNet\Alfred;

use \PDO;
use \PDOException;
use \PDOStatement;

// WARNING WARNING WARNING!
// This should probably be rewritten to use composition instead of inheritance, with facade calls
// for pass-through.  The current constructor fails badly if the DSN is wrong, which
// was NOT the original intent.  Sigh!

// Convenience class, extending PDO.
//   Assumes MySQL, and defaults to localhost, and the constructor DOESN'T throw an exception, but
//   rather has a failed/error status and info.
//
//   Also adds some useful utilities, e.g.
//      run()               -- vastly simplifies ordinary SQL query execution  (see the tests for examples)
//      runSF()             -- uses SqlFields to simplify construction and running of SQL prepared statements
//      getRawSql()         -- see the actual SQL that a prepared statement generated, including filled-in values!
//      bindKeyValuePairsToStatementByType() -- bind multiple values with different types(!) in one call.
//
//   Note that run() and runSF() return a PdoRunResult object, which is a much object-oriented approach
//   to dealing with the results (success/fail, error info, rows, last insert id) than the traditional
//   ask-lots-of-questions-of-the-pdo approach.
//
// Copyright (C) 2024 Charles Roth, all rights reserved.
// Released as open-source under the MIT license.
class AlfredPDO extends PDO {
   private string $error;
 
   // Note: this can be called in a multiple-constructor-LIKE way:
   //      new AlfredPDO("name", "user", "password)
   //  OR  new AlfredPDO("name,user,password")
   function __construct(string $dbname, string $dbuser = "", string $dbpw = "", string $dbhost = "localhost", int $dbport = 3306) {

      if (empty($dbuser) && empty($dbpw)) {
         $parts = Str::split($dbname, ",");
         if (sizeof($parts) == 3) {
            $dbname = $parts[0];
            $dbuser = $parts[1];
            $dbpw   = $parts[2];
         }
         else {
            $this->error = "Bad arguments: $dbname";   // Bad, since base object is never constructed!
            return;
         }
      }

      $this->error = "";
      $dsn = "mysql:host=$dbhost;dbname=$dbname;port=$dbport;charset=utf8";
      try {
         parent::__construct($dsn, $dbuser, $dbpw, [PDO::ATTR_EMULATE_PREPARES => true]);
      }
      catch (PDOException $e) {
         $this->error = "DSN: $dsn, user=$dbuser, dbpw=$dbpw, error = " . $e->getMessage();
      }
   }

   public function runSF(string $prefix, string $suffix, SqlFields $fields, bool $getRawSql=false): PdoRunResult {
      $sql = $fields->makePreparedStatement($prefix, $suffix);
      return $this->run($sql, $fields->getKeyValuePairs(), $getRawSql);
   }

   public function run(string $sql, array $keyValueParams = [], bool $getRawSql = false): PdoRunResult {
      $stm = $this->prepare($sql);
      $this->bindKeyValuePairsToStatementByType($stm, $keyValueParams);

      $lastId = -1;
      try {
         $stm->execute();
         $lastId = PDO::lastInsertId();
      }
      catch (PDOException $e) {
         $result = new PdoRunResult([], $e->getMessage(), $getRawSql ? $this->getRawSql($stm) : "");
         $stm->closeCursor();
         return $result;
      }

      $rows  = ($this->isSelect($sql) ? $stm->fetchAll(PDO::FETCH_ASSOC) : []);
      $error = (sizeof($rows) == 0  ?  Str::replaceAll($stm->errorCode(), "00000", "") : "");

      $stm->closeCursor();
      return new PdoRunResult($rows, $error, $getRawSql ? $this->getRawSql($stm) : "", $lastId);
   }

   // Only visible for testing!  Do not use otherwise!
   public function bindKeyValuePairsToStatementByType (PDOStatement &$stmt, array $keysToValues): void {
      foreach ($keysToValues as $key => $value) {
         $stmt->bindValue($key, $value, (is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR));
      }
   }


   public function failed(): bool {
      return ! empty($this->error);
   }

   public function succeeded(): bool {
      return empty($this->error);
   }

   public function getError(): string {
      return $this->error;
   }

   // Only visible for testing!  Do not use otherwise!
   public function getRawSql (PDOStatement &$stmt): string {
      ob_start();
      $stmt->debugDumpParams();
      $text = ob_get_clean();
      $text = Str::substringBetween ($text, "Sent SQL:", "\n");
      $text = Str::substringAfter   ($text, "] ");
      return $text;
   }

   private function isSelect(string $sql): bool {
       $sql = trim(strtolower($sql));
       return Str::startsWith($sql, "select");
   }
}
