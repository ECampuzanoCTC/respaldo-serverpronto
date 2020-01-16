<div id="bck_prjtsht" class="fl hide_buttn"><button type="button" value="Back" name="Back" class="btn btn_blue" onclick="hide_flscrn_div('resource_cost')"><?php echo __("Back"); ?></button></div>
<div id="exprt_prjtshet" class="fr hide_buttn"><button type="button" value="Export" name="Export" class="btn btn_blue" onclick="export_rsrccost()"><?php echo __("Export To CSV"); ?></button></div>
<div class="cb"></div>
<div class="table-responsive prjct-rag-tbl">
    <table class="table table-hover">
        <thead>
            <tr>
               
                <th style="text-align: center"><?php echo __("Project Name"); ?></th>
                <th style="text-align: center"><?php echo __("Client Name"); ?></th>
                <th style="text-align: center"><?php echo __("Resource Name"); ?></th>
                <th style="text-align: center"><?php echo __("Hours Spent"); ?></th>
                <th style="text-align: center"><?php echo __("Actual Cost"); ?></th>
                <th style="text-align: center"><?php echo __("Total Actual Cost"); ?></th>
                <th style="text-align: center"><?php echo __("Billing Rate"); ?></th>
                <th style="text-align: center"><?php echo __("Total Billing Amount"); ?></th>
                
            </tr>
        </thead>
        <tbody>
            <?php $prjnm = '';
                $limit = 5 ; 
                if(!empty($resource_cost_details)){
                foreach($resource_cost_details as $k => $val){ 
                    
                    $actual_cost = (isset($val['RoleRate']['actual_rate']) && !empty($val['RoleRate']['actual_rate'])) ? $val['RoleRate']['actual_rate']."&nbsp;<small>".$val['Project']['currency']."</small>" : 0 ;
                    $billing_cost = (isset($val['RoleRate']['billable_rate']) && !empty($val['RoleRate']['billable_rate'])) ? $val['RoleRate']['billable_rate']."&nbsp;<small>".$val['Project']['currency']."</small>" : 0 ;
                    $total_actual_rate = round(($val['0']['hours']/3600) * $val['RoleRate']['actual_rate']) != 0 ? round(($val['0']['hours']/3600) * $val['RoleRate']['actual_rate'])."&nbsp;<small>".$val['Project']['currency']."</small>" : 0;
                    $total_billing_rate = round(($val['0']['hours']/3600) * $val['RoleRate']['billable_rate']) != 0 ? round(($val['0']['hours']/3600) * $val['RoleRate']['billable_rate'])."&nbsp;<small>".$val['Project']['currency']."</small>": 0 ; 
                    $clnt_cmnpy_name = !empty($val['InvoiceCustomer']['project_company_name'])? $val['InvoiceCustomer']['project_company_name'] : 'None' ; ?>
            <tr <?php if($k >= $limit){?> style="display:none;" <?php } ?> class="resource_tr">
                <td style="text-align: center"><?php echo ucfirst($val['Project']['name']) ;?></td>
                <td style="text-align: center"><?php echo ucfirst($clnt_cmnpy_name) ;?></td>
                <td style="text-align: center"><?php echo ucfirst($val[0]['user_name']) ;//$prjnm != $val['Project']['name'] ? $val['Project']['name'] : ''; ?></td>
                <td style="text-align: center"><?php echo $this->Format->format_time_hr_min($val['0']['hours'])?></td>
                <td style="text-align: center"><?php echo $actual_cost; ?></td>
                <td style="text-align: center"><?php echo $total_actual_rate; ?></td>
                <td style="text-align: center"><?php echo $billing_cost ; ?></td>
                <td style="text-align: center"><?php echo $total_billing_rate; ?></td>
                
            </tr>
            <?php }
            
                } else{ ?>
            <tr>
                <td colspan='7'><div class='mytask_txt'><?php echo __("Oops!No Resource has logged time !What's happening");?> ?</div></td> 
            </tr>
                <?php } ?>
        </tbody>
    </table>
</div>
<script>
    function export_rsrccost(){
        var rsrc_prjflt = $("#sel_rsrc_prj_typ").val();
        var rsrc_tmeflt = $("#sel_rsrc_time_typ").val();
        window.open(HTTP_ROOT +"Dashboard/dashboards/resource_cost_report?type=export&project_id="+rsrc_prjflt+"&time_flt="+rsrc_tmeflt);
    }
     $(document).ready(function(){
      $('[rel=tooltip]').tipsy({gravity: 's', fade: true});
    });
</script>