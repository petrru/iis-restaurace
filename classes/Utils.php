<?php

class Utils
{
    public static $PRIV_OWNER = 3;
    public static $PRIV_BOSS = 2;
    public static $PRIV_WAITER = 1;

    /**
     * @var Employee
     */
    private static $logged_user = null;

    public static function set_success_message($msg) {
        $_SESSION['success_message'] = $msg;
    }

    public static function set_error_message($msg) {
        $_SESSION['error_message'] = $msg;
    }

    public static function init_user() {
        if (empty($_SESSION['employee_id'])) {
            self::$logged_user = new Employee();
            return;
        }
        try {
            self::$logged_user = Employee::get_by_id($_SESSION['employee_id']);
        } catch (NoEntryException $e) {
            self::$logged_user = new Employee();
            unset($_SESSION['employee_id']);
        }
    }

    public static function get_logged_user() {
        return self::$logged_user;
    }

    public static function redirect($url) {
        header('Location: ' . Mapper::url($url));
    }
}