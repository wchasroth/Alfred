<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;

use CharlesRothDotNet\Alfred\TempFile;
use CharlesRothDotNet\Alfred\Csv;

class CsvTest extends TestCase {

   #[Test]
   public function shouldSimulateLoadingCsvFile_fromCommandLine(): void {
      $temp = new TempFile("abc, def,\"ghi \"\n1,2,3\n");
      $argv = ["program", $temp->getPath()];
      $csv = Csv::loadFromCommandLine($argv, 1, "Usage: program filename");
      self::assertEquals (2, count($csv));
      self::assertEquals ("abc", $csv[0][0]);
      self::assertEquals ("ghi", $csv[0][2]);

      $temp->delete();
   }

   #[Test]
   public function shouldSimulateLoadingTsvFile_fromCommandLine(): void {
      $data = "abc\tdef\t\"ghi\t\"\n1\t2\t3\n";
      echo "$data\n";
      $temp = new TempFile($data);
      $argv = ["program", $temp->getPath()];
      $csv = Csv::loadFromCommandLine($argv, 1, "Usage: program filename", "\t");
      self::assertEquals (2, count($csv));
      self::assertEquals ("abc", $csv[0][0]);
      self::assertEquals ("ghi", $csv[0][2]);

      $temp->delete();
   }

   #[Test]
   public function shouldFail_whenNoSuchFile() {
      $argv = ["program", "noSuchFile"];
      $csv = Csv::loadFromCommandLine($argv, 1, "Usage: program filename", "\t", false);
      self::assertEquals (0, count($csv));
   }



}