<?php
namespace dongdavid\reptile\tools;

/**
 * 
 */
class PdfTool
{
	public function getMpdf()
	{
        //var_dump(realpath("./fonts/PingFang-Regular.ttf"));
        //exit();
		$mpdf = new \Mpdf\Mpdf([
			"autoScriptToLang"=>true,
			"autoLangToFont"=>false,
            // "tempDir"=>$tempDir,
            'fontDir'=>'./fonts/',
            'fontdata'=>[
                'tt'=>[
                    'R'=>'PingFang-Medium.ttf',
                    'B'=>'PingFang-Bold.ttf',
                ],
            ],
            "setAutoTopMargin"=>"stretch",
			"setAutoBottomMargin"=>"stretch",
			"autoMarginPadding"=>55
		]);
		//$mpdf->autoLangToFont = true;
		//$mpdf->autoScriptToLang = true;
		return $mpdf;
	}
	public function html2Pdf(string $savePath,string $inputHtml,string $outputPdf=null)
	{
		if (empty($outputPdf)) {
			$outputPdf = $this->getOutPutName($inputHtml);
		}
		$mpdf = $this->getMpdf();
		$html = file_get_contents($inputHtml);
		$mpdf->WriteHTML($html);
		$filepath = $savePath.$outputPdf;
		$mpdf->Output($filepath);
		$mpdf->Close();
		$mpdf = null;
		unset($mpdf);
		return $filepath;
	}
	// 将所有的章节合并到一个pdf中
	public function converAllHtml2Pdf(tring $title,string $inputDir,string $outputDir)
	{
		if (!is_dir($outputDir)) {
			mkdir($outputDir,0755,true);
		}
		consoleMemery();
		$files = $this->getFilesOfDir($inputDir);
		$html = '<h1>'.$title.'</h1><hr/>';
		foreach ($files as $k => $v) {
		    $html .= '<h2>'.str_replace('.html', '', basename($v)).'</h2><br/>'.file_get_contents($v).'<hr/>';
			 consoleMemery('',true);
		}
        $mpdf = $this->getMpdf();
        $mpdf->WriteHTML($html);
        $mpdf->Output($outputDir.$title.'.pdf');
        $mpdf->cleanup();
        consoleMemery('',true);
        return true;
	}
	// 获取文件名并替换后缀
	public function getOutPutName($filename,$suffix='pdf')
	{
		$filename = basename($filename);
		$sp = strrpos($filename,'.');
		if(false === $sp){
			$output = $filename.'.'.$suffix;
		}else{
			$output = substr($filename,0,$sp).'.'.$suffix;
		}
		return $output;
	}
	public function getFilesOfDir(string $dir)
	{
		// $dir = './output/'.$xiaoce_id.'/html';
		if (!is_dir($dir)) {
			die('目录不存在,请先下载小册到本地');
		}
		$files = scandir($dir);
		$arr = [];
		foreach ($files as $k => $v) {
			if ($v == '.' ||$v=='..') {
				continue;
			}
			$sort = explode('.', $v);
			$arr[$sort[0]] = $dir.'/'.$v;
		}
		ksort($arr);
		return $arr;
	}
	
}