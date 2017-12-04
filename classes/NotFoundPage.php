<?php

/**
 * Class NotFoundPage
 * Chyba 404
 */
class NotFoundPage extends Page
{
    public function __construct()
    {
        header("HTTP/1.0 404 Not Found");
    }

    public function get_title()
    {
        return "Stránka nenalezena";
    }

    public function print_content()
    {
        include_once "inc/content/not-found.php";
    }

    public function get_menu()
    {
        return new PublicMenu(null);
    }

    public function check_privileges($position_id)
    {
        return true;
    }
}