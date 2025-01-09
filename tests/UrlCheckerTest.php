<?php

namespace CharlesRothDotNet\Alfred;

//use CharlesRothDotNet\Alfred\UrlChecker;
//use CharlesRothDotNet\Alfred\Str;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;

class UrlCheckerTest extends TestCase {

    #[Test]
    public function shouldVerifyExistingSites() {
        self::assertEquals ("200:GOOD", UrlChecker::check("https://thedance.net"));
        self::assertEquals ("200:GOOD", UrlChecker::check("https://www.allegandems.com"));
    }

    #[Test]
    public function shouldDetectFailingUrls() {
        self::assertFailsWith ("000", "https://www.lapeerdems.com");
        self::assertFailsWith ("000", "https://www.ogemawdems.com");
        self::assertFailsWith ("000", "https://www.branchcountydemocrats.com");
        self::assertFailsWith ("000", "https://cheboygancountydemocraticparty.ruck.us");
        self::assertFailsWith ("002", "https://www.casscountydems.com");
        self::assertFailsWith ("002", "http://www.ioscodemocrats.org");
        self::assertFailsWith ("002", "http://baycountdems.com");
        self::assertFailsWith ("403", "http://bcdemocrats.org");
    }

    private function assertFailsWith (string $expectedCode, string $url): void {
        $actualCode = Str::substringBefore(UrlChecker::check($url), ":");
        self::assertEquals($expectedCode, $actualCode, " for $url");
    }

    #[Test]
    public function shouldGetNothing_fromNonexistentUrl() {
        $text = UrlChecker::getTextFromUrl("https://noSuchSite.xyz");
        self::assertEmpty($text);
    }

}
