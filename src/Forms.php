<?php 
// Copyright (C) 2024 Charles Roth.  See LICENSE.

declare(strict_types=1);

namespace charlesroth_net\alfred;

class Forms {
    public static function getPost(string $name): string {
        if (empty($name))  return "";
        return isset($_POST[$name]) ? trim($_POST[$name]) : "";
    }
    
    public static function getPostNum(string $name): int {
        if (empty($name))  return 0;
        $value = isset($_POST[$name]) ? trim(Forms::noComma($_POST[$name])) : "0";
        return   empty($value) ? 0 : intval($value);
    }
    
    public static function getGet(string $name): string {
        if (empty($name))  return "";
        return isset($_GET[$name])  ? trim($_GET[$name])  : "";
    }
    
    public static function getGetNum(string $name): int {
        if (empty($name))  return 0;
        $value = isset($_GET[$name])  ? trim(Forms::noComma($_GET[$name]))  : "";
        return empty($value) ? 0 : intval($value);
    }
    
    public static function noComma($text) {
        if (empty($text))  return "";
        return str_replace(",", "", $text);
    }
}
