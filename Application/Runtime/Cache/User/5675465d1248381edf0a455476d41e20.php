<?php if (!defined('THINK_PATH')) exit();?><div class="row main">
    <div class="col-xs-12">
        <form>
            <div class="form-group">
              <input id="phone" name="phone_number" value="<?php echo ($number); ?>" class="form-control" placeholder="请填写联系方式">
            </div>
            <div class="form-group">
              <input id="account" name="account" value="<?php echo ($account); ?>" class="form-control" id="exampleInputPassword1" placeholder="请填写收款账号">
              <span>我们会定期将您的佣金打入您所填写的收款账号，如为空则默认打入为微信钱包</span>
            </div>
        </form>
    </div>
</div>
<div class="row">
    <div class="col-xs-12" >
        <button class="btn btn-large btn-block btn-warning" type ="button" onclick="checkOrder()" id="btn">保存修改</button>
    </div>
</div>

<?php echo ($css); ?>
<?php echo ($js); ?>