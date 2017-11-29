<?php

class Model {
    protected $columns = [];
    protected $primary_key;
    protected $table_name;
    protected $save_changes = false;
    protected $changed_fields = [];
    protected $is_new = true;

    public function select($sql) {
        $q = DB::prepare($sql);
        $q->setFetchMode(PDO::FETCH_INTO, $this);
        return $q;
    }

    public static function get_by_id($id) {
        $out = new static();
        if ($id == 'new')
            return $out;
        $sql = 'SELECT `' . implode('`, `', $out->columns) . '`';
        $sql .= " FROM `{$out->table_name}` WHERE `{$out->primary_key}` = ?";
        $q = $out->select($sql);
        $q->bindValue(1, $id, PDO::PARAM_INT);
        $q->execute();
        if (!$q->rowCount())
            throw new NoEntryException();
        $q->fetch();
        return $out;
    }

    public function has_id($id) {
        $sql = 'SELECT `' . $this->primary_key . '`';
        $sql .= " FROM `{$this->table_name}` WHERE `{$this->primary_key}` = ?";
        $q = $this->select($sql);
        $q->bindValue(1, $id, PDO::PARAM_INT);
        $q->execute();
        return $q->rowCount();
    }

    public function get_primary_key() {
        return $this->primary_key;
    }

    public function get_id() {
        $pk = $this->primary_key;
        if (is_array($pk))
            $pk = $pk[0];
        return $this->$pk;
    }

    public function begin_update() {
        $this->save_changes = true;
        $this->changed_fields = [];
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
        if (!is_array($this->primary_key))
            return !$this->get_id();
        return $this->is_new;
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
            $sql .= " WHERE `{$this->primary_key}` = :_pk";
            $this->changed_fields['_pk'] = $this->get_id();
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
            $this->is_new = false;
        }
        $q = DB::prepare($sql);
        $q->execute($this->changed_fields);
        if (!$this->get_id() and !is_array($this->primary_key)) {
            $pk = $this->primary_key;
            $this->$pk = DB::lastInsertId();
        }
        //var_dump($q->errorInfo());
        $this->changed_fields = [];
        $this->save_changes = false;
        if ($q->errorInfo()[1] == 23000) {
            preg_match("/^.+'(.*)'[^']+'(.+)'$/", $q->errorInfo()[2], $rep);
            return $rep;
        }
        return $q->errorInfo()[1] == 0;
    }

    public function delete() {
        $sql = "DELETE FROM `{$this->table_name}`
                WHERE `{$this->primary_key}` = ?";
        $q = $this->select($sql);
        $q->bindValue(1, $this->get_id(), PDO::PARAM_INT);
        $q->execute();
        return $q->rowCount();
    }
}