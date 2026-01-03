<?php
declare(strict_types=1);

namespace CharlesRothDotNet\Alfred;

use CharlesRothDotNet\Alfred\Str;

class DomUtils {
   public static function getChildrenAtLevel ($obj, int $level): array {
      $result = [];
      if ($level == 1) {
         foreach ($obj->childNodes as $child)   $result[] = $child;
         return $result;
      }

      foreach($obj->childNodes as $child) {
         array_push($result, ... DomUtils::getChildrenAtLevel($child, $level - 1));
      }
      return $result;
   }

   public static function getTextByXpath(DOMXPath $xpath, $node, string $query): string {
      $nodes = $xpath->evaluate($query, $node); // DOMNodeList
      $result = "";
      foreach ($nodes as $node)  $result .= DomUtils::clean($node->textContent);
      return $result;
   }

   public static function cleanDomNodeText(string $text): string {
      $text = trim(preg_replace('/[^ -~]/', '', $text));
      $text = Str::join(Str::splitIntoTokens($text, " "), " ");
      return $text;
   }
}