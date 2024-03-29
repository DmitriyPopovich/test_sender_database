<?php

abstract class AbstractController{
	
	protected $view;
	protected $request;
	protected $fp = null;
	protected $jsv = null;

	public function __construct($view, $message){
		if(!session_id()) session_start();
		$this->view = $view;
		$this->request = new Request();
		$this->fp = new FormProcessor($this->request, $message);
		if(!$this->access()){
			$this->accessDenied();
			throw new Exception("ACCESS_DENIED");
		}	
	}
	abstract protected function render($str);
	abstract protected function accessDenied();
	abstract protected function action404();
	protected function access(){
		return true;
	}
	final protected function notFound(){
		$this->action404();
	}
	final protected function redirect($url){
		header("Location: $url");
		exit;
	}
	final protected function renderData($modules, $layout, $params = array()){
        if(!is_array($modules)) return false;
        foreach($modules as $key=>$value){
            $params[$key] = $value;
        }
        return $this->view->render($layout, $params, true);
	}
}

