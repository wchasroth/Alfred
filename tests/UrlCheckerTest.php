<?php

namespace CharlesRothDotNet\Alfred;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;

class UrlCheckerTest extends TestCase {

    #[Test]
    public function shouldVerifyExistingNormalWebsites() {
        self::assertEquals ("200", UrlChecker::check("https://thedance.net"));
        self::assertEquals ("200", UrlChecker::check("https://www.allegandems.com"));
        self::assertEquals ("200", UrlChecker::check("https://www.ioscocountydems.org"));
    }

    #[Test]
    public function shouldDetectFailingNormalWebsites() {
        self::assertFailsWith ("002", "https://www.lapeerdems.com");
        self::assertFailsWith ("002", "https://www.ogemawdems.com");
        self::assertFailsWith ("002", "https://www.branchcountydemocrats.com");
        self::assertFailsWith ("002", "https://cheboygancountydemocraticparty.ruck.us");
        self::assertFailsWith ("405", "https://www.casscountydems.com");
        self::assertFailsWith ("002", "http://www.ioscodemocrats.org");
        self::assertFailsWith ("002", "http://baycountdems.com");
        self::assertFailsWith ("405", "http://bcdemocrats.org");
    }

    private function assertFailsWith (string $expectedCode, string $url): void {
        self::assertEquals($expectedCode, UrlChecker::check($url), " for $url");
    }

    #[Test]
    public function shouldConsiderNonexistentFacebookPage_asNotPublic() {  // Sigh!
        self::assertFailsWith ("201", "https://facebook.com/NoSuchPageNorPerson");
    }

    #[Test]
    public function shouldVerifyExistingPublicFacebookPages() {
        self::assertEquals ("200", UrlChecker::check("https://www.facebook.com/charles.roth.1612/"));
        self::assertEquals ("200", UrlChecker::check("http://www.facebook.com/MiCalhounDems"));
        self::assertEquals ("200", UrlChecker::check("https://www.facebook.com/groups/crawfordcountymichdems"));
    }

    #[Test]
    public function shouldSeeKnownNonPublicFacebookPages_asNotPublic() {
        self::assertEquals ("201", UrlChecker::check("https://www.facebook.com/CheboyganCountyDemocrats"));
    }

}

