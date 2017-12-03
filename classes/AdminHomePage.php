<?php

class AdminHomePage extends Page
{

    public function get_title()
    {
        return 'Úvodní stránka - Administrace';
    }

    public function print_content()
    {
        include "inc/content/admin.php";
    }

    public function get_menu()
    {
        return new AdminMenu('');
    }

    public function check_privileges($position_id)
    {
        return $position_id >= 1;
    }
}
