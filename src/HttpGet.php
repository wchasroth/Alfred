<?php 
// Copyright (C) 2024 Charles Roth.  See LICENSE.

declare(strict_types=1);

namespace CharlesRothDotNet\Alfred;

use       CharlesRothDotNet\Alfred\Str;

class HttpGet {
    
    public static function value(string $name): string {
        if (empty($name))  return "";
        return isset($_GET[$name])  ? trim($_GET[$name])  : "";
    }
    
    public static function number(string $name): int {
        if (empty($name))  return 0;
        $value = isset($_GET[$name])  ? trim(Str::removeCommas($_GET[$name]))  : "";
        return empty($value) ? 0 : intval($value);
    }
}
