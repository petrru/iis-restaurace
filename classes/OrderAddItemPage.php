<?php

/**
 * Class OrderAddItemPage
 * Přidá položku k objednávce
 */
class OrderAddItemPage extends Page
{
    public function should_print_html()
    {
        return false;
    }

    public function init()
    {
        $order_id = explode('/', $this->url)[2];
        if (!isset($_POST['amount']) || empty($_POST['item'])) {
            Utils::set_error_message("Zpracování formuláře selhalo.");
            Utils::redirect("manage/orders");
            return;
        }
        if (!preg_match("/^[1-9][0-9]*$/", $_POST['amount'])) {
            Utils::set_error_message("Neplatný počet položek.");
            Utils::redirect("manage/orders/" . $order_id);
            return;
        }
        $oi = OrderedItem::get_by_id_or_new([$order_id, $_POST['item']]);
        $oi->begin_update();
        if ($oi->is_new()) {
            $oi->order_id = $order_id;
            $oi->item_id = $_POST['item'];
        }
        $oi->amount += $_POST['amount'];
        if ($oi->save()) {
            Utils::set_success_message("Položka byla úspěšně přidána");
        }
        else {
            Utils::set_error_message("Chyba při ukládání záznamu");
        }
        Utils::redirect("manage/orders/$order_id");
    }

    public function get_title()
    {
        return '';
    }

    public function print_content()
    {

    }

    /**
     * @return Menu
     */
    public function get_menu()
    {
        return new PublicMenu('');
    }

    public function check_privileges($position_id)
    {
        return $position_id >= Utils::$PRIV_WAITER;
    }
}