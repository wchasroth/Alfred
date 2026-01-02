<?php
declare(strict_types=1);

namespace CharlesRothDotNet\Alfred;

use function CharlesRothDotNet\ImportTools\getChildrenAtLevel;

class DomUtils {
   public static function getChildrenAtLevel ($obj, int $level): array {
      $result = [];
      if ($level == 1) {
         foreach ($obj->childNodes as $child)   $result[] = $child;
         return $result;
      }

      foreach($obj->childNodes as $child) {
         array_push($result, ... getChildrenAtLevel($child, $level - 1));
      }
      return $result;
   }
}