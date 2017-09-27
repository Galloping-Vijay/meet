<?php
// +----------------------------------------------------------------------
// | Rbac.权限认证
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.meetoyou.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wjf <184521508@qq.com> 2017/9/27
// +----------------------------------------------------------------------

namespace app\common\lib;

use think\Config;
use think\Loader;
use think\Request;

class Rbac
{
    //最后一次的错误信息
    protected $error = '';
    //无需验证列表
    protected $passList = [];
    //requset
    protected $request = null;
    //当前模块
    protected $module = null;
    //当前控制器
    protected $controller = null;
    //当前方法
    protected $action = null;

    /**
     * 构造函数
     * Rbac constructor.
     */
    public function __construct()
    {
        $this->request = request();
        $notAuthList = Config::get('not_auth_list');
        if (!empty($notAuthList)) {
            $this->passList = array_merge($this->passList, $notAuthList);
        }
    }

    /**
     * 获取最后一次错误信息
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * 获取操作句柄
     * @return Rbac
     */
    public static function handle()
    {
        static $handle = null;
        if (is_null($handle)) {
            $handle = new self();
        }
        return $handle;
    }

    /**
     * 设置 request 对象
     * @param Request|null $request
     * @return $this
     */
    public function request(Request $request = null)
    {
        if (is_null($request)) {
            $this->request = request();
        } else {
            $this->request = $request;
        }
        return $this;
    }

    /**
     * 设置无需验证的操作
     * @param $pass 格式 模块/控制器/方法
     * @return $this
     */
    public function pass($pass)
    {
        if (is_array($pass)) {
            $this->passList = array_merge($this->passList, $pass);
        } else {
            $this->passList[] = $pass;
        }
        return $this;
    }

    /**
     * 设置用户权限
     * @param array $accessList
     * @return $this
     */
    public function saveAccess(array $accessList)
    {
        session('_ACCESS_LIST', $accessList);
        return $this;
    }

    /**
     * 权限认证的过滤器方法
     * @return bool
     */
    public function access()
    {
        return $this->authenticate();
    }

    /**
     * 检查当前操作是否需要验证
     * @return bool true需要验证，false不需要验证
     */
    public function isCheck()
    {
        $acc = "{$this->module}/{$this->controller}/{$this->action}";
        if (in_array($acc, $this->passList)) {
            return false;
        }
        return true;
    }

    /**
     * 权限检查
     * @param string $map [模块/控制器/方法]，没有时，自动获取当前进行判断
     * @return boolean
     */
    public function authenticate($map = '')
    {
        list($this->module, $this->controller, $this->action) = self::supplement($map);
        $this->controller = Loader::parseName($this->controller);
        $keyName = strtolower("{$this->module}/{$this->controller}/{$this->action}");
        //检查是否需要认证
        if ($this->isCheck()) {
            //存在认证识别号，则进行进一步的访问决策
            $accessGuid = md5($this->module . $this->controller . $this->action);
            //验证过的
            if (true === session($accessGuid)) {
                return true;
            }
            //当前操作已经认证过，无需再次认证
            if (session($accessGuid)) {
                return true;
            }
            //已保存的可访问权限列表
            $accessList = session('_ACCESS_LIST');
            //判断是否为组件化模式，如果是，验证其全模块名
            if (isset($accessList[strtoupper($this->module)][strtoupper($this->controller)][strtoupper($this->action)]) && $accessList[strtoupper($this->module)][strtoupper($this->controller)][strtoupper($this->action)]) {
                session($accessGuid, true);
                return true;
            }
            //做例外处理，只要有管理员帐号，都有该项权限
            if (in_array($keyName, $this->passList)) {
                session($accessGuid, true);
                return true;
            }
            //如果是public_开头的验证通过。
            if (preg_match('/^public_/', $this->controller)) {
                session($accessGuid, true);
                return true;
            }
            if (substr($this->action, 0, 7) == 'public_') {
                session($accessGuid, true);
                return true;
            }
            return false;
        }
        return true;
    }

    /**
     * 当前访问模块补全
     * @param string $map 模块/控制器/方法
     * @return array
     */
    public static function supplement($map = '')
    {
        $map = $map ? explode('/', $map, 3) : [];
        switch (count($map)) {
            case 3:
                list($module, $controller, $action) = $map;
                break;
            case 2:
                $module = request()->module();
                list($controller, $action) = $map;
                break;
            case 1:
                $module = request()->module();
                $controller = request()->controller();
                list($action) = $map;
                break;
            default:
                $module = request()->module();
                $controller = request()->controller();
                $action = request()->action();
                break;
        }
        return [$module, $controller, $action];
    }
}