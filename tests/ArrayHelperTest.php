<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;

use CharlesRothDotNet\Alfred\ArrayHelper;

class ArrayHelperTest extends TestCase {

   #[Test]
   public function shouldFormatAssociativeArray_byColumns() {
      $myarray  =  ["name" => "quincy township", "type" => "township", "county" => "branch", "pop" => 4108, "sub" => ""];
      $result   = ArrayHelper::formatArrayDisplay($myarray, ["type", "name", "county", "pop"], [20, 33, 30, 7]);
      $expected = '[ "type" => "township",           "name" => "quincy township",                 "county" => "branch",                       "pop" => 4108   ]';
      self::assertEquals ($expected, $result);
   }

   #[Test]
   public function shouldRemoveOnlyUrlsDuplicatedUpToQuerystring() {
      $x = ["abc", "def?hello", "xyz", "def"];
      self::assertEquals (["abc", "def", "xyz"], ArrayHelper::removeUrlsDuplicatedUpToQuerystring($x));

      $x = ["xyz", "abc", "def?hello"];
      self::assertEquals (["xyz", "abc", "def?hello"], ArrayHelper::removeUrlsDuplicatedUpToQuerystring($x));
   }

   #[Test]
   public function shouldExtractAndRemapArrayKeyValues() {
      $input  = ["name" => "Charles Roth", "address1" => "2630 Lillian", "mail" => "wchasroth@gmail.com"];
      $result = ArrayHelper::extractAndRemapArray($input, "name", "address1/address", "mail/email");
      self::assertEquals (3, count($result));
      self::assertEquals ("Charles Roth",        $result["name"]);
      self::assertEquals ("2630 Lillian",        $result["address"]);
      self::assertEquals ("wchasroth@gmail.com", $result["email"]);
   }


}