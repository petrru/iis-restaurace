<?php

class OrderItemPaidPage extends Page
{
    public function should_print_html()
    {
        return false;
    }

    /**
     * @throws NoEntryException
     */
    public function init()
    {
        $arr = explode('/', $this->url);

        $o = Order::get_by_id($arr[2]);
        $o->begin_update();
        $o->paid = $arr[4];
        if ($o->save()) {
            if ($arr[4])
                $msg = 'Útrata byla zaplacena';
            else
                $msg = 'Zaplacení útraty bylo zrušeno';
            Utils::set_success_message($msg);
        }
        else {
            Utils::set_error_message("Chyba při ukládání změny záznamu");
        }
        Utils::redirect("manage/orders/{$arr[2]}");
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