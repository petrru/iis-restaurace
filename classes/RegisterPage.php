<?php
class RegisterPage extends Page
{

    public function get_title()
    {
        return 'Registrace';
    }

    public function print_content()
    {
        include "inc/content/register.php";
    }

    public function get_menu()
    {
        return new PublicMenu('login');
    }

    public function check_privileges($position_id)
    {
        return false;
    }
}