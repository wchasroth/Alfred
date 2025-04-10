<?php
declare(strict_types=1);

namespace CharlesRothDotNet\Alfred;

class ArrayHelper {

   // Produces column-aligned definition of single-level associative array $myarray,
   // showing keys in order from $keys, with respective column widths in $widths.
   public static function formatArrayDisplay(array $myarray, array $keys, array $widths): string {
      if (count($keys) != count($widths))  return "ERROR";

      $result = "";
      for ($i=0;   $i<count($keys);   ++$i) {
         $prefix = ' "' . $keys[$i] . '" => ';

         $isLast = ($i == count($keys) - 1);
         $comma  = ($isLast ? "" : ",");
         $value = $myarray[$keys[$i]];
         $width = $widths[$i];
         $result = $result . $prefix . (is_int($value)
               ? sprintf ("%-{$width}s", $value . $comma)
               : sprintf ("\"%-{$width}s", $value . '"' . $comma)
            );
      }

      return "[" . $result . "]";
   }

   // Removes seemingly duplication URLs.  A duplicates B if B does not have a querystring, and A does,
   // and everything before the querystring in A is the same as B.  (Yes, that's a mouthful!)
   public static function removeUrlsDuplicatedUpToQuerystring(array $myarray): array {
      $values = $myarray;
      asort($values);
      $prev = "";
      $result = [];
      $found = false;
      foreach ($values as $value) {
         if (Str::startsWith($value, "$prev?"))  $found = true;  // skip it, and marked at least one removed.
         else {
            $prev     = $value;
            $result[] = $value;
         }
      }
      return ($found ? $result : $myarray);
   }


}