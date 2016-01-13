<?php
namespace Admin\Controller;
use Think\Controller;
class SystemFileController extends BaseController {
    public function index(){
    	$FileService = D('File','Service');
		$files = $FileService->getFile();
		$this->assign('files',$files);
        $this->display();
    }
	/**
	 * 加载管理文件插件
	 * */
	public function mfile($id=''){
		if($id==''){
			$this->error("没有文件");
		}
		$FileService = D('File','Service');
		$files = $FileService->getFile($id);
		$listfile = listFile('./',$files['dir']);
		trace($listfile);
		$this->assign('files',$listfile);
		$this->display();
	}
}