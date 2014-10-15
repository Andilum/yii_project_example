<?php

abstract class UserHelper {

    public static function makeCurlProccess($url, array $opts = array()) {
        $ch = curl_init($url);
        curl_setopt_array($ch, $opts);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    public static function getCurlHeaders($response) {
        $headers = array();

        $header_text = substr($response, 0, strripos($response, "\r\n\r\n"));
        if (strripos($header_text, "\r\n\r\n")) {
            $header_text = substr($header_text, strripos($header_text, "\r\n\r\n") + 4);
        }

        foreach (explode("\r\n", $header_text) as $i => $line) {
            if ($i === 0)
                $headers['http_code'] = $line;
            else {
                list ($key, $value) = explode(': ', $line);

                $headers[$key] = $value;
            }
        }
        return $headers;
    }
    
    public static function getCurlBody($response) {
        $body = substr($response, strripos($response, "\r\n\r\n") + 4);
        return $body;
    }

}
