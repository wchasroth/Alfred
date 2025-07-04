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

   public static function replaceFirst (?string $text, ?string $find, string $replace) {
      if (empty($text))    return "";
      if (empty($find))    return $text;
      $pos = strpos($text, $find);
      if ($pos === false)  return $text;
      return substr_replace($text, $replace, $pos, strlen($find));
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
   public static function splitIntoTokens(?string $text, ?string $delimiter=' '): array {
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
      $word  = trim ($word);
      $value = (int) $word;
      if ($value > 0)    return $value;
      return self::$ordinalsToInts[strtolower($word)] ?? 0;
   }

   public static function reorderName(string $name): string {
      if (empty($name))                 return "";
      foreach (["JR.", "Jr.", "jr."] as $jr)  $name = Str::replaceAll($name, ", $jr", " $jr");
      if (! Str::contains($name, ","))  return $name;
      $first = trim(Str::substringAfter ($name, ","));
      $last  = trim(Str::substringBefore($name, ","));
      return "$first $last";
   }

   public static function jsonify (string $name, $value, bool $comma=true): string {
      $value  = is_int($value) ? strval($value) : self::quoted($value);
      return self::quoted($name) . ": " . $value . ($comma ? ", " : "");
   }

   public static function quoted(string $text): string {
      return '"' . $text . '"';
   }

   public static function singleQuoted(string $text, string $suffix = ""): string {
      return "'" . addslashes($text) . "'" . $suffix;
   }

   public static function jsonifyLast (string $name, $value): string {
      return self::jsonify($name, $value, false);
   }

   public static function equalsIgnoreCase(string $text1, string $text2): bool {
      return strcasecmp($text1, $text2) === 0;
   }

   public static function findEmailAddresses($text): array {
      if (empty($text)) return [];
      $result = [];
      $words = Str::split($text, " ");
      foreach ($words as $word) {
         $word = trim($word, ",");
         $word = self::removeMailto($word);
         if (filter_var($word, FILTER_VALIDATE_EMAIL))  $result[] = $word;
      }
      return array_unique($result);
   }

   public static function removeEmailAddresses($text): string {
      if (empty($text)) return "";
      $result = [];
      $words = Str::split($text, " ");
      foreach ($words as $word) {
         $email = self::removeMailto($word);
         $email = trim($email, ",");
         if (! filter_var($email, FILTER_VALIDATE_EMAIL))  $result[] = $word;
      }
      return Str::join($result, " ");
   }

   private static function removeMailto(string $address): string {
      if (! Str::startsWith($address, "mailto:"))  return $address;
      return Str::substringAfter($address, "mailto:");
   }

   public static function findUrls($text): array {
      if (empty($text)) return [];
      $result = [];
      $words = Str::split($text, " ");
      foreach ($words as $word) {
         if (self::isUrl($word))  {
            if (! Str::startsWith($word, "http"))  $word = "https://" . $word;
            $word = trim($word, ",");
            $word = trim($word, ".");
            $result[] = $word;
         }
      }
      return array_unique($result);
   }

   public static function removeUrls($text): string {
      if (empty($text)) return "";
      $result = [];
      $words = Str::split($text, " ");
      foreach ($words as $word) {
         if (! self::isUrl($word))  $result[] = $word;
      }
      return Str::join($result, " ");
   }

   private static $startsUrls = ["http://", "https://", "www.", "secure.everyaction.com", "bit.ly/"];

   private static function isUrl(string $word): bool {
//      $word = trim($word, ",");
//      $word = trim($word, ".");
      $url  = strtolower($word);
      foreach (self::$startsUrls as $start) {
         if (Str::startsWith($url, $start)) return true;
      }
      return false;
   }

   private static string $pattern = "/[( ]{0,2}[0-9]{3}[) ]{0,2}[-]{0,1}[0-9]{3}-[0-9]{4}/";

   public static function findPhones(string $text): array {
      $matches = [];
      $text = Str::replaceAll($text, ",", " ");
      $found = preg_match_all(self::$pattern, $text, $matches);
      if ($found == 0)  return [];

      $results = [];
      foreach ($matches[0] as $match) {
         $match = trim($match);
         $match = Str::replaceAll($match, "(",  "");
         $match = Str::replaceAll($match, ")",  "-");
         $match = Str::replaceAll($match, " ",  "-");
         $match = Str::replaceAll($match, "--", "-");
         $results[] = $match;
      }
      return array_unique($results);
   }

   public static function removePhones(string $text): string {
      return preg_replace(self::$pattern, "", $text);
   }

   public static function redactUnSafeTags(string $text, array $safeTags): string {
      $result = $text;
      $lower  = strtolower($text);

      $offset    = 0;
      while ( ($next = strpos($result, "<", $offset)) !== false) {
         if (self::isSafeTag(substr($lower, $next, 8), $safeTags))  $offset = $next + 1;
         else {
            $last = strpos($result, ">", $next);
            $last = ($last ?: strlen($result) - 1);
            for ($i=$next;  $i<=$last;   ++$i)  $result[$i] = ' ';
            $offset = $last;
         }
      }
      return $result;
   }

   public static function removeNonAscii(string $text): string {
      return preg_replace('/[^\x20-\x7E]/', '', $text);
   }

   private static function isSafeTag(string $text, array $safeTags): bool {
      foreach ($safeTags as $safeTag) {
         if (str_starts_with($text, "<"  . $safeTag . ">")  ||
             str_starts_with($text, "<"  . $safeTag . "/>") ||
             str_starts_with($text, "</" . $safeTag . ">"))  return true;
      }
      return false;
   }


}
