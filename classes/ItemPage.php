<?php

class ItemPage extends FormPage
{
    protected $extra_script = 'item.js';

    /** @var Item */
    protected $item;

    protected $entity_name = 'jídlo';
    protected $entity_name_plural = 'menu';


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
        echo '<div class="container">';
        echo '<div class="column_80 left">';
        $this->print_form();
        echo '</div>';
        echo '<div class="column_20 left">';
        echo "<h4>Ingredience</h4>";
        $this->print_ingredience();
        echo '</div>';
        echo '</div>';
        echo "<div class='clear'></div>";
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
              .  "<input type='submit' value='Uložit'></form>";
        Utils::print_modal('edit-ingredient', 'Změna ingredience', $body);
    }

    private function print_ingredience() {
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
                ."<span class='amount'>$amount</span> {$in->unit} "
                ."<span class='name'>{$in->ingredience_name}</span>"
                ."<i class='material-icons edit' title='Editovat'>mode_edit</i>"
                ."</div>";
        }
        echo "</div>";
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
     * @param $id ID položky
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
            Utils::set_success_message('Záznam byl úspěšně uložen.');
        }
        else {
            Utils::set_success_message('Záznam byl úspěšně upraven.');
        }
        Utils::redirect('manage/menu');
        return false;
    }
}