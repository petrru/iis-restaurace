<?php

/**
 * Class DB
 * Udržuje spojení s databází
 */
class DB
{
    /** @var PDO */
    private static $conn;

    /**
     * Navázání spojení
     */
    public static function connect() {
        $db_conn_str = null;
        $db_username = null;
        $db_password = null;
        include "inc/db.php";
        self::$conn = new PDO($db_conn_str, $db_username, $db_password);
    }

    /**
     * Vytvoření SQL dotazu
     * @param $sql string SQL dotaz
     * @return PDOStatement
     */
    public static function prepare($sql) {
        return self::$conn->prepare($sql);
    }

    /**
     * Vrátí ID posledního vloženého záznamu pomocí INSERT INTO
     * @return string
     */
    public static function lastInsertId() {
        return self::$conn->lastInsertId();
    }
}