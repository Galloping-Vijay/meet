<?php

/*
 * This file is part of the overtrue/wechat.
 *
 * (c) overtrue <i@overtrue.me>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

/**
 * Text.php.
 *
 * @author    overtrue <i@overtrue.me>
 * @copyright 2015 overtrue <i@overtrue.me>
 *
 * @see      https://github.com/overtrue
 * @see      http://overtrue.me
 */

namespace EasyWeChat\Message;

use app\common\lib\Curl;
use program\tuling\Tuling;

/**
 * Class Text.
 *
 * @property string $content
 */
class Text extends AbstractMessage
{
    /**
     * Message type.
     *
     * @var string
     */
    protected $type = 'text';

    /**
     * Properties.
     *
     * @var array
     */
    protected $properties = ['content'];

    /**
     * 用户id
     * @var string
     */
    protected $userid = '123456789';

    //自动回复功能
    public function reply($content = '')
    {
        $params = [
            'reqType' => 0,
            'perception' => [
                'inputText' => [
                    'text' => $content
                ]
            ],
            'userInfo' => [
                'userId' => $this->userid
            ]
        ];
        $tuling = new Tuling();
        $data = $tuling->config($params)->reply();

        return !empty($data) ? $data : '亲，不明白您说什么';
    }
}
