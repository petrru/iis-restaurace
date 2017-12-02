<?php

class EditItemPage extends JsonPage
{
    public function check_privileges($position_id)
    {
        return $position_id >= Utils::$PRIV_BOSS;
    }

    private function do_modify() {
        return "xx";
    }

    protected function get_output()
    {
        if ($this->check_input('modify', ['old_id', 'new_id', 'amount']))
            return $this->do_modify();
        return ['e' => 'invalid-request'];
    }
}