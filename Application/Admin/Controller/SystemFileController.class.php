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
	public function mfile($key='',$dir='./'){
		if($key==''){
			$this->error("没有文件");
		}
		$FileService = D('File','Service');
		$root = $FileService->getFile($key);
		$sdir = str_replace(' ', '/', $dir);
		$listfile = listFile($sdir,$root['dir']);
		$this->assign('files',$listfile);
		$this->assign('dir',$dir);
		$this->assign('root',$root);
		$this->display();
	}
	/**
	 * 读取文件
	 * */
	 public function rfile(){
	 	$key = I('post.key');
		$dir = I('post.dir');
		$fname = I('post.fname');
		if($key==''||$dir==''||$fname==''){
			$this -> ajaxReturn("数据为空");
		}
		$FileService = D('File','Service');
		$root = $FileService->getFile($key);
		$sdir = str_replace(' ', '/', $dir);
		if($dir=='./'){
			$path = $root['dir'].$fname;
		}else{
			$path = $root['dir'].$sdir.'/'.$fname;
		}
		foreach(file($path) as $temp){
			$content = $content.$temp;
		}
		if($content){
			$content=str_replace("\n","<br/>",$content); 
			//$content=str_replace("\t","&nbsp;",$content);
			$content=str_replace(" ","&nbsp;&nbsp;",$content);
			$data['status'] = TRUE;
			$data['content'] = $content;
			$this->ajaxReturn($data);
		}else{
			$msg = "打开失败";
			$this->ajaxReturn($msg);
		}
	 }
}