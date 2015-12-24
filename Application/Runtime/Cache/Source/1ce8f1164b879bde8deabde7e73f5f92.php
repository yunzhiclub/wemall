<?php if (!defined('THINK_PATH')) exit();?><!--
梦云智工作室
 
-->
<form class="form-horizontal" method="post" action="<?php echo ($actionUrl); ?>" enctype="multipart/form-data" />
<fieldset>
    <legend> 添加新来源</legend>
    <div class="control-group">
        <label class="control-label" >来源名称</label>
        <div class="controls">
            <input type="hidden" name ="id"  value="<?php echo ($infoN); ?>">
            <input type="text" name="name" >
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="SYSTEM_END_DAY">设置图标</label>
        <div class="controls">
            <input type="file" name="img" >
        </div>
    </div>
    <input type="hidden" name="attachment_id" value="<?php echo ($info); ?>"/>
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">保存</button> <button type="button" class="btn" onclick="location = '<?php echo ($url); ?>'">取消</button>
    </div>
</fieldset>
</form>