<?php
declare(strict_types=1);

use CharlesRothDotNet\Alfred\JsonHelper;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;

class JsonHelperTest extends TestCase {

   #[Test]
   public function shouldGetElementsFromJson() {
      $text = '{"url": "https://regents.umich.edu/regents/carl-j-meyers/", "archived_snapshots": {"closest": {"status": "200", "available": true, "url": "http://web.archive.org/web/20250831112539/https://regents.umich.edu/regents/carl-j-meyers/", "timestamp": "20250831112539"}}}';
      $json = json_decode($text, true);
      self::assertEquals("200", JsonHelper::getElement($json, "archived_snapshots", "closest", "status"));
      self::assertEquals("http://web.archive.org/web/20250831112539/https://regents.umich.edu/regents/carl-j-meyers/",
         JsonHelper::getElement($json, "archived_snapshots", "closest", "url"));
      self::assertTrue(JsonHelper::getElement($json, "archived_snapshots", "closest", "available"));
   }

   #[Test]
   public function shouldGetEmptyString_whenNoChildElementMatches() {
      $text = '{"url": "https://regents.umich.edu/regents/carl-j-meyers/", "archived_snapshots": {"closest": {"status": "200", "available": true, "url": "http://web.archive.org/web/20250831112539/https://regents.umich.edu/regents/carl-j-meyers/", "timestamp": "20250831112539"}}}';
      $json = json_decode($text, true);
      self::assertEquals ("", JsonHelper::getElement($json, "noSuchElement"));
   }

}