<?php if (!defined('THINK_PATH')) exit();?><form class="form-horizontal" method="post" action="<?php echo ($actionUrl); ?>"/>
<fieldset>
    <legend> 设置系统回复 </legend>
    <div class="control-group">
        <label class="control-label" >系统回复内容</label>
        <div class="controls">
            <input name="id" type="hidden" value="72"/>
            <textarea name="value"><?php echo ($word); ?></textarea>
        </div>
    </div>
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">保存</button>
        <!--<button type="button" class="btn" onclick="location = ''">取消</button>-->
    </div>
</fieldset>
</form>