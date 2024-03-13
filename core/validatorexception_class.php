<?php

class ValidatorException extends Exception{
	
	private $errors;
	
	public function __construct($errors){
		parent::__construct();
		$this->errors = $errors;
	}
	
	public function getErrors(){
		print_r($this->errors);
		exit;
		return $this->errors;
	}
	
}
