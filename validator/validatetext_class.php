<?php

class ValidateText extends Validator{
	
	const MAX_LEN = 5000;
	const MIN_LEN = 0;
	const CODE_EMPTY = "ERROR_TEXT_EMPTY";
	const CODE_INVALID = "ERROR_TEXT_INVALID";
	const CODE_MAX_LEN = "ERROR_TEXT_MAX_LEN";
	const CODE_MIN_LEN = "ERROR_TEXT_MIN_LEN";
		
	protected function validate(){
		$data = $this->data;
		if(mb_strlen($data) == 0) return false;
		else{
			if(mb_strlen($data) > self::MAX_LEN)  $this->setError(self::CODE_MAX_LEN);
			elseif(mb_strlen($data) < self::MIN_LEN)  $this->setError(self::CODE_MIN_LEN);
		}
	}
}