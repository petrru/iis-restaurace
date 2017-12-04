<?php

/**
 * Class DeleteIngredientPage
 * Smaže ingredienci
 */
class DeleteIngredientPage extends DeletePage {
    public function check_privileges($position_id)
    {
        return $position_id >= Utils::$PRIV_BOSS;
    }

    /**
     * @throws NoEntryException
     */
    protected function init_vars() {
        $this->model = Ingredience::get_by_id($this->get_id());
    }

    /**
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
            $msg = 'Tuto ingredienci nelze odstranit, jelikož je je součástí'
                 . ' některých jídel.';
            Utils::set_error_message($msg);
        }
        Utils::redirect($url);
    }
}