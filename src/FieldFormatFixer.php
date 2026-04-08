<?php
declare(strict_types=1);

namespace CharlesRothDotNet\Alfred;

use CharlesRothDotNet\Alfred\Str;

class FieldFormatFixer {

   public static function fixMI (string $address): string {
      $address = trim($address);
      $address = Str::endsWith($address, ',') ? substr($address, 0, -1) : $address;
      $address = Str::replaceAll($address, "&nbsp;", " ");
      $address = trim($address);
      $address = Str::replaceAll($address, "MI.", "MI");
      $address = Str::replaceAll($address, "Mi.", "MI");
      $address = Str::replaceAll($address, "MI-", "MI");
      $address = Str::replaceAll($address, ",MI", ", MI");
      $address = Str::replaceAll($address, "MI,", " MI ");
      if (Str::endsWith($address, " Michigan"))  $address = Str::substringBeforeLast($address, " Michigan") . " MI";

      if (Str::contains($address, 'Michigan')) {
         $address = Str::replaceAll($address, ",Michigan", ", Michigan");
         $words = Str::splitIntoTokens($address, " ");
         $last = count($words) - 1;
         if ($last > 1  &&  strspn($words[$last], "0123456789-") === strlen($words[$last])) {  // $address ends in zip or zip+4
            if      ($words[$last-1] === ",Michigan")  $words[$last-1] = ", MI";
            else if ($words[$last-1] === 'Michigan,')  $words[$last-1] = 'MI';
            else if ($words[$last-1] === 'Michigan')   $words[$last-1] = 'MI';
         }
         $address = Str::join($words, " ");
      }

      //---check for no-space "...MIzzzzz" forms.
      $zip = Str::substringAfterLast($address, "MI");
      if (strspn($zip, "0123456789-") == strlen($zip)) $address = Str::replaceFirst($address, "MI$zip", " MI $zip");

      //---Missing MI -- add before zip.
      if (! Str::contains($address, 'MI')  &&  ! Str::contains($address, " DC ")) {
         $zip = Str::substringAfterLast($address, " ");
         if (strspn($zip, "0123456789-") == strlen($zip)) $address = Str::replaceFirst($address, $zip, " MI $zip");
      }

      $words = Str::splitIntoTokens($address, " ");
      $address = Str::join($words, " ");
      return $address;
   }

   public static function fixPhone(string $phone): string {
      $phone  = trim($phone);
      $phone  = Str::replaceAll($phone, "'", "");
      $len    = strlen($phone);
      if ($len < 10)  return $phone;

      $digits = 0;
      $result = "";
      $isFirst10digits = true;
      for ($i=0;   $i<$len;  $i++) {
         $char = $phone[$i];
         if ($isFirst10digits) {
            if (ctype_digit($char)) {
               $result .= $char;
               ++$digits;
               $isFirst10digits = $digits < 10;
            }
         } else {
            if ($char === ',') $char = ' ';
            $result .= $char;
         }
      }

      $parts  = Str::splitIntoTokens($result, " ");
      $result = Str::join($parts, " ");
      if (strlen($result) < 10)  return $result;
      $result = substr($result, 0, 3) . "-" . substr($result, 3, 3) . "-" . substr($result, 6);
      $result = Str::replaceFirst($result, " ext ", " x");
      $result = Str::replaceFirst($result, " ext",  " x");
      $result = Str::replaceFirst($result, " x ",   " x");
      return $result;
   }

}
