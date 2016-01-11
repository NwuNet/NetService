<?php
namespace Admin\Controller;
use Think\Controller;
class SystemCacheController extends BaseController {
    public function index(){
    	$Cache = D('Cache','Service');
		$this -> assign('cache',$Cache->getCache());
        $this->display();
    }
	/**
	 * @param $id 键值
	 * @return true/msg
	 * 清空缓存*/
	public function delcache(){
		$Cache = D('Cache','Service');
		$id = I('post.id');
		if(empty($id)){
			$this -> ajaxReturn("数据为空");
		}
		if($Cache->delCache($id)) {
			$this -> ajaxReturn(TRUE);
		}else{
			$msg = "清除失败";
			$this -> ajaxReturn($msg);
		}
	}
}