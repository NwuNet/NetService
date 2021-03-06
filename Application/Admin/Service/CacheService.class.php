<?php
namespace Admin\Service;
use Think\Model;
/**
 * 系统缓存服务
 */

class CacheService{
	
	/**
     * 获取缓存列表
     * @param int $key 缓存key
     * @return array 用户信息
     */
    public function getCache($key = ''){
        $list = array(
            'Html' => array(
            	'id'=>'Html',
            	'name'=>'静态缓存',
            	'dir'=>HTML_PATH,
            	'size'=>sizeFormat(dir_size(HTML_PATH)), 
            	'level'=>sizeFormat(dir_size(HTML_PATH),'level')
			),
			'Tpl' => array(
				'id'=>'Tpl',
				'name'=>'模板缓存', 
				'dir'=>CACHE_PATH, 
				'size'=>sizeFormat(dir_size(CACHE_PATH)),
				'level'=>sizeFormat(dir_size(CACHE_PATH),'level')
			),
            'Data' => array(
            	'id'=>'Data',
            	'name'=>'数据缓存', 
            	'dir'=>DATA_PATH, 
            	'size'=>sizeFormat(dir_size(DATA_PATH)),
				'level'=>sizeFormat(dir_size(DATA_PATH),'level')
			),
			'Debug' => array(
				'id'=>'Debug',
				'name'=>'日志缓存', 
				'dir'=>LOG_PATH, 
				'size'=>sizeFormat(dir_size(LOG_PATH)),
				'level'=>sizeFormat(dir_size(LOG_PATH),'level')
			),
			'App' => array(
				'id'=>'App',
				'name'=>'应用缓存', 
				'dir'=>TEMP_PATH, 
				'size'=>sizeFormat(dir_size(TEMP_PATH)),
				'level'=>sizeFormat(dir_size(TEMP_PATH),'level')
			),
            'Run' => array(
            	'id'=>'Run',
            	'name'=>'运行缓存', 
            	'dir'=>RUNTIME_PATH.'common~runtime.php', 
            	'size'=>sizeFormat(filesize(RUNTIME_PATH.'common~runtime.php')),
				'level'=>sizeFormat(filesize(RUNTIME_PATH.'common~runtime.php'),'level')
			)
        );
        if($key){
            return $list[$key];
        }
        return $list;
    }
	/**
     * 清空指定缓存
     * @param int $key 缓存key
     * @return true/false
     */
    public function delCache($key){
        $info = $this->getCache($key);
        if(empty($info)){
            return;
        }
        $file = $info['dir'];
        if(is_dir($file)){
            del_dir($file);
        }elseif(is_file($file)){
            unlink($file);
        }
        return true;
    }
}
