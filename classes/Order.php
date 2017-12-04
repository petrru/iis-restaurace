<?php

/**
 * Class Order
 * @property $order_id
 * @property $date_created
 * @property $paid
 * @property $table_number
 * @property $reservation_id
 */
class Order extends Model
{
    protected $order_id, $date_created, $paid, $table_number, $reservation_id;

    public $total;

    protected $columns = ['order_id', 'date_created', 'paid',
        'table_number', 'reservation_id'];
    protected $primary_key = 'order_id';
    protected $table_name = 'orders';
}