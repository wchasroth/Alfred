<?php
declare(strict_types=1);

use CharlesRothDotNet\Alfred\StringArray;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;

class StringArrayTest extends TestCase {

   #[Test]
   public function shouldFailToLoadNonexistentFile(): void {
      $sa = new StringArray();
      self::assertFalse ($sa->load("/noSuchFile"));
      self::assertFalse ($sa->hasMore());
      self::assertEquals("", $sa->getNext());
   }

   #[Test]
   public function shouldLoadAllLinesOfFile_andTestEachOne() {
      $tempfile = tempnam("", "");
      $sa = $this->makeStringArrayWithTestData($tempfile);

      self::assertTrue   ($sa->hasMore());
      self::assertEquals ("Hello World",     $sa->getNext());
      self::assertEquals ("Goodbye Moon",    $sa->getNext());
      self::assertEquals ("Greetings, Mars", $sa->getNext());
      self::assertFalse  ($sa->hasMore());

      unlink($tempfile);
   }

   #[Test]
   public function shouldGetNextMatch() {
      $tempfile = tempnam("", "");
      $sa = $this->makeStringArrayWithTestData($tempfile);

      self::assertEquals ("Goodbye Moon",    $sa->getNextMatch("bye"));
      self::assertTrue   ($sa->hasMore());

      unlink($tempfile);
   }

   #[Test]
   public function shouldFindMatchBeforeEnder() {
      $tempfile = tempnam("", "");
      $sa = $this->makeStringArrayWithTestData($tempfile);
      self::assertEquals("Goodbye Moon",    $sa->getNextMatchBefore("G", "Mars"));
      self::assertEquals("Greetings, Mars", $sa->getNext());
      unlink($tempfile);
   }

   #[Test]
   public function shouldNotFindMatchAfterEnder() {
      $tempfile = tempnam("", "");
      $sa = $this->makeStringArrayWithTestData($tempfile);
      self::assertEquals("", $sa->getNextMatchBefore("Mars", "Hello"));
      self::assertEquals ("Hello World",     $sa->getNext());
      unlink($tempfile);
   }

   private function makeStringArrayWithTestData(string $tempFileName): StringArray {
      $sa = new StringArray();
      file_put_contents($tempFileName, "Hello World\nGoodbye Moon\nGreetings, Mars\n");
      $sa->load($tempFileName);
      return $sa;
   }

}