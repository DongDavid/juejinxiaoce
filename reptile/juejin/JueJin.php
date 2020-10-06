<?php

namespace dongdavid\reptile\juejin;

use dongdavid\reptile\tools\HttpClient;

/**
 * Created by PhpStorm.
 * User: dongdavid
 * Date: 2019-05-15
 * Time: 10:17
 */

class JueJin
{
    const SECTION_URL      = "https://xiaoce-cache-api-ms.juejin.im/v1/getSection";
    const SECTION_LIST_URL = "https://xiaoce-cache-api-ms.juejin.im/v1/getListSection";

    public $saveConfig = [
        'path'     => './output',
        'fileType' => ['markdown', 'html','pdf'],
    ];

    private $config = [
        "token"     => "",
        "client_id" => "",
        "src"       => "web",
        "uid"       => "",
        "id"        => "",
    ];

    public function setConfig($config)
    {
        foreach ($config as $k => $v) {
            isset($this->config[$k]) && $this->config[$k] = $v;
        }
    }

    public function getSectionIdList($id)
    {
        $data       = $this->config;
        $data['id'] = $id;
        $res        = HttpClient::request(self::SECTION_LIST_URL, "GET", $data);
        $res        = json_decode($res, true);
        $pages      = [];
        if ($res['s'] != 1) {
            return false;
        }
        foreach ($res['d'] as $re) {
            $pages[] = [
                'title'       => $re['title'],
                'sectionId'   => $re['sectionId'],
                'contentSize' => $re['contentSize'],
                'log'         => "已获取章节" . PHP_EOL,
            ];
        }
        return $pages;
    }

    public function start($id)
    {

        $path  = $this->saveConfig['path'] . DIRECTORY_SEPARATOR . $id;
        $pages = $this->getSectionIdList($id);
        if (!$pages) {
            return false;
        }
        foreach ($pages as $sort => &$page) {
            $r = $this->getPage($page['sectionId']);
            if (!$r) {
                $page['log'] .= "请求成功" . PHP_EOL;
            }
            $re = $this->saveToPath($path, $r['d'], $sort);
            if ($re) {
                $page['log'] .= "保存成功" . PHP_EOL;
            }
        }
        return $pages;
    }
    public function getPage(string $section_id)
    {
        $data              = $this->config;
        $data['sectionId'] = $section_id;
        $res               = HttpClient::request(self::SECTION_URL, "GET", $data);
        $res               = json_decode($res, true);
        if ($res['s'] != 1) {
            return false;
        }
        return $res;
    }
    public function saveToPath(string $path, array $page, int $sort)
    {
        // 因为$sort是从零开始的
        $sort += 1;
        $pathMarkdown = $path . DIRECTORY_SEPARATOR . "markdown" . DIRECTORY_SEPARATOR;
        $pathHtml     = $path . DIRECTORY_SEPARATOR . "html" . DIRECTORY_SEPARATOR;
        $pathPdf      = $path . DIRECTORY_SEPARATOR . "pdf"  . DIRECTORY_SEPARATOR;
        if (!is_dir($pathMarkdown)) {
            $r = mkdir($pathMarkdown, 0755, true);
        }
        if (!is_dir($pathHtml)) {
            mkdir($pathHtml, 0755, true);
        }
        if (!is_dir($pathPdf)) {
            mkdir($pathPdf, 0755, true);
        }

        $page['title'] = str_replace('/', "-bs-", $page['title']);

        $filename = $pathMarkdown . $sort.'. ' .$page['title'] . "-" . ".md";
        $this->saveAsMarkdown($filename, $page['content']);
        $filename = $pathHtml . $sort .'. '. $page['title'] . ".html";
        $this->saveAsHtml($filename, $page['html']);
    }
    public function saveAsMarkdown(string $filename, string $markdownContent)
    {
        $content = str_replace("↵", PHP_EOL, $markdownContent);
        file_put_contents($filename, $content);
    }
    public function saveAsHtml(string $fielname, string $htmlContent)
    {
        file_put_contents($fielname, $htmlContent);
    }
    public function convert2Pdf(string $inputHtml,string $outputPdf=null)
    {
              
    }
    public function downImages(string $content, string $savePath)
    {

    }
}
