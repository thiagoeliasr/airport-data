<?php

namespace Thiagoelias\Airports\Classes;

class Airport
{
    private $name;
    private $city;
    private $country;
    private $iata;
    private $icao;
    private $x;
    private $y;
    private $elevation;
    private $apid;
    private $uid;
    private $timezone;
    private $dst;
    private $tz_id;


    //Getters & setters

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function setCity($city)
    {
        $this->city = $city;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function setCountry($country)
    {
        $this->country = $country;
    }

    public function getIata()
    {
        return $this->iata;
    }

    public function setIata($iata)
    {
        $this->iata = $iata;
    }

    public function getIcao()
    {
        return $this->icao;
    }

    public function setIcao($icao)
    {
        $this->icao = $icao;
    }

    public function getX()
    {
        return $this->x;
    }

    public function setX($x)
    {
        $this->x = $x;
    }

    public function getY()
    {
        return $this->y;
    }

    public function setY($y)
    {
        $this->y = $y;
    }

    public function getElevation()
    {
        return $this->elevation;
    }

    public function setElevation($elevation)
    {
        $this->elevation = $elevation;
    }

    public function getApid()
    {
        return $this->apid;
    }

    public function setApid($apid)
    {
        $this->apid = $apid;
    }

    public function getUid()
    {
        return $this->uid;
    }

    public function setUid($uid)
    {
        $this->uid = $uid;
    }

    public function getTimezone()
    {
        return $this->timezone;
    }

    public function setTimezone($timezone)
    {
        $this->timezone = $timezone;
    }

    public function getDst()
    {
        return $this->dst;
    }

    public function setDst($dst)
    {
        $this->dst = $dst;
    }

    public function getTz_id()
    {
        return $this->tz_id;
    }

    public function setTz_id($tz_id)
    {
        $this->tz_id = $tz_id;
    }

    /**
     * Fill all data using an associative array.
     * @param Mixed $arrAirports Associative array ([field] => [data])
     */
    public function fillAirport($arrAirports)
    {
        foreach ($arrAirports as $k => $airport) {
            $functionName = "set" . ucfirst($k);
            $this->$functionName($airport);
        }
    }

}
