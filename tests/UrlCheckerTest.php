<?php

namespace CharlesRothDotNet\Alfred;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;

class UrlCheckerTest extends TestCase {

    #[Test]
    public function shouldVerifyExistingNormalWebsites() {
        self::assertEquals ("200:GOOD", UrlChecker::check("https://thedance.net"));
        self::assertEquals ("200:GOOD", UrlChecker::check("https://www.allegandems.com"));
    }

    #[Test]
    public function shouldDetectFailingNormalWebsites() {
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
    public function shouldDetectNonexistentFacebookPage() {
        self::assertFailsWith ("901", "https://facebook.com/NoSuchPageNorPerson");
    }

    #[Test]
    public function shouldVerifyExistingFacebookPage() {
        self::assertEquals ("200:GOOD", UrlChecker::check("https://www.facebook.com/charles.roth.1612/"));
        self::assertEquals ("200:GOOD", UrlChecker::check("https://www.facebook.com/MiCalhounDems"));
        self::assertEquals ("200:GOOD", UrlChecker::check("http://www.facebook.com/MiCalhounDems"));
        self::assertEquals ("200:GOOD", UrlChecker::check("https://www.facebook.com/groups/crawfordcountymichdems"));
    }

}

