<?php

/**
 * Class Page
 * Vykreslení stránky
 */
abstract class Page
{
    protected $url;

    /// Pokud se má připojit .js soubor, zde se napíše jeho jméno
    protected $extra_script = '';

    /**
     * Vrátí titulek stránky (přijde do <title>...</title>)
     * @return string
     */
    public abstract function get_title();

    /**
     * Vypíše obsah stránky (v <body>...</body>, mezi úvodní lištou a patičkou)
     */
    public abstract function print_content();

    /**
     * Vrátí instanci třídy tvořící hlavní lištu
     * @return Menu
     */
    public abstract function get_menu();

    /**
     * Nastaví URL načítané stránky
     * @param $url URL stránky
     */
    public function set_url($url) {
        $this->url = $url;
    }

    /**
     * True, pokud se má vypsat tělo HTML stránky
     * False, pokud se nemá vypisovat (jde o přesměrování, zpracování AJAXU
     *        apod.)
     * @return bool
     */
    public function should_print_html() {
        return true;
    }

    /**
     * Inicializuje stránku. Může vyvolat NoEntryException, pokud se uživatel
     * snaží načíst objekt, který neexistuje (bude vypsána chyba 404).
     */
    public function init() {

    }

    /**
     * Zkontroluje, zda má uživatel právo přistupovat k této stránce
     * @param $position_id int Pozice přihlášeného uživatele
     * @return bool
     */
    public abstract function check_privileges($position_id);

    /**
     * Vypíše extra hlavičky
     */
    public function print_extra_assets() {
        if ($this->extra_script)
            echo "<script src='assets/{$this->extra_script}'></script>";
    }
}