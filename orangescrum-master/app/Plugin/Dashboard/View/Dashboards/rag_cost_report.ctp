<div id="bck_cost_rprt" class="fl hide_buttn"><button type="button" value="Back" name="Back" class="btn btn_blue" onclick="hide_flscrn_div('cost_rprt')"><?php echo __("Back"); ?></button></div>
<div id="exprt_cost_rprt" class="fr hide_buttn"><button type="button" value="Export" name="Export" class="btn btn_blue" onclick="export_costrprt()"><?php echo __("Export Task (.csv)"); ?></button></div>
<div class="cb"></div>
<div class="table-responsive prjct-rag-tbl">
    <table class="table table-hover">
        <thead>
            <tr>
                <th><?php echo __("Project Name"); ?></th>
                <th><?php echo __("Client Name"); ?></th>
                <th style="text-align: center"><?php echo __("Cost approved"); ?></th>
                <th style="text-align: center"><?php echo __("Cost to client"); ?></th>
                <th style="text-align: center"><?php echo __("Cost to company"); ?></th> 
             <?php /*   <th style="text-align: center">Profit</th> */ ?>
              
            </tr>
        </thead>
        <tbody>
            <?php 
                $limit = 5 ; $cnt=0;
                if(!empty($projects)){ #echo "<pre>";print_r($projects);exit;
                foreach($projects as $k => $val){                   
                        $cost_approved = empty($val['cost_approved']) ? $val['budget'] : $val['cost_approved'] ; 
                        $rate= isset($val['rates']) && !empty($val['rates']) ? $val['rates']."&nbsp;<small>".$val['currency']."</small>" : 0 ;
                        $actual_cost= isset($val['actual_cost']) && !empty($val['actual_cost']) ? $val['actual_cost']."&nbsp;<small>".$val['currency']."</small>" : 0 ;
                     //   $profit = (int)($val['rates'] - $val['actual_cost']);
                        $curncy = $cost_approved != "0.00" ? "<small>".$val['currency']."</small>" : "" ;
                      //  $proft_crncy = $profit != "0" ? "<small>".$val['currency']."</small>" : "" ;
                        $clnt_cmnpy_name = !empty($val['client_company_name'])? $val['client_company_name'] : 'None' ;
                     ?>
            <tr <?php if($cnt >= $limit){?> style="display:none;" <?php } ?> class="cst_rprt_tr">
                <td><?php echo $val['name'] ;?></td>
                <td><?php echo $clnt_cmnpy_name ;?></td>
                <td style="text-align: center"><?php echo $cost_approved."&nbsp".$curncy ; ?></td>
                <td style="text-align: center"><?php  echo $rate ;?></td>
                <td style="text-align: center"><?php echo $actual_cost ?></td>
           <?php /*     <td style="text-align: center"><?php echo $profit."&nbsp".$proft_crncy ;?></td> */?>
                
            </tr>
            <?php $cnt++ ;
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
    function export_costrprt(){
        $.post(HTTP_ROOT +"Dashboard/dashboards/rag_cost_report?type=export",{}, function(res){
            
        location.href = HTTP_ROOT +"Dashboard/dashboards/rag_cost_report?type=export" ;
        
        });
    }
     $(document).ready(function(){
      $('[rel=tooltip]').tipsy({gravity: 's', fade: true});
    });
</script>