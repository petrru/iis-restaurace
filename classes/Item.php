<?php

class Item extends Model
{
    public $item_id, $item_name, $available, $price, $category_id;

    public $category_name;
}