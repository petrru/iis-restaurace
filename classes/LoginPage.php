<?php
class LoginPage extends Page
{
    public function get_title()
    {
        return 'Prihlásiť sa';
    }

    public function print_content()
    {
        include "inc/content/login.php";
    }

    public function get_menu()
    {
        return new PublicMenu('login');
    }

    public function should_print_html()
    {
        if (!empty($_SESSION['employee_id'])) {
            Utils::redirect('manage');
            return false;
        }
        if ($_POST) {
            $user = new Employee();
            $q = $user->select("SELECT employee_id, `password` FROM employees
                                WHERE username = ? AND position_id != 4");
            $q->execute([$_POST['username'] ?? '']);
            $q->fetch();
            if (password_verify($_POST['password'] ?? '', $user->password)) {
                $_SESSION['employee_id'] = $user->employee_id;
                $_SESSION['last_active'] = time();
                $url = $_POST['back'] ?? Mapper::url('manage');
                header("Location:" . $url);
                return false;
            }
            else {
                $_SESSION['invalid_password'] = true;
            }
        }
        return true;
    }


    public function check_privileges($position_id)
    {
        return true;
    }
}