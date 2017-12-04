<?php

/**
 * Class MenuPage
 * Menu
 */
class MenuPage extends Page
{
    protected $only_available = true;

    public function get_title()
    {
        return 'Menu';
    }

    /**
     * @param $item Item
     * @param bool $only_available
     * @return PDOStatement
     */
    public static function get_query($item, $only_available=true) {
        $only_available__sql = $only_available ? 'WHERE available = 1' : '';
        $sql = "SELECT item_id, item_name, category_name, price, category_id,
                available
                FROM items
                NATURAL JOIN item_categories $only_available__sql
                ORDER BY menu_order, item_name";
        $q = $item->select($sql);
        $q->execute();
        return $q;
    }

    public function print_content()
    {
        echo '<div class="container row">';

        $item = new Item;
        $q = self::get_query($item, $this->only_available);
        $last_category = null;

        while ($q->fetch()) {
            if ($item->category_id != $last_category) {
                if ($last_category != null)
                    echo "</table></div>\n";
                echo "<div class='column_50'>";
                echo "<h3>{$item->category_name}</h3>\n";
                echo "<table class='menu_table striped'>\n";
                echo "<thead>\n<tr><th>";
                echo implode('</th><th>', $this->get_header());
                echo "</th></tr>\n</thead>\n<tbody>\n";
                $last_category = $item->category_id;
            }

            echo "<tr><td>" . implode('</td><td>', $this->get_row($item))
               . "</td></tr>\n";
        }

        echo '</tbody></table></div>';
        echo '</div>';
    }

    /**
     * Vrátí hlavičku tabulky
     * @return array
     */
    protected function get_header() {
        return ['Název', 'Cena'];
    }

    /**
     * Vrátí řádek tabulky pro zadanou položku
     * @param $item Item
     * @return array
     */
    protected function get_row($item) {
        return [$item->item_name, "{$item->price} Kč"];
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