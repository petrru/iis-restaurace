<?php

class EmployeesPage extends Page
{
    public function get_title()
    {
        return 'Správa zaměstnanců';
    }

    public function print_content()
    {
        echo '<div class="container">';

        $e = new Employee;
        $q = $e->select("SELECT first_name, last_name, position_name, username,
                         street_name, street_number, city, salary, employee_id
                         FROM employees NATURAL JOIN positions
                         ORDER BY 2, 1");
        $q->execute();

        echo "<table class='striped employees_table'><thead><tr><th>";
        echo implode([
            "Jméno", "Adresa", "Login", "Pozice", "Plat",
            "<i class='material-icons'>mode_edit</i>"
        ], '</th><th>');


        echo "</th></tr></thead><tbody>";

        while ($q->fetch()) {
            echo "<tr><td>";
            echo implode("</td><td>", [
                "{$e->last_name} {$e->first_name}",
                $e->get_full_address(),
                $e->username,
                $e->position_name,
                $e->get_salary(),
                "<a href='{$e->get_edit_url()}'><i class='material-icons'>mode_edit</i></a>"
            ]);
            echo "</td></tr>";
        }

        echo "</tbody></table>";
        echo "<br><a href='manage/employees/new'>Přidat zaměstnance</a>";
        echo '</div>';
//        include_once "inc/content/employees.php";
    }

    public function get_menu()
    {
        return new AdminMenu('manage/employees');
    }

    public function check_privileges($position_id)
    {
        return $position_id == Utils::$PRIV_OWNER;
    }
}