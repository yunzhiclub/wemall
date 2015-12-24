<?php if (!defined('THINK_PATH')) exit(); echo ($js); ?>
<form id="goodsAddForm" class="form-horizontal" name="goodsAddForm" method="post" action="<?php echo ($url); ?>" enctype="multipart/form-data"/>

    <legend> 编辑</legend>
    <input type="hidden" name="id" value="<?php echo ($GoodsList['id']); ?>"/>
    <div class="control-group">
        <label class="control-label" for="name">商品名称</label>
        <div class="controls">
        <input type="text" name='name' id="name" value="" >
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="phpto">商品展示图</label>
        <div class="controls">
            <input type="file" id="phpto1" name="phpto1"/>
            </br>
            <input type="file" id="phpto2" name="phpto2"/>
            </br>
            <input type="file" id="phpto3" name="phpto3"/>
        </div>
    </div>
    
     <div class="control-group">
        <label class="control-label" for="international_price">海外同步价</label>
        <div class="controls">
        <input type="text" name = "purchasing_price" id="purchasing_price"  value = "0"  />
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="purchasing_price">代购参考价</label>
        <div class="controls">
        <input type="text" name = "international_price" id="international_price"  value = "0"/>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="source">商品来源</label>
        <div class="controls">
            <select  name="source" id="source">
<!--            <option selected="selected" value="0"></option>
            <option value="1"></option>-->
                    <?php if(is_array($Source)): foreach($Source as $key=>$value): ?><option value="<?php echo ($value["id"]); ?>" ><?php echo ($value["name"]); ?></option><?php endforeach; endif; ?>
            </select>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="arrive_days">预计到手天数</label>
        <div class="controls">
            <input  type="text" name = "arrive_days" id="arrive_days" class="" value = "请输入文字描述"/>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="direct_selling_commission">直销系数</label>
        <div class="controls">
            <input type="text" name = "direct_selling_commission"  id="direct_selling_commission" value = "0"/><span class="need">%</span>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="line_selling_commission">下线系数</label>
        <div class="controls">
            <input type="text" name = "line_selling_commission" id="line_selling_commission" value = "0"/><span class="need">%</span>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="internation_transportion_expenses">国际运费</label>
        <div class="controls">
            <input type="text" name = "internation_transportion_expenses" id="internation_transportion_expenses" value = "请输入文字描述"/>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="domestic_transportation_expenses">国内运费</label>
        <div class="controls">
            <input type="text" name = "domestic_transportation_expenses" id="domestic_transportation_expenses" value = "0"/>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="weight">产品计重</label>
        <div class="controls">
            <input type="text" name = "weight" id="weight" value = "0"/>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="customs">关税</label>
        <div class="controls">
            <input type="text" name = "customs" id="customs" value = "关税描述"/>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="logistics_mode">物流方式</label>
        <div class="controls">
<!--            <select id="namecheck" name="namecheck">
            <option selected="selected" value="0"></option>
            <option value="1"></option>
            </select>-->
            <select  name="logistics_mode" id="logistics_mode">
                    <?php if(is_array($Log)): foreach($Log as $key=>$value): ?><option value="<?php echo ($value["mode"]); ?>" ><?php echo ($value["name"]); ?></option><?php endforeach; endif; ?>
            </select>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="supplier_url">供应商链接</label>
        <div class="controls">
            <input  type="text" name="supplier_url" id="supplier_url" value = "http://"/>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="discount_way">优惠政策</label>
        <div class="controls">
            <input type="text" name="discount_way" id="discount_way" />
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="discount_amount">优惠金额</label>
        <div class="controls">
            <input type="text" name="discount_amount" id="discount_amount" value="0"/>
        </div>
    </div>
    <div class="control-group">
        <div class="controls" style="color:red">*请填写正整数 金额为 0 则不显示</div>
    </div>
    <div class="control-group">
        <label class="control-label" for="shop_superior_limit">购买上限</label>
        <div class="controls">
            <input type="text" name="shop_superior_limit" id="shop_superior_limit" value="0"/>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="shop_lower_limit">购买下限</label>
        <div class="controls">
            <input type="text" name="shop_lower_limit" id="shop_lower_limit" value="0"/>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="reorder">排序权重</label>
        <div class="controls">
            <input type="text" name="reorder" id="reorder" value="0"/>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="remarks">备注</label>
        <div class="controls">
            <input type="text" name="remarks" id="remarks" value="备注"/>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="state">状态</label>
        <div class="controls">
            <select class="c" name="state" id="state">
            <option value="1">架上</option>
            <option value="0" selected="selected">架下</option>
            </select>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="editor1">商品描述</label>
        <div class="controls textarea">
            <textarea  id="discribe" name="discribe">
            </textarea>
        </div>
    </div>
    <div class="form-actions">
        <input type="submit" value="保存" class="btn btn-primary"></input>
        <input type="button"  onclick="javascript:history.back(-1);" class="btn" value="返回">
    </div>
</form>
<style>
    *{
   padding:0px;
   margin: 0px;
    }
    .need{
        color: red;
        font-size: 20px;
        margin: 5px;
    }
</style>
</style>
<script>
$(document).ready(function(){
    editor();
});
/*function save(){
    var formdeal= document.getElementById("goodsAddForm");
    formdeal.action="/Well/WeiPHP/admin.php/Goods/Index/save";
    formdeal.target="_self";
    formdeal.submit();
}*/
</script>