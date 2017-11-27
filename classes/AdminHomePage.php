<?php

class AdminHome extends Page
{

    public function get_title()
    {
        return 'Úvodní stránka - Administrace';
    }

    public function print_content()
    {
        // TODO: Implement print_content() method.
    }

    public function get_menu()
    {
        return new AdminMenu('');
    }
}