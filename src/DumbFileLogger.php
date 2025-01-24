<?php

namespace CharlesRothDotNet\Alfred;

use \Exception;

class DumbFileLogger {
   private string $filename;

   public function __construct(string $file) {
      $this->filename = $file;
   }

   public function log(string $text) {
      if (empty($this->filename)) return;

      $fp = fopen($this->filename, 'a');
      if ($fp === false) throw new Exception("Cannot write to $this->filename");
      fwrite($fp, $text . "\n");
      fclose($fp);
   }

}