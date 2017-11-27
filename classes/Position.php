<?php

class Position extends Model
{
    public $position_id, $position_name;

    protected $columns = ['position_id', 'position_name'];
    protected $primary_key = 'position_id';
    protected $table_name = 'positions';
}