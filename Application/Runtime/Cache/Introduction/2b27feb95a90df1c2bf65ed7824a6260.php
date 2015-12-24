<?php if (!defined('THINK_PATH')) exit();?><form id="goodsEditForm" class="form-horizontal" name="goodsEditForm" method="post"  action="<?php echo ($urlUpdate); ?>"/>
<fieldset>  
   <input name ="id" type="hidden" value="1" />
   <div class="control-group">
                                <label class="control-label" for="firstWeight">首重</label>
                                <div class="controls">
                                <input type="text" name='firstWeight' id="firstWeight" value="<?php echo ($firstHeavy["value"]); ?>" >
                                </div>
                            </div>
   <div class="control-group">
                                <label class="control-label" for="firstCost">首重费用</label>
                                <div class="controls">
                                <input type="text" name='firstCost' id="firstCost" value="<?php echo ($firstCost["value"]); ?>" >
                                </div>
                            </div>
   <div class="control-group">
                                <label class="control-label" for="continueWeight">续重</label>
                                <div class="controls">
                                <input type="text" name='continueWeight' id="continueWeight" value="<?php echo ($continueHeavy["value"]); ?>" >
                                </div>
                            </div>
   <div class="control-group">
                                <label class="control-label" for="continueCost">续重费用</label>
                                <div class="controls">
                               <input type="text" name='continueCost' id="name" value="<?php echo ($continueCost["value"]); ?>" >
                                </div>
                            </div>
   <div class="control-group">
                                <label class="control-label" for="packageHeavy">包装重</label>
                                <div class="controls">
                                <input type="text" name='packageHeavy' id="packageHeavy" value="<?php echo ($packageHeavy["value"]); ?>" >
                                </div>
                            </div>
   <div class="control-group">
                                <label class="control-label" for="freightExplain">运费说明</label>
                                <div class="controls">
                                    <textarea type="text" name='freightExplain' id="freightExplain">
                                        <?php echo ($freightExplain); ?>
                                    </textarea>
                                </div>
                            </div>
                            <div class="form-actions">
				<button type="submit" class="btn btn-primary">保存</button> <button class="btn">取消</button>
			    </div>
</fieldset>
</form>