<?php

class MenuDB extends ObjectDB{
	
	protected static $table = "menu";
	
	public function __construct(){
		parent::__construct(self::$table);
		$this->add("id", "ValidateNumber");
		$this->add("type", "ValidateNumber");
		$this->add("name", "ValidateTitle");
		$this->add("link", "ValidateURL");
	}
    public static function getMainMenu(){
        return ObjectDB::getAllOnField(self::$table, __CLASS__, "type", TOPMENU, "id");
    }
}