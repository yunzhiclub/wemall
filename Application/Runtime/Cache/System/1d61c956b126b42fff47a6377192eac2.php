<?php if (!defined('THINK_PATH')) exit();?> <form id="adminAutoReplyForm" class="form-horizontal" name="adminAutoReplyForm" method="post" action="<?php echo ($actionUrl); ?>" enctype="multipart/form-data" />
   <fieldset>
   <legend> 编辑自动回复</legend>
    <input type="hidden" name="id" value="<?php echo ($reply["id"]); ?>" />
                            <input type="hidden"/>
<!--                            <div class="control-group">
                                <label class="control-label" for="type">回复选择</label>
                                <div class="controls">
                                  <select name="type" id="type">
                                    <option value="1">关注回复</option>
                                    <option value="0" <?php if(($type) == "0"): ?>selected="selected"<?php endif; ?>>关键字回复</option>
                                   </select>
                                </div>
                             </div>-->
                            <div class="control-group">
                                <label class="control-label" for="reply_type">回复类型</label>
                                <div class="controls">
                                <select name="news_type" id="news_type">
                                    <option value="0">文本回复</option>
                                    <option value="1" <?php if($reply['news_type'] == 1 ) echo 'selected="selected"'; ?>>图文回复</option>
                                   </select>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="content">回复标题</label>
                                <div class="controls">
                                <input type="text" name="title" id="title" value="<?php echo ($reply["title"]); ?>"/>
                                </div>
                            </div>
                            <div class="control-group">
                            <label class="control-label" for="text">文本回复</label>
                                <div class="controls">
                                <input type="text" name="description" id="description" value="<?php echo ($reply["description"]); ?>"/>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="pic_id">图片回复</label>
                                <div class="controls">
                                    <img style="display: inline;width: 40px;" class="smallPic"  src="<?php echo ($reply["picurl"]["url"]); ?>" alt=""/>
                                  <input type="file" name="picUrl" id="picUrl"/>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="url">链接回复</label>
                                <div class="controls">
                                <input  type="text" name="url" id="url" value="<?php echo ($reply['url']); ?>"/>
                                </div>
                            </div>
                            <div class="form-actions">
				<button type="submit" class="btn btn-primary">保存</button> 
<!--                                <button class="btn">取消</button>-->
			    </div>
</fieldset>
</form>