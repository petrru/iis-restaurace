<?php

class ItemPage extends FormPage
{
    /** @var Item */
    protected $item;

    protected $entity_name = 'jídlo';
    protected $entity_name_plural = 'menu';


    public function get_data()
    {
        return [
            ['item_name', 'Název jídla', 'text', ['required' => true]],
            ['price', 'Cena', 'number', ['after' => 'Kč', 'required' => true]],
            ['category_id', 'Kategorie', 'fk', ['table' => new ItemCategory(),
                'sql' => 'SELECT category_id, category_name FROM item_categories
                          ORDER BY menu_order',
                'display' => 'category_name', 'required' => true]],
            ['available', 'Jídlo je v nabídce', 'checkbox', []],
        ];
    }

    public function get_menu()
    {
        return new AdminMenu('manage/menu');
    }

    public function check_privileges($position_id)
    {
        return in_array($position_id, [Utils::$PRIV_OWNER, Utils::$PRIV_BOSS]);
    }

    public function validate() {
        return [];
    }

    protected function get_links()
    {
        return ["<a href='manage/menu'>Zpět na menu</a>"];
    }

    protected function get_delete_url()
    {
        return $this->item->get_delete_url();
    }

    protected function to_string()
    {
        if ($this->is_new)
            return "Nová položka";
        return $this->item->item_name;
    }

    protected function get_item($id)
    {
        return Item::get_by_id($id);
    }

    public function should_print_html() {
        if (!$this->form->is_save_successful()) {
            return true;
        }
        if ($this->is_new) {
            $_SESSION['success_message'] = 'Záznam byl úspěšně uložen.';
            header('Location: ' . Mapper::url('manage/menu'));
            return false;
        }
        else {
            $_SESSION['success_message'] = 'Záznam byl úspěšně upraven.';
            header('Location: ' . Mapper::url('manage/menu'));
            return false;
        }
    }
}