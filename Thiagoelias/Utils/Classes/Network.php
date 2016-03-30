<?php

namespace Thiagoelias\Utils\Classes;

class Network
{
    /**
     * Verify and return the http status for a desired url.
     * @param String $url
     * @return String Status Code
     */
    public static function getHttpStatus($url)
    {
        $headers = get_headers($url);
        return substr($headers[0], 9, 3);
    }
}
