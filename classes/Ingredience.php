<?php

/**
 * Class Ingredience
 * @property $ingredience_id
 * @property $ingredience_name
 * @property $unit
 */
class Ingredience extends Model
{
    protected $ingredience_id, $ingredience_name, $unit;

    public $item_id, $amount;

    protected $columns = ['ingredience_id', 'ingredience_name', 'unit'];
    protected $primary_key = 'ingredience_id';
    protected $table_name = 'ingredients';
}