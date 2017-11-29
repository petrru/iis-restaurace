<?php

/**
 * Class Position
 * @property $position_id
 * @property $position_name
 */
class Position extends Model
{
    protected $position_id, $position_name;

    protected $columns = ['position_id', 'position_name'];
    protected $primary_key = 'position_id';
    protected $table_name = 'positions';
}