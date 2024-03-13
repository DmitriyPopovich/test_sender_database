<?php

	mb_internal_encoding("UTF-8");
	error_reporting(E_ALL);
	ini_set('session.gc_maxlifetime', 2592000);
	ini_set('session.cookie_lifetime', 2592000);

	$path = "core".PATH_SEPARATOR."lib".PATH_SEPARATOR."objects".PATH_SEPARATOR."validator".PATH_SEPARATOR."controllers".PATH_SEPARATOR."modules";
    $path .= PATH_SEPARATOR."request_integration";

	set_include_path(get_include_path().PATH_SEPARATOR.$path);
	spl_autoload_extensions("_class.php");
	spl_autoload_register();

	define("TOPMENU", 1);
	AbstractObjectDB::setDB(DataBase::getDBO());



	
