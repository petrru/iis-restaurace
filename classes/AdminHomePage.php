<?php

class AdminHomePage extends Page
{
    /**
     * Vrátí titulek stránky (přijde do <title>...</title>)
     * @return string
     */
    public function get_title()
    {
        return 'Úvodní stránka - Administrace';
    }

    /**
     * Vypíše obsah stránky
     */
    public function print_content()
    {
        include "inc/content/admin.php";
    }

    /**
     * Vrátí instanci třídy tvořící hlavní lištu
     * @return Menu
     */
    public function get_menu()
    {
        return new AdminMenu('');
    }

    /**
     * Zkontroluje, zda má uživatel právo přistupovat k této stránce
     * @param $position_id int Pozice přihlášeného uživatele
     * @return bool
     */
    public function check_privileges($position_id)
    {
        return $position_id >= 1;
    }
}
