<?php

/**
 * Class IngredientsPage
 * Seznam ingrediencí
 */
class IngredientsPage extends Page
{
    public function get_title()
    {
        return 'Správa ingrediencí';
    }

    public function print_content()
    {
        echo '<div class="container">';

        $i = new Ingredience();
        $q = $i->select("SELECT ingredience_id, ingredience_name, unit,
                         COUNT(item_id) AS amount
                         FROM ingredients
                         NATURAL LEFT JOIN ingredients_in_items
                         GROUP BY ingredience_id
                         ORDER BY ingredience_name");
        $q->execute();

        echo "<table class='striped ingredients_table'><thead><tr><th>";
        echo implode([
            "Název", "Jednotka", "<span title='Počet jídel obsahujících tuto"
            ." ingredienci'>Počet jídel</span>",
            "<i class='material-icons'>mode_edit</i>"
            . '<i class="material-icons">delete</i>'
        ], '</th><th>');


        echo "</th></tr></thead><tbody>";

        while ($q->fetch()) {
            echo "<tr><td>";
            echo implode("</td><td>", [
                $i->ingredience_name,
                $i->unit,
                $i->amount,
                "<a href='{$i->get_edit_url()}'><i class='material-icons'>"
                ."mode_edit</i></a>"
                . "<a href='{$i->get_delete_url()}' class='confirm'"
                . " data-confirm-msg='odstranit ingredienci"
                . " {$i->ingredience_name}'>"
                . "<i class='material-icons'>delete</i></a>"
            ]);
            echo "</td></tr>";
        }

        echo "</tbody></table>";
        echo "<br><a href='manage/ingredients/new'>Přidat ingredienci</a>";
        echo '</div>';
    }

    public function get_menu()
    {
        return new AdminMenu('manage/other');
    }

    public function check_privileges($position_id)
    {
        return $position_id >= Utils::$PRIV_BOSS;
    }
}