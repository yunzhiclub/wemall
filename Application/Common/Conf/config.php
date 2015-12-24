<?php
S(array('type'=>'File','expire'=>7210));
return array(
    'WECHAT_BROWER'     =>TRUE,//'配置项'=>'配置值'
    
    'ACTION_SUFFIX'         => 'Action',//设置ACTION前缀为action
    /* 数据库配置 */
    'DB_TYPE'   => 'mysqli', // 数据库类型
    'DB_HOST'   => 'rdsrtmwb5f0n2nukioght.mysql.rds.aliyuncs.com', // 服务器地址
    'DB_NAME'   => 'wemall', // 数据库名
    'DB_USER'   => 'wemall', // 用户名
    'DB_PWD'    => 'jVmyXGfwEVE246QY',  // 密码
    'DB_PORT'   => '3306', // 端口
    'DB_PREFIX' => 'wemall_', // 数据库表前缀
    'UPLOAD_ROOT_PATH' => '/Uploads',//附件上传根路径
    'SESSION_AUTO_START' => true, //开启session
    'WECHAT_TOKEN'  => 'yunzhi',//验证token
    'MALL_DOMAIN'   =>'www.trends-media.com.cn',//商城域名
    'MALL_INTERFACE'    =>'index',//商城入口
    //'WECHAT_APPID'      =>'wx53bf06122618f768',//微信公众平台appid
    'WECHAT_APPID'      =>'wx15a60486abf8f053',//生产appid
    //'WECHAT_SECRET'     =>'c1c300ea63649dba1cedd8b400a2f377',//微信公众平台secret
    'WECHAT_SECRET'     =>'ed0950aa56198ecd9f689b7f558e1802',
    'WECHAT_OAUTH'      =>'https://open.weixin.qq.com/connect/oauth2/authorize?appid=%s&redirect_uri=%s&response_type=code&scope=%s&state=%s#wechat_redirect',//微信oauth地址
    /*
     * =======================================================
     * 微支付参数设置
     */
    'WXPAY_MCHID'           =>'1236255202',//受理商ID，身份标识
    'WXPAY_KEY'             =>'pz1gqdajec5z24iwryq9epy0dd6pgxfw',//支付密钥key.审核通过后，在微信发送邮件中查看
    'WXPAY_APPSECRET'       =>'ed0950aa56198ecd9f689b7f558e1802',//JS API接口中获取的OPENID，审核后在公众平台开启开发模式后查看
    'WXPAY_JS_API_CALL_URL' =>'',//获取access_token过程中的跳转url,通过跳转将code传入jsapi支付页面
    'WXPAY_SSLCERT_PATH'    =>'/Application/WxPay/Key/apiclient_cert.pem',//证书路径，以/打头
    'WXPAY_SSLKEY_PATH'     =>'/Application/WxPay/Key/apiclient_key.pem',//KEY路径，以/打头
    'WXPAY_NOTIFY_URL'      =>'WxPay/Index/interface',//异步通知URL地址,为THINKPHP的写法
    'WXPAY_CRUL_TIMEOUT'    =>30,//CURL超时时间
    'WXPAY_OPENID'          =>'oZuoxt8tnEUUf6YPBG-mNPYjoKQA',//测试OPENID
);