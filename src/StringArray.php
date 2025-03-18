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
      $result = file($filename, FILE_IGNORE_NEW_LINES);
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