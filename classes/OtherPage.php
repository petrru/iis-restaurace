<?php

/**
 * Class OtherPage
 * Záložka "ostatní" v administraci
 */
class OtherPage extends Page
{

    public function get_title()
    {
        return 'Ostatní';
    }

    public function print_content()
    {
        $list = [];
        $priv = Utils::get_logged_user()->position_id;

        $list[] = ['change-password', 'Změna hesla'];
        if ($priv == Utils::$PRIV_OWNER) {
            $list[] = ['rooms', 'Správa místností'];
        }
        if ($priv >= Utils::$PRIV_BOSS) {
            $list[] = ['ingredients', 'Správa ingrediencí'];
            $list[] = ['categories', 'Správa kategorií v menu'];
        }
        echo "<div class='container'><ul>";
        foreach ($list as list($url, $label)) {
            echo "<li><a href='manage/$url'>$label</a></li>";
        }
        echo "</ul></div>";
    }

    /**
     * @return Menu
     */
    public function get_menu()
    {
        return new AdminMenu('manage/other');
    }

    public function check_privileges($position_id)
    {
        return $position_id >= Utils::$PRIV_WAITER;
    }
}