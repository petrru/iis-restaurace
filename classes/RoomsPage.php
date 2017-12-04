<?php

/**
 * Class RoomsPage
 * Seznam místností
 */
class RoomsPage extends Page
{
    public function get_title()
    {
        return 'Správa místností';
    }

    public function print_content()
    {
        echo '<div class="container">';

        $r = new Room;
        $q = $r->select("SELECT * FROM rooms ORDER BY tables_from");
        $q->execute();

        echo "<table class='striped rooms_table'><thead><tr><th>";
        echo implode([
            "Název", "Kapacita", "Stoly",
            "<i class='material-icons'>mode_edit</i>"
            . '<i class="material-icons">delete</i>'
        ], '</th><th>');


        echo "</th></tr></thead><tbody>";

        while ($q->fetch()) {
            echo "<tr><td>";
            echo implode("</td><td>", [
                $r->description,
                $r->capacity,
                "{$r->tables_from} - {$r->tables_to}",
                "<a href='{$r->get_edit_url()}'><i class='material-icons'>"
                    ."mode_edit</i></a>"
                . "<a href='{$r->get_delete_url()}' class='confirm'"
                . " data-confirm-msg='odstranit místnost {$r->description}'>"
                . "<i class='material-icons'>delete</i></a>"
            ]);
            echo "</td></tr>";
        }

        echo "</tbody></table>";
        echo "<br><a href='manage/rooms/new'>Přidat místnost</a>";
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