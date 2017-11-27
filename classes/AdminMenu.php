<?php

class AdminMenu extends Menu
{
    public function __construct($active_item)
    {
        parent::__construct($active_item);
        $this->items = [
            ['manage/employees', 'Správa zaměstnanců'],
            ['logout', 'Odhlásit se'],
        ];

    }
}