<?php
declare(strict_types=1);

namespace CharlesRothDotNet\Alfred;

class MichiganCounties {
   private array $number2name = [
      1 => "alcona",
      2 => "alger",
      3 => "allegan",
      4 => "alpena",
      5 => "antrim",
      6 => "arenac",
      7 => "baraga",
      8 => "barry",
      9 => "bay",
      10 => "benzie",
      11 => "berrien",
      12 => "branch",
      13 => "calhoun",
      14 => "cass",
      15 => "charlevoix",
      16 => "cheboygan",
      17 => "chippewa",
      18 => "clare",
      19 => "clinton",
      20 => "crawford",
      21 => "delta",
      22 => "dickinson",
      23 => "eaton",
      24 => "emmet",
      25 => "genesee",
      26 => "gladwin",
      27 => "gogebic",
      28 => "grand traverse",
      29 => "gratiot",
      30 => "hillsdale",
      31 => "houghton",
      32 => "huron",
      33 => "ingham",
      34 => "ionia",
      35 => "iosco",
      36 => "iron",
      37 => "isabella",
      38 => "jackson",
      39 => "kalamazoo",
      40 => "kalkaska",
      41 => "kent",
      42 => "keweenaw",
      43 => "lake",
      44 => "lapeer",
      45 => "leelanau",
      46 => "lenawee",
      47 => "livingston",
      48 => "luce",
      49 => "mackinac",
      50 => "macomb",
      51 => "manistee",
      52 => "marquette",
      53 => "mason",
      54 => "mecosta",
      55 => "menominee",
      56 => "midland",
      57 => "missaukee",
      58 => "monroe",
      59 => "montcalm",
      60 => "montmorency",
      61 => "muskegon",
      62 => "newaygo",
      63 => "oakland",
      64 => "oceana",
      65 => "ogemaw",
      66 => "ontonagon",
      67 => "osceola",
      68 => "oscoda",
      69 => "otsego",
      70 => "ottawa",
      71 => "presque isle",
      72 => "roscommon",
      73 => "saginaw",
      74 => "st clair",
      75 => "st joseph",
      76 => "sanilac",
      77 => "schoolcraft",
      78 => "shiawassee",
      79 => "tuscola",
      80 => "van buren",
      81 => "washtenaw",
      82 => "wayne",
      83 => "wexford"
   ];
   private array $name2number = [];

   function __construct() {
      $this->name2number = array_flip($this->number2name);
   }

   public function getNumber(string $name): int {
      return $this->name2number[strtolower($name)] ?? 0;
   }

   public function getName(int $number): string {
      if ($number < 1  ||  $number > 83)  return "";
      return $this->number2name[$number];
   }




   }