<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;

use CharlesRothDotNet\Alfred\MichiganCounty;

class MichiganCountyTest extends TestCase {

   #[Test]
   public function shouldExerciseGetId() {
      self::assertEquals (1,  MichiganCounty::getId("Alcona"));
      self::assertEquals (1,  MichiganCounty::getId("Alcona "));
      self::assertEquals (0,  MichiganCounty::getId("OuterSpace"));
      self::assertEquals (0,  MichiganCounty::getId(""));

      self::assertEquals ( 75, MichiganCounty::getId("St Joseph"));
      self::assertEquals (999, MichiganCounty::getId("virtual"));
   }

   #[Test]
   public function shouldExerciseGetName() {
      self::assertEquals ("ALCONA",    MichiganCounty::getName(1));
      self::assertEquals ("",          MichiganCounty::getName(-1));
      self::assertEquals ("",          MichiganCounty::getName(84));

      self::assertEquals ("ST JOSEPH", MichiganCounty::getName(75));

   }

}