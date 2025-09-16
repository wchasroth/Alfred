<?php
declare(strict_types=1);

namespace CharlesRothDotNet\Alfred;

class FileUtils {

   public static function loadFileNamedOnCommandLine(array $argv, int $num, string $usageMessage, bool $exitOnFailure=true): string {
      if (count($argv) <= $num)        return self::failure($usageMessage,                     $exitOnFailure);
      if (! file_exists($argv[$num]))  return self::failure("Cannot open file " . $argv[$num], $exitOnFailure);

      $content = file_get_contents($argv[$num]);
      if ($content !== false)  return $content;
      return self::failure("Cannot read file " . $argv[$num], $exitOnFailure);
   }

   private static function failure(string $message, bool $exitOnFailure): string {
      fwrite(STDERR, "$message\n");
      if ($exitOnFailure) exit(1);
      return "";
   }

}