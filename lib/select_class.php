<?php

class Select extends AbstractSelect{
	
	public function __construct(){
		parent::__construct(Database::getDBO());
	}
}
