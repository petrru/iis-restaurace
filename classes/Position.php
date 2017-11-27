<?php

class Employee extends Model
{
    public $employee_id, $first_name, $last_name, $phone_number, $email,
           $birth_number, $position_id, $salary, $bank_account_prefix,
           $bank_account, $bank_code, $street_name, $street_number, $city, $zip,
           $username, $password;

    public $position_name;

    protected $columns = ['employee_id', 'first_name', 'last_name',
        'phone_number', 'email', 'birth_number', 'position_id', 'salary',
        'bank_account_prefix', 'bank_account', 'bank_code', 'street_name',
        'street_number', 'city', 'zip', 'username', 'password'];
    protected $primary_key = 'employee_id';
    protected $table_name = 'employees';

    public function get_full_address() {
        if ($this->street_name and $this->street_number and $this->city)
            return "{$this->street_name} {$this->street_number}, {$this->city}";
        elseif ($this->city)
            return $this->city;
        elseif ($this->street_name and $this->street_number)
            return "{$this->street_name} {$this->street_number}";
        else
            return "";
    }

    public function get_salary() {
        return $this->salary . " Kč";
    }

    public function get_edit_url() {
        return 'manage/employees/' . $this->employee_id;
    }
}