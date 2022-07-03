<?php 

namespace alfred;

class Forms {
    public static function getPost($name) {
        return isset($_POST[$name]) ? trim(makeSafe($_POST[$name])) : "";
    }
    
    public static function getNumPost($name) {
        $value = isset($_POST[$name]) ? trim(makeSafe(noComma($_POST[$name]))) : "0";
        return   empty($value) ? 0 : intval($value);
    }
    
    public static function getGet($name) {
        return isset($_GET[$name])  ? trim(makeSafe($_GET[$name]))  : "";
    }
    
    public static function getNumGet($name) {
        $value = isset($_GET[$name])  ? trim(makeSafe(noComma($_GET[$name])))  : "";
        return empty($value) ? 0 : intval($value);
    }
    
    public static function makeSafe($text) {
        return str_replace(array('\'', '"', ';'), '', $text);
    }
    
    public static function noComma($text) {
        return str_replace(",", "", $text);
    }
}

?>