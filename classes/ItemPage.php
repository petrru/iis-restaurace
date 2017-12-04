<?php

/**
 * Class ItemPage
 * Editace / přidání jídla
 */
class ItemPage extends FormPage
{
    protected $extra_script = 'item.js';

    /** @var Item */
    protected $item;

    protected $entity_name = 'jídlo';
    protected $entity_name_plural = 'menu';

    /**
     * @throws NoEntryException
     */
    public function init()
    {
        parent::init();
        $this->form->print_form_element = false;
    }


    public function get_data()
    {
        return [
            ['item_name', 'Název jídla', 'text', ['required' => true]],
            ['price', 'Cena', 'number', ['after' => 'Kč', 'required' => true]],
            ['category_id', 'Kategorie', 'fk', ['table' => new ItemCategory(),
                'sql' => 'SELECT category_id, category_name FROM item_categories
                          ORDER BY menu_order',
                'display' => 'category_name', 'required' => true]],
            ['available', 'Jídlo je v nabídce', 'checkbox', []],
        ];
    }

    public function get_menu()
    {
        return new AdminMenu('manage/menu');
    }

    public function check_privileges($position_id)
    {
        return in_array($position_id, [Utils::$PRIV_OWNER, Utils::$PRIV_BOSS]);
    }

    public function validate() {
        return [];
    }

    protected function get_links()
    {
        return ["<a href='manage/menu'>Zpět na menu</a>"];
    }

    public function print_content()
    {
        $this->form->print_form_open_element();
        echo '<div class="container">';
        echo '<div class="column_75 left">';
        $this->print_form();
        echo '</div>';
        echo '<div class="column_25 left">';
        echo "<h4>Ingredience</h4>";
        if (!$this->is_new) {
            $this->print_edit_ingredience();
        }
        else {
            $this->print_add_ingredience();
        }
        echo '</div>';
        echo '</div>';
        echo "<div class='clear'></div>";
        echo '</form>';
        $body = "<form id='in-form'>"
              . "<span class='label'>Název ingredience:</span>"
              . "<select id='in-name'>";
        $in = new Ingredience();
        $q = $in->select("SELECT * FROM ingredients ORDER BY ingredience_name");
        $q->execute();
        while ($q->fetch()) {
            $body .= "<option value='{$in->ingredience_id}' "
                  .  "data-unit='{$in->unit}'>{$in->ingredience_name}</option>";
        }
        $body .= "</select><br>"
              .  "<span class='label'>Množství:</span>"
              .  "<input type='text' id='in-amount'> "
              .  "<span id='in-unit'></span><br>"
              .  "<input type='submit' value='Uložit'> "
              .  "<input type='button' id='in-delete' value='Odstranit"
              .  " ingredienci'>"
              .  "</form>"
              .  "<div id='in-error'></div>";
        Utils::print_modal('edit-ingredient', 'Změna ingredience', $body);
    }

    /**
     * Pravý sloupec při zadání nového jídla
     */
    private function print_add_ingredience() {
        $in = new Ingredience();
        $q = $in->select("SELECT * FROM ingredients
                          ORDER BY ingredience_name");
        $q->execute();
        while ($q->fetch()) {
            $key = 'ingredience_' . $in->ingredience_id;
            echo "<div><input type='text' class='small-input' "
                ."name='$key' "
                ."value='" . ($_POST[$key] ?? '') . "'> "
                ."{$in->unit} {$in->ingredience_name}"
                ."</div>";
        }
    }

    /**
     * Pravý sloupec při editaci jídla
     */
    private function print_edit_ingredience() {
        $in = new IngredienceInItem();
        $q = $in->select("SELECT * FROM ingredients_in_items
                          NATURAL JOIN ingredients
                          WHERE item_id = ?
                          ORDER BY ingredience_name");
        $q->execute([$this->item->item_id]);
        echo "<div class='ingredients'>";
        while ($q->fetch()) {
            $amount = str_replace('.', ',', $in->amount);
            $amount = preg_replace("/,?0+$/", "", $amount);
            echo "<div data-id='{$in->ingredience_id}'>"
                ."<span class='amount'>$amount</span> "
                ."<span class='unit'>{$in->unit}</span> "
                ."<span class='name'>{$in->ingredience_name}</span>"
                ."<i class='material-icons edit' title='Editovat'>mode_edit</i>"
                ."</div>";
        }
        echo "<div class='no-items'>Žádné ingredience</div>";
        echo "</div>";
        echo "<br><a id='in-new' href='#'>"
            ."<i class='material-icons'>add</i> "
            ."Přidat ingredienci"
            ."</a>";
    }

    protected function get_delete_url()
    {
        return $this->item->get_delete_url();
    }

    protected function to_string()
    {
        if ($this->is_new)
            return "Nová položka";
        return $this->item->item_name;
    }

    /**
     * Najde položku podle ID
     * @param $id int ID položky
     * @return Item
     * @throws NoEntryException
     */
    protected function get_item($id)
    {
        return Item::get_by_id($id);
    }

    public function should_print_html() {
        if (!$this->form->is_save_successful()) {
            return true;
        }
        if ($this->is_new) {
            foreach ($_POST as $k => $v) {
                if (!preg_match("/^ingredience_([0-9]+)$/", $k, $match))
                    continue;
                if (!($v > 0))
                    continue;
                $iii = new IngredienceInItem();
                $iii->begin_update();
                $iii->ingredience_id = $match[1];
                $iii->item_id = $this->item->item_id;
                $iii->amount = str_replace(',', '.', $v);
                $iii->save();
            }
            Utils::set_success_message('Záznam byl úspěšně uložen.');
        }
        else {
            Utils::set_success_message('Záznam byl úspěšně upraven.');
        }
        Utils::redirect('manage/menu');
        return false;
    }
}