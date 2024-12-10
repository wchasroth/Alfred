<?php
   declare(strict_types=1);

   use PHPUnit\Framework\TestCase;
   use PHPUnit\Framework\Attributes\Test;
   use PHPUnit\Framework\Attributes\IgnoreDeprecations;

   use CharlesRothDotNet\Alfred\EnvFile;

   require_once('nullstr.php');

   class EnvFileTest extends TestCase {

      #[Test]
      public function shouldReadEnvVars_fromTemporaryEnvFile(): void {
         $dir = str_replace("\\", "/", getcwd());
         file_put_contents("$dir/.testenv", "hi=hello\ntest=true\nnum=37\n");

         $env = new EnvFile(".testenv");
         self::assertSame ("hello", $env->get('hi'));
         self::assertSame (3, count($env->getKeys()));
         self::assertSame ("hi", $env->getKeys()[0]);

         self::assertTrue ($env->bool('test'));
         self::assertFalse($env->bool('hi'));
         self::assertFalse($env->bool('noSuchKey'));

         self::assertSame (37, $env->int('num'));
         self::assertSame ( 0, $env->int('hi'));
         self::assertSame ( 0, $env->int('noSuchKey'));

         unlink ("$dir/.testenv");
      }

      #[Test]
      public function shouldGetError_whenNoEnvFileFound(): void {
         $env = new EnvFile(".env");
         self::assertSame ("Could not find file .env", $env->getError());
         self::assertSame ("", $env->get('hi'));
      }
   }
