<?php
declare(strict_types=1);

namespace CharlesRothDotNet\Alfred;

class JsonHelper {

   public static function getElement (array $json, string ... $nodeNames) {
      $node = $json;
      foreach ($nodeNames as $nodeName) {
         $child = $node[$nodeName] ?? null;
         if ($child === null)     return "";
         if (! is_array($child))  return $child;
         $node = $child;
      }
      return "";
   }
}