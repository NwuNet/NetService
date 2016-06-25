<?php
namespace Boss\Controller;
use Think\Controller;
class DataPeopleController extends BaseController {
	public function index() {
		$Area = M('UserArea');
		$userarea = $Area -> select();
		$this -> assign('userarea', $userarea);
		$this -> display();
	}

	public function report() {
		if (I('post.begin_time') == '' || I('post.end_time') == '') {
			echo "<h1>" . I('post.begin_time') . I('post.end_time') . "数据不正确</h1>";
		} else {
			$this -> begin_time = I('post.begin_time');
			$this -> end_time = I('post.end_time');
			//--------------------------area-----------------------
			$Area = M('UserArea');
			$userarea = $Area -> select();
			$this -> assign('userarea', $userarea);

			$Staff = D('Admin/StaffUserView');
			$Vacation = M('StaffVacation');
			$Model = new \Think\Model();

			//-------------------------graph bar line--------------------
			$map = array();
			$map['begintime'] = array('between', array(I('post.begin_time'), I('post.end_time')));
			$tmp = $Staff -> where($map) -> field('begintime') -> select();
			$timex = array();
			foreach ($tmp as $key => $item) {
				$str = date('Y-m-d', strtotime($item['begintime']));
				array_push($timex, $str);
			}
			//            $timey = array_count_values($timex);
			$timex = array_unique($timex);
			$this -> areax = $timex;
			//            $this->areay = $timey;
			//-------------------------Staff graph area--------------------
			$areacount = array();
			$areadata = array();
			foreach ($userarea as $key => $item) {
				$map['begintime'] = array('between', array(I('post.begin_time'), I('post.end_time')));
				$map['area'] = $item['area'];
				$tmp = $Staff -> where($map) -> select();
				$areacount[$key] = count($tmp);

				foreach ($timex as $x) {
					$map['begintime'] = array('like', $x . '%');
					$tmp = $Staff -> where($map) -> select();
					$areadata[$key][$x] = count($tmp);
				}
			}
			$this -> areacount = $areacount;
			$this -> areadata = $areadata;

			//-----------------------------Vacation done or not done ---------------------------
			//  $acation = $Vacation->select();
			// $this->ajaxReturn($acation);
			$vacationdoneornot = array();
			foreach ($userarea as $key => $item) {
				//   $map['start_time'] = array('between', array( I('post.begin_time'), I('post.end_time') ) );
				//   $map['area'] = $item['area'];
				//    $tmp = $Vacation->field('count(StaffVacation.status) as count')->group('StaffVacation.status')->where($map)->select();

				$tmp = $Model -> query("SELECT 
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
                ", $item['area'], I('post.begin_time'), I('post.end_time'));
				if ($tmp[0]['count'] == '') {
					$tmp[0]['count'] = 0;
				}
				if ($tmp[1]['count'] == '') {
					$tmp[1]['count'] = 0;
				}
				$vacationdoneornot[$key] = $tmp;
			}
			$this -> vacationdoneornot = $vacationdoneornot;

			//-----------------------------Dimission done or not done ---------------------------
			$dimissiondoneornot = array();
			foreach ($userarea as $key => $item) {
				$tmp = $Model -> query("SELECT 
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
                ", $item['area'], I('post.begin_time'), I('post.end_time'));
				if ($tmp[0]['count'] == '') {
					$tmp[0]['count'] = 0;
				}
				if ($tmp[1]['count'] == '') {
					$tmp[1]['count'] = 0;
				}
				$dimissiondoneornot[$key] = $tmp;
			}
			$this -> dimissiondoneornot = $dimissiondoneornot;
			//-----------------------------Apply done or not done ---------------------------
			$applydoneornot = array();
			foreach ($userarea as $key => $item) {
				$tmp = $Model -> query("SELECT 
                net_home_user.area,
                net_apply_home.a_status,
                COUNT(net_apply_home.a_status)AS count
                FROM
                net_home_user ,
                net_apply_home
                where net_apply_home.home_id=net_home_user.id and net_home_user.area='%s' 
                and net_apply_home.time>='%s' and net_apply_home.time<='%s'
                GROUP BY net_home_user.area,net_apply_home.a_status
                ", $item['area'], I('post.begin_time'), I('post.end_time'));
				if ($tmp[0]['count'] == '') {
					$tmp[0]['count'] = 0;
				}
				if ($tmp[1]['count'] == '') {
					$tmp[1]['count'] = 0;
				}
				$applydoneornot[$key] = $tmp;
			}
			$this -> applydoneornot = $applydoneornot;
			//-----------------------------Register  ---------------------------
			$RegisterSelect = M('RegisterSelect');
			$registerarea = $RegisterSelect -> select();
			$this -> assign('registerarea', $registerarea);

			$Register = M('StaffRegister');

			$map = array();
			$map['time'] = array('between', array(I('post.begin_time'), I('post.end_time')));
			$tmp = $Register -> where($map) -> field('time') -> select();
			$timex = array();
			foreach ($tmp as $key => $item) {
				$str = date('Y-m-d', strtotime($item['time']));
				array_push($timex, $str);
			}
			$timex = array_unique($timex);
			$this -> areax = $timex;

			//-----------------------------长安校区
			$registercount = array();
			$registerdata = array();
			foreach ($registerarea as $key => $item) {
				//    $map['time'] = array('between', array( I('post.begin_time'), I('post.end_time') ) );
				//   $map['name'] = $item['name'];
				//    $tmp = $Staff -> where($map) ->select();
				$tmp = $Model -> query("SELECT                
                count(net_staff_register.state)as count
                FROM
                net_staff_register ,
                net_staff_user ,
                net_user
                where net_user.user_id=net_staff_user.user_id and net_user.uname=net_staff_register.uname and net_staff_user.area='长安校区' 
                and net_staff_register.state='%s' and net_staff_register.time>='%s' and net_staff_register.time<='%s' 
                GROUP BY net_staff_register.state
                ", $item['name'], I('post.begin_time'), I('post.end_time'));
				$registercount[$key] = $tmp[0]['count'];

				/* foreach ($timex as $x){
				 $map['time'] = array('like', $x.'%' );
				 $tmp = $Register -> where($map) ->select();
				 $registerdata[$key][$x] = count($tmp);
				 }*/
			}
			// $this->ajaxReturn($registercount);
			$this -> registercount = $registercount;
			//   $this->registerdata = $registerdata;
			//-----------------------------太白校区
			$registercount2 = array();
			$registerdata = array();
			foreach ($registerarea as $key => $item) {
				//    $map['time'] = array('between', array( I('post.begin_time'), I('post.end_time') ) );
				//   $map['name'] = $item['name'];
				//    $tmp = $Staff -> where($map) ->select();
				$tmp = $Model -> query("SELECT                
                count(net_staff_register.state)as count
                FROM
                net_staff_register ,
                net_staff_user ,
                net_user
                where net_user.user_id=net_staff_user.user_id and net_user.uname=net_staff_register.uname and net_staff_user.area='太白校区' 
                and net_staff_register.state='%s' and net_staff_register.time>='%s' and net_staff_register.time<='%s' 
                GROUP BY net_staff_register.state
                ", $item['name'], I('post.begin_time'), I('post.end_time'));
				$registercount2[$key] = $tmp[0]['count'];

				/* foreach ($timex as $x){
				 $map['time'] = array('like', $x.'%' );
				 $tmp = $Register -> where($map) ->select();
				 $registerdata[$key][$x] = count($tmp);
				 }*/
			}
			// $this->ajaxReturn($registercount);
			$this -> registercount2 = $registercount2;
			//   $this->registerdata = $registerdata;

			//-----------------------------桃园校区
			$registercount3 = array();
			$registerdata3 = array();
			foreach ($registerarea as $key => $item) {
				//    $map['time'] = array('between', array( I('post.begin_time'), I('post.end_time') ) );
				//   $map['name'] = $item['name'];
				//    $tmp = $Staff -> where($map) ->select();
				$tmp = $Model -> query("SELECT                
                count(net_staff_register.state)as count
                FROM
                net_staff_register ,
                net_staff_user ,
                net_user
                where net_user.user_id=net_staff_user.user_id and net_user.uname=net_staff_register.uname and net_staff_user.area='桃园校区' 
                and net_staff_register.state='%s' and net_staff_register.time>='%s' and net_staff_register.time<='%s' 
                GROUP BY net_staff_register.state
                ", $item['name'], I('post.begin_time'), I('post.end_time'));
				$registercount3[$key] = $tmp[0]['count'];

				/* foreach ($timex as $x){
				 $map['time'] = array('like', $x.'%' );
				 $tmp = $Register -> where($map) ->select();
				 $registerdata3[$key][$x] = count($tmp);
				 }*/
			}

			$this -> registercount3 = $registercount3;
			//   $this->registerdata3 = $registerdata3;

			$this -> display();
		}
	}

	public function areareport() {
		if (I('post.begin_time') == '' || I('post.end_time') == '' || I('post.area') == '') {
			echo "<h1>" . I('post.begin_time') . I('post.end_time') . "数据不正确</h1>";
		} else {
			$begin_time = I('post.begin_time');
			$end_time = I('post.end_time');
			$this -> begin_time = $begin_time;
			$this -> end_time = $end_time;
			$area = I('post.area');
			$map['start'] = array('between', array($begin_time, $end_time));
			$map['area'] = $area;

			$PositionState = M('PositionState');
			$positionarea = $PositionState -> select();
			$this -> assign('positionarea', $positionarea);

			$Staff = D('Admin/StaffUserView');
			$Model = new \Think\Model();
			//-------------------------graph bar line--------------------
			$map = array();
			$map['begintime'] = array('between', array(I('post.begin_time'), I('post.end_time')));
			$tmp = $Staff -> where($map) -> field('begintime') -> select();
			$timex = array();
			foreach ($tmp as $key => $item) {
				$str = date('Y-m-d', strtotime($item['begintime']));
				array_push($timex, $str);
			}
			$timex = array_unique($timex);
			$this -> areax = $timex;
			//-------------------------Staff graph area--------------------
			$areacount = array();
			$areadata = array();
			foreach ($positionarea as $key => $item) {
				$map['begintime'] = array('between', array(I('post.begin_time'), I('post.end_time')));
				$map['job'] = $item['name'];
				$map['area'] = $area;
				$tmp = $Staff -> where($map) -> select();
				$areacount[$key] = count($tmp);

				foreach ($timex as $x) {
					$map['begintime'] = array('like', $x . '%');
					$tmp = $Staff -> where($map) -> select();
					$areadata[$key][$x] = count($tmp);
				}
			}
			$this -> areacount = $areacount;
			$this -> areadata = $areadata;
			//$this->ajaxReturn($areadata);
			$this -> area = $area;

			$vacationstaff = D("Admin/StaffUserView") -> where("area='%s'", $area) -> group('uname') -> field('uname') -> select();
			//$this->ajaxReturn($vacationstaff);
			$this -> vacationstaff = $vacationstaff;
			//-------------------------vacation--------------------			
			$vacationcount = array();
			$vacationdata = array();
			foreach ($vacationstaff as $key => $item) {				
				$tmp = $Model -> query("SELECT 
                net_staff_vacation.uname,              
                count(net_staff_vacation.uname)as count
                FROM
                net_user ,
                net_staff_user ,
                net_staff_vacation
                where net_user.user_id=net_staff_user.user_id and net_user.uname=net_staff_vacation.uname 
                  and net_staff_user.area='%s' 
                  and net_staff_vacation.time>='%s' and net_staff_vacation.time<='%s' 
                  and net_staff_vacation.uname='%s'
                GROUP BY net_staff_user.area,net_staff_vacation.uname
                ", $area, I('post.begin_time'), I('post.end_time'), $item['uname']);
				if ($tmp[0]['count'] != '') {
					$vacationcount[$key]['uname'] = $tmp[0]['uname'];
					$vacationdata[$key]['uname'] = $tmp[0]['uname'];
					$vacationdata[$key]['count'] = $tmp[0]['count'];
				}				
			}
			$this -> vacationcount = $vacationcount;
			// $this->ajaxReturn($vacationcount);
			$this -> vacationdata = $vacationdata;
			//---------------考勤----------------------
			$RegisterSelect = M('RegisterSelect');
			$registerarea = $RegisterSelect -> select();
			$this -> assign('registerarea', $registerarea);
			
			$registerareax = array();
			foreach ($registerarea as $key => $item) {
				$registerareax[$key] = $item['name'];
			}
			$this -> assign('registerareax', $registerareax);
			//	$this->ajaxReturn($areax);
			$registerdata = array();
			foreach ($vacationstaff as $key => $item) {
				$tmp = $Model -> query("SELECT 
                net_staff_register.uname,              
                count(net_staff_register.uname)as count
                FROM
                net_user ,
                net_staff_user ,
                net_staff_register
                where net_user.user_id=net_staff_user.user_id and net_user.uname=net_staff_register.uname 
                  and net_staff_user.area='%s' 
                  and net_staff_register.time>='%s' and net_staff_register.time<='%s' 
                  and net_staff_register.uname='%s'
                GROUP BY net_staff_user.area,net_staff_register.uname
                ", $area, I('post.begin_time'), I('post.end_time'), $item['uname']);
				if ($tmp[0]['count'] != '') {
					$registerdata[$key]['uname'] = $tmp[0]['uname'];
					$registerdata[$key]['count'] = $tmp[0]['count'];
				}				
			}
			$this -> registerdata = $registerdata;

			$registerdata2 = array();
			foreach ($vacationstaff as $key => $item) {
				foreach ($registerarea as $key2 => $item2) {
					$tmp = $Model -> query("SELECT 
                net_staff_register.state,             
                count(net_staff_register.state)as count
                FROM
                net_user ,
                net_staff_user ,
                net_staff_register
                where net_user.user_id=net_staff_user.user_id and net_user.uname=net_staff_register.uname 
                  and net_staff_user.area='%s' 
                  and net_staff_register.time>='%s' and net_staff_register.time<='%s' 
                  and net_staff_register.uname='%s' and net_staff_register.state='%s'
                GROUP BY net_staff_register.state,net_staff_register.uname
                ", $area, I('post.begin_time'), I('post.end_time'), $item['uname'], $item2['name']);
					if ($tmp[0]['count'] == '') {
						$tmp[0]['count'] = 0;
					}
					if ($tmp[1]['count'] == '') {
						$tmp[1]['count'] = 0;
					}
					if ($tmp[2]['count'] == '') {
						$tmp[2]['count'] = 0;
					}					
					$data[$key2] = $tmp[0]['count'];
				}
				$registerdata2[$key] = $data;				
			}
			foreach ($registerdata2 as $key => $item) {
				$register[0][$key]['count']=$item[0];
				$register[1][$key]['count']=$item[1];
				$register[2][$key]['count']=$item[2];
				}
			$this -> registerdata2 = $register;
		//	$this -> ajaxReturn($register[1]);

			//////////////////////////////////////////////////////
			
			$this -> display();
		}
	}

	public function topdf() {

	}

	public function _empty($name) {
		echo "Not Found!";
	}

}
