<?php
declare(strict_types=1);

namespace CharlesRothDotNet\Alfred;

class SqlFields {
   private array $fields;

   function __construct(array $fields) {
      $this->fields = $fields;
   }

   public function getSelectFragment(): string {
      return $this->getFragmentWithSeparator(" AND ");
   }

   public function getUpdateFragment(): string {
      return $this->getFragmentWithSeparator(", ");
   }

   public function getInsertFragment(): string {
      $names  = [];
      $values = [];
      foreach ($this->fields as $key => $value) {
         $names[]  = $key;
         $values[] = (is_int($value) ? $value : Str::singleQuoted($value));
      }
      return " (" . Str::join($names, ", ") . ") VALUES (" . Str::join($values, ", ") . ") ";

   }

   private function getFragmentWithSeparator(string $separator): string {
      $results = [];
      foreach ($this->fields as $key => $value) {
         $results[] = "$key = " . (is_int($value) ? $value : Str::singleQuoted($value));
      }
      return " " . Str::join($results, $separator) . " ";
   }
}