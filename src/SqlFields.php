<?php
declare(strict_types=1);

namespace CharlesRothDotNet\Alfred;

class SqlFields {
   private array $fields;

   function __construct(array $fields) {
      $this->fields = $fields;
   }
   
   public function getSelectFragment(): string {
      $results = [];
      foreach ($this->fields as $key => $value) {
         $results[] = "$key = " . (is_int($value) ? $value : Str::singleQuoted($value));
      }
      return " " . Str::join($results, " AND ") . " ";
   }

}