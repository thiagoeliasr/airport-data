<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
//phpinfo(); die();
//autoloading classes

require dirname(__FILE__) . "/Thiagoelias/Utils/autoload.php";

use Thiagoelias\Noaa\Classes\NoaaRequest;
use Thiagoelias\Noaa\Classes\Noaa;
use Thiagoelias\Noaa\Classes\Exceptions\NoaaRequestException;
use Thiagoelias\Airports\Classes\AirportDAO;

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if (!isset($_GET['icao']) || !isset($_GET['type'])) {
    $json = array('error' => 'Params missing');
    echo json_encode($json);
    exit;
}

if (empty($_GET['icao']) || empty($_GET['type'])) {
    $json = array('error' => 'ICAO and/or type are empty');
    echo json_encode($json);
    exit;
}

$icao = strtoupper($_GET['icao']);
$type = $_GET['type'];

//creating a new NoaaRequest object
$noaaRequest = new NoaaRequest('get_contents');

try {
    //making a request with a new Noaa Object (I'm already passing the icao in constructor)
    $noaa = $noaaRequest->request(new Noaa($icao));
    $json = null;

    switch ($type) {
        case 'metar':
            $metar = $noaa->getMetar();

            if (strpos($metar, '404 Not Found')) {
                $json = array('error' => 'Airport not found');
            } else {

                $airportDao = new AirportDAO();
                $airport = $airportDao->getAirportByICAO($icao);

                $json = array('metar' => array(
                    'information' => $metar,
                    'airportData' => array(
                        'name' => $airport->getName(),
                        'icao' => $airport->getIcao(),
                        'iata' => $airport->getIata(),
                        'lat' => $airport->getX(),
                        'lng' => $airport->getY(),
                        'city' => $airport->getCity(),
                        'country' => $airport->getCountry(),
                        'elevation' => $airport->getElevation(),
                        'timezone' => $airport->getTimezone(),
                        'timezone_id' => $airport->getTz_id()
                    )
                ));
            }

            break;
        case 'taf':
            $taf = $noaa->getTaf();

            if (strpos($taf, '404 Not Found')) {
                $json = array('error' => 'Airport not found');
            } else {
                $json = array('taf' => array(
                    'information' => $taf
                ));
            }

            break;
        case 'decoded':
            $decoded = $noaa->getDecoded();

            if (strpos($decoded, '404 Not Found')) {
                $json = array('error' => 'Airport not found');
            } else {
                $json = array('decoded' => array(
                    'information' => $decoded
                ));
            }

            break;
        default:
            $json = array('error' => 'Incorrect type');
            break;
    }

    echo json_encode($json);

} catch (NoaaRequestException $e) {
    $json = array('error' => $e->getMessage());
    echo json_encode($json);
}
