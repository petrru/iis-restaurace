<?php
class LoginPage extends Page
{
    public function get_title()
    {
        //$_SESSION['employee_id'] = 1;
        return 'Prihlásiť sa';
    }

    public function print_content()
    {
        include "inc/content/login.php";
    }

    public function get_menu()
    {
        return new PublicMenu('login');
    }

    public function should_print_html()
    {
        if (!empty($_SESSION['employee_id'])) {
            header("Location:manage/");
            return false;
        }
        if ($_POST) {
            // TODO
        }
        return true;
    }


}