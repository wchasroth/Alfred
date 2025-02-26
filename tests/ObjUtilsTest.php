<?php
declare(strict_types=1);

use CharlesRothDotNet\Alfred\ObjUtils;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ObjUtilsTest extends TestCase {

   #[Test]
   public function shouldGetExistingPropertyFromObject() {
      $x = new stdClass();
      $x->foo = "bar";
      self::assertEquals("bar",   ObjUtils::value($x, "foo"));
      self::assertEquals("bar, ", ObjUtils::value($x, "foo", "", ", "));
   }

   #[Test]
   public function shouldGetDefaultValue_whenPropertyDoesNotExist() {
      $x = new stdClass();
      self::assertEquals ("",        ObjUtils::value($x, "foo"));
      self::assertEquals ("nothing", ObjUtils::value($x, "foo", "nothing"));
   }

   #[Test]
   public function shouldGetExistingArrayPropertyFromObject() {
      $x = new stdClass();
      $x->foo = ["a", "b"];
      self::assertEquals(["a", "b"], ObjUtils::getArray($x, "foo"));
   }

   #[Test]
   public function shouldGetDefaultArrayPropertyFromObject() {
      $x = new stdClass();
      self::assertEquals([""],  ObjUtils::getArray($x, "foo"));
      self::assertEquals(["a"], ObjUtils::getArray($x, "foo", ["a"]));
   }

}