<?php
// +----------------------------------------------------------------------
// | Index.
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.meetoyou.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wjf <1937832819@qq.com> 2017-09-28
// +----------------------------------------------------------------------
namespace app\wechat\controller;

use app\common\lib\Download;
use program\tuling\Tuling;
use think\Db;
use EasyWeChat\Message\Text;
use EasyWeChat\Message\Image;
use EasyWeChat\Message\Voice;
use EasyWeChat\Message\News;
use EasyWeChat\Foundation\Application;

class Index extends WeBase
{
    public function _initialize()
    {
        //parent::_initialize();
        //微信平台
        $config = config('we_options');
        if (!empty($config)) $this->options = array_merge($this->options, $config);
        $this->app = new Application($this->options);
        config('app_debug', false);
        config('app_trace', false);
        if (input('echostr') && $this->checkSignature()) {
            //验证token
            return input('echostr');
        }
    }

    public function index()
    {
        $apps = $this->app;
        //消息处理
        $this->app->server->setMessageHandler(function ($message) use (&$apps) {
            switch ($message->MsgType) {
                case 'event':
                    # 事件消息...
                    switch ($message->Event) {
                        case 'subscribe':
                            # code...
                            $we_reply_list = Db::name('we_reply')->where('we_reply_key', 'subscribe')->find();
                            if ($we_reply_list) {
                                switch ($we_reply_list['we_reply_type']) {
                                    case 'text'://回复文本
                                        $text = new Text(['content' => $we_reply_list['we_replytext_content']]);
                                        return $text;
                                        break;
                                    case 'image'://回复图片
                                        $new = new Image(['media_id' => $we_reply_list['we_replyimage_mediaid']]);
                                        return $new;
                                        break;
                                    case 'voice'://回复语音
                                        $new = new Voice(['media_id' => $we_reply_list['we_replyvoice_mediaid']]);
                                        return $new;
                                        break;
                                    case 'news'://回复图文消息
                                        $news = json_decode($we_reply_list['we_replynews'], true);
                                        $new = new News($news);
                                        return $new;
                                        break;
                                    default:
                                        $res = Tuling::handle()->param('你好啊')->answer();
                                        return $res['content'];
                                        break;
                                }
                            } else {
                                $res = Tuling::handle()->param('你好啊')->answer();
                                return $res['content'];
                                break;
                            }
                        case 'unsubscribe':
                            # code...
                            //取消关注
                            break;
                        case 'CLICK':
                            # code...
                            //点击自定义click菜单
                            switch ($message->EventKey) {
                                case 'key1'://如果为key1菜单,执行
                                    break;
                                default :
                                    # code...
                                    break;
                            }
                            break;
                        default :
                            # code...
                            break;
                    }
                    break;
                case 'text':
                    # 文字消息...
                    $we_reply_list = Db::name('we_reply')->where('we_reply_key', 'like', '%' . $message->Content . '%')->find();
                    if (empty($we_reply_list)) {
                        $text = new Text(['content' => $message->FromUserName]);
                        return $text;
                        /*$res = Tuling::handle()->param($message->Content)->answer();
                        switch ($res['resultType']) {
                            case 'text':
                                return $res['content'];
                                break;
                            case 'image':
                                //上传文件并返回路径
                                $path = Download::handle()->downloadImage($res['content']);
                                //微信临时素材返回数据
                                $material = $apps->material_temporary;
                                $result = $material->uploadImage($path);
                                return new Image(['media_id' => $result->media_id]);
                                break;
                        }*/

                    } else {
                        switch ($we_reply_list['we_reply_type']) {
                            case 'text'://回复文本
                                $text = new Text(['content' => $we_reply_list['we_replytext_content']]);
                                return $text;
                                break;
                            case 'image'://回复图片
                                $new = new Image(['media_id' => $we_reply_list['we_replyimage_mediaid']]);
                                return $new;
                                break;
                            case 'voice'://回复语音
                                $new = new Voice(['media_id' => $we_reply_list['we_replyvoice_mediaid']]);
                                return $new;
                                break;
                            case 'news'://回复图文消息
                                $news = json_decode($we_reply_list['we_replynews'], true);
                                $new = new News($news);
                                return $new;
                                break;
                            default:
                                $res = Tuling::handle()->param('你好啊')->answer();
                                return $res['content'];
                                break;
                        }
                    }
                    break;
                case 'image':
                    # 图片消息...
                    //图灵返回的图片结果
                    $res = Tuling::handle()->param($message->PicUrl, 1)->answer();
                    if ($res['resultType'] == 'image') {
                        //上传文件并返回路径
                        $path = Download::handle()->downloadImage($res['content']);
                        //微信临时素材返回数据
                        $material = $apps->material_temporary;
                        $result = $material->uploadImage($path);
                        return new Image(['media_id' => $result->media_id]);
                    } else {
                        return $res['content'];
                    }
                    break;
                case 'voice':
                    # 语音消息...
                    break;
                case 'video':
                    # 视频消息...
                    break;
                case 'location':
                    # 坐标消息...
                    break;
                case 'link':
                    # 链接消息...
                    break;
                // ... 其它消息
                default:
                    # code...
                    break;
            }
        });
        $this->app->server->serve()->send();
    }

    private function checkSignature()
    {
        $signature = input('signature');
        $timestamp = input('timestamp');
        $nonce = input('nonce');
        $token = $this->options['token'];
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);
        if ($tmpStr == $signature) {
            return true;
        } else {
            return false;
        }
    }
}