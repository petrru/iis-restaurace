<?php

/**
 * Class ReservedRoom
 * @property $room_id
 * @property $reservation_id
 * @property $seat_count
 */
class ReservedRoom extends Model
{
    protected $room_id, $reservation_id, $seat_count;

    protected $columns = ['room_id', 'reservation_id', 'seat_count'];
    protected $primary_key = ['room_id', 'reservation_id'];
    protected $table_name = 'reserved_rooms';
}