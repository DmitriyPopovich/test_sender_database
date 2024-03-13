<?php

abstract class AbstractObjectDB {
	
	const TYPE_TIMESTAMP = 1;
	const TYPE_IP = 2;
	
	private static $types = array(self::TYPE_TIMESTAMP, self::TYPE_IP);
	protected static $db = null;
	
	private $format_date = "";
	
	private $id = null;
	private $properties = array();
	
	protected $table_name = "";
	
	public function __construct($table_name, $format_date) {
		$this->table_name = $table_name;
		$this->format_date = $format_date;
	}
	
	public static function setDB($db) {
		self::$db = $db;
	}

	public function load($id) {
		$id = (int) $id;
		if ($id < 0) return false;
		$select = new Select(self::$db);
		$select = $select->from($this->table_name, $this->getSelectFields())
			->where("`id` = ".self::$db->getSQ(), array($id));
		$row = self::$db->selectRow($select);
		if (!$row) return false;
		if ($this->init($row)) return $this->postLoad();
	}
	
	public function init($row) {
        foreach ($this->properties as $key => $value) {
		    $val = $row[$key];
			switch ($value["type"]) {
				case self::TYPE_TIMESTAMP:
					if (!is_null($val)) $val = strftime($this->format_date, $val);
					break;
				case self::TYPE_IP:
					if (!is_null($val)) $val = long2ip($val);
					break;
			}
			$this->properties[$key]["value"] = $val;
		}
		$this->id = $row["id"];
		return $this->postInit();
	}
	
	public function isSaved() {
		return $this->getID() > 0;
	}
	
	public function getID() {
		return (int) $this->id;
	}
	
	public function save() {
        $update = $this->isSaved();
        if ($update) $commit = $this->preUpdate();
        else $commit = $this->preInsert();

        if (!$commit) return false;
        $row = array();

        foreach ($this->properties as $key => $value) {
            switch ($value["type"]) {
				case self::TYPE_TIMESTAMP:
                    if (!is_null($value["value"])) $value["value"] = strtotime($value["value"]);
                    break;
                case self::TYPE_IP:
                    if (!is_null($value["value"])) $value["value"] = ip2long($value["value"]);
                    break;
            }
            $row[$key] = $value["value"];
        }

        if (count($row) > 0) {
            if ($update) {

                $success = self::$db->update($this->table_name, $row, "`id` = ".self::$db->getSQ(), array($this->getID()));
                if (!$success) throw new Exception();
            }
            else {
                $this->id = self::$db->insert($this->table_name, $row);
                if (!$this->id) throw new Exception();
            }
		}
		if ($update) return $this->postUpdate();
		return $this->postInsert();
	}
	
	public function delete() {
		if (!$this->isSaved()) return false;
		if (!$this->preDelete()) return false;
		$success = self::$db->delete($this->table_name, "`id` = ".self::$db->getSQ(), array($this->getID()));
		if (!$success) throw new Exception();
		$this->id = null;
		return $this->postDelete();
	}
	
	public function __set($name, $value) {
		if (array_key_exists($name, $this->properties)) {
			$this->properties[$name]["value"] = $value;
			return true;
		}
		else $this->$name = $value;
	}
		
	public function __get($name) {
		if ($name == "id") return $this->getID();
		return array_key_exists($name, $this->properties)? $this->properties[$name]["value"]: null;
	}
	
	public static function buildMultiple($class, $data) {
		$ret = array();
		
		if (!class_exists($class)) throw new Exception();
		
		$test_obj = new $class();
		if (!$test_obj instanceof AbstractObjectDB) throw new Exception();
		foreach ($data as $row) {
			$obj = new $class();
			$obj->init($row);
			$ret[$obj->getID()] = $obj;
		}
		return $ret;
	}
	
	public static function getAll($count = false, $offset = false, $self_clinica = false) {
		$class = get_called_class();
		return self::getAllWithOrder($class::$table, $class, "id", true, $count, $offset, $self_clinica);
	}
	
	public static function getCount($clinic_id = false) {
		$class = get_called_class();
		return self::getCountOnWhere($class::$table, false, false, $clinic_id);
	}
	
	public static function getAllOnField($table_name, $class, $field, $value, $order = false, $ask = true, $count = false, $offset = false) {
		return self::getAllOnWhere($table_name, $class, "`$field` = ".self::$db->getSQ(), array($value), $order, $ask, $count, $offset);
	}
	
	protected static function getCountOnField($table_name, $field, $value) {
		return self::getCountOnWhere($table_name, "`$field` = ".self::$db->getSQ(), array($value));
	}
	
	protected static function getCountOnWhere($table_name, $where = false, $values = false, $clinic_id = false) {
		$select = new Select();
		$select->from($table_name, array("COUNT(id)"));
		if ($where) $select->where($where, $values);
		if($clinic_id) $select->selfClinic($clinic_id);
		return self::$db->selectCell($select);
	}
	
	protected static function getAllWithOrder($table_name, $class, $order = false, $ask = true, $count = false, $offset = false, $self_clinica = false) {
		return self::getAllOnWhere($table_name, $class, false, false, $order, $ask, $count, $offset, $self_clinica);
	}
	
	protected static function getAllOnWhere($table_name, $class, $where = false, $values = false, $order = false, $ask = true, $count = false, $offset = false, $self_clinica = false) {
		$select = new Select();
		$select->from($table_name, "*");
		if ($where) $select->where($where, $values);
		if ($order) $select->order($order, $ask);
		else $select->order("id");
		if ($count) $select->limit($count, $offset);
		if($self_clinica) $select->selfClinic($self_clinica);
		$data = self::$db->select($select);
		return AbstractObjectDB::buildMultiple($class, $data);
	}
	
	protected static function addSubObject($data, $class, $field_out, $field_in) {
		$ids = array();
		
		foreach ($data as $value) {
			$ids[] = self::getComplexValue($value, $field_in);
		}
		if (count($ids) == 0) return array();
		$new_data = $class::getAllOnIDs($ids);
		if (count($new_data) == 0) return $data;
		foreach ($data as $id => $value) {
			if (isset($new_data[self::getComplexValue($value, $field_in)])) $data[$id]->$field_out = $new_data[self::getComplexValue($value, $field_in)];
			else $value->$field_out = null;
		}
		return $data;
	}
	
	protected static function getComplexValue($obj, $field) {
		if (strpos($field, "->") !== false) $field = explode("->", $field);
		if (is_array($field)) {
			$value = $obj;
			foreach ($field as $f) $value = $value->{$f};
		}
		else $value = $obj->$field;
		return $value;
	}
	
	public static function getAllOnIDs($ids) {
		return self::getAllOnIDsField($ids, "id");
	}
	
	public static function getAllOnIDsField($ids, $field) {
		$class = get_called_class();
		$select = new Select();
		$select->from($class::$table, "*")
			->whereIn($field, $ids);
		$data = self::$db->select($select);
		return AbstractObjectDB::buildMultiple($class, $data);
	}
	
	protected function loadOnField($field, $value) {
		$select = new Select();
		$select->from($this->table_name, "*")
			->where ("`$field` = ".self::$db->getSQ(), array($value));
		$row = self::$db->selectRow($select);
		if ($row) {
			if ($this->init($row)) return $this->postLoad();
		}
		return false;
	}
	
	protected function add($field, $validator, $type = null, $default = null) {
		$this->properties[$field] = array("value" => $default, "validator" => $validator, "type" => in_array($type, self::$types)? $type : null);
	}
	
	protected function preInsert() {
		return $this->validate();
	}
	
	protected function postInsert() {
		return true;
	}
	
	protected function preUpdate() {
		return $this->validate();
	}
	
	protected function postUpdate() {
		return true;
	}
	
	protected function preDelete() {
		return true;
	}
	
	protected function postDelete() {
		return true;
	}
	
	protected function postInit() {
		return true;
	}
	
	protected function preValidate() {
		return true;
	}
	
	protected function postValidate() {
		return true;
	}
	
	protected function postLoad() {
		return true;
	}

	private function validate() {
		if (!$this->preValidate()) throw new Exception();
		$v = array();
		$errors = array();
		foreach ($this->properties as $key => $value) {
			$v[$key] = new $value["validator"]($value["value"]);
		}
		foreach ($v as $key => $validator) {
			if (!$validator->isValid()) $errors[$key] = $validator->getErrors();
		}
		if (count($errors) == 0) {
			if (!$this->postValidate()) throw new Exception();
			return true;
		}
		else throw new ValidatorException($errors);
	}
	
}
