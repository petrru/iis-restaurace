<?php

abstract class FormPage extends Page
{
    /** @var Form Formulář */
    protected $form;

    /** @var bool Jde o nový záznam */
    protected $is_new;

    /** @var Model */
    protected $item;

    protected $entity_name = 'položku';
    protected $entity_name_plural = 'položek';

    /**
     * Vrátí seznam prvků formuláře
     * Jeden prvek se skládá z pole obsahujícího:
     * [0] .. Název sloupce v databázi
     * [1] .. Název pole pro uživatele
     * [2] .. Typ pole (password, text, email, number, birth_number, bank, fk,
     *                  readonly)
     * [3] .. Pole s volitelnými údaji:
     *        - 'other' => Vytisknout text za polem
     *        - 'required' => Povinná položka
     *        - 'placeholder' => Šedivá výchozí hodnota
     *        - 'do-not-load' => Nenačte aktuální hodnotu z databáze
     *        - 'do-not-save' => Neuloží změněnou hodnotu do databáze
     *        - 'second-password' => Příznak pro kontrolní pole hesla
     *        - 'table' => Název tabulky (při typu fk = foreign key)
     *        - 'sql' => SELECT dotaz na získání hodnot z cizí tabulky
     *                   (při typu fk = foreign key)
     *        - 'display' => Název sloupce z dotazu, který má být zobrazen
     *                   (při typu fk = foreign key)
     *        - 'code', 'prefix' => Vyplněné údaje při typu bank = číslo účtu
     * @return array
     */
    protected abstract function get_data();

    /**
     * Seznam odkazů na konci stránky
     * @return array
     */
    protected abstract function get_links();

    /**
     * Vrátí URL pro smazání tohoto záznamu
     * @return string
     */
    protected function get_delete_url()
    {
        return $this->item->get_delete_url();
    }

    /**
     * Najde záznam podle ID záznamu
     *
     * @throws NoEntryException
     * @param $id int ID záznamu
     * @return Item
     */
    protected abstract function get_item($id);

    /**
     * Vypíše tento záznam jako řetězec (používá se část titulku)
     * @return string
     */
    protected abstract function to_string();

    /**
     * Ověří správnost zadaných dat
     * @return array Seznam chyb, které nastaly
     */
    public abstract function validate();

    /**
     * Inicializuje formulář a uloží případné změny
     * @throws NoEntryException
     */
    public function init()
    {
        $this->item = $this->get_item(explode("/", $this->url)[2]);
        $this->is_new = !$this->item->get_id();
        $this->form = new Form($this->item, $this->get_data(),
                               [$this, 'validate']);
    }

    /**
     * Vypíše obsah stránky
     */
    public function print_content()
    {
        echo '<div class="container">';
        $this->print_form();
        echo '</div>';
    }

    /**
     * Vypíše formulář
     */
    protected function print_form() {
        echo '<h3>';
        echo $this->is_new ? 'Přidat' : 'Upravit';
        echo " {$this->entity_name}</h3>";
        $this->form->print_form();
        echo "<ul>";
        if (!$this->is_new) {
            echo "<li><a href='{$this->get_delete_url()}'"
                . " class='confirm'>Odstranit {$this->entity_name} "
                . $this->to_string() . "</a></li>";
        }
        echo "<li>" . implode("</li><li>", $this->get_links()) . "</li>";
        echo "</ul>";
    }

    /**
     * Vrátí titulek stránky
     * @return string
     */
    public function get_title()
    {
        return $this->to_string() . " - Správa {$this->entity_name_plural}";
    }

}