<?php

/**
 * Class Room
 * @property $room_id
 * @property $capacity
 * @property $description
 * @property $tables_from
 * @property $tables_to
 */
class Room extends Model
{
    protected $room_id, $capacity, $description, $tables_from, $tables_to;

    public $seat_count, $taken_seats;

    protected $columns = ['room_id', 'capacity', 'description', 'tables_from',
        'tables_to'];
    protected $primary_key = 'room_id';
    protected $table_name = 'rooms';
}