<?php 
// Copyright (C) 2024 Charles Roth.  See LICENSE.

declare(strict_types=1);

namespace CharlesRothDotNet\Alfred;

use       CharlesRothDotNet\Alfred\Str;

class HttpPost {
    
    public static function value(string $name, string $default = ""): string {
        if (empty($name))  return $default;
        return isset($_POST[$name])  ? trim($_POST[$name])  : $default;
    }
    
    public static function number(string $name, int $default = 0): int {
        if (empty($name))  return $default;
        $number = isset($_POST[$name])  ? trim(Str::removeCommas($_POST[$name]))  : "";
        return empty($number) ? $default : intval($number);
    }
}
