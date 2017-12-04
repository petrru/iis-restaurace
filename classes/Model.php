<?php

/**
 * Class Model
 * Reprezentuje tabulku v databázi
 */
class Model {
    protected $columns = [];
    protected $primary_key;
    protected $table_name;
    protected $save_changes = false;
    protected $changed_fields = [];
    protected $is_new = true;
    protected $before_change_id;

    /**
     * Připraví SELECT dotaz
     * @param $sql
     * @return PDOStatement
     */
    public function select($sql) {
        $this->is_new = false;
        $q = DB::prepare($sql);
        $q->setFetchMode(PDO::FETCH_INTO, $this);
        return $q;
    }

    /**
     * Najde záznam s příslušným ID
     * @param $id int|array ID záznamu
     * @return static
     * @throws NoEntryException
     */
    public static function get_by_id($id) {
        $out = new static();
        if ($id == 'new')
            return $out;
        $arr = [];
        $out->is_new = false;
        $sql = 'SELECT `' . implode('`, `', $out->columns) . '`';
        $sql .= " FROM `{$out->table_name}`";
        $sql .= $out->where_clause($arr, $id);
        $q = $out->select($sql);
        $q->execute($arr);
        if (!$q->rowCount())
            throw new NoEntryException();
        $q->fetch();
        return $out;
    }

    /**
     * Najde záznam s příslušným ID nebo vytvoří nový, pokud neexistuje
     * @param $id
     * @return Model|static
     */
    public static function get_by_id_or_new($id)
    {
        try {
            return self::get_by_id($id);
        } catch (NoEntryException $e) {
            return new static();
        }
    }

    /**
     * Zkontroluje, zda existuje záznam s tímto ID
     * @param int|array $id
     * @return int Počet záznamů s tímto ID (1 nebo 0)
     */
    public function has_id($id=[]) {
        $arr = [];
        $pk_list = implode('` , `', $this->get_primary_key_arr());
        $sql = "SELECT `$pk_list`";
        $sql .= " FROM `{$this->table_name}`";
        $sql .= $this->where_clause($arr, $id);
        $q = $this->select($sql);
        $q->execute($arr);
        return $q->rowCount();
    }

    /**
     * Vrátí název sloupce s PK (řetězec, pokud je jen jeden; jinak pole)
     * @return string|array
     */
    public function get_primary_key() {
        return $this->primary_key;
    }

    /**
     * Vrátí název sloupce s PK, vždy jako array
     * @return array
     */
    public function get_primary_key_arr() {
        if (!is_array($this->primary_key))
            return [$this->primary_key];
        return $this->primary_key;
    }

    /**
     * Vrátí hodnotu ID tohoto záznamu
     * Pokud PK obsahuje více sloupců, bude vrácena jen hodnota jednoho z nich
     * @return int
     */
    public function get_id() {
        $pk = $this->primary_key;
        if (is_array($pk))
            $pk = $pk[0];
        return $this->$pk;
    }

    /**
     * Zahájí aktualizaci záznamů
     */
    public function begin_update() {
        if ($this->get_id())
            $this->is_new = false;
        $this->save_changes = true;
        $this->changed_fields = [];
        $this->before_change_id = [];
        foreach ($this->get_primary_key_arr() as $value) {
            $this->before_change_id[$value] = $this->$value;
        }
    }

    /**
     * Změní hodnotu jednoho sloupce (pokud bylo předtím zavoláno
     * begin_update(), bude při save() změněna v DB)
     * @param $name string Název sloupce
     * @param $value int|string|null Nová hodnota
     */
    public function __set($name, $value) {
        if ($this->save_changes && in_array($name, $this->columns)) {
            $this->changed_fields[$name] = $value;
        }
        $this->$name = $value;
    }

    /**
     * Načte hodnotu sloupce
     * @param $name
     * @return int|string|null
     */
    public function __get($name) {
        return $this->$name;
    }

    /**
     * Jedná se o nový záznam (má být proveden INSERT místo UPDATE)?
     * @return bool
     */
    public function is_new() {
        return $this->is_new;
    }

    /**
     * Vygeneruje klauzuli WHERE pro nalezení záznamu pomocí PK.
     * @param $arr array Pole, kam budou přidány parametry dotazu
     * @param array $input Hodnoty PK (mají přednost před údaji z proměnných
     *                     objektu, pokud jsou zadány)
     * @return string Část SQL dotazu
     */
    private function where_clause(&$arr, $input=[]) {
        $first = true;
        $out = ' WHERE ';
        $pk = $this->get_primary_key_arr();
        if (!is_array($input))
            $input = [$input];
        foreach ($pk as $i => $value) {
            if ($first)
                $first = false;
            else
                $out .= ' AND ';
            $out .= "`$value` = :_old_$value";
            if (count($input) > $i)
                $arr['_old_' . $value] = $input[$i];
            else
                $arr['_old_' . $value] = $this->before_change_id[$value];
        }
        return $out;
    }

    /**
     * Uloží provedené změny (INSERT INTO nebo UPDATE)
     * @return bool True, pokud operace proběhla úspěšně
     */
    public function save() {
        $first = true;
        if (!$this->is_new()) {
            // Existuje ID --> UPDATE
            $sql = "UPDATE `$this->table_name` SET ";
            foreach ($this->changed_fields as $key => $val) {
                if ($first) {
                    $first = false;
                }
                else {
                    $sql .= ", ";
                }
                $sql .= "`$key` = :$key";
            }
            $sql .= $this->where_clause($this->changed_fields);
        }
        else {
            // Není ID --> INSERT INTO
            $sql = "INSERT INTO `$this->table_name` (";
            $values = ") VALUES (";
            foreach ($this->changed_fields as $key => $val) {
                if ($first) {
                    $first = false;
                }
                else {
                    $sql .= ", ";
                    $values .= ", ";
                }
                $sql .= "`$key`";
                $values .= ":$key";
            }
            $sql .= $values . ")";
        }
        $q = DB::prepare($sql);
        $q->execute($this->changed_fields);
        //echo $sql;
        //var_dump($q->errorInfo());
        $this->changed_fields = [];
        $this->save_changes = false;
        if ($this->is_new() and !is_array($this->primary_key)) {
            $pk = $this->primary_key;
            $this->$pk = DB::lastInsertId();
        }
        $this->is_new = false;
        if ($q->errorInfo()[1] == 1062) {
            preg_match("/^.+'(.*)'[^']+'(.+)'$/", $q->errorInfo()[2], $rep);
            return $rep;
        }
        return $q->errorInfo()[1] == 0;
    }

    /**
     * Smaže odpovídající řádek z tabulky
     * @return int Počet smazaných řádků (0 nebo 1)
     */
    public function delete() {
        if ($this->is_new)
            return 0;
        if (!$this->save_changes)
            $this->begin_update();
        $arr = [];
        $sql = "DELETE FROM `{$this->table_name}`";
        $sql .= $this->where_clause($arr);
        $q = $this->select($sql);
        $q->execute($arr);
        return $q->rowCount();
    }

    /**
     * @return string URL pro editaci entity
     */
    public function get_edit_url() {
        return 'manage/' . $this->table_name .'/' . $this->get_id();
    }

    /**
     * @return string URL pro smazání entity
     */
    public function get_delete_url() {
        return 'manage/' . $this->table_name . '/' . $this->get_id() . '/delete';
    }
}