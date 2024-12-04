<?php 
// Copyright (C) 2024 Charles Roth.  See LICENSE.

declare(strict_types=1);

namespace CharlesRothDotNet\Alfred;

use       CharlesRothDotNet\Alfred\Str;

class HttpPost {
    
    public static function value(string $name): string {
        if (empty($name))  return "";
        return isset($_POST[$name])  ? trim($_POST[$name])  : "";
    }
    
    public static function number(string $name): int {
        if (empty($name))  return 0;
        $number = isset($_POST[$name])  ? trim(Str::removeCommas($_POST[$name]))  : "";
        return empty($number) ? 0 : intval($number);
    }
}
