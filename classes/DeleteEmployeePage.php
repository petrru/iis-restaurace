<?php

class DeleteEmployeePage extends DeletePage {
    public function check_privileges($position_id) {
        return $position_id == Utils::$PRIV_OWNER;
    }

    protected function init_vars() {
        $this->model = Employee::get_by_id($this->get_id());
    }
}