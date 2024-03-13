<?php

set_time_limit(0);

class PartnerOne{

    protected static $config = [
        'box_id'          => 28,
        'offer_id'        => 3,
        'countryCode'     => 'RU',
        'language'        => 'ru',
        'password'        => 'qwerty12',
        'url_send'        => 'https://crm.belmar.pro/api/v1/addlead',
        'url_get'         => 'https://crm.belmar.pro/api/v1/getstatuses',
        'token'           => 'ba67df6a-a17c-476f-8e95-bcdb75ed3958',
    ];

    private function transform_data($data_input, $response_data){
        $data = array(array("name_first", $data_input['firstName']));
        $data[] = array("name_second", $data_input['lastName']);
        $data[] = array("phone", $data_input['phone']);
        $data[] = array("email", $data_input['email']);
        $data[] = array("referrer", $data_input['landingUrl']);
        $data[] = array("ip", $data_input['ip']);
        $data[] = array("foreign_key", $response_data['id']);
        $data[] = array("web_key", $data_input['clickId']);
        return $data;
    }
    private function sendCurlPartnerOne($data, $flag=false){
        if(!$flag) $url = self::$config['url_get'];
        else $url = self::$config['url_send'];
        $data_send = json_encode($data);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data_send,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'token: '.self::$config['token']
            ),
        ));
        $response = curl_exec($curl);
        $response_data = json_decode($response, true);
        curl_close($curl);
        return $response_data;
    }
    public function sendDataPartnerOne($request){
        $utils = new Utils();
        $user_ip = $utils->getRealIpAddr();
        $referrer = $utils->getRefferer();
        $random_key = $utils->generateRandomString(7);
        $data = [
            "firstName"   => $request->first_name,              // require
            "lastName"    => $request->last_name,               // require
            "phone"       => $request->phone,                   // require
            "email"       => $request->email,                   // require
            "countryCode" => self::$config['countryCode'],      // require ISO 3166 Alpha-2 (example 'CA')
            "box_id"      => self::$config['box_id'],           // require (get from your manager)
            "offer_id"    => self::$config['offer_id'],         // require (get from your manager)
            "landingUrl"  => $referrer,                         // require
            "ip"          => $user_ip,                          // require
            "password"    => self::$config['password'],
            "language"    => self::$config['language'],
            "clickId"     => $random_key,
            "quizAnswers" => "",
            "custom1"     => "",
            "custom2"     => "",
            "custom3"     => ""
        ];
        $response_data =self::sendCurlPartnerOne($data, true);
        if($response_data['status'] == "true") {
            $return_data = self::transform_data($data, $response_data);
            return [true, $return_data];
        }
        return [false, false];
    }
    public function getStatusesPartnerOne($request){
        for($i=0;;$i++){
            $data = [
                "date_from"  => $request->start,      // require
                "date_to"    => $request->end,        // require
                "page"       => $i,                   // require
                "limit"      => 100,                  // require ISO 3166 Alpha-2 (example 'CA')
            ];

            $response_data = self::sendCurlPartnerOne($data, false);
            if(!count($response_data['data'])) break;
            foreach($response_data['data'] as $currentItem){
                $order = new OrderDB();
                $order_data = $order->getOrderFromForeignID($currentItem['id']);
                if(count($order_data)){
                    $orderObject = reset($order_data);
                }
                else $orderObject = $order;
                $orderObject->foreign_key = $currentItem['id'];
                $orderObject->email = $currentItem['email'];
                $orderObject->setFtd($currentItem['ftd']);
                $orderObject->setStatus( $currentItem['status']);
                $orderObject->save();
                unset($order);
                unset($order_data);
                unset($orderObject);
            }
        }
        return true;
    }
}



