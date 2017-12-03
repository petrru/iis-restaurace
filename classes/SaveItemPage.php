<?php

class SaveItemPage extends JsonPage
{
    private $item_id;

    public function check_privileges($position_id)
    {
        return $position_id >= Utils::$PRIV_BOSS;
    }

    private function do_modify() {
        $iii = new IngredienceInItem();
        $iii->ingredience_id = $_POST['old_id'];
        $iii->item_id = $this->item_id;
        if (!$iii->has_id())
            return ["e" => "Záznam neexistuje"];
        $iii->begin_update();
        $iii->ingredience_id = $_POST['new_id'];
        $iii->amount = str_replace(',', '.', $_POST['amount']);
        $r = $iii->save();
        if (is_array($r))
            return ["e" => "Toto jídlo již obsahuje tuto ingredienci"];
        if (!$r)
            return ["e" => "Chyba při ukládání změn"];
        return ["e" => "ok"];
    }

    private function do_add()
    {
        if (!preg_match("/^[0-9]+[.,]?[0-9]*$/", $_POST['amount']))
            return ["e" => "Neplatné množství"];
        $iii = new IngredienceInItem();
        $iii->begin_update();
        $iii->ingredience_id = $_POST['new_id'];
        $iii->item_id = $this->item_id;
        $iii->amount = str_replace(',', '.', $_POST['amount']);
        $r = $iii->save();
        if (is_array($r))
            return ["e" => "Toto jídlo již obsahuje tuto ingredienci"];
        if (!$r)
            return ["e" => "Chyba při ukládání změn"];
        return ["e" => "ok"];
    }

    private function do_delete()
    {
        $id = [$_POST['id'], $this->item_id];
        try {
            $iii = IngredienceInItem::get_by_id($id);
        } catch (NoEntryException $e) {
            return ["e" => "Záznam neexistuje"];
        }
        if (!$iii->delete())
            return ["e" => "Chyba při ukládání změn"];
        return ["e" => "ok"];
    }

    protected function get_output()
    {
        $this->item_id = explode("/", $this->url)[2];
        if ($this->check_input('modify', ['old_id', 'new_id', 'amount']))
            return $this->do_modify();
        if ($this->check_input('add', ['new_id', 'amount']))
            return $this->do_add();
        if ($this->check_input('delete', ['id']))
            return $this->do_delete();
        return ['e' => 'invalid-request'];
    }
}