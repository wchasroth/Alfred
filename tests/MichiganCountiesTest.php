<?php
declare(strict_types=1);

use CharlesRothDotNet\Alfred\MichiganCounties;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;

class MichiganCountiesTest extends TestCase {
   #[Test]
   public function shouldExerciseGetNumber(): void {
      $counties = new MichiganCounties();
      self::assertEquals(81, $counties->getNumber("WASHTENAW"));
      self::assertEquals( 0, $counties->getNumber("WASH"));
      self::assertEquals( 0, $counties->getNumber("NoSuch"));
   }

   #[Test]
   public function shouldExerciseGetName(): void {
      $counties = new MichiganCounties();
      self::assertEquals("washtenaw", $counties->getName(81));
      self::assertEquals("",          $counties->getName(0));
      self::assertEquals("",          $counties->getName(200));
   }

}