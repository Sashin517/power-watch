<?php
    
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