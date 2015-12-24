<?php if (!defined('THINK_PATH')) exit();?><form id="goodsEditForm" class="form-horizontal" name="goodsEditForm" method="post" action="<?php echo ($url); ?>" enctype="multipart/form-data" />
<fieldset>    
<legend> 编辑商品</legend>
<input type="hidden" name="id" value="<?php echo ($GoodsList['id']); ?>"/>
    <div class="control-group">
        <label class="control-label" for="name">商品名称</label>
        <div class="controls">
        <input type="text" name='name' id="name" value="<?php echo ($GoodsList["name"]); ?>" >
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="phpto">商品展示图</label>   
        <div style="padding-top: 40px; margin-left: 100px">
        <img style="display: inline; width:30%" src="<?php echo ($GoodsList["picturePath"]["0"]); ?>"/>
        <img style="display: inline; width:30%" src="<?php echo ($GoodsList["picturePath"]["1"]); ?>"/>
        <img style="display: inline; width:30%" src="<?php echo ($GoodsList["picturePath"]["2"]); ?>"/>
        <input style="display: inline; width:30%" type="file" id="phpto0" name="phpto0"/>
        <input style="display: inline; width:30%;margin-left:" type="file" id="phpto1" name="phpto1"/>       
        <input style="display: inline; width:30% ;margin-left:" type="file" id="phpto2" name="phpto2"/>
        </div>
     </div>
    <div class="control-group">
        <label class="control-label" for="international_price">海外同步价</label>
        <div class="controls">
        <input type="text" name = "purchasing_price" id="purchasing_price"  value = "<?php echo format_money($GoodsList['purchasing_price']); ?>"/>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="purchasing_price">代购参考价</label>
        <div class="controls">
        <input type="text" name = "international_price" id="international_price"  value = "<?php echo format_money($GoodsList['international_price']); ?>"/>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="source">商品来源</label>
        <div class="controls">
        <select  name="source" id="source">
            <?php if(is_array($Source)): foreach($Source as $key=>$value): ?><option value="<?php echo ($value["id"]); ?>" <?php if($value['id'] == $GoodsList['source']) echo ' selected="selected"'; ?> > <?php echo ($value["name"]); ?></option><?php endforeach; endif; ?>
        </select>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="arrive_days">预计到手天数</label>
        <div class="controls">
        <input  type="text" name = "arrive_days" id="arrive_days" value = "<?php echo ($GoodsList["arrive_days"]); ?>"/>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="direct_selling_commission">直销系数</label>
        <div class="controls">
        <input type="text" name = "direct_selling_commission"  id="direct_selling_commission" value = "<?php echo $GoodsList['direct_selling_commission']; ?>"/><span class="need">%</span>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="line_selling_commission">下线系数</label>
        <div class="controls">
        <input type="text" name = "line_selling_commission" id="line_selling_commission" value = "<?php echo $GoodsList['line_selling_commission']; ?>"/><span class="need">%</span>
        </div>
    </div>
     
    <div class="control-group">
        <label class="control-label" for="internation_transportion_expenses">国际运费</label>
        <div class="controls">
        <input type="text" name = "internation_transportion_expenses" id="internation_transportion_expenses" value = "<?php echo ($GoodsList['internation_transportion_expenses']); ?>"/>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="domestic_transportation_expenses">国内运费</label>
        <div class="controls">
        <input type="text" name = "domestic_transportation_expenses" id="domestic_transportation_expenses" value = "<?php echo format_money($GoodsList['domestic_transportation_expenses']); ?>"/>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="weight">产品计重</label>
        <div class="controls">
        <input type="text" name = "weight" id="weight" value="<?php echo ($GoodsList["weight"]); ?>"/>
        </div>
    </div>
     <div class="control-group">
        <label class="control-label" for="customs">关税</label>
        <div class="controls">
        <input type="text" name = "customs" id="customs" value="<?php echo $GoodsList['customs']; ?>"/>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="logistics_mode">物流方式</label>
        <div class="controls">
            <select  name="logistics_mode" id="logistics_mode">
            <?php if(is_array($Log)): foreach($Log as $key=>$value): ?><option value="<?php echo ($value["mode"]); ?>" <?php if($value['mode'] == $GoodsList['logistics_mode']) echo ' selected="selected"'; ?> > <?php echo ($value["name"]); ?></option><?php endforeach; endif; ?>
        </select>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="supplier_url">供应商链接</label>
        <div class="controls">
        <input  type="text" name="supplier_url" id="supplier_url" value="<?php echo ($GoodsList["supplier_url"]); ?>"/>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="discount_way">优惠政策</label>
        <div class="controls">
        <input type="text" name="discount_way" id="discount_way" value="<?php echo ($GoodsList["discount_way"]); ?>"/>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="discount_amount">优惠金额</label>
        <div class="controls">
        <input type="text" name="discount_amount" id="discount_amount" value="<?php echo format_money($GoodsList['discount_amount']); ?>"/>
        </div>
    </div>
    <div class="control-group">
        <div class="controls" style="color:red">*请填写正整数 金额为 0 则不显示</div>
    </div>
    <div class="control-group">
        <label class="control-label" for="shop_superior_limit">购买上限</label>
        <div class="controls">
        <input type="text" name="shop_superior_limit" id="shop_superior_limit" value="<?php echo ($GoodsList["shop_superior_limit"]); ?>"/>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="shop_lower_limit">购买下限</label>
        <div class="controls">
        <input type="text" name="shop_lower_limit" id="shop_lower_limit" value="<?php echo ($GoodsList["shop_lower_limit"]); ?>"/>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="reorder">排序权重</label>
        <div class="controls">
        <input type="text" name="reorder" id="reorder" value="<?php echo ($GoodsList["reorder"]); ?>"/>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="remarks">备注</label>
        <div class="controls">
            <textarea type="text" name="remarks" id="remarks"> <?php echo ($GoodsList["remarks"]); ?></textarea>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="state">状态</label>
        <div class="controls">
        <select class="c" name="state" id="state">
        <option value="1">架上</option>
        <option value="0" <?php if($GoodsList['state'] == '0') echo 'selected="selected"'; ?>>架下</option>
        </select>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="discribe">商品描述</label>
        <div class="controls textarea">
         <textarea  id="editor1" name="discribe">
         <?php echo ($GoodsList['discribe']); ?>
         </textarea>
        </div>
    </div>
    <div class="form-actions">
        <input type="submit" value="保存" class="btn btn-primary"></input>
        <button class="btn" type="button" onclick="location='<?php if($GoodsList['state'] == 1) echo $urlIndex['onShelevesManager']; else echo $urlIndex['underShelevesManager']; ?>'">取消</button>
    </div>
</fieldset>
</form>
<script>
$(document).ready(function(){
    editor();
});
</script>
<style type="text/css">
    .need{
        color: red;
        font-size: 20px;
        margin: 5px;
    }
</style>