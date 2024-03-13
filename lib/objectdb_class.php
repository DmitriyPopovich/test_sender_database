<?php

abstract class ObjectDB extends AbstractObjectDB{

	public function __construct($table){
		parent::__construct($table, Config::FORMAT_DATE);
		
	}
	public function preEdit($field, $value){
		return true;
	}
	
	public function postEdit($field, $value){
		return true;
	}
}

