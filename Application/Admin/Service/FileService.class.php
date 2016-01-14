<?php
namespace Admin\Service;
use Think\Model;
/**
 * 系统缓存服务
 */

class FileService {

	/**
	 * 获取缓存列表
	 * @param int $key 缓存key
	 * @return array 用户信息
	 */
	public function getFile($key = '') {
		$path = array(
			'Admin' => APP_PATH . 'Admin/', 
			'Boss' => APP_PATH . 'Boss/', 
			'Home' => APP_PATH . 'Home/', 
			'Staff' => APP_PATH . 'Staff/', 
			'Cache' => CACHE_PATH, 
			'Log' => LOG_PATH, 
			'Html' => HTML_PATH, 
			'App' => TEMP_PATH, 
			'Data' => DATA_PATH, 
			'Run' => RUNTIME_PATH . 'common~runtime.php', 
			'Backup' => './Backup/', 
			'Images' => './Public/Images/'
		);
		$list = array(
			'Admin' => array(
				'id' => 'Admin', 
				'name' => 'Admin模块', 
				'dir' => $path['Admin'], 
				'size' => sizeFormat(dir_size($path['Admin'])), 
				'level' => sizeFormat(dir_size($path['Admin']), 'level')
			), 
			'Boss' => array(
				'id' => 'Boss', 
				'name' => 'Boss模块', 
				'dir' => $path['Boss'], 
				'size' => sizeFormat(dir_size($path['Boss'])), 
				'level' => sizeFormat(dir_size($path['Boss']), 'level')
			), 
			'Home' => array(
				'id' => 'Home', 
				'name' => 'Home模块', 
				'dir' => $path['Home'], 
				'size' => sizeFormat(dir_size($path['Home'])), 
				'level' => sizeFormat(dir_size($path['Home']), 'level')
			), 
			'Staff' => array(
				'id' => 'Staff', 
				'name' => 'Staff模块', 
				'dir' => $path['Staff'], 
				'size' => sizeFormat(dir_size($path['Staff'])), 
				'level' => sizeFormat(dir_size($path['Staff']), 'level')
			), 
			'Html' => array(
				'id' => 'Html', 
				'name' => '静态缓存', 
				'dir' => $path['Html'], 
				'size' => sizeFormat(dir_size($path['Html'])), 
				'level' => sizeFormat(dir_size($path['Html']), 'level')
			), 
			'Cache' => array(
				'id' => 'Cache', 
				'name' => '模板缓存', 
				'dir' => $path['Cache'], 
				'size' => sizeFormat(dir_size($path['Cache'])), 
				'level' => sizeFormat(dir_size($path['Cache']), 'level')
			), 
			'Data' => array(
				'id' => 'Data', 
				'name' => '数据缓存', 
				'dir' => $path['Data'], 
				'size' => sizeFormat(dir_size($path['Data'])), 
				'level' => sizeFormat(dir_size($path['Data']), 'level')
			), 
			'Log' => array(
				'id' => 'Log', 
				'name' => '日志缓存', 
				'dir' => $path['Log'], 
				'size' => sizeFormat(dir_size($path['Log'])), 
				'level' => sizeFormat(dir_size($path['Log']), 'level')
			), 
			'App' => array(
				'id' => 'App', 
				'name' => '应用缓存', 
				'dir' => $path['App'], 
				'size' => sizeFormat(dir_size($path['App'])), 
				'level' => sizeFormat(dir_size($path['App']), 'level')
			), 
			'Run' => array(
				'id' => 'Run', 
				'name' => '运行缓存', 
				'dir' => $path['Run'], 
				'size' => sizeFormat(filesize($path['Run'])), 
				'level' => sizeFormat(filesize($path['Run']), 'level')
			), 
			'Backup' => array(
				'id' => 'Backup', 
				'name' => '数据库备份', 
				'dir' => $path['Backup'], 
				'size' => sizeFormat(dir_size($path['Backup'])), 
				'level' => sizeFormat(dir_size($path['Backup']), 'level')
			), 
			'Images' => array(
				'id' => 'Images', 
				'name' => '图片目录', 
				'dir' => $path['Images'], 
				'size' => sizeFormat(dir_size($path['Images'])), 
				'level' => sizeFormat(dir_size($path['Images']), 'level')
			)
		);
		if ($key) {
			return $list[$key];
		}
		return $list;
	}

	/**
	 * 文件处理服务
	 * @param string $Root,string $cmd,string $target
	 * @return array 
	 */
	public function mfile($ROOT, $cmd, $target) {
		switch($cmd) {
			case 'init' :
				return getArray('0', 'success', array('root' => getFileInfo('/', $ROOT), 'config' => array()));
				break;
			case 'ls' :
				if (isset($_GET['target']))
					$target = $_GET['target'];
				else
					$target = '';
				$list = listFile($target, $ROOT);
				return getArray('0', 'success', array('files' => $list));
				break;
			case 'rename' :
				$name = $_GET['name'];
				if (file_exists($ROOT . $name)) {
					$res = false;
					$msg = 'file exist';
				} else {
					$res = rename($ROOT . $target, $ROOT . $name);
				}
				if ($res) {
					return getArray('0', 'success', array('file' => getFileInfo($name, $ROOT)));
				} else {
					return getArray('1', $msg ? $msg : 'rename error');
				}
				break;
			case 'rm' :
				foreach ($target as $key => $path) {
					if (is_dir($ROOT . $path)) {
						$res = removeDir($ROOT . $path);
					} else {
						$res = unlink($ROOT . $path);
					}
					if (!$res)
						break;
				}

				if ($res) {
					return getArray('0', 'success');
				} else {
					return getArray('1', 'romove error');
				}
				break;
			case 'touch' :
				if (!file_exists($ROOT . $target)) {
					file_put_contents($ROOT . $target, '');
					$res = file_exists($ROOT . $target);
				} else {
					$res = false;
					$msg = 'file exist';
				}
				if ($res) {
					return getArray('0', 'success', array('file' => getFileInfo($target, $ROOT)));
				} else {
					return getArray('1', $msg ? $msg : 'touch error');
				}
				break;
			case 'mkdir' :
				if (!file_exists($ROOT . $target)) {
					$res = mkdir($ROOT . $target);
				} else {
					$res = false;
					$msg = 'file exist';
				}
				if ($res) {
					return getArray('0', 'success', array('file' => getFileInfo($target, $ROOT)));
				} else {
					return getArray('1', $msg ? $msg : 'mkdir error', array('file' => getFileInfo($target, $ROOT)));
				}
				break;
			case 'upload' :
				include "Uploader.class.php";
				$uploadConfig = array("savePath" => $ROOT . $target, //存储文件夹
				"maxSize" => 200000, //允许的文件最大尺寸，单位KB
				"allowFiles" => array(".rar", ".zip", ".7z", "tar", "gz", ".doc", ".docx", ".xls", ".xlsx", ".ppt", ".pptx", "", ".txt", ".pdf", ".bmp", ".gif", ".jpg", ".jpeg", ".png", ".psd", ".swf", ".mkv", ".avi", ".rm", ".rmvb", ".mpeg", ".mpg", ".ogg", ".mov", ".wmv", ".mp4", ".webm") //允许的文件格式
				);

				$up = new Uploader("file", $uploadConfig);
				$info = $up -> getFileInfo();
				if ($info["state"] == 'SUCCESS') {
					return getArray('0', 'success', array('file' => getFileInfo($target . $info["name"], $ROOT)));
				} else {
					return getArray('1', $info["state"], array('file' => getFileInfo($target . $info["name"], $ROOT)));
				}
				break;
			case 'download' :
				$path = $ROOT . $target;
				$info = getFileInfo($target, $ROOT);
				downloadFile($path, $info['name']);
				break;
			case 'info' :
				return getArray('0', 'success', array('file' => getFileInfo($target, $ROOT)));
				break;
			default :
				return getArray('1', 'unknow command');
				break;
		}/*switch*/
	}/*mfile*/
	
}
