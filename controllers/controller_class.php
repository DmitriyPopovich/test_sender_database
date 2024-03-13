<?php

class Controller extends AbstractController{
	
	protected $title;
	protected $meta_desc;
	protected $meta_key;
	protected $url_active;
	protected $action;

	public function __construct(){
		parent::__construct(new View(Config::DIR_TMPL), new Message(Config::FILE_MESSAGES));
		$this->url_active = URL::deleteGet(URL::current(Config::ADDRESS), "page");
	}
    public function action404(){
		header("HTTP/1.1 404 Not Found");
		header("Status: 404 Not Found");
		$this->title = "Страница не найдена - 404";
		$this->meta_desc = "Запрошенная страница не существует";
		$this->meta_key = "страница не найдена, страница не существует, 404";
		$pm = new PageMessage();
		$pm->header = "Страница не найдена";
		$pm->text = "Запрашиваемая страница не существует, проверьте правильность ввода адреса.";
        $this->render($pm);
		exit();
	}
	protected function accessDenied(){
		$this->title = "Доступ закрыт!";
		$this->meta_desc = "Доступ к данной странице закрыт";
		$this->meta_key = "досуп закрыт, досуп закрыт страница, досуп закрыт страница 403";
		$pm = new PageMessage();
		$pm->header = "Доступ закрыт!";
		$pm->text = "У Вас нет доступа к данной странице.";
		$this->render($pm);
		exit();
	}
    protected function getHeader(){
        $header = new Header();
        $header->title = $this->title;
        $header->meta("Content-Type", "text/html; charset=utf8mb4", true);
        $header->meta("description", $this->meta_desc, false);
        $header->meta("keywords", $this->meta_key, false);
        $header->meta("viewport", "width=device-width, initial-scale=1.0", false);
        $header->favicon = "/favicon.ico";
        $header->css = array("/styles/bootstrap.css", "/styles/bootstrap-datepicker.css", "/styles/main.css");
        $header->js = array("/js/jquery-3.6.0.min.js","/js/bootstrap.js", "/js/bootstrap-datepicker.min.js", "/js/bootstrap-datepicker.ru.min.js", "/js/helper.js", );
        return $header;
    }
	final protected function render($str=false, $string = false){
	    $params = array();
        $params["header"] = $this->getHeader();
        $params["top"] = $this->getTop();
        $params["center"] = $str;
		$this->view->render(Config::LAYOUT, $params);
	}
    protected function parseMessageError($error){
	    $pos_error = strpos($error, "ERROR");
	    if($pos_error === false) return "success";
	    else return  "danger";
	}
	protected function getTop(){
        $items = MenuDB::getMainMenu();
		$topmenu = new TopMenu();
		$topmenu->uri = $this->url_active;
        $topmenu->cart_link = URL::get("cart");
        $topmenu->visible = true;
        $topmenu->items = $items;
        return $topmenu;
	}
}