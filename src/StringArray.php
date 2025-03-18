<?php
declare(strict_types=1);

namespace CharlesRothDotNet\Alfred;

use CharlesRothDotNet\Alfred\Str;

class StringArray {
   private array $lines;
   private int   $index;

   function __construct() {
      $this->lines = [];
      $this->index =  0;
   }

   public function load(string $filename): bool {

      if (empty($filename))   return false;
      set_error_handler(function() { /* ignore errors */ }); // Turn off the damn warnings!
      $result = file($filename, FILE_IGNORE_NEW_LINES);
      restore_error_handler();

      if ($result === false)  return false;
      $this->lines = $result;
      $this->index = 0;
      return true;
   }

   public function hasMore(): bool {
      return $this->index < count($this->lines);
   }

   public function getNext(): string {
      if (! $this->hasMore()) return "";
      return $this->lines[$this->index++];
   }

   public function getNextMatch(string $match) {
      while ($this->hasMore()) {
         $text = $this->getNext();
         if (Str::contains($text, $match))  return $text;
      }
      return "";
   }

}