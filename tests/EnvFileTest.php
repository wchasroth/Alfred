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
         file_put_contents("$dir/.testenv", "hi=hello\n");

         $env = new EnvFile(".testenv");
         self::assertSame ("hello", $env->get('hi'));
         self::assertSame (1, count($env->getKeys()));
         self::assertSame ("hi", $env->getKeys()[0]);

         unlink ("$dir/.testenv");
      }

      #[Test]
      public function shouldGetError_whenNoEnvFileFound(): void {
         $env = new EnvFile(".env");
         self::assertSame ("Could not find file .env", $env->getError());
         self::assertSame ("", $env->get('hi'));
      }
   }
