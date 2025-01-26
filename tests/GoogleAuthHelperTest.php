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
      $helper = $this->makeSimulatedGoogleAuthHelper("http://localhost/Alfred/htdocs/simUserProfile.php");
      $data = $helper->fetchAccessToken("client_secret", "code", $logger);
      self::assertEquals("abcdef", $data['access_token']);
   }
   public function makeSimulatedGoogleAuthHelper(string $simUserProfileUrl): GoogleAuthHelper {
      return new GoogleAuthHelper(
         "http://localhost/Alfred/htdocs/simRedirect.php", "simulatedClientId",
         "http://localhost/Alfred/htdocs/urlBase.php",
         "http://localhost/Alfred/htdocs/simAccessToken.php",
         $simUserProfileUrl);
   }

   #[Test]
   public function shouldSimulateMakingUserProfile() {
      $logger = new DumbFileLogger("C:/WAMP/www/Alfred/log");
      $helper = $this->makeSimulatedGoogleAuthHelper("http://localhost/Alfred/htdocs/simUserProfile.php");
      $userProfile = $helper->getGoogleUserProfile("client_secret", "code", $logger);
      self::assertTrue($userProfile->succeeded());
      self::assertEquals ("wchasroth@gmail.com", $userProfile->getEmail());
   }

   #[Test]
   public function shouldSimulateFailure_whenMakingUserProfile() {
      $logger = new DumbFileLogger("C:/WAMP/www/Alfred/log");
      $helper = $this->makeSimulatedGoogleAuthHelper("http://localhost/Alfred/htdocs/simUserProfileFail.php");
      $userProfile = $helper->getGoogleUserProfile("client_secret", "code", $logger);
      self::assertFalse($userProfile->succeeded());
      self::assertTrue (Str::contains($userProfile->getError(), "Error: no email found"));
   }

   #[Test]
   public function shouldSimulate500Error_whenMakingUserProfile() {
      $logger = new DumbFileLogger("C:/WAMP/www/Alfred/log");
      $helper = $this->makeSimulatedGoogleAuthHelper("http://localhost/Alfred/htdocs/simUserProfile500.php");
      $userProfile = $helper->getGoogleUserProfile("client_secret", "code", $logger);
      self::assertFalse($userProfile->succeeded());
      self::assertTrue (Str::contains($userProfile->getError(), "Error: http 500"));
   }

}