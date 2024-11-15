<?
   declare(strict_types=1);

   use PHPUnit\Framework\TestCase;
//   use charlesroth_net\Alfred;

   public class StringTest extends TestCase {

      public function testSubstringAfter(): void {
         $this->assertSame ("ef", String::substringAfter("abcdef", "d"));
      }

   }
