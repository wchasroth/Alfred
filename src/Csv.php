<?php

namespace CharlesRothDotNet\Alfred;

class Csv {

   public static function loadFromCommandLine(array $argv, int $num, string $usageMessage): array {
      if (count($argv) <= $num) {
         fwrite(STDERR, $usageMessage);
         exit(1);
      }

      $fp = fopen($argv[$num], 'r');
      if ($fp === false) {
         fwrite(STDERR, "Unable to open file " . $argv[$num] . "\n");
         exit(1);
      }

      $result = self::load($fp);
      fclose($fp);
      return $result;
   }

   public static function load($fp): array {
      $result = [];
      while ( ($data = fgetcsv($fp, 2000, ",")) !== false)   $result[] = $data;
      return $result;
   }
}