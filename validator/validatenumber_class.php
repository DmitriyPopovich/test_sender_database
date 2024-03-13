<?php

class ValidateNumber extends Validator{

    const CODE_NUMBER = "VALIDATE_NUMBER";

	protected function validate(){
        $data = $this->data;
        if(is_array($data)) return;
        $data = (int) $data;
        $data = abs($data);
        if($data === 0) return;
		$pattern = "/^[0-9]+$/";
		if(!preg_match($pattern, $data)) $this->setError(self::CODE_NUMBER);
	}
}

