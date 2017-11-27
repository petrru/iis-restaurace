<?php

class DB
{
    /** @var PDO */
    private static $conn;

    public static function connect() {
        $db_conn_str = null;
        $db_username = null;
        $db_password = null;
        include "inc/db.php";
        self::$conn = new PDO($db_conn_str, $db_username, $db_password);
    }

    public static function prepare($sql) {
        return self::$conn->prepare($sql);
    }

    public static function lastInsertId() {
        return self::$conn->lastInsertId();
    }
}