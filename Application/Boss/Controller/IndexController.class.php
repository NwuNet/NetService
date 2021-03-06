<?php
namespace Boss\Controller;
use Think\Controller;
class IndexController extends BaseController{

    public function index(){
        $Card = M('ServiceCard');
        $bossUser = D('Login','Service')->getuserInfo();
        
        //显示每日可预约服务单数量
        $areaquantity = M("ServiceQuantity");
        $aquantity = $areaquantity->where('area = "%s"',$bossUser['area'])->find();
        $this -> assign('aquantity',$aquantity );    
        //未维修服务单
        $servicecardinfo = $Card->where('status = 0 AND area = "%s"',$bossUser['area'])->order('dormitory')->select();
        $CardRepair = M('ServiceRepair');
       foreach ($servicecardinfo as $key => $value){
            $servicecardinfo[$key]['isrepair'] = count($CardRepair->where('servicecard_id = %d',$value['id'])->select());
        }
        $this -> assign('servicecardinfo',$servicecardinfo );//服务单表
        
        //已维修未确认服务单
//        $servicecardinfo2 = $Card->where('status = 2 AND area = "%s"',$bossUser['area'])->order('dormitory')->select();
//        $this -> assign('servicecardinfo2',$servicecardinfo2 );//服务单表


        $staff =D('Admin/StaffUserView')->where('status = 1')->field('uname')->select();
        $this->assign('staff',$staff);
        //----------------------------------------tool---------------------------------------
        $Tool = M('AssetTool');
        $maxtime = $Tool->field('start')->order('start desc')->find();
        $m = month(strtotime($maxtime['start']));
        $tooltime = array();
        $timenum = 0;
        foreach ($m as $item) {
            $map['start'] = array('like',$item.'%');
            $toolshow = $Tool->where($map)->field('start,count(start) as number')->select();
            $toolshow[0]['start'] =$item;
            array_push($tooltime,$toolshow[0]);
            $timenum ++;
        }
        $this->assign('tooltime',$tooltime);
        if($tooltime[0]['number'] == 0){
            $this->assign('toolcolor','bg-red-gradient');
        }else{
            $subnum = $tooltime[0]['number'] - $tooltime[1]['number'];
            if($subnum>0){
                $this->assign('toolcolor','bg-green-gradient');
            }else{
                $this->assign('toolcolor','bg-yellow-gradient');
            }
        }
        //---------------------------------------exhaust------------------------------------------
        $Exhaust = M('AssetExhaust');
        $maxtime = $Exhaust->field('start')->order('start desc')->find();
        $m = month(strtotime($maxtime['start']));
        $exhausttime = array();
        $timenum = 0;
        foreach ($m as $item) {
            $map['start'] = array('like',$item.'%');
            $exhaustshow = $Exhaust->where($map)->field('start,count(start) as num')->select();
            $exhaustshow[0]['start'] =$item;
            array_push($exhausttime,$exhaustshow[0]);
            $timenum++;
        }
        $this->assign('exhausttime',$exhausttime);
//        trace($exhausttime);

        if($exhausttime[0]['num'] == 0){
            $this->assign('exhaustcolor','bg-red-gradient');
        }else{
            $subnum = $exhausttime[0]['num'] - $exhausttime[1]['num'];
            if($subnum>0){
                $this->assign('exhaustcolor','bg-green-gradient');
            }else{
                $this->assign('exhaustcolor','bg-yellow-gradient');
            }
        }
        //----------------------------------------device---------------------------------------------
        $Device = M('AssetDevice');
        $maxtime = $Device->field('start')->order('start desc')->find();
        $m = month(strtotime($maxtime['start']));
        $devicetime = array();
        $timenum = 0;
        foreach ($m as $item) {
            $map['start'] = array('like',$item.'%');
            $deviceshow = $Device->where($map)->field('start,count(start) as number')->select();
            $deviceshow[0]['start'] =$item;
            array_push($devicetime,$deviceshow[0]);
            $timenum++;
        }
        $this->assign('devicetime',$devicetime);

        if($devicetime[0]['number'] == 0){
            $this->assign('devicecolor','bg-red-gradient');
        }else{
            $subnum = $devicetime[0]['number'] - $devicetime[1]['number'];
            if($subnum>0){
                $this->assign('devicecolor','bg-green-gradient');
            }else{
                $this->assign('devicecolor','bg-yellow-gradient');
            }
        }

        $this->display();
    }
    public function updatequantity(){
		//$id = I('post.id');
		$area = I('post.area');
		$quantity = I('post.quantity');		
		if($quantity==''||$area==''){
			$this->ajaxReturn("数据为空");
		}
		$aquantity = M('ServiceQuantity');
		$data = $aquantity->where('area = "%s"',$area)->find();	
		$data['quantity'] = $quantity;		
		if($aquantity->save($data)){
			$this->ajaxReturn(true);
		} else{
			$this->ajaxReturn("提交失败");
		}
	}

    public function _empty($name){
        echo "Not Found!";
    }
}