<?php

/**
 * Class NewOrderPage
 * Nová objednávky
 */
class NewOrderPage extends Page
{
    public function should_print_html()
    {
        return false;
    }

    public function init()
    {
        $arr = explode('/', $this->url);
        $order = new Order();
        $order->begin_update();
        $order->paid = 0;
        $order->table_number = $arr[3];
        $order->reservation_id = $arr[4] ?? null;
        $order->save();
        Utils::redirect("manage/orders/{$order->order_id}");
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