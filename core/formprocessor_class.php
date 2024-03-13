<?php

class FormProcessor {
	
	private $request;
	private $message;
	
	public function __construct($request, $message) {
		$this->request = $request;
		$this->message = $message;
	}
	
	public function process($message_name, $obj, $fields, $checks = array(), $success_message = false) {
		try {
			if (is_null($this->checks($message_name, $checks))) return null;
			foreach ($fields as $field) {
				if (is_array($field)) {
					$f = $field[0];
					$v = $field[1];
					if (strpos($f, "()") !== false) {
						$f = str_replace("()", "", $f);
                        $obj->$f($v);
                    }
					else $obj->$f = $v;
				}
				else $obj->$field = $this->request->$field;
			}
			if ($obj->save()) {
				if ($success_message) $this->setSessionMessage($message_name, $success_message);
				return $obj;
			}
		} catch (Exception $e) {
			$this->setSessionMessage($message_name, $this->getError($e));
			return null;
		}
	}
	
	public function checks($message_name, $checks) {
		try {
			for ($i =0; $i < count($checks); $i++) {
				$equal = isset($checks[$i][3])? $checks[$i][3]: true;
				if ($equal && ($checks[$i][0] != $checks[$i][1])) throw new Exception($checks[$i][2]);
				elseif (!$equal && ($checks[$i][0] == $checks[$i][1])) throw new Exception($checks[$i][2]);
			}
			return true;
		} catch (Exception $e) {
			$this->setSessionMessage($message_name, $this->getError($e));
			return null;
		}
	}

	public function setSessionMessage($to, $message) {
		if (!session_id()) session_start();
        setcookie("message[".$to."]",  $message);
        $_SESSION["message"] = array($to => $message);
	}

	public function resetForm($message_name, $error = false, $adress = false, $no_error = false){
		if(!$no_error){
			if(!$error) $error = "UNKNOWN_ERROR";
			$this->setSessionMessage($message_name, $error);
		}
		if($adress) header("Location:".$adress);
		else header("Location:".URL::deleteAllGet(URL::current()));
		exit();			
	}
	
	private function getError($e) {
		if ($e instanceof ValidatorException) {
			$error = current($e->getErrors());
			return $error[0];
		}
		elseif (($message = $e->getMessage())) return $message;
		return "UNKNOWN_ERROR";
	}
	
}
