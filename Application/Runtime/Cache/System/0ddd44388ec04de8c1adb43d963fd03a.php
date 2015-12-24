<?php if (!defined('THINK_PATH')) exit();?><div class="data-form">    
    <form action="<?php echo ($actionUrl); ?>" enctype="multipart/form-data" method="post">
        <?php if((ACTION_NAME) == "edit"): ?><input type="hidden" name="id" value="<?php echo ($data["id"]); ?>"/><?php endif; ?>
        <div class="control-group">
            <label class="control-label" for="title">菜单标题:</label>
            <div class="controls">
                <span><?php echo ($data["title"]); ?></span>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="reply_type">消息类型</label>
            <div class="controls">
                <select name="reply_type">
                    <option value="text">文本</option>
                    <option <?php if(($reply_type) == "news"): ?>selected="selected"<?php endif; ?> value="news">图文</option>
                </select>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="reply_title">回复标题</label>
            <div class="controls">
                <input type="text" value="<?php echo ($data["reply_title"]); ?>" name="reply_title" id="reply_title" />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="reply_url">打开页面链接</label>
            <div class="controls">
                <input type="text" value="<?php echo ($data["reply_url"]); ?>" name="reply_url" id="reply_url" />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="reply_image">回复图片</label>
            <div class="controls">
                <input type="hidden" name="reply_image" value="<?php echo ($data["reply_image"]["id"]); ?>" />
                <div id="showImage"><img src="<?php echo ($data["reply_image"]["url"]); ?>" /></div>
                <input type="file" name="reply_image" />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="reply_content">回复内容</label>
            <div class="controls">
               <textarea rows="10" cols="30" name="reply_content" id="reply_content"><?php echo ($data["reply_content"]); ?></textarea> 
            </div>
        </div>
        <div class="form-actions">
            <a href="#" onclick="submit()" class="btn btn-primary">保存</a>
        </div>
    </form>
</div>
<script>
    //表单上传
    function submit()
    {
        $("form").submit();
    }
</script>
<style>
#showImage img
{
    width:100px;
}
</style>