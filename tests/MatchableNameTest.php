<?php
declare(strict_types=1);

use CharlesRothDotNet\Alfred\MatchableName;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;

class MatchableNameTest extends TestCase {

   #[Test]
   public function shouldMatch() {
      self::assertTrue($this->match("Charles Roth", "Chuck Roth"));
      self::assertTrue($this->match("larry tiejema", "lawrence stewart tiejema"));
      self::assertTrue($this->match("lisaa mattila", "mattila james lisa"));
      self::assertTrue($this->match("Robert D. Henschel, Jr.", "rob hentschel"));
      self::assertTrue($this->match("robert orlando peña", "robert pena"));
   }

   #[Test]
   public function shouldNotMatch() {
      self::assertFalse($this->match("Charles Roth", "William Roth"));
      self::assertFalse($this->match("anyone", ""));
   }

   #[Test]
   public function shouldGetSimplifiedName() {
      self::assertEquals("charles roth", $this->makeSimplifiedName("Chuck Roth"));
      self::assertEquals("bob henschel jr", $this->makeSimplifiedName("Robert D. Henschel, Jr."));
   }

   private function makeSimplifiedName(string $name): string {
      $mn = new MatchableName($name);
      return $mn->getSimplifiedName();
   }

   private function match(string $name1, string $name2): bool {
      $match1 = new MatchableName($name1);
      $match2 = new MatchableName($name2);
      return $match1->matches($match2, 2);
   }

}