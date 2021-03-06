<?php
/**
 * Created by PhpStorm.
 * User: dongdavid
 * Date: 2019-05-15
 * Time: 11:02
 */
// 生成pdf 需要消耗较大的内存
ini_set('memory_limit','3072M');
$loader = require_once "vendor/autoload.php";

// 加载.env文件
\dongdavid\reptile\tools\Env::overloadEnv(__DIR__ . '/.env');

$juejin = new \dongdavid\reptile\juejin\JueJin();

// 这些要先登录，从浏览器里面自己找
$config = [
    'token'     => \dongdavid\reptile\tools\Env::get('TOKEN'),
    'client_id' => \dongdavid\reptile\tools\Env::get('CLIENT_ID'),
    'uid'       => \dongdavid\reptile\tools\Env::get('UID'),
];
// 小册子的id
$id = \dongdavid\reptile\tools\Env::get('XIAOCE_ID');
$title = \dongdavid\reptile\tools\Env::get('XIAOCE_NAME');

// 开启调试
\dongdavid\reptile\tools\HttpClient::$debug = false;
$juejin->setConfig($config);
$r = $juejin->start($id);

if ($r) {
    var_dump($r);
} else {
    echo "下载失败了,自己把index.php里面的第26行的debug打开看看原因吧";
}
// 生成pdf
$pt = new dongdavid\reptile\tools\PdfTool();
$pt->converAllHtml2Pdf($title,'./output/'.$id.'/html/','./output/'.$id.'/pdf/');
