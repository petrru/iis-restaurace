<?php

class PublicMenu extends Menu
{
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
}