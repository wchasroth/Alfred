<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;

use CharlesRothDotNet\Alfred\CsvFile;

class CsvFileTest extends TestCase {

   #[Test]
   public function shouldLoadCsvFileData() {
      $tempFile = tempnam("", "");
      file_put_contents($tempFile, "a,b,c\n1,2,3\n");
      $csv = new CsvFile();
      self::assertTrue ($csv->loadfile($tempFile));
      unlink($tempFile);

      self::assertTrue($csv->isCsv());
      self::assertEquals(2, $csv->getRowCount());
      self::assertEquals("b", $csv->getRow(0)['b']);
      self::assertEquals("2", $csv->getRow(1)['b']);
   }

   #[Test]
   public function shouldFail_givenNonExistentFile() {
      $csv = new CsvFile();
      self::assertFalse($csv->loadfile("nonexistent.csv"));
   }

   #[Test]
   public function shouldExtractFlagArguments() {
      $csv = new CsvFile();
      self::assertFalse($csv->outputAsTsv());

      $argv = $csv->extractFlags(["CvsFileTest", "-t", "input", "output"]);
      self::assertTrue($csv->outputAsTsv());
      self::assertEquals(["CvsFileTest", "input", "output"], $argv);
   }

}