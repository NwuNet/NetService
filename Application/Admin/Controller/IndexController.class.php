<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends BaseController {
    public function index(){
        $log = D('Admin/SystemLog','Service');
        $this->assign('log_sum',$log->count());
        $Cache = D('File','Service');
        $this -> assign('file',$Cache->getFile());

        $log = M('SystemLog');
        $this->assign('log',$log->where('module="Admin"')->select());

        

        $this->display();
    }
}