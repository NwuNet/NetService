<?php
namespace Admin\Controller;
use Think\Controller;
class SelfController extends BaseController {
	public function index() {
		$this -> display();
	}

	public function upload() {
		$fname = I('post.fname');
		if(empty($fname)){
			$this->error("数据为空");
		}
		$upload = new \Think\Upload();
		// 实例化上传类
		$upload -> maxSize = 3145728;
		// 设置附件上传大小
		$upload -> exts = array('jpg', 'gif', 'png', 'jpeg');
		// 设置附件上传类型
		$upload -> rootPath = './Public/Images/User/';
		// 设置附件上传根目录
		$upload -> savePath = '';
		// 设置附件上传（子）目录
		$upload -> subName = $fname;
		//设置子目录名
		// 上传文件
		$info = $upload -> upload();
		if ($info) {// 上传成功
			$this->redirect('Admin/Self/index');
		} else {// 上传错误提示错误信息
			$this->error($upload->getError());
		}
	}

}
