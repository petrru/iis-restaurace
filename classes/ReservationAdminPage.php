<?php

class ReservationAdminPage extends FormPage
{
    /** @var Reservation */
    protected $item;

    protected $entity_name = 'rezervaci';
    protected $entity_name_plural = 'rezervací';

    private $back_url;

    public $extra_script = 'reservation.js';

    /**
     * @throws NoEntryException
     */
    public function init()
    {
        parent::init();
        $this->form->print_form_element = false;
        $this->back_url = 'manage/reservations/';
        if ($this->item->date_reserved)
            $this->back_url .= substr($this->item->date_reserved, 0, 7);
        else
            $this->back_url .= "current";
        if (!$this->item->employee_id)
            $this->item->employee_id = Utils::get_logged_user()->employee_id;
    }

    private function print_seats() {
        $r = new Room();
        $sql = "SELECT rooms.room_id, capacity, description, seat_count
                FROM rooms
                LEFT JOIN reserved_rooms
                  ON rooms.room_id = reserved_rooms.room_id
                  AND (reservation_id = :id OR reservation_id IS NULL)";
        $q = $r->select($sql);
        $item = $this->item;
        $q->execute(['id' => $item->reservation_id]);
        while ($q->fetch()) {
            $key = 'seats_' . $r->room_id;
            $val = $_POST[$key] ?? $r->seat_count;
            echo "<input type='text' class='small-input' name='$key'"
                ." value='$val'> "
                ." / {$r->capacity} {$r->description}<br>";
        }
        echo "<hr><b>Celkem: </b><span id='total-seats'></span> míst";
    }

    public function print_content()
    {
        $this->form->print_form_open_element();
        echo '<div class="container">';
        echo '<div class="column_75 left">';
        $this->print_form();
        echo '</div>';
        echo '<div class="column_25 left">';
        echo "<h4>Počet míst</h4>";
        $this->print_seats();
        echo '</div>';
        echo '</div>';
        echo "<div class='clear'></div>";
        echo '</form>';
    }

    protected function get_data()
    {
        $out = [];
        $out[] = ['date_reserved', 'Rezervované datum', 'text',
                  ['required' => true]];
        if (!$this->is_new)
            $out[] = ['date_created', 'Datum vytvoření', 'readonly',
                      ['do-not-save' => true]];
        $out[] = ['customer_name', 'Jméno zákazníka', 'text',
                  ['required' => true]];
        $out[] = ['customer_phone', 'Telefon zákazníka', 'number', []];
        $out[] = ['customer_email', 'E-mail zákazníka', 'text', []];
        $out[] = ['employee_id', 'Vyřizuje', 'fk', ['table' => new Employee(),
            'sql' => 'SELECT employee_id, CONCAT(last_name, \' \', first_name)
                      AS first_name FROM employees
                      ORDER BY last_name, first_name',
            'display' => 'first_name', 'required' => true]];
        return $out;
    }

    protected function get_links()
    {
        return ["<a href='{$this->back_url}'>Zpět na seznam rezervací</a>"];
    }

    /**
     * @param int $id
     * @return Reservation
     * @throws NoEntryException
     */
    protected function get_item($id)
    {
        return Reservation::get_by_id($id);
    }

    protected function to_string()
    {
        if ($this->is_new)
            return "Nová rezervace";
        return "od " . $this->item->customer_name;
    }

    public function validate()
    {
        $out = [];
        $r = $this->item;
        if ($r->customer_phone == '' && $r->customer_email == '')
            $out[] = 'Je nutné zadat alespoň telefon nebo e-mail zákazníka.';
        if ($r->customer_phone && strlen($r->customer_phone) != 9)
            $out[] = 'Zadané telefonní číslo není správné';

        $r = new Room();
        $q = $r->select("SELECT room_id, capacity, description FROM rooms");
        $q->execute();
        $seat_entered = false;
        while ($q->fetch()) {
            $key = 'seats_' . $r->room_id;
            $des = $r->description;
            if (!isset($_POST[$key])) {
                $out[] = "Nebyl odeslán počet míst v místnosti $des";
            }
            elseif (!preg_match("/^[0-9]*$/", $_POST[$key])) {
                $out[] = "Zadaný počet míst v místnosti $des není číslo";
            }
            elseif ($_POST[$key] > $r->capacity) {
                $out[] = "Zadaný počet míst v místnosti $des je vyšší než je"
                       . " její kapacita";
            }
            elseif ($_POST[$key]) {
                $seat_entered = true;
            }
        }
        if (!$seat_entered) {
            $out[] = "Nebyl zadán počet rezervovaných míst";
        }
        return $out;
    }

    /**
     * @return Menu
     */
    public function get_menu()
    {
        return new AdminMenu('manage/reservations/current');
    }

    public function check_privileges($position_id)
    {
        return $position_id >= Utils::$PRIV_WAITER;
    }

    public function should_print_html() {
        if (!$this->form->is_save_successful()) {
            return true;
        }
        $room = new Room();
        $q = $room->select("SELECT room_id FROM rooms");
        $q->execute();
        $reservation_id = $this->item->reservation_id;
        while ($q->fetch()) {
            $room_id = $room->room_id;
            $rr = ReservedRoom::get_by_id_or_new([$room_id, $reservation_id]);
            $val = $_POST['seats_' . $room_id];
            if ($val) {
                $rr->begin_update();
                if ($rr->is_new()) {
                    $rr->room_id = $room_id;
                    $rr->reservation_id = $reservation_id;
                }
                $rr->seat_count = $val;
                $r = $rr->save();
            }
            else {
                $rr->delete();
            }
        }
        if ($this->is_new) {
            Utils::set_success_message('Záznam byl úspěšně uložen.');
        }
        else {
            Utils::set_success_message('Záznam byl úspěšně upraven.');
        }
        Utils::redirect($this->back_url);
        return false;
    }

    public function get_title()
    {
        $out = parent::get_title();
        if ($out[0] == 'o')
            $out = 'Rezervace ' . $out;
        return $out;
    }
}