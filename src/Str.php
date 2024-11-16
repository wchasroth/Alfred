<?php
// Copyright (C) 2024 Charles Roth.  See LICENSE.

declare(strict_types=1);

namespace charlesroth_net\alfred;

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
   
   public static function substringBetween (string $text, string $open, string $close): string {
      if (empty($text) || empty($open) || empty($close))   return "";
      $pos1 = strpos($text, $open);
      if ($pos1 === false)                                 return "";
      $pos2 = strpos($text, $close, $pos1);
      if ($pos2 === false)                                 return "";
      $pos1 = $pos1 + strlen($open);
      return substr($text, $pos1, $pos2 - $pos1);
   }
   
   public static function contains (string $text, string $searchFor): bool {
      if (empty($text) || empty($searchFor))   return false;
      return strpos($text, $searchFor) !== false;
   }
   
   public static function replace (string $text, string $find, string $replace) {
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
   
   public static function removeAll (string $text, $arrayOfChars) {
       return str_replace($arrayOfChars, "", $text);
   }
   
}
