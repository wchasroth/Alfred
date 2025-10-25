<?php
declare(strict_types=1);

use CharlesRothDotNet\Alfred\MatchablePlace;
use CharlesRothDotNet\Alfred\Str;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;

class MatchablePlaceTest extends TestCase {

   #[Test]
   public function shouldConstructMatchablePlace() {
      $place = new MatchablePlace("Bark_River-Harris_School_District", ["school", "district"]);
      self::assertEquals ("Bark_River-Harris_School_District", $place->getOriginal());
      self::assertEquals (["bark", "river", "harris"], $place->getWords());
   }

   #[Test]
   public function shouldFindBestMatch() {
      $remove = ["school", "schools", "district"];
      $others = ["Benzie_County_Central_Schools", "Central_Lake_Public_Schools", "Central_Montcalm_Public_Schools", "Superior_Central_School_District"];
      $places = [];
      foreach ($others as $other)   $places[] = new MatchablePlace($other, $remove);
      $mine = new MatchablePlace("Central Montcalm Public School", $remove);
      self::assertEquals (2, $mine->findBestMatch($places));
   }

}