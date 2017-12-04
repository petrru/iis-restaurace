<?php

/**
 * Class ChangePasswordPage
 * Změna vlastního hesla
 */
class ChangePasswordPage extends Page
{
    /** @var Form */
    protected $form;

    /** @var Employee */
    protected $employee;

    public function get_title()
    {
        echo "Změna hesla";
    }

    public function init()
    {
        $this->employee = Utils::get_logged_user();
        $extra = ['do-not-save' => true, 'do-not-load' => true,
                  'required' => true];
        $this->form = new Form($this->employee, [
            ['old-password', 'Staré heslo', 'password', $extra],
            ['new-password', 'Nové heslo', 'password', $extra],
            ['new-password-2', 'Nové heslo (znovu)', 'password', $extra],
        ], [$this, 'validate']);
    }

    public function validate() {
        $out = [];
        foreach (['old-password', 'new-password', 'new-password-2'] as $key) {
            if (empty($_POST[$key]))
                return $out;
        }
        if (!password_verify($_POST['old-password'], $this->employee->password))
            $out[] = 'Staré heslo není platné';
        if ($_POST['new-password'] != $_POST['new-password-2'])
            $out[] = 'Nová hesla nejsou stejná';
        $this->employee->password = password_hash($_POST['new-password'],
                                                  PASSWORD_DEFAULT);
        return $out;
    }

    public function print_content()
    {
        echo "<div class='container'><h3>Změna hesla</h3>";
        $this->form->print_form();
        echo "</div>";
    }

    public function should_print_html()
    {
        if (!$this->form->is_save_successful()) {
            return true;
        }
        Utils::set_success_message('Heslo bylo úspěšně změněno.');
        Utils::redirect('manage/other');
        return false;
    }


    /**
     * @return Menu
     */
    public function get_menu()
    {
        return new AdminMenu('manage/other');
    }

    public function check_privileges($position_id)
    {
        return $position_id >= Utils::$PRIV_WAITER;
    }
}