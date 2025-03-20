<?php
// Copyright (C) 2024 Charles Roth.  See LICENSE.

declare(strict_types=1);

namespace CharlesRothDotNet\Alfred;

class Str {
   
   public static function substringAfter (string $text, string $delimiter): string {
      $pos = strpos($text, $delimiter);
      if ($pos === false)  return "";
      return substr($text, $pos + strlen($delimiter));
   }
   
   public static function substringBefore (string $text, string $delimiter): string {
      $pos = strpos($text, $delimiter);
      if ($pos === false)  return $text;
      return substr($text, 0, $pos);
   }

   public static function substringBeforeLast (string $text, ?string $delimiter): string {
      if (empty($delimiter))  return "";
      $pos = strrpos($text, $delimiter);
      if ($pos === false)  return $text;
      return substr($text, 0, $pos);
   }

   public static function substringAfterLast (string $text, ?string $delimiter): string {
       if (empty($delimiter))  return "";
       $pos = strrpos($text, $delimiter);
       if ($pos === false)  return "";
       return substr($text, $pos+strlen($delimiter));
   }
   
   public static function substringBetween (?string $text, ?string $open, ?string $close): string {
      if (empty($text) || empty($open) || empty($close))   return "";
      $pos1 = strpos($text, $open);
      if ($pos1 === false)                                 return "";
      $pos2 = strpos($text, $close, $pos1 + strlen($open));
      if ($pos2 === false)                                 return "";
      $pos1 = $pos1 + strlen($open);
      return substr($text, $pos1, $pos2 - $pos1);
   }

   public static function substringsBetween (?string $text, ?string $open, ?string $close): array {
      if (empty($text) || empty($open) || empty($close))   return [];

      $results = [];
      $start = 0;
      while (true) {
         $pos1 = strpos($text, $open, $start);
         if ($pos1 === false)                              return $results;
         $pos2 = strpos($text, $close, $pos1 + strlen($open));
         if ($pos2 === false)                              return $results;

         $pos1  += strlen($open);
         $results[] = substr($text, $pos1, $pos2 - $pos1);
         $start = $pos2 + strlen($close);
      }

   }

   // "HTTP/1.1 403 Forbidden"
   // "HTTP/1.1 "     pos1 = 0
   
   public static function contains (?string $text, ?string ... $searchFors): bool {
      if (empty($text)) return false;
      foreach ($searchFors as $searchFor) {
         if (! empty($searchFor)) {
             if (strpos($text, $searchFor) !== false)  return true;
         }
      }
      return false;
   }

   public static function containsAll (?string $text, ?string ... $searchFors): bool {
      if (empty($text)) return false;
      foreach ($searchFors as $searchFor) {
         if (! empty($searchFor)) {
            if (strpos($text, $searchFor) === false)  return false;
         }
      }
      return true;
   }

    public static function hasAnyOf (?string $text, ?array $searchFors): bool {
        if (empty($text)) return false;
        foreach ($searchFors as $searchFor) {
            if (! empty($searchFor)) {
                if (strpos($text, $searchFor))  return true;
            }
        }
        return false;
    }

   public static function replaceAll (?string $text, ?string $find, string $replace) {
      if (empty($text))  return "";
      if (empty($find))  return $text;
      return str_replace($find, $replace, $text);
   }
   
   public static function firstNonEmpty(string ... $strings) {
       foreach ($strings as $str) {
           if (!empty($str))  return $str;
       }
       return "";
   }
   
   public static function removeAll (string $text, array $arrayOfChars) {
       return str_replace($arrayOfChars, "", $text);
   }

   public static function split(?string $text, ?string $delimiter): array {
       if (empty($text))       return array();
       if (empty($delimiter))  return array($text);
       return explode($delimiter, $text);
   }

   /**
    * Unlike split(), when multiple delimiters appear in sequence, they are treated
    * as one delimiter.  And leading and trailing delimiters are skipped.
    * E.g. " Hello,   world!" splits into ["Hello,"world!"].
    */
   public static function splitIntoTokens(?string $text, ?string $delimiter): array {
      if (empty($text))       return array();
      if (empty($delimiter))  return array($text);

      $token = strtok($text, $delimiter);
      if ($token === false)  return [];

      $results = [$token];
      while (true) {
         $token = strtok($delimiter);
         if ($token === false)  return $results;
         $results[] = $token;
      }
   }

   public static function join(?array $values, ?string $separator): string {
       if ($values    === null)  return "";
       if ($separator === null)  $separator = "";
       return implode($separator, $values);
   }

   public static function startsWith(?string $text, ?string $match): bool {
      if (empty($text)  ||  empty($match))  return false;
      return str_starts_with($text, $match);
   }

   public static function endsWith(?string $text, ?string $match): bool {
      if (empty($text)  ||  empty($match))  return false;
      return str_ends_with($text, $match);
   }

   public static function removeCommas(?string $text) {
      if (empty($text))  return "";
      return str_replace(",", "", $text);
   }

   private static array $ordinalsToInts = [
      "first" => 1, "second" => 2, "third" => 3, "fourth" => 4, "fifth" => 5, "sixth" => 6,
      "seventh" => 7, "eighth" => 8, "ninth" => 9, "tenth" => 10
   ];

   public static function ordinalValue(string $word): int {
      if (empty($word))  return 0;
      return self::$ordinalsToInts[trim(strtolower($word))] ?? 0;
   }

   public static function reorderName(string $name): string {
      if (empty($name))                 return "";
      foreach (["JR.", "Jr.", "jr."] as $jr)  $name = Str::replaceAll($name, ", $jr", " $jr");
      if (! Str::contains($name, ","))  return $name;
      $first = trim(Str::substringAfter ($name, ","));
      $last  = trim(Str::substringBefore($name, ","));
      return "$first $last";
   }
   
}
