<?php
declare(strict_types=1);

namespace CharlesRothDotNet\Alfred;

/**
 * Utility class to simplify building SQL statements, with a minimum of duplication, that are still
 * prepared-statement safe.
 *
 * The SQL syntax for parameters in INSERT, UPDATE, and SELECT statements are... dumb.  We have three
 * COMPLETELY different syntaxes for key-value pairs, depending on the statement.  E.g. given 'table'
 * with name (string), age (int), rating (float), and id (auto-increment PK), consider:
 *
 *    INSERT INTO   table (name, age, rating) VALUES ('Charles', 68, 9.5)
 *    UPDATE        table SET   name='Charles', age=68, rating=9.5 WHERE id=17
 *    SELECT * FROM table WHERE name='Charles' AND age=68 AND rating=90.5
 *
 * This is absurd.  It gets worse when we use prepared statements (which are A Good Thing),
 * because we're duplicating (one more time!) the key names (e.g. "SET name=:name").
 *
 * SqlFields provides a one-stop-shop for simple queries that match this pattern.  E.g.
 *
 *    $sqlFields = new SqlFields(['name' => 'Charles', 'age' => 68, 'rating' => 9.5);
 *    $insert = $sqlFields ("INSERT INTO");
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

   public function makeSelect(string $prefix): string {
      return $prefix . " " . $this->makeFragmentWithSeparator(" AND ");
   }

   public function getUpdateFragment(): string {
      return $this->getFragmentWithSeparator(", ");
   }

   public function makeUpdate(string $prefix, string $suffix): string {
      return "$prefix {$this->makeFragmentWithSeparator(", ")} $suffix";
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