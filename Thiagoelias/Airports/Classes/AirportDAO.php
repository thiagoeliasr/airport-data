<?php

namespace Thiagoelias\Airports\Classes;

require dirname(__FILE__) . "/../../Utils/autoload.php";

use Thiagoelias\TDbHelper\Classes\TDbHelper;
use Thiagoelias\Airports\Classes\Airports;
use \PDO;

class AirportDAO
{

    private $pdo;
    private static $allFields = 'name, city, country, iata, icao, x, y,
        elevation, apid, uid, timezone, dst, tz_id';

    public function __construct()
    {
        //Getting the PDO Database Instance from my singleton.
        $this->pdo = TDbHelper::getInstance();
    }

    /**
     * Get all data from an airport using icao code.
     * @param String $_icao ICAO code for the desired airport.
     * @return Thiagoelias\Airports\Classes\AirportDAO
     */
    public function getAirportByICAO($_icao)
    {
        return $this->getAirport(
            "SELECT ". self::$allFields .
            " FROM airports WHERE icao = '{$_icao}'"
        );
    }

    public function getAirportByIATA($_iata)
    {
        return $this->getAirport(
            "SELECT ". self::$allFields .
            " FROM airports WHERE iata = '{$_iata}'"
        );
    }

    public function getAirport($_query)
    {
        $stmt = $this->pdo->query($_query);

        $airport = new Airport();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $airport->fillAirport($row);
        }

        return $airport;
    }

}
