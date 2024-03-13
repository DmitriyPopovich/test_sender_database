<?php

class MainController extends Controller{

    public function actionIndex(){
        if ($this->request->auth) {
            self::sendLead();
            exit;
        }
        $this->title = "Главная";
        $this->meta_desc = "Главная";
        $this->meta_key = "главная";
        $this->action = URL::current();
        $table_data = $this->renderData(array(), "leadform");
        $this->render($table_data);
        exit;
    }
    public function actionLeads(){
        $this->title = "Лиды";
        $this->meta_desc = "Лиды";
        $this->meta_key = "Лиды";
        $this->action = URL::current();
        $search_data = self::getSearchData();
        $order = new OrderDB();
        $order_data = $order->getOrderListForPeriod($search_data['search']['start'], $search_data['search']['end'], true);
        $search_data['data'] = $order_data;
        $table_data = $this->renderData($search_data, "leads");
        $this->render($table_data);
        exit;
    }
    public function actionUpdate(){
        $redirect_url = URL::get("leads", "", array('start'=>$this->request->start, 'end'=>$this->request->end),true, Config::ADDRESS, true);
        self::getStatuses();
        $this->redirect($redirect_url);
        exit();
    }
    private function getStatuses() {
        $p1 = new PartnerOne();
        return $p1->getStatusesPartnerOne($this->request);
    }
    private function getSearchData(){
        $start = $this->request->start;
        $end = $this->request->end;
        if(!$start) $start = date('Y-m-d',strtotime('-7 days'));
        else self::resetPage($start, "/\d{4}-\d{2}-\d{2}/", true, true);
        if(!$end) $end = date('Y-m-d',time());
        else self::resetPage($end, "/\d{4}-\d{2}-\d{2}/", true, true);
        $search = array('start'=>$start, 'end'=>$end);
        $url_links = [];
        $url_links["link_get_collection"] = URL::get("leads", "", array('start'=>$start, 'end'=>$end),true, Config::ADDRESS, true);
        $url_links["link_update"] = URL::get("update", "", array('start'=>$start, 'end'=>$end),true, Config::ADDRESS, true);
        return array("url_links"=>$url_links, "search"=>$search);
    }
    private function sendLead() {
        $p1 = new PartnerOne();
        $data_success = $p1->sendDataPartnerOne($this->request);
        if($data_success[0]){
            $order_obj = new OrderDB();
            $res_order = $this->fp->process("", $order_obj, $data_success[1], array());
            if($res_order) $this->redirect('/leads.html');
        }
        $this->redirect(URL::current());
        //todo failure message
    }
    private function resetPage($value, $compare, $flag_no = false, $flag_regular = false, $url_reset = false)
    {
        $first_rule = isset($value);
        if ($flag_regular) $second_rule = preg_match($compare, $value);
        else $second_rule = ($value == $compare);
        if ($flag_no) $second_rule = !$second_rule;
        if (!$url_reset) $url_reset = URL::deleteAllGet(URL::current());
        if ($first_rule && $second_rule) $this->format->reset($url_reset);
    }
}
