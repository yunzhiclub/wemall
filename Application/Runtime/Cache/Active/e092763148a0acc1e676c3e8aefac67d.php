<?php if (!defined('THINK_PATH')) exit();?><form id="goodsAddForm" class="form-horizontal" name="goodsAddForm" method="post" action="<?php echo ($actionadd); ?>" enctype="multipart/form-data" />
    <fieldset>
    <legend> 手工送优惠券</legend>
    <input type="hidden" name="openid" value="<?php echo ($openid); ?>"/>
    <div class="control-group">
        <label class="control-label" for="count">数量(张)</label>
        <div class="controls">
        <input type="text" name='count' id="count"  >
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="cover">金额(元)</label>
        <div class="controls">
            <input type="text" name='cover' id="cover" >
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="duration">有效期(天)</label>
        <div class="controls">
            <input type="text" name='duration' id="duration" >
        </div>
    </div>
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">保存</button> <button class="btn">取消</button>
    </div>
</fieldset>
</form>