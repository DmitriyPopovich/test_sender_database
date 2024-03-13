<?php

abstract class Module extends AbstractModule {
	
	public function __construct($view = false) {
		if($view) parent::__construct($view);
		else parent::__construct(new View(Config::DIR_TMPL));
	}
	
}