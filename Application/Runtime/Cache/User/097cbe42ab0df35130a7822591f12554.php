<?php if (!defined('THINK_PATH')) exit();?><div class="panel-group" id="panel-165125">
    <?php if(is_array($data)): foreach($data as $key=>$value): ?><div class="panel panel-default">
        <div class="panel-heading">
                 <a class="panel-title collapsed" data-toggle="collapse" data-parent="#panel-165125" href="#panel-element-<?php echo ($i); ?>">业绩周期:<?php echo ($value["describe"]); ?></a>
        </div>
        <div id="panel-element-<?php echo ($i++); ?>" class="panel-collapse collapse">
                <div class="panel-body">
                <table class="table">
                  <thead>
                    <tr>
                          <th>
                                  直销业绩
                          </th>
                          <th>
                                  直销佣金
                          </th>
                          <th>
                                  下线业绩
                          </th>
                          <th>
                                  下线佣金
                          </th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                        <td>
                                <?php echo (format_money($value["_detail"]["direct_total_fee"])); ?>
                        </td>
                        <td>
                               <?php echo (format_money($value["_detail"]["direct_result"])); ?>
                        </td>
                        <td>
                                <?php echo (format_money($value["_detail"]["line_total_fee"])); ?>
                        </td>
                        <td>
                                <?php echo (format_money($value["_detail"]["line_result"])); ?>
                        </td>
                    </tr>
                  </tbody>
                </table>  
                </div>
        </div>
    </div><?php endforeach; endif; ?>
</div>