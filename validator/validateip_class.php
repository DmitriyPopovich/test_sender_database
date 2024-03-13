<?php

class ValidateIP extends Validator {

    protected function validate() {
        $data = $this->data;
        if ($data == 0) return;
        if (!filter_var($data, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6)) {
            $this->setError(self::CODE_UNKNOWN);
        }
    }
}