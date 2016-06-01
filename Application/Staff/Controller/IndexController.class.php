<?php
namespace Staff\Controller;
use Think\Controller;
class IndexController extends BaseController {
    public function index(){
        $Card = M('ServiceCard');
        $staffUser = D('Login','Service')->getuserInfo();

        $servicecardinfo = $Card->where('status = 0 AND area = "%s"',$staffUser['area'])->order('dormitory')->select();
        $CardRepair = M('ServiceRepair');
        foreach ($servicecardinfo as $key => $value){
            $servicecardinfo[$key]['isrepair'] = count($CardRepair->where('servicecard_id = %d',$value['id'])->select());
        }
        $this -> assign('servicecardinfo',$servicecardinfo );//服务单表

        $staff =D('Admin/StaffUserView')->where('status = 1')->field('uname')->select();
        $this->assign('staff',$staff);

        $this->display();
    }
}