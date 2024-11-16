<?php
   declare(strict_types=1);

   use PHPUnit\Framework\TestCase;
   use charlesroth_net\alfred\Str;

   require_once 'src/bootstrap.php';

   class StrTest extends TestCase {

      public function testSubstringAfter(): void {
         $this->assertSame ("ef", Str::substringAfter("abcdef", "d"));
      }

   }
