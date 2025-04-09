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

}