<?php
declare(strict_types=1);

use CharlesRothDotNet\Alfred\PartyNames;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;

class PartyNamesTest extends TestCase {

   #[Test]
   public function shouldTranslateNameToInitial() {
      $pn = new PartyNames();
      self::assertEquals("D", $pn->getInitial("Democratic"));
      self::assertEquals("",  $pn->getInitial("NoSuchParty"));
   }

   #[Test]
   public function shouldTranslateInitialToName() {
      $pn = new PartyNames();
      self::assertEquals("DEMOCRATIC", $pn->getName("D"));
      self::assertEquals("REPUBLICAN", $pn->getName("r"));
      self::assertEquals("",           $pn->getName("Z"));
   }

}