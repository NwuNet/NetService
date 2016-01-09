<?php
namespace Admin\Controller;
use Think\Controller;
class SystemCacheController extends BaseController {
    public function index(){
    	$Cache = D('Cache','Service');
		$this -> assign('cache',$Cache->getCache());
        $this->display();
    }
}