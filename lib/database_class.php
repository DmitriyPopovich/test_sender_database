<?php

class Database extends AbstractDatabase{
	
	private static $db;
	
	public static function getDBO(){
		if(self::$db == null){
            return self::$db = new Database(Config::DB_HOST, Config::DB_USER, Config::DB_PASSWORD, Config::DB_NAME, Config::DB_SYM_QUERY, Config::DB_PREFIX);
        }
		return self::$db;
	}
	
}
