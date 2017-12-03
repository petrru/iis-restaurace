<?php

class RoomPage extends FormPage
{
    /** @var Room */
    protected $item;

    protected $entity_name = 'místnost';
    protected $entity_name_plural = 'místností';


    protected function get_data()
    {
        return [
            ['description', 'Název místnosti', 'text', ['required' => true]],
            ['tables_from', 'Čísla stolů od', 'number', ['required' => true]],
            ['tables_to', 'Čísla stolů do', 'number', ['required' => true]],
        ];
    }

    protected function get_links()
    {
        return ["<a href='manage/rooms'>Zpět na seznam místností</a>"];
    }

    protected function get_item($id)
    {
        return Room::get_by_id($id);
    }

    protected function to_string()
    {
        if ($this->is_new)
            return "Nová položka";
        return $this->item->description;
    }

    public function validate()
    {
        $out = [];
        if ($this->item->tables_to < $this->item->tables_from)
            $out[] = 'Počáteční číslo stolu musí být nižší nebo rovno koncovému'
                   . ' číslu stolu';
        $room = new Room();
        $id = $this->item->room_id ?? 0;
        $from = $this->item->tables_from;
        $to = $this->item->tables_to;

        $q = $room->select("SELECT description FROM rooms
                            WHERE tables_from <= :i AND tables_to >= :i
                            AND room_id != :cur");
        $q->execute(['i' => $from, 'cur' => $id]);
        if ($q->rowCount()) {
            $q->fetch();
            $out[] = "Zadaný počáteční rozsah stolů koliduje s místností "
                   . $room->description;
        }
        $q->execute(['i' => $to, 'cur' => $id]);
        if ($q->rowCount()) {
            $q->fetch();
            $out[] = "Zadaný koncový rozsah stolů koliduje s místností "
                   . $room->description;
        }
        $q = $room->select("SELECT description FROM rooms
                            WHERE tables_from > ? AND tables_to < ?
                            AND room_id != ?");
        $q->execute([$from, $to, $id]);
        if ($q->rowCount()) {
            $q->fetch();
            $out[] = "Zadaný rozsah stolů koliduje s místností "
                . $room->description;
        }

        $this->item->capacity = $to - $from + 1;
        return $out;
    }

    /**
     * @return Menu
     */
    public function get_menu()
    {
        return new AdminMenu('manage/other');
    }

    public function check_privileges($position_id)
    {
        return $position_id == Utils::$PRIV_OWNER;
    }

    public function should_print_html() {
        if (!$this->form->is_save_successful()) {
            return true;
        }
        if ($this->is_new) {
            Utils::set_success_message('Záznam byl úspěšně uložen.');
        }
        else {
            Utils::set_success_message('Záznam byl úspěšně upraven.');
        }
        Utils::redirect('manage/rooms');
        return false;
    }
}