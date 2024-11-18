<?php
   declare(strict_types=1);

   use PHPUnit\Framework\TestCase;
   use PHPUnit\Framework\Attributes\Test;
   use PHPUnit\Framework\Attributes\IgnoreDeprecations;

   use charlesroth_net\Alfred\Str;

   require_once('nullstr.php');

   class StrTest extends TestCase {

      //---substringAfter() ----------------------------------------
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
         self::assertEmpty(Str::substringAfter(NULLSTR, NULLSTR));
         self::assertEmpty(Str::substringAfter(NULLSTR, "x"));

         self::assertSame("abc", Str::substringAfter("abc", NULLSTR));
         self::assertSame("abc", Str::substringAfter("abc", ""));
      }

      //---substringBefore() ----------------------------------------
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
         self::assertEmpty(Str::substringBefore(NULLSTR, NULLSTR));
         self::assertEmpty(Str::substringBefore(NULLSTR, "x"));

         self::assertEmpty(Str::substringBefore("abc", NULLSTR));
         self::assertEmpty(Str::substringBefore("abc", ""));
      }

      //---substringBetween() ----------------------------------------
      #[Test]
      public function shouldExtractSubstringBetween(): void {
         self::assertSame ("cd",  Str::substringBetween("abcdef", "b", "e"));
         self::assertSame ("",    Str::substringBetween("abcdef", "ab", "cd"));
      }

      #[Test]
      public function shouldGetEmpty_onAnyNullArgument(): void {
         self::assertEmpty(Str::substringBetween(NULLSTR, "a", "c"));
         self::assertEmpty(Str::substringBetween("abc", NULLSTR, "c"));
         self::assertEmpty(Str::substringBetween("abc", "a", NULLSTR));
      }

      #[Test]
      public function shouldGetEmpty_whenBetweenBoundariesNotFound(): void {
         self::assertEmpty(Str::substringBetween("abcd", "a", "e"));
         self::assertEmpty(Str::substringBetween("abcd", "x", "d"));
         self::assertEmpty(Str::substringBetween("abcd", "d", "a"));
      }

      //---contains() ----------------------------------------
      #[Test]
      public function shouldDetectContainedSubstring(): void {
         self::assertTrue (Str::contains("abc", "b"));
      }

      #[Test]
      public function shouldHandleNulls_forContains(): void {
         self::assertFalse(Str::contains("abc", NULLSTR));
         self::assertFalse(Str::contains(NULLSTR, "abc"));
      }

      #[Test]
      public function shouldFailWhenSubstringNotContained(): void {
         self::assertFalse(Str::contains("abc", "xyz"));
      }

      //---replaceAll() ----------------------------------------
      #[Test]
      public function shouldReplaceAllInstancesofSubstring(): void {
         self::assertSame ("Hallo, all thasa worlds!", Str::replaceAll("Hello, all these worlds!", "e", "a"));
         self::assertSame ("Hllo, all ths worlds!",    Str::replaceAll("Hello, all these worlds!", "e", ""));
         self::assertSame ("Hllo, all ths worlds!",    Str::replaceAll("Hello, all these worlds!", "e", NULLSTR));
      }

      #[Test]
      public function shouldDoNothingInReplace_givenNulls(): void {
         self::assertEmpty(Str::replaceAll(NULLSTR, "a", "b"));
         self::assertSame ("Hello, all these worlds!", Str::replaceAll("Hello, all these worlds!", NULLSTR, "a"));
      }

      //---firstNonEmpty() ----------------------------------------
      #[Test]
      public function shouldFindFirstNonEmpty(): void {
         self::assertSame ("abc", Str::firstNonEmpty("abc"));
         self::assertSame ("abc", Str::firstNonEmpty("abc", "def"));
         self::assertSame ("abc", Str::firstNonEmpty(NULLSTR, "abc"));
         self::assertSame ("abc", Str::firstNonEmpty("", "abc"));
         self::assertSame ("",    Str::firstNonEmpty(""));
         self::assertSame ("",    Str::firstNonEmpty());
      }

      //---removeAll() ----------------------------------------
      #[Test]
      public function shouldRemoveAll_givenArrayOfChars(): void {
         self::assertSame ("Hello,myworld", Str::removeAll("Hello, my world!", array(' ', '!')));
      }

      #[Test]
      public function shouldHandleNulls_inRemoveAll(): void {
         self::assertSame ("", Str::removeAll(NULLSTR, array(' ', '!')));
         self::assertSame ("Hello", Str::removeAll("Hello", (array) null));
      }

      //---split() ----------------------------------------
      #[Test]
      public function shouldSplit(): void {
         self::assertSame (array("Hello", " World"),     Str::split("Hello, World",  ","));
         self::assertSame (array("Hello", " World", ""), Str::split("Hello, World,", ","));
         self::assertSame (array("Hello, World"),        Str::split("Hello, World",  "x"));
      }

      #[Test]
      public function shouldHandleNulls_inSplit(): void {
         self::assertSame (array(),        Str::split(NULLSTR, "x"));
         self::assertSame (array(),        Str::split("", "x"));
         self::assertSame (array("hello"), Str::split("hello", NULLSTR));
         self::assertSame (array("hello"), Str::split("hello", ""));
      }

   }
