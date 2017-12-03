<?php

class CategoryPage extends FormPage
{
    /** @var ItemCategory */
    protected $item;

    protected $entity_name = 'kategorii';
    protected $entity_name_plural = 'kategorií';


    protected function get_data()
    {
        return [
            ['category_name', 'Název kategorie', 'text',
                ['required' => true]],
            ['menu_order', 'Pořadí v menu', 'number', ['required' => true]],
        ];
    }

    protected function get_links()
    {
        return ["<a href='manage/categories'>Zpět na seznam "
                ."{$this->entity_name_plural}</a>"];
    }

    /**
     * @param $id int ID záznamu
     * @return ItemCategory
     * @throws NoEntryException
     */
    protected function get_item($id)
    {
        return ItemCategory::get_by_id($id);
    }

    protected function to_string()
    {
        if ($this->is_new)
            return "Nová kategorie";
        return $this->item->category_name;
    }

    public function validate()
    {
        return [];
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
        return $position_id >= Utils::$PRIV_BOSS;
    }

    public function should_print_html() {
        if (!$this->form->is_save_successful()) {
            return true;
        }
        if ($this->is_new) {
            Utils::set_success_message('Záznam byl úspěšně uložen.');
        }
        else {
            Utils::set_success_message('Záznam byl úspěšně upraven.');
        }
        Utils::redirect('manage/categories');
        return false;
    }
}