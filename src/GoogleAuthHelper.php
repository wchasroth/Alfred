<?php

namespace CharlesRothDotNet\Alfred;

use CharlesRothDotNet\Alfred\Str;
use CharlesRothDotNet\Alfred\Dict;
use \Exception;

class GoogleAuthHelper {
   private string $googleLoginUrlBase;
   private string $googleAccessTokenUrl;
   private string $googleUserInfoUrl;
   private string $clientRedirectToUrl;
   private string $clientId;

   // These are the default values for talking to the real Google Auth service.
   // They can be overridden in the constructor, with values supplied inside unit-tests.
   const DefaultGoogleLoginUrlBase = "https://accounts.google.com/o/oauth2/v2/auth?scope="
      . "https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fuserinfo.profile+https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fuserinfo.email";  // note URL encoding.
   const DefaultGoogleAccessTokenUrl = "https://www.googleapis.com/oauth2/v4/token";
   const DefaultGoogleUserProfileUrl = "https://www.googleapis.com/oauth2/v2/userinfo?fields=name,email,gender,id,picture,verified_email";

   public function __construct(string $clientRedirectToUrl, string $clientId,
             string $googleLoginUrlBase   = self::DefaultGoogleLoginUrlBase,
             string $googleAccessTokenUrl = self::DefaultGoogleAccessTokenUrl,
             string $googleUserInfoUrl    = self::DefaultGoogleUserProfileUrl) {
      $this->clientRedirectToUrl  = $clientRedirectToUrl;
      $this->clientId             = $clientId;
      $this->googleLoginUrlBase   = $googleLoginUrlBase;
      $this->googleAccessTokenUrl = $googleAccessTokenUrl;
      $this->googleUserInfoUrl    = $googleUserInfoUrl;
   }

   public function makeGoogleLoginUrl(): string {
      return $this->googleLoginUrlBase
         . "&redirect_uri=" . urlencode($this->clientRedirectToUrl)
         . "&response_type=code"
         . "&client_id=$this->clientId"
         . "&access_type=online";
   }

   public function getGoogleUserProfile($clientSecret, $googleAuthorizationCode, $logger): GoogleUserProfile {
      $accessTokenWrapper = $this->fetchAccessToken($clientSecret, $googleAuthorizationCode, $logger);
      $accessToken = Dict::value($accessTokenWrapper, 'access_token');
      if (empty($accessToken))                     return new GoogleUserProfile("", "Error: no accessToken returned");
      if (Str::startsWith($accessToken, 'Error:')) return new GoogleUserProfile("", $accessToken);

      $user_info = $this->fetchUserProfileInfo($accessToken);
      $email = Dict::value($user_info, 'email');
      if (! empty($email)) return new GoogleUserProfile($email, "");

      $error = Dict::value($user_info, 'error', "Error: no email found");
      return  new GoogleUserProfile("", $error);
   }

   // --- Everything below here is private, or should be treated as private (e.g. called only by unit-tests).
   function fetchAccessToken(string $client_secret, string $code, $logger) {
      $curlPost = "client_id=$this->clientId&redirect_uri=$this->clientRedirectToUrl&client_secret=$client_secret&code=$code&grant_type=authorization_code";
      $logger->log("curlPost=" . $curlPost);
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $this->googleAccessTokenUrl);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
      $logger->log("about to exec");
      $data = json_decode(curl_exec($ch), true);
      $logger->log("after exec");
      $logger->log("data: " . print_r($data, true));
      $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      $logger->log("http code=" . $http_code);
      return ($http_code == 200 ? $data : ['access_token' => 'Error : Failed to receive access token']);
   }

   private function fetchUserProfileInfo($access_token) {
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $this->googleUserInfoUrl);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $access_token));
      $data = json_decode(curl_exec($ch), true);
      $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      if ($http_code != 200) $data['error'] = "Error: http $http_code";
      return $data;
   }

}