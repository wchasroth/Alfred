<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use CharlesRothDotNet\Alfred\SafeMap;

class SafeMapTest extends TestCase {

   #[Test]
   public function shouldCreateEmptyMap() {
      $map = new SafeMap();
      self::assertFalse ($map->exists("a"));
      self::assertFalse ($map->exists("a"));  // doesn't create 'a'

      self::assertEquals("", $map->getStr("a"));
      self::assertTrue  ($map->exists("a")); // getStr() DID create 'a'
   }

   #[Test]
   public function shouldPutStringsInMap() {
      $map = new SafeMap();
      $map->putStr("a", "alpha")->putStr("b", "beta"); // Note fluent-style.
      self::assertTrue($map->exists("b"));

      self::assertEquals("alpha", $map->getStr("a"));
      self::assertEquals("beta", $map->getStr("b"));
      self::assertEquals("", $map->getStr("noSuchKey"));

      self::assertEquals (3, count(array_intersect($map->getKeys(), ["a", "b", "noSuchKey"])));
   }

   #[Test]
   public function shouldPutIntsInMap() {
      $map = new SafeMap();
      $map->putInt("x", 10)->putInt("y", 11);
      self::assertEquals(10, $map->getInt("x"));
      self::assertEquals(11, $map->getInt("y"));
      self::assertEquals(0, $map->getInt("z"));
   }

   #[Test]
   public function shouldPutMapsInMap() {
      $map = new SafeMap();
      $map->getMap("bucket")->putStr("hole", "water");
      self::assertEquals("water", $map->getMap("bucket")->getStr("hole"));

      $other = new SafeMap();
      $other->putStr("hole", "martha");
      $map->putMap("bucket", $other);
      self::assertEquals("martha", $map->getMap("bucket")->getStr("hole"));
   }

}