<?php

namespace CharlesRothDotNet\Alfred;

// Not surprisingly, this should probably become a REAL class, with an error value that can be inspected.

class Csv {

   // Note: separator MUST be a double-quoted string for things like "\t".  Single quotes ('\t') DO NOT WORK!
   public static function loadFromCommandLine(array $argv, int $num, string $usageMessage, string $separator=',', bool $exitOnFailure=true): array {
      if (count($argv) <= $num)        return self::failure($usageMessage,                     $exitOnFailure);
      if (! file_exists($argv[$num]))  return self::failure("Cannot open file " . $argv[$num], $exitOnFailure);

      $fp = fopen($argv[$num], 'r');
      if ($fp === false)               return self::failure("Cannot open file " . $argv[$num], $exitOnFailure);
      $result = self::loadTrimmed($fp, $separator);
      fclose($fp);
      return $result;
   }

   private static function failure(string $message, bool $exitOnFailure): array {
      fwrite(STDERR, "$message\n");
      if ($exitOnFailure) exit(1);
      return [];
   }

   public static function loadTrimmed($fp, string $separator): array {
      $result = [];
      while ( ($data = fgetcsv($fp, 2000, $separator)) !== false) {
         for ($i=0;   $i<count($data);  ++$i)    $data[$i] = trim($data[$i]);
         $result[] = $data;
      }
      return $result;
   }
}