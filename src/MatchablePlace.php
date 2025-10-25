<?php
declare(strict_types=1);

namespace CharlesRothDotNet\Alfred;

use CharlesRothDotNet\Alfred\Str;

class MatchablePlace {
   private string $original;
   private array  $words;

   private static array $punctuationChars = ["-", "_", ".", "%"];

   public function __construct(string $name, array $removeWords) {
      $this->original = $name;
      $simplified = strtolower($name);
      foreach (self::$punctuationChars as $punctuation)   $simplified = Str::replaceAll($simplified, $punctuation, " ");
      $tokens = Str::split($simplified, " ");
      $this->words = [];
      foreach ($tokens as $token) {
         if (! in_array($token, $removeWords))  $this->words[] = $token;
      }
   }

   public function getOriginal(): string {
      return $this->original;
   }

   public function getWords(): array {
      return $this->words;
   }

   public function findBestMatch(array $others): int {
      $bestCount =  0;
      $bestIndex = -1;
      foreach ($others as $i => $other) {
         $count = $this->countMatchingWords($other);
         if ($count > $bestCount) {
            $bestCount = $count;
            $bestIndex = $i;
         }
      }
      return $bestIndex;
   }

   private function countMatchingWords (MatchablePlace $other): int {
      $count = 0;
      foreach ($this->getWords() as $mine) {
         foreach ($other->getWords() as $theirs) {
//          if ($mine == $theirs    ||    (strlen($mine) >= 4  &&  levenshtein($mine, $theirs) < 2))  ++$count;
            if ($mine == $theirs)  ++$count;
         }
      }
      return $count;
   }
}