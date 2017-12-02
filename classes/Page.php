<?php

abstract class Page
{
    protected $url;
    protected $extra_script = '';

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

    public function print_extra_assets() {
        if ($this->extra_script)
            echo "<script src='assets/{$this->extra_script}'></script>";
    }
}