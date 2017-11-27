<?php

class MenuPage extends Page
{

    public function get_title()
    {
        return 'Menu';
    }

    public function print_content()
    {
        echo '<div class="container row">';
        $sql = "SELECT item_name, category_name, price, category_id FROM items
                NATURAL JOIN item_categories WHERE available = 1
                ORDER BY menu_order, item_name";

        $item = new Item;
        $q = $item->select($sql);
        $q->execute();

        $last_category = null;

        while ($q->fetch()) {
            if ($item->category_id != $last_category) {
                if ($last_category != null)
                    echo "</table></div>\n";
                echo "<div class='column_50'>";
                echo "<h3>{$item->category_name}</h3>\n";
                echo "<table class='menu_table striped'>\n";
                echo "<thead><tr><th>Název</th><th>Cena</th></tr></thead>\n";
                echo "<tbody>\n";
                $last_category = $item->category_id;
            }

            $price = $item->price;
            echo "<tr><td>{$item->item_name}</td><td>$price Kč</td></tr>\n";
        }

        echo '</tbody></table></div>';
        echo '</div>';
    }

    /**
     * @return Menu
     */
    public function get_menu()
    {
        return new PublicMenu('menu');
    }

    public function check_privileges($position_id)
    {
        return true;
    }
}