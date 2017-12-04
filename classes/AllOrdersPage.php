<?php

class AllOrdersPage extends Page
{

    public function get_title()
    {
        return 'Všechny objednávky';
    }

    public function print_content()
    {
        echo "<div class='container'>";
        echo "<table class='striped orders_table'><thead><tr><th>";
        echo implode("</th><th>", [
            "Datum",
            "Č. stolu",
            "Cena celkem",
            "Zaplaceno",
            "<i class='material-icons'>mode_edit</i>"
        ]);
        echo "</th></tr></thead><tbody>";
        $o = new Order();
        $q = $o->select("SELECT order_id, date_created, table_number, paid,
                         SUM(amount * price) AS total
                         FROM orders NATURAL LEFT JOIN ordered_items
                         NATURAL LEFT JOIN items
                         GROUP BY order_id, date_created, table_number, paid
                         ORDER BY date_created, order_id");
        $q->execute();
        while ($q->fetch()) {
            $date = new DateTime($o->date_created);
            echo "<tr><td>";
            echo implode("</td><td>", [
                Utils::convert_weekday($date->format("%w% d.m.Y H:i")),
                $o->table_number,
                ($o->total ? $o->total : '0.00') . " Kč",
                ($o->paid) ? "Ano" : "<b>Ne</b>",
                "<a href='{$o->get_edit_url()}'><i class='material-icons'>"
                    ."mode_edit</i></a>",
            ]);
            echo "</td></tr>";
        }
        echo "</tbody></table>";
        echo "</div>";
    }

    /**
     * @return Menu
     */
    public function get_menu()
    {
        return new AdminMenu('manage/orders');
    }

    public function check_privileges($position_id)
    {
        return $position_id >= Utils::$PRIV_WAITER;
    }
}