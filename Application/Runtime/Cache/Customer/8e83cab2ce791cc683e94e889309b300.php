<?php if (!defined('THINK_PATH')) exit();?><form id="goodsEditForm" class="form-horizontal" name="goodsEditForm" method="post" enctype="multipart/form-data"/>
<fieldset>  
   <legend> <a href="<?php echo ($urlIndex); ?>"><h4>返回</h4></a></legend>
    <div class="control-group">
                                <label class="control-label" for="id">用户编号</label>
                                <div class="controls">
                                <input type="text" name='id' id="id" value="<?php echo ($customer["id"]); ?>" >
                                </div>
                            </div>
   <div class="control-group">
                                <label class="control-label" for="nickname">客户昵称</label>
                                <div class="controls">
                                <input type="text" name='nickname' id="nickname" value="<?php echo ($customer["nickname"]); ?>" >
                                </div>
                            </div>
   <div class="control-group">
                                <label class="control-label" for="weixinId">微信opend</label>
                                <div class="controls">
                                <?php echo ($customer["openid"]); ?>
                                </div>
                            </div>
   <div class="control-group">
                                <label class="control-label" for="aipayid">支付宝账号</label>
                                <div class="controls">
                                <input type="text" name='aipayid' id="aipayid" value="<?php echo ($customer["aipayid"]); ?>" >
                                </div>
                            </div>
   <div class="control-group">
                                <label class="control-label" for="phone_numeber">电话号码</label>
                                <div class="controls">
                                <input type="text" name='phone_numeber' id="aipayid" value="<?php echo ($customer["phone_number"]); ?>" >
                                </div>
                            </div>
   <div class="control-group">
                                <label class="control-label" for="sex">性别</label>
                                <div class="controls">
                                <input type="text" name='sex' id="aipayid" value="<?php echo ($customer["sex"]); ?>" >
                                </div>
                            </div>
   <div class="control-group">
                                <label class="control-label" for="city">所在城市</label>
                                <div class="controls">
                                <input type="text" name='city' id="aipayid" value="<?php echo ($customer["city"]); ?>" >
                                </div>
                            </div>
   <div class="control-group">
                                <label class="control-label" for="province">所在省份</label>
                                <div class="controls">
                                <input type="text" name='province' id="aipayid" value="<?php echo ($customer["province"]); ?>" >
                                </div>
                            </div>
    <div class="control-group">
                                <label class="control-label" for="country">所在国家</label>
                                <div class="controls">
                                <input type="text" name='country' id="country" value="<?php echo ($customer["country"]); ?>" >
                                </div>
                            </div>
   <div class="control-group">
                                <label class="control-label" for="parent_name">上级用户</label>
                                <div class="controls">
                                <input type="text" name='parent_name' id="parent_name" value="<?php echo ($customer["parent_name"]); ?>" >
                                </div>
                            </div>
   <div class="control-group">
                                <label class="control-label" for="subscirbe">注册时间</label>
                                <div class="controls">
                                <input type="text" name='subscirbe' id="subscirbe" value="<?php echo (date('20y年m月d日',$customer["subscribe_time"])); ?>" >
                                </div>
                            </div>
   <div class="control-group">
                                <label class="control-label" for="customer_state">用户状态</label>
                                <div class="controls">
                                <input type="text" name='customer_state' id="customer_state" value="<?php echo ($customer["subscribe_state"]); ?>" >
                                </div>
                            </div>
   <div class="control-group">
                                <label class="control-label" for="headimgurl">头像</label>
                                <div class="controls">
                                <img class="swiper-image" src="<?php echo ($customer["headimgurl"]); ?>" />
                                </div>
                            </div>
</fieldset>
</form>