<?php
declare(strict_types=1);

namespace CharlesRothDotNet\Alfred;

use CharlesRothDotNet\Alfred\Str;

class NameSimplifier {

   public static function simplify (string $name, bool $sort=true): string {
      $name = strtolower($name);
      $name = preg_replace('/[,()]/', '', $name);
      $name = Str::replaceAll($name, '-', ' ');

      $words = Str::splitIntoTokens($name, ' ');
      $keep  = [];
      foreach ($words as $word) {
         if (Str::endsWith($word, '.')  &&  strlen($word) <= 3)  continue;
         $word = Str::replaceAll($word, '.', '');
         if ($word === 'iii'  ||  $word === 'iv')  continue;
         $keep[] = $word;
      }

      if ($sort) sort($keep);
      return Str::join($keep, ' ');
   }

   public static function makeFilenameFrom (string $name): string {
      $badChars = str_split("` ~!@#$%^&*()_+=-[]{}|;:'?,<>\\\"");
      $name = str_replace($badChars, ' ', $name);
      $name = self::simplify ($name, false);
      $name = Str::replaceAll($name, ' ', '_');
      return $name;
   }

}
