<?php

/**
 * Class DeleteReservationPage
 * Odstraní rezervaci
 */
class DeleteReservationPage extends DeletePage {
    /** @var Reservation */
    protected $model;

    public function check_privileges($position_id) {
        return $position_id >= Utils::$PRIV_WAITER;
    }

    /**
     * @throws NoEntryException
     */
    protected function init_vars() {
        $this->model = Reservation::get_by_id($this->get_id());
    }

    /**
     * @throws NoEntryException
     */
    public function init() {
        $this->init_vars();
        if ($this->model->delete()) {
            $url = str_replace("/{$this->get_id()}/delete", '', $this->url)
                 . '/' . substr($this->model->date_reserved, 0, 7);
            Utils::set_success_message('Záznam byl úspěšně odstraněn');
        }
        else {
            $url = str_replace('/delete', '', $this->url);
            Utils::set_error_message('Záznam se nepodařilo odstranit');
        }
        Utils::redirect($url);
    }
}