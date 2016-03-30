<?php

namespace Thiagoelias\Noaa\Classes;

use Thiagoelias\Noaa\Classes\Noaa;
use Thiagoelias\Noaa\Classes\Exceptions\NoaaRequestException;
use Thiagoelias\Utils\Classes\Network;

class NoaaRequest
{
    const TYPE_METAR = 1;
    const TYPE_TAF = 2;
    const TYPE_DECODED = 3;
    const URL_METAR = "http://weather.noaa.gov/pub/data/observations/metar/stations/";
    const URL_TAF = "http://weather.noaa.gov/pub/data/forecasts/taf/stations/";
    const URL_DECODED = "http://weather.noaa.gov/pub/data/observations/metar/decoded/";

    private $methodCurl = true;

    public function __construct($method = 'curl')
    {
        if ($method != 'curl') {
            $this->methodCurl = false;
        }
    }

    /**
     * This method will perform a request to Noaa,
     * saving the result into a Noaa Object.
     * @param \Thiagoelias\Noaa\Classes\Noaa $noaa
     * @param int $type type of request (metar, taf, decoded)
     * @return \Thiagoelias\Noaa\Classes\Noaa
     * @throws NoaaRequestException
     */
    public function request(Noaa $noaa, $type = null)
    {
        try {
            switch ($type) {
                case self::TYPE_METAR:
                    $requestResult = $this->getData($noaa->getIcao(), $type);
                    $noaa->setMetar($requestResult);
                    break;
                case self::TYPE_TAF:
                    $requestResult = $this->getData($noaa->getIcao(), $type);
                    $noaa->setTaf($requestResult);
                    break;
                case self::TYPE_DECODED:
                    $requestResult = $this->getData($noaa->getIcao(), $type);
                    $noaa->setDecoded($requestResult);
                    break;
                default:
                    /* if $type is null, I'll fetch everything (please be warned
                     * that this may result in performance loss, due to
                     * the 3 requests that will be made.*/
                    $requestResultMetar = $this
                        ->getData($noaa->getIcao(), self::TYPE_METAR);
                    $noaa->setMetar($requestResultMetar);

                    $requestResultTaf = $this
                            ->getData($noaa->getIcao(), self::TYPE_TAF);
                    $noaa->setTaf($requestResultTaf);

                    $requestResultDecoded = $this
                            ->getData($noaa->getIcao(), self::TYPE_DECODED);
                    $noaa->setDecoded($requestResultDecoded);
                    break;
            }
        } catch (NoaaRequestException $exception) {
            throw new NoaaRequestException($exception->getMessage());
        }

        return $noaa;
    }

    /**
     * Will get NOAA text data for the desired Airport ICAO code
     * @param String $icao
     * @param int $type
     * @return String
     * @throws exceptions\NoaaRequestException
     */
    private function getData($icao, $type)
    {
        $result = null;

        if (empty($icao)) {
            throw new NoaaRequestException("Invalid ICAO code");
        }

        try {
            switch ($type) {
                case self::TYPE_METAR:
                    $result = self::get(self::URL_METAR . strtoupper($icao) . ".TXT", $this->methodCurl);
                    break;
                case self::TYPE_TAF:
                    $result = self::get(self::URL_TAF . strtoupper($icao) . ".TXT", $this->methodCurl);
                    break;
                case self::TYPE_DECODED:
                    $result = self::get(self::URL_DECODED . strtoupper($icao) . ".TXT", $this->methodCurl);
                    break;
                default:
                    throw new NoaaRequestException("Invalid Request Type");
            }
        } catch (NoaaRequestException $e) {
            throw new NoaaRequestException($e->getMessage());
        }

        return $result;
    }

    /**
     * Perform a cURL request
     * @param String $url String containing the URL that will be fetched
     * @return String
     */
    private static function curl($url)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);
        curl_close($ch);

        if (!empty($result)) {
            return $result;
        } else {
            return null;
        }
    }

    /**
    * Perform a request using file_get_contents.
    * @param String $url String containing the URL that will be fetched
    * @return String
    */
    private static function getContents($url)
    {
        $opts = array('http' =>
            array(
                'method'  => 'GET',
                //'user_agent'  => "Mozilla/5.0 (X11; U; Linux x86_64; en-US; rv:1.9.2) Gecko/20100301 Ubuntu/9.10 (karmic) Firefox/3.6",
                'header' => array(
                    'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*\/*;q=0.8'
                ),
            )
        );

        $context  = stream_context_create($opts);
        $content = file_get_contents($url, false, $context);
        return $content;
    }

    private static function get($url, $curl = true)
    {
        if (Network::getHttpStatus($url) == "200") {
            if ($curl) {
                return self::curl($url);
            } else {
                return self::getContents($url);
            }
        } else {
            throw new NoaaRequestException("Airport not found");
        }
    }

}
