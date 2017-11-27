<?php

class Employees extends Page
{

    public function get_title()
    {
        return 'Správa zaměstnanců';
    }

    public function print_content()
    {
        echo '<div class="container">';

        echo '</div>';
//        include_once "inc/content/employees.php";
    }

    public function get_menu()
    {
        return new AdminMenu('manage/employees');
    }
}