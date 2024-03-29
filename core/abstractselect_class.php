<?php

class AbstractSelect{

	private $db;
	private $from = "";
	private $where = "";
	private $order = "";
	private $limit = "";

	public function __construct($db){
		$this->db = $db;
	}

	public function from($table_name, $fields, $flag_distinct = false){
		$table_name = $this->db->getTableName($table_name);
        $from = "";
		if($flag_distinct) $from .= " DISTINCT ";
		if($fields == "*") $from .= "*";
		else{
			for($i=0; $i<count($fields); $i++){
				if(($pos_1 = strpos($fields[$i], "(")) !== false){
					$pos_2 = strpos($fields[$i], ")");
					$from .= substr($fields[$i], 0, $pos_1)."(`".substr($fields[$i], $pos_1 + 1, $pos_2 - $pos_1 -1)."`),";
				}
				else $from .= "`".$fields[$i]."`,";
			}
			$from = substr($from, 0, -1);
		}
		$from .= " FROM `$table_name`";
		$this->from = $from;

		return $this;
	}

	public function countData(){
		//echo $this->from;
		if($this->from) {
			$pos = strpos($this->from, " FROM");
			$this->from = substr($this->from, $pos);
		}
		$this->from = " COUNT(*) ".$this->from;
		return $this;
	}

	public function where($where, $values = array(), $and = true){
		if($where){
			$where = $this->db->getQuery($where, $values);
			$this->andWhere($where, $and);
		}
		return $this;
	}
	public function selfClinic($clinic_id, $and = true){
		if(!is_array($clinic_id)) $clinic_id = array($clinic_id);
		$where = $this->db->getQuery("`company_id` = {?}", $clinic_id);
		$this->andWhere($where, $and);
	}
	private function andWhere($where, $and){
		if($this->where){
			if($and) $this->where .= " AND ";
			else $this->where .= " OR ";
			$this->where .= $where;
		}
		else $this->where = "WHERE $where";
	}
	public function whereAnd($field, $values=array(), $and=true){
		$where = "(";
		foreach($values as $value){
			$where .= "$field".$this->db->getSQ()."OR";
		}
		$where = substr($where, 0, -2);
		$where .= ")";
		return $this->where($where, $values, $and);
	}

	public function whereOr($field_1, $value_1, $field_2, $value_2){
        $where = " AND (";
        $where .= $this->db->getQuery($field_1, $value_1);
        $where .= " OR ";
        $where .= $this->db->getQuery($field_2, $value_2);
        $where .= ")";
        $this->where .= $where;
    }

	public function whereIn($field, $values=array(), $and=true){
		$where = "`$field` IN (";
		foreach($values as $value){
			$where .= $this->db->getSQ().",";
		}
		$where = substr($where, 0, -1);
		$where .= ")";
		return $this->where($where, $values, $and);
	}

	public function whereFIS($col_name, $value, $and=true){
		$where = "FIND_IN_SET(".$this->db->getSQ().", `$col_name`) > 0";
		return $this->where($where, array($value), $and);
	}
	public function order($field, $ask = true){
		if(is_array($field)){
			$order = "ORDER BY ";
			if(!is_array($ask)){
				$temp = array();
				for($i=0; $i<count($field); $i++) $temp[] = $ask;
				$ask = $temp;
			}
			for($i=0; $i<count($field); $i++) {
				$this->order .= "`".$field[$i]."`";
				if(!$ask[$i]) $this->order .= " DESC,";
				else $this->order .= ",";
			}
			$this->order = substr($order, 0, -1);
		}
		else{
			$this->order = "ORDER BY `$field`";
			if(!$ask) $this->order .= " DESC";
		}
		return $this;
	}

	public function limit($count, $offset = 0){
		$count = (int) $count;
		$offset = (int) $offset;
		if($count<0 || $offset<0) return false;
		$this->limit = "LIMIT $offset, $count";
		return $this;
	}

	public function rand(){
		$this->order = "ORDER BY RAND()";
		return $this;
	}

	public function __toString(){
		if($this->from) $res = "SELECT ".$this->from." ".$this->where." ".$this->order." ".$this->limit;
		else $res = "";
		return $res;
	}
}