<?php
// Copyright (C) 2024 Charles Roth.  See LICENSE.

namespace charlesroth_net\alfred;

class Str {
   
   public static function substringAfter ($text, $delimiter) {
      $pos = strpos($text, $delimiter);
      if ($pos === false)  return "";
      return substr($text, $pos + strlen($delimiter));
   }
   
   public static function substringBefore ($text, $delimiter) {
      $pos = strpos($text, $delimiter);
      if ($pos === false)  return $text;
      return substr($text, 0, $pos);
   }
   
   public static function substringBetween ($text, $open, $close) {
      if ($text == ""  ||  $open == ""  ||  $close == "")  return "";
      $pos1 = strpos($text, $open);
      $pos2 = strpos($text, $close, $pos1);
      if ($pos1 === false  ||  $pos2 === false)            return "";
      $pos1 = $pos1 + strlen($open);
      return substr($text, $pos1, $pos2 - $pos1);
   }
   
   public static function contains ($text, $searchFor) {
      return strpos($text, $searchFor) !== false;
   }
   
   public static function replace ($text, $find, $replace) {
      return str_replace($find, $replace, $text);
   }
   
   public static function firstNonEmpty(... $strings) {
       foreach ($strings as $str) {
           if ($str != "")  return $str;
       }
       return "";
   }
   
   public static function removeAll ($text, $arrayOfChars) {
       return str_replace($arrayOfChars, "", $text);
   }
   
}
