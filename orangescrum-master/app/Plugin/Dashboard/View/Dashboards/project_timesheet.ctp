<div id="bck_prjtsht" class="fl hide_buttn"><button type="button" value="Back" name="Back" class="btn btn_blue" onclick="hide_flscrn_div('prj_timesheet')"><?php echo __("Back"); ?></button></div>
<div id="exprt_prjtshet" class="fr hide_buttn"><button type="button" value="Export" name="Export" class="btn btn_blue" onclick="export_prjtsht()"><?php echo __("Export Task (.csv)"); ?></button></div>
<div class="cb"></div>
<div class="table-responsive prjct-rag-tbl">
    <table class="table table-hover">
        <thead>
            <tr>
                <th><?php echo __("Project Name"); ?></th>
                <th><?php echo __("Date"); ?></th>
                <th style="text-align: center"><?php echo __("Total Task"); ?></th>
                <th style="text-align: center"><?php echo __("In Progress Task"); ?></th>
                <th style="text-align: center"><?php echo __("Completed Task"); ?></th>
                <th>Hour(s) Spent</th>
            </tr>
        </thead>
        <tbody>
            <?php $prjnm = '';
                $limit = 5 ; 
                if(!empty($data)){
                foreach($data as $k => $val){ 
                    if($val['0']['hours'] != '0.0' && $val['0']['hours'] != '---'){ 
                        $cmptd_tsk = !empty($val['0']['completed_tasks']) ? $val['0']['completed_tasks'] : '0'?>
            <tr <?php if($k >= $limit){?> style="display:none;" <?php } ?> class="progtmesht_tr">
                <td><?php echo $val['Project']['name'] ;//$prjnm != $val['Project']['name'] ? $val['Project']['name'] : ''; ?></td>
                <td><?php echo $this->Format->chngdate($val['0']['date']); ?></td>
                <td style="text-align: center"><?php echo $val['0']['prjct_total_task']; ?></td>
                <td style="text-align: center"><?php echo $val['0']['total_tasks']; ?></td>
                <td style="text-align: center"><?php echo $cmptd_tsk; ?></td>
                <td><?php echo $val['0']['hours']; ?></td>
            </tr>
            <?php $prjnm = $val['Project']['name'];
                }
                }
                }else{ ?>
            <tr>
                <td colspan='7'><div class='mytask_txt'><?php echo __("Oops!No activity in last week! What's cooking"); ?>?</div></td> 
            </tr>
                <?php } ?>
        </tbody>
    </table>
</div>
<script>
    function export_prjtsht(){
        $.post(HTTP_ROOT +"Dashboard/dashboards/project_timesheet?type=export",{}, function(res){
            
        location.href = HTTP_ROOT +"Dashboard/dashboards/project_timesheet?type=export" ;
        
        });
    }
     $(document).ready(function(){
      $('[rel=tooltip]').tipsy({gravity: 's', fade: true});
    });
</script>