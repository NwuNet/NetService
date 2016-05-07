<?php
namespace Boss\Controller;
use Think\Controller;
class IndexController extends BaseController{

    public function index(){
        $Card = M('ServiceCard');
        $servicecardinfo = $Card->where('status = 0')->order('dormitory')->select();
        $this -> assign('servicecardinfo',$servicecardinfo );//服务单表

        $staff =D('Admin/StaffUserView')->where('status = 1')->field('uname')->select();
        $this->assign('staff',$staff);

        $this->display();
    }

    public function _empty($name){
        echo "Not Found!";
    }
}