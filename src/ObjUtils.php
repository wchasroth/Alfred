<?php
// Copyright (C) 2024 Charles Roth.  See LICENSE.

declare(strict_types=1);

namespace CharlesRothDotNet\Alfred;

use stdClass;

class ObjUtils {

   /**
    * Simple approach to the pesky case of properties (I'm looking at YOU, Json objects) that may or may not exist.
    * Note the 'append' optional value, which simplifies assembling several optionally-existent properties into
    * a single string.
    */
   public static function value(stdClass $obj, string $key, string $defaultValue = "", string $append = "") {
      return (property_exists($obj, $key) ? ($obj->$key . $append) : $defaultValue);
   }

}