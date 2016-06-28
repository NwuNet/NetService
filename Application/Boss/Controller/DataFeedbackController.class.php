<?php
namespace Boss\Controller;
use Think\Controller;
class DataFeedbackController extends BaseController {
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
			$begin_time = I('post.begin_time');
			$end_time = I('post.end_time');
			$this -> begin_time = I('post.begin_time');
			$this -> end_time = I('post.end_time');
			//--------------------------area-----------------------
			$Area = M('UserArea');
			$userarea = $Area -> select();
			$this -> assign('userarea', $userarea);

			$Suggest = M('Suggest');
			$ServiceEvaluate = M('ServiceEvaluate');
			$Model = new \Think\Model();

			$time_begin = date('Y-m-d 00:00:00', strtotime($begin_time));
			$time_end = date('Y-m-d 23:59:59', strtotime($end_time));

			//-----------------------------建议反馈 ---------------------------
			$suggest = array();
			$tmpsuggest = $Model -> query("SELECT 
                net_suggest.id,
                net_suggest.time,
                net_suggest.uname,
                net_home_user.area
                FROM
                net_suggest ,
                net_home_user
                WHERE net_home_user.number=net_suggest.student_no               
                and net_suggest.time>='%s' and net_suggest.time<='%s'                  
                ", $time_begin, $end_time);
			//-------------找出有记录的日期--------------
			$tmpsuggesttime = $Model -> query("SELECT             
                net_suggest.time  FROM  net_suggest  
                WHERE net_suggest.time>='%s' and net_suggest.time<='%s'                
                ", $time_begin, $end_time);
			foreach ($tmpsuggesttime as $key => $item) {
				$tmpsuggesttime[$key]['time'] = date('Y-m-d ', strtotime($item['time']));
			}

			$k = 0;
			$tmpsuggesttime2 = $tmpsuggesttime;
			foreach ($tmpsuggesttime as $key => $item) {
				$count = 0;
				foreach ($tmpsuggesttime2 as $key2 => $item2) {
					if ($item['time'] == $item2['time']) {
						$count = $count + 1;
						unset($tmpsuggesttime2[$key2]);
					}
				}
				if ($count != 0) {
					$suggesttime[$k] = $item;
					$k = $k + 1;
				}
			}
			$this -> suggesttime = $suggesttime;

			foreach ($userarea as $key => $item) {
				$i = 0;
				foreach ($tmpsuggest as $key2 => $item2) {
					if ($item['area'] == $item2['area']) {
						$i = $i + 1;
						$item2['time'] = date('Y-m-d ', strtotime($item2['time']));
						$suggest[$key][$i] = $item2;
					}
				}
			}

			//	$this -> ajaxReturn($suggesttime);

			//-------------------按校区分类，以日期记录用户建议次数----------------
			foreach ($suggest as $key => $item) {
				$dsuggest = $item;
				//	$dsuggest2 = $item;$k = 0;
				foreach ($suggesttime as $key2 => $item2) {
					$count = 0;

					$datasuggest[$key][$key2]['time'] = $item2['time'];
					foreach ($dsuggest as $key3 => $item3) {
						if ($item2['time'] == $item3['time']) {
							$count = $count + 1;

							$datasuggest[$key][$key2]['count'] = $count;
							//	  unset($dsuggest2[$key3]);
							//	array_splice($dsuggest2, $key3, 1);
						}

					}
					if ($count == 0) {
						$datasuggest[$key][$key2]['count'] = 0;
					}
				}

			}

			$this -> suggestdata = $datasuggest;
			//---------各校区用户建议总次数------------
			foreach ($datasuggest as $key => $item) {
				$ds = $item;
				$count = 0;
				foreach ($ds as $key2 => $item2) {
					$count = $count + $item2['count'];
					$suggestareacount[$key] = $count;
				}

			}
			$this -> suggestareacount = $suggestareacount;
			//   	   $this -> ajaxReturn($suggestareacount);

			//-----------------------------服务评价 ---------------------------
			$evaluate = array();
			$tmpevaluate = $Model -> query("SELECT 
               net_service_evaluate.id,
               net_service_evaluate.servicecard_id,
               net_service_evaluate.time,
               net_service_card.area
               FROM
               net_service_evaluate ,
               net_service_card
               WHERE net_service_evaluate.servicecard_id=net_service_card.id               
               and net_service_evaluate.time>='%s' and net_service_evaluate.time<='%s'                  
               ", $time_begin, $end_time);
			//-------------找出有记录的日期--------------
			$tmpevaluatetime = $Model -> query("SELECT             
                net_service_evaluate.time  FROM  net_service_evaluate  
                WHERE net_service_evaluate.time>='%s' and net_service_evaluate.time<='%s'                
                ", $time_begin, $end_time);
			foreach ($tmpevaluatetime as $key => $item) {
				$tmpevaluatetime[$key]['time'] = date('Y-m-d ', strtotime($item['time']));
			}

			$k = 0;
			$tmpevaluatetime2 = $tmpevaluatetime;
			foreach ($tmpevaluatetime as $key => $item) {
				$count = 0;
				foreach ($tmpevaluatetime2 as $key2 => $item2) {
					if ($item['time'] == $item2['time']) {
						$count = $count + 1;
						unset($tmpevaluatetime2[$key2]);
					}
				}
				if ($count != 0) {
					$evaluatetime[$k] = $item;
					$k = $k + 1;
				}
			}
			$this -> evaluatetime = $evaluatetime;

			foreach ($userarea as $key => $item) {
				$i = 0;
				foreach ($tmpevaluate as $key2 => $item2) {
					if ($item['area'] == $item2['area']) {
						$i = $i + 1;
						$item2['time'] = date('Y-m-d ', strtotime($item2['time']));
						$evaluate[$key][$i] = $item2;
					}
				}
			}

			//	$this -> ajaxReturn($evaluatetime);

			//-------------------按校区分类，以日期记录服务评价次数----------------
			foreach ($evaluate as $key => $item) {
				$devaluate = $item;
				//	$devaluate2 = $item;$k = 0;
				foreach ($evaluatetime as $key2 => $item2) {
					$count = 0;

					$dataevaluate[$key][$key2]['time'] = $item2['time'];
					foreach ($devaluate as $key3 => $item3) {
						if ($item2['time'] == $item3['time']) {
							$count = $count + 1;

							$dataevaluate[$key][$key2]['count'] = $count;
							//	  unset($devaluate2[$key3]);
							//	array_splice($devaluate2, $key3, 1);
						}

					}
					if ($count == 0) {
						$dataevaluate[$key][$key2]['count'] = 0;
					}
				}

			}

			//	   $this -> ajaxReturn($dataevaluate);
			$this -> evaluatedata = $dataevaluate;
			//---------各校区服务评价总次数------------
			foreach ($dataevaluate as $key => $item) {
				$ds = $item;
				$count = 0;
				foreach ($ds as $key2 => $item2) {
					$count = $count + $item2['count'];
					$evaluateareacount[$key] = $count;
				}

			}
			$this -> evaluateareacount = $evaluateareacount;

		}
		$this -> display();
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

			$Model = new \Think\Model();

			$time_begin = date('Y-m-d 00:00:00', strtotime($begin_time));
			$time_end = date('Y-m-d 23:59:59', strtotime($end_time));

			$this -> area = $area;

			//-----------------------------建议反馈 ---------------------------
			$suggest = array();
			$tmpsuggest = $Model -> query("SELECT 
                net_suggest.id,
                net_suggest.time,
                net_suggest.uname                
                FROM
                net_suggest ,
                net_home_user
                WHERE net_home_user.number=net_suggest.student_no  
                and net_home_user.area='%s'             
                and net_suggest.time>='%s' and net_suggest.time<='%s'                  
                ", $area, $time_begin, $end_time);
			//-------------找出有记录的日期--------------
			foreach ($tmpsuggest as $key => $item) {
				$tmpsuggesttime[$key]['time'] = date('Y-m-d ', strtotime($item['time']));
				$tmpsuggest[$key]['time'] = date('Y-m-d ', strtotime($item['time']));
			}
			//		$this -> ajaxReturn($tmpsuggesttime);

			$k = 0;
			$tmpsuggesttime2 = $tmpsuggesttime;
			foreach ($tmpsuggesttime as $key => $item) {
				$count = 0;
				foreach ($tmpsuggesttime2 as $key2 => $item2) {
					if ($item['time'] == $item2['time']) {
						$count = $count + 1;
						unset($tmpsuggesttime2[$key2]);
					}
				}
				if ($count != 0) {
					$suggesttime[$k] = $item;
					$k = $k + 1;
				}
			}
			$this -> suggesttime = $suggesttime;
			//	$this -> ajaxReturn($suggesttime);
			//------------以日期记录用户建议次数----------------

			foreach ($suggesttime as $key => $item) {
				$count = 0;
				$datasuggest[$key]['time'] = $item['time'];
				foreach ($tmpsuggest as $key2 => $item2) {
					if ($item2['time'] == $item['time']) {
						$count = $count + 1;
						$datasuggest[$key]['count'] = $count;
					}
				}
				if ($count == 0) {
					$datasuggest[$key]['count'] = 0;
				}
			}
			$this -> suggestdata = $datasuggest;

			//-----------------------------服务评价 ---------------------------
			$tmpevaluate = $Model -> query("SELECT 
               net_service_evaluate.id, 
               net_service_card.name,          
               net_service_evaluate.time
               FROM
               net_service_evaluate ,
               net_service_card
               WHERE net_service_evaluate.servicecard_id=net_service_card.id 
                and net_service_card.area='%s'             
                and net_service_evaluate.time>='%s' and net_service_evaluate.time<='%s'                  
                ", $area, $time_begin, $end_time);
			//-------------找出有记录的日期--------------
			foreach ($tmpevaluate as $key => $item) {
				$tmpevaluatetime[$key]['time'] = date('Y-m-d ', strtotime($item['time']));
				$tmpevaluate[$key]['time'] = date('Y-m-d ', strtotime($item['time']));
			}
			//		$this -> ajaxReturn($tmpevaluatetime);

			$k = 0;
			$tmpevaluatetime2 = $tmpevaluatetime;
			foreach ($tmpevaluatetime as $key => $item) {
				$count = 0;
				foreach ($tmpevaluatetime2 as $key2 => $item2) {
					if ($item['time'] == $item2['time']) {
						$count = $count + 1;
						unset($tmpevaluatetime2[$key2]);
					}
				}
				if ($count != 0) {
					$evaluatetime[$k] = $item;
					$k = $k + 1;
				}
			}
			$this -> evaluatetime = $evaluatetime;
			//	$this -> ajaxReturn($evaluatetime);
			//------------以日期记录用户建议次数----------------

			foreach ($evaluatetime as $key => $item) {
				$count = 0;
				$dataevaluate[$key]['time'] = $item['time'];
				foreach ($tmpevaluate as $key2 => $item2) {
					if ($item2['time'] == $item['time']) {
						$count = $count + 1;
						$dataevaluate[$key]['count'] = $count;
					}
				}
				if ($count == 0) {
					$dataevaluate[$key]['count'] = 0;
				}
			}
			$this -> evaluatedata = $dataevaluate;

			$this -> display();

		}
	}

	public function _empty($name) {
		echo "Not Found!";
	}

}
