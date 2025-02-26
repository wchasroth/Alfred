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
      public function shouldGetEmptyString_forNoKey_orNoDict() {
         $dict = array("hi" => "hello");
         self::assertSame("", Dict::value($dict,  "noSuchKey"));
         self::assertSame("", Dict::value($dict,  ""));
         self::assertSame("", Dict::value($dict,  NULLSTR));
         self::assertSame("", Dict::value((array) null, "hi"));
      }

       //--- getArray() ----------------------------------------
       #[Test]
       public function shouldExerciseGetArray() {
           $dict = array("hi" => ['abc', 'def']);
           self::assertSame(['abc', 'def'], Dict::getArray($dict, "hi"));
           self::assertSame([], Dict::getArray($dict, "noSuchArray"));
       }

   }
