<?php

/**
 * Class Position
 * @property $category_id
 * @property $category_name
 * @property $menu_order
 */
class ItemCategory extends Model
{
    protected $category_id, $category_name, $menu_order;
    public $amount;

    protected $columns = ['category_id', 'category_name', 'menu_order'];
    protected $primary_key = 'category_id';
    protected $table_name = 'item_categories';

    public function get_edit_url()
    {
        return 'manage/categories/' . $this->get_id();
    }

    public function get_delete_url()
    {
        return 'manage/categories/' . $this->get_id() . '/delete';
    }
}