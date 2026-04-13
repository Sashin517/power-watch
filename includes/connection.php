<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 0);

    // Also force MySQL to throw secure Exceptions instead of leaky Warnings
    mysqli_report(MYSQLI_REPORT_STRICT | MYSQLI_REPORT_ERROR);
    
class Database {

    public static $connection;

    public static function setUpConnection() {

        if(!isset(Database::$connection)) {


            Database::$connection = new mysqli("vortex.nodebuckethost.com", "sldevswe_powerwatch", '.L{$fxm3z60mj@_I', "sldevswe_powerwatch", 3306);

            if (Database::$connection->connect_error) {
                error_log("DB CONNECTION FAILED: " . Database::$connection->connect_error);
                die("Database connection error");
            }

        }
    }

    public static function search($query) {

        Database::setUpConnection();
        return Database::$connection->query($query);

    }

    public static function iud($query) {

        Database::setUpConnection();
        Database::$connection->query($query);

    }
}