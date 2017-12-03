<?php

class AdminMenu extends Menu
{
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

    public function get_homepage_url()
    {
        return './manage';
    }
}