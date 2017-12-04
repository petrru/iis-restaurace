<?php

/**
 * Class Order
 * @property $order_id
 * @property $item_id
 * @property $amount
 */
class OrderedItem extends Model
{
    protected $order_id, $item_id, $amount = 0;

    public $item_name, $price;

    protected $columns = ['order_id', 'item_id', 'amount'];
    protected $primary_key = ['order_id', 'item_id'];
    protected $table_name = 'ordered_items';
}