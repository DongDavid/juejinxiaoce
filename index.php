<?php
/**
 * Created by PhpStorm.
 * User: dongdavid
 * Date: 2019-05-15
 * Time: 11:02
 */

$loader = require_once "vendor/autoload.php";


$juejin = new \dongdavid\reptile\juejin\JueJin();

// 这些要先登录，从浏览器里面自己找
$config = [
    'token'=>"==",
    'client_id'=>"",
    'uid'=>"",
];
// 小册子的id
$id = "";
// 开启调试
// \dongdavid\reptile\tools\HttpClient::$debug = true;
$juejin->setConfig($config);
$r = $juejin->start($id);
var_dump($r);