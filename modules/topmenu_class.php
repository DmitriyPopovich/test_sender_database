<?php

class TopMenu extends Module{
	
	public function __construct($company_name = false){
		parent::__construct();
        if(!$company_name) $company = Config::COMPANY_NAME;
        else $company = $company_name;
		$this->add("uri");
		$this->add("items", null, true);
		$this->add("visible", false);
		$this->add("cart_link", false);
		$this->add("menu_name", $company);
	}
    public function preRender() {
        $this->add("childrens", null, true);
        $this->add("active", null, true);
        $childrens = array();
        foreach ($this->items as $item) {
            if ($item->parent_id) {
                $childrens[$item->id] = $item->parent_id;
            }
        }
        $this->childrens = $childrens;
        $active = array();
        foreach ($this->items as $item) {
            if ($item->link == $this->uri) {
                $active[] = $item->id;
                if ($item->parent_id) {
                    $parent_id = $item->parent_id;
                    $active[] = $parent_id;
                    while ($parent_id) {
                        $parent_id = $this->items[$parent_id]->parent_id;
                        if ($parent_id) $active[] = $parent_id;
                    }
                }
            }
        }
        $this->active = $active;
    }


	public function getTmplFile(){
		return "topmenu";
	}
}