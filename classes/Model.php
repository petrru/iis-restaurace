<?php

class Model {
    protected $columns = [];
    protected $primary_key;
    protected $table_name;
    protected $save_changes = false;
    protected $changed_fields = [];
    protected $is_new = true;
    protected $before_change_id;

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

    public function get_primary_key() {
        return $this->primary_key;
    }

    public function get_primary_key_arr() {
        if (!is_array($this->primary_key))
            return [$this->primary_key];
        return $this->primary_key;
    }

    public function get_id() {
        $pk = $this->primary_key;
        if (is_array($pk))
            $pk = $pk[0];
        return $this->$pk;
    }

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

    public function __set($name, $value) {
        if ($this->save_changes && in_array($name, $this->columns)) {
            $this->changed_fields[$name] = $value;
        }
        $this->$name = $value;
    }

    public function __get($name) {
        return $this->$name;
    }

    public function is_new() {
        return $this->is_new;
    }

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

    public function delete() {
        if (!$this->save_changes)
            $this->begin_update();
        $arr = [];
        $sql = "DELETE FROM `{$this->table_name}`";
        $sql .= $this->where_clause($arr);
        $q = $this->select($sql);
        $q->execute($arr);
        return $q->rowCount();
    }

    public function get_edit_url() {
        return 'manage/' . $this->table_name .'/' . $this->get_id();
    }

    public function get_delete_url() {
        return 'manage/' . $this->table_name . '/' . $this->get_id() . '/delete';
    }
}