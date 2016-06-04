<?php
namespace Boss\Controller;
use Think\Controller;
class SelfController extends BaseController {
	public function index() {
		$Area = M('UserArea');
		$userarea = $Area->select();
		$this->assign('userarea',$userarea);
		$this -> display();
	}
	//---------------------------boss key 8-----------------------------------
	public function edit(){
		$User = D('Admin/User', 'Logic');
		if(I('post.repassword') != I('post.password')) $this -> ajaxReturn("确认密码失败");
		$BossView = D('Admin/BossUserView');
		$data = array();
		if(I('post.id')==''||I('post.user_id')==''){
			$this->ajaxReturn('数据为空');
		}
		$data['id'] = I('post.id');//---------------------1
		$data['user_id'] = I('post.user_id');//---------------------2
		$boss = $BossView->where('id = %d',$data['id'])->find();

		if(I('post.cname')==''){//---------------------3
			$data['cname'] = $boss['cname'];
		}else{
			$data['cname'] = I('post.cname');
		}

		if(I('post.uname')==''){//---------------------4
			$data['uname'] = $boss['uname'];
		}else{
			$data['uname'] = I('post.uname');
		}

		if(I('post.area')==''){//---------------------5
			$data['area'] = $boss['area'];
		}else{
			$data['area'] = I('post.area');
		}

		if(I('post.img')==''){//---------------------6
			$data['img'] = $boss['img'];
		}else{
			$data['img'] = I('post.img');
		}

		if(I('post.status')==''){//---------------------7
			$data['status'] = $boss['status'];
		}else{
			$data['status'] = I('post.status');
		}

		if(I('post.password')==''){//---------------------8
			$data['password'] = $boss['password'];
		}else{
			$data['password'] = I('post.password');
		}

		if ($User -> bossedit($data)) {
			$msg = "修改成功！";
			$this -> ajaxReturn(TRUE);
		} else {
			$msg = "修改失败！";
			$this -> ajaxReturn(FALSE);
		}
	}
	/**
	 * @param file,fname,id
	 * 图片上传
	 * */
	public function upload() {
		$fname = I('post.fname');
		$id = I('post.user_id');
		if(empty($fname)||empty($id)){
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
		// 设置子目录名
//		$upload -> saveName = $fname;
		// 上传文件
		$upload -> replace = TRUE;
		// 允许覆盖上传
		$info = $upload -> upload();
		//trace($info);
		$user = M('BossUser');
		$data = $user -> where('id = %d',$id)->find();
		unlink('Public/'.$data['img']);
		$data['img'] = '/Images/User/'.$fname.'/'.$info['photo']['savename'];
		$status = $user -> save($data);
		if ($info && $status) {// 上传成功
			$this->redirect('Boss/Self/index');
		} else {// 上传错误提示错误信息
			$this->redirect('Boss/Self/index');
		}
	}
	/**
	 * 头像保存
	 * */
	public function save(){
		$imgX = I('post.imgX');
		$imgY = I('post.imgY');
		$imgW = I('post.imgW');
		$imgH = I('post.imgH');
		$path = I('post.path');
		if($imgX==''||$imgY==''||$imgW==''||$imgH==''||$path==''){
			$this -> ajaxReturn("数据为空");
		}
		$image = new \Think\Image();
		$file = './Public'.$path;
		//trace($file);
		$image -> open($file);
		$status = $image->crop($imgW, $imgH,$imgX,$imgY)->save($file);
		if($status){
			$this -> ajaxReturn(TRUE);
		}else{
			$msg = "保存失败";
			$this -> ajaxReturn($msg);
		}
	}
}
