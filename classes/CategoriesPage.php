<?php

class CategoriesPage extends Page
{
    public function get_title()
    {
        return 'Správa kategorií v menu';
    }

    public function print_content()
    {
        echo '<div class="container">';

        $c = new ItemCategory();
        $q = $c->select("SELECT category_id, category_name, menu_order,
                         COUNT(item_id) AS amount
                         FROM item_categories
                         NATURAL LEFT JOIN items
                         GROUP BY category_id
                         ORDER BY menu_order");
        $q->execute();

        echo "<table class='striped categories_table'><thead><tr><th>";
        echo implode([
            "Pořadí", "Název", "Jídel",
            "<i class='material-icons'>mode_edit</i>"
            . '<i class="material-icons">delete</i>'
        ], '</th><th>');


        echo "</th></tr></thead><tbody>";

        while ($q->fetch()) {
            echo "<tr><td>";
            echo implode("</td><td>", [
                $c->menu_order,
                $c->category_name,
                $c->amount,
                "<a href='{$c->get_edit_url()}'><i class='material-icons'>"
                    ."mode_edit</i></a>"
                . "<a href='{$c->get_delete_url()}' class='confirm'"
                . " data-confirm-msg='odstranit místnost {$c->category_name}'>"
                . "<i class='material-icons'>delete</i></a>"
            ]);
            echo "</td></tr>";
        }

        echo "</tbody></table>";
        echo "<br><a href='manage/categories/new'>Přidat kategorii</a>";
        echo '</div>';
    }

    public function get_menu()
    {
        return new AdminMenu('manage/other');
    }

    public function check_privileges($position_id)
    {
        return $position_id == Utils::$PRIV_OWNER;
    }
}