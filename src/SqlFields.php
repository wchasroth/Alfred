<?php
declare(strict_types=1);

namespace CharlesRothDotNet\Alfred;

/**
 * Utility class to simplify building SQL prepared statements, with a minimum of duplication.
 *
 * The SQL syntax for parameters in INSERT, UPDATE, and SELECT statements is... dumb.  We have three
 * COMPLETELY different syntaxes for key-value pairs, depending on the statement.  E.g. given 'table'
 * with name (string), age (int), rating (float), and id (auto-increment int PK), consider:
 *
 *    INSERT INTO   table (name, age, rating) VALUES ('Charles', 68, 9.5)
 *    UPDATE        table SET   name='Charles', age=68, rating=9.5 WHERE id=17
 *    SELECT * FROM table WHERE name='Charles' AND age=68 AND rating=90.5
 *
 * This is absurd!  There's no good reason for the references to name, age, and rating to be
 * done in different ways.  It gets worse when we use prepared statements (which are A Good Thing),
 * because we're duplicating (one more time!) the key names (e.g. "SET name=:name").
 *
 * SqlFields provides a one-stop-shop for simple queries that match this pattern.  Given
 * the key/value map, it produces the correct syntax (as a prepared statement) in the following
 * examples:
 *
 *    $sqlFields = new SqlFields(['name' => 'Charles', 'age' => 68, 'rating' => 9.5);
 *    $insert = $sqlFields->makePreparedStatement("INSERT INTO table");
 *    $update = $sqlFields->makePreparedStatement("UPDATE table SET",  "WHERE id=17");
 *    $select = $sqlFields->makePreparedStatement("SELECT * FROM table WHERE");
 *
 * Combined with the AlfredPDO->runSF() function, this makes for a very simple/elegant
 * way to run (relatively) simple queries, without a lot of SQL chatter and duplication.
 */
class SqlFields {
   private array $keyValuePairs;

   function __construct(array $keyValuePairs) {
      $this->keyValuePairs = $keyValuePairs;
   }

   public function getKeyValuePairs(): array {
      return $this->keyValuePairs;
   }

   public function getSelectFragment(): string {
      return $this->getFragmentWithSeparator(" AND ");
   }

   public function getUpdateFragment(): string {
      return $this->getFragmentWithSeparator(" AND ");
   }

   public function getInsertFragment(): string {
      $names  = [];
      $values = [];
      foreach ($this->keyValuePairs as $key => $value) {
         $names[]  = $key;
         $values[] = (is_int($value) ? $value : Str::singleQuoted($value));
      }
      return " (" . Str::join($names, ", ") . ") VALUES (" . Str::join($values, ", ") . ") ";
   }

   public function makePreparedStatement(string $prefix, string $suffix=""): string {
      $operation = strtolower(Str::substringBefore(trim($prefix), " "));
      $sql = match($operation) {
         'insert'           =>  $this->makeInsert($prefix),
         'update'           => "$prefix {$this->makeFragmentWithSeparator(", ")} $suffix",
         'select', 'delete' => "$prefix {$this->makeFragmentWithSeparator(" AND ")} $suffix",
         default            => ""
      };
      return trim($sql);
   }

   public function makeInsert(string $prefix): string {
      $names  = [];
      $values = [];
      foreach ($this->keyValuePairs as $key => $value) {
         $names[]  =   $key;
         $values[] = ":$key";
      }
      return "$prefix (" . Str::join($names, ", ") . ") VALUES (" . Str::join($values, ", ") . ") ";
   }

   private function getFragmentWithSeparator(string $separator): string {
      $results = [];
      foreach ($this->keyValuePairs as $key => $value) {
         $results[] = "$key = " . (is_int($value) ? $value : Str::singleQuoted($value));
      }
      return " " . Str::join($results, $separator) . " ";
   }

   private function makeFragmentWithSeparator(string $separator): string {
      $results = [];
      foreach ($this->keyValuePairs as $key => $value)  $results[] = "$key = :$key";
      return " " . Str::join($results, $separator) . " ";
   }


}