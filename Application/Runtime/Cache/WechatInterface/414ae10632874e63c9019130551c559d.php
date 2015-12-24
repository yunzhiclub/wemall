<?php if (!defined('THINK_PATH')) exit();?><xml>
    <ToUserName><![CDATA[<?php echo ($toUserName); ?>]]></ToUserName>
    <FromUserName><![CDATA[<?php echo ($fromUserName); ?>]]></FromUserName>
    <CreateTime><?php echo time(); ?></CreateTime>
    <MsgType><![CDATA[news]]></MsgType>
    <ArticleCount><?php echo count($article); ?></ArticleCount>
    <Articles>
        <?php if(is_array($article)): foreach($article as $key=>$value): ?><item>
            <Title><![CDATA[<?php echo ($value["title"]); ?>]]></Title> 
            <Description><![CDATA[<?php echo ($value["description"]); ?>]]></Description>
            <PicUrl><![CDATA[<?php echo ($value["picurl"]); ?>]]></PicUrl>
            <Url><![CDATA[<?php echo ($value["url"]); ?>]]></Url>
        </item><?php endforeach; endif; ?>
    </Articles>
</xml>