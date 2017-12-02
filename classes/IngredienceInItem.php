<?php

/**
 * Class Ingredience
 * @property $ingredience_id
 * @property $item_id
 * @property $amount
 */
class IngredienceInItem extends Model
{
    protected $ingredience_id, $item_id, $amount;

    public $ingredience_name, $unit;

    protected $columns = ['ingredience_id', 'item_id', 'amount'];
    protected $primary_key = ['ingredience_id', 'item_id'];
    protected $table_name = 'ingredients_in_items';
}