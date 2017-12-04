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
        if ($_SESSION['last_active'] < time() - 15 * 60) {
            self::set_error_message("Platnost vašeho přihlášení vypršela");
            self::$logged_user = new Employee();
            unset($_SESSION['employee_id']);
            unset($_SESSION['last_active']);
            return;
        }
        try {
            self::$logged_user = Employee::get_by_id($_SESSION['employee_id']);
        } catch (NoEntryException $e) {
            self::$logged_user = new Employee();
            unset($_SESSION['employee_id']);
            unset($_SESSION['last_active']);
        }
        if (self::$logged_user->position_id == 4) {
            self::$logged_user = new Employee();
            unset($_SESSION['employee_id']);
            unset($_SESSION['last_active']);
            return;
        }
    }

    public static function get_logged_user() {
        return self::$logged_user;
    }

    public static function redirect($url) {
        header('Location: ' . Mapper::url($url));
    }

    public static function print_modal($id, $header, $body) {
        echo "<div class='modal' id='$id'><div>"
            ."<i class='material-icons close' title='Zavřít'>close</i>"
            ."<h3>$header</h3>$body</div></div>";
    }

    public static function convert_weekday($str) {
        return preg_replace_callback("/%([0-6])%/", function ($match) {
            $days = ['Ne', 'Po', 'Út', 'St', 'Čt', 'Pá', 'So'];
            return $days[$match[1]];
        }, $str);
    }
}