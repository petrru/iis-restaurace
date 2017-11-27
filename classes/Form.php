<?php

class Form
{
    /**
     * @var array
     */
    private $data;

    /**
     * @var Model
     */
    private $model;

    /**
     * @var array
     */
    private $errors;

    /**
     * @var bool
     */
    private $save_successful = false;

    public function __construct($model, $data, callable $validate_func)
    {
        $this->model = $model;
        $this->data = $data;
        if ($_POST) {
            $this->model->begin_update();
            foreach ($this->data as list($key, $label, $type, $other)) {
                if (!empty($other['do-not-save']))
                    continue;
                $val = trim($_POST[$key] ?? '');
                if ($val == '' && empty($other['required']))
                    $val = NULL;
                $this->model->$key = $val;
            }
            $this->errors = $validate_func();
            $this->validate_form();
            if (count($this->errors) == 0) {
                $res = $this->model->save();
                if (is_array($res)) {
                    foreach ($this->data as list($key, $label, $type, $other)) {
                        if ($key == $res[2]) {
                            $this->errors[] = "$label není unikátní";
                            break;
                        }
                    }
                }
                elseif (!$res)
                    $this->errors[] = 'Chyba při ukládání záznamu do databáze';
                else
                    $this->save_successful = true;
            }
        }
    }

    public function print_form() {
        if ($this->errors) {
            echo "Při ukládání formuláře došlo k následujícím chybám:<ul><li>"
               . implode('</li><li>', $this->errors), "</li></ul>";
        }
        echo "<form action='" . htmlspecialchars($_SERVER['REQUEST_URI'])
           . "' method='post'>";
        echo "<table class='form_table'>";
        foreach ($this->data as list($key, $label, $type, $other)) {
            if ($type == 'none')
                continue;
            $value = empty($other['do-not-load']) ? $this->model->$key : '';
            echo "<tr><th>$label:";
            if (!empty($other['required']))
                echo " <sup class='required_star' title='Povinná položka'>*"
                    ."</sup>";
            echo "</th><td>";
            switch ($type) {
                case "password":
                    echo "<input type='password' name='$key"
                        . (isset($other['second-password']) ? '-2' : '')
                        . "' autocomplete='off'>";
                    break;
                case "text":
                case "email":
                    echo "<input type='$type' name='$key' value='$value'>";
                    break;
                case "number":
                    echo "<input type='text' name='$key' value='$value'>";
                    break;
                case "birth_number":
                    $value = preg_replace("_(.{6})([0-9]+)_", "$1/$2", $value);
                    echo "<input type='text' name='$key' value='$value'"
                        ." maxlength='11'>";
                    break;
                case "bank":
                    echo "<input type='text' name='bank_account_prefix'"
                        ." value='{$other['prefix']}' maxlength='6'> - ";
                    echo "<input type='text' name='bank_account'"
                        ." value='$value' maxlength='10'> / ";
                    echo "<input type='text' name='bank_code'"
                        ." value='{$other['code']}' maxlength='4'>";
                    break;
                case "fk":
                    echo "<select name='$key'>";
                    /* @var $table Model */
                    $table = $other['table'];
                    $q = $table->select($other['sql']);
                    $q->execute();
                    $pk = $table->get_primary_key();
                    $display = $other['display'];
                    while ($q->fetch()) {
                        echo "<option value='{$table->$pk}'"
                           . ($table->$pk == $value ? ' selected' : '')
                           . ">{$table->$display}</option>";
                    }
                    echo "</select>";
                    break;
            }
            if (isset($other['after']))
                echo ' ' . $other['after'];
            echo "</td></tr>";
        }
        echo "</table>";
        echo "<input type='submit' value='Uložit změny'>";
        echo "</form>";
    }

    private function validate_form() {
        foreach ($this->data as list($key, $label, $type, $other)) {
            if (!isset($_POST[$key])) {
                $this->errors[] = "Nebyla odeslána hodnota $label";
                continue;
            }
            $val = trim($_POST[$key]);
            if (!empty($other['required']) and $val == '') {
                $this->errors[] = "Nebyla vyplněna hodnota pole $label";
                continue;
            }
            switch ($type) {
                case 'email':
                    if ($val && !preg_match('/.+@.+/', $val)) {
                        $this->errors[] = "Zadaný e-mail není platný";
                    }
                    break;
                case 'number':
                    if (!preg_match("/^[0-9]*$/", $val)) {
                        $this->errors[] = "$label neobsahuje číslo";
                    }
                    break;
                case 'fk':
                    /* @var $table Model */
                    $table = $other['table'];
                    if (!$table->has_id($val)) {
                        $this->errors[] = "Vybraná položka v $label neexistuje";
                    }
                    break;
            }
        }
    }

    public function is_save_successful() {
        return $this->save_successful;
    }
}