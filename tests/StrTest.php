<?php
   declare(strict_types=1);

   use PHPUnit\Framework\TestCase;
   use PHPUnit\Framework\Attributes\Test;
   use PHPUnit\Framework\Attributes\IgnoreDeprecations;

   use charlesroth_net\Alfred\Str;

   class StrTest extends TestCase {

      //---substringAfter()
      #[Test]
      public function shouldExtractSubstringAfter(): void {
         self::assertSame ("ef", Str::substringAfter("abcdef", "d"));
         self::assertSame ("f",  Str::substringAfter("abcdef", "de"));
      }

      #[Test]
      public function shouldGetNothing_forNonexistentAfterString(): void {
         self::assertEmpty (Str::substringAfter("abcdef", "x"));
      }

      #[Test] #[IgnoreDeprecations]  // ignore null warnings
      public function shouldHandleNullsForAfter(): void {
         self::assertEmpty(Str::substringAfter(null, null));
         self::assertEmpty(Str::substringAfter(null, "x"));

         self::assertSame("abc", Str::substringAfter("abc", null));
         self::assertSame("abc", Str::substringAfter("abc", ""));
      }

      //---substringBefore()
      #[Test]
      public function shouldExtractSubstringBefore(): void {
         self::assertSame ("ab",  Str::substringBefore("abcdef", "c"));
         self::assertSame ("abc", Str::substringBefore("abcdef", "de"));
      }

      #[Test]
      public function shouldGetOriginalString_forNonexistentBeforeString(): void {
         self::assertSame ("abcdef", Str::substringBefore("abcdef", "x"));
      }

      #[Test] #[IgnoreDeprecations]  // ignore null warnings
      public function shouldHandleNullsForBefore(): void {
         self::assertEmpty(Str::substringBefore(null, null));
         self::assertEmpty(Str::substringBefore(null, "x"));

         self::assertEmpty(Str::substringBefore("abc", null));
         self::assertEmpty(Str::substringBefore("abc", ""));
      }

      //---substringBetween()
      #[Test]
      public function shouldExtractSubstringBetween(): void {
         self::assertSame ("cd",  Str::substringBetween("abcdef", "b", "e"));
      }

   }
