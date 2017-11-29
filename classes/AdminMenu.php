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
        if (in_array($position, [Utils::$PRIV_OWNER, Utils::$PRIV_BOSS])) {
            $this->items[] = ['manage/menu', 'Menu'];
        }
        $this->items[] = ['logout', 'Odhlásit se'];

    }
}