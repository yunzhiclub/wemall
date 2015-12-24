<?php
//格式化微信服务器传送数据
function get_postObj()
{
    $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
    if (!empty($postStr)){
        libxml_disable_entity_loader(true);
        $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
    }  
    else
    {
        $postObj = null;
    }
    return $postObj;
}
//获取用户基本信息
function get_base_information($openid)
{
    $accessToken = get_access_token();
    $url = sprintf('https://api.weixin.qq.com/cgi-bin/user/info?access_token=%s&openid=%s&lang=zh_CN',$accessToken,$openid);
    $res = http_get_data($url);
    return $res;
    }