<?php
namespace Boss\Controller;
use Think\Controller;
class DataServiceController extends BaseController {
    public function index(){
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
            $staffdo = $Repair->field('operator,count(operator) as count')->group('operator')->where($map)->select();
            $this->staffdo = $staffdo;

            $this->display();
        }
    }
    public function topdf(){

    }
	public function _empty($name){
		echo "Not Found!";
    }
}