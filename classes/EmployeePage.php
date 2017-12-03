<?php

class EmployeePage extends FormPage
{
    /** @var Employee */
    protected $item;

    protected $entity_name = 'zaměstnance';
    protected $entity_name_plural = 'zaměstnanců';

    public function get_data()
    {
        return [
            ['first_name', 'Jméno', 'text', ['required' => true]],
            ['last_name', 'Příjmení', 'text', ['required' => true]],
            ['email', 'E-mail', 'email', []],
            ['birth_number', 'Rodné číslo', 'birth_number', []],
            ['position_id', 'Pozice', 'fk', ['table' => new Position(),
                'sql' => 'SELECT position_id, position_name FROM positions
                          ORDER BY position_id',
                'display' => 'position_name', 'required' => true]],
            ['phone_number', 'Telefon', 'number', []],
            ['salary', 'Plat', 'number', ['after' => 'Kč']],
            ['bank_account', 'Bankovní účet', 'bank', [
                'prefix' => $_POST['bank_account_prefix'] ??
                            $this->item->bank_account_prefix,
                'code' => $_POST['bank_code'] ?? $this->item->bank_code]],
            ['bank_account_prefix', '', 'none', ''],
            ['bank_code', '', 'none', ''],
            ['street_name', 'Ulice', 'text', []],
            ['street_number', 'Číslo popisné', 'number', []],
            ['city', 'Město', 'text', []],
            ['zip', 'PSČ', 'number', []],
            ['username', 'Přihlašovací jméno', 'text', ['required' => true]],
            ['password', 'Přihlašovací heslo', 'password',
                ['do-not-save' => true, 'do-not-load' => true,
                 'required' => $this->is_new]],
            ['password-2', 'Přihlašovací heslo (znovu)', 'password',
                ['do-not-save' => true, 'do-not-load' => true,
                 'required' => $this->is_new]],
        ];
    }

    public function should_print_html() {
        if (!$this->form->is_save_successful()) {
            return true;
        }
        if ($this->is_new) {
            Utils::set_success_message('Záznam byl úspěšně uložen.');
            Utils::redirect('manage/employees');
            return false;
        }
        else {
            Utils::set_success_message('Záznam byl úspěšně upraven.');
            return true;
        }
    }

    public function to_string()
    {
        if ($this->is_new)
            return "Nový zaměstnanec";
        $e = $this->item;
        return "{$e->first_name} {$e->last_name}";
    }

    /**
     * @return Menu
     */
    public function get_menu()
    {
        return new AdminMenu('manage/employees');
    }

    public function validate() {
        $e = $this->item;
        $e->employee_id;
        $errors = [];
        if (!preg_match("/^[0-9]*$/", $e->bank_account_prefix)
            || !preg_match("/^[0-9]*$/", $e->bank_code)
            || !preg_match("/^[0-9]*$/", $e->bank_account)) {
            $errors[] = "Číslo bankovního účtu smí obsahovat pouze čísla";
        }
        elseif ($e->bank_code xor $e->bank_account) {
            $errors[] = "Je nutné vyplnit číslo účtu i kód banky";
        }
        elseif ($e->bank_account_prefix && !$e->bank_account) {
            $errors[] = "Nelze vyplnit pouze předčíslí účtu";
        }
        if (!empty($_POST['password'] || !empty($_POST['password-2']))) {
            if ($_POST['password'] != $_POST['password-2']) {
                $errors[] = "Hesla nejsou stejná";
            }
            else {
                $e->password = password_hash($_POST['password'],
                                             PASSWORD_DEFAULT);
            }
        }
        if ($e->birth_number) {
            if (!preg_match("_^[0-9]{6}/[0-9]{3,4}_", $e->birth_number)) {
                $errors[] = 'Neplatný formát rodného čísla';
            }
            else {
                $e->birth_number = str_replace('/', '', $e->birth_number);
            }
        }
        return $errors;
    }

    public function check_privileges($position_id)
    {
        return $position_id == Utils::$PRIV_OWNER;
    }


    protected function get_links()
    {
        return ["<a href='manage/employees'>Zpět na seznam zaměstnanců</a>"];
    }

    protected function get_delete_url()
    {
        return $this->item->get_delete_url();
    }

    protected function get_item($id)
    {
        return Employee::get_by_id($id);
    }
}