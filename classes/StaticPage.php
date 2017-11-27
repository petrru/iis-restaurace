<?php

class StaticPage extends Page
{
    private $titles = [
        "" => "Hlavná stránka",
        "about" => "O nás",
        "contact" => "Kontakt",
    ];

    public function get_title()
    {
        return $this->titles[$this->url];
    }

    public function print_content()
    {
        include_once "inc/content/" . ($this->url ?: "main") . ".php";
    }

    public function get_menu()
    {
        return new PublicMenu($this->url);
    }

    public function check_privileges($position_id)
    {
        return true;
    }
}