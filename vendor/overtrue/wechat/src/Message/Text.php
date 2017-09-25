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
use tuling\Tuling;

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
        $config = [
            'userid' => $this->userid,
            'info' => $content
        ];
        $tuling = new Tuling();
        $conf = $tuling->config($config);

        $curl = new Curl();
        $data = $curl->post($tuling->app_url(), $conf);

        if ($data) {
            $data = json_decode($data, true);
            return $data['text'];
        } else {
            return '亲，不明白您说什么';
        }

    }
}
