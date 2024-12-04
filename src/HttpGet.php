<?php 
// Copyright (C) 2024 Charles Roth.  See LICENSE.

declare(strict_types=1);

namespace CharlesRothDotNet\Alfred;

use       CharlesRothDotNet\Alfred\Str;

class HttpGet {
    
    public static function value(string $name, string $default = ""): string {
        if (empty($name))  return $default;
        return isset($_GET[$name])  ? trim($_GET[$name])  : $default;
    }
    
    public static function number(string $name, int $default = 0): int {
        if (empty($name))  return $default;
        $value = isset($_GET[$name])  ? trim(Str::removeCommas($_GET[$name]))  : "0";
        return empty($value) ? $default : intval($value);
    }
}
