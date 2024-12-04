<?php
   declare(strict_types=1);

   use PHPUnit\Framework\TestCase;
   use PHPUnit\Framework\Attributes\Test;
   use PHPUnit\Framework\Attributes\IgnoreDeprecations;

   use CharlesRothDotNet\Alfred\HttpGet;

   require_once('nullstr.php');

   class HttpGetTest extends TestCase {

      //---value() ----------------------------------------
      #[Test]
      public function shouldGetFormValueByKey(): void {
         $_GET['key'] = 'value';
         self::assertSame ('value', HttpGet::value('key'));
      }

      #[Test]
      public function shouldGetFormDefaultValue(): void {
         unset($_GET['key']);
         self::assertSame ('def', HttpGet::value('key', 'def'));
      }

      #[Test]
      public function shouldGetEmpty_fromNoOrNonexistentKey(): void {
         unset($_GET['key']);
         self::assertEmpty (HttpGet::value(NULLSTR));
         self::assertEmpty (HttpGet::value('key'));
      }

      //---number() ----------------------------------------
      #[Test]
      public function shouldGetFormNumberByKey(): void {
         $_GET['key1'] = '17';
         $_GET['key2'] = '18, ';
         self::assertSame (17, HttpGet::number('key1'));
         self::assertSame (18, HttpGet::number('key2'));
      }

      #[Test]
      public function shouldGetDefaultFormNumber(): void {
         unset($_GET['key']);
         self::assertSame (17, HttpGet::number('key',   17));
         self::assertSame (17, HttpGet::number(NULLSTR, 17));
      }

      #[Test]
      public function shouldGetZero_fromNoOrNonexistentKey(): void {
         unset($_GET['key']);
         self::assertEquals (0, HttpGet::number(NULLSTR));
         self::assertEquals (0, HttpGet::number('key'));
      }
   }
