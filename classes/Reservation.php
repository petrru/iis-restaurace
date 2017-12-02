<?php

/**
 * Class Reservation
 * @property $reservation_id
 * @property $date_created
 * @property $date_reserved
 * @property $employee_id
 * @property $customer_name
 * @property $customer_phone
 * @property $customer_email
 */
class Reservation extends Model
{
    protected $reservation_id, $date_created, $date_reserved, $employee_id, $customer_name, $customer_phone, $customer_email;

    protected $columns = ['reservation_id', 'date_created', 'date_reserved', 'employee_id',
        'customer_name', 'customer_phone', 'customer_email'];
    protected $primary_key = 'reservation_id';
    protected $table_name = 'reservations';
}