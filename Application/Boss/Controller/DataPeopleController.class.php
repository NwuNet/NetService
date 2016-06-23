<?php
namespace Boss\Controller;
use Think\Controller;
class DataPeopleController extends BaseController {
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

            $Staff = D('Admin/StaffUserView');
            $Vacation = M('StaffVacation');

            //-------------------------graph bar line--------------------
            $map = array();
            $map['begintime'] = array('between', array( I('post.begin_time'), I('post.end_time') ) );
            $tmp = $Staff->where($map)->field('begintime')->select();
            $timex = array();
            foreach ($tmp as $key => $item){
                $str = date('Y-m-d',strtotime($item['begintime']));
                array_push($timex,$str);
            }
//            $timey = array_count_values($timex);
            $timex = array_unique($timex);
            $this->areax = $timex;
//            $this->areay = $timey;
            //-------------------------Staff graph area--------------------
            $areacount = array();
            $areadata = array();
            foreach ($userarea as $key => $item){
                $map['begintime'] = array('between', array( I('post.begin_time'), I('post.end_time') ) );
                $map['area'] = $item['area'];
                $tmp = $Staff -> where($map) ->select();
                $areacount[$key] = count($tmp);

                foreach ($timex as $x){
                    $map['begintime'] = array('like', $x.'%' );
                    $tmp = $Staff -> where($map) ->select();
                    $areadata[$key][$x] = count($tmp);
                }
            }
            $this->areacount = $areacount;
            $this->areadata = $areadata;
		
            //-----------------------------Vacation done or not done ---------------------------
        //  $acation = $Vacation->select();
         // $this->ajaxReturn($acation);
            $vacationdoneornot = array();
            foreach ($userarea as $key => $item){
             //   $map['start_time'] = array('between', array( I('post.begin_time'), I('post.end_time') ) );
             //   $map['area'] = $item['area'];
			//    $tmp = $Vacation->field('count(StaffVacation.status) as count')->group('StaffVacation.status')->where($map)->select();
        	
				$tmp = $Vacation->query("SELECT 
				net_staff_user.area,
                net_staff_vacation.`status`,
                count(net_staff_vacation.`status`)as count
                FROM
                net_user ,
                net_staff_user ,
                net_staff_vacation
                where net_user.user_id=net_staff_user.user_id and net_user.uname=net_staff_vacation.uname and net_staff_user.area='%s' 
                and net_staff_vacation.start_time>='%s' and net_staff_vacation.time<='%s'
                GROUP BY net_staff_user.area,net_staff_vacation.`status`
                ",$item['area'], I('post.begin_time'), I('post.end_time'));            		  
                if($tmp[0]['count']==''){
                    $tmp[0]['count'] = 0;
                }
                if($tmp[1]['count']==''){
                    $tmp[1]['count'] = 0;
                }
                $vacationdoneornot[$key] = $tmp;
            }
            $this->vacationdoneornot = $vacationdoneornot;
			
			//-----------------------------Dimission done or not done ---------------------------
            $Dimission=M('Dimission');
            $dimissiondoneornot = array();
            foreach ($userarea as $key => $item){                   	
				$tmp = $Dimission->query("SELECT 
                net_staff_user.area,
                net_dimission.`status`,
                count(net_dimission.`status`)as count
                FROM
                net_user ,
                net_staff_user ,
                net_dimission
                where net_user.user_id=net_staff_user.user_id and net_user.uname=net_dimission.uname and net_staff_user.area='%s' 
                and net_dimission.time>='%s' and net_dimission.time<='%s'
                GROUP BY net_staff_user.area,net_dimission.`status`
                ",$item['area'], I('post.begin_time'), I('post.end_time'));            		  
                if($tmp[0]['count']==''){
                    $tmp[0]['count'] = 0;
                }
                if($tmp[1]['count']==''){
                    $tmp[1]['count'] = 0;
                }
                $dimissiondoneornot[$key] = $tmp;
            }
            $this->dimissiondoneornot = $dimissiondoneornot;
			//-----------------------------Apply done or not done ---------------------------
            $Apply=M('ApplyHome');
            $applydoneornot = array();
            foreach ($userarea as $key => $item){                   	
				$tmp = $Apply->query("SELECT 
                net_home_user.area,
                net_apply_home.a_status,
                COUNT(net_apply_home.a_status)AS count
                FROM
                net_home_user ,
                net_apply_home
                where net_apply_home.home_id=net_home_user.id and net_home_user.area='%s' 
                and net_apply_home.time>='%s' and net_apply_home.time<='%s'
                GROUP BY net_home_user.area,net_apply_home.a_status
                ",$item['area'], I('post.begin_time'), I('post.end_time'));            		  
                if($tmp[0]['count']==''){
                    $tmp[0]['count'] = 0;
                }
                if($tmp[1]['count']==''){
                    $tmp[1]['count'] = 0;
                }
                $applydoneornot[$key] = $tmp;
            }
            $this->applydoneornot = $applydoneornot;
			//-----------------------------Register done or not done ---------------------------
            $Register=M('StaffRegister');
            $registerdoneornot = array();
            foreach ($userarea as $key => $item){                   	
				$tmp = $Register->query("SELECT 
                net_staff_user.area,
net_staff_register.state,
count(net_staff_register.state)as count
FROM
net_staff_register ,
net_staff_user ,
net_user
                where net_user.user_id=net_staff_user.user_id and net_user.uname=net_staff_register.uname and net_staff_user.area='%s' 
                and net_staff_register.time>='%s' and net_staff_register.time<='%s'
                GROUP BY net_home_user.area,net_staff_register.state
                ",$item['area'], I('post.begin_time'), I('post.end_time'));            		  
                if($tmp[0]['count']==''){
                    $tmp[0]['count'] = 0;
                }
                if($tmp[1]['count']==''){
                    $tmp[1]['count'] = 0;
                }
                $registerdoneornot[$key] = $tmp;
            }
            $this->registerdoneornot = $registerdoneornot;

       
            $this->display();
        }
    }
	public function _empty($name){
		echo "Not Found!";
    }
}