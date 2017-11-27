<?php

abstract class Page
{
    protected $url;

    public abstract function get_title();

    public abstract function print_content();

    /**
     * @return Menu
     */
    public abstract function get_menu();

    public function set_url($url) {
        $this->url = $url;
    }

    public function should_print_html() {
        return true;
    }

    public function init() {

    }

    public abstract function check_privileges($position_id);
}