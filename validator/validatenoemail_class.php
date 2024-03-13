<?php

class ValidateNoEmail extends Validator{
	
	const MAX_LEN = 100;
	const CODE_INVALID = "ERROR_EMAIL_INVALID";
	const CODE_MAX_LEN = "ERROR_EMAIL_MAX_LEN";
	
	protected function validate(){
		$data = $this->data;
		if(mb_strlen($data) != 0){
			if(mb_strlen($data) > self::MAX_LEN) $this->setError(self::CODE_MAX_LEN);
			else{
				$pattern = "/^[a-z0-9_][a-z0-9\._-]*@([a-z0-9]+([a-z0-9-]*[a-z0-9]+)*\.)+[a-z]+$/i";
				if(!preg_match($pattern, $data)) $this->setError(self::CODE_INVALID);
			}
		}
	}
}