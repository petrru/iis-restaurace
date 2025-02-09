<?php

/**
 * Class DeletePage
 * Odstraní řádek z databáze
 */
abstract class DeletePage extends Page
{
    /** @var Model */
    protected $model;

    public function get_title()
    {
        return '';
    }

    public function print_content()
    {

    }

    public function get_menu()
    {
        return new PublicMenu('');
    }

    /**
     * Inicializuje obsah proměnné $model
     * @throws NoEntryException
     */
    protected abstract function init_vars();

    /**
     * Vrátí ID mazaného řádku
     * @return int|array
     */
    protected function get_id() {
        return explode('/', $this->url)[2];
    }

    /**
     * Provede smazání
     * @throws NoEntryException
     */
    public function init() {
        $this->init_vars();
        if ($this->model->delete()) {
            $url = str_replace("/{$this->get_id()}/delete", '', $this->url);
            Utils::set_success_message('Záznam byl úspěšně odstraněn');
        }
        else {
            $url = str_replace('/delete', '', $this->url);
            Utils::set_error_message('Záznam se nepodařilo odstranit');
        }
        Utils::redirect($url);
    }

    public function should_print_html()
    {
        return false;
    }
}