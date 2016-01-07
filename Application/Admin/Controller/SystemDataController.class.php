<?php
namespace Admin\Controller;
use Think\Controller;
class SystemDataController extends BaseController {
	/*
	 * 显示界面
	 * */
	public function index() {
		$this -> fileDir = './Backup' . DIRECTORY_SEPARATOR;
		$table = D('Database') -> backupList();
		$list = D('Database') -> loadTableList();
		$this -> assign('list', $list);
		$this -> assign('table', $table);
		$this -> display();
	}

	/*
	 * 备份数据库
	 * */
	public function backup() {
		if (IS_POST) {
			$action = 'backupData';
			if ( D('Database') -> $action()) {
				$this -> ajaxReturn(TRUE);
			} else {
				$msg =    D('Database') -> error;
				$this -> ajaxReturn(FALSE);
			}
		}
	}

	/*
	 * 优化
	 * */
	public function optimize() {
		if (IS_POST) {
			$action = 'optimizeData';
			if ( D('Database') -> $action()) {
				$this -> ajaxReturn(TRUE);
			} else {
				$msg =    D('Database') -> error;
				$this -> ajaxReturn(FALSE);
			}
		}
	}

	/*
	 * 修复
	 * */
	public function repair() {
		if (IS_POST) {
			$action = 'repairData';
			if ( D('Database') -> $action()) {
				$this -> ajaxReturn(TRUE);
			} else {
				$msg =    D('Database') -> error;
				$this -> ajaxReturn(FALSE);
			}
		}
	}

	/*
	 * 下载备份
	 * */
	public function downbackup($name) {//备份下载
		if (!empty($name)) {
			$file_path = "./Backup/" . $name;
			trace($file_path);
			if (!file_exists($file_path)) {
				echo "没有该文件文件";
				return;
			}
			$file_size = filesize($file_path);
			//下载文件需要用到的头
			Header("Content-type: application/x-tar");
			Header("Content-Disposition: attachment; filename=" . $name);
			Header("Accept-Length:" . $file_size);
			readfile($file_path);
		}
	}

	/*
	 * 删除备份
	 * */
	public function deletebackup() {//备份删除
		if (IS_POST) {
			$time = I('post.time');
			if (!empty($time)) {
				if ( D('Database') -> delData($time)) {
					$this -> ajaxReturn(TRUE);
				} else {$this -> ajaxReturn(FALSE);
				}
			} else {$this -> ajaxReturn(FALSE);
			}
		}
	}

	/*
	 * 还原备份
	 * */
	public function restorebackup() {
		if (IS_POST) {
			$time = I('post.time');
			trace($time);
			if (!empty($time)) {
				if ( D('Database') -> importData($time)) {
					$this -> ajaxReturn(TRUE);
				} else {$this -> ajaxReturn(FALSE);
				}
			} else {$this -> ajaxReturn(FALSE);
			}
		}
	}

}
