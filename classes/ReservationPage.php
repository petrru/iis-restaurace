<?php

/**
 * Class ReservationPage
 * Přidání zákaznické rezervace
 */
class ReservationPage extends Page {


    public function get_title()
    {
        return 'Rezervácia';
    }

    public function print_content()
    {
        include "inc/content/reservation.php";
    }

    /**
     * @return Menu
     */
    public function get_menu()
    {
        return new PublicMenu('reservation');
    }

    public function check_privileges($position_id)
    {
        return true;
    }

    public function should_print_html() {
        if ($_POST) {
            if (empty($_POST['customer_name']) || empty($_POST['reservation_day']) || empty($_POST['reservation_time']) ||
                empty($_POST['people_amount']) || empty($_POST['reservation_room']) || (empty($_POST['customer_phone']) &&
                    empty($_POST['customer_email']))) {
                Utils::set_error_message("Vyplňte prosím všetky údaje označené hviezdičkou");
                return true;
            }

            if (!preg_match("/^([0-9]{1,2}\.){2}[0-9]{4}$/", $_POST['reservation_day'])) {
                Utils::set_error_message("Nesprávny formát dátumu (DD.MM.YYYY)");
                return true;
            }

            if (!preg_match("/^[0-9]{1,2}:[0-9]{1,2}$/", $_POST['reservation_time'])) {
                Utils::set_error_message("Nesprávny formát času (HH:MM)");
                return true;
            }

            if (!preg_match("/^[0-9]*$/", $_POST['people_amount'])) {
                Utils::set_error_message("Nesprávny formát počtu ľudí");
                return true;
            }

            if ($_POST['people_amount'] > 8) {
                Utils::set_error_message("Maximálny počet rezervovaných miest je 8. V prípade záujmu rezervácie väčšieho počtu miest nás, prosíme, kontaktujte.");
                return true;
            }

            if ($_POST['customer_phone'] && !preg_match("/^[0-9]{9}$/", $_POST['customer_phone'])) {
                Utils::set_error_message("Nesprávne telefónne číslo");
                return true;
            }

            if ($_POST['customer_email'] && !preg_match("/^.*@.*$/", $_POST['customer_email'])) {
                Utils::set_error_message("Nesprávny email");
                return true;
            }

            $day = preg_replace("/^([0-9]+)\.([0-9]+)\.([0-9]+)$/",
                                "$3-$2-$1",
                                $_POST['reservation_day']);

            $new_reservation = new Reservation();
            $new_reservation->begin_update();
            $new_reservation->customer_name = $_POST['customer_name'];
            $new_reservation->date_reserved = $day ." ".$_POST['reservation_time'];
            $new_reservation->customer_phone = $_POST['customer_phone'];
            $new_reservation->customer_email = $_POST['customer_email'];
            $new_reservation->customer_name = $_POST['customer_name'];
            $new_reservation->save();

            $new_reserved_room = new ReservedRoom();
            $new_reserved_room->begin_update();
            $new_reserved_room->room_id = $_POST['reservation_room'];
            $new_reserved_room->reservation_id = $new_reservation->reservation_id;
            $new_reserved_room->seat_count = $_POST['people_amount'];
            if ($new_reserved_room->save()) {
                Utils::set_success_message("Vašu rezerváciu sme prijali.");
                Utils::redirect("reservation");
                return false;
            }
            else {
                Utils::set_error_message("Chyba při ukládání rezervace.");
            }
        }
        return true;
    }

    public function init()
    {

    }
}
