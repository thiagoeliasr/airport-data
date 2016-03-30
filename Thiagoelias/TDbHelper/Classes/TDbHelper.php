<?php

namespace Thiagoelias\TDbHelper\Classes;

use \PDO;
use \PDOException;

class TDbHelper
{
    const DB_HOSTNAME = 'localhost';
    const DB_DATABASE = 'flightdb2';
    const DB_USERNAME = 'root';
    const DB_PASSWORD = 'root';
    //const DB_PORT = '3306';
    const DB_PORT = '8889';
    const DB_SOCKET = null;

    public static $instance;

    private function __construct()
    {
        //
    }

    public static function getInstance()
    {
        if (!isset(self::$instance)) {

            try {
                $connString = "mysql:host=" . self::DB_HOSTNAME .
                ";port=" . self::DB_PORT . ";dbname=" . self::DB_DATABASE;

                if (self::DB_SOCKET !== null) {
                    $connString = "mysql:port=" . self::DB_PORT .
                    ";dbname=" . self::DB_DATABASE .
                    ";unix_socket=" . self::DB_SOCKET;
                }

                self::$instance = new PDO(
                    $connString,
                    self::DB_USERNAME,
                    self::DB_PASSWORD,
                    array(
                        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
                ));

                self::$instance->setAttribute(
                    PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                self::$instance->setAttribute(PDO::ATTR_ORACLE_NULLS,
                    PDO::NULL_EMPTY_STRING);

            } catch (PDOException $e) {
                die("An error ocurred: " . $e->getMessage());
            }
        }

        return self::$instance;
    }

}
