<?php

/**
 * Class PublicMenu
 * Menu pro nepřihlášené uživatele
 */
class PublicMenu extends Menu
{
    /**
     * Konstruktor Menu
     * @param $active_item
     */
    public function __construct($active_item)
    {
        parent::__construct($active_item);
        $this->items = [
            ['about', 'O nás'],
            ['menu', 'Menu'],
            ['reservation', 'Rezervácia'],
            ['login', 'Prihlásiť sa'],
            ['contact', 'Kontakt'],
        ];

    }

    /**
     * URL, kam má vést hlavní odkaz "Dos compañeros"
     * @return string
     */
    public function get_homepage_url()
    {
        return './';
    }
}