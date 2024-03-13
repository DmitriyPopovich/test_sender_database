<?php

class URL{

    public static function get($action, $controller = "", $data = array(), $amp=true, $adress="", $handler=true){
        if($amp) $amp = "&amp;";
        else $amp = "&";
        if($controller) $uri = "/$controller/$action";
        else $uri = "/$action";
        if(count($data) != 0){
            $uri .= "?";
            foreach($data as $key=>$value){
                $uri .= "$key=$value".$amp;
            }
            $uri = substr($uri, 0, -strlen($amp));
        }
        if($handler) return self::postHandler($uri, $adress);
        return self::getAbsolute($adress, $uri);
    }
    public static function getAbsolute($adress, $uri){
        return $adress.$uri;
    }
    public static function current($adress = "", $amp=false){
        $url = self::getAbsolute($adress, $_SERVER["REQUEST_URI"]);
        if($amp) $url = str_replace("&", "&amp;", $url);           //возможно нужен цикл
        return $url;
    }
    public static function getControllerAndAction() {
        $uri = $_SERVER["REQUEST_URI"];
        $adress = Config::ADDRESS;
        $res_substr = false;
        $uri = UseSEf::getRequest($adress, $uri);
        if (!$uri && !$res_substr) return array("Main", "404");
        list($url_part, $qs_part) = array_pad(explode("?", $uri), 2, "");
        parse_str($qs_part, $qs_vars);
        Request::addSEFData($qs_vars);
        if($res_substr){
            $controller_name = "Main".$res_substr;
            if(!$uri) $action_name = "404";
            else $action_name = "Index";
        }
        else{
            $controller_name = "Main";
            $action_name = "Index";
        }
        if(($pos=strpos($uri, "?")) !== false) $uri = substr($uri, 0, $pos);
        $routes = explode("/", $uri);
        if(!empty($routes[2])){
            if(!empty($routes[1])) $controller_name = $routes[1];
            $action_name = $routes[2];
        }
        elseif(!empty($routes[1])) $action_name = $routes[1];
        return array($controller_name, $action_name);
    }
    public static function deleteAllGet($url){
        $url_len = strpos($url, "?");
        if($url_len){
            $url = substr($url, 0, $url_len);
            return $url;
        }
        return $url;
    }
    private static function postHandler($uri, $address = "") {
        $uri = UseSEF::replaceSEF($uri, $address);
        return $uri;
    }
    public static function deleteGET($url, $name, $amp=true){
        $url = str_replace("&amp;", "&", $url);
        list($url_part, $qs_part) = array_pad(explode("?", $url), 2, "");
        parse_str($qs_part, $qs_vars);
        unset($qs_vars[$name]);
        if (count($qs_vars) != 0) {
            $url = $url_part."?".http_build_query($qs_vars);
            if ($amp) $url = str_replace("&", "&amp;", $url);
        }
        else $url = $url_part;
        return self::postHandler($url);
    }
}
