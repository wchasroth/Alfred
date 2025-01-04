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
      $pos2 = strpos($text, $close, $pos1);
      if ($pos2 === false)                                 return "";
      $pos1 = $pos1 + strlen($open);
      return substr($text, $pos1, $pos2 - $pos1);
   }
   
   public static function contains (?string $text, ?string ... $searchFors): bool {
      if (empty($text)) return false;
      foreach ($searchFors as $searchFor) {
         if (! empty($searchFor)) {
             if (strpos($text, $searchFor))  return true;
         }
      }
      return false;
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
   
}
