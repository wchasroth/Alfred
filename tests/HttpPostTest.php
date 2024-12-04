<?php
   declare(strict_types=1);

   use PHPUnit\Framework\TestCase;
   use PHPUnit\Framework\Attributes\Test;
   use PHPUnit\Framework\Attributes\IgnoreDeprecations;

   use CharlesRothDotNet\Alfred\HttpPost;

   require_once('nullstr.php');

   class HttpPostTest extends TestCase {

      //---value() ----------------------------------------
      #[Test]
      public function shouldGetFormValueByKey(): void {
         $_POST['key'] = 'value';
         self::assertSame ('value', HttpPost::value('key'));
      }

      #[Test]
      public function shouldGetEmpty_fromNoOrNonexistentKey(): void {
         unset($_POST['key']);
         self::assertEmpty (HttpPost::value(NULLSTR));
         self::assertEmpty (HttpPost::value('key'));
      }

      //---getPostNum() ----------------------------------------
      #[Test]
      public function shouldGetFormNumberByKey(): void {
         $_POST['key1'] = '17';
         $_POST['key2'] = '18, ';
         self::assertSame (17, HttpPost::number('key1'));
         self::assertSame (18, HttpPost::number('key2'));
      }

      #[Test]
      public function shouldGetZero_fromNoOrNonexistentKey(): void {
         unset($_POST['key']);
         self::assertEquals (0, HttpPost::number(NULLSTR));
         self::assertEquals (0, HttpPost::number('key'));
      }
   }
