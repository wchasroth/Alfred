<?php 

namespace alfred;

class Logger {
    
    function log ($text, $filename = "log") {
        $targetDir = getcwd() . "/LOGS/";
        $fp = fopen($targetDir . $filename, 'a');
        fwrite($fp, $text . "\n");
        fclose($fp);
    }
}

?>