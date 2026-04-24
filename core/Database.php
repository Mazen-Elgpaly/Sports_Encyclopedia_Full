<?php
class Database
{
    private static ?mysqli $instance = null;

    private function __construct() {}

    public static function getInstance(): mysqli
    {
        if (self::$instance === null) {
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
            self::$instance = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            self::$instance->set_charset(DB_CHARSET);
        }
        return self::$instance;
    }
}
