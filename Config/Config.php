<?php namespace Config;

    define('ROOT', str_replace('\\','/',dirname(__DIR__) . "/"));
    define('VIEWS', ROOT . "/Views/");
    $base=explode($_SERVER['DOCUMENT_ROOT'],ROOT);
    define("BASE",$base[1]);
    define("API_KEY", "f3f1134a7dc656a9b269c6eb27c4bf6e"); //API KEY for TMDb
    define("DB_HOST", "localhost");
    define("DB_NAME", "moviepass");
    define("DB_USER", "root");
    define("DB_PASS", "");
    
?>