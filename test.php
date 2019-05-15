<?php

$loader = require_once "vendor/autoload.php";

// \dongdavid\reptile\tools\Env::overloadEnv(__DIR__ . '/.env');

// $env = \dongdavid\reptile\tools\Env::get('UID');
// env 里面如果值包括了= 那么这个方法会抛出异常， 必须加上双引号
$env = parse_ini_file('.env');


var_dump($env);
