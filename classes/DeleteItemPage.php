<?php

/**
 * Class DeleteItemPage
 * Smaže položku (jídlo)
 */
class DeleteItemPage extends DeletePage {
    public function check_privileges($position_id)
    {
        return in_array($position_id, [Utils::$PRIV_OWNER, Utils::$PRIV_BOSS]);
    }

    /**
     * @throws NoEntryException
     */
    protected function init_vars() {
        $this->model = Item::get_by_id($this->get_id());
    }
}