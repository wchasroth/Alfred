<?php
declare(strict_types=1);

namespace CharlesRothDotNet\Alfred;

class CsvFile {
   private string $errorMessage = "";
   private bool   $isTsv = false;
   private array  $rows;
   private array  $keys;
   private bool   $outputTsv = false;

   function __construct() {
   }

   public function loadfile(string $filename): bool {
      $fp = fopen($filename, "r");
      if ($fp    === false)  { $this->errorMessage = "Cannot find file $filename";     return false; }
      $line1 = fgets($fp);
      if ($line1 === false)  { $this->errorMessage = "Unable to read file $filename";  return false; }
      $this->isTsv = Str::contains($line1, "\t");
      $separator = $this->isTsv ? "\t" : ",";
      rewind($fp);

      $this->keys = fgetcsv($fp, 0, $separator);
      rewind($fp);

      $this->rows = [];
      while ( ($fields = fgetcsv($fp, 0, $separator)) !== false) {
         $row = [];
         for ($i=0;   $i<count($fields);  ++$i) {
            $row[$this->keys[$i]] = trim($fields[$i]);
         }
         $this->rows[] = $row;
      }
      return true;
   }

   public function getRowCount(): int {
      return count($this->rows);
   }

   public function getRow(int $i): array {
      return $this->rows[$i];
   }

   public function exitWithError(): void {
      fwrite(STDERR, $this->errorMessage . "\n");
      exit(1);
   }

   public function isTsv(): bool {
      return $this->isTsv;
   }

   public function isCsv(): bool {
      return ! $this->isTsv;
   }

   public function outputAsTsv() {
      return $this->outputTsv;
   }

   public function extractFlags (array $argv): array {
      $result = [];
      foreach ($argv as $arg) {
         if      ($arg === "-t")  $this->outputTsv = true;
         else if ($arg === "-c")  $this->outputTsv = false;
         else $result[] = $arg;
      }
      return $result;
   }
}