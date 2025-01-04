<?php
   declare(strict_types=1);

   use PHPUnit\Framework\TestCase;
   use PHPUnit\Framework\Attributes\Test;
   use PHPUnit\Framework\Attributes\IgnoreDeprecations;

   use CharlesRothDotNet\Alfred\Str;

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
         self::assertSame ("ab",  Str::substringBefore("abcdefc", "c"));
         self::assertSame ("abc", Str::substringBefore("abcdefc", "de"));
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

      //---substringBeforeLast() ----------------------------------------
      #[Test]
      public function shouldExtractSubstringBeforeLast(): void {
         self::assertSame ("abcdef",  Str::substringBeforeLast("abcdefcx",   "c"));
         self::assertSame ("abcdefx", Str::substringBeforeLast("abcdefxdey", "de"));
      }

      #[Test]
      public function shouldGetOriginalString_forNonexistentBeforeLastString(): void {
         self::assertSame ("abcdef", Str::substringBeforeLast("abcdef", "x"));
      }

      #[Test] #[IgnoreDeprecations]  // ignore null warnings
      public function shouldHandleNullsForBeforeLast(): void {
         self::assertEmpty(Str::substringBeforeLast(NULLSTR, NULLSTR));
         self::assertEmpty(Str::substringBeforeLast(NULLSTR, "x"));

         self::assertEmpty(Str::substringBeforeLast("abc", NULLSTR));
         self::assertEmpty(Str::substringBeforeLast("abc", ""));
      }

      //---substringAfterLast() ----------------------------------
      #[Test]
      public function shouldExtractSubstringAfterLast(): void {
          self::assertEquals("X", Str::substringAfterLast("abdef12X", "12"));
          self::assertEquals("X", Str::substringAfterLast("abd12ef12X", "12"));
      }

      #[Test]
      public function shouldGetEmpty_forNonexistentSubstringAfterLast(): void {
          self::assertEmpty(Str::substringAfterLast("abcdef", "x"));
          self::assertEmpty(Str::substringAfterLast("abcdef", ""));
          self::assertEmpty(Str::substringAfterLast("abcdef", NULLSTR));
          self::assertEmpty(Str::substringAfterLast("", "x"));
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

      //---hasAnyOf() ----------------------------------------
      #[Test]
      public function shouldDetectHasAnyOfSubstring(): void {
         self::assertTrue (Str::hasAnyOf("abc", ["b"]));
         self::assertTrue (Str::hasAnyOf("abc", ["x", "b"]));
      }

       #[Test]
       public function shouldHandleNulls_forHasAnyOf(): void {
           self::assertFalse(Str::hasAnyOf("abc", [NULLSTR]));
           self::assertFalse(Str::hasAnyOf(NULLSTR, ["abc"]));
           self::assertFalse(Str::hasAnyOf(NULLSTR, ["abc", "xyz"]));
       }

       #[Test]
       public function shouldFailWhenSubstringNotInHasAnyOf(): void {
           self::assertFalse(Str::hasAnyOf("abc", ["xyz"]));
           self::assertFalse(Str::hasAnyOf("abc", ["xyz", "123"]));
           self::assertFalse(Str::hasAnyOf("abc", []));
       }

      //---contains() ----------------------------------------
      #[Test]
      public function shouldDetectContainedSubstring(): void {
          self::assertTrue (Str::contains("abc", "b"));
          self::assertTrue (Str::contains("abc", "x", "b"));
      }

      #[Test]
      public function shouldHandleNulls_forContains(): void {
         self::assertFalse(Str::contains("abc", NULLSTR));
         self::assertFalse(Str::contains(NULLSTR, "abc"));
         self::assertFalse(Str::contains(NULLSTR, "abc", "xyz"));
      }

      #[Test]
      public function shouldFailWhenSubstringNotContained(): void {
         self::assertFalse(Str::contains("abc", "xyz"));
         self::assertFalse(Str::contains("abc", "xyz", "123"));
         self::assertFalse(Str::contains("abc"));
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

      //---startsWith() ----------------------------------------
      #[Test]
      public function shouldExerciseStartsWith(): void {
         self::assertTrue (Str::startsWith("#comment", "#"));
         self::assertFalse(Str::startsWith("comment",  "#"));
      }

      #[Test]
      public function shouldFailOnEmptyStartsWithCases(): void {
         self::assertFalse (Str::startsWith(NULLSTR,    "#"));
         self::assertFalse (Str::startsWith("",         "#"));
         self::assertFalse (Str::startsWith("#comment", ""));
      }

      //---endsWith() ----------------------------------------
      #[Test]
      public function shouldExerciseEndsWith(): void {
         self::assertTrue (Str::endsWith("comment#", "#"));
         self::assertFalse(Str::endsWith("comment",  "#"));
      }

      #[Test]
      public function shouldFailOnEmptyEndsWithCases(): void {
         self::assertFalse (Str::endsWith(NULLSTR,    "#"));
         self::assertFalse (Str::endsWith("",         "#"));
         self::assertFalse (Str::endsWith("#comment", ""));
      }

      //---removeCommas() ----------------------------------
      #[Test]
      public function shouldRemoveAllCommas(): void {
         self::assertSame ("abcd", Str::removeCommas("abcd"));
         self::assertSame ("1024", Str::removeCommas("1,024"));
         self::assertSame ("",     Str::removeCommas(",,,"));
      }

      #[Test]
      public function shouldHandleEmpty_inRemoveCommas(): void {
         self::assertEmpty (Str::removeCommas(NULLSTR));
         self::assertEmpty (Str::removeCommas(""));
      }

   }
