<?php
class OrderDB extends ObjectDB{

    protected static $table = "order";

    public function __construct(){
        parent::__construct(self::$table);
        $this->add("id", "ValidateNumber");
        $this->add("phone", "ValidateText", null, "");
        $this->add("name_first", "ValidateText", null, "");
        $this->add("name_second", "ValidateText", null, "");
        $this->add("email", "ValidateNoEmail", null, "");
        $this->add("add_date", "ValidateNumber", null, "");
        $this->add("add_date_f", "ValidateDate", null, date("Y-m-d", (time() + 60*60*2)));
        $this->add("status_kc", "ValidateNumber", null, "");
        $this->add("ip", "ValidateIP", null, "");
        $this->add("referrer", "ValidateText", null, "");
        $this->add("foreign_key", "ValidateText", null, "");
        $this->add("web_key", "ValidateText", null, "");
        $this->add("ftd", "ValidateText", null, "");
    }

    public function getOrderListForPeriod($start=false, $end = false,  $flag=false){
        $select = new Select();
        $select->from(self::$table, "*");//todo поля!!!
        if($start) $select->where("`add_date_f` >= ".self::$db->getSQ(), array($start));
        if($end) $select->where("`add_date_f` <= ".self::$db->getSQ(), array($end));
        $select->order("foreign_key", false);
        $data = self::$db->select($select);
        if(!$data) return array();
        if(!$flag) $data = ObjectDB::buildMultiple(__CLASS__, $data);
        return $data;
    }

    public function getOrderFromForeignID($foreign_id){
        $select = new Select();
        $select->from(self::$table, "*");
        $select->where("`foreign_key` = ".self::$db->getSQ(), array($foreign_id));
        $data = self::$db->select($select);
        if(!$data) return array();
        else $data = ObjectDB::buildMultiple(__CLASS__, $data);
        return $data;
    }
    public function setFtd($value)
    {
        $this->ftd = $value;
        return $this;
    }
    public function setStatus($value)
    {
        $this->status_kc = $value;
        return $this;
    }


}