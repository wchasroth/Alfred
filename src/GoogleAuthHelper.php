<?php

namespace CharlesRothDotNet\Alfred;

use CharlesRothDotNet\Alfred\Str;

class GooglAuthHelper {
    public static function makeGoogleUserProfile($clientId, $clientRedirectUrl, $clientSecret, $code): GoogleUserProfile {
        $data = getAccessToken($clientId, $clientRedirectUrl, $clientSecret, $code);
        $accessToken = $data['access_token'];
        if (Str::startsWith($accessToken, 'Error:'))  return new GoogleUserProfile("", $accessToken);

        $user_info = getUserProfileInfo($accessToken);
        $email = $user_info['email'];
        return new GoogleUserProfile($email, "");
    }

    private static function getAccessToken(string $client_id, string $redirect_uri, string $client_secret, string $code): string {
        $url = 'https://www.googleapis.com/oauth2/v4/token';

        $curlPost = 'client_id=' . $client_id . '&redirect_uri=' . $redirect_uri . '&client_secret=' . $client_secret . '&code='. $code . '&grant_type=authorization_code';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
        $data = json_decode(curl_exec($ch), true);
        $http_code = curl_getinfo($ch,CURLINFO_HTTP_CODE);
        return ($http_code == 200 ? $data : ['access_token' => 'Error : Failed to receive access token']);
    }

    private static function getUserProfileInfo($access_token) {
        $url = 'https://www.googleapis.com/oauth2/v2/userinfo?fields=name,email,gender,id,picture,verified_email';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '. $access_token));
        $data = json_decode(curl_exec($ch), true);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if($http_code != 200)
            throw new Exception('Error : Failed to get user information');

        return $data;
    }

}