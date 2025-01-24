<?php

namespace CharlesRothDotNet\Alfred;

use CharlesRothDotNet\Alfred\Str;
use \Exception;

class GoogleAuthHelper {
   private string $accessTokenUrl;
   private string $userInfoUrl;

   public function __construct(string $accessTokenUrl, string $userInfoUrl) {
      $this->accessTokenUrl = $accessTokenUrl;
      $this->userInfoUrl = $userInfoUrl;
   }

   public static function makeDefaultGoogleAuthHelper(): GoogleAuthHelper {
      return new GoogleAuthHelper("https://www.googleapis.com/oauth2/v4/token",
         "https://www.googleapis.com/oauth2/v2/userinfo?fields=name,email,gender,id,picture,verified_email");
   }

   public function makeGoogleUserProfile($clientId, $clientRedirectUrl, $clientSecret, $code, $logger): GoogleUserProfile {
      $data = $this->getAccessToken($clientId, $clientRedirectUrl, $clientSecret, $code, $logger);
      $accessToken = $data['access_token'];
      if (Str::startsWith($accessToken, 'Error:')) return new GoogleUserProfile("", $accessToken);

      $user_info = $this->getUserProfileInfo($accessToken);
      $email = $user_info['email'];
      return new GoogleUserProfile($email, "");
   }

   function getAccessToken(string $client_id, string $redirect_uri, string $client_secret, string $code, $logger) {
      $curlPost = 'client_id=' . $client_id . '&redirect_uri=' . $redirect_uri . '&client_secret=' . $client_secret . '&code=' . $code . '&grant_type=authorization_code';
      $logger->log("curlPost=" . $curlPost);
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $this->accessTokenUrl);
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

   function getUserProfileInfo($access_token) {
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $this->userInfoUrl);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $access_token));
      $data = json_decode(curl_exec($ch), true);
      $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      if ($http_code != 200)
         throw new Exception('Error : Failed to get user information');

      return $data;
   }

}