<?php

/**
 * Class Menu
 * Úvodní lišta s odkazy
 */
abstract class Menu
{
    /** @var array Položky jmenu (pole polí [url, label]) */
    protected $items;

    /** @var string URL aktivní položky */
    private $active_item;

    /**
     * Konstruktor Menu
     * @param $active_item
     */
    public function __construct($active_item)
    {
        $this->active_item = $active_item;
    }

    /**
     * Vypíše menu
     */
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

    /**
     * URL, kam má vést hlavní odkaz "Dos compañeros"
     * @return string
     */
    public abstract function get_homepage_url();
}