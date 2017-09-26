<?php
// +----------------------------------------------------------------------
// | YFCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://www.rainfer.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: rainfer <81818832@qq.com>
// +----------------------------------------------------------------------
namespace app\home\controller;

use app\common\controller\Common;
use app\admin\model\Options;
use EasyWeChat\Foundation\Application;
use think\Db;

class Base extends Common
{
    protected $view;
    protected $user;
    protected $yf_theme_path;
    //微信平台
    protected $options=[
        /**
         * Debug 模式，bool 值：true/false
         *
         * 当值为 false 时，所有的日志都不会记录
         */
        'debug'  => true,
        /**
         * 账号基本信息，请从微信公众平台/开放平台获取
         */
        'app_id' => '',
        'secret' => '',
        'token'  => '',
        'aes_key' => '',
        'we_name'=>'',
        'we_id'=>'',
        'we_number'=>'',
        'we_type'=>1,
        /**
         * 日志配置
         *
         * level: 日志级别, 可选为：
         *         debug/info/notice/warning/error/critical/alert/emergency
         * permission：日志文件权限(可选)，默认为null（若为null值,monolog会取0644）
         * file：日志文件位置(绝对路径!!!)，要求可写权限
         */
        'log' => [
            'level'      => 'debug',
            'permission' => 0777,
            'file'       => './data/runtime/temp/easywechat.log',
        ],
        /**
         * OAuth 配置
         *
         * scopes：公众平台（snsapi_userinfo / snsapi_base），开放平台：snsapi_login
         * callback：OAuth授权完成后的回调页地址
         */
        'oauth' => [
            'scopes'   => ['snsapi_userinfo'],
            'callback' => '/examples/oauth_callback.php',
        ],
        /**
         * 微信支付
         */
        'payment' => [
            'merchant_id'        => 'your-mch-id',
            'key'                => 'key-for-signature',
            'cert_path'          => 'path/to/your/cert.pem', // XXX: 绝对路径！！！！
            'key_path'           => 'path/to/your/key',      // XXX: 绝对路径！！！！
            // 'device_info'     => '013467007045764',
            // 'sub_app_id'      => '',
            // 'sub_merchant_id' => '',
            // ...
        ],
        /**
         * Guzzle 全局设置
         *
         * 更多请参考： http://docs.guzzlephp.org/en/latest/request-options.html
         */
        'guzzle' => [
            'timeout' => 300.0, // 超时时间（秒）
            //'verify' => false, // 关掉 SSL 认证（强烈不建议！！！）
        ],
    ];
    protected $jsApiList = [
        "onMenuShareTimeline",//分享到朋友圈
        "onMenuShareAppMessage",//分享给朋友
        "onMenuShareQQ",//分享到QQ
        "onMenuShareWeibo",//分享到腾讯微博
        "onMenuShareQZone",//分享到QQ空间
        "startRecord",//开始录音接口
        "stopRecord",//停止录音接口
        "onVoiceRecordEnd",//监听录音自动停止接口
        "playVoice",//播放语音接口
        "pauseVoice",//暂停播放接口
        "stopVoice",//停止播放接口
        "onVoicePlayEnd",//监听语音播放完毕接口
        "uploadVoice",//上传语音接口
        "downloadVoice",//下载语音接口
        "chooseImage",//拍照或从手机相册中选图接口
        "previewImage",//预览图片接口
        "uploadImage",//上传图片接口
        "downloadImage",//下载图片接口
        "getLocalImgData",//获取本地图片接口
        "translateVoice",//识别音频并返回识别结果接口
        "getNetworkType",//获取网络状态接口
        "openLocation",//使用微信内置地图查看位置接口
        "getLocation",//获取地理位置接口
        "startSearchBeacons",//开启查找周边ibeacon设备接口
        "stopSearchBeacons",//关闭查找周边ibeacon设备接口
        "onSearchBeacons",//监听周边ibeacon设备接口
        "hideOptionMenu",
        "showOptionMenu",
        "hideMenuItems",//批量隐藏功能按钮接口
        "showMenuItems",//批量显示功能按钮接口
        "hideAllNonBaseMenuItem",//隐藏所有非基础按钮接口
        "showAllNonBaseMenuItem",//显示所有功能按钮接口
        "closeWindow",//关闭当前网页窗口接口
        "scanQRCode",//调起微信扫一扫接口
        "chooseWXPay",//发起一个微信支付请求
        "openProductSpecificView",//跳转微信商品页接口
        "addCard",//批量添加卡券接口
        "chooseCard",//拉取适用卡券列表并获取用户选择信息
        "openCard",//查看微信卡包中的卡券接口
    ];

    protected function _initialize()
    {
        parent::_initialize();
        //主题
        $site_options = Options::get_options('site_options', $this->lang);
        $site_options['site_tongji'] = htmlspecialchars_decode($site_options['site_tongji']);
        $site_options['site_copyright'] = htmlspecialchars_decode($site_options['site_copyright']);
        if (request()->isMobile()) {
            $theme = $site_options['site_tpl_m'] ?: $site_options['site_tpl'];
        } else {
            $theme = $site_options['site_tpl'];
        }
        $this->view = $this->view->config('view_path', APP_PATH . request()->module() . '/view/' . $theme . '/');
        $yf_theme_path = __ROOT__ . '/app/home/view/' . $theme . '/';
        $this->assign($site_options);
        $this->assign('yf_theme_path', $yf_theme_path);
        $address = '';
        $this->user = array();
        $uid = session('hid');
        if (empty($uid)) {
            //检测cookies
            $cookie = cookie('yf_logged_user');//'id'.'时间'
            $cookie = explode(".", jiemi($cookie));
            $uid = empty($cookie[0]) ? 0 : $cookie[0];
            if ($uid && !empty($cookie[1])) {
                //判断是否存在此用户
                $member = Db::name("member_list")->find($uid);
                if ($member && (time() - intval($cookie[1])) < config('cookie.expire')) {
                    //更新字段
                    $data = array(
                        'last_login_time' => time(),
                        'last_login_ip' => request()->ip(),
                    );
                    Db::name("member_list")->where(array('member_list_id' => $member["member_list_id"]))->update($data);
                    $member['last_login_time'] = $data['last_login_time'];
                    $member['last_login_ip'] = $data['last_login_ip'];
                    //设置session
                    session('hid', $member['member_list_id']);
                    session('user', $member);
                }
            }
        }
        $is_admin = false;
        if (session('hid')) {
            $this->user = Db::name('member_list')->find(session('hid'));
            if (!empty($this->user['member_list_province'])) {
                $rst = Db::name('region')->field('name')->find($this->user['member_list_province']);
                $address .= $rst ? $rst['name'] . lang('province') : '';
            }
            if (!empty($this->user['member_list_city'])) {
                $rst = Db::name('region')->field('name')->find($this->user['member_list_city']);
                $address .= $rst ? $rst['name'] . lang('city') : '';
            }
            if (!empty($this->user['member_list_town'])) {
                $rst = Db::name('region')->field('name')->find($this->user['member_list_town']);
                $address .= $rst ? $rst['name'] : '';
            }
            //判断是否为管理员
            $admin = Db::name('admin')->where('member_id', $this->user['member_list_id'])->find();
            if ($admin) {
                $is_admin = true;
            }
        }
        //微信配置
        $config=config('we_options');
        if(!empty($config)) $this->options=array_merge($this->options,$config);
        $app = new Application($this->options);
        $js = $app->js;
        $wxconfig = $js->config($this->jsApiList, $debug = true, $beta = false, $json = true);
        $this->user['address'] = $address;
        $this->assign('wxconfig',$wxconfig);
        $this->assign("user", $this->user);
        $this->assign("is_admin", $is_admin);
    }

    /**
     * 检查用户登录
     */
    protected function check_login()
    {
        if (!session('hid')) {
            $this->error(lang('not logged'), __ROOT__ . "/");
        }
    }

    /**
     * 检查操作频率
     * @param int $t_check 距离最后一次操作的时长
     */
    protected function check_last_action($t_check)
    {
        $action = MODULE_NAME . "-" . CONTROLLER_NAME . "-" . ACTION_NAME;
        $time = time();
        $action_s = session('last_action.action');
        if (!empty($action_s) && $action = $action_s) {
            $t = $time - session('last_action.time');
            if ($t_check > $t) {
                $this->error(lang('frequent operation'));
            } else {
                session('last_action.time', $time);
            }
        } else {
            session('last_action.action', $action);
            session('last_action.time', $time);
        }
    }

    /**
     * 上传文件方法
     * @param array $file
     * @param array $files
     * @param string $backUrl
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
                    $info = $file[0]->validate($validate)->rule('uniqid')->move(ROOT_PATH . config('upload_path') . DS . date('Y-m-d'));
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