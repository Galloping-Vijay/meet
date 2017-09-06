<?php	return array (
  'yfcmf_version' => 'V2.3.1',
  'update_check' => false,
  'think_sdk_qq' => 
  array (
    'app_key' => '1106069934',
    'app_secret' => '7aYz2ABPT8QDi81M',
    'display' => true,
    'callback' => 'http://www.meetoyou.com/home/oauth/callback/type/qq.html',
  ),
  'think_sdk_sina' => 
  array (
    'app_key' => '602735229',
    'app_secret' => '66781cbeab9fdb9b014a387ab6e943fe',
    'display' => true,
    'callback' => 'http://www.meetoyou.com/home/oauth/callback/type/sina.html',
  ),
  'comment' => 
  array (
    't_open' => true,
    't_limit' => 60,
  ),
  'auth_config' => 
  array (
    'auth_on' => true,
    'auth_type' => 1,
    'auth_group' => 'auth_group',
    'auth_group_access' => 'auth_group_access',
    'auth_rule' => 'auth_rule',
    'auth_user' => 'admin',
  ),
  'app_debug' => true,
  'app_trace' => false,
  'baidumap_ak' => 'D91c810554767b49e3bdd2a7b25d97c1',
  'upload_validate' => 
  array (
    'size' => 10485760,
    'ext' => 
    array (
      0 => 'jpg',
      1 => 'gif',
      2 => 'png',
      3 => 'jpeg',
    ),
  ),
  'upload_path' => '/data/upload',
  'url_route_on' => true,
  'url_route_must' => false,
  'route_complete_match' => false,
  'url_html_suffix' => 'html',
  'storage' => 
  array (
    'storage_open' => false,
    'accesskey' => '',
    'secretkey' => '',
    'bucket' => '',
    'domain' => '',
  ),
  'lang_switch_on' => true,
  'default_lang' => 'zh-cn',
  'geetest' => 
  array (
    'geetest_on' => false,
    'captcha_id' => '',
    'private_key' => '',
  ),
  'log' => 
  array (
    'clear_on' => true,
    'timebf' => 2592000,
    'level' => 
    array (
    ),
  ),
  'web_log' => 
  array (
    'weblog_on' => false,
    'not_log_module' => 
    array (
      0 => 'install',
    ),
    'not_log_controller' => 
    array (
      0 => 'home/Error',
      1 => 'home/Token',
      2 => 'admin/Ajax',
      3 => 'admin/Error',
      4 => 'admin/Ueditor',
      5 => 'admin/WebLog',
    ),
    'not_log_action' => 
    array (
    ),
    'not_log_data' => 
    array (
    ),
    'not_log_request_method' => 
    array (
      0 => 'GET',
    ),
  ),
  'url_route_mode' => '2',
  'think_sdk_facebook' => 
  array (
    'app_key' => '',
    'app_secret' => '',
    'display' => false,
    'callback' => 'http://www.meetoyou.com/home/oauth/callback/type/facebook.html',
  ),
  'payment' => 
  array (
    'alipay' => 
    array (
      'account' => '',
      'account_name' => '',
      'partner' => '',
      'md5_key' => '',
      'rsa_private_key' => '/data/config/rsa_private_key.pem',
      'notify_url' => 'http://www.rainfer.cn/home/payment/ali_notify_url',
      'return_url' => 'http://www.rainfer.cn/home/payment/success',
      'time_expire' => '14',
      'sign_type' => 'MD5',
      'display' => '1',
    ),
    'aliwappay' => 
    array (
      'account' => '',
      'account_name' => '',
      'partner' => '',
      'md5_key' => '',
      'rsa_private_key' => '/data/config/rsa_private_key.pem',
      'notify_url' => 'http://www.rainfer.cn/home/payment/ali_notify_url',
      'return_url' => 'http://www.rainfer.cn/home/payment/success',
      'time_expire' => '14',
      'sign_type' => 'MD5',
      'display' => '1',
    ),
    'wxpayqrcode' => 
    array (
      'app_id' => '',
      'md5_key' => '',
      'mch_id' => '',
      'notify_url' => 'http://www.rainfer.cn/home/payment/wxqrcode_notify_url',
      'time_expire' => '14',
      'cert_path' => 'F:\\phpStudy\\WWW\\yfcms_dev\\YFCMF\\data\\conf\\wx\\apiclient_cert.pem',
      'key_path' => 'F:\\phpStudy\\WWW\\yfcms_dev\\YFCMF\\data\\conf\\wx\\apiclient_key.pem',
      'display' => '1',
    ),
    'wxpaypub' => 
    array (
      'app_id' => 'wxxxxx',
      'md5_key' => 'xxxxxx',
      'mch_id' => 'xxxxx',
      'notify_url' => 'http://www.rainfer.cn/home/payment/wxpub_notify_url',
      'cert_path' => 'F:\\phpStudy\\WWW\\yfcms_dev\\YFCMF\\data\\conf\\wx\\apiclient_cert.pem',
      'key_path' => 'F:\\phpStudy\\WWW\\yfcms_dev\\YFCMF\\data\\conf\\wx\\apiclient_key.pem',
    ),
    'wxqrcode' => 
    array (
      'time_expire' => '14',
    ),
    'tenpay' => 
    array (
      'key' => '',
      'partner' => '',
    ),
    'unionpay' => 
    array (
      'key' => '',
      'partner' => '',
    ),
    'palpay' => 
    array (
      'business' => '',
    ),
  ),
  'think_sdk_weixin' => 
  array (
    'app_key' => '',
    'app_secret' => '',
    'display' => false,
    'callback' => 'http://www.meetoyou.com/home/oauth/callback/type/weixin.html',
  ),
  'think_sdk_wechat' => 
  array (
    'app_key' => 'wxf31792477fe34e63',
    'app_secret' => 'daf5b015a1d08f88d4daba57a63b0054',
    'display' => true,
    'callback' => 'http://www.meetoyou.com/home/oauth/callback/type/wechat.html',
  ),
  'think_sdk_google' => 
  array (
    'app_key' => '',
    'app_secret' => '',
    'display' => false,
    'callback' => 'http://www.meetoyou.com/home/oauth/callback/type/google.html',
  ),
  'we_options' => 
  array (
    'we_name' => '莫寻网',
    'we_id' => 'gh_ef828df27ba5',
    'we_number' => 'wjf19940211',
    'app_id' => 'wxf31792477fe34e63',
    'secret' => 'daf5b015a1d08f88d4daba57a63b0054',
    'token' => 'weijinfeng',
    'aes_key' => 'CSHUvByoMNJIXBEjfPhVHKdESjMrAggFzRhyxXKJEoz',
    'we_type' => '2',
  ),
  'addons_sql' => true,
  'think_sdk_sms' => 
  array (
    'AccessKeyId' => '23715246',
    'accessKeySecret' => '8329cfd5ca674dc5e72849e38206472b',
    'signName' => '悦遇客家',
    'TemplateCode' => 'SMS_57040243',
    'sms_open' => '1',
  ),
);