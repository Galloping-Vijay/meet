<?php
// +----------------------------------------------------------------------
// | YFCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://www.rainfer.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: rainfer <81818832@qq.com>
// +----------------------------------------------------------------------
namespace app\common\controller;

use think\Controller;
use think\Db;
use think\Lang;
use think\captcha\Captcha;

class Common extends Controller
{
    // Request实例
	protected $lang;
	protected function _initialize()
    {
		parent::_initialize();
        if (!defined('__ROOT__')) {
            $_root = rtrim(dirname(rtrim($_SERVER['SCRIPT_NAME'], '/')), '/');
            define('__ROOT__', (('/' == $_root || '\\' == $_root) ? '' : $_root));
        }
		if (!file_exists(ROOT_PATH.'data/install.lock')) {
            //不存在，则进入安装
            header('Location: ' . url('install/Index/index'));
            exit();
        }
        if (!defined('MODULE_NAME')){define('MODULE_NAME', $this->request->module());}
        if (!defined('CONTROLLER_NAME')){define('CONTROLLER_NAME', $this->request->controller());}
        if (!defined('ACTION_NAME')){define('ACTION_NAME', $this->request->action());}
		// 多语言
		if(config('lang_switch_on')){
			$this->lang=Lang::detect();
		}else{
			$this->lang=config('default_lang');
		}
		$this->assign('lang',$this->lang);
	}
    //空操作
    public function _empty()
    {
        $this->error(lang('operation not valid'));
    }
	protected function verify_build($id)
	{
		ob_end_clean();
		$verify = new Captcha (config('verify'));
		return $verify->entry($id);
	}
	protected function verify_check($id)
	{
		$verify =new Captcha ();
		if (!$verify->check(input('verify'), $id)) {
			$this->error(lang('verifiy incorrect'),url(MODULE_NAME.'/Login/login'));
		}
	}
    protected function check_admin_login(){
		return model('admin/Admin')->is_login();
    }

    /**
     * 上传文件方法
     * @param array $file 单图
     * @param array $files 多图
     * @param string $backUrl 出错时回调地址
     * @return array
     */
    public function fileUpload($file = [], $files = [], $backUrl = '/')
    {
        //上传图片部分
        $img_one = '';
        $picall_url = '';
        if ($file || $files) {
            if (config('storage.storage_open')) {
                //七牛
                $upload = \Qiniu::instance();
                $info = $upload->upload();
                $error = $upload->getError();
                if ($info) {
                    if ($file && $files) {
                        //有单图、多图
                        if (!empty($info['pic_one'])) $img_one = config('storage.domain') . $info['pic_one'][0]['key'];
                        if (!empty($info['pic_all'])) {
                            foreach ($info['pic_all'] as $file) {
                                $img_url = config('storage.domain') . $file['key'];
                                $picall_url = $img_url . ',' . $picall_url;
                            }
                        }
                    } elseif ($file) {
                        //单图
                        $img_one = config('storage.domain') . $info[0]['key'];
                    } else {
                        //多图
                        foreach ($info as $file) {
                            $img_url = config('storage.domain') . $file['key'];
                            $picall_url = $img_url . ',' . $picall_url;
                        }
                    }
                } else {
                    $this->error($error, url($backUrl));//否则就是上传错误，显示错误原因
                }
            } else {
                $validate = config('upload_validate');
                //单图
                if ($file) {
                    //$info = $file[0]->validate($validate)->rule('uniqid')->move(ROOT_PATH . config('upload_path') . DS . date('Y-m-d'));
                    $info = $file->validate($validate)->rule('uniqid')->move(ROOT_PATH . config('upload_path') . DS . date('Y-m-d'));
                    if ($info) {
                        $img_url = config('upload_path') . '/' . date('Y-m-d') . '/' . $info->getFilename();
                        //写入数据库
                        $data['uptime'] = time();
                        $data['filesize'] = $info->getSize();
                        $data['path'] = $img_url;
                        Db::name('plug_files')->insert($data);
                        $img_one = $img_url;
                    } else {
                        $this->error($file->getError(), url($backUrl));//否则就是上传错误，显示错误原因
                    }
                }
                //多图
                if ($files) {
                    foreach ($files as $file) {
                        $info = $file->validate($validate)->rule('uniqid')->move(ROOT_PATH . config('upload_path') . DS . date('Y-m-d'));
                        if ($info) {
                            $img_url = config('upload_path') . '/' . date('Y-m-d') . '/' . $info->getFilename();
                            //写入数据库
                            $data['uptime'] = time();
                            $data['filesize'] = $info->getSize();
                            $data['path'] = $img_url;
                            Db::name('plug_files')->insert($data);
                            $picall_url = $img_url . ',' . $picall_url;
                        } else {
                            $this->error($file->getError(), url($backUrl));//否则就是上传错误，显示错误原因
                        }
                    }
                }
            }
        }

        $imgInfo = [
            'picall_url' => $picall_url,
            'img_one' => $img_one,
        ];
        return $imgInfo;
    }
}