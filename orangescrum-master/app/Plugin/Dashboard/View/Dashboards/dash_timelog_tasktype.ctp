<div id="tsktyp_bck" class="fl hide_buttn"><button type="button" value="Back" name="Back" class="btn btn_blue" onclick="fulscreen_timelog('dash_tasktype')"><?php echo __("Back"); ?></button></div>
<div id="exprt_tsktyp" class="fr hide_buttn"><button type="button" value="Export" name="Export" class="btn btn_blue" onclick="export_tasktype()"><?php echo __("Export Task (.csv)"); ?></button></div>
<input type="hidden" id="prjct_id" name="prjct_id" value="<?php echo $project_id ; ?>">
<input type="hidden" id="user_id" name="user_id" value="<?php echo $user_id ; ?>">
<input type="hidden" id="time_flt" name="time_flt" value="<?php echo $time_flt ; ?>"> 
<div class="cb"></div>
<div id="dash_timelog_tasktype" class="tlog-chrt-prnt"></div>
<div id="tasktype_table" class="table-responsive">
    <table class="table table-hover tbody_scroll">
        <thead>
            <tr>
                <th><?php echo __("Project Name"); ?></th>
                <th><?php echo __("Resource Name"); ?></th>
                <th><?php echo __("Task Name"); ?></th>
                <th style="text-align:center"><?php echo __("Billable Hour"); ?></th>
                <th style="text-align:center"><?php echo __("Non Billable Hour"); ?></th>
                <th style="text-align:center"><?php echo __("Total Hour"); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php  
                foreach($type_timelog as $k => $val){ 
                    foreach($val['project_name'] as $kv => $vv){ 
                        $billbale_hr = isset($val['billable_hr'][$kv]) && !empty($val['billable_hr'][$kv]) ? $val['billable_hr'][$kv] : '0' ;
                        $unbillable_hr = isset($val['non_billable_hr'][$kv]) && !empty($val['non_billable_hr'][$kv]) ? $val['non_billable_hr'][$kv] : '0' ;
                         if($billbale_hr != '0' || $unbillable_hr != '0'){
                             $total_hr = round($billbale_hr + $unbillable_hr,2); 
                            $bil_hr = (int)($billbale_hr);
                            $bil_min = round(($billbale_hr - $bil_hr)*60);
                            $non_bil_hr = (int)($unbillable_hr);
                            $non_bil_min = round(($unbillable_hr - $non_bil_hr)*60);
                            $tot_hr = (int)($total_hr);
                            $tot_min = round(($total_hr - $tot_hr)*60) ;?>
                        <tr>
                        <td><?php echo $vv//$prjnm != $val['Project']['name'] ? $val['Project']['name'] : ''; ?></td>
                        <td><?php echo $val['user_name'][$kv]; ?></td>
                        <td><?php echo $val['type_name'][$kv]; ?></td>
                        <td style="text-align:center"><?php echo $bil_hr." hrs&nbsp".$bil_min." mins" ; ?></td>
                        <td style="text-align:center"><?php echo $non_bil_hr." hrs&nbsp".$non_bil_min." mins" ; ?></td>
                        <td style="text-align:center"><?php echo $tot_hr." hrs&nbsp".$tot_min." mins" ; ?></td>
                    </tr>
                 <?php  }
                 }
                } ?>
        </tbody>
    </table>
</div>
<script>
    function export_tasktype(){
        var prjct_id = $('#prjct_id').val();
        var user_id = $('#user_id').val();
        var time_flt =  $('#time_flt').val();
        window.open(HTTP_ROOT +"Dashboard/dashboards/dash_timelog_tasktype?type=export&project_id="+prjct_id+"&user_id="+user_id+"&time_flt="+time_flt);
    }
$(function () {
    
    var Type_name = <?php echo $type_names; ?>;
    var series =<?php echo $series; ?>;
    $('#dash_timelog_tasktype').highcharts({

        chart: {
            type: 'column'
        },

        title: {
            align: 'left',
            text: ' '
        },
        scrollbar:{
            enabled:true
        },
        exporting: {
            enabled: true,
            buttons: {
                contextButton: {
                    symbolStrokeWidth: 2,
                    symbolStroke: '#969696',
                    menuItems: [{
                        text: 'PNG',
                        onclick: function() {
                            this.exportChart();
                        },
                        separator: false
                    }, {
                        text: 'JPEG',
                        onclick: function() {
                            this.exportChart({
                                type: 'image/jpeg'
                            });
                        },
                        separator: false
                    }, {
                        text: 'PDF',
                        onclick: function() {
                            this.exportChart({
                                type: 'application/pdf'
                            });
                        },
                        separator: false
                    }, {
                        text: 'Print',
                        onclick: function() {
                            this.print();
                        },
                        separator: false
                    }]
                }
            }
        },
        xAxis: {         
            categories: Type_name,
            showFirstLabel:true,
            showLastLabel:true,
            title: {
                text: '<?php echo __("Task Type"); ?>'
            },
             min:0,
            max:Type_name.length >= 3 ? 3: '',
            labels:{
                    formatter:function(){
                        return(this.value.substring(0,10)+"...");
                        
            }
                }
        },

        yAxis: {
            allowDecimals: false,
            gridLineWidth: 0,
            minorGridLineWidth: 0,
            min: 0,
            title: {
                text: '<?php echo __("Hour(s) Spent"); ?>'
            },
            stackLabels: {
                enabled: true,
                style: {
                    fontWeight: 'bold',
                    color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                    
                },
                formatter:function(){
                    return Math.round(this.total * 100) / 100;
                }
      
            }
        },

        tooltip: {
        // pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b><br/>',
         /*   shared: true,
           formatter: function () {
               var hr =  this.y;
               console.log(this.y);
               var time = hr.split('.');
               var mins = parseInt((time[1]/100)*60);
               var times = time[0]+":"+ mins ; 
                return '<b>' + this.x + '</b><br/>' +
                    this.series.name + ': ' + this.y + '<br/>' +
                    'Total: ' + this.point.stackTotal;
            } */
        formatter: function () {          
            var s = '<b>' + this.x + '</b>';
               $.each(this.points, function () {
                    var hrs = parseInt(Number(this.y));
                    var min = Math.round((Number(this.y)-hrs) * 60);
                    var ytimes = hrs +" hrs "+ min +" mins";
                    s += '<br/>' + this.series.name + ': ' +
                        ytimes ;
                });

                return s;
            },
            shared: true
        },
		credits: {
			enabled: false
		},
        plotOptions: {
            series: {
                stacking: 'normal',
                dataLabels: {
                    enabled: false,
                    color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
                    
                }
            }
        },

        series: eval(series)
    });
});
</script>