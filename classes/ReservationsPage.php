<?php

/**
 * Class ReservationsPage
 * Seznam rezervací
 */
class ReservationsPage extends Page
{
    private $month, $year;
    public $extra_script = "calendar.js";

    /**
     * Inicializuje stránku. Může vyvolat NoEntryException, pokud se uživatel
     * snaží načíst objekt, který neexistuje (bude vypsána chyba 404).
     */
    public function init()
    {
        $url_part = explode("/", $this->url)[2];
        if ($url_part == 'current') {
            $this->month = date("m");
            $this->year = date("Y");
        }
        else {
            list ($this->year, $this->month) = explode('-', $url_part);
        }
    }

    /**
     * Vytiskne pole pro změnu měsíce
     */
    private function print_change_month() {
        setlocale(LC_TIME, "czech");
        echo "<form id='change-month'>";
        echo "Zobrazit měsíc: ";
        $months = ["", "Leden", "Únor", "Březen", "Duben", "Květen", "Červen",
                   "Červenec", "Srpen", "Září", "Říjen", "Listopad",
                   "Prosinec"];
        echo "<select id='month'>";
        for ($i = 1; $i <= 12; $i++) {
            $val = str_pad($i, 2, '0', STR_PAD_LEFT);
            echo "<option value='$val'";
            if ($this->month == $i)
                echo ' selected';
            echo ">{$months[$i]}</option>";
        }
        echo "</select>";
        echo "<input type='text' id='year' value='{$this->year}'>";
        echo "<input type='submit' value='Změnit měsíc'>";
        echo "</form>";
    }

    /**
     * Vypíše obsah stránky (v <body>...</body>, mezi úvodní lištou a patičkou)
     */
    public function print_content()
    {
        echo '<div class="container">';
        $this->print_change_month();


        $r = new Reservation();
        $q = $r->select("SELECT reservation_id, date_reserved, customer_name,
                         customer_phone, customer_email, first_name, last_name,
                         SUM(seat_count) AS seats
                         FROM reservations
                         NATURAL LEFT JOIN reserved_rooms
                         NATURAL LEFT JOIN employees
                         WHERE date_reserved >= ? AND date_reserved < ?
                         GROUP BY reservation_id, date_reserved, customer_name,
                         customer_phone, customer_email, first_name, last_name
                         ORDER BY date_reserved, reservation_id");
        $start_date = "{$this->year}-{$this->month}-01";
        $dt = new DateTime("$start_date + 1 month");
        $end_date = $dt->format("Y-m-d");
        $q->execute([$start_date, $end_date]);
        echo "<table class='striped reservations_table'><thead><tr><th>";
        echo implode([
            "Rezervované datum", "Zákazník", "Kontakt", "Míst", "Vyřizuje",
            "<i class='material-icons'>mode_edit</i>"
            . '<i class="material-icons">delete</i>'
        ], '</th><th>');


        echo "</th></tr></thead><tbody>";

        while ($q->fetch()) {
            $date = new DateTime($r->date_reserved);
            echo "<tr><td>";
            echo implode("</td><td>", [
                Utils::convert_weekday($date->format("%w% d.m.Y H:i")),
                $r->customer_name,
                $r->customer_phone ?? $r->customer_email,
                $r->seats ?? 0,
                "{$r->first_name} {$r->last_name}",
                "<a href='{$r->get_edit_url()}'><i class='material-icons'>"
                    ."mode_edit</i></a>"
                . "<a href='{$r->get_delete_url()}' class='confirm'"
                . " data-confirm-msg='odstranit rezervaci"
                . " od {$r->customer_name}'>"
                . "<i class='material-icons'>delete</i></a>"
            ]);
            echo "</td></tr>";
        }

        echo "</tbody></table>";
        echo "<br><a href='manage/reservations/new'>Přidat rezervaci</a>";
        echo '</div>';
    }

    /**
     * Vrátí instanci třídy tvořící hlavní lištu
     * @return Menu
     */
    public function get_menu()
    {
        return new AdminMenu('manage/reservations/current');
    }

    /**
     * Zkontroluje, zda má uživatel právo přistupovat k této stránce
     * @param $position_id int Pozice přihlášeného uživatele
     * @return bool
     */
    public function check_privileges($position_id)
    {
        return $position_id >= Utils::$PRIV_WAITER;
    }

    /**
     * Vrátí titulek stránky (přijde do <title>...</title>)
     * @return string
     */
    public function get_title()
    {
        return 'Správa rezervací';
    }
}