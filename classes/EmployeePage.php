<?php

class EmployeePage extends Page
{
    /**
     * @var Employee
     */
    private $employee;

    /**
     * @var Form
     */
    private $form;

    /**
     * @var bool
     */
    private $is_new;

    public function init()
    {
        $this->employee = Employee::get_by_id(explode("/", $this->url)[2]);
        $this->is_new = !$this->employee->employee_id;
        $this->form = new Form($this->employee, [
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
                            $this->employee->bank_account_prefix,
                'code' => $_POST['bank_code'] ?? $this->employee->bank_code]],
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
        ], [$this, 'validate']);
    }

    public function should_print_html() {
        if (!$this->form->is_save_successful()) {
            return true;
        }
        if ($this->is_new) {
            $_SESSION['success_message'] = 'Záznam byl úspěšně uložen.';
            header('Location: ' . Mapper::url('manage/employees'));
            return false;
        }
        else {
            $_SESSION['success_message'] = 'Záznam byl úspěšně upraven.';
            return true;
        }
    }


    public function get_title()
    {
        if (!$this->employee->employee_id)
            return "Nový zaměstnanec - Správa zaměstnanců";
        $e = $this->employee;
        return "{$e->first_name} {$e->last_name} - Správa zaměstnanců";
    }

    public function print_content()
    {
        echo '<div class="container"><h3>';
        echo $this->employee->employee_id ? 'Upravit' : 'Přidat';
        echo ' zaměstnance</h3>';
        $this->form->print_form();
        echo "<ul>";
        if ($this->employee->employee_id) {
            $id = $this->employee->employee_id;
            echo "<li><a href='manage/employees/$id/delete' class='confirm'>"
                ."Odstranit zaměstnance</a></li>";
        }
        echo "<li><a href='manage/employees'>"
            ."Zpět na seznam zaměstnanců</a></li>";
        echo "</ul>";
        echo '</div>';
    }

    /**
     * @return Menu
     */
    public function get_menu()
    {
        return new AdminMenu('manage/employees');
    }

    public function validate() {
        $e = $this->employee;
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


}