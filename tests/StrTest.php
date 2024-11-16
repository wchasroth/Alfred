<?php
   declare(strict_types=1);

   use PHPUnit\Framework\TestCase;
   use PHPUnit\Framework\Attributes\Test;

   use charlesroth_net\Alfred\Str;

   class StrTest extends TestCase {

      //---substringAfter()
      #[Test]
      public function shouldExtractSubstringAfter(): void {
         self::assertSame ("ef", Str::substringAfter("abcdef", "d"));
         self::assertSame ("f",  Str::substringAfter("abcdef", "de"));
      }

      #[Test]
      public function shouldGetNothingForNonexistentSubstring(): void {
         self::assertEmpty (Str::substringAfter("abcdef", "x"));
      }

      #[Test]
      public function shouldHandleNulls(): void {
         self::assertEmpty("",   Str::substringAfter(null, null));
         self::assertEmpty("",   Str::substringAfter(null, "x"));

         self::assertSame("abc", Str::substringAfter("abc", null));
         self::assertSame("abc", Str::substringAfter("abc", ""));
      }

   }
