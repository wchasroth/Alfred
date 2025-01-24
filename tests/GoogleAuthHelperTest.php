<?php
declare(strict_types=1);

use CharlesRothDotNet\Alfred\GoogleAuthHelper;
use CharlesRothDotNet\Alfred\Str;
use CharlesRothDotNet\Alfred\DumbFileLogger;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\IgnoreDeprecations;

use CharlesRothDotNet\Alfred\AlfredPDO;
use CharlesRothDotNet\Alfred\PdoRunResult;

class GoogleAuthHelperTest extends TestCase {

   #[Test]
   public function shouldSimulateGettingAccessToken() {
      $logger = new DumbFileLogger("C:/WAMP/www/Alfred/log");
      $helper = new GoogleAuthHelper("http://localhost/Alfred/htdocs/simAccessToken.php",
         "http://localhost/Alfred/htdocs/simUserProfile.php");
      $data = $helper->getAccessToken("client_id", "redirect_uri", "client_secret", "code", $logger);
      self::assertEquals("abcdef", $data['access_token']);
   }

   #[Test]
   public function shouldSimulateMakingUserProfile() {
      $logger = new DumbFileLogger("C:/WAMP/www/Alfred/log");
      $helper = new GoogleAuthHelper("http://localhost/Alfred/htdocs/simAccessToken.php",
         "http://localhost/Alfred/htdocs/simUserProfile.php");
      $userProfile = $helper->makeGoogleUserProfile("client_id", "redirect_uri", "client_secret", "code", $logger);
      self::assertTrue($userProfile->succeeded());
      self::assertEquals ("wchasroth@gmail.com", $userProfile->getEmail());
   }

   #[Test]
   public function shouldSimulateFailureMakingUserProfile() {
      $logger = new DumbFileLogger("C:/WAMP/www/Alfred/log");
      $helper = new GoogleAuthHelper("http://localhost/Alfred/htdocs/simAccessToken.php",
         "http://localhost/Alfred/htdocs/simUserProfileFail.php");
      $userProfile = $helper->makeGoogleUserProfile("client_id", "redirect_uri", "client_secret", "code", $logger);
      self::assertFalse($userProfile->succeeded());
      self::assertTrue (Str::contains($userProfile->getError(), "Error"));
   }

}