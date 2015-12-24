<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<!--
梦云智工作室
 
-->
<form id="goodsEditForm" class="form-horizontal" name="goodsEditForm" method="post" enctype="multipart/form-data" action="<?php echo ($urlUpdate); ?>"/>
<fieldset>  
       <input name ="id" type="hidden" value="2" />
   <div class="control-group">
                                <label class="control-label" for="name">名称</label>
                                <div class="controls">
                                <input type="text" name='name' id="name" value="<?php echo ($Introduction['name']); ?>" >
                                </div>
                            </div>
   <div class="control-group">
                                <label class="control-label" for="content">设置内容</label>
                                <div class="controls">
                                <textarea name="content" rows="10" cols="30"><?php echo ($Introduction['content']); ?></textarea>
                                </div>
                            </div>
   <div class="form-actions">
				<button type="submit" class="btn btn-primary">保存</button> <button class="btn">取消</button>
			    </div>
</fieldset>

</form>