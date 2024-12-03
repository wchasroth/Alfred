<?php
   namespace CharlesRothDotNet\Alfred;

   class EnvFile {
      private array  $dict;
      private string $error;

      function __construct($filename) {
         $this->dict  = array();
         $this->error = "";

         $dir = str_replace("\\", "/", getcwd());
   
         while (true) {
            $envFile = $dir . "/$filename";
            if (file_exists($envFile)) {
               $fp = fopen($envFile, "r");
               while ( ($line = fgets($fp, 4096)) !== false) {
                  $line = trim($line);
                  if (! str_starts_with($line, "#")  &&  str_contains($line, "=")) {
                     $parts = explode("=", $line, 2);
                     $this->dict[$parts[0]] = $parts[1];
                  }
               }
               fclose ($fp);
               break;
            }
   
            $lastSlash = strrpos($dir, "/");
            if ($lastSlash === false)  {
               $this->error = "Could not find file $filename";
               break;
            }

            $dir = substr($dir, 0, $lastSlash);
         }
      }

      public function get($key):  string {
         if (! array_key_exists($key, $this->dict))  return "";
         return $this->dict[$key];
      }

      public function getKeys(): array {
         return array_keys($this->dict);
      }

      public function getError(): string {
         return $this->error;
      }

   }
