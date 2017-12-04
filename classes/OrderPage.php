<?php

class OrderPage extends Page
{
    /** @var Order */
    private $order;

    public function get_title()
    {
        return "Stůl č. {$this->order->table_number} - Objednávky";
    }

    /**
     * @throws NoEntryException
     */
    public function init()
    {
        $this->order = Order::get_by_id(explode("/", $this->url)[2]);
    }

    public function print_content()
    {
        $o = $this->order;
        echo "<div class='container'>";
        echo "<table class='striped ordered_items_table'><thead><tr><th>";
        echo implode("</th><th>", [
            "Název jídla",
            "Cena / ks",
            "Množství",
            "Cena celkem",
            "<i class='material-icons'>delete</i>"
        ]);
        echo "</th></tr></thead><tbody>";
        $i = new OrderedItem();
        $q = $i->select("SELECT item_id, amount, item_name, price
                         FROM ordered_items NATURAL JOIN items
                         NATURAL JOIN item_categories
                         WHERE order_id = ? ORDER BY menu_order, item_name");
        $q->execute([$o->order_id]);
        $total_price = 0;
        while ($q->fetch()) {
            $price = $i->price * $i->amount;
            $total_price += $price;
            echo "<tr><td>";
            echo implode("</td><td>", [
                $i->item_name,
                $i->price . " Kč",
                $i->amount,
                number_format($price, 2) . " Kč",
                "<a href='manage/orders/{$o->order_id}/del-item/"
                . "{$i->item_id}'><i class='material-icons'>delete</i></a>"
            ]);
            echo "</td></tr>";
        }
        if (!$q->rowCount()) {
            echo "<tr><td colspan='5'>-- Žádné položky --</td></tr>";
        }
        echo "</tbody></table>";
        echo "<hr>";
        echo "<table class='form_table'>";
        echo "<tr><th>Číslo stolu:</th>"
            ."<td>{$o->table_number}</td></tr>";
        echo "<tr><th>Celková útrata:</th>"
            ."<td>" . number_format($total_price, 2) . " Kč</td></tr>";
        $paid = $o->paid ? "Ano" : "Ne";
        $n_paid = $o->paid ? 0 : 1;
        echo "<tr><th>Zaplaceno:</th>"
            ."<td>$paid (<a href='manage/orders/{$o->order_id}/paid/$n_paid'>"
            ."změnit</a>)</td></tr>";
        echo "</table>";

        echo "<h3>Přidat položku</h3>";
        echo "<form action='manage/orders/{$o->order_id}/item' method='post'>";
        echo "<input type='text' class='smaller-input' placeholder='Množství'"
            ." value='1' name='amount'>×&nbsp;&nbsp;";
        echo "<select name='item'>";

        $item = new Item;
        $q = MenuPage::get_query($item);
        $last_category = null;
        while ($q->fetch()) {
            if ($item->category_id != $last_category) {
                if ($last_category)
                    echo "</optgroup>\n";
                echo "<optgroup label='{$item->category_name}'>\n";
                $last_category = $item->category_id;
            }
            echo "<option value='{$item->item_id}'>{$item->item_name}"
                ."</option>\n";
        }

        echo "</optgroup>\n</select> <input type='submit' value='Uložit'>";
        echo "</form>";
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