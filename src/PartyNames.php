<?php
declare(strict_types=1);

namespace CharlesRothDotNet\Alfred;

class PartyNames {

   private array $partyToInitial;
   private array $initialToParty;

   function __construct() {
      $this->partyToInitial = [
         "DEMOCRATIC"          => "D",
         "REPUBLICAN"          => "R",
         "LIBERTARIAN"         => "L",
         "GREEN"               => "G",
         "NON PARTISAN"        => "N",
         "NATURAL LAW"         => "A",
         "U.S. TAXPAYERS"      => "T",
         "WORKING CLASS PARTY" => "C",
         "WRITE-IN"            => "W"
      ];
      $this->initialToParty = array_flip($this->partyToInitial);
   }

   public function getName(string $initial): string {
      return $this->initialToParty[trim(strtoupper($initial))] ?? "";
   }

   public function getInitial(string $name): string {
      return $this->partyToInitial[trim(strtoupper($name))] ?? "";
   }


}