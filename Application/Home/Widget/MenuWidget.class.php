<?php
namespace Home\Widget;
use Think\Controller;
class MenuWidget extends Controller {
    public function index($id1,$id2=''){
		/*
		 * 加载菜单组
		 * */
    	$this->assign('id1',$id1);
		if($id2!=''){
			$this->assign('id2',$id2);
		}
        $this->display('Menu:index');
    }
}