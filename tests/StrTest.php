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


      //---substringsBetween() ----------------------------------------
      #[Test]
      public function shouldExtractSubstringsBetween(): void {
         self::assertEquals (["cd"],          Str::substringsBetween("abcdef", "b", "e"));
         self::assertEquals ([""],            Str::substringsBetween("abcdef", "ab", "cd"));
         self::assertEquals (["cd", "ody "],  Str::substringsBetween("abcdef body edge", "b", "e"));
      }

      #[Test]
      public function shouldGetEmptyArray_onAnyNullArgument(): void {
         self::assertEquals([], Str::substringsBetween(NULLSTR, "a", "c"));
         self::assertEquals([], Str::substringsBetween("abc", NULLSTR, "c"));
         self::assertEquals([], Str::substringsBetween("abc", "a", NULLSTR));
      }

      #[Test]
      public function shouldGetEmptyArray_whenBetweenBoundariesNotFound(): void {
         self::assertEquals([], Str::substringsBetween("abcd", "a", "e"));
         self::assertEquals([], Str::substringsBetween("abcd", "x", "d"));
         self::assertEquals([], Str::substringsBetween("abcd", "d", "a"));
      }

      #[Test]
      public function shouldGetProperBetweenStrings_evenWhenCloseIsPartOfOpenString(): void {
         $header = "HTTP/1.1 403 Forbidden";
         self::assertEquals (["403"], Str::substringsBetween($header, "HTTP/1.1 ", " "));
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

      #[Test]
      public function shouldGetProperBetweenString_evenWhenCloseIsPartOfOpenString(): void {
          $header = "HTTP/1.1 403 Forbidden";
          self::assertEquals ("403", Str::substringBetween($header, "HTTP/1.1 ", " "));
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
          self::assertTrue (Str::contains("  https://www.facebook.com", "http"));
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

      //---contains() ----------------------------------------
      #[Test]
      public function shouldDetectAllContainedSubstrings(): void {
         self::assertTrue (Str::containsAll("abcdef", "b"));
         self::assertTrue (Str::containsAll("abcdef", "b", "f"));
         self::assertTrue (Str::containsAll("abcdef", "b", "a"));
      }

      #[Test]
      public function shouldFailWhenAnyOneStringNotContained(): void {
         self::assertFalse (Str::containsAll("abcdef", "c", "x"));
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
      public function shouldRemoveAll_givenArrayOfStrings(): void {
         self::assertSame ("Hello,myworld", Str::removeAll("Hello, my world!", array(' ', '!')));
         self::assertSame ("1",  Str::removeALl("1st", ["st", "nd"]));
         self::assertSame ("2",  Str::removeALl("2nd", ["st", "nd"]));
         self::assertSame ("sd", Str::removeALl("sd", ["st", "nd"]));
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

      //---splitIntoTokens() ----------------------------------------
      #[Test]
      public function shouldSplitTokens(): void {
         self::assertEquals (["Hello,", "World"], Str::splitIntoTokens(" Hello,   World ",  " "));
         self::assertEquals (["Hello, World"],    Str::splitIntoTokens("Hello, World",  "x"));
      }

      #[Test]
      public function shouldHandleNulls_inSplitTokens(): void {
         self::assertSame ([],        Str::splitIntoTokens(NULLSTR, "x"));
         self::assertSame ([],        Str::splitIntoTokens("", "x"));
         self::assertSame (["hello"], Str::splitIntoTokens("hello", NULLSTR));
         self::assertSame (["hello"], Str::splitIntoTokens("hello", ""));
      }

      //---join() ----------------------------------------
      #[Test]
      public function shouldJoin(): void {
          self::assertEquals ("abc:def:xyz", Str::join(["abc", "def", "xyz"], ":"));
          self::assertEquals ("abcdefxyz", Str::join(["abc", "def", "xyz"], null));
          self::assertEquals ("", Str::join([], ":"));
          self::assertEquals ("", Str::join(null, ":"));
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

      //---ordinalValue() ----------------------------------
      #[Test]
      public function shouldConvertSpelledOutOrdinalsToInts() {
         self::assertEquals ( 1, Str::ordinalValue("First"));
         self::assertEquals ( 1, Str::ordinalValue("1st"));
         self::assertEquals ( 2, Str::ordinalValue("SECOND"));
         self::assertEquals ( 3, Str::ordinalValue("third "));
         self::assertEquals (10, Str::ordinalValue(" TENTH "));
         self::assertEquals (10, Str::ordinalValue(" 10th "));
      }

      #[Test]
      public function shouldFailToConvertNonOrdinals() {
         self::assertEquals (0, Str::ordinalValue(""));
         self::assertEquals (0, Str::ordinalValue("eleventh"));  // only handles 1-10 so far
         self::assertEquals (0, Str::ordinalValue("xyz"));
      }

      //---reorderName() ----------------------------------
      #[Test]
      public function shouldReorderBackwardsName() {
         self::assertEquals("ROBERT F. KENNEDY JR.", Str::reorderName(" KENNEDY, JR., ROBERT F."));
         self::assertEquals("ELISSA SLOTKIN", Str::reorderName("SLOTKIN, ELISSA "));
      }

      #[Test]
      public function shouldLeaveNormalNameIntact() {
         self::assertEquals("ELISSA SLOTKIN", Str::reorderName("ELISSA SLOTKIN"));
      }

      //---jsonify() ----------------------------------
      #[Test]
      public function shouldJsonifyNameValueWithComma() {
         self::assertEquals ('"name": "charles", ', Str::jsonify("name", "charles"));
         self::assertEquals ('"number": 37, ',      Str::jsonify("number", 37));
      }

      #[Test]
      public function shouldJsonifyNameValueWithoutComma() {
         self::assertEquals ('"name": "charles"', Str::jsonifyLast("name", "charles"));
         self::assertEquals ('"number": 37',      Str::jsonifyLast("number", 37));
      }

      //---singleQuoted() ----------------------------------
      #[Test]
      public function shouldSingleQuoteArgument_withOptionalSuffix() {
         self::assertEquals ("'abc'",   Str::singleQuoted("abc"));
         self::assertEquals ("'abc', ", Str::singleQuoted("abc", ", "));
         self::assertEquals ("'O\'Malley'", Str::singleQuoted("O'Malley"));
      }

      //---equalsIgnoreCase() ----------------------------------
      #[Test]
      public function shouldEquateSameStrings_differentCase() {
         self::assertTrue (Str::equalsIgnoreCase("abc", "abc"));
         self::assertTrue (Str::equalsIgnoreCase("ABC", "abc"));
      }

      #[Test]
      public function shouldFailGivenDifferentStrings() {
         self::assertFalse (Str::equalsIgnoreCase("acb", "abc"));
      }

      //---findEmailAddresses() ----------------------------------
      #[Test]
      public function shouldFindEmails() {
         $text = "I'm wchasroth@gmail.com, hello!  Once mailto:croth@thedance.net But also croth@thedance.net ";
         self::assertEquals (["wchasroth@gmail.com", "croth@thedance.net"], Str::findEmailAddresses($text));
      }

      #[Test]
      public function shouldNotFindAnyEmails() {
         self::assertNone (Str::findEmailAddresses("I'm wchasroth @gmail.com, hello!  Once croth@thedance"));
         self::assertNone (Str::findEmailAddresses(""));
         self::assertNone (Str::findEmailAddresses("website is https://thedance.net blah @thedance.net"));
      }

      //---removeEmailAddresses() ----------------------------------
      #[Test]
      public function shouldRemoveEmails() {
         $text = "I'm wchasroth@gmail.com, hello!  Once mailto:croth@thedance.net  ";
         self::assertEquals ("I'm hello!  Once  ", Str::removeEmailAddresses($text));
      }

      #[Test]
      public function shouldNotRemoveAnyEmails() {
         self::assertEquals ("I'm wchasroth @gmail.com, hello!  Once croth@thedance", Str::removeEmailAddresses("I'm wchasroth @gmail.com, hello!  Once croth@thedance"));
      }

      //---findUrls() ----------------------------------
      #[Test]
      public function shouldFindUrls() {
         $text = "My website is https://CharlesRoth.net, but also http://thedance.net yeah!";
         self::assertEquals (["https://CharlesRoth.net", "http://thedance.net"], Str::findUrls($text));

         $www = "Instead, try www.charlesroth.net, that might work too.";
         self::assertEquals (["https://www.charlesroth.net"], Str::findUrls($www));
      }

      #[Test]
      public function shouldRemoveUrls() {
         $text = "My website is https://CharlesRoth.net, but also http://thedance.net yeah!";
         self::assertEquals("My website is but also yeah!", Str::removeUrls($text));

         $www = "Instead, try www.charlesroth.net, that might work too.";
         self::assertEquals ("Instead, try that might work too.", Str::removeUrls($www));
      }

      #[Test]
      public function shouldNotFindAnyUrls() {
         self::assertNone (Str::findUrls(""));
         self::assertNone (Str::findUrls("I'm wchasroth @gmail.com, hello!  Once croth@thedance"));
         self::assertNone (Str::findEmailAddresses("website is https//thedance.net blah @thedance.net"));
      }

      //---findPhones() ----------------------------------
      #[Test]
      public function shouldFindPhones() {
         $text = "well,586-713-4305 & We (248) 396-9571 blah";
         self::assertEquals (["586-713-4305", "248-396-9571"], Str::findPhones($text));
      }

      #[Test]
      public function shouldNotFindPhones() {
         $text = "well 5x6-713-4305 & We (248] 396-9571 blah NOR  12 3 456-7890";
         self::assertNone (Str::findPhones($text));
      }

      private static function assertNone($collection): void {
         self::assertCount(0, $collection);
      }

      //---removePhones() ----------------------------------
      #[Test]
      public function shouldRemovePhones() {
         $text = "well 586-713-4305 & We (248) 396-9571 blah";
         self::assertEquals ("well & We blah", Str::removePhones($text));
      }

      #[Test]
      public function shouldNotRemovePhones() {
         $text = "well 5x6-713-4305 & We (248] 396-9571 blah NOR  12 3 456-7890";
         self::assertEquals ($text, Str::removePhones($text));
      }



   }
