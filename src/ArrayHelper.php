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


}