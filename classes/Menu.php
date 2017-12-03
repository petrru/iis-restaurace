<?php

abstract class Menu
{
    protected $items;
    private $active_item;

    public function __construct($active_item)
    {
        $this->active_item = $active_item;
    }

    public function print_menu() {
        echo "<ul>";
        foreach ($this->items as $item) {
            $url = $item[0];
            $name = $item[1];
            $active = $this->active_item == $item[0] ? " class='active'" : "";
            echo "<li$active><a href='$url'>$name</a></li>";
        }
        echo "</ul>";
    }

    public abstract function get_homepage_url();
}