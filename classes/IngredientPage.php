<?php

/**
 * Class IngredientPage
 * Přidání / změna ingredience
 */
class IngredientPage extends FormPage
{
    /** @var Ingredience */
    protected $item;

    protected $entity_name = 'ingredienci';
    protected $entity_name_plural = 'ingrediencí';


    protected function get_data()
    {
        return [
            ['ingredience_name', 'Název ingredience', 'text',
                ['required' => true]],
            ['unit', 'Jednotka', 'text', ['required' => true]],
        ];
    }

    protected function get_links()
    {
        return ["<a href='manage/ingredients'>Zpět na seznam ingrediencí</a>"];
    }

    /**
     * @param $id int ID záznamu
     * @return Ingredience
     * @throws NoEntryException
     */
    protected function get_item($id)
    {
        return Ingredience::get_by_id($id);
    }

    protected function to_string()
    {
        if ($this->is_new)
            return "Nová ingredience";
        return $this->item->ingredience_name;
    }

    public function validate()
    {
        return [];
    }

    public function print_content()
    {
        if ($this->is_new) {
            parent::print_content();
            return;
        }
        echo '<div class="container">';
        echo '<div class="column_75 left">';
        $this->print_form();
        echo '</div>';
        echo '<div class="column_25 left">';
        echo "<h4>Jídla s touto ingrediencí</h4>";
        $i = new Item();
        $q = $i->select('SELECT item_id, item_name, amount FROM items
                         NATURAL JOIN ingredients_in_items
                         WHERE ingredience_id = ?');
        $q->execute([$this->item->ingredience_id]);
        if (!$q->rowCount())
            echo "<i>Žádné jídlo neobsahuje tuto ingredienci.</i>";
        else {
            echo "<ul>";
            while ($q->fetch()) {
                $amount = str_replace('.', ',', $i->amount);
                $amount = preg_replace('/,?0+$/', '', $amount);
                echo "<li><a href='{$i->get_edit_url()}'>{$i->item_name} "
                    . "($amount {$this->item->unit})</a></li>";
            }
            echo '</ul>';
        }
        echo '</div>';
        echo '</div>';
        echo "<div class='clear'></div>";
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
        return $position_id >= Utils::$PRIV_BOSS;
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
        Utils::redirect('manage/ingredients');
        return false;
    }
}