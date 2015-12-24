<?php if (!defined('THINK_PATH')) exit(); echo ($js); ?>
<form id="pickForm" class="form-horizontal" name="pickForm" method="post" action="<?php echo ($url); ?>" />
<fieldset>
   <legend>修改状态</legend>
        <div class="control-group">
            <label class="control-label" for="title">采购来源 1(必填)</label>
            <div class="controls">
                <input type="text" name='procure_source1' id="procure_source1" >
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="title">采购订单号 1(必填)</label>
            <div class="controls">
                <input type="text" name='procure_orderid1' id="procure_orderId1">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="title">采购来源 2(选填)</label>
            <div class="controls">
                <input type="text" name='procure_source2' id="procure_source2">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="title">采购订单号 2(选填)</label>
            <div class="controls">
                <input type="text" name='procure_orderid2' id="procure_orderId2">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="title">采购来源 3(选填)</label>
            <div class="controls">
                <input type="text" name='procure_source3' id="procure_source3">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="title">采购订单号 3(选填)</label>
            <div class="controls">
                <input type="text" name='procure_orderid3' id="procure_orderId3">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="title">备注</label>
            <div class="controls">
                <input type="text" name='remarks' id="remarks">
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">保存</button> 
            <input type="button"  onclick="javascript:history.back(-1);" value="返回" class="btn">
        </div>
</fieldset>
</form>