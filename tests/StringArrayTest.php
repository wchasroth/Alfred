<?php
declare(strict_types=1);

use CharlesRothDotNet\Alfred\StringArray;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;

class StringArrayTest extends TestCase {

   #[Test]
   public function shouldFailToLoadNonexistentFile(): void {
      $sa = new StringArray();
      self::assertFalse($sa->load("/noSuchFile"));
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

   private function makeStringArrayWithTestData(string $tempFileName): StringArray {
      $sa = new StringArray();
      file_put_contents($tempFileName, "Hello World\nGoodbye Moon\nGreetings, Mars\n");
      $sa->load($tempFileName);
      return $sa;
   }

}