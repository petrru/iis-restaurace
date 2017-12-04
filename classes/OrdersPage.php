<?php

class OrdersPage extends Page
{
    public $extra_script = 'orders.js';

    public function get_title()
    {
        return 'Objednávky';
    }

    private $table_orders = [];

    public function print_content()
    {
        echo '<div class="container">';
        echo '<select id="reservation">';
        echo '<option value="">Zákazník bez rezervace</option>';

        $order = new Order();
        $q = $order->select("SELECT order_id, table_number
                             FROM orders WHERE paid = 0
                             ORDER BY 2");
        $q->execute();
        while ($q->fetch()) {
            $this->table_orders[$order->table_number] = $order->order_id;
        }

        $res = new Reservation();
        $q = $res->select("SELECT reservation_id, customer_name,
                           SUM(seat_count) AS seats,
                           DATE_FORMAT(date_reserved, '%H:%i') AS date_reserved
                           FROM reservations NATURAL JOIN reserved_rooms
                           NATURAL LEFT JOIN orders
                           WHERE date_reserved >= NOW() - INTERVAL 20 MINUTE
                           AND date_reserved <= NOW() + INTERVAL 1 HOUR
                           AND order_id IS NULL
                           GROUP BY reservation_id, customer_name,
                           date_reserved
                           ORDER BY date_reserved");
        $q->execute();
        while ($q->fetch()) {
            echo "<option value='{$res->reservation_id}'>"
                ."{$res->date_reserved} - {$res->customer_name}</option>";
        }
        echo '</select>';

        echo '<h2>Vyberte stůl:</h2>';
        $r = new Room;
        $q = $r->select("SELECT * FROM rooms ORDER BY tables_from");
        $q->execute();

        while ($q->fetch()) {
            echo "<h3>{$r->description}</h3>";
            for ($i = $r->tables_from; $i <= $r->tables_to; $i++) {
                if (isset($this->table_orders[$i]))
                    $id = " data-order='{$this->table_orders[$i]}'";
                else
                    $id = "";
                echo "<div class='room-box'$id>" . $i . "</div>";
            }
        }


        echo "<hr><div class='room-box small'></div> Volné stoly"
            ."&nbsp;&nbsp;&nbsp;&nbsp;"
            ."<div class='room-box small' data-order=''></div> Obsazené stoly"
            ."<br><a href='manage/orders/all'>Historie objednávek</a>";
        echo '</div>';
    }

    public function get_menu()
    {
        return new AdminMenu('manage/orders');
    }

    public function check_privileges($position_id)
    {
        return $position_id >= Utils::$PRIV_WAITER;
    }
}