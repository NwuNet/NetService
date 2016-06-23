<?php
namespace Boss\Controller;
use Think\Controller;
class DataServiceController extends BaseController {
    public function index(){
        $Area = M('UserArea');
        $userarea = $Area->select();
        $this->assign('userarea',$userarea);
        $this->display();
    }
    public function report(){
        if(I('post.begin_time')==''||I('post.end_time')==''){
            echo "<h1>".I('post.begin_time').I('post.end_time')."数据不正确</h1>";
        }else{
            $this->begin_time = I('post.begin_time');
            $this->end_time = I('post.end_time');
            //--------------------------area-----------------------
            $Area = M('UserArea');
            $userarea = $Area->select();
            $this->assign('userarea',$userarea);

            $Service = M('ServiceCard');
            $Repair = M('ServiceRepair');

            //-------------------------graph bar line--------------------
            $map = array();
            $map['start'] = array('between', array( I('post.begin_time'), I('post.end_time') ) );
            $tmp = $Service->where($map)->field('start')->select();
            $timex = array();
            foreach ($tmp as $key => $item){
                $str = date('Y-m-d',strtotime($item['start']));
                array_push($timex,$str);
            }
//            $timey = array_count_values($timex);
            $timex = array_unique($timex);
            $this->areax = $timex;
//            $this->areay = $timey;
            //-------------------------graph area--------------------
            $areacount = array();
            $areadata = array();
            foreach ($userarea as $key => $item){
                $map['start'] = array('between', array( I('post.begin_time'), I('post.end_time') ) );
                $map['area'] = $item['area'];
                $tmp = $Service -> where($map) ->select();
                $areacount[$key] = count($tmp);

                foreach ($timex as $x){
                    $map['start'] = array('like', $x.'%' );
                    $tmp = $Service -> where($map) ->select();
                    $areadata[$key][$x] = count($tmp);
                }
            }
            $this->areacount = $areacount;
            $this->areadata = $areadata;

            //-----------------------------done or not done ---------------------------
            $doneornot = array();
            foreach ($userarea as $key => $item){
                $map['start'] = array('between', array( I('post.begin_time'), I('post.end_time') ) );
                $map['area'] = $item['area'];
                $tmp = $Service->field('count(status) as count')->group('status')->where($map)->select();
                if($tmp[0]['count']==''){
                    $tmp[0]['count'] = 0;
                }
                if($tmp[1]['count']==''){
                    $tmp[1]['count'] = 0;
                }
                $doneornot[$key] = $tmp;
            }
            $this->doneornot = $doneornot;

            //-----------------------------故障大类------------------------------------
            $map['time'] = array('between', array( I('post.begin_time'), I('post.end_time') ) );
            $breakinfo = $Repair->field('breakinfo,count(breakinfo) as count')->group('breakinfo')->where($map)->select();
            $this->breakinfo = $breakinfo;
            $staffdo = $Repair->field('staff,count(staff) as count')->group('staff')->where($map)->select();
            $this->staffdo = $staffdo;

            $this->display();
        }
    }
    public function areareport(){
        if(I('post.begin_time')==''||I('post.end_time')==''||I('post.area')==''){
            echo "<h1>".I('post.begin_time').I('post.end_time')."数据不正确</h1>";
        }else{
            $begin_time = I('post.begin_time');
            $end_time = I('post.end_time');
            $this->begin_time = $begin_time;
            $this->end_time = $end_time;
            $area = I('post.area');
            $map['start'] = array('between', array( $begin_time, $end_time ) );
            $map['area'] = $area;

            $Service = M('ServiceCard');
            $Repair = D('ServiceRepairView');

            $tmp = $Service->where($map)->field('start')->select();
            $areacount = count($tmp);
            $timex = array();
            foreach ($tmp as $key => $item){
                $str = date('Y-m-d',strtotime($item['start']));
                array_push($timex,$str);
            }
            $timex = array_unique($timex);
            $this->areax = $timex;

            $areadata = array();
            foreach ($timex as $x){
                $map['start'] = array('like', $x.'%' );
                $tmp = $Service -> where($map) ->select();
                $areadata[$x] = count($tmp);
            }
            $this->areacount = $areacount;
            $this->areadata = $areadata;
            $this->area = $area;

            //-----------------------------done or not done ---------------------------
            $doneornot = array();
            $map['start'] = array('between', array( $begin_time, $end_time ) );
            $map['area'] = $area;
            $tmp = $Service->field('count(status) as count')->group('status')->where($map)->select();
            if($tmp[0]['count']==''){
                $tmp[0]['count'] = 0;
            }
            if($tmp[1]['count']==''){
                $tmp[1]['count'] = 0;
            }
            $doneornot = $tmp;
            $this->doneornot = $doneornot;

            //-----------------------------故障大类------------------------------------
            $map['time'] = array('between', array( $begin_time, $end_time ) );
            $map['area'] = $area;
            $breakinfo = $Repair->field('breakinfo,count(breakinfo) as count')->group('breakinfo')->where($map)->select();
            $this->breakinfo = $breakinfo;
            $staffdo = $Repair->field('operator,count(ServiceRepair.operator) as count')->group('operator')->where($map)->select();
            $this->staffdo = $staffdo;

            //------------------------------楼宇故障------------------------------------
            $tmp = $Service->where($map)->field('dormitory')->select();
            $addressx = array();
            foreach ($tmp as $key => $item){
                $str = explode('-',$item['dormitory']);
                trace($str);
                array_push($addressx,$str[0]);
            }
            $addressx = array_unique($addressx);
            $addressdata = array();
            foreach ($addressx as $x){
                $map['dormitory'] = array('like', $x.'%' );
                $tmp = $Service -> where($map) ->select();
                $addressdata[$x]['count'] = count($tmp);
                $addressdata[$x]['x'] = $x.'号楼';
            }
//            $this->addressx = $addressx;
            $this->addressdata = $addressdata;
//            trace($addressdata);
//            trace($addressx);

            $this->display();
        }
    }
    public function topdf(){

    }
	public function _empty($name){
		echo "Not Found!";
    }
}