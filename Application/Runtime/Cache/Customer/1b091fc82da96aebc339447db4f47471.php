<?php if (!defined('THINK_PATH')) exit(); echo ($css); ?>
<h4><a href="<?php echo ($listCustomerUrl); ?>">重新抓取客户信息</a></h4>
<table class="table table-bordered table-striped table-hover">
  
        <thead>
            <tr>
                <th class="width">昵称</th>
                <th class="width">直销业绩</th>
                <th class="width">下线业绩</th>
                <th class="width">业绩汇总</th>
                <th class="width">关注时间</th>
                <th class="width">上线用户姓名</th>
                <th class="width">用户手机号</th>
                <th class="width">操作</th>
            </tr>
        </thead>
        <tbody>
                 <?php $i=1; ?>
                <?php if(is_array($customer)): foreach($customer as $key=>$value): ?><tr>
                <td><a onclick="#" href="<?php echo ($value["actionUrl"]["look"]); ?>"><?php echo ($value["nickname"]); ?></a></td>
                <td><?php echo format_money($achievement[$key]['totalGoodsFee']); ?></td>
                <td><?php echo format_money($achievement[$key]['_line']['totalGoodsFee']); ?></td>
                <td><?php echo format_money($achievement[$key]['_line']['totalGoodsFee']+$achievement[$key]['totalGoodsFee']); ?></td>
                <td><?php echo (date("y/m/d H:s",$value["subscribe_time"])); ?></td>
                <td><a onclick="#" href="<?php echo ($value["actionUrl"]["parent_look"]); ?>"><?php echo ($value["_parentname"]["nickname"]); ?></a></td>
                <td><?php echo ($value["phone_number"]); ?></td>
                <td>
                <a onclick="#" href="<?php echo ($value["actionUrl"]["edit"]); ?>">
                    <button class="btn btn-mini btn-primary" type="button">
                        修改
                    </button>
                </a>
                <a onclick="#" href="<?php echo ($value["actionUrl"]["change"]); ?>">
                    <button class="btn btn-mini btn-warning" type="button">注册</button>
                </a>
                    <a href="<?php echo ($value["actionUrl"]["freezen"]); ?>">
                        <button class="btn btn-mini <?php if(($value["freezen_state"]) == "0"): else: ?>btn-warning<?php endif; ?>" type="button"><?php if(($value["freezen_state"]) == "0"): ?>冻结<?php else: ?>解冻<?php endif; ?></button>
                    </a>
                </td>
                </tr><?php endforeach; endif; ?>
        </tbody>
</table>
<div class="pagination" align="center"><?php echo ($page); ?></div>