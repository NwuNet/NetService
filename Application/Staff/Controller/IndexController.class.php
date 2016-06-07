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
            $servicecardinfo[$key]['isrepair'] = count($CardRepair->where('servicecard_id = %d and operator="%s"',$value['id'],$staffUser['cname'])->select());
        }
        $this -> assign('servicecardinfo',$servicecardinfo );//服务单表

        $this->display();
    }
}