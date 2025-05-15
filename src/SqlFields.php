<?php
declare(strict_types=1);

namespace CharlesRothDotNet\Alfred;

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

   public function makeSelectFragment(): string {
      return $this->makeFragmentWithSeparator(" AND ");
   }

   public function getUpdateFragment(): string {
      return $this->getFragmentWithSeparator(", ");
   }

   public function makeUpdateFragment(): string {
      return $this->makeFragmentWithSeparator(", ");
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

   public function makeInsertFragment(): string {
      $names  = [];
      $values = [];
      foreach ($this->keyValuePairs as $key => $value) {
         $names[]  =   $key;
         $values[] = ":$key";
      }
      return " (" . Str::join($names, ", ") . ") VALUES (" . Str::join($values, ", ") . ") ";
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