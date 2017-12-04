<?php

/**
 * Class DeleteOrderedItemPage
 * Odebere jídlo z objednávky
 */

class DeleteOrderedItemPage extends DeletePage {
    public function check_privileges($position_id) {
        return $position_id >= Utils::$PRIV_WAITER;
    }

    protected function get_id() {
        $arr = explode('/', $this->url);
        return [$arr[2], $arr[4]];
    }

    /**
     * @throws NoEntryException
     */
    protected function init_vars() {
        $this->model = OrderedItem::get_by_id($this->get_id());
    }

    /**
     * @throws NoEntryException
     */
    public function init() {
        $this->init_vars();
        if ($this->model->delete()) {
            $arr = $this->get_id();
            $url = str_replace("/del-item/{$arr[1]}", '', $this->url);
            Utils::set_success_message('Záznam byl úspěšně odstraněn');
        }
        else {
            $url = str_replace('/delete', '', $this->url);
            Utils::set_error_message('Záznam se nepodařilo odstranit');
        }
        Utils::redirect($url);
    }
}