<?php
   namespace CharlesRothDotNet\Alfred;

   use CharlesRothDotNet\Alfred\Str;

   class EnvFile {
      private array  $dict;
      private string $error;

      function __construct($filename) {
         $this->dict  = array();
         $this->error = "";

         //---Recurse upwards from the cwd, looking for file $filename
         for ( $dir = str_replace("\\", "/", getcwd());
               Str::contains($dir, "/");
               $dir = Str::substringBeforeLast($dir, "/")) {

            $envFile = $dir . "/$filename";
            if (file_exists($envFile)) {
               foreach (file($envFile) as $line) {
                  $line = trim($line);
                  if (! Str::startsWith($line, "#")  &&  Str::contains($line, "=")) {
                     $key              = Str::substringBefore($line, "=");
                     $this->dict[$key] = Str::substringAfter ($line, "=");
                  }
               }
               return;
            }
         }
         $this->error = "Could not find file $filename";
      }

      public function get(string $key):  string {
         if (! array_key_exists($key, $this->dict))  return "";
         return $this->dict[$key];
      }

      public function getValues(string $keys): string {
         $result = "";
         foreach (Str::split($keys, ",") as $key)  $result = $result . $this->get($key) . ",";
         return   Str::substringBeforeLast($result, ",");
      }

      public function int(string $key):  int {
         $text = $this->get($key);
         return (empty($text) ? 0 : intval($text));
      }

      public function bool(string $key): bool {
         $text = $this->get($key);
         if (empty($text))   return false;
         return strcasecmp($text, "true") == 0;
      }

      public function getKeys(): array {
         return array_keys($this->dict);
      }

      public function getError(): string {
         return $this->error;
      }

   }
