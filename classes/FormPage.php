<?php

abstract class FormPage extends Page
{
    /** @var Form */
    protected $form;

    /** @var bool */
    protected $is_new;

    /** @var Model */
    protected $item;

    protected $entity_name = 'položku';
    protected $entity_name_plural = 'položek';


    protected abstract function get_data();

    protected abstract function get_links();

    protected abstract function get_delete_url();

    protected abstract function get_item($id);

    protected abstract function to_string();

    public abstract function validate();


    public function init()
    {
        $this->item = $this->get_item(explode("/", $this->url)[2]);
        $this->is_new = !$this->item->get_id();
        $this->form = new Form($this->item, $this->get_data(),
                               [$this, 'validate']);
    }

    public function print_content()
    {
        echo '<div class="container">';
        $this->print_form();
        echo '</div>';
    }

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

    public function get_title()
    {
        return $this->to_string() . " - Správa {$this->entity_name_plural}";
    }

}