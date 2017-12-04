<?php

/**
 * Class LogoutPage
 * Odhlásí uživatele
 */
class LogoutPage extends Page
{
    public function get_title()
    {
        return '';
    }

    public function get_menu()
    {
        return new PublicMenu('');
    }

    public function should_print_html()
    {
        unset($_SESSION['employee_id']);
        unset($_SESSION['last_active']);
        Utils::redirect('login');
        Utils::set_success_message('Byl jste úspěšně odhlášen');
        return false;
    }


    public function print_content()
    {

    }

    public function check_privileges($position_id)
    {
        return true;
    }
}