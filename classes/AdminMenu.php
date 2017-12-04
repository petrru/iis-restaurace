<?php

/**
 * Class AdminMenu
 * Menu pro přihlášené uživatele
 */
class AdminMenu extends Menu
{
    /**
     * Konstruktor Menu
     * @param $active_item
     */
    public function __construct($active_item)
    {
        parent::__construct($active_item);
        $this->items = [];
        $position = Utils::get_logged_user()->position_id;
        if ($position == Utils::$PRIV_OWNER) {
            $this->items[] = ['manage/employees', 'Zaměstnanci'];
        }
        if ($position >= Utils::$PRIV_BOSS) {
            $this->items[] = ['manage/menu', 'Menu'];
        }
        $this->items[] = ['manage/reservations/current', 'Rezervace'];
        $this->items[] = ['manage/orders', 'Objednávky'];
        $this->items[] = ['manage/other', 'Ostatní'];
        $this->items[] = ['logout', 'Odhlásit se'];

    }

    /**
     * URL, kam má vést hlavní odkaz "Dos compañeros"
     * @return string
     */
    public function get_homepage_url()
    {
        return './manage';
    }
}