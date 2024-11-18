<?php
   declare(strict_types=1);

   use PHPUnit\Framework\TestCase;
   use PHPUnit\Framework\Attributes\Test;
   use PHPUnit\Framework\Attributes\IgnoreDeprecations;

   use charlesroth_net\Alfred\Forms;

   require_once('nullstr.php');

   class FormsTest extends TestCase {

      //---getPost() ----------------------------------------
      #[Test]
      public function shouldGetPostFromKeyValue(): void {
         $_POST['key'] = 'value';
         self::assertSame ('value', Forms::getPost('key'));
      }

      #[Test]
      public function shouldGetEmpty_fromNoOrNonexistentKey(): void {
         unset($_POST['key']);
         self::assertEmpty (Forms::getPost(NULLSTR));
         self::assertEmpty (Forms::getPost('key'));
      }

      //---getPostNum() ----------------------------------------
      #[Test]
      public function shouldGetPostNum(): void {
         $_POST['key1'] = '17';
         $_POST['key2'] = '17,';
         self::assertSame (17, Forms::getPostNum('key1'));
         self::assertSame (17, Forms::getPostNum('key2'));
      }

      #[Test]
      public function shouldGetZero_fromNoOrNonexistentKey(): void {
         unset($_POST['key']);
         self::assertEquals (0, Forms::getPostNum(NULLSTR));
         self::assertEquals (0, Forms::getPostNum('key'));
      }
   }
