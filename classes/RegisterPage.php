<?php
class LoginPage extends Page
{

    public function get_title()
    {
        return 'Prihlásiť sa';
    }

    public function print_content()
    {
        include "inc/content/login.php";
    }

    public function get_menu()
    {
        return new Menu('login');
    }
}