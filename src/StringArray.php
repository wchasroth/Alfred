<?php
declare(strict_types=1);

namespace CharlesRothDotNet\Alfred;

use CharlesRothDotNet\Alfred\Str;

class StringArray {
   private array $lines;
   private int   $index;
   private int   $count;

   function __construct() {
      $this->lines = [];
      $this->count =  0;
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
      $this->count = count($result);
      return true;
   }

   public static function makeFromFile(string $path): StringArray {
      $sa = new StringArray();
      $sa->load($path);
      return $sa;
   }

   public static function makeFromCommandLineArgs_elseExit(array $argv, int $number, string $usageMessage): StringArray {
      if (count($argv) <= $number) {
         fwrite(STDERR, $usageMessage);
         exit(1);
      }

      $lines = new StringArray();
      $lines->load($argv[$number]);
      if ($lines->hasMore()) return $lines;

      fwrite(STDERR, "Cannot read file " . $argv[$number] . "\n");
      exit(1);
   }

   public function hasMore(): bool {
      return $this->index < $this->count;
   }

   public function getNext(): string {
      if (! $this->hasMore()) return "";
      return $this->lines[$this->index++];
   }

   public function getNextMatch(string $match): string {
      while ($this->hasMore()) {
         $text = $this->getNext();
         if (Str::contains($text, $match))  return $text;
      }
      return "";
   }

   public function getNextMatchBefore(string $match, string $before): string {
      if (! $this->hasMore())  return "";

      for ($i = $this->index;   $i < $this->count;  ++$i) {
         $line = $this->lines[$i];
         if (Str::contains($line, $before))  return "";
         if (Str::contains($line, $match)) {
            $this->index = $i + 1;
            return $line;
         }
      }

      return "";
   }

}