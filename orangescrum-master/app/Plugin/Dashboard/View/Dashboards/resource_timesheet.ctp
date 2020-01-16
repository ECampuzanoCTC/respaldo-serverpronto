<div id="bck_rcrstsht" class="fl hide_buttn"><button type="button" value="Back" name="Back" class="btn btn_blue" onclick="hide_flscrn_div('resource_timesheet')">Back</button></div>
<div id="exprt_rcrtshet" class="fr hide_buttn"><button type="button" value="Export" name="Export" class="btn btn_blue" onclick="export_rcrtsht()">Export Task (.csv)</button></div>
<div class="cb"></div>
<div class="table-responsive prjct-rag-tbl">
    <table class="table table-hover">
        <thead>
            <tr>
                <th><?php echo __("Resource"); ?></th>
                <th><?php echo __("Date"); ?></th>
                <th><?php echo __("Project Name"); ?> </th> 
                <th style="text-align: center"><?php echo __("Total Task"); ?></th>
                <th style="text-align: center"><?php echo __("In Progress Task"); ?></th>
                <th style="text-align: center"><?php echo __("Completed Task"); ?></th>
                <th><?php echo __("Hour(s) Spent"); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php $usrnm = '';
            //echo "<pre>";print_r($data);exit;
                $limit = 5 ; 
                if(!empty($data)){
                foreach($data as $k => $val){ 
                    if($val['0']['hours'] != '0.0' && $val['0']['hours'] != '---'){ 
                       $cmptd_tsk = !empty($val['0']['completed_task']) ? $val['0']['completed_task'] : '0' ;
                       $usr_tsks = (isset($val['0']['completed_task']) && !empty($val['0']['user_total_tasks'])) ? $val['0']['user_total_tasks'] : '0' ;?>
            <tr <?php if($k >= $limit){?> style="display:none;" <?php } ?> class="rsrctmesht_tr">
                <td><?php echo $val[0]['resource'];//$usrnm != $val[0]['resource'] ? $val[0]['resource'] : ''; ?></td>
                <td  title="<?php echo $this->Format->chngdate($val['0']['date']); ?>"><?php echo $this->Format->chngdate($val['0']['date']); ?></td>
                <td><?php echo $val['Project']['name'] ; ?></td>
                <td style="text-align: center"><?php echo $usr_tsks; ?></td>
                <td style="text-align: center"><?php echo $val['0']['total_tasks']; ?></td>
                <td style="text-align: center"><?php echo $cmptd_tsk; ?></td>
                <td><?php echo $val['0']['hours']; ?></td>
            </tr>
            <?php $usrnm = $val[0]['resource'];
                } 
                }
                }else{ ?>
            <tr>
                <td colspan='7'><div class='mytask_txt'><?php echo __("Oops!No activity in last week! What's cooking"); ?> ?</div></td> 
            </tr>
                <?php } ?>
        </tbody>
    </table>
</div>

<script>
    function export_rcrtsht(){
        $.post(HTTP_ROOT+"Dashboard/dashboards/resource_timesheet?type=export",{},function(res){
           location.href = HTTP_ROOT+"Dashboard/dashboards/resource_timesheet?type=export" ;
        });
    }
    $(document).ready(function(){
      $('[rel=tooltip]').tipsy({gravity: 's', fade: true});
    });
</script>