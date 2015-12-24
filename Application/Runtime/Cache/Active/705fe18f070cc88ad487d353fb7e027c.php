<?php if (!defined('THINK_PATH')) exit();?><form id="goodsAddForm" class="form-horizontal" name="goodsAddForm" method="post" action="<?php echo ($update); ?>" enctype="multipart/form-data"/>
    <fieldset>
    <legend>老用户</legend>
    <input type="hidden" name="oldobject" value="<?php echo ($oldList["object"]); ?>"/>
    <div class="control-group">
        <label class="control-label" for="oldcount">数量(张)</label>
        <div class="controls">
        <input type="text" name='oldcount' id="oldcount" value="<?php echo ($oldList["count"]); ?>" >
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="oldcover">金额(元)</label>
        <div class="controls">
            <input type="text" name='oldcover' id="oldcover" value="<?php echo ($oldList["cover"]); ?>" >
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="oldduration">有效期(天)</label>
        <div class="controls">
            <input type="text" name='oldduration' id="oldduration" value="<?php echo ($oldList["duration"]); ?>" >
        </div>
    </div>
        <div class="control-group">
        <label class="control-label" for="oldstate">状态</label>
        <div class="controls">
            <select name="oldstate">
                <option value="0">正常</option>
                <option value="1" <?php if(($oldList["state"]) == "1"): ?>selected="selected"<?php endif; ?>>停用</option>
            </select>
        </div>
    </div>
</fieldset>
    <fieldset>
    <legend>新用户</legend>
    <input type="hidden" name="newobject" value="<?php echo ($newList["object"]); ?>"/>
    <div class="control-group">
        <label class="control-label" for="newcount">数量(张)</label>
        <div class="controls">
        <input type="text" name='newcount' id="newcount" value="<?php echo ($newList["count"]); ?>" >
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="newcover">金额(元)</label>
        <div class="controls">
            <input type="text" name='newcover' id="newcover" value="<?php echo ($newList["cover"]); ?>" >
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="newduration">有效期(天)</label>
        <div class="controls">
            <input type="text" name='newduration' id="newduration" value="<?php echo ($newList["duration"]); ?>" >
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="newstate">状态</label>
        <div class="controls">
            <select name="newstate">
                <option value="0">正常</option>
                <option value="1" <?php if(($newList["state"]) == "1"): ?>selected="selected"<?php endif; ?>>停用</option>
            </select>
        </div>
    </div>
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">保存</button> <button class="btn">取消</button>
    </div>
</fieldset>
</form>