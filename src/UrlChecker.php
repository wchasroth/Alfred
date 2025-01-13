<?php

namespace CharlesRothDotNet\Alfred;

use CharlesRothDotNet\Alfred\Str;
class UrlChecker {

    // Return values:
    //  200:GOOD
    //  000:nonExistent
    //  001:noInfo
    //  002:parked
    //  403:httpError   (forbidden)
    //  436:httpError   (bad identity)
    //  NNN:httpError   (other http errors)
    public static function check(string $url): string {
        stream_context_set_default( [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
        ]);
        $headers = @get_headers($url);
        if ($headers === false)  return "000:nonExistent";

        if (UrlChecker::headersHas200($headers)) {
            return ! UrlChecker::isParkedDomain($url, $headers) ? "200:GOOD" : "002:parked";
        }

        $code = "001:noInfo";
        foreach ($headers as $header) {
            if (Str::contains($header, 'HTTP/1.1 '))     $code = Str::substringBetween($header, "HTTP/1.1 ", " ");
        }

        return "$code:httpError";
    }

    private static function isParkedDomain(string $url): bool {
        $random = @get_headers($url . "/someRandomImpossiblePageUri123789");
        return UrlChecker::headersHas200($random);   // Almost always means entire domain is parked!
    }

    private static function headersHas200(array $headers): bool {
        foreach ($headers as $header) {
            if (Str::contains($header, 'HTTP/1.1 200'))  return true;
        }
        return false;
    }

    public static function getTextFromUrl(string $url): string {
        $header = [
            'User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.2.12) Gecko/20101026 Firefox/3.6.12',
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'Accept-Language: en-us,en;q=0.5',
            'Accept-Encoding: gzip,deflate',
            'Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7',
            'Keep-Alive: 115',
            'Connection: keep-alive'
        ];
        $handle = curl_init($url);
        curl_setopt($handle, CURLOPT_HTTPHEADER, $header);
        curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, 0);

        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($handle);

        curl_close($handle);
        return $response;
    }

}
