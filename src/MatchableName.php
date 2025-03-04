<?php
declare(strict_types=1);

namespace CharlesRothDotNet\Alfred;

class MatchableName {
   private array $canonicalNameParts;

   public function __construct(string $name) {
      $simplifiedName = $this->removePunctuation(strtolower($name));
      $tokens = Str::splitIntoTokens($simplifiedName, " ");
      $this->canonicalNameParts = [];
      foreach ($tokens as $token) {
         if (strlen($token) > 1)
            $this->canonicalNameParts[] = $this->canonicalName($token);
      }
   }

   public function matches(MatchableName $other, int $number): bool {
      $count = 0;
      foreach ($this->canonicalNameParts as $mine) {
         foreach ($other->canonicalNameParts as $theirs) {
            if ($mine == $theirs    ||
               (strlen($mine) >= 4  &&  levenshtein($mine, $theirs) < 2)) {
               if (++$count >= $number) return true;
            }
         }
      }
      return false;
   }

   private function removePunctuation(string $name): string {
      return str_replace(self::$punctuation, " ", $name);
   }

   private function canonicalName(string $name): string {
      $name = strtolower($name);
      return Dict::value(self::$map, $name, $name);
   }

   private static array $punctuation = [",", "-", ".", '"', "\n"];

   private static array $map = [
      "albert"      => "al",
      "catherine"   => "cathy",
      "christopher" => "chris",
      "chuck"       => "charles",
      "daniel"      => "dan",
      "david"       => "dave",
      "donald"      => "don",
      "douglass"    => "doug",
      "drian"       => "brian",
      "edward"      => "ed",
      "jacqueline"  => "jackie",
      "james"       => "jim",
      "jeffery"     => "jeff",
      "jeffrey"     => "jeff",
      "jenn"        => "jen",
      "jennifer"    => "jen",
      "jerome"      => "jay",
      "joseph"      => "joe",
      "karren"      => "karen",
      "kimberlee"   => "kim",
      "kimberly"    => "kim",
      "lawrence"    => "larry",
      "lisaa"       => "lisa",
      "luebs"       => "leubs",
      "matthew"     => "mat",
      "mathew"      => "mat",
      "michael"     => "mike",
      "nicholas"    => "nick",
      "pamela"      => "pam",
      "patrick"     => "pat",
      "peña"        => "pena",
      "poulin"      => "pulin",
      "richard"     => "rick",
      "rob"         => "bob",
      "robert"      => "bob",
      "samuel"      => "sam",
      "stephenie"   => "stephanie",
      "stephen"     => "steve",
      "steven"      => "steve",
      "thomas"      => "tom",
      "timothy"     => "tim",
      "tommy"       => "tom",
      "william"     => "bill",
   ];



}