<?php

/**
 * Class ItemsPage
 * Seznam jídel
 */
class ItemsPage extends MenuPage
{
    protected $only_available = false;

    public function get_title()
    {
        return 'Správa menu';
    }

    /**
     * @return Menu
     */
    public function get_menu()
    {
        return new AdminMenu('manage/menu');
    }

    public function check_privileges($position_id)
    {
        return in_array($position_id, [Utils::$PRIV_OWNER, Utils::$PRIV_BOSS]);
    }

    protected function get_header()
    {
        $arr = parent::get_header();
        $arr[] = '<i class="material-icons">mode_edit</i>'
               . '<i class="material-icons">delete</i>';
        return $arr;
    }

    /**
     * Vrátí sloupce jednoho řádku menu
     * @param Item $item
     * @return array
     */
    protected function get_row($item)
    {
        $arr = parent::get_row($item);
        if (!$item->available) {
            for ($i = 0; $i < count($arr); $i++) {
                $arr[$i] = "<s>{$arr[$i]}</s>";
            }
        }
        $arr[] = "<a href='{$item->get_edit_url()}'><i class='material-icons'>"
               . "mode_edit</i></a>"
               . "<a href='{$item->get_delete_url()}' class='confirm'"
               . " data-confirm-msg='odstranit jídlo {$item->item_name}'>"
               . "<i class='material-icons'>delete</i></a>";
        return $arr;
    }

    public function print_content()
    {
        echo "<div class='add_item_btn'>";
        echo "<a href='manage/menu/new'>Nová položka</a></div>";
        parent::print_content();
    }



}