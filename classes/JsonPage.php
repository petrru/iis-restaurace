<?php

abstract class JsonPage extends Page
{
    public function should_print_html()
    {
        return false;
    }

    public function init()
    {
        header('Content-Type: application/json');
        echo json_encode($this->get_output());
    }

    protected abstract function get_output();

    protected function check_input($action_name, $required_fields) {
        if (($_POST['action'] ?? '') != $action_name)
            return false;
        foreach ($required_fields as $v) {
            if (!isset($_POST[$v]))
                return false;
        }
        return true;
    }

    public function get_title()
    {

    }

    public function print_content()
    {

    }

    /**
     * @return Menu
     */
    public function get_menu()
    {
        return new AdminMenu('');
    }
}