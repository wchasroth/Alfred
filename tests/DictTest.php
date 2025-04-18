<?php
   declare(strict_types=1);

   use PHPUnit\Framework\TestCase;
   use PHPUnit\Framework\Attributes\Test;
   use PHPUnit\Framework\Attributes\IgnoreDeprecations;

   use CharlesRothDotNet\Alfred\Dict;

   require_once('nullstr.php');

   class DictTest extends TestCase {

      //---value() ----------------------------------------
      #[Test]
      public function shouldGetKeyValue_fromDictionary() {
         $dict = array("hi" => "hello");
         self::assertSame("hello",   Dict::value($dict, "hi"));
         self::assertSame("hello, ", Dict::value($dict, "hi", "", ", "));
      }

      #[Test]
      public function shouldGetValueForNumericKey() {
         $dict = ["hi", "bye"];
         self::assertSame("bye", Dict::value($dict, 1));
         self::assertSame("",    Dict::value($dict, 17));
      }

      #[Test]
      public function shouldGetEmptyString_forNoKey_orNoDict() {
         $dict = array("hi" => "hello");
         self::assertSame("", Dict::value($dict,  "noSuchKey"));
         self::assertSame("", Dict::value($dict,  ""));
         self::assertSame("", Dict::value($dict,  NULLSTR));
         self::assertSame("", Dict::value((array) null, "hi"));
      }

      #[Test]
      public function shouldHandleNumericKeyZero() {
         $dict = ["4/4", "something"];
         self::assertEquals ("something", Dict::value($dict, 1));
         self::assertEquals ("something", Dict::value($dict, 1));
         self::assertEquals ("",          Dict::value($dict, 2));
         self::assertEquals ("",          Dict::value($dict, ""));
      }

       //--- intValue() ----------------------------------------
      #[Test]
      public function shouldGetDefaultIntValue_whenGivenNothing() {
         self::assertEquals(0, Dict::intValue([], "a"));
         self::assertEquals(9, Dict::intValue([], "a", 9));
      }

      #[Test]
      public function shouldGetIntValue_fromDictionary() {
         self::assertEquals(1, Dict::intValue(["a" => 1],    "a"));
         self::assertEquals(9, Dict::intValue(["a" => "9"],  "a"));
         self::assertEquals(9, Dict::intValue(["a" => "9z"], "a"));
      }


       //--- getArray() ----------------------------------------
       #[Test]
       public function shouldExerciseGetArray() {
           $dict = array("hi" => ['abc', 'def']);
           self::assertSame(['abc', 'def'], Dict::getArray($dict, "hi"));
           self::assertSame([], Dict::getArray($dict, "noSuchArray"));
       }

   }
