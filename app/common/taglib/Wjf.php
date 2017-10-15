<?php
// +----------------------------------------------------------------------
// | Wjftag.WJF框架通用标签
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.meetoyou.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wjf <1937832819@qq.com> 2017/10/12
// +----------------------------------------------------------------------

namespace app\common\taglib;

use think\exception\ClassNotFoundException;
use think\Loader;
use think\Request;
use think\template\TagLib;

class Wjf extends TagLib
{
    // 标签定义
    protected $tags = [
        // 标签定义： attr 属性列表 close 是否闭合（0 或者1 默认1） alias 标签别名 level 嵌套层次
        //'attr'=>返回的数组变量、文章ids、查询字段、limit、order、字符串条件、是否分页、单页数、搜索类型、搜索的值;具体参考get_news函数
        'news' => ['attr' => 'name,ids,cid,field,limit,order,where,ispage,pagesize,type,key', 'close' => 0],
        'ads' => ['attr' => 'name,cid,limit,order', 'close' => 0],
        'singlepage' => ['attr' => 'name,menuid', 'close' => 0],
        'comments' => ['attr' => 'name,field,limit,order', 'close' => 0],
        'menu' => ['attr' => 'top_ul_id,top_ul_class,child_ul_class,child_li_class,firstchild_dropdown_class,haschild_a_class,haschild_span_class,nochild_a_class,showlevel', 'close' => 0],
        //取数组
        'data' => ['attr' => 'name,table,join,joinon,ids,cid,field,limit,order,where,ispage,pagesize,key,page', 'close' => 0],
        //volist
        'list' => ['attr' => 'name,table,join,joinon,ids,cid,field,limit,order,where,ispage,pagesize,key,page', 'close' => 1],
        'get' => ['attr' => 'sql,return,cache,limit,table,order,where,page', 'level' => 3],
    ];

    /**
     * 返回news
     * @param $tag
     * @return string
     */
    public function tagNews($tag)
    {
        $name = $tag['name'];
        $ids = isset($tag['ids']) ? $tag['ids'] : '';
        $cid = isset($tag['cid']) ? $tag['cid'] : '';
        $field = isset($tag['field']) ? $tag['field'] : '';
        $limit = isset($tag['limit']) ? $tag['limit'] : '';
        $order = isset($tag['order']) ? $tag['order'] : '';
        $where = isset($tag['where']) ? $tag['where'] : '';
        $ispage = isset($tag['ispage']) ? $tag['ispage'] : 'false';
        $pagesize = !empty($tag['ispage']) && !empty($tag['pagesize']) && is_numeric($tag['pagesize']) ? intval($tag['pagesize']) : config('paginate.list_rows');
        $type = isset($tag['type']) ? $tag['type'] : 'null';
        $key = isset($tag['key']) ? $tag['key'] : '';
        $tag_str = '';
        $tag_str .= !empty($ids) ? 'ids:' . $ids : '';
        $tag_str .= !empty($cid) ? ';cid:' . $cid : '';
        $tag_str .= !empty($field) ? ';field:' . $field : '';
        $tag_str .= !empty($limit) ? ';limit:' . $limit : '';
        $tag_str .= !empty($order) ? ';order:' . $order : '';
        $tag_str .= !empty($where) ? ';where:' . $where : '';
        if (substr($tag_str, 0, 1) == ';') $tag_str = substr($tag_str, 1);
        $parseStr = '<?php ';
        $parseStr .= '$' . $name . '=get_news(' . '"' . $tag_str . '",' . $ispage . ',' . $pagesize . ',"' . $type . '","' . $key . '");';
        $parseStr .= "?>";
        if (!empty($parseStr)) {
            return $parseStr;
        }
        return;
    }

    /**
     * 返回ads
     * @param $tag
     * @return string
     */
    public function tagAds($tag)
    {
        $name = $tag['name'];
        $cid = isset($tag['cid']) ? $tag['cid'] : '';
        $limit = isset($tag['limit']) ? $tag['limit'] : '';
        $order = isset($tag['order']) ? $tag['order'] : '';
        $parseStr = '<?php ';
        $parseStr .= '$' . $name . '=get_ads(' . '"' . $cid . '","' . $limit . '","' . $order . '");';
        $parseStr .= "?>";
        if (!empty($parseStr)) {
            return $parseStr;
        }
        return;
    }

    /**
     * 返回singlepage的menu
     * @param $tag
     * @return string
     */
    public function tagSinglepage($tag)
    {
        $name = $tag['name'];
        $menuid = isset($tag['menuid']) ? $tag['menuid'] : '';
        $parseStr = '<?php ';
        $parseStr .= '$' . $name . '=get_menu_one(' . '"' . $menuid . '");';
        $parseStr .= "?>";
        if (!empty($parseStr)) {
            return $parseStr;
        }
        return;
    }

    /**
     * 返回comments
     * @param $tag
     * @return string
     */
    public function tagComments($tag)
    {
        $name = $tag['name'];
        $field = isset($tag['field']) ? $tag['field'] : '';
        $limit = isset($tag['limit']) ? $tag['limit'] : '';
        $order = isset($tag['order']) ? $tag['order'] : '';
        $where = isset($tag['where']) ? $tag['where'] : '';
        $tag_str = '';
        $tag_str .= !empty($field) ? 'field:' . $field : '';
        $tag_str .= !empty($limit) ? ';limit:' . $limit : '';
        $tag_str .= !empty($order) ? ';order:' . $order : '';
        if (substr($tag_str, 0, 1) == ';') $tag_str = substr($tag_str, 1);
        $parseStr = '<?php ';
        $parseStr .= '$' . $name . '=get_comments(' . '"' . $tag_str . '");';
        $parseStr .= "?>";
        if (!empty($parseStr)) {
            return $parseStr;
        }
        return;
    }

    /**
     * 返回前台menu
     * @param $tag
     * @return string
     */
    public function tagMenu($tag)
    {
        $top_ul_id = isset($tag['top_ul_id']) ? $tag['top_ul_id'] : '';
        $top_ul_class = isset($tag['top_ul_class']) ? $tag['top_ul_class'] : '';
        $child_ul_class = isset($tag['child_ul_class']) ? $tag['child_ul_class'] : '';
        $child_li_class = isset($tag['child_li_class']) ? $tag['child_li_class'] : '';
        $firstchild_dropdown_class = isset($tag['firstchild_dropdown_class']) ? $tag['firstchild_dropdown_class'] : '';
        $haschild_a_class = isset($tag['haschild_a_class']) ? $tag['haschild_a_class'] : '';
        $haschild_span_class = isset($tag['haschild_span_class']) ? $tag['haschild_span_class'] : '';
        $nochild_a_class = isset($tag['nochild_a_class']) ? $tag['nochild_a_class'] : '';
        $showlevel = !empty($tag['showlevel']) ? intval($tag['showlevel']) : 6;

        $childtpl = '<a href=\'\$href\' class=\'' . $nochild_a_class . '\'>\$menu_name</a>';
        $parenttpl = '<a href=\'#\' class=\'' . $haschild_a_class . '\'>\$menu_name<span class=\'' . $haschild_span_class . '\'>&nbsp;<i class=\'fa fa-angle-down\'></i></span></a>';
        $parseStr = '<?php ';
        $parseStr .= 'echo get_menu(0,"' . $top_ul_id . '","' . $childtpl . '","' . $parenttpl . '","' . $child_ul_class . '","' . $child_li_class . '","' . $top_ul_class . '","' . $showlevel . '","' . $firstchild_dropdown_class . '");';
        $parseStr .= "?>";
        if (!empty($parseStr)) {
            return $parseStr;
        }
        return;
    }

    /**
     * 返回data
     * @param $tag
     * @return string
     */
    public function tagData($tag)
    {
        $name = $tag['name'];
        $table = isset($tag['table']) ? $tag['table'] : 'news';//'news'不含前缀形式
        $join = isset($tag['join']) ? $tag['join'] : '';//'member_list'不含前缀形式
        //'a.news_auto =b.member_list_id'字符串形式,$table为a表,$join为b表
        $joinon = isset($tag['joinon']) ? $tag['joinon'] : '';
        $ids = isset($tag['ids']) ? $tag['ids'] : '';//'id:1,2,3'形式或'1,2,3'形式
        $cid = isset($tag['cid']) ? $tag['cid'] : '';//'cid:1,2,3'形式或'1,2,3'形式
        $field = isset($tag['field']) ? $tag['field'] : '';//'a,b,c,d'形式
        $limit = isset($tag['limit']) ? $tag['limit'] : '';//'5,10'或'5'形式
        $order = isset($tag['order']) ? $tag['order'] : '';//'a,b desc,c'形式
        $where_str = isset($tag['where']) ? $tag['where'] : '';//sql字符串形式"news_open=1 and news_title='aa'"形式
        $ispage = isset($tag['ispage']) ? $tag['ispage'] : 'false'; //true表示分页
        $pagesize = !empty($tag['ispage']) && !empty($tag['pagesize']) && is_numeric($tag['pagesize']) ? intval($tag['pagesize']) : config('paginate.list_rows'); //分页单页数据条数
        $key = isset($tag['key']) ? $tag['key'] : '';//搜索"a,b,c,d:'aaa'"或'aaa'
        $page = !empty($tag['page']) && is_numeric($tag['page']) ? intval($tag['page']) : 0; //当前页
        $parseStr = '<?php ';
        $parseStr .= '$' . $name . '=get_data(' . '"' . $table . '","' . $join . '","' . $joinon . '","' . $ids . '","' . $cid . '","' . $field . '","' . $limit . '","' . $order . '","' . $where_str . '","' . $ispage . '","' . $pagesize . '","' . $key . '","' . $page . '");';
        $parseStr .= "?>";
        if (!empty($parseStr)) {
            return $parseStr;
        }
        return;
    }

    /**
     * 获取数据标签
     * sql/table 不能同时存在,sql优先级最高
     * where 部分不能存在 limit 和 order 语句部分,只能是where部分
     * page 是否启用分页,当 limit 小于 2 时,强制关闭分页
     * 其他属性,作为where 部分条件传入
     * Author: wjf <1937832819@qq.com>
     * @param  $tag 标签属性
     * @param $content
     * @return string
     * @throws \think\Exception
     */
    public function tagGet($tag, $content)
    {
        $att = ['return', 'cache', 'sql', 'limit', 'table', 'model', 'order', 'where', 'whereArr', 'page', 'pageUrl', 'field', 'datasource'];
        $tag['return'] = isset($tag['return']) ? $tag['return'] : 'data';
        $tag['cache'] = isset($tag['cache']) ? (int)$tag['cache'] : false;
        $tag['sql'] = isset($tag['sql']) ? $tag['sql'] : null;
        $tag['limit'] = isset($tag['limit']) ? $tag['limit'] : 200;
        $tag['table'] = isset($tag['table']) ? (Config('database.prefix') . $tag['table']) : '';
        $tag['model'] = isset($tag['model']) ? $tag['model'] : '';
        $tag['order'] = isset($tag['order']) ? $tag['order'] : [];
        $tag['where'] = isset($tag['where']) ? $tag['where'] : '';
        $tag['whereArr'] = isset($tag['whereArr']) ? $tag['whereArr'] : '';
        $tag['page'] = isset($tag['page']) ? $tag['page'] : 0;
        $tag['pageUrl'] = isset($tag['pageUrl']) ? $tag['pageUrl'] : false;
        $tag['field'] = isset($tag['field']) ? explode(',', $tag['field']) : '*';
        $tag['datasource'] = isset($tag['datasource']) ? trim($tag['datasource']) : [];
        if (!is_null($tag['sql'])) {
            $tag['table'] = '';
            //删除，插入不执行！这样处理感觉有点鲁莽了，，，-__,-!
            if (strpos($tag['sql'], 'delete') || strpos($tag['sql'], 'insert')) {
                throw new \think\Exception('_XML_TAG_ERROR_: get 标签不支持 delete/insert ');
            }
        }
        //差集部分作为条件,同时进行分析
        foreach ($tag as $k => $v) {
            if (!in_array($k, $att)) {
                if (!is_array($tag['whereArr'])) {
                    $tag['whereArr'] = [];
                }
                $tag['whereArr'][$k] = $v;
                unset($tag[$k]);
            }
        }
        //数量统计sql
        $tag['sql_count'] = preg_replace('/select([^from].*)from/i', "SELECT COUNT(*) as count FROM ", $tag['sql']);
        //模型参数处理
        if ($tag['model']) {
            if (false !== strpos($tag['model'], '\\')) {
                $class = $tag['model'];
                $module = Request::instance()->module();
            } else {
                if (strpos($tag['model'], '/')) {
                    list($module, $tag['model']) = explode('/', $tag['model'], 2);
                } else {
                    $module = Request::instance()->module();
                }
                $class = Loader::parseClass($module, 'model', $tag['model'], false);
            }
            if (class_exists($class)) {
                $tag['model'] = "{$class}::";
            } else {
                $class = str_replace('\\' . $module . '\\', '\\common\\', $class);
                if (class_exists($class)) {
                    $tag['model'] = "{$class}::";
                } else {
                    throw new ClassNotFoundException('class not exists:' . $class, $class);
                }
            }
        } else {
            $tag['model'] = "\\think\\Db::table('{$tag['table']}')->";
        }
        $parseStr = '';
        $parseStr .= " <?php \n";
        $parseStr .= " if(isset(\${$tag['return']})):unset(\${$tag['return']});endif;";
        $parseStr .= "  \$_tag = " . self::varExport($tag) . "; \n";
        $parseStr .= "  \$_tag['cacheKey'] = md5('_get' . json_encode(\$_tag) . 'd1xz'); \n";
        $parseStr .= "  \${$tag['return']} = []; \n";
        if ($tag['sql'] && !empty($tag['sql_count'])) {
            $tag['table'] = $tag['order'] = $tag['where'] = '';
            $tag['where'] = $tag['whereArr'] = '1 = 1';
            $parseStr .= "  if(\$_tag['page']):; \n";
            $parseStr .= "   \$count = \\think\\Db::connect(\$_tag['datasource'])->query(\$_tag['sql_count']); \n";
            $parseStr .= "   \$count = isset(\$count[0]['count'])?\$count[0]['count']:0; \n";
            $parseStr .= "   \$page = page(\$count, \$_tag['limit'], \$_tag['page'], []); \n";
            $parseStr .= "   \$page->setPageUrl(\$_tag['pageUrl']); \n";
            $parseStr .= "   \$Page = \$page->show(); \n";
            $parseStr .= "   \$PageInfo = ['total'=>\$count,'size'=>\$_tag['limit'],'page'=>\$_tag['page'],'pageurl'=>\$_tag['pageUrl'],'object'=>\$page]; \n";
            $parseStr .= "   \${$tag['return']} = \\think\\Db::connect(\$_tag['datasource'])->query(\$_tag['sql'].' limit '.\$page->limit()); \n";
            $parseStr .= "  else: \n";
            $parseStr .= "    if(\$_tag['cache']):; \n";
            $parseStr .= "      \${$tag['return']} = cache(\$_tag['cacheKey']); \n";
            $parseStr .= "      if(empty(\${$tag['return']})):; \n";
            $parseStr .= "         \${$tag['return']} = \\think\\Db::connect(\$_tag['datasource'])->query(\$_tag['sql']); \n";
            $parseStr .= "         cache(\$_tag['cacheKey'],\${$tag['return']},\$_tag['cache']); \n";
            $parseStr .= "      endif; \n";
            $parseStr .= "    else: \n";
            $parseStr .= "      \${$tag['return']} = \\think\\Db::connect(\$_tag['datasource'])->query(\$_tag['sql']); \n";
            $parseStr .= "    endif; \n";
            $parseStr .= "  endif; \n";
        } else {
            $parseStr .= "  if(\$_tag['page']):; \n";
            $parseStr .= "   \$count = {$tag['model']}where(\$_tag['where'])->where(\$_tag['whereArr'])->field(\$_tag['field'])->cache(\$_tag['cache'])->count(); \n";
            $parseStr .= "   \$page = page(\$count, \$_tag['limit'], \$_tag['page'], []); \n";
            $parseStr .= "   \$page->setPageUrl(\$_tag['pageUrl']); \n";
            $parseStr .= "   \$Page = \$page->show(); \n";
            $parseStr .= "   \$PageInfo = ['total'=>\$count,'size'=>\$_tag['limit'],'page'=>\$_tag['page'],'pageurl'=>\$_tag['pageUrl'],'object'=>\$page]; \n";
            $parseStr .= "   \${$tag['return']} = {$tag['model']}where(\$_tag['where'])->where(\$_tag['whereArr'])->field(\$_tag['field'])->cache(\$_tag['cache'])->limit(\$page->limit())->order(\$_tag['order'])->select(); \n";
            $parseStr .= "  else: \n";
            $parseStr .= "   \${$tag['return']} = {$tag['model']}where(\$_tag['where'])->where(\$_tag['whereArr'])->field(\$_tag['field'])->cache(\$_tag['cache'])->limit(\$_tag['limit'])->order(\$_tag['order'])->select(); \n";
            $parseStr .= "  endif; \n";
        }
        $parseStr .= "  ?>{$content}<?php \n";
        $parseStr .= "  \n";
        $parseStr .= " ?>";
        return $parseStr;
    }

    /**
     * 转换数据为HTML代码
     * Author: wjf <1937832819@qq.com>
     * @param $data 数组
     * @return bool|string
     */
    private static function varExport($data)
    {
        if (is_array($data)) {
            $str = "[";
            foreach ($data as $key => $val) {
                if (is_array($val)) {
                    $str .= "\n'$key'=>" . self::varExport($val) . ",";
                } else {
                    //如果是变量的情况
                    if (strpos($val, '$') === 0) {
                        $str .= "\n'$key'=>$val,";
                    } else if (preg_match("/^([a-zA-Z_].*)\(/i", $val, $matches)) {//判断是否使用函数
                        if (function_exists($matches[1])) {
                            $str .= "\n'$key'=>$val,";
                        } else {
                            $str .= "\n'$key'=>'" . self::newAddslashes($val) . "',";
                        }
                    } else if (is_bool($val)) {
                        $val = $val ? 'true' : 'false';
                        $str .= "\n'$key'=>$val,";
                    } else if (is_null($val)) {
                        $str .= "\n'$key'=>null,";
                    } else if (is_integer($val)) {
                        $str .= "\n'$key'=>$val,";
                    } else {
                        $str .= "\n'$key'=>'" . self::newAddslashes($val) . "',";
                    }
                }
            }
            return $str . "\n]";
        }
        return false;
    }

    /**
     *  返回经addslashes处理过的字符串或数组
     * Author: wjf <1937832819@qq.com>
     * @param array|string $string 需要处理的字符串或数组
     * @return array|string
     */
    protected static function newAddslashes($string)
    {
        if (!is_array($string)) {
            return addslashes($string);
        }
        foreach ($string as $key => $val) {
            $string[$key] = self::newAddslashes($val);
        }
        return $string;
    }
}