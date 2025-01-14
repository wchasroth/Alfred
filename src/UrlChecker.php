<?php

namespace CharlesRothDotNet\Alfred;

use CharlesRothDotNet\Alfred\Str;

/**
 * Generalized URL Checker -- does this URL exist?
 *
 * Contains various heuristics, depending on the context.  The key method, check(),
 * returns NNN:text to indicate the results.  The NNN values are mostly HTTP codes,
 * but additional values have been added for specific cases, e.g. Facebook pages.
 *
 * Handles
 *    Generic websites
 *    Facebook pages
 */
class UrlChecker {

    // Return values:
    //  200       Good
    //  201       Facebook page, not public (could exist, can't tell for sure)
    //  000       not checked
    //  001       does not exist
    //  002       no info available
    //  003       parked      (domain parked, but no content)
    //  403       http forbidden
    //  436       http bad identity
    //  NNN       other http error
    //  900       Facebook page doesn't seem to exist
    //  999       url and protocol incompatible (e.g. https://someone@gmail.com)

    public static function check(string $url):  string {
        $urlLower = strtolower($url);
        if (Str::contains($urlLower, "facebook.com/"))   return UrlChecker::checkFacebookPage($url);

//        // Generic website checking here, uses headers to determine existence.
//        stream_context_set_default( [
//            'ssl' => [
//                'verify_peer' => false,
//                'verify_peer_name' => false,
//            ],
//            'http' => ['Accept' => '', 'Accept-Encoding' => '']
//        ]);
//        $headers = @get_headers($url);
//        if ($headers === false)  return "001";
//
//        echo "\n";
//        echo "$url\n";
//        foreach ($headers as $header) {
//            echo "   $header\n";
//        }
        $headers = UrlChecker::getHeadersOnly($url);
        echo "Has 200? " . UrlChecker::headersHas200($headers) . "\n";

        if (UrlChecker::headersHas200($headers)) {
            return ! UrlChecker::isParkedDomain($url, $headers) ? "200" : "003";
        }

        $code = "002";
        foreach ($headers as $header) {
            if (Str::contains($header, 'HTTP/1.1 ', 'HTTP/2 '))     $code = Str::substringBetween($header, " ", " ");
        }

        return $code;
    }

    private static function getHeadersOnly(string $url): array {
        $header = [
            'User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.2.12) Gecko/20101026 Firefox/3.6.12',
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'Accept-Language: en-us,en;q=0.5',
            'Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7',
            'Keep-Alive: 115',
            'Connection: keep-alive',
        ];
        $handle = curl_init($url);
        curl_setopt($handle, CURLOPT_HTTPHEADER, $header);
        curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($handle, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($handle, CURLOPT_COOKIESESSION, false);
        curl_setopt($handle, CURLOPT_HEADER, true);
        curl_setopt($handle, CURLOPT_NOBODY, true);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($handle);
        curl_close($handle);
        $result = Str::split($response, "\n");
        print_r($result);
        return $result;
    }

    private static function checkFacebookPage(string $url): string {
        $text = UrlChecker::getTextFromUrl($url);
        if (strlen($text) == 0)  return "900:noFbPage";
        return (Str::contains($text, "This content isn't available right now") ? "201" : "200");
    }

    private static function isParkedDomain(string $url): bool {
//        $random = @get_headers($url . "/someRandomImpossiblePageUri123789");
        $random = UrlChecker::getHeadersOnly($url . "/someRandomImpossiblePageUri123789");
        return UrlChecker::headersHas200($random);   // Almost always means entire domain is parked!
    }

    private static function headersHas200(array $headers): bool {
        foreach ($headers as $header) {
            if (Str::contains($header, 'HTTP/1.1 200', 'HTTP/2 200'))  return true;
        }
        return false;
    }

    private static function getTextFromUrl(string $url): string {
        $header = [
            'User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.2.12) Gecko/20101026 Firefox/3.6.12',
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'Accept-Language: en-us,en;q=0.5',
            'Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7',
            'Keep-Alive: 115',
            'Connection: keep-alive',
        ];
        $handle = curl_init($url);
        curl_setopt($handle, CURLOPT_HTTPHEADER, $header);
        curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($handle, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($handle, CURLOPT_COOKIESESSION, false);

        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($handle);

        curl_close($handle);
        return $response;
    }

}
