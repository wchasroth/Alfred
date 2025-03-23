<?php
declare(strict_types=1);

use CharlesRothDotNet\Alfred\StringArray;
use CharlesRothDotNet\Alfred\TempFile;
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
      $tempFile = new TempFile("Hello World\nGoodbye Moon\nGreetings, Mars\n");
      $sa = StringArray::makeFromFile($tempFile->getPath());

      self::assertTrue   ($sa->hasMore());
      self::assertEquals ("Hello World",     $sa->getNext());
      self::assertEquals ("Goodbye Moon",    $sa->getNext());
      self::assertEquals ("Greetings, Mars", $sa->getNext());
      self::assertFalse  ($sa->hasMore());

      $tempFile->delete();
   }

   #[Test]
   public function shouldGetNextMatch() {
      $tempFile = new TempFile("Hello World\nGoodbye Moon\nGreetings, Mars\n");
      $sa = StringArray::makeFromFile($tempFile->getPath());

      self::assertEquals ("Goodbye Moon",    $sa->getNextMatch("bye"));
      self::assertTrue   ($sa->hasMore());

      $tempFile->delete();
   }

   #[Test]
   public function shouldFindMatchBeforeEnder() {
      $tempFile = new TempFile("Hello World\nGoodbye Moon\nGreetings, Mars\n");
      $sa = StringArray::makeFromFile($tempFile->getPath());
      self::assertEquals("Goodbye Moon",    $sa->getNextMatchBefore("G", "Mars"));
      self::assertEquals("Greetings, Mars", $sa->getNext());
      $tempFile->delete();
   }

   #[Test]
   public function shouldNotFindMatchAfterEnder() {
      $tempFile = new TempFile("Hello World\nGoodbye Moon\nGreetings, Mars\n");
      $sa = StringArray::makeFromFile($tempFile->getPath());
      self::assertEquals("", $sa->getNextMatchBefore("Mars", "Hello"));
      self::assertEquals ("Hello World",     $sa->getNext());
      $tempFile->delete();
   }

}