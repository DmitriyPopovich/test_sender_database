<?php

abstract class Config{

    const COMPANY_NAME = "LeadSender";
    const ADDRESS = "http://localhost:8080";
//    const ADDRESS = "http://slonking.fun";

    const DB_HOST = "mariadb";
    const DB_USER = "root";
    const DB_PASSWORD = "jhdagf8765fjJGF+";
    const DB_NAME = "lead_base";
    const DB_PREFIX = "bku_";
    const DB_SYM_QUERY = "{?}";
    const DB_ENCODE = "utf8mb4";

    const DIR_TMPL = "/var/www/html/tmpl/";
    const LAYOUT = "main";
    const FILE_MESSAGES = "/var/www/html/text/messages.ini";
    const FORMAT_DATE = "%d.%m.%Y %H:%M:%S";
    const SEF_SUFFIX = ".html";

}