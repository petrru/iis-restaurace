<?php

class DeleteRoomPage extends DeletePage {
    public function check_privileges($position_id)
    {
        return $position_id == Utils::$PRIV_OWNER;
    }

    /**
     * @throws NoEntryException
     */
    protected function init_vars() {
        $this->model = Room::get_by_id($this->get_id());
    }
}