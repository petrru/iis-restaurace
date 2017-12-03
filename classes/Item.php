<?php

/**
 * Class Item
 * @property $item_id
 * @property $item_name
 * @property $available
 * @property $price
 * @property $category_id
 */
class Item extends Model
{
    protected $item_id, $item_name, $available = true, $price, $category_id;

    public $category_name, $amount;

    protected $columns = ['item_id', 'item_name', 'available', 'price',
        'category_id'];
    protected $primary_key = 'item_id';
    protected $table_name = 'items';


    public function get_edit_url() {
        return 'manage/menu/' . $this->item_id;
    }

    public function get_delete_url() {
        return 'manage/menu/' . $this->item_id . '/delete';
    }
}